@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <title>Route</title>
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header ph-flex">
                    <div class="ph-left">
                        <div class="ph-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 20l-5.447-2.724A1 1 0 0 1 3 16.382V5.618a1 1 0 0 1 1.447-.894L9 7m0 13 6-3m-6 3V7m6 10 4.553 2.276A1 1 0 0 0 21 18.382V7.618a1 1 0 0 0-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        </div>
                        <div>
                            <h3 class="page-title">Route</h3>
                            <p class="page-subtitle">Manage delivery routes</p>
                        </div>
                    </div>
                </div>

                <div class="container-fluid px-0">
                    @if (session('delete'))
                    <div class="alert alert-danger text-center" role="alert">
                        {{ session('delete') }} &#10004;
                    </div>
                    @endif
                    @if (session('added'))
                    <div class="alert alert-success text-center" role="alert">
                        {{ session('added') }} &#10004;
                    </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="static-toolbar">
                        <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartmentModel">
                            <i class="fas fa-plus"></i> Add Route
                        </a>
                        <div class="toolbar-search">
                            Search:
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search here">
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="table-data">
                                    <table class="table table-bordered table-center table-hover" id="DepartmentTable">
                                        <thead>
                                            <tr>
                                                <th>Route Code</th>
                                                <th>Route Name</th>
                                                <th>Branch</th>
                                                <th>Branch Code</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($DepartmentData as $key=>$Department)
                                            <tr>
                                                <th>{{$Department->code }}</th>
                                                <td>{{$Department->description  }}</td>
                                                <td>{{$Department->Branch}}</td>
                                                <td>{{$Department->BranchCode}}</td>
                                                <td>
                                                    <div class="dt-actions">
                                                        <a href="javascript:void(0)"
                                                            class="dt-act-btn dt-act-edit update_Department_form"
                                                            data-bs-toggle="modal" data-bs-target="#updateDepartmentModel"
                                                            data-id="{{$Department->id}}"
                                                            data-code="{{$Department->code}}"
                                                            data-description="{{$Department->description}}" title="Edit">
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="dt-act-btn dt-act-delete delete_Department" data-id="{{$Department->id}}" title="Delete">
                                                            <i class="far fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div id="RouteCustomPager"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @include('layouts.footer')
        </div>
    </div>
    {!! Toastr::message() !!}

    <!-- Add Route modal -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModelLabel" aria-hidden="true" id="addDepartmentModel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDepartmentModelLabel">Add Route</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errMsgContainer"></div>
                    <form action="" id="addDepartment" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">Route Code</label>
                            <input type="text" class="form-control" id="code" name="code" placeholder="Enter a Route Code" value="" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Route Name</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="Enter a Route Name" value="" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" class="form-control" id="Branch" name="Branch" value="{{ Auth::user()->Branch}}" readonly>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" class="form-control" id="BranchCode" name="BranchCode" value="{{ Auth::user()->BC}}" readonly>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-success add_Department" type="button">Save</button>
                            <input class="btn btn-outline-warning" type="reset" value="Reset">
                            <button type="button" id="closeModel" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Route modal -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="updateDepartmentModelLabel" aria-hidden="true" id="updateDepartmentModel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateDepartmentModelLabel">Update Route</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errMsgContainer"></div>
                    <form action="" id="updateDepartment" method="POST">
                        @csrf
                        <input type="hidden" id="up_id" name="up_id">
                        <div class="mb-3">
                            <label for="up_code" class="form-label">Route Code</label>
                            <input type="text" class="form-control" id="up_code" name="up_code" placeholder="Enter a Route Code" required>
                        </div>
                        <div class="mb-3">
                            <label for="up_description" class="form-label">Route Name</label>
                            <input type="text" class="form-control" id="up_description" name="up_description" placeholder="Enter a Route Name" required>
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-success update_Department" type="button">Update</button>
                            <input class="btn btn-outline-warning" type="reset" value="Reset">
                            <button type="button" id="closeModel" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/datatables.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    {{-- This page loads a second, later jQuery build (cdn.bootcss.com) after the
         DataTables plugin scripts above attached themselves to the first one,
         which silently orphans $.fn.DataTable on the jQuery instance actually
         in scope by the time this block runs. Re-including the plugin here
         re-attaches it to whichever jQuery is current. --}}
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/datatables.min.js"></script>
    <script src="assets/js/dt-custom-pager.js"></script>
    <script>
        $(document).ready(function () {
            var DepartmentTable = $('#DepartmentTable').DataTable({
                paging: true,
                lengthChange: false,
                pageLength: 15,
                columnDefs: [
                    { orderable: false, targets: -1 }
                ],
            });

            $('#DepartmentTable_wrapper').addClass('dt-collapsed');
            DTCustomPager.init(DepartmentTable, '#RouteCustomPager');
        });
    </script>

    <script>
        $(document).ready(function () {

            $(document).on('click', '.add_Department', function (e) {
                e.preventDefault();
                let code = $('#code').val();
                let description = $('#description').val();
                let Branch = $('#Branch').val();
                let BranchCode = $('#BranchCode').val();
                $.ajax({
                    url: "{{ route('add_Route_ajax') }}",
                    method: 'post',
                    data: {
                        code: code,
                        description: description,
                        Branch: Branch,
                        BranchCode: BranchCode,
                    },
                    success: function (res) {
                        if (res.status == 'success') {
                            $("#addDepartmentModel").modal('hide');
                            $('#addDepartment')[0].reset();
                            $('.table').load(location.href + ' .table');
                            Command: toastr["success"]("Route Added ...!", "Success")
                            toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": true,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                        }
                    },
                    error: function (err) {
                        $('.errMsgContainer').html('');
                        let error = err.responseJSON;
                        $.each(error.errors, function (index, value) {
                            $('.errMsgContainer').append(
                                '<span class="text-danger">' + value +
                                '<span>' + '<br>');
                        });
                    }
                })
            })

            $(document).on('click', '.update_Department_form', function () {
                let id = $(this).data('id');
                let code = $(this).data('code');
                let description  = $(this).data('description');
                let Branch  = $(this).data('Branch');
                let BranchCode  = $(this).data('BranchCode');

                $('#up_id').val(id);
                $('#up_code').val(code);
                $('#up_description').val(description);
                $('#up_Branch').val(Branch);
                $('#up_BranchCode').val(BranchCode);
            });

            $(document).on('click', '.update_Department', function (e) {
                e.preventDefault();
                let up_id = $('#up_id').val();
                let up_code = $('#up_code').val();
                let up_description = $('#up_description').val();
                let up_Branch = $('#up_Branch').val();
                let up_BranchCode = $('#up_BranchCode').val();
                $.ajax({
                    url: "{{ route('update_Route_ajax') }}",
                    method: 'post',
                    data: {
                        up_id: up_id,
                        up_code: up_code,
                        up_description: up_description,
                        up_Branch: up_Branch,
                        up_BranchCode: up_BranchCode,
                    },
                    success: function (res) {
                        if (res.status == 'success') {
                            $("#updateDepartmentModel").modal('hide');
                            $('#updateDepartment')[0].reset();
                            $('.table').load(location.href + ' .table');
                            Command: toastr["success"]("Route datails updated...",
                                "Success")
                            toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": true,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                        }
                    },
                    error: function (err) {
                        $('.errMsgContainer').html('');
                        let error = err.responseJSON;
                        $.each(error.errors, function (index, value) {
                            $('.errMsgContainer').append(
                                '<span class="text-danger">' + value +
                                '<span>' + '<br>');

                        });
                    }
                })
            })

            $(document).on('click', '.delete_Department', function (e) {
                e.preventDefault();
                let Department_id = $(this).data('id');

                if (confirm('Are you sure to delete Route ?')) {
                    $.ajax({
                        url: "{{ route('delete_Route_ajax') }}",
                        method: 'post',
                        data: {
                            Department_id: Department_id
                        },
                        success: function (res) {
                            if (res.status == 'success') {
                                $('.table').load(location.href + ' .table');
                                Command: toastr["success"]("Route deleted...", "Success")
                                toastr.options = {
                                    "closeButton": true,
                                    "debug": false,
                                    "newestOnTop": false,
                                    "progressBar": true,
                                    "positionClass": "toast-top-right",
                                    "preventDuplicates": false,
                                    "onclick": null,
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "5000",
                                    "extendedTimeOut": "1000",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                }
                            }
                        }
                    });
                }
            })

        });
    </script>

</body>
@endsection
</html>
