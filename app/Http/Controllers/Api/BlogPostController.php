<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with('category')
            ->where('status', 'published')
            ->latest();

        if ($request->has('category_slug')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category_slug);
            });
        }

        $posts = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $posts
        ]);
    }

    public function show($slug)
    {
        $post = BlogPost::with('category')->where('slug', $slug)->first();

        if (!$post) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }

        // Tambahkan view count
        $post->increment('views');

        return response()->json([
            'status' => 'success',
            'data' => $post
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'blog_category_id' => 'required|uuid|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'author' => 'nullable|string|max:100',
            'source' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048'
        ]);

        $data['slug'] = Str::slug($data['title']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        $blog = BlogPost::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Artikel berhasil ditambahkan',
            'data' => $blog
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $blog = BlogPost::findOrFail($id);

        $data = $request->validate([
            'blog_category_id' => 'required|uuid|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'author' => 'nullable|string|max:100',
            'source' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($data['title'] !== $blog->title) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        if ($data['status'] === 'published' && !$blog->published_at) {
            $data['published_at'] = now();
        }

        $blog->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Artikel berhasil diperbarui',
            'data' => $blog
        ]);
    }

    public function destroy($id)
    {
        $blog = BlogPost::findOrFail($id);

        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Artikel berhasil dihapus'
        ]);
    }
}
