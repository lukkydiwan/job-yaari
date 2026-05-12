<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JobYaari – Jobs & Recruitment Blogs')</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --blue: #1565C0;
            --blue-light: #1976D2;
            --blue-vivid: #2196F3;
            --blue-pale: #E3F2FD;
            --white: #fff;
            --gray-50: #F8FAFC;
            --gray-100: #F1F5F9;
            --gray-200: #E2E8F0;
            --gray-400: #94A3B8;
            --gray-600: #475569;
            --gray-800: #1E293B;
            --radius: 10px;
            --shadow: 0 2px 12px rgba(21,101,192,0.08);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--gray-800);
            background: var(--gray-50);
            line-height: 1.65;
        }

        h1,h2,h3,h4,h5 { font-family: 'Sora', sans-serif; }

        a { color: inherit; text-decoration: none; }

        /* NAVBAR */
        .navbar {
            background: var(--blue);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 62px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-family: 'Sora', sans-serif;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .nav-brand .logo-box {
            width: 36px; height: 36px;
            background: white;
            border-radius: 8px;
            display: grid; place-items: center;
            font-weight: 800;
            color: var(--blue);
            font-size: 0.9rem;
        }

        .nav-links {
            display: flex;
            gap: 1.75rem;
            list-style: none;
        }

        .nav-links a {
            color: rgba(255,255,255,0.88);
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.2s;
            padding: 4px 0;
            border-bottom: 2px solid transparent;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: white;
            border-bottom-color: rgba(255,255,255,0.6);
        }

        .nav-whatsapp {
            width: 36px; height: 36px;
            background: #25D366;
            border-radius: 50%;
            display: grid; place-items: center;
            color: white;
            font-size: 1.1rem;
            transition: transform 0.2s;
        }

        .nav-whatsapp:hover { transform: scale(1.1); }

        .hamburger {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.4rem;
            cursor: pointer;
        }

        /* HERO BANNER */
        .page-banner {
            background: linear-gradient(135deg, var(--blue) 0%, var(--blue-vivid) 100%);
            padding: 2.5rem 2rem;
            text-align: center;
            color: white;
        }

        .page-banner h1 { font-size: 2rem; margin-bottom: 0.5rem; }

        .breadcrumb {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: rgba(255,255,255,0.75);
        }

        .breadcrumb a:hover { color: white; }

        /* LAYOUT */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }

        .main-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 2rem;
            padding: 2rem 0 3rem;
        }

        /* SEARCH BAR */
        .search-bar {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .search-bar input {
            flex: 1;
            padding: 0.65rem 1rem;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius);
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s;
            background: white;
        }

        .search-bar input:focus { border-color: var(--blue-vivid); }

        .search-bar button {
            padding: 0.65rem 1.25rem;
            background: var(--blue);
            color: white;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.2s;
        }

        .search-bar button:hover { background: var(--blue-light); }

        /* DATE FILTER */
        .date-filter {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .date-filter label { font-size: 0.875rem; color: var(--gray-600); font-weight: 500; }

        .date-filter input[type="date"] {
            padding: 0.55rem 0.85rem;
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius);
            font-size: 0.875rem;
            background: white;
            outline: none;
            cursor: pointer;
        }

        .date-filter input[type="date"]:focus { border-color: var(--blue-vivid); }

        .btn-clear-filters {
            padding: 0.5rem 1rem;
            background: var(--gray-100);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius);
            font-size: 0.8rem;
            cursor: pointer;
            color: var(--gray-600);
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-clear-filters:hover { background: var(--gray-200); }

        /* BLOG CARD */
        .blog-grid {
            display: grid;
            gap: 1.5rem;
        }

        .blog-card {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            display: flex;
            gap: 0;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid var(--gray-200);
        }

        .blog-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(21,101,192,0.12);
        }

        .blog-card-img {
            width: 220px;
            min-height: 180px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .blog-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .blog-card-img .no-img {
            width: 100%;
            height: 100%;
            background: var(--blue-pale);
            display: grid;
            place-items: center;
            color: var(--blue);
            font-size: 2.5rem;
        }

        .blog-card-body {
            padding: 1.25rem 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .blog-meta {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            font-size: 0.78rem;
            color: var(--gray-400);
            flex-wrap: wrap;
        }

        .blog-category-badge {
            background: var(--blue-pale);
            color: var(--blue);
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .blog-card h2 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--gray-800);
            line-height: 1.4;
        }

        .blog-card h2 a:hover { color: var(--blue); }

        .blog-excerpt {
            font-size: 0.875rem;
            color: var(--gray-600);
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn-read-more {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: auto;
            padding: 0.5rem 1.25rem;
            background: var(--blue);
            color: white;
            border-radius: 8px;
            font-size: 0.825rem;
            font-weight: 600;
            width: fit-content;
            transition: background 0.2s;
        }

        .btn-read-more:hover { background: var(--blue-light); }

        /* SIDEBAR */
        .sidebar { display: flex; flex-direction: column; gap: 1.5rem; }

        .sidebar-card {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .sidebar-card-header {
            background: var(--blue);
            color: white;
            padding: 0.75rem 1.25rem;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 0.925rem;
        }

        .sidebar-card-body { padding: 1rem 1.25rem; }

        /* CATEGORIES FILTER */
        .category-list { display: flex; flex-wrap: wrap; gap: 0.5rem; }

        .category-tag {
            padding: 5px 14px;
            background: var(--gray-100);
            border: 1.5px solid var(--gray-200);
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--gray-600);
        }

        .category-tag:hover,
        .category-tag.active {
            background: var(--blue);
            border-color: var(--blue);
            color: white;
        }

        /* PAGINATION */
        .pagination-wrap {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        .pagination-wrap .pagination { display: flex; gap: 0.35rem; list-style: none; }
        .pagination-wrap .page-item .page-link {
            padding: 0.45rem 0.85rem;
            border: 1.5px solid var(--gray-200);
            border-radius: 8px;
            font-size: 0.85rem;
            color: var(--gray-600);
            background: white;
            transition: all 0.2s;
            display: block;
        }
        .pagination-wrap .page-item.active .page-link,
        .pagination-wrap .page-item .page-link:hover {
            background: var(--blue);
            border-color: var(--blue);
            color: white;
        }
        .pagination-wrap .page-item.disabled .page-link { opacity: 0.5; pointer-events: none; }

        /* LOADING SPINNER */
        #blog-loading {
            display: none;
            text-align: center;
            padding: 3rem 0;
            color: var(--blue);
        }

        .spinner {
            width: 36px; height: 36px;
            border: 3px solid var(--blue-pale);
            border-top-color: var(--blue);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* FOOTER */
        footer {
            background: var(--gray-800);
            color: rgba(255,255,255,0.65);
            text-align: center;
            padding: 1.5rem;
            font-size: 0.825rem;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .main-grid { grid-template-columns: 1fr; }
            .sidebar { order: -1; }
        }

        @media (max-width: 640px) {
            .nav-links { display: none; flex-direction: column; position: absolute; top: 62px; left: 0; right: 0; background: var(--blue); padding: 1rem; }
            .nav-links.open { display: flex; }
            .hamburger { display: block; }
            .blog-card { flex-direction: column; }
            .blog-card-img { width: 100%; height: 180px; }
            .navbar { padding: 0 1rem; }
        }

        /* FLASH MESSAGES */
        .alert {
            padding: 0.85rem 1.25rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    </style>

    @stack('styles')
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="{{ route('home') }}" class="nav-brand">
        <div class="logo-box">JY</div>
        JobYaari
    </a>

    <ul class="nav-links" id="navLinks">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('blogs.index') }}" class="{{ request()->routeIs('blogs.*') ? 'active' : '' }}">Blogs</a></li>
        <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    </ul>

    <div style="display:flex; align-items:center; gap:1rem;">
        <a href="https://wa.me/" class="nav-whatsapp" title="WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
        <button class="hamburger" id="hamburger"><i class="fas fa-bars"></i></button>
    </div>
</nav>

<!-- CONTENT -->
@yield('content')

<footer>
    <p>&copy; {{ date('Y') }} JobYaari. All rights reserved.</p>
</footer>

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    // Hamburger menu
    $('#hamburger').click(function () {
        $('#navLinks').toggleClass('open');
    });
</script>

@stack('scripts')
</body>
</html>
