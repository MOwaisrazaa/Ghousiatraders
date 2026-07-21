@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Accounting Report</h1>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.accounting.report') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Received</h6>
                    <h3>Rs {{ number_format($totalReceived, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Used</h6>
                    <h3>Rs {{ number_format($totalUsed, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Transferred</h6>
                    <h3>Rs {{ number_format($totalTransferred, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Transactions ({{ $transactions->count() }})</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $transaction->transaction_type)) }}</td>
                                <td>{{ ucfirst($transaction->payment_method) }}</td>
                                <td>Rs {{ number_format($transaction->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No transactions in this period</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Transfers -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Transfers ({{ $transfers->count() }})</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Amount</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                            <tr>
                                <td>{{ $transfer->created_at->format('M d, Y') }}</td>
                                <td>{{ ucfirst($transfer->from_account) }}</td>
                                <td>{{ ucfirst($transfer->to_account) }}</td>
                                <td>Rs {{ number_format($transfer->amount, 2) }}</td>
                                <td>{{ Str::limit($transfer->reason, 50) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No transfers in this period</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Usages -->
    <div class="card">
        <div class="card-header">
            <h5>Usages ({{ $usages->count() }})</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Account</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usages as $usage)
                            <tr>
                                <td>{{ $usage->created_at->format('M d, Y') }}</td>
                                <td>{{ ucfirst($usage->account_name) }}</td>
                                <td>{{ ucfirst($usage->usage_category) }}</td>
                                <td>Rs {{ number_format($usage->amount, 2) }}</td>
                                <td>{{ Str::limit($usage->description, 50) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No usages in this period</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
