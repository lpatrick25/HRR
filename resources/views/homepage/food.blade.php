@extends('homepage.base')

@section('APP-TITLE')
    Hotel Room Category
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

        @media only screen and (max-width: 767px) {
            .product__sidebar__view {
                margin-bottom: 30px;
            }
        }
    </style>
@endsection

@section('APP-CONTENT')
    <!-- Breadcrumb Begin -->
    <div class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__links">
                        <a href="{{ route('homepage-homepage') }}"><i class="fa fa-home"></i> Home</a>
                        <a href="{{ route('homepage-categories') }}">Categories</a>
                        <span>Restaurant</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <!-- Product Section Begin -->
    <section class="product-page spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product__page__content">
                        <div class="product__page__title">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="section-title">
                                        <h4>Food</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-0 mb-4 justify-content-center text-center">

                            <!-- Order Now Button -->
                            <div class="mt-3 w-100">
                                <a href="/guest/foodOrder"" class="btn btn-dark">Order Now</a>
                            </div>
                        </div>


                        <div class="row" id="food-list">
                            @foreach ($foods as $food)
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 d-flex align-items-stretch food-item"
                                    data-category="{{ $food->food_category_id }}">
                                    <div class="card mb-4 shadow-sm w-100">
                                        <div class="card-img-wrapper">
                                            <img src="{{ asset($food->picture) }}" class="card-img-top"
                                                alt="{{ $food->food_name }}" loading="lazy">
                                        </div>
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">{{ $food->food_name }}</h5>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Optional: Show message when no items are visible -->
                        <div id="no-results" class="text-center text-muted" style="display: none;">
                            <p>No items found for this category.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('APP-SCRIPT')
    <script type="text/javascript">
        $(document).ready(function() {
            console.log("jQuery ready. Food items:", $('.food-item').length, "Categories:", $('.filter-btn')
                .length - 1);

            // Show all items by default
            $('.food-item').show();
            $('.filter-btn[data-category="all"]').addClass('btn-dark').removeClass('btn-outline-dark');

            $('.filter-btn').on('click', function() {
                const selectedCategory = $(this).data('category').toString();
                console.log("Selected category:", selectedCategory);

                // Update button styling
                $('.filter-btn').removeClass('btn-dark').addClass('btn-outline-dark');
                $(this).removeClass('btn-outline-dark').addClass('btn-dark');

                // Show/hide food items
                $('.food-item').each(function() {
                    const itemCategory = $(this).data('category').toString();
                    console.log("Checking food item category:", itemCategory);

                    if (selectedCategory === 'all' || itemCategory === selectedCategory) {
                        $(this).fadeIn(300);
                    } else {
                        $(this).fadeOut(300);
                    }
                });
            });
        });
    </script>
@endsection
