<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Cash Payment',
                'key' => 'cash',
                'description' => 'Pay with cash on delivery',
                'icon' => 'fas fa-money-bill-wave',
                'instructions' => 'You will need to pay cash at the following address: IEC Courses Office, Floor 3, Building 5, Main Street, Islamabad, Pakistan',
                'is_active' => true,
                'sort_order' => 1,
                'details' => [
                    'color' => 'text-success',
                ]
            ],
            [
                'name' => 'Jazz Cash',
                'key' => 'jazzcash',
                'description' => 'Pay with Jazz Cash mobile wallet',
                'icon' => 'fas fa-mobile-alt',
                'instructions' => 'Please send your payment to the following account: Jazz Cash Account: +92 333 1234567',
                'is_active' => true,
                'sort_order' => 2,
                'details' => [
                    'account' => '+92 333 1234567',
                    'color' => 'text-danger',
                ]
            ],
            [
                'name' => 'Easypaisa',
                'key' => 'easypaisa',
                'description' => 'Pay with Easypaisa mobile wallet',
                'icon' => 'fas fa-wallet',
                'instructions' => 'Please send your payment to the following account: Easypaisa Account: +92 345 1234567',
                'is_active' => true,
                'sort_order' => 3,
                'details' => [
                    'account' => '+92 345 1234567',
                    'color' => 'text-warning',
                ]
            ],
            [
                'name' => 'Bank Transfer',
                'key' => 'banktransfer',
                'description' => 'Pay via bank transfer',
                'icon' => 'fas fa-university',
                'instructions' => "Bank: HBL Pakistan\nAccount Title: IEC Courses\nAccount Number: 1234-5678-9012-3456\nIBAN: PK36HABB0000123456789012",
                'is_active' => true,
                'sort_order' => 4,
                'details' => [
                    'bank_name' => 'HBL Pakistan',
                    'account_title' => 'IEC Courses',
                    'account_number' => '1234-5678-9012-3456',
                    'iban' => 'PK36HABB0000123456789012',
                    'color' => 'text-primary',
                ]
            ],
            [
                'name' => 'Credit/Debit Card',
                'key' => 'card',
                'description' => 'Pay with Visa, Mastercard, etc.',
                'icon' => 'fas fa-credit-card',
                'instructions' => 'You will be redirected to a secure payment page to enter your card details.',
                'is_active' => true,
                'sort_order' => 5,
                'details' => [
                    'processor' => 'stripe',
                    'color' => 'text-info',
                ]
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::updateOrCreate(
                ['key' => $method['key']],
                $method
            );
        }
    }
}
