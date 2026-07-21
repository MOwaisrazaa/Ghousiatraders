<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Clear existing categories
        DB::table('categories')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Create categories
        $categories = [
            ['name' => 'Islamic Banking and Finance', 'slug' => 'islamic-banking-and-finance'],
            ['name' => 'Modern Applications of Mudarabah and Murabaha', 'slug' => 'mudarabah-murabaha'],
            ['name' => 'Riba (Interest) and its Contemporary Forms', 'slug' => 'riba-and-contemporary-forms'],
            ['name' => 'Investment Tools in Shariah', 'slug' => 'shariah-investment-tools'],
            ['name' => 'Legal Personality in Islamic Law', 'slug' => 'legal-personality-islamic-law'],
            ['name' => 'Agency and Partnership in Contemporary Context', 'slug' => 'agency-partnership-modern'],
            ['name' => 'Contracts and Guarantees in Islamic Finance', 'slug' => 'islamic-financial-contracts'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
