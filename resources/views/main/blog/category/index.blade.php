@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-28 px-6 max-w-6xl">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">📂 Kategori Blog</h1>
            <a href="{{ route('blogCategories.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                + Tambah Kategori
            </a>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded-2xl">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Nama</th>
                        <th class="px-6 py-3 text-left">Slug</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Gambar</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="border-t hover:bg-gray-50 transition">
                            <td class="px-6 py-3 font-medium">{{ $category->name }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $category->slug }}</td>
                            <td class="px-6 py-3">
                                <span
                                    class="px-2 py-1 text-xs rounded-full
                                {{ $category->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ ucfirst($category->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}"
                                        class="w-12 h-12 rounded-lg object-cover">
                                @else
                                    <span class="text-gray-400 text-sm">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-right">
                                <a href="{{ route('blogCategories.edit', $category->id) }}"
                                    class="text-blue-600 hover:text-blue-800 mr-3">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('blogCategories.destroy', $category->id) }}" method="POST"
                                    class="inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-400 py-6">Belum ada kategori 😶</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
