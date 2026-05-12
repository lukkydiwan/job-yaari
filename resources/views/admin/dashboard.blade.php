@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">

    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-newspaper"></i></div>
        <div>
            <p style="font-size: 0.78rem; color: #94a3b8; font-weight: 500;">Total Blogs</p>
            <p style="font-size: 1.75rem; font-weight: 700; font-family: 'Sora', sans-serif; color: #1e293b;">{{ $stats['total_blogs'] }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-th-large"></i></div>
        <div>
            <p style="font-size: 0.78rem; color: #94a3b8; font-weight: 500;">Categories</p>
            <p style="font-size: 1.75rem; font-weight: 700; font-family: 'Sora', sans-serif; color: #1e293b;">{{ $stats['total_categories'] }}</p>
        </div>
    </div>

    <div class="stat-card" style="cursor: pointer;" onclick="window.location='{{ route('admin.blogs.create') }}'">
        <div class="stat-icon" style="background: #fef3c7; color: #d97706;"><i class="fas fa-plus-circle"></i></div>
        <div>
            <p style="font-size: 0.78rem; color: #94a3b8; font-weight: 500;">Quick Action</p>
            <p style="font-size: 0.95rem; font-weight: 600; color: #1565c0;">Add New Blog</p>
        </div>
    </div>
</div>

<!-- Recent Blogs -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-clock" style="color: #1565c0;"></i> Recent Blogs</h2>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary btn-sm">View All</a>
    </div>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats['recent_blogs'] as $blog)
                <tr>
                    <td style="max-width: 300px;">
                        <p style="font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $blog->title }}</p>
                        <p style="font-size: 0.78rem; color: #94a3b8;">{{ Str::limit($blog->short_description, 60) }}</p>
                    </td>
                    <td><span class="badge badge-blue">{{ $blog->category->name ?? '—' }}</span></td>
                    <td style="font-size: 0.8rem; color: #94a3b8;">{{ $blog->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-secondary btn-sm"><i class="fas fa-edit"></i></a>
                            <a href="{{ route('blogs.show', $blog->slug) }}" class="btn btn-secondary btn-sm" target="_blank"><i class="fas fa-eye"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align: center; color: #94a3b8; padding: 2rem;">No blogs yet. <a href="{{ route('admin.blogs.create') }}" style="color: #1565c0;">Add one now!</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
