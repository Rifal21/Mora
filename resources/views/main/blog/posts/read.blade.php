@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-24 px-4 max-w-6xl grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Artikel Utama -->
        <article class="md:col-span-2 bg-white rounded-2xl shadow p-6">
            <!-- Gambar -->
            @if ($blogPost->image)
                <img src="{{ asset('storage/' . $blogPost->image) }}" alt="{{ $blogPost->title }}"
                    class="w-full h-72 object-cover rounded-xl mb-6">
            @endif

            <!-- Judul -->
            <h1 class="text-3xl font-bold text-gray-800 mb-3">{{ $blogPost->title }}</h1>

            <!-- Meta -->
            <div class="flex flex-wrap items-center text-sm text-gray-500 mb-6 gap-4">
                <div class="flex items-center gap-1">
                    <i class="fa-solid fa-user text-gray-400"></i>
                    <span>{{ $blogPost->author ?? 'Admin' }}</span>
                </div>

                <div class="flex items-center gap-1">
                    <i class="fa-regular fa-calendar text-gray-400"></i>
                    <span>
                        @php
                            $publishedDate = $blogPost->published_at
                                ? \Carbon\Carbon::parse($blogPost->published_at)
                                : $blogPost->created_at ?? null;
                        @endphp
                        {{ $publishedDate ? $publishedDate->format('d M Y') : '-' }}
                    </span>
                </div>

                <div class="flex items-center gap-1">
                    <i class="fa-regular fa-eye text-gray-400"></i>
                    <span>{{ number_format($blogPost->views) }} views</span>
                </div>

                <div class="flex items-center gap-1">
                    <i class="fa-solid fa-tags text-gray-400"></i>
                    <span>{{ $blogPost->tags ?? '-' }}</span>
                </div>
            </div>


            <!-- Konten -->
            <div class="prose max-w-none text-gray-700 leading-relaxed">
                {!! nl2br(e($blogPost->content)) !!}
            </div>

            <!-- Source / Tags -->
            <div class="mt-10 border-t pt-6 text-sm text-gray-500 flex flex-col sm:flex-row justify-between">
                @if ($blogPost->source)
                    <div>Sumber: <a href="{{ $blogPost->source }}"
                            class="text-blue-600 hover:underline">{{ $blogPost->source }}</a></div>
                @endif
                @if ($blogPost->tags)
                    <div class="mt-2 sm:mt-0">
                        <span class="font-medium text-gray-600">Tags:</span>
                        @foreach (explode(',', $blogPost->tags) as $tag)
                            <span
                                class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Related Posts -->
            @if ($relatedPosts->count())
                <div class="mt-14">
                    <h2 class="text-xl font-semibold text-gray-800 mb-5 border-b pb-2">Artikel Terkait</h2>
                    <div class="grid sm:grid-cols-2 gap-6">
                        @foreach ($relatedPosts as $related)
                            <a href="{{ route('blogPosts.read', $related->slug) }}"
                                class="group block bg-gray-50 rounded-xl overflow-hidden hover:shadow-md transition">
                                @if ($related->image)
                                    <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}"
                                        class="h-40 w-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @endif
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 group-hover:text-blue-600">
                                        {{ Str::limit($related->title, 70) }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ Str::limit(strip_tags($related->content), 100) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </article>

        <!-- Sidebar -->
        <aside class="space-y-6">
            <div class="bg-white rounded-2xl shadow p-6">
                <h2 class="text-lg font-semibold mb-4 border-b pb-2">🔥 Artikel Populer</h2>
                <ul class="space-y-4">
                    @forelse($popularPosts as $popular)
                        <li>
                            <a href="{{ route('blogPosts.read', $popular->slug) }}"
                                class="flex items-start space-x-3 group">
                                @if ($popular->image)
                                    <img src="{{ asset('storage/' . $popular->image) }}" alt="{{ $popular->title }}"
                                        class="w-16 h-16 object-cover rounded-md">
                                @endif
                                <div>
                                    <h3 class="font-medium text-gray-800 group-hover:text-blue-600">
                                        {{ Str::limit($popular->title, 60) }}
                                    </h3>
                                    <p class="text-xs text-gray-500">{{ number_format($popular->views) }} views</p>
                                </div>
                            </a>
                        </li>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada artikel populer.</p>
                    @endforelse
                </ul>
            </div>
        </aside>
    </div>
@endsection
