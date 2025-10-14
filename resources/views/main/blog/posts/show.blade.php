@extends('main.layouts.app')

@section('content')
    <div class="container mx-auto mt-28 px-6 max-w-5xl">
        <div class="bg-white/70 backdrop-blur-lg border border-gray-200 shadow-md rounded-3xl p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">{{ $blogPost->title }}</h1>
                <a href="{{ route('blogPosts.list') }}"
                    class="px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition">
                    ← Kembali
                </a>
            </div>

            @if ($blogPost->image)
                <img src="{{ asset('storage/' . $blogPost->image) }}" alt="{{ $blogPost->title }}"
                    class="w-full h-80 object-cover rounded-2xl mb-8 shadow">
            @endif

            <div class="flex flex-wrap gap-3 mb-4">
                <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-sm font-medium">
                    {{ $blogPost->category->name ?? 'Umum' }}
                </span>
                @if ($blogPost->status === 'published')
                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                        Published
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-sm font-medium">
                        Draft
                    </span>
                @endif
                <span class="text-gray-500 text-sm">
                    {{ $blogPost->published_at ? date('d M Y', strtotime($blogPost->published_at)) : 'Belum diterbitkan' }}
                </span>
            </div>

            <div class="prose prose-indigo max-w-none mb-8">
                {!! nl2br(e($blogPost->content)) !!}
            </div>

            @if ($blogPost->tags)
                <div class="mt-6 border-t pt-4 text-sm text-gray-500">
                    <strong>Tags:</strong>
                    @foreach (explode(',', $blogPost->tags) as $tag)
                        <span class="ml-2 px-3 py-1 bg-gray-100 rounded-full">{{ trim($tag) }}</span>
                    @endforeach
                </div>
            @endif

            <div class="mt-10 flex justify-between text-gray-500 text-sm">
                <span><i class="fa-regular fa-eye"></i> {{ $blogPost->views }} views</span>
                <span>Author: {{ $blogPost->author ?? 'Tidak diketahui' }}</span>
            </div>
        </div>
    </div>
@endsection
