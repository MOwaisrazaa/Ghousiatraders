<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Support Departments
        if (!Schema::hasTable('support_departments')) {
            Schema::create('support_departments', function (Blueprint $table) {
                $table->id();
                $table->string('name', 50);
                $table->string('code', 30)->unique();
                $table->string('color', 30)->default('blue');
                $table->string('icon', 50)->default('help-circle');
                $table->boolean('is_active')->default(true);
                $table->string('default_priority', 20)->default('medium');
                $table->timestamps();
            });
        }

        // 2. Support Tickets
        if (!Schema::hasTable('support_tickets')) {
            Schema::create('support_tickets', function (Blueprint $table) {
                $table->id();
                $table->string('ticket_number', 30)->unique(); // e.g. TKT-10086
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Customer
                $table->string('customer_name', 100);
                $table->string('customer_email', 100);
                $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
                $table->foreignId('department_id')->nullable()->constrained('support_departments')->onDelete('set null');
                $table->string('subject');
                $table->string('priority', 20)->default('medium'); // low, medium, high, urgent
                $table->string('status', 20)->default('open'); // open, pending, resolved, closed
                $table->foreignId('assigned_agent_id')->nullable()->constrained('users')->onDelete('set null');
                $table->tinyInteger('satisfaction_rating')->nullable(); // 1-5
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
            });
        }

        // 3. Ticket Messages (Thread & Internal Notes)
        if (!Schema::hasTable('ticket_messages')) {
            Schema::create('ticket_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ticket_id')->constrained('support_tickets')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('sender_name', 100)->nullable();
                $table->string('sender_email', 100)->nullable();
                $table->boolean('is_admin_reply')->default(false);
                $table->boolean('is_internal_note')->default(false);
                $table->text('message');
                $table->json('attachments')->nullable();
                $table->timestamps();
            });
        }

        // 4. Canned Responses
        if (!Schema::hasTable('canned_responses')) {
            Schema::create('canned_responses', function (Blueprint $table) {
                $table->id();
                $table->string('title', 100);
                $table->string('shortcut', 50)->nullable();
                $table->foreignId('department_id')->nullable()->constrained('support_departments')->onDelete('cascade');
                $table->text('content');
                $table->boolean('is_active')->default(true);
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                $table->integer('usage_count')->default(0);
                $table->timestamps();
            });
        }

        // 5. Knowledge Base Articles
        if (!Schema::hasTable('knowledge_base_articles')) {
            Schema::create('knowledge_base_articles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('category', 50)->default('General');
                $table->text('content');
                $table->boolean('is_published')->default(true);
                $table->integer('helpful_count')->default(0);
                $table->integer('unhelpful_count')->default(0);
                $table->timestamps();
            });
        }

        // Seed initial Departments
        $depts = [
            ['name' => 'Orders', 'code' => 'orders', 'color' => 'blue', 'icon' => 'shopping-cart', 'default_priority' => 'high'],
            ['name' => 'Returns', 'code' => 'returns', 'color' => 'purple', 'icon' => 'rotate-ccw', 'default_priority' => 'medium'],
            ['name' => 'Shipping', 'code' => 'shipping', 'color' => 'light-blue', 'icon' => 'truck', 'default_priority' => 'medium'],
            ['name' => 'Payments', 'code' => 'payments', 'color' => 'green', 'icon' => 'credit-card', 'default_priority' => 'high'],
            ['name' => 'Products', 'code' => 'products', 'color' => 'beige', 'icon' => 'package', 'default_priority' => 'low'],
            ['name' => 'Support', 'code' => 'support', 'color' => 'pink', 'icon' => 'help-circle', 'default_priority' => 'medium'],
            ['name' => 'Account', 'code' => 'account', 'color' => 'grey', 'icon' => 'user', 'default_priority' => 'low'],
        ];

        foreach ($depts as $d) {
            DB::table('support_departments')->updateOrInsert(
                ['code' => $d['code']],
                array_merge($d, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // Seed initial Canned Responses
        $canned = [
            ['title' => 'Order Status Update', 'shortcut' => '!order-status', 'content' => 'Dear Customer, your order has been packed and is currently in transit with our logistics partner. Track your parcel here.'],
            ['title' => 'Return Policy Info', 'shortcut' => '!return-info', 'content' => 'Our return policy allows items to be returned within 7 days of delivery provided they are unused and in original packaging.'],
            ['title' => 'Payment Verification', 'shortcut' => '!payment-verif', 'content' => 'Thank you for your payment receipt. Our accounts team is verifying the funds and will update your order status within 2 hours.'],
        ];

        foreach ($canned as $c) {
            DB::table('canned_responses')->updateOrInsert(
                ['title' => $c['title']],
                array_merge($c, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // Seed initial Knowledge Base Articles
        $kb = [
            ['title' => 'How to Track Your Order', 'slug' => 'how-to-track-order', 'category' => 'Orders', 'content' => 'You can track your order using the order tracking link sent to your registered phone or email.'],
            ['title' => 'Return & Exchange Guidelines', 'slug' => 'return-exchange-guidelines', 'category' => 'Returns', 'content' => 'To initiate a return, contact our support team with your order number and item details.'],
        ];

        foreach ($kb as $k) {
            DB::table('knowledge_base_articles')->updateOrInsert(
                ['slug' => $k['slug']],
                array_merge($k, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        // Seed Realistic Support Tickets matching reference design
        $sampleTickets = [
            [
                'ticket_number' => '#TKT-10086',
                'customer_name' => 'Ali Raza',
                'customer_email' => 'ali.raza@email.com',
                'dept_code' => 'orders',
                'subject' => 'Order not received',
                'preview' => "I haven't received my order #ORD-1256...",
                'priority' => 'high',
                'status' => 'open',
                'updated_minutes_ago' => 10,
            ],
            [
                'ticket_number' => '#TKT-10085',
                'customer_name' => 'Sara Khan',
                'customer_email' => 'sara.khan@email.com',
                'dept_code' => 'returns',
                'subject' => 'Return & refund request',
                'preview' => 'I want to return the product and get a refund...',
                'priority' => 'medium',
                'status' => 'pending',
                'updated_minutes_ago' => 45,
            ],
            [
                'ticket_number' => '#TKT-10084',
                'customer_name' => 'Usman Ahmed',
                'customer_email' => 'usman.ahmed@email.com',
                'dept_code' => 'shipping',
                'subject' => 'Damaged product received',
                'preview' => 'The product arrived damaged. Please assist...',
                'priority' => 'high',
                'status' => 'open',
                'updated_minutes_ago' => 60,
            ],
            [
                'ticket_number' => '#TKT-10083',
                'customer_name' => 'Hina Fatima',
                'customer_email' => 'hina.fatima@email.com',
                'dept_code' => 'payments',
                'subject' => 'Payment failure issue',
                'preview' => 'Payment was deducted but order failed.',
                'priority' => 'high',
                'status' => 'pending',
                'updated_minutes_ago' => 120,
            ],
            [
                'ticket_number' => '#TKT-10082',
                'customer_name' => 'Ibrahim Malik',
                'customer_email' => 'ibrahim.malik@email.com',
                'dept_code' => 'products',
                'subject' => 'Product not as described',
                'preview' => 'The product I received is different from...',
                'priority' => 'medium',
                'status' => 'open',
                'updated_minutes_ago' => 180,
            ],
            [
                'ticket_number' => '#TKT-10081',
                'customer_name' => 'Ayesha Noor',
                'customer_email' => 'ayesha.noor@email.com',
                'dept_code' => 'support',
                'subject' => 'Need help with coupon',
                'preview' => 'My coupon code is not working.',
                'priority' => 'low',
                'status' => 'resolved',
                'updated_minutes_ago' => 1440,
            ],
            [
                'ticket_number' => '#TKT-10080',
                'customer_name' => 'Faisal Qureshi',
                'customer_email' => 'faisal.qureshi@email.com',
                'dept_code' => 'orders',
                'subject' => 'Where is my order?',
                'preview' => 'Can you please provide tracking details?',
                'priority' => 'medium',
                'status' => 'resolved',
                'updated_minutes_ago' => 1440,
            ],
            [
                'ticket_number' => '#TKT-10079',
                'customer_name' => 'Zainab Ali',
                'customer_email' => 'zainab.ali@email.com',
                'dept_code' => 'account',
                'subject' => 'Account login issue',
                'preview' => 'I am unable to login to my account.',
                'priority' => 'low',
                'status' => 'closed',
                'updated_minutes_ago' => 2880,
            ],
        ];

        foreach ($sampleTickets as $st) {
            $deptId = DB::table('support_departments')->where('code', $st['dept_code'])->value('id');
            $updatedAt = now()->subMinutes($st['updated_minutes_ago']);
            
            $ticketId = DB::table('support_tickets')->insertGetId([
                'ticket_number' => $st['ticket_number'],
                'customer_name' => $st['customer_name'],
                'customer_email' => $st['customer_email'],
                'department_id' => $deptId,
                'subject' => $st['subject'],
                'priority' => $st['priority'],
                'status' => $st['status'],
                'satisfaction_rating' => ($st['status'] === 'resolved' || $st['status'] === 'closed') ? 5 : null,
                'created_at' => $updatedAt,
                'updated_at' => $updatedAt,
            ]);

            // Add initial message
            DB::table('ticket_messages')->insert([
                'ticket_id' => $ticketId,
                'sender_name' => $st['customer_name'],
                'sender_email' => $st['customer_email'],
                'is_admin_reply' => false,
                'is_internal_note' => false,
                'message' => $st['preview'],
                'created_at' => $updatedAt,
                'updated_at' => $updatedAt,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_base_articles');
        Schema::dropIfExists('canned_responses');
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('support_departments');
    }
};
