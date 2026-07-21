<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrafficLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TrafficAnalyticsController extends Controller
{
    public function index()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('traffic_logs')) {
            return redirect()->route('admin.dashboard')->with('error', 'Traffic Analytics feature is disabled.');
        }

        $startDate = Carbon::now()->subDays(30);

        // Total Visits in last 30 days
        $totalVisits = TrafficLog::where('created_at', '>=', $startDate)->count();

        // Unique Visitors (by IP)
        $uniqueVisitors = TrafficLog::where('created_at', '>=', $startDate)->distinct('ip_address')->count('ip_address');

        // Visits by Country
        $visitsByCountry = TrafficLog::where('created_at', '>=', $startDate)
            ->select('country', DB::raw('count(*) as total'))
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top Referrers
        $topReferrers = TrafficLog::where('created_at', '>=', $startDate)
            ->whereNotNull('referer')
            ->select('referer', DB::raw('count(*) as total'))
            ->groupBy('referer')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top Pages
        // We might want to strip query parameters for aggregation if they vary too much, 
        // but for now keeping full URL or just path.
        // Let's try to group by URL.
        $topPages = TrafficLog::where('created_at', '>=', $startDate)
            ->select('url', DB::raw('count(*) as total'))
            ->groupBy('url')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
            
        // Device Types
        $deviceStats = TrafficLog::where('created_at', '>=', $startDate)
            ->select('device_type', DB::raw('count(*) as total'))
            ->groupBy('device_type')
            ->get();

        // Browser Stats
        $browserStats = TrafficLog::where('created_at', '>=', $startDate)
            ->select('browser', DB::raw('count(*) as total'))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Daily Visits for Chart
        $dailyVisits = TrafficLog::where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top Internal Search Terms
        $topSearches = \App\Models\SearchLog::where('created_at', '>=', $startDate)
            ->select('keyword', DB::raw('count(*) as total'), DB::raw('avg(results_count) as avg_results'))
            ->groupBy('keyword')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top Landing Pages (Traffic from External Sources)
        // We assume 'external' means referer does not contain our app URL or is empty (direct traffic could be landing too)
        // Adjust logic: If referer is null OR not containing '127.0.0.1' or 'localhost' or your production domain
        $appUrl = config('app.url');
        $domain = parse_url($appUrl, PHP_URL_HOST) ?? '127.0.0.1'; // simplistic check

        // In a real production app, you might want to filter referers more robustly.
        // For now, let's just show top pages where referer is NOT internal.
        $topLandingPages = TrafficLog::where('created_at', '>=', $startDate)
            ->where(function($q) use ($domain) {
                $q->whereNull('referer')
                  ->orWhere('referer', 'not like', "%$domain%");
            })
            ->select('url', DB::raw('count(*) as total'))
            ->groupBy('url')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('admin.analytics.index', compact(
            'totalVisits', 
            'uniqueVisitors', 
            'visitsByCountry', 
            'topReferrers', 
            'topPages', 
            'deviceStats',
            'browserStats',
            'dailyVisits',
            'topSearches',
            'topLandingPages'
        ));
    }
}
