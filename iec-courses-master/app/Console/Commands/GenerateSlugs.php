<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use App\Models\Lecture;
use Illuminate\Support\Str;

class GenerateSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs for all courses and lectures that don\'t have one';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting slug generation...');
        
        // Generate slugs for courses
        $this->info('Generating slugs for courses...');
        $courses = Course::whereNull('slug')->orWhere('slug', '')->get();
        $courseCount = 0;
        
        foreach ($courses as $course) {
            $baseSlug = Str::slug($course->name);
            $slug = $baseSlug;
            $counter = 1;
            
            // Ensure uniqueness
            while (Course::where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $course->slug = $slug;
            $course->save();
            $courseCount++;
            
            $this->line("Course: {$course->name} -> {$slug}");
        }
        
        $this->info("Generated {$courseCount} course slugs.");
        
        // Generate slugs for lectures
        $this->info('Generating slugs for lectures...');
        $lectures = Lecture::whereNull('slug')->orWhere('slug', '')->get();
        $lectureCount = 0;
        
        foreach ($lectures as $lecture) {
            $baseSlug = Str::slug($lecture->name);
            $slug = $baseSlug;
            $counter = 1;
            
            // Ensure uniqueness
            while (Lecture::where('slug', $slug)->where('id', '!=', $lecture->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            $lecture->slug = $slug;
            $lecture->save();
            $lectureCount++;
            
            $this->line("Lecture: {$lecture->name} -> {$slug}");
        }
        
        $this->info("Generated {$lectureCount} lecture slugs.");
        $this->info('Slug generation completed!');
        
        return Command::SUCCESS;
    }
}
