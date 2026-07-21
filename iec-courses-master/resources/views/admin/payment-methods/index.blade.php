@extends('admin.layout')

@section('header', 'Payment Methods')

@section('content')

    {{-- Instructions box --}}
    <div class="pf-info-note" style="background:rgba(212,166,88,0.07);border:1px solid rgba(212,166,88,0.22);border-radius:14px;padding:16px 20px;margin-bottom:24px;color:#f8e7d0;">
        <div style="display:flex;align-items:flex-start;gap:10px;">
            <i class="fas fa-info-circle" style="color:#d4a658;margin-top:3px;font-size:1rem;"></i>
            <div>
                <strong style="color:#d4a658;">Instructions:</strong>
                <ul style="margin:8px 0 0 0;padding-left:18px;color:rgba(248,231,208,0.8);">
                    <li>Toggle the switch to enable/disable a payment method</li>
                    <li><strong style="color:#f8e7d0;">Only active payment methods will be visible to users during checkout</strong></li>
                    <li>Changes take effect immediately after toggling</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="pf-table-wrap">
        <table class="pf-table" id="paymentMethodsTable">
            <thead>
                <tr>
                    <th width="20%">Name</th>
                    <th width="25%">Description</th>
                    <th width="30%">Details</th>
                    <th width="10%">Status</th>
                    <th width="15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paymentMethods as $method)
                <tr data-id="{{ $method->id }}">
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div>
                                @if(Str::startsWith($method->icon, 'fas '))
                                    <i class="{{ $method->icon }} fa-lg {{ $method->details['color'] ?? '' }}"></i>
                                @else
                                    <img src="{{ asset($method->icon) }}" alt="{{ $method->name }}" width="24">
                                @endif
                            </div>
                            <span style="color:#f8e7d0;font-weight:500;">{{ $method->name }}</span>
                        </div>
                    </td>
                    <td style="color:rgba(248,231,208,0.75);">{{ $method->description }}</td>
                    <td>
                        @if($method->key === 'cash')
                            <small style="color:rgba(248,231,208,0.6);">Cash payment at office</small>
                        @elseif($method->key === 'jazzcash')
                            <small style="color:rgba(248,231,208,0.6);">Account: {{ $method->details['account'] ?? 'Not set' }}</small>
                        @elseif($method->key === 'easypaisa')
                            <small style="color:rgba(248,231,208,0.6);">Account: {{ $method->details['account'] ?? 'Not set' }}</small>
                        @elseif($method->key === 'banktransfer')
                            <small style="color:rgba(248,231,208,0.6);">
                                Bank: {{ $method->details['bank_name'] ?? 'Not set' }}<br>
                                Account: {{ $method->details['account_number'] ?? 'Not set' }}
                            </small>
                        @elseif($method->key === 'card')
                            <small style="color:rgba(248,231,208,0.6);">Payment processor: {{ ucfirst($method->details['processor'] ?? 'stripe') }}</small>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.payment-methods.toggle-status', $method->id) }}" method="POST" class="toggle-status-form">
                            @csrf
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                                <input class="payment-toggle" type="checkbox" role="switch" id="status-{{ $method->id }}"
                                    {{ $method->is_active ? 'checked' : '' }}
                                    style="accent-color:#d4a658;width:18px;height:18px;cursor:pointer;">
                                <span class="{{ $method->is_active ? 'pf-badge-active' : 'pf-badge-inactive' }}">
                                    {{ $method->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </label>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('admin.payment-methods.edit', $method->id) }}" class="pf-btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
<script >
    document.addEventListener('DOMContentLoaded', function() {
        // Handle payment method toggle switches
        const toggles = document.querySelectorAll('.payment-toggle');
        
        toggles.forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                // Submit the form when toggle is changed
                this.closest('form').submit();
            });
        });
    });
</script>
@endpush
