<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Logbook PKL</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/pembimbing.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="app-wrapper">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <h2>
                    <i class="bi bi-journal-text"></i>
                    Logbook PKL
                </h2>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-label">Menu</div>
                <a href="{{ route('pembimbing.dashboard') }}" class="nav-link {{ request()->routeIs('pembimbing.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('pembimbing.peserta') }}" class="nav-link {{ request()->routeIs('pembimbing.peserta') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Peserta PKL</span>
                </a>
                <a href="{{ route('pembimbing.logbook.index') }}" class="nav-link {{ request()->routeIs('pembimbing.logbook.index', 'pembimbing.logbook.show') ? 'active' : '' }}">
                    <i class="bi bi-journal-bookmark"></i>
                    <span>Logbook</span>
                </a>
                <a href="{{ route('pembimbing.riwayat') }}" class="nav-link {{ request()->routeIs('pembimbing.riwayat', 'pembimbing.logbook.riwayat') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Riwayat</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link">
                        <i class="bi bi-box-arrow-left"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="main-content">
            <header class="header">
                <div class="d-flex align-items-center gap-3">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="header-left">
                        <h1>@yield('page-title', 'Dashboard')</h1>
                        <p>@yield('page-subtitle', '')</p>
                    </div>
                </div>
                <div class="header-right">
                    <div class="dropdown" id="userDropdown">
                        <button class="user-info" onclick="document.getElementById('userDropdown').classList.toggle('active')" style="cursor: pointer;">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
                            <i class="bi bi-chevron-down" style="font-size: 0.75rem; color: var(--text-secondary);"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{ route('profile.show') }}" class="dropdown-item">
                                <i class="bi bi-person"></i>
                                Profile
                            </a>
                            <hr style="margin: 4px 0; border-color: var(--border);">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-left"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <div class="content">
                <div class="container-fluid">
                    @if (session('success'))
                    <div class="flash-pmb flash-success">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="flash-pmb flash-error">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
                </div>
            </div>

            <footer class="footer">
                {{ config('app.name', 'Laravel') }} &copy; {{ date('Y') }}
            </footer>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }

            // User dropdown toggle
            document.addEventListener('click', function(e) {
                const dropdowns = document.querySelectorAll('.dropdown');
                dropdowns.forEach(function(dropdown) {
                    if (!dropdown.contains(e.target)) {
                        dropdown.classList.remove('active');
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
