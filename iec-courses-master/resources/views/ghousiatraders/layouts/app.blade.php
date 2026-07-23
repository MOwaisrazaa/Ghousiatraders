<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ghousia Traders | Little Essentials, Big Joy')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Lora:ital,wght@0,400..700;1,400..700&family=Pinyon+Script&family=Playball&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Storefront Stylesheet -->
    <link rel="stylesheet" href="{{ asset('ghousiatraders/style.css') }}">
    @livewireStyles
    @stack('head')
</head>
<body class="ghousia-storefront">

    @include('ghousiatraders.partials.header')

    <main id="main-content">
        @include('ghousiatraders.partials.alerts')
        @yield('content')
    </main>

    @include('ghousiatraders.partials.footer')

    <!-- Theme JS -->
    <script src="{{ asset('ghousiatraders/script.js') }}?v={{ filemtime(public_path('ghousiatraders/script.js')) }}"></script>
    <script>
        // Initialize Lucide Icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
