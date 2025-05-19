<div id="checkBookings" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="checkBookingsLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="searchBookingDetails" class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="checkBookingsLabel">
                    <i class="fa fa-info-circle mr-2"></i> My Booking Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body px-4 py-4" id="booking-details">
                <div class="form-group">
                    <label for="search_name">Customer Name <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control" name="search_name" id="search_name"
                            placeholder="Full Name" required />
                    </div>
                    <small class="form-text text-muted">Enter the full name you provided during the
                        reservation.</small>
                </div>

                <div class="form-group">
                    <label for="search_email">Customer Email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        </div>
                        <input type="email" class="form-control" name="search_email" id="search_email"
                            placeholder="example@domain.com" required />
                    </div>
                    <small class="form-text text-muted">Enter the email address used for the reservation.</small>
                </div>

                <div class="form-group">
                    <label for="search_number">Customer Number <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">+63</span>
                        </div>
                        <input type="text" class="form-control" name="search_number" id="search_number"
                            data-mask="999-999-9999" placeholder="XXX-XXX-XXXX" required />
                    </div>
                    <small class="form-text text-muted">Enter the mobile number you provided during the
                        reservation.</small>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search mr-1"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>
