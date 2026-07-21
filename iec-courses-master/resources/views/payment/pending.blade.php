@section('styles')
    <link rel="stylesheet" href="{{ asset('css/payment-pending.css') }}">
@endsection

<x-app-layout>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">


        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">Order Received</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <i class="fas fa-check-circle text-success icon-large"></i>
                                <h3 class="mt-3">Thank You for Your Order!</h3>
                                <p class="lead">Your order has been placed successfully.</p>
                                <div class="alert alert-info">
                                    <p class="mb-0"><strong>Order Number:</strong> #{{ $order->id }}</p>
                                    <p class="mb-0"><strong>Total Amount:</strong> Rs {{ number_format($order->total, 2) }}</p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5>Payment Instructions</h5>
                                <div class="alert alert-warning">
                                    <i class="fas fa-info-circle me-2"></i>
                                    {{ $message }}
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5>Payment Method Details</h5>
                                <div class="card p-3">
                                    @if($paymentMethod)
                                        <div class="d-flex align-items-center">
                                            @if(Str::startsWith($paymentMethod->icon, 'fas '))
                                                <i class="{{ $paymentMethod->icon }} {{ $paymentMethod->details['color'] ?? 'text-primary' }} me-3 icon-medium"></i>
                                            @else
                                                <img src="{{ asset($paymentMethod->icon) }}" alt="{{ $paymentMethod->name }}" class="me-3 payment-method-img">
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $paymentMethod->name }}</h6>
                                                <p class="mb-0">{{ $paymentMethod->description }}</p>

                                                @if($order->payment_method === 'jazzcash' || $order->payment_method === 'easypaisa')
                                                    <p class="mb-0">Account: {{ $paymentMethod->details['account'] ?? 'Contact support for account details' }}</p>
                                                @elseif($order->payment_method === 'banktransfer')
                                                    <p class="mb-0">Bank: {{ $paymentMethod->details['bank_name'] ?? 'Contact support for bank details' }}</p>
                                                    <p class="mb-0">Account Title: {{ $paymentMethod->details['account_title'] ?? '' }}</p>
                                                    <p class="mb-0">Account Number: {{ $paymentMethod->details['account_number'] ?? '' }}</p>
                                                    <p class="mb-0">IBAN: {{ $paymentMethod->details['iban'] ?? '' }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <!-- Fallback for legacy payment methods -->
                                        @if($order->payment_method === 'cash')
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-money-bill-wave text-success me-3 icon-medium"></i>
                                                <div>
                                                    <h6 class="mb-1">Cash Payment</h6>
                                                    <p class="mb-0">Please visit our office at: IEC Courses Office, Floor 3, Building 5, Main Street, Islamabad, Pakistan</p>
                                                </div>
                                            </div>
                                        @elseif($order->payment_method === 'jazzcash')
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-mobile-alt text-danger me-3 icon-medium"></i>
                                                <div>
                                                    <h6 class="mb-1">Jazz Cash</h6>
                                                    <p class="mb-0">Account: +92 333 1234567</p>
                                                    <p class="mb-0">Send screenshot to WhatsApp: +92 312 9876543</p>
                                                </div>
                                            </div>
                                        @elseif($order->payment_method === 'easypaisa')
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-wallet text-warning me-3 icon-medium"></i>
                                                <div>
                                                    <h6 class="mb-1">Easypaisa</h6>
                                                    <p class="mb-0">Account: +92 345 1234567</p>
                                                    <p class="mb-0">Send screenshot to WhatsApp: +92 312 9876543</p>
                                                </div>
                                            </div>
                                        @elseif($order->payment_method === 'banktransfer')
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-university text-primary me-3 icon-medium"></i>
                                                <div>
                                                    <h6 class="mb-1">Bank Transfer</h6>
                                                    <p class="mb-0">Bank: HBL Pakistan</p>
                                                    <p class="mb-0">Account Title: IEC Courses</p>
                                                    <p class="mb-0">Account Number: 1234-5678-9012-3456</p>
                                                    <p class="mb-0">IBAN: PK36HABB0000123456789012</p>
                                                    <p class="mb-0">Send confirmation to WhatsApp: +92 312 9876543</p>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="text-center">
                                <p>After completing your payment, your purchased courses will be available in your account.</p>
                                <a href="{{ route('user.dashboard') }}" class="btn btn-primary">My Courses</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</x-app-layout>
