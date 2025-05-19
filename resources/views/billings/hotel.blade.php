@extends('layouts.base')
@section('APP_TITLE')
    Hotel Billings
@endsection
@section('active_billings')
    active
@endsection
@section('APP_CONTENT')
    <table id="table1" data-toggle="table" data-click-to-select="true" data-single-select="true" data-url="/hotelPayments"
        data-pagination="true" data-search="true" data-toolbar="#toolbar1">
        <thead>
            <tr>
                <th class="text-center" data-field="count">#</th>
                <th class="text-left" data-field="transaction_number">Transaction Number</th>
                <th class="text-left" data-field="customer_name">Customer Name</th>
                <th class="text-left" data-field="room_name">Room Name</th>
                <th class="text-left" data-field="payment_method">Payment Method</th>
                <th class="text-right" data-field="total_amount">Total Amount</th>
                <th class="text-right" data-field="amount_paid">Amount Paid</th>
                <th class="text-left" data-field="status">Status</th>
            </tr>
        </thead>
    </table>
@endsection
@section('APP_SCRIPT')
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>
@endsection
