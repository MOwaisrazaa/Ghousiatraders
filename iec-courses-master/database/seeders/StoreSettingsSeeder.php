<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\StoreSettingsService;

class StoreSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds for Ghousia Traders store settings.
     */
    public function run(): void
    {
        $defaults = StoreSettingsService::defaults();
        
        foreach ($defaults as $key => $value) {
            if ($value !== null && $value !== '') {
                \App\Models\Setting::firstOrCreate(
                    ['key' => $key],
                    ['value' => is_array($value) ? json_encode($value) : (string)$value]
                );
            }
        }

        StoreSettingsService::syncFooterSetting();
        StoreSettingsService::clearCache();

        $this->command->info('Ghousia Traders store settings seeded successfully.');
    }
}
