@extends('homepage.base')

@section('APP-TITLE')
    Contact Us
@endsection

@section('active-contact')
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

        /* Contact Form Styling */
        #contactForm {
            background: #f8f9fa;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        .form-group label {
            font-weight: bold;
            color: #e53637;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 16px;
        }

        /* Submit Button */
        .follow-btn {
            background: #e53637;
            color: #fff;
            padding: 12px 20px;
            font-size: 18px;
            border-radius: 5px;
            transition: 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
        }

        .follow-btn:hover {
            background: #b02a2a;
        }

        /* Contact Information Section */
        .product__page__content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .product__page__content h4 {
            color: #e53637;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        /* Icon Styling */
        .product__page__content h4 i {
            margin-right: 10px;
            color: #e53637;
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
            margin-top: 20px;
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
                        <h2>Contact Us</h2>
                        <p>Facilities provided may range from a modest-quality mattress in a small room to large suites with
                            bigger.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Normal Breadcrumb End -->

    <!-- Contact Section Begin -->
    <section class="spad">
        <div class="container">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-8">
                    <form id="contactForm" class="modal-content">
                        <div class="modal-header section-title">
                            <h5 style="font-size: 30px;">Fill the form</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <input type="text" class="form-control" id="customer_full_name"
                                                    name="customer_full_name" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" id="customer_email"
                                                    name="customer_email" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Subject</label>
                                                <input type="text" class="form-control" id="customer_subject"
                                                    name="customer_subject" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Message</label>
                                                <textarea class="form-control" id="customer_message" name="customer_message" required cols="30" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer resort__details__btn">
                            <button type="submit" class="follow-btn"><i class="fa fa-reply"></i> Send <i
                                    class="fa fa-angle-right"></i></button>
                        </div>
                    </form>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <div class="product__page__content">
                        <div class="product__page__title">
                            <div class="section-title">
                                <h5><i class="fa fa-envelope"></i> Email</h5>
                            </div>
                        </div>
                        <h4 style="text-indent: 15px;">{{ $contact->email ?? 'N/A' }}</h4>
                    </div>

                    <div class="product__page__content mt-3">
                        <div class="product__page__title">
                            <div class="section-title">
                                <h5><i class="fa fa-phone"></i> Contact #</h5>
                            </div>
                        </div>
                        <h4 style="text-indent: 15px;">{{ $contact->contact ?? 'N/A' }}</h4>
                    </div>

                    <div class="product__page__content mt-3">
                        <div class="product__page__title">
                            <div class="section-title">
                                <h5><i class="fa fa-map-marker"></i> Address</h5>
                            </div>
                        </div>
                        <h4 style="text-indent: 15px;">{{ $contact->address ?? 'N/A' }}</h4>
                    </div>

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
    <!-- Contact Section End -->
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

            $('#contactForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'POST',
                    url: '/contactInquiries',
                    data: $('#contactForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            $('#contactForm').trigger('reset');
                            showSuccessMessage(response.msg);
                        }
                    },
                    error: function(jqXHR) {
                        if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                            let errors = jqXHR.responseJSON.errors;
                            let errorMsg = `${jqXHR.responseJSON.msg}\n`;
                            for (const [field, messages] of Object.entries(errors)) {
                                errorMsg += `- ${messages.join(', ')}\n`;
                            }
                            showErrorMessage(errorMsg);
                        } else {
                            showErrorMessage("An unexpected error occurred. Please try again.");
                        }
                    }
                });
            });
        });
    </script>
@endsection
