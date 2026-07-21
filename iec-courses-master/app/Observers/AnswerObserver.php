<?php

namespace App\Observers;

use App\Models\Answer;
use App\Models\Question;

class AnswerObserver
{
    /**
     * Handle the Answer "created" event.
     */
    public function created(Answer $answer): void
    {
        // Update the question status to 'approved' when an answer is created
        \Log::info("AnswerObserver: Answer created with ID {$answer->id} for question {$answer->question_id}");
        
        $question = Question::find($answer->question_id);
        if ($question) {
            \Log::info("AnswerObserver: Found question {$question->id} with status {$question->status}");
            $question->status = 'approved';
            $question->save();
            \Log::info("AnswerObserver: Updated question {$question->id} status to approved");
        } else {
            \Log::warning("AnswerObserver: Question {$answer->question_id} not found");
        }
    }

    /**
     * Handle the Answer "updated" event.
     */
    public function updated(Answer $answer): void
    {
        //
    }

    /**
     * Handle the Answer "deleted" event.
     */
    public function deleted(Answer $answer): void
    {
        // If all answers are deleted, revert question status back to pending
        $question = Question::find($answer->question_id);
        if ($question) {
            $answerCount = Answer::where('question_id', $answer->question_id)->count();
            if ($answerCount === 0) {
                $question->update(['status' => 'pending']);
            }
        }
    }

    /**
     * Handle the Answer "restored" event.
     */
    public function restored(Answer $answer): void
    {
        //
    }

    /**
     * Handle the Answer "force deleted" event.
     */
    public function forceDeleted(Answer $answer): void
    {
        //
    }
}
