<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') – JobYaari</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --blue: #1565C0;
            --blue-light: #1976D2;
            --blue-pale: #E3F2FD;
            --sidebar-w: 220px;
            --gray-50: #F8FAFC;
            --gray-100: #F1F5F9;
            --gray-200: #E2E8F0;
            --gray-600: #475569;
            --gray-800: #1E293B;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--gray-50); color: var(--gray-800); display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .admin-sidebar {
            width: var(--sidebar-w);
            background: var(--gray-800);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 50;
        }

        .sidebar-logo {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-logo .logo-box {
            width: 32px; height: 32px;
            background: var(--blue);
            border-radius: 8px;
            display: grid; place-items: center;
            color: white; font-weight: 800;
            font-family: 'Sora', sans-serif;
            font-size: 0.85rem;
        }

        .sidebar-logo span {
            color: white;
            font-family: 'Sora', sans-serif;
            font-weight: 700;
            font-size: 1rem;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1rem 0;
            overflow-y: auto;
        }

        .sidebar-section-label {
            font-size: 0.68rem;
            font-weight: 600;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 0.75rem 1.5rem 0.35rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.6rem 1.5rem;
            color: rgba(255,255,255,0.65);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            border-right: 3px solid transparent;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            color: white;
            background: rgba(255,255,255,0.06);
            border-right-color: var(--blue);
        }

        .sidebar-link i { width: 18px; text-align: center; opacity: 0.7; }
        .sidebar-link:hover i, .sidebar-link.active i { opacity: 1; }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        /* MAIN CONTENT */
        .admin-main {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* TOP BAR */
        .admin-topbar {
            background: white;
            padding: 0 2rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--gray-200);
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-title {
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            color: var(--gray-800);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-avatar {
            width: 34px; height: 34px;
            background: var(--blue);
            border-radius: 50%;
            display: grid; place-items: center;
            color: white;
            font-weight: 600;
            font-size: 0.825rem;
        }

        /* CONTENT AREA */
        .admin-content {
            padding: 2rem;
            flex: 1;
        }

        /* CARDS */
        .card {
            background: white;
            border-radius: 10px;
            border: 1px solid var(--gray-200);
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h2 {
            font-family: 'Sora', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-800);
        }

        .card-body { padding: 1.5rem; }

        /* BUTTONS */
        .btn {
            padding: 0.5rem 1.1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-primary { background: var(--blue); color: white; }
        .btn-primary:hover { background: var(--blue-light); }
        .btn-secondary { background: var(--gray-100); color: var(--gray-600); border: 1px solid var(--gray-200); }
        .btn-secondary:hover { background: var(--gray-200); }
        .btn-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .btn-danger:hover { background: #fecaca; }
        .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.8rem; }

        /* FORM */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.45rem; color: var(--gray-600); }
        .form-label .required { color: #e24b4a; margin-left: 2px; }
        .form-control {
            width: 100%;
            padding: 0.6rem 0.9rem;
            border: 1.5px solid var(--gray-200);
            border-radius: 8px;
            font-size: 0.875rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s;
            background: white;
        }
        .form-control:focus { border-color: var(--blue); }
        .form-control.is-invalid { border-color: #e24b4a; }
        .invalid-feedback { color: #e24b4a; font-size: 0.78rem; margin-top: 0.3rem; }

        select.form-control { cursor: pointer; }

        /* TABLE */
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 0.75rem 1rem; text-align: left; font-size: 0.875rem; border-bottom: 1px solid var(--gray-200); }
        .table th { color: var(--gray-600); font-weight: 600; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; background: var(--gray-50); }
        .table tr:hover td { background: var(--gray-50); }
        .table img { width: 50px; height: 40px; object-fit: cover; border-radius: 6px; }

        /* ALERTS */
        .alert { padding: 0.85rem 1.25rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem; font-weight: 500; display: flex; align-items: center; gap: 8px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* GRID */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        @media (max-width: 640px) { .grid-2 { grid-template-columns: 1fr; } }

        /* STAT CARDS */
        .stat-card {
            background: white;
            border-radius: 10px;
            border: 1px solid var(--gray-200);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: grid; place-items: center;
            font-size: 1.2rem;
        }
        .stat-icon.blue { background: var(--blue-pale); color: var(--blue); }
        .stat-icon.green { background: #d1fae5; color: #065f46; }

        /* BADGE */
        .badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
        }
        .badge-blue { background: var(--blue-pale); color: var(--blue); }

        /* Tag input */
        .tag-hint { font-size: 0.75rem; color: var(--gray-600); margin-top: 0.3rem; }

        /* Upload area */
        .upload-area {
            border: 2px dashed var(--gray-200);
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--gray-600);
        }
        .upload-area:hover { border-color: var(--blue); background: var(--blue-pale); }
        .upload-area i { font-size: 2rem; margin-bottom: 0.5rem; display: block; color: var(--gray-600); }

        /* Logout form */
        .logout-btn {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.6rem 1.5rem;
            color: rgba(255,255,255,0.55);
            font-size: 0.875rem;
            font-weight: 500;
            transition: color 0.2s;
            font-family: inherit;
        }
        .logout-btn:hover { color: #fca5a5; }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar { transform: translateX(-100%); transition: transform 0.25s; }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }
            .admin-content { padding: 1rem; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-logo">
        <div class="logo-box">JY</div>
        <span>JobYaari</span>
    </div>

    <nav class="sidebar-nav">
        <p class="sidebar-section-label">Main</p>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <p class="sidebar-section-label">Blog</p>
        <a href="{{ route('admin.blogs.index') }}" class="sidebar-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
            <i class="fas fa-newspaper"></i> Manage Blogs
        </a>
        <a href="{{ route('admin.blogs.create') }}" class="sidebar-link {{ request()->routeIs('admin.blogs.create') ? 'active' : '' }}">
            <i class="fas fa-plus-circle"></i> Add Blog
        </a>
        <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Categories
        </a>

        <p class="sidebar-section-label">Site</p>
        <a href="{{ route('blogs.index') }}" class="sidebar-link" target="_blank">
            <i class="fas fa-external-link-alt"></i> View Site
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</aside>

<!-- MAIN -->
<div class="admin-main">
    <header class="admin-topbar">
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <button id="mobileSidebarToggle" style="background:none;border:none;cursor:pointer;font-size:1.2rem;color:#475569;display:none;">
                <i class="fas fa-bars"></i>
            </button>
            <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="topbar-actions">
            <span style="font-size:0.8rem; color:#94a3b8;">{{ Auth::user()->name ?? 'Admin' }}</span>
            <div class="admin-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
        </div>
    </header>

    <main class="admin-content">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-times-circle"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $('#mobileSidebarToggle').click(function () {
        $('#adminSidebar').toggleClass('open');
    });

    // Show mobile toggle on small screens
    if (window.innerWidth <= 768) {
        $('#mobileSidebarToggle').show();
    }
</script>
@stack('scripts')
</body>
</html>
