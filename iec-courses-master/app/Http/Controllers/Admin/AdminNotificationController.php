<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminNotificationController extends Controller
{
    protected AdminNotificationService $service;

    public function __construct(AdminNotificationService $service)
    {
        $this->middleware(['auth', 'check.role:Admin,Super Admin']);
        $this->service = $service;
    }

    /**
     * Display the dedicated notifications page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'all');
        $search = (string) $request->input('search', '');
        $sort = $request->input('sort', 'newest');

        $allNotifications = $this->service->getNotifications();
        $unreadCount = $this->service->getUnreadCount();

        // Calculate counts for tabs
        $counts = [
            'all' => count($allNotifications),
            'unread' => $unreadCount,
            'orders' => collect($allNotifications)->where('type', 'order')->count(),
            'reviews' => collect($allNotifications)->where('type', 'review')->count(),
            'customers' => collect($allNotifications)->where('type', 'customer')->count(),
            'support' => collect($allNotifications)->where('type', 'ticket')->count(),
            'stock' => collect($allNotifications)->whereIn('type', ['low_stock', 'out_of_stock'])->count(),
        ];

        // Filter by Tab
        $filtered = collect($allNotifications)->filter(function($n) use ($tab) {
            if ($tab === 'unread') return empty($n['is_read']);
            if ($tab === 'orders') return $n['type'] === 'order';
            if ($tab === 'reviews') return $n['type'] === 'review';
            if ($tab === 'customers') return $n['type'] === 'customer';
            if ($tab === 'support') return $n['type'] === 'ticket';
            if ($tab === 'stock') return in_array($n['type'], ['low_stock', 'out_of_stock'], true);
            return true;
        });

        // Filter by Search
        if (!empty($search)) {
            $searchLower = strtolower(trim($search));
            $filtered = $filtered->filter(function($n) use ($searchLower) {
                return str_contains(strtolower($n['title']), $searchLower)
                    || str_contains(strtolower($n['message']), $searchLower);
            });
        }

        // Sort
        if ($sort === 'oldest') {
            $filtered = $filtered->sortBy('created_at');
        } else {
            $filtered = $filtered->sortByDesc('created_at');
        }

        return view('admin.notifications.index', [
            'notifications' => $filtered->values()->all(),
            'unreadCount' => $unreadCount,
            'counts' => $counts,
            'tab' => $tab,
            'search' => $search,
            'sort' => $sort,
        ]);
    }

    /**
     * Fetch current unread count & notification items JSON.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request): JsonResponse
    {
        $notifications = $this->service->getNotifications();
        $unreadCount = $this->service->getUnreadCount();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Mark a single notification as read.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function markRead(Request $request): JsonResponse
    {
        $notifId = (string) $request->input('id');
        if (empty($notifId)) {
            return response()->json([
                'success' => false,
                'message' => 'Notification ID required'
            ], 422);
        }

        $newUnreadCount = $this->service->markAsRead($notifId);

        return response()->json([
            'success' => true,
            'unread_count' => $newUnreadCount,
        ]);
    }

    /**
     * Mark all active notifications as read.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function markAllRead(Request $request): JsonResponse
    {
        $this->service->markAllAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }
}
