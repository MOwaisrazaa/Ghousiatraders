<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Rating;
use App\Models\SupportTicket;
use App\Models\Course;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminNotificationService
{
    /**
     * Get all active notifications for the admin layout.
     *
     * @param int|null $userId
     * @return array
     */
    public function getNotifications(?int $userId = null): array
    {
        $userId = $userId ?: (Auth::id() ?: 1);
        $readIds = $this->getReadNotificationIds($userId);

        $notifications = [];

        // 1. REAL NEW ORDERS
        try {
            $orders = Order::orderBy('created_at', 'desc')->take(20)->get();
            foreach ($orders as $o) {
                $customerName = trim(($o->billing_first_name ?? '') . ' ' . ($o->billing_last_name ?? ''));
                if (empty($customerName)) {
                    $customerName = $o->user ? $o->user->name : 'Customer';
                }
                $total = number_format((float) ($o->final_total ?: ($o->grand_total ?: 0)));
                $notifId = "order_{$o->id}";

                $notifications[] = [
                    'id' => $notifId,
                    'type' => 'order',
                    'icon' => 'shopping-bag',
                    'title' => 'New Order Received',
                    'message' => "Order #GT-{$o->id} received from {$customerName} — PKR {$total}",
                    'url' => route('admin.orders'),
                    'created_at' => $o->created_at ? $o->created_at->toIso8601String() : now()->toIso8601String(),
                    'time_ago' => $o->created_at ? $o->created_at->diffForHumans() : 'Recently',
                    'is_read' => in_array($notifId, $readIds, true),
                ];
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        // 2. REAL NEW CUSTOMERS (Account registrations)
        try {
            $customers = User::whereDoesntHave('roles', function($q) {
                $q->whereIn('name', ['Admin', 'Super Admin']);
            })
            ->where(function($q) {
                $q->whereNull('is_admin')->orWhere('is_admin', 0);
            })
            ->orderBy('created_at', 'desc')
            ->take(15)
            ->get();

            foreach ($customers as $c) {
                $notifId = "customer_{$c->id}";
                $notifications[] = [
                    'id' => $notifId,
                    'type' => 'customer',
                    'icon' => 'user-plus',
                    'title' => 'New Customer Registered',
                    'message' => "{$c->name} created a new customer account.",
                    'url' => route('admin.customers'),
                    'created_at' => $c->created_at ? $c->created_at->toIso8601String() : now()->toIso8601String(),
                    'time_ago' => $c->created_at ? $c->created_at->diffForHumans() : 'Recently',
                    'is_read' => in_array($notifId, $readIds, true),
                ];
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        // 3. REAL NEW REVIEWS
        try {
            $reviews = Rating::with(['rateable', 'user'])
                ->orderBy('created_at', 'desc')
                ->take(15)
                ->get();

            foreach ($reviews as $r) {
                $reviewer = $r->user ? $r->user->name : ($r->reviewer_name ?: 'Customer');
                $productName = $r->rateable ? $r->rateable->name : 'Product';
                $statusTag = ($r->status === 'pending' || !$r->is_approved) ? ' (Pending Approval)' : '';
                $notifId = "review_{$r->id}";

                $notifications[] = [
                    'id' => $notifId,
                    'type' => 'review',
                    'icon' => 'star',
                    'title' => 'New Review Submitted',
                    'message' => "{$reviewer} submitted a {$r->rating}-star review for {$productName}.{$statusTag}",
                    'url' => route('admin.reviews.index'),
                    'created_at' => $r->created_at ? $r->created_at->toIso8601String() : now()->toIso8601String(),
                    'time_ago' => $r->created_at ? $r->created_at->diffForHumans() : 'Recently',
                    'is_read' => in_array($notifId, $readIds, true),
                ];
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        // 4. REAL SUPPORT TICKETS (If active)
        try {
            if (\Schema::hasTable('support_tickets')) {
                $tickets = SupportTicket::with('user')
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get();

                foreach ($tickets as $t) {
                    $userStr = $t->user ? $t->user->name : 'Customer';
                    $prio = ucfirst($t->priority ?: 'normal');
                    $notifId = "ticket_{$t->id}";

                    $notifications[] = [
                        'id' => $notifId,
                        'type' => 'ticket',
                        'icon' => 'life-buoy',
                        'title' => 'New Support Ticket',
                        'message' => "{$userStr} — {$t->subject} ({$prio} Priority)",
                        'url' => \Route::has('admin.support-tickets.show') 
                            ? route('admin.support-tickets.show', $t->id) 
                            : (\Route::has('admin.support-tickets.index') ? route('admin.support-tickets.index') : route('admin.dashboard')),
                        'created_at' => $t->created_at ? $t->created_at->toIso8601String() : now()->toIso8601String(),
                        'time_ago' => $t->created_at ? $t->created_at->diffForHumans() : 'Recently',
                        'is_read' => in_array($notifId, $readIds, true),
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        // 5. REAL LOW STOCK ALERTS
        try {
            $lowStockProducts = Course::where('status', 'active')
                ->where('stock', '>', 0)
                ->where('stock', '<=', 3)
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();

            foreach ($lowStockProducts as $lp) {
                $notifId = "low_stock_{$lp->id}";
                $notifications[] = [
                    'id' => $notifId,
                    'type' => 'low_stock',
                    'icon' => 'alert-triangle',
                    'title' => 'Low Stock Alert',
                    'message' => "{$lp->name} has only {$lp->stock} units remaining.",
                    'url' => route('admin.products'),
                    'created_at' => $lp->updated_at ? $lp->updated_at->toIso8601String() : now()->toIso8601String(),
                    'time_ago' => $lp->updated_at ? $lp->updated_at->diffForHumans() : 'Recently',
                    'is_read' => in_array($notifId, $readIds, true),
                ];
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        // 6. REAL OUT OF STOCK ALERTS
        try {
            $outOfStockProducts = Course::where('status', 'active')
                ->where('stock', '<=', 0)
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get();

            foreach ($outOfStockProducts as $op) {
                $notifId = "out_stock_{$op->id}";
                $notifications[] = [
                    'id' => $notifId,
                    'type' => 'out_of_stock',
                    'icon' => 'alert-circle',
                    'title' => 'Product Out of Stock',
                    'message' => "{$op->name} is now out of stock.",
                    'url' => route('admin.products'),
                    'created_at' => $op->updated_at ? $op->updated_at->toIso8601String() : now()->toIso8601String(),
                    'time_ago' => $op->updated_at ? $op->updated_at->diffForHumans() : 'Recently',
                    'is_read' => in_array($notifId, $readIds, true),
                ];
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        // Sort all notifications chronologically descending
        usort($notifications, function($a, $b) {
            return strcmp($b['created_at'], $a['created_at']);
        });

        // Limit total notifications payload
        return array_slice($notifications, 0, 30);
    }

    /**
     * Get list of read notification IDs for specific admin user.
     *
     * @param int $userId
     * @return array
     */
    public function getReadNotificationIds(int $userId): array
    {
        return Cache::get("admin_read_notifs_{$userId}", []);
    }

    /**
     * Mark single notification as read.
     *
     * @param string $notifId
     * @param int|null $userId
     * @return int New unread count
     */
    public function markAsRead(string $notifId, ?int $userId = null): int
    {
        $userId = $userId ?: (Auth::id() ?: 1);
        $readIds = $this->getReadNotificationIds($userId);

        if (!in_array($notifId, $readIds, true)) {
            $readIds[] = $notifId;
            Cache::put("admin_read_notifs_{$userId}", $readIds, now()->addDays(365));
        }

        return $this->getUnreadCount($userId);
    }

    /**
     * Mark all current notifications as read.
     *
     * @param int|null $userId
     * @return int (Always 0)
     */
    public function markAllAsRead(?int $userId = null): int
    {
        $userId = $userId ?: (Auth::id() ?: 1);
        $notifications = $this->getNotifications($userId);

        $readIds = $this->getReadNotificationIds($userId);
        foreach ($notifications as $n) {
            if (!in_array($n['id'], $readIds, true)) {
                $readIds[] = $n['id'];
            }
        }

        Cache::put("admin_read_notifs_{$userId}", $readIds, now()->addDays(365));
        return 0;
    }

    /**
     * Get count of unread notifications.
     *
     * @param int|null $userId
     * @return int
     */
    public function getUnreadCount(?int $userId = null): int
    {
        $userId = $userId ?: (Auth::id() ?: 1);
        $notifications = $this->getNotifications($userId);
        $unread = 0;
        foreach ($notifications as $n) {
            if (empty($n['is_read'])) {
                $unread++;
            }
        }
        return $unread;
    }
}
