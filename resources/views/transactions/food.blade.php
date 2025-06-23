@extends('layouts.base')

@section('APP_TITLE')
    Food Transactions
@endsection

@section('active_transactions')
    active
@endsection

@section('APP_CONTENT')
    <div class="button-style-three" id="toolbar">
        <button type="button" class="btn btn-custon-rounded-three btn-primary" id="walkInBtn">
            <span class="adminpro-icon adminpro-plus-add-button-in"></span> Walk-In Order
        </button>
    </div>
    <table id="table1" data-toggle="table" data-auto-refresh="true" data-auto-refresh-interval="20" data-fixed-columns="true"
        data-fixed-number="1" data-i18n-enhance="true" data-mobile-responsive="true" data-multiple-sort="true"
        data-page-jump-to="true" data-pipeline="true" data-reorder-rows="true" data-sticky-header="true"
        data-toolbar="#toolbar" data-pagination="true" data-search="true" data-show-refresh="true"
        data-show-copy-rows="true" data-show-columns="true" data-url="/foodTransactions">
        <thead>
            <tr>
                <th data-field="count">ID</th>
                <th data-field="transaction_number">Transaction #</th>
                <th data-field="customer_name">Customer Name</th>
                <th data-field="food_items">Food Orders</th>
                <th data-field="reservation_date">Reservation Date</th>
                <th data-field="total_amount">Amount</th>
                <th data-field="status">Status</th>
                <th data-field="action" data-formatter="actionFormatter">Actions</th>
            </tr>
        </thead>
    </table>

    @include('walkin.walkin_food')
@endsection

@section('APP_SCRIPT')
    <script type="text/javascript">
        let currentStepWalkIn = 1;
        let transactionNumber = '';

        // Function to show steps
        function showStep(modalId, step) {
            $(`#${modalId} .step-content`).hide();
            $(`#${modalId} .step-content[data-step="${step}"]`).show();
            $(`#${modalId} .step-indicator`).removeClass('active');
            $(`#${modalId} .step-indicator[data-step="${step}"]`).addClass('active');

            const progressPercentage = (step / 4) * 100;
            $(`#${modalId} #progressBar`).css('width', `${progressPercentage}%`);

            if (step === 2 && modalId === 'walkInModal') {
                const categoryId = $('#food_category').val();
                if (categoryId) {
                    fetchAvailableFoods(categoryId);
                }
            }
        }

        // Function to validate steps
        function validateStep(modalId, step) {
            let valid = true;

            if (modalId === 'walkInModal') {
                if (step === 1) {
                    if (!$('#food_category').val()) {
                        showErrorMessage('Please select a food category.');
                        valid = false;
                    }
                }
                if (step === 2) {
                    let selectedFoods = $('#table3').bootstrapTable('getSelections');
                    if (selectedFoods.length === 0) {
                        showErrorMessage('Please select at least one food item.');
                        valid = false;
                    } else {
                        valid = selectedFoods.every(food => {
                            const quantityInput = $(`#quantity_${food.id}`);
                            const quantity = parseInt(quantityInput.val());
                            if (!quantity || quantity < 1) {
                                showErrorMessage(`Please enter a valid quantity for ${food.food_name}.`);
                                return false;
                            }
                            return true;
                        });
                    }
                }
                if (step === 3) {
                    if (!$('#customer_name').val() || !$('#customer_email').val() || !$('#customer_number').val()) {
                        showErrorMessage('Please fill in all customer information.');
                        valid = false;
                    }
                }
            }

            return valid;
        }



        // Quantity formatter for the table
        function quantityFormatter(value, row) {
            return `
                <input type="number" id="quantity_${row.id}" class="form-control pos-input" min="1" value="1">
            `;
        }

        // Action formatter for the transactions table
        function actionFormatter(value, row) {
            let actions = '';
            if (row.status === 'Pending') {
                actions +=
                    `<button class="btn btn-sm btn-success confirm-btn" data-id="${row.transaction_number}">Confirm</button>`;
            }
            if (row.status === 'Confirmed') {
                actions +=
                    `<button class="btn btn-sm btn-primary complete-btn" data-id="${row.transaction_number}">Complete</button>`;
            }
            return actions;
        }



        

        // Function to show error message
        function showErrorMessage(message) {
            alert(message); // Replace with a better notification system if available
        }

        // Function to show success message
        function showSuccessMessage(message) {
            alert(message); // Replace with a better notification system if available
        }

        $(document).ready(function() {
            $('#walkInBtn').click(function(event) {
                event.preventDefault();
                $('#walkInModal').modal({
                    backdrop: 'static',
                    keyboard: true,
                    show: true
                });
            });

            $('.next-step').click(function() {
                const modalId = $(this).closest('.modal').attr('id');
                if (validateStep(modalId, currentStepWalkIn)) {
                    if (modalId === 'walkInModal' && currentStepWalkIn === 3) {
                        updateSummary(modalId);
                    }
                    currentStepWalkIn++;
                    showStep(modalId, currentStepWalkIn);
                }
            });

            $('.prev-step').click(function() {
                currentStepWalkIn--;
                showStep('walkInModal', currentStepWalkIn);
            });

            $('#walkInForm').submit(function(event) {
                event.preventDefault();
                const selectedFoods = $('#table3').bootstrapTable('getSelections');
                const items = selectedFoods.map(food => ({
                    food_id: food.id,
                    quantity: parseInt($(`#quantity_${food.id}`).val())
                }));
                const formData = {
                    customer_name: $('#customer_name').val(),
                    customer_email: $('#customer_email').val(),
                    customer_number: '+63' + $('#customer_number').val(),
                    customer_type: 'Walk-in',
                    reservation_date: new Date().toISOString().split('T')[0],
                    items: items,
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '{{ route('food.order') }}',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#walkInModal').modal('hide');
                        $('#walkInForm').trigger('reset');
                        currentStepWalkIn = 1;
                        showStep('walkInModal', currentStepWalkIn);
                        showSuccessMessage('Order placed successfully!');
                        $('#table1').bootstrapTable('refresh');
                    },
                    error: function(xhr) {
                        showErrorMessage(xhr.responseJSON?.message ||
                            'An error occurred while placing the order.');
                    }
                });
            });

            // Handle confirm and complete buttons
            $('#table1').on('click', '.confirm-btn', function() {
                const transactionNumber = $(this).data('id');
                confirmTransaction(transactionNumber);
            });

            $('#table1').on('click', '.complete-btn', function() {
                const transactionNumber = $(this).data('id');
                completeTransaction(transactionNumber);
            });

            showStep('walkInModal', currentStepWalkIn);
        });
    </script>
@endsection
