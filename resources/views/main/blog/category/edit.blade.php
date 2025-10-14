@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-28 px-6 max-w-3xl">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">✏️ Edit Kategori</h1>

        <form action="{{ route('blogCategories.update', $category->id) }}" method="POST" enctype="multipart/form-data"
            class="bg-white shadow rounded-2xl p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-gray-700 font-medium mb-2">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}"
                    class="w-full border-gray-300 rounded-lg focus:ring focus:ring-blue-100" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Status</label>
                <select name="status" class="w-full border-gray-300 rounded-lg focus:ring focus:ring-blue-100">
                    <option value="active" {{ $category->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $category->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Gambar (opsional)</label>
                @if ($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" class="w-32 h-32 object-cover rounded-lg mb-3">
                @endif
                <input type="file" name="image" class="w-full text-gray-600 border-gray-300 rounded-lg">
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('blogCategories.index') }}"
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</a>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
@endsection
