<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin,Super Admin']);
    }

    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        // 1. Base query for customers (excluding Admins and Super Admins)
        $baseQuery = User::whereDoesntHave('roles', function($q) {
            $q->whereIn('name', ['Admin', 'Super Admin']);
        });

        // 2. Statistics counts (always calculated before filter query matches)
        $totalCustomersCount = (clone $baseQuery)->count();
        $newCustomersCount = (clone $baseQuery)->where('created_at', '>=', now()->subDays(30))->count();
        $activeCustomersCount = (clone $baseQuery)->where('status', 'active')->count();
        
        // Repeat customers: customers with more than 1 valid (non-cancelled) order
        $repeatCustomersCount = (clone $baseQuery)->whereHas('orders', function($q) {
            $q->where('status', '!=', 'cancelled');
        }, '>', 1)->count();
        
        $inactiveCustomersCount = (clone $baseQuery)->where('status', 'inactive')->count();

        // 3. Filter and Sort Queries
        $query = clone $baseQuery;

        // Search keyword: name, email, phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Group Filter
        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        // Eager count valid orders and sum spent inside DB to avoid N+1
        $query->withCount(['orders as valid_orders_count' => function($q) {
            $q->where('status', '!=', 'cancelled');
        }]);

        $query->selectSub(function($q) {
            $q->from('orders')
              ->selectRaw('COALESCE(SUM(final_total), 0)')
              ->whereColumn('user_id', 'users.id')
              ->where('status', '!=', 'cancelled');
        }, 'total_spent');

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'most_orders':
                $query->orderBy('valid_orders_count', 'desc');
                break;
            case 'highest_spending':
                $query->orderBy('total_spent', 'desc');
                break;
            case 'lowest_spending':
                $query->orderBy('total_spent', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $perPage = $request->input('per_page', 10);
        $customers = $query->paginate($perPage)->withQueryString();

        // 4. Dynamically sync group classification on load
        foreach ($customers as $user) {
            $isNew = $user->created_at->gt(now()->subDays(30));
            $ordersCount = $user->valid_orders_count;
            $spent = $user->total_spent;

            $computedGroup = 'regular';
            if ($spent >= 20000 || $ordersCount >= 5) {
                $computedGroup = 'vip';
            } elseif ($isNew && $ordersCount <= 1) {
                $computedGroup = 'new';
            }

            if ($user->group !== $computedGroup) {
                $user->update(['group' => $computedGroup]);
            }
        }

        return view('admin.customers.index', compact(
            'customers',
            'totalCustomersCount',
            'newCustomersCount',
            'activeCustomersCount',
            'repeatCustomersCount',
            'inactiveCustomersCount'
        ));
    }

    /**
     * Store new customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6',
            'status' => 'required|string|in:active,inactive',
            'group' => 'required|string|in:regular,vip,new',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['email'] = strtolower(trim($validated['email']));
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully.');
    }

    /**
     * Update customer.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'status' => 'required|string|in:active,inactive',
            'group' => 'required|string|in:regular,vip,new',
            'shipping_address' => 'nullable|string',
            'billing_address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['email'] = strtolower(trim($validated['email']));

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Delete customer (or deactivate if has active order records).
     */
    public function destroy(User $user)
    {
        $ordersCount = Order::where('user_id', $user->id)->count();

        if ($ordersCount > 0) {
            // Preserve history: switch status to Inactive instead of deleting
            $user->update(['status' => 'inactive']);
            return redirect()->route('admin.customers.index')
                ->with('success', 'Customer has active orders in history. Account deactivated/set to Inactive instead of deletion to preserve records.');
        }

        $user->delete();

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }

    /**
     * Fast toggle status.
     */
    public function toggleStatus(Request $request, User $user)
    {
        $status = $request->input('status');
        if (!in_array($status, ['active', 'inactive'])) {
            return back()->with('error', 'Invalid status selection.');
        }

        $user->update(['status' => $status]);

        return back()->with('success', 'Customer status updated successfully.');
    }

    /**
     * Export customer details to CSV (excludes sensitive tokens / password hashes).
     */
    public function export(Request $request)
    {
        $query = User::whereDoesntHave('roles', function($q) {
            $q->whereIn('name', ['Admin', 'Super Admin']);
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        $customers = $query->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customers_export_' . now()->format('Ymd_His') . '.csv"',
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Customer ID', 'Full Name', 'Email', 'Phone', 'Status', 'Group', 'Shipping Address', 'Billing Address', 'Notes', 'Joined On']);

            foreach ($customers as $c) {
                fputcsv($file, [
                    $c->id,
                    $c->name,
                    $c->email,
                    $c->phone,
                    $c->status,
                    $c->group,
                    $c->shipping_address,
                    $c->billing_address,
                    $c->notes,
                    $c->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
