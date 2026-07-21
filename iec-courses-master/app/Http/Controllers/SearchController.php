<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lecture;

class SearchController extends Controller
{
    /**
     * Search for courses and lectures based on the query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Return empty response if query is empty
        if (empty($query)) {
            return response()->json([
                'courses' => [],
                'lectures' => []
            ]);
        }

        // Search for courses
        $courseResults = Course::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        // Search for lectures
        $lectureResults = Lecture::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        // Log the search
        try {
            \App\Models\SearchLog::create([
                'keyword' => $query,
                'results_count' => $courseResults->count() + $lectureResults->count(),
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
            ]);
        } catch (\Exception $e) {
            // Fail silently
        }

        // Format course results
        $coursesData = $courseResults->map(function($course) use ($query) {
            return [
                'id' => $course->id,
                'name' => $this->highlightSearchTerm($course->name, $query),
                'type' => 'course',
                'url' => route('course.detail', ['slug' => $course->slug ?? $course->id])
            ];
        });

        // Format lecture results
        $lecturesData = $lectureResults->map(function($lecture) use ($query) {
            // Check if lecture is standalone or part of a course
            if ($lecture->course) {
                // Course lecture: use course slug + lecture slug
                $url = route('lecture.detail', [
                    'course' => $lecture->course->slug ?? $lecture->course->id,
                    'lecture' => $lecture->slug ?? $lecture->id
                ]);
            } else {
                // Standalone lecture: use just lecture slug
                $url = route('lecture.standalone', [
                    'lecture' => $lecture->slug ?? $lecture->id
                ]);
            }

            return [
                'id' => $lecture->id,
                'name' => $this->highlightSearchTerm($lecture->name, $query),
                'type' => 'lecture',
                'url' => $url
            ];
        });

        return response()->json([
            'courses' => $coursesData,
            'lectures' => $lecturesData
        ]);
    }

    /**
     * Highlight the search term in the text
     *
     * @param string $text
     * @param string $searchTerm
     * @return string
     */
    private function highlightSearchTerm($text, $searchTerm)
    {
        $pattern = '/(' . preg_quote($searchTerm, '/') . ')/i';
        return preg_replace($pattern, '<span class="bg-warning text-dark">$1</span>', $text);
    }
}
