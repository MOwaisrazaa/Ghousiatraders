@extends('admin.ghousia-layout')

@section('title', 'Admin Dashboard - Coupons')

@section('content')
<style>
    /* Stats row for 5 cards */
    .coupons-stats-row {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
        width: 100%;
        box-sizing: border-box;
    }

    .coupons-stat-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 16px;
        padding: 16px;
        box-shadow: var(--gt-shadow);
        display: flex;
        align-items: flex-start;
        gap: 12px;
        min-height: 110px;
        min-width: 0;
        box-sizing: border-box;
    }

    .coupons-stat-icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .coupons-stat-icon-wrapper i {
        width: 20px;
        height: 20px;
    }

    .coupons-stat-meta {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
        min-width: 0;
    }

    .coupons-stat-title {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--gt-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    .coupons-stat-count {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--gt-text);
        line-height: 1.1;
        margin-bottom: 4px;
    }

    .coupons-stat-desc {
        font-size: 0.68rem;
        font-weight: 600;
        color: var(--gt-text-muted);
    }

    /* Colors and Accents for Stats */
    .stat-accent-total { background: #f3f4f6; color: #4b5563; }
    .stat-accent-active { background: #ecfdf5; color: #047857; }
    .stat-accent-scheduled { background: #fffbeb; color: #b45309; }
    .stat-accent-expired { background: #fef2f2; color: #b91c1c; }
    .stat-accent-usage { background: #fff8ee; color: var(--gt-primary); }

    /* Coupons container card */
    .coupons-container-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--gt-shadow);
        box-sizing: border-box;
        width: 100%;
        min-width: 0;
        margin-bottom: 24px;
    }

    /* Filters Bar */
    .filters-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-input-wrapper {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .search-input-wrapper input {
        width: 100%;
        padding-right: 40px;
    }

    .search-input-wrapper i {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gt-text-muted);
        width: 16px;
        height: 16px;
        pointer-events: none;
    }

    .gt-input {
        background: #fffdf9;
        border: 1.5px solid var(--gt-border);
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 0.85rem;
        color: var(--gt-text);
        outline: none;
        transition: all 0.2s;
        box-sizing: border-box;
        min-height: 38px;
    }

    .gt-input:focus {
        border-color: #d7a64a;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(215, 166, 74, 0.1);
    }

    .gt-btn-filter {
        background: #fffdf9;
        border: 1.5px solid var(--gt-border);
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 0.85rem;
        color: var(--gt-text);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        transition: all 0.2s;
        min-height: 38px;
    }

    .gt-btn-filter:hover {
        background-color: var(--gt-primary-light);
        border-color: #d7a64a;
    }

    /* Table styles */
    .gt-table-wrap {
        border: 1.5px solid var(--gt-border);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 20px;
        background: #ffffff;
        box-sizing: border-box;
    }

    .gt-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.82rem;
    }

    .gt-table th {
        background-color: #fffaf3;
        color: var(--gt-text);
        font-weight: 800;
        padding: 12px 14px;
        border-bottom: 1.5px solid var(--gt-border);
        white-space: nowrap;
    }

    .gt-table td {
        padding: 12px 14px;
        border-bottom: 1px solid rgba(215, 166, 74, 0.1);
        color: var(--gt-text);
        vertical-align: middle;
    }

    .gt-table tr:last-child td {
        border-bottom: none;
    }

    /* Coupon code label block */
    .coupon-code-badge {
        font-family: monospace;
        font-size: 0.85rem;
        font-weight: 800;
        color: var(--gt-primary);
        background: var(--gt-primary-light);
        border: 1px dashed rgba(215, 166, 74, 0.4);
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    /* Group Type Badges */
    .badge-type {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        text-align: center;
        display: inline-block;
        text-transform: uppercase;
        border: 1px solid transparent;
        white-space: nowrap;
    }

    .type-percentage {
        background-color: #fef3c7;
        color: #d97706;
        border-color: rgba(217, 119, 6, 0.2);
    }

    .type-fixed {
        background-color: #dbeafe;
        color: #1d4ed8;
        border-color: rgba(29, 78, 216, 0.15);
    }

    .type-free {
        background-color: #f3e8ff;
        color: #7c3aed;
        border-color: rgba(124, 58, 237, 0.15);
    }

    /* Status Badges */
    .badge-status {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
        text-transform: capitalize;
        white-space: nowrap;
    }

    .status-active { background-color: #ecfdf5; color: #047857; }
    .status-scheduled { background-color: #fffbeb; color: #b45309; }
    .status-expired { background-color: #fef2f2; color: #b91c1c; }
    .status-inactive { background-color: #f3f4f6; color: #6b7280; }

    /* Progress bar */
    .progress-bar-container {
        width: 100%;
        background-color: #f3f4f6;
        border-radius: 99px;
        height: 6px;
        overflow: hidden;
        margin-top: 4px;
    }

    .progress-bar-fill {
        background-color: var(--gt-primary);
        height: 100%;
        border-radius: 99px;
    }

    .progress-bar-fill.fill-active { background-color: #10b981; }

    /* Actions Row */
    .row-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-action-icon {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--gt-text-muted);
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .btn-action-icon:hover {
        background-color: var(--gt-primary-light);
        color: var(--gt-primary);
    }

    /* Modals */
    .gt-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(53, 27, 13, 0.4);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 500;
        padding: 20px;
        box-sizing: border-box;
    }

    .gt-modal.show {
        display: flex;
    }

    .gt-modal-dialog {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 20px;
        width: 100%;
        max-width: 720px;
        box-shadow: 0 20px 50px rgba(53, 27, 13, 0.15);
        display: flex;
        flex-direction: column;
        max-height: 90vh;
        animation: modalFadeIn 0.3s ease;
        box-sizing: border-box;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .gt-modal-header {
        padding: 16px 24px;
        border-bottom: 1.5px solid var(--gt-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .gt-modal-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--gt-primary);
    }

    .gt-modal-close {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--gt-text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        transition: background 0.2s;
    }

    .gt-modal-close:hover {
        background-color: var(--gt-primary-light);
    }

    .gt-modal-body {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
        box-sizing: border-box;
    }

    .gt-modal-footer {
        padding: 16px 24px;
        border-top: 1.5px solid var(--gt-border);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-group-full {
        margin-bottom: 16px;
    }

    .gt-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--gt-text);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .gt-btn-primary {
        background: linear-gradient(135deg, var(--gt-primary), #633618);
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 10px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
        min-height: 38px;
    }

    .gt-btn-primary:hover { opacity: 0.95; }

    .gt-btn-outline {
        background: transparent;
        color: var(--gt-primary);
        border: 1.5px solid var(--gt-border);
        padding: 10px 20px;
        font-size: 0.85rem;
        font-weight: 700;
        border-radius: 10px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
        min-height: 38px;
    }

    .gt-btn-outline:hover {
        background-color: var(--gt-primary-light);
        border-color: #d7a64a;
    }

    /* Action Dropdown Menu */
    .action-dropdown-container {
        position: relative;
    }

    .action-dropdown-menu {
        position: absolute;
        right: 0;
        top: 32px;
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(53, 27, 13, 0.08);
        min-width: 170px;
        z-index: 80;
        display: none;
        flex-direction: column;
        padding: 4px;
    }

    .action-dropdown-menu.show {
        display: flex;
    }

    .action-dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        color: var(--gt-text);
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 6px;
        transition: background 0.2s;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .action-dropdown-item:hover {
        background-color: var(--gt-primary-light);
        color: var(--gt-primary);
    }

    .action-dropdown-item.text-danger:hover {
        background-color: #fef2f2;
        color: var(--gt-danger);
    }

    /* Pagination */
    .pagination-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .pagination-info {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--gt-text-muted);
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pagination-btn {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 8px;
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gt-text);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .pagination-btn:hover {
        background-color: var(--gt-primary-light);
        border-color: #d7a64a;
    }

    .pagination-btn.active {
        background: var(--gt-primary);
        color: #ffffff;
        border-color: var(--gt-primary);
    }

    .pagination-btn.disabled {
        opacity: 0.5;
        pointer-events: none;
    }

    /* Bottom strip benefits */
    .benefits-strip-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;
        width: 100%;
        box-sizing: border-box;
    }

    .benefit-item-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 16px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: var(--gt-shadow);
        box-sizing: border-box;
    }

    .benefit-icon-wrapper {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background-color: var(--gt-primary-light);
        color: var(--gt-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .benefit-icon-wrapper i {
        width: 18px;
        height: 18px;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .coupons-stats-row {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .benefits-strip-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .coupons-stats-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .coupons-stats-row {
            grid-template-columns: 1fr;
        }
        .benefits-strip-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Sub navigation header -->
<div class="sub-nav-bar" style="margin-bottom: 24px;">
    <div class="sub-nav-left">
        <h1 class="page-title">Coupons</h1>
        <div class="breadcrumbs-list">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <i data-lucide="chevron-right"></i>
            <span>Coupons</span>
        </div>
    </div>
    <div class="sub-nav-right">
        <button class="gt-btn-primary" onclick="openModal('addCouponModal')">
            <i data-lucide="plus"></i> Add New Coupon
        </button>
    </div>
</div>

<!-- Coupon Statistics Row -->
<div class="coupons-stats-row">
    <!-- Stat Card 1 -->
    <div class="coupons-stat-card">
        <div class="coupons-stat-icon-wrapper stat-accent-total">
            <i data-lucide="ticket"></i>
        </div>
        <div class="coupons-stat-meta">
            <div>
                <div class="coupons-stat-title">Total Coupons</div>
                <div class="coupons-stat-count">{{ $totalCouponsCount }}</div>
            </div>
            <div class="coupons-stat-desc">All time coupons</div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="coupons-stat-card">
        <div class="coupons-stat-icon-wrapper stat-accent-active">
            <i data-lucide="check-circle-2"></i>
        </div>
        <div class="coupons-stat-meta">
            <div>
                <div class="coupons-stat-title">Active Coupons</div>
                <div class="coupons-stat-count">{{ $activeCouponsCount }}</div>
            </div>
            <div class="coupons-stat-desc">Currently active</div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="coupons-stat-card">
        <div class="coupons-stat-icon-wrapper stat-accent-scheduled">
            <i data-lucide="hourglass"></i>
        </div>
        <div class="coupons-stat-meta">
            <div>
                <div class="coupons-stat-title">Scheduled Coupons</div>
                <div class="coupons-stat-count">{{ $scheduledCouponsCount }}</div>
            </div>
            <div class="coupons-stat-desc">Starts in future</div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="coupons-stat-card">
        <div class="coupons-stat-icon-wrapper stat-accent-expired">
            <i data-lucide="pause-circle"></i>
        </div>
        <div class="coupons-stat-meta">
            <div>
                <div class="coupons-stat-title">Expired Coupons</div>
                <div class="coupons-stat-count">{{ $expiredCouponsCount }}</div>
            </div>
            <div class="coupons-stat-desc">No longer valid</div>
        </div>
    </div>

    <!-- Stat Card 5 -->
    <div class="coupons-stat-card">
        <div class="coupons-stat-icon-wrapper stat-accent-usage">
            <i data-lucide="x-circle"></i>
        </div>
        <div class="coupons-stat-meta">
            <div>
                <div class="coupons-stat-title">Usage (This Month)</div>
                <div class="coupons-stat-count">{{ number_format($usageThisMonthCount) }}</div>
            </div>
            <div class="coupons-stat-desc">Total times used</div>
        </div>
    </div>
</div>

<!-- Coupons Card -->
<div class="coupons-container-card">
    <form action="{{ route('admin.coupons.index') }}" method="GET" id="filterForm">
        <!-- Filters Row -->
        <div class="filters-bar">
            <select name="status" class="gt-input" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <select name="type" class="gt-input" onchange="this.form.submit()">
                <option value="">All Types</option>
                <option value="percentage" {{ request('type') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                <option value="free" {{ request('type') === 'free' ? 'selected' : '' }}>Free Shipping</option>
            </select>

            <div class="search-input-wrapper">
                <input type="text" name="search" value="{{ request('search') }}" class="gt-input" placeholder="Search by coupon code or name..." onkeypress="if(event.key === 'Enter') this.form.submit();">
                <i data-lucide="search"></i>
            </div>

            <button type="submit" class="gt-btn-filter">
                <i data-lucide="filter"></i> Filters
            </button>

            <div style="flex:1; display:flex; align-items:center; justify-content:flex-end; gap:8px;">
                <span style="font-size:0.78rem;font-weight:700;color:var(--gt-text-muted);">Sort By:</span>
                <select name="sort" class="gt-input" style="padding:4px 8px;min-height:30px;font-size:0.8rem;" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="expiring_soon" {{ request('sort') === 'expiring_soon' ? 'selected' : '' }}>Expiring Soon</option>
                    <option value="most_used" {{ request('sort') === 'most_used' ? 'selected' : '' }}>Most Used</option>
                    <option value="least_used" {{ request('sort') === 'least_used' ? 'selected' : '' }}>Least Used</option>
                    <option value="highest_discount" {{ request('sort') === 'highest_discount' ? 'selected' : '' }}>Highest Discount</option>
                </select>
            </div>
            
            <input type="hidden" name="per_page" id="formPerPageInput" value="{{ request('per_page', 10) }}">
        </div>
    </form>

    <!-- Table Grid -->
    <div class="gt-table-wrap">
        <div style="overflow-x:auto;">
            <table class="gt-table">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            <input type="checkbox" id="selectAllCheckbox">
                        </th>
                        <th>Coupon</th>
                        <th>Type</th>
                        <th>Discount</th>
                        <th>Min. Order</th>
                        <th>Usage</th>
                        <th>Validity</th>
                        <th>Status</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        @php
                            // Evaluate computed status
                            $now = now();
                            $status = 'inactive';
                            if (!$coupon->is_active) {
                                $status = 'inactive';
                            } elseif ($now->lt($coupon->valid_from)) {
                                $status = 'scheduled';
                            } elseif ($now->gt($coupon->valid_until)) {
                                $status = 'expired';
                            } else {
                                $status = 'active';
                            }
                            
                            // Format discount values
                            $discountStr = '';
                            if ($coupon->type === 'percentage') {
                                $discountStr = number_format($coupon->value, 0) . '% OFF';
                            } elseif ($coupon->type === 'fixed') {
                                $discountStr = 'PKR ' . number_format($coupon->value);
                            } else {
                                $discountStr = 'Free Shipping';
                            }

                            // Calculate progress bar values
                            $uses = $coupon->uses_count;
                            $limit = $coupon->max_uses;
                            $limitStr = $limit ? number_format($limit) : '∞';
                            $percentage = 0;
                            if ($limit > 0) {
                                $percentage = min(100, round(($uses / $limit) * 100));
                            }
                            
                            $couponData = [
                                'id' => $coupon->id,
                                'code' => $coupon->code,
                                'type' => $coupon->type,
                                'value' => $coupon->value,
                                'min_order_amount' => $coupon->min_order_amount,
                                'max_discount_amount' => $coupon->max_discount_amount,
                                'max_uses' => $coupon->max_uses,
                                'per_user_limit' => $coupon->per_user_limit,
                                'valid_from' => $coupon->valid_from ? $coupon->valid_from->format('Y-m-d\TH:i') : '',
                                'valid_until' => $coupon->valid_until ? $coupon->valid_until->format('Y-m-d\TH:i') : '',
                                'description' => $coupon->description,
                                'is_active' => $coupon->is_active,
                                'new_customers_only' => $coupon->new_customers_only,
                                'free_shipping' => $coupon->free_shipping,
                                'selected_products' => $coupon->selected_products ?? [],
                                'selected_categories' => $coupon->selected_categories ?? [],
                                'excluded_products' => $coupon->excluded_products ?? [],
                                'excluded_categories' => $coupon->excluded_categories ?? []
                            ];
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" class="item-row-checkbox">
                            </td>
                            <td>
                                <div style="display:flex;flex-direction:column;gap:4px;">
                                    <div class="coupon-code-badge">{{ $coupon->code }}</div>
                                    <span style="font-size:0.75rem;color:var(--gt-text-muted);">{{ $coupon->description ?: 'No description provided.' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-type {{ $coupon->type === 'percentage' ? 'type-percentage' : ($coupon->type === 'fixed' ? 'type-fixed' : 'type-free') }}">
                                    {{ $coupon->type === 'free' ? 'free shipping' : $coupon->type }}
                                </span>
                            </td>
                            <td style="font-weight:800;color:var(--gt-primary);">{{ $discountStr }}</td>
                            <td style="font-weight:700;">PKR {{ number_format($coupon->min_order_amount) }}</td>
                            <td>
                                <div style="display:flex;flex-direction:column;width:120px;">
                                    <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.72rem;font-weight:700;color:var(--gt-text-muted);">
                                        <span>{{ $uses }} / {{ $limitStr }}</span>
                                        @if($limit > 0)
                                            <span>{{ $percentage }}%</span>
                                        @endif
                                    </div>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar-fill {{ $status === 'active' ? 'fill-active' : '' }}" style="width: {{ $limit > 0 ? $percentage : 100 }}%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;flex-direction:column;line-height:1.2;white-space:nowrap;">
                                    <span style="font-size:0.75rem;"><strong style="font-weight:700;">Start:</strong> {{ $coupon->valid_from->format('M d, Y') }}</span>
                                    <span style="font-size:0.75rem;"><strong style="font-weight:700;">End:</strong> {{ $coupon->valid_until->format('M d, Y') }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-status {{ $status === 'active' ? 'status-active' : ($status === 'scheduled' ? 'status-scheduled' : ($status === 'expired' ? 'status-expired' : 'status-inactive')) }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <div class="row-actions" style="justify-content:flex-end;">
                                    <button class="btn-action-icon" onclick='openEditModal({!! json_encode($couponData) !!})' title="Edit Coupon">
                                        <i data-lucide="edit-3" style="width:14px;height:14px;"></i>
                                    </button>
                                    
                                    <div class="action-dropdown-container">
                                        <button type="button" class="btn-action-icon" onclick="toggleRowMenu(event, 'dropmenu-{{ $coupon->id }}')">
                                            <i data-lucide="more-vertical" style="width:14px;height:14px;"></i>
                                        </button>
                                        <div class="action-dropdown-menu" id="dropmenu-{{ $coupon->id }}">
                                            <button class="action-dropdown-item" type="button" onclick='openEditModal({!! json_encode($couponData) !!})'>
                                                <i data-lucide="edit-2" style="width:12px;height:12px;"></i> Edit Coupon
                                            </button>
                                            
                                            <form action="{{ route('admin.coupons.duplicate', $coupon->id) }}" method="POST" style="display:none;" id="duplicateForm-{{ $coupon->id }}">@csrf</form>
                                            <button class="action-dropdown-item" type="button" onclick="document.getElementById('duplicateForm-{{ $coupon->id }}').submit()">
                                                <i data-lucide="copy" style="width:12px;height:12px;"></i> Duplicate Coupon
                                            </button>
                                            
                                            <form action="{{ route('admin.coupons.toggle-status', $coupon->id) }}" method="POST" style="display:none;" id="statusForm-{{ $coupon->id }}">@csrf</form>
                                            <button class="action-dropdown-item" type="button" onclick="document.getElementById('statusForm-{{ $coupon->id }}').submit()">
                                                <i data-lucide="power" style="width:12px;height:12px;"></i> Activate/Deactivate
                                            </button>

                                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" style="display:none;" id="deleteForm-{{ $coupon->id }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button class="action-dropdown-item text-danger" type="button" onclick="confirmCouponDelete({{ $coupon->id }}, '{{ addslashes($coupon->code) }}')">
                                                <i data-lucide="trash-2" style="width:12px;height:12px;color:var(--gt-danger);"></i> Delete Coupon
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;padding:30px;color:var(--gt-text-muted);font-weight:600;">No coupons found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-row">
        <div class="pagination-info">
            Showing {{ $coupons->firstItem() ?? 0 }} to {{ $coupons->lastItem() ?? 0 }} of {{ $coupons->total() }} coupons
        </div>

        <div class="pagination-controls">
            <div style="display:flex;align-items:center;gap:8px;margin-right:16px;">
                <select class="gt-input" style="padding:4px 8px;min-height:30px;font-size:0.8rem;" onchange="setPerPage(this.value)">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span style="font-size:0.8rem;color:var(--gt-text-muted);font-weight:600;">per page</span>
            </div>

            @if ($coupons->hasPages())
                <a href="{{ $coupons->previousPageUrl() }}" class="pagination-btn {{ $coupons->onFirstPage() ? 'disabled' : '' }}">
                    <i data-lucide="chevron-left" style="width:16px;height:16px;"></i>
                </a>
                
                @foreach ($coupons->getUrlRange(max(1, $coupons->currentPage() - 2), min($coupons->lastPage(), $coupons->currentPage() + 2)) as $page => $url)
                    <a href="{{ $url }}" class="pagination-btn {{ $page == $coupons->currentPage() ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach

                <a href="{{ $coupons->nextPageUrl() }}" class="pagination-btn {{ !$coupons->hasMorePages() ? 'disabled' : '' }}">
                    <i data-lucide="chevron-right" style="width:16px;height:16px;"></i>
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Bottom benefit cards strip -->
<div class="benefits-strip-grid" style="margin-bottom: 30px;">
    <div class="benefit-item-card">
        <div class="benefit-icon-wrapper">
            <i data-lucide="trending-up"></i>
        </div>
        <div style="display:flex;flex-direction:column;line-height:1.2;">
            <strong style="font-size:0.85rem;color:var(--gt-text);font-weight:800;">Increase Conversions</strong>
            <span style="font-size:0.7rem;color:var(--gt-text-muted);">Encourage buyers to complete cart purchases.</span>
        </div>
    </div>
    <div class="benefit-item-card">
        <div class="benefit-icon-wrapper">
            <i data-lucide="refresh-cw"></i>
        </div>
        <div style="display:flex;flex-direction:column;line-height:1.2;">
            <strong style="font-size:0.85rem;color:var(--gt-text);font-weight:800;">Drive Repeat Orders</strong>
            <span style="font-size:0.7rem;color:var(--gt-text-muted);">Reward loyal customers with coupon codes.</span>
        </div>
    </div>
    <div class="benefit-item-card">
        <div class="benefit-icon-wrapper">
            <i data-lucide="bar-chart-3"></i>
        </div>
        <div style="display:flex;flex-direction:column;line-height:1.2;">
            <strong style="font-size:0.85rem;color:var(--gt-text);font-weight:800;">Track Performance</strong>
            <span style="font-size:0.7rem;color:var(--gt-text-muted);">Monitor usage frequency of coupon codes.</span>
        </div>
    </div>
    <div class="benefit-item-card">
        <div class="benefit-icon-wrapper">
            <i data-lucide="sliders"></i>
        </div>
        <div style="display:flex;flex-direction:column;line-height:1.2;">
            <strong style="font-size:0.85rem;color:var(--gt-text);font-weight:800;">Flexible Options</strong>
            <span style="font-size:0.7rem;color:var(--gt-text-muted);">Target specific products or customer groups.</span>
        </div>
    </div>
</div>

<!-- ================= MODALS SECTION ================= -->

<!-- 1. Add Coupon Modal -->
<div class="gt-modal" id="addCouponModal" onclick="closeModalOnOutsideClick(event, 'addCouponModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Add New Coupon</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('addCouponModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf
            <div class="gt-modal-body">
                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Coupon Code *</label>
                        <input type="text" name="code" required class="gt-input" style="width:100%;font-family:monospace;" placeholder="e.g. WELCOME10" oninput="this.value = this.value.toUpperCase()">
                    </div>
                    <div>
                        <label class="gt-label">Discount Type *</label>
                        <select name="type" required class="gt-input" style="width:100%;">
                            <option value="percentage">Percentage OFF</option>
                            <option value="fixed">Fixed Amount OFF</option>
                            <option value="free">Free Shipping</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Discount Value *</label>
                        <input type="number" step="0.01" name="value" required class="gt-input" style="width:100%;" placeholder="e.g. 10 or 500">
                    </div>
                    <div>
                        <label class="gt-label">Minimum Order Amount</label>
                        <input type="number" step="0.01" name="min_order_amount" class="gt-input" style="width:100%;" placeholder="e.g. 1000">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Max Discount Amount (Optional)</label>
                        <input type="number" step="0.01" name="max_discount_amount" class="gt-input" style="width:100%;" placeholder="e.g. 500">
                    </div>
                    <div>
                        <label class="gt-label">Usage Limit (Max Uses)</label>
                        <input type="number" name="max_uses" class="gt-input" style="width:100%;" placeholder="Leave blank for unlimited">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Per Customer Usage Limit</label>
                        <input type="number" name="per_user_limit" class="gt-input" style="width:100%;" placeholder="e.g. 1">
                    </div>
                    <div>
                        <label class="gt-label">Start Date & Time *</label>
                        <input type="datetime-local" name="valid_from" required class="gt-input" style="width:100%;">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">End Date & Time *</label>
                        <input type="datetime-local" name="valid_until" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Coupon Name / Short Description</label>
                        <input type="text" name="description" class="gt-input" style="width:100%;" placeholder="e.g. 10% discount for first order">
                    </div>
                </div>

                <div class="form-grid-2" style="grid-template-columns: repeat(3, 1fr); display: grid;">
                    <label style="display:flex;align-items:center;gap:6px;font-size:0.8rem;font-weight:700;color:var(--gt-text);">
                        <input type="checkbox" name="is_active" value="1" checked> Active Status
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;font-size:0.8rem;font-weight:700;color:var(--gt-text);">
                        <input type="checkbox" name="new_customers_only" value="1"> New Customers Only
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;font-size:0.8rem;font-weight:700;color:var(--gt-text);">
                        <input type="checkbox" name="free_shipping" value="1"> Free Shipping Option
                    </label>
                </div>

                <div style="border-top: 1.5px solid var(--gt-border); margin: 16px 0; padding-top: 16px;">
                    <h3 style="font-size:0.8rem;font-weight:800;color:var(--gt-primary);margin-bottom:12px;text-transform:uppercase;">Inclusions & Exclusions</h3>
                    
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Limit to Selected Products</label>
                            <select name="selected_products[]" class="gt-input" multiple style="width:100%; height:80px; padding:4px;">
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="gt-label">Limit to Selected Categories</label>
                            <select name="selected_categories[]" class="gt-input" multiple style="width:100%; height:80px; padding:4px;">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Exclude Products</label>
                            <select name="excluded_products[]" class="gt-input" multiple style="width:100%; height:80px; padding:4px;">
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="gt-label">Exclude Categories</label>
                            <select name="excluded_categories[]" class="gt-input" multiple style="width:100%; height:80px; padding:4px;">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('addCouponModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Save Coupon</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Edit Coupon Modal -->
<div class="gt-modal" id="editCouponModal" onclick="closeModalOnOutsideClick(event, 'editCouponModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Edit Coupon</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('editCouponModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="" method="POST" id="editCouponForm">
            @csrf
            @method('PUT')
            <div class="gt-modal-body">
                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Coupon Code *</label>
                        <input type="text" name="code" id="editFormCode" required class="gt-input" style="width:100%;font-family:monospace;" oninput="this.value = this.value.toUpperCase()">
                    </div>
                    <div>
                        <label class="gt-label">Discount Type *</label>
                        <select name="type" id="editFormType" required class="gt-input" style="width:100%;">
                            <option value="percentage">Percentage OFF</option>
                            <option value="fixed">Fixed Amount OFF</option>
                            <option value="free">Free Shipping</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Discount Value *</label>
                        <input type="number" step="0.01" name="value" id="editFormValue" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Minimum Order Amount</label>
                        <input type="number" step="0.01" name="min_order_amount" id="editFormMinOrder" class="gt-input" style="width:100%;">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Max Discount Amount (Optional)</label>
                        <input type="number" step="0.01" name="max_discount_amount" id="editFormMaxDiscount" class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Usage Limit (Max Uses)</label>
                        <input type="number" name="max_uses" id="editFormMaxUses" class="gt-input" style="width:100%;">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Per Customer Usage Limit</label>
                        <input type="number" name="per_user_limit" id="editFormPerUserLimit" class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Start Date & Time *</label>
                        <input type="datetime-local" name="valid_from" id="editFormValidFrom" required class="gt-input" style="width:100%;">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">End Date & Time *</label>
                        <input type="datetime-local" name="valid_until" id="editFormValidUntil" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Coupon Name / Short Description</label>
                        <input type="text" name="description" id="editFormDescription" class="gt-input" style="width:100%;">
                    </div>
                </div>

                <div class="form-grid-2" style="grid-template-columns: repeat(3, 1fr); display: grid;">
                    <label style="display:flex;align-items:center;gap:6px;font-size:0.8rem;font-weight:700;color:var(--gt-text);">
                        <input type="checkbox" name="is_active" id="editFormIsActive" value="1"> Active Status
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;font-size:0.8rem;font-weight:700;color:var(--gt-text);">
                        <input type="checkbox" name="new_customers_only" id="editFormNewCustomers" value="1"> New Customers Only
                    </label>
                    <label style="display:flex;align-items:center;gap:6px;font-size:0.8rem;font-weight:700;color:var(--gt-text);">
                        <input type="checkbox" name="free_shipping" id="editFormFreeShipping" value="1"> Free Shipping Option
                    </label>
                </div>

                <div style="border-top: 1.5px solid var(--gt-border); margin: 16px 0; padding-top: 16px;">
                    <h3 style="font-size:0.8rem;font-weight:800;color:var(--gt-primary);margin-bottom:12px;text-transform:uppercase;">Inclusions & Exclusions</h3>
                    
                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Limit to Selected Products</label>
                            <select name="selected_products[]" id="editFormSelectedProducts" class="gt-input" multiple style="width:100%; height:80px; padding:4px;">
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="gt-label">Limit to Selected Categories</label>
                            <select name="selected_categories[]" id="editFormSelectedCategories" class="gt-input" multiple style="width:100%; height:80px; padding:4px;">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div>
                            <label class="gt-label">Exclude Products</label>
                            <select name="excluded_products[]" id="editFormExcludedProducts" class="gt-input" multiple style="width:100%; height:80px; padding:4px;">
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="gt-label">Exclude Categories</label>
                            <select name="excluded_categories[]" id="editFormExcludedCategories" class="gt-input" multiple style="width:100%; height:80px; padding:4px;">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('editCouponModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Update Coupon</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toggle action dropdown menu
        window.toggleRowMenu = function(event, menuId) {
            event.stopPropagation();
            document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
                if (menu.id !== menuId) {
                    menu.classList.remove('show');
                }
            });
            document.getElementById(menuId).classList.toggle('show');
        };

        // Dismiss action dropdowns clicking outside
        document.addEventListener('click', () => {
            document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        });

        // Select All rows checkbox toggle
        const selectAll = document.getElementById('selectAllCheckbox');
        if (selectAll) {
            selectAll.addEventListener('change', () => {
                document.querySelectorAll('.item-row-checkbox').forEach(cb => {
                    cb.checked = selectAll.checked;
                });
            });
        }

        // Custom pagination items limit submit
        window.setPerPage = function(perPage) {
            document.getElementById('formPerPageInput').value = perPage;
            document.getElementById('filterForm').submit();
        };

        // Modal triggers
        window.openModal = function(modalId) {
            document.getElementById(modalId).classList.add('show');
        };

        window.closeModal = function(modalId) {
            document.getElementById(modalId).classList.remove('show');
        };

        window.closeModalOnOutsideClick = function(event, modalId) {
            if (event.target === document.getElementById(modalId)) {
                closeModal(modalId);
            }
        };

        // Delete confirmation
        window.confirmCouponDelete = function(id, code) {
            if (confirm(`Are you sure you want to permanently delete coupon "${code}"?`)) {
                document.getElementById(`deleteForm-${id}`).submit();
            }
        };

        // Edit Modal Loader
        window.openEditModal = function(c) {
            const form = document.getElementById('editCouponForm');
            form.action = `/admin/coupons/${c.id}`;

            document.getElementById('editFormCode').value = c.code;
            document.getElementById('editFormType').value = c.type;
            document.getElementById('editFormValue').value = c.value;
            document.getElementById('editFormMinOrder').value = parseFloat(c.min_order_amount) || 0;
            document.getElementById('editFormMaxDiscount').value = parseFloat(c.max_discount_amount) || '';
            document.getElementById('editFormMaxUses').value = c.max_uses || '';
            document.getElementById('editFormPerUserLimit').value = c.per_user_limit || '';
            document.getElementById('editFormValidFrom').value = c.valid_from;
            document.getElementById('editFormValidUntil').value = c.valid_until;
            document.getElementById('editFormDescription').value = c.description || '';
            
            document.getElementById('editFormIsActive').checked = c.is_active;
            document.getElementById('editFormNewCustomers').checked = c.new_customers_only;
            document.getElementById('editFormFreeShipping').checked = c.free_shipping;

            // Load multi-select lists values helper
            setMultiSelectValues('editFormSelectedProducts', c.selected_products);
            setMultiSelectValues('editFormSelectedCategories', c.selected_categories);
            setMultiSelectValues('editFormExcludedProducts', c.excluded_products);
            setMultiSelectValues('editFormExcludedCategories', c.excluded_categories);

            openModal('editCouponModal');
        };

        // Multi select options mapping helper
        function setMultiSelectValues(elementId, valuesArray) {
            const selectEl = document.getElementById(elementId);
            if (!selectEl) return;
            
            const arr = Array.isArray(valuesArray) ? valuesArray : [];
            const strArr = arr.map(String);
            
            Array.from(selectEl.options).forEach(opt => {
                opt.selected = strArr.includes(String(opt.value));
            });
        }
    });
</script>
@endsection
