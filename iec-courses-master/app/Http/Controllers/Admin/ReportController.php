<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use App\Models\AccountTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.role:Admin,Super Admin']);
    }

    /**
     * Helper to get date range from period selection.
     */
    private function resolveDateRange($period, Request $request)
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        switch ($period) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'last_3_months':
                $startDate = Carbon::now()->subMonths(3)->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
        }

        return [$startDate, $endDate];
    }

    /**
     * Helper to apply database queries with filters.
     */
    private function getFilteredOrdersQuery($startDate, $endDate, $channel, $paymentMethod)
    {
        $query = Order::whereIn('status', ['paid', 'shipped', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($channel && $channel !== 'all') {
            if ($channel === 'facebook') {
                $query->where('billing_address', 'like', '%facebook%');
            } elseif ($channel === 'instagram') {
                $query->where('billing_address', 'like', '%instagram%');
            } elseif ($channel === 'mobile_app') {
                $query->where('billing_address', 'like', '%app%');
            } elseif ($channel === 'website') {
                $query->where('billing_address', 'not like', '%facebook%')
                      ->where('billing_address', 'not like', '%instagram%')
                      ->where('billing_address', 'not like', '%app%');
            }
        }

        if ($paymentMethod && $paymentMethod !== 'all') {
            $query->where('payment_method', $paymentMethod);
        }

        return $query;
    }

    /**
     * Compute statistics for a given range.
     */
    private function computePeriodStats($startDate, $endDate, $channel, $paymentMethod)
    {
        $ordersQuery = $this->getFilteredOrdersQuery($startDate, $endDate, $channel, $paymentMethod);
        
        $totalRevenue = (float) $ordersQuery->sum('final_total');
        $totalOrders = (int) $ordersQuery->count();
        $totalCustomers = (int) $ordersQuery->distinct('user_id')->count('user_id');
        $avgOrderValue = $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0.0;
        
        // Sum refunds
        $refunds = 0.0;
        if (Schema::hasTable('account_transactions')) {
            $refundsQuery = AccountTransaction::where('transaction_type', 'refund');
            if ($paymentMethod && $paymentMethod !== 'all') {
                $refundsQuery->where('payment_method', $paymentMethod);
            }
            $refunds = (float) $refundsQuery->whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        }
        
        // Also add potential refunded orders status
        $refundedOrdersSum = (float) Order::where('status', 'refunded')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('final_total');
        $refunds += $refundedOrdersSum;

        return [
            'revenue' => $totalRevenue,
            'orders' => $totalOrders,
            'customers' => $totalCustomers,
            'aov' => $avgOrderValue,
            'refunds' => $refunds
        ];
    }

    /**
     * Show main reports page.
     */
    public function index(Request $request)
    {
        $period = $request->input('period', 'this_month');
        $channel = $request->input('channel', 'all');
        $paymentMethod = $request->input('payment_method', 'all');

        [$startDate, $endDate] = $this->resolveDateRange($period, $request);

        // Preceding period comparison range
        $diffInDays = $startDate->diffInDays($endDate) ?: 1;
        $compStartDate = $startDate->copy()->subDays($diffInDays + 1);
        $compEndDate = $startDate->copy()->subDay();

        // 1. Calculate Stats
        $currentStats = $this->computePeriodStats($startDate, $endDate, $channel, $paymentMethod);
        $comparisonStats = $this->computePeriodStats($compStartDate, $compEndDate, $channel, $paymentMethod);

        // Growth changes
        $stats = [];
        foreach (['revenue', 'orders', 'customers', 'aov', 'refunds'] as $key) {
            $currentVal = $currentStats[$key];
            $prevVal = $comparisonStats[$key];
            
            $growth = 0;
            if ($prevVal > 0) {
                $growth = (($currentVal - $prevVal) / $prevVal) * 100;
            } elseif ($currentVal > 0) {
                $growth = 100.0;
            }

            $stats[$key] = [
                'value' => $currentVal,
                'growth' => round($growth, 1)
            ];
        }

        // Conversion Rate calculation
        if (Schema::hasTable('traffic_logs')) {
            $visits = \DB::table('traffic_logs')->whereBetween('created_at', [$startDate, $endDate])->count();
            $prevVisits = \DB::table('traffic_logs')->whereBetween('created_at', [$compStartDate, $compEndDate])->count();
            
            $convRate = $visits > 0 ? (($currentStats['orders'] / $visits) * 100) : 0.0;
            $prevConvRate = $prevVisits > 0 ? (($comparisonStats['orders'] / $prevVisits) * 100) : 0.0;
            
            $convGrowth = 0;
            if ($prevConvRate > 0) {
                $convGrowth = (($convRate - $prevConvRate) / $prevConvRate) * 100;
            } elseif ($convRate > 0) {
                $convGrowth = 100.0;
            }

            $stats['conversion_rate'] = [
                'value' => round($convRate, 2) . '%',
                'growth' => round($convGrowth, 1)
            ];
        } else {
            $stats['conversion_rate'] = [
                'value' => 'Not Available',
                'growth' => null
            ];
        }

        // 2. Sales Overview Chart Data (daily points)
        $overviewQuery = $this->getFilteredOrdersQuery($startDate, $endDate, $channel, $paymentMethod);
        
        $chartGrouping = $request->input('chart_grouping', 'daily');
        if ($chartGrouping === 'hourly') {
            $chartData = $overviewQuery->selectRaw("HOUR(created_at) as label, SUM(final_total) as revenue, COUNT(*) as orders_count")
                ->groupBy('label')
                ->orderBy('label')
                ->get();
        } elseif ($chartGrouping === 'weekly') {
            $chartData = $overviewQuery->selectRaw("WEEK(created_at) as label, SUM(final_total) as revenue, COUNT(*) as orders_count")
                ->groupBy('label')
                ->orderBy('label')
                ->get();
        } elseif ($chartGrouping === 'monthly') {
            $chartData = $overviewQuery->selectRaw("MONTHNAME(created_at) as label, SUM(final_total) as revenue, COUNT(*) as orders_count")
                ->groupBy('label')
                ->orderBy('label')
                ->get();
        } else { // default: daily
            $chartData = $overviewQuery->selectRaw("DATE_FORMAT(created_at, '%b %d') as label, SUM(final_total) as revenue, COUNT(*) as orders_count")
                ->groupBy('label')
                ->orderBy('label')
                ->get();
        }

        // 3. Sales by Category
        $orders = $overviewQuery->get();
        $categoryStats = [];
        foreach ($orders as $order) {
            $cartItems = json_decode($order->cart_items, true);
            if (is_array($cartItems)) {
                foreach ($cartItems as $item) {
                    $productId = $item['course_id'] ?? null;
                    $price = $item['price'] ?? 0;
                    $qty = $item['quantity'] ?? 1;

                    if ($productId) {
                        $product = Course::find($productId);
                        if ($product) {
                            $catId = $product->category_id ?? 0;
                            $catName = $product->category ? $product->category->name : 'Uncategorized';
                            
                            if (!isset($categoryStats[$catId])) {
                                $categoryStats[$catId] = [
                                    'name' => $catName,
                                    'revenue' => 0.0,
                                    'units' => 0,
                                    'orders' => 0,
                                    'order_ids' => []
                                ];
                            }
                            
                            $categoryStats[$catId]['revenue'] += ($price * $qty);
                            $categoryStats[$catId]['units'] += $qty;
                            if (!in_array($order->id, $categoryStats[$catId]['order_ids'])) {
                                $categoryStats[$catId]['order_ids'][] = $order->id;
                                $categoryStats[$catId]['orders']++;
                            }
                        }
                    }
                }
            }
        }

        // Category Contribution Sorting
        $categorySort = $request->input('category_sort', 'revenue');
        uasort($categoryStats, function($a, $b) use ($categorySort) {
            return $b[$categorySort] <=> $a[$categorySort];
        });

        // Slice top categories, aggregate others
        $topCategories = array_slice($categoryStats, 0, 4, true);
        $otherCategories = array_slice($categoryStats, 4, null, true);
        if (!empty($otherCategories)) {
            $otherRevenue = 0.0;
            $otherUnits = 0;
            $otherOrders = 0;
            foreach ($otherCategories as $cat) {
                $otherRevenue += $cat['revenue'];
                $otherUnits += $cat['units'];
                $otherOrders += $cat['orders'];
            }
            $topCategories['others'] = [
                'name' => 'Others',
                'revenue' => $otherRevenue,
                'units' => $otherUnits,
                'orders' => $otherOrders
            ];
        }

        // Calculate Category percentages contribution
        $totalCategoryRevenue = array_sum(array_column($topCategories, 'revenue'));
        foreach ($topCategories as &$cat) {
            $cat['percentage'] = $totalCategoryRevenue > 0 ? round(($cat['revenue'] / $totalCategoryRevenue) * 100, 1) : 0.0;
        }
        unset($cat);

        // 4. Top Products query
        $productStats = [];
        foreach ($orders as $order) {
            $cartItems = json_decode($order->cart_items, true);
            if (is_array($cartItems)) {
                foreach ($cartItems as $item) {
                    $productId = $item['course_id'] ?? null;
                    $price = $item['price'] ?? 0;
                    $qty = $item['quantity'] ?? 1;

                    if ($productId) {
                        if (!isset($productStats[$productId])) {
                            $product = Course::find($productId);
                            $productStats[$productId] = [
                                'id' => $productId,
                                'name' => $product ? $product->name : 'Unknown Product',
                                'image_path' => $product ? $product->image_path : '',
                                'revenue' => 0.0,
                                'units' => 0,
                                'orders' => 0,
                                'order_ids' => []
                            ];
                        }
                        
                        $productStats[$productId]['revenue'] += ($price * $qty);
                        $productStats[$productId]['units'] += $qty;
                        if (!in_array($order->id, $productStats[$productId]['order_ids'])) {
                            $productStats[$productId]['order_ids'][] = $order->id;
                            $productStats[$productId]['orders']++;
                        }
                    }
                }
            }
        }

        $productSort = $request->input('product_sort', 'revenue');
        uasort($productStats, function($a, $b) use ($productSort) {
            return $b[$productSort] <=> $a[$productSort];
        });
        $topProducts = array_slice($productStats, 0, 5);

        // 5. Sales by Channel
        $channels = ['website', 'mobile_app', 'facebook', 'instagram', 'other'];
        $channelData = [];
        foreach ($channels as $ch) {
            $chOrders = $this->getFilteredOrdersQuery($startDate, $endDate, $ch, $paymentMethod)->get();
            $chRevenue = $chOrders->sum('final_total');
            $chOrdersCount = $chOrders->count();
            $chCustomers = $chOrders->pluck('user_id')->unique()->count();

            $channelData[] = [
                'name' => ucfirst(str_replace('_', ' ', $ch)),
                'revenue' => $chRevenue,
                'orders' => $chOrdersCount,
                'customers' => $chCustomers,
                'conversion_rate' => 'Not Available'
            ];
        }

        // 6. Sales by Payment Method
        $paymentMethods = ['cod', 'jazzcash', 'easypaisa', 'bank_transfer', 'stripe'];
        $paymentData = [];
        $totalRevenueForPayment = 0.0;
        foreach ($paymentMethods as $pm) {
            $pmOrders = $this->getFilteredOrdersQuery($startDate, $endDate, $channel, $pm)->get();
            $pmRevenue = $pmOrders->sum('final_total');
            
            $nameStr = 'Other';
            if ($pm === 'cod') $nameStr = 'Cash on Delivery';
            elseif ($pm === 'jazzcash') $nameStr = 'JazzCash';
            elseif ($pm === 'easypaisa') $nameStr = 'EasyPaisa';
            elseif ($pm === 'bank_transfer') $nameStr = 'Bank Transfer';
            elseif ($pm === 'stripe') $nameStr = 'Credit / Debit Card';

            $paymentData[$pm] = [
                'name' => $nameStr,
                'revenue' => $pmRevenue,
                'percentage' => 0.0
            ];
            $totalRevenueForPayment += $pmRevenue;
        }

        foreach ($paymentData as $pm => &$data) {
            if ($totalRevenueForPayment > 0) {
                $data['percentage'] = round(($data['revenue'] / $totalRevenueForPayment) * 100, 1);
            }
        }
        unset($data);

        return view('admin.reports.index', compact(
            'stats',
            'chartData',
            'topCategories',
            'totalCategoryRevenue',
            'topProducts',
            'channelData',
            'paymentData',
            'startDate',
            'endDate',
            'period',
            'channel',
            'paymentMethod',
            'chartGrouping'
        ));
    }

    /**
     * Export reports layout.
     */
    public function export(Request $request)
    {
        $format = $request->input('export_format', 'csv');
        $period = $request->input('period', 'this_month');
        $channel = $request->input('channel', 'all');
        $paymentMethod = $request->input('payment_method', 'all');

        [$startDate, $endDate] = $this->resolveDateRange($period, $request);
        $currentStats = $this->computePeriodStats($startDate, $endDate, $channel, $paymentMethod);

        if ($format === 'csv' || $format === 'excel') {
            $fileName = 'Ghousia_Traders_Report_' . $startDate->format('Ymd') . '_to_' . $endDate->format('Ymd') . '.csv';
            
            $headers = [
                "Content-type" => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=" . $fileName,
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];

            $callback = function() use ($startDate, $endDate, $currentStats, $channel, $paymentMethod) {
                $file = fopen('php://output', 'w');
                // UTF-8 BOM
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                fputcsv($file, ['Ghousia Traders Store Performance Report']);
                fputcsv($file, ['Period:', $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y')]);
                fputcsv($file, ['Channel Filter:', ucfirst($channel)]);
                fputcsv($file, ['Payment Method Filter:', ucfirst($paymentMethod)]);
                fputcsv($file, ['Generated At:', now()->format('Y-m-d H:i:s')]);
                fputcsv($file, []);

                // Executive Summary stats
                fputcsv($file, ['Executive Summary Metrics']);
                fputcsv($file, ['Metric Name', 'Value']);
                fputcsv($file, ['Total Revenue (PKR)', $currentStats['revenue']]);
                fputcsv($file, ['Total Orders', $currentStats['orders']]);
                fputcsv($file, ['Total Customers', $currentStats['customers']]);
                fputcsv($file, ['Average Order Value (PKR)', $currentStats['aov']]);
                fputcsv($file, ['Refunds (PKR)', $currentStats['refunds']]);
                fputcsv($file, []);

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // Print/PDF friendly HTML view
        return view('admin.reports.print', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'channel' => $channel,
            'paymentMethod' => $paymentMethod,
            'stats' => $currentStats,
            'period' => $period
        ]);
    }
}
