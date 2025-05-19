@extends('layouts.base')
@section('APP_TITLE')
    Hotel Transactions
@endsection
@section('active_transactions')
    active
@endsection
@section('APP_CONTENT')
    <div class="button-style-three" id="toolbar">
        <button type="button" class="btn btn-custon-rounded-three btn-primary" id="add-btn-one">
            <span class="adminpro-icon adminpro-plus-add-button-in"></span> Add Reservations
        </button>
        <button type="button" class="btn btn-custon-rounded-three btn-success" id="add-btn-two">
            <span class="adminpro-icon adminpro-check-correct"></span> Check-in
        </button>
    </div>
    <table id="table1" data-toggle="table" data-auto-refresh="true" data-auto-refresh-interval="20"
        data-fixed-columns="true" data-fixed-number="1" data-i18n-enhance="true" data-mobile-responsive="true"
        data-multiple-sort="true" data-page-jump-to="true" data-pipeline="true" data-reorder-rows="true"
        data-sticky-header="true" data-toolbar="#toolbar" data-pagination="true" data-search="true" data-show-refresh="true"
        data-show-copy-rows="true" data-show-columns="true" data-url="/hotelTransactions">
        <thead>
            <tr>
                <th data-field="count">ID</th>
                <th data-field="transaction_number">Transaction #</th>
                <th data-field="customer_name">Customer Name</th>
                <th data-field="room_name">Room Reservation</th>
                <th data-field="check_in_date">Check-in</th>
                <th data-field="check_out_date">Check-out</th>
                <th data-field="total_days">Total Day(s)</th>
                <th data-field="total_amount">Amount</th>
                <th data-field="status">Status</th>
                <th data-field="action">Actions</th>
            </tr>
        </thead>
    </table>

    @include('walkin.walkin_hotel')
    @include('reservations.reservation_hotel')
@endsection

@section('APP_SCRIPT')
    <script type="text/javascript">
        let currentStepAdd = 1;
        let currentStepWalkIn = 1;
        let transactionNumber = 1;

        // Function to show steps based on current step for a modal
        function showStep(modalId, step) {
            $(`#${modalId} .step-content`).hide();
            $(`#${modalId} .step-content[data-step="${step}"]`).show();
            $(`#${modalId} .step-indicator`).removeClass('active');
            $(`#${modalId} .step-indicator[data-step="${step}"]`).addClass('active');
            $(`#${modalId} #progressBar`).css('width', `${step * 25}%`);
        }

        async function validateStep(modalId, step) {
            let valid = true;

            if (modalId === 'addModal') {
                if (step === 2) {
                    let inDate = $('#check_in_date').val();
                    let outDate = $('#check_out_date').val();

                    if (!inDate || !outDate) {
                        showErrorMessage('Check-in and Check-out dates are required.');
                        valid = false;
                    }

                    if (valid) {
                        try {
                            const response = await fetchAvailableRooms(inDate, outDate);
                            $('#table2').bootstrapTable('load', response);
                        } catch (errorMsg) {
                            showErrorMessage(errorMsg);
                            valid = false;
                        }
                    }
                }

                if (step === 3) {
                    let selectedRoom = $('#table2').bootstrapTable('getSelections');
                    if (selectedRoom.length !== 1) {
                        showErrorMessage('Please select a room');
                        valid = false;
                    }
                }
            } else if (modalId === 'walkInModal') {
                if (step === 1) {
                    let stayDays = parseInt($('#stay_days').val(), 10);
                    if (isNaN(stayDays) || stayDays < 1) {
                        showErrorMessage('Please enter a valid number of days.');
                        valid = false;
                    }

                    let inDate = new Date(); // Today's date
                    let outDate = new Date();
                    outDate.setDate(inDate.getDate() + stayDays); // Add stayDays to inDate

                    // Format dates as 'YYYY-MM-DD' (or however your backend expects it)
                    const formatDate = (date) => date.toISOString().split('T')[0];

                    inDate = formatDate(inDate);
                    outDate = formatDate(outDate);

                    if (valid) {
                        try {
                            const response = await fetchAvailableRooms(inDate, outDate);
                            $('#table3').bootstrapTable('load', response);
                        } catch (errorMsg) {
                            showErrorMessage(errorMsg);
                            valid = false;
                        }
                    }
                }

                if (step === 2) {
                    let selectedRoom = $('#table3').bootstrapTable('getSelections');
                    if (selectedRoom.length !== 1) {
                        showErrorMessage('Select a room');
                        valid = false;
                    }
                }
            }

            return valid;
        }

        function fetchAvailableRooms(inDate, outDate) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '{{ route('getAvailableRooms') }}',
                    method: 'GET',
                    data: {
                        check_in_date: inDate,
                        check_out_date: outDate
                    },
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMsg = errors.check_out_date ? errors.check_out_date[0] :
                                'Validation failed.';
                            reject(errorMsg);
                        } else {
                            reject('An error occurred. Please try again.');
                        }
                    }
                });
            });
        }

        function updateSummary(modalId) {
            $.ajax({
                url: '{{ route('getHotelTransactionCount') }}',
                method: 'GET',
                success: function(response) {
                    const currentYear = new Date().getFullYear();
                    transactionNumber =
                        `HOTEL-${currentYear}-${(response.count + 1).toString().padStart(3, '0')}`;

                    const formatDate = (date) => new Date(date).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    let customerName, customerEmail, customerNumber, checkInDate, checkOutDate, stayDays, room;

                    const updateSummaryFields = (modalSelector, checkIn, checkOut, stayDaysValue, roomData) => {
                        const summary = $(modalSelector).find('.step-content[data-step="4"]');

                        summary.find('#transactionNumber').text(transactionNumber);
                        summary.find('#customerName').text(customerName);
                        summary.find('#customerEmail').text(customerEmail);
                        summary.find('#customerNumber').text(customerNumber);
                        summary.find('#checkInDate').text(formatDate(checkIn));
                        summary.find('#checkOutDate').text(formatDate(checkOut));
                        summary.find('#roomDescription').text(
                            `${roomData.room_name} - ${roomData.hotel_type.type_name}`
                        );
                        summary.find('#roomRate').text(new Intl.NumberFormat('en-PH', {
                            style: 'currency',
                            currency: 'PHP'
                        }).format(roomData.room_rate));
                        summary.find('#stayDays').text(stayDaysValue);
                        summary.find('#totalPrice').text(new Intl.NumberFormat('en-PH', {
                            style: 'currency',
                            currency: 'PHP'
                        }).format(roomData.room_rate * stayDaysValue));
                    };

                    if (modalId === 'addModal') {
                        const form = $('#addForm');
                        customerName = form.find('input[id=customer_name]').val();
                        customerEmail = form.find('input[id=customer_email]').val();
                        customerNumber = '+63' + form.find('input[id=customer_number]').val();
                        checkInDate = form.find('#check_in_date').val();
                        checkOutDate = form.find('#check_out_date').val();

                        room = $('#table2').bootstrapTable('getSelections')[0];
                        stayDays = Math.max(0, Math.ceil((new Date(checkOutDate) - new Date(checkInDate)) / (
                            1000 * 3600 * 24)));

                        updateSummaryFields('#addForm', checkInDate, checkOutDate, stayDays, room);
                    } else if (modalId === 'walkInModal') {
                        const form = $('#walkInForm');
                        customerName = form.find('input[id=customer_name]').val();
                        customerEmail = form.find('input[id=customer_email]').val();
                        customerNumber = '+63' + form.find('input[id=customer_number]').val();
                        stayDays = parseInt(form.find('#stay_days').val(), 10);

                        room = $('#table3').bootstrapTable('getSelections')[0];

                        const today = new Date();
                        const checkOutDateWalkIn = new Date(today.getTime() + stayDays * 24 * 60 * 60 * 1000);

                        updateSummaryFields('#walkInForm', today, checkOutDateWalkIn, stayDays, room);
                    }
                },
                error: function() {
                    showErrorMessage("Failed to generate transaction number. Please try again.");
                }
            });
        }

        function roomTypeFormatter(value, row, index) {
            return row.hotel_type.type_name;
        }

        function confirmBooking(transaction_id) {
            $.ajax({
                method: 'PUT',
                url: '{{ route('reservationHotelStatus') }}',
                data: {
                    transaction_id: transaction_id,
                    status: 'Confirmed'
                },
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response.valid) {
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

        function checkInBooking(transaction_id) {
            $.ajax({
                method: 'PUT',
                url: '{{ route('reservationHotelStatus') }}',
                data: {
                    transaction_id: transaction_id,
                    status: 'Checked-in'
                },
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response.valid) {
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

        function checkOutBooking(transaction_id) {
            $.ajax({
                method: 'PUT',
                url: '{{ route('reservationHotelStatus') }}',
                data: {
                    transaction_id: transaction_id,
                    status: 'Checked-out'
                },
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response.valid) {
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

        function noShowBooking(transaction_id) {
            $.ajax({
                method: 'PUT',
                url: '{{ route('reservationHotelStatus') }}',
                data: {
                    transaction_id: transaction_id,
                    status: 'No-show'
                },
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response.valid) {
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

        $(document).ready(function() {
            $('#add-btn-one').click(function(event) {
                event.preventDefault();

                $('#addModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });

            $('#add-btn-two').click(function(event) {
                event.preventDefault();

                $('#walkInModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });

            // Handling next and previous steps for both modals
            $('.next-step').click(async function() {
                const modalId = $(this).closest('.modal').attr('id');
                const currentStep = modalId === 'addModal' ? currentStepAdd : currentStepWalkIn;

                const isValid = await validateStep(modalId, currentStep);
                if (isValid) {
                    if (modalId === 'addModal' && currentStep === 3) {
                        updateSummary(modalId);
                    } else if (modalId === 'walkInModal' && currentStep === 3) {
                        updateSummary(modalId);
                    }

                    if (modalId === 'addModal') {
                        currentStepAdd++;
                        showStep(modalId, currentStepAdd);
                    } else {
                        currentStepWalkIn++;
                        showStep(modalId, currentStepWalkIn);
                    }
                }
            });

            $('.prev-step').click(function() {
                const modalId = $(this).closest('.modal').attr('id');
                if (modalId === 'addModal') {
                    currentStepAdd--;
                    showStep(modalId, currentStepAdd);
                } else {
                    currentStepWalkIn--;
                    showStep(modalId, currentStepWalkIn);
                }
            });

            $('#addForm').submit(function(event) {
                event.preventDefault();
                handleFormSubmit('addModal');
            });

            $('#walkInForm').submit(function(event) {
                event.preventDefault();
                handleFormSubmit('walkInModal');
            });

            function handleFormSubmit(modalId) {
                let customerName, customerEmail, customerNumber, roomId, roomRate, checkInDate, checkOutDate,
                    totalAmount, status;
                const customerType = 'Walk-in';


                // Gather inputs based on modal type
                if (modalId === 'addModal') {
                    // For Add Modal
                    status = 'Confirmed';
                    customerName = $('#addForm').find('input[id=customer_name]').val();
                    customerEmail = $('#addForm').find('input[id=customer_email]').val();
                    customerNumber = '+63' + $('#addForm').find('input[id=customer_number]').val();
                    checkInDate = $('#check_in_date').val();
                    checkOutDate = $('#check_out_date').val();

                    const selectedRoom = $('#table2').bootstrapTable('getSelections')[0];
                    if (!selectedRoom) {
                        showErrorMessage('Please select a room.');
                        return;
                    }

                    roomId = selectedRoom.id;
                    roomRate = parseFloat(selectedRoom.room_rate);

                    // Calculate number of days
                    const checkIn = new Date(checkInDate);
                    const checkOut = new Date(checkOutDate);
                    const timeDiff = checkOut.getTime() - checkIn.getTime();
                    const numberOfDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

                    totalAmount = numberOfDays * roomRate;
                } else if (modalId === 'walkInModal') {
                    // For Walk-In Modal
                    status = 'Checked-in';
                    customerName = $('#walkInForm').find('input[id=customer_name]').val();
                    customerEmail = $('#walkInForm').find('input[id=customer_email]').val();
                    customerNumber = '+63' + $('#walkInForm').find('input[id=customer_number]').val();
                    const stayDays = parseInt($('#stay_days').val(), 10);

                    if (isNaN(stayDays) || stayDays < 1) {
                        showErrorMessage('Please enter a valid number of days.');
                        return;
                    }

                    const selectedRoom = $('#table3').bootstrapTable('getSelections')[0];
                    if (!selectedRoom) {
                        showErrorMessage('Please select a room.');
                        return;
                    }

                    roomId = selectedRoom.id;
                    roomRate = parseFloat(selectedRoom.room_rate);

                    // Calculate the check-in and check-out dates
                    let inDate = new Date();
                    let outDate = new Date();
                    outDate.setDate(inDate.getDate() + stayDays);

                    // Format dates as 'YYYY-MM-DD'
                    const formatDate = (date) => date.toISOString().split('T')[0];
                    checkInDate = formatDate(inDate);
                    checkOutDate = formatDate(outDate);

                    // Calculate number of days
                    const timeDiff = outDate.getTime() - inDate.getTime();
                    const numberOfDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

                    totalAmount = numberOfDays * roomRate;
                }

                // Generate transaction number via AJAX
                // Submit transaction details via AJAX
                $.ajax({
                    method: 'POST',
                    url: '/hotelTransactions',
                    data: {
                        transaction_number: transactionNumber,
                        customer_name: customerName,
                        customer_email: customerEmail,
                        customer_number: customerNumber,
                        customer_type: customerType,
                        hotel_room_id: roomId,
                        check_in_date: checkInDate,
                        check_out_date: checkOutDate,
                        total_amount: totalAmount,
                        status: status,
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        if (response.valid) {
                            // Handle success: Hide modal, reset form, refresh table, show success message
                            if (modalId === 'addModal') {
                                $('#addModal').modal('hide');
                                $('#addForm').trigger('reset');
                                $('#table1').bootstrapTable('refresh');
                            } else {
                                $('#walkInModal').modal('hide');
                                $('#walkInForm').trigger('reset');
                                $('#table1').bootstrapTable('refresh');
                            }
                            showSuccessMessage(response.msg);

                            // Reset stepper to initial step
                            currentStepAdd = 1;
                            currentStepWalkIn = 1;
                            showStep('addModal', currentStepAdd);
                            showStep('walkInModal', currentStepWalkIn);
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

            showStep('addModal', currentStepAdd);
            showStep('walkInModal', currentStepWalkIn);
        });
    </script>
@endsection
