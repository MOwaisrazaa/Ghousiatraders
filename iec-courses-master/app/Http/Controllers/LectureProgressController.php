<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class LectureProgressController extends Controller
{
    protected function notConfigured(): JsonResponse
    {
        return response()->json([
            'message' => 'Lecture progress tracking is not configured in this branch.',
        ], 501);
    }

    public function updateProgress()
    {
        return $this->notConfigured();
    }

    public function getProgress()
    {
        return $this->notConfigured();
    }

    public function markCompleted()
    {
        return $this->notConfigured();
    }
}
