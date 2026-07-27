<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin,Super Admin']);
    }

    /**
     * Display a listing of the coupons.
     */
    public function index(Request $request)
    {
        // 1. Statistics counts
        $totalCouponsCount = Coupon::count();
        $activeCouponsCount = Coupon::where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->count();
        $scheduledCouponsCount = Coupon::where('valid_from', '>', now())->count();
        $expiredCouponsCount = Coupon::where('valid_until', '<', now())->count();
        
        // Usage this month from valid orders
        $usageThisMonthCount = Order::whereNotNull('coupon_code')
            ->where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        // 2. Base query for filters
        $query = Coupon::query();

        // Search by coupon code or name (description)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Status dropdown filter
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'active') {
                $query->where('is_active', true)
                      ->where('valid_from', '<=', now())
                      ->where('valid_until', '>=', now());
            } elseif ($status === 'scheduled') {
                $query->where('valid_from', '>', now());
            } elseif ($status === 'expired') {
                $query->where('valid_until', '<', now());
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Type dropdown filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'expiring_soon':
                $query->orderBy('valid_until', 'asc');
                break;
            case 'most_used':
                $query->orderBy('uses_count', 'desc');
                break;
            case 'least_used':
                $query->orderBy('uses_count', 'asc');
                break;
            case 'highest_discount':
                $query->orderBy('value', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $perPage = $request->input('per_page', 10);
        $coupons = $query->paginate($perPage)->withQueryString();

        // Load items for the select selectors
        $products = Course::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();

        return view('admin.coupons.index', compact(
            'coupons',
            'products',
            'categories',
            'totalCouponsCount',
            'activeCouponsCount',
            'scheduledCouponsCount',
            'expiredCouponsCount',
            'usageThisMonthCount'
        ));
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:percentage,fixed,free',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'description' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
            'new_customers_only' => 'sometimes|boolean',
            'free_shipping' => 'sometimes|boolean',
            'selected_products' => 'nullable|array',
            'selected_categories' => 'nullable|array',
            'excluded_products' => 'nullable|array',
            'excluded_categories' => 'nullable|array',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->has('is_active');
        $validated['new_customers_only'] = $request->has('new_customers_only');
        $validated['free_shipping'] = $request->has('free_shipping');
        $validated['uses_count'] = 0;

        // Serialize constraints arrays
        $validated['selected_products'] = $request->input('selected_products', []);
        $validated['selected_categories'] = $request->input('selected_categories', []);
        $validated['excluded_products'] = $request->input('excluded_products', []);
        $validated['excluded_categories'] = $request->input('excluded_categories', []);

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percentage,fixed,free',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'description' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
            'new_customers_only' => 'sometimes|boolean',
            'free_shipping' => 'sometimes|boolean',
            'selected_products' => 'nullable|array',
            'selected_categories' => 'nullable|array',
            'excluded_products' => 'nullable|array',
            'excluded_categories' => 'nullable|array',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['is_active'] = $request->has('is_active');
        $validated['new_customers_only'] = $request->has('new_customers_only');
        $validated['free_shipping'] = $request->has('free_shipping');

        $validated['selected_products'] = $request->input('selected_products', []);
        $validated['selected_categories'] = $request->input('selected_categories', []);
        $validated['excluded_products'] = $request->input('excluded_products', []);
        $validated['excluded_categories'] = $request->input('excluded_categories', []);

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    /**
     * Duplicate existing coupon.
     */
    public function duplicate(Coupon $coupon)
    {
        $newCoupon = $coupon->replicate();
        
        // Ensure code remains unique
        $baseCode = $coupon->code;
        $counter = 1;
        $newCode = $baseCode . '_COPY' . $counter;
        while (Coupon::where('code', $newCode)->exists()) {
            $counter++;
            $newCode = $baseCode . '_COPY' . $counter;
        }

        $newCoupon->code = $newCode;
        $newCoupon->uses_count = 0;
        $newCoupon->save();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon duplicated successfully as ' . $newCode);
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(Request $request, Coupon $coupon)
    {
        $coupon->update([
            'is_active' => !$coupon->is_active
        ]);

        return back()->with('success', 'Coupon status updated successfully.');
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }
}
