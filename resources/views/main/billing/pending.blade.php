@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-24 px-6 max-w-3xl">
        <div class="bg-white/80 backdrop-blur-md shadow-lg rounded-2xl p-8 border border-gray-100">
            <h1 class="text-3xl font-extrabold text-gray-800 mb-6 text-center">
                💳 Menunggu Pembayaran
            </h1>
            <p class="text-gray-600 text-center mb-10">
                Silakan selesaikan pembayaran untuk paket
                <span class="font-semibold text-indigo-600">{{ $transaction->plan->name }}</span>.
            </p>

            <!-- Tabel Detail Transaksi -->
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <tbody class="divide-y divide-gray-200">
                        <tr class="bg-gray-50">
                            <th class="text-left px-6 py-4 font-semibold text-gray-700 w-1/3">Nomor Invoice</th>
                            <td class="px-6 py-4 text-gray-800">{{ $transaction->invoice_number }}</td>
                        </tr>
                        <tr>
                            <th class="text-left px-6 py-4 font-semibold text-gray-700">Paket</th>
                            <td class="px-6 py-4 text-gray-800">{{ $transaction->plan->name }}</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <th class="text-left px-6 py-4 font-semibold text-gray-700">Jumlah Pembayaran</th>
                            <td class="px-6 py-4 text-gray-800 font-bold text-lg">
                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-left px-6 py-4 font-semibold text-gray-700">Status</th>
                            <td class="px-6 py-4">
                                <span id="statusBadge"
                                    class="px-3 py-1 rounded-full text-sm font-semibold 
                                    {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr class="bg-gray-50">
                            <th class="text-left px-6 py-4 font-semibold text-gray-700">Tanggal Transaksi</th>
                            <td class="px-6 py-4 text-gray-800">
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y, H:i') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p class="text-sm text-gray-500 mt-6 text-center">
                Setelah pembayaran berhasil, halaman ini akan otomatis ter-update atau Anda bisa melakukan refresh manual.
            </p>

            @if ($transaction->status === 'pending')
                <div class="flex justify-center mt-8">
                    <a href="{{ $transaction->payment_url }}" target="_blank"
                        class="bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 
                        text-white font-semibold px-8 py-3 rounded-full shadow-md hover:shadow-indigo-400/40 transition-all inline-flex items-center gap-2">
                        <i class="fa-solid fa-credit-card"></i>
                        Bayar Sekarang
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const invoiceNumber = "{{ $transaction->invoice_number }}";
            const statusBadge = document.getElementById("statusBadge");

            // Cek status pembayaran setiap 5 detik
            const interval = setInterval(async () => {
                try {
                    const response = await fetch(`/api/transaction/status/${invoiceNumber}`);
                    const data = await response.json();

                    if (data.status !== "pending") {
                        statusBadge.textContent = data.status.charAt(0).toUpperCase() + data.status
                            .slice(1);
                        statusBadge.className =
                            `px-3 py-1 rounded-full text-sm font-semibold ${
                                data.status === "success" ? "bg-green-100 text-green-700" :
                                data.status === "failed" ? "bg-red-100 text-red-700" :
                                "bg-yellow-100 text-yellow-700"
                            }`;

                        if (data.status === "success") {
                            clearInterval(interval);
                            setTimeout(() => {
                                window.location.href = "{{ route('billing.index') }}";
                            }, 2000);
                        }
                    }
                } catch (err) {
                    console.error("Gagal memeriksa status transaksi:", err);
                }
            }, 5000);
        });
    </script>
@endsection
