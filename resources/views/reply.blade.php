@extends('layouts.base')
@section('APP_TITLE')
    Reply Inquiry
@endsection
@section('APP_CONTENT')
    <div class="view-mail-wrap">
        <div class="mail-title">
            <h2>Compose Mail</h2>
            <div class="view-mail-action">
                <a class="compose-draft-bt" href="#" onclick="markAsRead({{ $inquiry->id }})"><i class="fa fa-pencil"></i> Mark as Read</a>
                <a class="compose-discard-bt" href="#" onclick="deleteInquiry({{ $inquiry->id }})"><i class="fa fa-trash"></i> Delete</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="compose-email-to">
                            <span>To :</span>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <input type="email" name="recipient_email" id="recipient_email" class="form-control" value="{{ $inquiry->customer_email }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="compose-email-to compose-subject-title">
                            <span>Subject :</span>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <input type="text" name="recipient_subject" id="recipient_subject" class="form-control" value="{{ $inquiry->customer_subject }}" readonly />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="text-editor-compose">
                    <textarea id="summernote5"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="view-mail-reply-list">
                    <ul class="view-mail-forword">
                        <li>
                            <button type="button" class="btn btn-primary btn-md" id="send-btn">
                                <i class="fa fa-reply"></i> Send
                            </button>
                        </li>
                        <li><a class="compose-discard-bt" href="#"><i class="fa fa-times"></i> Discard</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('APP_SCRIPT')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#summernote5').summernote({
                height: 400,
                callbacks: {
                    onImageUpload: function (files) {
                        uploadImage(files[0]);
                    }
                }
            });

            function uploadImage(file) {
                let formData = new FormData();
                formData.append("image", file);

                $.ajax({
                    url: "{{ route('uploadImage') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $('#summernote5').summernote('insertImage', data.url);
                    },
                    error: function () {
                        alert("Image upload failed.");
                    }
                });
            }

            $('#send-btn').click(function (event) {
                event.preventDefault();

                let button = $(this);
                if (button.prop('disabled')) return; // Prevent multiple clicks
                button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');

                let content = $('#summernote5').summernote('code');

                $.ajax({
                    method: 'POST',
                    url: '{{ route('replyInquiry') }}',
                    data: {
                        inquiry_id: {{ $inquiry->id }},
                        content: content
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        $('#table1').bootstrapTable('refresh');
                        showSuccessMessage(response.msg);
                    },
                    error: function (jqXHR) {
                        let errorMsg = "An unexpected error occurred.";
                        if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                            errorMsg = jqXHR.responseJSON.message + "\n";
                            for (const [field, messages] of Object.entries(jqXHR.responseJSON.errors)) {
                                errorMsg += `- ${messages.join(', ')}\n`;
                            }
                        }
                        showErrorMessage(errorMsg);
                    },
                    complete: function () {
                        button.prop('disabled', false).html('<i class="fa fa-reply"></i> Send');
                    }
                });
            });
        });

        function markAsRead(inquiry_id) {
            $.ajax({
                method: 'PUT',
                url: `/contactInquiries/${inquiry_id}`,
                data: { status: 'Read' },
                dataType: 'JSON',
                success: function (response) {
                    $('#table1').bootstrapTable('refresh');
                    showSuccessMessage(response.msg);
                },
                error: function (jqXHR) {
                    handleAjaxError(jqXHR);
                }
            });
        }

        function deleteInquiry(inquiry_id) {
            $.ajax({
                method: 'DELETE',
                url: `/contactInquiries/${inquiry_id}`,
                dataType: 'JSON',
                success: function (response) {
                    $('#table1').bootstrapTable('refresh');
                    showSuccessMessage(response.msg);
                },
                error: function (jqXHR) {
                    handleAjaxError(jqXHR);
                }
            });
        }

        function handleAjaxError(jqXHR) {
            let errorMsg = "An unexpected error occurred.";
            if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                errorMsg = jqXHR.responseJSON.message + "\n";
                for (const [field, messages] of Object.entries(jqXHR.responseJSON.errors)) {
                    errorMsg += `- ${messages.join(', ')}\n`;
                }
            }
            showErrorMessage(errorMsg);
        }
    </script>
@endsection
