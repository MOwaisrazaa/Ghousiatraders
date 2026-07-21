<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecture;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Store a new rating for a course
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $courseId
     * @return \Illuminate\Http\Response
     */
    public function storeCourseRating(Request $request, $courseId)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if course exists
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $reviewerName = $request->input('name') ?: (Auth::check() ? null : 'Anonymous');

        // Create new rating
        $rating = $course->ratings()->create([
            'user_id' => Auth::id(),
            'reviewer_name' => $reviewerName,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true,
            'show_publicly' => true,
        ]);
        
        $message = 'Rating submitted successfully';

        return response()->json([
            'success' => true,
            'message' => $message,
            'rating' => $rating,
            'average_rating' => $course->average_rating,
            'rating_count' => $course->rating_count
        ]);
    }

    /**
     * Store a new rating for a lecture
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $lectureId
     * @return \Illuminate\Http\Response
     */
    public function storeLectureRating(Request $request, $lectureId)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if lecture exists
        $lecture = Lecture::find($lectureId);
        if (!$lecture) {
            return response()->json([
                'success' => false,
                'message' => 'Lecture not found'
            ], 404);
        }

        // Check if user has already rated this lecture
        $existingRating = $lecture->ratings()
            ->where('user_id', Auth::id())
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            $rating = $existingRating;
            $message = 'Rating updated successfully';
        } else {
            // Create new rating
            $rating = $lecture->ratings()->create([
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            
            $message = 'Rating submitted successfully';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'rating' => $rating,
            'average_rating' => $lecture->average_rating,
            'rating_count' => $lecture->rating_count
        ]);
    }

    /**
     * Get ratings for a specific course
     *
     * @param int $courseId
     * @return \Illuminate\Http\Response
     */
    public function getCourseRatings($courseId)
    {
        $course = Course::findOrFail($courseId);
        
        $ratings = $course->ratings()
            ->where('is_approved', true)
            ->where('show_publicly', true)
            ->with('user:id,name,email')
            ->latest()
            ->get();
            
        return response()->json([
            'success' => true,
            'average_rating' => $course->average_rating,
            'rating_count' => $course->rating_count,
            'ratings' => $ratings
        ]);
    }

    /**
     * Get ratings for a specific lecture
     *
     * @param int $lectureId
     * @return \Illuminate\Http\Response
     */
    public function getLectureRatings($lectureId)
    {
        $lecture = Lecture::findOrFail($lectureId);
        
        $ratings = $lecture->ratings()
            ->where('is_approved', true)
            ->where('show_publicly', true)
            ->with('user:id,name,email')
            ->latest()
            ->get();
            
        return response()->json([
            'success' => true,
            'average_rating' => $lecture->average_rating,
            'rating_count' => $lecture->rating_count,
            'ratings' => $ratings
        ]);
    }
}
