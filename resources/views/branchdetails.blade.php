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
    <title>Branches</title>
    <style>
        /* Branch page — same green accent as Company (both live under
           System), blue stays the app's dominant colour everywhere else. */
        :root {
            --br-green:       #16A34A;
            --br-green-light: #EAFBEF;
            --br-navy:        #14213D;
            --br-border:      #E5EAF2;
            --br-surface:     #F6F8FC;
            --br-text-sub:    #667085;
        }

        .br-header {
            display: flex; align-items: flex-start; justify-content: space-between;
            flex-wrap: wrap; gap: 14px; margin-bottom: 22px;
        }
        .br-header-left { display: flex; align-items: center; }
        .br-icon {
            width: 46px; height: 46px; border-radius: 12px;
            background: var(--br-green-light); color: var(--br-green);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; margin-right: 14px;
        }
        .br-header h3 { font-size: 1.4rem; font-weight: 700; color: var(--br-navy); margin: 0; }
        .br-header p { font-size: .83rem; color: var(--br-text-sub); margin-top: 2px; }

        .br-total-row {
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            border-bottom: 1px solid var(--br-border); padding-bottom: 20px; margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .br-total-left { display: flex; align-items: center; gap: 12px; }
        .br-stat-dot {
            width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
            background: var(--br-green-light); color: var(--br-green);
            display: flex; align-items: center; justify-content: center;
        }
        .br-stat-num { font-size: 1.4rem; font-weight: 700; color: var(--br-navy); line-height: 1; }
        .br-stat-lbl { font-size: .75rem; color: var(--br-text-sub); margin-top: 2px; font-weight: 500; }

        .br-search-box { position: relative; }
        .br-search-box svg {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: var(--br-text-sub); pointer-events: none;
        }
        .br-search-box input {
            border: 1.5px solid var(--br-border); border-radius: 9px;
            padding: 9px 14px 9px 36px; font-size: .83rem; color: #475569; background: #fff;
            width: 230px; outline: none;
        }
        .br-search-box input:focus { border-color: var(--br-green); }

        .btn-success {
            background: var(--br-green) !important; border-color: var(--br-green) !important;
            font-weight: 600 !important; transition: all .2s ease;
        }
        .btn-success:hover { background: #128A3E !important; border-color: #128A3E !important; }

        /* Flat table header, matching the rest of the app */
        #branchTable thead tr { background: var(--br-surface) !important; }
        #branchTable thead th {
            background: var(--br-surface) !important;
            border: none !important; border-bottom: 1px solid var(--br-border) !important;
            padding: 13px 16px !important; font-weight: 600 !important;
            text-transform: uppercase !important; font-size: .72rem !important; letter-spacing: .05em !important;
            color: var(--br-navy) !important;
        }
        #branchTable tbody td, #branchTable tbody th {
            padding: 12px 16px !important; vertical-align: middle !important; color: #475569 !important;
            font-weight: 400 !important;
        }

        /* Pagination — Laravel's default paginator markup, green active
           page (this page's accent). #branchPager gives it enough
           specificity to always beat the app-wide blue default. */
        #branchPager .page-link { color: var(--br-green) !important; border-color: var(--br-border) !important; }
        #branchPager .page-link:hover { background: var(--br-green-light) !important; color: var(--br-green) !important; }
        #branchPager .page-item.active .page-link,
        #branchPager .page-item.active .page-link:hover {
            background: var(--br-green) !important; border-color: var(--br-green) !important; color: #fff !important;
        }

        .modal-header { background: var(--br-surface) !important; border-bottom: 1px solid var(--br-border) !important; }
        .form-control:focus { border-color: var(--br-green) !important; box-shadow: 0 0 0 3px rgba(22,163,74,.10) !important; }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                    <div class="br-header">
                        <div class="br-header-left">
                            <div class="br-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="6" y1="3" x2="6" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>
                            </div>
                            <div>
                                <h3>Branches</h3>
                                <p>Manage your branch locations and contact details</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBranchModel">
                            <i class="fas fa-plus"></i> Add Branch
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                                    {{-- error showing section --}}
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

                        <div class="card">
                            <div class="card-body br-total-row">
                                <div class="br-total-left">
                                    <div class="br-stat-dot">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="6" y1="3" x2="6" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>
                                    </div>
                                    <div>
                                        <div class="br-stat-num">{{ $branchDel->total() }}</div>
                                        <div class="br-stat-lbl">Total Branches</div>
                                    </div>
                                </div>
                                <div class="br-search-box">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                                    <input type="text" name="search" id="search" placeholder="Search...">
                                </div>
                            </div>

                                    {{-- ------------- Table branch -------------- --}}
                                        <div class="card-body pt-0">
                                            <div class="table-responsive ">
                                                <div class="table-data ">
                                                    <table class="table table-bordered table-center table-hover"
                                                        id="branchTable">
                                                        <thead>
                                                            <tr>
                                                                <th>BC Code</th>
                                                                <th>Name</th>
                                                                <th>Address</th>
                                                                <th>Contact 1</th>
                                                                <th>Contact 2</th>
                                                                <th>Date</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($branchDel as $key=>$branch)
                                                            <tr>
                                                                <th>{{$branch->bccode }}</th>
                                                                <td>{{$branch->name }}</td>
                                                                <td>{{$branch->address}}</td>
                                                                <td>{{$branch->contact1}}</td>
                                                                <td>{{$branch->contact2}}</td>
                                                                <td>{{$branch->date}}</td>
                                                                <td>
                                                                    <div class="dt-actions">
                                                                    <a href="javascript:void(0)"
                                                                        class="dt-act-btn dt-act-edit update_branch_form" title="Edit"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#updateBranchModel"
                                                                        data-id="{{$branch->id}}"
                                                                        data-bccode="{{$branch->bccode}}"
                                                                        data-name="{{$branch->name}}"
                                                                        data-address="{{$branch->address}}"
                                                                        data-contact1="{{$branch->contact1}}"
                                                                        data-contact2="{{$branch->contact2}}"
                                                                        data-date="{{$branch->date}}">
                                                                        <i class="far fa-edit"></i>
                                                                    </a>
                                                                    <a href="javascript:void(0)"
                                                                        class="dt-act-btn dt-act-delete delete_branch" title="Delete"
                                                                        data-id="{{$branch->id}}">
                                                                        <i class="far fa-trash-alt"></i>
                                                                    </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <div class="ml-4 mb-3 mt-1" id="branchPager">
                                                    {!! $branchDel->links() !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    {!! Toastr::message() !!}


     {{-- ------------- model for add branch -------------- --}}
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addBranchModelLabel"
        aria-hidden="true" id="addBranchModel">
        <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h4 class="modal-title" id="addBranchModelLabel">Add Branch </h4>
                 <button type="button" class="btn-close" data-bs-dismiss="modal"
                     aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-12">
                         <div class="card">
                             <div class="card-body">
                                 <div class="errMsgContainer"></div>
                                 <form action="" id="addBranch" method="POST"
                                     enctype="multipart/form-data">
                                     @csrf
                                     <div class="mb-3">
                                         <label for="exampleFormControlInput1"
                                             class="form-label">
                                             BC Code </label>
                                         <input type="text" class="form-control" id="bccode"
                                             name="bccode" placeholder="Branch Code"
                                             value="{{ old('bccode') }}" required>
                                     </div>
                                     <div class="mb-3">
                                         <label for="exampleFormControlTextarea1"
                                             class="form-label">Name</label>
                                         <input type="text" class="form-control" id="name"
                                             name="name" placeholder="Branch Name"
                                             value="{{ old('name') }}" required>
                                     </div>
                                     <div class="mb-3">
                                         <label for="exampleFormControlTextarea1"
                                             class="form-label">Address</label>
                                         <input type="text" class="form-control" id="address"
                                             name="address" placeholder="Branch Address"
                                             value="{{ old('address') }}" required>
                                     </div>
                                     <label for="exampleFormControlInput1"
                                         class="form-label">
                                         Contact
                                     </label>
                                     <div class="row mb-3">
                                         <div class="col-md-6"><input type="text"
                                                 class="form-control" id="contact1"
                                                 name="contact1" placeholder="Contact 1"
                                                 value="{{ old('contact1') }}" required>
                                         </div>
                                         <div class="col-md-6"><input type="text"
                                                 class="form-control" id="contact2"
                                                 name="contact2" placeholder="Contact 2"
                                                 value="{{ old('contact2') }}"></div>
                                     </div>
                                     <div class="mb-3">
                                         <label for="exampleFormControlInput1"
                                             class="form-label">
                                             Date Of Joined
                                         </label>
                                         <input type="date" class="form-control" id="date"
                                             name="date" placeholder=""
                                             value="{{ old('date') }}">
                                     </div>
                             </div>
                         </div>
                     </div>
                     <div class="text-center mt-4">
                         <button class="btn btn-success add_branch"
                             type="button">Save</button>
                         <input class="btn btn-outline-warning" type="reset" value="Reset">
                         <button type="button" id="closeModel"
                             class="btn btn-outline-secondary" data-bs-dismiss="modal"
                             aria-label="Close">Close</button>
                     </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
    </div>

 {{-- ------------- model for update branch -------------- --}}
 <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="updateBranchModelLabel"
     aria-hidden="true" id="updateBranchModel">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h4 class="modal-title" id="updateBranchModelLabel">Update Branch </h4>
                 <button type="button" class="btn-close" data-bs-dismiss="modal"
                     aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-12">
                         <div class="card">
                             <div class="card-body">
                                 <div class="errMsgContainer2"></div>
                                 <form action="" id="updateBranch" method="POST">
                                     @csrf
                                     <input type="hidden" id="up_id" name="up_id">
                                     <div class="mb-3">
                                         <label for="exampleFormControlInput1"
                                             class="form-label">
                                             BC Code </label>
                                         <input type="text" class="form-control"
                                             id="up_bccode" name="up_bccode"
                                             placeholder="Branch Code" required>
                                     </div>
                                     <div class="mb-3">
                                         <label for="exampleFormControlTextarea1"
                                             class="form-label">Name</label>
                                         <input type="text" class="form-control" id="up_name"
                                             name="up_name" placeholder="Branch Name"
                                             required>
                                     </div>
                                     <div class="mb-3">
                                         <label for="exampleFormControlTextarea1"
                                             class="form-label">Address</label>
                                         <input type="text" class="form-control"
                                             id="up_address" name="up_address"
                                             placeholder="Branch Address" required>
                                     </div>
                                     <label for="exampleFormControlInput1"
                                         class="form-label">Contact</label>
                                     <div class="row mb-3">
                                         <div class="col-md-6">
                                             <input type="text" class="form-control"
                                                 id="up_contact1" name="up_contact1"
                                                 placeholder="Contact 1" required>
                                         </div>
                                         <div class="col-md-6">
                                             <input type="text" class="form-control"
                                                 id="up_contact2" name="up_contact2"
                                                 placeholder="Contact 2">
                                         </div>
                                     </div>
                                     <div class="mb-3">
                                         <label for="exampleFormControlInput1"
                                             class="form-label">
                                             Date Of Joined
                                         </label>
                                         <input type="date" class="form-control" id="up_date"
                                             name="up_date">
                                     </div>
                             </div>
                         </div>
                     </div>
                     <div class="text-center mt-4">
                         <button class="btn btn-success update_branch"
                             type="button">Update</button>
                         <input class="btn btn-outline-warning" type="reset" value="Reset">
                         <button type="button" id="closeModel"
                             class="btn btn-outline-secondary" data-bs-dismiss="modal"
                             aria-label="Close">Close</button>
                     </div>
                     </form>
                 </div>
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

    {{-- CSRF token script --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            //add new branch
            $(document).on('click', '.add_branch', function (e) {
                e.preventDefault();
                let bccode = $('#bccode').val();
                let name = $('#name').val();
                let address = $('#address').val();
                let contact1 = $('#contact1').val();
                let contact2 = $('#contact2').val();
                let date = $('#date').val();
                $.ajax({
                    url: "{{ route('add_branch_ajax') }}",
                    method: 'post',
                    data: {
                        bccode: bccode,
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
                            Command: toastr["success"]("Branch Added ...!", "Success")
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
                let bccode = $(this).data('bccode');
                let name = $(this).data('name');
                let address = $(this).data('address');
                let contact1 = $(this).data('contact1');
                let contact2 = $(this).data('contact2');
                let date = $(this).data('date');

                $('#up_id').val(id);
                $('#up_bccode').val(bccode);
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
                let up_bccode = $('#up_bccode').val();
                let up_name = $('#up_name').val();
                let up_address = $('#up_address').val();
                let up_contact1 = $('#up_contact1').val();
                let up_contact2 = $('#up_contact2').val();
                let up_date = $('#up_date').val();
                $.ajax({
                    url: "{{ route('update_branch_ajax') }}",
                    method: 'post',
                    data: {
                        up_id: up_id,
                        up_bccode: up_bccode,
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
                            Command: toastr["success"]("Branch datails updated...",
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

                if (confirm('Are you sure to delete branch ?')) {
                    $.ajax({
                        url: "{{ route('delete_branch_ajax') }}",
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
            $(document).on('keyup', function (e) {
                e.preventDefault();
                let search_string = $('#search').val();
                // console.log(search_string);
                $.ajax({
                    url: "{{ route('search_branch_ajax') }}",
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
