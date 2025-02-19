<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand me-lg-5 me-0" href="{{ route('home') }}">
            <img src="{{ asset('icons/maskot.png') }}" class="logo-image img-fluid" alt="templatemo pod talk">
        </a>

        <form action="#" method="get" class="custom-form search-form flex-fill me-3" role="search">
            <div class="input-group input-group-lg">
                <!-- <input name="search" type="search" class="form-control" id="search" placeholder="Search"
                    aria-label="Search"> -->

                <!-- <button type="submit" class="form-control" id="submit">
                    <i class="bi-search"></i>
                </button> -->
            </div>
        </form>

        <!-- Hamburger Menu Icon (3 stripes) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Menu -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-lg-auto">

                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('lapor.index') ? 'active' : '' }}" href="{{ route('lapor.index') }}">Laporan</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('tentang') ? 'active' : '' }}" href="{{ route('tentang') }}">Tentang REMI</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('kontak') ? 'active' : '' }}" href="{{ route('kontak') }}">Kontak</a>
                </li>

                @auth
                <!-- Dropdown for logged-in user with Bootstrap styling -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle btn btn-primary smoothscroll text-white px-3 py-2 rounded-pill" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        @if (Auth::user()->role === 'teknisi' && Str::lower(optional(Auth::user()->zoneUser)->zone_name) === 'dkv')
                            <li>
                                <a class="dropdown-item" href="{{ url('/dkv') }}">
                                    Dashboard
                                </a>
                            </li>
                        @elseif (Auth::user()->role === 'teknisi' && Str::lower(optional(Auth::user()->zoneUser)->zone_name) === 'sija')
                            <li>
                                <a class="dropdown-item" href="{{ url('/sija') }}">
                                    Dashboard
                                </a>
                            </li>
                        @elseif (Auth::user()->role === 'teknisi' && Str::lower(optional(Auth::user()->zoneUser)->zone_name) === 'sarpras')
                            <li>
                                <a class="dropdown-item" href="{{ url('/sarpras') }}">
                                    Dashboard
                                </a>
                            </li>
                        @elseif (Auth::user()->role === 'admin')
                            <li>
                                <a class="dropdown-item" href="{{ url('/sija') }}">
                                    Dashboard
                                </a>
                            </li>
                        @endif
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Log Out
                            </a>
                        </li>
                    </ul>
                    <!-- Hidden Logout Form -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                </li>
                @else
                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link">Log In</a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
