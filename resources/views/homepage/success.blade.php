@extends('homepage.base')

@section('APP-TITLE', 'Booking Confirmed')

@section('active-login', 'active')

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
            font-size: 24px;
        }

        .confirmation-container p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        .confirmation-icon {
            font-size: 50px;
            color: #28a745; /* Green for "Success" */
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

        /* Countdown Styling */
        .countdown-container {
            font-size: 18px;
            color: #555;
            margin-top: 15px;
        }

        .countdown-number {
            font-size: 24px;
            font-weight: bold;
            color: #003366;
            display: inline-block;
            transition: all 0.5s ease-in-out;
        }

        /* Progress Bar */
        .progress-bar {
            width: 100%;
            height: 5px;
            background: #ddd;
            border-radius: 3px;
            margin-top: 10px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: #28a745;
            width: 100%;
            transition: width 1s linear;
        }

        /* CTA Button */
        .btn-primary-custom {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
            background: #003366;
            color: white;
            border: none;
            margin-top: 15px;
        }

        .btn-primary-custom:hover {
            background: #002244;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 51, 102, 0.3);
        }

        /* Sidebar */
        .sidebar-container {
            background: #f8f9fa;
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
        Booking Confirmed ✔️
    </div>

    <!-- Confirmation Section -->
    <section class="auth-section spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="confirmation-container" data-aos="fade-up">
                        <i class="fa fa-check-circle confirmation-icon"></i>
                        <h4>Thank You for Booking</h4>
                        <p>Your reservation has been successfully confirmed at <strong>Tia Inday Haven Resort</strong>.</p>
                        <p>We look forward to providing you with a relaxing and unforgettable experience.</p>

                        <!-- Countdown -->
                        <div class="countdown-container">
                            You will be redirected to the homepage in
                            <span class="countdown-number" id="countdown">10</span> seconds.
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress-bar">
                            <div class="progress-fill" id="progress-fill"></div>
                        </div>

                        <p>If you are not redirected, click the button below:</p>
                        <a href="{{ route('homepage-homepage') }}" class="btn-primary-custom">Go to Homepage</a>
                    </div>
                </div>

                <!-- Sidebar with Map -->
                <div class="col-lg-4">
                    <div class="sidebar-container" data-aos="fade-up">
                        <h5><i class="fa fa-map-marker"></i> Visit Us</h5>
                        <div class="map-container">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3932.002580437633!2d124.9486105111568!3d10.714001190019339!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3307bb8936174b07%3A0x955e002eea10fcd!2sTia%20Inday%20Haven%20Farm!5e0!3m2!1sen!2sph!4v{{ time() }}"
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

        // Countdown with Smooth Transition
        let countdown = 10;
        const countdownElement = document.getElementById('countdown');
        const progressFill = document.getElementById('progress-fill');

        const interval = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            countdownElement.style.opacity = 0.5;
            setTimeout(() => { countdownElement.style.opacity = 1; }, 300);

            progressFill.style.width = (countdown * 10) + "%";

            if (countdown <= 0) {
                clearInterval(interval);
                window.location.href = "{{ route('homepage-homepage') }}";
            }
        }, 1000);
    </script>
@endsection
