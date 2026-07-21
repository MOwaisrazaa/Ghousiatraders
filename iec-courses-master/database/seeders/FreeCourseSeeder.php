<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Lecture;
use Carbon\Carbon;

class FreeCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create free courses
        $freeCourses = [
            [
                'name' => 'Introduction to Islamic Finance - FREE',
                'description' => 'A comprehensive introduction to Islamic finance principles. This free course covers the basics of Islamic banking, Shariah compliance, and fundamental concepts. Perfect for beginners looking to understand Islamic finance.',
                'instructor' => 'Dr. Islamic Finance Expert',
                'weekly_price' => 0,
                'monthly_price' => 0,
                'image_path' => 'islamic-finance-intro.jpg',
                'category_id' => 1,
                'is_free' => true,
            ],
            [
                'name' => 'Basics of Shariah Compliance - FREE',
                'description' => 'Learn the fundamentals of Shariah compliance in financial transactions. This free course is designed for anyone interested in understanding how Islamic law applies to modern finance.',
                'instructor' => 'Prof. Shariah Compliance',
                'weekly_price' => 0,
                'monthly_price' => 0,
                'image_path' => 'shariah-basics.jpg',
                'category_id' => 3,
                'is_free' => true,
            ],
            [
                'name' => 'Understanding Islamic Banking - FREE',
                'description' => 'Explore the principles and practices of Islamic banking. This free introductory course covers the history, structure, and operations of Islamic banks worldwide.',
                'instructor' => 'Dr. Banking Expert',
                'weekly_price' => 0,
                'monthly_price' => 0,
                'image_path' => 'islamic-banking-intro.jpg',
                'category_id' => 1,
                'is_free' => true,
            ],
        ];

        foreach ($freeCourses as $courseData) {
            $course = Course::create($courseData);

            // Create free lectures for each free course
            $lectures = $this->getFreeLecturesForCourse($course->name);
            foreach ($lectures as $lectureData) {
                Lecture::create([
                    'course_id' => $course->id,
                    'name' => $lectureData['title'],
                    'description' => $lectureData['description'],
                    'instructor' => $lectureData['instructor'] ?? $course->instructor,
                    'youtube_url' => $lectureData['video_url'],
                    'weekly_price' => 0,
                    'monthly_price' => 0,
                    'image_path' => $lectureData['image_path'] ?? null,
                    'duration' => $lectureData['duration'] ?? 30,
                    'is_free' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

    /**
     * Get free lectures for each free course
     */
    private function getFreeLecturesForCourse($courseTitle)
    {
        switch ($courseTitle) {
            case 'Introduction to Islamic Finance - FREE':
                return [
                    [
                        'title' => 'What is Islamic Finance?',
                        'description' => 'An overview of Islamic finance, its principles, and how it differs from conventional finance. Learn about the core values and objectives of Islamic financial systems.',
                        'video_url' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE',
                        'instructor' => 'Dr. Islamic Finance Expert',
                        'duration' => 25,
                        'image_path' => 'what-is-islamic-finance.jpg',
                    ],
                    [
                        'title' => 'Key Principles of Islamic Banking',
                        'description' => 'Discover the fundamental principles that guide Islamic banking operations. Understand concepts like Riba-free transactions, profit-sharing, and asset-backed financing.',
                        'video_url' => 'https://www.youtube.com/watch?v=ZbqPuFUXvfA',
                        'instructor' => 'Dr. Islamic Finance Expert',
                        'duration' => 30,
                        'image_path' => 'islamic-banking-principles.jpg',
                    ],
                    [
                        'title' => 'Shariah Compliance in Finance',
                        'description' => 'Learn how financial institutions ensure Shariah compliance. This lecture covers the role of Shariah boards, compliance frameworks, and regulatory requirements.',
                        'video_url' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE',
                        'instructor' => 'Prof. Shariah Compliance',
                        'duration' => 28,
                        'image_path' => 'shariah-compliance-finance.jpg',
                    ],
                    [
                        'title' => 'Common Islamic Financial Products',
                        'description' => 'Explore the most common Islamic financial products including Murabaha, Ijara, Musharaka, and Mudaraba. Understand how each product works and its applications.',
                        'video_url' => 'https://www.youtube.com/watch?v=ZbqPuFUXvfA',
                        'instructor' => 'Dr. Islamic Finance Expert',
                        'duration' => 35,
                        'image_path' => 'islamic-products.jpg',
                    ],
                ];

            case 'Basics of Shariah Compliance - FREE':
                return [
                    [
                        'title' => 'Understanding Shariah Law',
                        'description' => 'An introduction to Shariah law and its application in financial transactions. Learn about the sources of Shariah and how it guides Islamic finance.',
                        'video_url' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE',
                        'instructor' => 'Prof. Shariah Compliance',
                        'duration' => 32,
                        'image_path' => 'understanding-shariah.jpg',
                    ],
                    [
                        'title' => 'Riba: Prohibition and Implications',
                        'description' => 'Understand the concept of Riba (interest) in Islamic law. Learn why it is prohibited and how this principle shapes Islamic financial systems.',
                        'video_url' => 'https://www.youtube.com/watch?v=ZbqPuFUXvfA',
                        'instructor' => 'Prof. Shariah Compliance',
                        'duration' => 28,
                        'image_path' => 'riba-prohibition.jpg',
                    ],
                    [
                        'title' => 'Halal and Haram in Finance',
                        'description' => 'Explore the concepts of Halal (permissible) and Haram (forbidden) in financial transactions. Learn how to identify compliant and non-compliant financial activities.',
                        'video_url' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE',
                        'instructor' => 'Prof. Shariah Compliance',
                        'duration' => 26,
                        'image_path' => 'halal-haram-finance.jpg',
                    ],
                    [
                        'title' => 'Shariah Screening and Compliance Frameworks',
                        'description' => 'Learn about the frameworks and processes used to ensure Shariah compliance. Understand screening criteria, compliance monitoring, and governance structures.',
                        'video_url' => 'https://www.youtube.com/watch?v=ZbqPuFUXvfA',
                        'instructor' => 'Prof. Shariah Compliance',
                        'duration' => 30,
                        'image_path' => 'compliance-frameworks.jpg',
                    ],
                ];

            case 'Understanding Islamic Banking - FREE':
                return [
                    [
                        'title' => 'History and Evolution of Islamic Banking',
                        'description' => 'Trace the history of Islamic banking from its origins to modern times. Learn about key milestones and the growth of Islamic banking globally.',
                        'video_url' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE',
                        'instructor' => 'Dr. Banking Expert',
                        'duration' => 35,
                        'image_path' => 'islamic-banking-history.jpg',
                    ],
                    [
                        'title' => 'Structure and Operations of Islamic Banks',
                        'description' => 'Understand how Islamic banks are structured and operate. Learn about their departments, functions, and how they differ from conventional banks.',
                        'video_url' => 'https://www.youtube.com/watch?v=ZbqPuFUXvfA',
                        'instructor' => 'Dr. Banking Expert',
                        'duration' => 32,
                        'image_path' => 'islamic-bank-structure.jpg',
                    ],
                    [
                        'title' => 'Islamic Banking Products and Services',
                        'description' => 'Explore the various products and services offered by Islamic banks. Learn about deposit accounts, financing products, and investment services.',
                        'video_url' => 'https://www.youtube.com/watch?v=M7lc1UVf-VE',
                        'instructor' => 'Dr. Banking Expert',
                        'duration' => 30,
                        'image_path' => 'islamic-banking-products.jpg',
                    ],
                    [
                        'title' => 'Challenges and Future of Islamic Banking',
                        'description' => 'Examine the current challenges facing Islamic banking and explore future opportunities. Learn about regulatory issues, standardization, and growth prospects.',
                        'video_url' => 'https://www.youtube.com/watch?v=ZbqPuFUXvfA',
                        'instructor' => 'Dr. Banking Expert',
                        'duration' => 28,
                        'image_path' => 'islamic-banking-future.jpg',
                    ],
                ];

            default:
                return [];
        }
    }
}
