@extends('layouts.admin')
@section('title', 'Manage Blogs')
@section('page-title', 'Manage Blogs')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-newspaper" style="color:#1565c0;"></i> All Blogs</h2>
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Blog
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:60px">Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $blog)
                <tr>
                    <td>
                        @if($blog->image)
                            <img src="{{ asset('storage/' . $blog->image) }}" alt="">
                        @else
                            <div style="width:50px; height:40px; background:#e3f2fd; border-radius:6px; display:grid; place-items:center; color:#1565c0;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <p style="font-weight: 500; font-size: 0.875rem; max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $blog->title }}</p>
                    </td>
                    <td><span class="badge badge-blue">{{ $blog->category->name ?? '—' }}</span></td>
                    <td style="font-size: 0.8rem; color: #94a3b8;">{{ $blog->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="btn btn-secondary btn-sm" target="_blank" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-secondary btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.blogs.destroy', $blog) }}" onsubmit="return confirm('Delete this blog?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding: 3rem; color: #94a3b8;">
                        No blogs found. <a href="{{ route('admin.blogs.create') }}" style="color:#1565c0;">Create your first blog!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($blogs->hasPages())
    <div style="padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0;">
        {{ $blogs->links() }}
    </div>
    @endif
</div>
@endsection
