@extends('admin.ghousia-layout')

@section('title', 'Admin Dashboard - Categories')

@section('content')
<style>
    /* Stats row for 4 cards */
    .categories-stats-row {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 24px;
        width: 100%;
        box-sizing: border-box;
    }

    .categories-stat-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 16px;
        padding: 18px;
        box-shadow: var(--gt-shadow);
        display: flex;
        align-items: flex-start;
        gap: 14px;
        min-height: 110px;
        min-width: 0;
        box-sizing: border-box;
    }

    .categories-stat-icon-wrapper {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: #fff8ee;
        border: 1.5px solid rgba(215, 166, 74, 0.2);
    }

    .categories-stat-icon-wrapper i {
        width: 22px;
        height: 22px;
        color: #d7a64a;
    }

    .categories-stat-meta {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
        min-width: 0;
    }

    .categories-stat-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--gt-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    .categories-stat-count {
        font-size: 1.45rem;
        font-weight: 800;
        color: var(--gt-text);
        line-height: 1.1;
        margin-bottom: 6px;
    }

    .categories-stat-desc {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--gt-text-muted);
        white-space: nowrap;
    }

    /* Master Card */
    .categories-container-card {
        background: #ffffff;
        border: 1.5px solid var(--gt-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--gt-shadow);
        box-sizing: border-box;
        width: 100%;
        min-width: 0;
    }

    /* Filter Row */
    .filters-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .filters-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 280px;
        max-width: 650px;
    }

    .filters-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .gt-input {
        background: #fffdf9;
        border: 1.5px solid var(--gt-border);
        border-radius: 10px;
        padding: 8px 14px;
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

    .search-input-wrapper {
        position: relative;
        flex: 1;
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
        font-size: 0.85rem;
    }

    .gt-table th {
        background-color: #fffaf3;
        color: var(--gt-text);
        font-weight: 800;
        padding: 14px 16px;
        border-bottom: 1.5px solid var(--gt-border);
        white-space: nowrap;
    }

    .gt-table td {
        padding: 14px 16px;
        border-bottom: 1px solid rgba(215, 166, 74, 0.1);
        color: var(--gt-text);
        vertical-align: middle;
    }

    .gt-table tr:last-child td {
        border-bottom: none;
    }

    .draggable-row.dragging {
        opacity: 0.5;
        background-color: var(--gt-primary-light);
    }

    .drag-handle {
        cursor: grab;
        color: var(--gt-text-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px;
        border-radius: 4px;
        transition: background 0.2s;
    }

    .drag-handle:active {
        cursor: grabbing;
    }

    .drag-handle:hover {
        background-color: var(--gt-primary-light);
        color: var(--gt-primary);
    }

    /* Category thumbnail badge */
    .category-thumbnail-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .category-image {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid var(--gt-border);
        background-color: #fffcf8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: var(--gt-primary);
        font-size: 0.78rem;
    }

    .category-meta-info {
        display: flex;
        flex-direction: column;
        line-height: 1.3;
    }

    .category-meta-info strong {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--gt-primary);
    }

    .category-meta-info span {
        font-size: 0.72rem;
        color: var(--gt-text-muted);
    }

    /* Badges */
    .badge-type {
        background-color: #fff8ee;
        color: var(--gt-primary);
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid rgba(215, 166, 74, 0.25);
        display: inline-flex;
        flex-direction: column;
        gap: 2px;
    }

    .badge-type small {
        font-size: 0.65rem;
        color: var(--gt-text-muted);
        font-weight: 600;
    }

    .badge-status {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        text-align: center;
        text-transform: capitalize;
    }

    .status-active {
        background-color: #ecfdf5;
        color: #047857;
    }

    .status-inactive {
        background-color: #fef2f2;
        color: #b91c1c;
    }

    .status-hidden {
        background-color: #fffbeb;
        color: #b45309;
    }

    /* Display order input cell */
    .order-input-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .display-order-input {
        width: 50px;
        text-align: center;
        padding: 4px;
        font-size: 0.8rem;
        border-radius: 6px;
        border: 1.5px solid var(--gt-border);
        color: var(--gt-text);
        background: #fffdf9;
    }

    /* Actions dropdown trigger */
    .row-actions-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .row-btn-action {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--gt-text-muted);
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .row-btn-action:hover {
        background-color: var(--gt-primary-light);
        color: var(--gt-primary);
    }

    .action-dropdown-container {
        position: relative;
    }

    .action-dropdown-menu {
        position: absolute;
        right: 0;
        top: 36px;
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

    /* Modal dialog */
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
        max-width: 650px;
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
        color: var(--gt-primary);
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
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--gt-text);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .preview-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
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

    .gt-btn-primary:hover {
        opacity: 0.95;
    }

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
    @media (max-width: 991px) {
        .categories-stats-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 576px) {
        .categories-stats-row {
            grid-template-columns: 1fr;
        }

        .filters-left {
            flex-direction: column;
            align-items: stretch;
        }

        .form-grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Sticky Sub header bar -->
<div class="sub-nav-bar" style="margin-bottom: 24px;">
    <div class="sub-nav-left">
        <h1 class="page-title">Categories</h1>
        <div class="breadcrumbs-list">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <i data-lucide="chevron-right"></i>
            <span>Categories</span>
        </div>
    </div>
    <div class="sub-nav-right">
        <button class="gt-btn-primary" onclick="openModal('addCategoryModal')">
            <i data-lucide="plus"></i> Add New Category
        </button>
    </div>
</div>

<!-- Category Statistics Row -->
<div class="categories-stats-row">
    <!-- Stat Card 1 -->
    <div class="categories-stat-card">
        <div class="categories-stat-icon-wrapper">
            <i data-lucide="folder"></i>
        </div>
        <div class="categories-stat-meta">
            <div>
                <div class="categories-stat-title">Total Categories</div>
                <div class="categories-stat-count">{{ $totalCategoriesCount }}</div>
            </div>
            <div class="categories-stat-desc">All categories</div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="categories-stat-card">
        <div class="categories-stat-icon-wrapper">
            <i data-lucide="layers"></i>
        </div>
        <div class="categories-stat-meta">
            <div>
                <div class="categories-stat-title">Main Categories</div>
                <div class="categories-stat-count">{{ $mainCategoriesCount }}</div>
            </div>
            <div class="categories-stat-desc">Top level categories</div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="categories-stat-card">
        <div class="categories-stat-icon-wrapper">
            <i data-lucide="list"></i>
        </div>
        <div class="categories-stat-meta">
            <div>
                <div class="categories-stat-title">Sub Categories</div>
                <div class="categories-stat-count">{{ $subCategoriesCount }}</div>
            </div>
            <div class="categories-stat-desc">Child categories</div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="categories-stat-card">
        <div class="categories-stat-icon-wrapper">
            <i data-lucide="tag"></i>
        </div>
        <div class="categories-stat-meta">
            <div>
                <div class="categories-stat-title">Hidden Categories</div>
                <div class="categories-stat-count">{{ $hiddenCategoriesCount }}</div>
            </div>
            <div class="categories-stat-desc">Not visible on store</div>
        </div>
    </div>
</div>

<!-- Categories Card -->
<div class="categories-container-card">
    <!-- Filters Bar -->
    <form action="{{ route('admin.categories.index') }}" method="GET" id="filterForm">
        <div class="filters-bar">
            <div class="filters-left">
                <select name="type" class="gt-input" onchange="this.form.submit()">
                    <option value="" {{ request('type') == '' ? 'selected' : '' }}>All Types</option>
                    <option value="main" {{ request('type') == 'main' ? 'selected' : '' }}>Main Categories</option>
                    <option value="sub" {{ request('type') == 'sub' ? 'selected' : '' }}>Sub Categories</option>
                </select>

                <div class="search-input-wrapper">
                    <input type="text" name="search" value="{{ request('search') }}" class="gt-input" placeholder="Search category name..." onkeypress="if(event.key === 'Enter') this.form.submit();">
                    <i data-lucide="search"></i>
                </div>

                <button type="submit" class="gt-btn-filter">
                    <i data-lucide="filter"></i> Filters
                </button>
            </div>

            <div class="filters-right">
                <span style="font-size:0.82rem;font-weight:700;color:var(--gt-text-muted);">Sort By:</span>
                <select name="sort" class="gt-input" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                    <option value="most_products" {{ request('sort') == 'most_products' ? 'selected' : '' }}>Most Products</option>
                    <option value="least_products" {{ request('sort') == 'least_products' ? 'selected' : '' }}>Least Products</option>
                    <option value="display_order" {{ request('sort', 'display_order') == 'display_order' ? 'selected' : '' }}>Display Order</option>
                </select>
                
                <input type="hidden" name="per_page" id="formPerPageInput" value="{{ request('per_page', 10) }}">
            </div>
        </div>
    </form>

    <!-- Table content -->
    <div class="gt-table-wrap">
        <div style="overflow-x:auto;">
            <table class="gt-table">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            <input type="checkbox" id="selectAllCheckbox">
                        </th>
                        <th>Category Name</th>
                        <th>Type</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th>Created At</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody">
                    @forelse($categories as $category)
                        <tr class="draggable-row" data-id="{{ $category->id }}">
                            <td>
                                <input type="checkbox" class="item-row-checkbox">
                            </td>
                            <td>
                                <div class="category-thumbnail-wrapper">
                                    @if($category->image_path)
                                        <img src="{{ asset($category->image_path) }}" class="category-image">
                                    @else
                                        <div class="category-image">
                                            {{ strtoupper(substr($category->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="category-meta-info">
                                        <strong>{{ $category->name }}</strong>
                                        <span>{{ Str::limit($category->description ?? 'No description provided', 50) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($category->parent_id)
                                    <div class="badge-type">
                                        <span>Sub Category</span>
                                        <small>Parent: {{ $category->parent->name ?? 'Deleted' }}</small>
                                    </div>
                                @else
                                    <div class="badge-type">
                                        <span>Main Category</span>
                                    </div>
                                @endif
                            </td>
                            <td style="font-weight:700;">
                                {{ $category->courses_count }}
                            </td>
                            <td>
                                <span class="badge-status {{ $category->status == 'active' ? 'status-active' : ($category->status == 'inactive' ? 'status-inactive' : 'status-hidden') }}">
                                    {{ $category->status }}
                                </span>
                            </td>
                            <td>
                                <div class="order-input-wrapper">
                                    <div class="drag-handle" draggable="true">
                                        <i data-lucide="grip-vertical" style="width:16px;height:16px;"></i>
                                    </div>
                                    <input type="number" class="display-order-input" value="{{ $category->display_order }}" data-id="{{ $category->id }}">
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;flex-direction:column;line-height:1.2;">
                                    <strong style="font-size:0.82rem;font-weight:700;">{{ $category->created_at->format('M d, Y') }}</strong>
                                    <span style="font-size:0.72rem;color:var(--gt-text-muted);">{{ $category->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td style="text-align:right;">
                                <div class="row-actions-wrapper" style="justify-content:flex-end;">
                                    <button class="row-btn-action" onclick='openEditModal({!! json_encode($category) !!})' title="Edit Category">
                                        <i data-lucide="edit-3" style="width:16px;height:16px;"></i>
                                    </button>
                                    
                                    <div class="action-dropdown-container">
                                        <button class="row-btn-action" onclick="toggleRowMenu(event, 'dropmenu-{{ $category->id }}')">
                                            <i data-lucide="more-vertical" style="width:16px;height:16px;"></i>
                                        </button>
                                        <div class="action-dropdown-menu" id="dropmenu-{{ $category->id }}">
                                            <button class="action-dropdown-item" onclick='openEditModal({!! json_encode($category) !!})'>
                                                <i data-lucide="edit-2" style="width:14px;height:14px;"></i> Edit Category
                                            </button>
                                            <a href="{{ route('admin.courses') }}?category_id={{ $category->id }}" class="action-dropdown-item">
                                                <i data-lucide="eye" style="width:14px;height:14px;"></i> View Products
                                            </a>
                                            <button class="action-dropdown-item" onclick="openAddSubcategoryModal({{ $category->id }})">
                                                <i data-lucide="plus-circle" style="width:14px;height:14px;"></i> Add Subcategory
                                            </button>
                                            
                                            <form action="{{ route('admin.categories.toggle-status', $category->id) }}" method="POST" id="statusForm-{{ $category->id }}">
                                                @csrf
                                                <input type="hidden" name="status" id="statusInput-{{ $category->id }}" value="">
                                            </form>
                                            
                                            @if($category->status == 'hidden')
                                                <button class="action-dropdown-item" onclick="submitStatusToggle({{ $category->id }}, 'active')">
                                                    <i data-lucide="eye" style="width:14px;height:14px;"></i> Show on Store
                                                </button>
                                            @else
                                                <button class="action-dropdown-item" onclick="submitStatusToggle({{ $category->id }}, 'hidden')">
                                                    <i data-lucide="eye-off" style="width:14px;height:14px;"></i> Hide on Store
                                                </button>
                                            @endif

                                            @if($category->status == 'active')
                                                <button class="action-dropdown-item" onclick="submitStatusToggle({{ $category->id }}, 'inactive')">
                                                    <i data-lucide="slash" style="width:14px;height:14px;"></i> Deactivate
                                                </button>
                                            @else
                                                <button class="action-dropdown-item" onclick="submitStatusToggle({{ $category->id }}, 'active')">
                                                    <i data-lucide="check" style="width:14px;height:14px;"></i> Activate
                                                </button>
                                            @endif

                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" id="deleteForm-{{ $category->id }}" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            
                                            <button class="action-dropdown-item text-danger" onclick="confirmDelete({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->courses_count }}, {{ $category->children()->count() }})">
                                                <i data-lucide="trash-2" style="width:14px;height:14px;color:var(--gt-danger);"></i> Delete Category
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:30px;color:var(--gt-text-muted);font-weight:600;">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination row -->
    <div class="pagination-row">
        <div class="pagination-info">
            Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} categories
        </div>
        
        <div class="pagination-controls">
            <!-- Per page selector -->
            <div style="display:flex;align-items:center;gap:8px;margin-right:16px;">
                <select class="gt-input" style="padding:4px 8px;min-height:30px;font-size:0.8rem;" onchange="setPerPage(this.value)">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span style="font-size:0.8rem;color:var(--gt-text-muted);font-weight:600;">per page</span>
            </div>

            <!-- Page Buttons -->
            @if ($categories->hasPages())
                <a href="{{ $categories->previousPageUrl() }}" class="pagination-btn {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                    <i data-lucide="chevron-left" style="width:16px;height:16px;"></i>
                </a>
                
                @foreach ($categories->getUrlRange(max(1, $categories->currentPage() - 2), min($categories->lastPage(), $categories->currentPage() + 2)) as $page => $url)
                    <a href="{{ $url }}" class="pagination-btn {{ $page == $categories->currentPage() ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach

                <a href="{{ $categories->nextPageUrl() }}" class="pagination-btn {{ !$categories->hasMorePages() ? 'disabled' : '' }}">
                    <i data-lucide="chevron-right" style="width:16px;height:16px;"></i>
                </a>
            @endif
        </div>
    </div>
</div>

<!-- ================= MODALS SECTION ================= -->

<!-- 1. Add Category Modal -->
<div class="gt-modal" id="addCategoryModal" onclick="closeModalOnOutsideClick(event, 'addCategoryModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Add New Category</h2>
            <button class="gt-modal-close" onclick="closeModal('addCategoryModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="gt-modal-body">
                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Category Name *</label>
                        <input type="text" name="name" id="addCategoryNameInput" required class="gt-input" style="width:100%;" placeholder="e.g. Baby Care" oninput="suggestSlug(this.value, 'addCategorySlugInput')">
                    </div>
                    <div>
                        <label class="gt-label">Slug</label>
                        <input type="text" name="slug" id="addCategorySlugInput" class="gt-input" style="width:100%;" placeholder="e.g. baby-care">
                    </div>
                </div>

                <div class="form-group-full">
                    <label class="gt-label">Description</label>
                    <textarea name="description" rows="3" class="gt-input" style="width:100%;font-family:inherit;" placeholder="Short description of this category..."></textarea>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Parent Category</label>
                        <select name="parent_id" id="addCategoryParentId" class="gt-input" style="width:100%;">
                            <option value="">None (Main Category)</option>
                            @foreach($allCategories as $cat)
                                @if(!$cat->parent_id)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="gt-label">Category Image</label>
                        <input type="file" name="image_path" class="gt-input" style="width:100%;padding:5px 12px;">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Display Order</label>
                        <input type="number" name="display_order" value="0" class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Status</label>
                        <select name="status" class="gt-input" style="width:100%;">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="hidden">Hidden</option>
                        </select>
                    </div>
                </div>

                <!-- SEO Section -->
                <div style="margin-top:20px;border-top:1.5px dashed var(--gt-border);padding-top:16px;">
                    <h3 style="font-size:0.85rem;font-weight:800;color:var(--gt-primary);margin-bottom:12px;text-transform:uppercase;">SEO Meta Tags</h3>
                    <div class="form-group-full">
                        <label class="gt-label">SEO Title</label>
                        <input type="text" name="seo_title" class="gt-input" style="width:100%;" placeholder="Meta title for search engines...">
                    </div>
                    <div class="form-group-full" style="margin-bottom:0;">
                        <label class="gt-label">Meta Description</label>
                        <textarea name="meta_description" rows="2" class="gt-input" style="width:100%;font-family:inherit;" placeholder="Meta description for search results..."></textarea>
                    </div>
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('addCategoryModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Save Category</button>
            </div>
        </form>
    </div>
</div>

<!-- 2. Edit Category Modal -->
<div class="gt-modal" id="editCategoryModal" onclick="closeModalOnOutsideClick(event, 'editCategoryModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Edit Category</h2>
            <button class="gt-modal-close" onclick="closeModal('editCategoryModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="" method="POST" id="editCategoryForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="gt-modal-body">
                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Category Name *</label>
                        <input type="text" name="name" id="editCategoryName" required class="gt-input" style="width:100%;" placeholder="e.g. Baby Care" oninput="suggestSlug(this.value, 'editCategorySlug')">
                    </div>
                    <div>
                        <label class="gt-label">Slug</label>
                        <input type="text" name="slug" id="editCategorySlug" class="gt-input" style="width:100%;" placeholder="e.g. baby-care">
                    </div>
                </div>

                <div class="form-group-full">
                    <label class="gt-label">Description</label>
                    <textarea name="description" id="editCategoryDescription" rows="3" class="gt-input" style="width:100%;font-family:inherit;" placeholder="Short description of this category..."></textarea>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Parent Category</label>
                        <select name="parent_id" id="editCategoryParentId" class="gt-input" style="width:100%;">
                            <option value="">None (Main Category)</option>
                            @foreach($allCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="gt-label">Category Image</label>
                        <input type="file" name="image_path" class="gt-input" style="width:100%;padding:5px 12px;">
                        <div id="editImagePreviewContainer" style="display:flex;align-items:center;gap:10px;margin-top:6px;"></div>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Display Order</label>
                        <input type="number" name="display_order" id="editCategoryDisplayOrder" class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Status</label>
                        <select name="status" id="editCategoryStatus" class="gt-input" style="width:100%;">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="hidden">Hidden</option>
                        </select>
                    </div>
                </div>

                <!-- SEO Section -->
                <div style="margin-top:20px;border-top:1.5px dashed var(--gt-border);padding-top:16px;">
                    <h3 style="font-size:0.85rem;font-weight:800;color:var(--gt-primary);margin-bottom:12px;text-transform:uppercase;">SEO Meta Tags</h3>
                    <div class="form-group-full">
                        <label class="gt-label">SEO Title</label>
                        <input type="text" name="seo_title" id="editCategorySeoTitle" class="gt-input" style="width:100%;" placeholder="Meta title for search engines...">
                    </div>
                    <div class="form-group-full" style="margin-bottom:0;">
                        <label class="gt-label">Meta Description</label>
                        <textarea name="meta_description" id="editCategoryMetaDescription" rows="2" class="gt-input" style="width:100%;font-family:inherit;" placeholder="Meta description for search results..."></textarea>
                    </div>
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('editCategoryModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Update Category</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toggles Dropdowns on action menus
        window.toggleRowMenu = function(event, menuId) {
            event.stopPropagation();
            document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
                if (menu.id !== menuId) {
                    menu.classList.remove('show');
                }
            });
            document.getElementById(menuId).classList.toggle('show');
        };

        // Dismiss dropdowns on clicking outside
        document.addEventListener('click', () => {
            document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        });

        // Select All checkboxes
        const selectAll = document.getElementById('selectAllCheckbox');
        if (selectAll) {
            selectAll.addEventListener('change', () => {
                document.querySelectorAll('.item-row-checkbox').forEach(cb => {
                    cb.checked = selectAll.checked;
                });
            });
        }

        // Custom AJAX pagination size setting
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

        // Slug helper
        window.suggestSlug = function(value, outputId) {
            const slug = value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            document.getElementById(outputId).value = slug;
        };

        // Edit category loader
        window.openEditModal = function(category) {
            const form = document.getElementById('editCategoryForm');
            form.action = `/admin/categories/${category.id}`;
            
            document.getElementById('editCategoryName').value = category.name;
            document.getElementById('editCategorySlug').value = category.slug;
            document.getElementById('editCategoryDescription').value = category.description || '';
            document.getElementById('editCategoryDisplayOrder').value = category.display_order || 0;
            document.getElementById('editCategoryStatus').value = category.status || 'active';
            document.getElementById('editCategorySeoTitle').value = category.seo_title || '';
            document.getElementById('editCategoryMetaDescription').value = category.meta_description || '';
            
            const parentSelect = document.getElementById('editCategoryParentId');
            parentSelect.value = category.parent_id || '';
            
            // Loop prevention checking
            Array.from(parentSelect.options).forEach(opt => {
                opt.disabled = false;
                if (opt.value == category.id) {
                    opt.disabled = true;
                }
            });

            // Preview Thumbnail
            const previewContainer = document.getElementById('editImagePreviewContainer');
            if (category.image_path) {
                previewContainer.innerHTML = `<img src="/${category.image_path}" class="preview-thumbnail">`;
            } else {
                previewContainer.innerHTML = '<span style="font-size:0.75rem;color:var(--gt-text-muted);">No image uploaded</span>';
            }

            openModal('editCategoryModal');
        };

        // Quick add subcategory shortcut helper
        window.openAddSubcategoryModal = function(parentId) {
            document.getElementById('addCategoryParentId').value = parentId;
            openModal('addCategoryModal');
        };

        // Fast status changes triggers
        window.submitStatusToggle = function(categoryId, status) {
            const form = document.getElementById(`statusForm-${categoryId}`);
            const input = document.getElementById(`statusInput-${categoryId}`);
            input.value = status;
            form.submit();
        };

        // Constraints deletion warnings
        window.confirmDelete = function(id, name, productsCount, subcategoriesCount) {
            if (productsCount > 0) {
                showToast(`Cannot delete category "${name}" because it has ${productsCount} active products assigned to it. Please reassign the products first.`, 'error');
                return;
            }
            if (subcategoriesCount > 0) {
                showToast(`Cannot delete category "${name}" because it has ${subcategoriesCount} subcategories. Please delete or reassign subcategories first.`, 'error');
                return;
            }

            if (confirm(`Are you sure you want to delete category "${name}"?`)) {
                document.getElementById(`deleteForm-${id}`).submit();
            }
        };

        // DRAG & DROP persistence sorting logic
        let dragRow = null;
        
        document.querySelectorAll('.draggable-row').forEach(row => {
            const handle = row.querySelector('.drag-handle');
            
            handle.addEventListener('dragstart', (e) => {
                dragRow = row;
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', row.innerHTML);
                row.classList.add('dragging');
            });

            handle.addEventListener('dragend', () => {
                row.classList.remove('dragging');
                saveNewOrder();
            });

            row.addEventListener('dragover', (e) => {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                const bounding = row.getBoundingClientRect();
                const offset = e.clientY - bounding.top - (bounding.height / 2);
                const parent = row.parentNode;
                if (offset > 0) {
                    parent.insertBefore(dragRow, row.nextSibling);
                } else {
                    parent.insertBefore(dragRow, row);
                }
            });
        });

        // Numeric change reordering
        document.querySelectorAll('.display-order-input').forEach(input => {
            input.addEventListener('change', () => {
                const rows = Array.from(document.querySelectorAll('.draggable-row'));
                rows.sort((a, b) => {
                    const valA = parseInt(a.querySelector('.display-order-input').value) || 0;
                    const valB = parseInt(b.querySelector('.display-order-input').value) || 0;
                    return valA - valB;
                });
                
                const parent = document.getElementById('categoriesTableBody');
                rows.forEach(row => parent.appendChild(row));
                
                saveNewOrder();
            });
        });

        function saveNewOrder() {
            const rows = document.querySelectorAll('.draggable-row');
            const orders = [];
            rows.forEach((row, index) => {
                const id = row.getAttribute('data-id');
                const orderInput = row.querySelector('.display-order-input');
                const newOrder = index + 1;
                if (orderInput) {
                    orderInput.value = newOrder;
                }
                orders.push({ id: id, display_order: newOrder });
            });

            // AJAX post call
            fetch('{{ route("admin.categories.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ orders: orders })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Categories order updated successfully.', 'success');
                } else {
                    showToast('Failed to update category order.', 'error');
                }
            })
            .catch(err => {
                showToast('Failed to update category order.', 'error');
            });
        }
    });
</script>
@endsection
