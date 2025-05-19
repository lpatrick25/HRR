@extends('layouts.base')
@section('APP_TITLE')
    Resort Transactions
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
        data-show-copy-rows="true" data-show-columns="true" data-url="/resortTransactions">
        <thead>
            <tr>
                <th data-field="count">ID</th>
                <th data-field="transaction_number">Transaction #</th>
                <th data-field="customer_name">Customer Name</th>
                <th data-field="cottage_name">Cottage Reservation</th>
                <th data-field="booking_date">Reservation Date</th>
                <th data-field="total_amount">Amount</th>
                <th data-field="status">Status</th>
                <th data-field="action">Actions</th>
            </tr>
        </thead>
    </table>

    @include('walkin.walkin_resort')
    @include('reservations.reservation_resort')
@endsection

@section('APP_SCRIPT')
    <script type="text/javascript">
        let currentStepAdd = 1;
        let currentStepWalkIn = 1;
        let transactionNumber = 1;

        // Function to show steps based on the current step for a modal
        async function showStep(modalId, step) {
            $(`#${modalId} .step-content`).hide();
            $(`#${modalId} .step-content[data-step="${step}"]`).show();
            $(`#${modalId} .step-indicator`).removeClass('active');
            $(`#${modalId} .step-indicator[data-step="${step}"]`).addClass('active');

            const progressPercentage = (step / 4) * 100; // Assuming 4 steps, adjust as needed
            $(`#${modalId} #progressBar`).css('width', `${progressPercentage}%`);

            if (step === 1 && modalId === 'walkInModal') {
                try {
                    const bookingDate = new Date().toISOString().split('T')[0]; // Format as 'YYYY-MM-DD'

                    const response = await fetchAvailableCottage(bookingDate);

                    if (response && Array.isArray(response)) {
                        $('#table3').bootstrapTable('load', response);
                    } else {
                        showErrorMessage('Invalid response received for available cottages: ' + response);
                    }
                } catch (error) {
                    showErrorMessage('Failed to fetch available cottages: ' + error);
                }
            }
        }

        async function validateStep(modalId, step) {
            let valid = true;

            if (modalId === 'addModal') {
                if (step === 2) {
                    let bookingDate = $('#booking_date').val();

                    if (!bookingDate) {
                        showErrorMessage('Reservation date are required.');
                        valid = false;
                    }

                    if (valid) {
                        try {
                            const response = await fetchAvailableCottage(bookingDate);
                            $('#table2').bootstrapTable('load', response);
                        } catch (errorMsg) {
                            showErrorMessage(errorMsg);
                            valid = false;
                        }
                    }
                }

                if (step === 3) {
                    let selectedCottage = $('#table2').bootstrapTable('getSelections');
                    if (selectedCottage.length !== 1) {
                        showErrorMessage('Please select a cottage');
                        valid = false;
                    }
                }
            } else if (modalId === 'walkInModal') {

                if (step === 1) {
                    let selectedCottage = $('#table3').bootstrapTable('getSelections');
                    if (selectedCottage.length !== 1) {
                        showErrorMessage('Select a cottage');
                        valid = false;
                    }
                }
            }

            return valid;
        }

        function fetchAvailableCottage(bookingDate) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '{{ route('getAvailableCottages') }}',
                    method: 'GET',
                    data: {
                        booking_date: bookingDate,
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
                url: '{{ route('getResortTransactionCount') }}',
                method: 'GET',
                success: function(response) {
                    const currentYear = new Date().getFullYear();
                    transactionNumber =
                        `COTTAGE-${currentYear}-${(response.count + 1).toString().padStart(3, '0')}`;

                    const formatDate = (date) => new Date(date).toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    let customerName, customerEmail, customerNumber, bookingDate, cottage;

                    if (modalId === 'addModal') {
                        const form = $('#addForm');
                        customerName = form.find('input[id=customer_name]').val();
                        customerEmail = form.find('input[id=customer_email]').val();
                        customerNumber = '+63' + form.find('input[id=customer_number]').val();
                        bookingDate = form.find('#booking_date').val();
                        cottage = $('#table2').bootstrapTable('getSelections')[0];

                        updateSummaryFields('#addForm', transactionNumber, customerName, customerEmail,
                            customerNumber, bookingDate, cottage, 4);
                    } else {

                        const form = $('#walkInForm');
                        customerName = form.find('input[id=customer_name]').val();
                        customerEmail = form.find('input[id=customer_email]').val();
                        customerNumber = '+63' + form.find('input[id=customer_number]').val();

                        // For Walk-in, booking date is today
                        bookingDate = new Date().toISOString().split('T')[0];
                        cottage = $('#table3').bootstrapTable('getSelections')[0];

                        updateSummaryFields('#walkInForm', transactionNumber, customerName, customerEmail,
                            customerNumber, bookingDate, cottage, 3);
                    }
                },
                error: function() {
                    showErrorMessage("Failed to generate transaction number. Please try again.");
                }
            });
        }

        function updateSummaryFields(modalSelector, transactionNumber, customerName, customerEmail, customerNumber,
            bookingDate, cottageData, stepNumber) {
            if (!cottageData) {
                showErrorMessage("Please select a cottage.");
                return;
            }

            const summary = $(`${modalSelector} .step-content[data-step="${stepNumber}"]`);

            const formatDate = (date) => new Date(date).toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const formattedBookingDate = formatDate(bookingDate);
            const cottageRateFormatted = new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP'
            }).format(cottageData.cottage_rate);

            const totalPriceFormatted = new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP'
            }).format(cottageData.cottage_rate * 1);

            summary.find('#transactionNumber').text(transactionNumber);
            summary.find('#customerName').text(customerName);
            summary.find('#customerEmail').text(customerEmail);
            summary.find('#customerNumber').text(customerNumber);
            summary.find('#bookingDate').text(formattedBookingDate);
            summary.find('#cottageDescription').text(`${cottageData.cottage_name} - ${cottageData.resort_type.type_name}`);
            summary.find('#cottageRate').text(cottageRateFormatted);
            summary.find('#stayDays').text('1');
            summary.find('#totalPrice').text(totalPriceFormatted);
        }

        function cottageTypeFormatter(value, row, index) {
            return row.resort_type.type_name;
        }

        function confirmReservation(transaction_id) {
            $.ajax({
                method: 'PUT',
                url: '{{ route('reservationResortStatus') }}',
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

        function completeReservation(transaction_id) {
            $.ajax({
                method: 'PUT',
                url: '{{ route('reservationResortStatus') }}',
                data: {
                    transaction_id: transaction_id,
                    status: 'Completed'
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
                url: '{{ route('reservationResortStatus') }}',
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
                    } else if (modalId === 'walkInModal' && currentStep === 2) {
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
                let customerName, customerEmail, customerNumber, cottageId, cottageRate, bookingDate,
                    totalAmount, status;
                const customerType = 'Walk-in';


                // Gather inputs based on modal type
                if (modalId === 'addModal') {
                    // For Add Modal
                    status = 'Confirmed';
                    customerName = $('#addForm').find('input[id=customer_name]').val();
                    customerEmail = $('#addForm').find('input[id=customer_email]').val();
                    customerNumber = '+63' + $('#addForm').find('input[id=customer_number]').val();
                    bookingDate = $('#booking_date').val();

                    const selectedCottage = $('#table2').bootstrapTable('getSelections')[0];
                    if (!selectedCottage) {
                        showErrorMessage('Please select a cottage.');
                        return;
                    }

                    cottageId = selectedCottage.id;
                    cottageRate = parseFloat(selectedCottage.cottage_rate);

                    totalAmount = 1 * cottageRate;
                } else if (modalId === 'walkInModal') {
                    // For Walk-In Modal
                    status = 'Confirmed';
                    customerName = $('#walkInForm').find('input[id=customer_name]').val();
                    customerEmail = $('#walkInForm').find('input[id=customer_email]').val();
                    customerNumber = '+63' + $('#walkInForm').find('input[id=customer_number]').val();

                    const selectedCottage = $('#table3').bootstrapTable('getSelections')[0];
                    if (!selectedCottage) {
                        showErrorMessage('Please select a cottage.');
                        return;
                    }

                    cottageId = selectedCottage.id;
                    cottageRate = parseFloat(selectedCottage.cottage_rate);

                    // Calculate the check-in and check-out dates
                    let inDate = new Date();

                    // Format dates as 'YYYY-MM-DD'
                    const formatDate = (date) => date.toISOString().split('T')[0];
                    bookingDate = formatDate(inDate);

                    totalAmount = 1 * cottageRate;
                }

                // Generate transaction number via AJAX
                // Submit transaction details via AJAX
                $.ajax({
                    method: 'POST',
                    url: '/resortTransactions',
                    data: {
                        transaction_number: transactionNumber,
                        customer_name: customerName,
                        customer_email: customerEmail,
                        customer_number: customerNumber,
                        customer_type: customerType,
                        resort_cottage_id: cottageId,
                        booking_date: bookingDate,
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
