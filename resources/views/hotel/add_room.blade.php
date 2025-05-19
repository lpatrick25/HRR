<div id="addRoomModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="addRoomForm" class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add Hotel Room
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Room Name: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="login-input-area">
                            <input type="text" name="room_name" id="room_name" />
                            <i class="fa fa-home login-user"></i>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Room Type: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="login-input-area">
                            <select name="hotel_type_id" id="hotel_type_id" class="form-control">
                                @foreach ($roomTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Room Status: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="login-input-area">
                            <select name="room_status" id="room_status" class="form-control">
                                <option value="Available">Available</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Room Rate: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" name="room_rate" id="room_rate" class="form-control" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Room Capacity: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" name="room_capacity" id="room_capacity" class="form-control" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-custon-rounded-three btn-primary">
                    <span class="fa fa-save"></span> Save
                </button>
                <button type="button" class="btn btn-custon-rounded-three btn-danger" data-dismiss="modal">
                    <span class="fa fa-times"></span> Cancel
                </button>
            </div>
        </form>
    </div>
</div>
