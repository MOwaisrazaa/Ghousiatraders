@extends('admin.ghousia-layout')

@section('title', 'Orders')

@push('head')
<style>
    .orders-stat-card {
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

    .orders-stat-icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .orders-stat-meta {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
        min-width: 0;
    }

    .orders-stat-title {
        font-size: clamp(0.72rem, 0.85vw, 0.82rem);
        font-weight: 700;
        color: var(--gt-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 4px;
        overflow-wrap: break-word;
        word-break: normal;
        white-space: normal;
        line-height: 1.25;
        min-width: 0;
    }

    .orders-stat-count {
        font-size: clamp(1.25rem, 1.6vw, 1.6rem);
        font-weight: 800;
        color: var(--gt-text);
        line-height: 1.15;
        margin-bottom: 6px;
        word-break: break-word;
        min-width: 0;
    }

    .orders-stat-growth {
        font-size: clamp(0.68rem, 0.8vw, 0.78rem);
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
        white-space: normal;
        overflow-wrap: break-word;
        width: 100%;
        min-width: 0;
    }

    /* Colors and Accents */
    .stat-accent-total { background: #f3f4f6; color: #4b5563; }
    .stat-accent-pending { background: #fffbeb; color: #b45309; }
    .stat-accent-processing { background: #eff6ff; color: #1d4ed8; }
    .stat-accent-shipped { background: #faf5ff; color: #6b21a8; }
    .stat-accent-delivered { background: #ecfdf5; color: #047857; }
    .stat-accent-cancelled { background: #fef2f2; color: #b91c1c; }

    /* Orders Master Card container */
    .orders-container-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--gt-shadow);
        box-sizing: border-box;
        width: 100%;
        min-width: 0;
    }

    /* Filters elements styling */
    .filters-bar {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .filter-field {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--gt-text);
        outline: none;
        transition: all 0.2s;
    }

    .filter-field:focus {
        border-color: var(--gt-primary);
        box-shadow: 0 0 0 3px rgba(53, 27, 13, 0.05);
    }

    .search-input-wrapper {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .search-input-wrapper i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gt-text-muted);
        width: 16px;
        height: 16px;
    }

    .search-input-field {
        width: 100%;
        padding-left: 38px;
    }

    /* Status tabs */
    .tabs-and-sorting-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        border-bottom: 1.5px solid var(--gt-border);
        margin-bottom: 20px;
        padding-bottom: 2px;
    }

    .status-tabs-list {
        display: flex;
        align-items: center;
        gap: 20px;
        list-style: none;
    }

    .status-tab-item {
        padding: 10px 4px;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--gt-text-muted);
        cursor: pointer;
        text-decoration: none;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
    }

    .status-tab-item:hover, .status-tab-item.active {
        color: var(--gt-primary);
    }

    .status-tab-item.active {
        border-bottom-color: var(--gt-primary);
    }

    /* Table styles */
    .orders-table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid rgba(215, 166, 74, 0.15);
        border-radius: 12px;
        background: #ffffff;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .orders-table th {
        background: #fffcf8;
        padding: 14px 16px;
        font-size: 0.76rem;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--gt-text-muted);
        border-bottom: 1.5px solid rgba(215, 166, 74, 0.18);
        white-space: nowrap;
    }

    .orders-table td {
        padding: 16px;
        font-size: 0.86rem;
        border-bottom: 1px solid rgba(215, 166, 74, 0.1);
        vertical-align: middle;
        white-space: nowrap;
    }

    .orders-table tbody tr:hover {
        background-color: var(--gt-primary-light);
    }

    /* Selection checkbox styling */
    .select-checkbox {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        border: 1.5px solid var(--gt-border);
        accent-color: var(--gt-primary);
        cursor: pointer;
    }

    /* Customer Cell initials circle */
    .cust-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cust-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: var(--gt-primary-light);
        border: 1px solid var(--gt-border);
        color: var(--gt-primary);
        font-weight: 700;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cust-meta {
        display: flex;
        flex-direction: column;
        line-height: 1.25;
    }

    .cust-name {
        font-weight: 700;
        color: var(--gt-text);
    }

    .cust-email {
        font-size: 0.74rem;
        color: var(--gt-text-muted);
    }

    /* Date and Time styling */
    .date-meta {
        display: flex;
        flex-direction: column;
        line-height: 1.3;
    }

    .date-main {
        font-weight: 700;
        color: var(--gt-text);
    }

    .date-time {
        font-size: 0.74rem;
        color: var(--gt-text-muted);
        font-weight: 500;
    }

    /* Status badges */
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        display: inline-block;
        text-align: center;
    }

    .status-badge-delivered { background: #ecfdf5; color: #047857; border: 1px solid rgba(16, 185, 129, 0.2); }
    .status-badge-processing { background: #fffbeb; color: #b45309; border: 1px solid rgba(245, 158, 11, 0.2); }
    .status-badge-shipped { background: #eff6ff; color: #1d4ed8; border: 1px solid rgba(59, 130, 246, 0.2); }
    .status-badge-pending { background: #f3f4f6; color: #4b5563; border: 1px solid rgba(107, 114, 128, 0.2); }
    .status-badge-cancelled { background: #fef2f2; color: #b91c1c; border: 1px solid rgba(239, 68, 68, 0.2); }

    /* Payment method badges */
    .payment-badge {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .payment-badge-cod { background: #fdf6ec; color: #b45309; border: 1px solid rgba(215, 166, 74, 0.2); }
    .payment-badge-jazzcash { background: #fffbeb; color: #d97706; border: 1px solid rgba(217, 119, 6, 0.2); }
    .payment-badge-easypaisa { background: #f0fdf4; color: #15803d; border: 1px solid rgba(22, 163, 74, 0.2); }
    .payment-badge-bank { background: #eff6ff; color: #1d4ed8; border: 1px solid rgba(59, 130, 246, 0.2); }

    /* Actions Column icons buttons */
    .action-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid var(--gt-border);
        background: #ffffff;
        color: var(--gt-text-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .action-icon-btn:hover {
        background-color: var(--gt-primary-light);
        color: var(--gt-primary);
        border-color: var(--gt-primary);
    }

    /* Actions Menu Trigger */
    .actions-menu-wrapper {
        position: relative;
        display: inline-block;
    }

    .dropdown-menu-list {
        position: absolute;
        right: 0;
        top: 36px;
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        padding: 6px;
        z-index: 100;
        display: none;
        flex-direction: column;
        min-width: 160px;
    }

    .dropdown-menu-list.show {
        display: flex;
    }

    .dropdown-menu-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--gt-text);
        text-decoration: none;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
        border-radius: 6px;
    }

    .dropdown-menu-item:hover {
        background-color: var(--gt-primary-light);
        color: var(--gt-primary);
    }

    .dropdown-menu-item.danger-action {
        color: var(--gt-danger);
    }

    .dropdown-menu-item.danger-action:hover {
        background-color: #fef2f2;
        color: var(--gt-danger);
    }

    /* Footer pagination bar */
    .orders-footer-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 24px;
        flex-wrap: wrap;
        gap: 16px;
        font-size: 0.82rem;
        color: var(--gt-text-muted);
        font-weight: 500;
    }

    /* Page Navigation styled links */
    .pages-nav-list {
        display: flex;
        align-items: center;
        gap: 6px;
        list-style: none;
    }

    .page-nav-link {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1.5px solid var(--gt-border);
        background: #ffffff;
        color: var(--gt-text);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
    }

    .page-nav-link:hover, .page-nav-link.active {
        background-color: var(--gt-primary);
        color: #ffffff;
        border-color: var(--gt-primary);
    }

    .page-nav-link.disabled {
        opacity: 0.5;
        pointer-events: none;
        cursor: not-allowed;
    }

    /* Custom Admin Modal Styling */
    .admin-custom-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(53, 27, 13, 0.4);
        backdrop-filter: blur(4px);
        z-index: 1050;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .admin-custom-modal.show {
        display: flex;
    }

    .modal-dialog-box {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 20px;
        width: 100%;
        max-width: 600px;
        box-shadow: 0 15px 45px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        animation: scaleModal 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes scaleModal {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .modal-head {
        padding: 20px 24px;
        border-bottom: 1.5px solid var(--gt-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fffcf8;
    }

    .modal-head h3 {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--gt-primary);
    }

    .modal-close-btn {
        background: none;
        border: none;
        color: var(--gt-text-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close-btn:hover {
        color: var(--gt-primary);
    }

    .modal-body-panel {
        padding: 24px;
        overflow-y: auto;
        max-height: 70vh;
    }

    .modal-foot-panel {
        padding: 16px 24px;
        border-top: 1.5px solid var(--gt-border);
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        background: #fffcf8;
    }

    .form-group-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 16px;
    }

    .form-group-item label {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--gt-text);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Buttons */
    .gt-btn-primary {
        background-color: var(--gt-primary);
        color: #ffffff;
        border: none;
        border-radius: 10px;
        padding: 10px 18px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s;
        text-decoration: none;
    }

    .gt-btn-primary:hover {
        background-color: #4b2611;
    }

    .gt-btn-outline {
        background: none;
        border: 1.5px solid var(--gt-border);
        border-radius: 10px;
        padding: 9px 18px;
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--gt-primary);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .gt-btn-outline:hover {
        background-color: var(--gt-primary-light);
        border-color: var(--gt-primary);
    }

    /* Responsive scaling rules */
    @media (max-width: 1400px) {
        .orders-stats-row {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 991px) {
        .orders-stats-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 576px) {
        .orders-stats-row {
            grid-template-columns: 1fr;
        }
        
        .tabs-and-sorting-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .status-tabs-list {
            flex-wrap: wrap;
            gap: 10px 16px;
        }
    }
</style>
@endpush

@section('content')

<!-- Header bar -->
<x-admin-page-header title="Orders">
    <a href="{{ route('admin.orders.export', request()->query()) }}" class="gt-btn-outline">
        <i data-lucide="download" style="width:16px; height:16px;"></i>
        Export
    </a>
    <button type="button" class="gt-btn-outline" onclick="openModal('importOrdersModal')">
        <i data-lucide="upload" style="width:16px; height:16px;"></i>
        Import
    </button>
    <button type="button" class="gt-btn-primary" onclick="openModal('createOrderModal')">
        <i data-lucide="plus" style="width:16px; height:16px;"></i>
        Create Order
    </button>
</x-admin-page-header>

<!-- Order Statistics Row (6 Cards) -->
<div class="orders-stats-row">
    <!-- Stat 1: Total Orders -->
    <div class="orders-stat-card">
        <div class="orders-stat-icon-wrapper stat-accent-total">
            <i data-lucide="shopping-bag" style="width:20px; height:20px;"></i>
        </div>
        <div class="orders-stat-meta">
            <span class="orders-stat-title">Total Orders</span>
            <h3 class="orders-stat-count">{{ number_format($stats['total']['count']) }}</h3>
            <div class="orders-stat-growth">
                <span class="{{ $stats['total']['is_up'] ? 'growth-up' : 'growth-down' }}" style="color:{{ $stats['total']['is_up'] ? 'var(--gt-success)' : 'var(--gt-danger)' }}; font-weight:700;">
                    <i data-lucide="{{ $stats['total']['is_up'] ? 'arrow-up-right' : 'arrow-down-left' }}" style="width:10px; height:10px; display:inline-block; vertical-align:middle;"></i>
                    {{ $stats['total']['growth'] }}%
                </span>
                <span class="growth-text" style="color:var(--gt-text-muted); font-weight:500;">vs last week</span>
            </div>
        </div>
    </div>

    <!-- Stat 2: Pending -->
    <div class="orders-stat-card">
        <div class="orders-stat-icon-wrapper stat-accent-pending">
            <i data-lucide="clock" style="width:20px; height:20px;"></i>
        </div>
        <div class="orders-stat-meta">
            <span class="orders-stat-title">Pending</span>
            <h3 class="orders-stat-count">{{ number_format($stats['pending']['count']) }}</h3>
            <div class="orders-stat-growth">
                <span class="{{ $stats['pending']['is_up'] ? 'growth-up' : 'growth-down' }}" style="color:{{ $stats['pending']['is_up'] ? 'var(--gt-success)' : 'var(--gt-danger)' }}; font-weight:700;">
                    <i data-lucide="{{ $stats['pending']['is_up'] ? 'arrow-up-right' : 'arrow-down-left' }}" style="width:10px; height:10px; display:inline-block; vertical-align:middle;"></i>
                    {{ $stats['pending']['growth'] }}%
                </span>
                <span class="growth-text" style="color:var(--gt-text-muted); font-weight:500;">vs last week</span>
            </div>
        </div>
    </div>

    <!-- Stat 3: Processing -->
    <div class="orders-stat-card">
        <div class="orders-stat-icon-wrapper stat-accent-processing">
            <i data-lucide="refresh-cw" style="width:20px; height:20px;"></i>
        </div>
        <div class="orders-stat-meta">
            <span class="orders-stat-title">Processing</span>
            <h3 class="orders-stat-count">{{ number_format($stats['processing']['count']) }}</h3>
            <div class="orders-stat-growth">
                <span class="{{ $stats['processing']['is_up'] ? 'growth-up' : 'growth-down' }}" style="color:{{ $stats['processing']['is_up'] ? 'var(--gt-success)' : 'var(--gt-danger)' }}; font-weight:700;">
                    <i data-lucide="{{ $stats['processing']['is_up'] ? 'arrow-up-right' : 'arrow-down-left' }}" style="width:10px; height:10px; display:inline-block; vertical-align:middle;"></i>
                    {{ $stats['processing']['growth'] }}%
                </span>
                <span class="growth-text" style="color:var(--gt-text-muted); font-weight:500;">vs last week</span>
            </div>
        </div>
    </div>

    <!-- Stat 4: Shipped -->
    <div class="orders-stat-card">
        <div class="orders-stat-icon-wrapper stat-accent-shipped">
            <i data-lucide="truck" style="width:20px; height:20px;"></i>
        </div>
        <div class="orders-stat-meta">
            <span class="orders-stat-title">Shipped</span>
            <h3 class="orders-stat-count">{{ number_format($stats['shipped']['count']) }}</h3>
            <div class="orders-stat-growth">
                <span class="{{ $stats['shipped']['is_up'] ? 'growth-up' : 'growth-down' }}" style="color:{{ $stats['shipped']['is_up'] ? 'var(--gt-success)' : 'var(--gt-danger)' }}; font-weight:700;">
                    <i data-lucide="{{ $stats['shipped']['is_up'] ? 'arrow-up-right' : 'arrow-down-left' }}" style="width:10px; height:10px; display:inline-block; vertical-align:middle;"></i>
                    {{ $stats['shipped']['growth'] }}%
                </span>
                <span class="growth-text" style="color:var(--gt-text-muted); font-weight:500;">vs last week</span>
            </div>
        </div>
    </div>

    <!-- Stat 5: Delivered -->
    <div class="orders-stat-card">
        <div class="orders-stat-icon-wrapper stat-accent-delivered">
            <i data-lucide="package" style="width:20px; height:20px;"></i>
        </div>
        <div class="orders-stat-meta">
            <span class="orders-stat-title">Delivered</span>
            <h3 class="orders-stat-count">{{ number_format($stats['delivered']['count']) }}</h3>
            <div class="orders-stat-growth">
                <span class="{{ $stats['delivered']['is_up'] ? 'growth-up' : 'growth-down' }}" style="color:{{ $stats['delivered']['is_up'] ? 'var(--gt-success)' : 'var(--gt-danger)' }}; font-weight:700;">
                    <i data-lucide="{{ $stats['delivered']['is_up'] ? 'arrow-up-right' : 'arrow-down-left' }}" style="width:10px; height:10px; display:inline-block; vertical-align:middle;"></i>
                    {{ $stats['delivered']['growth'] }}%
                </span>
                <span class="growth-text" style="color:var(--gt-text-muted); font-weight:500;">vs last week</span>
            </div>
        </div>
    </div>

    <!-- Stat 6: Cancelled -->
    <div class="orders-stat-card">
        <div class="orders-stat-icon-wrapper stat-accent-cancelled">
            <i data-lucide="x-circle" style="width:20px; height:20px;"></i>
        </div>
        <div class="orders-stat-meta">
            <span class="orders-stat-title">Cancelled</span>
            <h3 class="orders-stat-count">{{ number_format($stats['cancelled']['count']) }}</h3>
            <div class="orders-stat-growth">
                <span class="{{ $stats['cancelled']['is_up'] ? 'growth-up' : 'growth-down' }}" style="color:{{ $stats['cancelled']['is_up'] ? 'var(--gt-success)' : 'var(--gt-danger)' }}; font-weight:700;">
                    <i data-lucide="{{ $stats['cancelled']['is_up'] ? 'arrow-up-right' : 'arrow-down-left' }}" style="width:10px; height:10px; display:inline-block; vertical-align:middle;"></i>
                    {{ $stats['cancelled']['growth'] }}%
                </span>
                <span class="growth-text" style="color:var(--gt-text-muted); font-weight:500;">vs last week</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="orders-container-card">

    <form method="GET" action="{{ route('admin.orders') }}" id="filterForm">
        <input type="hidden" name="status" id="formStatusInput" value="{{ request('status', 'all') }}">
        <input type="hidden" name="sort" id="formSortInput" value="{{ request('sort', 'newest') }}">
        <input type="hidden" name="per_page" id="formPerPageInput" value="{{ request('per_page', 10) }}">

        <!-- Filters section -->
        <div class="filters-bar">
            <!-- Search bar -->
            <div class="search-input-wrapper">
                <i data-lucide="search"></i>
                <input type="text" name="search" value="{{ request('search') }}" class="filter-field search-input-field" placeholder="Search by order ID, customer...">
            </div>

            <!-- Date Range -->
            <input type="text" name="date_range" value="{{ request('date_range') }}" class="filter-field" placeholder="YYYY-MM-DD - YYYY-MM-DD" style="width:220px;">

            <!-- Payment dropdown -->
            <select name="payment_method" class="filter-field" onchange="this.form.submit()">
                <option value="all" {{ request('payment_method') === 'all' ? 'selected' : '' }}>All Payment Methods</option>
                <option value="cod" {{ request('payment_method') === 'cod' ? 'selected' : '' }}>Cash on Delivery (COD)</option>
                <option value="easypaisa" {{ request('payment_method') === 'easypaisa' ? 'selected' : '' }}>EasyPaisa</option>
                <option value="jazzcash" {{ request('payment_method') === 'jazzcash' ? 'selected' : '' }}>JazzCash</option>
                <option value="bank" {{ request('payment_method') === 'bank' ? 'selected' : '' }}>Bank Transfer</option>
            </select>

            <button type="submit" class="gt-btn-primary">
                <i data-lucide="sliders-horizontal" style="width:16px; height:16px;"></i>
                Filters
            </button>
            <a href="{{ route('admin.orders') }}" class="gt-btn-outline" style="text-decoration:none;">Reset</a>
        </div>
    </form>

    <!-- Tabs and Sorting Row -->
    <div class="tabs-and-sorting-row">
        <!-- Left side Tabs -->
        <ul class="status-tabs-list">
            <li>
                <a href="javascript:void(0)" onclick="setTabStatus('all')" class="status-tab-item {{ request('status', 'all') === 'all' ? 'active' : '' }}">
                    All Orders ({{ $tabCounts['all'] }})
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" onclick="setTabStatus('pending')" class="status-tab-item {{ request('status') === 'pending' ? 'active' : '' }}">
                    Pending ({{ $tabCounts['pending'] }})
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" onclick="setTabStatus('processing')" class="status-tab-item {{ request('status') === 'processing' ? 'active' : '' }}">
                    Processing ({{ $tabCounts['processing'] }})
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" onclick="setTabStatus('shipped')" class="status-tab-item {{ request('status') === 'shipped' ? 'active' : '' }}">
                    Shipped ({{ $tabCounts['shipped'] }})
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" onclick="setTabStatus('delivered')" class="status-tab-item {{ request('status') === 'delivered' ? 'active' : '' }}">
                    Delivered ({{ $tabCounts['delivered'] }})
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" onclick="setTabStatus('cancelled')" class="status-tab-item {{ request('status') === 'cancelled' ? 'active' : '' }}">
                    Cancelled ({{ $tabCounts['cancelled'] }})
                </a>
            </li>
        </ul>

        <!-- Right side sorting options -->
        <div style="display:flex; align-items:center; gap:8px;">
            <select class="filter-field" onchange="setSortOrder(this.value)" style="padding:6px 12px; font-size:0.8rem;">
                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                <option value="total_high" {{ request('sort') === 'total_high' ? 'selected' : '' }}>Total: High to Low</option>
                <option value="total_low" {{ request('sort') === 'total_low' ? 'selected' : '' }}>Total: Low to High</option>
            </select>
            <button type="button" class="action-icon-btn" style="width:30px; height:30px;"><i data-lucide="list" style="width:14px; height:14px;"></i></button>
        </div>
    </div>

    <!-- Data Table wrap -->
    <div class="orders-table-wrapper">
        <table class="orders-table">
            <thead>
                <tr>
                    <th style="width:40px; text-align:center;">
                        <input type="checkbox" class="select-checkbox" id="selectAllCheckbox">
                    </th>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $orderItem)
                    @php
                        $billingAddress = json_decode($orderItem->billing_address, true) ?? [];
                        $custName = (isset($billingAddress['first_name']) && trim($billingAddress['first_name']) !== '') ? ($billingAddress['first_name'] . ' ' . ($billingAddress['last_name'] ?? '')) : ($orderItem->user ? $orderItem->user->name : 'Guest');
                        $custEmail = $orderItem->user ? $orderItem->user->email : ($billingAddress['email'] ?? 'No Email');
                        $initials = strtoupper(substr($custName, 0, 1) . substr(str_contains($custName, ' ') ? explode(' ', $custName)[1] : '', 0, 1));
                        $itemsArr = json_decode($orderItem->cart_items, true) ?? [];
                        $itemsCount = count($itemsArr);
                    @endphp
                    <tr>
                        <td style="text-align:center;">
                            <input type="checkbox" class="select-checkbox item-row-checkbox">
                        </td>
                        <td>
                            <a href="{{ route('admin.order.show', $orderItem->id) }}" style="font-weight: 800; color: var(--gt-primary); text-decoration: none;">
                                #GT-{{ str_pad($orderItem->id, 5, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>
                        <td>
                            <div class="cust-cell">
                                <div class="cust-avatar">
                                    {{ $initials ?: 'GT' }}
                                </div>
                                <div class="cust-meta">
                                    <span class="cust-name">{{ $custName }}</span>
                                    <span class="cust-email">{{ $custEmail }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="date-meta">
                                <span class="date-main">{{ $orderItem->created_at ? $orderItem->created_at->format('M d, Y') : now()->format('M d, Y') }}</span>
                                <span class="date-time">{{ $orderItem->created_at ? $orderItem->created_at->format('h:i A') : now()->format('h:i A') }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $payMethod = strtolower($orderItem->payment_method ?? 'cod');
                            @endphp
                            @if(str_contains($payMethod, 'cod') || str_contains($payMethod, 'cash'))
                                <span class="payment-badge payment-badge-cod">COD</span>
                            @elseif(str_contains($payMethod, 'jazz'))
                                <span class="payment-badge payment-badge-jazzcash">JazzCash</span>
                            @elseif(str_contains($payMethod, 'easy'))
                                <span class="payment-badge payment-badge-easypaisa">EasyPaisa</span>
                            @else
                                <span class="payment-badge payment-badge-bank">Bank Transfer</span>
                            @endif
                        </td>
                        <td>
                            <div class="date-meta">
                                <span class="date-main">PKR {{ number_format($orderItem->final_total ?? $orderItem->total) }}</span>
                                <span class="date-time" style="font-weight:600;">{{ $itemsCount }} {{ $itemsCount === 1 ? 'Item' : 'Items' }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusStr = strtolower($orderItem->status ?? 'pending');
                            @endphp
                            @if($statusStr === 'completed')
                                <span class="status-badge status-badge-delivered">Delivered</span>
                            @elseif($statusStr === 'paid')
                                <span class="status-badge status-badge-processing">Processing</span>
                            @elseif($statusStr === 'shipped')
                                <span class="status-badge status-badge-shipped">Shipped</span>
                            @elseif($statusStr === 'rejected' || $statusStr === 'cancelled')
                                <span class="status-badge status-badge-cancelled">Cancelled</span>
                            @else
                                <span class="status-badge status-badge-pending">Pending</span>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex; justify-content:flex-end; gap:6px;">
                                <a href="{{ route('admin.order.show', $orderItem->id) }}" class="action-icon-btn" title="View details">
                                    <i data-lucide="eye" style="width:14px; height:14px;"></i>
                                </a>
                                <button type="button" class="action-icon-btn" onclick="openUpdateStatusModal({{ $orderItem->id }}, '{{ $orderItem->status }}')" title="Edit Status">
                                    <i data-lucide="edit-3" style="width:14px; height:14px;"></i>
                                </button>
                                
                                <div class="actions-menu-wrapper">
                                    <button type="button" class="action-icon-btn" onclick="toggleRowMenu(event, 'rowMenu{{ $orderItem->id }}')">
                                        <i data-lucide="more-vertical" style="width:14px; height:14px;"></i>
                                    </button>
                                    
                                    <div class="dropdown-menu-list" id="rowMenu{{ $orderItem->id }}">
                                        <a href="{{ route('admin.order.show', $orderItem->id) }}" class="dropdown-menu-item">
                                            <i data-lucide="eye" style="width:12px; height:12px;"></i> View Details
                                        </a>
                                        <a href="javascript:void(0)" onclick="openUpdateStatusModal({{ $orderItem->id }}, '{{ $orderItem->status }}')" class="dropdown-menu-item">
                                            <i data-lucide="edit-2" style="width:12px; height:12px;"></i> Update Status
                                        </a>
                                        <a href="javascript:void(0)" onclick="window.print()" class="dropdown-menu-item">
                                            <i data-lucide="printer" style="width:12px; height:12px;"></i> Print Invoice
                                        </a>
                                        <form action="{{ route('admin.orders.duplicate', $orderItem->id) }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="dropdown-menu-item">
                                                <i data-lucide="copy" style="width:12px; height:12px;"></i> Duplicate Order
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.order.reject', $orderItem->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="dropdown-menu-item danger-action">
                                                <i data-lucide="x" style="width:12px; height:12px;"></i> Cancel Order
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.orders.destroy', $orderItem->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this order?')" style="margin:0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-menu-item danger-action">
                                                <i data-lucide="trash-2" style="width:12px; height:12px;"></i> Delete Order
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:30px; font-weight:700; color:var(--gt-text-muted);">No orders found matching the filter selection.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer Pagination Row -->
    <div class="orders-footer-row">
        <div>
            Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ number_format($orders->total()) }} orders
        </div>

        <!-- Pagination Page Numbers links -->
        <div style="display:flex; align-items:center; gap:16px;">
            <ul class="pages-nav-list">
                <li>
                    <a href="{{ $orders->previousPageUrl() }}" class="page-nav-link {{ $orders->onFirstPage() ? 'disabled' : '' }}">
                        <i data-lucide="chevron-left" style="width:14px; height:14px;"></i>
                    </a>
                </li>
                @foreach ($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
                    <li>
                        <a href="{{ $url }}" class="page-nav-link {{ $page == $orders->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    </li>
                @endforeach
                <li>
                    <a href="{{ $orders->nextPageUrl() }}" class="page-nav-link {{ !$orders->hasMorePages() ? 'disabled' : '' }}">
                        <i data-lucide="chevron-right" style="width:14px; height:14px;"></i>
                    </a>
                </li>
            </ul>

            <div style="display:flex; align-items:center; gap:8px;">
                <span>Show</span>
                <select class="filter-field" onchange="setPerPage(this.value)" style="padding:4px 8px; font-size:0.8rem;">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span>per page</span>
            </div>
        </div>
    </div>

</div>

<!-- MODAL: Create Order -->
<div class="admin-custom-modal" id="createOrderModal">
    <div class="modal-dialog-box">
        <div class="modal-head">
            <h3>Create New Order</h3>
            <button class="modal-close-btn" onclick="closeModal('createOrderModal')">
                <i data-lucide="x" style="width:20px; height:20px;"></i>
            </button>
        </div>
        <form action="{{ route('admin.orders.store') }}" method="POST">
            @csrf
            <div class="modal-body-panel">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group-item">
                        <label>Select Customer</label>
                        <select name="user_id" class="filter-field" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-item">
                        <label>Select Product/Course</label>
                        <select name="course_id" class="filter-field" required>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }} - PKR {{ number_format($course->price) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group-item">
                        <label>Total Price (PKR)</label>
                        <input type="number" name="total" class="filter-field" required min="0">
                    </div>
                    <div class="form-group-item">
                        <label>Discount Amount (PKR)</label>
                        <input type="number" name="discount" class="filter-field" value="0" min="0">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group-item">
                        <label>Payment Method</label>
                        <select name="payment_method" class="filter-field" required>
                            <option value="cod">Cash on Delivery (COD)</option>
                            <option value="easypaisa">EasyPaisa</option>
                            <option value="jazzcash">JazzCash</option>
                            <option value="bank">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="form-group-item">
                        <label>Order Status</label>
                        <select name="status" class="filter-field" required>
                            <option value="pending">Pending</option>
                            <option value="paid">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="completed">Delivered</option>
                        </select>
                    </div>
                </div>

                <h4 style="font-size:0.8rem; font-weight:800; color:var(--gt-primary); text-transform:uppercase; margin:14px 0 10px 0; border-bottom:1px solid var(--gt-border); padding-bottom:4px;">Billing Information</h4>
                
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group-item">
                        <label>First Name</label>
                        <input type="text" name="first_name" class="filter-field" required>
                    </div>
                    <div class="form-group-item">
                        <label>Last Name</label>
                        <input type="text" name="last_name" class="filter-field" required>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group-item">
                        <label>Email</label>
                        <input type="email" name="email" class="filter-field" required>
                    </div>
                    <div class="form-group-item">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="filter-field" required>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:2fr 1fr; gap:16px;">
                    <div class="form-group-item">
                        <label>Billing Address</label>
                        <input type="text" name="address" class="filter-field" required>
                    </div>
                    <div class="form-group-item">
                        <label>City</label>
                        <input type="text" name="city" class="filter-field" required>
                    </div>
                </div>
            </div>
            <div class="modal-foot-panel">
                <button type="button" class="gt-btn-outline" onclick="closeModal('createOrderModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Create Order</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Import Orders -->
<div class="admin-custom-modal" id="importOrdersModal">
    <div class="modal-dialog-box">
        <div class="modal-head">
            <h3>Import Orders from CSV</h3>
            <button class="modal-close-btn" onclick="closeModal('importOrdersModal')">
                <i data-lucide="x" style="width:20px; height:20px;"></i>
            </button>
        </div>
        <form action="{{ route('admin.orders.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body-panel">
                <div class="form-group-item">
                    <label>Select CSV File</label>
                    <input type="file" name="csv_file" class="filter-field" required accept=".csv,.txt" style="padding:12px;">
                </div>
                <div style="background-color:var(--gt-primary-light); border:1px solid var(--gt-border); border-radius:10px; padding:12px; font-size:0.76rem; color:var(--gt-text);">
                    <strong>Required Columns in CSV:</strong>
                    <p style="margin-top:4px; font-family:monospace;">customer_email, course_id, total, discount, payment_method, status, first_name, last_name, phone, address, city</p>
                </div>
            </div>
            <div class="modal-foot-panel">
                <button type="button" class="gt-btn-outline" onclick="closeModal('importOrdersModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Upload & Import</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Update Status -->
<div class="admin-custom-modal" id="updateStatusModal">
    <div class="modal-dialog-box" style="max-width:400px;">
        <div class="modal-head">
            <h3>Update Order Status</h3>
            <button class="modal-close-btn" onclick="closeModal('updateStatusModal')">
                <i data-lucide="x" style="width:20px; height:20px;"></i>
            </button>
        </div>
        <form action="" method="POST" id="updateStatusForm">
            @csrf
            <div class="modal-body-panel">
                <div class="form-group-item">
                    <label>New Status</label>
                    <select name="status" id="updateStatusSelect" class="filter-field" required>
                        <option value="pending">Pending</option>
                        <option value="paid">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="completed">Delivered</option>
                        <option value="rejected">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="modal-foot-panel">
                <button type="button" class="gt-btn-outline" onclick="closeModal('updateStatusModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Save Status</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Handle status tabs
    function setTabStatus(status) {
        document.getElementById('formStatusInput').value = status;
        document.getElementById('filterForm').submit();
    }

    // Handle sorting
    function setSortOrder(sort) {
        document.getElementById('formSortInput').value = sort;
        document.getElementById('filterForm').submit();
    }

    // Handle pagination size
    function setPerPage(perPage) {
        document.getElementById('formPerPageInput').value = perPage;
        document.getElementById('filterForm').submit();
    }

    // Modals control
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('show');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
    }

    // Update Status modal trigger
    function openUpdateStatusModal(orderId, currentStatus) {
        const form = document.getElementById('updateStatusForm');
        form.action = `/admin/order/${orderId}/update-status`;
        document.getElementById('updateStatusSelect').value = currentStatus;
        openModal('updateStatusModal');
    }

    // Three dot action dropdown toggler
    function toggleRowMenu(event, menuId) {
        event.stopPropagation();
        
        // Hide all other open menus
        document.querySelectorAll('.dropdown-menu-list').forEach(menu => {
            if (menu.id !== menuId) {
                menu.classList.remove('show');
            }
        });

        // Toggle the clicked menu
        document.getElementById(menuId).classList.toggle('show');
    }

    // Select All check box trigger
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            document.querySelectorAll('.item-row-checkbox').forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
        });
    }

    // Close action dropdowns on document click
    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu-list').forEach(menu => {
            menu.classList.remove('show');
        });
    });
</script>
@endpush
