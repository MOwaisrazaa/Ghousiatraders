<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\AdminPermissionsSeeder;

class SeedAdminPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-admin-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed permissions for existing admin users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding admin permissions...');

        $seeder = new AdminPermissionsSeeder();
        $seeder->setCommand($this);
        $seeder->run();

        $this->info('Admin permissions seeded successfully.');

        return Command::SUCCESS;
    }
}
