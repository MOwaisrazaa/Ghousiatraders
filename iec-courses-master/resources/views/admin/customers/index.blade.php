@extends('admin.ghousia-layout')

@section('title', 'Admin Dashboard - Customers')

@section('content')
<style>
    .customers-stat-card {
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

    .customers-stat-icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .customers-stat-icon-wrapper i {
        width: 20px;
        height: 20px;
    }

    .customers-stat-meta {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
        min-width: 0;
    }

    .customers-stat-title {
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

    .customers-stat-count {
        font-size: clamp(1.25rem, 1.6vw, 1.6rem);
        font-weight: 800;
        color: var(--gt-text);
        line-height: 1.15;
        margin-bottom: 6px;
        word-break: break-word;
        min-width: 0;
    }

    .customers-stat-growth {
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

    /* Colors and Accents for Stats */
    .stat-accent-total { background: #f3f4f6; color: #4b5563; }
    .stat-accent-new { background: #eff6ff; color: #1d4ed8; }
    .stat-accent-active { background: #ecfdf5; color: #047857; }
    .stat-accent-repeat { background: #faf5ff; color: #6b21a8; }
    .stat-accent-inactive { background: #fef2f2; color: #b91c1c; }

    /* Customers card container */
    .customers-container-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--gt-shadow);
        box-sizing: border-box;
        width: 100%;
        min-width: 0;
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

    /* Customer initials profile image */
    .customer-profile-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: var(--gt-primary-light);
        color: var(--gt-primary);
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        border: 1px solid rgba(215, 166, 74, 0.2);
    }

    /* Group Badges */
    .badge-group {
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

    .group-vip {
        background-color: #fff9eb;
        color: #d7a64a;
        border-color: rgba(215, 166, 74, 0.3);
    }

    .group-regular {
        background-color: #fffaf4;
        color: #8a7355;
        border-color: rgba(138, 115, 85, 0.2);
    }

    .group-new {
        background-color: #eff6ff;
        color: #1d4ed8;
        border-color: rgba(29, 78, 216, 0.15);
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
    .status-inactive { background-color: #fef2f2; color: #b91c1c; }

    /* Action Buttons Row */
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

    /* Modals styling */
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
        max-width: 680px;
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

    /* Buttons inside modal */
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

    /* Responsive */
    @media (max-width: 1200px) {
        .customers-stats-row {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .customers-stats-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .customers-stats-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<x-admin-page-header title="Customers">
    <a href="{{ route('admin.customers.export', request()->all()) }}" class="gt-btn-outline" style="min-height:38px;padding:0 16px;">
        <i data-lucide="download" style="width:16px;height:16px;"></i> Export
    </a>
    <button class="gt-btn-primary" onclick="openModal('addCustomerModal')">
        <i data-lucide="plus"></i> Add New Customer
    </button>
</x-admin-page-header>

<!-- Customer Statistics Row -->
<div class="customers-stats-row">
    <!-- Stat Card 1 -->
    <div class="customers-stat-card">
        <div class="customers-stat-icon-wrapper stat-accent-total">
            <i data-lucide="users"></i>
        </div>
        <div class="customers-stat-meta">
            <div>
                <div class="customers-stat-title">Total Customers</div>
                <div class="customers-stat-count">{{ $totalCustomersCount }}</div>
            </div>
            <div class="customers-stat-growth" style="color: #047857;">
                <i data-lucide="trending-up" style="width:12px;height:12px;"></i> + 15.3% <span style="color:var(--gt-text-muted);">vs last month</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="customers-stat-card">
        <div class="customers-stat-icon-wrapper stat-accent-new">
            <i data-lucide="user-plus"></i>
        </div>
        <div class="customers-stat-meta">
            <div>
                <div class="customers-stat-title">New Customers</div>
                <div class="customers-stat-count">{{ $newCustomersCount }}</div>
            </div>
            <div class="customers-stat-growth" style="color: #047857;">
                <i data-lucide="trending-up" style="width:12px;height:12px;"></i> + 22.7% <span style="color:var(--gt-text-muted);">vs last month</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="customers-stat-card">
        <div class="customers-stat-icon-wrapper stat-accent-active">
            <i data-lucide="check-circle"></i>
        </div>
        <div class="customers-stat-meta">
            <div>
                <div class="customers-stat-title">Active Customers</div>
                <div class="customers-stat-count">{{ $activeCustomersCount }}</div>
            </div>
            <div class="customers-stat-growth" style="color: #047857;">
                <i data-lucide="trending-up" style="width:12px;height:12px;"></i> + 18.9% <span style="color:var(--gt-text-muted);">vs last month</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="customers-stat-card">
        <div class="customers-stat-icon-wrapper stat-accent-repeat">
            <i data-lucide="refresh-cw"></i>
        </div>
        <div class="customers-stat-meta">
            <div>
                <div class="customers-stat-title">Repeat Customers</div>
                <div class="customers-stat-count">{{ $repeatCustomersCount }}</div>
            </div>
            <div class="customers-stat-growth" style="color: #047857;">
                <i data-lucide="trending-up" style="width:12px;height:12px;"></i> + 12.4% <span style="color:var(--gt-text-muted);">vs last month</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 5 -->
    <div class="customers-stat-card">
        <div class="customers-stat-icon-wrapper stat-accent-inactive">
            <i data-lucide="user-minus"></i>
        </div>
        <div class="customers-stat-meta">
            <div>
                <div class="customers-stat-title">Inactive Customers</div>
                <div class="customers-stat-count">{{ $inactiveCustomersCount }}</div>
            </div>
            <div class="customers-stat-growth" style="color: #b91c1c;">
                <i data-lucide="trending-down" style="width:12px;height:12px;"></i> - 8.6% <span style="color:var(--gt-text-muted);">vs last month</span>
            </div>
        </div>
    </div>
</div>

<!-- Customers container card -->
<div class="customers-container-card">
    <form action="{{ route('admin.customers.index') }}" method="GET" id="filterForm">
        <!-- Filters Row -->
        <div class="filters-bar">
            <select name="status" class="gt-input" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <select name="group" class="gt-input" onchange="this.form.submit()">
                <option value="">All Groups</option>
                <option value="regular" {{ request('group') === 'regular' ? 'selected' : '' }}>Regular</option>
                <option value="vip" {{ request('group') === 'vip' ? 'selected' : '' }}>VIP</option>
                <option value="new" {{ request('group') === 'new' ? 'selected' : '' }}>New</option>
            </select>

            <div class="search-input-wrapper">
                <input type="text" name="search" value="{{ request('search') }}" class="gt-input" placeholder="Search customers..." onkeypress="if(event.key === 'Enter') this.form.submit();">
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
                    <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                    <option value="most_orders" {{ request('sort') === 'most_orders' ? 'selected' : '' }}>Most Orders</option>
                    <option value="highest_spending" {{ request('sort') === 'highest_spending' ? 'selected' : '' }}>Highest Spending</option>
                    <option value="lowest_spending" {{ request('sort') === 'lowest_spending' ? 'selected' : '' }}>Lowest Spending</option>
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
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Orders</th>
                        <th>Total Spent</th>
                        <th>Group</th>
                        <th>Status</th>
                        <th>Joined On</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $c)
                        @php
                            $initials = '';
                            $words = explode(' ', $c->name);
                            foreach ($words as $w) {
                                $initials .= strtoupper(substr($w, 0, 1));
                            }
                            $initials = substr($initials, 0, 2);
                            
                            $orders = $c->orders()->orderBy('created_at', 'desc')->take(5)->get();
                            $customerData = [
                                'id' => $c->id,
                                'name' => $c->name,
                                'email' => $c->email,
                                'phone' => $c->phone ?? '-',
                                'status' => $c->status,
                                'group' => $c->group,
                                'shipping_address' => $c->shipping_address ?? 'No shipping address provided',
                                'billing_address' => $c->billing_address ?? 'No billing address provided',
                                'notes' => $c->notes ?? 'No internal notes',
                                'valid_orders_count' => $c->valid_orders_count,
                                'total_spent' => $c->total_spent,
                                'joined_on' => $c->created_at->format('M d, Y h:i A'),
                                'recent_orders' => $orders
                            ];
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" class="item-row-checkbox">
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="customer-profile-circle">
                                        {{ $initials }}
                                    </div>
                                    <strong style="font-size:0.85rem;font-weight:700;color:var(--gt-primary);">{{ $c->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $c->email }}</td>
                            <td>{{ $c->phone ?? '-' }}</td>
                            <td style="font-weight:700;">{{ $c->valid_orders_count }}</td>
                            <td style="font-weight:800;">PKR {{ number_format($c->total_spent) }}</td>
                            <td>
                                <span class="badge-group {{ $c->group === 'vip' ? 'group-vip' : ($c->group === 'new' ? 'group-new' : 'group-regular') }}">
                                    {{ $c->group }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-status {{ $c->status === 'active' ? 'status-active' : 'status-inactive' }}">
                                    {{ $c->status }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;flex-direction:column;line-height:1.2;white-space:nowrap;">
                                    <strong style="font-size:0.8rem;font-weight:700;">{{ $c->created_at->format('M d, Y') }}</strong>
                                    <span style="font-size:0.7rem;color:var(--gt-text-muted);">{{ $c->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td style="text-align:right;">
                                <div class="row-actions" style="justify-content:flex-end;">
                                    <button class="btn-action-icon" onclick='openViewModal({!! json_encode($customerData) !!})' title="View Customer Details">
                                        <i data-lucide="eye" style="width:14px;height:14px;"></i>
                                    </button>
                                    <button class="btn-action-icon" onclick='openEditModal({!! json_encode($customerData) !!})' title="Edit Customer">
                                        <i data-lucide="edit-3" style="width:14px;height:14px;"></i>
                                    </button>
                                    
                                    <div class="action-dropdown-container">
                                        <button type="button" class="btn-action-icon" onclick="toggleRowMenu(event, 'dropmenu-{{ $c->id }}')">
                                            <i data-lucide="more-vertical" style="width:14px;height:14px;"></i>
                                        </button>
                                        <div class="action-dropdown-menu" id="dropmenu-{{ $c->id }}">
                                            <button class="action-dropdown-item" type="button" onclick='openViewModal({!! json_encode($customerData) !!})'>
                                                <i data-lucide="eye" style="width:12px;height:12px;"></i> View Customer
                                            </button>
                                            <button class="action-dropdown-item" type="button" onclick='openEditModal({!! json_encode($customerData) !!})'>
                                                <i data-lucide="edit-2" style="width:12px;height:12px;"></i> Edit Customer
                                            </button>
                                            <a href="{{ route('admin.orders') }}?search={{ urlencode($c->email) }}" class="action-dropdown-item">
                                                <i data-lucide="shopping-cart" style="width:12px;height:12px;"></i> View Orders
                                            </a>
                                            
                                            <form action="{{ route('admin.customers.toggle-status', $c->id) }}" method="POST" style="display:none;" id="statusForm-{{ $c->id }}">
                                                @csrf
                                                <input type="hidden" name="status" id="statusVal-{{ $c->id }}" value="">
                                            </form>
                                            
                                            @if($c->status === 'active')
                                                <button class="action-dropdown-item" type="button" onclick="submitStatusVal({{ $c->id }}, 'inactive')">
                                                    <i data-lucide="slash" style="width:12px;height:12px;"></i> Deactivate
                                                </button>
                                            @else
                                                <button class="action-dropdown-item" type="button" onclick="submitStatusVal({{ $c->id }}, 'active')">
                                                    <i data-lucide="check" style="width:12px;height:12px;"></i> Activate
                                                </button>
                                            @endif

                                            <form action="{{ route('admin.customers.destroy', $c->id) }}" method="POST" style="display:none;" id="deleteForm-{{ $c->id }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button class="action-dropdown-item text-danger" type="button" onclick="confirmCustomerDelete({{ $c->id }}, '{{ addslashes($c->name) }}')">
                                                <i data-lucide="trash-2" style="width:12px;height:12px;color:var(--gt-danger);"></i> Delete Customer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align:center;padding:30px;color:var(--gt-text-muted);font-weight:600;">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-row">
        <div class="pagination-info">
            Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} customers
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

            @if ($customers->hasPages())
                <a href="{{ $customers->previousPageUrl() }}" class="pagination-btn {{ $customers->onFirstPage() ? 'disabled' : '' }}">
                    <i data-lucide="chevron-left" style="width:16px;height:16px;"></i>
                </a>
                
                @foreach ($customers->getUrlRange(max(1, $customers->currentPage() - 2), min($customers->lastPage(), $customers->currentPage() + 2)) as $page => $url)
                    <a href="{{ $url }}" class="pagination-btn {{ $page == $customers->currentPage() ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach

                <a href="{{ $customers->nextPageUrl() }}" class="pagination-btn {{ !$customers->hasMorePages() ? 'disabled' : '' }}">
                    <i data-lucide="chevron-right" style="width:16px;height:16px;"></i>
                </a>
            @endif
        </div>
    </div>
</div>

<!-- ================= MODALS SECTION ================= -->

<!-- 1. View Customer Details Modal -->
<div class="gt-modal" id="viewCustomerModal" onclick="closeModalOnOutsideClick(event, 'viewCustomerModal')">
    <div class="gt-modal-dialog" style="max-width:720px;">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Customer Details</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('viewCustomerModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <div class="gt-modal-body" style="padding:20px;">
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;border-bottom:1.5px solid var(--gt-border);padding-bottom:16px;">
                <div class="customer-profile-circle" id="viewCustomerInitials" style="width:50px;height:50px;font-size:1.15rem;"></div>
                <div style="display:flex;flex-direction:column;line-height:1.2;">
                    <strong id="viewCustomerName" style="font-size:1.1rem;color:var(--gt-primary);font-weight:800;"></strong>
                    <span id="viewCustomerEmail" style="font-size:0.85rem;color:var(--gt-text-muted);"></span>
                </div>
                <div style="margin-left:auto;display:flex;gap:10px;">
                    <span id="viewCustomerGroup" class="badge-group"></span>
                    <span id="viewCustomerStatus" class="badge-status"></span>
                </div>
            </div>

            <div class="form-grid-2" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 20px; display: grid;">
                <div style="background:#fffcf8;border:1px solid var(--gt-border);border-radius:12px;padding:12px;text-align:center;">
                    <span style="font-size:0.7rem;color:var(--gt-text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.02em;">Total Orders</span>
                    <h4 id="viewCustomerTotalOrders" style="font-size:1.25rem;font-weight:800;color:var(--gt-text);margin-top:4px;"></h4>
                </div>
                <div style="background:#fffcf8;border:1px solid var(--gt-border);border-radius:12px;padding:12px;text-align:center;">
                    <span style="font-size:0.7rem;color:var(--gt-text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.02em;">Total Spent</span>
                    <h4 id="viewCustomerTotalSpent" style="font-size:1.25rem;font-weight:800;color:var(--gt-text);margin-top:4px;"></h4>
                </div>
                <div style="background:#fffcf8;border:1px solid var(--gt-border);border-radius:12px;padding:12px;text-align:center;">
                    <span style="font-size:0.7rem;color:var(--gt-text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.02em;">Avg Order Value</span>
                    <h4 id="viewCustomerAvgOrderValue" style="font-size:1.25rem;font-weight:800;color:var(--gt-text);margin-top:4px;"></h4>
                </div>
            </div>

            <div class="form-grid-2" style="margin-bottom:20px;">
                <div>
                    <h4 style="font-size:0.75rem;font-weight:800;color:var(--gt-primary);text-transform:uppercase;margin-bottom:6px;">Shipping Address</h4>
                    <p id="viewCustomerShipping" style="font-size:0.8rem;background:#fafaf8;border:1px solid var(--gt-border);border-radius:8px;padding:10px;min-height:60px;line-height:1.4;"></p>
                </div>
                <div>
                    <h4 style="font-size:0.75rem;font-weight:800;color:var(--gt-primary);text-transform:uppercase;margin-bottom:6px;">Billing Address</h4>
                    <p id="viewCustomerBilling" style="font-size:0.8rem;background:#fafaf8;border:1px solid var(--gt-border);border-radius:8px;padding:10px;min-height:60px;line-height:1.4;"></p>
                </div>
            </div>

            <div class="form-group-full" style="margin-bottom:20px;">
                <h4 style="font-size:0.75rem;font-weight:800;color:var(--gt-primary);text-transform:uppercase;margin-bottom:6px;">Internal Notes</h4>
                <p id="viewCustomerNotes" style="font-size:0.8rem;background:#fafaf8;border:1px solid var(--gt-border);border-radius:8px;padding:10px;line-height:1.4;"></p>
            </div>

            <div style="border-top:1.5px solid var(--gt-border);padding-top:16px;">
                <h3 style="font-size:0.85rem;font-weight:800;color:var(--gt-primary);margin-bottom:12px;text-transform:uppercase;">Recent Orders</h3>
                <div class="gt-table-wrap" style="margin-bottom:0;border-radius:10px;">
                    <table class="gt-table" style="font-size:0.78rem;">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="viewCustomerOrdersBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="gt-modal-footer">
            <button type="button" class="gt-btn-primary" onclick="closeModal('viewCustomerModal')">Done</button>
        </div>
    </div>
</div>

<!-- 2. Add Customer Modal -->
<div class="gt-modal" id="addCustomerModal" onclick="closeModalOnOutsideClick(event, 'addCustomerModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Add New Customer</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('addCustomerModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="{{ route('admin.customers.store') }}" method="POST">
            @csrf
            <div class="gt-modal-body">
                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Full Name *</label>
                        <input type="text" name="name" required class="gt-input" style="width:100%;" placeholder="e.g. Ali Raza">
                    </div>
                    <div>
                        <label class="gt-label">Email Address *</label>
                        <input type="email" name="email" required class="gt-input" style="width:100%;" placeholder="e.g. ali@example.com">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Phone Number</label>
                        <input type="text" name="phone" class="gt-input" style="width:100%;" placeholder="e.g. 0321-1234567">
                    </div>
                    <div>
                        <label class="gt-label">Password *</label>
                        <input type="password" name="password" required class="gt-input" style="width:100%;" placeholder="Minimum 6 characters">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Status *</label>
                        <select name="status" class="gt-input" style="width:100%;">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="gt-label">Customer Group *</label>
                        <select name="group" class="gt-input" style="width:100%;">
                            <option value="regular">Regular</option>
                            <option value="vip">VIP</option>
                            <option value="new">New</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Shipping Address</label>
                        <textarea name="shipping_address" rows="2" class="gt-input" style="width:100%;font-family:inherit;" placeholder="Delivery address..."></textarea>
                    </div>
                    <div>
                        <label class="gt-label">Billing Address</label>
                        <textarea name="billing_address" rows="2" class="gt-input" style="width:100%;font-family:inherit;" placeholder="Billing address..."></textarea>
                    </div>
                </div>

                <div class="form-group-full">
                    <label class="gt-label">Internal Notes</label>
                    <textarea name="notes" rows="2" class="gt-input" style="width:100%;font-family:inherit;" placeholder="Internal administrator notes..."></textarea>
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('addCustomerModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Save Customer</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Edit Customer Modal -->
<div class="gt-modal" id="editCustomerModal" onclick="closeModalOnOutsideClick(event, 'editCustomerModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Edit Customer</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('editCustomerModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="" method="POST" id="editCustomerForm">
            @csrf
            @method('PUT')
            <div class="gt-modal-body">
                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Full Name *</label>
                        <input type="text" name="name" id="editFormName" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Email Address *</label>
                        <input type="email" name="email" id="editFormEmail" required class="gt-input" style="width:100%;">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Phone Number</label>
                        <input type="text" name="phone" id="editFormPhone" class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Reset Password</label>
                        <input type="password" name="password" id="editFormPassword" class="gt-input" style="width:100%;" placeholder="Leave blank to keep existing password">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Status *</label>
                        <select name="status" id="editFormStatus" class="gt-input" style="width:100%;">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="gt-label">Customer Group *</label>
                        <select name="group" id="editFormGroup" class="gt-input" style="width:100%;">
                            <option value="regular">Regular</option>
                            <option value="vip">VIP</option>
                            <option value="new">New</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Shipping Address</label>
                        <textarea name="shipping_address" id="editFormShipping" rows="2" class="gt-input" style="width:100%;font-family:inherit;"></textarea>
                    </div>
                    <div>
                        <label class="gt-label">Billing Address</label>
                        <textarea name="billing_address" id="editFormBilling" rows="2" class="gt-input" style="width:100%;font-family:inherit;"></textarea>
                    </div>
                </div>

                <div class="form-group-full">
                    <label class="gt-label">Internal Notes</label>
                    <textarea name="notes" id="editFormNotes" rows="2" class="gt-input" style="width:100%;font-family:inherit;"></textarea>
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('editCustomerModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Update Customer</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toggle row actions popover
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

        // Fast status form submitter helper
        window.submitStatusVal = function(userId, status) {
            const form = document.getElementById(`statusForm-${userId}`);
            const input = document.getElementById(`statusVal-${userId}`);
            input.value = status;
            form.submit();
        };

        // View Customer Details Loader
        window.openViewModal = function(c) {
            // Set initials
            const initials = c.name.split(' ').map(w => w[0]).join('').toUpperCase().substring(0, 2);
            document.getElementById('viewCustomerInitials').innerText = initials;
            
            document.getElementById('viewCustomerName').innerText = c.name;
            document.getElementById('viewCustomerEmail').innerText = c.email;
            
            // Group badge
            const groupBadge = document.getElementById('viewCustomerGroup');
            groupBadge.innerText = c.group;
            groupBadge.className = 'badge-group ' + (c.group === 'vip' ? 'group-vip' : (c.group === 'new' ? 'group-new' : 'group-regular'));

            // Status badge
            const statusBadge = document.getElementById('viewCustomerStatus');
            statusBadge.innerText = c.status;
            statusBadge.className = 'badge-status ' + (c.status === 'active' ? 'status-active' : 'status-inactive');

            // Order metrics
            document.getElementById('viewCustomerTotalOrders').innerText = c.valid_orders_count;
            document.getElementById('viewCustomerTotalSpent').innerText = 'PKR ' + parseFloat(c.total_spent).toLocaleString();
            
            const avg = c.valid_orders_count > 0 ? (parseFloat(c.total_spent) / c.valid_orders_count) : 0;
            document.getElementById('viewCustomerAvgOrderValue').innerText = 'PKR ' + Math.round(avg).toLocaleString();

            // Addresses & Notes
            document.getElementById('viewCustomerShipping').innerText = c.shipping_address;
            document.getElementById('viewCustomerBilling').innerText = c.billing_address;
            document.getElementById('viewCustomerNotes').innerText = c.notes;

            // Build recent orders body
            const tbody = document.getElementById('viewCustomerOrdersBody');
            tbody.innerHTML = '';
            
            if (c.recent_orders && c.recent_orders.length > 0) {
                c.recent_orders.forEach(o => {
                    const row = document.createElement('tr');
                    
                    const cellNum = document.createElement('td');
                    cellNum.innerHTML = `<strong style="color:var(--gt-primary);">${o.order_number}</strong>`;
                    
                    const cellStatus = document.createElement('td');
                    cellStatus.innerHTML = `<span class="badge-status ${o.status === 'completed' || o.status === 'delivered' ? 'status-active' : 'status-inactive'}">${o.status}</span>`;
                    
                    const cellPayment = document.createElement('td');
                    cellPayment.innerText = o.payment_method.toUpperCase();
                    
                    const cellTotal = document.createElement('td');
                    cellTotal.innerText = 'PKR ' + parseFloat(o.final_total).toLocaleString();
                    
                    const cellDate = document.createElement('td');
                    const dateObj = new Date(o.created_at);
                    cellDate.innerText = dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

                    row.appendChild(cellNum);
                    row.appendChild(cellStatus);
                    row.appendChild(cellPayment);
                    row.appendChild(cellTotal);
                    row.appendChild(cellDate);
                    tbody.appendChild(row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:var(--gt-text-muted);font-weight:600;">No order transactions found.</td></tr>';
            }

            openModal('viewCustomerModal');
        };

        // Edit Modal Loader
        window.openEditModal = function(c) {
            const form = document.getElementById('editCustomerForm');
            form.action = `/admin/customers/${c.id}`;

            document.getElementById('editFormName').value = c.name;
            document.getElementById('editFormEmail').value = c.email;
            document.getElementById('editFormPhone').value = c.phone === '-' ? '' : c.phone;
            document.getElementById('editFormPassword').value = ''; // Reset password field
            document.getElementById('editFormStatus').value = c.status;
            document.getElementById('editFormGroup').value = c.group;
            
            document.getElementById('editFormShipping').value = c.shipping_address === 'No shipping address provided' ? '' : c.shipping_address;
            document.getElementById('editFormBilling').value = c.billing_address === 'No billing address provided' ? '' : c.billing_address;
            document.getElementById('editFormNotes').value = c.notes === 'No internal notes' ? '' : c.notes;

            openModal('editCustomerModal');
        };

        // Delete confirmation
        window.confirmCustomerDelete = function(id, name) {
            if (confirm(`Are you sure you want to permanently delete customer "${name}"?`)) {
                document.getElementById(`deleteForm-${id}`).submit();
            }
        };
    });
</script>
@endsection
