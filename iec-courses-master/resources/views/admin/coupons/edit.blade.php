@extends('admin.layout')

@section('title', 'Edit Coupon')

@section('header', 'Edit Coupon')

@section('actions')
<a href="{{ route('admin.coupons.index') }}" class="pf-btn-outline">
    <i class="fas fa-arrow-left"></i> Back to Coupons
</a>
@endsection

@section('content')

<style>
.pf-generate-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0 18px;
    border-radius: 0 10px 10px 0;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    background: linear-gradient(135deg, #d4a658 0%, #b8892e 100%);
    color: #0a0a0a;
    border: none;
    cursor: pointer;
    transition: opacity 0.2s;
    white-space: nowrap;
}
.pf-generate-btn:hover { opacity: 0.88; }
.pf-input-group {
    display: flex;
    align-items: stretch;
}
.pf-input-group .pf-input {
    border-radius: 10px 0 0 10px !important;
    flex: 1;
}
.pf-usage-info {
    background: rgba(212,166,88,0.07);
    border: 1px solid rgba(212,166,88,0.18);
    border-radius: 12px;
    padding: 14px 20px;
    color: #f8e7d0;
    font-size: 0.9rem;
    margin-bottom: 22px;
}
.pf-usage-info strong { color: #d4a658; }
</style>

    <div style="background:rgba(10,10,10,0.82);border:1px solid rgba(212,166,88,0.16);border-radius:20px;padding:32px 36px;">
        <form method="POST" action="{{ route('admin.coupons.update', $coupon->id) }}">
            @csrf
            @method('PUT')

            {{-- Section: Coupon Identity --}}
            <div style="display:flex;align-items:center;gap:12px;margin:0 0 22px;">
                <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Coupon Identity</span>
                <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
            </div>

            <div class="pf-row">
                <div class="pf-field">
                    <label for="code" class="pf-form-label">Coupon Code</label>
                    <div class="pf-input-group">
                        <input type="text" class="pf-input @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $coupon->code) }}" required placeholder="e.g. SUMMER2023">
                        <button type="button" class="pf-generate-btn" id="generateCodeBtn">
                            <i class="fas fa-random"></i> Generate
                        </button>
                    </div>
                    @error('code')
                        <span class="pf-error">{{ $message }}</span>
                    @enderror
                    <span class="pf-hint">Enter a unique code for the coupon (e.g. SUMMER2023).</span>
                </div>

                <div class="pf-field">
                    <label for="type" class="pf-form-label">Discount Type</label>
                    <select class="pf-select-field @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="percentage" {{ old('type', $coupon->type) == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                        <option value="free" {{ old('type', $coupon->type) == 'free' ? 'selected' : '' }}>Free (100% off)</option>
                    </select>
                    @error('type')
                        <span class="pf-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Section: Discount Settings --}}
            <div style="display:flex;align-items:center;gap:12px;margin:8px 0 22px;">
                <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Discount Settings</span>
                <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
            </div>

            <div class="pf-row">
                <div class="pf-field">
                    <label for="value" class="pf-form-label">Discount Value</label>
                    <input type="number" step="0.01" min="0" class="pf-input @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value', $coupon->value) }}" required placeholder="0">
                    @error('value')
                        <span class="pf-error">{{ $message }}</span>
                    @enderror
                    <span class="pf-hint">For percentage: 1–100. For fixed amount: currency value.</span>
                </div>

                <div class="pf-field">
                    <label for="max_uses" class="pf-form-label">Maximum Uses</label>
                    <input type="number" min="0" class="pf-input @error('max_uses') is-invalid @enderror" id="max_uses" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}" placeholder="Unlimited">
                    @error('max_uses')
                        <span class="pf-error">{{ $message }}</span>
                    @enderror
                    <span class="pf-hint">Leave empty for unlimited uses.</span>
                </div>
            </div>

            {{-- Section: Validity Period --}}
            <div style="display:flex;align-items:center;gap:12px;margin:8px 0 22px;">
                <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Validity Period</span>
                <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
            </div>

            <div class="pf-row">
                <div class="pf-field">
                    <label for="valid_from" class="pf-form-label">Valid From</label>
                    <input type="datetime-local" class="pf-input @error('valid_from') is-invalid @enderror" id="valid_from" name="valid_from" value="{{ old('valid_from', $coupon->valid_from->format('Y-m-d\TH:i')) }}" required>
                    @error('valid_from')
                        <span class="pf-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="pf-field">
                    <label for="valid_until" class="pf-form-label">Valid Until</label>
                    <input type="datetime-local" class="pf-input @error('valid_until') is-invalid @enderror" id="valid_until" name="valid_until" value="{{ old('valid_until', $coupon->valid_until->format('Y-m-d\TH:i')) }}" required>
                    @error('valid_until')
                        <span class="pf-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Section: Status & Description --}}
            <div style="display:flex;align-items:center;gap:12px;margin:8px 0 22px;">
                <span style="font-size:0.72rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:#d4a658;">Status &amp; Notes</span>
                <div style="flex:1;height:1px;background:rgba(212,166,88,0.15);"></div>
            </div>

            <div class="pf-field">
                <div class="pf-switch-wrap">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $coupon->is_active ? 'checked' : '' }} style="accent-color:#d4a658;width:18px;height:18px;">
                        @if($coupon->is_active)
                            <span class="pf-badge-active">Active</span>
                        @else
                            <span class="pf-badge-inactive">Inactive</span>
                        @endif
                    </label>
                </div>
                @error('is_active')
                    <span class="pf-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="pf-field">
                <label for="description" class="pf-form-label">Description (Optional)</label>
                <textarea class="pf-textarea @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Short description of the coupon…">{{ old('description', $coupon->description) }}</textarea>
                @error('description')
                    <span class="pf-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Current Usage Info --}}
            <div class="pf-usage-info">
                <strong>Current Usage:</strong> {{ $coupon->uses_count ?? 0 }} {{ Str::plural('time', $coupon->uses_count ?? 0) }}
            </div>

            <div class="pf-form-actions">
                <a href="{{ route('admin.coupons.index') }}" class="pf-btn-cancel">Cancel</a>
                <button type="submit" class="pf-btn-gold">Update Coupon</button>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Generate random coupon code
        document.getElementById('generateCodeBtn').addEventListener('click', function() {
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let code = '';
            const length = 8;

            for (let i = 0; i < length; i++) {
                code += characters.charAt(Math.floor(Math.random() * characters.length));
            }

            document.getElementById('code').value = code;
        });

        // Set discount value field based on selected type
        document.getElementById('type').addEventListener('change', function() {
            const valueField = document.getElementById('value');
            if (this.value === 'free') {
                valueField.value = 100;
                valueField.setAttribute('readonly', true);
            } else {
                valueField.removeAttribute('readonly');
            }
        });

        // Initialize the type value handler
        const typeSelect = document.getElementById('type');
        if (typeSelect.value === 'free') {
            document.getElementById('value').setAttribute('readonly', true);
        }
    });
</script>
@endsection
