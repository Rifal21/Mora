@extends('main.layouts.app')

@section('content')
    @auth
        <div class="container mx-auto px-4 py-10 mt-20">

            <div class="relative flex flex-col items-center justify-center overflow-hidden mb-5">
                <!-- Background bokeh particles -->
                <div id="bokeh-container" class="absolute inset-0"></div>

                <!-- Mora Finance -->
                <h1 id="moraTitle"
                    class="text-5xl sm:text-6xl font-extrabold text-center bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent relative mb-2">
                    Mora Finance
                    <span class="absolute inset-0 shimmer"></span>
                </h1>

                <!-- Greeting -->
                <div id="greeting"
                    class="flex flex-col sm:flex-row items-center justify-center gap-3 text-center bg-white/5 backdrop-blur-lg px-6 py-1">
                    <p id="welcomeText" class="text-lg sm:text-2xl font-semibold tracking-wide text-slate-600 drop-shadow-md">
                        Selamat Datang,
                        <span id="userName"
                            class="font-serif font-bold text-slate-700 bg-clip-text bg-gradient-to-r from-indigo-400 to-pink-400">
                            {{ Auth::user()->name }}
                        </span>
                    </p>
                    <img id="waveHand" src="{{ asset('assets/images/hi.gif') }}" class="w-36 sm:w-36 opacity-0" alt="👋">
                </div>
            </div>

            @push('styles')
                <style>
                    /* shimmer effect */
                    .shimmer::after {
                        content: '';
                        position: absolute;
                        top: 0;
                        left: -100%;
                        width: 60%;
                        height: 100%;
                        background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.5), transparent);
                        animation: shimmerMove 3s infinite;
                    }

                    @keyframes shimmerMove {
                        0% {
                            left: -100%;
                        }

                        50% {
                            left: 100%;
                        }

                        100% {
                            left: 100%;
                        }
                    }

                    /* bokeh soft dots */
                    #bokeh-container {
                        position: absolute;
                        overflow: hidden;
                        z-index: 0;
                    }

                    .bokeh {
                        position: absolute;
                        border-radius: 50%;
                        background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
                        animation: floatUp linear infinite;
                    }

                    @keyframes floatUp {
                        from {
                            transform: translateY(100vh) scale(0.5);
                            opacity: 0;
                        }

                        to {
                            transform: translateY(-10vh) scale(1);
                            opacity: 1;
                        }
                    }
                </style>
            @endpush

            @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.2/lib/anime.min.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        // --- BOkeh effect ---
                        const container = document.getElementById('bokeh-container');
                        for (let i = 0; i < 20; i++) {
                            const b = document.createElement('div');
                            b.classList.add('bokeh');
                            b.style.width = `${Math.random() * 20 + 10}px`;
                            b.style.height = b.style.width;
                            b.style.left = `${Math.random() * 100}%`;
                            b.style.animationDuration = `${Math.random() * 8 + 6}s`;
                            b.style.animationDelay = `${Math.random() * 5}s`;
                            container.appendChild(b);
                        }

                        // --- Mora Finance Animation ---
                        anime({
                            targets: '#moraTitle',
                            opacity: [0, 1],
                            scale: [0.9, 1],
                            translateY: [20, 0],
                            easing: 'easeOutElastic(1, .9)',
                            duration: 1800,
                            delay: 300
                        });

                        // --- Greeting Text ---
                        anime({
                            targets: '#greeting',
                            opacity: [0, 1],
                            translateY: [30, 0],
                            easing: 'easeOutExpo',
                            duration: 1200,
                            delay: 1200
                        });

                        // --- User Name Typing Effect ---
                        const userName = document.querySelector('#userName');
                        const text = userName.textContent;
                        userName.textContent = '';
                        [...text].forEach((ch, i) => {
                            const span = document.createElement('span');
                            span.textContent = ch;
                            span.style.opacity = 0;
                            span.style.display = 'inline-block';
                            userName.appendChild(span);
                        });
                        anime({
                            targets: '#userName span',
                            opacity: [0, 1],
                            translateY: [10, 0],
                            delay: anime.stagger(70, {
                                start: 1600
                            }),
                            easing: 'easeOutBack'
                        });

                        // --- Wave hand after typing done ---
                        anime({
                            targets: '#waveHand',
                            opacity: [0, 1],
                            duration: 600,
                            delay: 2200,
                            easing: 'easeOutExpo',
                            complete: () => {
                                anime({
                                    targets: '#waveHand',
                                    rotate: [{
                                        value: -15,
                                        duration: 300
                                    }, {
                                        value: 15,
                                        duration: 300
                                    }],
                                    loop: true,
                                    direction: 'alternate',
                                    easing: 'easeInOutSine'
                                });
                            }
                        });
                    });
                </script>
            @endpush



            <!-- Summary Cards -->
            <div class="mb-8">
                <!-- Swiper Wrapper (hanya aktif di mobile) -->
                <div class="block md:hidden">
                    <div class="swiper mySummarySwiper">
                        <div class="swiper-wrapper">

                            <!-- Saldo -->
                            <div class="swiper-slide">
                                <div
                                    class="bg-white rounded-2xl p-6 border-b-8 border-r-8 border-l border-t-2  border-blue-600">
                                    <div class="flex items-center justify-between">
                                        <h2 class="text-lg font-semibold text-gray-700">Saldo Saat Ini</h2>
                                        <i class="fa-solid fa-wallet text-blue-600 text-xl"></i>
                                    </div>
                                    <p class="text-3xl font-bold text-gray-900 mt-3">
                                        Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Pemasukan -->
                            <div class="swiper-slide">
                                <div
                                    class="bg-white rounded-2xl p-6 border-b-8 border-r-8 border-l border-t-2  border-green-500">
                                    <div class="flex items-center justify-between">
                                        <h2 class="text-lg font-semibold text-gray-700">Total Pemasukan</h2>
                                        <i class="fa-solid fa-circle-arrow-up text-green-500 text-xl"></i>
                                    </div>
                                    <p class="text-3xl font-bold text-gray-900 mt-3">
                                        Rp {{ number_format($income ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Pengeluaran -->
                            <div class="swiper-slide">
                                <div class="bg-white rounded-2xl p-6 border-b-8 border-r-8 border-l border-t-2  border-red-500">
                                    <div class="flex items-center justify-between">
                                        <h2 class="text-lg font-semibold text-gray-700">Total Pengeluaran</h2>
                                        <i class="fa-solid fa-circle-arrow-down text-red-500 text-xl"></i>
                                    </div>
                                    <p class="text-3xl font-bold text-gray-900 mt-3">
                                        Rp {{ number_format($expense ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="swiper-pagination mt-4"></div>
                    </div>
                </div>

                <!-- Grid layout (desktop) -->
                <div class="hidden md:grid grid-cols-3 gap-6">
                    <!-- Saldo -->
                    <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-blue-600">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-700">Saldo Saat Ini</h2>
                            <i class="fa-solid fa-wallet text-blue-600 text-xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mt-3">
                            Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Pemasukan -->
                    <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-700">Total Pemasukan</h2>
                            <i class="fa-solid fa-circle-arrow-up text-green-500 text-xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mt-3">
                            Rp {{ number_format($income ?? 0, 0, ',', '.') }}
                        </p>
                    </div>

                    <!-- Pengeluaran -->
                    <div class="bg-white rounded-2xl shadow-md p-6 border-t-4 border-red-500">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-700">Total Pengeluaran</h2>
                            <i class="fa-solid fa-circle-arrow-down text-red-500 text-xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mt-3">
                            Rp {{ number_format($expense ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>


            <!-- Content: Grafik + Transaksi Terakhir -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Grafik -->
                <div class="bg-white rounded-2xl shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-indigo-600"></i> Ringkasan Transaksi
                    </h2>
                    <canvas id="transactionChart" height="180"></canvas>
                </div>

                <!-- Transaksi Terakhir -->
                <div class="bg-white rounded-2xl shadow-md p-6">
                    <div class="flex justify-between items-start">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-receipt text-indigo-600"></i> Transaksi Terakhir
                        </h2>
                        @if (auth()->check() && auth()->user()->profile->user_type === 'free')
                            <p>
                                sisa transaksi: <span class="text-red-500">{{ auth()->user()->profile->quota_trx }}x</span>
                            </p>
                        @endif
                    </div>

                    @if ($transactions->isEmpty())
                        <p class="text-gray-500 italic">Belum ada transaksi.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($transactions as $trx)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $trx->notes ?? $trx->invoice_number }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $trx->created_at ? \Carbon\Carbon::parse($trx->created_at)->format('d M Y') : '' }}
                                        </p>
                                    </div>
                                    <p class="font-semibold {{ $trx->type === 'income' ? 'text-green-600' : 'text-red-500' }}">
                                        {{ $trx->type === 'income' ? '+' : '-' }}
                                        Rp{{ number_format($trx->total_amount, 0, ',', '.') }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    @endauth

    @guest
        <section class="relative h-screen flex items-center justify-center bg-cover bg-center"
            style="background-image: url('{{ asset('assets/images/bg.jpeg') }}');">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/80"></div>

            <!-- Content -->
            <div class="relative z-10 text-center text-white px-6 max-w-full">
                <!-- Logo / Title -->
                <h1
                    class="text-6xl md:text-7xl font-extrabold mb-6 bg-clip-text text-transparent 
                   bg-gradient-to-r from-indigo-400 via-sky-400 to-blue-600 drop-shadow-[0_5px_15px_rgba(99,102,241,0.4)] 
                   animate-gradient-x">
                    Mora Finance
                </h1>

                <!-- Subtitle -->
                <h3 class="text-3xl md:text-5xl font-bold mb-6 leading-tight">
                    Kelola Keuangan Bisnismu dengan Mudah, Cepat, dan Akurat
                </h3>

                <!-- Description -->
                <p class="text-lg text-gray-300 mb-10">
                    Catat pemasukan, pantau pengeluaran, dan nikmati laporan keuangan real-time dalam satu platform yang modern
                    dan aman.
                </p>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}"
                        class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700
                       text-white font-semibold px-8 py-3 rounded-full transition-all shadow-lg hover:shadow-indigo-500/30">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}"
                        class="bg-white/90 text-indigo-700 hover:bg-white font-semibold px-8 py-3 rounded-full 
                       transition-all shadow-md hover:shadow-lg">
                        Masuk
                    </a>
                </div>
            </div>

            <!-- Custom Gradient Animation -->
            <style>
                @keyframes gradient-x {

                    0%,
                    100% {
                        background-position: 0% 50%;
                    }

                    50% {
                        background-position: 100% 50%;
                    }
                }

                .animate-gradient-x {
                    background-size: 200% 200%;
                    animation: gradient-x 5s ease infinite;
                }
            </style>
        </section>
    @endguest

    <!-- Paket Langganan -->
    <section class="container mx-auto px-4 py-10 mt-10">
        <div class="text-center mb-5">
            <h2
                class="font-extrabold text-4xl mb-4 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-blue-600">
                Pilih Paket Langganan
            </h2>
            <p class="text-gray-500 text-lg">
                Pilih paket sesuai kebutuhanmu dan nikmati semua fitur premium untuk bisnismu.
            </p>
        </div>

        <!-- Wrapper untuk Swiper -->
        <div class="swiper paket-swiper md:hidden">
            <div class="swiper-wrapper">
                @foreach ($billing as $package)
                    <div class="swiper-slide p-3">
                        <div
                            class="relative bg-white/70 backdrop-blur-xl rounded-3xl shadow-lg p-8 border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                            @if ($loop->first)
                                <span
                                    class="absolute top-5 right-5 bg-indigo-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                                    POPULER
                                </span>
                            @endif
                            <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition">
                                {{ $package->name }}
                            </h3>
                            <p class="text-gray-500 mb-6 h-16">{{ Str::limit($package->description, 80) }}</p>

                            @php
                                $durationLabel =
                                    $package->duration_days < 30
                                        ? '/hari'
                                        : ($package->duration_days < 365
                                            ? '/bulan'
                                            : '/tahun');
                                $originalPrice = $package->price * 1.5;
                            @endphp

                            <div class="mb-6">
                                <p class="text-gray-400 text-lg line-through mb-1">
                                    Rp{{ number_format($originalPrice, 0, ',', '.') }}
                                </p>
                                <p class="text-4xl font-extrabold text-indigo-600">
                                    Rp{{ number_format($package->price, 0, ',', '.') }}
                                    <span class="text-gray-500 text-lg font-medium">{{ $durationLabel }}</span>
                                </p>
                            </div>

                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="package_id" value="{{ $package->id }}">
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 
                           text-white py-3 rounded-full font-semibold shadow-md hover:shadow-indigo-400/40 transition-all">
                                    Tambahkan ke Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Versi Grid untuk Desktop -->
        <div class="hidden md:grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach ($billing as $package)
                <div
                    class="relative bg-white/70 backdrop-blur-xl rounded-3xl shadow-lg p-8 border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group">
                    @if ($loop->first)
                        <span
                            class="absolute top-5 right-5 bg-indigo-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                            POPULER
                        </span>
                    @endif
                    <h3 class="text-2xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition">
                        {{ $package->name }}
                    </h3>
                    <p class="text-gray-500 mb-6 h-16">{{ Str::limit($package->description, 80) }}</p>

                    @php
                        $durationLabel =
                            $package->duration_days < 30
                                ? '/hari'
                                : ($package->duration_days < 365
                                    ? '/bulan'
                                    : '/tahun');
                        $originalPrice = $package->price * 1.5;
                    @endphp

                    <div class="mb-6">
                        <p class="text-gray-400 text-lg line-through mb-1">
                            Rp{{ number_format($originalPrice, 0, ',', '.') }}
                        </p>
                        <p class="text-4xl font-extrabold text-indigo-600">
                            Rp{{ number_format($package->price, 0, ',', '.') }}
                            <span class="text-gray-500 text-lg font-medium">{{ $durationLabel }}</span>
                        </p>
                    </div>

                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package->id }}">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 
                           text-white py-3 rounded-full font-semibold shadow-md hover:shadow-indigo-400/40 transition-all">
                            Tambahkan ke Keranjang
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Carousel Logo -->
    <section class="container mx-auto px-4 py-10">
        <div class="text-center mb-10">
            <h2
                class="font-extrabold text-3xl mb-4 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-blue-600">
                Dipercaya Oleh Banyak Bisnis
            </h2>
            <p class="text-gray-500 text-lg">
                Dari UMKM hingga bisnis berkembang, mereka semua menggunakan
                <span class="font-semibold text-indigo-600">Mora Finance</span>.
            </p>
        </div>

        <div class="swiper logo-swiper">
            <div class="swiper-wrapper items-center">
                <!-- Logo Dummy -->
                <div class="swiper-slide flex justify-center">
                    <img src="{{ asset('assets/images/fallshop-bg.png') }}"
                        class="h-24 opacity-70 hover:opacity-100 transition" alt="Tokopedia">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Shopee.svg/960px-Shopee.svg.png?20220725160704"
                        class="h-24 opacity-70 hover:opacity-100 transition" alt="Shopee">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Gojek_logo_2022.svg/640px-Gojek_logo_2022.svg.png"
                        class="h-24 opacity-70 hover:opacity-100 transition" alt="Gojek">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/Tokopedia.svg/640px-Tokopedia.svg.png"
                        class="h-24 opacity-70 hover:opacity-100 transition" alt="Tokopedia">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Shopee.svg/960px-Shopee.svg.png?20220725160704"
                        class="h-24 opacity-70 hover:opacity-100 transition" alt="Shopee">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Gojek_logo_2022.svg/640px-Gojek_logo_2022.svg.png"
                        class="h-24 opacity-70 hover:opacity-100 transition" alt="Gojek">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/Tokopedia.svg/640px-Tokopedia.svg.png"
                        class="h-24 opacity-70 hover:opacity-100 transition" alt="Tokopedia">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/Shopee.svg/960px-Shopee.svg.png?20220725160704"
                        class="h-24 opacity-70 hover:opacity-100 transition" alt="Shopee">
                </div>
                <div class="swiper-slide flex justify-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Gojek_logo_2022.svg/640px-Gojek_logo_2022.svg.png"
                        class="h-24 opacity-70 hover:opacity-100 transition" alt="Gojek">
                </div>
            </div>
        </div>
    </section>

    <!-- Berita Terbaru -->
    <section class="container mx-auto px-4 py-10">
        <div class="text-center mb-5">
            <h2
                class="font-extrabold text-4xl mb-4 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-blue-600">
                Berita Terbaru
            </h2>
            <p class="text-gray-500 text-lg">
                Dapatkan insight, tips, dan berita terbaru seputar bisnis & keuangan.
            </p>
        </div>

        <!-- Swiper untuk mobile -->
        <div class="swiper news-swiper md:hidden">
            <div class="swiper-wrapper">
                @foreach ($news as $item)
                    <div class="swiper-slide p-3">
                        <div
                            class="group bg-white rounded-3xl shadow-md overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                            @if ($item->image)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                        class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition">
                                    </div>
                                </div>
                            @endif
                            <div class="p-6">
                                <p class="text-sm text-gray-400 mb-2">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                                </p>
                                <h3
                                    class="font-bold text-xl text-gray-800 mb-3 group-hover:text-indigo-600 transition line-clamp-2">
                                    {{ $item->title }}
                                </h3>
                                <p class="text-gray-600 text-sm mb-5 line-clamp-3">
                                    {{ Str::limit(strip_tags($item->content), 100) }}
                                </p>
                                <a href="{{ route('blogPosts.read', $item->slug) }}"
                                    class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800 transition">
                                    Baca Selengkapnya
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Grid versi desktop -->
        <div class="hidden md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach ($news as $item)
                <div
                    class="group bg-white rounded-3xl shadow-md overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    @if ($item->image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                                class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition">
                            </div>
                        </div>
                    @endif
                    <div class="p-6">
                        <p class="text-sm text-gray-400 mb-2">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                        </p>
                        <h3
                            class="font-bold text-xl text-gray-800 mb-3 group-hover:text-indigo-600 transition line-clamp-2">
                            {{ $item->title }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-5 line-clamp-3">
                            {{ Str::limit(strip_tags($item->content), 100) }}
                        </p>
                        <a href="{{ route('blogPosts.read', $item->slug) }}"
                            class="inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800 transition">
                            Baca Selengkapnya
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <!-- Testimonial Section -->
    <section class="container mx-auto px-4 py-10">
        <div class="text-center mb-5">
            <h2
                class="font-extrabold text-4xl mb-4 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-blue-600">
                Apa Kata Pengguna
            </h2>
            <p class="text-gray-500 text-lg">
                Cerita nyata dari mereka yang telah menggunakan
                <span class="font-semibold text-indigo-600">Mora Finance</span>
                untuk mengelola bisnisnya dengan lebih mudah.
            </p>
        </div>

        <!-- Swiper untuk semua layar -->
        <div class="swiper testimonial-swiper">
            <div class="swiper-wrapper">
                <!-- Testimoni 1 -->
                <div class="swiper-slide p-4">
                    <div
                        class="bg-white/70 backdrop-blur-lg rounded-3xl shadow-md p-8 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg"
                                class="w-14 h-14 rounded-full object-cover border-2 border-indigo-500 mr-4"
                                alt="Siti">
                            <div>
                                <h3 class="font-bold text-gray-800">Siti Rahmawati</h3>
                                <p class="text-sm text-gray-500">Pemilik Toko Kue Manis</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">
                            “Sejak pakai Mora Finance, pencatatan keuangan toko jadi jauh lebih rapi dan cepat.
                            Sekarang saya bisa fokus ke produksi tanpa pusing ngitung manual lagi!”
                        </p>
                    </div>
                </div>

                <!-- Testimoni 2 -->
                <div class="swiper-slide p-4">
                    <div
                        class="bg-white/70 backdrop-blur-lg rounded-3xl shadow-md p-8 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <img src="https://randomuser.me/api/portraits/men/52.jpg"
                                class="w-14 h-14 rounded-full object-cover border-2 border-indigo-500 mr-4"
                                alt="Budi">
                            <div>
                                <h3 class="font-bold text-gray-800">Budi Santoso</h3>
                                <p class="text-sm text-gray-500">Owner Warung Kopi Budi</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">
                            “Aplikasi ini ngebantu banget buat ngatur pengeluaran harian dan stok barang.
                            Desainnya juga enak dilihat, jadi nggak bosen buka tiap hari.”
                        </p>
                    </div>
                </div>

                <!-- Testimoni 3 -->
                <div class="swiper-slide p-4">
                    <div
                        class="bg-white/70 backdrop-blur-lg rounded-3xl shadow-md p-8 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <img src="https://randomuser.me/api/portraits/men/79.jpg"
                                class="w-14 h-14 rounded-full object-cover border-2 border-indigo-500 mr-4"
                                alt="Andi">
                            <div>
                                <h3 class="font-bold text-gray-800">Andi Prasetyo</h3>
                                <p class="text-sm text-gray-500">Pengusaha Percetakan</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">
                            “Dengan Mora Finance, saya bisa pantau arus kas dari HP aja.
                            Laporannya jelas dan detail — cocok banget buat UKM kayak saya.”
                        </p>
                    </div>
                </div>

                <!-- Testimoni 4 -->
                <div class="swiper-slide p-4">
                    <div
                        class="bg-white/70 backdrop-blur-lg rounded-3xl shadow-md p-8 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <img src="https://randomuser.me/api/portraits/women/70.jpg"
                                class="w-14 h-14 rounded-full object-cover border-2 border-indigo-500 mr-4"
                                alt="Rina">
                            <div>
                                <h3 class="font-bold text-gray-800">Rina Marlina</h3>
                                <p class="text-sm text-gray-500">Pemilik Butik RinaStyle</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">
                            “Mora Finance bantu saya kontrol arus kas dan profit tiap minggu.
                            Sekarang laporan keuangan jadi tinggal klik aja!”
                        </p>
                    </div>
                </div>

                <!-- Testimoni 5 -->
                <div class="swiper-slide p-4">
                    <div
                        class="bg-white/70 backdrop-blur-lg rounded-3xl shadow-md p-8 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <img src="https://randomuser.me/api/portraits/men/48.jpg"
                                class="w-14 h-14 rounded-full object-cover border-2 border-indigo-500 mr-4"
                                alt="Eko">
                            <div>
                                <h3 class="font-bold text-gray-800">Eko Prabowo</h3>
                                <p class="text-sm text-gray-500">Freelancer Desain Grafis</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">
                            “Fitur laporan bulanan Mora Finance keren banget,
                            bisa langsung saya kirim ke klien untuk transparansi pengeluaran.”
                        </p>
                    </div>
                </div>

                <!-- Testimoni 6 -->
                <div class="swiper-slide p-4">
                    <div
                        class="bg-white/70 backdrop-blur-lg rounded-3xl shadow-md p-8 border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center mb-6">
                            <img src="https://randomuser.me/api/portraits/women/81.jpg"
                                class="w-14 h-14 rounded-full object-cover border-2 border-indigo-500 mr-4"
                                alt="Lestari">
                            <div>
                                <h3 class="font-bold text-gray-800">Dewi Lestari</h3>
                                <p class="text-sm text-gray-500">Pemilik Kedai Makan Lestari</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">
                            “Sekarang saya bisa cek omzet harian dari HP aja,
                            nggak perlu buka buku catatan lagi. Mora Finance sangat membantu!”
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="swiper-pagination mt-8"></div>
        </div>
    </section>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('transactionChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels ?? []) !!},
                datasets: [{
                    label: 'Pemasukan',
                    data: {!! json_encode($chartIncome ?? []) !!},
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22,163,74,0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2
                }, {
                    label: 'Pengeluaran',
                    data: {!! json_encode($chartExpense ?? []) !!},
                    borderColor: '#dc2626',
                    backgroundColor: 'rgba(220,38,38,0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
    </script>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new Swiper('.mySummarySwiper', {
                    slidesPerView: 1,
                    spaceBetween: 16,
                    autoplay: true,
                    loop: true,
                    // pagination: {
                    //     el: '.swiper-pagination',
                    //     clickable: true,
                    // },
                });
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                new Swiper(".paket-swiper", {
                    slidesPerView: 1,
                    spaceBetween: 16,
                    centeredSlides: true,
                    autoplay: true,
                    loop: true,
                });

                new Swiper(".news-swiper", {
                    slidesPerView: 1,
                    spaceBetween: 16,
                    centeredSlides: true,
                    autoplay: true,
                    loop: true,
                });
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                new Swiper(".testimonial-swiper", {
                    slidesPerView: 1.1,
                    spaceBetween: 20,
                    centeredSlides: true,
                    loop: true,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2.2,
                        },
                        1024: {
                            slidesPerView: 3,
                        },
                    },
                });
            });
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Swiper untuk carousel logo
                new Swiper(".logo-swiper", {
                    slidesPerView: 2.5,
                    spaceBetween: 40,
                    loop: true,
                    speed: 3000, // semakin kecil semakin cepat
                    freeMode: true,
                    autoplay: {
                        delay: 0,
                        disableOnInteraction: false,
                    },
                    allowTouchMove: false, // biar nggak bisa di-drag, jadi benar-benar auto jalan
                    breakpoints: {
                        640: {
                            slidesPerView: 3.5,
                        },
                        768: {
                            slidesPerView: 5,
                        },
                        1024: {
                            slidesPerView: 6,
                        },
                    },
                });
            });
        </script>
    @endpush
@endsection
