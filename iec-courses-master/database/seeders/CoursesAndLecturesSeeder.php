<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Lecture;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CoursesAndLecturesSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        DB::table('shoppingcarts')->truncate();
        DB::table('lectures')->truncate();
        DB::table('courses')->truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create sample courses
        $courses = [
            [
                'name' => 'Islamic Banking and Finance',
                'description' => 'Comprehensive overview of Islamic banking principles and practices.',
                'weekly_price' => 120.00,
                'monthly_price' => 360.00,
                'image_path' => 'islamic-banking.jpg',
                'category_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Modern Applications of Mudarabah and Murabaha',
                'description' => 'Exploring the use of Mudarabah and Murabaha in contemporary investment scenarios.',
                'weekly_price' => 100.00,
                'monthly_price' => 300.00,
                'image_path' => 'mudarabah-murabaha.jpg',
                'category_id' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Riba (Interest) and its Contemporary Forms',
                'description' => 'Understanding Riba, its types, and modern manifestations.',
                'weekly_price' => 90.00,
                'monthly_price' => 270.00,
                'image_path' => 'riba.jpg',
                'category_id' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Investment Tools in Shariah',
                'description' => 'Study of various Shariah-compliant investment instruments.',
                'weekly_price' => 110.00,
                'monthly_price' => 330.00,
                'image_path' => 'shariah-investment.jpg',
                'category_id' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Legal Personality in Islamic Law',
                'description' => 'Examining the concept of legal personality from a Shariah perspective.',
                'weekly_price' => 80.00,
                'monthly_price' => 240.00,
                'image_path' => 'legal-personality.jpg',
                'category_id' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Agency and Partnership in Contemporary Context',
                'description' => 'Application of agency and partnership principles in modern Islamic finance.',
                'weekly_price' => 95.00,
                'monthly_price' => 285.00,
                'image_path' => 'agency-partnership.jpg',
                'category_id' => 6,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Contracts and Guarantees in Islamic Finance',
                'description' => 'In-depth look at contracts, guarantees, and sales in Islamic finance.',
                'weekly_price' => 105.00,
                'monthly_price' => 315.00,
                'image_path' => 'contracts-guarantees.jpg',
                'category_id' => 7,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($courses as $courseData) {
            $course = Course::create($courseData);

            // Create lectures for each course
            $lectures = $this->getLecturesForCourse($course->name);
            foreach ($lectures as $lectureData) {
                Lecture::create([
                    'course_id' => $course->id,
                    'name' => $lectureData['title'],
                    'description' => $lectureData['description'],
                    'youtube_url' => $lectureData['video_url'],
                    'weekly_price' => $lectureData['weekly_price'] ?? null,
                    'monthly_price' => $lectureData['monthly_price'] ?? null,
                    'image_path' => $lectureData['image_path'] ?? null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

    private function getLecturesForCourse($courseTitle)
    {
        switch ($courseTitle) {
            case 'Islamic Banking and Finance':
                return [
                    [
                        'title' => 'Currency Transactions in Islamic Banking',
                        'description' => 'Understanding permissible currency exchange practices.',
                        'video_url' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE', // Educational video that allows embedding
                        'weekly_price' => 30.00,
                        'monthly_price' => 90.00,
                        'image_path' => 'currency-transactions.jpg',
                    ],
                    [
                        'title' => 'Dollar Booking and Shariah Compliance',
                        'description' => 'Examining the Shariah aspects of dollar booking.',
                        'video_url' => 'https://www.youtube.com/watch?v=ZbqPuFUXvfA', // Another educational video
                        'weekly_price' => 30.00,
                        'monthly_price' => 90.00,
                        'image_path' => 'dollar-booking.jpg',
                    ],
                    [
                        'title' => 'Letter of Credit (LC) Amount Collection',
                        'description' => 'Shariah perspective on collecting LC amounts in advance.',
                        'video_url' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE',
                        'weekly_price' => 30.00,
                        'monthly_price' => 90.00,
                        'image_path' => 'lc-collection.jpg',
                    ],
                    [
                        'title' => 'Shariah Perspective on Bank Card Types',
                        'description' => 'Analyzing different bank cards from a Shariah viewpoint.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 30.00,
                        'monthly_price' => 90.00,
                        'image_path' => 'bank-cards.jpg',
                    ],
                    [
                        'title' => 'Treasury of Islamic Banks',
                        'description' => 'Overview of treasury operations in Islamic banking.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 30.00,
                        'monthly_price' => 90.00,
                        'image_path' => 'treasury-islamic-banks.jpg',
                    ],
                ];

            case 'Modern Applications of Mudarabah and Murabaha':
                return [
                    [
                        'title' => 'Introduction to Mudarabah',
                        'description' => 'Basics of Mudarabah and its principles.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 25.00,
                        'monthly_price' => 75.00,
                        'image_path' => 'mudarabah-intro.jpg',
                    ],
                    [
                        'title' => 'Use of Mudarabah in Contemporary Investment',
                        'description' => 'Practical applications of Mudarabah in modern investments.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 25.00,
                        'monthly_price' => 75.00,
                        'image_path' => 'mudarabah-investment.jpg',
                    ],
                    [
                        'title' => 'Introduction to Murabaha',
                        'description' => 'Understanding Murabaha and its structure.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 25.00,
                        'monthly_price' => 75.00,
                        'image_path' => 'murabaha-intro.jpg',
                    ],
                    [
                        'title' => 'Use of Murabaha in Contemporary Investment',
                        'description' => 'How Murabaha is applied in modern finance.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 25.00,
                        'monthly_price' => 75.00,
                        'image_path' => 'murabaha-investment.jpg',
                    ],
                ];

            case 'Riba (Interest) and its Contemporary Forms':
                return [
                    [
                        'title' => 'Definition of Riba',
                        'description' => 'Understanding the concept and implications of Riba.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 22.50,
                        'monthly_price' => 67.50,
                        'image_path' => 'riba-definition.jpg',
                    ],
                    [
                        'title' => 'Types of Riba',
                        'description' => 'Exploring the different categories of Riba.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 22.50,
                        'monthly_price' => 67.50,
                        'image_path' => 'riba-types.jpg',
                    ],
                    [
                        'title' => 'Examples of Riba in the Modern Era',
                        'description' => 'Identifying Riba in contemporary financial systems.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 22.50,
                        'monthly_price' => 67.50,
                        'image_path' => 'riba-modern-examples.jpg',
                    ],
                    [
                        'title' => 'Shariah Rulings on Riba',
                        'description' => 'Islamic legal perspectives on Riba.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 22.50,
                        'monthly_price' => 67.50,
                        'image_path' => 'riba-rulings.jpg',
                    ],
                ];

            case 'Investment Tools in Shariah':
                return [
                    [
                        'title' => 'Istisna Contract and Its Role',
                        'description' => 'Understanding Istisna and its applications in investment.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 27.50,
                        'monthly_price' => 82.50,
                        'image_path' => 'istisna-contract.jpg',
                    ],
                    [
                        'title' => 'Salam Contract as a Business Solution',
                        'description' => 'How Salam contracts address modern business issues.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 27.50,
                        'monthly_price' => 82.50,
                        'image_path' => 'salam-contract.jpg',
                    ],
                    [
                        'title' => 'What are Mutual Fund Institutions?',
                        'description' => 'Overview of mutual funds in Islamic finance.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 27.50,
                        'monthly_price' => 82.50,
                        'image_path' => 'mutual-funds.jpg',
                    ],
                    [
                        'title' => 'How Mutual Funds Work in Shariah',
                        'description' => 'Operational mechanics of Shariah-compliant mutual funds.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 27.50,
                        'monthly_price' => 82.50,
                        'image_path' => 'mutual-funds-shariah.jpg',
                    ],
                ];

            case 'Legal Personality in Islamic Law':
                return [
                    [
                        'title' => 'Concept of Legal Person',
                        'description' => 'Defining legal personality in Shariah.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 20.00,
                        'monthly_price' => 60.00,
                        'image_path' => 'legal-person-concept.jpg',
                    ],
                    [
                        'title' => 'Shariah Status of Legal Persons',
                        'description' => 'Examining the recognition of legal persons in Islamic law.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 20.00,
                        'monthly_price' => 60.00,
                        'image_path' => 'legal-person-status.jpg',
                    ],
                    [
                        'title' => 'Applications in Islamic Finance',
                        'description' => 'How legal personality applies to modern finance.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 20.00,
                        'monthly_price' => 60.00,
                        'image_path' => 'legal-person-finance.jpg',
                    ],
                ];

            case 'Agency and Partnership in Contemporary Context':
                return [
                    [
                        'title' => 'Agency in Contemporary Issues',
                        'description' => 'Use of agency (Wakalah) in modern financial contexts.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 23.75,
                        'monthly_price' => 71.25,
                        'image_path' => 'agency-modern.jpg',
                    ],
                    [
                        'title' => 'Partnership in Contemporary Issues',
                        'description' => 'Use of partnership (Shirkah) in modern financial contexts.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 23.75,
                        'monthly_price' => 71.25,
                        'image_path' => 'partnership-modern.jpg',
                    ],
                    [
                        'title' => 'Principles of Agency in Shariah',
                        'description' => 'Core concepts of agency under Islamic law.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 23.75,
                        'monthly_price' => 71.25,
                        'image_path' => 'agency-principles.jpg',
                    ],
                    [
                        'title' => 'Principles of Partnership in Shariah',
                        'description' => 'Core concepts of partnership under Islamic law.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 23.75,
                        'monthly_price' => 71.25,
                        'image_path' => 'partnership-principles.jpg',
                    ],
                ];

            case 'Contracts and Guarantees in Islamic Finance':
                return [
                    [
                        'title' => 'Services and Their Compensation',
                        'description' => 'Examining services and remuneration in modern contexts.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 26.25,
                        'monthly_price' => 78.75,
                        'image_path' => 'services-compensation.jpg',
                    ],
                    [
                        'title' => 'Guarantee System in Bank Agreements',
                        'description' => 'Understanding guarantees in the context of banking.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 26.25,
                        'monthly_price' => 78.75,
                        'image_path' => 'guarantee-system.jpg',
                    ],
                    [
                        'title' => 'Basic Rulings of Sales',
                        'description' => 'Fundamental principles of sales in Islamic law.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 26.25,
                        'monthly_price' => 78.75,
                        'image_path' => 'sales-rulings.jpg',
                    ],
                    [
                        'title' => 'Contemporary Issues in Sales',
                        'description' => 'Addressing modern challenges in Islamic sales contracts.',
                        'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                        'weekly_price' => 26.25,
                        'monthly_price' => 78.75,
                        'image_path' => 'sales-issues.jpg',
                    ],
                ];

            default:
                return [];
        }
    }
}
