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
    <title>SalesMan</title>
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header ph-flex">
                    <div class="ph-left">
                        <div class="ph-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div>
                            <h3 class="page-title">SalesMan</h3>
                            <p class="page-subtitle">Manage salesman records</p>
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
                        <a href="javascript:void(0)" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBranchModel">
                            <i class="fas fa-plus"></i> Add SalesMan
                        </a>
                        <div class="toolbar-search" id="salesManSearchSlot"></div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="table-data">
                                    <table class="table table-bordered table-center table-hover" id="branchTable">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Contact 1</th>
                                                <th>Contact 2</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($Branchdetails as $key=>$branch)
                                            <tr>
                                                <th>{{$branch->code }}</th>
                                                <td>{{$branch->name }}</td>
                                                <td>{{$branch->address}}</td>
                                                <td>{{$branch->contact1}}</td>
                                                <td>{{$branch->contact2}}</td>
                                                <td>{{$branch->date}}</td>
                                                <td>
                                                    <div class="dt-actions">
                                                        <a href="javascript:void(0)"
                                                            class="dt-act-btn dt-act-edit update_branch_form"
                                                            data-bs-toggle="modal" data-bs-target="#updateBranchModel"
                                                            data-id="{{$branch->id}}"
                                                            data-code="{{$branch->code}}"
                                                            data-name="{{$branch->name}}"
                                                            data-address="{{$branch->address}}"
                                                            data-contact1="{{$branch->contact1}}"
                                                            data-contact2="{{$branch->contact2}}"
                                                            data-date="{{$branch->date}}" title="Edit">
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="dt-act-btn dt-act-delete delete_branch" data-id="{{$branch->id}}" title="Delete">
                                                            <i class="far fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div id="SalesManCustomPager"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {!! Toastr::message() !!}

    <!-- Add SalesMan modal -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addBranchModelLabel" aria-hidden="true" id="addBranchModel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBranchModelLabel">Add SalesMan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errMsgContainer"></div>
                    <form action="" id="addBranch" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" name="code" placeholder="Enter SalesMan Code" value="{{ old('code') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Salesman Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter SalesMan Name" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="SalesMan Address" value="{{ old('address') }}" required>
                        </div>
                        <label class="form-label">Contact</label>
                        <div class="row mb-3">
                            <div class="col-md-6"><input type="text" class="form-control" id="contact1" name="contact1" placeholder="Contact 1" value="{{ old('contact1') }}" required></div>
                            <div class="col-md-6"><input type="text" class="form-control" id="contact2" name="contact2" placeholder="Contact 2" value="{{ old('contact2') }}"></div>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date Of Joined</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}">
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-success add_branch" type="button">Save</button>
                            <input class="btn btn-outline-warning" type="reset" value="Reset">
                            <button type="button" id="closeModel" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update SalesMan modal -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="updateBranchModelLabel" aria-hidden="true" id="updateBranchModel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateBranchModelLabel">Update SalesMan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="errMsgContainer2"></div>
                    <form action="" id="updateBranch" method="POST">
                        @csrf
                        <input type="hidden" id="up_id" name="up_id">
                        <div class="mb-3">
                            <label for="up_code" class="form-label">SalesMan Code</label>
                            <input type="text" class="form-control" id="up_code" name="up_code" placeholder="Branch Code" required>
                        </div>
                        <div class="mb-3">
                            <label for="up_name" class="form-label">SalesMan Name</label>
                            <input type="text" class="form-control" id="up_name" name="up_name" placeholder="SalesMan Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="up_address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="up_address" name="up_address" placeholder="SalesMan Address" required>
                        </div>
                        <label class="form-label">Contact</label>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="up_contact1" name="up_contact1" placeholder="Contact 1" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="up_contact2" name="up_contact2" placeholder="Contact 2">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="up_date" class="form-label">Date Of Joined</label>
                            <input type="date" class="form-control" id="up_date" name="up_date">
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-success update_branch" type="button">Update</button>
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
            var branchTable = $('#branchTable').DataTable({
                paging: true,
                lengthChange: false,
                pageLength: 15,
                columnDefs: [
                    { orderable: false, targets: -1 }
                ],
                initComplete: function () {
                    var $wrapper = $('#branchTable_wrapper');
                    $wrapper.find('.dataTables_filter input').attr('placeholder', 'Search here');
                    $wrapper.find('.dataTables_filter').appendTo('#salesManSearchSlot');
                }
            });

            $('#branchTable_wrapper').addClass('dt-collapsed');
            DTCustomPager.init(branchTable, '#SalesManCustomPager');
        });
    </script>

    <script>
        $(document).ready(function () {
            //add new branch
            $(document).on('click', '.add_branch', function (e) {
                e.preventDefault();
                let code = $('#code').val();
                let name = $('#name').val();
                let address = $('#address').val();
                let contact1 = $('#contact1').val();
                let contact2 = $('#contact2').val();
                let date = $('#date').val();
                $.ajax({
                    url: "{{ route('add_SalesMan_ajax') }}",
                    method: 'post',
                    data: {
                        code: code,
                        name: name,
                        address: address,
                        contact1: contact1,
                        contact2: contact2,
                        date: date
                    },
                    success: function (res) {
                        if (res.status == 'success') {
                            $("#addBranchModel").modal('hide');
                            $('#addBranch')[0].reset();
                            $('.table').load(location.href + ' .table');
                            Command: toastr["success"]("SalesMan Added ...!", "Success")
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

            //show branch details in update form
            $(document).on('click', '.update_branch_form', function () {
                let id = $(this).data('id');
                let code = $(this).data('code');
                let name = $(this).data('name');
                let address = $(this).data('address');
                let contact1 = $(this).data('contact1');
                let contact2 = $(this).data('contact2');
                let date = $(this).data('date');

                $('#up_id').val(id);
                $('#up_code').val(code);
                $('#up_name').val(name);
                $('#up_address').val(address);
                $('#up_contact1').val(contact1);
                $('#up_contact2').val(contact2);
                $('#up_date').val(date);
            });

            //update branch data
            $(document).on('click', '.update_branch', function (e) {
                e.preventDefault();
                let up_id = $('#up_id').val();
                let up_code = $('#up_code').val();
                let up_name = $('#up_name').val();
                let up_address = $('#up_address').val();
                let up_contact1 = $('#up_contact1').val();
                let up_contact2 = $('#up_contact2').val();
                let up_date = $('#up_date').val();
                $.ajax({
                    url: "{{ route('update_SalesMan_ajax') }}",
                    method: 'post',
                    data: {
                        up_id: up_id,
                        up_code: up_code,
                        up_name: up_name,
                        up_address: up_address,
                        up_contact1: up_contact1,
                        up_contact2: up_contact2,
                        up_date: up_date
                    },
                    success: function (res) {
                        if (res.status == 'success') {
                            $("#updateBranchModel").modal('hide');
                            $('#updateBranch')[0].reset();
                            $('.table').load(location.href + ' .table');
                            Command: toastr["success"]("SalesMan datails updated...",
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
                        $('.errMsgContaine2r').html('');
                        let error = err.responseJSON;
                        $.each(error.errors, function (index, value) {
                            $('.errMsgContainer2').append(
                                '<span class="text-danger">' + value +
                                '<span>' + '<br>');

                        });
                    }
                })
            })

            //delete branch data
            $(document).on('click', '.delete_branch', function (e) {
                e.preventDefault();
                let branch_id = $(this).data('id');

                if (confirm('Are you sure to delete SalesMan ?')) {
                    $.ajax({
                        url: "{{ route('delete_SalesMan_ajax') }}",
                        method: 'post',
                        data: {
                            branch_id: branch_id
                        },
                        success: function (res) {
                            if (res.status == 'success') {
                                $('.table').load(location.href + ' .table');
                                Command: toastr["success"]("Branch deleted...", "Success")
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

            // pagination
            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1]
                branchdetails(page)
            })

            function branchdetails(page) {
                $.ajax({
                    url: "/branch_pagination?page=" + page,
                    success: function (res) {
                        $('.table-data').html(res);
                    }
                })
            }

            // search branch data
            $(document).on('keyup', '#search', function (e) {
                e.preventDefault();
                let search_string = $('#search').val();
                $.ajax({
                    url: "{{ route('search_SalesMan_ajax') }}",
                    method: 'GET',
                    data: {
                        search_string: search_string
                    },
                    success: function (res) {
                        $('.table-data').html(res);
                        if (res.status == 'not_found') {
                            $('.table-data').html('<span class="text-danger">' +
                                'Nothing found...' + '</span>');
                        }
                    }
                });
            })
        });
    </script>


</body>
@endsection
</html>
