@extends('homepage.base')

@section('APP-TITLE', 'Verify Your Email')

@section('custom-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <style>
        /* Parallax Section */
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

        /* Form Container */
        .verify-email-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.15);
            text-align: center;
            transition: 0.3s;
        }

        .verify-email-container h4 {
            color: #333;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .verify-email-container p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
        }

        .btn-verify {
            background: #28a745;
            border: none;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 6px;
            font-weight: bold;
            transition: 0.3s ease-in-out;
        }

        .btn-verify:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
        }

        /* Sidebar */
        .sidebar-container {
            background: #fff;
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
        Verify Your Email
    </div>

    <!-- Auth Section -->
    <section class="auth-section spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="verify-email-container" data-aos="fade-up">
                        <h4><i class="fa fa-envelope"></i> Email Verification Required</h4>
                        <p>To access your account, you need to verify your email address. Click the button below to receive
                            a new verification link.</p>

                        <form id="verifyForm">
                            <input type="hidden" name="email" value="{{ Crypt::encryptString($user->email) }}">
                            <button type="submit" class="btn btn-verify">
                                <i class="fa fa-paper-plane"></i> Send Verification Email
                            </button>
                        </form>

                        <p class="mt-3">
                            Didn't receive the email? Please check your spam folder or try again by clicking the button
                            above.
                        </p>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar-container">
                        <h5><i class="fa fa-map-marker"></i> Our Location</h5>
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
    <!-- Load Google reCAPTCHA v3 -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
    <script>
        AOS.init();

        function executeRecaptcha(action, callback) {
            grecaptcha.ready(function() {
                grecaptcha.execute("{{ env('RECAPTCHA_SITE_KEY') }}", {
                    action: action
                }).then(function(token) {
                    callback(token);
                });
            });
        }

        $('#verifyForm').submit(function(event) {
            event.preventDefault();

            executeRecaptcha('register', function(token) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'recaptcha_token', // ✅ FIXED: Correct key
                    value: token
                }).appendTo('#verifyForm');

                $.ajax({
                    method: 'POST',
                    url: '{{ route('resendVerification') }}',
                    data: $('#verifyForm').serialize(),
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.valid) {
                            showSuccessMessage(response.msg);
                        }
                    },
                    error: function(jqXHR) {
                        if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                            let errors = jqXHR.responseJSON.errors;
                            let errorMsg = `${jqXHR.responseJSON.msg}\n`;
                            for (const [field, messages] of Object.entries(
                                    errors)) {
                                errorMsg += `- ${messages.join(', ')}\n`;
                            }
                            showErrorMessage(errorMsg);
                        } else {
                            showErrorMessage(
                                "An unexpected error occurred. Please try again."
                            );
                        }
                    }
                });
            });
        });
    </script>
@endsection
