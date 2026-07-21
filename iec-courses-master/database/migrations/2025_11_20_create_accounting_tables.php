<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Account Transactions - Track all money received
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('transaction_type'); // 'payment_received', 'transfer_out', 'withdrawal', 'deposit'
            $table->string('payment_method'); // 'easypaisa', 'banktransfer', 'cash', 'free'
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('completed'); // 'pending', 'completed', 'failed'
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->index('payment_method');
            $table->index('status');
            $table->index('created_at');
        });

        // Account Transfers - Track money transfers between departments/accounts
        Schema::create('account_transfers', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 12, 2);
            $table->string('from_account'); // 'main', 'marketing', 'operations', 'development', etc.
            $table->string('to_account');
            $table->string('status')->default('pending'); // 'pending', 'approved', 'completed', 'rejected'
            $table->text('reason');
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->text('approval_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->index('status');
            $table->index('created_at');
        });

        // Journal Entries - Track accounting entries for cash/bank transactions
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_type'); // 'cash_deposit', 'bank_withdrawal', 'bank_deposit', 'cash_withdrawal'
            $table->string('account_from'); // 'cash', 'bank_account_1', 'bank_account_2', etc.
            $table->string('account_to');
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending'); // 'pending', 'verified', 'completed'
            $table->text('description');
            $table->string('reference_number')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->index('status');
            $table->index('created_at');
        });

        // Account Balance Sheet - Track balance by account
        Schema::create('account_balances', function (Blueprint $table) {
            $table->id();
            $table->string('account_name')->unique(); // 'main', 'marketing', 'operations', etc.
            $table->decimal('balance', 12, 2)->default(0);
            $table->decimal('total_received', 12, 2)->default(0);
            $table->decimal('total_used', 12, 2)->default(0);
            $table->decimal('total_transferred', 12, 2)->default(0);
            $table->timestamps();
        });

        // Account Usage - Track where money is used
        Schema::create('account_usages', function (Blueprint $table) {
            $table->id();
            $table->string('account_name'); // which account the money came from
            $table->string('usage_category'); // 'marketing', 'operations', 'development', 'infrastructure', 'salaries', etc.
            $table->decimal('amount', 12, 2);
            $table->text('description');
            $table->string('status')->default('pending'); // 'pending', 'approved', 'completed'
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('account_usages');
        Schema::dropIfExists('account_balances');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('account_transfers');
        Schema::dropIfExists('account_transactions');
    }
};
