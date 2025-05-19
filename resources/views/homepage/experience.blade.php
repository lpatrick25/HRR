@extends('homepage.base')

@section('APP-TITLE')
    Experience
@endsection

@section('active-experience')
    active
@endsection

@section('custom-css')
    <!-- Include AOS CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        /* Experience Header Section */
        .normal-breadcrumb {
            position: relative;
            text-align: center;
            padding: 100px 20px;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* Overlay for Readability */
        .normal-breadcrumb::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Darker overlay */
        }

        /* Text Box */
        .normal__breadcrumb__text {
            position: relative;
            z-index: 2;
            background: rgba(0, 0, 0, 0.5);
            padding: 30px 50px;
            border-radius: 10px;
            display: inline-block;
            max-width: 85%;
        }

        .normal-breadcrumb h2 {
            font-size: 48px;
            font-weight: bold;
            color: #fff;
            text-transform: uppercase;
        }

        .normal-breadcrumb p {
            font-size: 20px;
            max-width: 700px;
            margin: auto;
            color: #f8f9fa;
            line-height: 1.6;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .normal-breadcrumb {
                padding: 80px 15px;
            }

            .normal__breadcrumb__text {
                max-width: 95%;
                padding: 20px;
            }

            .normal-breadcrumb h2 {
                font-size: 36px;
            }

            .normal-breadcrumb p {
                font-size: 18px;
            }
        }

        /* Experience Sections */
        .experience-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 60px 0;
            flex-wrap: wrap;
            gap: 20px;
        }

        .experience-section:nth-child(even) {
            flex-direction: row-reverse;
        }

        /* Text Content */
        .experience-text {
            flex: 1;
            padding: 40px;
            max-width: 50%;
        }

        .experience-text h4 {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }

        .experience-text h4::after {
            content: "";
            display: block;
            width: 50px;
            height: 4px;
            background-color: #ff9f1c;
            margin-top: 8px;
        }

        .experience-text p {
            font-size: 16px;
            color: #555;
            line-height: 1.8;
        }

        /* Image Styling */
        .experience-image {
            flex: 1;
            max-width: 50%;
            position: relative;
        }

        .experience-image img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .experience-image img:hover {
            transform: scale(1.05);
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.25);
        }

        /* Responsive Layout Adjustments */
        @media (max-width: 992px) {
            .experience-section {
                flex-direction: column;
                text-align: center;
            }

            .experience-text {
                max-width: 100%;
                padding: 30px 20px;
            }

            .experience-image {
                max-width: 100%;
                text-align: center;
            }

            .experience-image img {
                width: 90%;
            }
        }

        /* Call to Action */
        .cta {
            text-align: center;
            padding: 50px;
            background: #ff9f1c;
            color: white;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.2);
            margin: 40px 0;
        }

        .cta h3 {
            font-size: 32px;
            font-weight: bold;
        }

        .cta a {
            background: white;
            color: #ff9f1c;
            padding: 12px 25px;
            font-size: 18px;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s ease;
            display: inline-block;
            margin-top: 15px;
        }

        .cta a:hover {
            background: rgba(255, 255, 255, 0.8);
        }

        /* Responsive CTA */
        @media (max-width: 768px) {
            .cta h3 {
                font-size: 26px;
            }

            .cta a {
                font-size: 16px;
                padding: 10px 20px;
            }
        }
    </style>
@endsection

@section('APP-CONTENT')
    <!-- Magazine-Style Header -->
    <section class="normal-breadcrumb set-bg" data-setbg="{{ asset('homepage/img/hero/hero-4.png') }}" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Experience</h2>
                        <p>Discover luxury, nature, and unforgettable moments in our stunning resort.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Experience Section 1 -->
        <div class="experience-section">
            <div class="experience-text" data-aos="fade-right">
                <h4>Elevate Your Nature Escape</h4>
                <p>Relax at our rooftop oasis, where the beauty of nature surrounds you. Enjoy breathtaking views of lush
                    landscapes, fresh air, and gourmet dining.</p>
            </div>
            <div class="experience-image" data-aos="fade-left">
                <img src="{{ asset('homepage/img/hero/hero-5.jpeg') }}" alt="Nature Escape">
            </div>
        </div>

        <!-- Experience Section 2 -->
        <div class="experience-section">
            <div class="experience-text" data-aos="fade-left">
                <h4>Elevate Your Events</h4>
                <p>Celebrate life's most cherished moments in our elegant function halls. Whether it’s a wedding,
                    conference, or special gathering, our venue is the perfect choice.</p>
            </div>
            <div class="experience-image" data-aos="fade-right">
                <img src="{{ asset('homepage/img/hero/hero-2.jpeg') }}" alt="Event Hall">
            </div>
        </div>

        <!-- Experience Section 3 -->
        <div class="experience-section">
            <div class="experience-text" data-aos="fade-right">
                <h4>Mountain Harmony in Bloom</h4>
                <p>Our beautifully landscaped gardens offer a peaceful retreat. Stroll through vibrant flowerbeds and enjoy
                    nature’s calming presence.</p>
            </div>
            <div class="experience-image" data-aos="fade-left">
                <img src="{{ asset('homepage/img/hero/hero-3.jpeg') }}" alt="Garden">
            </div>
        </div>

        <!-- Experience Section 4 -->
        <div class="experience-section">
            <div class="experience-text" data-aos="fade-left">
                <h4>Serene Cottages and Poolside Paradise</h4>
                <p>Escape to our cozy cottages and unwind by the pool, surrounded by breathtaking mountain views. A perfect
                    place for relaxation and adventure.</p>
            </div>
            <div class="experience-image" data-aos="fade-right">
                <img src="{{ asset('homepage/img/hero/hero-4.png') }}" alt="Cottages">
            </div>
        </div>

        <!-- Call to Action -->
        <div class="cta" data-aos="zoom-in">
            <h3>Ready to Experience Luxury?</h3>
            <a href="#">Book Your Stay</a>
        </div>
    </div>
@endsection

@section('APP-SCRIPT')
    <!-- Include AOS JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            AOS.init({
                duration: 1000, // Animation speed in milliseconds
                once: true, // Ensures animation happens only once per scroll
            });
        });
    </script>
@endsection
