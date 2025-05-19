@extends('layouts.base')
@section('APP_TITLE')
    Food Management
@endsection
@section('active_food')
    active
@endsection
@section('APP_CONTENT')
    <div class="row">
        <div class="col-lg-3">
            <div class="inbox-email-menu-list compose-b-mg-30 shadow-reset">
                <div class="compose-email">
                    <a href="#"><i class="fa fa-building"></i> Food Menu</a>
                </div>
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a data-toggle="tab" href="#hotel_type">
                            <span class="inbox-icon">
                                <i class="fa fa-list-alt"></i>
                            </span>
                            Food Category
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#hotel_food">
                            <span class="inbox-icon">
                                <i class="fa fa-bed"></i>
                            </span>
                            Food List
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
                        data-show-columns="true" data-url="/foodCategories">
                        <thead>
                            <tr>
                                <th data-field="count">ID</th>
                                <th data-field="category_name">Name</th>
                                <th data-field="action">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div id="hotel_food" class="tab-pane fade in animated fadeIn shadow-reset custom-inbox-message">
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
                        data-show-columns="true" data-url="/foods">
                        <thead>
                            <tr>
                                <th data-field="count">ID</th>
                                <th data-field="food_name">Food Name</th>
                                <th data-field="food_category_id">Food Category</th>
                                <th data-field="food_status">Food Status</th>
                                <th data-field="food_price">Food Price</th>
                                <th data-field="food_unit">Food Unit</th>
                                <th data-field="action">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('food.add_category')
    @include('food.update_category')
    @include('food.add_food')
    @include('food.update_food')
@endsection
@section('APP_SCRIPT')
    <script type="text/javascript">
        let categoryID, foodID;

        function view_category(category_id) {
            $.ajax({
                method: 'GET',
                url: `/foodCategories/${category_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response) {
                        categoryID = response.id;
                        $('#updateCategoryForm').find('input[id=category_name]').val(response.category_name);
                        $('#updateCategoryModal').modal({
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

        function trash_category(category_id) {
            $.ajax({
                method: 'DELETE',
                url: `/foodCategories/${category_id}`,
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

        function view_food(food_id) {
            $.ajax({
                method: 'GET',
                url: `/foods/${food_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response) {
                        foodID = response.id;
                        $('#updateFoodForm').find('input[id=food_name]').val(response.food_name);
                        $('#updateFoodForm').find('select[id=food_category_id]').val(response.food_category_id);
                        $('#updateFoodForm').find('input[id=food_status]').val(response.food_status);
                        $('#updateFoodForm').find('input[id=food_price]').val(response.food_price);
                        $('#updateFoodForm').find('select[id=food_unit]').val(response.food_unit);
                        $('select').trigger("change");
                        $('select').click();
                        $('#updateFoodModal').modal({
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

        function trash_food(food_id) {
            $.ajax({
                method: 'DELETE',
                url: `/foods/${food_id}`,
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

        function refreshFoodCategoriesDropdown() {
            $.ajax({
                method: 'GET',
                url: '/foodCategories/getAll',
                dataType: 'JSON',
                success: function(response) {
                    const $dropdown1 = $('#addFoodForm').find('select[id=food_category_id]');
                    const $dropdown2 = $('#updateFoodForm').find('select[id=food_category_id]');

                    $dropdown1.empty();
                    $dropdown2.empty();

                    response.forEach(function(type) {
                        $dropdown1.append(
                            $('<option>', {
                                value: type.id,
                                text: type.category_name
                            })
                        );
                        $dropdown2.append(
                            $('<option>', {
                                value: type.id,
                                text: type.category_name
                            })
                        );
                    });
                },
                error: function() {
                    showErrorMessage("An unexpected error occurred. Please try again.");
                }
            });
        }

        $(document).ready(function() {
            $('#add-btn-one').click(function() {
                event.preventDefault();

                $('#addCategoryModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });

            $('#add-btn-two').click(function() {
                event.preventDefault();

                $('#addFoodModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });

            $('#addFoodForm').find('input[id=food_price]').TouchSpin({
                min: 1,
                max: 10000,
                decimals: 2,
                postfix: 'PHP',
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            $('#updateFoodForm').find('input[id=food_price]').TouchSpin({
                min: 1,
                max: 10000,
                decimals: 2,
                postfix: 'PHP',
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            $('#addCategoryForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'POST',
                    url: '/foodCategories',
                    data: $('#addCategoryForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            $('#addCategoryModal').modal('hide');
                            $('#addCategoryForm').trigger('reset');
                            $('#table1').bootstrapTable('refresh');
                            showSuccessMessage(response.msg);

                            // Call a function to refresh the dropdown
                            refreshFoodCategoriesDropdown();
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

            $('#updateCategoryForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'PUT',
                    url: `/foodCategories/${categoryID}`,
                    data: $('#updateCategoryForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            $('#updateCategoryModal').modal('hide');
                            $('#updateCategoryForm').trigger('reset');
                            $('#table1').bootstrapTable('refresh');
                            showSuccessMessage(response.msg);

                            // Call a function to refresh the dropdown
                            refreshFoodCategoriesDropdown();
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

            $('#addFoodForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'POST',
                    url: '/foods',
                    data: $('#addFoodForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            $('#addFoodModal').modal('hide');
                            $('#addFoodForm').trigger('reset');
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

            $('#updateFoodForm').submit(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'PUT',
                    url: `/foods/${foodID}`,
                    data: $('#updateFoodForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            $('#updateFoodModal').modal('hide');
                            $('#updateFoodForm').trigger('reset');
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
