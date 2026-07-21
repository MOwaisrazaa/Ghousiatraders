@extends('admin.layout')

@section('header', 'Edit Payment Method')

@section('actions')
    <a href="{{ route('admin.payment-methods.index') }}" class="pf-btn-outline">
        <i class="fas fa-arrow-left fa-sm me-1"></i> Back to Payment Methods
    </a>
@endsection

@section('content')

    {{-- Payment Method Preview Card --}}
    <div style="background:rgba(212,166,88,0.06);border:1px solid rgba(212,166,88,0.18);border-radius:16px;padding:24px;margin-bottom:32px;max-width:520px;">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:16px;">
            @if(Str::startsWith($paymentMethod->icon, 'fas '))
                <i class="{{ $paymentMethod->icon }} fa-2x {{ $paymentMethod->details['color'] ?? '' }}" style="color:#d4a658;"></i>
            @else
                <img src="{{ asset($paymentMethod->icon) }}" alt="{{ $paymentMethod->name }}" width="40">
            @endif
            <div>
                <h5 style="margin:0;color:#f8e7d0;font-weight:700;">{{ $paymentMethod->name }}</h5>
                <small style="color:rgba(248,231,208,0.6);">{{ $paymentMethod->description }}</small>
            </div>
        </div>
        <div style="background:rgba(212,166,88,0.08);border:1px solid rgba(212,166,88,0.15);border-radius:10px;padding:14px 16px;">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                <i class="fas fa-info-circle" style="color:#d4a658;font-size:0.9rem;"></i>
                <span style="font-size:0.82rem;font-weight:700;color:#d4a658;letter-spacing:0.06em;">PAYMENT INSTRUCTIONS</span>
            </div>
            <p style="margin:0;color:rgba(248,231,208,0.8);font-size:0.9rem;">{!! nl2br(e($paymentMethod->instructions)) !!}</p>
        </div>
    </div>

    {{-- Edit Form --}}
    <form action="{{ route('admin.payment-methods.update', $paymentMethod->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Section: General Info --}}
        <div style="display:flex;align-items:center;gap:12px;margin:0 0 20px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">General Information</span>
            <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
        </div>

        <div class="pf-row">
            <div class="pf-field">
                <label for="name" class="pf-form-label">Name</label>
                <input type="text" class="pf-input" id="name" name="name" value="{{ old('name', $paymentMethod->name) }}" required>
                @error('name')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="pf-field">
                <label for="icon" class="pf-form-label">Icon (Font Awesome Class)</label>
                <input type="text" class="pf-input" id="icon" name="icon" value="{{ old('icon', $paymentMethod->icon) }}">
                <span class="pf-hint">Example: fas fa-money-bill-wave</span>
                @error('icon')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="pf-field">
            <label for="description" class="pf-form-label">Short Description</label>
            <input type="text" class="pf-input" id="description" name="description" value="{{ old('description', $paymentMethod->description) }}">
            @error('description')
                <div class="pf-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="pf-field">
            <label for="instructions" class="pf-form-label">Instructions</label>
            <textarea class="pf-textarea" id="instructions" name="instructions" rows="5">{{ old('instructions', $paymentMethod->instructions) }}</textarea>
            @error('instructions')
                <div class="pf-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Payment Method specific fields --}}
        @if($paymentMethod->key === 'jazzcash' || $paymentMethod->key === 'easypaisa')

        <div style="display:flex;align-items:center;gap:12px;margin:28px 0 20px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Account Details</span>
            <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
        </div>

        <div class="pf-row">
            <div class="pf-field">
                <label for="account_number" class="pf-form-label">Account Number</label>
                <input type="text" class="pf-input" id="account_number" name="account_number"
                    value="{{ old('account_number', $paymentMethod->details['account'] ?? '') }}" required>
                @error('account_number')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        @endif

        @if($paymentMethod->key === 'banktransfer')

        <div style="display:flex;align-items:center;gap:12px;margin:28px 0 20px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Bank Transfer Details</span>
            <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
        </div>

        <div class="pf-row">
            <div class="pf-field">
                <label for="bank_name" class="pf-form-label">Bank Name</label>
                <input type="text" class="pf-input" id="bank_name" name="bank_name"
                    value="{{ old('bank_name', $paymentMethod->details['bank_name'] ?? '') }}" required>
                @error('bank_name')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="pf-field">
                <label for="account_title" class="pf-form-label">Account Title</label>
                <input type="text" class="pf-input" id="account_title" name="account_title"
                    value="{{ old('account_title', $paymentMethod->details['account_title'] ?? '') }}" required>
                @error('account_title')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="pf-row">
            <div class="pf-field">
                <label for="account_number" class="pf-form-label">Account Number</label>
                <input type="text" class="pf-input" id="account_number" name="account_number"
                    value="{{ old('account_number', $paymentMethod->details['account_number'] ?? '') }}" required>
                @error('account_number')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="pf-field">
                <label for="iban" class="pf-form-label">IBAN</label>
                <input type="text" class="pf-input" id="iban" name="iban"
                    value="{{ old('iban', $paymentMethod->details['iban'] ?? '') }}">
                @error('iban')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        @endif

        @if($paymentMethod->key === 'card')

        <div style="display:flex;align-items:center;gap:12px;margin:28px 0 20px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Card Processor</span>
            <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
        </div>

        <div class="pf-row">
            <div class="pf-field">
                <label for="processor" class="pf-form-label">Payment Processor</label>
                <select class="pf-select-field" id="processor" name="processor">
                    <option value="stripe" {{ (old('processor', $paymentMethod->details['processor'] ?? '') === 'stripe') ? 'selected' : '' }}>Stripe</option>
                    <option value="paypal" {{ (old('processor', $paymentMethod->details['processor'] ?? '') === 'paypal') ? 'selected' : '' }}>PayPal</option>
                    <option value="other" {{ (old('processor', $paymentMethod->details['processor'] ?? '') === 'other') ? 'selected' : '' }}>Other</option>
                </select>
                @error('processor')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>
        </div>
        @endif

        {{-- Section: Settings --}}
        <div style="display:flex;align-items:center;gap:12px;margin:28px 0 20px;">
            <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Settings</span>
            <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
        </div>

        <div class="pf-row">
            <div class="pf-field">
                <label for="sort_order" class="pf-form-label">Sort Order</label>
                <input type="number" class="pf-input" id="sort_order" name="sort_order"
                    value="{{ old('sort_order', $paymentMethod->sort_order) }}" min="0">
                @error('sort_order')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="pf-field">
                <label for="is_active" class="pf-form-label">Status</label>
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;margin-top:6px;">
                    <input type="checkbox" role="switch" id="is_active" name="is_active" value="1"
                        {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}
                        style="accent-color:#d4a658;width:18px;height:18px;cursor:pointer;">
                    <span class="{{ $paymentMethod->is_active ? 'pf-badge-active' : 'pf-badge-inactive' }}">
                        {{ $paymentMethod->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </label>
                @error('is_active')
                    <div class="pf-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="pf-form-actions">
            <button type="submit" class="pf-btn-gold">
                <i class="fas fa-save me-1"></i> Save Changes
            </button>
            <a href="{{ route('admin.payment-methods.index') }}" class="pf-btn-cancel">
                <i class="fas fa-times me-1"></i> Cancel
            </a>
        </div>

    </form>

@endsection
