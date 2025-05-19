@extends('layouts.base')
@section('APP_TITLE')
    Resort Management
@endsection
@section('active_resort')
    active
@endsection
@section('APP_CONTENT')
    <div class="row">
        <div class="col-lg-3">
            <div class="inbox-email-menu-list compose-b-mg-30 shadow-reset">
                <div class="compose-email">
                    <a href="#"><i class="fa fa-building"></i> Resort Menu</a>
                </div>
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" href="#resort_type">
                            <span class="inbox-icon">
                                <i class="fa fa-list-alt"></i>
                            </span>
                            Resort Types
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#resort_cottage">
                            <span class="inbox-icon">
                                <i class="fa fa-bed"></i>
                            </span>
                            Resort Cottages
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#resort_review">
                            <span class="inbox-icon">
                                <i class="fa fa-bed"></i>
                            </span>
                            Resort Reviews
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="tab-content">
                <div id="resort_type" class="tab-pane fade in animated fadeIn custom-inbox-message shadow-reset active">
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
                        data-show-columns="true" data-url="/resortTypes">
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
                <div id="resort_cottage" class="tab-pane fade in animated fadeIn shadow-reset custom-inbox-message">
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
                        data-show-columns="true" data-url="/resortCottages">
                        <thead>
                            <tr>
                                <th data-field="count">ID</th>
                                <th data-field="picture">Thumbnail</th>
                                <th data-field="cottage_name">Name</th>
                                <th data-field="resort_type_id">Type</th>
                                <th data-field="cottage_status">Status</th>
                                <th data-field="cottage_rate">Rate</th>
                                <th data-field="cottage_capacity">Capacity</th>
                                <th data-field="action">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="resort_review" class="tab-pane fade in animated fadeIn shadow-reset custom-inbox-message">
                    <div class="button-style-three" id="toolbar3"></div>
                    <table id="table3" data-toggle="table" data-auto-refresh="true" data-auto-refresh-interval="20"
                        data-fixed-columns="true" data-fixed-number="1" data-i18n-enhance="true"
                        data-mobile-responsive="true" data-multiple-sort="true" data-page-jump-to="true"
                        data-pipeline="true" data-reorder-rows="true" data-sticky-header="true" data-toolbar="#toolbar3"
                        data-pagination="true" data-search="true" data-show-refresh="true" data-show-copy-rows="true"
                        data-show-columns="true" data-url="/resortReviews">
                        <thead>
                            <tr>
                                <th data-field="count">#</th>
                                <th data-field="cottage_name">Cottage Name</th>
                                <th data-field="cottage_type">Cottage Type</th>
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
    @include('resort.add_type')
    @include('resort.update_type')
    @include('resort.add_cottage')
    @include('resort.update_cottage')
@endsection
@section('APP_SCRIPT')
    <script type="text/javascript">
        let typeID, cottageID;

        function view_type(type_id) {
            $.ajax({
                method: 'GET',
                url: `/resortTypes/${type_id}`,
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
                url: `/resortTypes/${type_id}`,
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

        function view_cottage(cottage_id) {
            $.ajax({
                method: 'GET',
                url: `/resortCottages/${cottage_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response) {
                        cottageID = response.id;
                        $('#updateCottageForm').find('input[id=cottage_name]').val(response.cottage_name);
                        $('#updateCottageForm').find('select[id=resort_type_id]').val(response.resort_type_id);
                        $('#updateCottageForm').find('select[id=cottage_status]').val(response.cottage_status);
                        $('#updateCottageForm').find('input[id=cottage_rate]').val(response.cottage_rate);
                        $('#updateCottageForm').find('input[id=cottage_capacity]').val(response
                            .cottage_capacity);
                        $('select').trigger("change");
                        $('select').click();
                        $('#updateCottageModal').modal({
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

        function trash_cottage(cottage_id) {
            $.ajax({
                method: 'DELETE',
                url: `/resortCottages/${cottage_id}`,
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

        function refreshResortTypesDropdown() {
            $.ajax({
                method: 'GET',
                url: '/resortTypes/getAll',
                dataType: 'JSON',
                success: function(response) {
                    const $dropdown1 = $('#addCottageForm').find('select[id=resort_type_id]');
                    const $dropdown2 = $('#updateCottageForm').find('select[id=resort_type_id]');

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
                },
                error: function() {
                    showErrorMessage("An unexpected error occurred. Please try again.");
                }
            });
        }

        function approveReview(reviewId) {
            $.ajax({
                method: 'PUT',
                url: `/resortReviews/updateStatus/${reviewId}`,
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
                url: `/resortReviews/updateStatus/${reviewId}`,
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

                $('#addCottageModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });

            $('#addCottageForm').find('input[id=cottage_rate]').TouchSpin({
                min: 1,
                max: 10000,
                decimals: 2,
                postfix: 'PHP',
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            $('#addCottageForm').find('input[id=cottage_capacity]').TouchSpin({
                min: 1,
                max: 10000,
                postfix: 'Person',
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            $('#updateCottageForm').find('input[id=cottage_rate]').TouchSpin({
                min: 1,
                max: 10000,
                decimals: 2,
                postfix: 'PHP',
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            $('#updateCottageForm').find('input[id=cottage_capacity]').TouchSpin({
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
                    url: '/resortTypes',
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
                            refreshResortTypesDropdown();
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
                    url: `/resortTypes/${typeID}`,
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
                            refreshResortTypesDropdown();
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

            $('#addCottageForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'POST',
                    url: '/resortCottages',
                    data: $('#addCottageForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            $('#addCottageModal').modal('hide');
                            $('#addCottageForm').trigger('reset');
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

            $('#updateCottageForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'PUT',
                    url: `/resortCottages/${cottageID}`,
                    data: $('#updateCottageForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            $('#updateCottageModal').modal('hide');
                            $('#updateCottageForm').trigger('reset');
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
