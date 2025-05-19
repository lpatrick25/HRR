@extends('homepage.base')
@section('APP-TITLE')
    Resort Cottage Category
@endsection
@section('active-categories')
    active
@endsection
@section('custom-css')
    <style>
        /* Improved Scrollable Picture Containers */
        #roomPicturesHtml,
        #cottagePicturesHtml {
            height: 300px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #f80000 #f1f1f1;
        }

        /* Smooth Scroll */
        #roomPicturesHtml::-webkit-scrollbar,
        #cottagePicturesHtml::-webkit-scrollbar {
            width: 8px;
        }

        #roomPicturesHtml::-webkit-scrollbar-thumb,
        #cottagePicturesHtml::-webkit-scrollbar-thumb {
            background-color: #f80000;
            border-radius: 10px;
        }

        #roomPicturesHtml::-webkit-scrollbar-track,
        #cottagePicturesHtml::-webkit-scrollbar-track {
            background-color: #f1f1f1;
        }

        /* Modal Image Fit */
        #cottage-img {
            object-fit: cover;
            border-radius: 5px;
        }

        /* Active thumbnail styling */
        .active-thumbnail {
            border: 2px solid #f80000 !important;
            opacity: 1;
        }

        #cottagePicturesHtml img {
            cursor: pointer;
            transition: opacity 0.3s;
        }

        #cottagePicturesHtml img:hover {
            opacity: 0.8;
        }

        .card-img-wrapper {
            position: relative;
            width: 100%;
            padding-top: 56.25%;
            /* 16:9 Aspect Ratio */
            overflow: hidden;
            background-color: #f8f9fa;
            /* Optional fallback */
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .card-img-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Crop images to fill the area */
        }

        /* Responsive Adjustments */
        @media only screen and (max-width: 767px) {
            .product__sidebar__view {
                margin-bottom: 30px;
            }

            #roomPicturesHtml,
            #cottagePicturesHtml {
                height: auto;
                max-height: 200px;
            }

            #vieCottage .modal-dialog {
                margin: 0.5rem;
            }

            #cottage-img {
                height: auto;
                max-height: 200px;
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
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="{{ route('homepage-homepage') }}"><i class="fa fa-home"></i> Home</a>
                        <a href="{{ route('homepage-categories') }}">Categories</a>
                        <span>Resort Cottages</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Product Section Begin -->
    <section class="product-page spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="product__page__content">
                        <div class="product__page__title">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="section-title">
                                        <h4>Cottage</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($resortCottages as $cottage)
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 d-flex align-items-stretch">
                                    <div class="card mb-4 shadow-sm w-100">
                                        <div class="card-img-wrapper">
                                            <img src="{{ asset($cottage->picture) }}" class="card-img-top"
                                                alt="{{ $cottage->cottage_name }}" loading="lazy">
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $cottage->cottage_name }}</h5>
                                            <div class="mt-auto">
                                                <a href="{{ route('homepage-cottageDetails', encrypt($cottage->id)) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fa fa-eye"></i> View Details
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="view_cottage(`{{ encrypt($cottage->id) }}`)">
                                                    <i class="fa fa-search"></i> Discover More
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
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
    <div id="vieCottage" class="modal animated fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header section-title">
                    <h5>Cottage</h5>
                </div>
                <div class="modal-body">
                    <div class="resort__details__content" style="margin-bottom: 0px;">
                        <div class="row">
                            <div class="col-lg-9">
                                <img class="resort__details__pic set-bg" id="cottage-img"
                                    style="height: 300px; width: 100%;" alt="Cottage Image">
                            </div>
                            <div class="col-lg-3">
                                <div class="row" id="cottagePicturesHtml"></div>
                            </div>
                            <div class="col-lg-12">
                                <div class="resort__details__text">
                                    <div class="resort__details__title">
                                        <h5 class="text-center font-weight-bold pt-2" id="cottage_name">Name Here</h5>
                                    </div>
                                    <p class="text-center" id="cottage_details">Details Here</p>
                                    <div class="section-title">
                                        <h5 class="text-center">More Info</h5>
                                        <div class="hr-one"></div>
                                        <div class="resort__details__widget" id="cottageAmenitiesHtml"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-lg btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('APP-SCRIPT')
    <script type="text/javascript">
        function view_cottage(encryptedId) {
            $('#cottage-img').attr('src', '');
            $('#cottage_name').text('Loading...');
            $('#cottage_details').text('');
            $('#cottageAmenitiesHtml').html('');
            $('#cottagePicturesHtml').html('');

            $("#vieCottage").modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');

            $.ajax({
                method: 'POST',
                url: "{{ route('get-cottage-info') }}",
                data: {
                    cottage_id: encryptedId
                },
                cache: false,
                success: function(data) {
                    $('#cottage-img').attr('src', data.image);
                    $('#cottage_name').text(data.cottage_name);
                    $('#cottage_details').text(data.cottage_details);
                    $('#cottageAmenitiesHtml').html(data.cottageAmenitiesHtml);
                    $('#cottagePicturesHtml').html(data.cottagePicturesHtml);

                    let imageList = data.cottage_pictures;
                    let currentIndex = 0;
                    if (imageList.length > 1) {
                        setInterval(function() {
                            currentIndex = (currentIndex + 1) % imageList.length;
                            $('#cottage-img').fadeOut(300, function() {
                                $(this).attr('src', imageList[currentIndex]).fadeIn(300);
                            });

                            $('[id^="countCottage-"]').removeClass('active-thumbnail');
                            $('#countCottage-' + currentIndex).addClass('active-thumbnail');
                        }, 5000);
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
        }

        function set_cottage(image, index) {
            $('#cottage-img').fadeOut(200, function() {
                $(this).attr('src', image).fadeIn(200);
            });

            $('[id^="countCottage-"]').removeClass('active-thumbnail');
            $('#countCottage-' + index).addClass('active-thumbnail');
        }
    </script>
@endsection
