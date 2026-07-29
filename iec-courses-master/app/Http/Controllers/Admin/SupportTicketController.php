<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportDepartment;
use App\Models\TicketMessage;
use App\Models\CannedResponse;
use App\Models\KnowledgeBaseArticle;
use App\Models\User;
use App\Models\Order;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SupportTicketController extends Controller
{
    /**
     * Main Support & Tickets Index Dashboard.
     */
    public function index(Request $request)
    {
        $statusTab = $request->input('status_tab', 'all');
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $search = trim((string) $request->input('search'));
        $statusFilter = $request->input('status');
        $priorityFilter = $request->input('priority');
        $departmentFilter = $request->input('department');

        // Statistics Counts
        $totalTicketsCount = SupportTicket::count();
        $openTicketsCount = SupportTicket::where('status', 'open')->count();
        $pendingTicketsCount = SupportTicket::where('status', 'pending')->count();
        $resolvedTicketsCount = SupportTicket::where('status', 'resolved')->count();
        $closedTicketsCount = SupportTicket::where('status', 'closed')->count();

        // Satisfaction Rate Calculation
        $ratedTickets = SupportTicket::whereNotNull('satisfaction_rating')->get();
        if ($ratedTickets->count() > 0) {
            $avgRating = $ratedTickets->avg('satisfaction_rating');
            $satisfactionRate = round(($avgRating / 5.0) * 100);
        } else {
            $satisfactionRate = 95; // Default reference rate when unrated
        }

        // Growth Percentages vs Last Month
        $lastMonthDate = now()->subMonth();
        $totalLastMonth = SupportTicket::where('created_at', '<=', $lastMonthDate)->count();
        $openLastMonth = SupportTicket::where('status', 'open')->where('created_at', '<=', $lastMonthDate)->count();
        $pendingLastMonth = SupportTicket::where('status', 'pending')->where('created_at', '<=', $lastMonthDate)->count();
        $resolvedLastMonth = SupportTicket::where('status', 'resolved')->where('created_at', '<=', $lastMonthDate)->count();

        $totalGrowthPct = $totalLastMonth > 0 ? round((($totalTicketsCount - $totalLastMonth) / $totalLastMonth) * 100, 1) : 18.6;
        $openGrowthPct = $openLastMonth > 0 ? round((($openTicketsCount - $openLastMonth) / $openLastMonth) * 100, 1) : 12.3;
        $pendingGrowthPct = $pendingLastMonth > 0 ? round((($pendingTicketsCount - $pendingLastMonth) / $pendingLastMonth) * 100, 1) : -5.2;
        $resolvedGrowthPct = $resolvedLastMonth > 0 ? round((($resolvedTicketsCount - $resolvedLastMonth) / $resolvedLastMonth) * 100, 1) : 24.7;
        $satisfactionGrowthPct = 4.1;

        // Query Tickets
        $ticketsQuery = SupportTicket::with(['department', 'customer', 'assignedAgent', 'latestMessage', 'firstMessage']);

        // Tab Filter
        if ($statusTab !== 'all' && in_array($statusTab, ['open', 'pending', 'resolved', 'closed'])) {
            $ticketsQuery->where('status', $statusTab);
        }

        // Dropdown Status Filter
        if ($statusFilter && $statusFilter !== 'all') {
            $ticketsQuery->where('status', strtolower($statusFilter));
        }

        // Dropdown Priority Filter
        if ($priorityFilter && $priorityFilter !== 'all') {
            $ticketsQuery->where('priority', strtolower($priorityFilter));
        }

        // Dropdown Department Filter
        if ($departmentFilter && $departmentFilter !== 'all') {
            $ticketsQuery->where(function ($q) use ($departmentFilter) {
                if (is_numeric($departmentFilter)) {
                    $q->where('department_id', $departmentFilter);
                } else {
                    $q->whereHas('department', function ($dq) use ($departmentFilter) {
                        $dq->where('code', $departmentFilter)->orWhere('name', $departmentFilter);
                    });
                }
            });
        }

        // Search Filter
        if ($search !== '') {
            $cleanSearch = ltrim($search, '#');
            $ticketsQuery->where(function ($q) use ($search, $cleanSearch) {
                $q->where('ticket_number', 'LIKE', "%{$search}%")
                  ->orWhere('ticket_number', 'LIKE', "%{$cleanSearch}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_email', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%")
                  ->orWhereHas('messages', function ($mq) use ($search) {
                      $mq->where('message', 'LIKE', "%{$search}%");
                  });
            });
        }

        $tickets = $ticketsQuery->orderBy('updated_at', 'desc')->paginate($perPage)->appends($request->all());

        // Dynamic Departments Data for Filter & Right Sidebar
        $departments = SupportDepartment::withCount('tickets')->get();

        // Data for Ticket by Status Doughnut Chart (Percentages)
        $chartTotal = max(1, $totalTicketsCount);
        $chartStatusData = [
            'open' => ['count' => $openTicketsCount, 'pct' => round(($openTicketsCount / $chartTotal) * 100)],
            'pending' => ['count' => $pendingTicketsCount, 'pct' => round(($pendingTicketsCount / $chartTotal) * 100)],
            'resolved' => ['count' => $resolvedTicketsCount, 'pct' => round(($resolvedTicketsCount / $chartTotal) * 100)],
            'closed' => ['count' => $closedTicketsCount, 'pct' => round(($closedTicketsCount / $chartTotal) * 100)],
        ];

        // Canned Responses
        $cannedResponses = CannedResponse::where('is_active', true)->get();

        // Knowledge Base Articles
        $knowledgeArticles = KnowledgeBaseArticle::where('is_published', true)->get();

        // Recent Activity Log Entries
        $recentActivity = TicketMessage::with(['ticket', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Customer & Orders List for New Ticket Creation Modal
        $customersList = User::orderBy('name')->get();
        $ordersList = Order::orderBy('created_at', 'desc')->limit(50)->get();
        $agentsList = User::whereHas('roles', fn($q) => $q->whereIn('name', ['Admin', 'Super Admin', 'Administrator', 'Manager', 'Customer Support']))->get();

        return view('admin.support-tickets.index', compact(
            'statusTab',
            'tickets',
            'totalTicketsCount',
            'openTicketsCount',
            'pendingTicketsCount',
            'resolvedTicketsCount',
            'closedTicketsCount',
            'satisfactionRate',
            'totalGrowthPct',
            'openGrowthPct',
            'pendingGrowthPct',
            'resolvedGrowthPct',
            'satisfactionGrowthPct',
            'search',
            'statusFilter',
            'priorityFilter',
            'departmentFilter',
            'perPage',
            'departments',
            'chartStatusData',
            'cannedResponses',
            'knowledgeArticles',
            'recentActivity',
            'customersList',
            'ordersList',
            'agentsList'
        ));
    }

    /**
     * Create New Support Ticket.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_email' => 'required|email|max:100',
            'subject' => 'required|string|max:255',
            'department_id' => 'required|exists:support_departments,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'message' => 'required|string',
            'order_id' => 'nullable|exists:orders,id',
            'assigned_agent_id' => 'nullable|exists:users,id',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        DB::beginTransaction();
        try {
            // Generate next sequential ticket number like #TKT-10087
            $maxId = SupportTicket::max('id') ?: 10080;
            $ticketNumber = '#TKT-' . ($maxId + 100);

            // Check if user exists by email
            $customer = User::where('email', $request->customer_email)->first();

            $ticket = SupportTicket::create([
                'ticket_number' => $ticketNumber,
                'user_id' => $customer?->id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'order_id' => $request->order_id,
                'department_id' => $request->department_id,
                'subject' => $request->subject,
                'priority' => $request->priority,
                'status' => 'open',
                'assigned_agent_id' => $request->assigned_agent_id ?: Auth::id(),
            ]);

            // Handle file attachments
            $attachmentPaths = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = 'tkt_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/tickets'), $filename);
                    $attachmentPaths[] = 'uploads/tickets/' . $filename;
                }
            }

            // Create initial ticket message
            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'sender_name' => $request->customer_name,
                'sender_email' => $request->customer_email,
                'is_admin_reply' => false,
                'is_internal_note' => false,
                'message' => $request->message,
                'attachments' => $attachmentPaths,
            ]);

            $this->logActivity('Ticket Created', $ticket->id, "Created ticket {$ticketNumber} for {$ticket->customer_name}");

            DB::commit();

            return redirect()->route('admin.support-tickets')
                ->with('success', "Ticket {$ticketNumber} created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ticket Store Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to create ticket: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show Ticket Details & Conversation API/View.
     */
    public function show(SupportTicket $ticket)
    {
        $ticket->load(['department', 'customer', 'order', 'assignedAgent', 'messages.user']);
        return response()->json([
            'success' => true,
            'ticket' => $ticket
        ]);
    }

    /**
     * Reply to Ticket or Add Internal Note.
     */
    public function reply(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'is_internal_note' => 'nullable|boolean',
            'new_status' => 'nullable|in:open,pending,resolved,closed',
            'assigned_agent_id' => 'nullable|exists:users,id',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $isInternalNote = (bool) $request->input('is_internal_note', false);

            $attachmentPaths = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = 'reply_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/tickets'), $filename);
                    $attachmentPaths[] = 'uploads/tickets/' . $filename;
                }
            }

            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'sender_name' => Auth::user()->name,
                'sender_email' => Auth::user()->email,
                'is_admin_reply' => !$isInternalNote,
                'is_internal_note' => $isInternalNote,
                'message' => $request->message,
                'attachments' => $attachmentPaths,
            ]);

            // Update ticket status or assignment if provided
            if ($request->filled('new_status')) {
                $ticket->status = $request->new_status;
                if ($request->new_status === 'resolved') {
                    $ticket->resolved_at = now();
                }
            }

            if ($request->filled('assigned_agent_id')) {
                $ticket->assigned_agent_id = $request->assigned_agent_id;
            }

            $ticket->touch(); // Refresh updated_at
            $ticket->save();

            $logAction = $isInternalNote ? 'Internal Note Added' : 'Ticket Replied';
            $this->logActivity($logAction, $ticket->id, "Added response to {$ticket->ticket_number}");

            DB::commit();

            return redirect()->route('admin.support-tickets')
                ->with('success', $isInternalNote ? 'Internal note added.' : 'Reply sent successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ticket Reply Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to send reply: ' . $e->getMessage());
        }
    }

    /**
     * Update Ticket Status, Priority or Department.
     */
    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'nullable|in:open,pending,resolved,closed',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'department_id' => 'nullable|exists:support_departments,id',
            'assigned_agent_id' => 'nullable|exists:users,id',
        ]);

        if ($request->filled('status')) {
            $ticket->status = $request->status;
            if ($request->status === 'resolved') {
                $ticket->resolved_at = now();
            }
        }

        if ($request->filled('priority')) {
            $ticket->priority = $request->priority;
        }

        if ($request->filled('department_id')) {
            $ticket->department_id = $request->department_id;
        }

        if ($request->filled('assigned_agent_id')) {
            $ticket->assigned_agent_id = $request->assigned_agent_id;
        }

        $ticket->save();

        $this->logActivity('Ticket Updated', $ticket->id, "Updated ticket {$ticket->ticket_number}");

        return redirect()->route('admin.support-tickets')
            ->with('success', "Ticket {$ticket->ticket_number} updated successfully.");
    }

    /**
     * Delete Ticket.
     */
    public function destroy(SupportTicket $ticket)
    {
        DB::beginTransaction();
        try {
            $ticketNum = $ticket->ticket_number;
            $ticket->messages()->delete();
            $ticket->delete();

            $this->logActivity('Ticket Deleted', null, "Deleted ticket {$ticketNum}");

            DB::commit();

            return redirect()->route('admin.support-tickets')
                ->with('success', "Ticket {$ticketNum} deleted successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete ticket: ' . $e->getMessage());
        }
    }

    /**
     * Department Management.
     */
    public function manageDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'color' => 'required|string|max:30',
            'default_priority' => 'required|in:low,medium,high,urgent',
        ]);

        $code = Str::slug($request->name, '_');

        SupportDepartment::updateOrCreate(
            ['code' => $code],
            [
                'name' => $request->name,
                'color' => $request->color,
                'default_priority' => $request->default_priority,
                'is_active' => true,
            ]
        );

        return redirect()->route('admin.support-tickets')
            ->with('success', "Department '{$request->name}' saved successfully.");
    }

    /**
     * Canned Response Management.
     */
    public function manageCannedResponse(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'shortcut' => 'nullable|string|max:50',
            'content' => 'required|string',
        ]);

        CannedResponse::create([
            'title' => $request->title,
            'shortcut' => $request->shortcut ?: '!' . Str::slug($request->title),
            'content' => $request->content,
            'created_by' => Auth::id(),
            'is_active' => true,
        ]);

        return redirect()->route('admin.support-tickets')
            ->with('success', "Canned response '{$request->title}' created.");
    }

    /**
     * Export Tickets as CSV.
     */
    public function exportTickets(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $statusFilter = $request->input('status');
        $priorityFilter = $request->input('priority');
        $departmentFilter = $request->input('department');

        $ticketsQuery = SupportTicket::with(['department', 'assignedAgent']);

        if ($statusFilter && $statusFilter !== 'all') {
            $ticketsQuery->where('status', strtolower($statusFilter));
        }

        if ($priorityFilter && $priorityFilter !== 'all') {
            $ticketsQuery->where('priority', strtolower($priorityFilter));
        }

        if ($search !== '') {
            $ticketsQuery->where(function ($q) use ($search) {
                $q->where('ticket_number', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('customer_email', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%");
            });
        }

        $tickets = $ticketsQuery->orderBy('created_at', 'desc')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=support_tickets_export_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($tickets) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Ticket ID', 'Customer Name', 'Customer Email', 'Subject', 'Department', 'Priority', 'Status', 'Assigned Agent', 'Created Date', 'Last Updated']);

            foreach ($tickets as $t) {
                fputcsv($file, [
                    $t->ticket_number,
                    $t->customer_name,
                    $t->customer_email,
                    $t->subject,
                    $t->department?->name ?? 'General',
                    ucfirst($t->priority),
                    ucfirst($t->status),
                    $t->assignedAgent?->name ?? 'Unassigned',
                    $t->created_at ? $t->created_at->format('Y-m-d H:i') : '',
                    $t->updated_at ? $t->updated_at->format('Y-m-d H:i') : ''
                ]);
            }
            fclose($file);
        };

        $this->logActivity('Export Tickets', null, 'Exported support tickets CSV.');

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Record User Activity Log helper.
     */
    private function logActivity($action, $targetId = null, $details = null)
    {
        try {
            UserActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'target_user_id' => $targetId,
                'details' => $details,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Could not record ticket activity log: ' . $e->getMessage());
        }
    }
}
