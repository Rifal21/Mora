@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-24 px-6 max-w-2xl text-center">
        <div class="bg-white shadow-md rounded-2xl p-8 border border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Menunggu Pembayaran</h1>
            <p class="text-gray-600 mb-6">Silakan selesaikan pembayaran untuk paket
                <strong>{{ $transaction->plan->name }}</strong>.
            </p>

            <div class="bg-gray-50 p-4 rounded-lg text-left mb-6">
                <p><strong>Nomor Invoice:</strong> {{ $transaction->invoice_number }}</p>
                <p><strong>Jumlah:</strong> Rp {{ number_format($transaction->amount, 0, ',', '.') }}</p>
                <p><strong>Status:</strong>
                    <span
                        class="px-2 py-1 rounded-full text-xs font-semibold 
                    {{ $transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </p>
            </div>

            <p class="text-sm text-gray-500 mb-4">
                Setelah pembayaran berhasil, halaman ini akan otomatis ter-update atau Anda bisa refresh manual.
            </p>

            @if ($transaction->status === 'pending')
                <a href="{{ $transaction->payment_url }}" target="_blank"
                    class="bg-indigo-600 hover:bg-indigo-800 font-semibold text-white py-2 px-4 rounded-lg inline-flex items-center justify-center">Bayar
                    <i class="fa-solid fa-arrow-right ml-2"></i></a>
            @endif
        </div>
    </div>
@endsection
