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
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please sign in to write a review.'
            ], 401);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|between:1,5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|max:2000',
        ], [
            'rating.required' => 'Please select a star rating between 1 and 5.',
            'comment.required' => 'Please enter your review comment.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if course exists
        $course = $courseId instanceof Course ? $courseId : Course::find($courseId);
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $user = Auth::user();

        // Check verified purchase eligibility from orders table
        $hasPurchased = \App\Models\Order::where('user_id', $user->id)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->where(function($q) use ($course) {
                $q->where('cart_items', 'like', '%"course_id":' . $course->id . '%')
                  ->orWhere('cart_items', 'like', '%"course_id": ' . $course->id . '%')
                  ->orWhere('cart_items', 'like', '%"id":' . $course->id . '%')
                  ->orWhere('cart_items', 'like', '%"id": ' . $course->id . '%')
                  ->orWhere('cart_items', 'like', '%"id":"' . $course->slug . '"%')
                  ->orWhere('cart_items', 'like', '%"slug":"' . $course->slug . '"%');
            })
            ->exists();

        $isAdmin = $user->isAdmin() || $user->isSuperAdmin() || $user->hasRole(['Admin', 'Super Admin']);

        if (!$hasPurchased && !$isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Only verified purchasers of this product can submit a review.'
            ], 403);
        }

        // Check if user already submitted a rating for this product
        $existingRating = $course->ratings()
            ->where('user_id', $user->id)
            ->first();

        if ($existingRating) {
            $existingRating->update([
                'reviewer_name' => $user->name,
                'rating' => (int) $request->rating,
                'title' => $request->title ?: null,
                'comment' => $request->comment,
                'status' => 'approved',
                'is_approved' => true,
                'show_publicly' => true,
                'is_verified_purchase' => $hasPurchased,
            ]);
            $rating = $existingRating;
            $message = 'Your review has been updated and published successfully!';
        } else {
            $rating = $course->ratings()->create([
                'user_id' => $user->id,
                'reviewer_name' => $user->name,
                'rating' => (int) $request->rating,
                'title' => $request->title ?: null,
                'comment' => $request->comment,
                'status' => 'approved',
                'is_approved' => true,
                'show_publicly' => true,
                'is_verified_purchase' => $hasPurchased,
            ]);
            $message = 'Thank you! Your review has been published successfully.';
        }

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
