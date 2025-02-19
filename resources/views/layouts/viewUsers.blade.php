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

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <!-- Add these lines to include Starability CSS and JS -->
    <link rel="stylesheet" href="{{ asset('css/starability-min.css') }}">
    <script src="{{ asset('js/starability-all.min.js') }}"></script>


    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #app {
            max-height: 100%;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }
        /* Add this style to your CSS file or style tag */
        .bg-orange {
            background-color: #036EB8;
        }        
    </style>
</head>

<body>
    <div id="viewUsers">
        <main class>
            @yield('content')
        </main>
    </div>
</body>
</html>
