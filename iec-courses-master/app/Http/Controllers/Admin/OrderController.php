<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Apply Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $cleanSearch = $search;
                if (preg_match('/#GT-(\d+)/i', $search, $matches)) {
                    $cleanSearch = intval($matches[1]);
                }
                
                $q->where('id', $cleanSearch)
                  ->orWhere('billing_address->first_name', 'like', '%' . $search . '%')
                  ->orWhere('billing_address->last_name', 'like', '%' . $search . '%')
                  ->orWhere('billing_address->email', 'like', '%' . $search . '%')
                  ->orWhere('billing_address->phone', 'like', '%' . $search . '%')
                  ->orWhere('billing_address->city', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%');
                  });
            });
        }

        // Apply Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $statusVal = $request->status;
            if ($statusVal === 'processing') {
                $query->where('status', 'paid');
            } elseif ($statusVal === 'delivered') {
                $query->where('status', 'completed');
            } elseif ($statusVal === 'cancelled') {
                $query->whereIn('status', ['rejected', 'cancelled']);
            } else {
                $query->where('status', $statusVal);
            }
        }

        // Apply Payment Method Filter
        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }

        // Apply Date Range Filter
        if ($request->filled('date_range')) {
            $parts = explode(' - ', $request->date_range);
            if (count($parts) === 2) {
                try {
                    $startDate = Carbon::parse($parts[0])->startOfDay();
                    $endDate = Carbon::parse($parts[1])->endOfDay();
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                } catch (\Exception $e) {}
            }
        }

        // Apply Sorting
        $sort = $request->get('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'total_high') {
            $query->orderBy('final_total', 'desc');
        } elseif ($sort === 'total_low') {
            $query->orderBy('final_total', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Get count for each tab status dynamically
        $tabCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'paid')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::whereIn('status', ['rejected', 'cancelled'])->count(),
        ];

        // Stats Row Data (6 statistics cards)
        $stats = [
            'total' => $this->getStatsData(),
            'pending' => $this->getStatsData('pending'),
            'processing' => $this->getStatsData('paid'),
            'shipped' => $this->getStatsData('shipped'),
            'delivered' => $this->getStatsData('completed'),
            'cancelled' => $this->getStatsData('rejected'),
        ];

        // Paginate
        $perPage = intval($request->get('per_page', 10));
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }
        $orders = $query->paginate($perPage)->withQueryString();

        $users = User::all();
        $courses = Course::all();

        return view('admin.orders.index', compact('orders', 'tabCounts', 'stats', 'users', 'courses'));
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        $cartItems = json_decode($order->cart_items, true);
        $items = [];

        if (is_array($cartItems)) {
            foreach ($cartItems as $item) {
                if (isset($item['course_id'])) {
                    $course = Course::find($item['course_id']);
                    if ($course) {
                        $items[] = [
                            'type' => 'Course',
                            'name' => $course->name,
                            'price' => $item['price'] ?? 0,
                        ];
                    }
                } elseif (isset($item['lecture_id'])) {
                    $lecture = Lecture::find($item['lecture_id']);
                    if ($lecture) {
                        $items[] = [
                            'type' => 'Lecture',
                            'name' => $lecture->lecture_title,
                            'course' => $lecture->course ? $lecture->course->name : 'Standalone Lecture',
                            'price' => $item['price'] ?? 0,
                        ];
                    }
                }
            }
        }

        $billingAddress = json_decode($order->billing_address, true);

        return view('admin.orders.show', compact('order', 'items', 'billingAddress'));
    }

    /**
     * Approve an order payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, Order $order)
    {
        $oldStatus = $order->status;
        $order->update(['status' => 'paid']);
        $this->processStatusTransition($order, $oldStatus, 'paid');

        return redirect()->route('admin.orders')
            ->with('success', 'Order #' . $order->id . ' has been approved successfully.');
    }

    /**
     * Reject an order payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, Order $order)
    {
        $order->update(['status' => 'rejected']);

        if ($request->has('reason')) {
            $order->update(['rejection_reason' => $request->reason]);
        }

        return redirect()->route('admin.orders')
            ->with('success', 'Order #' . $order->id . ' has been rejected.');
    }

    /**
     * Update order status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,rejected',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->update(['status' => $newStatus]);
        $this->processStatusTransition($order, $oldStatus, $newStatus);

        return back()->with('success', 'Order #' . $order->id . ' status has been updated to ' . ucfirst($newStatus) . '.');
    }

    /**
     * Helper to process accounting & course permissions when an order gets approved.
     */
    private function processStatusTransition(Order $order, $oldStatus, $newStatus)
    {
        $approvedStatuses = ['paid', 'shipped', 'completed'];
        $wasApprovedBefore = in_array($oldStatus, $approvedStatuses);
        $isApprovedNow = in_array($newStatus, $approvedStatuses);

        if ($isApprovedNow && !$wasApprovedBefore) {
            if (Schema::hasTable('account_transactions') && class_exists(\App\Models\AccountTransaction::class)) {
                $exists = \App\Models\AccountTransaction::where('order_id', $order->id)->exists();
                if (!$exists) {
                    \App\Models\AccountTransaction::create([
                        'order_id' => $order->id,
                        'transaction_type' => 'payment_received',
                        'payment_method' => $order->payment_method ?? 'Cash',
                        'amount' => $order->final_total,
                        'status' => 'completed',
                        'description' => 'Payment for Order #' . $order->id,
                    ]);
                }
            }

            if (Schema::hasTable('account_balances') && class_exists(\App\Models\AccountBalance::class)) {
                $method = strtolower($order->payment_method ?? 'cash');
                $accountName = 'main';

                if (str_contains($method, 'cash')) {
                    $accountName = 'cash';
                } elseif (str_contains($method, 'easypaisa')) {
                    $accountName = 'easypaisa';
                } elseif (str_contains($method, 'bank')) {
                    $accountName = 'bank_account_1';
                }

                $balance = \App\Models\AccountBalance::firstOrCreate(
                    ['account_name' => $accountName],
                    ['balance' => 0, 'total_received' => 0, 'total_used' => 0, 'total_transferred' => 0]
                );

                $balance->increment('balance', $order->final_total);
                $balance->increment('total_received', $order->final_total);
            }

            if (Schema::hasTable('user_courses') && class_exists(\App\Models\UserCourse::class)) {
                $userId = $order->user_id;
                $cartItems = json_decode($order->cart_items, true);

                if (is_array($cartItems)) {
                    foreach ($cartItems as $item) {
                        if (isset($item['course_id'])) {
                            $existingAccess = \App\Models\UserCourse::where([
                                'user_id' => $userId,
                                'course_id' => $item['course_id'],
                                'lecture_id' => null,
                                'status' => 'active'
                            ])->first();

                            if (!$existingAccess) {
                                \App\Models\UserCourse::create([
                                    'user_id' => $userId,
                                    'course_id' => $item['course_id'],
                                    'lecture_id' => null,
                                    'status' => 'active',
                                    'order_id' => $order->id
                                ]);
                            }
                        } elseif (isset($item['lecture_id'])) {
                            $lecture = \App\Models\Lecture::find($item['lecture_id']);

                            if ($lecture) {
                                $existingAccessQuery = \App\Models\UserCourse::where([
                                    'user_id' => $userId,
                                    'lecture_id' => $item['lecture_id'],
                                    'status' => 'active'
                                ]);

                                if ($lecture->course_id) {
                                    $existingAccessQuery->where('course_id', $lecture->course_id);
                                } else {
                                    $existingAccessQuery->whereNull('course_id');
                                }

                                $existingAccess = $existingAccessQuery->first();

                                if (!$existingAccess) {
                                    \App\Models\UserCourse::create([
                                        'user_id' => $userId,
                                        'course_id' => $lecture->course_id,
                                        'lecture_id' => $item['lecture_id'],
                                        'status' => 'active',
                                        'order_id' => $order->id
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Helper to get stats and growth percentage.
     */
    private function getStatsData($status = null)
    {
        $now = Carbon::now();
        
        $queryThisWeek = Order::whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
        $queryLastWeek = Order::whereBetween('created_at', [$now->copy()->subWeek()->startOfWeek(), $now->copy()->subWeek()->endOfWeek()]);
        
        $queryTotal = Order::query();
        
        if ($status) {
            $queryThisWeek->where('status', $status);
            $queryLastWeek->where('status', $status);
            $queryTotal->where('status', $status);
        }
        
        $totalCount = $queryTotal->count();
        $thisWeekCount = $queryThisWeek->count();
        $lastWeekCount = $queryLastWeek->count();
        
        if ($lastWeekCount > 0) {
            $growth = (($thisWeekCount - $lastWeekCount) / $lastWeekCount) * 100;
        } else {
            $growth = $thisWeekCount > 0 ? 100.0 : 0.0;
        }
        
        return [
            'count' => $totalCount,
            'growth' => abs(round($growth, 1)),
            'is_up' => $growth >= 0
        ];
    }

    /**
     * Export orders to CSV.
     */
    public function export(Request $request)
    {
        $query = Order::with('user');
        
        if ($request->filled('status') && $request->status !== 'all') {
            $statusVal = $request->status;
            if ($statusVal === 'processing') {
                $query->where('status', 'paid');
            } elseif ($statusVal === 'delivered') {
                $query->where('status', 'completed');
            } elseif ($statusVal === 'cancelled') {
                $query->whereIn('status', ['rejected', 'cancelled']);
            } else {
                $query->where('status', $statusVal);
            }
        }
        if ($request->filled('payment_method') && $request->payment_method !== 'all') {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $cleanSearch = $search;
                if (preg_match('/#GT-(\d+)/i', $search, $matches)) {
                    $cleanSearch = intval($matches[1]);
                }
                
                $q->where('id', $cleanSearch)
                  ->orWhere('billing_address->first_name', 'like', '%' . $search . '%')
                  ->orWhere('billing_address->last_name', 'like', '%' . $search . '%')
                  ->orWhere('billing_address->email', 'like', '%' . $search . '%')
                  ->orWhere('billing_address->phone', 'like', '%' . $search . '%')
                  ->orWhere('billing_address->city', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('phone', 'like', '%' . $search . '%');
                  });
            });
        }
        if ($request->filled('date_range')) {
            $parts = explode(' - ', $request->date_range);
            if (count($parts) === 2) {
                try {
                    $startDate = Carbon::parse($parts[0])->startOfDay();
                    $endDate = Carbon::parse($parts[1])->endOfDay();
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                } catch (\Exception $e) {}
            }
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders_export_' . now()->format('Ymd_His') . '.csv"',
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order ID', 'Customer Name', 'Customer Email', 'Date', 'Payment Method', 'Total (PKR)', 'Discount (PKR)', 'Final Total (PKR)', 'Status']);
            
            foreach ($orders as $order) {
                $billing = json_decode($order->billing_address, true) ?? [];
                $custName = (isset($billing['first_name']) && trim($billing['first_name']) !== '') ? ($billing['first_name'] . ' ' . ($billing['last_name'] ?? '')) : ($order->user ? $order->user->name : 'Guest');
                $custEmail = $order->user ? $order->user->email : ($billing['email'] ?? '');
                
                fputcsv($file, [
                    '#GT-' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                    $custName,
                    $custEmail,
                    $order->created_at ? $order->created_at->format('Y-m-d H:i') : '',
                    $order->payment_method,
                    $order->total,
                    $order->discount ?? 0,
                    $order->final_total,
                    $order->status
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import orders from CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|max:5000',
        ]);

        $file = $request->file('csv_file');
        $filePath = $file->getRealPath();
        
        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle);
        
        if (!$header || count($header) < 5) {
            return redirect()->route('admin.orders')->with('error', 'Invalid CSV header format.');
        }

        $errors = [];
        $successCount = 0;
        $rowNum = 1;
        
        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            if (count($row) < count($header)) {
                // Pad short rows
                $row = array_pad($row, count($header), '');
            }
            
            $data = array_combine(array_slice($header, 0, count($row)), $row);
            
            $validator = Validator::make($data, [
                'customer_email' => 'required|email',
                'course_id' => 'required|exists:products,id',
                'total' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'payment_method' => 'required|string',
                'status' => 'required|in:pending,paid,shipped,completed,rejected',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'phone' => 'required|string',
                'address' => 'required|string',
                'city' => 'required|string',
            ]);
            
            if ($validator->fails()) {
                $errors[] = "Row {$rowNum}: " . implode(' ', $validator->errors()->all());
                continue;
            }
            
            $user = User::where('email', $data['customer_email'])->first();
            if (!$user) {
                $user = User::create([
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                    'email' => $data['customer_email'],
                    'phone' => $data['phone'],
                    'password' => bcrypt('Ghousia123!'),
                ]);
            }
            
            $course = Course::findOrFail($data['course_id']);
            $cartItems = collect([[
                'course_id' => $course->id,
                'price' => $data['total'],
            ]]);
            
            $finalTotal = floatval($data['total']) - floatval($data['discount'] ?? 0);
            
            $order = Order::create([
                'user_id' => $user->id,
                'cart_items' => json_encode($cartItems),
                'total' => $data['total'],
                'discount' => $data['discount'] ?? 0,
                'final_total' => $finalTotal,
                'status' => $data['status'],
                'payment_method' => $data['payment_method'],
                'billing_address' => json_encode([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['customer_email'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'country' => 'Pakistan',
                ]),
            ]);
            
            $this->processStatusTransition($order, 'pending', $data['status']);
            $successCount++;
        }
        
        fclose($handle);
        
        if (count($errors) > 0) {
            return redirect()->route('admin.orders')
                ->with('error', 'Import completed with errors. ' . $successCount . ' orders imported. Errors: ' . implode(' | ', array_slice($errors, 0, 5)));
        }
        
        return redirect()->route('admin.orders')
            ->with('success', "Successfully imported {$successCount} orders.");
    }

    /**
     * Store a manually created order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:products,id',
            'payment_method' => 'required|string',
            'status' => 'required|string',
            'total' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
        ]);

        $course = Course::findOrFail($request->course_id);
        $cartItems = collect([[
            'course_id' => $course->id,
            'price' => $request->total,
        ]]);

        $finalTotal = $request->total - ($request->discount ?? 0);

        $order = Order::create([
            'user_id' => $request->user_id,
            'cart_items' => json_encode($cartItems),
            'total' => $request->total,
            'discount' => $request->discount ?? 0,
            'final_total' => $finalTotal,
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'billing_address' => json_encode([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'country' => 'Pakistan',
            ]),
        ]);

        $this->processStatusTransition($order, 'pending', $request->status);

        return redirect()->route('admin.orders')->with('success', 'Order #' . $order->id . ' has been created successfully.');
    }

    /**
     * Duplicate an order.
     */
    public function duplicate(Order $order)
    {
        $newOrder = $order->replicate();
        $newOrder->created_at = now();
        $newOrder->updated_at = now();
        $newOrder->save();

        return redirect()->route('admin.orders')
            ->with('success', 'Order #' . $order->id . ' duplicated successfully as Order #' . $newOrder->id . '.');
    }

    /**
     * Delete an order.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders')
            ->with('success', 'Order #' . $order->id . ' has been deleted successfully.');
    }
}
