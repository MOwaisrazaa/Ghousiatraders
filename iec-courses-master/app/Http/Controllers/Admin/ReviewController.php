<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        // Get all reviews with their product (rateable) and user relations
        $reviews = Rating::with(['rateable', 'user'])
            ->latest('id')
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleStatus(Rating $rating)
    {
        // Toggle visibility and approval status
        $newStatus = !$rating->show_publicly;
        
        $rating->update([
            'show_publicly' => $newStatus,
            'is_approved' => $newStatus
        ]);

        $message = $newStatus ? 'Review approved and published.' : 'Review hidden from public view.';
        return back()->with('success', $message);
    }

    public function destroy(Rating $rating)
    {
        $rating->delete();
        return back()->with('success', 'Review deleted successfully.');
    }
}
