@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-28 px-6 max-w-7xl">
        <h1 class="text-4xl font-bold text-center mb-12 text-gray-800">
            ✨ Artikel & Insight Terbaru
        </h1>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($blogs as $blog)
                <div
                    class="group relative rounded-3xl overflow-hidden bg-gradient-to-br from-gray-50/60 to-gray-100/30 backdrop-blur-md border border-white/40 shadow-[0_8px_32px_rgba(31,38,135,0.1)] hover:shadow-[0_8px_32px_rgba(31,38,135,0.2)] transition-all duration-300 ease-out hover:-translate-y-1">

                    <!-- Gambar -->
                    <div class="relative h-56 overflow-hidden">
                        <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                        <div
                            class="absolute bottom-3 left-3 px-3 py-1 text-xs font-semibold bg-white/20 backdrop-blur-md rounded-full text-white">
                            {{ $blog->category->name ?? 'Umum' }}
                        </div>
                    </div>

                    <!-- Konten -->
                    <div class="p-6">
                        <h2
                            class="text-xl font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors duration-300">
                            {{ $blog->title }}
                        </h2>
                        <p class="text-gray-500 text-sm mt-2 line-clamp-3">
                            {{ $blog->excerpt ?? Str::limit(strip_tags($blog->content), 120) }}
                        </p>

                        <div class="mt-5 flex items-center justify-between text-sm text-gray-400">
                            <div class="flex items-center space-x-2">
                                <i class="fa-regular fa-clock"></i>
                                <span>{{ $blog->created_at->diffForHumans() }}</span>
                            </div>
                            <a href="{{ route('blogPosts.read', $blog->slug) }}"
                                class="text-indigo-500 font-medium hover:underline">Baca →</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Jika tidak ada blog -->
        @if ($blogs->isEmpty())
            <div class="text-center text-gray-500 mt-20">
                Belum ada artikel untuk ditampilkan 🌿
            </div>
        @endif
    </div>
@endsection
