@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-24 px-6 max-w-2xl text-center">
        <div class="bg-white shadow-md rounded-2xl p-8 border border-green-200">
            <div class="flex justify-center mb-4">
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fa-solid fa-check text-green-600 text-2xl"></i>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Berhasil 🎉</h1>
            <p class="text-gray-600 mb-6">Paket <strong>{{ $plan->name }}</strong> Anda telah aktif hingga
                <strong>{{ $plan->end_date->format('d M Y') }}</strong>.</p>

            <a href="{{ url('/dashboard') }}"
                class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
@endsection
