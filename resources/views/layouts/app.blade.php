<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | @yield('title')</title>

    <!-- Fonts -->
    <!-- fontawesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body style="background-color:#f4f2f0">
    <div id="app">
        @if (!View::hasSection('hideNavbar'))
            <nav class="navbar navbar-expand-lg shadow-sm"
                style="background: linear-gradient(90deg, #6F6358, #B4AA9A);">
                <div class="container">
                    <!-- Brand -->
                    <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                        {{-- <i class="fa-solid fa-book-open-reader me-2" style="color:#F5F5F5;"></i> --}}
                        <span class="brand-text">{{ config('app.name') }}</span>
                    </a>

                    <!-- Toggler -->
                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Links -->
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side -->
                        {{-- @auth
                            @if (!request()->is('admin/*'))
                                <form action="{{ route('search') }}" class="ms-lg-4 my-2 my-lg-0" style="width:280px;">
                                    <input type="search" name="search" class="form-control rounded-pill form-control-sm"
                                        placeholder="🔍 Search...">
                                </form>
                            @endif
                        @endauth --}}

                        <!-- Right Side -->
                        <ul class="navbar-nav ms-auto align-items-center">
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item me-2">
                                        <a class="btn btn-outline-light btn-sm px-3" href="{{ route('login') }}">Login</a>
                                    </li>
                                @endif

                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="btn btn-light btn-sm px-3 fw-semibold"
                                            href="{{ route('register') }}">Register</a>
                                    </li>
                                @endif
                            @else
                                <!-- Category Search -->
                                <li class="nav-item">
                                    <a href="{{ route('categories.search') }}" class="nav-link text-light">
                                        <i class="fas fa-globe icon-sm"></i>
                                    </a>
                                </li>

                                {{-- Search --}}
                                <li class="nav-item dropdown" title="Search">
                                    <button class="btn shadow-none nav-link text-light" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-magnifying-glass icon-sm"></i>
                                    </button>
                                    <div class="dropdown-menu p-3 shadow border-0 rounded-3" style="min-width: 200px;">
                                        <form action="{{ route('search') }}" method="GET" class="d-flex">
                                            <input type="search" name="search"
                                                class="form-control form-control-sm me-2 rounded-pill shadow-sm"
                                                placeholder="Search..."
                                                style="flex: 1; border: 1px solid #ccc; background-color: #fafafa;">
                                            <button type="submit" class="btn btn-sm rounded-pill px-2"
                                                style="background-color: #6F6358; color: #fff;">
                                                <i class="fa-solid fa-arrow-right"></i>
                                            </button>
                                        </form>
                                    </div>
                                </li>


                                <!-- Home -->
                                <li class="nav-item">
                                    <a href="{{ route('index') }}" class="nav-link text-light">
                                        <i class="fa-solid fa-house icon-sm"></i>
                                    </a>
                                </li>

                                <!-- Create Post -->
                                <li class="nav-item">
                                    <a href="{{ route('post.create') }}" class="nav-link text-light">
                                        <i class="fa-solid fa-circle-plus icon-sm"></i>
                                    </a>
                                </li>

                                <!-- User Dropdown -->
                                <li class="nav-item dropdown">
                                    <button class="btn shadow-none nav-link text-light d-flex align-items-center"
                                        id="account-dropdown" data-bs-toggle="dropdown">
                                        @if (Auth::user()->avatar)
                                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}"
                                                class="rounded-circle avatar-sm">
                                        @else
                                            <i class="fa-solid fa-circle-user fs-5"></i>
                                        @endif
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2"
                                        aria-labelledby="account-dropdown">
                                        @can('admin')
                                            <a href="{{ route('admin.users') }}" class="dropdown-item">
                                                <i class="fa-solid fa-user-gear me-2"></i> Admin
                                            </a>
                                            <hr class="dropdown-divider">
                                        @endcan

                                        <a href="{{ route('profile.show', Auth::user()->id) }}" class="dropdown-item">
                                            <i class="fa-solid fa-user me-2"></i> Profile
                                        </a>

                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                                            <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        @endif

        <main class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    {{-- [SOON] Admin Controls --}}
                    @if (request()->is('admin/*'))
                        <div class="col-3">
                            <div class="list-group">
                                <a href="{{ route('admin.users') }}"
                                    class="list-group-item {{ request()->is('admin/users') ? 'active' : '' }}">
                                    <i class="fa-solid fa-users"></i> Users
                                </a>
                                <a href="{{ route('admin.posts') }}"
                                    class="list-group-item {{ request()->is('admin/posts') ? 'active' : '' }}">
                                    <i class="fa-solid fa-newspaper"></i> Posts
                                </a>
                                <a href="{{ route('admin.categories') }}"
                                    class="list-group-item {{ request()->is('admin/categories') ? 'active' : '' }}">
                                    <i class="fa-solid fa-tags"></i> Categories
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="col-9">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchDropdown = document.querySelector('[title="Search"]');
            if (searchDropdown) {
                searchDropdown.addEventListener('shown.bs.dropdown', function() {
                    const input = searchDropdown.querySelector('input[type="search"]');
                    if (input) input.focus();
                });
            }
        });
    </script>


    @stack('scripts')

</body>

</html>
