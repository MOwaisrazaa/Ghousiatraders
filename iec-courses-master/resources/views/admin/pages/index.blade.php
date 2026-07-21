@extends('admin.layout')

@section('title', 'Page Settings')
@section('header', 'Manage Storefront Navigation Pages')

@section('actions')
    <a href="{{ route('admin.pages.create') }}" class="pf-btn-gold">
        <i class="fas fa-plus"></i> Add New Page / Link
    </a>
@endsection

@section('content')
    <style>
        .page-type-badge {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-system {
            background: rgba(212, 166, 88, 0.15);
            color: #d4a658;
            border: 1px solid rgba(212, 166, 88, 0.3);
        }
        .badge-custom {
            background: rgba(59, 130, 246, 0.15);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        .reorder-handle {
            cursor: grab;
            color: rgba(248, 231, 208, 0.35);
            transition: color 0.2s ease;
            padding: 6px 12px;
        }
        .reorder-handle:hover {
            color: #d4a658;
        }
        .order-btn {
            background: transparent;
            border: 1px solid rgba(212, 166, 88, 0.25);
            color: rgba(248, 231, 208, 0.75);
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .order-btn:hover:not(:disabled) {
            border-color: #d4a658;
            color: #d4a658;
            background: rgba(212, 166, 88, 0.1);
        }
        .order-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }
    </style>

    <div class="pf-table-wrap">
        <div class="table-responsive">
            <table class="pf-table" id="pagesTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="80" style="text-align: center;">Order</th>
                        <th>Name</th>
                        <th>Link / URL</th>
                        <th>Type</th>
                        <th width="100" style="text-align: center;">Status</th>
                        <th width="200" style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="pagesTableBody">
                    @forelse($pages as $index => $page)
                        <tr data-id="{{ $page->id }}" data-order="{{ $page->order }}">
                            <td style="text-align: center;">
                                <div style="display: inline-flex; gap: 4px; align-items: center; justify-content: center;">
                                    <button class="order-btn move-up-btn" type="button" onclick="moveRow(this, 'up')" {{ $index === 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-chevron-up"></i>
                                    </button>
                                    <button class="order-btn move-down-btn" type="button" onclick="moveRow(this, 'down')" {{ $index === count($pages) - 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <strong style="color: #f8e7d0; font-size: 0.95rem;">{{ $page->name }}</strong>
                            </td>
                            <td>
                                <code style="background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 6px; color: rgba(248, 231, 208, 0.85); font-size: 0.85rem;">{{ $page->link }}</code>
                            </td>
                            <td>
                                @if($page->type === 'system')
                                    <span class="page-type-badge badge-system">
                                        <i class="fas fa-cog" style="font-size: 0.65rem;"></i> System Link
                                    </span>
                                @else
                                    <span class="page-type-badge badge-custom">
                                        <i class="fas fa-file-alt" style="font-size: 0.65rem;"></i> Custom Page
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($page->is_active)
                                    <span class="pf-badge-active">
                                        <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></span> Active
                                    </span>
                                @else
                                    <span class="pf-badge-inactive">
                                        <span style="display: inline-block; width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></span> Inactive
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 8px; align-items: center; justify-content: flex-end;">
                                    @if($page->type === 'custom')
                                        <a href="{{ route('polani.custom-page', $page->slug) }}" target="_blank" class="pf-btn-cancel" style="padding: 8px 12px; font-size: 0.75rem; border-radius: 8px; border-color: rgba(248, 231, 208, 0.15);">
                                            <i class="fas fa-external-link-alt" style="font-size: 0.7rem;"></i> View
                                        </a>
                                    @else
                                        <a href="{{ str_starts_with($page->link, 'http') || str_starts_with($page->link, '#') ? $page->link : url($page->link) }}" target="_blank" class="pf-btn-cancel" style="padding: 8px 12px; font-size: 0.75rem; border-radius: 8px; border-color: rgba(248, 231, 208, 0.15);">
                                            <i class="fas fa-external-link-alt" style="font-size: 0.7rem;"></i> View
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="pf-btn-edit" style="padding: 8px 12px; font-size: 0.75rem; border-radius: 8px;">
                                        <i class="fas fa-edit" style="font-size: 0.7rem;"></i> Edit
                                    </a>
                                    
                                    <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this page? This will remove it from the header navigation.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="pf-btn-delete" style="padding: 8px 12px; font-size: 0.75rem; border-radius: 8px;">
                                            <i class="fas fa-trash" style="font-size: 0.7rem;"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="pf-empty">No navigation pages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        function moveRow(button, direction) {
            const row = button.closest('tr');
            const tbody = row.parentNode;
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const currentIndex = rows.indexOf(row);
            
            if (direction === 'up' && currentIndex > 0) {
                tbody.insertBefore(row, rows[currentIndex - 1]);
            } else if (direction === 'down' && currentIndex < rows.length - 1) {
                tbody.insertBefore(row, rows[currentIndex + 2] || null);
            }
            
            updateOrderButtons();
            saveNewOrder();
        }
        
        function updateOrderButtons() {
            const tbody = document.getElementById('pagesTableBody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            rows.forEach((row, index) => {
                const upBtn = row.querySelector('.move-up-btn');
                const downBtn = row.querySelector('.move-down-btn');
                
                if (upBtn && downBtn) {
                    upBtn.disabled = (index === 0);
                    downBtn.disabled = (index === rows.length - 1);
                }
            });
        }
        
        function saveNewOrder() {
            const tbody = document.getElementById('pagesTableBody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const pagesData = rows.map((row, index) => {
                return {
                    id: row.dataset.id,
                    order: index + 1
                };
            });
            
            fetch("{{ route('admin.pages.reorder') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ pages: pagesData })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Failed to save order: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error reordering pages:', error);
                alert('An error occurred while saving the page order.');
            });
        }
    </script>
    @endpush
@endsection
