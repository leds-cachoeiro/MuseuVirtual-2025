<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/img/logo_sem_estado.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/menu_site.blade.css','resources/js/app.js','resources/js/menu.js', 'resources/js/baselayout.js'])
    @endif
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

</head>

<body>
        <x-menu_site />
    </div>
    {{ $slot }}
    <x-rodape_site></x-rodape_site>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    {{-- Inclui o JavaScript do Swiper do CDN (idealmente no final do <body> para melhor performance) --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</body>

</html>
