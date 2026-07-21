<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoTokenController extends Controller
{
    public function generateVideoToken(Request $request)
    {
        return response()->json([
            'error' => 'Video token generation is not configured in this branch.',
        ], 501);
    }

    public function getVideoData(Request $request)
    {
        return response()->json([
            'error' => 'Video data lookup is not configured in this branch.',
        ], 501);
    }
}
