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
            height: 80vh;
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
            /** background-image: linear-gradient(to right, rgba(255, 255, 255, 0), rgba(255, 226, 106, 1), rgba(255, 255, 255, 0)); **/
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0), rgba(248, 0, 0, 1), rgba(255, 255, 255, 0));

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
            background-image: url('{{ asset('homepage/img/Room1.png') }}');
        }

        .col-lg-6:nth-child(2) .fixed-img {
            background-image: url('{{ asset('homepage/img/cottage-2.jpeg') }}');
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
            background: url('{{ asset('homepage/img/hero/hero-2.jpeg') }}') center/cover no-repeat;
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
            background: url('{{ asset('homepage/img/hero/hero-2.png') }}') center/cover no-repeat;
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
                height: 60vh;
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
            <div class="hero__slider owl-carousel">
                @foreach (['hero-1.png', 'hero-2.jpeg', 'hero-3.jpeg', 'hero-4.png'] as $heroImage)
                    <div class="hero__items set-bg" data-setbg="{{ asset("homepage/img/hero/$heroImage") }}">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="hero__text" data-aos="fade-up">
                                    <h2>Tia Inday Haven Farm Resort</h2>
                                    <p>Brgy. Balocawehay, Abuyog, Leyte</p>
                                    <a href="{{ route('homepage-bookHotel') }}" class="btn btn-dark">Book Hotel</a>
                                    <a href="{{ route('homepage-bookResort') }}" class="btn btn-dark">Book Cottage</a>
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
                        <h2 class="font-weight-bold">Serene Sanctuaries</h2>
                        <div class="hr-one"></div>
                        <p class="text-muted">Explore our exquisite accommodations, from luxurious hotel rooms to tranquil
                            cottages.</p>
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
                            <h2 class="font-weight-bold text-dark">Tia Inday Haven Farm Resort</h2>
                            <div class="hr-one"></div>
                            <p class="text-muted">
                                Welcome to <strong>Tia Inday Haven Farm Resort</strong>, nestled in the serene landscapes of
                                <em>Brgy Balocawehay, Abuyog, Leyte</em>. Just a brief 5-minute drive from the main highway,
                                our resort offers an escape into tranquility, blending breathtaking natural beauty with
                                luxurious accommodations.
                            </p>
                            <p class="text-muted">
                                Whether you seek relaxation by the pool, gourmet dining, or adventure in the great outdoors,
                                our resort promises an <strong>unforgettable experience</strong> filled with comfort and
                                discovery.
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
                        <h2 class="font-weight-bold">Serene Havens</h2>
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
                            <h4 class="card-title font-weight-bold">Hotel</h4>
                            <p class="card-text text-muted">
                                Discover the ultimate in comfort with deluxe hotel rooms offering stunning views.
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
                            <h4 class="card-title font-weight-bold">Resort</h4>
                            <p class="card-text text-muted">
                                Unwind at our tranquil resort and enjoy the peaceful atmosphere.
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
                        <h2 class="font-weight-bold">Function Hall</h2>
                        <div class="hr-one"></div>
                        <p class="text-muted">The perfect venue for your special occasions, combining elegance and
                            versatility.</p>
                    </div>
                </div>
            </div>

            <div class="row align-items-stretch">
                <!-- Function Hall Image -->
                <div class="col-lg-8 d-flex" data-aos="fade-right">
                    <div class="card shadow-lg border-0 function-hall-card w-100">
                        <div class="card-img-top function-hall-img"></div>
                    </div>
                </div>

                <!-- Function Hall Text -->
                <div class="col-lg-4 d-flex" data-aos="fade-left">
                    <div class="function-text card p-4 w-100 shadow-lg">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <h4 class="font-weight-bold">A Grand Venue for Unforgettable Moments</h4>
                            <p class="text-muted">
                                Experience luxury and functionality in our exquisite function hall, an elegant space
                                designed
                                to host weddings, conferences, and grand celebrations. With modern amenities and a refined
                                atmosphere, it's the perfect backdrop for your special occasions.
                            </p>
                            <a href="#" class="btn btn-dark btn-sm mt-auto align-self-start">Learn More</a>
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
