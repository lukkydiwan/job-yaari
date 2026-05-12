@extends('layouts.app')

@section('title', $blog->title . ' – JobYaari')

@section('content')

<div class="page-banner">
    <h1 style="font-size: 1.5rem; max-width: 700px; margin: 0 auto 0.5rem;">{{ $blog->title }}</h1>
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <a href="{{ route('blogs.index') }}">Blogs</a>
        <span>/</span>
        <span>{{ Str::limit($blog->title, 40) }}</span>
    </div>
</div>

<div class="container">
    <div class="main-grid">

        <!-- ARTICLE -->
        <main>
            <article style="background: white; border-radius: 10px; box-shadow: 0 2px 12px rgba(21,101,192,0.08); border: 1px solid #e2e8f0; overflow: hidden;">

                @if($blog->image)
                <div style="height: 360px; overflow: hidden;">
                    <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                @endif

                <div style="padding: 2rem 2.5rem;">

                    <!-- Meta -->
                    <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; margin-bottom: 1.25rem;">
                        <span class="blog-category-badge" style="font-size: 0.8rem; padding: 4px 14px;">
                            {{ $blog->category->name ?? 'General' }}
                        </span>
                        <span style="font-size: 0.8rem; color: #94a3b8;">
                            <i class="fas fa-calendar-alt"></i>
                            {{ $blog->created_at->format('d M Y') }}
                        </span>
                        @if($blog->published_at)
                        <span style="font-size: 0.8rem; color: #94a3b8;">
                            <i class="fas fa-clock"></i>
                            Published: {{ $blog->published_at->format('d M Y') }}
                        </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 style="font-size: 1.6rem; font-weight: 700; line-height: 1.35; margin-bottom: 1rem; color: #1e293b;">
                        {{ $blog->title }}
                    </h1>

                    <!-- Short Desc -->
                    <p style="color: #475569; font-size: 1rem; border-left: 4px solid #1565c0; padding-left: 1rem; margin-bottom: 2rem; line-height: 1.7; background: #e3f2fd; padding: 1rem 1.25rem; border-radius: 0 8px 8px 0;">
                        {{ $blog->short_description }}
                    </p>

                    <!-- Full Content -->
                    <div class="blog-content">
                        {!! $blog->content !!}
                    </div>

                    <!-- Tags -->
                    @if($blog->tags && count($blog->tags))
                    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0;">
                        <span style="font-weight: 600; font-size: 0.85rem; color: #475569; margin-right: 0.5rem;">Tags:</span>
                        @foreach($blog->tags as $tag)
                            <span style="background: #f1f5f9; color: #475569; padding: 3px 12px; border-radius: 20px; font-size: 0.8rem; margin: 2px; display: inline-block;">#{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif

                    <!-- Share -->
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                        <span style="font-weight: 600; font-size: 0.85rem; color: #475569;">Share:</span>
                        <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . url()->current()) }}" target="_blank"
                           style="background: #25D366; color: white; padding: 0.45rem 1rem; border-radius: 6px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 5px; font-weight: 600;">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(url()->current()) }}" target="_blank"
                           style="background: #1DA1F2; color: white; padding: 0.45rem 1rem; border-radius: 6px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 5px; font-weight: 600;">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                    </div>

                </div>
            </article>

            <!-- Related Blogs -->
            @if($related->count())
            <section style="margin-top: 2rem;">
                <h2 style="font-family: 'Sora', sans-serif; font-size: 1.2rem; font-weight: 700; margin-bottom: 1.25rem; color: #1e293b;">
                    <i class="fas fa-newspaper" style="color: #1565c0;"></i> Related Blogs
                </h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem;">
                    @foreach($related as $rel)
                    <a href="{{ route('blogs.show', $rel->slug) }}"
                       style="background: white; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; display: block; transition: transform 0.2s, box-shadow 0.2s; text-decoration: none;"
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(21,101,192,0.1)'"
                       onmouseout="this.style.transform=''; this.style.boxShadow=''">
                        @if($rel->image)
                        <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->title }}"
                             style="width: 100%; height: 140px; object-fit: cover;">
                        @else
                        <div style="height: 140px; background: #e3f2fd; display: grid; place-items: center; color: #1565c0; font-size: 2rem;">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        @endif
                        <div style="padding: 0.85rem 1rem;">
                            <p style="font-size: 0.825rem; color: #94a3b8; margin-bottom: 0.25rem;">{{ $rel->created_at->format('d M Y') }}</p>
                            <h3 style="font-family: 'Sora', sans-serif; font-size: 0.9rem; font-weight: 600; color: #1e293b; line-height: 1.4;">
                                {{ Str::limit($rel->title, 60) }}
                            </h3>
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>
            @endif

        </main>

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-card">
                <div class="sidebar-card-header"><i class="fas fa-th-large"></i> Categories</div>
                <div class="sidebar-card-body">
                    <div class="category-list">
                        @foreach($categories as $cat)
                            <a href="{{ route('blogs.index', ['category_id' => $cat->id]) }}"
                               class="category-tag" style="text-decoration: none;">
                                {{ $cat->name }}
                                <small style="opacity:0.7">({{ $cat->blogs_count }})</small>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>

@push('styles')
<style>
.blog-content {
    font-size: 0.975rem;
    line-height: 1.85;
    color: #334155;
}
.blog-content h1, .blog-content h2, .blog-content h3 {
    font-family: 'Sora', sans-serif;
    margin: 1.5rem 0 0.75rem;
    color: #1e293b;
}
.blog-content h2 { font-size: 1.3rem; }
.blog-content h3 { font-size: 1.1rem; }
.blog-content p { margin-bottom: 1rem; }
.blog-content table { width: 100%; border-collapse: collapse; margin: 1.25rem 0; }
.blog-content table th, .blog-content table td {
    padding: 0.6rem 0.9rem;
    border: 1px solid #e2e8f0;
    font-size: 0.875rem;
}
.blog-content table th { background: #e3f2fd; color: #1565c0; font-weight: 600; }
.blog-content table tr:nth-child(even) td { background: #f8fafc; }
.blog-content ul, .blog-content ol { margin: 0.75rem 0 0.75rem 1.5rem; }
.blog-content li { margin-bottom: 0.4rem; }
.blog-content img { max-width: 100%; border-radius: 8px; }
.blog-content a { color: #1565c0; text-decoration: underline; }
.blog-content blockquote {
    border-left: 4px solid #1565c0;
    background: #e3f2fd;
    padding: 1rem 1.25rem;
    border-radius: 0 8px 8px 0;
    margin: 1rem 0;
    font-style: italic;
    color: #1565c0;
}
.blog-content pre, .blog-content code {
    background: #f1f5f9;
    border-radius: 6px;
    font-family: monospace;
    font-size: 0.85rem;
}
.blog-content pre { padding: 1rem; overflow-x: auto; }
.blog-content code { padding: 2px 6px; }
</style>
@endpush

@endsection
