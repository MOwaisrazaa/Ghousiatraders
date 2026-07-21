<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateQuizAttempts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:cleanup-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up duplicate quiz attempts for the same user and quiz';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup of duplicate quiz attempts...');
        
        // Find all user-quiz combinations that have multiple attempts
        $duplicates = DB::table('quiz_attempts')
            ->select('user_id', 'quiz_id', DB::raw('COUNT(*) as count'))
            ->groupBy('user_id', 'quiz_id')
            ->having('count', '>', 1)
            ->get();
        
        $totalCleaned = 0;
        
        foreach ($duplicates as $duplicate) {
            $this->info("Found {$duplicate->count} attempts for User {$duplicate->user_id}, Quiz {$duplicate->quiz_id}");
            
            // Get all attempts for this combination, ordered by priority and created_at
            $attempts = QuizAttempt::where('user_id', $duplicate->user_id)
                ->where('quiz_id', $duplicate->quiz_id)
                ->orderByRaw("CASE 
                    WHEN status = 'passed' THEN 1
                    WHEN status = 'in_progress' THEN 2
                    WHEN status = 'pending_review' THEN 3
                    WHEN status = 'failed' THEN 4
                    ELSE 5
                END")
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Keep the best one (passed > in_progress > pending_review > failed)
            $keepAttempt = $attempts->first();
            $this->info("Keeping attempt ID: {$keepAttempt->id} (Status: {$keepAttempt->status}, created at {$keepAttempt->created_at})");
            
            // Delete all others
            foreach ($attempts->skip(1) as $oldAttempt) {
                $this->warn("Deleting old attempt ID: {$oldAttempt->id} (Status: {$oldAttempt->status}, created at {$oldAttempt->created_at})");
                
                // Delete associated answers first
                $oldAttempt->answers()->delete();
                
                // Delete the attempt
                $oldAttempt->delete();
                
                $totalCleaned++;
            }
        }
        
        $this->info("Cleanup complete! Removed {$totalCleaned} duplicate attempts.");
        
        return 0;
    }
}
