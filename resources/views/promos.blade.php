@extends('layouts.base')
@section('APP_TITLE')
    Promo's
@endsection
@section('APP_CONTENT')
    <div class="button-style-three" id="toolbar1">
        <button type="button" class="btn btn-custon-rounded-three btn-primary" id="add-btn-one">
            <span class="adminpro-icon adminpro-plus-add-button-in"></span> Add New
        </button>
    </div>

    <table id="table1" data-toggle="table" data-auto-refresh="true" data-auto-refresh-interval="20" data-fixed-columns="true"
        data-fixed-number="1" data-i18n-enhance="true" data-mobile-responsive="true" data-multiple-sort="true"
        data-page-jump-to="true" data-pipeline="true" data-reorder-rows="true" data-sticky-header="true"
        data-toolbar="#toolbar1" data-pagination="true" data-search="true" data-show-refresh="true"
        data-show-copy-rows="true" data-show-columns="true" data-url="/seasonalPromos">
        <thead>
            <tr>
                <th data-field="count">#</th>
                <th data-field="title">Title</th>
                <th data-field="description">Description</th>
                <th data-field="start_date">Start Date</th>
                <th data-field="end_date">End Date</th>
                <th data-field="promo_code">Promo Code</th>
                <th data-field="created_at">Created At</th>
                <th data-field="action">Actions</th>
            </tr>
        </thead>
    </table>

    <!-- Add Seasonal Promo Modal -->
    <div id="addModal" class="modal animated fadeIn" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="addForm" class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add Seasonal Promo</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Enter promo title" required>
                    </div>
                    <div class="form-group">
                        <label>Description <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Enter promo description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Promo Code <span class="text-danger">*</span></label>
                        <input type="text" name="promo_code" class="form-control text-uppercase" maxlength="15"
                            placeholder="Enter promo code" required>
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
@endsection
@section('APP_SCRIPT')
    <script type="text/javascript">
        function trash_promo(promo_id) {
            $.ajax({
                method: 'DELETE',
                url: `/seasonalPromos/${promo_id}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    $('#table1').bootstrapTable('refresh');
                    showSuccessMessage(response.msg);
                },
                error: function(jqXHR) {
                    if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                        let errors = jqXHR.responseJSON.errors;
                        let errorMsg = `${jqXHR.responseJSON.message}\n`;
                        for (const [field, messages] of Object.entries(errors)) {
                            errorMsg += `- ${messages.join(', ')}\n`;
                        }
                        showErrorMessage(errorMsg);
                    } else {
                        showErrorMessage("An unexpected error occurred. Please try again.");
                    }
                }
            });
        }

        $(document).ready(function() {

            // Open the add modal and reset the form
            $('#add-btn-one').click(function(event) {
                event.preventDefault();
                $('#addForm').trigger('reset');
                $('#addModal').modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });
            });

            // Handle adding new user
            $('#addForm').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    method: 'POST',
                    url: '/seasonalPromos',
                    data: $('#addForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        if (response.valid) {
                            $('#addModal').modal('hide');
                            $('#addForm').trigger('reset');
                            $('#table1').bootstrapTable('refresh');
                            showSuccessMessage(response.msg);
                        }
                    },
                    error: function(jqXHR) {
                        if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                            let errors = jqXHR.responseJSON.errors;
                            let errorMsg = `${jqXHR.responseJSON.message}\n`;
                            for (const [field, messages] of Object.entries(errors)) {
                                errorMsg += `- ${messages.join(', ')}\n`;
                            }
                            showErrorMessage(errorMsg);
                        } else {
                            showErrorMessage("An unexpected error occurred. Please try again.");
                        }
                    }
                });
            });
        });
    </script>
@endsection
