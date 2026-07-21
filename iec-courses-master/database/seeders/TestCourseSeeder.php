<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestCourseSeeder extends Seeder
{
    public function run()
    {
        // Insert a single test course directly using DB facade
        DB::table('courses')->insert([
            'name' => 'Test Course ' . time(),
            'description' => 'This is a test course created to verify the admin panel is working correctly.',
            'weekly_price' => 99.99,
            'monthly_price' => 299.99,
            'image_path' => 'test-course.jpg',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $courseId = DB::getPdo()->lastInsertId();

        // Add some features
        DB::table('course_features')->insert([
            [
                'course_id' => $courseId,
                'feature_text' => 'Test learning feature 1',
                'feature_type' => 'learn',
                'sort_order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'course_id' => $courseId,
                'feature_text' => 'Test requirement feature 1',
                'feature_type' => 'requirement',
                'sort_order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        $this->command->info('Test course created with ID: ' . $courseId);
    }
}
