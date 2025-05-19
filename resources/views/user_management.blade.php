@extends('layouts.base')
@section('APP_TITLE')
    User Management
@endsection
@section('active_users')
    active
@endsection
@section('APP_CONTENT')
    <div class="button-style-three" id="toolbar1">
        <button type="button" class="btn btn-custon-rounded-three btn-primary" id="add-btn-one">
            <span class="adminpro-icon adminpro-plus-add-button-in"></span> Add New
        </button>
    </div>

    <table id="table1" data-toggle="table" data-auto-refresh="true" data-auto-refresh-interval="20" data-fixed-columns="true"
        data-fixed-number="1" data-i18n-enhance="true" data-mobile-responsive="true" data-multiple-sort="true"
        data-page-jump-to="true" data-pipeline="true" data-reorder-rows="true" data-sticky-header="true"
        data-toolbar="#toolbar1" data-pagination="true" data-search="true" data-show-refresh="true"
        data-show-copy-rows="true" data-show-columns="true" data-url="/users">
        <thead>
            <tr>
                <th data-field="count">ID</th>
                <th data-field="full_name">Name</th>
                <th data-field="email">Email</th>
                <th data-field="phone_number">Phone Number</th>
                <th data-field="role">Role</th>
                <th data-field="action">Actions</th>
            </tr>
        </thead>
    </table>

    {{-- Add User Modal --}}
    <div id="addUserModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="addUserForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add User</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">+63</span>
                            <input type="text" class="form-control" name="phone_number" id="phone_number"
                                data-mask="999-999-9999" placeholder="XXX-XXX-XXXX" required />
                        </div>
                        <small class="text-muted">Enter the remaining 9 digits after +63.</small>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>User Role <span class="text-danger">*</span></label>
                        <select name="user_role" class="form-control" required>
                            <option value="Owner">Owner</option>
                            <option value="Admin">Admin</option>
                            <option value="Front Desk - Hotel">Front Desk - Hotel</option>
                            <option value="Front Desk - Resort">Front Desk - Resort</option>
                            <option value="Front Desk - Food">Front Desk - Food</option>
                            <option value="Customer">Customer</option>
                        </select>
                    </div>
                    <!-- reCAPTCHA v3 Hidden Input -->
                    <input type="hidden" name="recaptcha_token" id="recaptcha_token">
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

    {{-- Update User Modal --}}
    <div id="updateUserModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="updateUserForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Update User</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id">
                    <div class="form-group">
                        <label>First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">+63</span>
                            <input type="text" class="form-control" name="phone_number" id="phone_number"
                                data-mask="999-999-9999" placeholder="XXX-XXX-XXXX" required />
                        </div>
                        <small class="text-muted">Enter the remaining 9 digits after +63.</small>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>User Role <span class="text-danger">*</span></label>
                        <select name="user_role" class="form-control" required>
                            <option value="Owner">Owner</option>
                            <option value="Admin">Admin</option>
                            <option value="Front Desk - Hotel">Front Desk - Hotel</option>
                            <option value="Front Desk - Resort">Front Desk - Resort</option>
                            <option value="Front Desk - Food">Front Desk - Food</option>
                            <option value="Customer">Customer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <!-- reCAPTCHA v3 Hidden Input -->
                    <input type="hidden" name="recaptcha_token" id="recaptcha_token">
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
@endsection
@section('APP_SCRIPT')
    <!-- Load reCAPTCHA v3 Script -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
    <script type="text/javascript">
        let userID;

        function view_user(user_id) {
            $.ajax({
                method: 'GET',
                url: `/users/${user_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response) {
                        userID = response.id;
                        $('#updateUserForm').find('input[name=first_name]').val(response.first_name);
                        $('#updateUserForm').find('input[name=last_name]').val(response.last_name);
                        $('#updateUserForm').find('input[name=phone_number]').val(response.phone_number);
                        $('#updateUserForm').find('input[name=email]').val(response.email);
                        $('#updateUserForm').find('select[name=user_role]').val(response.user_role);

                        $('select').trigger('change');

                        $('#updateUserModal').modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true
                        });
                    }
                },
                error: function(jqXHR) {
                    if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                        let errors = jqXHR.responseJSON.errors;
                        let errorMsg = `${jqXHR.responseJSON.message}\n`;
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

        function trash_user(user_id) {
            $.ajax({
                method: 'DELETE',
                url: `/users/${user_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    $('#table1').bootstrapTable('refresh');
                    showSuccessMessage(response.msg);
                },
                error: function(jqXHR) {
                    if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                        let errors = jqXHR.responseJSON.errors;
                        let errorMsg = `${jqXHR.responseJSON.message}\n`;
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

        function active_user(user_id) {
            $.ajax({
                method: 'PUT',
                url: `/users/activeStatus/${user_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    $('#table1').bootstrapTable('refresh');
                    showSuccessMessage(response.msg);
                },
                error: function(jqXHR) {
                    if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                        let errors = jqXHR.responseJSON.errors;
                        let errorMsg = `${jqXHR.responseJSON.message}\n`;
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
            // Open the add modal and reset the form
            $('#add-btn-one').click(function(event) {
                event.preventDefault();
                $('#addUserForm').trigger('reset');
                $('#addUserModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });

            // Handle adding new user
            $('#addUserForm').submit(function(event) {
                event.preventDefault();

                let form = $(this);

                grecaptcha.execute("{{ env('RECAPTCHA_SITE_KEY') }}", {
                    action: 'submit'
                }).then(function(token) {
                    form.find('input[name=recaptcha_token]').val(token);

                    $.ajax({
                        method: 'POST',
                        url: '/users',
                        data: form.serialize(),
                        dataType: 'JSON',
                        cache: false,
                        success: function(response) {
                            if (response.valid) {
                                $('#addUserModal').modal('hide');
                                $('#addUserForm').trigger('reset');
                                $('#table1').bootstrapTable('refresh');
                                showSuccessMessage(response.msg);
                            }
                        },
                        error: function(jqXHR) {
                            if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                                let errors = jqXHR.responseJSON.errors;
                                let errorMsg = `${jqXHR.responseJSON.message}\n`;
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

            // Handle user update
            $('#updateUserForm').submit(function(event) {
                event.preventDefault();

                let form = $(this);

                grecaptcha.execute("{{ env('RECAPTCHA_SITE_KEY') }}", {
                    action: 'submit'
                }).then(function(token) {
                    form.find('input[name=recaptcha_token]').val(token);

                    $.ajax({
                        method: 'PUT',
                        url: `/users/${userID}`,
                        data: form.serialize(),
                        dataType: 'JSON',
                        cache: false,
                        success: function(response) {
                            if (response.valid) {
                                $('#updateUserModal').modal('hide');
                                $('#updateUserForm').trigger('reset');
                                $('#table1').bootstrapTable('refresh');
                                showSuccessMessage(response.msg);
                            }
                        },
                        error: function(jqXHR) {
                            if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                                let errors = jqXHR.responseJSON.errors;
                                let errorMsg = `${jqXHR.responseJSON.message}\n`;
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
