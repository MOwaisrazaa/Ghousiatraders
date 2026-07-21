<x-app-layout>
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('user.payment-details') }}" class="btn btn-link btn-sm mb-3">
                <i class="fas fa-arrow-left me-1"></i> Back to Payment Details
            </a>
            <h1 class="h3 mb-2">Order #{{ $order->id }}</h1>
            <p class="text-muted">{{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>
    </div>

    <div class="row">
        <!-- Order Items -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white p-3">
                    <h5 class="mb-0"><i class="fas fa-box me-2 text-primary"></i>Order Items</h5>
                </div>
                <div class="card-body p-0">
                    @forelse($items as $item)
                        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                            <div>
                                <p class="mb-1 fw-bold">{{ $item['name'] }}</p>
                                <small class="text-muted">{{ $item['type'] }}</small>
                            </div>
                            <div class="text-end">
                                <p class="mb-0 fw-bold">Rs {{ number_format($item['price'], 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <p>No items in this order</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Billing Address -->
            @if($billingAddress)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white p-3">
                        <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Billing Address</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(isset($billingAddress['first_name']) || isset($billingAddress['last_name']))
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">Name</small>
                                    <p class="mb-0">{{ ($billingAddress['first_name'] ?? '') . ' ' . ($billingAddress['last_name'] ?? '') }}</p>
                                </div>
                            @endif
                            @if(isset($billingAddress['email']))
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">Email</small>
                                    <p class="mb-0">{{ $billingAddress['email'] }}</p>
                                </div>
                            @endif
                            @if(isset($billingAddress['phone']))
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">Phone</small>
                                    <p class="mb-0">{{ $billingAddress['phone'] }}</p>
                                </div>
                            @endif
                            @if(isset($billingAddress['address']))
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">Address</small>
                                    <p class="mb-0">{{ $billingAddress['address'] }}</p>
                                </div>
                            @endif
                            @if(isset($billingAddress['city']))
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">City</small>
                                    <p class="mb-0">{{ $billingAddress['city'] }}</p>
                                </div>
                            @endif
                            @if(isset($billingAddress['state']))
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">State</small>
                                    <p class="mb-0">{{ $billingAddress['state'] }}</p>
                                </div>
                            @endif
                            @if(isset($billingAddress['postal_code']))
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">Postal Code</small>
                                    <p class="mb-0">{{ $billingAddress['postal_code'] }}</p>
                                </div>
                            @endif
                            @if(isset($billingAddress['country']))
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">Country</small>
                                    <p class="mb-0">{{ $billingAddress['country'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-white p-3">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2 text-primary"></i>Order Summary</h5>
                </div>
                <div class="card-body">
                    <!-- Subtotal -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span class="fw-bold">Rs {{ number_format($order->total, 2) }}</span>
                    </div>

                    <!-- Discount -->
                    @if($order->discount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount</span>
                            <span class="text-success fw-bold">-Rs {{ number_format($order->discount, 2) }}</span>
                        </div>
                        @if($order->coupon_code)
                            <div class="d-flex justify-content-between mb-3">
                                <small class="text-muted">Coupon Code</small>
                                <small><span class="badge bg-info">{{ $order->coupon_code }}</span></small>
                            </div>
                        @endif
                    @endif

                    <!-- Divider -->
                    <hr class="my-3">

                    <!-- Final Total -->
                    <div class="d-flex justify-content-between mb-3">
                        <span class="h6 mb-0">Total</span>
                        <span class="h6 mb-0 fw-bold">Rs {{ number_format($order->final_total ?? $order->total, 2) }}</span>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Payment Status</small>
                        @if($order->status === 'paid')
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Paid</span>
                        @elseif($order->status === 'completed')
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Completed</span>
                        @elseif($order->status === 'pending')
                            <span class="badge bg-warning"><i class="fas fa-hourglass-half me-1"></i>Pending</span>
                        @elseif($order->status === 'rejected')
                            <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Rejected</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                        @endif
                    </div>

                    @if($order->status === 'rejected' && $order->rejection_reason)
                        <div class="alert alert-danger alert-sm mb-3">
                            <small><strong>Reason:</strong> {{ $order->rejection_reason }}</small>
                        </div>
                    @endif

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Payment Method</small>
                        <p class="mb-0">{{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                    </div>

                    <!-- Order Date -->
                    <div>
                        <small class="text-muted d-block mb-1">Order Date</small>
                        <p class="mb-0">{{ $order->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
