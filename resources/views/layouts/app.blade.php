<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'e-Appointment System for UTHM Health Centre') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Include jQuery first, then Popper.js, and then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <!-- Add Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

    <!-- Add Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('resources/css/app.css') }}">
    <!-- Add these lines to include Starability CSS and JS -->
    <link rel="stylesheet" href="{{ asset('css/starability-min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="{{ asset('js/starability-all.min.js') }}"></script>
    <!-- Add the SweetAlert2 CDN to your HTML file -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    
</style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ Auth::check() && Auth::user()->is_admin ? route('admin.dashboard') : url('/') }}">
                    <img src="{{ asset('img/uthm.png') }}" alt="UTHM Health Centre" height="50" class="d-inline-block align-top mr-2">
                    <img src="{{ asset('img/ePKU.png') }}" alt="ePKU Logo" height="60" class="d-inline-block align-top">
                    <span class="ml-2" style="font-weight: bold;">{{ config('app.name', 'e-Appointment System for UTHM Health Centre') }}</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    {{ __('Patient') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.login') }}">
                                    {{ __('Admin') }}
                                </a>
                            </li>
                            @endif
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <!-- Regular user logged in, show regular user links -->
                                <a class="dropdown-item" href="{{ route('home') }}" style="color: blue;">
                                    <i class="fas fa-home"></i> Home
                                </a>
                                <a class="dropdown-item" href="{{ route('appointments.create') }}">
                                    <i class="fas fa-book"></i> Book Appointment
                                </a>
                                <a class="dropdown-item" href="{{ route('booking-history') }}">
                                    <i class="fas fa-history"></i> Booking History
                                </a>
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: red;">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="text-center mt-5">
            <div class="container d-flex justify-content-between">
                <!-- Footer for Kampus Parit Raja -->
                <div>
                    <p><strong>PKU Parit Raja</strong></p>
                    <p>Pusat Kesihatan Universiti (Kampus Parit Raja)<br>
                        Universiti Tun Hussein Onn Malaysia<br>
                        86400 Parit Raja, Batu Pahat<br>
                        Johor, Malaysia<br>
                    </p>
                </div>

                <!-- Footer for Kampus Pagoh -->
                <div>
                    <p><strong>PKU Pagoh</strong></p>
                    <p>Pusat Kesihatan Universiti (Kampus Pagoh)<br>
                        Universiti Tun Hussein Onn Malaysia<br>
                        Hab Pendidikan Tinggi Pagoh,<br>
                        KM1, Jalan Panchor,<br>
                        84600 Panchor, Johor, Malaysia</p>
                </div>

                <!-- Contact Us -->
                <div>
                    <p><strong>Contact Us</strong></p>
                    <p>Talian pertanyaan : +607-453 7846 / 019-392 9849<br>
                        Talian Kecemasan: +6019-868 7854<br>
                        Fax: +607-453 6077<br>
                        Email: pku@uthm.edu.my</p>
                </div>
            </div><br>
            <div class="container text-center">
                <br><p>&copy; 2024 Pusat Kesihatan Universiti, UTHM. All rights reserved.</p><br>
            </div>
        </footer>
    </div>
</body>
</html>
<script>
    function changeTextColor() {
        document.getElementById('navbarDropdown').style.color = 'orange';
    }
</script>
