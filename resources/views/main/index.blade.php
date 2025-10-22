@extends('main.layouts.app')

@section('content')
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

    <!-- Paket Langganan -->
    <div class="container mx-auto px-4 py-14 mt-10">
        <div class="text-center mb-10">
            <h2 class="font-bold text-2xl uppercase mb-2 text-gray-800">Pilih Paket Langganan</h2>
            <p class="text-gray-500">Pilih paket sesuai kebutuhanmu dan nikmati semua fitur premium.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($billing as $package)
                <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-lg hover:-translate-y-1 transition">
                    <h3 class="text-xl font-bold text-indigo-600 mb-2">{{ $package->name }}</h3>
                    <p class="text-gray-500 mb-4 h-14">{{ Str::limit($package->description, 80) }}</p>
                    <p class="text-3xl font-extrabold text-gray-900 mb-4">
                        Rp{{ number_format($package->price, 0, ',', '.') }}
                    </p>
                    <a href="#"
                        class="block text-center bg-indigo-600 text-white py-2 rounded-xl font-semibold hover:bg-indigo-700 transition">
                        Pilih Paket
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Berita Terbaru -->
    <div class="container mx-auto px-4 py-14">
        <div class="text-center mb-10">
            <h2 class="font-bold text-2xl uppercase mb-2 text-gray-800">Berita Terbaru</h2>
            <p class="text-gray-500">Dapatkan update dan tips terbaru seputar bisnis & keuangan.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($news->take(5) as $item)
                <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-lg transition">
                    @if ($item->thumbnail)
                        <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}"
                            class="w-full h-48 object-cover">
                    @endif
                    <div class="p-5">
                        <p class="text-sm text-gray-400 mb-1">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</p>
                        <img src="{{ asset('storage/' . $item->image) }}" alt="">
                        <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2">{{ $item->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ Str::limit(strip_tags($item->content), 100) }}
                        </p>
                        <a href="{{ route('blogPosts.read', $item->slug) }}"
                            class="text-indigo-600 font-semibold hover:text-indigo-800 transition">
                            Baca Selengkapnya →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

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
