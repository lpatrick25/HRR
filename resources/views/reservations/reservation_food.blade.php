<div id="foodReservationModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <form id="foodReservationForm" class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add Food Order</h3>
            </div>
            <div class="modal-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="progress">
                            <div class="progress-bar progress-bar-custom" role="progressbar" id="progressBar"
                                style="width: 25%;"></div>
                        </div>
                        <div class="text-center">
                            <div class="step-indicator" data-step="1"><i class="fa fa-list-alt"></i><br>Food Category
                            </div>
                            <div class="step-indicator" data-step="2"><i class="fa fa-cutlery"></i><br>Food Items</div>
                            <div class="step-indicator" data-step="3"><i class="fa fa-user"></i><br>Customer Info</div>
                            <div class="step-indicator" data-step="4"><i class="fa fa-check-circle"></i><br>Summary
                            </div>
                        </div>

                        <!-- Step 1: Select Food Category -->
                        <div class="step-content mg-t-30" data-step="1">
                            <hr class="bg-danger">
                            <div class="form-group">
                                <label for="food_category">Food Category: <span class="text-danger">*</span></label>
                                <select id="food_category" name="food_category" class="form-control" required>
                                    <option value="">Select a category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Select a food category to view available items.</small>
                            </div>
                            <hr class="bg-danger">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="button"
                                        class="btn btn-custon-rounded-three btn-primary next-step">Next</button>
                                    <button type="button" data-dismiss="modal"
                                        class="btn btn-custon-rounded-three btn-danger">Cancel</button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Select Food Items -->
                        <div class="step-content mg-t-30" data-step="2" style="display:none;">
                            <hr class="bg-danger">
                            <table id="foodTable" data-toggle="table" data-click-to-select="true" data-pagination="true"
                                data-search="false">
                                <thead>
                                    <tr>
                                        <th data-field="state" data-checkbox="true"></th>
                                        <th data-field="food_name">Food Name</th>
                                        <th data-field="category_name">Category</th>
                                        <th data-field="food_price">Price</th>
                                        <th data-field="food_unit">Unit</th>
                                        <th data-field="quantity" data-formatter="quantityFormatter">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <hr class="bg-danger">
                            <div class="row">
                                <div class="col-lg-6 text-left">
                                    <button type="button"
                                        class="btn btn-custon-rounded-three btn-warning prev-step">Back</button>
                                </div>
                                <div class="col-lg-6 text-right">
                                    <button type="button"
                                        class="btn btn-custon-rounded-three btn-primary next-step">Next</button>
                                    <button type="button" data-dismiss="modal"
                                        class="btn btn-custon-rounded-three btn-danger">Cancel</button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Customer Information -->
                        <div class="step-content mg-t-30" data-step="3" style="display:none;">
                            <hr class="bg-danger">
                            <div class="form-group">
                                <label for="customer_name">Customer Name: <span class="text-danger">*</span></label>
                                <input type="text" id="customer_name" name="customer_name" class="form-control"
                                    required />
                                <small class="text-muted">Enter the customer's full name.</small>
                            </div>
                            <div class="form-group">
                                <label for="customer_email">Customer Email: <span class="text-danger">*</span></label>
                                <input type="email" id="customer_email" name="customer_email" class="form-control"
                                    required />
                                <small class="text-muted">Enter the customer's email for order confirmation.</small>
                            </div>
                            <div class="form-group">
                                <label for="customer_number">Customer Number: <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">+63</span>
                                    <input type="text" id="customer_number" name="customer_number"
                                        class="form-control" placeholder="XXX-XXX-XXXX" data-mask="999-999-9999"
                                        required />
                                </div>
                                <small class="text-muted">Enter the customer's phone number after +63.</small>
                            </div>
                            <div class="form-group">
                                <label for="reservation_date">Reservation Date: <span
                                        class="text-danger">*</span></label>
                                <input type="date" id="reservation_date" name="reservation_date"
                                    class="form-control" required />
                                <small class="text-muted">Select the date for the food reservation.</small>
                            </div>
                            <hr class="bg-danger">
                            <div class="row">
                                <div class="col-lg-6 text-left">
                                    <button type="button"
                                        class="btn btn-custon-rounded-three btn-warning prev-step">Back</button>
                                </div>
                                <div class="col-lg-6 text-right">
                                    <button type="button"
                                        class="btn btn-custon-rounded-three btn-primary next-step">Next</button>
                                    <button type="button" data-dismiss="modal"
                                        class="btn btn-custon-rounded-three btn-danger">Cancel</button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Order Summary -->
                        <div class="step-content mg-t-30" data-step="4" style="display:none;">
                            <hr class="bg-danger">
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-12">
                                    <h4>Food Reservation Summary</h4>
                                </div>
                                <div class="col-md-4">
                                    <div
                                        class="contact-client-single shadow-reset ct-client-b-mg-30 ct-client-b-mg-30-n contact-client-v2">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="contact-client-img contact-img-v2">
                                                    <a href="#"><img src="{{ asset('img/notification/5.jpg') }}"
                                                            alt="" /></a>
                                                    <h2><a id="customerName" class="contact-client-name"
                                                            href="#"></a></h2>
                                                    <h1><a href="#" id="orderType" data-type="select">Food
                                                            Reservation</a></h1>
                                                </div>
                                                <div class="contact-client-address">
                                                    <h3>Contact</h3>
                                                    <p id="customerEmail" class="address-client-ct client-addres-v2">
                                                    </p>
                                                    <p id="customerNumber"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="shadow-reset">
                                        <div class="panel-body">
                                            <div class="d-flex flex-row p-2">
                                                <img src="{{ asset('img/notification/5.jpg') }}" width="48"
                                                    alt="">
                                                <div class="d-flex flex-column">
                                                    <small>Transaction Number: <span
                                                            id="transactionNumber"></span></small>
                                                </div>
                                            </div>
                                            <hr style="color: #0000004f; margin-top: 5px; margin-bottom: 5px;">
                                            <div class="table-responsive p-2">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                        <tr class="add">
                                                            <td>Order Date</td>
                                                            <td>Total Amount</td>
                                                        </tr>
                                                        <tr class="content">
                                                            <td class="font-weight-bold" id="orderDate"></td>
                                                            <td class="font-weight-bold" id="totalPrice"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <hr style="color: #0000004f; margin-top: 5px; margin-bottom: 5px;">
                                            <div class="products p-2">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr class="add">
                                                            <td>Food Item</td>
                                                            <td class="text-center">Quantity</td>
                                                            <td class="text-center">Price</td>
                                                            <td class="text-center">Subtotal</td>
                                                        </tr>
                                                    <tbody id="foodItems"></tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <hr style="color: #0000004f; margin-top: 5px; margin-bottom: 5px;">
                                        </div>
                                        <div class="panel-footer text-right">
                                            <div class="row">
                                                <div class 1px solid #ddd; padding: 8px;" class="col-lg-6 text-left">
                                                    <button type="button"
                                                        class="btn btn-md btn-custon-rounded-three btn-warning prev-step">Back</button>
                                                </div>
                                                <div class="col-lg-6 text-right">
                                                    <button type="submit"
                                                        class="btn btn-md btn-custon-rounded-three btn-success">Confirm</button>
                                                    <button type="button" data-dismiss="modal"
                                                        class="btn btn-custon-rounded-three btn-danger">Cancel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
