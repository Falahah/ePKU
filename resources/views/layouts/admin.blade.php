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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('resources/css/admin.css') }}">

    <!-- Add these lines to include Starability CSS and JS -->
    <link rel="stylesheet" href="{{ asset('css/starability-min.css') }}">
    <script src="{{ asset('js/starability-all.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

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
            display: flex;
        }

        .bg {
            background-color: rgba(255, 255, 255, 0.59);
        }

        .dropdown-menu {
            max-height: 500px;
            overflow-y: auto;
            width: 250px;
            left: 0;
            right: auto;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            white-space: normal;
            font-size: 0.875rem;
            padding: 10px 15px; 
        }

        .dropdown-item:hover {
            background-color: rgba(0, 123, 255, 0.1); 
            color: #007bff; 
            border-left: 3px solid #007bff;
        }

        .badge-danger {
            background-color: red;
            color: white;
        }

        .dropdown-divider {
            margin: 0;
        }

        .margin-left {
            margin-left: 150px;
        }
        
        .margin-right {
            margin-right: -100px;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            padding-top: 60px; /* Adjusted to leave space for the button */
            z-index: 1000;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: width 0.3s;
        }

        .sidebar.collapsed {
            width: 0;
            overflow: hidden;
        }

        .sidebar .nav-link {
            color: white;
            padding: 10px 15px;
        }

        .sidebar .nav-link:hover {
            background-color: #007bff;
            color: white;
        }

        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        .sidebar .sidebar-footer {
            padding: 10px 15px;
            background-color: #2c2f33;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
            transition: margin-left 0.3s;
        }

        .content.collapsed {
            margin-left: 0;
        }

        .toggle-btn {
            position: fixed;
            top: 10px;
            left: 15px;
            z-index: 1100;
            background-color: transparent;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding-top: 0;
            }
            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ Auth::check() && Auth::user()->is_admin ? route('admin.dashboard') : url('/') }}">
                    <img src="{{ asset('img/ePKU.png') }}" alt="ePKU Logo" height="30" class="d-inline-block align-top">
                    <span class="ml-2">{{ config('app.name', 'e-Appointment System for UTHM Health Centre') }}</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        @auth
                        <li class="nav-item dropdown">
                            <a id="notificationDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell"></i> <span class="badge badge-pill badge-danger" id="notificationCount" style="display:none;"></span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="notificationDropdown">
                                <div id="notificationList">
                                    <!-- Notifications will be loaded here -->
                                </div>
                            </div>
                        </li>                       
                        @endauth
                        @guest
                            @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.login') }}">
                                    {{ __('Login') }}
                                </a>
                            </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}" style="color: blue;">Admin Dashboard</a>
                                    <a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: red;">
                                        {{ __('Logout') }}
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
        @auth
            @if(Auth::user()->is_admin)
                <button class="toggle-btn" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="sidebar" id="sidebar">
                    <nav class="nav flex-column">
                        <a class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        <a class="nav-link @if(request()->routeIs('admin.manageAppointments')) active @endif" href="{{ route('admin.manageAppointments') }}"><i class="fas fa-calendar-alt"></i> Appointments</a>
                        <a class="nav-link @if(request()->routeIs('admin.manageUsers')) active @endif" href="{{ route('admin.manageUsers') }}"><i class="fas fa-users"></i> Users</a>
                        <a class="nav-link @if(request()->routeIs('admin.manageMedStaff')) active @endif" href="{{ route('admin.manageMedStaff') }}"><i class="fas fa-user-md"></i> Medical Staff</a>
                        <a class="nav-link @if(request()->routeIs('admin.manageMedServices')) active @endif" href="{{ route('admin.manageMedServices') }}"><i class="fas fa-syringe"></i> Medical Services</a>
                        <a class="nav-link @if(request()->routeIs('admin.manageTimeSlots')) active @endif" href="{{ route('admin.manageTimeSlots') }}"><i class="fas fa-clock"></i> Time Slots</a>
                        <a class="nav-link @if(request()->routeIs('admin.manageAnnouncements')) active @endif" href="{{ route('admin.manageAnnouncements') }}"><i class="fas fa-bullhorn"></i> Announcements</a>
                    </nav>
                    <div class="sidebar-footer">
                        <p>PKU UTHM</p>
                    </div>
                </div>
            @endif
        @endauth
            </div>
            <div class="content" id="content">
                <div class="container">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">       
                        <div>
                        @auth
                            <h3 class="fw-bold mb-3">{{ Auth::user()->name }}'s Dashboard</h3>
                            <h6 class="op-7 mb-2">Hello {{ Auth::user()->name }}!</h6>
                        </div>
                        <div class="ms-md-auto py-2 py-md-0">
                            <a href="{{ route('admin.profile') }}" class="btn btn-primary btn-round">My Profile</a>
                        </div>
                        @else
                        <div class="row margin-right">
                            <h2 class="fw-bold text-primary mb-3 margin-left">Welcome to the Admin Page</h2>
                            <h6 class="op-2 mb-2 margin-left">Please log in to access the dashboard.</h6>
                        </div>
                        @endauth                    
                    </div>
                    @yield('content')
                </div>
            </div>
        </main>

        <footer class="footer">
            <div class="container-fluid d-flex justify-content-between">
                <nav class="pull-left">

                </nav>
                <div class="copyright">
                    2024 Pusat Kesihatan Universiti, UTHM. All rights reserved.
                </div>
                <div>

                </div>
            </div>
        </footer>        
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                // Activate the correct sidebar link
                var currentUrl = window.location.href;
                $('.nav-link').each(function() {
                    if (this.href === currentUrl) {
                        $(this).addClass('active');
                    }
                });

                // Toggle sidebar visibility
                document.getElementById('sidebarToggle').addEventListener('click', function() {
                    document.getElementById('sidebar').classList.toggle('collapsed');
                    document.getElementById('content').classList.toggle('collapsed');
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                fetchNotifications();

                function fetchNotifications() {
                    fetch("{{ route('admin.fetchNotifications') }}")
                        .then(response => response.json())
                        .then(data => {
                            console.log('Fetched Notifications:', data); // Log fetched data
                            const notificationList = document.getElementById('notificationList');
                            const notificationCount = document.getElementById('notificationCount');
                            notificationList.innerHTML = '';
                            if (data.length > 0) {
                                notificationCount.style.display = 'inline';
                                notificationCount.textContent = data.length;
                            } else {
                                notificationCount.style.display = 'none';
                            }

                            data.forEach((notification, index) => {
                                console.log('Notification:', notification); // Log each notification
                                const notificationItem = document.createElement('a');
                                notificationItem.className = 'dropdown-item';
                                notificationItem.textContent = notification.data; // Directly accessing the data field
                                notificationItem.href = "{{ url('/admin/booking-details') }}/" + notification.appointment_id; // Directly accessing the appointment_id field
                                notificationItem.addEventListener('click', function (event) {
                                    event.preventDefault(); // Prevent the default action to mark as read first
                                    markAsRead(notification.id, notificationItem.href);
                                });
                                notificationList.appendChild(notificationItem);

                                if (index < data.length > 1) {
                                    const divider = document.createElement('div');
                                    divider.className = 'dropdown-divider';
                                    notificationList.appendChild(divider);
                                }
                            });
                        })
                        .catch(error => console.error('Error fetching notifications:', error));
                }

                function markAsRead(notificationId, redirectUrl) {
                    fetch("{{ route('admin.markNotificationsAsRead') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ id: notificationId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data.message);
                        window.location.href = redirectUrl; // Redirect after marking as read
                    })
                    .catch(error => console.error('Error marking notification as read:', error));
                }

                document.getElementById('notificationDropdown').addEventListener('click', function() {
                    fetchNotifications();
                });
            });
        </script>
    
    </div>
    @yield('scripts')
</body>
</html>
