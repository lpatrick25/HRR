@extends('homepage.base')

@section('APP-TITLE')
    Login & Register
@endsection

@section('active-login')
    active
@endsection

@section('custom-css')
    <!-- Include AOS CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

    <style>
        /* Contact Page Header */
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

        /* Auth Section */
        .auth-container {
            background: #f8f9fa;
            padding: 40px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        .form-control {
            border-radius: 5px;
            height: 45px;
        }

        .btn-auth {
            border-radius: 5px;
            font-weight: bold;
            padding: 12px;
        }

        .toggle-auth {
            text-align: center;
            cursor: pointer;
            color: #007bff;
        }

        .toggle-auth:hover {
            text-decoration: underline;
        }

        .forgot-password {
            font-size: 14px;
            float: right;
            cursor: pointer;
            color: #007bff;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Sidebar */
        .sidebar-container {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .map-container {
            height: 250px;
            overflow: hidden;
            border-radius: 8px;
        }
    </style>
@endsection

@section('APP-CONTENT')
    <!-- Normal Breadcrumb Begin -->
    <section class="normal-breadcrumb set-bg" data-setbg="{{ asset('homepage/img/hero/hero-4.png') }}" data-aos="fade-up">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Login & Register</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Normal Breadcrumb End -->

    <!-- Auth Section -->
    <section class="spad">
        <div class="container">
            <div class="row">
                <!-- Login & Register Form -->
                <div class="col-lg-8">
                    <div class="auth-container">
                        <!-- Login Form -->
                        <div id="login-form">
                            <h4 class="text-center mb-4"><i class="fa fa-sign-in"></i> Login</h4>
                            <form id="loginForm">

                                <div class="form-group">
                                    <label><i class="fa fa-envelope"></i> Email Address</label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter your email"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label><i class="fa fa-lock"></i> Password</label>
                                    <input type="password" class="form-control" name="password"
                                        placeholder="Enter your password" required>
                                    <span class="forgot-password" data-toggle="modal"
                                        data-target="#forgotPasswordModal">Forgot Password?</span>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block btn-auth">
                                    <i class="fa fa-sign-in"></i> Login
                                </button>
                            </form>
                            <p class="text-center mt-3 toggle-auth">Don't have an account? <strong>Register here</strong>
                            </p>
                        </div>

                        <!-- Registration Form -->
                        <div id="register-form" style="display: none;">
                            <h4 class="text-center mb-4"><i class="fa fa-user-plus"></i> Register</h4>
                            <form id="registrationForm">

                                <div class="row">
                                    <!-- First Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fa fa-user"></i> First Name</label>
                                            <input type="text" class="form-control" name="first_name"
                                                placeholder="Enter first name" required>
                                        </div>
                                    </div>
                                    <!-- Last Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fa fa-user"></i> Last Name</label>
                                            <input type="text" class="form-control" name="last_name"
                                                placeholder="Enter last name" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Phone Number -->
                                <div class="form-group">
                                    <label><i class="fa fa-phone"></i> Phone Number <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"
                                            style="padding: 5px 10px; border: 1px solid #ced4da;">+63</span>
                                        <input type="tel" class="form-control" name="phone_number" id="phone_number"
                                            data-mask="999-999-9999" placeholder="XXX-XXX-XXXX" required>
                                    </div>
                                    <small class="text-muted">Enter the remaining 9 digits after +63.</small>
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label><i class="fa fa-envelope"></i> Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter email"
                                        required>
                                </div>

                                <!-- Password -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fa fa-lock"></i> Password</label>
                                            <input type="password" class="form-control" name="password" id="password"
                                                placeholder="Enter password" required>
                                        </div>
                                    </div>
                                    <!-- Confirm Password -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fa fa-lock"></i> Confirm Password</label>
                                            <input type="password" class="form-control" name="password_confirmation"
                                                id="confirm_password" placeholder="Confirm password" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Newsletter Subscription -->
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="news_letter" name="news_letter"
                                            value="1">
                                        <label class="form-check-label" for="news_letter">
                                            I would like to receive notifications about promotions and newsletters.
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success btn-block btn-auth">
                                    <i class="fa fa-user-plus"></i> Register
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <!-- Sidebar -->
                    <div class="product__sidebar">
                        <div class="sidebar-container">
                            <div class="section-title">
                                <h5>Tiya Inday Haven Farm Resort</h5>
                            </div>
                            <div class="map-container">
                                <iframe style="width: 100%; height: 100%; border: none;"
                                    src="https://www.google.com/maps/embed/v1/directions?origin=Tia+Inday+Haven+Farm,+Balocawehay,+Abuyog,+Leyte,+Philippines&destination=Abuyog+Bus+Terminal,+Abuyog,+Leyte,+Philippines&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-lock"></i> Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="forgotForm">

                        <div class="form-group">
                            <label><i class="fa fa-envelope"></i> Enter Your Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter your email"
                                required>
                        </div>
                        <button type="submit" class="btn btn-warning btn-block btn-auth">
                            <i class="fa fa-paper-plane"></i> Send Reset Link
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('APP-SCRIPT')
    <!-- Load Google reCAPTCHA v3 -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
    <!-- Include AOS JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <script type="text/javascript">
        function executeRecaptcha(action, callback) {
            grecaptcha.ready(function() {
                grecaptcha.execute("{{ env('RECAPTCHA_SITE_KEY') }}", {
                    action: action
                }).then(function(token) {
                    callback(token);
                });
            });
        }

        $(document).ready(function() {

            AOS.init({
                duration: 1000, // Animation speed in milliseconds
                once: true, // Ensures animation happens only once per scroll
            });

            $('.toggle-auth').click(function() {
                $('#login-form, #register-form').toggle();
            });

            $('#loginForm').submit(function(event) {
                event.preventDefault();

                executeRecaptcha('register', function(token) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'recaptcha_token', // ✅ FIXED: Correct key
                        value: token
                    }).appendTo('#loginForm');

                    $.ajax({
                        method: 'POST',
                        url: '{{ route('loginAccount') }}',
                        data: $('#loginForm').serialize(),
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.valid) {
                                showSuccessMessage(response.msg);
                                window.location.href = response.redirect;
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

            $('#registrationForm').submit(function(event) {
                event.preventDefault();

                executeRecaptcha('register', function(token) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'recaptcha_token', // ✅ FIXED: Correct key
                        value: token
                    }).appendTo('#registrationForm');

                    $.ajax({
                        method: 'POST',
                        url: '{{ route('registerAccount') }}',
                        data: $('#registrationForm').serialize(),
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.valid) {
                                showSuccessMessage(response.msg);
                                setInterval(() => {
                                    window.location.reload();
                                }, 1000);
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

            $('#forgotForm').submit(function(event) {
                event.preventDefault();

                executeRecaptcha('register', function(token) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'recaptcha_token', // ✅ FIXED: Correct key
                        value: token
                    }).appendTo('#forgotForm');

                    $.ajax({
                        method: 'POST',
                        url: '{{ route('forgotPassword') }}',
                        data: $('#forgotForm').serialize(),
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.valid) {
                                showSuccessMessage(response.msg);
                                window.location.href =
                                    '{{ route('homepage-homepage') }}';
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

        });
    </script>
@endsection
