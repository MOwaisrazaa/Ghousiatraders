@extends('admin.layout')

@section('title', 'Order Details')

@section('header', 'Order Details #' . $order->id)

@section('actions')
    <a href="{{ route('admin.orders') }}" class="pf-btn-outline" style="margin-right: 10px;">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>

    <form action="{{ route('admin.order.update-status', $order->id) }}" method="POST" class="d-inline-flex align-items-center" style="gap: 8px;">
        @csrf
        <select name="status" class="pf-select-field" style="margin: 0; min-width: 150px; height: 36px; padding: 4px 10px; background: #111; color: #f8e7d0; border: 1px solid rgba(212,166,88,0.25); border-radius: 8px; font-size: 0.85rem; outline: none;">
            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Confirmed</option>
            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Delivered</option>
            <option value="rejected" {{ $order->status === 'rejected' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="pf-btn-gold" style="padding: 7px 14px; font-size: 0.85rem; border-radius: 8px; height: 36px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fas fa-save"></i> Update Status
        </button>
    </form>
@endsection

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
.pf-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 32px;
}
@media(max-width:768px){ .pf-detail-grid{ grid-template-columns:1fr; } }
.pf-info-table { width: 100%; border-collapse: collapse; }
.pf-info-table tr { border-bottom: 1px solid rgba(212,166,88,0.08); }
.pf-info-table tr:last-child { border-bottom: none; }
.pf-info-table th {
    width: 40%;
    padding: 10px 14px;
    color: rgba(248,231,208,0.55);
    font-size: 0.8rem;
    font-weight: 600;
    text-align: left;
    letter-spacing: 0.04em;
}
.pf-info-table td {
    padding: 10px 14px;
    color: #f8e7d0;
    font-size: 0.88rem;
}
.pf-tfoot-total th {
    color: #d4a658 !important;
    font-size: 0.95rem;
}
.pf-tfoot-discount { color: #f07080 !important; }
.pf-no-info { color: rgba(248,231,208,0.45); font-size: 0.88rem; font-style: italic; padding: 10px 0; }
</style>



    {{-- Order Details & Customer Info side-by-side --}}
    <div class="pf-detail-grid">

        {{-- Left: Order Details --}}
        <div class="pf-sidebar-box">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:18px;">
                <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Order Details</span>
                <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
            </div>
            <table class="pf-info-table">
                <tr>
                    <th>Order ID</th>
                    <td>#{{ $order->id }}</td>
                </tr>
                <tr>
                    <th>Order Date</th>
                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Status</th>
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
                </tr>
                <tr>
                    <th>Payment Method</th>
                    <td>{{ ucfirst($order->payment_method) }}</td>
                </tr>
                <tr>
                    <th>Subtotal</th>
                    <td>Rs {{ number_format($order->total, 2) }}</td>
                </tr>
                @if($order->discount > 0)
                <tr>
                    <th>Discount</th>
                    <td style="color:#f07080;">- Rs {{ number_format($order->discount, 2) }}</td>
                </tr>
                @endif
                @if($order->coupon_code)
                <tr>
                    <th>Coupon Code</th>
                    <td style="color:#d4a658;font-weight:700;">{{ $order->coupon_code }}</td>
                </tr>
                @endif
                <tr>
                    <th>Final Total</th>
                    <td style="color:#d4a658;font-weight:700;font-size:1rem;">Rs {{ number_format($order->final_total ?? ($order->total - ($order->discount ?? 0)), 2) }}</td>
                </tr>
            </table>
        </div>

        {{-- Right: Customer / Billing --}}
        <div class="pf-sidebar-box">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:18px;">
                <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Customer Information</span>
                <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
            </div>
            @if($billingAddress)
                <table class="pf-info-table">
                    <tr>
                        <th>Name</th>
                        <td>{{ $billingAddress['first_name'] ?? '' }} {{ $billingAddress['last_name'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $billingAddress['email'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $billingAddress['phone'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $billingAddress['address'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>{{ $billingAddress['city'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td>{{ $billingAddress['state'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{ $billingAddress['country'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Postal Code</th>
                        <td>{{ $billingAddress['postal_code'] ?? 'N/A' }}</td>
                    </tr>
                </table>
            @else
                <p class="pf-no-info">No billing address information available.</p>
            @endif
        </div>
    </div>

    {{-- Ordered Items --}}
    <div style="display:flex;align-items:center;gap:12px;margin:0 0 20px;">
        <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Ordered Items</span>
        <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
    </div>

    <div class="pf-table-wrap">
        <table class="pf-table">
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th width="15%">Type</th>
                    <th>Item</th>
                    @if(count(array_filter($items, function($item) { return isset($item['course']); })))
                        <th>Product</th>
                    @endif
                    <th class="text-end" width="15%">Price</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['type'] }}</td>
                        <td>{{ $item['name'] }}</td>
                        @if(count(array_filter($items, function($item) { return isset($item['course']); })))
                            <td>{{ $item['course'] ?? 'N/A' }}</td>
                        @endif
                        <td class="text-end">Rs {{ number_format($item['price'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="pf-empty">No items found</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="{{ count(array_filter($items, function($item) { return isset($item['course']); })) ? '4' : '3' }}" class="text-end" style="color:rgba(248,231,208,0.6);font-weight:600;">Subtotal:</th>
                    <th class="text-end" style="color:#f8e7d0;">Rs {{ number_format($order->total, 2) }}</th>
                </tr>
                @if($order->discount > 0)
                    <tr>
                        <th colspan="{{ count(array_filter($items, function($item) { return isset($item['course']); })) ? '4' : '3' }}" class="text-end" style="color:rgba(248,231,208,0.6);font-weight:600;">Discount:</th>
                        <th class="text-end pf-tfoot-discount" style="color:#f07080;">-Rs {{ number_format($order->discount, 2) }}</th>
                    </tr>
                @endif
                <tr>
                    <th colspan="{{ count(array_filter($items, function($item) { return isset($item['course']); })) ? '4' : '3' }}" class="text-end" style="color:#d4a658;font-weight:700;">Final Total:</th>
                    <th class="text-end pf-tfoot-total" style="color:#d4a658;font-weight:700;font-size:1rem;">Rs {{ number_format($order->final_total ?? ($order->total - ($order->discount ?? 0)), 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

@endsection
