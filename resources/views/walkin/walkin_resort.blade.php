<div id="walkInModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <form id="walkInForm" class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Walk-In Cottage Reservation</h3>
            </div>
            <div class="modal-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="progress">
                            <div class="progress-bar progress-bar-custom" role="progressbar" id="progressBar"
                                style="width: 25%;"></div>
                        </div>
                        <div class="text-center">
                            <div class="step-indicator" data-step="1"><i
                                    class="glyphicon glyphicon-bed"></i><br>Available Cottage</div>
                            <div class="step-indicator" data-step="2"><i
                                    class="glyphicon glyphicon-user"></i><br>Customer Info</div>
                            <div class="step-indicator" data-step="3"><i
                                    class="glyphicon glyphicon-ok-circle"></i><br>Summary</div>
                        </div>

                        <!-- Step 1: Available Cottage -->
                        <div class="step-content mg-t-30" data-step="1" style="display:none;">
                            <hr class="bg-danger">
                            <table id="table3" data-toggle="table" data-click-to-select="true"
                                data-single-select="true" data-pagination="true" data-search="false">
                                <thead>
                                    <tr>
                                        <th data-field="state" data-checkbox="true"></th>
                                        <th data-field="cottage_name">Cottage Name</th>
                                        <th data-field="cottage_type" data-formatter="cottageTypeFormatter">Cottage Type
                                        </th>
                                        <th data-field="cottage_rate">Cottage Rate</th>
                                        <th data-field="cottage_capacity">Cottage Capacity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Dynamic cottage options will be populated here -->
                                </tbody>
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
                                    <button type="button" class="btn btn-custon-rounded-three btn-danger"
                                        data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Customer Information -->
                        <div class="step-content mg-t-30" data-step="2" style="display:none;">
                            <hr class="bg-danger">
                            <div class="form-group">
                                <label for="customer_name">Customer Name: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="customer_name" id="customer_name"
                                    required />
                                <small class="text-muted">Enter the customer's full name.</small>
                            </div>
                            <div class="form-group">
                                <label for="customer_email">Customer Email: <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="customer_email" id="customer_email"
                                    required />
                                <small class="text-muted">Enter the customer's email for booking confirmation.</small>
                            </div>
                            <div class="form-group">
                                <label for="customer_number">Customer Number: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">+63</span>
                                    <input type="text" class="form-control" name="customer_number"
                                        id="customer_number" data-mask="999-999-9999" placeholder="XXX-XXX-XXXX"
                                        required />
                                </div>
                                <small class="text-muted">Enter the customer's phone number after +63.</small>
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
                                    <button type="button" class="btn btn-custon-rounded-three btn-danger"
                                        data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Booking Summary -->
                        <div class="step-content mg-t-30" data-step="3" style="display:none;">
                            <hr class="bg-danger">
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-12">
                                    <h4>Booking Summary</h4>
                                </div>
                                <div class="col-md-4">
                                    <div
                                        class="contact-client-single shadow-reset ct-client-b-mg-30 ct-client-b-mg-30-n contact-client-v2">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="contact-client-img contact-img-v2">
                                                    <a href="#"><img src="{{ asset('img/notification/5.jpg') }}"
                                                            alt="" />
                                                    </a>
                                                    <h2><a class="contact-client-name" href="#"
                                                            id="customerName"></a>
                                                    </h2>
                                                    <h1><a id="group" data-type="select" data-pk="1"
                                                            data-value="5" data-source="/groups"
                                                            data-title="Select group" href="#">Customer</a></h1>
                                                </div>
                                                <div class="contact-client-address">
                                                    <h3>Contact</h3>
                                                    <p class="address-client-ct client-addres-v2" id="customerEmail">
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
                                                <img src="{{ asset('img/notification/5.jpg') }}"" width="48">
                                                <div class="d-flex flex-column">
                                                    <small>Transaction Number: <span
                                                            id="transactionNumber"></span></small>
                                                </div>
                                            </div>
                                            <hr style="color: #0000004f; margin-top: 5px; margin-bottom: 5px">
                                            <div class="table-responsive p-2">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                        <tr class="add">
                                                            <td>Booking Date</td>
                                                            <td>Arrival Time</td>
                                                        </tr>
                                                        <tr class="content">
                                                            <td class="font-weight-bold" id="bookingDate"></td>
                                                            <td class="font-weight-bold" id="arrivalTime">10:00 AM
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <hr style="color: #0000004f; margin-top: 5px; margin-bottom: 5px">
                                            <div class="products p-2">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                        <tr class="add">
                                                            <td>Description</td>
                                                            <td class="text-center">Days</td>
                                                            <td class="text-center">Cottage Rate / Day</td>
                                                            <td class="text-center">Total Amount</td>
                                                        </tr>
                                                        <tr class="content">
                                                            <td id="cottageDescription"></td>
                                                            <td class="text-center" id="stayDays"></td>
                                                            <td class="text-center" id="cottageRate"></td>
                                                            <td class="text-center" id="totalPrice"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <hr style="color: #0000004f; margin-top: 5px; margin-bottom: 5px">
                                        </div>
                                        <div class="panel-footer text-right">
                                            <div class="row">
                                                <div class="col-lg-6 text-left">
                                                    <button type="button"
                                                        class="btn btn-md btn-custon-rounded-three btn-warning prev-step">Back</button>
                                                </div>
                                                <div class="col-lg-6 text-right">
                                                    <button type="submit"
                                                        class="btn btn-md btn-custon-rounded-three btn-success">Confirm</button>
                                                    <button type="button"
                                                        class="btn btn-custon-rounded-three btn-danger"
                                                        data-dismiss="modal">Cancel</button>
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
