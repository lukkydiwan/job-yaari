@if($blogs->isEmpty())
    <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: 10px; border: 1px dashed #e2e8f0;">
        <i class="fas fa-newspaper" style="font-size: 3rem; color: #94a3b8; margin-bottom: 1rem; display: block;"></i>
        <h3 style="font-family: 'Sora', sans-serif; color: #475569; margin-bottom: 0.5rem;">No blogs found</h3>
        <p style="color: #94a3b8; font-size: 0.875rem;">Try a different search or filter combination.</p>
    </div>
@else
    <div class="blog-grid">
        @foreach($blogs as $blog)
        <article class="blog-card">
            <div class="blog-card-img">
                @if($blog->image)
                    <img src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}" loading="lazy">
                @else
                    <div class="no-img">
                        <i class="fas fa-newspaper"></i>
                    </div>
                @endif
            </div>
            <div class="blog-card-body">
                <div class="blog-meta">
                    <span class="blog-category-badge">{{ $blog->category->name ?? 'General' }}</span>
                    <span><i class="fas fa-calendar-alt"></i> {{ $blog->created_at->format('d M Y') }}</span>
                </div>
                <h2><a href="{{ route('blogs.show', $blog->slug) }}">{{ $blog->title }}</a></h2>
                <p class="blog-excerpt">{{ $blog->short_description }}</p>
                @if($blog->tags && count($blog->tags))
                    <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 0.25rem;">
                        @foreach($blog->tags as $tag)
                            <span style="background: #f1f5f9; color: #475569; padding: 2px 10px; border-radius: 20px; font-size: 0.72rem;">#{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
                <a href="{{ route('blogs.show', $blog->slug) }}" class="btn-read-more">
                    Read More <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </article>
        @endforeach
    </div>
@endif
