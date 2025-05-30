<div id="bookingModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <form id="bookingForm" class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Hotel Room Booking</h3>
            </div>
            <div class="modal-body">
                <div class="panel">
                    <div class="panel-body">
                        <div class="progress">
                            <div class="progress-bar progress-bar-custom" role="progressbar" id="progressBar"
                                style="width: 25%;"></div>
                        </div>
                        <div class="text-center" style="margin-top: 10px;">
                            <div class="step-indicator" data-step="1"><i class="fa fa-user"></i><br>Customer Info</div>
                            <div class="step-indicator" data-step="2"><i class="fa fa-calendar"></i><br>Dates</div>
                            <div class="step-indicator" data-step="3"><i class="fa fa-bed"></i><br>Room
                            </div>
                            <div class="step-indicator" data-step="4"><i class="fa fa-check-circle"></i><br>Summary
                            </div>
                        </div>

                        <div class="step-content mg-t-30" data-step="1">
                            <hr class="bg-danger">
                            <div class="form-group">
                                <label for="customer_name">Customer Name: <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="customer_name" id="customer_name"
                                    required />
                                <small class="text-muted">Name of the recipient.</small>
                            </div>
                            <div class="form-group">
                                <label for="customer_email">Customer Email: <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="customer_email" id="customer_email"
                                    required />
                                <small class="text-muted">Enter the email of the customer to receive a
                                    notification.</small>
                            </div>
                            <div class="form-group">
                                <label for="customer_number">Customer Number: <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon"
                                        style="padding: 5px 5px 0 5px; border: 1px solid #ced4da;">+63</span>
                                    <input type="text" class="form-control" name="customer_number"
                                        id="customer_number" data-mask="999-999-9999" placeholder="XXX-XXX-XXXX"
                                        required />
                                </div>
                                <small class="text-muted">Enter the remaining 9 digits after +63.</small>
                            </div>
                            <hr class="bg-danger">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="button"
                                        class="btn btn-custon-rounded-three btn-primary next-step">Next</button>
                                    <button type="button" class="btn btn-custon-rounded-three btn-danger"
                                        data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>

                        <div class="step-content mg-t-30" data-step="2" style="display:none;">
                            <hr class="bg-danger">
                            <div class="form-group">
                                <label for="check_in_date">Check-in Date: <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="check_in_date" id="check_in_date"
                                    required readonly />
                            </div>
                            <div class="form-group">
                                <label for="check_out_date">Check-out Date: <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="check_out_date" id="check_out_date"
                                    required readonly />
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

                        <div class="step-content mg-t-30" data-step="3" style="display:none;">
                            <hr class="bg-danger">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="section-title">
                                        <h5>Hotel Room Details</h5>
                                    </div>
                                </div>
                                <!-- Image Gallery Section -->
                                <div class="col-lg-5">
                                    <div class="position-relative mb-3">
                                        <img src="" class="img-fluid rounded main-room-image"
                                            alt="Room Image" style="cursor: pointer;">
                                    </div>
                                    <div class="row g-2 modal-room-pictures">
                                        <!-- Thumbnails will be inserted here by JS -->
                                    </div>
                                </div>

                                <!-- Room Details Section -->
                                <div class="col-lg-7">
                                    <div class="resort__details__text">
                                        <div class="resort__details__title mb-3">
                                            <h3 class="fw-bold modal-room-name"></h3>
                                            <p class="text-muted modal-room-description"></p>
                                        </div>

                                        <div class="resort__details__widget mb-3">
                                            <ul class="list-unstyled">
                                                <li><strong>Type:</strong> <span class="modal-room-type"></span></li>
                                                <li><strong>Rate:</strong> <span class="modal-room-rate"></span></li>
                                                <li><strong>Capacity:</strong> <span
                                                        class="modal-room-capacity"></span></li>
                                            </ul>
                                        </div>

                                        <div class="section-title mb-3">
                                            <h5 class="fw-semibold">Amenities</h5>
                                        </div>
                                        <ul class="list-unstyled d-flex flex-wrap gap-2 modal-room-amenities">
                                            <!-- Amenities will be inserted here by JS -->
                                        </ul>
                                    </div>
                                </div>
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

                        <div class="step-content mg-t-30" data-step="4" style="display:none;">
                            <hr class="bg-danger">
                            <div class="row d-flex justify-content-center">
                                <div class="col-md-12">
                                    <div class="section-title">
                                        <h5>Booking Summary</h5>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="shadow-reset">
                                        <div class="panel-body">
                                            <div class="section-title">
                                                <h5 style="font-size: 15px;">Transaction Number: <span
                                                        id="transactionNumber"></span></h5>
                                            </div>
                                            <hr style="color: #0000004f; margin-top: 5px; margin-bottom: 5px">
                                            <div class="table-responsive p-2">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                        <tr class="add">
                                                            <td>Customer Name</td>
                                                            <td>Customer Email</td>
                                                            <td>Customer Contact</td>
                                                        </tr>
                                                        <tr class="content">
                                                            <td class="font-weight-bold" id="customerName"></td>
                                                            <td class="font-weight-bold" id="customerEmail"></td>
                                                            <td class="font-weight-bold" id="customerNumber"></td>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <hr style="color: #0000004f; margin-top: 5px; margin-bottom: 5px">
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
                                                            <td class="font-weight-bold" id="checkInDate"></td>
                                                            <td class="font-weight-bold" id="checkInTime">1:00 PM</td>
                                                            <td class="font-weight-bold" id="checkOutDate"></td>
                                                            <td class="font-weight-bold" id="checkOutTime">12:00 PM
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
                                                            <td class="text-center">Day(s)</td>
                                                            <td class="text-center">Room Rate / Day</td>
                                                            <td class="text-center">Total Amount</td>
                                                        </tr>
                                                        <tr class="content">
                                                            <td id="roomDescription"></td>
                                                            <td class="text-center" id="stayDays"></td>
                                                            <td class="text-center" id="roomRate"></td>
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
