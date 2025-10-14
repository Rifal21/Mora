@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-28 px-6 max-w-7xl">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">📝 Manajemen Blog</h1>
            <a href="{{ route('blogPosts.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-full shadow transition">
                + Tambah Artikel
            </a>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-gray-200 shadow-md bg-white/60 backdrop-blur">
            <table class="min-w-full text-left text-gray-700">
                <thead class="bg-gray-100 text-gray-800 font-semibold">
                    <tr>
                        <th class="px-6 py-3">Judul</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Dilihat</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($blogs as $blog)
                        <tr class="border-t border-gray-100 hover:bg-gray-50 transition">
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $blog->title }}</td>
                            <td class="px-6 py-3">{{ $blog->category->name ?? '-' }}</td>
                            <td class="px-6 py-3">
                                <span
                                    class="px-3 py-1 rounded-full text-xs
                                {{ $blog->status === 'published' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                                    {{ ucfirst($blog->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3">{{ $blog->views }}</td>
                            <td class="px-6 py-3 text-sm text-gray-500">{{ $blog->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-3 text-right space-x-2">
                                <a href="{{ route('blogPosts.show', $blog->id) }}"
                                    class="text-blue-500 hover:text-blue-700">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('blogPosts.edit', $blog->id) }}"
                                    class="text-yellow-500 hover:text-yellow-700">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('blogPosts.destroy', $blog->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus artikel ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($blogs->isEmpty())
                <div class="p-6 text-center text-gray-500">Belum ada artikel</div>
            @endif
        </div>
    </div>
@endsection
