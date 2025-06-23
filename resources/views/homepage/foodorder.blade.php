@extends('homepage.base')

@section('APP-TITLE')
    POS Food Ordering
@endsection

@section('active-categories')
    active
@endsection

@section('custom-css')
    <style>
        .filter-btn {
            margin: 2px;
        }

        .card-img-wrapper {
            position: relative;
            width: 100%;
            padding-top: 56.25%;
            overflow: hidden;
            background-color: #f8f9fa;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .card-img-wrapper img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cart-summary {
            position: sticky;
            top: 20px;
        }

        .pos-input {
            width: 70px;
        }

        @media only screen and (max-width: 767px) {
            .product__sidebar__view {
                margin-bottom: 30px;
            }

            .cart-summary {
                position: static;
            }
        }
    </style>
@endsection

@section('APP-CONTENT')
    <div class="container-fluid py-4">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-2 product__sidebar__view">
                <h4>Categories</h4>
                <div class="d-flex flex-column">
                    <a href="{{ route('homepage-foodOrder') }}"
                        class="btn btn-outline-primary filter-btn mb-2 {{ !$selectedCategory ? 'active' : '' }}">All</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('food.index', ['category_id' => $category->id]) }}"
                            class="btn btn-outline-primary filter-btn mb-2 {{ $selectedCategory == $category->id ? 'active' : '' }}">
                            {{ $category->category_name }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-md-7">
                <div class="row">
                    @foreach ($foods as $food)
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-img-wrapper">
                                    <img src="{{ asset($food->picture) }}" alt="{{ $food->food_name }}">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $food->food_name }}</h5>
                                    <p class="card-text">
                                        ₱{{ $food->food_price }} / {{ $food->food_unit }}
                                    </p>
                                    <form action="{{ route('food.add-to-cart') }}" method="POST"
                                        class="d-flex align-items-center">
                                        @csrf
                                        <input type="hidden" name="food_id" value="{{ $food->id }}">
                                        <input type="number" name="quantity" class="form-control pos-input me-2"
                                            min="1" value="1">
                                        <button type="submit" class="btn btn-primary btn-sm">Add</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-3">
                <div class="card cart-summary">
                    <div class="card-body">
                        <h5 class="card-title">Current Order</h5>
                        @if (!empty($cart))
                            <form action="{{ route('food.update-cart') }}" method="POST">
                                @csrf
                                <ul class="list-group mb-3">
                                    @foreach ($cart as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                {{ $item['name'] }}<br>
                                                ₱{{ $item['price'] }} x
                                                <input type="number" name="quantities[{{ $item['id'] }}]"
                                                    value="{{ $item['quantity'] }}" min="0" class="pos-input">
                                            </div>
                                            <span>₱{{ $item['price'] * $item['quantity'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <p class="fw-bold">
                                    Total:
                                    ₱{{ collect($cart)->sum(function ($item) {return $item['price'] * $item['quantity'];}) }}
                                </p>
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-secondary btn-sm">Update</button>
                                    <a href="{{ route('food.checkout') }}" class="btn btn-primary btn-sm">Checkout</a>
                                </div>
                            </form>
                        @else
                            <p>No items in order.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('APP-SCRIPT')
    <script>
        // Ensure quantity inputs don't go below 0
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('change', () => {
                if (input.value < 0) input.value = 0;
            });
        });
    </script>
@endsection
