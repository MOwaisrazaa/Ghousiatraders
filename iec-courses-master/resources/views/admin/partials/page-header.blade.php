<div class="admin-page-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 24px; width: 100%; min-width: 0;">
    <div>
        <h1 class="admin-page-title" style="font-size: 1.8rem; font-weight: 800; color: #351b0d; margin: 0 0 4px 0; line-height: 1.2; letter-spacing: -0.02em;">{{ $title }}</h1>
        <nav aria-label="breadcrumb" class="admin-breadcrumb" style="font-size: 0.85rem; color: #786458; margin-top: 4px; font-weight: 600; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
            <a href="{{ route('admin.dashboard') }}" style="color: #786458; text-decoration: none; font-weight: 600; transition: color 0.15s ease;">Dashboard</a>
            
            @if(!empty($breadcrumbs))
                @foreach($breadcrumbs as $item)
                    <span class="admin-breadcrumb-separator" style="color: #b0a095; font-size: 0.85rem; user-select: none;">&rsaquo;</span>
                    @if(!empty($item['url']))
                        <a href="{{ $item['url'] }}" style="color: #786458; text-decoration: none; font-weight: 600;">{{ $item['label'] }}</a>
                    @else
                        <span style="color: #786458; font-weight: 600;">{{ $item['label'] }}</span>
                    @endif
                @endforeach
            @endif

            <span class="admin-breadcrumb-separator" style="color: #b0a095; font-size: 0.85rem; user-select: none;">&rsaquo;</span>
            <span class="admin-breadcrumb-current" style="color: #351b0d; font-weight: 700;">{{ $currentPage ?? $title }}</span>
        </nav>
    </div>

    @if(isset($actions) && !empty($actions))
        <div class="admin-header-actions" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            {!! $actions !!}
        </div>
    @endif
</div>
