@extends('layouts.base')
@section('APP_TITLE')
    Manage Food
@endsection
@section('active_food')
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
                                <img src="{{ $foodInfo->picture ? asset($foodInfo->picture) : asset('img/profile/1.jpg') }}"
                                    id="food-thumbnail" alt="Food Image"
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
                                    {{ $foodInfo->food_name }}</a></h2>
                            <p> <strong>Category:</strong>
                                {{ $foodInfo->foodCategory->category_name }}
                            </p>
                        </div>

                        <div class="contact-client-address" style="margin-top: 10px;">
                            <p class="address-client-ct"><strong>Status:</strong>
                                {{ ucfirst($foodInfo->food_status) }}</p>
                            <p class="address-client-ct"><strong>Rate:</strong>
                                {{ number_format($foodInfo->food_price, 2) }}
                                PHP</p>
                            <p class="address-client-ct"><strong>Capacity:</strong>Per {{ $foodInfo->food_unit }}
                                {{ $foodInfo->food_unit > 1 ? 's' : '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="sparkline12-list shadow-reset">
                <div class="sparkline12-hd">
                    <div class="main-sparkline12-hd">
                        <h1>Food Images</h1>
                        <div class="sparkline12-outline-icon">
                            <button type="button" class="btn btn-custon-rounded-three btn-primary" id="add-btn-two">
                                <span class="fa fa-file-image-o"></span> Upload
                            </button>
                        </div>
                    </div>
                </div>
                <div id="foodPicturesContainer" class="sparkline12-graph clearfix">
                    @include('partials.food_pictures', ['pictures' => $foodInfo->pictures])
                </div>
            </div>
        </div>
    </div>
    <div id="addPictureModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <form id="addPictureForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Picture
                </div>
                <div class="modal-body">
                    <input type="hidden" name="food_id" id="food_id" value="{{ $foodInfo->id }}" />
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
    <div id="addFoodPictureModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <form id="addFoodPictureForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Food Picture
                </div>
                <div class="modal-body">
                    <input type="hidden" name="food_id" id="food_id" value="{{ $foodInfo->id }}" />
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
                url: `/resortAmenities/${amenity_id}`,
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
                url: `/resortPictures/${picture_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response.valid) {
                        showSuccessMessage(response.msg);
                        reloadFoodPictures();
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

        function reloadFoodPictures() {
            $.ajax({
                method: 'GET',
                url: `/foodPictures/{{ $foodInfo->id }}`, // Create this route in your controller
                dataType: 'html',
                success: function(response) {
                    $('#foodPicturesContainer').html(response); // Replace image container
                },
                error: function() {
                    showErrorMessage('Failed to refresh food pictures.');
                }
            });
        }

        $(document).ready(function() {
            var cropperFoodPicture = null;

            // Open Add Picture Modal (Resort)
            $('#add-btn-two').click(function(event) {
                event.preventDefault();
                $('#addPictureModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                }).on('shown.bs.modal', function() {
                    const image = document.querySelector('#addPictureModal .image-crop > img');
                    if (!cropperFoodPicture) {
                        cropperFoodPicture = new Cropper(image, {
                            aspectRatio: 1.618,
                            viewMode: 1,
                            preview: '#addPictureModal .img-preview',
                        });
                    }
                }).on('hidden.bs.modal', function() {
                    if (cropperFoodPicture) {
                        cropperFoodPicture.destroy();
                        cropperFoodPicture = null;
                    }
                });
            });

            // // Open Add Food Picture Modal
            $('#add-btn-three').click(function(event) {
                event.preventDefault();
                $('#addFoodPictureModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                }).on('shown.bs.modal', function() {
                    const image = document.querySelector(
                        '#addFoodPictureModal .image-crop > img');
                    if (!cropperFoodPicture) {
                        cropperFoodPicture = new Cropper(image, {
                            aspectRatio: 1.618,
                            viewMode: 1,
                            preview: '#addFoodPictureModal .img-preview',
                        });
                    }
                }).on('hidden.bs.modal', function() {
                    if (cropperFoodPicture) {
                        cropperFoodPicture.destroy();
                        cropperFoodPicture = null;
                    }
                });
            });

            // Image Upload for Food Picture Modal
            $('#addFoodPictureModal #inputImage').on('change', function(event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (cropperFoodPicture) {
                        cropperFoodPicture.replace(e.target.result);
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

            // Submit Resort Picture
            $('#addPictureForm').submit(function(event) {
                event.preventDefault();

                if (!cropperFoodPicture) {
                    showErrorMessage('Please select and crop an image before submitting.');
                    return;
                }

                cropperFoodPicture.getCroppedCanvas().toBlob(function(blob) {
                    const formData = new FormData($('#addPictureForm')[0]);
                    formData.append('picture', blob, 'cropped_image.png');

                    $.ajax({
                        method: 'POST',
                        url: '/foodPictures',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.valid) {
                                $('#addPictureModal').modal('hide');
                                $('#addPictureForm')[0].reset();
                                showSuccessMessage(response.msg);
                                reloadFoodPictures();
                            }
                        },
                        error: function() {
                            showErrorMessage('An error occurred. Please try again.');
                        }
                    });
                });
            });

            // Submit Food Picture
            $('#addFoodPictureForm').submit(function(event) {
                event.preventDefault();

                if (!cropperFoodPicture) {
                    showErrorMessage('Please select and crop an image before submitting.');
                    return;
                }

                cropperFoodPicture.getCroppedCanvas().toBlob(function(blob) {
                    const formData = new FormData($('#addFoodPictureForm')[0]);
                    formData.append('picture', blob, 'cropped_image.png');

                    $.ajax({
                        method: 'POST',
                        url: '/foods/foodPicture',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'JSON',
                        success: function(response) {
                            if (response.valid) {
                                $('#addFoodPictureModal').modal('hide');
                                $('#addFoodPictureForm')[0].reset();
                                $('#food-thumbnail').attr('src', '/' + response
                                    .food
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
                    return cropperFoodPicture;
                } else if ($modal.is('#addFoodPictureModal')) {
                    return cropperFoodPicture;
                }
                return null;
            }
        });
    </script>
@endsection
