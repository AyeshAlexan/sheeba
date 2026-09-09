@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>All Jobs</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card shadow">
                            <div class="col-md-9">
                                <h4 class="card-title m-3">All Jobs</h4>
                            </div>
                            <hr style="height: 5px; color: blue;">

                            <div class="card-body">
                                {{-- alerts section --}}
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
                                @if (Session::has('done'))
                                <div class="alert alert-success text-center">
                                    <p>{{ Session::get('done') }}</p>
                                </div>
                                @endif

                                <div class="row ">

                                    <div class="card card-table">
                                        <div class="card-body">
                                            {{-- --------------search and add job button-------- --}}
                                            <div class="row">
                                                {{-- ........search area.......... --}}
                                                <div class="col-md-6">
                                                    <div class="top-nav-search">
                                                        <form>
                                                            <input type="text" name="search" id="search" class="form-control" placeholder="Search here">
                                                            <button class="btn" type="button"><i class="fas fa-search"></i></button>
                                                        </form>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <div class="table-data">
                                                    <table class="table table-bordered table-center table-hover datatable">
                                                        <thead >
                                                            <tr class="table-secondary">
                                                                <th>Job No</th>
                                                                <th>Name</th>
                                                                <th>Tel</th>
                                                                <th>Brand</th>
                                                                <th>Model</th>
                                                                <th>IMEI Number</th>
                                                                <th>Amount</th>
                                                                <th>Advance</th>
                                                                <th>Balance</th>
                                                                <th>Password</th>
                                                                <th>Problem Reported</th>
                                                                <th>Product Configuration</th>

                                                                <th>Items</th>
                                                                <th>Problems</th>
                                                                <th>Date</th>
                                                                <th>Technician</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($alljobs as $key=>$data)
                                                            <tr>
                                                                <td>{{$data->Job_no}}</td>
                                                                <td>{{$data->Customer_Name}}</td>
                                                                <td>{{$data->Customer_Phone}}</td>
                                                                <td>{{$data->Brand}}</td>
                                                                <td>{{$data->Device_Model}}</td>
                                                                <td>{{$data->IMEI_Number}}</td>
                                                                <td>{{$data->Amount}}</td>
                                                                <td>{{$data->Advance}}</td>
                                                                <td>{{$data->Balance}}</td>
                                                                <td>{{$data->Password}}</td>
                                                                <td>{{$data->Problem_Reported}}</td>
                                                                <td>{{$data->Product_Configuration}}</td>
                                                                <td>{{$data->Item}}</td>
                                                                <td>{{$data->Problem}}</td>
                                                                <td>{{$data->Date}}</td>
                                                                <td>{{$data->Technician}}</td>

                                                                <td>
                                                                    @if ($data->Status == 'Pending')
                                                                    <span style="color: rgb(245, 208, 0)">Pending</span>
                                                                    @elseif ($data->Status == 'Complete')
                                                                    <span style="color: rgb(12, 252, 4)">Complete</span>
                                                                    @else
                                                                    <span style="color: rgb(252, 25, 25)">Job Failed</span>
                                                                    @endif
                                                                </td>

                                                                <td>
                                                                    {{-- ..............Edit button................................. --}}
                                                                    {{-- <button type="button" class="btn btn-sm btn-success bg-success-light text-success me-2" data-bs-toggle="modal"
                                                                        data-bs-target="#edit-customer-{{$data->Code}}">
                                                                        <i class="far fa-edit me-1"></i> Edit
                                                                    </button> --}}
                                                                    <a href=""
                                                                        class="btn btn-sm btn-success update_job_form bg-success-light text-success me-2"
                                                                        data-bs-toggle="modal" data-bs-target="#updateJobModel"
                                                                        data-id="{{$data->id}}"
                                                                        data-job_no="{{$data->Job_no}}"
                                                                        data-name="{{$data->Customer_Name}}"
                                                                        data-contact1="{{$data->Customer_Phone}}"
                                                                        data-brand="{{$data->Brand}}"
                                                                        data-model="{{$data->Device_Model}}"
                                                                        data-imei_no="{{$data->IMEI_Number}}"
                                                                        data-amount="{{$data->Amount}}"
                                                                        data-advance="{{$data->Advance}}"
                                                                        data-balance="{{$data->Balance}}"
                                                                        data-configuration="{{$data->Product_Configuration}}"
                                                                        data-password="{{$data->Password}}"
                                                                        data-problem_reported="{{$data->Problem_Reported}}"
                                                                        data-condition="{{$data->Product_Condition}}"
                                                                        data-technician="{{$data->Technician}}"
                                                                        data-date="{{$data->Date}}"
                                                                        data-items="{{$data->Item}}"
                                                                        data-problems="{{$data->Problem}}"
                                                                        data-status="{{$data->Status}}"
                                                                         >
                                                                        <i class="far fa-edit me-1"></i> Edit
                                                                    </a>

                                                                    <button type="button" class="btn btn-sm delete_job btn-danger bg-danger-light text-danger me-2"
                                                                    data-id="{{$data->id}}">
                                                                        <i class="far fa-trash-alt me-1"></i> Delete
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                {!! $alljobs->links() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                    {{-- ............Update job model................................. --}}
                    <div class="modal fade" id="updateJobModel" tabindex="-1" role="dialog"
                        aria-labelledby="updateJobModelLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                        <div class="modal-header">
                        <h4 class="modal-title m-2" id="updateJobModelLabel"> Edit Job </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="errMsgContainer2"></div>

                                        <div class="row ">

                                            <form action="" method="post" name="updateJob" id="updateJob">
                                                @csrf
                                                {{-- form top row --}}
                                                <div class="row">
                                                    <input type="hidden" name="up_id" id="up_id">
                                                    <div class="col-md-3">
                                                        <label for="customer_phone"> Tel :
                                                            <div class="input-group">
                                                                <input type="text" class="form-control  form-control-lg"
                                                                    name="up_customer_phone" id="up_customer_phone"
                                                                    placeholder="Customer Tel" required />

                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="col-md-3 ">
                                                        <div class="showCustomer">
                                                            <label for="customer_name"> Customer Name :
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control  form-control-lg"
                                                                        name="up_customer_name" id="up_customer_name"
                                                                        placeholder="Customer Name" required />

                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col">
                                                        <label for="job_no">Job No :
                                                            <input class="form-control"  type="text" placeholder="Job Number:"
                                                            id="up_job_no" name="up_job_no" readonly required>
                                                        </label>
                                                    </div>

                                                    <div class="col">
                                                        <label for="date">Date :
                                                            <input class="form-control datetimepicker" type="date" id="up_receipt_date"
                                                                name="up_receipt_date" required>
                                                        </label>
                                                    </div>
                                                </div>
                                                <hr>

                                                {{-- Brand Model IMEI Section --}}
                                                <div class="row">
                                                    {{-- second row --}}
                                                    <div class="row mt-2 mb-3">
                                                        {{-- brand --}}
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="brand">Brand:</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="up_brand" id="up_brand" class="form-control"
                                                                        placeholder="Enter Brand Name" required />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{--Phone Model--}}
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="device_model">Device Model :</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="up_device_model" id="up_device_model" class="form-control"
                                                                        placeholder="Enter Device Model" required />
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Device Modal --}}
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label for="imei_number">IMEI Number:</label>
                                                                <div class="input-group">
                                                                    <input type="text" name="up_imei_number" id="up_imei_number" class="form-control"
                                                                        placeholder="Enter IMEI Number" required />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>

                                                    {{-- checkbox first --}}
                                                    <div class="row mt-2 mb-3">
                                                        <div class="row ml-5">


                                                            <div class="col" >
                                                                <div class="form-check form-check-inline ">
                                                                    <input class="form-check-input" type="checkbox" id="battery" value="Battery" name="items[]">
                                                                    <label class="form-check-label"  for="inlineCheckbox1">Battery</label>
                                                                </div>

                                                            </div>
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="charger" value="Charger" name="items[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">Charger</label>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="memory_card" value="Memory Card" name="items[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">Memory Card</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="sim" value="Sim" name="items[]">
                                                                    <label class="form-check-label" for="inlineCheckbox1">Sim</label>
                                                                </div>

                                                            </div>
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="back_cover" value="Back Cover" name="items[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">Back Cover</label>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                {{-- <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="other" value="Other" name="items[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">Other</label>
                                                                </div> --}}
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input type="text" name="items[]" id="item_other" class="form-control"
                                                                            placeholder="Other" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>

                                                    {{-- payment section --}}
                                                    <div class="row mt-2 mb-3">
                                                        <div class="col">
                                                            <label for="amount">AMOUNT :
                                                                <input class="form-control form-control-lg"
                                                                    style="font-weight:bold;" type="text"
                                                                    placeholder="AMOUNT:" id="up_amount" name="up_amount"
                                                                    required>
                                                            </label>
                                                        </div>
                                                        <div class="col">
                                                            <label for="amount">ADVANCE :
                                                                <input style="font-weight:bold;"
                                                                    class="form-control form-control-lg" type="text"
                                                                    placeholder="ADVANCE:" id="up_advance"
                                                                    name="up_advance" >
                                                            </label>
                                                        </div>
                                                        <div class="col">
                                                            <label for="amount">BALANCE :
                                                                <input style="font-weight:bold;"
                                                                    class="form-control form-control-lg" type="text"
                                                                    placeholder="BALANCE:" id="up_balance"
                                                                    name="up_balance" required>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <hr>

                                                    {{-- checkbox second section --}}
                                                    <div class="row mt-2 mb-3">
                                                        <div class="row ml-5">
                                                            <div class="col" >
                                                                <div class="form-check form-check-inline ">
                                                                    <input class="form-check-input" type="checkbox" id="mic" value="Mic" name="problems[]">
                                                                    <label class="form-check-label"  for="inlineCheckbox1">Mic</label>
                                                                </div>

                                                            </div>
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="ringer" value="Ringer" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">Ringer</label>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="ear_piece" value="Ear piece" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">Ear piece</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="no_power" value="No power" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox1">No power</label>
                                                                </div>

                                                            </div>
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="software" value="Software" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">Software</label>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="SIM_connector" value="SIM connector" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">SIM connector</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="charging_pin" value="Charging pin" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox1">Charging pin</label>
                                                                </div>

                                                            </div>
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="lock" value="Lock" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">Lock</label>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="water_damage" value="Water damage" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">Water damage</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="display" value="Display" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox1">Display</label>
                                                                </div>

                                                            </div>
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="country_lock" value="Country Lock" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">Country Lock</label>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="ribbon" value="Ribbon" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">Ribbon</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="touch" value="Touch" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox1">Touch</label>
                                                                </div>

                                                            </div>
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="B_pin" value="B/ pin" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">B/ pin</label>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="short" value="Short" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">Short</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="ploblem_battery" value="Battery" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">Battery</label>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="checkbox" id="ic" value="IC" name="problems[]">
                                                                    <label class="form-check-label" for="inlineCheckbox2">IC</label>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input type="text" name="problems[]" id="problem_other" class="form-control"
                                                                            placeholder="Other" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>

                                                    {{-- row for password details --}}
                                                    <div class="row mt-2 mb-3">
                                                        <div class="col 4">
                                                            <label for="Product Configuration" class="form-label">
                                                                Product Configuration :
                                                            </label>
                                                            <input type="text" class="form-control" id="up_product_configuration"
                                                            name="up_product_configuration" placeholder="Product Configuration"/>
                                                        </div>
                                                        <div class="col 4">
                                                            <label for="password" class="form-label"> Password :</label>
                                                            <input type="text" class="form-control" id="up_password" name="up_password"
                                                                placeholder="Password">
                                                        </div>
                                                        <div class="col 4">
                                                            <label for="status" class="form-label"> Status :</label>
                                                            <select class="select form-control"
                                                                id="up_status" name="up_status" aria-hidden="true">
                                                                <option value="">Select an item</option>
                                                                <option value="Complete">Complete</option>
                                                                <option value="Pending">Pending</option>
                                                                <option value="Reject">Reject</option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    {{-- row for form footer  --}}
                                                    <div class="row mt-2 mb-2">

                                                        <div class="col">
                                                            <label for="Problem Reported" class="form-label">
                                                                Problem Reported By The customer :
                                                            </label>
                                                            <input type="text" class="form-control" id="up_problem_reported"
                                                            name="up_problem_reported" placeholder="Problem Reported By The customer"/>
                                                        </div>
                                                        <div class="col">
                                                            <label for="product_condition" class="form-label">
                                                                Technician :
                                                            </label>
                                                            <input type="text" class="form-control" id="up_technician" name="up_technician"
                                                                placeholder="Technician"/>
                                                        </div>
                                                    </div>

                                                    {{-- row for buttons --}}
                                                    <div class="row">
                                                        <div class="text-center mt-4">
                                                            <button type="button" class="btn btn-success update_job bg-success-light text-success me-2">Update</button>
                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>

                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                        </div>
                        </div>
                    </div>






                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>

    {!! Toastr::message() !!}



    {{-- form default date set for today --}}
    {{-- <script>
        document.getElementById('receipt_date').valueAsDate = new Date();
    </script> --}}

         {{-- value auto calculation --}}
         <script>
            $(document).ready(function () {
                // Listen for changes in the amount and advance fields
                $('#up_amount, #up_advance').on('input', function () {
                    // Get values from the Weight and QTY fields
                    let amount = parseFloat($('#up_amount').val()) || 0;
                    let advance = parseInt($('#up_advance').val()) || 0;

                    // Retrieve the karatage rate from the selected option
                    // let karatageRate = parseFloat($('#karatage').find(':selected').data('pawningrate')) ||
                    // 0;
                    // Calculate the value
                    let balance = (amount - advance).toFixed(2);

                    // Update the Value field
                    $('#up_balance').val(balance);
                });
            });
        </script>

{{-- Delete Jobs --}}
<script>
    $(document).ready(function(){
        //delete jobs data
        $(document).on('click','.delete_job',function(e){
                    e.preventDefault();
                    let job_id = $(this).data('id');

                    if(confirm('Are you sure to delete customer ?')){
                        $.ajax({
                            url:"{{ route('delete_jobs_ajax') }}",
                            method: 'post',
                            data:{"_token": "{{ csrf_token() }}",job_id:job_id},
                            success:function(res){
                                if(res.status=='success'){
                                        $('.table').load(location.href+' .table');
                                        Command: toastr["success"]("Customer deleted...", "Success")
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

         //show job details in update form
            $(document).on('click','.update_job_form',function(){
                $('#updateJob')[0].reset();

                let id = $(this).data('id');
                let job_no = $(this).data('job_no');
                let name = $(this).data('name');
                let contact1 = $(this).data('contact1');
                let brand = $(this).data('brand');
                let model = $(this).data('model');
                let imei_no = $(this).data('imei_no');
                let amount = $(this).data('amount');
                let advance = $(this).data('advance');
                let balance = $(this).data('balance');
                let configuration = $(this).data('configuration');
                let password = $(this).data('password');
                let problem_reported  = $(this).data('problem_reported');
                let condition = $(this).data('condition');
                let date = $(this).data('date');
                let items = $(this).data('items');
                let problems = $(this).data('problems');
                let technician = $(this).data('technician');
                let status = $(this).data('status');

                // catch the items in to variables
                var items_array = items.split(", ");
                var item1 = items_array[0];
                var item2 = items_array[1];
                var item3 = items_array[2];
                var item4 = items_array[3];
                var item5 = items_array[4];
                var item6 = items_array[5];

                    // switch cases for checked items
                    if(item1 != null){
                        switch (item1){
                        case 'Battery':
                            $("#battery").prop("checked", true);
                            break;
                        case 'Charger':
                            $("#charger").prop("checked", true);
                            break;
                        case 'Memory Card':
                            $("#memory_card").prop("checked", true);
                            break;
                        case 'Sim':
                            $("#sim").prop("checked", true);
                            break;
                        case 'Back Cover':
                            $("#back_cover").prop("checked", true);
                            break;
                        default:
                            $('#item_other').val(item1);
                        }
                    }

                    if(item2 != null){
                        switch (item2){
                        case 'Battery':
                            $("#battery").prop("checked", true);
                            break;
                        case 'Charger':
                            $("#charger").prop("checked", true);
                            break;
                        case 'Memory Card':
                            $("#memory_card").prop("checked", true);
                            break;
                        case 'Sim':
                            $("#sim").prop("checked", true);
                            break;
                        case 'Back Cover':
                            $("#back_cover").prop("checked", true);
                            break;
                        default:
                            $('#item_other').val(item2);
                        }
                    }

                    if(item3 != null){
                        switch (item3){
                        case 'Battery':
                            $("#battery").prop("checked", true);
                            break;
                        case 'Charger':
                            $("#charger").prop("checked", true);
                            break;
                        case 'Memory Card':
                            $("#memory_card").prop("checked", true);
                            break;
                        case 'Sim':
                            $("#sim").prop("checked", true);
                            break;
                        case 'Back Cover':
                            $("#back_cover").prop("checked", true);
                            break;
                        default:
                            $('#item_other').val(item3);
                        }
                    }

                    if(item4 != null){
                        switch (item4){
                        case 'Battery':
                            $("#battery").prop("checked", true);
                            break;
                        case 'Charger':
                            $("#charger").prop("checked", true);
                            break;
                        case 'Memory Card':
                            $("#memory_card").prop("checked", true);
                            break;
                        case 'Sim':
                            $("#sim").prop("checked", true);
                            break;
                        case 'Back Cover':
                            $("#back_cover").prop("checked", true);
                            break;
                        default:
                            $('#item_other').val(item4);
                        }
                    }

                    if(item5 != null){
                        switch (item5){
                        case 'Battery':
                            $("#battery").prop("checked", true);
                            break;
                        case 'Charger':
                            $("#charger").prop("checked", true);
                            break;
                        case 'Memory Card':
                            $("#memory_card").prop("checked", true);
                            break;
                        case 'Sim':
                            $("#sim").prop("checked", true);
                            break;
                        case 'Back Cover':
                            $("#back_cover").prop("checked", true);
                            break;
                        default:
                            $('#item_other').val(item5);
                        }
                    }

                    if(item6 != null){
                        switch (item6){
                        case 'Battery':
                            $("#battery").prop("checked", true);
                            break;
                        case 'Charger':
                            $("#charger").prop("checked", true);
                            break;
                        case 'Memory Card':
                            $("#memory_card").prop("checked", true);
                            break;
                        case 'Sim':
                            $("#sim").prop("checked", true);
                            break;
                        case 'Back Cover':
                            $("#back_cover").prop("checked", true);
                            break;
                        default:
                            $('#item_other').val(item6);
                        }
                    }



                    // catch the problems in to variables
                    var problems_array = problems.split(", ");
                    var problem1 = problems_array[0];
                    var problem2 = problems_array[1];
                    var problem3 = problems_array[2];
                    var problem4 = problems_array[3];
                    var problem5 = problems_array[4];
                    var problem6 = problems_array[5];
                    var problem7 = problems_array[6];
                    var problem8 = problems_array[7];
                    var problem9 = problems_array[8];
                    var problem10 = problems_array[9];
                    var problem11 = problems_array[10];
                    var problem12 = problems_array[11];
                    var problem13 = problems_array[12];
                    var problem14 = problems_array[13];
                    var problem15 = problems_array[14];
                    var problem16 = problems_array[15];
                    var problem17 = problems_array[16];
                    var problem18 = problems_array[17];


                    // switch cases for checked problems
                    if(problem1 != null){
                        switch (problem1){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem1);
                        }
                    }

                    if(problem2 != null){
                        switch (problem2){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem2);
                        }
                    }

                    if(problem3 != null){
                        switch (problem3){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem3);
                        }
                    }

                    if(problem4 != null){
                        switch (problem4){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem4);
                        }
                    }

                    if(problem5 != null){
                        switch (problem5){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem5);
                        }
                    }

                    if(problem6 != null){
                        switch (problem6){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem6);
                        }
                    }

                    if(problem7 != null){
                        switch (problem7){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem7);
                        }
                    }

                    if(problem8 != null){
                        switch (problem8){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem8);
                        }
                    }

                    if(problem9 != null){
                        switch (problem9){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem9);
                        }
                    }

                    if(problem10 != null){
                        switch (problem10){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem10);
                        }
                    }

                    if(problem11 != null){
                        switch (problem11){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem11);
                        }
                    }

                    if(problem12 != null){
                        switch (problem12){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem12);
                        }
                    }

                    if(problem13 != null){
                        switch (problem13){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem13);
                        }
                    }

                    if(problem14 != null){
                        switch (problem14){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem14);
                        }
                    }

                    if(problem15 != null){
                        switch (problem15){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem15);
                        }
                    }

                    if(problem16 != null){
                        switch (problem16){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem16);
                        }
                    }

                    if(problem17 != null){
                        switch (problem17){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem17);
                        }
                    }

                    if(problem18 != null){
                        switch (problem18){
                        case 'Mic':
                            $("#mic").prop("checked", true);
                            break;
                        case 'Ringer':
                            $("#ringer").prop("checked", true);
                            break;
                        case 'Ear piece':
                            $("#ear_piece").prop("checked", true);
                            break;
                        case 'No power':
                            $("#no_power").prop("checked", true);
                            break;
                        case 'Software':
                            $("#software").prop("checked", true);
                            break;
                        case 'SIM connector':
                            $("#SIM_connector").prop("checked", true);
                            break;
                        case 'Charging pin':
                            $("#charging_pin").prop("checked", true);
                            break;
                        case 'Lock':
                            $("#lock").prop("checked", true);
                            break;
                        case 'Water damage':
                            $("#water_damage").prop("checked", true);
                            break;
                        case 'Display':
                            $("#display").prop("checked", true);
                            break;
                        case 'Country Lock':
                            $("#country_lock").prop("checked", true);
                            break;
                        case 'Ribbon':
                            $("#ribbon").prop("checked", true);
                            break;
                        case 'Touch':
                            $("#touch").prop("checked", true);
                            break;
                        case 'B/ pin':
                            $("#B_pin").prop("checked", true);
                            break;
                        case 'Short':
                            $("#short").prop("checked", true);
                            break;
                        case 'Battery':
                            $("#ploblem_battery").prop("checked", true);
                            break;
                        case 'IC':
                            $("#ic").prop("checked", true);
                            break;
                        default:
                            $('#problem_other').val(problem18);
                        }
                    }


                $('#up_id').val(id);
                $('#up_customer_phone').val(contact1);
                $('#up_customer_name').val(name);
                $('#up_job_no').val(job_no);
                $('#up_receipt_date').val(date);
                $('#up_brand').val(brand);
                $('#up_device_model').val(model);
                $('#up_imei_number').val(imei_no);
                $('#up_amount').val(amount);
                $('#up_advance').val(advance);
                $('#up_balance').val(balance);
                $('#up_product_configuration').val(configuration);
                $('#up_password').val(password);
                $('#up_problem_reported').val(problem_reported);
                $('#up_technician').val(technician);
                $('#up_item').val(items);
                $('#up_status').val(status);

            });

             //update job details
            $(document).on('click','.update_job',function(e){
                        e.preventDefault();
                        let up_id = $('#up_id').val();
                        let up_customer_phone = $('#up_customer_phone').val();
                        let up_customer_name = $('#up_customer_name').val();
                        let up_job_no = $('#up_job_no').val();
                        let up_receipt_date = $('#up_receipt_date').val();
                        let up_brand = $('#up_brand').val();
                        let up_device_model = $('#up_device_model').val();
                        let up_imei_number = $('#up_imei_number').val();
                        let up_amount = $('#up_amount').val();
                        let up_advance = $('#up_advance').val();
                        let up_balance = $('#up_balance').val();
                        let up_product_configuration = $('#up_product_configuration').val();
                        let up_password = $('#up_password').val();
                        let up_problem_reported = $('#up_problem_reported').val();
                        let up_technician = $('#up_technician').val();
                        let up_status = $('#up_status').val();
                        // Find the input field with the id "problem_other"
                        var otherProblem = $('#problem_other').val();
                        var otherItem = $('#item_other').val();

                        var itemValues = [];
                        $('input[name="items[]"]:checked').each(function() {
                            itemValues.push($(this).val());
                        });
                        // Push the value of the input field to the itemValues array
                        if (otherItem.trim() !== "") {
                            itemValues.push(otherItem);
                        }

                        var problemValues = [];
                        $('input[name="problems[]"]:checked').each(function() {
                            problemValues.push($(this).val());
                        });
                        // Push the value of the input field to the itemValues array
                        if (otherProblem.trim() !== "") {
                            problemValues.push(otherProblem);
                        }


                        $.ajax({
                            url:"{{ route('update_job_ajax') }}",
                            method: 'post',
                            data:{"_token": "{{ csrf_token() }}",
                            up_id:up_id,
                            up_customer_phone:up_customer_phone,
                            up_customer_name:up_customer_name,
                            up_job_no:up_job_no,
                            up_receipt_date:up_receipt_date,
                            up_brand:up_brand,
                            up_device_model:up_device_model,
                            up_imei_number:up_imei_number,
                            up_amount:up_amount,
                            up_advance:up_advance,
                            up_balance:up_balance,
                            up_product_configuration:up_product_configuration,
                            up_password:up_password,
                            up_problem_reported:up_problem_reported,
                            up_technician:up_technician,
                            up_status:up_status,
                            // itemValues:itemValues,
                            // problemValues:problemValues
                            itemValues: itemValues.join(', '), // Join the array into a comma-separated string
                            problemValues: problemValues.join(', '), // Join the array into a comma-separated string
                        },

                            success:function(res){
                                if(res.status=='success'){
                                    $("#updateJobModel").modal('hide');
                                    $('#updateJob')[0].reset();
                                    $('.table').load(location.href+' .table');
                                    Command: toastr["success"]("Job Datails Updated...", "Success")
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
                            },error:function(err){
                                $('.errMsgContaine2r').html('');
                                let error = err.responseJSON;
                                $.each(error.errors,function(index, value){
                                    $('.errMsgContainer2').append('<span class="text-danger">'+value+'<span>'+'<br>');

                                });
                            }
                        })
            })

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
</html>
@endsection
