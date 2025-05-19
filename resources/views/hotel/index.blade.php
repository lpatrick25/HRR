@extends('layouts.base')
@section('APP_TITLE')
    Hotel Management
@endsection
@section('active_hotel')
    active
@endsection
@section('APP_CONTENT')
    <div class="row">
        <div class="col-lg-3">
            <div class="inbox-email-menu-list compose-b-mg-30 shadow-reset">
                <div class="compose-email">
                    <a href="#"><i class="fa fa-building"></i> Hotel Menu</a>
                </div>
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" href="#hotel_type">
                            <span class="inbox-icon">
                                <i class="fa fa-list-alt"></i>
                            </span>
                            Hotel Types
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#hotel_room">
                            <span class="inbox-icon">
                                <i class="fa fa-bed"></i>
                            </span>
                            Hotel Rooms
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#hotel_review">
                            <span class="inbox-icon">
                                <i class="fa fa-bed"></i>
                            </span>
                            Hotel Reviews
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="tab-content">
                <div id="hotel_type" class="tab-pane fade in animated fadeIn custom-inbox-message shadow-reset active">
                    <div class="button-style-three" id="toolbar1">
                        <button type="button" class="btn btn-custon-rounded-three btn-primary" id="add-btn-one">
                            <span class="adminpro-icon adminpro-plus-add-button-in"></span> Add New
                        </button>
                    </div>
                    <table id="table1" data-toggle="table" data-auto-refresh="true" data-auto-refresh-interval="20"
                        data-fixed-columns="true" data-fixed-number="1" data-i18n-enhance="true"
                        data-mobile-responsive="true" data-multiple-sort="true" data-page-jump-to="true"
                        data-pipeline="true" data-reorder-rows="true" data-sticky-header="true" data-toolbar="#toolbar1"
                        data-pagination="true" data-search="true" data-show-refresh="true" data-show-copy-rows="true"
                        data-show-columns="true" data-url="/hotelTypes">
                        <thead>
                            <tr>
                                <th data-field="count">ID</th>
                                <th data-field="type_name">Name</th>
                                <th data-field="type_description">Description</th>
                                <th data-field="action">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="hotel_room" class="tab-pane fade in animated fadeIn shadow-reset custom-inbox-message">
                    <div class="button-style-three" id="toolbar2">
                        <button type="button" class="btn btn-custon-rounded-three btn-primary" id="add-btn-two">
                            <span class="adminpro-icon adminpro-plus-add-button-in"></span> Add New
                        </button>
                    </div>
                    <table id="table2" data-toggle="table" data-auto-refresh="true" data-auto-refresh-interval="20"
                        data-fixed-columns="true" data-fixed-number="1" data-i18n-enhance="true"
                        data-mobile-responsive="true" data-multiple-sort="true" data-page-jump-to="true"
                        data-pipeline="true" data-reorder-rows="true" data-sticky-header="true" data-toolbar="#toolbar2"
                        data-pagination="true" data-search="true" data-show-refresh="true" data-show-copy-rows="true"
                        data-show-columns="true" data-url="/hotelRooms">
                        <thead>
                            <tr>
                                <th data-field="count">ID</th>
                                <th data-field="picture">Thumbnail</th>
                                <th data-field="room_name">Name</th>
                                <th data-field="hotel_type_id">Type</th>
                                <th data-field="room_status">Status</th>
                                <th data-field="room_rate">Rate</th>
                                <th data-field="room_capacity">Capacity</th>
                                <th data-field="action">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="hotel_review" class="tab-pane fade in animated fadeIn shadow-reset custom-inbox-message">
                    <div class="button-style-three" id="toolbar3"></div>
                    <table id="table2" data-toggle="table" data-auto-refresh="true" data-auto-refresh-interval="20"
                        data-fixed-columns="true" data-fixed-number="1" data-i18n-enhance="true"
                        data-mobile-responsive="true" data-multiple-sort="true" data-page-jump-to="true"
                        data-pipeline="true" data-reorder-rows="true" data-sticky-header="true" data-toolbar="#toolbar3"
                        data-pagination="true" data-search="true" data-show-refresh="true" data-show-copy-rows="true"
                        data-show-columns="true" data-url="/hotelReviews">
                        <thead>
                            <tr>
                                <th data-field="count">#</th>
                                <th data-field="room_name">Room Name</th>
                                <th data-field="room_type">Room Type</th>
                                <th data-field="customer_name">Customer Name</th>
                                <th data-field="customer_review">Review</th>
                                <th data-field="customer_rating">Rating</th>
                                <th data-field="action">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
    @include('hotel.add_type')
    @include('hotel.update_type')
    @include('hotel.add_room')
    @include('hotel.update_room')
@endsection
@section('APP_SCRIPT')
    <script type="text/javascript">
        let typeID, roomID;

        function view_type(type_id) {
            $.ajax({
                method: 'GET',
                url: `/hotelTypes/${type_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response) {
                        typeID = response.id;
                        $('#updateTypeForm').find('input[id=type_name]').val(response.type_name);
                        $('#updateTypeForm').find('input[id=type_description]').val(response.type_description);
                        $('#updateTypeModal').modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true
                        });
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

        function trash_type(type_id) {
            $.ajax({
                method: 'DELETE',
                url: `/hotelTypes/${type_id}`,
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

        function view_room(room_id) {
            $.ajax({
                method: 'GET',
                url: `/hotelRooms/${room_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response) {
                        roomID = response.id;
                        $('#updateRoomForm').find('input[id=room_name]').val(response.room_name);
                        $('#updateRoomForm').find('select[id=hotel_type_id]').val(response.hotel_type_id);
                        $('#updateRoomForm').find('select[id=room_status]').val(response.room_status);
                        $('#updateRoomForm').find('input[id=room_rate]').val(response.room_rate);
                        $('#updateRoomForm').find('input[id=room_capacity]').val(response.room_capacity);
                        $('select').trigger("change");
                        $('select').click();
                        $('#updateRoomModal').modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true
                        });
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

        function trash_room(room_id) {
            $.ajax({
                method: 'DELETE',
                url: `/hotelRooms/${room_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response) {
                        $('#table2').bootstrapTable('refresh');
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

        function refreshHotelTypesDropdown() {
            $.ajax({
                method: 'GET',
                url: '/hotelTypes/getAll',
                dataType: 'JSON',
                success: function(response) {
                    const $dropdown1 = $('#addRoomForm').find('select[id=hotel_type_id]');
                    const $dropdown2 = $('#updateRoomForm').find('select[id=hotel_type_id]');

                    $dropdown1.empty();
                    $dropdown2.empty();

                    response.forEach(function(type) {
                        $dropdown1.append(
                            $('<option>', {
                                value: type.id,
                                text: type.type_name
                            })
                        );
                        $dropdown2.append(
                            $('<option>', {
                                value: type.id,
                                text: type.type_name
                            })
                        );
                    });

                    $('select').trigger("change");
                },
                error: function() {
                    showErrorMessage("An unexpected error occurred. Please try again.");
                }
            });
        }



        function approveReview(reviewId) {
            $.ajax({
                method: 'PUT',
                url: `/hotelReviews/updateStatus/${reviewId}`,
                data: {
                    status: 'Approved'
                },
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response) {
                        $('#table3').bootstrapTable('refresh');
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

        function rejectReview(reviewId) {
            $.ajax({
                method: 'PUT',
                url: `/hotelReviews/updateStatus/${reviewId}`,
                data: {
                    status: 'Rejected'
                },
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response) {
                        $('#table3').bootstrapTable('refresh');
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

        $(document).ready(function() {
            $('#add-btn-one').click(function() {
                event.preventDefault();

                $('#addTypeModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });

            $('#add-btn-two').click(function() {
                event.preventDefault();

                $('#addRoomModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });

            $('#addRoomForm').find('input[id=room_rate]').TouchSpin({
                min: 1,
                max: 10000,
                decimals: 2,
                postfix: 'PHP',
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            $('#addRoomForm').find('input[id=room_capacity]').TouchSpin({
                min: 1,
                max: 10000,
                postfix: 'Person',
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            $('#updateRoomForm').find('input[id=room_rate]').TouchSpin({
                min: 1,
                max: 10000,
                decimals: 2,
                postfix: 'PHP',
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            $('#updateRoomForm').find('input[id=room_capacity]').TouchSpin({
                min: 1,
                max: 10000,
                postfix: 'Person',
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            $('#addTypeForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'POST',
                    url: '/hotelTypes',
                    data: $('#addTypeForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            $('#addTypeModal').modal('hide');
                            $('#addTypeForm').trigger('reset');
                            $('#table1').bootstrapTable('refresh');
                            showSuccessMessage(response.msg);

                            // Call a function to refresh the dropdown
                            refreshHotelTypesDropdown();
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

            $('#updateTypeForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'PUT',
                    url: `/hotelTypes/${typeID}`,
                    data: $('#updateTypeForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            $('#updateTypeModal').modal('hide');
                            $('#updateTypeForm').trigger('reset');
                            $('#table1').bootstrapTable('refresh');
                            showSuccessMessage(response.msg);

                            // Call a function to refresh the dropdown
                            refreshHotelTypesDropdown();
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

            $('#addRoomForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'POST',
                    url: '/hotelRooms',
                    data: $('#addRoomForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            $('#addRoomModal').modal('hide');
                            $('#addRoomForm').trigger('reset');
                            $('#table2').bootstrapTable('refresh');
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

            $('#updateRoomForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'PUT',
                    url: `/hotelRooms/${roomID}`,
                    data: $('#updateRoomForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            $('#updateRoomModal').modal('hide');
                            $('#updateRoomForm').trigger('reset');
                            $('#table2').bootstrapTable('refresh');
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
