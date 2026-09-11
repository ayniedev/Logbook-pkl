<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Google Fonts: Source Sans 3 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- OverlayScrollbars CSS -->
    <link href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" rel="stylesheet">

    <!-- AdminLTE CSS -->
    <link href="{{ asset('adminlte/css/adminlte.min.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        {{-- Navbar --}}
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <i class="bi bi-house-door me-1"></i>
                            Dashboard
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <!-- User Menu -->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ asset('adminlte/img/user2-160x160.jpg') }}" class="user-image rounded-circle shadow" alt="User Image">
                            <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Admin' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary">
                                <img src="{{ asset('adminlte/img/user2-160x160.jpg') }}" class="rounded-circle shadow" alt="User Image">
                                <p>
                                    {{ Auth::user()->name ?? 'Admin' }}
                                    <small>{{ Auth::user()->email ?? '' }}</small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Profile</a>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline float-end">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">Sign out</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        {{-- Sidebar --}}
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="{{ route('admin.dashboard') }}" class="brand-link">
                    <img src="{{ asset('adminlte/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow">
                    <span class="brand-text fw-light">Admin Panel</span>
                </a>
            </div>

            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false" id="navigation">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-people-fill"></i>
                                <p>Manajemen User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.divisions.index') }}" class="nav-link {{ request()->routeIs('admin.divisions.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-diagram-3-fill"></i>
                                <p>Manajemen Divisi</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.mentors.index') }}" class="nav-link {{ request()->routeIs('admin.mentors.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-person-badge-fill"></i>
                                <p>Manajemen Pembimbing</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.assignments.index') }}" class="nav-link {{ request()->routeIs('admin.assignments.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-clipboard2-pulse-fill"></i>
                                <p>Penempatan PKL</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="mb-0 fs-3">@yield('page-title', 'Dashboard')</h1>
                        </div>
                        <div class="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb float-sm-end">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                    @yield('breadcrumb')
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">
                <strong>{{ config('app.name', 'Laravel') }}</strong>
            </div>
            <strong>
                Copyright &copy; {{ date('Y') }}&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
            </strong>
            All rights reserved.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- OverlayScrollbars JS -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"></script>

    <!-- AdminLTE JS -->
    <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>

    <!-- OverlayScrollbars Configure -->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            const isMobile = window.innerWidth <= 992;

            if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined && !isMobile) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
