@extends('layouts.app')

@section('content')
<style>
    .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .user-actions-section {
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #007bff;
        color: white;
        border-radius: 10px 10px 0 0;
        text-align: center;
    }

    .card-body {
        padding: 20px;
    }

    .btn-group > .btn {
        flex: 1;
        text-align: center;
    }

    .btn-group > .btn:not(:first-child) {
        margin-left: 5px;
    }

    .btn-group {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        border-radius: 10px;
        padding: 86px;
    }

    .position-relative {
        position: relative;
    }

    .position-absolute {
        position: absolute;
    }

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .panel-carousel-container {
        display: flex;
        justify-content: center;
        overflow-x: hidden;
        position: relative;
        max-width: 95%;
        margin: 0 auto;
    }

    .panel-carousel {
        display: inline-flex;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
        max-width: 100%;
        border-radius: 20px;
        scroll-behavior: smooth;
    }

    .panel-carousel::-webkit-scrollbar {
        display: none;
    }

    .panel-carousel-item {
        flex: 0 0 auto;
        text-decoration: none;
        color: inherit;
        overflow: hidden;
        transition: width 0.5s ease; 
        background-color: transparent;
        margin-right: 0px; 
        width: 485px;
        height: 300px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .panel-carousel-image {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .panel-carousel-image img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .panel-carousel-text {
        padding: 20px;
        background: #EA5514;
        height: auto;
        text-align: center;
        color:#fff;
    }

    .panel-carousel-title {
        margin-top: 0;
        margin-bottom: 10px;
        font-weight: bold;
        white-space: normal;
        overflow: hidden;
        transition: font-size 0.3s ease;
        font-size: 1rem;
    }

    .panel-carousel-desc {
        overflow: hidden;
        height: 0;
        transition: height 0.3s ease-out;
    }

    .panel-carousel-item:hover .panel-carousel-desc {
        height: fit-content;
    }

    .panel-carousel-item:hover .panel-carousel-title {
        font-size: 1.5rem;
    }
    
    .carousel-button-right {
        position: absolute;
        top: 125px; 
        right: 5px; 
        z-index: 30; 
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 50px;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        font-size: 1.2rem;
    }

    .carousel-button-right:hover {
        background-color: #fff;
        color: orange;
    }    

    .video-banner {
        position: relative;
        width: 100%;
        height: 370px;
        overflow: hidden;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .video-banner video {
        width: 100%;
        height: 120%;
        object-fit: cover;
    }

    .video-banner .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 2rem;
        font-weight: bold;
    }

    .announcement-container {
        position: absolute;
        bottom: 40px;
        right: 20px;
        background-color: rgba(255, 255, 255, 0.8);
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 470px;
        max-height: 250px;
        overflow-y: auto;
        z-index: 20; 
    }

    .announcement-title {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }

    .announcement-content {
        font-size: 1rem;
        color: #666;
    }

    .announcement-divider {
        margin: 10px 0;
        border-top: 1px solid #ccc;
    }
</style>
<div class="container-top">
    <div>
        @guest
        <div class="text-center">
            <div>
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-block">Login to Book Appointment</a>
            </div>
        </div>
        @else
        <div class="col-md-10 text-center mx-auto">
            <br><h3 class="text-primary"><strong>Welcome, {{ Auth::user()->name }}!</strong></h3><br>
        @endguest
        </div>
    </div>
</div>

<!-- Video Banner -->
<div class="video-banner">
    <video autoplay loop muted playsinline>
        <source src="vid/main.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="announcement-container">
        <div class="announcement-title text-primary text-center"><h3><strong>Announcements</strong></h3></div>
        @forelse ($announcements as $announcement)
            @if ($announcement->visible)
                <div class="announcement-content">
                    <h5><strong>{{ $announcement->title }}</strong></h5>
                    <p>{!! nl2br(e($announcement->content)) !!}</p>
                    @if (!$loop->last)
                        <div class="announcement-divider"></div>
                    @endif
                </div>
            @endif
        @empty
            <p class="announcement-content">No announcements available.</p>
        @endforelse
    </div>
</div>

<div class="container-top">
    <div>
        @guest
        <div class="text-center">
            <div>
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-block">Login to Book Appointment</a>
            </div>
        </div>
        @else
        <div class="col-md-10 text-center">
        <div class="mt-4 btn-group w-100 d-flex justify-content-center">
            <a href="{{ route('profile') }}" class="btn btn-lg flex-fill" style="background-color: #6d95ea; color: white;">
                <i class="fas fa-user"></i> View Profile
            </a>
                <a href="{{ route('appointments.create') }}" class="btn btn-lg flex-fill" style="background-color: #EA5514; color: white;">
                <i class="fas fa-book"></i> Book Appointment
            </a>
            <a href="{{ route('booking-history') }}" class="btn btn-lg flex-fill" style="background-color: #6d95ea; color: white;">
                <i class="fas fa-history"></i> Booking History
            </a>
        </div>
        @endguest
        </div>
    </div>
</div><br><br>

<!-- Panel Carousel -->
<div class="panel-carousel-container">
    <button class="carousel-button-right" onclick="scrollCarousel()">&gt;</button>
    <div class="panel-carousel">
        <a href="#" class="panel-carousel-item">
            <div class="panel-carousel-image"><img src="img/login.jpg" alt="First Time Login"></div>
            <div class="panel-carousel-text">
                <h3 class="panel-carousel-title">First-Time Login?</h3>
                <div class="panel-carousel-desc">
                <p>📌 Username: Matric Number / Staff ID<br>
                📌 Password: Your IC</p>
                </div>
                <div class="panel-carousel-btn"></div>
            </div>
        </a>
        <a href="#" class="panel-carousel-item">
            <div class="panel-carousel-image"><img src="img/medical_hrs_latest.png" alt="Medical Operating Hours"></div>
            <div class="panel-carousel-text">
                <h3 class="panel-carousel-title">Medical Operating Hours</h3>
                <div class="panel-carousel-desc">
                <p>📌 Sunday - Wednesday: 8.00 AM - 7.30 PM<br>
                📌 Thursday: 8.00 AM - 6.00 PM</p>            
                </div>
                <div class="panel-carousel-btn"></div>
            </div>
        </a>
        <a href="#" class="panel-carousel-item">
            <div class="panel-carousel-image"><img src="img/dental_hrs_latest.png" alt="Dental Operating Hours"></div>
            <div class="panel-carousel-text">
                <h3 class="panel-carousel-title">Dental Operating Hours</h3>
                <div class="panel-carousel-desc">
                <p>📌 Sunday - Wednesday: 8.00 AM - 5.00 PM<br>
                📌 Thursday: 8.00 AM - 3.30 PM</p>
                </div>
                <div class="panel-carousel-btn"></div>
            </div>
        </a>
        <a href="#" class="panel-carousel-item">
            <div class="panel-carousel-image"><img src="img/emergency_latest.png" alt="Emergency Cases"></div>
            <div class="panel-carousel-text">
                <h3 class="panel-carousel-title">Emergency Cases</h3>
                <div class="panel-carousel-btn"></div>
            </div>
        </a>
        <a href="#" class="panel-carousel-item">
            <div class="panel-carousel-image"><img src="img/kecemasan_latest.png" alt="Kes Kecemasan"></div>
            <div class="panel-carousel-text">
                <h3 class="panel-carousel-title">Kes Kecemasan</h3>
                <div class="panel-carousel-btn"></div>
            </div>
        </a>
        <a href="#" class="panel-carousel-item">
            <div class="panel-carousel-image"><img src="img/hubungi_kami.png" alt="Hubungi Kami"></div>
            <div class="panel-carousel-text">
                <h3 class="panel-carousel-title">Contact Us</h3>
                <div class="panel-carousel-btn"></div>
            </div>
        </a>
        <a href="#" class="panel-carousel-item">
            <div class="panel-carousel-image"><img src="img/mask.jpg" alt="Reminders"></div>
            <div class="panel-carousel-text">
                <h3 class="panel-carousel-title">Reminders</h3>
                <div class="panel-carousel-desc">
                <p>📌 Bring along your Matric Card/Staff card<br>
                📌 Wear a face mask</p>
                </div>
                <div class="panel-carousel-btn"></div>
            </div>
        </a>
    </div>
</div>

<!-- Add Swiper CSS -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

<!-- Add Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
    // Check visibility state of announcements
    $(document).ready(function() {
        $('#announcement-section .announcement').each(function() {
            var announcementId = $(this).data('announcement-id');
            var isVisible = localStorage.getItem('announcement_' + announcementId);

            if (isVisible === 'false') {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    function scrollCarousel() {
        const carousel = document.querySelector('.panel-carousel');
        const itemWidth = carousel.querySelector('.panel-carousel-item').offsetWidth;
        const maxScrollLeft = carousel.scrollWidth - carousel.clientWidth;
        
        if (carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth) {
            // If already at the end, scroll back to the beginning
            carousel.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            // Otherwise, scroll one item width to the right
            carousel.scrollBy({ left: itemWidth, behavior: 'smooth' });
        }
    }

    // Enhance hover effect
    document.addEventListener("DOMContentLoaded", function() {
        const items = document.querySelectorAll(".panel-carousel-item");

        items.forEach(function(item) {
            item.addEventListener("mouseover", function() {
                items.forEach(function(otherItem) {
                    if (otherItem !== item) {
                        otherItem.style.transform = "scale(0.95)";
                        otherItem.style.opacity = "0.5";
                    }
                });
                item.style.transform = "scale(1)";
                item.style.opacity = "1";
            });

            item.addEventListener("mouseout", function() {
                items.forEach(function(otherItem) {
                    otherItem.style.transform = "scale(1)";
                    otherItem.style.opacity = "1";
                });
            });
        });
    });
</script>

@endsection
