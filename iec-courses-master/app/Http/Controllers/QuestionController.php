<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        return response()->json(['message' => 'Question posting is not configured in this branch.'], 501);
    }

    public function getQuestions(Request $request)
    {
        return response()->json(['message' => 'Question lookup is not configured in this branch.'], 501);
    }

    public function getQuestionLimits(Request $request)
    {
        return response()->json(['message' => 'Question limit lookup is not configured in this branch.'], 501);
    }
}
