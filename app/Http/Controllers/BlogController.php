<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('blogs')->get();
        $query = Blog::with('category')->orderByDesc('created_at');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        $blogs = $query->paginate(6);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('blogs.partials.blog-list', compact('blogs'))->render(),
                'pagination' => $blogs->links()->toHtml(),
            ]);
        }

        return view('blogs.index', compact('blogs', 'categories'));
    }

    public function show($slug)
    {
        $blog = Blog::with('category')->where('slug', $slug)->firstOrFail();
        $related = Blog::where('category_id', $blog->category_id)
            ->where('id', '!=', $blog->id)
            ->latest()->take(3)->get();
        $categories = Category::withCount('blogs')->get();
        return view('blogs.show', compact('blog', 'related', 'categories'));
    }
}
