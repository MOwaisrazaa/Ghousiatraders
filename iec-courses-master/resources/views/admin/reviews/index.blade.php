@extends('admin.ghousia-layout')

@section('title', 'Admin Dashboard - Reviews')

@section('content')
<style>
    /* Stats row for 5 cards */
    .reviews-stats-row {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
        width: 100%;
        box-sizing: border-box;
    }

    .reviews-stat-card {
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

    .reviews-stat-icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .reviews-stat-icon-wrapper i {
        width: 20px;
        height: 20px;
    }

    .reviews-stat-meta {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
        min-width: 0;
    }

    .reviews-stat-title {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--gt-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    .reviews-stat-count {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--gt-text);
        line-height: 1.1;
        margin-bottom: 6px;
    }

    .reviews-stat-growth {
        font-size: 0.68rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 2px;
        white-space: nowrap;
    }

    /* Colors and Accents for Stats */
    .stat-accent-total { background: #f3f4f6; color: #4b5563; }
    .stat-accent-approved { background: #ecfdf5; color: #047857; }
    .stat-accent-pending { background: #fffbeb; color: #b45309; }
    .stat-accent-rejected { background: #fef2f2; color: #b91c1c; }
    .stat-accent-rating { background: #faf5ff; color: #6b21a8; }

    /* Reviews container card */
    .reviews-container-card {
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

    /* Status Tabs */
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

    /* Thumbnail styling */
    .product-thumbnail-sm {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid var(--gt-border);
    }

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

    /* Stars display styling */
    .rating-stars-gold {
        color: #d7a64a;
        display: flex;
        align-items: center;
        gap: 2px;
    }

    .rating-stars-gold i {
        width: 13px;
        height: 13px;
        fill: currentColor;
    }

    .rating-stars-gold i.star-empty {
        fill: none;
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

    .status-approved { background-color: #ecfdf5; color: #047857; }
    .status-pending { background-color: #fffbeb; color: #b45309; }
    .status-rejected { background-color: #fef2f2; color: #b91c1c; }

    /* Action buttons */
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
        max-width: 600px;
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

    /* Responsive */
    @media (max-width: 1200px) {
        .reviews-stats-row {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .reviews-stats-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .reviews-stats-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<x-admin-page-header title="Reviews">
    <button class="gt-btn-primary" onclick="openModal('addReviewModal')">
        <i data-lucide="plus"></i> Add Review Manually
    </button>
</x-admin-page-header>

<!-- Review Statistics Row -->
<div class="reviews-stats-row">
    <!-- Stat Card 1 -->
    <div class="reviews-stat-card">
        <div class="reviews-stat-icon-wrapper stat-accent-total">
            <i data-lucide="message-square"></i>
        </div>
        <div class="reviews-stat-meta">
            <div>
                <div class="reviews-stat-title">Total Reviews</div>
                <div class="reviews-stat-count">{{ $totalReviewsCount }}</div>
            </div>
            <div class="reviews-stat-growth" style="color: #047857;">
                <i data-lucide="trending-up" style="width:12px;height:12px;"></i> + 18.7% <span style="color:var(--gt-text-muted);">vs last month</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="reviews-stat-card">
        <div class="reviews-stat-icon-wrapper stat-accent-approved">
            <i data-lucide="check-circle-2"></i>
        </div>
        <div class="reviews-stat-meta">
            <div>
                <div class="reviews-stat-title">Approved Reviews</div>
                <div class="reviews-stat-count">{{ $approvedReviewsCount }}</div>
            </div>
            <div class="reviews-stat-growth" style="color: #047857;">
                <i data-lucide="trending-up" style="width:12px;height:12px;"></i> + 16.3% <span style="color:var(--gt-text-muted);">vs last month</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="reviews-stat-card">
        <div class="reviews-stat-icon-wrapper stat-accent-pending">
            <i data-lucide="help-circle"></i>
        </div>
        <div class="reviews-stat-meta">
            <div>
                <div class="reviews-stat-title">Pending Reviews</div>
                <div class="reviews-stat-count">{{ $pendingReviewsCount }}</div>
            </div>
            <div class="reviews-stat-growth" style="color: #b91c1c;">
                <i data-lucide="trending-down" style="width:12px;height:12px;"></i> - 8.2% <span style="color:var(--gt-text-muted);">vs last month</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="reviews-stat-card">
        <div class="reviews-stat-icon-wrapper stat-accent-rejected">
            <i data-lucide="x-circle"></i>
        </div>
        <div class="reviews-stat-meta">
            <div>
                <div class="reviews-stat-title">Rejected Reviews</div>
                <div class="reviews-stat-count">{{ $rejectedReviewsCount }}</div>
            </div>
            <div class="reviews-stat-growth" style="color: #b91c1c;">
                <i data-lucide="trending-down" style="width:12px;height:12px;"></i> - 12.1% <span style="color:var(--gt-text-muted);">vs last month</span>
            </div>
        </div>
    </div>

    <!-- Stat Card 5 -->
    <div class="reviews-stat-card">
        <div class="reviews-stat-icon-wrapper stat-accent-rating">
            <i data-lucide="star"></i>
        </div>
        <div class="reviews-stat-meta">
            <div>
                <div class="reviews-stat-title">Average Rating</div>
                <div class="reviews-stat-count">{{ number_format($averageRating, 1) }} / 5</div>
            </div>
            <div class="rating-stars-gold">
                @php
                    $fullStars = floor($averageRating);
                    $hasHalf = ($averageRating - $fullStars) >= 0.5;
                @endphp
                @for($i=1; $i<=5; $i++)
                    @if($i <= $fullStars)
                        <i data-lucide="star" style="fill:currentColor;"></i>
                    @elseif($i == $fullStars + 1 && $hasHalf)
                        <i data-lucide="star-half" style="fill:currentColor;"></i>
                    @else
                        <i data-lucide="star" class="star-empty"></i>
                    @endif
                @endfor
            </div>
        </div>
    </div>
</div>

<!-- Reviews container card -->
<div class="reviews-container-card">
    <form action="{{ route('admin.reviews.index') }}" method="GET" id="filterForm">
        <!-- Status tabs row -->
        <div class="status-tabs-row">
            <div class="status-tabs">
                <a href="{{ route('admin.reviews.index', array_merge(request()->except('status_tab'), ['status_tab' => 'all'])) }}" class="status-tab-btn {{ $tab === 'all' ? 'active' : '' }}">
                    All Reviews <span>{{ $totalReviewsCount }}</span>
                </a>
                <a href="{{ route('admin.reviews.index', array_merge(request()->except('status_tab'), ['status_tab' => 'approved'])) }}" class="status-tab-btn {{ $tab === 'approved' ? 'active' : '' }}">
                    Approved <span>{{ $approvedReviewsCount }}</span>
                </a>
                <a href="{{ route('admin.reviews.index', array_merge(request()->except('status_tab'), ['status_tab' => 'pending'])) }}" class="status-tab-btn {{ $tab === 'pending' ? 'active' : '' }}">
                    Pending <span>{{ $pendingReviewsCount }}</span>
                </a>
                <a href="{{ route('admin.reviews.index', array_merge(request()->except('status_tab'), ['status_tab' => 'rejected'])) }}" class="status-tab-btn {{ $tab === 'rejected' ? 'active' : '' }}">
                    Rejected <span>{{ $rejectedReviewsCount }}</span>
                </a>
            </div>
            
            <input type="hidden" name="status_tab" value="{{ $tab }}">
            <input type="hidden" name="per_page" id="formPerPageInput" value="{{ request('per_page', 10) }}">
        </div>

        <!-- Filters Row -->
        <div class="filters-bar">
            <select name="status" class="gt-input" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <select name="rating" class="gt-input" onchange="this.form.submit()">
                <option value="">All Ratings</option>
                <option value="5" {{ request('rating') == 5 ? 'selected' : '' }}>5 Stars</option>
                <option value="4" {{ request('rating') == 4 ? 'selected' : '' }}>4 Stars</option>
                <option value="3" {{ request('rating') == 3 ? 'selected' : '' }}>3 Stars</option>
                <option value="2" {{ request('rating') == 2 ? 'selected' : '' }}>2 Stars</option>
                <option value="1" {{ request('rating') == 1 ? 'selected' : '' }}>1 Star</option>
            </select>

            <div class="search-input-wrapper">
                <input type="text" name="search" value="{{ request('search') }}" class="gt-input" placeholder="Search by product or customer..." onkeypress="if(event.key === 'Enter') this.form.submit();">
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
                    <option value="highest_rating" {{ request('sort') === 'highest_rating' ? 'selected' : '' }}>Highest Rating</option>
                    <option value="lowest_rating" {{ request('sort') === 'lowest_rating' ? 'selected' : '' }}>Lowest Rating</option>
                    <option value="recently_updated" {{ request('sort') === 'recently_updated' ? 'selected' : '' }}>Recently Updated</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Table Wrap -->
    <div class="gt-table-wrap">
        <div style="overflow-x:auto;">
            <table class="gt-table">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            <input type="checkbox" id="selectAllCheckbox">
                        </th>
                        <th>Review</th>
                        <th>Customer</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $r)
                        @php
                            $reviewerName = $r->reviewer_name ?: ($r->user ? $r->user->name : 'Guest User');
                            $reviewerEmail = $r->user ? $r->user->email : 'guest@example.com';
                            
                            $initials = '';
                            $words = explode(' ', $reviewerName);
                            foreach ($words as $w) {
                                $initials .= strtoupper(substr($w, 0, 1));
                            }
                            $initials = substr($initials, 0, 2);
                            
                            // Morph relation resolves to Product (Course)
                            $product = $r->rateable ?: $r->product;
                            $productName = $product ? $product->name : 'Unnamed Product';
                            $productCategory = ($product && $product->category) ? $product->category->name : 'Ride On Toys';

                            // Real product primary image resolution with fallback
                            if ($product && !empty($product->image_path)) {
                                $productImg = str_starts_with($product->image_path, 'http')
                                    ? $product->image_path
                                    : asset(ltrim($product->image_path, '/'));
                            } else {
                                $productImg = asset('ghousiatraders/assets/toy_jeep.png');
                            }
                            
                            $reviewData = [
                                'id' => $r->id,
                                'reviewer_name' => $reviewerName,
                                'reviewer_email' => $reviewerEmail,
                                'product_name' => $productName,
                                'product_category' => $productCategory,
                                'product_image' => $productImg,
                                'rating' => $r->rating,
                                'comment' => $r->comment,
                                'status' => $r->status,
                                'moderation_note' => $r->moderation_note ?? 'No moderation notes.',
                                'date' => $r->created_at->format('M d, Y h:i A')
                            ];
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" class="item-row-checkbox">
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <img src="{{ $productImg }}" class="product-thumbnail-sm" alt="{{ $productName }}">
                                    <div style="display:flex;flex-direction:column;line-height:1.2;">
                                        <strong style="font-size:0.82rem;font-weight:700;color:var(--gt-primary);">{{ $productName }}</strong>
                                        <span style="font-size:0.7rem;color:var(--gt-text-muted);">{{ $productCategory }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="customer-profile-circle">
                                        {{ $initials }}
                                    </div>
                                    <div style="display:flex;flex-direction:column;line-height:1.2;">
                                        <strong style="font-size:0.8rem;font-weight:700;color:var(--gt-text);">{{ $reviewerName }}</strong>
                                        <span style="font-size:0.7rem;color:var(--gt-text-muted);">{{ $reviewerEmail }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="rating-stars-gold">
                                    @for($i=1; $i<=5; $i++)
                                        @if($i <= $r->rating)
                                            <i data-lucide="star" style="fill:currentColor;"></i>
                                        @else
                                            <i data-lucide="star" class="star-empty"></i>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td>
                                <div style="max-width:280px;line-height:1.4;">
                                    <span>{{ Str::limit($r->comment, 65) }}</span>
                                    @if(strlen($r->comment) > 65)
                                        <button class="status-tab-btn" style="padding:0;font-size:0.75rem;color:var(--gt-primary);border:none;" onclick='openViewModal({!! json_encode($reviewData) !!})'>Read more</button>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge-status {{ $r->status === 'approved' ? 'status-approved' : ($r->status === 'pending' ? 'status-pending' : 'status-rejected') }}">
                                    {{ $r->status }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;flex-direction:column;line-height:1.2;white-space:nowrap;">
                                    <strong style="font-size:0.8rem;font-weight:700;">{{ $r->created_at->format('M d, Y') }}</strong>
                                    <span style="font-size:0.7rem;color:var(--gt-text-muted);">{{ $r->created_at->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td style="text-align:right;">
                                <div class="row-actions" style="justify-content:flex-end;">
                                    <button class="btn-action-icon" onclick='openViewModal({!! json_encode($reviewData) !!})' title="View Review Details">
                                        <i data-lucide="eye" style="width:14px;height:14px;"></i>
                                    </button>
                                    <button class="btn-action-icon" onclick='openEditModal({!! json_encode($reviewData) !!})' title="Edit Review">
                                        <i data-lucide="edit-3" style="width:14px;height:14px;"></i>
                                    </button>
                                    
                                    <div class="action-dropdown-container">
                                        <button type="button" class="btn-action-icon" onclick="toggleRowMenu(event, 'dropmenu-{{ $r->id }}')">
                                            <i data-lucide="more-vertical" style="width:14px;height:14px;"></i>
                                        </button>
                                        <div class="action-dropdown-menu" id="dropmenu-{{ $r->id }}">
                                            <button class="action-dropdown-item" type="button" onclick='openViewModal({!! json_encode($reviewData) !!})'>
                                                <i data-lucide="eye" style="width:12px;height:12px;"></i> View Review
                                            </button>
                                            <button class="action-dropdown-item" type="button" onclick='openEditModal({!! json_encode($reviewData) !!})'>
                                                <i data-lucide="edit-2" style="width:12px;height:12px;"></i> Edit Review
                                            </button>
                                            
                                            <form action="{{ route('admin.reviews.approve', $r->id) }}" method="POST" style="display:none;" id="approveForm-{{ $r->id }}">@csrf</form>
                                            <form action="{{ route('admin.reviews.pending', $r->id) }}" method="POST" style="display:none;" id="pendingForm-{{ $r->id }}">@csrf</form>
                                            
                                            @if($r->status !== 'approved')
                                                <button class="action-dropdown-item" type="button" onclick="document.getElementById('approveForm-{{ $r->id }}').submit()">
                                                    <i data-lucide="check" style="width:12px;height:12px;"></i> Approve Review
                                                </button>
                                            @endif
                                            
                                            @if($r->status !== 'rejected')
                                                <button class="action-dropdown-item" type="button" onclick="openRejectDialog({{ $r->id }})">
                                                    <i data-lucide="slash" style="width:12px;height:12px;"></i> Reject Review
                                                </button>
                                            @endif

                                            @if($r->status !== 'pending')
                                                <button class="action-dropdown-item" type="button" onclick="document.getElementById('pendingForm-{{ $r->id }}').submit()">
                                                    <i data-lucide="clock" style="width:12px;height:12px;"></i> Mark as Pending
                                                </button>
                                            @endif

                                            <form action="{{ route('admin.reviews.destroy', $r->id) }}" method="POST" style="display:none;" id="deleteForm-{{ $r->id }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button class="action-dropdown-item text-danger" type="button" onclick="confirmReviewDelete({{ $r->id }})">
                                                <i data-lucide="trash-2" style="width:12px;height:12px;color:var(--gt-danger);"></i> Delete Review
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center;padding:30px;color:var(--gt-text-muted);font-weight:600;">No reviews found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination-row">
        <div class="pagination-info">
            Showing {{ $reviews->firstItem() ?? 0 }} to {{ $reviews->lastItem() ?? 0 }} of {{ $reviews->total() }} reviews
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

            @if ($reviews->hasPages())
                <a href="{{ $reviews->previousPageUrl() }}" class="pagination-btn {{ $reviews->onFirstPage() ? 'disabled' : '' }}">
                    <i data-lucide="chevron-left" style="width:16px;height:16px;"></i>
                </a>
                
                @foreach ($reviews->getUrlRange(max(1, $reviews->currentPage() - 2), min($reviews->lastPage(), $reviews->currentPage() + 2)) as $page => $url)
                    <a href="{{ $url }}" class="pagination-btn {{ $page == $reviews->currentPage() ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach

                <a href="{{ $reviews->nextPageUrl() }}" class="pagination-btn {{ !$reviews->hasMorePages() ? 'disabled' : '' }}">
                    <i data-lucide="chevron-right" style="width:16px;height:16px;"></i>
                </a>
            @endif
        </div>
    </div>
</div>

<!-- ================= MODALS SECTION ================= -->

<!-- 1. View Review Details Modal -->
<div class="gt-modal" id="viewReviewModal" onclick="closeModalOnOutsideClick(event, 'viewReviewModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Review Details</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('viewReviewModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <div class="gt-modal-body" style="padding:20px;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;border-bottom:1.5px solid var(--gt-border);padding-bottom:16px;">
                <img id="viewReviewProductImg" src="{{ asset('ghousiatraders/assets/toy_jeep.png') }}" style="width:50px;height:50px;border-radius:8px;object-fit:cover;" alt="product thumbnail">
                <div style="display:flex;flex-direction:column;line-height:1.2;">
                    <strong id="viewReviewProductName" style="font-size:0.95rem;color:var(--gt-primary);font-weight:800;"></strong>
                    <span id="viewReviewProductCategory" style="font-size:0.75rem;color:var(--gt-text-muted);"></span>
                </div>
                <div style="margin-left:auto;">
                    <span id="viewReviewStatus" class="badge-status"></span>
                </div>
            </div>

            <div class="form-grid-2" style="margin-bottom:16px;">
                <div>
                    <span class="gt-label">Reviewer Name</span>
                    <strong id="viewReviewName" style="font-size:0.85rem;color:var(--gt-text);"></strong>
                </div>
                <div>
                    <span class="gt-label">Email Address</span>
                    <span id="viewReviewEmail" style="font-size:0.85rem;color:var(--gt-text-muted);"></span>
                </div>
            </div>

            <div class="form-grid-2" style="margin-bottom:16px;">
                <div>
                    <span class="gt-label">Rating Value</span>
                    <div class="rating-stars-gold" id="viewReviewStars"></div>
                </div>
                <div>
                    <span class="gt-label">Submission Date</span>
                    <span id="viewReviewDate" style="font-size:0.85rem;color:var(--gt-text);"></span>
                </div>
            </div>

            <div class="form-group-full" style="margin-bottom:16px;">
                <span class="gt-label">Customer Comment</span>
                <p id="viewReviewComment" style="font-size:0.82rem;background:#fafaf8;border:1px solid var(--gt-border);border-radius:8px;padding:12px;line-height:1.4;"></p>
            </div>

            <div class="form-group-full" style="margin-bottom:0;">
                <span class="gt-label">Moderation Note / Rejection Reason</span>
                <p id="viewReviewModeration" style="font-size:0.82rem;background:#fafaf8;border:1px solid var(--gt-border);border-radius:8px;padding:12px;line-height:1.4;color:var(--gt-text-muted);"></p>
            </div>
        </div>
        <div class="gt-modal-footer">
            <button type="button" class="gt-btn-primary" onclick="closeModal('viewReviewModal')">Close</button>
        </div>
    </div>
</div>

<!-- 2. Add Review Modal -->
<div class="gt-modal" id="addReviewModal" onclick="closeModalOnOutsideClick(event, 'addReviewModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Add Review Manually</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('addReviewModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="{{ route('admin.reviews.store') }}" method="POST">
            @csrf
            <div class="gt-modal-body">
                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Target Product *</label>
                        <select name="product_id" required class="gt-input" style="width:100%;">
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="gt-label">Rating Value *</label>
                        <select name="rating" required class="gt-input" style="width:100%;">
                            <option value="5">5 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="2">2 Stars</option>
                            <option value="1">1 Star</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Reviewer Name *</label>
                        <input type="text" name="reviewer_name" required class="gt-input" style="width:100%;" placeholder="e.g. Ali Raza">
                    </div>
                    <div>
                        <label class="gt-label">Email Address *</label>
                        <input type="email" name="email" required class="gt-input" style="width:100%;" placeholder="e.g. ali@example.com">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Status *</label>
                        <select name="status" class="gt-input" style="width:100%;">
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="gt-label">Customer Profile Link</label>
                        <select name="user_id" class="gt-input" style="width:100%;">
                            <option value="">Guest Reviewer</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group-full">
                    <label class="gt-label">Review Comment *</label>
                    <textarea name="comment" rows="3" required class="gt-input" style="width:100%;font-family:inherit;" placeholder="Review comment text..."></textarea>
                </div>

                <div class="form-group-full">
                    <label class="gt-label">Moderation Note</label>
                    <textarea name="moderation_note" rows="2" class="gt-input" style="width:100%;font-family:inherit;" placeholder="Reason for rejection or internal notes..."></textarea>
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('addReviewModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Save Review</button>
            </div>
        </form>
    </div>
</div>

<!-- 3. Edit Review Modal -->
<div class="gt-modal" id="editReviewModal" onclick="closeModalOnOutsideClick(event, 'editReviewModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Edit Review</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('editReviewModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="" method="POST" id="editReviewForm">
            @csrf
            @method('PUT')
            <div class="gt-modal-body">
                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Reviewer Name *</label>
                        <input type="text" name="reviewer_name" id="editFormName" required class="gt-input" style="width:100%;">
                    </div>
                    <div>
                        <label class="gt-label">Rating Value *</label>
                        <select name="rating" id="editFormRating" class="gt-input" style="width:100%;">
                            <option value="5">5 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="2">2 Stars</option>
                            <option value="1">1 Star</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div>
                        <label class="gt-label">Status *</label>
                        <select name="status" id="editFormStatus" class="gt-input" style="width:100%;">
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="gt-label">Submission Date (Read Only)</label>
                        <input type="text" id="editFormDate" readonly class="gt-input" style="width:100%;opacity:0.6;">
                    </div>
                </div>

                <div class="form-group-full">
                    <label class="gt-label">Review Comment *</label>
                    <textarea name="comment" id="editFormComment" rows="3" required class="gt-input" style="width:100%;font-family:inherit;"></textarea>
                </div>

                <div class="form-group-full">
                    <label class="gt-label">Moderation Note</label>
                    <textarea name="moderation_note" id="editFormModeration" rows="2" class="gt-input" style="width:100%;font-family:inherit;"></textarea>
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('editReviewModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary">Update Review</button>
            </div>
        </form>
    </div>
</div>

<!-- 4. Reject Note Confirmation Modal -->
<div class="gt-modal" id="rejectNoteModal" onclick="closeModalOnOutsideClick(event, 'rejectNoteModal')">
    <div class="gt-modal-dialog">
        <div class="gt-modal-header">
            <h2 class="gt-modal-title">Reject Review</h2>
            <button type="button" class="gt-modal-close" onclick="closeModal('rejectNoteModal')">
                <i data-lucide="x"></i>
            </button>
        </div>
        <form action="" method="POST" id="rejectConfirmForm">
            @csrf
            <div class="gt-modal-body">
                <p style="font-size:0.85rem;color:var(--gt-text);margin-bottom:16px;">Are you sure you want to reject this review? Optionally specify a reason/internal note below.</p>
                <div class="form-group-full">
                    <label class="gt-label">Rejection Reason</label>
                    <textarea name="moderation_note" rows="3" class="gt-input" style="width:100%;font-family:inherit;" placeholder="Specify reason..."></textarea>
                </div>
            </div>
            <div class="gt-modal-footer">
                <button type="button" class="gt-btn-outline" onclick="closeModal('rejectNoteModal')">Cancel</button>
                <button type="submit" class="gt-btn-primary" style="background:var(--gt-danger);">Confirm Rejection</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toggle row action menu
        window.toggleRowMenu = function(event, menuId) {
            event.stopPropagation();
            document.querySelectorAll('.action-dropdown-menu').forEach(menu => {
                if (menu.id !== menuId) {
                    menu.classList.remove('show');
                }
            });
            document.getElementById(menuId).classList.toggle('show');
        };

        // Dismiss menus clicking outside
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

        // Reject dialog
        window.openRejectDialog = function(ratingId) {
            const form = document.getElementById('rejectConfirmForm');
            form.action = `/admin/reviews/${ratingId}/reject`;
            openModal('rejectNoteModal');
        };

        // Delete confirmation
        window.confirmReviewDelete = function(id) {
            if (confirm("Are you sure you want to permanently delete this review?")) {
                document.getElementById(`deleteForm-${id}`).submit();
            }
        };

        // View Modal Loader
        window.openViewModal = function(r) {
            document.getElementById('viewReviewProductName').innerText = r.product_name;
            document.getElementById('viewReviewProductCategory').innerText = r.product_category;
            
            const modalImg = document.getElementById('viewReviewProductImg');
            if (modalImg && r.product_image) {
                modalImg.src = r.product_image;
            }
            
            // Status badge
            const statusBadge = document.getElementById('viewReviewStatus');
            statusBadge.innerText = r.status;
            statusBadge.className = 'badge-status ' + (r.status === 'approved' ? 'status-approved' : (r.status === 'pending' ? 'status-pending' : 'status-rejected'));

            document.getElementById('viewReviewName').innerText = r.reviewer_name;
            document.getElementById('viewReviewEmail').innerText = r.reviewer_email;
            document.getElementById('viewReviewDate').innerText = r.date;
            document.getElementById('viewReviewComment').innerText = r.comment;
            document.getElementById('viewReviewModeration').innerText = r.moderation_note;

            // Rating Stars
            const starsContainer = document.getElementById('viewReviewStars');
            starsContainer.innerHTML = '';
            for (let i = 1; i <= 5; i++) {
                const star = document.createElement('i');
                star.setAttribute('data-lucide', 'star');
                if (i <= r.rating) {
                    star.style.fill = 'currentColor';
                } else {
                    star.classList.add('star-empty');
                }
                starsContainer.appendChild(star);
            }

            openModal('viewReviewModal');
            if (window.lucide) {
                lucide.createIcons();
            }
        };

        // Edit Modal Loader
        window.openEditModal = function(r) {
            const form = document.getElementById('editReviewForm');
            form.action = `/admin/reviews/${r.id}`;

            document.getElementById('editFormName').value = r.reviewer_name;
            document.getElementById('editFormRating').value = r.rating;
            document.getElementById('editFormStatus').value = r.status;
            document.getElementById('editFormDate').value = r.date;
            document.getElementById('editFormComment').value = r.comment;
            document.getElementById('editFormModeration').value = r.moderation_note === 'No moderation notes.' ? '' : r.moderation_note;

            openModal('editReviewModal');
        };
    });
</script>
@endsection
