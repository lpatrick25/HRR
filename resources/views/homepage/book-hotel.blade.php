@extends('homepage.base')
@section('APP-TITLE')
    Hotel Booking
@endsection
@section('active-book')
    active
@endsection
@section('custom-css')
    <style style="text/css">
        #roomPicturesHtml {
            width: 400px;
            overflow: none;
            scrollbar-color: #f80000 #b5ffef;
            scrollbar-width: thin;
        }

        #calendar {
            max-height: 600px;
        }

        .fc-day-today {
            background-color: #f3f4f6 !important;
        }

        #calendar .fc-daygrid-day {
            transition: background-color 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        #calendar .fc-day-today {
            background-color: #f0f8ff !important;
            /* Light blue for today */
            border-radius: 5px;
        }

        #calendar .fc-daygrid-day:hover {
            background-color: #f3f3f3 !important;
            cursor: pointer;
        }

        /* Highlight background events */
        .fc-daygrid-day.fc-day-reserved {
            background-color: #f80000 !important;
            color: white;
            border-radius: 5px;
        }

        .fc-day-reserved {
            background-color: #f80000 !important;
            color: white !important;
            border-radius: 5px;
            cursor: not-allowed;
        }

        .fc-day-reserved:hover {
            background-color: #f88080 !important;
        }

        .fc .fc-bg-event .fc-event-title {
            color: #ffffff;
            /* White text for contrast */
            font-weight: bolder;
            margin: .5em;
            font-size: .85em;
            font-size: var(--fc-small-font-size, .85em);
            font-style: italic;
            text-shadow: 0 0 2px rgba(0, 0, 0, 0.5);
            /* Optional for extra readability */
        }

        .step-indicator {
            text-align: center;
            flex: 1;
            display: inline-block;
            width: 24%;
        }

        .step-indicator i {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .step-indicator.active i {
            color: #337ab7;
        }

        .progress-bar-custom {
            width: 25%;
        }

        .floating-action-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1060;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .btn-fab {
            display: flex;
            align-items: center;
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            font-size: 16px;
            transition: all 0.3s ease;
            animation: none;
        }

        .btn-fab i {
            margin-right: 8px;
        }

        .btn-fab:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .fab-title {
            display: block;
            font-weight: bold;
        }

        .fab-popup {
            margin-top: 10px;
            background-color: white;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
            width: 200px;
        }

        .fab-popup button,
        .fab-popup a {
            text-align: left;
            font-size: 14px;
        }

        .fab-popup i {
            margin-right: 8px;
        }

        /* Bounce Animation */
        @keyframes bounceAttention {

            0%,
            100% {
                transform: translateY(0);
            }

            30% {
                transform: translateY(-10px);
            }

            50% {
                transform: translateY(0);
            }

            70% {
                transform: translateY(-5px);
            }
        }

        @media only screen and (min-width: 992px) and (max-width: 1199px) {
            #roomPicturesHtml {
                width: 100%;
            }
        }

        @media only screen and (min-width: 768px) and (max-width: 991px) {
            #roomPicturesHtml {
                width: 100%;
            }
        }

        @media only screen and (max-width: 767px) {
            #roomPicturesHtml {
                width: 100%;
            }
        }

        @media only screen and (max-width: 479px) {
            #roomPicturesHtml {
                width: 100%;
            }
        }
    </style>
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
@endsection
@section('APP-CONTENT')
    <!-- Normal Breadcrumb Begin -->
    <section class="normal-breadcrumb set-bg" data-setbg="{{ asset('homepage/img/normal-breadcrumb.png') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2
                            style="background-image: linear-gradient(to right, rgba(212, 252, 121, 0.1), rgba(150, 230, 161, 0.8), rgba(212, 252, 121, 0.1)); padding: 10px; color: #000;">
                            Book Now</h2>
                        <p
                            style="background-image: linear-gradient(to right, rgba(212, 252, 121, 0.1), rgba(150, 230, 161, 0.8), rgba(212, 252, 121, 0.1)); padding: 10px; color: #000;">
                            Welcome to Tiya Inday Haven Farm Resort.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Normal Breadcrumb End -->

    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="{{ route('homepage-homepage') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Hotel Room Booking</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <section class="-page spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-right">

                </div>
                <div class="col-lg-2" style="margin-top: 10px;">
                    <div class="section-title">
                        <h5>Check-in</h5>
                    </div>
                </div>
                <div class="col-lg-3" style="margin-bottom: 10px;">
                    <input type="date" class="form-control" id="checkInDate" name="checkInDate"
                        min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-lg-2" style="margin-top: 10px;">
                    <div class="section-title">
                        <h5>Check-out</h5>
                    </div>
                </div>
                <div class="col-lg-3" style="margin-bottom: 10px;">
                    <input type="date" class="form-control" id="checkOutDate" name="checkOutDate"
                        min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-lg-2">
                    <button type="button" class="btn btn-primary btn-block" id="bookBtn">Search Availability</button>
                </div>
            </div>
            <hr>
            <div class="row" id="room-reservation">
            </div>
        </div>
    </section>
    <!-- FullCalendar Modal -->
    <div class="modal fade" id="roomAvailabilityModal" tabindex="-1" aria-labelledby="roomAvailabilityModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="roomAvailabilityModalLabel">Room Availability</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
    @include('components.room-booking')
    <!-- How to Book Modal -->
    @include('guide.hotel-booking')
    @include('guide.hotel-details')
    <!-- Floating Action Button -->
    <div class="floating-action-btn">
        <button type="button" class="btn btn-primary btn-fab shadow-lg" id="fabButton">
            <i class="fa fa-question-circle fa-lg"></i>
            <span class="fab-title">How to Book</span>
        </button>

        <!-- Hidden Popup Menu -->
        <div class="fab-popup d-none" id="fabPopupMenu">
            <button class="btn btn-outline-secondary btn-block mb-2" data-toggle="modal" data-target="#howToBookModal">
                <i class="fa fa-book"></i> View Guide
            </button>
            <button class="btn btn-outline-secondary btn-block mb-2" data-toggle="modal" data-target="#checkBookings">
                <i class="fa fa-info-circle"></i> Check My Booking Details
            </button>
            <a href="https://m.me/320237691505456" target="_blank" class="btn btn-outline-primary btn-block">
                <i class="fa fa-facebook-official"></i> Message Us on Facebook
            </a>
        </div>
    </div>
@endsection
@section('APP-SCRIPT')
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script type="text/javascript">
        let hotelRoomID = 0;
        let hotelRoomRate = 0;
        let currentStepAdd = 1;
        let transactionNumber = 1;

        function showStep(modalId, step) {
            $(`#${modalId} .step-content`).hide();
            $(`#${modalId} .step-content[data-step="${step}"]`).show();
            $(`#${modalId} .step-indicator`).removeClass('active');
            $(`#${modalId} .step-indicator[data-step="${step}"]`).addClass('active');
            $(`#${modalId} #progressBar`).css('width', `${step * 25}%`);
        }

        function getStatusBadgeClass(status) {
            switch (status) {
                case 'Pending':
                    return 'warning';
                case 'Confirmed':
                    return 'success';
                case 'Checked-in':
                    return 'primary';
                case 'Checked-out':
                    return 'secondary';
                case 'Cancelled':
                    return 'danger';
                case 'No-show':
                    return 'dark';
                case 'Walk-in':
                    return 'info';
                default:
                    return 'light';
            }
        }

        function updateSummary(modalId) {
            $.ajax({
                url: '{{ route('getHotelTransactionCount') }}',
                method: 'GET',
                success: function(response) {
                    const currentYear = new Date().getFullYear();
                    transactionNumber =
                        `HOTEL-${currentYear}-${(response.count + 1).toString().padStart(3, '0')}`;

                    const form = $('#bookingForm');

                    form.find('#transactionNumber').text(transactionNumber);
                    const customerName = form.find('input[id=customer_name]').val();
                    const customerEmail = form.find('input[id=customer_email]').val();
                    const customerNumber = '+63' + form.find('input[id=customer_number]').val();
                    form.find('#customerName').text(customerName);
                    form.find('#customerEmail').text(customerEmail);
                    form.find('#customerNumber').text(customerNumber);
                },
                error: function() {
                    showErrorMessage("Failed to generate transaction number. Please try again.");
                }
            });
        }

        function view_room(roomId) {
            $.ajax({
                method: 'GET',
                url: `/guest/room-information/${roomId}`, // Assuming your app is in the root directory
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response) {

                        hotelRoomID = response.id;
                        hotelRoomRate = response.room_rate;

                        currentStepAdd = 1;
                        showStep('bookingModal', currentStepAdd);
                        $('#bookingForm').trigger('reset');

                        let inDate = $('#checkInDate').val();
                        let outDate = $('#checkOutDate').val();
                        $('#bookingModal #check_in_date').val(inDate);
                        $('#bookingModal #check_out_date').val(outDate);

                        // Update the modal content with the room details
                        $('#bookingModal .main-room-image').attr('src', '../' + response.picture);
                        $('#bookingModal .modal-room-name').text(response.room_name);
                        $('#bookingModal .modal-room-type').text(response.hotel_type.type_name);
                        $('#bookingModal .modal-room-description').text(response.hotel_type.type_description);
                        $('#bookingModal .modal-room-rate').text('₱' + parseFloat(response.room_rate)
                            .toLocaleString('en-US', {
                                minimumFractionDigits: 2
                            }));
                        $('#bookingModal .modal-room-capacity').text(response.room_capacity + ' persons');

                        // Clear and append amenities
                        let amenitiesHtml = '';
                        response.amenities.forEach(function(amenity) {
                            amenitiesHtml +=
                                `<li class="badge bg-light text-dark border">${amenity.amenity}</li>`;
                        });
                        $('#bookingModal .modal-room-amenities').html(amenitiesHtml);

                        // Clear and append pictures (gallery)
                        let picturesHtml = '';
                        picturesHtml += `
                            <div class="col-4">
                                <img src="${'../' + response.picture}" class="img-fluid rounded room-thumbnail"
                                    style="cursor: pointer; height: 80px; object-fit: cover;"
                                    onclick="swapMainImage('${response.picture}')">
                            </div>
                        `;
                        response.pictures.forEach(function(picture) {
                            picturesHtml += `
                            <div class="col-4">
                                <img src="${'../' + picture.picture}" class="img-fluid rounded room-thumbnail"
                                    style="cursor: pointer; height: 80px; object-fit: cover;">
                            </div>
                        `;
                        });
                        $('#bookingModal .modal-room-pictures').html(picturesHtml);

                        const formatDate = (date) => new Date(date).toLocaleDateString('en-US', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });

                        const summary = $('#bookingForm').find('.step-content[data-step="4"]');
                        const stayDays = Math.max(0, Math.ceil((new Date(outDate) - new Date(
                            inDate)) / (
                            1000 * 3600 * 24)));
                        summary.find('#checkInDate').text(formatDate(inDate));
                        summary.find('#checkOutDate').text(formatDate(outDate));
                        summary.find('#roomDescription').text(
                            `${response.room_name} - ${response.hotel_type.type_name}`);
                        summary.find('#roomRate').text(new Intl.NumberFormat('en-PH', {
                            style: 'currency',
                            currency: 'PHP'
                        }).format(response.room_rate));
                        summary.find('#stayDays').text(stayDays);
                        summary.find('#totalPrice').text(new Intl.NumberFormat('en-PH', {
                            style: 'currency',
                            currency: 'PHP'
                        }).format(response.room_rate * stayDays));

                        // Show the modal
                        $('#bookingModal').modal('show');
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

        function viewReservations(roomId, roomName, roomType) {
            $('#roomAvailabilityModalLabel').text(roomName + ' - ' + roomType);
            $('#roomAvailabilityModal').modal('show');

            // Destroy previous calendar instance if it exists
            if (window.roomCalendar) {
                window.roomCalendar.destroy();
            }

            // Initialize the calendar
            window.roomCalendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                height: 600,
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: `/guest/room-reservations/${roomId}`,
                        type: 'GET',
                        success: function(data) {
                            successCallback(data);
                            highlightReservedDays(); // Call this after events are fetched
                        },
                        error: function() {
                            failureCallback();
                        }
                    });
                },
                displayEventTime: false,
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                },
                datesSet: function() {
                    highlightReservedDays(); // Also call this when the calendar month is changed
                }
            });

            window.roomCalendar.render();

            // Helper function to mark reserved days
            function highlightReservedDays() {
                // Iterate over each visible day cell
                $('#calendar .fc-daygrid-day').each(function() {
                    const $cell = $(this);
                    const cellDate = $cell.data('date'); // This is like "2025-02-21"
                    const cellDateObj = new Date(cellDate);

                    // Reset styles on every check
                    $cell.removeClass('fc-day-reserved').css({
                        'border': '1px solid #ddd',
                        'font-size': '13px',
                        'padding': '8px',
                        'cursor': 'pointer',
                        'background-color': '',
                        'transition': 'background-color 0.2s ease',
                        'color': ''
                    });

                    // Check if this day is reserved
                    let isReserved = window.roomCalendar.getEvents().some(function(event) {
                        if (event.display === 'background') {
                            const eventStart = event.start;
                            const eventEnd = event.end ? new Date(event.end) : eventStart;

                            // FullCalendar end is exclusive, so we don't subtract a day here
                            return isSameOrAfter(cellDateObj, eventStart) && isBefore(cellDateObj,
                                eventEnd);
                        }
                        return false;
                    });

                    function isSameOrAfter(date1, date2) {
                        return date1.getTime() >= dateOnly(date2).getTime();
                    }

                    function isBefore(date1, date2) {
                        return date1.getTime() < dateOnly(date2).getTime();
                    }

                    function dateOnly(date) {
                        return new Date(date.getFullYear(), date.getMonth(), date.getDate());
                    }

                    if (isReserved) {
                        $cell.addClass('fc-day-reserved').css({
                            'cursor': 'not-allowed',
                            'background-color': '#f80000',
                            'color': 'white',
                            'border-radius': '5px'
                        });

                        $cell.off('mouseenter mouseleave'); // Remove old hover events
                        $cell.hover(
                            function() {
                                $(this).css('background-color', '#f88080');
                            },
                            function() {
                                $(this).css('background-color', '#f80000');
                            }
                        );
                    } else {
                        $cell.off('mouseenter mouseleave');
                        $cell.hover(
                            function() {
                                $(this).css('background-color', '#f3f4f6');
                            },
                            function() {
                                $(this).css('background-color', '');
                            }
                        );
                    }
                });
            }
        }

        $(document).ready(function() {

            // Show/Hide Popup Menu
            document.getElementById('fabButton').addEventListener('click', function() {
                var popup = document.getElementById('fabPopupMenu');
                popup.classList.toggle('d-none');
            });

            // Close popup when clicking outside
            document.addEventListener('click', function(e) {
                var fabButton = document.getElementById('fabButton');
                var popupMenu = document.getElementById('fabPopupMenu');

                if (!fabButton.contains(e.target) && !popupMenu.contains(e.target)) {
                    popupMenu.classList.add('d-none');
                }
            });

            // Bounce animation every 15 seconds
            setInterval(function() {
                var fabButton = document.getElementById('fabButton');
                fabButton.style.animation = 'bounceAttention 1.2s ease';
                setTimeout(function() {
                    fabButton.style.animation = '';
                }, 1200); // Reset after animation
            }, 15000);

            $('#checkInDate').on('change', function() {
                let checkInDate = new Date($(this).val());
                if (!isNaN(checkInDate.getTime())) {
                    checkInDate.setDate(checkInDate.getDate() + 1);
                    let minCheckoutDate = checkInDate.toISOString().split('T')[0];
                    $('#checkOutDate').val(minCheckoutDate);
                    $('#checkOutDate').attr('min', minCheckoutDate);
                }
            });

            $('.next-step').click(async function() {
                const modalId = $(this).closest('.modal').attr('id');
                if (modalId !== 'bookingModal') return;

                // Validation for Step 1
                if (currentStepAdd === 1) {
                    let isValid = true;

                    // Customer Name Validation
                    const customerName = $('#customer_name').val().trim();
                    if (!customerName) {
                        showWarningMessage(
                            'Customer Name is required. Please enter the customer’s full name.');
                        $('.customer_name_error').show();
                        return; // Stop here if invalid
                    } else {
                        $('.customer_name_error').hide();
                    }

                    // Customer Email Validation
                    const customerEmail = $('#customer_email').val().trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!customerEmail) {
                        showWarningMessage(
                            'Customer Email is required. Please enter an email address.');
                        $('.customer_email_error').show();
                        return;
                    } else if (!emailRegex.test(customerEmail)) {
                        showWarningMessage(
                            'Invalid Email Address. Please enter a valid email (e.g., example@example.com).'
                        );
                        $('.customer_email_error').show();
                        return;
                    } else {
                        $('.customer_email_error').hide();
                    }

                    // Customer Number Validation
                    let customerNumber = $('#customer_number').val().replace(/-/g, '').trim();

                    customerNumber = customerNumber.replace('9', '').trim();

                    console.log('Processed Customer Number:', customerNumber);

                    // Ensure the number is exactly 9 digits (since +63 is fixed in input)
                    if (!/^\d{9}$/.test(customerNumber)) {
                        showWarningMessage(
                            'Invalid Customer Number. Enter the last 9 digits after "+63" (e.g., 912345678).'
                        );
                        $('.customer_number_error').show();
                        return;
                    }

                    // Prepend "0" to match local format (e.g., 09123456789)
                    customerNumber = '09' + customerNumber;
                    // customerNumber = customerNumber.replace('9', '').trim();

                    console.log('Final Customer Number:', customerNumber);

                    // Final check: Must be exactly 11 digits and start with "09"
                    if (!/^09\d{9}$/.test(customerNumber)) {
                        showWarningMessage(
                            'Invalid Customer Number. It should start with "09" and be 11 digits long (e.g., 09123456789).'
                        );
                        $('.customer_number_error').show();
                        return;
                    }

                    // Valid input, hide error
                    $('.customer_number_error').hide();
                }

                // Continue steps if valid
                if (currentStepAdd === 3) {
                    updateSummary(modalId);
                }
                currentStepAdd++;
                showStep(modalId, currentStepAdd);
            });

            $('.prev-step').click(function() {
                const modalId = $(this).closest('.modal').attr('id');
                if (modalId !== 'bookingModal') return;

                currentStepAdd--;
                showStep(modalId, currentStepAdd);
            });

            $('#bookBtn').click(function() {
                let checkInDate = $('#checkInDate').val();
                let checkOutDate = $('#checkOutDate').val();

                if (checkInDate && checkOutDate) {
                    $.ajax({
                        method: 'GET',
                        url: '{{ route('checkRoomAvailability') }}',
                        data: {
                            check_in_date: checkInDate,
                            check_out_date: checkOutDate
                        },
                        success: function(response) {
                            $('#room-reservation').html(response);
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
                                showErrorMessage(
                                    "An unexpected error occurred. Please try again.");
                            }
                        }
                    });
                } else {
                    showErrorMessage("Please select valid dates.");
                }
            });

            $('#searchBookingDetails').submit(function(event) {
                event.preventDefault();

                const $modalBody = $('#booking-details');
                const $form = $(this)
                const $submitButton = $(this).find('button[type="submit"]');

                let searchName = $('#search_name').val();
                let searchEmail = $('#search_email').val();
                let searchNumber = '+63' + $('#search_number').val();

                // Show Loading Animation (Bootstrap Spinner)
                const loadingHTML = `
                    <div class="text-center py-5" id="searchLoading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2">Searching for your booking details...</p>
                    </div>
                `;

                $modalBody.html(loadingHTML);
                $submitButton.prop('disabled', true); // Disable submit button while searching

                $.ajax({
                    method: 'GET',
                    url: '{{ route('getRoomBookingDetails') }}',
                    data: {
                        search_name: searchName,
                        search_email: searchEmail,
                        search_number: searchNumber,
                    },
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        setTimeout(function() {
                            // Hide Loading Animation
                            $('#searchLoading').remove();
                            // $submitButton.prop('disabled', false);
                            $submitButton.hide();

                            if (response) {
                                // Replace Modal Body Content with Booking Summary Template
                                const bookingHTML = `
                                    <div class="row d-flex justify-content-center">
                                        <div class="col-md-12">
                                            <div class="shadow-reset">
                                                <div class="panel-body">
                                                    <div class="section-title">
                                                        <h5 style="font-size: 15px;">Transaction Number: <span id="transactionNumber">${response.transaction_number}</span></h5>
                                                    </div>
                                                    <hr>
                                                    <div class="table-responsive p-2">
                                                        <table class="table table-borderless">
                                                            <tbody>
                                                                <tr class="add">
                                                                    <td>Customer Name</td>
                                                                    <td>Customer Email</td>
                                                                    <td>Customer Contact</td>
                                                                </tr>
                                                                <tr class="content">
                                                                    <td class="font-weight-bold">${response.customer_name}</td>
                                                                    <td class="font-weight-bold">${response.customer_email}</td>
                                                                    <td class="font-weight-bold">${response.customer_number}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <hr>
                                                    <div class="table-responsive p-2">
                                                        <table class="table table-borderless">
                                                            <tbody>
                                                                <tr class="add">
                                                                    <td>Check-in Date</td>
                                                                    <td>Check-in Time</td>
                                                                    <td>Check-out Date</td>
                                                                    <td>Check-out Time</td>
                                                                </tr>
                                                                <tr class="content">
                                                                    <td class="font-weight-bold">${response.check_in_date}</td>
                                                                    <td class="font-weight-bold">1:00 PM</td>
                                                                    <td class="font-weight-bold">${response.check_out_date}</td>
                                                                    <td class="font-weight-bold">12:00 PM</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <hr>
                                                    <div class="products p-2">
                                                        <table class="table table-borderless">
                                                            <tbody>
                                                                <tr class="add">
                                                                    <td>Description</td>
                                                                    <td class="text-center">Day(s)</td>
                                                                    <td class="text-center">Room Rate / Day</td>
                                                                    <td class="text-center">Total Amount</td>
                                                                </tr>
                                                                <tr class="content">
                                                                    <td>${response.room.room_name} - ${response.room.type.type_name}</td>
                                                                    <td class="text-center">${response.stay_days}</td>
                                                                    <td class="text-center">₱${parseFloat(response.room.room_rate).toLocaleString()}</td>
                                                                    <td class="text-center">₱${parseFloat(response.total_amount).toLocaleString()}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <hr>
                                                    <div class="text-center font-weight-bold">
                                                        Booking Status: <span class="badge badge-${getStatusBadgeClass(response.status)}">${response.status}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;

                                $modalBody.html(bookingHTML);
                            }
                        }, 1000); // 1-second delay before showing the result
                    },
                    error: function(jqXHR) {
                        $submitButton.prop('disabled', false);
                        $('#searchLoading').remove();

                        let errorMsg = 'An unexpected error occurred. Please try again.';
                        if (jqXHR.responseJSON && jqXHR.responseJSON.msg) {
                            errorMsg = jqXHR.responseJSON.msg;
                        }

                        // Show Error Alert
                        showErrorMessage(errorMsg);

                        // Re-Render the Search Form inside Modal
                        $modalBody.html(`
                            <div class="form-group">
                                <label for="search_name">Customer Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="search_name" id="search_name"
                                        placeholder="Full Name" required />
                                </div>
                                <small class="form-text text-muted">Enter the full name you provided during the reservation.</small>
                            </div>

                            <div class="form-group">
                                <label for="search_email">Customer Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" name="search_email" id="search_email"
                                        placeholder="example@domain.com" required />
                                </div>
                                <small class="form-text text-muted">Enter the email address used for the reservation.</small>
                            </div>

                            <div class="form-group">
                                <label for="search_number">Customer Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+63</span>
                                    </div>
                                    <input type="text" class="form-control" name="search_number" id="search_number"
                                        data-mask="999-999-9999" placeholder="XXX-XXX-XXXX" required />
                                </div>
                                <small class="form-text text-muted">Enter the mobile number you provided during the reservation.</small>
                            </div>
                        `);

                        // Reapply Input Mask after re-rendering the form
                        $('#search_number').mask('999-999-9999');
                    }

                });
            });

            $('#bookingForm').submit(function(event) {
                event.preventDefault();

                // For Add Modal
                status = 'Confirmed';
                customerName = $('#bookingForm').find('input[id=customer_name]').val();
                customerEmail = $('#bookingForm').find('input[id=customer_email]').val();
                customerNumber = '+63' + $('#bookingForm').find('input[id=customer_number]').val();
                checkInDate = $('#check_in_date').val();
                checkOutDate = $('#check_out_date').val();

                roomRate = parseFloat(hotelRoomRate);

                // Calculate number of days
                const checkIn = new Date(checkInDate);
                const checkOut = new Date(checkOutDate);
                const timeDiff = checkOut.getTime() - checkIn.getTime();
                const numberOfDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

                totalAmount = numberOfDays * roomRate;

                $.ajax({
                    method: 'POST',
                    url: '/hotelTransactions',
                    data: {
                        transaction_number: transactionNumber,
                        customer_name: customerName,
                        customer_email: customerEmail,
                        customer_number: customerNumber,
                        customer_type: 'Online',
                        hotel_room_id: hotelRoomID,
                        check_in_date: checkInDate,
                        check_out_date: checkOutDate,
                        total_amount: totalAmount,
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.valid) {
                            $('#bookBtn').click();
                            $('#bookingModal').modal('hide');
                            $('#bookingForm').trigger('reset');
                            showSuccessMessage(response.msg);

                            setInterval(() => {
                                window.location.href =
                                '{{ route('homepage-success') }}';
                            }, 1000);

                            window.open("https://m.me/320237691505456", "_blank");

                            // Reset stepper to initial step
                            currentStepAdd = 1;
                            showStep('bookingModal', currentStepAdd);
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

            showStep('bookingModal', currentStepAdd);
        });
    </script>
@endsection
