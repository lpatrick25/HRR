<div id="updateCottageModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="updateCottageForm" class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Update Hotel Cottage
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Cottage Name: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="login-input-area">
                            <input type="text" name="cottage_name" id="cottage_name" />
                            <i class="fa fa-home login-user"></i>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Cottage Type: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="login-input-area">
                            <select name="resort_type_id" id="resort_type_id" class="form-control">
                                @foreach ($cottageTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Cottage Status: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="login-input-area">
                            <select name="cottage_status" id="cottage_status" class="form-control">
                                <option value="Available">Available</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Cottage Rate: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" name="cottage_rate" id="cottage_rate" class="form-control" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Cottage Capacity: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" name="cottage_capacity" id="cottage_capacity" class="form-control" />
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
