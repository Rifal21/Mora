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
                <h2 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-indigo-600"></i> Transaksi Terakhir
                </h2>

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
