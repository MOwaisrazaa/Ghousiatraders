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
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'products' => Course::count(),
            'categories' => Schema::hasTable('categories') ? Category::count() : 0,
            'orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('final_total'),
            'users' => User::count(),
            'coupons' => Coupon::count(),
            'payment_methods' => Schema::hasTable('payment_methods') ? PaymentMethod::count() : 0,
            'footer_settings' => Schema::hasTable('footer_settings') ? FooterSetting::count() : 0,
        ];

        return view('admin.dashboard', compact('stats'));
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
