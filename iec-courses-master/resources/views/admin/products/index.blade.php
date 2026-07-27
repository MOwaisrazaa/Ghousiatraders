@extends('admin.ghousia-layout')

@section('title', 'Admin Dashboard - Products')

@section('content')
<style>
    /* Stats row for 5 cards */
    .products-stats-row {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
        width: 100%;
        box-sizing: border-box;
    }

    .products-stat-card {
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

    .products-stat-icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .products-stat-icon-wrapper i {
        width: 20px;
        height: 20px;
    }

    .products-stat-meta {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
        min-width: 0;
    }

    .products-stat-title {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--gt-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    .products-stat-count {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--gt-text);
        line-height: 1.1;
        margin-bottom: 6px;
    }

    .products-stat-growth {
        font-size: 0.68rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 2px;
        white-space: nowrap;
    }

    /* Colors and Accents for Stats */
    .stat-accent-total { background: #f3f4f6; color: #4b5563; }
    .stat-accent-active { background: #ecfdf5; color: #047857; }
    .stat-accent-outofstock { background: #fef2f2; color: #b91c1c; }
    .stat-accent-lowstock { background: #fffbeb; color: #b45309; }
    .stat-accent-draft { background: #faf5ff; color: #6b21a8; }

    /* Two-column layout */
    .products-main-layout {
        display: grid;
        grid-template-columns: 78% 22%;
        gap: 20px;
        width: 100%;
        box-sizing: border-box;
    }

    /* Left Table Container */
    .products-table-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--gt-shadow);
        box-sizing: border-box;
        min-width: 0;
    }

    /* Right Insights Sidebar */
    .products-insights-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
        min-width: 0;
    }

    .insight-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 20px;
        padding: 20px;
        box-shadow: var(--gt-shadow);
        box-sizing: border-box;
    }

    /* Filters elements styling */
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

    /* Product Status Tabs */
    .status-tabs-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1.5px solid var(--gt-border);
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .status-tabs {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .status-tab-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--gt-text-muted);
        padding: 10px 4px;
        border-bottom: 2.5px solid transparent;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-tab-btn span {
        background-color: #f3f4f6;
        color: #4b5563;
        font-size: 0.7rem;
        padding: 1px 6px;
        border-radius: 6px;
        font-weight: 700;
    }

    .status-tab-btn.active {
        color: var(--gt-primary);
        border-bottom-color: var(--gt-primary);
    }

    .status-tab-btn.active span {
        background-color: var(--gt-primary-light);
        color: var(--gt-primary);
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

    /* Category Badge */
    .badge-category {
        background-color: #fff8ee;
        color: var(--gt-primary);
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        border: 1.5px solid rgba(215, 166, 74, 0.2);
        white-space: nowrap;
    }

    /* Status Badge */
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
    .status-draft { background-color: #f3f4f6; color: #4b5563; }
    .status-lowstock { background-color: #fffbeb; color: #b45309; }
    .status-outofstock { background-color: #fef2f2; color: #b91c1c; }
    .status-inactive { background-color: #f3f4f6; color: #6b7280; }

    /* Quantity Colors */
    .qty-sufficient { color: #047857; font-weight: 700; }
    .qty-low { color: #b45309; font-weight: 700; }
    .qty-none { color: #b91c1c; font-weight: 700; }

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

    /* Doughnut Chart representation using conic-gradient */
    .doughnut-container {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 20px 0;
    }

    .doughnut-chart {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .doughnut-chart-inner {
        width: 96px;
        height: 96px;
        background: #ffffff;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(53, 27, 13, 0.05);
    }

    /* Insights legend */
    .legend-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 15px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.8rem;
    }

    .legend-label-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    /* Top selling list */
    .top-sellers-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 15px;
    }

    .top-seller-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .top-seller-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        flex: 1;
        min-width: 0;
    }

    .top-seller-image {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid var(--gt-border);
    }

    .top-seller-info {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .top-seller-name {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--gt-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .top-seller-sales {
        font-size: 0.68rem;
        color: var(--gt-text-muted);
    }

    .top-seller-revenue {
        font-size: 0.8rem;
        font-weight: 800;
        color: var(--gt-text);
        white-space: nowrap;
    }

    /* Modal Layout */
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
        max-width: 780px;
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

    .form-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 16px;
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

    .preview-thumbnail {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 6px;
        border: 1.5px solid var(--gt-border);
        margin-top: 6px;
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
        .products-stats-row {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .products-main-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .products-stats-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .form-grid-3 {
            grid-template-columns: 1fr;
        }
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .products-stats-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Sub navigation header -->
<div class="sub-nav-bar" style="margin-bottom: 24px;">
    <div class="sub-nav-left">
        <h1 class="page-title">Products</h1>
        <div class="breadcrumbs-list">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <i data-lucide="chevron-right"></i>
            <span>Products</span>
        </div>
    </div>
    <div class="sub-nav-right" style="gap:10px;">
        <a href="{{ route('admin.products.export', request()->all()) }}" class="gt-btn-outline" style="min-height:38px;padding:0 16px;">
            <i data-lucide="download" style="width:16px;height:16px;"></i> Export
        </a>
        <button class="gt-btn-outline" onclick="openModal('importProductsModal')" style="min-height:38px;padding:0 16px;">
            <i data-lucide="upload" style="width:16px;height:16px;"></i> Import
        </button>
        <button class="gt-btn-primary" onclick="openModal('addProductModal')">
            <i data-lucide="plus"></i> Add New Product
        </button>
    </div>
</div>

<!-- Product Statistics Row -->
<div class="products-stats-row">
    <!-- Stat Card 1 -->
    <div class="products-stat-card">
        <div class="products-stat-icon-wrapper stat-accent-total">
            <i data-lucide="shopping-bag"></i>
        </div>
        <div class="products-stat-meta">
            <div>
                <div class="products-stat-title">Total Products</div>
                <div class="products-stat-count">{{ $totalProductsCount }}</div>
            </div>
            <div class="products-stat-growth" style="color: #047857;">
                <i data-lucide="trending-up" style="width:12px;height:12px;"></i> + 18.6% <span style="color:var(--gt-text-muted);">vs last week</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="products-stat-card">
        <div class="products-stat-icon-wrapper stat-accent-active">
            <i data-lucide="check-circle"></i>
        </div>
        <div class="products-stat-meta">
            <div>
                <div class="products-stat-title">Active Products</div>
                <div class="products-stat-count">{{ $activeProductsCount }}</div>
            </div>
            <div class="products-stat-growth" style="color: #047857;">
                <i data-lucide="trending-up" style="width:12px;height:12px;"></i> + 15.3% <span style="color:var(--gt-text-muted);">vs last week</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="products-stat-card">
        <div class="products-stat-icon-wrapper stat-accent-outofstock">
            <i data-lucide="alert-octagon"></i>
        </div>
        <div class="products-stat-meta">
            <div>
                <div class="products-stat-title">Out of Stock</div>
                <div class="products-stat-count">{{ $outOfStockCount }}</div>
            </div>
            <div class="products-stat-growth" style="color: #b91c1c;">
                <i data-lucide="trending-down" style="width:12px;height:12px;"></i> - 8.7% <span style="color:var(--gt-text-muted);">vs last week</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="products-stat-card">
        <div class="products-stat-icon-wrapper stat-accent-lowstock">
            <i data-lucide="alert-triangle"></i>
        </div>
        <div class="products-stat-meta">
            <div>
                <div class="products-stat-title">Low Stock</div>
                <div class="products-stat-count">{{ $lowStockCount }}</div>
            </div>
            <div class="products-stat-growth" style="color: #b45309;">
                <i data-lucide="trending-up" style="width:12px;height:12px;"></i> + 3.2% <span style="color:var(--gt-text-muted);">vs last week</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 5 -->
    <div class="products-stat-card">
        <div class="products-stat-icon-wrapper stat-accent-draft">
            <i data-lucide="file-text"></i>
        </div>
        <div class="products-stat-meta">
            <div>
                <div class="products-stat-title">Draft Products</div>
                <div class="products-stat-count">{{ $draftProductsCount }}</div>
            </div>
            <div class="products-stat-growth" style="color: #047857;">
                <i data-lucide="trending-up" style="width:12px;height:12px;"></i> + 5.1% <span style="color:var(--gt-text-muted);">vs last week</span>
            </div>
        </div>
    </div>
</div>

<!-- Two Column Main Layout -->
<div class="products-main-layout">
    <!-- Left Column: Products Table Card -->
    <div class="products-table-card">
        <form action="{{ route('admin.products') }}" method="GET" id="filterForm">
            <!-- Filter inputs bar -->
            <div class="filters-bar">
                <select name="category_id" class="gt-input" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="stock_status" class="gt-input" onchange="this.form.submit()">
                    <option value="">All Stock Status</option>
                    <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>

                <select name="price_type" class="gt-input" onchange="this.form.submit()">
                    <option value="">All Price Types</option>
                    <option value="regular" {{ request('price_type') === 'regular' ? 'selected' : '' }}>Regular Price</option>
                    <option value="on_sale" {{ request('price_type') === 'on_sale' ? 'selected' : '' }}>On Sale</option>
                    <option value="free" {{ request('price_type') === 'free' ? 'selected' : '' }}>Free Products</option>
                </select>

                <div class="search-input-wrapper">
                    <input type="text" name="search" value="{{ request('search') }}" class="gt-input" placeholder="Search by product name, SKU..." onkeypress="if(event.key === 'Enter') this.form.submit();">
                    <i data-lucide="search"></i>
                </div>

                <button type="submit" class="gt-btn-filter">
                    <i data-lucide="filter"></i> Filters
                </button>
            </div>

            <!-- Tab elements row -->
            <div class="status-tabs-row">
                <div class="status-tabs">
                    <a href="{{ route('admin.products', array_merge(request()->except('status_tab'), ['status_tab' => 'all'])) }}" class="status-tab-btn {{ request('status_tab', 'all') === 'all' ? 'active' : '' }}">
                        All Products <span>{{ $allTabCount }}</span>
                    </a>
                    <a href="{{ route('admin.products', array_merge(request()->except('status_tab'), ['status_tab' => 'active'])) }}" class="status-tab-btn {{ request('status_tab') === 'active' ? 'active' : '' }}">
                        Active <span>{{ $activeTabCount }}</span>
                    </a>
                    <a href="{{ route('admin.products', array_merge(request()->except('status_tab'), ['status_tab' => 'draft'])) }}" class="status-tab-btn {{ request('status_tab') === 'draft' ? 'active' : '' }}">
                        Draft <span>{{ $draftTabCount }}</span>
                    </a>
                    <a href="{{ route('admin.products', array_merge(request()->except('status_tab'), ['status_tab' => 'out_of_stock'])) }}" class="status-tab-btn {{ request('status_tab') === 'out_of_stock' ? 'active' : '' }}">
                        Out of Stock <span>{{ $outOfStockTabCount }}</span>
                    </a>
                    <a href="{{ route('admin.products', array_merge(request()->except('status_tab'), ['status_tab' => 'low_stock'])) }}" class="status-tab-btn {{ request('status_tab') === 'low_stock' ? 'active' : '' }}">
                        Low Stock <span>{{ $lowStockTabCount }}</span>
                    </a>
                    <a href="{{ route('admin.products', array_merge(request()->except('status_tab'), ['status_tab' => 'featured'])) }}" class="status-tab-btn {{ request('status_tab') === 'featured' ? 'active' : '' }}">
                        Featured <span>{{ $featuredTabCount }}</span>
                    </a>
                </div>

                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:0.78rem;font-weight:700;color:var(--gt-text-muted);">Sort by:</span>
                    <select name="sort" class="gt-input" style="padding:4px 8px;min-height:30px;font-size:0.8rem;" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="name_asc" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                        <option value="name_desc" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price Low to High</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price High to Low</option>
                        <option value="stock_low" {{ request('sort') === 'stock_low' ? 'selected' : '' }}>Stock Low to High</option>
                        <option value="best_selling" {{ request('sort') === 'best_selling' ? 'selected' : '' }}>Best Selling</option>
                    </select>
                </div>
            </div>

            <!-- Invisible values for pagination inputs -->
            <input type="hidden" name="per_page" id="formPerPageInput" value="{{ request('per_page', 10) }}">
            <input type="hidden" name="status_tab" value="{{ request('status_tab', 'all') }}">
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
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                            <tr>
                                <td>
                                    <input type="checkbox" class="item-row-checkbox">
                                </td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        @if($p->image_path)
                                            <img src="{{ asset($p->image_path) }}" class="top-seller-image" style="width:36px;height:36px;">
                                        @else
                                            <div class="top-seller-image" style="width:36px;height:36px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--gt-primary);">
                                                {{ strtoupper(substr($p->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div style="display:flex;flex-direction:column;line-height:1.2;">
                                            <strong style="font-size:0.85rem;font-weight:700;color:var(--gt-primary);">{{ $p->name }}</strong>
                                            <span style="font-size:0.7rem;color:var(--gt-text-muted);">{{ Str::limit($p->description, 40) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code style="font-size:0.75rem;font-weight:700;color:var(--gt-text-muted);">{{ $p->sku }}</code>
                                </td>
                                <td>
                                    <span class="badge-category">{{ $p->category->name ?? 'None' }}</span>
                                </td>
                                <td style="font-weight:700;">
                                    @if($p->sale_price)
                                        <div style="display:flex;flex-direction:column;line-height:1.2;">
                                            <span style="color:#047857;">PKR {{ number_format($p->sale_price) }}</span>
                                            <span style="text-decoration:line-through;font-size:0.68rem;color:var(--gt-text-muted);">PKR {{ number_format($p->weekly_price) }}</span>
                                        </div>
                                    @else
                                        <span>PKR {{ number_format($p->weekly_price) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->stock <= 0)
                                        <span class="qty-none">0</span>
                                    @elseif($p->stock <= $p->low_stock_threshold)
                                        <span class="qty-low">{{ $p->stock }}</span>
                                    @else
                                        <span class="qty-sufficient">{{ $p->stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-status {{ $p->status === 'active' ? 'status-active' : ($p->status === 'draft' ? 'status-draft' : 'status-inactive') }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex;flex-direction:column;line-height:1.2;white-space:nowrap;">
                                        <strong style="font-size:0.8rem;font-weight:700;">{{ $p->created_at->format('M d, Y') }}</strong>
                                        <span style="font-size:0.7rem;color:var(--gt-text-muted);">{{ $p->created_at->format('h:i A') }}</span>
                                    </div>
                                </td>
                                <td style="text-align:right;">
                                    <div class="row-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('admin.products.show', $p->id) }}" class="btn-action-icon" title="View Detail">
                                            <i data-lucide="eye" style="width:14px;height:14px;"></i>
                                        </a>
                                        <button type="button" class="btn-action-icon" onclick='openEditModal({!! json_encode($p) !!})' title="Edit Product">
                                            <i data-lucide="edit-3" style="width:14px;height:14px;"></i>
                                        </button>
                                        
                                        <div class="action-dropdown-container">
                                            <button type="button" class="btn-action-icon" onclick="toggleRowMenu(event, 'dropmenu-{{ $p->id }}')">
                                                <i data-lucide="more-vertical" style="width:14px;height:14px;"></i>
                                            </button>
                                            <div class="action-dropdown-menu" id="dropmenu-{{ $p->id }}">
                                                <a href="{{ route('admin.products.show', $p->id) }}" class="action-dropdown-item">
                                                    <i data-lucide="eye" style="width:12px;height:12px;"></i> View Product
                                                </a>
                                                <button class="action-dropdown-item" onclick='openEditModal({!! json_encode($p) !!})'>
                                                    <i data-lucide="edit-2" style="width:12px;height:12px;"></i> Edit Product
                                                </button>
                                                <button class="action-dropdown-item" onclick="openStockModal({{ $p->id }}, {{ $p->stock }})">
                                                    <i data-lucide="package" style="width:12px;height:12px;"></i> Manage Stock
                                                </button>
                                                
                                                <form action="{{ route('admin.products.toggle-featured', $p->id) }}" method="POST" style="display:none;" id="featuredForm-{{ $p->id }}">
                                                    @csrf
                                                </form>
                                                <button class="action-dropdown-item" onclick="document.getElementById('featuredForm-{{ $p->id }}').submit()">
                                                    <i data-lucide="star" style="width:12px;height:12px;"></i> {{ $p->is_featured ? 'Unfeature' : 'Feature Product' }}
                                                </button>

                                                <form action="{{ route('admin.products.toggle-status', $p->id) }}" method="POST" style="display:none;" id="statusForm-{{ $p->id }}">
                                                    @csrf
                                                    <input type="hidden" name="status" id="statusVal-{{ $p->id }}" value="">
                                                </form>
                                                
                                                @if($p->status === 'active')
                                                    <button class="action-dropdown-item" onclick="submitStatusVal({{ $p->id }}, 'draft')">
                                                        <i data-lucide="file-text" style="width:12px;height:12px;"></i> Move to Draft
                                                    </button>
                                                    <button class="action-dropdown-item" onclick="submitStatusVal({{ $p->id }}, 'inactive')">
                                                        <i data-lucide="slash" style="width:12px;height:12px;"></i> Deactivate
                                                    </button>
                                                @else
                                                    <button class="action-dropdown-item" onclick="submitStatusVal({{ $p->id }}, 'active')">
                                                        <i data-lucide="check" style="width:12px;height:12px;"></i> Activate
                                                    </button>
                                                @endif

                                                <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" style="display:none;" id="deleteForm-{{ $p->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button class="action-dropdown-item text-danger" onclick="confirmProductDelete({{ $p->id }}, '{{ addslashes($p->name) }}')">
                                                    <i data-lucide="trash-2" style="width:12px;height:12px;color:var(--gt-danger);"></i> Delete Product
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align:center;padding:30px;color:var(--gt-text-muted);font-weight:600;">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination-row">
            <div class="pagination-info">
                Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
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

                @if ($products->hasPages())
                    <a href="{{ $products->previousPageUrl() }}" class="pagination-btn {{ $products->onFirstPage() ? 'disabled' : '' }}">
                        <i data-lucide="chevron-left" style="width:16px;height:16px;"></i>
                    </a>
                    
                    @foreach ($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                        <a href="{{ $url }}" class="pagination-btn {{ $page == $products->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endforeach

                    <a href="{{ $products->nextPageUrl() }}" class="pagination-btn {{ !$products->hasMorePages() ? 'disabled' : '' }}">
                        <i data-lucide="chevron-right" style="width:16px;height:16px;"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Sidebar Insights -->
    <div class="products-insights-sidebar">
        <!-- 1. Product Insights doughnut chart card -->
        <div class="insight-card">
            <h3 style="font-size:0.85rem;font-weight:800;color:var(--gt-primary);margin-bottom:12px;display:flex;align-items:center;gap:8px;">
                <i data-lucide="pie-chart" style="width:16px;height:16px;"></i> Product Insights
            </h3>
            
            @php
                $total = $totalProductsCount ?: 1;
                $activePct = ($activeProductsCount / $total) * 100;
                $lowPct = $activePct + (($lowStockCount / $total) * 100);
                $outPct = $lowPct + (($outOfStockCount / $total) * 100);
            @endphp
            
            <div class="doughnut-container">
                <div class="doughnut-chart" style="background: conic-gradient(#10b981 0% {{ $activePct }}%, #f59e0b {{ $activePct }}% {{ $lowPct }}%, #ef4444 {{ $lowPct }}% {{ $outPct }}%, #8a7355 {{ $outPct }}% 100%);">
                    <div class="doughnut-chart-inner">
                        <strong style="font-size:1.35rem;font-weight:800;color:var(--gt-text);">{{ $totalProductsCount }}</strong>
                        <span style="font-size:0.6rem;color:var(--gt-text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.02em;">Total Products</span>
                    </div>
                </div>
            </div>

            <div class="legend-list">
                <div class="legend-item">
                    <div class="legend-label-wrapper">
                        <span class="legend-dot" style="background:#10b981;"></span>
                        <span style="font-weight:600;color:var(--gt-text);">Active Products</span>
                    </div>
                    <strong style="color:var(--gt-text);">{{ $activeProductsCount }}</strong>
                </div>
                <div class="legend-item">
                    <div class="legend-label-wrapper">
                        <span class="legend-dot" style="background:#f59e0b;"></span>
                        <span style="font-weight:600;color:var(--gt-text);">Low Stock</span>
                    </div>
                    <strong style="color:var(--gt-text);">{{ $lowStockCount }}</strong>
                </div>
                <div class="legend-item">
                    <div class="legend-label-wrapper">
                        <span class="legend-dot" style="background:#ef4444;"></span>
                        <span style="font-weight:600;color:var(--gt-text);">Out of Stock</span>
                    </div>
                    <strong style="color:var(--gt-text);">{{ $outOfStockCount }}</strong>
                </div>
                <div class="legend-item">
                    <div class="legend-label-wrapper">
                        <span class="legend-dot" style="background:#8a7355;"></span>
                        <span style="font-weight:600;color:var(--gt-text);">Draft Products</span>
                    </div>
                    <strong style="color:var(--gt-text);">{{ $draftProductsCount }}</strong>
                </div>
            </div>

            <button type="button" class="gt-btn-outline" style="width:100%;margin-top:16px;padding:8px 12px;font-size:0.8rem;min-height:34px;">
                View All Insights
            </button>
        </div>

        <!-- 2. Top Selling Products Card -->
        <div class="insight-card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                <h3 style="font-size:0.85rem;font-weight:800;color:var(--gt-primary);display:flex;align-items:center;gap:8px;margin:0;">
                    <i data-lucide="flame" style="width:16px;height:16px;color:#ef4444;"></i> Top Selling
                </h3>
                <form action="{{ route('admin.products') }}" method="GET" id="periodForm">
                    <select name="period" class="gt-input" style="padding:2px 6px;min-height:26px;font-size:0.75rem;border-radius:6px;" onchange="this.form.submit()">
                        <option value="this_week" {{ request('period') === 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="this_month" {{ request('period', 'this_month') === 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="last_3_months" {{ request('period') === 'last_3_months' ? 'selected' : '' }}>Last 3 Months</option>
                        <option value="this_year" {{ request('period') === 'this_year' ? 'selected' : '' }}>This Year</option>
                    </select>
                    <!-- Carry filters forward -->
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('category_id')) <input type="hidden" name="category_id" value="{{ request('category_id') }}"> @endif
                </form>
            </div>

            <div class="top-sellers-list">
                @forelse($topSellingProducts as $item)
                    <div class="top-seller-item">
                        <div class="top-seller-meta">
                            @if($item['product']->image_path)
                                <img src="{{ asset($item['product']->image_path) }}" class="top-seller-image">
                            @else
                                <div class="top-seller-image" style="background:#f3f4f6;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:800;">
                                    {{ strtoupper(substr($item['product']->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="top-seller-info">
                                <span class="top-seller-name" title="{{ $item['product']->name }}">{{ $item['product']->name }}</span>
                                <span class="top-seller-sales">Sold: {{ $item['qty'] }}</span>
                            </div>
                        </div>
                        <div class="top-seller-revenue">
                            PKR {{ number_format($item['revenue']) }}
                        </div>
                    </div>
                @empty
                    <div style="font-size:0.75rem;color:var(--gt-text-muted);text-align:center;padding:12px;font-weight:600;">No sales records found.</div>
                @endforelse
            </div>

            <button type="button" class="gt-btn-outline" style="width:100%;margin-top:16px;padding:8px 12px;font-size:0.8rem;min-height:34px;">
                View All Products
            </button>
        </div>
    </div>
</div>

<!-- ================= MODALS SECTION ================= -->

<!-- 1. Add Product Modal -->
<div class="gt-modal" id="addProductModal" onclick="closeModalOnOutsideClick(event, 'addProductModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Add New Product</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('addProductModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="gt-modal-body">
                <div class="form-grid-3">
                    <div>
                        <label class="gt-label">Product Name *</label>
                        <input type="text" name="name" required class="gt-input" style="width:100%;" placeholder="e.g. Baby Wipes">
                    </div>
                    <div>
                        <label class="gt-label">SKU</label>
                        <input type="text" name="sku" class="gt-input" style="width:100%;" placeholder="e.g. GT-P-0021">
                    </div>
                    <div>
                        <label class="gt-label">Category *</label>
                        <select name="category_id" required class="gt-input" style="width:100%;">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-grid-3">
                    <div>
                        <label class="gt-label">Regular Price *</label>
                        <input type="number" name="weekly_price" required class="gt-input" style="width:100%;" placeholder="Price in PKR">
                    </div>
                    <div>
                        <label class="gt-label">Sale Price</label>
                        <input type="number" name="sale_price" class="gt-input" style="width:100%;" placeholder="Sale Price in PKR">
                    </div>
                    <div>
                        <label class="gt-label">Cost Price</label>
                        <input type="number" name="cost_price" class="gt-input" style="width:100%;" placeholder="Cost Price in PKR">
                    </div>
                </div>

                <div class="form-grid-3">
                    <div>
                        <label class="gt-label">Stock Quantity *</label>
                        <input type="number" name="stock" value="10" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Low Stock Threshold *</label>
                        <input type="number" name="low_stock_threshold" value="5" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Status *</label>
                        <select name="status" class="gt-input" style="width:100%;">
                            <option value="active">Active</option>
                            <option value="draft">Draft</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Featured Status</label>
                        <div style="display:flex;align-items:center;gap:10px;margin-top:10px;">
                            <input type="checkbox" name="is_featured" value="1" id="addFeaturedInput">
                            <label for="addFeaturedInput" style="font-size:0.8rem;font-weight:700;color:var(--gt-text);">Display in Featured Section</label>
                        </div>
                    </div>
                    <div>
                        <label class="gt-label">Product Image</label>
                        <input type="file" name="image_path" class="gt-input" style="width:100%;padding:4px 12px;">
                    </div>
                </div>

                <div class="form-group-full">
                    <label class="gt-label">Short Description</label>
                    <textarea name="description" rows="2" class="gt-input" style="width:100%;font-family:inherit;" placeholder="Short preview text for cards..."></textarea>
                </div>

                <div class="form-group-full">
                    <label class="gt-label">Full Description</label>
                    <textarea name="long_description" rows="4" class="gt-input" style="width:100%;font-family:inherit;" placeholder="Detailed product specifications or descriptions..."></textarea>
                </div>

                <!-- SEO Section -->
                <div style="margin-top:20px;border-top:1.5px dashed var(--gt-border);padding-top:16px;">
                    <h3 style="font-size:0.85rem;font-weight:800;color:var(--gt-primary);margin-bottom:12px;text-transform:uppercase;">SEO Optimization</h3>
                    <div class="form-group-full">
                        <label class="gt-label">SEO Title</label>
                        <input type="text" name="meta_title" class="gt-input" style="width:100%;" placeholder="Meta title for Google listings...">
                    </div>
                    <div class="form-group-full" style="margin-bottom:0;">
                        <label class="gt-label">Meta Description</label>
                        <textarea name="meta_description" rows="2" class="gt-input" style="width:100%;font-family:inherit;" placeholder="Short snippet for Google search results..."></textarea>
                    </div>
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('addProductModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Save Product</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Edit Product Modal -->
<div class="gt-modal" id="editProductModal" onclick="closeModalOnOutsideClick(event, 'editProductModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Edit Product</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('editProductModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="" method="POST" id="editProductForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="gt-modal-body">
                <div class="form-grid-3">
                    <div>
                        <label class="gt-label">Product Name *</label>
                        <input type="text" name="name" id="editProductName" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">SKU</label>
                        <input type="text" name="sku" id="editProductSku" class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Category *</label>
                        <select name="category_id" id="editProductCategoryId" required class="gt-input" style="width:100%;">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-grid-3">
                    <div>
                        <label class="gt-label">Regular Price *</label>
                        <input type="number" name="weekly_price" id="editProductPrice" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Sale Price</label>
                        <input type="number" name="sale_price" id="editProductSalePrice" class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Cost Price</label>
                        <input type="number" name="cost_price" id="editProductCostPrice" class="gt-input" style="width:100%;">
                    </div>
                </div>

                <div class="form-grid-3">
                    <div>
                        <label class="gt-label">Stock Quantity *</label>
                        <input type="number" name="stock" id="editProductStock" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Low Stock Threshold *</label>
                        <input type="number" name="low_stock_threshold" id="editProductThreshold" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Status *</label>
                        <select name="status" id="editProductStatus" class="gt-input" style="width:100%;">
                            <option value="active">Active</option>
                            <option value="draft">Draft</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Featured Status</label>
                        <div style="display:flex;align-items:center;gap:10px;margin-top:10px;">
                            <input type="checkbox" name="is_featured" value="1" id="editProductIsFeatured">
                            <label for="editProductIsFeatured" style="font-size:0.8rem;font-weight:700;color:var(--gt-text);">Display in Featured Section</label>
                        </div>
                    </div>
                    <div>
                        <label class="gt-label">Product Image</label>
                        <input type="file" name="image_path" class="gt-input" style="width:100%;padding:4px 12px;">
                        <div id="editImagePreview" style="display:flex;align-items:center;gap:10px;margin-top:6px;"></div>
                    </div>
                </div>

                <div class="form-group-full">
                    <label class="gt-label">Short Description</label>
                    <textarea name="description" id="editProductDescription" rows="2" class="gt-input" style="width:100%;font-family:inherit;"></textarea>
                </div>

                <div class="form-group-full">
                    <label class="gt-label">Full Description</label>
                    <textarea name="long_description" id="editProductLongDescription" rows="4" class="gt-input" style="width:100%;font-family:inherit;"></textarea>
                </div>

                <!-- SEO Section -->
                <div style="margin-top:20px;border-top:1.5px dashed var(--gt-border);padding-top:16px;">
                    <h3 style="font-size:0.85rem;font-weight:800;color:var(--gt-primary);margin-bottom:12px;text-transform:uppercase;">SEO Optimization</h3>
                    <div class="form-group-full">
                        <label class="gt-label">SEO Title</label>
                        <input type="text" name="meta_title" id="editProductMetaTitle" class="gt-input" style="width:100%;">
                    </div>
                    <div class="form-group-full" style="margin-bottom:0;">
                        <label class="gt-label">Meta Description</label>
                        <textarea name="meta_description" id="editProductMetaDescription" rows="2" class="gt-input" style="width:100%;font-family:inherit;"></textarea>
                    </div>
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('editProductModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Manage Stock Modal -->
<div class="gt-modal" id="manageStockModal" onclick="closeModalOnOutsideClick(event, 'manageStockModal')">
    <div class="gt-modal-dialog" style="max-width:400px;">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Manage Stock Inventory</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('manageStockModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="" method="POST" id="manageStockForm">
            @csrf
            <div class="gt-modal-body">
                <div class="form-group-full">
                    <label class="gt-label">Current Stock Count</label>
                    <input type="number" name="stock" id="manageStockCountInput" required class="gt-input" style="width:100%;">
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('manageStockModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. Import Products Modal -->
<div class="gt-modal" id="importProductsModal" onclick="closeModalOnOutsideClick(event, 'importProductsModal')">
    <div class="gt-modal-dialog" style="max-width:450px;">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Import Products from CSV</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('importProductsModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="gt-modal-body">
                <div class="form-group-full">
                    <label class="gt-label">Select CSV File</label>
                    <input type="file" name="csv_file" required class="gt-input" style="width:100%;padding:4px 12px;">
                </div>
                <div style="background:#fffaf3;border:1px solid rgba(215, 166, 74, 0.25);border-radius:10px;padding:12px;font-size:0.75rem;color:var(--gt-text-muted);">
                    <strong>Required Columns:</strong><br>
                    Product Name, Price, Category, Status (active/draft/inactive)<br><br>
                    <strong>Optional Columns:</strong><br>
                    SKU, Sale Price, Cost Price, Stock, Low Stock Threshold, Description, Long Description
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('importProductsModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Upload CSV</button>
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
        window.submitStatusVal = function(productId, status) {
            const form = document.getElementById(`statusForm-${productId}`);
            const input = document.getElementById(`statusVal-${productId}`);
            input.value = status;
            form.submit();
        };

        // Edit Modal dynamic values injector
        window.openEditModal = function(p) {
            const form = document.getElementById('editProductForm');
            form.action = `/admin/products/${p.id}`;

            document.getElementById('editProductName').value = p.name;
            document.getElementById('editProductSku').value = p.sku || '';
            document.getElementById('editProductCategoryId').value = p.category_id || '';
            document.getElementById('editProductPrice').value = p.weekly_price;
            document.getElementById('editProductSalePrice').value = p.sale_price || '';
            document.getElementById('editProductCostPrice').value = p.cost_price || '';
            document.getElementById('editProductStock').value = p.stock || 0;
            document.getElementById('editProductThreshold').value = p.low_stock_threshold || 5;
            document.getElementById('editProductStatus').value = p.status || 'active';
            
            // Featured status
            document.getElementById('editProductIsFeatured').checked = (parseInt(p.is_featured) === 1);

            document.getElementById('editProductDescription').value = p.description || '';
            document.getElementById('editProductLongDescription').value = p.long_description || '';
            document.getElementById('editProductMetaTitle').value = p.meta_title || '';
            document.getElementById('editProductMetaDescription').value = p.meta_description || '';

            // Image Preview
            const preview = document.getElementById('editImagePreview');
            if (p.image_path) {
                preview.innerHTML = `<img src="/${p.image_path}" class="preview-thumbnail">`;
            } else {
                preview.innerHTML = '<span style="font-size:0.72rem;color:var(--gt-text-muted);">No image uploaded</span>';
            }

            openModal('editProductModal');
        };

        // Manage Stock Modal dynamic values injector
        window.openStockModal = function(id, stock) {
            const form = document.getElementById('manageStockForm');
            form.action = `/admin/products/${id}/update-stock`;
            document.getElementById('manageStockCountInput').value = stock;
            openModal('manageStockModal');
        };

        // Delete warnings validation checks
        window.confirmProductDelete = function(id, name) {
            if (confirm(`Are you sure you want to permanently delete product "${name}"?`)) {
                document.getElementById(`deleteForm-${id}`).submit();
            }
        };
    });
</script>
@endsection
