@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-28 px-6 max-w-4xl">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">📝 Tambah Artikel Baru</h1>

        <form action="{{ route('blogPosts.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6 bg-white/60 backdrop-blur-lg p-8 rounded-2xl shadow-md border border-gray-200">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul</label>
                <input type="text" name="title" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                <select name="blog_category_id" class="w-full px-4 py-2 border rounded-lg">
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Konten</label>
                <textarea name="content" rows="8" class="w-full px-4 py-2 border rounded-lg"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar</label>
                <input type="file" name="image" class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border rounded-lg">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Author</label>
                    <input type="text" name="author" class="w-full px-4 py-2 border rounded-lg">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tags (pisahkan dengan koma)</label>
                <input type="text" name="tags" class="w-full px-4 py-2 border rounded-lg">
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('blogPosts.list') }}"
                    class="px-5 py-2 rounded-full border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2 rounded-full bg-indigo-600 text-white hover:bg-indigo-700 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
