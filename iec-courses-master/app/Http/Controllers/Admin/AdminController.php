<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\User;
use App\Models\Role;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\FooterSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin,Super Admin']);
    }

    /**
     * Admin dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $searchQuery = trim($request->input('search', ''));
        $searchResults = null;
        if ($searchQuery !== '') {
            $searchResults = [
                'products' => Course::where('name', 'like', "%{$searchQuery}%")->take(5)->get(),
                'orders' => Order::where('id', 'like', "%{$searchQuery}%")
                                 ->orWhere('cart_items', 'like', "%{$searchQuery}%")
                                 ->take(5)->get(),
                'customers' => User::where('name', 'like', "%{$searchQuery}%")
                                   ->orWhere('email', 'like', "%{$searchQuery}%")
                                   ->take(5)->get(),
            ];
        }

        $startDateInput = $request->input('start_date', '');
        $endDateInput = $request->input('end_date', '');

        $hasDateFilter = !empty($startDateInput) && !empty($endDateInput);
        $startDate = null;
        $endDate = null;

        if ($hasDateFilter) {
            try {
                $startDate = \Carbon\Carbon::parse($startDateInput)->startOfDay();
                $endDate = \Carbon\Carbon::parse($endDateInput)->endOfDay();
            } catch (\Exception $e) {
                $hasDateFilter = false;
            }
        }

        // DB stats from actual valid storefront orders
        $salesQuery = Order::where('status', '!=', 'cancelled');
        $ordersQuery = Order::where('status', '!=', 'cancelled');

        if ($hasDateFilter && $startDate && $endDate) {
            $salesQuery->whereBetween('created_at', [$startDate, $endDate]);
            $ordersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalSales = (float) $salesQuery->sum('final_total');
        $totalOrders = (int) $ordersQuery->count();
        $totalCustomers = User::whereDoesntHave('roles', function($q) {
            $q->whereIn('name', ['Admin', 'Super Admin']);
        })->count();
        $totalProducts = Course::count();
        
        $avgOrderValue = $totalOrders > 0 ? ($totalSales / $totalOrders) : 0.0;

        // Sales Overview chart data
        $chartLabels = [];
        $chartData = [];

        if ($hasDateFilter && $startDate && $endDate) {
            $current = $startDate->copy();
            while ($current->lte($endDate)) {
                $dayLabel = $current->format('M d');
                $chartLabels[] = $dayLabel;
                
                $daySales = Order::where('status', '!=', 'cancelled')
                    ->whereDate('created_at', $current->format('Y-m-d'))
                    ->sum('final_total');
                $chartData[] = (float) $daySales;
                
                $current->addDay();
            }
        } else {
            // Default 7 days chart (last 7 days including today)
            for ($i = 6; $i >= 0; $i--) {
                $day = \Carbon\Carbon::today()->subDays($i);
                $chartLabels[] = $day->format('M d');
                $daySales = Order::where('status', '!=', 'cancelled')
                    ->whereDate('created_at', $day->format('Y-m-d'))
                    ->sum('final_total');
                $chartData[] = (float) $daySales;
            }
        }

        // Order Status summary counts from real orders
        $statusCountsQuery = Order::query();
        if ($hasDateFilter && $startDate && $endDate) {
            $statusCountsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $deliveredCount = (clone $statusCountsQuery)->whereIn('status', ['completed', 'delivered'])->count();
        $processingCount = (clone $statusCountsQuery)->whereIn('status', ['paid', 'processing'])->count();
        $shippedCount = (clone $statusCountsQuery)->whereIn('status', ['shipped', 'out_for_delivery', 'out for delivery'])->count();
        $pendingCount = (clone $statusCountsQuery)->where('status', 'pending')->count();
        
        $statusTotal = (clone $statusCountsQuery)->count();

        // Recent Orders list (real orders only, no fake fallbacks)
        $recentOrders = Order::latest()->take(5)->get()->map(function($o) {
            $billing = json_decode($o->billing_address, true) ?: [];
            $customerName = trim(($billing['first_name'] ?? '') . ' ' . ($billing['last_name'] ?? ''));
            if (!$customerName && $o->user) {
                $customerName = $o->user->name;
            }
            return [
                'id' => $o->id,
                'customer' => $customerName ?: 'Customer',
                'amount' => $o->final_total,
                'status' => $o->status ?: 'pending',
                'statusLabel' => match($o->status) {
                    'completed', 'delivered' => 'Delivered',
                    'shipped', 'out_for_delivery', 'out for delivery' => 'Shipped',
                    'paid', 'processing' => 'Processing',
                    'pending' => 'Pending',
                    default => 'Cancelled'
                }
            ];
        });

        // Best Selling products calculations (real storefront orders only)
        $productSales = [];
        $validOrders = Order::where('status', '!=', 'cancelled')->get();
        foreach ($validOrders as $o) {
            $items = json_decode($o->cart_items, true) ?: [];
            foreach ($items as $item) {
                $courseId = $item['course_id'] ?? null;
                if ($courseId) {
                    if (!isset($productSales[$courseId])) {
                        $productSales[$courseId] = ['sold' => 0, 'revenue' => 0.0];
                    }
                    $productSales[$courseId]['sold'] += (int)($item['quantity'] ?? 1);
                    $productSales[$courseId]['revenue'] += (float)($item['price'] ?? 0) * (int)($item['quantity'] ?? 1);
                }
            }
        }
        
        $bestSellers = Course::all()->map(function($product) use ($productSales) {
            $sales = $productSales[$product->id] ?? null;
            $product->sold = $sales ? $sales['sold'] : 0;
            $product->revenue = $sales ? $sales['revenue'] : 0.0;
            return $product;
        })->filter(fn($p) => $p->sold > 0)->sortByDesc('revenue')->take(5);

        // Low stock products calculations (real stock column)
        $lowStockProducts = Course::whereNotNull('stock')
            ->where('stock', '<=', 8)
            ->orderBy('stock', 'asc')
            ->take(4)
            ->get();

        return view('admin.dashboard', compact(
            'user', 'totalSales', 'totalOrders', 'totalCustomers', 'totalProducts', 'avgOrderValue',
            'chartLabels', 'chartData', 'deliveredCount', 'processingCount', 'shippedCount', 'pendingCount', 'statusTotal',
            'recentOrders', 'bestSellers', 'lowStockProducts', 'startDateInput', 'endDateInput', 'searchResults', 'searchQuery'
        ));
    }

    /**
     * Manage products
     */
    public function products()
    {
        $products = Course::with('category')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function courses()
    {
        return $this->products();
    }

    /**
     * Manage lectures
     */
    public function lectures()
    {
        if (!Schema::hasTable('lectures')) {
            return redirect()->route('admin.products')->with('error', 'Lectures table is not available in this branch.');
        }

        $lectures = Lecture::with('course')->paginate(10);
        return view('admin.lectures.index', compact('lectures'));
    }

    /**
     * Show lecture details
     */
    public function showLecture(Lecture $lecture)
    {
        if (!Schema::hasTable('lectures')) {
            return redirect()->route('admin.products')->with('error', 'Lectures table is not available in this branch.');
        }

        $lecture->load(['course', 'materials']);
        return view('admin.lectures.show', compact('lecture'));
    }

    /**
     * Show product details
     */
    public function showProduct(Course $product)
    {
        $product->load(['category']);
        return view('admin.products.show', compact('product'));
    }

    public function showCourse(Course $course)
    {
        return $this->showProduct($course);
    }

    /**
     * Manage roles
     */
    public function roles()
    {
        $roles = Role::paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Manage users
     */
    public function users()
    {
        $users = User::with('roles')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Manage coupons
     */
    public function coupons()
    {
        return view('admin.coupons.index');
    }
    /**
     * Manage orders
     */
    public function orders()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Manage quizzes
     */
    public function quizzes()
    {
        $user = Auth::user();

        // If super admin, show all quizzes
        if ($user->isSuperAdmin()) {
            $quizzes = Quiz::with(['lecture.course'])->paginate(10);
        } else {
            // Regular admin only sees stats from their assigned users
            $assignedUserIds = AdminUserAssignment::where('admin_id', $user->id)
                ->pluck('user_id')
                ->toArray();

            // Show quizzes that are related to courses with assigned users
            $quizzes = Quiz::whereHas('lecture.course.users', function($query) use ($assignedUserIds) {
                $query->whereIn('users.id', $assignedUserIds);
            })->with(['lecture.course'])->paginate(10);
        }

        return view('admin.quizzes.index', compact('quizzes'));
    }

    /**
     * Manage instructor profiles
     */
    public function instructorProfiles()
    {
        $instructorProfiles = InstructorProfile::orderBy('name')->paginate(10);
        return view('admin.instructor-profiles.index', compact('instructorProfiles'));
    }

    /**
     * Show instructor profile form for creating a new profile
     */
    public function createInstructorProfile()
    {
        return view('admin.instructor-profiles.create');
    }

    /**
     * Store a new instructor profile
     */
    public function storeInstructorProfile(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'expertise' => 'nullable|string',
            'skills' => 'nullable|string',
            'social_linkedin' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_website' => 'nullable|url',
            'image_path' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image_path')) {
            $validatedData['image_path'] = $request->file('image_path')->store('instructors', 'public');
        }

        InstructorProfile::create($validatedData);

        return redirect()->route('admin.instructor-profiles')
            ->with('success', 'Instructor profile created successfully.');
    }

    /**
     * Show instructor profile form for editing
     */
    public function editInstructorProfile(InstructorProfile $instructorProfile)
    {
        return view('admin.instructor-profiles.edit', compact('instructorProfile'));
    }

    /**
     * Update instructor profile
     */
    public function updateInstructorProfile(Request $request, InstructorProfile $instructorProfile)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'title' => 'nullable|string|max:255',
            'expertise' => 'nullable|string',
            'skills' => 'nullable|string',
            'social_linkedin' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_website' => 'nullable|url',
            'image_path' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image_path')) {
            // Delete old image if exists
            if ($instructorProfile->image_path && Storage::disk('public')->exists($instructorProfile->image_path)) {
                Storage::disk('public')->delete($instructorProfile->image_path);
            }
            $validatedData['image_path'] = $request->file('image_path')->store('instructors', 'public');
        }

        $instructorProfile->update($validatedData);

        return redirect()->route('admin.instructor-profiles')
            ->with('success', 'Instructor profile updated successfully.');
    }

    /**
     * Delete instructor profile
     */
    public function destroyInstructorProfile(InstructorProfile $instructorProfile)
    {
        // Delete image if exists
        if ($instructorProfile->image_path && Storage::disk('public')->exists($instructorProfile->image_path)) {
            Storage::disk('public')->delete($instructorProfile->image_path);
        }

        $instructorProfile->delete();

        return redirect()->route('admin.instructor-profiles')
            ->with('success', 'Instructor profile deleted successfully.');
    }

    /**
     * Manage admin permissions
     */
    public function permissions()
    {
        $user = Auth::user();

        // Make sure only Super Admins can access this page
        if (!$user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Only Super Admins can manage permissions.');
        }

        // Get all admin users except super admins
        $adminRoleId = Role::where('name', 'Admin')->first()->id;
        $admins = User::whereHas('roles', function($query) use ($adminRoleId) {
            $query->where('role_id', $adminRoleId);
        })->get();

        // Available pages for permission management
        $availablePages = [
            'products' => 'Products Management',
            'lectures' => 'Lectures Management',
            'users' => 'Users Management',
            'coupons' => 'Coupons Management',
            'orders' => 'Orders Management',
            'questions' => 'Questions Management',
            'roles' => 'Roles Management',
            'payment_methods' => 'Payment Methods Management',
            'quizzes' => 'Quizzes Management',
            'instructor_profiles' => 'Instructor Profiles Management',
        ];

        return view('admin.permissions.index', compact('admins', 'availablePages'));
    }

    /**
     * Update admin permissions
     */
    public function updatePermissions(Request $request)
    {
        $user = Auth::user();

        // Make sure only Super Admins can update permissions
        if (!$user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Only Super Admins can manage permissions.');
        }

        // Log the incoming request data for debugging
        \Log::info('Permission Update Request', [
            'admin_id' => $request->admin_id,
            'permissions' => $request->permissions,
            'all_data' => $request->all()
        ]);

        $request->validate([
            'admin_id' => 'required|exists:users,id',
            'permissions' => 'required|array',
            'permissions.*' => 'boolean',
        ]);

        $adminId = $request->admin_id;

        // Make sure the target user is an admin (not a super admin)
        $user = User::find($adminId);
        if ($user->isSuperAdmin()) {
            return redirect()->back()->with('error', 'Cannot modify permissions for Super Admin.');
        }

        // Update permissions
        foreach ($request->permissions as $page => $isAllowed) {
            // Cast to boolean to ensure proper value is stored
            $isAllowed = ($isAllowed == '1' || $isAllowed === true || $isAllowed === 'true') ? true : false;

            // Log each permission update
            \Log::info('Updating permission', [
                'admin_id' => $adminId,
                'page' => $page,
                'is_allowed' => $isAllowed,
                'raw_value' => $request->permissions[$page]
            ]);

            AdminPermission::updateOrCreate(
                ['admin_user_id' => $adminId, 'page' => $page],
                ['is_allowed' => $isAllowed]
            );
        }

        // Log all permissions after update
        $updatedPermissions = AdminPermission::where('admin_user_id', $adminId)->get();
        \Log::info('Updated permissions', [
            'admin_id' => $adminId,
            'permissions' => $updatedPermissions->toArray()
        ]);

        return redirect()->back()->with('success', 'Admin permissions updated successfully.');
    }

    /**
     * Debug user roles and permissions
     */
    public function debugUser()
    {
        $user = Auth::user();
        $data = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_super_admin' => $user->isSuperAdmin(),
            'is_admin' => $user->isAdmin(),
            'roles' => $user->roles()->pluck('name')->toArray(),
            'permissions' => $user->permissions()->get()->toArray()
        ];

        return response()->json($data);
    }
}
