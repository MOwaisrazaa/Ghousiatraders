@extends('admin.layout')

@section('title', 'Manage Coupons')

@section('header', 'Coupon Management')

@section('actions')
<a href="{{ route('admin.coupons.create') }}" class="pf-btn-gold">
    <i class="fas fa-plus"></i> Add New Coupon
</a>
@endsection

@section('content')

<style>
.pf-coupon-code {
    font-weight: 700;
    color: #d4a658;
    letter-spacing: 0.08em;
    font-size: 0.95rem;
}
.pf-coupon-desc {
    font-size: 0.78rem;
    color: rgba(248,231,208,0.45);
    margin-top: 2px;
}
.pf-expiry-ok { color: #5fcf6e; font-size: 0.78rem; }
.pf-expiry-bad {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    background: rgba(220,53,69,0.15);
    color: #f07080;
    border: 1px solid rgba(220,53,69,0.25);
}
.pf-uses { color: #f8e7d0; font-size: 0.88rem; }
/* Dropdown dark styling */
.pf-dropdown-menu {
    background: #111 !important;
    border: 1px solid rgba(212,166,88,0.25) !important;
    border-radius: 12px !important;
    padding: 6px 0;
    min-width: 140px;
}
.pf-dropdown-menu .dropdown-item {
    color: #f8e7d0 !important;
    font-size: 0.85rem;
    padding: 8px 16px;
    transition: background 0.15s;
}
.pf-dropdown-menu .dropdown-item:hover {
    background: rgba(212,166,88,0.1) !important;
    color: #d4a658 !important;
}
.pf-dropdown-menu .dropdown-item.text-danger { color: #f07080 !important; }
.pf-dropdown-menu .dropdown-item.text-danger:hover { background: rgba(220,53,69,0.1) !important; color: #f07080 !important; }
.pf-dropdown-toggle {
    background: none !important;
    border: none !important;
    color: rgba(212,166,88,0.7) !important;
    padding: 4px 10px;
    border-radius: 8px;
    transition: color 0.2s, background 0.2s;
}
.pf-dropdown-toggle:hover { color: #d4a658 !important; background: rgba(212,166,88,0.08) !important; }
</style>

    <div class="pf-table-wrap">
        <table class="pf-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Discount</th>
                    <th>Valid Until</th>
                    <th>Uses / Max</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr>
                    <td>
                        <span class="pf-coupon-code">{{ $coupon->code }}</span>
                        @if($coupon->description)
                        <div class="pf-coupon-desc">{{ Str::limit($coupon->description, 30) }}</div>
                        @endif
                    </td>
                    <td>
                        <span style="color:#f8e7d0;font-weight:600;">
                            @if($coupon->type == 'percentage')
                            {{ $coupon->value }}%
                            @elseif($coupon->type == 'fixed')
                            {{ config('app.currency_symbol', '$') }}{{ $coupon->value }}
                            @else
                            Free
                            @endif
                        </span>
                    </td>
                    <td>
                        <span style="color:#f8e7d0;font-size:0.88rem;">{{ $coupon->valid_until->format('M d, Y') }}</span>
                        <div>
                            @if($coupon->valid_until->isPast())
                            <span class="pf-expiry-bad">Expired</span>
                            @else
                            <span class="pf-expiry-ok">{{ $coupon->valid_until->diffForHumans() }}</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="pf-uses">
                            {{ $coupon->uses_count ?? 0 }}
                            @if($coupon->max_uses)
                            / {{ $coupon->max_uses }}
                            @else
                            / ∞
                            @endif
                        </span>
                    </td>
                    <td>
                        @if($coupon->is_active)
                            <span class="pf-badge-active">Active</span>
                        @else
                            <span class="pf-badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="pf-dropdown-toggle"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end pf-dropdown-menu">
                                <a class="dropdown-item" href="{{ route('admin.coupons.edit', $coupon->id) }}">
                                    <i class="fas fa-edit me-2"></i> Edit
                                </a>
                                <a class="dropdown-item text-danger delete-coupon" href="javascript:;"
                                   data-coupon-id="{{ $coupon->id }}" data-coupon-code="{{ $coupon->code }}">
                                    <i class="fas fa-trash me-2"></i> Delete
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="pf-empty">No coupons found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($coupons->hasPages())
    <div style="display:flex;justify-content:center;margin-top:24px;">
        {{ $coupons->links() }}
    </div>
    @endif

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set up delete confirmation
        const deleteLinks = document.querySelectorAll('.delete-coupon');
        deleteLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const couponId = this.getAttribute('data-coupon-id');
                const couponCode = this.getAttribute('data-coupon-code');

                if (confirm(`Are you sure you want to delete coupon "${couponCode}"? This action cannot be undone.`)) {
                    // Create and submit a form to delete the coupon
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/coupons/${couponId}`;
                    form.style.display = 'none';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
