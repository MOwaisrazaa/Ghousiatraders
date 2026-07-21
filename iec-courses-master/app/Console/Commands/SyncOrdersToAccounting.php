<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\AccountTransaction;
use App\Models\AccountBalance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncOrdersToAccounting extends Command
{
    protected $signature = 'accounting:sync-orders';
    protected $description = 'Sync existing orders to accounting transactions';

    public function handle()
    {
        $this->info('Starting order sync to accounting...');

        DB::transaction(function () {
            // Get all paid orders that don't have accounting transactions yet
            $orders = Order::where('status', 'paid')->get();

            $count = 0;
            foreach ($orders as $order) {
                // Create transaction record
                AccountTransaction::create([
                    'order_id' => $order->id,
                    'transaction_type' => 'payment_received',
                    'payment_method' => $order->payment_method ?? 'unknown',
                    'amount' => $order->final_total,
                    'status' => 'completed',
                    'description' => 'Payment for order #' . $order->id,
                ]);

                // Update main account balance
                $balance = AccountBalance::firstOrCreate(
                    ['account_name' => 'main'],
                    ['balance' => 0, 'total_received' => 0, 'total_used' => 0, 'total_transferred' => 0]
                );
                $balance->increment('balance', $order->final_total);
                $balance->increment('total_received', $order->final_total);

                $count++;
            }

            $this->info("Synced $count orders to accounting.");
        });
    }
}
