@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Balance Sheet</h1>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Balance</h6>
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

    <!-- Detailed Balance Sheet -->
    <div class="card">
        <div class="card-header">
            <h5>Account Balances</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            <th class="text-end">Current Balance</th>
                            <th class="text-end">Total Received</th>
                            <th class="text-end">Total Used</th>
                            <th class="text-end">Total Transferred</th>
                            <th class="text-end">Net Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($balances as $balance)
                            <tr>
                                <td><strong>{{ ucfirst($balance->account_name) }}</strong></td>
                                <td class="text-end">
                                    <span class="badge bg-{{ $balance->balance >= 0 ? 'success' : 'danger' }}">
                                        Rs {{ number_format($balance->balance, 2) }}
                                    </span>
                                </td>
                                <td class="text-end text-success">+ Rs {{ number_format($balance->total_received, 2) }}</td>
                                <td class="text-end text-danger">- Rs {{ number_format($balance->total_used, 2) }}</td>
                                <td class="text-end text-info">- Rs {{ number_format($balance->total_transferred, 2) }}</td>
                                <td class="text-end">
                                    <strong>Rs {{ number_format($balance->total_received - $balance->total_used - $balance->total_transferred, 2) }}</strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No accounts found</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-active">
                            <th>TOTAL</th>
                            <th class="text-end">
                                <span class="badge bg-primary">
                                    Rs {{ number_format($totalBalance, 2) }}
                                </span>
                            </th>
                            <th class="text-end text-success">+ Rs {{ number_format($totalReceived, 2) }}</th>
                            <th class="text-end text-danger">- Rs {{ number_format($totalUsed, 2) }}</th>
                            <th class="text-end text-info">- Rs {{ number_format($totalTransferred, 2) }}</th>
                            <th class="text-end">
                                <strong>Rs {{ number_format($totalReceived - $totalUsed - $totalTransferred, 2) }}</strong>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Legend -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Legend</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><strong>Current Balance:</strong> The current available balance in the account</li>
                        <li><strong>Total Received:</strong> Total amount received from payments</li>
                        <li><strong>Total Used:</strong> Total amount used/spent from this account</li>
                        <li><strong>Total Transferred:</strong> Total amount transferred to other accounts</li>
                        <li><strong>Net Change:</strong> Received - Used - Transferred</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
