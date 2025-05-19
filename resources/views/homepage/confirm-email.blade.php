@extends('homepage.base')

@section('APP-TITLE', 'Email Verified')

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
            transition: 0.3s;
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
            color: #28a745;
            margin-bottom: 15px;
        }

        /* CTA Buttons */
        .btn-primary-custom {
            background: #003366;
            border: none;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 6px;
            font-weight: bold;
            transition: 0.3s ease-in-out;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .btn-primary-custom:hover {
            background: #002244;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 51, 102, 0.3);
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
    </style>
@endsection

@section('APP-CONTENT')
    <!-- Parallax Header -->
    <div class="parallax-section">
        Email Verified Successfully 🎉
    </div>

    <!-- Confirmation Section -->
    <section class="auth-section spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="confirmation-container" data-aos="fade-up">
                        <i class="fa fa-check-circle confirmation-icon"></i>
                        <h4>Email Verified Successfully!</h4>
                        <p>Thank you for confirming your email, <strong>{{ ucfirst($user->first_name) }} {{ ucfirst($user->last_name) }}</strong>!</p>
                        <p>Your account is now active, and you're all set to explore the exclusive experiences at
                            <strong>Tia Inday Haven Resort</strong>.</p>

                        <p class="mt-3">Get started by exploring our stunning accommodations and amenities:</p>
                        <a href="{{ route('homepage-homepage') }}" class="btn-primary-custom">Explore Resort</a>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar-container">
                        <h5><i class="fa fa-map-marker"></i> Visit Us</h5>
                        <div class="map-container">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3932.002580437633!2d124.9486105111568!3d10.714001190019339!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3307bb8936174b07%3A0x955e002eea10fcd!2sTia%20Inday%20Haven%20Farm!5e0!3m2!1sen!2sph!4v{{ time() }} "
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
