<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ArnSeavphov') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Load assets using Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @yield('styles')

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #11BBAB;
            --secondary-color: #0D9488;
            --dark-color: #333333;
            --light-color: #F8F9FA;
        }

        body {
            font-family: 'Nunito', sans-serif;
            color: var(--dark-color);
            padding-top: 76px;
            /* For fixed navbar */
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .opacity-10 {
            opacity: 0.1;
        }

        /* Add smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand fw-bold" style="font-size: 25px; color: #11BBAB" href="{{ url('/') }}">
                    <i class="fas fa-book-reader me-2"></i>ArnSeavphov
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" style="font-size: 18px;" href="/">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" style="font-size: 18px;" href="{{ route('books.index') }}">Books</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" style="font-size: 18px;" href="{{ route('authors.index') }}">Authors</a>
                        </li>

                        @auth
                            <?php $role = Auth::user()->role; ?>
                            @if($role == 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" style="font-size: 18px;" href="{!! route('admin.show', Auth::user()->id) !!}">{{__('My Account')}}</a>
                                </li>
                            @endif

                            @if($role == 'user')
                                <li class="nav-item">
                                    <a class="nav-link" style="font-size: 18px;" href="{!! route('user.show', Auth::user()->id) !!}">{{__('My Account')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" style="font-size: 18px;" href="{!! route('user.books', Auth::user()->id) !!}">{{__('My Books')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" style="font-size: 18px;" href="{!! route('user.credits', Auth::user()->id) !!}">{{__('My Credits')}}</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
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
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                 document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                    @endguest
                </ul>
            </div>
        </nav>
    @yield('content')

    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CSRF Error Handler -->
    <script>
        // Listen for AJAX errors and handle 419 Page Expired errors
        document.addEventListener('DOMContentLoaded', function() {
            // Add error handling for fetch API requests
            window.addEventListener('error', function(event) {
                if (event && event.target && event.target.status === 419) {
                    console.error('Session expired (419 error). Refreshing page to get a new CSRF token.');
                    alert('Your session has expired. The page will reload to restore your session.');
                    window.location.reload();
                }
            });

            // Add global CSRF token to all forms
            document.querySelectorAll('form').forEach(form => {
                // Skip forms that already have a CSRF token
                if (!form.querySelector('input[name="_token"]')) {
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                }
            });
        });
    </script>

    @yield('scripts')
</body>

</html>