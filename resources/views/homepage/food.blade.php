@extends('homepage.base')
@section('APP-TITLE')
    Hotel Room Category
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
        #room-img {
            object-fit: cover;
            border-radius: 5px;
        }

        /* Active thumbnail styling */
        .active-thumbnail {
            border: 2px solid #f80000 !important;
            opacity: 1;
        }

        #roomPicturesHtml img {
            cursor: pointer;
            transition: opacity 0.3s;
        }

        #roomPicturesHtml img:hover {
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

            #viewRoom .modal-dialog {
                margin: 0.5rem;
            }

            #room-img {
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
                        <span>Restaurant</span>
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
                                        <h4>Room</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach ($foods as $food)
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 d-flex align-items-stretch">
                                    <div class="card mb-4 shadow-sm w-100">
                                        <div class="card-img-wrapper">
                                            <img src="{{ asset($food->picture) }}" class="card-img-top"
                                                alt="{{ $food->food_name }}" loading="lazy">
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $food->food_name }}</h5>
                                            <div class="mt-auto">
                                                <a href="{{ route('homepage-categoryFood', encrypt($food->id)) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fa fa-eye"></i> View Details
                                                </a>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="view_food(`{{ encrypt($food->id) }}`)">
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
                                <h5>Belle's Bistro Resort and Hotel</h5>
                            </div>
                            <div class="map-container">
                                <iframe style="width: 100%; height: 100%; border: none;"
                                    src="https://www.google.com/maps/embed/v1/place?q=Poblacion+Zone+1,+Mayorga,+Leyte,+Eastern+Visayas,+Philippines,+Mayorga,+Philippines,&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="viewRoom" class="modal animated fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header section-title">
                    <h5>Room</h5>
                </div>
                <div class="modal-body">
                    <div class="resort__details__content" style="margin-bottom: 0px;">
                        <div class="row">
                            <div class="col-lg-9">
                                <img id="room-img" class="resort__details__pic set-bg" style="height: 300px; width: 100%;">
                            </div>
                            <div class="col-lg-3">
                                <div class="row" id="roomPicturesHtml"></div>
                            </div>
                            <div class="col-lg-12">
                                <div class="resort__details__text">
                                    <div class="resort__details__title">
                                        <h5 class="text-center" style="font-weight: bolder; padding-top: 10px;"
                                            id="room_name">Name Here</h5>
                                    </div>
                                    <p class="text-center" id="room_details">Details Here</p>
                                    <div class="section-title">
                                        <h5 class="text-center">Amenities</h5>
                                        <div class="hr-one"></div>
                                    </div>
                                    <div class="resort__details__widget" id="roomAmenitiesHtml"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-lg btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('APP-SCRIPT')
    <script type="text/javascript">
        function view_room(encryptedId) {
            $('#room-img').attr('src', '');
            $('#room_name').text('Loading...');
            $('#room_details').text('');
            $('#roomAmenitiesHtml').html('');
            $('#roomPicturesHtml').html('');

            $("#viewRoom").modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');

            $.ajax({
                method: 'POST',
                url: "{{ route('get-room-info') }}",
                data: {
                    room_id: encryptedId,
                },
                cache: false,
                dataType: 'json',
                success: function(data) {
                    $('#room-img').attr('src', data.image);
                    $('#room_name').text(data.room_name);
                    $('#room_details').text(data.room_details);
                    $('#roomAmenitiesHtml').html(data.roomAmenitiesHtml);
                    $('#roomPicturesHtml').html(data.roomPicturesHtml);

                    let imageList = data.room_pictures;
                    let currentIndex = 0;
                    if (imageList.length > 1) {
                        setInterval(function() {
                            currentIndex = (currentIndex + 1) % imageList.length;
                            $('#room-img').fadeOut(300, function() {
                                $(this).attr('src', imageList[currentIndex]).fadeIn(300);
                            });

                            $('[id^="countRoom-"]').removeClass('active-thumbnail');
                            $('#countRoom-' + currentIndex).addClass('active-thumbnail');
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
            $('#room-img').fadeOut(200, function() {
                $(this).attr('src', image).fadeIn(200);
            });

            $('[id^="countRoom-"]').removeClass('active-thumbnail');
            $('#countRoom-' + index).addClass('active-thumbnail');
        }
    </script>
@endsection
