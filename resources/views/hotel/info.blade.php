@extends('layouts.base')
@section('APP_TITLE')
    Manage Room
@endsection
@section('active_hotel')
    active
@endsection
@section('APP_CONTENT')
    <div class="row">

        <div class="col-lg-6">
            <div class="contact-client-single ct-client-b-mg-30 ct-client-b-mg-30-n shadow-reset">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="contact-client-img text-center" style="position: relative;">
                            <a href="#">
                                <img src="{{ $roomInfo->picture ? asset($roomInfo->picture) : asset('img/profile/1.jpg') }}"
                                    id="room-thumbnail" alt="Room Image"
                                    style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                            </a>
                            <h1 style="margin-top: 10px;">
                                <a href="#" class="btn btn-custon-rounded-three btn-primary btn-md btn-block"
                                    id="add-btn-three">
                                    <i class="fa fa-upload"></i> Upload
                                </a>
                            </h1>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="contact-client-content">
                            <h2><a href="#" style="text-decoration: none; color: inherit;">Name:
                                    {{ $roomInfo->room_name }}</a></h2>
                            <p><i class="fa fa-map-marker"></i> <strong>Type:</strong> {{ $roomInfo->hotelType->type_name }}
                            </p>
                        </div>

                        <div class="contact-client-address" style="margin-top: 10px;">
                            <p class="address-client-ct"><strong>Status:</strong> {{ ucfirst($roomInfo->room_status) }}</p>
                            <p class="address-client-ct"><strong>Rate:</strong> {{ number_format($roomInfo->room_rate, 2) }}
                                PHP</p>
                            <p class="address-client-ct"><strong>Capacity:</strong> {{ $roomInfo->room_capacity }}
                                Person{{ $roomInfo->room_capacity > 1 ? 's' : '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sparkline12-list mg-t-30 shadow-reset">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <h1>Room Images</h1>
                        <div class="sparkline12-outline-icon">
                            <button type="button" class="btn btn-custon-rounded-three btn-primary" id="add-btn-two">
                                <span class="fa fa-file-image-o"></span> Upload
                            </button>
                        </div>
                    </div>
                </div>
                <div id="roomPicturesContainer" class="sparkline12-graph clearfix">
                    @include('partials.room_pictures', ['pictures' => $roomInfo->pictures])
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="sparkline12-list shadow-reset">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <h1>Amenities</h1>
                    </div>
                </div>
                <div class="sparkline12-graph">
                    <div class="button-style-three" id="toolbar1">
                        <button type="button" class="btn btn-custon-rounded-three btn-primary" id="add-btn-one">
                            <span class="adminpro-icon adminpro-plus-add-button-in"></span> Add New
                        </button>
                    </div>
                    <table id="table1" data-toggle="table" data-click-to-select="true" data-single-select="true"
                        data-url="/hotelAmenities" data-pagination="true" data-search="false" data-toolbar="#toolbar1">
                        <thead>
                            <tr>
                                <th class="text-center" data-field="count">ID</th>
                                <th class="text-left" data-field="amenity">Amenities</th>
                                <th class="text-center" data-field="action">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="addAmenityModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="addAmenityForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Amenity
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="login-input-head">
                                <p>Amenity: <span class="text-danger">*</span></p>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="login-input-area">
                                <input type="text" name="amenity" id="amenity" />
                                <input type="hidden" name="hotel_room_id" id="hotel_room_id" value="{{ $roomInfo->id }}" />
                                <i class="fa fa-file-text-o login-user"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-custon-rounded-three btn-primary">
                        <span class="fa fa-save"></span> Save
                    </button>
                    <button type="button" class="btn btn-custon-rounded-three btn-danger" data-dismiss="modal">
                        <span class="fa fa-times"></span> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="addPictureModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <form id="addPictureForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Picture
                </div>
                <div class="modal-body">
                    <input type="hidden" name="hotel_room_id" id="hotel_room_id" value="{{ $roomInfo->id }}" />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="image-crop">
                                <img src="{{ asset('img/cropper/1.jpg') }}" alt="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="preview-img-pro-ad">
                                <h4>Preview image</h4>
                                <div class="img-preview img-preview-custom"></div>
                                <hr>
                                <div class="btn-group images-cropper-pro">
                                    <label title="Upload image file" for="inputImage"
                                        class="btn btn-block btn-custon-rounded-three btn-primary img-cropper-cp">
                                        <input type="file" accept="image/*" name="file" class="inputImage"
                                            id="inputImage" class="hide">
                                    </label>
                                </div>
                                <hr>
                                <div class="btn-group images-action-pro">
                                    <div class="row">
                                        <div class="col-lg-6" style="padding: 5px;">
                                            <button type="button"
                                                class="btn btn-block btn-custon-rounded-three btn-info zoomIn">
                                                <i class="fa fa-search-plus"></i> Zoom In
                                            </button>
                                        </div>
                                        <div class="col-lg-6" style="padding: 5px;">
                                            <button type="button"
                                                class="btn btn-block btn-custon-rounded-three btn-info zoomOut">
                                                <i class="fa fa-search-minus"></i> Zoom Out
                                            </button>
                                        </div>
                                        <div class="col-lg-6" style="padding: 5px;">
                                            <button type="button"
                                                class="btn btn-block btn-custon-rounded-three btn-info rotateLeft">
                                                <i class="fa fa-rotate-left"></i> Rotate Left
                                            </button>
                                        </div>
                                        <div class="col-lg-6" style="padding: 5px;">
                                            <button type="button"
                                                class="btn btn-block btn-custon-rounded-three btn-info rotateRight">
                                                <i class="fa fa-rotate-right"></i> Rotate Right
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-custon-rounded-three btn-primary">
                        <span class="fa fa-save"></span> Upload
                    </button>
                    <button type="button" class="btn btn-custon-rounded-three btn-danger" data-dismiss="modal">
                        <span class="fa fa-times"></span> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="addRoomPictureModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <form id="addRoomPictureForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Room Picture
                </div>
                <div class="modal-body">
                    <input type="hidden" name="hotel_room_id" id="hotel_room_id" value="{{ $roomInfo->id }}" />
                    <div class="row">
                        <div class="col-md-6">
                            <div class="image-crop">
                                <img src="{{ asset('img/cropper/1.jpg') }}" alt="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="preview-img-pro-ad">
                                <h4>Preview image</h4>
                                <div class="img-preview img-preview-custom"></div>
                                <hr>
                                <div class="btn-group images-cropper-pro">
                                    <label title="Upload image file" for="inputImage"
                                        class="btn btn-block btn-custon-rounded-three btn-primary img-cropper-cp">
                                        <input type="file" accept="image/*" name="file" class="inputImage"
                                            id="inputImage" class="hide">
                                    </label>
                                </div>
                                <hr>
                                <div class="btn-group images-action-pro">
                                    <div class="row">
                                        <div class="col-lg-6" style="padding: 5px;">
                                            <button type="button"
                                                class="btn btn-block btn-custon-rounded-three btn-info zoomIn">
                                                <i class="fa fa-search-plus"></i> Zoom In
                                            </button>
                                        </div>
                                        <div class="col-lg-6" style="padding: 5px;">
                                            <button type="button"
                                                class="btn btn-block btn-custon-rounded-three btn-info zoomOut">
                                                <i class="fa fa-search-minus"></i> Zoom Out
                                            </button>
                                        </div>
                                        <div class="col-lg-6" style="padding: 5px;">
                                            <button type="button"
                                                class="btn btn-block btn-custon-rounded-three btn-info rotateLeft">
                                                <i class="fa fa-rotate-left"></i> Rotate Left
                                            </button>
                                        </div>
                                        <div class="col-lg-6" style="padding: 5px;">
                                            <button type="button"
                                                class="btn btn-block btn-custon-rounded-three btn-info rotateRight">
                                                <i class="fa fa-rotate-right"></i> Rotate Right
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-custon-rounded-three btn-primary">
                        <span class="fa fa-save"></span> Upload
                    </button>
                    <button type="button" class="btn btn-custon-rounded-three btn-danger" data-dismiss="modal">
                        <span class="fa fa-times"></span> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('APP_SCRIPT')
    <script type="text/javascript">
        function trash_amenity(amenity_id) {
            $.ajax({
                method: 'DELETE',
                url: `/hotelAmenities/${amenity_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response) {
                        $('#table1').bootstrapTable('refresh');
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
        }

        function trash_picture(picture_id) {
            $.ajax({
                method: 'DELETE',
                url: `/hotelPictures/${picture_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response.valid) {
                        showSuccessMessage(response.msg);
                        reloadRoomPictures();
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
        }

        function reloadRoomPictures() {
            $.ajax({
                method: 'GET',
                url: `/hotelPictures/{{ $roomInfo->id }}`, // Create this route in your controller
                dataType: 'html',
                success: function(response) {
                    $('#roomPicturesContainer').html(response); // Replace image container
                },
                error: function() {
                    showErrorMessage('Failed to refresh room pictures.');
                }
            });
        }

        $(document).ready(function() {
            var cropperHotelPicture = null;
            var cropperRoomPicture = null;

            $('#add-btn-one').click(function() {
                event.preventDefault();

                $('#addAmenityModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });

            // Open Add Picture Modal (Hotel)
            $('#add-btn-two').click(function(event) {
                event.preventDefault();
                $('#addPictureModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                }).on('shown.bs.modal', function() {
                    const image = document.querySelector('#addPictureModal .image-crop > img');
                    if (!cropperHotelPicture) {
                        cropperHotelPicture = new Cropper(image, {
                            aspectRatio: 1.618,
                            viewMode: 1,
                            preview: '#addPictureModal .img-preview',
                        });
                    }
                }).on('hidden.bs.modal', function() {
                    if (cropperHotelPicture) {
                        cropperHotelPicture.destroy();
                        cropperHotelPicture = null;
                    }
                });
            });

            // Open Add Room Picture Modal
            $('#add-btn-three').click(function(event) {
                event.preventDefault();
                $('#addRoomPictureModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                }).on('shown.bs.modal', function() {
                    const image = document.querySelector('#addRoomPictureModal .image-crop > img');
                    if (!cropperRoomPicture) {
                        cropperRoomPicture = new Cropper(image, {
                            aspectRatio: 1.618,
                            viewMode: 1,
                            preview: '#addRoomPictureModal .img-preview',
                        });
                    }
                }).on('hidden.bs.modal', function() {
                    if (cropperRoomPicture) {
                        cropperRoomPicture.destroy();
                        cropperRoomPicture = null;
                    }
                });
            });

            $('#addAmenityForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'POST',
                    url: '/hotelAmenities',
                    data: $('#addAmenityForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            $('#addAmenityModal').modal('hide');
                            $('#addAmenityForm').trigger('reset');
                            $('#table1').bootstrapTable('refresh');
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

            // Image Upload for Hotel Picture Modal
            $('#addPictureModal #inputImage').on('change', function(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (cropperHotelPicture) {
                        cropperHotelPicture.replace(e.target.result);
                    }
                };
                reader.readAsDataURL(file);
            });

            // Image Upload for Room Picture Modal
            $('#addRoomPictureModal #inputImage').on('change', function(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (cropperRoomPicture) {
                        cropperRoomPicture.replace(e.target.result);
                    }
                };
                reader.readAsDataURL(file);
            });

            // Cropper Controls (Zoom, Rotate) - Generic handler for both modals
            $(document).on('click', '.zoomIn', function() {
                const cropper = getActiveCropper($(this));
                if (cropper) cropper.zoom(0.1);
            });

            $(document).on('click', '.zoomOut', function() {
                const cropper = getActiveCropper($(this));
                if (cropper) cropper.zoom(-0.1);
            });

            $(document).on('click', '.rotateLeft', function() {
                const cropper = getActiveCropper($(this));
                if (cropper) cropper.rotate(-45);
            });

            $(document).on('click', '.rotateRight', function() {
                const cropper = getActiveCropper($(this));
                if (cropper) cropper.rotate(45);
            });

            // Submit Hotel Picture
            $('#addPictureForm').submit(function(event) {
                event.preventDefault();

                if (!cropperHotelPicture) {
                    showErrorMessage('Please select and crop an image before submitting.');
                    return;
                }

                cropperHotelPicture.getCroppedCanvas().toBlob(function(blob) {
                    const formData = new FormData($('#addPictureForm')[0]);
                    formData.append('picture', blob, 'cropped_image.png');

                    $.ajax({
                        method: 'POST',
                        url: '/hotelPictures',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.valid) {
                                $('#addPictureModal').modal('hide');
                                $('#addPictureForm')[0].reset();
                                showSuccessMessage(response.msg);
                                reloadRoomPictures();
                            }
                        },
                        error: function() {
                            showErrorMessage('An error occurred. Please try again.');
                        }
                    });
                });
            });

            // Submit Room Picture
            $('#addRoomPictureForm').submit(function(event) {
                event.preventDefault();

                if (!cropperRoomPicture) {
                    showErrorMessage('Please select and crop an image before submitting.');
                    return;
                }

                cropperRoomPicture.getCroppedCanvas().toBlob(function(blob) {
                    const formData = new FormData($('#addRoomPictureForm')[0]);
                    formData.append('picture', blob, 'cropped_image.png');

                    $.ajax({
                        method: 'POST',
                        url: '/hotelRooms/roomPicture',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.valid) {
                                $('#addRoomPictureModal').modal('hide');
                                $('#addRoomPictureForm')[0].reset();
                                $('#room-thumbnail').attr('src', '/' + response.room
                                    .picture + '?' + new Date().getTime());
                                showSuccessMessage(response.msg);
                            }
                        },
                        error: function() {
                            showErrorMessage('An error occurred. Please try again.');
                        }
                    });
                });
            });

            // Helper Function to determine which Cropper instance to use based on the button clicked
            function getActiveCropper($button) {
                const $modal = $button.closest('.modal');
                if ($modal.is('#addPictureModal')) {
                    return cropperHotelPicture;
                } else if ($modal.is('#addRoomPictureModal')) {
                    return cropperRoomPicture;
                }
                return null;
            }
        });
    </script>
@endsection
