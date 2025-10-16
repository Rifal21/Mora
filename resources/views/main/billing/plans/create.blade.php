@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-28 px-6 max-w-3xl">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">➕ Tambah Paket Langganan</h1>

        <form action="{{ route('plans.store') }}" method="POST"
            class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Nama Paket</label>
                    <input type="text" name="name" class="border rounded-lg p-2 w-full" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Harga (Rp)</label>
                    <input type="number" name="price" class="border rounded-lg p-2 w-full" required>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-1">Durasi (hari)</label>
                    <input type="number" name="duration_days" class="border rounded-lg p-2 w-full" required>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-gray-700 font-medium mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="border rounded-lg p-2 w-full"></textarea>
            </div>

            <div class="mt-4">
                <label class="block text-gray-700 font-medium mb-1">Fitur (pisahkan dengan koma)</label>
                <input type="text" name="features" placeholder="contoh: Akses Premium, Support 24 Jam, Backup Otomatis"
                    class="border rounded-lg p-2 w-full">
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('plans.index') }}" class="text-gray-600 hover:text-gray-800 mr-3">Batal</a>
                <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
