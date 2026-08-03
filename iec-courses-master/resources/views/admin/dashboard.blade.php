@extends('admin.ghousia-layout')

@section('title', 'Dashboard')

@push('head')
<style>
    /* Dashboard-specific CSS Styles */
    .dashboard-grid {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .stat-box {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--gt-shadow);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 12px;
        min-height: 135px;
        min-width: 0;
        box-sizing: border-box;
    }

    .stat-box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-icon-container {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background-color: var(--gt-primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gt-primary);
    }

    .stat-icon-container i {
        width: 20px;
        height: 20px;
    }

    .stat-box-label {
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--gt-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .stat-box-value {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--gt-text);
        margin: 4px 0;
    }

    .stat-box-growth {
        font-size: 0.72rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .growth-up {
        color: var(--gt-success);
    }

    .growth-text {
        color: var(--gt-text-muted);
        font-weight: 500;
    }

    /* Layout section rows */
    .dashboard-row-upper {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr) minmax(300px, 1fr);
        gap: 24px;
        width: 100%;
    }

    .dashboard-row-lower {
        display: grid;
        grid-template-columns: minmax(0, 1.6fr) minmax(280px, 1fr) minmax(300px, 1fr);
        gap: 24px;
        width: 100%;
    }

    /* Base Card structure */
    .dash-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 16px;
        padding: 24px;
        box-shadow: var(--gt-shadow);
        display: flex;
        flex-direction: column;
        min-width: 0;
        box-sizing: border-box;
    }

    .dash-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(215, 166, 74, 0.15);
    }

    .dash-card-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--gt-text);
    }

    .dash-card-filter-btn {
        border: 1.5px solid var(--gt-border);
        background: transparent;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        color: var(--gt-primary);
    }

    .dash-card-filter-btn:hover {
        background-color: var(--gt-primary-light);
    }

    /* Recent Orders styling */
    .orders-stack {
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex: 1;
    }

    .order-stack-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        border: 1px solid rgba(215, 166, 74, 0.12);
        border-radius: 10px;
        text-decoration: none;
        color: inherit;
        font-size: 0.82rem;
        transition: all 0.2s;
    }

    .order-stack-item:hover {
        background-color: var(--gt-primary-light);
        border-color: var(--gt-border);
    }

    .order-stack-id {
        font-weight: 800;
        color: var(--gt-primary);
    }

    .order-stack-name {
        font-weight: 600;
        color: var(--gt-text);
    }

    .order-stack-amount {
        font-weight: 700;
        color: var(--gt-text);
    }

    /* Status pills */
    .status-pill {
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .status-pill-completed { background: #ecfdf5; color: #047857; }
    .status-pill-paid { background: #fffbeb; color: #b45309; }
    .status-pill-shipped { background: #eff6ff; color: #1d4ed8; }
    .status-pill-pending { background: #f3f4f6; color: #374151; }
    .status-pill-cancelled { background: #fef2f2; color: #b91c1c; }

    .view-all-orders-btn {
        text-align: center;
        display: block;
        margin-top: 14px;
        color: var(--gt-primary);
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 700;
        transition: color 0.2s;
    }

    .view-all-orders-btn:hover {
        color: #d7a64a;
    }

    /* Bestsellers Table styling */
    .bestsellers-table-wrapper {
        overflow-x: auto;
    }

    .bestsellers-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .bestsellers-table th {
        padding: 10px 12px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--gt-text-muted);
        border-bottom: 2px solid rgba(215, 166, 74, 0.15);
    }

    .bestsellers-table td {
        padding: 12px;
        font-size: 0.85rem;
        vertical-align: middle;
        border-bottom: 1px solid rgba(215, 166, 74, 0.1);
    }

    .prod-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .prod-thumb {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid rgba(215, 166, 74, 0.15);
        background: #fffbf5;
    }

    .prod-title {
        font-weight: 700;
        color: var(--gt-text);
    }

    /* Quick Actions Grid */
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        flex: 1;
    }

    .action-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--gt-border);
        background: #fffdf9;
        text-decoration: none;
        color: var(--gt-text);
        transition: all 0.2s ease;
        text-align: center;
    }

    .action-box:hover {
        background-color: var(--gt-primary-light);
        border-color: #d7a64a;
        transform: translateY(-2px);
    }

    .action-box i {
        width: 24px;
        height: 24px;
        color: var(--gt-primary);
    }

    .action-box span {
        font-size: 0.82rem;
        font-weight: 700;
    }

    /* Low Stock items styling */
    .low-stock-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex: 1;
    }

    .low-stock-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        border: 1px solid rgba(215, 166, 74, 0.12);
        border-radius: 10px;
        background: #fffcf8;
    }

    .low-stock-meta {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .low-stock-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--gt-text);
    }

    .low-stock-cat {
        font-size: 0.72rem;
        color: var(--gt-text-muted);
        font-weight: 600;
    }

    .stock-count-badge {
        background: #fef2f2;
        color: var(--gt-danger);
        font-size: 0.75rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 6px;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Doughnut Chart Alignment */
    .doughnut-chart-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
    }

    .doughnut-legend {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-top: 16px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
        color: var(--gt-text);
        font-weight: 600;
    }

    .legend-color-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    /* Responsive adjustments */
    @media (max-width: 1199px) {
        .stats-row {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .dashboard-row-upper, .dashboard-row-lower {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 991px) {
        .stats-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-row-upper, .dashboard-row-lower {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .stats-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-grid">
    
    <!-- Dashboard Header Section -->
    <x-admin-page-header title="Dashboard">
        <!-- Date filter form -->
        <form action="{{ route('admin.dashboard') }}" method="GET" id="dateFilterForm" style="display:flex; align-items:center; gap:8px;">
            <div style="background:#ffffff; border:1.5px solid var(--gt-border); border-radius:10px; padding:6px 12px; display:flex; align-items:center; gap:8px; font-size:0.82rem; font-weight:700;">
                <i data-lucide="calendar" style="width:16px; height:16px; color:var(--gt-text-muted);"></i>
                <input type="date" name="start_date" value="{{ $startDateInput }}" style="border:none; outline:none; font-family:inherit; color:inherit; cursor:pointer;" onchange="document.getElementById('dateFilterForm').submit();">
                <span style="color:var(--gt-text-muted);">to</span>
                <input type="date" name="end_date" value="{{ $endDateInput }}" style="border:none; outline:none; font-family:inherit; color:inherit; cursor:pointer;" onchange="document.getElementById('dateFilterForm').submit();">
            </div>
        </form>
    </x-admin-page-header>

    <!-- 4. Statistics Cards (5 Cards) -->
    <div class="stats-row">
        
        <!-- Stat 1: Total Sales -->
        <div class="stat-box">
            <div class="stat-box-header">
                <span class="stat-box-label">Total Sales</span>
                <div class="stat-icon-container"><i data-lucide="shopping-bag"></i></div>
            </div>
            <div>
                <h3 class="stat-box-value">PKR {{ number_format($totalSales) }}</h3>
                <div class="stat-box-growth">
                    <span class="growth-up"><i data-lucide="arrow-up-right" style="width:12px; height:12px; display:inline-block; vertical-align:middle;"></i> 23.5%</span>
                    <span class="growth-text">vs last week</span>
                </div>
            </div>
        </div>

        <!-- Stat 2: Total Orders -->
        <div class="stat-box">
            <div class="stat-box-header">
                <span class="stat-box-label">Total Orders</span>
                <div class="stat-icon-container"><i data-lucide="shopping-cart"></i></div>
            </div>
            <div>
                <h3 class="stat-box-value">{{ number_format($totalOrders) }}</h3>
                <div class="stat-box-growth">
                    <span class="growth-up"><i data-lucide="arrow-up-right" style="width:12px; height:12px; display:inline-block; vertical-align:middle;"></i> 18.7%</span>
                    <span class="growth-text">vs last week</span>
                </div>
            </div>
        </div>

        <!-- Stat 3: Total Customers -->
        <div class="stat-box">
            <div class="stat-box-header">
                <span class="stat-box-label">Total Customers</span>
                <div class="stat-icon-container"><i data-lucide="users"></i></div>
            </div>
            <div>
                <h3 class="stat-box-value">{{ number_format($totalCustomers) }}</h3>
                <div class="stat-box-growth">
                    <span class="growth-up"><i data-lucide="arrow-up-right" style="width:12px; height:12px; display:inline-block; vertical-align:middle;"></i> 15.3%</span>
                    <span class="growth-text">vs last week</span>
                </div>
            </div>
        </div>

        <!-- Stat 4: Total Products -->
        <div class="stat-box">
            <div class="stat-box-header">
                <span class="stat-box-label">Total Products</span>
                <div class="stat-icon-container"><i data-lucide="package"></i></div>
            </div>
            <div>
                <h3 class="stat-box-value">{{ number_format($totalProducts) }}</h3>
                <div class="stat-box-growth">
                    <span class="growth-up"><i data-lucide="arrow-up-right" style="width:12px; height:12px; display:inline-block; vertical-align:middle;"></i> 8.2%</span>
                    <span class="growth-text">vs last week</span>
                </div>
            </div>
        </div>

        <!-- Stat 5: Average Order Value -->
        <div class="stat-box">
            <div class="stat-box-header">
                <span class="stat-box-label">Avg. Order Value</span>
                <div class="stat-icon-container"><i data-lucide="calculator"></i></div>
            </div>
            <div>
                <h3 class="stat-box-value">PKR {{ number_format($avgOrderValue) }}</h3>
                <div class="stat-box-growth">
                    <span class="growth-up"><i data-lucide="arrow-up-right" style="width:12px; height:12px; display:inline-block; vertical-align:middle;"></i> 12.1%</span>
                    <span class="growth-text">vs last week</span>
                </div>
            </div>
        </div>

    </div>

    <!-- 5. Main Dashboard Grid (Upper Row) -->
    <div class="dashboard-row-upper">
        
        <!-- Sales Overview Card (Line Chart) -->
        <div class="dash-card">
            <div class="dash-card-header">
                <span class="dash-card-title">Sales Overview</span>
                <button type="button" class="dash-card-filter-btn">This Week</button>
            </div>
            
            <div style="margin-bottom:14px;">
                <h3 style="font-size:1.6rem; font-weight:800; color:var(--gt-primary);">PKR {{ number_format($totalSales) }}</h3>
                <div style="font-size:0.75rem; color:var(--gt-success); font-weight:700; margin-top:2px;">
                    <i data-lucide="arrow-up-right" style="width:12px; height:12px; display:inline-block; vertical-align:middle;"></i> 23.5%
                    <span style="color:var(--gt-text-muted); font-weight:500;">vs last period</span>
                </div>
            </div>

            <!-- Canvas wrapper -->
            <div style="position:relative; height:200px; width:100%; flex:1;">
                <canvas id="salesOverviewChart"></canvas>
            </div>
        </div>

        <!-- Order Status Summary Card (Doughnut Chart) -->
        <div class="dash-card">
            <div class="dash-card-header">
                <span class="dash-card-title">Order Status Summary</span>
            </div>
            
            <!-- Doughnut Flex Wrapper to display side-by-side without overlapping -->
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-top: 10px; flex-wrap: wrap;">
                <!-- Doughnut core -->
                <div class="doughnut-chart-container" style="flex-shrink: 0; width: 140px; height: 140px; margin: 0;">
                    <canvas id="orderStatusChart"></canvas>
                </div>

                <!-- Doughnut details -->
                <div class="doughnut-legend" style="flex: 1; min-width: 130px; margin-top: 0;">
                    <div class="legend-item">
                        <span><span class="legend-color-dot" style="background:#10b981;"></span> Delivered</span>
                        <strong>{{ $deliveredCount }} ({{ round(($deliveredCount/$statusTotal)*100, 1) }}%)</strong>
                    </div>
                    <div class="legend-item">
                        <span><span class="legend-color-dot" style="background:#f59e0b;"></span> Processing</span>
                        <strong>{{ $processingCount }} ({{ round(($processingCount/$statusTotal)*100, 1) }}%)</strong>
                    </div>
                    <div class="legend-item">
                        <span><span class="legend-color-dot" style="background:#3b82f6;"></span> Shipped</span>
                        <strong>{{ $shippedCount }} ({{ round(($shippedCount/$statusTotal)*100, 1) }}%)</strong>
                    </div>
                    <div class="legend-item">
                        <span><span class="legend-color-dot" style="background:#6b7280;"></span> Pending</span>
                        <strong>{{ $pendingCount }} ({{ round(($pendingCount/$statusTotal)*100, 1) }}%)</strong>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.orders') }}" class="view-all-orders-btn">View All Orders &rarr;</a>
        </div>

        <!-- Recent Orders Stack List -->
        <div class="dash-card">
            <div class="dash-card-header">
                <span class="dash-card-title">Recent Orders</span>
                <a href="{{ route('admin.orders') }}" class="dash-card-filter-btn" style="text-decoration:none;">View All</a>
            </div>

            <div class="orders-stack">
                @foreach($recentOrders as $orderItem)
                    <a href="{{ route('admin.orders') }}" class="order-stack-item" style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                        <div style="display: flex; align-items: center; gap: 8px; min-width: 0; flex: 1;">
                            <span class="order-stack-id" style="font-weight: 800; color: var(--gt-primary); flex-shrink: 0;">#GT-{{ $orderItem['id'] }}</span>
                            <span class="order-stack-name" style="font-weight: 600; color: var(--gt-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $orderItem['customer'] }}</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; flex-shrink: 0;">
                            <span class="order-stack-amount" style="font-weight: 700; color: var(--gt-text); white-space: nowrap;">PKR {{ number_format($orderItem['amount']) }}</span>
                            <span class="status-pill status-pill-{{ $orderItem['status'] }}" style="flex-shrink: 0;">{{ $orderItem['statusLabel'] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <a href="{{ route('admin.orders') }}" class="view-all-orders-btn">View All Orders &rarr;</a>
        </div>

    </div>

    <!-- 5. Main Dashboard Grid (Lower Row) -->
    <div class="dashboard-row-lower">
        
        <!-- 6. Best-Selling Products Card -->
        <div class="dash-card">
            <div class="dash-card-header">
                <span class="dash-card-title">Best-Selling Products</span>
            </div>
            <div class="bestsellers-table-wrapper">
                <table class="bestsellers-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Sold</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bestSellers as $seller)
                            @php
                                $catName = $seller->category ? $seller->category->name : 'Baby Care';
                            @endphp
                            <tr>
                                <td>
                                    <div class="prod-cell">
                                        <img src="{{ $seller->image_path ? asset($seller->image_path) : asset('ghousiatraders/assets/baby_lotion.png') }}" alt="{{ $seller->name }}" class="prod-thumb">
                                        <span class="prod-title">{{ $seller->name }}</span>
                                    </div>
                                </td>
                                <td><span style="font-weight:600; color:var(--gt-text-muted);">{{ $catName }}</span></td>
                                <td><span style="font-weight:700; color:var(--gt-text);">{{ $seller->sold }}</span></td>
                                <td><span style="font-weight:800; color:var(--gt-primary);">PKR {{ number_format($seller->revenue) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 7. Quick Actions Card -->
        <div class="dash-card">
            <div class="dash-card-header">
                <span class="dash-card-title">Quick Actions</span>
            </div>
            <div class="actions-grid">
                <a href="{{ route('admin.products.create') }}" class="action-box">
                    <i data-lucide="plus-circle"></i>
                    <span>Add New Product</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="action-box">
                    <i data-lucide="folder-plus"></i>
                    <span>Add New Category</span>
                </a>
                <a href="{{ route('admin.coupons.index') }}" class="action-box">
                    <i data-lucide="ticket"></i>
                    <span>Create Coupon</span>
                </a>
                <a href="{{ route('admin.orders') }}" class="action-box">
                    <i data-lucide="shopping-cart"></i>
                    <span>Create Order</span>
                </a>
                <a href="{{ route('admin.users') }}" class="action-box">
                    <i data-lucide="users"></i>
                    <span>Manage Customers</span>
                </a>
                <a href="#" class="action-box">
                    <i data-lucide="bar-chart-3"></i>
                    <span>View Reports</span>
                </a>
            </div>
        </div>

        <!-- 8. Low-Stock Products Card -->
        <div class="dash-card">
            <div class="dash-card-header">
                <span class="dash-card-title">Low Stock Products</span>
                <a href="{{ route('admin.products') }}" class="dash-card-filter-btn" style="text-decoration:none;">View All</a>
            </div>
            <div class="low-stock-list">
                @foreach($lowStockProducts as $lowItem)
                    @php
                        $lowCatName = $lowItem->category ? $lowItem->category->name : 'Baby Care';
                    @endphp
                    <div class="low-stock-item">
                        <div class="prod-cell">
                            <img src="{{ $lowItem->image_path ? asset($lowItem->image_path) : asset('ghousiatraders/assets/baby_lotion.png') }}" alt="{{ $lowItem->name }}" class="prod-thumb">
                            <div class="low-stock-meta">
                                <span class="low-stock-title">{{ $lowItem->name }}</span>
                                <span class="low-stock-cat">{{ $lowCatName }}</span>
                            </div>
                        </div>
                        <span class="stock-count-badge">Stock: {{ $lowItem->stock }}</span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    
    // Initialize Line Chart for Sales Overview
    const salesCtx = document.getElementById('salesOverviewChart');
    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Sales Trend',
                    data: {!! json_encode($chartData) !!},
                    borderColor: '#8a7355',
                    borderWidth: 3,
                    backgroundColor: 'rgba(215, 166, 74, 0.08)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#44240f',
                    pointBorderColor: '#ffffff',
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#8a7355', font: { family: 'Plus Jakarta Sans', size: 9, weight: '600' } }
                    },
                    y: {
                        grid: { color: 'rgba(215, 166, 74, 0.08)' },
                        ticks: {
                            color: '#8a7355',
                            font: { family: 'Plus Jakarta Sans', size: 9, weight: '600' },
                            callback: function(value) {
                                return value >= 1000 ? (value / 1000) + 'k' : value;
                            }
                        }
                    }
                }
            }
        });
    }

    // Initialize Doughnut Chart for Order Status
    const statusCtx = document.getElementById('orderStatusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Delivered', 'Processing', 'Shipped', 'Pending'],
                datasets: [{
                    data: [{{ $deliveredCount }}, {{ $processingCount }}, {{ $shippedCount }}, {{ $pendingCount }}],
                    backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#9ca3af'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false }
                }
            },
            plugins: [{
                id: 'centerText',
                afterDraw: (chart) => {
                    const { ctx, chartArea: { top, bottom, left, right, width, height } } = chart;
                    ctx.save();
                    ctx.font = '800 1.25rem "Plus Jakarta Sans"';
                    ctx.fillStyle = '#351b0d';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(Number({{ $statusTotal }}).toLocaleString(), left + width / 2, top + height / 2 - 8);
                    
                    ctx.font = '600 0.65rem "Plus Jakarta Sans"';
                    ctx.fillStyle = '#8a7355';
                    ctx.fillText('Total Orders', left + width / 2, top + height / 2 + 10);
                    ctx.restore();
                }
            }]
        });
    }

});
</script>
@endpush
