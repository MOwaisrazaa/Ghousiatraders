<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lecture;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
    public function index()
    {
        $courses = Course::where('show_publicly', true)->get();
        
        $lectures = Lecture::whereHas('course', function($q) {
            $q->where('show_publicly', true);
        })->with('course')->get();

        return response()->view('sitemap.index', [
            'courses' => $courses,
            'lectures' => $lectures,
        ])->header('Content-Type', 'text/xml');
    }
}
