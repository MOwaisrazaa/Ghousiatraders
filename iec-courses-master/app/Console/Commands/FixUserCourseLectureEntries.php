<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserCourse;
use App\Models\Lecture;

class FixUserCourseLectureEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:user-course-lectures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix UserCourse entries for lecture purchases by setting correct course_id';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to fix UserCourse entries for lecture purchases...');

        // Find all UserCourse entries with lecture_id but null course_id
        $entriesToFix = UserCourse::whereNull('course_id')
            ->whereNotNull('lecture_id')
            ->get();

        $this->info("Found {$entriesToFix->count()} entries to check.");

        $fixed = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($entriesToFix as $entry) {
            // Get the lecture to find its course_id
            $lecture = Lecture::find($entry->lecture_id);

            if (!$lecture) {
                $this->error("Could not find lecture for entry ID: {$entry->id}");
                $errors++;
                continue;
            }

            // If lecture has a course_id, update the entry
            if ($lecture->course_id) {
                $entry->course_id = $lecture->course_id;
                $entry->save();
                $fixed++;
            } else {
                // This is a standalone lecture - course_id should remain null
                $this->line("Entry ID: {$entry->id} is for standalone lecture ID: {$lecture->id} - skipping.");
                $skipped++;
            }
        }

        $this->info("Fixed $fixed entries successfully.");
        $this->info("Skipped $skipped standalone lecture entries.");

        if ($errors > 0) {
            $this->warn("Encountered $errors errors.");
        }

        return 0;
    }
}
