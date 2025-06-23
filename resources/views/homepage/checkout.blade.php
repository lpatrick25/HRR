@extends('homepage.base')

@section('APP-TITLE')
    POS Checkout
@endsection

@section('active-categories')
    active
@endsection

@section('custom-css')
    <style>
        .cart-item-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }
    </style>
@endsection

@section('APP-CONTENT')
    <div class="container py-4">
        <h2>Checkout</h2>
        <div class="row">
            <div class="col-md-6">
                <h4>Order Summary</h4>
                <ul class="list-group mb-3">
                    @foreach ($cart as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset($item['picture']) }}" alt="{{ $item['name'] }}" class="cart-item-img me-3">
                                <div>
                                    {{ $item['name'] }} ({{ $item['unit'] }})<br>
                                    ₱{{ $item['price'] }} x {{ $item['quantity'] }}
                                </div>
                            </div>
                            <span>₱{{ $item['price'] * $item['quantity'] }}</span>
                        </li>
                    @endforeach
                </ul>
                <p class="fw-bold">
                    Total: ₱{{ collect($cart)->sum(function ($item) {return $item['price'] * $item['quantity'];}) }}
                </p>
            </div>
            <div class="col-md-6">
                <h4>Customer Details</h4>
                <form id="order-form">
                    <div class="mb-3">
                        <label for="reservation_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="reservation_date" name="reservation_date"
                            value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="customer_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="customer_number" name="customer_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="customer_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                    </div>
                    <input type="hidden" name="customer_type" value="Walk-in">
                    <button type="submit" class="btn btn-primary">Process Order</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('APP-SCRIPT')
    <script>
        $('#order-form').on('submit', function(e) {
            e.preventDefault();

            const formData = $(this).serialize();

            $.ajax({
                url: "{{ route('food.order') }}",
                method: "POST",
                data: formData,
                success: function(response) {
                    if (response.transactionNumber) {
                        const url =
                            "{{ route('foodPayment', ['transactionNumber' => 'TRANSACTION_NUMBER']) }}"
                            .replace('TRANSACTION_NUMBER', response.transactionNumber);
                        window.open(`/guest/${response.transactionNumber}/paymentFood`, "_blank");
                    } else {
                        alert('Order processed, but no transaction number returned.');
                    }
                },
                error: function(xhr) {
                    alert('Error processing order.');
                    console.error(xhr.responseText);
                }
            });
        });
    </script>
@endsection
