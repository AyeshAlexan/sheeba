@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <title>Customer Table</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <style>
        .status-active { color: rgb(20, 247, 13); }
        .status-blacklisted { color: rgb(252, 4, 4); }
    </style>
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
                            <h3 class="page-title">Customers</h3>
                            <p class="page-subtitle">Manage customer accounts and cash balances</p>
                        </div>
                    </div>
                </div>

                <div class="container-fluid px-0">
                    @if (session('delete'))
                    <div class="alert alert-danger text-center" role="alert">
                        {{session('delete')}} &#10004;
                    </div>
                    @endif
                    @if (session('added'))
                    <div class="alert alert-success text-center" role="alert">
                        {{session('added')}} &#10004;
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

                    <a id="addCustomerBtn" class="btn btn-primary" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#addCustomerModel" style="display:none;">
                        <i class="fas fa-plus"></i> Add Customers
                    </a>

                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <div class="table-data">
                                    <table class="table table-bordered table-center table-hover datatable" id="customerTable">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>First Name</th>
                                                <th>Contact</th>
                                                <th>VAT Number</th>
                                                <th>Status</th>
                                                <th>Address</th>
                                                <th>Route</th>
                                                <th>Cash Balance</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customers as $key=>$data)
                                            <tr>
                                                <td>{{$data->Code}}</td>
                                                <td>{{$data->First_name}}</td>
                                                <td>{{$data->Contact_1}}</td>
                                                <td>{{$data->Passport}}</td>
                                                <td>
                                                    @if ($data->Status == 1)
                                                    <button type="button" class="btn btn-outline-success status-active">&nbsp;&nbsp;Active&nbsp;&nbsp;</button>
                                                    @else
                                                    <button type="button" class="btn btn-outline-danger status-blacklisted">Blacklisted</button>
                                                    @endif
                                                </td>
                                                <td>{{$data->Address_1}}</td>
                                                <td>{{$data->Address_2}}</td>
                                                <td>{{ number_format((float) $data->Other_identifications, 2, '.', ',') }}</td>
                                                <td>
                                                    <div class="dt-actions">
                                                        <a href="javascript:void(0)"
                                                            class="dt-act-btn dt-act-edit update_customer_form"
                                                            data-bs-toggle="modal" data-bs-target="#updateCustomerModel"
                                                            data-id="{{$data->id}}"
                                                            data-code="{{$data->Code}}"
                                                            data-title="{{$data->Title}}"
                                                            data-gender="{{$data->Gender}}"
                                                            data-first_name="{{$data->First_name}}"
                                                            data-middle_name="{{$data->Middle_name}}"
                                                            data-last_name="{{$data->Last_name}}"
                                                            data-address1="{{$data->Address_1}}"
                                                            data-city1="{{$data->City_1}}"
                                                            data-address2="{{$data->Address_2}}"
                                                            data-city2="{{$data->City_2}}"
                                                            data-contact1="{{$data->Contact_1}}"
                                                            data-contact2="{{$data->Contact_2}}"
                                                            data-email="{{$data->Email}}"
                                                            data-nic="{{$data->NIC}}"
                                                            data-driving_license="{{$data->Driving_license}}"
                                                            data-passport="{{$data->Passport}}"
                                                            data-other_identifications="{{$data->Other_identifications}}"
                                                            data-status="{{$data->Status}}" title="Edit">
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="dt-act-btn dt-act-delete delete_customer" data-id="{{$data->id}}" title="Delete">
                                                            <i class="far fa-trash-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add customer modal -->
                <div class="modal fade" id="addCustomerModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myLargeModalLabel">Add Customers</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="errMsgContainer"></div>
                                <form action="" method="post" id="addCustomer">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-9 d-flex gap-4" style="display: none">
                                            <div class="form-check" style="display: none">
                                                <input class="form-check-input" type="checkbox" id="Wholesale_Customers" name="SaleDecimal" value="1">
                                                <label class="form-check-label" for="Wholesale_Customers">Wholesale Customers</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="Retail_Customers" name="Serialnumber" value="1">
                                                <label class="form-check-label" for="Retail_Customers">Retail Customers</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="code" class="form-label">Customer Code</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="code" value="{{ $maxCustomer }}" id="code" class="form-control" placeholder="Customer Code" required>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col">
                                            <label>First Name <span style="color:#FF0000; font-weight: bold;">*</span>:</label>
                                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" required>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label>Address-1 <span style="color:#FF0000; font-weight: bold;">*</span>:</label>
                                            <textarea class="form-control" name="address1" id="address1" rows="3" placeholder="Address-1:" required></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Customer Route:</label>
                                            <select class="select form-control" name="address2" id="address2" aria-hidden="true" required>
                                                <option value="">Please Select</option>
                                                @foreach($Route as $areaData)
                                                <option value="{{ $areaData->description}}">{{ $areaData->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label>VAT Number <span style="color:#FF0000; font-weight: bold;">*</span>:</label>
                                            <input type="text" name="passport" id="passport" class="form-control" placeholder="VAT Number" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Contact-1 <span style="color:#FF0000; font-weight: bold;">*</span>:</label>
                                            <input type="text" name="contact1" id="contact1" class="form-control" placeholder="Contact 1" required>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label>NIC:</label>
                                            <input type="text" name="nic" id="nic" class="form-control" placeholder="NIC">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Email:</label>
                                            <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label>Customer Cash Balance:</label>
                                            <input type="text" name="other_identifications" id="other_identifications" class="form-control" placeholder="Cash Balance ..">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Mark as Active or Blacklisted <span style="color:#FF0000; font-weight: bold;">*</span>:</label>
                                            <select class="select form-control" name="status" id="status" aria-hidden="true" required>
                                                <option value="">Please Select</option>
                                                <option value="1" selected>Active</option>
                                                <option value="0">Blacklist</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label>Customer Route:</label>
                                            <textarea class="form-control" name="city1" id="city1" rows="1" placeholder="Enter Your Route Details"></textarea>
                                        </div>
                                    </div>

                                    <div class="text-center mt-4">
                                        <button type="button" class="btn btn-primary add_customer me-2">Save</button>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update customer modal -->
                <div class="modal fade" id="updateCustomerModel" tabindex="-1" role="dialog" aria-labelledby="updateCustomerModelLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateCustomerModelLabel">Edit Customers</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="errMsgContainer2"></div>
                                <form action="" method="post" id="updateCustomer">
                                    @csrf
                                    <input type="hidden" id="up_id" name="up_id">
                                    <div class="row">
                                        <div class="col">
                                            <label>Code <span style="color:#FF0000; font-weight: bold;">*</span>:</label>
                                            <input type="text" name="up_code" id="up_code" class="form-control" placeholder="Customer Code" required>
                                        </div>
                                        <div class="col">
                                            <label>First Name <span style="color:#FF0000; font-weight: bold;">*</span>:</label>
                                            <input type="text" name="up_first_name" id="up_first_name" class="form-control" placeholder="First Name" required>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label>Address-1:</label>
                                            <textarea class="form-control" name="up_address1" id="up_address1" rows="3" placeholder="Address-1:" required></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Customer Route:</label>
                                            <select class="select form-control" name="up_address2" id="up_address2" aria-hidden="true" required>
                                                <option value="">Please Select</option>
                                                @foreach($Route as $areaData)
                                                <option value="{{ $areaData->description}}">{{ $areaData->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label>VAT Number <span style="color:#FF0000; font-weight: bold;">*</span>:</label>
                                            <input type="text" name="up_passport" id="up_passport" class="form-control" placeholder="VAT Number" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Contact-1 <span style="color:#FF0000; font-weight: bold;">*</span>:</label>
                                            <input type="text" name="up_contact1" id="up_contact1" class="form-control" placeholder="Contact 1" required>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label>NIC:</label>
                                            <input type="text" name="up_nic" id="up_nic" class="form-control" placeholder="NIC" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Email:</label>
                                            <input type="text" name="up_email" id="up_email" class="form-control" placeholder="Email">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <label>Customer Cash Balance:</label>
                                            <input type="text" name="up_other_identifications" id="up_other_identifications" class="form-control" placeholder="Other Identifications">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Mark as Active or Blacklisted <span style="color:#FF0000; font-weight: bold;">*</span></label>
                                            <select class="select form-control" name="up_status" id="up_status" aria-hidden="true" required>
                                                <option value="1">Active</option>
                                                <option value="0">Blacklist</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label>Customer Route:</label>
                                            <textarea class="form-control" name="up_city1" id="up_city1" rows="1" placeholder="Enter Your Route:"></textarea>
                                        </div>
                                    </div>

                                    <div class="text-center mt-4">
                                        <button type="button" class="btn btn-primary update_customer me-2">Update</button>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {!! Toastr::message() !!}

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    {{-- add new customer script --}}
    <script>
        $(document).ready(function () {
            $(document).on('click', '.add_customer', function (e) {
                e.preventDefault();
                let code = $('#code').val();
                let title = $('#title').val();
                let gender = $('#gender').val();
                let name = $('#name').val();
                let first_name = $('#first_name').val();
                let middle_name = $('#middle_name').val();
                let last_name = $('#last_name').val();
                let address1 = $('#address1').val();
                let city1 = $('#city1').val();
                let address2 = $('#address2').val();
                let city2 = $('#city2').val();
                let contact1 = $('#contact1').val();
                let contact2 = $('#contact2').val();
                let email = $('#email').val();
                let nic = $('#nic').val();
                let driving_license = $('#driving_license').val();
                let passport = $('#passport').val();
                let other_identifications = $('#other_identifications').val();
                let status = $('#status').val();

                $.ajax({
                    url: "{{ route('add_customer_ajax') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        code: code, title: title, gender: gender, name: name,
                        first_name: first_name, middle_name: middle_name, last_name: last_name,
                        address1: address1, city1: city1, address2: address2, city2: city2,
                        contact1: contact1, contact2: contact2, email: email, nic: nic,
                        driving_license: driving_license, passport: passport,
                        other_identifications: other_identifications, status: status
                    },
                    success: function (res) {
                        if (res.status == 'success') {
                            $("#addCustomerModel").modal('hide');
                            $('#addCustomer')[0].reset();
                            Command: toastr["success"]("Customer Added ...!", "Success")
                            toastr.options = {
                                "closeButton": true, "debug": false, "newestOnTop": false,
                                "progressBar": true, "positionClass": "toast-top-right",
                                "preventDuplicates": false, "onclick": null,
                                "showDuration": "300", "hideDuration": "1000",
                                "timeOut": "5000", "extendedTimeOut": "1000",
                                "showEasing": "swing", "hideEasing": "linear",
                                "showMethod": "fadeIn", "hideMethod": "fadeOut"
                            }

                            let data = res.telNo;
                            let cus_details = data[0];
                            let cus_tel = cus_details.Contact_1;
                            let cus_name = cus_details.First_name;

                            $('#searchCustomer').val(cus_tel);
                        }
                    },
                    error: function (err) {
                        $('.errMsgContainer').html('');
                        let error = err.responseJSON;
                        $.each(error.errors, function (index, value) {
                            $('.errMsgContainer').append('<span class="text-danger">' + value + '<span>' + '<br>');
                        });
                    }
                })
            })
        });
    </script>

    <script>
        $(document).ready(function(){
            $(document).on('click','.delete_customer',function(e){
                e.preventDefault();
                let customer_id = $(this).data('id');

                if(confirm('Are you sure to delete customer ?')){
                    $.ajax({
                        url:"{{ route('delete_customer_ajax') }}",
                        method: 'post',
                        data:{"_token": "{{ csrf_token() }}",customer_id:customer_id},
                        success:function(res){
                            if(res.status=='success'){
                                $('.table').load(location.href+' .table');
                                Command: toastr["success"]("Customer deleted...", "Success")
                                toastr.options = {
                                    "closeButton": true, "debug": false, "newestOnTop": false,
                                    "progressBar": true, "positionClass": "toast-top-right",
                                    "preventDuplicates": false, "onclick": null,
                                    "showDuration": "300", "hideDuration": "1000",
                                    "timeOut": "5000", "extendedTimeOut": "1000",
                                    "showEasing": "swing", "hideEasing": "linear",
                                    "showMethod": "fadeIn", "hideMethod": "fadeOut"
                                }
                            }
                        }
                    });
                }
            })

            $(document).on('click','.update_customer_form',function(){
                let id = $(this).data('id');
                let code = $(this).data('code');
                let title = $(this).data('title');
                let gender = $(this).data('gender');
                let first_name = $(this).data('first_name');
                let middle_name = $(this).data('middle_name');
                let last_name = $(this).data('last_name');
                let address1 = $(this).data('address1');
                let city1 = $(this).data('city1');
                let address2 = $(this).data('address2');
                let city2 = $(this).data('city2');
                let contact1 = $(this).data('contact1');
                let contact2 = $(this).data('contact2');
                let email = $(this).data('email');
                let nic = $(this).data('nic');
                let driving_license = $(this).data('driving_license');
                let passport = $(this).data('passport');
                let other_identifications = $(this).data('other_identifications');
                let status = $(this).data('status');

                $('#up_id').val(id);
                $('#up_code').val(code);
                $('#up_title').val(title);
                $('#up_gender').val(gender);
                $('#up_first_name').val(first_name);
                $('#up_middle_name').val(middle_name);
                $('#up_last_name').val(last_name);
                $('#up_address1').val(address1);
                $('#up_city1').val(city1);
                $('#up_address2').val(address2);
                $('#up_city2').val(city2);
                $('#up_contact1').val(contact1);
                $('#up_contact2').val(contact2);
                $('#up_email').val(email);
                $('#up_nic').val(nic);
                $('#up_driving_license').val(driving_license);
                $('#up_passport').val(passport);
                $('#up_other_identifications').val(other_identifications);
                $('#up_status').val(status);
            });

            $(document).on('click','.update_customer',function(e){
                e.preventDefault();
                let up_id = $('#up_id').val();
                let up_code = $('#up_code').val();
                let up_title = $('#up_title').val();
                let up_gender = $('#up_gender').val();
                let up_first_name = $('#up_first_name').val();
                let up_middle_name = $('#up_middle_name').val();
                let up_last_name = $('#up_last_name').val();
                let up_address1 = $('#up_address1').val();
                let up_city1 = $('#up_city1').val();
                let up_address2 = $('#up_address2').val();
                let up_city2 = $('#up_city2').val();
                let up_contact1 = $('#up_contact1').val();
                let up_contact2 = $('#up_contact2').val();
                let up_email = $('#up_email').val();
                let up_nic = $('#up_nic').val();
                let up_driving_license = $('#up_driving_license').val();
                let up_passport = $('#up_passport').val();
                let up_other_identifications = $('#up_other_identifications').val();
                let up_status = $('#up_status').val();

                $.ajax({
                    url:"{{ route('update_customer_ajax') }}",
                    method: 'post',
                    data:{"_token": "{{ csrf_token() }}",
                    up_id:up_id, up_code:up_code, up_title:up_title, up_gender:up_gender,
                    up_first_name:up_first_name, up_middle_name:up_middle_name, up_last_name:up_last_name,
                    up_address1:up_address1, up_city1:up_city1, up_address2:up_address2, up_city2:up_city2,
                    up_contact1:up_contact1, up_contact2:up_contact2, up_email:up_email, up_nic:up_nic,
                    up_driving_license:up_driving_license, up_passport:up_passport,
                    up_other_identifications:up_other_identifications, up_status:up_status},

                    success:function(res){
                        if(res.status=='success'){
                            $("#updateCustomerModel").modal('hide');
                            $('#updateCustomer')[0].reset();
                            $('.table').load(location.href+' .table');
                            Command: toastr["success"]("Customer Datails Updated...", "Success")
                            toastr.options = {
                                "closeButton": true, "debug": false, "newestOnTop": false,
                                "progressBar": true, "positionClass": "toast-top-right",
                                "preventDuplicates": false, "onclick": null,
                                "showDuration": "300", "hideDuration": "1000",
                                "timeOut": "5000", "extendedTimeOut": "1000",
                                "showEasing": "swing", "hideEasing": "linear",
                                "showMethod": "fadeIn", "hideMethod": "fadeOut"
                            }
                        }
                    },error:function(err){
                        $('.errMsgContaine2r').html('');
                        let error = err.responseJSON;
                        $.each(error.errors,function(index, value){
                            $('.errMsgContainer2').append('<span class="text-danger">'+value+'<span>'+'<br>');
                        });
                    }
                })
            })

            $(document).on('click','.pagination a', function(e){
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1]
                customerdetails(page)
            })
            function customerdetails(page){
                $.ajax({
                    url:"/customer_pagination?page="+page,
                    success:function(res){
                        $('.table-data').html(res);
                    }
                })
            }

            $(document).on('keyup','#search',function(e){
                e.preventDefault();
                let search_string =  $('#search').val();
                $.ajax({
                    url:"{{ route('search_customer_ajax') }}",
                    method:'GET',
                    data:{search_string:search_string},
                    success: function(res) {
                        $('.table-data').html(res);
                        if(res.status=='not_found'){
                            $('.table-data').html('<span class="text-danger">'+'Nothing found...'+'</span>');
                        }
                    }
                });
            })
        });
    </script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#customerTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthChange: true,
                pageLength: 10,
                columnDefs: [
                    { orderable: false, targets: -1 }
                ],
                initComplete: function () {
                    dtFixToolbar(this, '#addCustomerBtn');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            const baseNormal    = "{{ $maxCustomer }}";
            const baseRetail    = "{{ $maxRetail }}";
            const baseWholesale = "{{ $maxWholesale }}";

            function updateCustomerCode() {
                if ($('#Retail_Customers').is(':checked')) {
                    $('#code').val(baseRetail);
                } else if ($('#Wholesale_Customers').is(':checked')) {
                    $('#code').val(baseWholesale);
                } else {
                    $('#code').val(baseNormal);
                }
            }

            $(document).on('change', '#Retail_Customers', function () {
                if ($(this).is(':checked')) $('#Wholesale_Customers').prop('checked', false);
                updateCustomerCode();
            });

            $(document).on('change', '#Wholesale_Customers', function () {
                if ($(this).is(':checked')) $('#Retail_Customers').prop('checked', false);
                updateCustomerCode();
            });
        });
    </script>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    {{-- <script src="assets/plugins/select2/js/select2.min.js"></script> --}}
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/datatables.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

</body>
@endsection
</html>
