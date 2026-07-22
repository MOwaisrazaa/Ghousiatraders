@props(['items' => [], 'current'])

<nav class="pdp-breadcrumb" aria-label="Breadcrumb">
    <ol class="pdp-breadcrumb-list">
        <li><a href="{{ route('home') }}">Home</a></li>
        
        @foreach($items as $item)
            <li class="separator">&gt;</li>
            <li><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
        @endforeach
        
        @if(isset($current))
            <li class="separator">&gt;</li>
            <li class="current" aria-current="page">{{ $current }}</li>
        @endif
    </ol>
</nav>
