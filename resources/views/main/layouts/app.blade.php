<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Meta dasar -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Title dinamis -->
    <title>@yield('title', config('app.name', 'Mora'))</title>

    <!-- Meta SEO -->
    <meta name="description" content="@yield('meta_description', 'Mora adalah aplikasi pengelola keuangan dan inventori untuk UMKM modern.')">
    <meta name="keywords" content="@yield('meta_keywords', 'mora, aplikasi keuangan, UMKM, inventori, pos, akuntansi')">
    <meta name="author" content="Mora Team">

    <!-- Open Graph (Facebook, WhatsApp, LinkedIn) -->
    <meta property="og:title" content="@yield('og_title', config('app.name', 'Mora'))">
    <meta property="og:description" content="@yield('og_description', 'Kelola keuangan dan inventori UMKM Anda dengan mudah bersama Mora!')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.png'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', config('app.name', 'Mora'))">
    <meta name="twitter:description" content="@yield('twitter_description', 'Aplikasi pengelola keuangan dan inventori untuk UMKM')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/og-image.png'))">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-u8gN0zN1A3Qb1M2U0cPDbSYvP3o1k6UeCrxBiLkxZkGQjOifk4Wb8w4QdZ4Qih5+AkJ1aQzNhtI1D1Hdt1RQA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- Styles tambahan -->
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Navbar -->
    @include('main.layouts.navbar')

    <!-- Konten utama -->
    <main class="mb-20">
        @yield('content')
    </main>

    <!-- Footer opsional -->
    @includeWhen(View::exists('main.layouts.footer'), 'main.layouts.footer')

    <!-- Script tambahan -->
    @stack('scripts')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success', // success | error | info | warning
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: '#fff',
                color: '#333',
                customClass: {
                    popup: 'rounded-xl shadow-lg'
                }
            });
        @endif

        @if (session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error', // success | error | info | warning
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: '#fff',
                color: '#333',
                customClass: {
                    popup: 'rounded-xl shadow-lg'
                }
            });
        @endif
    </script>
</body>

</html>
