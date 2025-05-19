@extends('layouts.base')
@section('APP_TITLE')
    Contact Inquiries
@endsection
@section('APP_CONTENT')
    <div class="button-style-three" id="toolbar1">
    </div>

    <table id="table1" data-toggle="table" data-auto-refresh="true" data-auto-refresh-interval="20" data-fixed-columns="true"
        data-fixed-number="1" data-i18n-enhance="true" data-mobile-responsive="true" data-multiple-sort="true"
        data-page-jump-to="true" data-pipeline="true" data-reorder-rows="true" data-sticky-header="true"
        data-toolbar="#toolbar1" data-pagination="true" data-search="true" data-show-refresh="true"
        data-show-copy-rows="true" data-show-columns="true" data-url="/contactInquiries">
        <thead>
            <tr>
                <th data-field="count">#</th>
                <th data-field="customer_name">Name</th>
                <th data-field="customer_email">Email</th>
                <th data-field="customer_subject">Subject</th>
                <th data-field="customer_message">Message</th>
                <th data-field="created_at">Date</th>
                <th data-field="action">Actions</th>
            </tr>
        </thead>
    </table>
@endsection
@section('APP_SCRIPT')
    <script type="text/javascript">
        function markAsRead(inquiry_id) {
            $.ajax({
                method: 'PUT',
                url: `/contactInquiries/${inquiry_id}`,
                data: {
                    status: 'Read'
                },
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

        function deleteInquiry(inquiry_id) {
            $.ajax({
                method: 'DELETE',
                url: `/contactInquiries/${inquiry_id}`,
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

        function replyInquiry(inquiry_id) {
            window.location.href  = `/owner/replyInquiry/${inquiry_id}`;
        }

        $(document).ready(function() {

        });
    </script>
@endsection
