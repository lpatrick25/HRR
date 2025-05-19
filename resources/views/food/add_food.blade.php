<div id="addFoodModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="addFoodForm" class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add Food
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Food Name: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="login-input-area">
                            <input type="text" name="food_name" id="food_name" />
                            <i class="fa fa-home login-user"></i>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Food Category: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="login-input-area">
                            <select name="food_category_id" id="food_category_id" class="form-control">
                                @foreach ($foodCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Food Status: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="login-input-area">
                            <select name="food_status" id="food_status" class="form-control">
                                <option value="Available">Available</option>
                                <option value="Not Available">Not Available</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Food Price: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" name="food_price" id="food_price" class="form-control" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="login-input-head">
                            <p>Food Unit: <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="login-input-area">
                            <select name="food_unit" id="food_unit" class="form-control">
                                <option value="Piece">Piece</option>
                                <option value="Slice">Slice</option>
                                <option value="Serving">Serving</option>
                                <option value="Platter">Platter</option>
                                <option value="Plate">Plate</option>
                                <option value="Set">Set</option>
                                <option value="Combo">Combo</option>
                                <option value="Milliliter">Milliliter</option>
                                <option value="Liter">Liter</option>
                                <option value="Cup">Cup</option>
                                <option value="Glass">Glass</option>
                                <option value="Bottle">Bottle</option>
                                <option value="Can">Can</option>
                            </select>
                        </div>
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
