<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserCourse;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateUserCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:duplicate-user-courses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up duplicate UserCourse entries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of duplicate UserCourse entries...');

        // Find and remove duplicate course access entries
        $this->info('Cleaning up duplicate course access entries...');
        $courseQuery = "
            DELETE uc1 FROM user_courses uc1
            INNER JOIN user_courses uc2 
            WHERE uc1.id > uc2.id 
            AND uc1.user_id = uc2.user_id 
            AND uc1.course_id = uc2.course_id 
            AND uc1.lecture_id IS NULL 
            AND uc2.lecture_id IS NULL
            AND uc1.status = 'active'
            AND uc2.status = 'active'
        ";
        
        $courseDeleted = DB::delete($courseQuery);
        $this->info("Removed {$courseDeleted} duplicate course access entries.");

        // Find and remove duplicate lecture access entries
        $this->info('Cleaning up duplicate lecture access entries...');
        $lectureQuery = "
            DELETE uc1 FROM user_courses uc1
            INNER JOIN user_courses uc2 
            WHERE uc1.id > uc2.id 
            AND uc1.user_id = uc2.user_id 
            AND uc1.course_id = uc2.course_id 
            AND uc1.lecture_id = uc2.lecture_id 
            AND uc1.lecture_id IS NOT NULL
            AND uc1.status = 'active'
            AND uc2.status = 'active'
        ";
        
        $lectureDeleted = DB::delete($lectureQuery);
        $this->info("Removed {$lectureDeleted} duplicate lecture access entries.");

        // Show summary
        $totalRemoved = $courseDeleted + $lectureDeleted;
        $this->info("Total duplicate entries removed: {$totalRemoved}");

        // Show current counts
        $totalCourseAccess = UserCourse::whereNull('lecture_id')->where('status', 'active')->count();
        $totalLectureAccess = UserCourse::whereNotNull('lecture_id')->where('status', 'active')->count();
        
        $this->info("Current active course access entries: {$totalCourseAccess}");
        $this->info("Current active lecture access entries: {$totalLectureAccess}");
        
        $this->info('Cleanup completed successfully!');
        
        return 0;
    }
}
