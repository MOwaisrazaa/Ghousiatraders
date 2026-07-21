@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Account Transactions</h1>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Order ID</th>
                        <th>Type</th>
                        <th>Payment Method</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>#{{ $transaction->id }}</td>
                            <td>
                                @if($transaction->order)
                                    <a href="{{ route('admin.order.show', $transaction->order) }}" class="text-decoration-none">
                                        #{{ $transaction->order->id }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ ucfirst(str_replace('_', ' ', $transaction->transaction_type)) }}</td>
                            <td>{{ ucfirst($transaction->payment_method) }}</td>
                            <td><strong>Rs {{ number_format($transaction->amount, 2) }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $transaction->status === 'completed' ? 'success' : ($transaction->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>{{ $transaction->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $transaction->id }}">Details</button>
                            </td>
                        </tr>

                        <!-- Details Modal -->
                        <div class="modal fade" id="detailsModal{{ $transaction->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Transaction Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Transaction ID:</strong> #{{ $transaction->id }}</p>
                                        <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $transaction->transaction_type)) }}</p>
                                        <p><strong>Payment Method:</strong> {{ ucfirst($transaction->payment_method) }}</p>
                                        <p><strong>Amount:</strong> Rs {{ number_format($transaction->amount, 2) }}</p>
                                        <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
                                        @if($transaction->order)
                                            <p><strong>Order:</strong> <a href="{{ route('admin.order.show', $transaction->order) }}">#{{ $transaction->order->id }}</a></p>
                                            @if($transaction->order->user)
                                                <p><strong>Customer:</strong> {{ $transaction->order->user->name }}</p>
                                            @else
                                                <p><strong>Customer:</strong> <span class="text-muted">Unknown</span></p>
                                            @endif
                                        @endif
                                        @if($transaction->description)
                                            <p><strong>Description:</strong> {{ $transaction->description }}</p>
                                        @endif
                                        <p><strong>Date:</strong> {{ $transaction->created_at->format('M d, Y H:i:s') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No transactions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
