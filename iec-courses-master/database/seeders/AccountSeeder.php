<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountBalance;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $defaultAccounts = [
            [
                'name' => 'main',
                'description' => 'Main operating account',
            ],
            [
                'name' => 'marketing',
                'description' => 'Marketing department account',
            ],
            [
                'name' => 'operations',
                'description' => 'Operations department account',
            ],
            [
                'name' => 'development',
                'description' => 'Development department account',
            ],
            [
                'name' => 'infrastructure',
                'description' => 'Infrastructure account',
            ],
        ];

        foreach ($defaultAccounts as $accountData) {
            $account = Account::firstOrCreate(
                ['name' => $accountData['name']],
                $accountData
            );

            // Create corresponding balance record if it doesn't exist
            AccountBalance::firstOrCreate(
                ['account_name' => $account->name],
                [
                    'balance' => 0,
                    'total_received' => 0,
                    'total_used' => 0,
                    'total_transferred' => 0,
                ]
            );
        }
    }
}
