@extends('homepage.base')

@section('APP-TITLE', 'Email Already Verified')

@section('custom-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <style>
        /* Parallax Header */
        .parallax-section {
            background: url("{{ asset('homepage/img/hero/hero-2.jpeg') }}") no-repeat center center fixed;
            background-size: cover;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);
            font-size: 2rem;
            font-weight: bold;
        }

        /* Confirmation Container */
        .confirmation-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.15);
            text-align: center;
            transition: all 0.3s ease-in-out;
        }

        .confirmation-container h4 {
            color: #333;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .confirmation-container p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        .confirmation-icon {
            font-size: 50px;
            color: #007bff;
            /* Blue for "Already Verified" */
            margin-bottom: 15px;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* CTA Buttons */
        .btn-primary-custom,
        .btn-secondary-custom {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary-custom {
            background: #003366;
            border: none;
            color: white;
        }

        .btn-primary-custom:hover {
            background: #002244;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 51, 102, 0.3);
        }

        .btn-secondary-custom {
            background: #6c757d;
            color: white;
            margin-left: 10px;
        }

        .btn-secondary-custom:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
        }

        /* Sidebar */
        .sidebar-container {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .sidebar-container h5 {
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .map-container iframe {
            width: 100%;
            height: 250px;
            border-radius: 10px;
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.15);
        }

        /* Responsive Design */
        @media (max-width: 768px) {

            .btn-primary-custom,
            .btn-secondary-custom {
                width: 100%;
                margin-top: 10px;
            }
        }
    </style>
@endsection

@section('APP-CONTENT')
    <!-- Parallax Header -->
    <div class="parallax-section">
        Email Already Verified ✔️
    </div>

    <!-- Confirmation Section -->
    <section class="auth-section spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="confirmation-container" data-aos="fade-up">
                        <i class="fa fa-info-circle confirmation-icon"></i>
                        <h4>Your Email is Already Verified</h4>
                        <p>Hello, <strong>{{ ucfirst($user->first_name) }} {{ ucfirst($user->last_name) }}</strong>! 🎉</p>
                        <p>Your email has already been verified. You can now enjoy all the features of <strong>Belle's Bistro Resort and Hotel</strong>.</p>

                        <p class="mt-3">Continue exploring our stunning accommodations and services:</p>
                        <a href="{{ route('homepage-homepage') }}" class="btn-primary-custom">Go to Homepage</a>
                        {{-- @if (Auth::check())
                            <a href="{{ route('dashboard') }}" class="btn-secondary-custom">Return to Dashboard</a>
                        @endif --}}
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar-container">
                        <h5><i class="fa fa-map-marker"></i> Visit Us</h5>
                        <div class="map-container">
                            <iframe
                                src="https://www.google.com/maps/embed/v1/place?q=Poblacion+Zone+1,+Mayorga,+Leyte,+Eastern+Visayas,+Philippines,+Mayorga,+Philippines,&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"
                                width="100%" height="300" style="border: 0; border-radius: 10px;" allowfullscreen
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('APP-SCRIPT')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init();
    </script>
@endsection
