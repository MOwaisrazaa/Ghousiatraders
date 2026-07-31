@extends('admin.ghousia-layout')

@section('title', 'Admin Dashboard - Reports')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Stats Row for 6 cards */
    .reports-stats-row {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
        width: 100%;
        box-sizing: border-box;
    }

    .reports-stat-card {
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

    .reports-stat-icon-wrapper {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: #f9f6f0;
        color: var(--gt-primary);
    }

    .reports-stat-icon-wrapper i {
        width: 18px;
        height: 18px;
    }

    .reports-stat-meta {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
        min-width: 0;
    }

    .reports-stat-title {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--gt-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .reports-stat-count {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--gt-text);
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .reports-stat-growth {
        font-size: 0.72rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 4px;
    }

    .growth-up { color: #10b981; }
    .growth-down { color: #ef4444; }

    .reports-stat-desc {
        font-size: 0.68rem;
        font-weight: 600;
        color: var(--gt-text-muted);
    }

    /* Filter card */
    .filter-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 16px;
        padding: 16px;
        box-shadow: var(--gt-shadow);
        margin-bottom: 24px;
        box-sizing: border-box;
        width: 100%;
    }

    .filter-flex {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
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

    /* Split Button Export */
    .split-btn-container {
        position: relative;
        display: inline-flex;
        vertical-align: middle;
    }

    .split-btn-main {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-right: 1px solid rgba(255, 255, 255, 0.15);
    }

    .split-btn-toggle {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        padding: 10px 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .export-dropdown-menu {
        position: absolute;
        right: 0;
        top: 42px;
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 10px;
        box-shadow: 0 10px 25px rgba(53, 27, 13, 0.1);
        min-width: 150px;
        z-index: 100;
        display: none;
        flex-direction: column;
        padding: 4px;
    }

    .export-dropdown-menu.show {
        display: flex;
    }

    .export-dropdown-item {
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

    .export-dropdown-item:hover {
        background-color: var(--gt-primary-light);
        color: var(--gt-primary);
    }

    /* CSS Grid Layout */
    .reports-main-grid {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 24px;
        margin-bottom: 24px;
        width: 100%;
        box-sizing: border-box;
    }

    .grid-col-6 { grid-column: span 6 / span 6; }
    .grid-col-3 { grid-column: span 3 / span 3; }
    .grid-col-4 { grid-column: span 4 / span 4; }
    .grid-col-8 { grid-column: span 8 / span 8; }

    .report-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 20px;
        padding: 20px;
        box-shadow: var(--gt-shadow);
        box-sizing: border-box;
        min-width: 0;
        display: flex;
        flex-direction: column;
    }

    .report-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .report-card-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--gt-primary);
    }

    /* Tables */
    .report-table-wrap {
        border: 1px solid var(--gt-border);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 12px;
        background: #ffffff;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.8rem;
    }

    .report-table th {
        background-color: #fffaf3;
        color: var(--gt-text);
        font-weight: 800;
        padding: 10px 12px;
        border-bottom: 1px solid var(--gt-border);
        white-space: nowrap;
    }

    .report-table td {
        padding: 10px 12px;
        border-bottom: 1px solid rgba(215, 166, 74, 0.1);
        color: var(--gt-text);
        vertical-align: middle;
    }

    .report-table tr:last-child td {
        border-bottom: none;
    }

    /* Progress bar */
    .progress-bar-container {
        width: 100%;
        background-color: #f3f4f6;
        border-radius: 99px;
        height: 6px;
        overflow: hidden;
    }

    .progress-bar-fill {
        background-color: var(--gt-primary);
        height: 100%;
        border-radius: 99px;
    }

    /* Product Thumbnail Row */
    .product-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        border-bottom: 1px solid rgba(215, 166, 74, 0.1);
    }

    .product-row:last-child {
        border-bottom: none;
    }

    .product-thumb {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        object-fit: cover;
        background-color: #f9f6f0;
    }

    /* Report Center links list */
    .report-center-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid rgba(215, 166, 74, 0.1);
        cursor: pointer;
        transition: all 0.2s;
    }

    .report-center-item:last-child {
        border-bottom: none;
    }

    .report-center-item:hover {
        padding-left: 4px;
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
        max-width: 520px;
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

    .form-group {
        margin-bottom: 14px;
    }

    .gt-label {
        display: block;
        font-size: 0.72rem;
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
        box-sizing: border-box;
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

    /* Bottom quick action cards */
    .quick-actions-row {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
        width: 100%;
        box-sizing: border-box;
    }

    .quick-action-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 16px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        box-shadow: var(--gt-shadow);
        transition: all 0.2s;
        box-sizing: border-box;
    }

    .quick-action-card:hover {
        border-color: #d7a64a;
        background-color: var(--gt-primary-light);
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {
        .reports-stats-row {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .reports-main-grid {
            display: flex;
            flex-direction: column;
        }
        .quick-actions-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .reports-stats-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .quick-actions-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .reports-stats-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<x-admin-page-header title="Reports">
    <div class="split-btn-container">
        <button class="gt-btn-primary split-btn-main" onclick="triggerReportExport('csv')">
            <i data-lucide="download"></i> Export Report
        </button>
        <button class="gt-btn-primary split-btn-toggle" onclick="toggleExportMenu(event)">
            <i data-lucide="chevron-down" style="width:14px;height:14px;"></i>
        </button>
        <div class="export-dropdown-menu" id="exportDropdownMenu">
            <button class="export-dropdown-item" onclick="triggerReportExport('csv')">
                <i data-lucide="file-text" style="width:12px;height:12px;"></i> CSV
            </button>
            <button class="export-dropdown-item" onclick="triggerReportExport('excel')">
                <i data-lucide="table" style="width:12px;height:12px;"></i> Excel
            </button>
            <button class="export-dropdown-item" onclick="triggerReportExport('pdf')">
                <i data-lucide="file" style="width:12px;height:12px;"></i> PDF / Print
            </button>
        </div>
    </div>
</x-admin-page-header>

<!-- Report Statistics Row -->
<div class="reports-stats-row">
    <!-- Stat 1: Revenue -->
    <div class="reports-stat-card">
        <div class="reports-stat-icon-wrapper">
            <i data-lucide="credit-card"></i>
        </div>
        <div class="reports-stat-meta">
            <div>
                <div class="reports-stat-title">Total Revenue</div>
                <div class="reports-stat-count">PKR {{ number_format($stats['revenue']['value']) }}</div>
            </div>
            <div>
                <span class="reports-stat-growth {{ $stats['revenue']['growth'] >= 0 ? 'growth-up' : 'growth-down' }}">
                    <i data-lucide="{{ $stats['revenue']['growth'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ abs($stats['revenue']['growth']) }}%
                </span>
                <span class="reports-stat-desc">vs last period</span>
            </div>
        </div>
    </div>

    <!-- Stat 2: Orders -->
    <div class="reports-stat-card">
        <div class="reports-stat-icon-wrapper">
            <i data-lucide="shopping-bag"></i>
        </div>
        <div class="reports-stat-meta">
            <div>
                <div class="reports-stat-title">Total Orders</div>
                <div class="reports-stat-count">{{ number_format($stats['orders']['value']) }}</div>
            </div>
            <div>
                <span class="reports-stat-growth {{ $stats['orders']['growth'] >= 0 ? 'growth-up' : 'growth-down' }}">
                    <i data-lucide="{{ $stats['orders']['growth'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ abs($stats['orders']['growth']) }}%
                </span>
                <span class="reports-stat-desc">vs last period</span>
            </div>
        </div>
    </div>

    <!-- Stat 3: Customers -->
    <div class="reports-stat-card">
        <div class="reports-stat-icon-wrapper">
            <i data-lucide="users"></i>
        </div>
        <div class="reports-stat-meta">
            <div>
                <div class="reports-stat-title">Total Customers</div>
                <div class="reports-stat-count">{{ number_format($stats['customers']['value']) }}</div>
            </div>
            <div>
                <span class="reports-stat-growth {{ $stats['customers']['growth'] >= 0 ? 'growth-up' : 'growth-down' }}">
                    <i data-lucide="{{ $stats['customers']['growth'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ abs($stats['customers']['growth']) }}%
                </span>
                <span class="reports-stat-desc">vs last period</span>
            </div>
        </div>
    </div>

    <!-- Stat 4: Average Order Value -->
    <div class="reports-stat-card">
        <div class="reports-stat-icon-wrapper">
            <i data-lucide="percent"></i>
        </div>
        <div class="reports-stat-meta">
            <div>
                <div class="reports-stat-title">Average Order Value</div>
                <div class="reports-stat-count">PKR {{ number_format($stats['aov']['value']) }}</div>
            </div>
            <div>
                <span class="reports-stat-growth {{ $stats['aov']['growth'] >= 0 ? 'growth-up' : 'growth-down' }}">
                    <i data-lucide="{{ $stats['aov']['growth'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ abs($stats['aov']['growth']) }}%
                </span>
                <span class="reports-stat-desc">vs last period</span>
            </div>
        </div>
    </div>

    <!-- Stat 5: Refunds -->
    <div class="reports-stat-card">
        <div class="reports-stat-icon-wrapper">
            <i data-lucide="refresh-cw"></i>
        </div>
        <div class="reports-stat-meta">
            <div>
                <div class="reports-stat-title">Refunds</div>
                <div class="reports-stat-count">PKR {{ number_format($stats['refunds']['value']) }}</div>
            </div>
            <div>
                <span class="reports-stat-growth {{ $stats['refunds']['growth'] >= 0 ? 'growth-up' : 'growth-down' }}">
                    <i data-lucide="{{ $stats['refunds']['growth'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ abs($stats['refunds']['growth']) }}%
                </span>
                <span class="reports-stat-desc">vs last period</span>
            </div>
        </div>
    </div>

    <!-- Stat 6: Conversion Rate -->
    <div class="reports-stat-card">
        <div class="reports-stat-icon-wrapper">
            <i data-lucide="mouse-pointer"></i>
        </div>
        <div class="reports-stat-meta">
            <div>
                <div class="reports-stat-title">Conversion Rate</div>
                <div class="reports-stat-count">{{ $stats['conversion_rate']['value'] }}</div>
            </div>
            <div>
                @if($stats['conversion_rate']['growth'] !== null)
                    <span class="reports-stat-growth {{ $stats['conversion_rate']['growth'] >= 0 ? 'growth-up' : 'growth-down' }}">
                        <i data-lucide="{{ $stats['conversion_rate']['growth'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                        {{ abs($stats['conversion_rate']['growth']) }}%
                    </span>
                @endif
                <span class="reports-stat-desc">vs last period</span>
            </div>
        </div>
    </div>
</div>

<!-- Global Filters Card -->
<div class="filter-card">
    <form action="{{ route('admin.reports.index') }}" method="GET" id="globalFilterForm">
        <div class="filter-flex">
            <select name="period" class="gt-input" style="min-width:140px;" onchange="toggleCustomDates(this.value)">
                <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Today</option>
                <option value="this_week" {{ $period === 'this_week' ? 'selected' : '' }}>This Week</option>
                <option value="this_month" {{ $period === 'this_month' ? 'selected' : '' }}>This Month</option>
                <option value="last_month" {{ $period === 'last_month' ? 'selected' : '' }}>Last Month</option>
                <option value="last_3_months" {{ $period === 'last_3_months' ? 'selected' : '' }}>Last 3 Months</option>
                <option value="this_year" {{ $period === 'this_year' ? 'selected' : '' }}>This Year</option>
                <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Range</option>
            </select>

            <select name="channel" class="gt-input" style="min-width:140px;" onchange="this.form.submit()">
                <option value="all" {{ $channel === 'all' ? 'selected' : '' }}>All Channels</option>
                <option value="website" {{ $channel === 'website' ? 'selected' : '' }}>Website</option>
                <option value="mobile_app" {{ $channel === 'mobile_app' ? 'selected' : '' }}>Mobile App</option>
                <option value="facebook" {{ $channel === 'facebook' ? 'selected' : '' }}>Facebook</option>
                <option value="instagram" {{ $channel === 'instagram' ? 'selected' : '' }}>Instagram</option>
                <option value="other" {{ $channel === 'other' ? 'selected' : '' }}>Other Sources</option>
            </select>

            <select name="payment_method" class="gt-input" style="min-width:160px;" onchange="this.form.submit()">
                <option value="all" {{ $paymentMethod === 'all' ? 'selected' : '' }}>All Payment Methods</option>
                <option value="cod" {{ $paymentMethod === 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                <option value="jazzcash" {{ $paymentMethod === 'jazzcash' ? 'selected' : '' }}>JazzCash</option>
                <option value="easypaisa" {{ $paymentMethod === 'easypaisa' ? 'selected' : '' }}>EasyPaisa</option>
                <option value="bank_transfer" {{ $paymentMethod === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                <option value="stripe" {{ $paymentMethod === 'stripe' ? 'selected' : '' }}>Credit / Debit Card</option>
            </select>

            <div id="customDatesWrapper" style="display: {{ $period === 'custom' ? 'flex' : 'none' }}; align-items:center; gap:8px;">
                <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="gt-input">
                <span style="font-size:0.8rem;font-weight:700;color:var(--gt-text-muted);">to</span>
                <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="gt-input">
            </div>

            <button type="submit" class="gt-btn-primary" style="margin-left:auto;">
                Apply Filters
            </button>
        </div>
    </form>
</div>

<!-- Main Reports Grid -->
<div class="reports-main-grid">
    <!-- Chart: Sales Overview -->
    <div class="report-card grid-col-6">
        <div class="report-card-header">
            <h2 class="report-card-title">Sales Overview</h2>
            <form action="{{ route('admin.reports.index') }}" method="GET" id="chartGroupingForm" style="display:flex;align-items:center;gap:6px;">
                <!-- Propagate existing filters -->
                <input type="hidden" name="period" value="{{ $period }}">
                <input type="hidden" name="channel" value="{{ $channel }}">
                <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">

                <select name="chart_grouping" class="gt-input" style="padding:4px 8px;min-height:30px;font-size:0.8rem;" onchange="this.form.submit()">
                    <option value="hourly" {{ $chartGrouping === 'hourly' ? 'selected' : '' }}>Hourly</option>
                    <option value="daily" {{ $chartGrouping === 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="weekly" {{ $chartGrouping === 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="monthly" {{ $chartGrouping === 'monthly' ? 'selected' : '' }}>Monthly</option>
                </select>
            </form>
        </div>
        
        <div style="flex:1; display:flex; align-items:center; justify-content:center; min-height: 280px; position:relative;">
            @if(count($chartData) > 0)
                <canvas id="salesOverviewChart" style="width:100%;height:100%;max-height:280px;"></canvas>
            @else
                <div style="text-align:center;color:var(--gt-text-muted);font-weight:600;font-size:0.85rem;">No transaction data available for the selected range.</div>
            @endif
        </div>
    </div>

    <!-- Chart: Sales by Category -->
    <div class="report-card grid-col-3">
        <div class="report-card-header">
            <h2 class="report-card-title">Sales by Category</h2>
        </div>
        
        <div style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; min-height: 280px;">
            @if(count($topCategories) > 0)
                <div style="width:140px; height:140px; margin-bottom:16px; position:relative; display:flex; align-items:center; justify-content:center;">
                    <canvas id="categorySalesChart"></canvas>
                    <div style="position:absolute; text-align:center; pointer-events:none;">
                        <span style="font-size:0.62rem; font-weight:700; color:var(--gt-text-muted); text-transform:uppercase;">Total</span>
                        <div style="font-size:0.85rem; font-weight:800; color:var(--gt-text);">PKR {{ number_format($totalCategoryRevenue, 0) }}</div>
                    </div>
                </div>

                <div style="width:100%; display:flex; flex-direction:column; gap:4px; font-size:0.75rem;">
                    @foreach($topCategories as $cat)
                        <div style="display:flex; align-items:center; justify-content:space-between; font-weight:700;">
                            <span style="color:var(--gt-text);">{{ $cat['name'] }}</span>
                            <span style="color:var(--gt-text-muted);">PKR {{ number_format($cat['revenue']) }} ({{ $cat['percentage'] }}%)</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center;color:var(--gt-text-muted);font-weight:600;font-size:0.85rem;">No category contribution logs.</div>
            @endif
        </div>
    </div>

    <!-- Top Products Card -->
    <div class="report-card grid-col-3">
        <div class="report-card-header">
            <h2 class="report-card-title">Top Products</h2>
            <form action="{{ route('admin.reports.index') }}" method="GET" style="display:flex;align-items:center;gap:6px;">
                <input type="hidden" name="period" value="{{ $period }}">
                <input type="hidden" name="channel" value="{{ $channel }}">
                <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">
                
                <select name="product_sort" class="gt-input" style="padding:4px 8px;min-height:30px;font-size:0.8rem;" onchange="this.form.submit()">
                    <option value="revenue" {{ request('product_sort') === 'revenue' ? 'selected' : '' }}>By Revenue</option>
                    <option value="units" {{ request('product_sort') === 'units' ? 'selected' : '' }}>By Units Sold</option>
                    <option value="orders" {{ request('product_sort') === 'orders' ? 'selected' : '' }}>By Orders</option>
                </select>
            </form>
        </div>

        <div style="flex:1; display:flex; flex-direction:column; justify-content:space-between; min-height:280px;">
            <div style="display:flex; flex-direction:column;">
                @forelse($topProducts as $p)
                    <div class="product-row">
                        <img src="{{ $p['image_path'] ? asset($p['image_path']) : asset('assets/default.png') }}" class="product-thumb" alt="Thumb">
                        <div style="display:flex;flex-direction:column;flex:1;min-width:0;line-height:1.2;">
                            <span style="font-size:0.75rem;font-weight:700;color:var(--gt-text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $p['name'] }}</span>
                            <span style="font-size:0.68rem;color:var(--gt-text-muted);font-weight:600;">{{ $p['orders'] }} Orders</span>
                        </div>
                        <span style="font-size:0.75rem;font-weight:800;color:var(--gt-primary);">
                            @if(request('product_sort', 'revenue') === 'revenue')
                                PKR {{ number_format($p['revenue']) }}
                            @else
                                {{ $p['units'] }} Units
                            @endif
                        </span>
                    </div>
                @empty
                    <div style="text-align:center;padding:30px;color:var(--gt-text-muted);font-weight:600;font-size:0.85rem;">No products performance history.</div>
                @endforelse
            </div>
            
            <a href="{{ route('admin.products') }}" class="gt-btn-outline" style="width:100%; justify-content:center; padding:6px; min-height:30px; font-size:0.75rem; margin-top:8px;">
                View All Products
            </a>
        </div>
    </div>
</div>

<!-- Second Row Grid: Tables & Report Center -->
<div class="reports-main-grid">
    <!-- Table: Sales by Channel -->
    <div class="report-card grid-col-5">
        <div class="report-card-header">
            <h2 class="report-card-title">Sales by Channel</h2>
        </div>
        <div class="report-table-wrap">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Channel</th>
                        <th>Revenue</th>
                        <th>Orders</th>
                        <th>Customers</th>
                        <th>Conv. Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($channelData as $ch)
                        <tr>
                            <td style="font-weight:700;">{{ $ch['name'] }}</td>
                            <td style="font-weight:800;color:var(--gt-primary);">PKR {{ number_format($ch['revenue']) }}</td>
                            <td>{{ $ch['orders'] }}</td>
                            <td>{{ $ch['customers'] }}</td>
                            <td style="color:var(--gt-text-muted);font-weight:600;">{{ $ch['conversion_rate'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button class="gt-btn-outline" onclick="alert('Viewing full channel stats details.')" style="width:100%; justify-content:center; padding:6px; min-height:30px; font-size:0.75rem; margin-top:auto;">
            View Full Channel Report
        </button>
    </div>

    <!-- Table: Sales by Payment Method -->
    <div class="report-card grid-col-4">
        <div class="report-card-header">
            <h2 class="report-card-title">Sales by Payment Method</h2>
        </div>
        <div class="report-table-wrap">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Method</th>
                        <th>Revenue</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentData as $pm)
                        <tr>
                            <td style="font-weight:700;">{{ $pm['name'] }}</td>
                            <td style="font-weight:800;color:var(--gt-primary);">PKR {{ number_format($pm['revenue']) }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px;">
                                    <span style="font-weight:700;width:30px;">{{ $pm['percentage'] }}%</span>
                                    <div class="progress-bar-container" style="flex:1;">
                                        <div class="progress-bar-fill" style="width:{{ $pm['percentage'] }}%;"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <button class="gt-btn-outline" onclick="alert('Viewing payment method details report.')" style="width:100%; justify-content:center; padding:6px; min-height:30px; font-size:0.75rem; margin-top:auto;">
            View Payment Report
        </button>
    </div>

    <!-- Report Center -->
    <div class="report-card grid-col-3">
        <div class="report-card-header">
            <h2 class="report-card-title">Report Center</h2>
        </div>
        <div style="display:flex;flex-direction:column;flex:1;justify-content:space-between;">
            <div>
                <!-- Report Center Item 1 -->
                <div class="report-center-item" onclick="location.href='{{ route('admin.reports.index') }}'">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <i data-lucide="bar-chart-2" style="width:16px;height:16px;color:var(--gt-primary);"></i>
                        <div style="display:flex;flex-direction:column;line-height:1.2;">
                            <strong style="font-size:0.78rem;color:var(--gt-text);font-weight:800;">Sales Report</strong>
                            <span style="font-size:0.65rem;color:var(--gt-text-muted);">Detailed sales and revenue report</span>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;color:var(--gt-text-muted);"></i>
                </div>

                <!-- Report Center Item 2 -->
                <div class="report-center-item" onclick="location.href='{{ route('admin.orders') }}'">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <i data-lucide="shopping-bag" style="width:16px;height:16px;color:var(--gt-primary);"></i>
                        <div style="display:flex;flex-direction:column;line-height:1.2;">
                            <strong style="font-size:0.78rem;color:var(--gt-text);font-weight:800;">Orders Report</strong>
                            <span style="font-size:0.65rem;color:var(--gt-text-muted);">Order status and performance report</span>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;color:var(--gt-text-muted);"></i>
                </div>

                <!-- Report Center Item 3 -->
                <div class="report-center-item" onclick="location.href='{{ route('admin.customers.index') }}'">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <i data-lucide="users" style="width:16px;height:16px;color:var(--gt-primary);"></i>
                        <div style="display:flex;flex-direction:column;line-height:1.2;">
                            <strong style="font-size:0.78rem;color:var(--gt-text);font-weight:800;">Customers Report</strong>
                            <span style="font-size:0.65rem;color:var(--gt-text-muted);">Customer insights and analytics</span>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;color:var(--gt-text-muted);"></i>
                </div>

                <!-- Report Center Item 4 -->
                <div class="report-center-item" onclick="location.href='{{ route('admin.products') }}'">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <i data-lucide="package" style="width:16px;height:16px;color:var(--gt-primary);"></i>
                        <div style="display:flex;flex-direction:column;line-height:1.2;">
                            <strong style="font-size:0.78rem;color:var(--gt-text);font-weight:800;">Products Report</strong>
                            <span style="font-size:0.65rem;color:var(--gt-text-muted);">Product performance and stock report</span>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;color:var(--gt-text-muted);"></i>
                </div>

                <!-- Report Center Item 5 -->
                <div class="report-center-item" onclick="alert('Tax calculations reports.')">
                    <div style="display:flex;align-items:center;gap:10px;">
                        <i data-lucide="percent" style="width:16px;height:16px;color:var(--gt-primary);"></i>
                        <div style="display:flex;flex-direction:column;line-height:1.2;">
                            <strong style="font-size:0.78rem;color:var(--gt-text);font-weight:800;">Tax Report</strong>
                            <span style="font-size:0.65rem;color:var(--gt-text-muted);">Tax collected and summary report</span>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;color:var(--gt-text-muted);"></i>
                </div>
            </div>
            
            <button class="gt-btn-outline" onclick="alert('Viewing all registered reports overview.')" style="width:100%; justify-content:center; padding:6px; min-height:30px; font-size:0.75rem; margin-top:8px;">
                View All Reports
            </button>
        </div>
    </div>
</div>

<!-- Reports Help Strip -->
<div class="filter-card" style="display:flex; align-items:center; gap:16px;">
    <div style="width:40px;height:40px;border-radius:50%;background-color:var(--gt-primary-light);color:var(--gt-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i data-lucide="info" style="width:20px;height:20px;"></i>
    </div>
    <div style="display:flex;flex-direction:column;line-height:1.3;">
        <strong style="font-size:0.85rem;color:var(--gt-text);font-weight:800;">Reports Help</strong>
        <span style="font-size:0.72rem;color:var(--gt-text-muted);">Use the reporting dashboard to monitor conversion parameters, payment breakdowns, and top-selling courses. You can refine dates, channels, or payment groupings using filters and export standard data formats.</span>
    </div>
</div>

<!-- Quick Actions Cards -->
<h3 style="font-size:0.85rem;font-weight:800;color:var(--gt-primary);margin-bottom:12px;text-transform:uppercase;letter-spacing:0.05em;">Quick Actions</h3>
<div class="quick-actions-row">
    <div class="quick-action-card" onclick="openModal('scheduleReportsModal')">
        <div style="width:36px;height:36px;border-radius:8px;background:var(--gt-primary-light);color:var(--gt-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i data-lucide="calendar"></i>
        </div>
        <div style="display:flex;flex-direction:column;line-height:1.2;">
            <strong style="font-size:0.8rem;color:var(--gt-text);font-weight:800;">Schedule Reports</strong>
            <span style="font-size:0.68rem;color:var(--gt-text-muted);">Automate report delivery</span>
        </div>
    </div>

    <div class="quick-action-card" onclick="openModal('comparePeriodsModal')">
        <div style="width:36px;height:36px;border-radius:8px;background:var(--gt-primary-light);color:var(--gt-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i data-lucide="git-compare"></i>
        </div>
        <div style="display:flex;flex-direction:column;line-height:1.2;">
            <strong style="font-size:0.8rem;color:var(--gt-text);font-weight:800;">Compare Periods</strong>
            <span style="font-size:0.68rem;color:var(--gt-text-muted);">Analyze trends</span>
        </div>
    </div>

    <div class="quick-action-card" onclick="triggerReportExport('csv')">
        <div style="width:36px;height:36px;border-radius:8px;background:var(--gt-primary-light);color:var(--gt-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i data-lucide="download"></i>
        </div>
        <div style="display:flex;flex-direction:column;line-height:1.2;">
            <strong style="font-size:0.8rem;color:var(--gt-text);font-weight:800;">Export Data</strong>
            <span style="font-size:0.68rem;color:var(--gt-text-muted);">CSV, PDF or Excel</span>
        </div>
    </div>

    <div class="quick-action-card" onclick="openModal('customReportModal')">
        <div style="width:36px;height:36px;border-radius:8px;background:var(--gt-primary-light);color:var(--gt-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i data-lucide="sliders"></i>
        </div>
        <div style="display:flex;flex-direction:column;line-height:1.2;">
            <strong style="font-size:0.8rem;color:var(--gt-text);font-weight:800;">Custom Report</strong>
            <span style="font-size:0.68rem;color:var(--gt-text-muted);">Build your own report</span>
        </div>
    </div>
</div>

<!-- ================= MODALS SECTION ================= -->

<!-- 1. Schedule Reports Modal -->
<div class="gt-modal" id="scheduleReportsModal" onclick="closeModalOnOutsideClick(event, 'scheduleReportsModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Schedule Reports</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('scheduleReportsModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form onsubmit="event.preventDefault(); alert('Reports schedule has been saved successfully!'); closeModal('scheduleReportsModal');">
            <div class="gt-modal-body">
                <div class="form-group">
                    <label class="gt-label">Report Type</label>
                    <select class="gt-input" style="width:100%;">
                        <option>Sales Performance Summary</option>
                        <option>Products Inventory Status</option>
                        <option>Customer Registrations Analysis</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="gt-label">Delivery Frequency</label>
                    <select class="gt-input" style="width:100%;">
                        <option>Daily</option>
                        <option>Weekly (Every Monday)</option>
                        <option>Monthly (1st of Month)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="gt-label">Recipient Email Address</label>
                    <input type="email" required value="{{ auth()->user()->email ?? '' }}" class="gt-input" style="width:100%;">
                </div>
                <div class="form-group">
                    <label class="gt-label">Attachment Export Format</label>
                    <select class="gt-input" style="width:100%;">
                        <option>Excel / CSV</option>
                        <option>PDF Document</option>
                    </select>
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('scheduleReportsModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Schedule Report</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Compare Periods Modal -->
<div class="gt-modal" id="comparePeriodsModal" onclick="closeModalOnOutsideClick(event, 'comparePeriodsModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Compare Periods</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('comparePeriodsModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="{{ route('admin.reports.index') }}" method="GET">
            <div class="gt-modal-body">
                <div class="form-group">
                    <label class="gt-label">Target Period</label>
                    <select name="period" class="gt-input" style="width:100%;">
                        <option value="this_month">This Month vs Last Month</option>
                        <option value="this_week">This Week vs Last Week</option>
                        <option value="this_year">This Year vs Last Year</option>
                    </select>
                </div>
                <div style="background-color:#fffdf9; border:1px dashed var(--gt-border); padding:12px; border-radius:10px; font-size:0.75rem; font-weight:600; color:var(--gt-text-muted);">
                    Comparison details will render percentages indicators above stats cards automatically upon form submission.
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('comparePeriodsModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Compare</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Custom Report Modal -->
<div class="gt-modal" id="customReportModal" onclick="closeModalOnOutsideClick(event, 'customReportModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Create Custom Report</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('customReportModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form onsubmit="event.preventDefault(); alert('Custom report generated successfully!'); closeModal('customReportModal');">
            <div class="gt-modal-body">
                <div class="form-group">
                    <label class="gt-label">Primary Metric</label>
                    <select class="gt-input" style="width:100%;">
                        <option>Total Sales Value (PKR)</option>
                        <option>Units Ordered Quantity</option>
                        <option>AOV (Average Order Value)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="gt-label">Group Dimension</label>
                    <select class="gt-input" style="width:100%;">
                        <option>Group by Product Item</option>
                        <option>Group by Category Name</option>
                        <option>Group by Billing City</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="gt-label">Channel Source</label>
                    <select class="gt-input" style="width:100%;">
                        <option>All channels</option>
                        <option>Website traffic</option>
                        <option>Direct link reference</option>
                    </select>
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('customReportModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Generate Report</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toggle export drop menu dropdown
        window.toggleExportMenu = function(event) {
            event.stopPropagation();
            document.getElementById('exportDropdownMenu').classList.toggle('show');
        };

        document.addEventListener('click', () => {
            const el = document.getElementById('exportDropdownMenu');
            if (el) el.classList.remove('show');
        });

        // Trigger PDF/Print or CSV exports
        window.triggerReportExport = function(format) {
            const queryParams = new URLSearchParams(window.location.search);
            queryParams.set('export_format', format);
            window.open(`/admin/reports/export?${queryParams.toString()}`, '_blank');
        };

        // Custom datepicker toggles
        window.toggleCustomDates = function(period) {
            const datesWrap = document.getElementById('customDatesWrapper');
            if (period === 'custom') {
                datesWrap.style.display = 'flex';
            } else {
                datesWrap.style.display = 'none';
                document.getElementById('globalFilterForm').submit();
            }
        };

        // Modal triggers
        window.openModal = function(id) {
            document.getElementById(id).classList.add('show');
        };

        window.closeModal = function(id) {
            document.getElementById(id).classList.remove('show');
        };

        window.closeModalOnOutsideClick = function(event, id) {
            if (event.target === document.getElementById(id)) {
                closeModal(id);
            }
        };

        // Render Sales Overview Chart.js line
        const overviewEl = document.getElementById('salesOverviewChart');
        if (overviewEl) {
            const chartDataRaw = {!! json_encode($chartData) !!};
            const labels = chartDataRaw.map(d => d.label);
            const revenues = chartDataRaw.map(d => parseFloat(d.revenue) || 0);
            const counts = chartDataRaw.map(d => parseInt(d.orders_count) || 0);

            new Chart(overviewEl, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Revenue (PKR)',
                            data: revenues,
                            borderColor: '#351b0d',
                            backgroundColor: 'rgba(53, 27, 13, 0.05)',
                            fill: true,
                            tension: 0.3,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Orders Count',
                            data: counts,
                            borderColor: '#d7a64a',
                            backgroundColor: 'transparent',
                            borderDash: [5, 5],
                            tension: 0.3,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            type: 'linear',
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Revenue (PKR)',
                                font: { weight: 'bold' }
                            }
                        },
                        y1: {
                            type: 'linear',
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            title: {
                                display: true,
                                text: 'Orders Count',
                                font: { weight: 'bold' }
                            }
                        }
                    }
                }
            });
        }

        // Render Sales by Category Doughnut Chart.js
        const categoryEl = document.getElementById('categorySalesChart');
        if (categoryEl) {
            const categoriesRaw = {!! json_encode($topCategories) !!};
            const labels = Object.values(categoriesRaw).map(c => c.name);
            const values = Object.values(categoriesRaw).map(c => parseFloat(c.revenue) || 0);

            new Chart(categoryEl, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: ['#351b0d', '#d7a64a', '#8b5a2b', '#cd853f', '#b0c4de'],
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: { display: false }
                    },
                    cutout: '75%',
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    });
</script>
@endsection
