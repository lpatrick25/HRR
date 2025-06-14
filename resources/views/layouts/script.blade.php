<!-- jquery
  ============================================ -->
<script src="{{ asset('js/vendor/jquery-1.11.3.min.js') }}"></script>
<!-- bootstrap JS
            ============================================ -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<!-- meanmenu JS
            ============================================ -->
<script src="{{ asset('js/jquery.meanmenu.js') }}"></script>
<!-- mCustomScrollbar JS
            ============================================ -->
<script src="{{ asset('js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<!-- sticky JS
            ============================================ -->
<script src="{{ asset('js/jquery.sticky.js') }}"></script>
<!-- scrollUp JS
            ============================================ -->
<script src="{{ asset('js/jquery.scrollUp.min.js') }}"></script>
<!-- counterup JS
            ============================================ -->
<script src="{{ asset('js/counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('js/counterup/waypoints.min.js') }}"></script>
<!-- cropper JS
            ============================================ -->
<script src="{{ asset('js/cropper/cropper.min.js') }}"></script>
<!-- notification JS
    ============================================ -->
<script src="{{ asset('js/Lobibox.js') }}"></script>
<!-- touchspin JS
    ============================================ -->
<script src="{{ asset('js/touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
<!-- datapicker JS
    ============================================ -->
<script src="{{ asset('js/datapicker/bootstrap-datepicker.js') }}"></script>
<!-- input-mask JS
    ============================================ -->
<script src="{{ asset('js/input-mask/jasny-bootstrap.min.js') }}"></script>
<script src="{{ asset('js/input-mask/jquery.mask.min.js') }}"></script>
<!-- chosen JS
    ============================================ -->
<script src="{{ asset('js/chosen/chosen.jquery.js') }}"></script>
<!-- select2 JS
    ============================================ -->
<script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
<!-- pwstrength JS
    ============================================ -->
<script src="{{ asset('js/password-meter/pwstrength-bootstrap.min.js') }}"></script>
<script src="{{ asset('js/password-meter/zxcvbn.js') }}"></script>
<!-- data table JS
    ============================================ -->
<script src="{{ asset('js/data-table/bootstrap-table.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-auto-refresh.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-copy-rows.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-fixed-columns.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-i18n-enhance.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-mobile.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-multiple-sort.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-page-jump-to.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-pipeline.js') }}"></script>
<script src="{{ asset('js/data-table/table-dragger.js') }}"></script>
{{-- <script src="{{ asset('js/data-table/bootstrap-table-reorder-columns.js') }}"></script> --}}
<script src="{{ asset('js/data-table/bootstrap-table-reorder-rows.js') }}"></script>
{{-- <script src="{{ asset('js/data-table/bootstrap-table-resizable.js') }}"></script> --}}
<script src="{{ asset('js/data-table/bootstrap-table-sticky-header.js') }}"></script>
<script src="{{ asset('js/data-table/bootstrap-table-toolbar.js') }}"></script>
<!-- summernote JS
  ============================================ -->
<script src="{{ asset('js/summernote.min.js') }}"></script>
<!-- main JS
            ============================================ -->
<script src="{{ asset('js/main.js') }}"></script>
<script type="text/javascript">
    function showSuccessMessage(message) {
        Lobibox.notify('success', {
            title: 'Success',
            position: "top right",
            msg: message
        });
    }

    function showErrorMessage(message) {
        Lobibox.notify('error', {
            title: 'Error',
            position: "top right",
            msg: message
        });
    }

    function showWarningMessage(message) {
        Lobibox.notify('warning', {
            title: 'Warning',
            position: "top right",
            msg: message
        });
    }

    $(document).ready(function() {
        var $table1 = $('#table1');
        $table1.bootstrapTable('destroy').bootstrapTable({
            searchAlign: 'left',
            buttonsAlign: 'left',
            paginationHAlign: 'left',
            paginationDetailHAlign: 'right',
            paginationVAlign: 'bottom',
            toolbarAlign: 'right',
            formatLoadingMessage: function formatLoadingMessage() {
                return 'Belles Bistro Resort and Hotel';
            },
        });

        var $table2 = $('#table2');
        $table2.bootstrapTable('destroy').bootstrapTable({
            searchAlign: 'left',
            buttonsAlign: 'left',
            paginationHAlign: 'left',
            paginationDetailHAlign: 'right',
            paginationVAlign: 'bottom',
            toolbarAlign: 'right',
            formatLoadingMessage: function formatLoadingMessage() {
                return 'Tia Inday Haven Farm Resort';
            },
        });

        var $table3 = $('#table3');
        $table3.bootstrapTable('destroy').bootstrapTable({
            searchAlign: 'left',
            buttonsAlign: 'left',
            paginationHAlign: 'left',
            paginationDetailHAlign: 'right',
            paginationVAlign: 'bottom',
            toolbarAlign: 'right',
            formatLoadingMessage: function formatLoadingMessage() {
                return 'Tia Inday Haven Farm Resort';
            },
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("select").select2({
            width: '100%'
        });
    });
</script>
