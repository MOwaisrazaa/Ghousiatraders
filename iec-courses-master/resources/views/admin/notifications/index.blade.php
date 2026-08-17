@extends('admin.ghousia-layout')

@section('title', 'Notifications - Ghousia Traders Admin')

@section('content')
<div class="notifications-page-container">
    
    <!-- Header Title & Breadcrumb -->
    <div class="page-header-row" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
        <div>
            <h1 class="page-title" style="font-size: 1.6rem; font-weight: 800; color: #351B0D; margin: 0 0 4px 0;">Notifications</h1>
            <nav class="breadcrumb-nav" style="font-size: 0.84rem; color: #8C7B70;">
                <a href="{{ route('admin.dashboard') }}" style="color: #8C7B70; text-decoration: none;">Dashboard</a>
                <span style="margin: 0 6px;">&rsaquo;</span>
                <span style="color: #351B0D; font-weight: 600;">Notifications</span>
            </nav>
        </div>

        <button type="button" class="btn-mark-all-page" id="btnPageMarkAllRead" style="background: #351B0D; color: #DFAC4D; border: 1px solid rgba(223, 172, 77, 0.4); padding: 8px 16px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;">
            <i data-lucide="check-check" style="width: 16px; height: 16px;"></i>
            <span>Mark All as Read</span>
        </button>
    </div>

    <!-- Filter Toolbar -->
    <div class="notifications-toolbar-card" style="background: #FFFFFF; border-radius: 10px; padding: 16px 20px; border: 1px solid rgba(223, 172, 77, 0.25); box-shadow: 0 4px 12px rgba(53, 27, 13, 0.05); margin-bottom: 24px;">
        <form method="GET" action="{{ route('admin.notifications.index') }}" id="notifFilterForm">
            <input type="hidden" name="tab" id="activeTabInput" value="{{ $tab }}">

            <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                
                <!-- Category Tabs -->
                <div class="notif-tab-pills" style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                    <a href="?tab=all&search={{ urlencode($search) }}&sort={{ $sort }}" class="notif-tab-pill {{ $tab === 'all' ? 'active' : '' }}">
                        All <span class="tab-count">{{ $counts['all'] }}</span>
                    </a>
                    <a href="?tab=unread&search={{ urlencode($search) }}&sort={{ $sort }}" class="notif-tab-pill {{ $tab === 'unread' ? 'active' : '' }}">
                        Unread <span class="tab-count {{ $counts['unread'] > 0 ? 'unread-count-badge' : '' }}">{{ $counts['unread'] }}</span>
                    </a>
                    <a href="?tab=orders&search={{ urlencode($search) }}&sort={{ $sort }}" class="notif-tab-pill {{ $tab === 'orders' ? 'active' : '' }}">
                        Orders <span class="tab-count">{{ $counts['orders'] }}</span>
                    </a>
                    <a href="?tab=reviews&search={{ urlencode($search) }}&sort={{ $sort }}" class="notif-tab-pill {{ $tab === 'reviews' ? 'active' : '' }}">
                        Reviews <span class="tab-count">{{ $counts['reviews'] }}</span>
                    </a>
                    <a href="?tab=customers&search={{ urlencode($search) }}&sort={{ $sort }}" class="notif-tab-pill {{ $tab === 'customers' ? 'active' : '' }}">
                        Customers <span class="tab-count">{{ $counts['customers'] }}</span>
                    </a>
                    @if($counts['support'] > 0)
                        <a href="?tab=support&search={{ urlencode($search) }}&sort={{ $sort }}" class="notif-tab-pill {{ $tab === 'support' ? 'active' : '' }}">
                            Support <span class="tab-count">{{ $counts['support'] }}</span>
                        </a>
                    @endif
                    <a href="?tab=stock&search={{ urlencode($search) }}&sort={{ $sort }}" class="notif-tab-pill {{ $tab === 'stock' ? 'active' : '' }}">
                        Stock Alerts <span class="tab-count">{{ $counts['stock'] }}</span>
                    </a>
                </div>

                <!-- Search & Sort Controls -->
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <div style="position: relative; min-width: 220px;">
                        <input type="text" name="search" class="notif-search-input" placeholder="Search notifications..." value="{{ $search }}" style="width: 100%; height: 38px; padding: 0 12px 0 34px; border-radius: 6px; border: 1px solid #E2D9CD; background: #FAF6F0; font-size: 0.85rem; color: #351B0D;">
                        <i data-lucide="search" style="position: absolute; left: 10px; top: 10px; width: 16px; height: 16px; color: #8C7B70;"></i>
                    </div>

                    <select name="sort" class="notif-sort-select" onchange="this.form.submit()" style="height: 38px; padding: 0 12px; border-radius: 6px; border: 1px solid #E2D9CD; background: #FAF6F0; font-size: 0.85rem; color: #351B0D; font-weight: 600; cursor: pointer;">
                        <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Notifications Card List -->
    <div class="notifications-list-container" style="display: flex; flex-direction: column; gap: 12px;">
        @forelse($notifications as $item)
            @php
                $isUnread = empty($item['is_read']);
                $icon = match($item['type']) {
                    'order' => 'shopping-bag',
                    'customer' => 'user-plus',
                    'review' => 'star',
                    'ticket' => 'life-buoy',
                    'low_stock' => 'alert-triangle',
                    'out_of_stock' => 'alert-circle',
                    default => 'bell'
                };
            @endphp
            <div class="page-notif-card {{ $isUnread ? 'unread-card' : '' }}" data-id="{{ $item['id'] }}" style="background: {{ $isUnread ? '#FFFDF8' : '#FFFFFF' }}; border: 1px solid {{ $isUnread ? 'rgba(223, 172, 77, 0.45)' : '#EFE6D8' }}; border-radius: 8px; padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; gap: 16px; transition: all 0.15s ease; position: relative;">
                
                <div style="display: flex; align-items: flex-start; gap: 14px; flex: 1; min-width: 0;">
                    <!-- Type Icon -->
                    <div class="page-notif-icon-box type-{{ $item['type'] }}" style="width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;">
                        <i data-lucide="{{ $icon }}" style="width: 20px; height: 20px;"></i>
                    </div>

                    <!-- Details -->
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px; flex-wrap: wrap;">
                            <h4 style="font-size: 0.95rem; font-weight: 700; color: #351B0D; margin: 0;">{{ $item['title'] }}</h4>
                            @if($isUnread)
                                <span class="badge-unread-tag" style="background: #DFAC4D; color: #351B0D; font-size: 0.68rem; font-weight: 800; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">UNREAD</span>
                            @endif
                        </div>
                        <p style="font-size: 0.88rem; color: #5C4A3E; margin: 0 0 6px 0; line-height: 1.4;">{{ $item['message'] }}</p>
                        <div style="font-size: 0.76rem; color: #8C7B70; display: flex; align-items: center; gap: 12px;">
                            <span><i data-lucide="clock" style="width: 12px; height: 12px; display: inline-block; vertical-align: middle;"></i> {{ $item['time_ago'] }}</span>
                            <span>&bull;</span>
                            <span>{{ \Carbon\Carbon::parse($item['created_at'])->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Link Button -->
                <a href="{{ $item['url'] }}" class="btn-view-record" onclick="markPageNotifRead('{{ $item['id'] }}')" style="background: #FAF6F0; border: 1px solid #E2D9CD; color: #351B0D; padding: 8px 14px; border-radius: 6px; font-weight: 700; font-size: 0.82rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; white-space: nowrap; transition: all 0.15s ease;">
                    <span>View Record</span>
                    <i data-lucide="arrow-right" style="width: 14px; height: 14px;"></i>
                </a>

            </div>
        @empty
            <div class="notifications-empty-card" style="background: #FFFFFF; border-radius: 10px; padding: 50px 20px; text-align: center; border: 1px solid #EFE6D8;">
                <div style="width: 54px; height: 54px; border-radius: 50%; background: #FAF6F0; color: #CBD5E1; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                    <i data-lucide="bell-off" style="width: 28px; height: 28px;"></i>
                </div>
                <h3 style="font-size: 1.15rem; font-weight: 700; color: #351B0D; margin: 0 0 6px 0;">No notifications found.</h3>
                <p style="font-size: 0.88rem; color: #8C7B70; margin: 0;">There are no activity alerts matching your selected filter.</p>
            </div>
        @endforelse
    </div>

</div>

<style>
    .notif-tab-pill {
        padding: 6px 14px;
        border-radius: 20px;
        background: #FAF6F0;
        border: 1px solid #E2D9CD;
        color: #5C4A3E;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }
    .notif-tab-pill:hover {
        background: #EFE6D8;
        color: #351B0D;
    }
    .notif-tab-pill.active {
        background: #351B0D;
        color: #FFFFFF;
        border-color: #351B0D;
    }
    .notif-tab-pill.active .tab-count {
        background: #DFAC4D;
        color: #351B0D;
    }
    .tab-count {
        background: #E2D9CD;
        color: #351B0D;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 10px;
    }
    .unread-count-badge {
        background: #EF4444 !important;
        color: #FFFFFF !important;
    }
    .btn-mark-all-page:hover {
        background: #4A2814 !important;
        color: #FFFFFF !important;
    }
    .btn-view-record:hover {
        background: #351B0D !important;
        color: #DFAC4D !important;
        border-color: #351B0D !important;
    }
    .page-notif-icon-box.type-order { background-color: #FEF3C7; color: #D97706; }
    .page-notif-icon-box.type-customer { background-color: #DBEAFE; color: #2563EB; }
    .page-notif-icon-box.type-review { background-color: #FEF9C3; color: #CA8A04; }
    .page-notif-icon-box.type-ticket { background-color: #F3E8FF; color: #9333EA; }
    .page-notif-icon-box.type-low_stock { background-color: #FFEDD5; color: #EA580C; }
    .page-notif-icon-box.type-out_of_stock { background-color: #FEE2E2; color: #DC2626; }
</style>

<script>
    function markPageNotifRead(id) {
        if (!id) return;
        fetch("{{ route('admin.notifications.mark-read') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ id: id })
        }).catch(e => console.error(e));
    }

    document.addEventListener('DOMContentLoaded', function() {
        const btnPageMarkAll = document.getElementById('btnPageMarkAllRead');
        if (btnPageMarkAll) {
            btnPageMarkAll.addEventListener('click', function() {
                fetch("{{ route('admin.notifications.mark-all-read') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Remove unread indicators on page
                        document.querySelectorAll('.unread-card').forEach(card => {
                            card.style.background = '#FFFFFF';
                            card.style.borderColor = '#EFE6D8';
                            card.classList.remove('unread-card');
                        });
                        document.querySelectorAll('.badge-unread-tag').forEach(tag => tag.remove());
                        
                        // Update bell badge in header
                        const bellBadge = document.getElementById('adminBellBadge');
                        if (bellBadge) {
                            bellBadge.style.display = 'none';
                            bellBadge.textContent = '';
                        }
                    }
                })
                .catch(err => console.error('Mark all read error:', err));
            });
        }
    });
</script>
@endsection
