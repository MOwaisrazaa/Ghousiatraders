<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Lecture;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use App\Models\AdminUserAssignment;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Display a listing of the quizzes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizzes = Quiz::with('lecture')->paginate(10);
        return view('admin.quizzes.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new quiz.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lectures = Lecture::all();
        return view('admin.quizzes.create', compact('lectures'));
    }

    /**
     * Store a newly created quiz in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lecture_id' => 'required|exists:lectures,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_points' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:1|max:100',
            'is_required' => 'boolean',
            'time_limit' => 'nullable|integer|min:1',
        ]);

        $quiz = Quiz::create($validated);

        return redirect()->route('admin.quizzes.edit', $quiz)
            ->with('success', 'Quiz created successfully. Now add some questions!');
    }

    /**
     * Show the quiz.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function show(Quiz $quiz)
    {
        $quiz->load(['lecture', 'questions.options']);
        return view('admin.quizzes.show', compact('quiz'));
    }

    /**
     * Show the form for editing the quiz.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function edit(Quiz $quiz)
    {
        $lectures = Lecture::all();
        $quiz->load(['questions.options']);
        return view('admin.quizzes.edit', compact('quiz', 'lectures'));
    }

    /**
     * Update the quiz in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'lecture_id' => 'required|exists:lectures,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_points' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:1|max:100',
            'is_required' => 'boolean',
            'time_limit' => 'nullable|integer|min:1',
        ]);

        $quiz->update($validated);

        return redirect()->route('admin.quizzes.edit', $quiz)
            ->with('success', 'Quiz updated successfully.');
    }

    /**
     * Remove the quiz from storage.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz deleted successfully.');
    }

    /**
     * Show the form for creating a new question.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function createQuestion(Quiz $quiz)
    {
        $nextOrder = $quiz->questions()->count() + 1;
        return view('admin.quiz-questions.create', compact('quiz', 'nextOrder'));
    }

    /**
     * Store a new question for the quiz.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,open_ended',
            'points' => 'required|integer|min:1',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*.option_text' => 'required_if:question_type,multiple_choice|string',
            'options.*.is_correct' => 'nullable|boolean',
        ]);

        // Create the question
        $question = $quiz->questions()->create([
            'question_text' => $validated['question_text'],
            'question_type' => $validated['question_type'],
            'points' => $validated['points'],
            'order' => $quiz->questions()->count() + 1,
        ]);

        // Create options for multiple choice questions
        if ($validated['question_type'] === 'multiple_choice' && isset($validated['options'])) {
            $order = 1;
            $hasCorrectOption = false;

            foreach ($validated['options'] as $optionData) {
                $isCorrect = isset($optionData['is_correct']) && $optionData['is_correct'];

                $option = $question->options()->create([
                    'option_text' => $optionData['option_text'],
                    'is_correct' => $isCorrect,
                    'order' => $order++,
                ]);

                if ($isCorrect) {
                    $hasCorrectOption = true;
                }
            }

            // Ensure at least one option is marked as correct
            if (!$hasCorrectOption) {
                // Mark the first option as correct by default
                $question->options()->first()->update(['is_correct' => true]);
            }
        }

        return redirect()->route('admin.quizzes.edit', $quiz)
            ->with('success', 'Question added successfully.');
    }

    /**
     * Show the form for editing a quiz question.
     *
     * @param  \App\Models\QuizQuestion  $question
     * @return \Illuminate\Http\Response
     */
    public function editQuestion(QuizQuestion $question)
    {
        $question->load('options');
        return view('admin.quiz-questions.edit', compact('question'));
    }

    /**
     * Update a quiz question.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuizQuestion  $question
     * @return \Illuminate\Http\Response
     */
    public function updateQuestion(Request $request, QuizQuestion $question)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,open_ended',
            'points' => 'required|integer|min:1',
            'options' => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*.id' => 'nullable|exists:quiz_options,id',
            'options.*.option_text' => 'required_if:question_type,multiple_choice|string',
            'options.*.is_correct' => 'nullable|boolean',
        ]);

        // Update question
        $question->update([
            'question_text' => $validated['question_text'],
            'question_type' => $validated['question_type'],
            'points' => $validated['points'],
        ]);

        // Handle options for multiple choice questions
        if ($validated['question_type'] === 'multiple_choice' && isset($validated['options'])) {
            $existingOptionIds = $question->options->pluck('id')->toArray();
            $submittedOptionIds = collect($validated['options'])->pluck('id')->filter()->toArray();

            // Delete options that are no longer needed
            $optionsToDelete = array_diff($existingOptionIds, $submittedOptionIds);
            if (!empty($optionsToDelete)) {
                QuizOption::whereIn('id', $optionsToDelete)->delete();
            }

            // Update or create options
            $order = 1;
            $hasCorrectOption = false;

            foreach ($validated['options'] as $optionData) {
                $isCorrect = isset($optionData['is_correct']) && $optionData['is_correct'];

                if (isset($optionData['id'])) {
                    // Update existing option
                    $option = QuizOption::find($optionData['id']);
                    if ($option) {
                        $option->update([
                            'option_text' => $optionData['option_text'],
                            'is_correct' => $isCorrect,
                            'order' => $order++,
                        ]);
                    }
                } else {
                    // Create new option
                    $option = $question->options()->create([
                        'option_text' => $optionData['option_text'],
                        'is_correct' => $isCorrect,
                        'order' => $order++,
                    ]);
                }

                if ($isCorrect) {
                    $hasCorrectOption = true;
                }
            }

            // Ensure at least one option is marked as correct
            if (!$hasCorrectOption) {
                // Mark the first option as correct by default
                $question->options()->first()->update(['is_correct' => true]);
            }
        }

        return redirect()->route('admin.quizzes.edit', $question->quiz)
            ->with('success', 'Question updated successfully.');
    }

    /**
     * Delete a quiz question.
     *
     * @param  \App\Models\QuizQuestion  $question
     * @return \Illuminate\Http\Response
     */
    public function destroyQuestion(QuizQuestion $question)
    {
        $quiz = $question->quiz;
        $question->delete();

        return redirect()->route('admin.quizzes.edit', $quiz)
            ->with('success', 'Question deleted successfully.');
    }

    /**
     * View quiz attempt details.
     *
     * @param  \App\Models\QuizAttempt  $attempt
     * @return \Illuminate\Http\Response
     */
    public function viewAttempt(QuizAttempt $attempt)
    {
        $attempt->load(['user', 'quiz', 'answers.question', 'answers.selectedOption']);

        return view('admin.quiz-attempts.show', compact('attempt'));
    }

    /**
     * List quiz attempts for a specific quiz.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function quizAttempts(Quiz $quiz)
    {
        $query = QuizAttempt::where('quiz_id', $quiz->id)->with('user');

        // Filter by result (passed/failed)
        if (request()->has('result')) {
            if (request('result') === 'passed') {
                $query->where('status', 'passed');
            } elseif (request('result') === 'failed') {
                $query->where('status', 'failed');
            }
        }

        // Search by student name
        if (request()->has('search') && !empty(request('search'))) {
            $search = request('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $attempts = $query->latest()->paginate(15);

        // Count unique students
        $uniqueStudentCount = $quiz->attempts()->distinct('user_id')->count('user_id');

        // Generate score distribution data for chart
        $scoreRanges = [
            '0-20' => [0, 20, 0],
            '21-40' => [21, 40, 0],
            '41-60' => [41, 60, 0],
            '61-80' => [61, 80, 0],
            '81-100' => [81, 100, 0]
        ];

        $allScores = $quiz->attempts()->pluck('score')->toArray();
        foreach ($allScores as $score) {
            foreach ($scoreRanges as $key => &$range) {
                if ($score >= $range[0] && $score <= $range[1]) {
                    $range[2]++;
                }
            }
        }

        $scoreDistribution = [
            'labels' => array_keys($scoreRanges),
            'data' => array_column($scoreRanges, 2)
        ];

        // Generate results over time data
        $results = $quiz->attempts()
            ->selectRaw('DATE(created_at) as date, AVG(CASE WHEN status = "passed" THEN 100 ELSE 0 END) as pass_rate')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $resultsOverTime = [
            'labels' => $results->pluck('date')->toArray(),
            'data' => $results->pluck('pass_rate')->toArray()
        ];

        // Get statistics on the most challenging questions
        $questionStats = [];
        foreach ($quiz->questions as $question) {
            $totalAttempts = $question->answers()->count();
            if ($totalAttempts > 0) {
                $correctCount = $question->answers()->where('is_correct', true)->count();
                $successRate = round(($correctCount / $totalAttempts) * 100);

                $questionStats[] = [
                    'question_text' => $question->question_text,
                    'question_type' => $question->question_type,
                    'points' => $question->points,
                    'success_rate' => $successRate
                ];
            }
        }

        // Sort by success rate ascending (most challenging first)
        usort($questionStats, function($a, $b) {
            return $a['success_rate'] - $b['success_rate'];
        });

        return view('admin.quizzes.attempts', compact('quiz', 'attempts', 'uniqueStudentCount', 'scoreDistribution', 'resultsOverTime', 'questionStats'));
    }

    /**
     * Grade a quiz answer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuizAttempt  $attempt
     * @return \Illuminate\Http\Response
     */
    public function gradeAnswer(Request $request, QuizAttempt $attempt)
    {
        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'required|boolean',
            'feedback' => 'nullable|array',
            'feedback.*' => 'nullable|string',
        ]);

        foreach ($validated['grades'] as $answerId => $isCorrect) {
            $answer = QuizAnswer::findOrFail($answerId);

            // Make sure the answer belongs to this attempt
            if ($answer->quiz_attempt_id != $attempt->id) {
                continue;
            }

            // Use the markAsReviewed method to properly update the answer
            $feedback = isset($validated['feedback'][$answerId]) ? $validated['feedback'][$answerId] : null;
            $answer->markAsReviewed(Auth::id(), (bool) $isCorrect, $feedback);
        }

        // Recalculate the overall score
        $attempt->calculateScore();

        return redirect()->route('admin.quiz-attempts.show', $attempt)
            ->with('success', 'Answers graded successfully.');
    }

    /**
     * Show pending quiz reviews.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingReviews()
    {
        $user = Auth::user();

        // Get pending text answers based on admin permissions
        if ($user->isSuperAdmin()) {
            // Super admin sees all pending reviews
            $pendingAnswers = QuizAnswer::with(['question.quiz.lecture.course', 'attempt.user'])
                ->where('review_status', 'pending_review')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Regular admin only sees pending reviews from their assigned users
            $assignedUserIds = AdminUserAssignment::where('admin_id', $user->id)
                ->pluck('user_id')
                ->toArray();

            $pendingAnswers = QuizAnswer::with(['question.quiz.lecture.course', 'attempt.user'])
                ->where('review_status', 'pending_review')
                ->whereHas('attempt', function($query) use ($assignedUserIds) {
                    $query->whereIn('user_id', $assignedUserIds);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        }

        return view('admin.quiz-reviews.pending', compact('pendingAnswers'));
    }

    /**
     * Review and grade text answers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reviewAnswers(Request $request)
    {
        $validated = $request->validate([
            'reviews' => 'required|array',
            'reviews.*.answer_id' => 'required|exists:quiz_answers,id',
            'reviews.*.is_correct' => 'required|boolean',
            'reviews.*.feedback' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $reviewedCount = 0;

        foreach ($validated['reviews'] as $review) {
            $answer = QuizAnswer::findOrFail($review['answer_id']);

            // Check if admin has permission to review this answer
            if (!$user->isSuperAdmin()) {
                $assignedUserIds = AdminUserAssignment::where('admin_id', $user->id)
                    ->pluck('user_id')
                    ->toArray();

                if (!in_array($answer->attempt->user_id, $assignedUserIds)) {
                    continue; // Skip this answer if admin doesn't have permission
                }
            }

            // Only review if it's still pending
            if ($answer->review_status === 'pending_review') {
                $answer->markAsReviewed(
                    $user->id,
                    $review['is_correct'],
                    $review['feedback'] ?? null
                );

                // Recalculate the attempt score
                $answer->attempt->calculateScore();

                $reviewedCount++;
            }
        }

        return redirect()->route('admin.quiz-reviews.pending')
            ->with('success', "Successfully reviewed {$reviewedCount} answer(s).");
    }
}
