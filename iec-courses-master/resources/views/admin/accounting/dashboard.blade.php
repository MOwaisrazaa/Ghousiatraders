@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="mb-4">Accounting Dashboard</h1>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="row mb-4">
        <div class="col-md-12">
            <form action="{{ route('admin.accounting.dashboard') }}" method="GET" class="card shadow-sm">
                <div class="card-body py-3">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            @if(isset($isFiltered) && $isFiltered)
                                <a href="{{ route('admin.accounting.dashboard') }}" class="btn btn-secondary">Clear Filter</a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">{{ isset($isFiltered) && $isFiltered ? 'Net Flow' : 'Total Balance' }}</h6>
                    <h3>Rs {{ number_format($totalBalance, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Received</h6>
                    <h3>Rs {{ number_format($totalReceived, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Used</h6>
                    <h3>Rs {{ number_format($totalUsed, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Transferred</h6>
                    <h3>Rs {{ number_format($totalTransferred, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods Summary -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Payment Methods Summary</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Payment Method</th>
                                <th>Orders</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentSummary as $payment)
                                <tr>
                                    <td>{{ ucfirst($payment->payment_method) }}</td>
                                    <td>{{ $payment->count }}</td>
                                    <td>Rs {{ number_format($payment->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No payments received</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Orders Summary -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Orders Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p><strong>Pending Orders:</strong> {{ $pendingOrdersCount }} | <strong>Amount:</strong> Rs {{ number_format($pendingOrdersAmount, 2) }}</p>
                        <p><strong>Paid Orders:</strong> {{ $paidOrdersCount }} | <strong>Amount:</strong> Rs {{ number_format($paidOrdersAmount, 2) }}</p>
                    </div>
                    <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-primary">View All Orders</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.accounting.transfers.create') }}" class="btn btn-primary">Create Transfer</a>
                    <a href="{{ route('admin.accounting.journal-entries.create') }}" class="btn btn-info">Create Journal Entry</a>
                    <a href="{{ route('admin.accounting.usages.create') }}" class="btn btn-warning">Record Usage</a>
                    <a href="{{ route('admin.accounting.balance-sheet') }}" class="btn btn-success">View Balance Sheet</a>
                    <a href="{{ route('admin.accounting.report') }}" class="btn btn-secondary">Generate Report</a>
                    <a href="{{ route('admin.accounting.accounts') }}" class="btn btn-danger">Manage Accounts</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Items -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-header bg-warning">
                    <h5>Pending Transfers ({{ $pendingTransfers->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($pendingTransfers as $transfer)
                        <div class="mb-2 pb-2 border-bottom">
                            <p class="mb-1"><strong>Rs {{ number_format($transfer->amount, 2) }}</strong></p>
                            <small class="text-muted">{{ $transfer->from_account }} → {{ $transfer->to_account }}</small>
                            <br>
                            <small class="text-muted">By: {{ $transfer->requestedBy->name }}</small>
                        </div>
                    @empty
                        <p class="text-muted">No pending transfers</p>
                    @endforelse
                    <a href="{{ route('admin.accounting.transfers') }}" class="btn btn-sm btn-warning mt-2">View All</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-header bg-info">
                    <h5>Pending Journal Entries ({{ $pendingJournalEntries->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($pendingJournalEntries as $entry)
                        <div class="mb-2 pb-2 border-bottom">
                            <p class="mb-1"><strong>Rs {{ number_format($entry->amount, 2) }}</strong></p>
                            <small class="text-muted">{{ $entry->entry_type }}</small>
                            <br>
                            <small class="text-muted">By: {{ $entry->createdBy->name }}</small>
                        </div>
                    @empty
                        <p class="text-muted">No pending entries</p>
                    @endforelse
                    <a href="{{ route('admin.accounting.journal-entries') }}" class="btn btn-sm btn-info mt-2">View All</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-header bg-success">
                    <h5>Recent Transactions ({{ $recentTransactions->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($recentTransactions as $transaction)
                        <div class="mb-2 pb-2 border-bottom">
                            <p class="mb-1"><strong>Rs {{ number_format($transaction->amount, 2) }}</strong></p>
                            <small class="text-muted">{{ $transaction->payment_method }}</small>
                            <br>
                            <small class="text-muted">{{ $transaction->created_at->format('M d, Y') }}</small>
                        </div>
                    @empty
                        <p class="text-muted">No transactions</p>
                    @endforelse
                    <a href="{{ route('admin.accounting.transactions') }}" class="btn btn-sm btn-success mt-2">View All</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Balances -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Account Balances</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Account</th>
                                <th>Balance</th>
                                <th>Total Received</th>
                                <th>Total Used</th>
                                <th>Total Transferred</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($balances as $balance)
                                <tr>
                                    <td><strong>{{ ucfirst($balance->account_name) }}</strong></td>
                                    <td>Rs {{ number_format($balance->balance, 2) }}</td>
                                    <td>Rs {{ number_format($balance->total_received, 2) }}</td>
                                    <td>Rs {{ number_format($balance->total_used, 2) }}</td>
                                    <td>Rs {{ number_format($balance->total_transferred, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No accounts</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
