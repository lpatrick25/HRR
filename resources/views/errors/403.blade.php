@extends('homepage.base')

@section('APP-TITLE', '403 Forbidden')

@section('custom-css')
    <style>
        .error-container {
            text-align: center;
            padding: 80px 20px;
            animation: fadeIn 0.8s ease-in-out;
        }

        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #dc3545;
            text-shadow: 3px 3px 0px rgba(0, 0, 0, 0.1);
        }

        .error-message {
            font-size: 26px;
            font-weight: bold;
            color: #333;
        }

        .error-description {
            font-size: 18px;
            color: #777;
            margin-bottom: 20px;
        }

        .error-container a.btn {
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 30px;
            transition: all 0.3s ease-in-out;
        }

        .error-container a.btn:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        .search-box {
            max-width: 400px;
            margin: 20px auto;
        }

        /* Sidebar */
        .resort__details__sidebar {
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

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@section('APP-CONTENT')
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="{{ route('homepage-homepage') }}"><i class="fa fa-home"></i> Home</a>
                        <span>404 Not Found</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="resort-details spad">
        <div class="container">
            <div class="resort__details__content">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="error-container">
                            <div class="error-code">403</div>
                            <div class="error-message">Access Denied</div>
                            <div class="error-description">
                                You don’t have permission to view this page.
                            </div>

                            <!-- Search Box (Optional) -->
                            <form action="{{ route('homepage-homepage') }}" method="GET" class="search-box">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search our site..."
                                        aria-label="Search">
                                    <div class="input-group-append">
                                        <button class="btn btn-danger" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>

                            <a href="{{ route('homepage-homepage') }}" class="btn btn-danger mt-3">
                                <i class="fa fa-arrow-left"></i> Back to Home
                            </a>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <div class="resort__details__sidebar p-4 border rounded">
                            <h5 class="mb-3"><i class="fa fa-map-marker"></i> Belle's Bistro Resort and Hotel</h5>
                            <div class="map-container">
                                <iframe style="width: 100%; height: 250px; border-radius: 5px; border: none;"
                                    src="https://www.google.com/maps/embed/v1/place?q=Poblacion+Zone+1,+Mayorga,+Leyte,+Eastern+Visayas,+Philippines,+Mayorga,+Philippines,&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8">
                                </iframe>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
