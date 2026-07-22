@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <!-- No previous page -->
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn page-prev" style="text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                <i data-lucide="chevron-left" style="width: 14px; height: 14px;"></i> Prev
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="page-separator">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button class="page-btn active" type="button">{{ $page }}</button>
                    @else
                        <a href="{{ $url }}" class="page-btn" style="text-decoration: none;">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn page-next" style="text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                Next <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            </a>
        @endif
    </div>
@endif
