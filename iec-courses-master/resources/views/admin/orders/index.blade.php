@extends('admin.layout')

@section('title', 'Orders Management')

@section('header', 'Orders Management')

@section('content')

<style>
.pf-modal .modal-content { background: #111; border: 1px solid rgba(212,166,88,0.25); border-radius: 18px; color: #f8e7d0; }
.pf-modal .modal-header { border-bottom: 1px solid rgba(212,166,88,0.15); }
.pf-modal .modal-footer { border-top: 1px solid rgba(212,166,88,0.15); }
.pf-modal .modal-title { color: #d4a658; font-weight: 700; }
.pf-modal .btn-close { filter: invert(1); }
.pf-badge-pending {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    background: rgba(245,158,11,0.15);
    color: #f5a623;
    border: 1px solid rgba(245,158,11,0.3);
}
.pf-badge-danger {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    background: rgba(220,53,69,0.15);
    color: #f07080;
    border: 1px solid rgba(220,53,69,0.25);
}
.pf-btn-view {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 13px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    background: rgba(100,180,255,0.12);
    color: #7ec8f7;
    border: 1px solid rgba(100,180,255,0.25);
    text-decoration: none;
    transition: background 0.2s;
}
.pf-btn-view:hover { background: rgba(100,180,255,0.22); color: #7ec8f7; }
.pf-btn-success {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 13px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    background: rgba(95,207,110,0.13);
    color: #5fcf6e;
    border: 1px solid rgba(95,207,110,0.25);
    cursor: pointer;
    transition: background 0.2s;
}
.pf-btn-success:hover { background: rgba(95,207,110,0.22); color: #5fcf6e; }
.pf-pagination { display: flex; justify-content: center; margin-top: 24px; }
</style>

    <div class="pf-table-wrap">
        <table class="pf-table" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Discount</th>
                    <th>Final Amount</th>
                    <th>Status</th>
                    <th>Payment Method</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>
                            @if($order->user)
                                {{ $order->user->name ?? ($order->user->first_name . ' ' . $order->user->last_name) }}
                            @else
                                Unknown User
                            @endif
                        </td>
                        <td>Rs {{ number_format($order->total, 2) }}</td>
                        <td>Rs {{ number_format($order->discount ?? 0, 2) }}</td>
                        <td>Rs {{ number_format($order->final_total ?? ($order->total - ($order->discount ?? 0)), 2) }}</td>
                        <td>
                            @if($order->status === 'pending' || $order->status === 'awaiting_payment')
                                <span class="pf-badge-pending">Pending</span>
                            @elseif($order->status === 'paid')
                                <span class="pf-badge-blue">Confirmed</span>
                            @elseif($order->status === 'shipped')
                                <span class="pf-badge-gold">Shipped</span>
                            @elseif($order->status === 'completed')
                                <span class="pf-badge-active">Delivered</span>
                            @elseif($order->status === 'rejected')
                                <span class="pf-badge-danger">Cancelled</span>
                            @else
                                <span class="pf-badge-inactive">{{ ucfirst($order->status) }}</span>
                            @endif
                        </td>
                        <td>{{ ucfirst($order->payment_method) }}</td>
                        <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                        <td class="text-center" style="white-space:nowrap;">
                            <a href="{{ route('admin.order.show', $order->id) }}" class="pf-btn-view">
                                <i class="fas fa-eye"></i> View
                            </a>

                            @if($order->status === 'pending' || $order->status === 'awaiting_payment')
                                <form action="{{ route('admin.order.approve', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="pf-btn-success">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>

                                <button type="button" class="pf-btn-delete" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $order->id }}">
                                    <i class="fas fa-times"></i> Reject
                                </button>

                                <!-- Reject Modal -->
                                <div class="modal fade pf-modal" id="rejectModal{{ $order->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $order->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="rejectModalLabel{{ $order->id }}">Reject Order #{{ $order->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('admin.order.reject', $order->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="pf-field">
                                                        <label for="reason" class="pf-form-label">Reason for Rejection (Optional)</label>
                                                        <textarea class="pf-textarea" id="reason" name="reason" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="pf-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="pf-btn-delete">Reject Order</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="pf-empty">No orders found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pf-pagination">
        {{ $orders->links() }}
    </div>

@endsection
