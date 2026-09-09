@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>TAX Invoice</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
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
                                <h4 class="card-title m-3">TAX Invoice</h4>
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
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        var pdfLink = "{{ Session::get('pdfLink') }}";
                                        window.open(pdfLink, '_blank');
                                    });

                                </script>
                                @endif
                                <div class="row ">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="pending_repair_tab">
                                            <div class="row mb-4">
                                                <div class="col-md-8 mb-12">
                                                    <!--<a target="_blank" class="btn  btn-primary pull-right"-->
                                                    <!--    data-bs-toggle="modal" data-bs-target="#addInvoiceModel">-->
                                                    <!--    <i class="fa fa-plus"></i> Add </a>-->


                                                        <a href={{route('sales_create_invoice')}} class="btn  btn-primary pull-right">
                                                        <i class="fa fa-plus"></i> Create Invoice </a>

                                                </div>
                                                <div class="col-sm-4">
                                                    <div id="pending_repair_table_filter" class="dataTables_filter">
                                                        <label><input type="search" class="form-control input-sm"
                                                                placeholder="Search ..."
                                                                aria-controls="pending_repair_table"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive">

                                               <table class="table table-bordered table-striped ajax_view dataTable"
                                                    style="width: 1523px;">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>Action</th>
                                                            <th>Job_no</th>
                                                            <th>Invoice_no</th>
                                                            <th>Invoice_date</th>
                                                            <th>Customer_NIC</th>
                                                            <th>Customer_Name</th>
                                                            <th>Customer_Phone</th>
                                                            <th>Item</th>
                                                            <th>Problem</th>
                                                            <th>Amount</th>
                                                            <th>Advance</th>
                                                            <th>Balance</th>
                                                            <th>Brand</th>
                                                            <th>Device_Model</th>
                                                            <th>Delivery_date</th>
                                                            <th>Reported_date</th>
                                                            <th>Technician</th>
                                                            <th>Status</th>
                                                            <th>Password</th>
                                                            <th>IMEI_Number</th>
                                                            <th>Serial_Number</th>
                                                            <th>Product_Configuration</th>
                                                            <th>Problem_Reported</th>
                                                            <th>Product_Condition</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($TInvoiceSum as $data)
                                                        <tr>
                                                            <td>
                                                                <a href=""
                                                                            class="btn btn-sm btn-success update_customer_form bg-success-light text-success me-2"
                                                                            data-bs-toggle="modal" data-bs-target="#updateCustomerModel"
                                                                            data-id="{{$data->id}}"
                                                                            data-Job_no="{{$data->Job_no}}"
                                                                            data-Invoice_no="{{$data->Invoice_no}}"
                                                                            data-Invoice_date="{{$data->Invoice_date}}"
                                                                            data-Customer_NIC="{{$data->Customer_NIC}}"
                                                                            data-Customer_Name="{{$data->Customer_Name}}"
                                                                            data-Customer_Phone="{{$data->Customer_Phone}}"
                                                                            data-Item="{{$data->Item}}"
                                                                            data-Problem="{{$data->Problem}}"
                                                                            data-Amount="{{$data->Amount}}"
                                                                            data-Advance="{{$data->Advance}}"
                                                                            data-Balance="{{$data->Balance}}"
                                                                            data-Brand="{{$data->Brand}}"
                                                                            data-Device_Model="{{$data->Device_Model}}"
                                                                            data-Delivery_date="{{$data->Delivery_date}}"
                                                                            data-Reported_date="{{$data->Reported_date}}"
                                                                            data-Technician="{{$data->Technician}}"
                                                                            data-Status="{{$data->Status}}"
                                                                            data-Password="{{$data->Password}}"
                                                                            data-IMEI_Number="{{$data->IMEI_Number}}"
                                                                            data-Serial_Number="{{$data->Serial_Number}}"
                                                                            data-Product_Configuration="{{$data->Product_Configuration}}"
                                                                            data-Problem_Reported="{{$data->Problem_Reported}}"
                                                                            data-Product_Condition="{{$data->Product_Condition}}"

                                                                            ><i class="far fa-edit me-1"></i> Edit
                                                                        </a>

                                                                        {{-- <button type="button" class="btn btn-sm delete_invoice btn-danger bg-danger-light text-danger me-2"
                                                                        data-id="{{$data->id}}">
                                                                            <i class="far fa-trash-alt me-1"></i> Delete
                                                                        </button> --}}
                                                            </td>
                                                            <td>{{$data->Job_no}}</td>
                                                            <td>{{$data->Invoice_no}}</td>
                                                            <td>{{$data->Invoice_date}}</td>
                                                            <td>{{$data->Customer_NIC}}</td>
                                                            <td>{{$data->Customer_Name}}</td>
                                                            <td>{{$data->Customer_Phone}}</td>
                                                            <td>{{$data->Item}}</td>
                                                            <td>{{$data->Problem}}</td>
                                                            <td>{{$data->Amount}}</td>
                                                            <td>{{$data->Advance}}</td>
                                                            <td>{{$data->Balance}}</td>
                                                            <td>{{$data->Brand}}</td>
                                                            <td>{{$data->Device_Model}}</td>
                                                            <td>{{$data->Delivery_date}}</td>
                                                            <td>{{$data->Reported_date}}</td>
                                                            <td>{{$data->Technician}}</td>
                                                            <td>
                                                                @if ($data->Status == 'Pending')
                                                                <span style="color: rgb(231, 209, 5)">Pending</span>
                                                                @elseif ($data->Status == 'Complete')
                                                                <span style="color: rgb(62, 252, 4)">Complete</span>
                                                                @endif
                                                            </td>
                                                            <td>{{$data->Password}}</td>
                                                            <td>{{$data->IMEI_Number}}</td>
                                                            <td>{{$data->Serial_Number}}</td>
                                                            <td>{{$data->Product_Configuration}}</td>
                                                            <td>{{$data->Problem_Reported}}</td>
                                                            <td>{{$data->Product_Condition}}</td>

                                                        </tr>
                                                        @endforeach
                                                    </tbody>

                                                    {{-- <tfoot>
                                                        <tr class="bg-gray font-17 footer-total text-center">
                                                            <td colspan="11" rowspan="1">
                                                                <strong>Total:</strong>
                                                            </td>
                                                            <td id="footer_pending_repair_status_count" rowspan="1"
                                                                colspan="1">
                                                                <p class="text-left"><small></small>
                                                                </p>
                                                            </td>
                                                            <td rowspan="1" colspan="1"></td>
                                                            <td rowspan="1" colspan="1"></td>
                                                            <td id="pending_repair_footer_payment_status_count"
                                                                rowspan="1" colspan="1">
                                                                <p class="text-left"><small></small>
                                                                </p>
                                                            </td>
                                                            <td rowspan="1" colspan="1">
                                                                <span class="display_currency"
                                                                    id="pending_repair_footer_total"
                                                                    data-currency_symbol="true">₨
                                                                    0.00</span>
                                                            </td>
                                                            <td class="text-left" rowspan="1" colspan="1">
                                                                <small>
                                                                    <span class="display_currency"
                                                                        id="pending_repair_footer_total_remaining"
                                                                        data-currency_symbol="true">₨
                                                                        0.00</span>
                                                                </small>
                                                            </td>
                                                            <td rowspan="1" colspan="1"><span class="display_currency"
                                                                    id="pending_repair_footer_total_sell_return_due"
                                                                    data-currency_symbol="true">₨
                                                                    0.00</span></td>
                                                        </tr>
                                                    </tfoot> --}}

                                                </table>

                                                {!! $TInvoiceSum->links() !!}

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

    {{-- ............Add Job Invoice model................................. --}}
    <div class="modal fade" id="addInvoiceModel" tabindex="-1" role="dialog" aria-labelledby="addInvoiceModelLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title m-2" id="addInvoiceModelLabel"> Add Invoice </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="errMsgContainer2"></div>

                                    <div class="row ">
                                        <form action="" method="post" name="addInvoice" id="addInvoice">
                                            @csrf
                                            <input type="hidden" id="operator" name="operator" value="{{ Auth::user()->name }}" required>
                                            {{-- row 1 --}}
                                            <div class="row mb-2">
                                                <div class="col-md-4"></div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="date">Invoice Date : </label>
                                                        <input class="form-control datetimepicker" type="date"
                                                            id="invoice_date" name="invoice_date" required>

                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="date">Invoice No : </label>
                                                        <input class="form-control datetimepicker" type="text"
                                                            id="invoice_no" name="invoice_no"
                                                            value="{{$maxInvoiceNo+1}}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- row 2 --}}
                                            <div class="row mb-2">
                                                <input type="hidden" name="id" id="id">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="job_no">Job No : </label>
                                                        <input class="form-control" type="text"
                                                            placeholder="Job Number:" id="job_no" name="job_no"
                                                            required>

                                                    </div>
                                                </div>
                                                <div class="col-md-4 ">
                                                    <div class="form-group">
                                                        <div class="showCustomer">
                                                            <label for="customer_name">Customer Name : </label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control  form-control-lg"
                                                                    name="customer_name" id="customer_name"
                                                                    placeholder="Customer Name" required />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="customer_phone">Customer Tel :</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control  form-control-lg"
                                                                name="customer_phone" id="customer_phone"
                                                                placeholder="Customer Tel" required />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- row 3 --}}
                                            <div class="row  mb-2">
                                                {{-- brand --}}
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="brand">Brand :</label>
                                                        <div class="input-group">
                                                            <input type="text" name="brand" id="brand"
                                                                class="form-control" placeholder="Enter Brand Name"
                                                                required />
                                                        </div>
                                                    </div>
                                                </div>

                                                {{--Phone Model--}}
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="device_model">Device Model :</label>
                                                        <div class="input-group">
                                                            <input type="text" name="device_model" id="device_model"
                                                                class="form-control" placeholder="Enter Device Model"
                                                                required />
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Device Modal --}}
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label for="imei_number">IMEI Number :</label>
                                                        <div class="input-group">
                                                            <input type="text" name="imei_number" id="imei_number"
                                                                class="form-control" placeholder="Enter IMEI Number"
                                                                required />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- row 4 --}}
                                            <div class="row mb-2">
                                                {{-- brand --}}
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="brand">Problem Reported By :</label>
                                                        <div class="input-group">
                                                            <input type="date" name="receipt_date" id="receipt_date"
                                                                class="form-control" placeholder="Enter Brand Name"
                                                                required />
                                                        </div>
                                                    </div>
                                                </div>

                                                {{--Phone Model--}}
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="completed_on">Repair Completed On :</label>
                                                        <div class="input-group">
                                                            <input type="date" name="completed_on" id="completed_on"
                                                                class="form-control" placeholder="Enter Device Model"
                                                                required />
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Device Modal --}}
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label for="status">Status :</label>
                                                        <div class="input-group">
                                                            <input type="text" name="status" id="status"
                                                                class="form-control" placeholder="Enter Status"
                                                                required />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>

                                            {{-- checkbox first --}}
                                            <div class="row mt-2 mb-2">
                                                    <div class="row ml-5">

                                                        <div class="col">
                                                            <div class="form-check form-check-inline ">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="battery" value="Battery" name="items[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox1">Battery</label>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="charger" value="Charger" name="items[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox2">Charger</label>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="memory_card" value="Memory Card" name="items[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox2">Memory Card</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="sim"
                                                                    value="Sim" name="items[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox1">Sim</label>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="back_cover" value="Back Cover" name="items[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox2">Back Cover</label>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text" name="items[]" id="item_other"
                                                                        class="form-control" placeholder="Other" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <hr>

                                            {{-- payment section --}}
                                            <div class="row mt-2 ">
                                                <div class="col">
                                                    <label for="amount">AMOUNT :
                                                        <input class="form-control form-control-lg"
                                                            style="font-weight:bold;" type="text" placeholder="AMOUNT:"
                                                            id="amount" name="amount" required>
                                                    </label>
                                                </div>
                                                <div class="col">
                                                    <label for="amount">ADVANCE :
                                                        <input style="font-weight:bold;"
                                                            class="form-control form-control-lg" type="text"
                                                            placeholder="ADVANCE:" id="advance" name="advance">
                                                    </label>
                                                </div>
                                                <div class="col">
                                                    <label for="amount">BALANCE :
                                                        <input style="font-weight:bold;"
                                                            class="form-control form-control-lg" type="text"
                                                            placeholder="BALANCE:" id="balance" name="balance" required>
                                                    </label>
                                                </div>
                                            </div>
                                            {{-- <hr> --}}

                                            {{-- checkbox second section --}}
                                            {{-- <div class="row mt-2 mb-3">
                                                    <div class="row ml-5">
                                                        <div class="col">
                                                            <div class="form-check form-check-inline ">
                                                                <input class="form-check-input" type="checkbox" id="mic"
                                                                    value="Mic" name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox1">Mic</label>
                                                            </div>

                                                        </div>
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="ringer" value="Ringer" name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox2">Ringer</label>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="ear_piece" value="Ear piece" name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox2">Ear piece</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="no_power" value="No power" name="problems[]">
                                                                <label class="form-check-label" for="inlineCheckbox1">No
                                                                    power</label>
                                                            </div>

                                                        </div>
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="software" value="Software" name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox2">Software</label>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="SIM_connector" value="SIM connector"
                                                                    name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox2">SIM connector</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="charging_pin" value="Charging pin"
                                                                    name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox1">Charging pin</label>
                                                            </div>

                                                        </div>
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="lock" value="Lock" name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox2">Lock</label>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="water_damage" value="Water damage"
                                                                    name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox2">Water damage</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="display" value="Display" name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox1">Display</label>
                                                            </div>

                                                        </div>
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="country_lock" value="Country Lock"
                                                                    name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox2">Country Lock</label>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="ribbon" value="Ribbon" name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox2">Ribbon</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="touch" value="Touch" name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox1">Touch</label>
                                                            </div>

                                                        </div>
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="B_pin" value="B/ pin" name="problems[]">
                                                                <label class="form-check-label" for="inlineCheckbox2">B/
                                                                    pin</label>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="short" value="Short" name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox2">Short</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox"
                                                                    id="ploblem_battery" value="Battery"
                                                                    name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox2">Battery</label>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="checkbox" id="ic"
                                                                    value="IC" name="problems[]">
                                                                <label class="form-check-label"
                                                                    for="inlineCheckbox2">IC</label>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input type="text" name="problems[]"
                                                                        id="problem_other" class="form-control"
                                                                        placeholder="Other" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr> --}}

                                            {{-- row for password details --}}
                                            {{-- <div class="row mt-2 mb-3">
                                                    <div class="col 6">
                                                        <label for="Product Configuration" class="form-label">
                                                            Product Configuration :
                                                        </label>
                                                        <input type="text" class="form-control"
                                                            id="product_configuration"
                                                            name="product_configuration"
                                                            placeholder="Product Configuration" />
                                                    </div>
                                                    <div class="col 6">
                                                        <label for="password" class="form-label"> Password :</label>
                                                        <input type="text" class="form-control" id="password"
                                                            name="password" placeholder="Password">
                                                    </div>
                                                </div> --}}


                                            {{-- row for form footer  --}}
                                            {{-- <div class="row mt-2 mb-2">
                                                    <div class="col">
                                                        <label for="Problem Reported" class="form-label">
                                                            Problem Reported By The customer :
                                                        </label>
                                                        <input type="text" class="form-control" id="problem_reported"
                                                            name="problem_reported"
                                                            placeholder="Problem Reported By The customer" />
                                                    </div>
                                                    <div class="col">
                                                        <label for="product_condition" class="form-label">
                                                            Technician :
                                                        </label>
                                                        <input type="text" class="form-control" id="technician"
                                                            name="technician" placeholder="Technician" />
                                                    </div>
                                                </div> --}}

                                            {{-- row for buttons --}}
                                            <div class="row">
                                                <div class="text-center mt-4">
                                                    <button type="button"
                                                        class="btn btn-success add_invoice bg-success-light text-success me-2">Save</button>
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal" aria-label="Close">Close</button>
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


    {!! Toastr::message() !!}

    {{-- form default date set for today --}}
    <script>
        document.getElementById('completed_on').valueAsDate = new Date();
        document.getElementById('invoice_date').valueAsDate = new Date();

    </script>

    {{-- CSRF Token --}}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>

    {{-- value auto calculation --}}
    <script>
        $(document).ready(function () {
            // Listen for changes in the amount and advance fields
            $('#amount, #advance').on('input', function () {
                // Get values from the Weight and QTY fields
                let amount = parseFloat($('#amount').val()) || 0;
                let advance = parseInt($('#advance').val()) || 0;

                // Calculate the value
                let balance = (amount - advance).toFixed(2);

                // Update the Value field
                $('#balance').val(balance);
            });
        });

    </script>

    {{--  get customer data inserting job no --}}
    <script>
        $(document).ready(function () {
            // search customer data
            $(document).on('keyup', function (e) {
                e.preventDefault();
                var search_string = $('#job_no').val();
                // console.log(search_string);
                if (search_string != null) {
                    if (e.keyCode == 13 || e.keyCode == 10) {
                        $.ajax({
                            url: "{{ route('get_job_ajax') }}",
                            method: 'GET',
                            data: {
                                search_string: search_string
                            },
                            success: function (res) {
                                if (res.status == 'success') {
                                    let data = res.jobNo;
                                    let job_details = data[0];

                                    // get job data
                                    let Customer_Name = job_details.Customer_Name;
                                    let Customer_Phone = job_details.Customer_Phone;
                                    let Date = job_details.Date;
                                    let Brand = job_details.Brand;
                                    let Device_Model = job_details.Device_Model;
                                    let IMEI_Number = job_details.IMEI_Number;
                                    let Amount = job_details.Amount;
                                    let Advance = job_details.Advance;
                                    let Balance = job_details.Balance;
                                    let Problem_Reported = job_details.Problem_Reported;
                                    let Status = job_details.Status;

                                    $('#customer_name').val(Customer_Name);
                                    $('#customer_phone').val(Customer_Phone);
                                    $('#receipt_date').val(Date);
                                    $('#brand').val(Brand);
                                    $('#device_model').val(Device_Model);
                                    $('#imei_number').val(IMEI_Number);
                                    $('#amount').val(Amount);
                                    $('#advance').val(Advance);
                                    $('#balance').val(Balance);
                                    $('#problem_reported').val(Problem_Reported);
                                    $('#status').val(Status);
                                }

                                if (res.status == 'not_found') {
                                    $('.showCustomer').html(
                                        `   <div class="form-group">
                                            <label for="customer_name" class="text-danger"> Customer Name : </label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control text-danger form-control-lg"
                                                        name="customer_name" id="customer_name"
                                                        placeholder="Customer Not Found ..!!" required />
                                                </div>
                                            </div>
                                                    `
                                    );

                                }
                            }
                        });
                    }
                }
            })
        });

    </script>

    {{-- clear form --}}
    <script>
        $(document).ready(function () {
            //add new customer
            $(document).on('keyup', '#job_no', function (e) {
                e.preventDefault();
                $('#customer_name').val("");
                $('#customer_phone').val("");
                $('#receipt_date').val("");
                $('#brand').val("");
                $('#device_model').val("");
                $('#imei_number').val("");
                $('#amount').val("");
                $('#advance').val("");
                $('#balance').val("");
                $('#problem_reported').val("");
                $('#status').val("");



            })
        });

    </script>

    {{-- add new invoice script --}}
    <script>
        $(document).ready(function () {
            $(document).on('click', '.add_invoice', function (e) {
                e.preventDefault();
                let invoice_no = $('#invoice_no').val();
                let invoice_date = $('#invoice_date').val();
                let job_no = $('#job_no').val();
                let customer_name = $('#customer_name').val();
                let customer_phone = $('#customer_phone').val();
                let brand = $('#brand').val();
                let device_model = $('#device_model').val();
                let imei_number = $('#imei_number').val();
                let receipt_date = $('#receipt_date').val();
                let completed_on = $('#completed_on').val();
                let status = $('#status').val();
                let amount = $('#amount').val();
                let advance = $('#advance').val();
                let balance = $('#balance').val();

                $.ajax({
                    url: "{{ route('add_invoice_ajax') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        invoice_no: invoice_no,
                        invoice_date: invoice_date,
                        job_no: job_no,
                        customer_name: customer_name,
                        customer_phone: customer_phone,
                        brand: brand,
                        device_model: device_model,
                        imei_number: imei_number,
                        receipt_date: receipt_date,
                        completed_on: completed_on,
                        status: status,
                        amount: amount,
                        advance: advance,
                        balance: balance,
                    },

                    success: function (res) {
                        if (res.status == 'success') {
                            $("#addInvoiceModel").modal('hide');
                            $('#addInvoice')[0].reset();
                            $('.table').load(location.href+' .table');
                            Command: toastr["success"]("Invoice Added ...!", "Success")
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

                            // get customer data
                            // let data = res.telNo;
                            // let cus_details = data[0];
                            // let cus_tel = cus_details.Contact_1;
                            // let cus_name = cus_details.First_name;

                            // $('#searchCustomer').val(cus_tel);
                            // $('#customer_name').val(cus_name);
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
        });

    </script>

    {{-- delete invoice script --}}
    <script>
        $(document).ready(function(){
            $(document).on('click','.delete_invoice',function(e){
                        e.preventDefault();
                        let invoice_id = $(this).data('id');

                        if(confirm('Are you sure to delete invoice ?')){
                            $.ajax({
                                url:"{{ route('delete_invoice_ajax') }}",
                                method: 'post',
                                data:{"_token": "{{ csrf_token() }}",invoice_id:invoice_id},
                                success:function(res){
                                    if(res.status=='success'){
                                        $('.table').load(location.href+' .table');
                                        Command: toastr["success"]("Invoice deleted...", "Success")
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

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/datatables.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

</body>

</html>
@endsection