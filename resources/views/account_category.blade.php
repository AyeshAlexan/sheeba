@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
          <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
                <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
                <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
                <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
                <title>Account Category</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16v4H4z"/><path d="M4 10h10v10H4z"/><path d="M16 10h4v10h-4z"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Account Category</h3>
                                <p class="page-subtitle">The account types used across Chart of Accounts — Assets, Liabilities, Equity, Income, Expenses.</p>
                            </div>
                        </div>
                        <a class="btn btn-primary" onClick="add()" href="javascript:void(0)">
                            <i class="fas fa-plus"></i> Add Category
                        </a>
                    </div>

                    <div class="container-fluid px-0">
                        @if ($message = Session::get('added'))
                            <div class="alert alert-success"><p>{{ $message }}</p></div>
                        @endif

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="AccountCategoryTable">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div id="AccountCategoryCustomPager"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
         </div>

        <!-- Add/Edit modal -->
        <div class="modal fade" id="Store-modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="StoreModal">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="errMsgContainer"></div>
                        <form action="javascript:void(0)" id="StoreForm" name="StoreForm" class="form-horizontal">
                            <input type="hidden" name="id" id="id">
                            <div class="form-group mb-2">
                                <label class="control-label">Code</label>
                                <input type="text" class="form-control" id="code" name="code" placeholder="Code" maxlength="80" required>
                            </div>
                            <div class="form-group mb-2">
                                <label class="control-label">Description</label>
                                <input type="text" class="form-control" id="categoryName" name="categoryName" placeholder="e.g. Assets" maxlength="80" required>
                            </div>
                            <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>

                    <script src="assets/js/dt-custom-pager.js"></script>
                    <script type="text/javascript">
                    $(document).ready( function () {
                        $.ajaxSetup({
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                        });

                        var AccountCategoryTable = $('#AccountCategoryTable').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ url('account_category') }}",
                            columns: [
                                { data: 'code', name: 'code' },
                                { data: 'category', name: 'category' },
                                { data: 'action', name: 'action', orderable: false, searchable: false },
                            ],
                            order: [[0, 'asc']],
                            pageLength: 15,
                            lengthChange: false,
                        });

                        $('#AccountCategoryTable_wrapper').addClass('dt-collapsed');
                        DTCustomPager.init(AccountCategoryTable, '#AccountCategoryCustomPager');

                        function add() {
                            $('#StoreForm').trigger("reset");
                            $('#StoreModal').html("Add Category");
                            $('#id').val('');
                            $('.errMsgContainer').html('');
                            $('#Store-modal').modal('show');
                        }
                        window.add = add;

                        window.editFunc = function (id) {
                            $.ajax({
                                type: "POST",
                                url: "{{ url('edit_Account_Category_ajax') }}",
                                data: { id: id },
                                dataType: 'json',
                                success: function (res) {
                                    $('#StoreModal').html("Edit Category");
                                    $('#id').val(res.id);
                                    $('#code').val(res.code);
                                    $('#categoryName').val(res.category);
                                    $('#Store-modal').modal('show');
                                }
                            });
                        };

                        window.deleteFunc = function (id) {
                            if (confirm("Delete this category?") == true) {
                                $.ajax({
                                    type: "POST",
                                    url: "{{ url('delete_Account_Category_ajax') }}",
                                    data: { id: id },
                                    dataType: 'json',
                                    success: function () {
                                        AccountCategoryTable.ajax.reload();
                                    }
                                });
                            }
                        };

                        $('#StoreForm').submit(function (e) {
                            e.preventDefault();
                            var id = $('#id').val();
                            var isEdit = !!id;
                            var url = isEdit ? "{{ url('update_Account_Category_ajax') }}" : "{{ url('add_Account_Category_ajax') }}";
                            var data = isEdit
                                ? { up_id: id, up_code: $('#code').val(), up_categoryName: $('#categoryName').val() }
                                : { code: $('#code').val(), categoryName: $('#categoryName').val() };

                            $.ajax({
                                type: 'POST',
                                url: url,
                                data: data,
                                success: function () {
                                    $("#Store-modal").modal('hide');
                                    AccountCategoryTable.ajax.reload();
                                },
                                error: function (xhr) {
                                    $('.errMsgContainer').html('');
                                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                                        $.each(xhr.responseJSON.errors, function (index, value) {
                                            $('.errMsgContainer').append('<span class="text-danger">' + value + '</span><br>');
                                        });
                                    }
                                }
                            });
                        });
                    });
                    </script>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>

</body>
@endsection

</html>
