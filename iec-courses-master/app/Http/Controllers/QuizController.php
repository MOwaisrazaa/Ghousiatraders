<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    protected function notConfigured(): JsonResponse
    {
        return response()->json([
            'message' => 'Quiz functionality is not configured in this branch.',
        ], 501);
    }

    public function show()
    {
        return $this->notConfigured();
    }

    public function beginQuiz()
    {
        return $this->notConfigured();
    }

    public function showQuestion()
    {
        return $this->notConfigured();
    }

    public function submitQuestionAnswer()
    {
        return $this->notConfigured();
    }

    public function submitAnswer()
    {
        return $this->notConfigured();
    }

    public function complete()
    {
        return $this->notConfigured();
    }

    public function result()
    {
        return $this->notConfigured();
    }
}
