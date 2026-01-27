<nav class="navbar navbar-expand-lg navbar-elite sticky-top py-3">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <span class="gold-text display-font fw-bold fs-3">LaraBids</span>
        </a>
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item px-3">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link {{ request()->routeIs('auctions.*') ? 'active' : '' }}" href="{{ route('auctions.index') }}">Auctions</a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                </li>
                <li class="nav-item px-3">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
            <div class="d-flex gap-3 align-items-center">
                <!-- Search Bar -->
                <form action="{{ route('auctions.index') }}" method="GET" class="nav-search d-none d-lg-block me-3">
                    <i class="fas fa-search"></i>
                    <input type="text" name="q" class="form-control" placeholder="Search auctions..." value="{{ request('q') }}">
                </form>

                @guest
                    <!-- Guest State -->
                    <div class="d-none d-sm-flex gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-gold px-4 btn-sm">Sign In</a>
                        <a href="{{ route('register') }}" class="btn btn-gold px-4 btn-sm text-dark fw-bold">Join Now</a>
                    </div>
                @else
                    <!-- Member State -->
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-white"
                            data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=d4af37&color=0a192f"
                                class="rounded-circle border border-gold border-opacity-25 me-2" height="35" alt="Avatar">
                            <span class="small d-none d-md-inline">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end glass-panel border-gold border-opacity-25">
                            <li>
                                <a class="dropdown-item text-white small" href="{{ route('user.dashboard') }}">
                                    <i class="fas fa-th-large me-2 text-gold"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-white small" href="{{ route('user.profile') }}">
                                    <i class="fas fa-user-edit me-2 text-gold"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-white small" href="{{ route('user.wishlist') }}">
                                    <i class="fas fa-heart me-2 text-gold"></i> Watchlist
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider border-white border-opacity-10">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger small">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>
