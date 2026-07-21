<?php

namespace Database\Seeders;

use App\Models\CarouselSlide;
use Illuminate\Database\Seeder;

class CarouselSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create carousel directory if needed
        $carouselPath = public_path('assets/img/carousel');
        if (!file_exists($carouselPath)) {
            mkdir($carouselPath, 0755, true);
        }

        // Copy existing hero images to carousel directory
        $this->copyExistingImages();

        // Insert carousel slide data
        $slides = [
            [
                'title' => 'Master Islamic Finance',
                'subtitle' => 'Gain deep expertise in Sharia-compliant banking, ethical investing, and the global Islamic economy with our world-class curriculum.',
                'cta_text' => 'Explore FinTech',
                'cta_url' => route('courses'),
                'image_name' => 'hero-dashboard-1',
                'order' => 1,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'title' => 'Advance Your Career',
                'subtitle' => 'Unlock professional opportunities in the rapidly evolving field of Islamic finance with expert-led industry certifications.',
                'cta_text' => 'Discover Courses',
                'cta_url' => route('courses'),
                'image_name' => 'hero-dashboard-2',
                'order' => 2,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'title' => 'Global Certification',
                'subtitle' => 'Earn recognized certificates and join an elite worldwide network of dedicated Islamic finance professionals.',
                'cta_text' => 'Start Learning',
                'cta_url' => route('courses'),
                'image_name' => 'hero-dashboard-3',
                'order' => 3,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'title' => 'Join Our Community',
                'subtitle' => 'Connect with industry mentors and like-minded peers in our vibrant global learning ecosystem.',
                'cta_text' => 'Join Now',
                'cta_url' => route('sign-up'),
                'image_name' => 'hero-dashboard-4',
                'order' => 4,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
            ],
        ];

        foreach ($slides as $slide) {
            CarouselSlide::firstOrCreate(
                ['image_name' => $slide['image_name']],
                $slide
            );
        }

        echo "✓ Carousel slides seeded successfully!\n";
    }

    /**
     * Copy existing hero images to carousel directory.
     */
    private function copyExistingImages(): void
    {
        $sourcePath = public_path('assets/img');
        $destPath = public_path('assets/img/carousel');

        // List of image files to copy
        for ($i = 1; $i <= 4; $i++) {
            $files = [
                "hero-dashboard-{$i}.png",
                "hero-dashboard-{$i}.webp",
                "hero-dashboard-{$i}-400w.webp",
                "hero-dashboard-{$i}-800w.webp",
                "hero-dashboard-{$i}-1200w.webp",
            ];

            foreach ($files as $file) {
                $source = "{$sourcePath}/{$file}";
                $dest = "{$destPath}/{$file}";

                if (file_exists($source) && !file_exists($dest)) {
                    copy($source, $dest);
                }
            }
        }
    }
}
