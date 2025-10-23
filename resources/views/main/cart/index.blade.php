@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-28 px-4 max-w-5xl">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-8">🛒 Keranjang Langganan</h1>

        @if (count($cart) > 0)
            <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-lg p-8 border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-600 border-b">
                                <th class="py-3">Nama Paket</th>
                                <th class="py-3">Durasi</th>
                                <th class="py-3 text-right">Harga</th>
                                <th class="py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart as $item)
                                @php
                                    $durationLabel =
                                        $item['duration_days'] < 30
                                            ? 'Harian'
                                            : ($item['duration_days'] < 365
                                                ? 'Bulanan'
                                                : 'Tahunan');
                                @endphp
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="py-4 font-semibold text-gray-800">{{ $item['name'] }}</td>
                                    <td class="py-4 text-gray-600">{{ $durationLabel }}</td>
                                    <td class="py-4 text-right font-bold text-indigo-600">
                                        Rp{{ number_format($item['price'], 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 text-center">
                                        <form action="{{ route('cart.remove', $item['id']) }}" method="POST"
                                            onsubmit="return confirm('Hapus item ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800">Total:</h2>
                    <p class="text-3xl font-extrabold text-indigo-600">
                        Rp{{ number_format($total, 0, ',', '.') }}
                    </p>
                </div>

                <div class="mt-10 text-right">
                    <a href="{{ route('billing.checkout', $item['id']) }}"
                        class="inline-block bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 
                          text-white font-semibold px-8 py-3 rounded-full shadow-md hover:shadow-indigo-400/40 transition-all">
                        Lanjut ke Pembayaran
                    </a>
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <img src="https://cdn-icons-png.flaticon.com/512/11329/11329060.png" alt="Empty Cart"
                    class="mx-auto w-40 opacity-70 mb-6">
                <h2 class="text-2xl font-bold text-gray-700 mb-3">Keranjang Kamu Kosong 😅</h2>
                <p class="text-gray-500 mb-6">Belum ada paket yang kamu tambahkan ke keranjang.</p>
                <a href="{{ route('billing.index') }}"
                    class="bg-indigo-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-indigo-700 transition">
                    Pilih Paket Sekarang
                </a>
            </div>
        @endif
    </div>
@endsection
