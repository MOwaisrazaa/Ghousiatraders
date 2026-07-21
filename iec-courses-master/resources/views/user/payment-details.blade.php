<x-app-layout>
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2">Payment Details</h1>
            <p class="text-muted">View your transaction history and course purchases</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <!-- Total Spent -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Spent</p>
                                <h5 class="font-weight-bolder mb-0">Rs {{ number_format($totalSpent, 2) }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle p-3">
                                <i class="fas fa-wallet text-white text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Courses -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Courses</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalCourses }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle p-3">
                                <i class="fas fa-book text-white text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paid Courses -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Paid Courses</p>
                                <h5 class="font-weight-bolder mb-0">{{ $paidCourses }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle p-3">
                                <i class="fas fa-check-circle text-white text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Free Courses -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Free Courses</p>
                                <h5 class="font-weight-bolder mb-0">{{ $freeCourses }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle p-3">
                                <i class="fas fa-gift text-white text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white p-3">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2 text-primary"></i>Transaction History</h5>
                </div>
                <div class="card-body p-0">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Amount</th>
                                        <th>Discount</th>
                                        <th>Final Amount</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                        <tr>
                                            <td class="fw-bold">#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @php
                                                    $cartItems = json_decode($order->cart_items, true);
                                                    $itemCount = is_array($cartItems) ? count($cartItems) : 0;
                                                @endphp
                                                {{ $itemCount }} item(s)
                                            </td>
                                            <td>Rs {{ number_format($order->total, 2) }}</td>
                                            <td>Rs {{ number_format($order->discount ?? 0, 2) }}</td>
                                            <td class="fw-bold">Rs {{ number_format($order->final_total ?? $order->total, 2) }}</td>
                                            <td>{{ ucfirst($order->payment_method ?? 'N/A') }}</td>
                                            <td>
                                                @if($order->status === 'paid')
                                                    <span class="badge bg-success">✓ Paid</span>
                                                @elseif($order->status === 'completed')
                                                    <span class="badge bg-success">✓ Completed</span>
                                                @elseif($order->status === 'pending')
                                                    <span class="badge bg-warning">⏳ Pending</span>
                                                @elseif($order->status === 'rejected')
                                                    <span class="badge bg-danger">✗ Rejected</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('user.order.details', $order->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                No orders found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center p-3">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No transactions yet</h5>
                            <p class="text-muted small">Your payment history will appear here once you purchase a course.</p>
                            <p class="text-muted small">If you have purchased courses, they may still be pending admin approval.</p>
                            <a href="{{ route('courses') }}" class="btn btn-primary btn-sm mt-3">
                                <i class="fas fa-search me-1"></i> Browse Courses
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
