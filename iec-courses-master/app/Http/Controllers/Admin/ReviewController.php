<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin,Super Admin']);
    }

    /**
     * Display a listing of reviews.
     */
    public function index(Request $request)
    {
        // 1. Base query including product (rateable) and customer (user) relations
        $baseQuery = Rating::with(['rateable', 'user']);

        // 2. Statistics counts (always evaluated before search query filters)
        $totalReviewsCount = (clone $baseQuery)->count();
        $approvedReviewsCount = (clone $baseQuery)->where('status', 'approved')->count();
        $pendingReviewsCount = (clone $baseQuery)->where('status', 'pending')->count();
        $rejectedReviewsCount = (clone $baseQuery)->where('status', 'rejected')->count();
        $averageRating = (clone $baseQuery)->where('status', 'approved')->avg('rating') ?: 0.0;

        // 3. Filter and Search Queries
        $query = clone $baseQuery;

        // Search: product name, reviewer name, reviewer email, comment text
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('comment', 'like', '%' . $search . '%')
                  ->orWhere('reviewer_name', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', '%' . $search . '%')
                         ->orWhere('email', 'like', '%' . $search . '%');
                  })
                  ->orWhereHasMorph('rateable', [Course::class], function($mq) use ($search) {
                      $mq->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Tab filter (Status tab: all, approved, pending, rejected)
        $tab = $request->input('status_tab', 'all');
        if (in_array($tab, ['approved', 'pending', 'rejected'])) {
            $query->where('status', $tab);
        }

        // Rating Filter (1 to 5 Stars dropdown)
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'highest_rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'lowest_rating':
                $query->orderBy('rating', 'asc');
                break;
            case 'recently_updated':
                $query->orderBy('updated_at', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $perPage = $request->input('per_page', 10);
        $reviews = $query->paginate($perPage)->withQueryString();

        // 4. Products and users for manual reviews form
        $products = Course::orderBy('name', 'asc')->get();
        $users = User::whereDoesntHave('roles', function($q) {
            $q->whereIn('name', ['Admin', 'Super Admin']);
        })->orderBy('name', 'asc')->get();

        return view('admin.reviews.index', compact(
            'reviews',
            'products',
            'users',
            'totalReviewsCount',
            'approvedReviewsCount',
            'pendingReviewsCount',
            'rejectedReviewsCount',
            'averageRating',
            'tab'
        ));
    }

    /**
     * Store new manual review.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'nullable|exists:users,id',
            'reviewer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'status' => 'required|string|in:approved,pending,rejected',
            'moderation_note' => 'nullable|string',
        ]);

        // Resolve rateable type & id
        $course = Course::findOrFail($validated['product_id']);

        // Check if matching user exists
        $userId = $validated['user_id'];
        if (!$userId) {
            $user = User::where('email', strtolower(trim($validated['email'])))->first();
            if ($user) {
                $userId = $user->id;
            }
        }

        Rating::create([
            'user_id' => $userId,
            'reviewer_name' => $validated['reviewer_name'],
            'rateable_type' => Course::class,
            'rateable_id' => $course->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'status' => $validated['status'],
            'is_approved' => $validated['status'] === 'approved',
            'show_publicly' => $validated['status'] === 'approved',
            'moderation_note' => $validated['moderation_note'] ?? null,
        ]);

        return redirect()->route('admin.reviews.index')->with('success', 'Review added manually successfully.');
    }

    /**
     * Update review.
     */
    public function update(Request $request, Rating $rating)
    {
        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'status' => 'required|string|in:approved,pending,rejected',
            'moderation_note' => 'nullable|string',
        ]);

        $rating->update([
            'reviewer_name' => $validated['reviewer_name'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'status' => $validated['status'],
            'is_approved' => $validated['status'] === 'approved',
            'show_publicly' => $validated['status'] === 'approved',
            'moderation_note' => $validated['moderation_note'] ?? null,
        ]);

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully.');
    }

    /**
     * Approve review.
     */
    public function approve(Rating $rating)
    {
        $rating->update([
            'status' => 'approved',
            'is_approved' => true,
            'show_publicly' => true,
        ]);

        return back()->with('success', 'Review approved successfully.');
    }

    /**
     * Reject review.
     */
    public function reject(Request $request, Rating $rating)
    {
        $rating->update([
            'status' => 'rejected',
            'is_approved' => false,
            'show_publicly' => false,
            'moderation_note' => $request->input('moderation_note') ?? $rating->moderation_note,
        ]);

        return back()->with('success', 'Review rejected successfully.');
    }

    /**
     * Move review to pending.
     */
    public function pending(Rating $rating)
    {
        $rating->update([
            'status' => 'pending',
            'is_approved' => false,
            'show_publicly' => false,
        ]);

        return back()->with('success', 'Review moved to pending state.');
    }

    /**
     * Delete review permanently.
     */
    public function destroy(Rating $rating)
    {
        $rating->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }
}
