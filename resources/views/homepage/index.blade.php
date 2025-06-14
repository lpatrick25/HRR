@extends('homepage.base')

@section('APP-TITLE', 'Homepage')

@section('active-homepage', 'active')

@section('custom-css')
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <style>
        body {
            overflow-x: hidden;
            /* Prevents any horizontal scrolling */
        }

        /* Hero Section Improvements */
        .hero__text {
            background: rgba(0, 0, 0, 0.6);
            padding: 25px;
            border-radius: 10px;
            color: white;
            text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
        }


        /* Responsive Hero Slider */
        .hero__slider .owl-item img {
            width: 100%;
            height: 75vh;
            object-fit: cover;
            border-radius: 10px;
        }

        /* Custom Button Styling */
        .btn-dark {
            background: #333;
            border: none;
            padding: 10px 20px;
            transition: 0.3s ease-in-out;
        }

        .btn-dark:hover {
            background: #000;
            transform: translateY(-3px);
        }

        /* Section Titles */
        .section-title h2 {
            font-weight: bold;
        }

        .hr-one {
            margin: 20px auto;
            width: 40%;
            border: 0;
            height: 2px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0), rgb(138, 43, 226), rgba(255, 255, 255, 0));

        }

        .product__item__pic {
            background-size: cover;
            background-position: center;
            border-radius: 10px;
        }

        /* Ensure both cards have the same height */
        .fixed-height-card {
            height: 100%;
            /* Allow Bootstrap to handle equal heights */
            min-height: 450px;
            /* Ensures both cards stay equal */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Ensure images also align properly */
        .fixed-img {
            height: 300px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Set different background images dynamically */
        .col-lg-6:nth-child(1) .fixed-img {
            background-image: url('{{ asset('homepage/img/cover3.jpg') }}');
        }

        .col-lg-6:nth-child(2) .fixed-img {
            background-image: url('{{ asset('homepage/img/cover34.jpg') }}');
        }

        .col-lg-6:nth-child(3) .fixed-img {
            background-image: url('{{ asset('homepage/img/cover4.jpg') }}');
        }

        /* Overlay effect on images */
        .card-img-top {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .card-img-top .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            transition: background 0.3s ease-in-out;
        }

        .card:hover .overlay {
            background: rgba(0, 0, 0, 0.1);
        }

        /* Card styling */
        .card {
            border-radius: 8px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Section Styling */
        .function-hall-section {
            padding: 80px 0;
        }

        /* Ensure Equal Height for Both Blocks */
        .function-hall-card,
        .function-text {
            height: 100%;
            border-radius: 8px;
            overflow: hidden;
        }

        /* Image Styling */
        .function-hall-img {
            height: 100%;
            min-height: 400px;
            background: url('{{ asset('homepage/img/function.jpg') }}') center/cover no-repeat;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        /* Image Hover Effect */
        .function-hall-img:hover {
            transform: scale(1.03);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Text Block Styling */
        .function-text {
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
        }

        /* Card Styling */
        .about-text,
        .about-img {
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            height: 100%;
        }

        /* Image Container */
        .img-container {
            height: 100%;
            min-height: 400px;
            background: url('{{ asset('homepage/img/png/cover1.png') }}') center/cover no-repeat;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        /* Image Hover Effect */
        .img-container:hover {
            transform: scale(1.03);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Section Styling */
        .accommodations-section {
            padding: 80px 0;
            /* background: #f8f9fa; */
        }

        /* Scrollable Cards */
        .scroll-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            margin-top: 20px;
        }

        .scroll-wrapper {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            scroll-behavior: smooth;
            white-space: nowrap;
            padding-bottom: 10px;
        }

        .scroll-wrapper::-webkit-scrollbar {
            display: none;
        }

        /* Card Styling */
        .accommodation-card {
            min-width: 320px;
            max-width: 320px;
            border-radius: 10px;
            overflow: hidden;
            background: white;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .accommodation-card img {
            height: 200px;
            object-fit: cover;
        }

        .accommodation-card:hover {
            transform: scale(1.05);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* Auto-Scrolling */
        .scroll-wrapper:hover {
            animation-play-state: paused;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .hero__slider .owl-item img {
                height: 50vh;
            }

            .scroll-wrapper {
                flex-wrap: nowrap;
            }

            .accommodation-card {
                min-width: 90%;
                max-width: 90%;
            }
        }

        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
@endsection

@section('APP-CONTENT')

    <!-- Hero Section -->
    <section class="hero">
        <div class="container-fluid">
            <div class="hero__slider  owl-carousel">
                @foreach (['cover1.jpg', 'cover2.jpg', 'cover3.jpg', 'cover4.jpg'] as $heroImage)
                    <div class="hero__items set-bg" data-setbg="{{ asset("homepage/img/hero/$heroImage") }}">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="hero__text" data-aos="fade-up">
                                    <h2>Belle's Bistro Resort and Hotel</h2>
                                    <p>Poblacion Zone 1 Mayorga Leyte</p>
                                    <a href="{{ route('homepage-bookHotel') }}" class="btn btn-dark">Book Hotel</a>
                                    <a href="{{ route('homepage-bookResort') }}" class="btn btn-dark">Book Cottage</a>
                                    <a href="{{ route('homepage-bookResort') }}" class="btn btn-dark">Food Order</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Accommodations Section -->
    <section class="accommodations-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center" data-aos="fade-up">
                    <div class="section-title">
                        <h2 class="font-weight-bold">Belle's Hotel Room</h2>
                        <div class="hr-one"></div>
                        <p class="text-muted">Belle's Hotel offers timeless elegance and exceptional comfort for a truly
                            unforgettable stay.
                        </p>
                    </div>
                </div>
            </div>

            <div class="scroll-container">
                <div class="scroll-wrapper">
                    @foreach ($hotelRooms as $hotel)
                        <div class="card accommodation-card">
                            <img src="{{ asset($hotel->picture ?? 'img/default.jpg') }}" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">{{ $hotel->room_name }}</h5>
                                <p class="card-text">₱{{ number_format($hotel->room_rate, 2) }} |
                                    {{ $hotel->room_capacity }} people</p>
                                <a href="{{ route('homepage-roomDetails', ['id' => encrypt($hotel->id)]) }}"
                                    class="btn btn-dark btn-sm">View Details</a>
                            </div>
                        </div>
                    @endforeach

                    @foreach ($resortCottages as $cottage)
                        <div class="card accommodation-card">
                            <img src="{{ asset($cottage->picture ?? 'img/default.jpg') }}" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title">{{ $cottage->cottage_name }}</h5>
                                <p class="card-text">₱{{ number_format($cottage->cottage_rate, 2) }} |
                                    {{ $cottage->cottage_capacity }} people</p>
                                <a href="{{ route('homepage-cottageDetails', ['id' => encrypt($cottage->id)]) }}"
                                    class="btn btn-dark btn-sm">View Details</a>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="row align-items-stretch">
                <!-- About Text Block -->
                <div class="col-lg-4 d-flex" data-aos="fade-right">
                    <div class="about-text card p-4 w-100 shadow-lg">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <h2 class="font-weight-bold text-dark">Belle's Bistro Resort and Hotel</h2>
                            <div class="hr-one"></div>
                            <p class="text-muted">
                                Welcome to <strong>Belle's Bistro Resort & Hotel</strong> is a cozy yet luxurious retreat
                                nestled in the heart of nature, offering comfortable rooms, charming cottages, essential
                                amenities, and warm hospitality. Complete with a refreshing pool, exciting water slides, and
                                scenic views for singles, couples, and families seeking a peaceful and memorable escape.

                            </p>
                            <p class="text-muted">
                                Whether you're looking for a peaceful retreat or a fun-filled family getaway, enjoy
                                refreshing pools, exciting water slides, and scenic views.
                            </p>
                            <a href="#" class="btn btn-dark btn-sm mt-auto align-self-start">Discover More</a>
                        </div>
                    </div>
                </div>

                <!-- About Image -->
                <div class="col-lg-8 d-flex" data-aos="fade-left">
                    <div class="about-img card border-0 w-100 shadow-lg">
                        <div class="card-img-top img-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Accommodations Section -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <!-- Section Title -->
                <div class="col-lg-12 text-center" data-aos="fade-up">
                    <div class="section-title">
                        <h2 class="font-weight-bold">Belle's Bistro Havens</h2>
                        <div class="hr-one"></div>
                        <p class="text-muted">Escape into luxury and tranquility with our exclusive accommodations.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Hotel Card -->
                <div class="col-lg-6 mb-4" data-aos="fade-right">
                    <div class="card shadow-lg border-0 fixed-height-card">
                        <div class="card-img-top position-relative fixed-img">
                            <div class="overlay"></div>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-title font-weight-bold">Resort</h4>
                            <p class="card-text text-muted">
                                Belle's Bistro Resort offers a perfect blend of luxury and comfort nestled in nature,
                                featuring cozy cottages, a refreshing pool, and exciting water slides for the whole family.

                            </p>
                            <a href="{{ route('homepage-categoryHotel') }}" class="btn btn-dark btn-sm">Discover More</a>
                        </div>
                    </div>
                </div>

                <!-- Resort Card -->
                <div class="col-lg-6 mb-4" data-aos="fade-left">
                    <div class="card shadow-lg border-0 fixed-height-card">
                        <div class="card-img-top position-relative fixed-img">
                            <div class="overlay"></div>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-title font-weight-bold">Hotel</h4>
                            <p class="card-text text-muted">
                                Belle's Bistro Hotel is a cozy retreat with comfortable rooms, essential amenities, and
                                friendly service, ideal for singles, couples, and families seeking a peaceful and memorable
                                stay.

                            </p>
                            <a href="{{ route('homepage-categoryResort') }}" class="btn btn-dark btn-sm">Discover More</a>
                        </div>
                    </div>
                </div>
                <!-- Resto Card -->
                <div class="col-lg-6 mb-4" data-aos="fade-left">
                    <div class="card shadow-lg border-0 fixed-height-card">
                        <div class="card-img-top position-relative fixed-img">
                            <div class="overlay"></div>
                        </div>
                        <div class="card-body text-center">
                            <h4 class="card-title font-weight-bold">Restaurant</h4>
                            <p class="card-text text-muted">
                                Belle's Bistro Restaurant offers a cozy atmosphere and a menu of gourmet dishes and
                                home-style favorites made from fresh, locally sourced ingredients, perfect for casual
                                dinners or special celebrations.


                            </p>
                            <a href="{{ route('homepage-categoryResort') }}" class="btn btn-dark btn-sm">Discover More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Function Hall Section -->
    <section class="function-hall-section spad">
        <div class="container">
            <!-- Section Title -->
            <div class="row align-items-center">
                <div class="col-lg-12 text-center" data-aos="fade-up">
                    <div class="section-title">
                        <h2 class="font-weight-bold">Hotel Facilities</h2>
                        <div class="hr-one"></div>
                        <p class="text-muted">The perfect venue for your special occasions, combining elegance and
                            versatility.</p>
                    </div>
                </div>
            </div>

            <div class="card ">
                <div class="content mt-3">
                    <div class="row  text-center">
                        <div class="col-sm">
                            <div class="card ">
                                <img src="/img/hotel/IMG_20241118_112941.jpg" class="card-img-top"
                                    style="max-height: 400px; width: auto;" alt="facilities">
                                <div class="card-body">

                                    <ul class="list-group fw-bold">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Outdoor Pool</span>
                                            <span>Room Service</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Belle's Bistro Restaurant and Bar</span>
                                            <span>Parking Area</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>Complimentary Wi-Fi</span>
                                            <span>Function Hall</span>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('APP-SCRIPT')
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
        });

        // Auto-scroll for accommodations section
        document.addEventListener("DOMContentLoaded", function() {
            let scrollWrapper = document.querySelector(".scroll-wrapper");
            let scrollAmount = 0;

            function autoScroll() {
                if (scrollAmount < scrollWrapper.scrollWidth - scrollWrapper.clientWidth) {
                    scrollAmount += 2;
                    scrollWrapper.scrollLeft = scrollAmount;
                } else {
                    scrollAmount = 0;
                    scrollWrapper.scrollLeft = 0;
                }
            }

            let scrollInterval = setInterval(autoScroll, 50);

            scrollWrapper.addEventListener("mouseenter", () => clearInterval(scrollInterval));
            scrollWrapper.addEventListener("mouseleave", () => scrollInterval = setInterval(autoScroll, 50));
        });
    </script>
@endsection
