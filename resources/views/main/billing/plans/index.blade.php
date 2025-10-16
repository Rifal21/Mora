@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-28 px-6 max-w-6xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">📦 Daftar Paket Langganan</h1>
            <a href="{{ route('plans.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                + Tambah Plan
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-indigo-600 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">Nama</th>
                        <th class="py-3 px-4 text-left">Harga</th>
                        <th class="py-3 px-4 text-left">Durasi</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($plans as $plan)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 font-semibold">{{ $plan->name }}</td>
                            <td class="py-3 px-4">Rp {{ number_format($plan->price, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">{{ $plan->duration_days }} hari</td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('plans.edit', $plan->id) }}"
                                    class="text-blue-600 hover:text-blue-800 mr-2">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Hapus plan ini?')"
                                        class="text-red-500 hover:text-red-700">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">Belum ada plan tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
