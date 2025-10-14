<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogPostRequest;
use App\Http\Requests\UpdateBlogPostRequest;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blogs = BlogPost::with('category')->latest()->paginate(20);
        $categories = BlogCategory::all();

        return view('main.blog.posts.index', compact('blogs', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = BlogCategory::all();
        return view('main.blog.posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:draft,published',
            'author' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']);

        // Upload image
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        // Set published_at jika status published
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        // Set default values
        $validated['views'] = 0;

        BlogPost::create($validated);

        return redirect()->route('blogPosts.list')->with('success', 'Artikel berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPost $blogPost)
    {
        return view('main.blog.posts.show', compact('blogPost'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPost $blogPost)
    {
        $categories = BlogCategory::all();
        return view('main.blog.posts.edit', compact('blogPost', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogPost $blogPost)
    {
        $validated = $request->validate([
            'blog_category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:draft,published',
            'author' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
        ]);

        // Update slug jika judul berubah
        if ($validated['title'] !== $blogPost->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Ganti image jika ada upload baru
        if ($request->hasFile('image')) {
            if ($blogPost->image) {
                Storage::disk('public')->delete($blogPost->image);
            }
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        // Published_at logic
        if ($validated['status'] === 'published' && !$blogPost->published_at) {
            $validated['published_at'] = now();
        } elseif ($validated['status'] === 'draft') {
            $validated['published_at'] = null;
        }

        $blogPost->update($validated);

        return redirect()->route('blogPosts.list')->with('success', 'Artikel berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $blogPost)
    {
        if ($blogPost->image) {
            Storage::disk('public')->delete($blogPost->image);
        }

        $blogPost->delete();

        return redirect()->route('blogPosts.list')->with('success', 'Artikel berhasil dihapus.');
    }

    public function list()
    {
        $blogs = BlogPost::latest()->paginate(20);
        $categories = BlogCategory::all();
        return view('main.blog.posts.post-list', compact('blogs', 'categories'));
    }

    public function read($slug)
    {
        $blogPost = BlogPost::where('slug', $slug)->firstOrFail();
        $blogPost->increment('views');
        $relatedPosts = BlogPost::where('blog_category_id', $blogPost->blog_category_id)
            ->where('id', '!=', $blogPost->id)
            ->latest()
            ->take(4)
            ->get();

        $popularPosts = BlogPost::orderByDesc('views')
            ->take(5)
            ->get();

        return view('main.blog.posts.read', compact('blogPost', 'relatedPosts', 'popularPosts'));
    }
}
