<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard | LaraBids')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">

    @stack('styles')
</head>

<body>

    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar-elite p-4" style="width: 280px;">
            <div class="text-center mb-5">
                <a href="{{ route('home') }}">
                    <span class="gold-text display-font fw-bold fs-3">LaraBids</span>
                </a>
            </div>

            <nav>
                <a href="{{ route('user.dashboard') }}" 
                   class="sidebar-link rounded mb-2 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large me-3"></i> Overview
                </a>
                <a href="{{ route('user.my-bids') }}" 
                   class="sidebar-link rounded mb-2 {{ request()->routeIs('user.my-bids') ? 'active' : '' }}">
                    <i class="fas fa-gavel me-3"></i> My Active Bids
                </a>
                <a href="{{ route('user.winning-items') }}" 
                   class="sidebar-link rounded mb-2 {{ request()->routeIs('user.winning-items') ? 'active' : '' }}">
                    <i class="fas fa-trophy me-3"></i> Won Items
                </a>
                <a href="{{ route('user.wishlist') }}" 
                   class="sidebar-link rounded mb-2 {{ request()->routeIs('user.wishlist') ? 'active' : '' }}">
                    <i class="fas fa-heart me-3"></i> Watchlist
                </a>
                <a href="{{ route('user.profile') }}" 
                   class="sidebar-link rounded mb-2 {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                    <i class="fas fa-user-edit me-3"></i> Profile Settings
                </a>
                <div class="mt-5 pt-5">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-link rounded text-danger opacity-75 w-100 border-0 bg-transparent text-start">
                            <i class="fas fa-sign-out-alt me-3"></i> Secure Logout
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay"></div>

        <!-- Sidebar Toggle Button -->
        <button class="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Main Content -->
        <main class="flex-grow-1"
            style="background: radial-gradient(at top left, #0e0e0e 0%, #000000 100%); min-height: 100vh;">

            <div class="p-4 p-lg-5">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>
