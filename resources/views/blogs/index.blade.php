@extends('layouts.app')

@section('title', 'Blogs – JobYaari')

@section('content')

<div class="page-banner">
    <h1>Blogs</h1>
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <span>Blogs</span>
    </div>
</div>

<div class="container">
    <div class="main-grid">

        <!-- MAIN CONTENT -->
        <main>
            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search blogs by title or description..." value="{{ request('search') }}">
                <button id="searchBtn"><i class="fas fa-search"></i> Search</button>
            </div>

            <!-- Date Filter -->
            <div class="date-filter">
                <label for="dateFilter"><i class="fas fa-calendar-alt"></i> Filter by date:</label>
                <input type="date" id="dateFilter" value="{{ request('date') }}">
                <button class="btn-clear-filters" id="clearFilters">
                    <i class="fas fa-times"></i> Clear Filters
                </button>
            </div>

            <!-- Active Filter Badge -->
            <div id="activeFilterBadge" style="margin-bottom: 1rem; display: none;">
                <span style="background: #e3f2fd; color: #1565c0; padding: 4px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                    <i class="fas fa-filter"></i> Filtered results
                </span>
            </div>

            <!-- Blog List -->
            <div id="blog-loading">
                <div class="spinner"></div>
                <p>Loading blogs...</p>
            </div>

            <div id="blog-results">
                @include('blogs.partials.blog-list', ['blogs' => $blogs])
            </div>

            <div class="pagination-wrap" id="pagination-wrap">
                {{ $blogs->links() }}
            </div>
        </main>

        <!-- SIDEBAR -->
        <aside class="sidebar">

            <!-- Categories -->
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <i class="fas fa-th-large"></i> Categories
                </div>
                <div class="sidebar-card-body">
                    <div class="category-list">
                        <span class="category-tag active" data-id="">
                            All
                        </span>
                        @foreach($categories as $cat)
                            <span class="category-tag" data-id="{{ $cat->id }}">
                                {{ $cat->name }}
                                <small style="opacity:0.7">({{ $cat->blogs_count }})</small>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- WhatsApp CTA -->
            <div class="sidebar-card" style="background: linear-gradient(135deg, #25D366, #128C7E); border: none;">
                <div class="sidebar-card-body" style="color: white; text-align: center; padding: 1.5rem;">
                    <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <p style="font-family: 'Sora',sans-serif; font-weight: 700; font-size: 1.1rem; margin-bottom: 0.25rem;">WhatsApp</p>
                    <p style="font-size: 0.8rem; opacity: 0.9; margin-bottom: 1rem;">Get daily alerts and PDF notifications instantly</p>
                    <div style="display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
                        <span style="background: rgba(255,255,255,0.2); padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">Fast Updates</span>
                        <span style="background: rgba(255,255,255,0.2); padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">No Spam</span>
                        <span style="background: rgba(255,255,255,0.2); padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">50K+ Aspirants</span>
                    </div>
                    <a href="https://wa.me/" style="background: white; color: #128C7E; padding: 0.6rem 1.5rem; border-radius: 25px; font-weight: 700; font-size: 0.875rem; display: inline-block;">
                        Follow Now
                    </a>
                </div>
            </div>

        </aside>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    let activeCategoryId = '';
    let searchTimer;

    // Category click filter (AJAX)
    $(document).on('click', '.category-tag', function () {
        $('.category-tag').removeClass('active');
        $(this).addClass('active');
        activeCategoryId = $(this).data('id');
        fetchBlogs();
    });

    // Search with debounce
    $('#searchInput').on('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(fetchBlogs, 400);
    });

    $('#searchBtn').on('click', fetchBlogs);

    // Date filter
    $('#dateFilter').on('change', function () {
        fetchBlogs();
    });

    // Clear filters
    $('#clearFilters').on('click', function () {
        activeCategoryId = '';
        $('#searchInput').val('');
        $('#dateFilter').val('');
        $('.category-tag').removeClass('active');
        $('.category-tag[data-id=""]').addClass('active');
        fetchBlogs();
    });

    // Pagination (AJAX)
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        const page = new URL($(this).attr('href')).searchParams.get('page');
        fetchBlogs(page);
    });

    function fetchBlogs(page) {
        $('#blog-loading').show();
        $('#blog-results').css('opacity', 0.4);

        const params = {
            category_id: activeCategoryId,
            search: $('#searchInput').val(),
            date: $('#dateFilter').val(),
        };

        if (page) params.page = page;

        const hasFilters = params.category_id || params.search || params.date;
        $('#activeFilterBadge').toggle(!!hasFilters);

        $.ajax({
            url: '{{ route("blogs.index") }}',
            method: 'GET',
            data: params,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                $('#blog-results').html(response.html).css('opacity', 1);
                $('#pagination-wrap').html(response.pagination);
                $('#blog-loading').hide();
                $('html, body').animate({ scrollTop: $('#blog-results').offset().top - 100 }, 300);
            },
            error: function () {
                $('#blog-loading').hide();
                $('#blog-results').css('opacity', 1);
            }
        });
    }
});
</script>
@endpush
