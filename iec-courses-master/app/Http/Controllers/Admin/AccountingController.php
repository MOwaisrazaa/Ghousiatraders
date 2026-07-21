<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountTransaction;
use App\Models\AccountTransfer;
use App\Models\JournalEntry;
use App\Models\AccountBalance;
use App\Models\AccountUsage;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $isFiltered = !empty($startDate) && !empty($endDate);

        // Get payment summary by method
        $paymentSummaryQuery = AccountTransaction::where('transaction_type', 'payment_received')
            ->where('status', 'completed');
        
        if ($isFiltered) {
            $paymentSummaryQuery->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        }

        $paymentSummary = $paymentSummaryQuery
            ->groupBy('payment_method')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->get();

        // Get pending orders (not yet paid) - always current
        $pendingOrders = Order::where('status', 'pending')
            ->with('user')
            ->get();

        $pendingOrdersAmount = $pendingOrders->sum('final_total');
        $pendingOrdersCount = $pendingOrders->count();

        // Get paid orders for summary
        $paidOrdersQuery = Order::where('status', 'paid')
            ->with('user');
        
        if ($isFiltered) {
            $paidOrdersQuery->whereBetween('updated_at', [$startDate, $endDate . ' 23:59:59']);
        }

        $paidOrders = $paidOrdersQuery->get();
        $paidOrdersAmount = $paidOrders->sum('final_total');
        $paidOrdersCount = $paidOrders->count();

        // Get account balances logic
        $balances = AccountBalance::all();
        
        if ($isFiltered) {
            // Calculate totals for the period
            $totalReceived = AccountTransaction::where('transaction_type', 'payment_received')
                ->where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                ->sum('amount');
                
            $totalUsed = AccountUsage::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                ->sum('amount');
                
            $totalTransferred = AccountTransfer::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                ->sum('amount');
                
            $totalBalance = $totalReceived - $totalUsed; // Net Change for the period
        } else {
            // Default: All time / Current State from Balances table
            $totalBalance = $balances->sum('balance');
            $totalReceived = $balances->sum('total_received');
            $totalUsed = $balances->sum('total_used');
            $totalTransferred = $balances->sum('total_transferred');
        }

        // Get recent transactions
        $recentTransactionsQuery = AccountTransaction::latest();
        if ($isFiltered) {
            $recentTransactionsQuery->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        }
        $recentTransactions = $recentTransactionsQuery->limit(10)->get();

        // Get pending transfers
        $pendingTransfers = AccountTransfer::where('status', 'pending')
            ->with('requestedBy')
            ->latest()
            ->get();

        // Get pending journal entries
        $pendingJournalEntries = JournalEntry::where('status', 'pending')
            ->with('createdBy')
            ->latest()
            ->get();

        return view('admin.accounting.dashboard', compact(
            'paymentSummary',
            'pendingOrders',
            'pendingOrdersAmount',
            'pendingOrdersCount',
            'paidOrders',
            'paidOrdersAmount',
            'paidOrdersCount',
            'balances',
            'totalBalance',
            'totalReceived',
            'totalUsed',
            'totalTransferred',
            'recentTransactions',
            'pendingTransfers',
            'pendingJournalEntries',
            'startDate',
            'endDate',
            'isFiltered'
        ));
    }

    public function transactions()
    {
        $transactions = AccountTransaction::with('order')
            ->latest()
            ->paginate(20);

        return view('admin.accounting.transactions', compact('transactions'));
    }

    public function transfers()
    {
        $transfers = AccountTransfer::with('requestedBy', 'approvedBy')
            ->latest()
            ->paginate(20);

        return view('admin.accounting.transfers', compact('transfers'));
    }

    public function createTransfer()
    {
        $accounts = \App\Models\Account::where('is_active', true)->pluck('name')->toArray();
        return view('admin.accounting.create-transfer', compact('accounts'));
    }

    public function storeTransfer(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'from_account' => 'required|string',
            'to_account' => 'required|string|different:from_account',
            'reason' => 'required|string|min:10',
        ]);

        $validated['requested_by'] = auth()->id();
        $validated['status'] = 'pending';

        AccountTransfer::create($validated);

        return redirect()->route('admin.accounting.transfers')
            ->with('success', 'Transfer request created successfully');
    }

    public function approveTransfer(AccountTransfer $transfer)
    {
        $transfer->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Transfer approved');
    }

    public function completeTransfer(AccountTransfer $transfer)
    {
        DB::transaction(function () use ($transfer) {
            $transfer->update(['status' => 'completed']);

            // Update account balances
            $fromBalance = AccountBalance::firstOrCreate(
                ['account_name' => $transfer->from_account],
                ['balance' => 0, 'total_received' => 0, 'total_used' => 0, 'total_transferred' => 0]
            );

            $toBalance = AccountBalance::firstOrCreate(
                ['account_name' => $transfer->to_account],
                ['balance' => 0, 'total_received' => 0, 'total_used' => 0, 'total_transferred' => 0]
            );

            $fromBalance->decrement('balance', $transfer->amount);
            $fromBalance->increment('total_transferred', $transfer->amount);

            $toBalance->increment('balance', $transfer->amount);
        });

        return back()->with('success', 'Transfer completed');
    }

    public function rejectTransfer(AccountTransfer $transfer, Request $request)
    {
        $transfer->update([
            'status' => 'rejected',
            'approval_notes' => $request->input('reason'),
        ]);

        return back()->with('success', 'Transfer rejected');
    }

    public function journalEntries()
    {
        $entries = JournalEntry::with('createdBy', 'verifiedBy')
            ->latest()
            ->paginate(20);

        return view('admin.accounting.journal-entries', compact('entries'));
    }

    public function createJournalEntry()
    {
        $accounts = ['cash', 'bank_account_1', 'bank_account_2', 'easypaisa', 'main'];
        return view('admin.accounting.create-journal-entry', compact('accounts'));
    }

    public function storeJournalEntry(Request $request)
    {
        $validated = $request->validate([
            'entry_type' => 'required|in:cash_deposit,bank_withdrawal,bank_deposit,cash_withdrawal',
            'account_from' => 'required|string',
            'account_to' => 'required|string|different:account_from',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|min:10',
            'reference_number' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'pending';

        JournalEntry::create($validated);

        return redirect()->route('admin.accounting.journal-entries')
            ->with('success', 'Journal entry created successfully');
    }

    public function verifyJournalEntry(JournalEntry $entry)
    {
        $entry->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Journal entry verified');
    }

    public function completeJournalEntry(JournalEntry $entry)
    {
        $entry->update(['status' => 'completed']);
        return back()->with('success', 'Journal entry completed');
    }

    public function usages()
    {
        $usages = AccountUsage::with('requestedBy', 'approvedBy')
            ->latest()
            ->paginate(20);

        return view('admin.accounting.usages', compact('usages'));
    }

    public function createUsage()
    {
        $accounts = \App\Models\Account::where('is_active', true)->pluck('name')->toArray();
        $categories = ['marketing', 'operations', 'development', 'infrastructure', 'salaries', 'utilities', 'other'];

        return view('admin.accounting.create-usage', compact('accounts', 'categories'));
    }

    public function storeUsage(Request $request)
    {
        $validated = $request->validate([
            'account_name' => 'required|string',
            'usage_category' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|min:10',
        ]);

        $validated['requested_by'] = auth()->id();
        $validated['status'] = 'pending';

        AccountUsage::create($validated);

        return redirect()->route('admin.accounting.usages')
            ->with('success', 'Usage request created successfully');
    }

    public function approveUsage(AccountUsage $usage)
    {
        $usage->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Usage approved');
    }

    public function completeUsage(AccountUsage $usage)
    {
        DB::transaction(function () use ($usage) {
            $usage->update(['status' => 'completed']);

            $balance = AccountBalance::firstOrCreate(
                ['account_name' => $usage->account_name],
                ['balance' => 0, 'total_received' => 0, 'total_used' => 0, 'total_transferred' => 0]
            );

            $balance->decrement('balance', $usage->amount);
            $balance->increment('total_used', $usage->amount);
        });

        return back()->with('success', 'Usage completed');
    }

    public function balanceSheet()
    {
        $balances = AccountBalance::all();
        $totalBalance = $balances->sum('balance');
        $totalReceived = $balances->sum('total_received');
        $totalUsed = $balances->sum('total_used');
        $totalTransferred = $balances->sum('total_transferred');

        return view('admin.accounting.balance-sheet', compact(
            'balances',
            'totalBalance',
            'totalReceived',
            'totalUsed',
            'totalTransferred'
        ));
    }

    public function report(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $transactions = AccountTransaction::whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $transfers = AccountTransfer::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        $usages = AccountUsage::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->get();

        $totalReceived = $transactions->where('transaction_type', 'payment_received')->sum('amount');
        $totalTransferred = $transfers->sum('amount');
        $totalUsed = $usages->sum('amount');

        return view('admin.accounting.report', compact(
            'transactions',
            'transfers',
            'usages',
            'totalReceived',
            'totalTransferred',
            'totalUsed',
            'startDate',
            'endDate'
        ));
    }

    public function accounts()
    {
        $accounts = \App\Models\Account::all();
        return view('admin.accounting.accounts', compact('accounts'));
    }

    public function createAccount()
    {
        return view('admin.accounting.create-account');
    }

    public function storeAccount(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:accounts,name',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = true;

        \App\Models\Account::create($validated);

        // Create corresponding balance record
        AccountBalance::create([
            'account_name' => $validated['name'],
            'balance' => 0,
            'total_received' => 0,
            'total_used' => 0,
            'total_transferred' => 0,
        ]);

        return redirect()->route('admin.accounting.accounts')
            ->with('success', 'Account created successfully');
    }

    public function deleteAccount(\App\Models\Account $account)
    {
        // Check if account has any transactions
        $hasTransactions = AccountBalance::where('account_name', $account->name)
            ->where(function ($query) {
                $query->where('total_received', '>', 0)
                    ->orWhere('total_used', '>', 0)
                    ->orWhere('total_transferred', '>', 0);
            })
            ->exists();

        if ($hasTransactions) {
            return back()->with('error', 'Cannot delete account with existing transactions');
        }

        // Delete the account balance record
        AccountBalance::where('account_name', $account->name)->delete();

        // Delete the account
        $account->delete();

        return redirect()->route('admin.accounting.accounts')
            ->with('success', 'Account deleted successfully');
    }

    public function toggleAccount(\App\Models\Account $account)
    {
        $account->update(['is_active' => !$account->is_active]);

        return back()->with('success', 'Account status updated');
    }
}
