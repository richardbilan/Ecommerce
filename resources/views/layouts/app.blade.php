<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Link to Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/login_style.css') }}">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">

                    <!-- Sidebar Button -->
                    <button class="menu-button" onclick="toggleSidebar(event)">☰</button>
                    
                    <img src="{{ asset('/images/cart.png') }}" alt="Beyouuuuu Brew Cafe" class="navbar-logo">
                </a>

                <!-- Display Username in Navbar (for logged-in users) -->
                <div class="navbar-nav ms-auto">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <!-- Display Username -->
                        <li class="nav-item">
                            <span class="nav-link">Welcome, {{ Auth::user()->name }}</span>
                        </li>
                        <!-- Logout link -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                        </li>
                    @endguest
                </div>
            </div>
        </nav>

        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            <ul class="sidebar-nav">
                <li><a href="{{ url('/') }}">Home</a></li>
                @guest
                    @if (Route::has('login'))
                        <li><a href="{{ route('login') }}">Login</a></li>
                    @endif

                    @if (Route::has('register'))
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @endif
                @else
                    <!-- Display Username in Sidebar -->
                    <li><span>Welcome, {{ Auth::user()->name }}</span></li>
                    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
                @endguest
            </ul>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script>
        // Toggle Sidebar
        function toggleSidebar(event) {
            event.preventDefault(); // Prevent page reload
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        // Close sidebar when mouse moves outside
        document.getElementById('sidebar').addEventListener('mouseleave', function() {
            this.classList.remove('open');
        });
    </script>
</body>
</html>
