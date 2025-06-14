<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('APP_TITLE') | {{ env('APP_NAME') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('layouts.style')
    <style type="text/css">

    </style>
</head>

<body>
    <div class="header-top-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <div class="admin-logo">
                        <a href="#">
                            <img src="{{ asset('img/logo/log.png') }}" alt="Logo"
                                style="height: 50px; width: auto;">
                        </a>

                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-0 col-xs-12">
                    <div class="header-top-menu">
                        <ul class="nav navbar-nav mai-top-nav">
                            <li class="nav-item"><a href="{{ route('homepage-homepage') }}" class="nav-link">Home</a>
                            </li>
                            <li class="nav-item"><a href="{{ route('owner-contactInquiries') }}"
                                    class="nav-link">Messages</a>
                            </li>
                            <li class="nav-item"><a href="{{ route('owner-seasonalPromos') }}"
                                    class="nav-link">Promo(s)</a>
                            </li>
                            <li class="nav-item"><a href="#" class="nav-link" id="view-contact">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-9 col-sm-6 col-xs-12">
                    <div class="header-right-info">
                        <ul class="nav navbar-nav mai-top-nav header-right-menu">
                            <li class="nav-item">
                                <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                                    class="nav-link dropdown-toggle">
                                    <span class="adminpro-icon adminpro-user-rounded header-riht-inf"></span>
                                    <span class="admin-name">{{ auth()->user()->first_name }}
                                        {{ auth()->user()->last_name }}</span>
                                    <span class="author-project-icon adminpro-icon adminpro-down-arrow"></span>
                                </a>
                                <ul role="menu"
                                    class="dropdown-header-top author-log dropdown-menu animated flipInX">
                                    <li><a href="#" id="password-btn"><span
                                                class="adminpro-icon adminpro-home-admin author-log-ic"></span>My
                                            Account</a>
                                    </li>
                                    <li><a href="#" id="profile-btn"><span
                                                class="adminpro-icon adminpro-user-rounded author-log-ic"></span>My
                                            Profile</a>
                                    </li>
                                    <li><a href="#" id="logout"><span
                                                class="adminpro-icon adminpro-locked author-log-ic"></span>Log Out</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header top area end-->
    <!-- Main Menu area start-->
    @include('navigation.navigation_desktop')
    <!-- Main Menu area End-->
    <!-- Mobile Menu start -->
    @include('navigation.navigation_mobile')
    <!-- Mobile Menu end -->
    <!-- Breadcome start-->
    @include('breadcome.breadcome')
    <!-- Breadcome End-->
    <!-- Basic Form Start -->
    <div class="dual-list-box-area mg-b-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="sparkline10-list shadow-reset">
                        <div class="sparkline10-hd">
                            <div class="main-sparkline10-hd">
                                <h1>@yield('APP_TITLE')</h1>
                                <div class="sparkline10-outline-icon">
                                    {{-- <span class="sparkline10-collapse-link"><i class="fa fa-chevron-up"></i></span>
                                    <span><i class="fa fa-wrench"></i></span>
                                    <span class="sparkline10-collapse-close"><i class="fa fa-times"></i></span> --}}
                                </div>
                            </div>
                        </div>
                        <div class="sparkline10-graph">
                            @yield('APP_CONTENT')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Basic Form End-->
    <!-- Footer Start-->
    {{-- @include('layouts.footer') --}}
    <!-- Footer End-->

    <!-- User Profile Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="updateModalLabel">
                        <i class="fa fa-user-circle"></i> My Profile
                    </h5>
                </div>

                <form id="updateForm">
                    <div class="modal-body">
                        <div class="row">
                            <!-- Profile Picture -->
                            <div class="col-md-4 text-center">
                                <img id="profilePicture" src="https://via.placeholder.com/150"
                                    class="rounded-circle img-thumbnail" alt="Profile Picture">
                            </div>

                            <!-- Profile Details -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label><i class="fa fa-user"></i> First Name</label>
                                    <input type="text" class="form-control" id="firstName" name="first_name"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label><i class="fa fa-user"></i> Last Name</label>
                                    <input type="text" class="form-control" id="lastName" name="last_name"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label><i class="fa fa-envelope"></i> Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        required readonly>
                                </div>
                                <div class="form-group">
                                    <label>Phone Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">+63</span>
                                        <input type="text" class="form-control" name="phone_number"
                                            id="phoneNumber" data-mask="999-999-9999" placeholder="XXX-XXX-XXXX"
                                            required />
                                    </div>
                                    <small class="text-muted">Enter the remaining 9 digits after +63.</small>
                                </div>
                                <div class="form-group">
                                    <label><i class="fa fa-user-tag"></i> Role</label>
                                    <input type="text" class="form-control" id="userRole" name="user_role"
                                        readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Password Update Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="passwordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="passwordModalLabel"><i class="fa fa-lock"></i> Update Password</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="passwordForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label><i class="fa fa-key"></i> Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" name="current_password"
                                required>
                        </div>

                        <div class="form-group">
                            <label><i class="fa fa-lock"></i> New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="new_password"
                                required>
                            <small class="text-muted">Must be at least 8 characters, including uppercase, lowercase,
                                number, and special character.</small>
                        </div>

                        <div class="form-group">
                            <label><i class="fa fa-lock"></i> Confirm Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password"
                                required>
                            <small id="passwordMatchMessage" class="text-danger d-none">Passwords do not
                                match!</small>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger"><i class="fa fa-save"></i> Update
                            Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Contact Modal -->
    <div id="addContactModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="addContactForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Update Contact</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control"
                            placeholder="Enter email address" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-addon">+63</span>
                            <input type="text" class="form-control" name="contact" id="contact"
                                data-mask="999-999-9999" placeholder="XXX-XXX-XXXX" required />
                        </div>
                        <small class="text-muted">Enter the remaining 9 digits after +63.</small>
                    </div>
                    <div class="form-group">
                        <label>Address <span class="text-danger">*</span></label>
                        <textarea name="address" id="address" class="form-control" rows="3" placeholder="Enter address" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-custon-rounded-three btn-primary">
                        <span class="fa fa-save"></span> Update Contact
                    </button>
                    <button type="button" class="btn btn-custon-rounded-three btn-danger" data-dismiss="modal">
                        <span class="fa fa-times"></span> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Chat Box Start-->
    {{-- @include('messenger.chat') --}}
    <!-- Chat Box End-->
    @include('layouts.script')
    @yield('APP_SCRIPT')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#logout').click(function(event) {
                event.preventDefault();

                $.ajax({
                    url: '{{ route('logoutAccount') }}',
                    type: 'POST',
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            window.location.href = '{{ route('homepage-homepage') }}';
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
            });

            $('#password-btn').click(function(event) {
                event.preventDefault();

                $('#passwordModal').modal({
                    backdrop: 'static',
                    keyboard: false
                }).modal('show');
            });

            $('#view-contact').click(function(event) {
                event.preventDefault();

                $.ajax({
                    type: 'GET',
                    url: '/contacts',
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response) {

                            $('#addContactForm').find('input[id=email]').val(response.email);
                            $('#addContactForm').find('input[id=contact]').val(response
                            .contact);
                            $('#addContactForm').find('textarea[id=address]').val(response
                                .address);

                            $('#addContactModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            }).modal('show');
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
            });

            $('#profile-btn').click(function(event) {
                event.preventDefault();

                $.ajax({
                    method: 'GET',
                    url: '{{ route('profile') }}',
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response) {
                            $("#firstName").val(response.first_name);
                            $("#lastName").val(response.last_name);
                            $("#email").val(response.email);
                            $("#phoneNumber").val(response.phone_number);
                            $("#userRole").val(response.user_role);
                            $("#profilePicture").attr("src", response.profile_picture ||
                                '{{ asset('img/profile/1.jpg') }}');

                            $('#updateModal').modal({
                                backdrop: 'static',
                                keyboard: false
                            }).modal('show');
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
            });

            // Handle user update
            $('#updateForm').submit(function(event) {
                event.preventDefault();

                let formData = $('#updateForm').serialize();
                let submitButton = $('#updateForm button[type="submit"]');

                // Disable submit button while request is being processed
                submitButton.prop('disabled', true).text('Updating...');

                $.ajax({
                    method: 'PUT',
                    url: `/updateProfile`,
                    data: formData,
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.valid) {
                            $('#profileModal').modal('hide'); // Close modal on success
                            $('#updateForm').trigger('reset'); // Reset form fields
                            $('#profile-btn').click(); // Refresh profile info if needed
                            showSuccessMessage(response.msg);
                        }
                    },
                    error: function(jqXHR) {
                        let errorMsg = "An unexpected error occurred. Please try again.";

                        if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                            let errors = jqXHR.responseJSON.errors;
                            errorMsg = "<strong>Validation Errors:</strong><br>";
                            for (const [field, messages] of Object.entries(errors)) {
                                errorMsg += `- ${messages.join(', ')}<br>`;
                            }
                        }
                        showErrorMessage(errorMsg);
                    },
                    complete: function() {
                        // Enable submit button after request completes
                        submitButton.prop('disabled', false).text('Update Profile');
                    }
                });
            });

            $("#passwordForm").submit(function(event) {
                event.preventDefault();

                let formData = $(this).serialize();
                let submitButton = $("#passwordForm button[type='submit']");

                // Disable button while processing
                submitButton.prop("disabled", true).html(
                    '<i class="fa fa-spinner fa-spin"></i> Updating...');

                $.ajax({
                    method: "PUT",
                    url: "/updatePassword",
                    data: formData,
                    dataType: "JSON",
                    success: function(response) {
                        if (response.valid) {
                            $("#passwordModal").modal("hide");
                            $("#passwordForm")[0].reset();
                            showSuccessMessage(response.msg);
                        }
                    },
                    error: function(jqXHR) {
                        let errorMsg = "An error occurred. Please try again.";

                        if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                            errorMsg = "<strong>Validation Errors:</strong><br>";
                            $.each(jqXHR.responseJSON.errors, function(key, value) {
                                errorMsg += `- ${value}<br>`;
                            });
                        }

                        showErrorMessage(errorMsg);
                    },
                    complete: function() {
                        submitButton.prop("disabled", false).html(
                            '<i class="fa fa-save"></i> Update Password');
                    }
                });
            });

            // Password Matching Validation
            $("#confirmPassword").on("keyup", function() {
                let newPassword = $("#newPassword").val();
                let confirmPassword = $(this).val();

                if (newPassword !== confirmPassword) {
                    $("#passwordMatchMessage").removeClass("d-none");
                } else {
                    $("#passwordMatchMessage").addClass("d-none");
                }
            });

            $("#addContactForm").submit(function(event) {
                event.preventDefault();

                let formData = $(this).serialize();
                let submitButton = $("#addContactForm button[type='submit']");

                // Disable button while processing
                submitButton.prop("disabled", true).html(
                    '<i class="fa fa-spinner fa-spin"></i> Updating...');

                $.ajax({
                    method: "POST",
                    url: "/contacts",
                    data: formData,
                    dataType: "JSON",
                    success: function(response) {
                        if (response.valid) {
                            $("#addContactModal").modal("hide");
                            $("#addContactForm")[0].reset();
                            showSuccessMessage(response.msg);
                        }
                    },
                    error: function(jqXHR) {
                        let errorMsg = "An error occurred. Please try again.";

                        if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                            errorMsg = "<strong>Validation Errors:</strong><br>";
                            $.each(jqXHR.responseJSON.errors, function(key, value) {
                                errorMsg += `- ${value}<br>`;
                            });
                        }

                        showErrorMessage(errorMsg);
                    },
                    complete: function() {
                        submitButton.prop("disabled", false).html(
                            '<i class="fa fa-save"></i> Update Contact');
                    }
                });
            });
        });
    </script>
</body>

</html>
