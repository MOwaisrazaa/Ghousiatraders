<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;
use App\Models\UserCourse;

class DebugUserOrders extends Command
{
    protected $signature = 'debug:user-orders {user_id}';
    protected $description = 'Debug orders for a specific user';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return;
        }

        $this->info("=== User Information ===");
        $this->line("ID: {$user->id}");
        $this->line("Name: {$user->name}");
        $this->line("Email: {$user->email}");

        $orders = Order::where('user_id', $userId)->get();
        $this->info("\n=== Orders ===");
        $this->line("Total Orders: {$orders->count()}");

        foreach ($orders as $order) {
            $this->line("\nOrder ID: {$order->id}");
            $this->line("Status: {$order->status}");
            $this->line("Total: {$order->total}");
            $this->line("Discount: {$order->discount}");
            $this->line("Final Total: {$order->final_total}");
            $this->line("Payment Method: {$order->payment_method}");
            $this->line("Created: {$order->created_at}");
            
            $cartItems = json_decode($order->cart_items, true);
            $this->line("Cart Items: " . json_encode($cartItems, JSON_PRETTY_PRINT));
        }

        $userCourses = UserCourse::where('user_id', $userId)->with('order')->get();
        $this->info("\n=== User Courses ===");
        $this->line("Total User Courses: {$userCourses->count()}");

        foreach ($userCourses as $uc) {
            $this->line("\nCourse ID: {$uc->course_id}");
            $this->line("Status: {$uc->status}");
            $this->line("Order ID: {$uc->order_id}");
            if ($uc->order) {
                $this->line("Order Status: {$uc->order->status}");
                $this->line("Order Final Total: {$uc->order->final_total}");
            }
        }
    }
}
