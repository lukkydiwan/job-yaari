<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('category')->orderByDesc('created_at')->paginate(10);
        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'content'           => 'required',
            'category_id'       => 'required|exists:categories,id',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'published_at'      => 'nullable|date',
            'tags'              => 'nullable|string',
        ]);

        $slug = Str::slug($validated['title']);
        $original = $slug;
        $count = 1;
        while (Blog::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        $tags = $request->tags
            ? array_map('trim', explode(',', $request->tags))
            : [];

        Blog::create([
            'title'             => $validated['title'],
            'slug'              => $slug,
            'short_description' => $validated['short_description'],
            'content'           => $validated['content'],
            'category_id'       => $validated['category_id'],
            'image'             => $imagePath,
            'published_at'      => $validated['published_at'] ?? now()->toDateString(),
            'tags'              => $tags,
        ]);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog created successfully!');
    }

    public function edit(Blog $blog)
    {
        $categories = Category::all();
        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'content'           => 'required',
            'category_id'       => 'required|exists:categories,id',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'published_at'      => 'nullable|date',
            'tags'              => 'nullable|string',
        ]);

        $imagePath = $blog->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        $tags = $request->tags
            ? array_map('trim', explode(',', $request->tags))
            : [];

        $blog->update([
            'title'             => $validated['title'],
            'slug'              => Str::slug($validated['title']),
            'short_description' => $validated['short_description'],
            'content'           => $validated['content'],
            'category_id'       => $validated['category_id'],
            'image'             => $imagePath,
            'published_at'      => $validated['published_at'] ?? $blog->published_at,
            'tags'              => $tags,
        ]);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog updated successfully!');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blogs.index')
            ->with('success', 'Blog deleted.');
    }
}
