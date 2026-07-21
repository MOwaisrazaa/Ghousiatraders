<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class VideoProxyController extends Controller
{
    protected function notConfigured(): JsonResponse
    {
        return response()->json([
            'message' => 'Video proxy streaming is not configured in this branch.',
        ], 501);
    }

    public function embedVideo()
    {
        return $this->notConfigured();
    }

    public function getEmbedUrl()
    {
        return $this->notConfigured();
    }

    public function getVideo()
    {
        return $this->notConfigured();
    }
}
