@extends('main.layouts.app')

@section('content')
    @auth
        <div class="container mx-auto px-4 py-10 mt-20">

            <!-- Header -->
            <h1 class="text-3xl font-bold text-gray-800 text-center mb-8">
                Dashboard Keuangan
            </h1>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
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
                    Mora
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
    <section class="container mx-auto px-4 py-20 mt-10">
        <div class="text-center mb-16">
            <h2
                class="font-extrabold text-4xl mb-4 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-blue-600">
                Pilih Paket Langganan
            </h2>
            <p class="text-gray-500 text-lg">
                Pilih paket sesuai kebutuhanmu dan nikmati semua fitur premium untuk bisnismu.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
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


    <!-- Berita Terbaru -->
    <section class="container mx-auto px-4 py-20">
        <div class="text-center mb-16">
            <h2
                class="font-extrabold text-4xl mb-4 bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-blue-600">
                Berita Terbaru
            </h2>
            <p class="text-gray-500 text-lg">
                Dapatkan insight, tips, dan berita terbaru seputar bisnis & keuangan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            @foreach ($news as $item)
                <div
                    class="group bg-white rounded-3xl shadow-md overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">

                    @if ($item->thumbnail)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}"
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
@endsection
