@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Purchase Invoice</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <style>
        .item-description-wrapper {
            display: inline-block;
            max-width: 400px;
            white-space: normal;
        }


    </style>

<style>
    #scrollbar {
        display: block;
        overflow-y: auto;
        max-height: 200px;
        max-width:: 1000px;
    }
</style>
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card shadow">
                            <div class="col-md-9">
                                <h4 class="card-title m-3">Purchase Invoice</h4>
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
                                        // Replace 'your_pdf_link_here' with the actual variable containing the PDF link
                                        var pdfLink = "{{ Session::get('pdfLink') }}";
                                        var newWindow = window.open(pdfLink, '_blank');

                                        // Wait for the new window load, then trigger the print function
                                        newWindow.onload = function () {
                                            newWindow.print();
                                        };
                                    });

                                </script>
                                @endif


                                <form action="{{route('add_Purchases')}}" method="post" id="purchase_form">
                                    @csrf
                                    <div class="row ">
                                        <div class="row mb-1 form-group justify-content-between">
                                            <div class="row">
                                                <div class="col-md-5">

                                                    <div class="input-group">
                                                        <div class="input-group-text" id="btnGroupAddon1">Supplier Code :
                                                        </div>
                                                        <input type="text" id="searchCustomer" name="customer_nic"
                                                            class="form-control" placeholder="Enter Supplier Code :"
                                                            required aria-label="Invoice Number:"
                                                            aria-describedby="btnGroupAddon1">
                                                        <div class="input-group-append">

                                                            <button type="button"
                                                            class="btn btn-info btn-lg form-control "
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addguarantor1Model">
                                                            <i class="fas fa-eye eye-icon" style="color: white"></i>
                                                        </button>
                                                            <button type="button"
                                                                class="btn btn-success btn-lg form-control "
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#addCustomerModel">
                                                                <i class="fas fa-plus" style="color: white"></i>
                                                            </button>

                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-2 payment-history">
                                                </div>

                                                <div class="col-md-1">
                                                </div>

                                                <div class="col">
                                                    <div class="input-group">
                                                        <div class="input-group-text" id="btnGroupAddon2">No :
                                                            &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        </div>
                                                        <input type="text" id="invoice_no" name="invoice_no"
                                                            value="{{$maxInvoiceNo+1}}" class="form-control"
                                                            placeholder="Invoice Number:" aria-label="Invoice Number:"
                                                            aria-describedby="btnGroupAddon2">
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-8"></div>
                                                <div class="col">
                                                    <div class="input-group">
                                                        <div class="input-group-text" id="btnGroupAddon2">Purchase No :

                                                        </div>
                                                        <input type="text" id="sales_invoice_no" name="sales_invoice_no"
                                                            class="form-control"
                                                            placeholder="Stock Sales No" aria-label="Stock Sales No:"
                                                            aria-describedby="btnGroupAddon2">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-8">
                                                </div>
                                                <div class="col">
                                                    <div class="input-group">
                                                        <div class="input-group-text" id="btnGroupAddon3">Date :
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        </div>
                                                        <input type="date" id="invoice_date" name="invoice_date"
                                                            class="form-control" aria-label="Date:"
                                                            aria-describedby="btnGroupAddon3">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        {{-- heading inputs --}}
                                        <div class="row form-group">
                                            <div class="col-md-4 ">
                                            </div>
                                            <div class="row">

                                                <div class="customer-data">

                                                </div>
                                                <div class="showCustomer">

                                                </div>

                                            </div>
                                        </div>

<!-- Manual Serial Number Modal -->
<div class="modal fade" id="addSerialNoModal" tabindex="-1" role="dialog" aria-labelledby="addSerialNoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="addSerialNoModalLabel">Add Manual Serial Numbers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- JS will inject input fields here -->
            </div>
            <div class="modal-footer">
                <button type="button" id="saveManualSerialNumbers" class="btn btn-primary">Save Serial Numbers</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


<!-- Auto Serial Number Modal -->
<div class="modal fade" id="addAutoSerialNoModal" tabindex="-1" role="dialog" aria-labelledby="addAutoSerialNoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="addAutoSerialNoModalLabel">Add Auto Serial Numbers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- JS will inject input fields here -->
            </div>
            <div class="modal-footer">
                <button type="button" id="saveAutoSerialNumbers" class="btn btn-primary">Save Serial Numbers</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="itemSerialsField" name="itemSerialsField">





                                        {{-- dynamicAdded table --}}
                                        <div class="table-responsive">
                                        <table class="table table-bordered" >
                                            <thead  style="background-color: #22a9ec;">
                                                <tr>

                                                    <th style=" center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Item Code&nbsp;&nbsp;&nbsp;</th>
                                                    <th style=" text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Description&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>

                                                    <th style=" text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GRN Price&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                       <th style=" text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;QTY&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>

                                                    <th style=" text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;Net Value&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                    <th class="text-center" style="">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr>
                                                    <td id="showItems">

                                                        <div class="input-group">

                                                            <input type="hidden" id="item_code" name="item_code" class="form-control"
                                                                placeholder="item Code" required aria-label="item Code"
                                                                aria-describedby="btnGroupAddon1">
                                                            <input type="text" id="item_s_code" name="item_s_code" class="form-control"
                                                                placeholder="item Code" required aria-label="item Code"
                                                                aria-describedby="btnGroupAddon1">
                                                            <div class="input-group-append">
                                                                <button type="button" class="btn btn-success btn-lg form-control "
                                                                    data-bs-toggle="modal" data-bs-target="#searchItemModel">
                                                                    <i class="fas fa-plus" style="color: white"></i>
                                                                </button>
                                                            </div>
                                                        </div>


                                                    </td>
                                                    <td>
                                                        <select class="select form-control " id="item_description"
                                                            name="item_description" aria-hidden="true">
                                                            <option value="">Select an item</option>
                                                            @foreach( $itemCode as $itemData)
                                                            <option value="{{ $itemData->Item_description }}">
                                                                {{ $itemData->Item_description}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <input class="form-control" type="text" placeholder="Unit Price"
                                                            id="unit_price" name="unit_price" value="0">
                                                    </td>

                                                        <td>
                                                        <div class="input-group">
                                                            <input class="form-control" type="text" placeholder="QTY" id="qty" name="qty">
                                                            <div class="input-group-append">

                                                            </div>
                                                        </div>
                                                    </td>


                                                    <td>
                                                        <input class="form-control" type="text" placeholder="Net Value"
                                                            id="net_value" name="net_value" value="0">
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" name="add"
                                                            class="btn add-item  btn-outline-info btn-lg shadow"> Add
                                                            <i class="fas fa-plus"></i></button>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                        </div>
                                        <br>

                                        <div id="addSerial">

                                        </div>


                                        {{--------------search Item Model----------------- --}}
                                        <div class="modal fade" id="searchItemModel" tabindex="-1" role="dialog"
                                            aria-labelledby="searchItemModelLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title m-2" id="searchItemModelLabel"> Search Item </h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="errMsgContainer"></div>

                                                                        {{-- -------------Item Details Table -------------- --}}
                                                                        <div class="card-body ">
                                                                            <div class="table-responsive ">
                                                                                <div class="table-data ">
                                                                                    <table
                                                                                        class="table table-bordered table-center table-hover mt-5"
                                                                                        id="ItemTable">
                                                                                        <thead>
                                                                                            <tr class="table-secondary">
                                                                                                <th style="display:none;">Code</th>
                                                                                                <th style="text-align: center">Code</th>
                                                                                                <th style="text-align: center">Bar Code</th>
                                                                                                <th style="text-align: center">Name</th>
                                                                                                <th style="text-align: center">Credit Price</th>
                                                                                                <th style="text-align: center">Price</th>
                                                                                                <th style="text-align: center">Action</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            @foreach ($itemDetails as $key=>$ItemData)
                                                                                            <tr>
                                                                                                <td style="display:none;">{{$ItemData->Item_code }}</td>
                                                                                                <td >{{$ItemData->Item_code }}</td>
                                                                                                  <td>
                                                                                        <div class="item-description-wrapper">
                                                                                            {{$ItemData->Bar_code}}
                                                                                        </div>
                                                                                    </td>
                                                                                                <td>
                                                                                                    <div class="item-description-wrapper">
                                                                                                        {{$ItemData->Item_description}}
                                                                                                    </div>
                                                                                                </td>
                                                                                                <td style="text-align: right">{{$ItemData->Credit}}</td>
                                                                                                <td style="text-align: right">{{$ItemData->purchasePrice}}</td>
                                                                                                <td>
                                                                                                    <a href=""
                                                                                                        class="btn btn-outline-info btn-sm shadow"
                                                                                                        name="add_item"
                                                                                                        id="add_item"
                                                                                                        data-bs-toggle="modal"
                                                                                                        data-bs-target="#searchItemModel"
                                                                                                        data-id="{{$ItemData->id}}"
                                                                                                        data-add_item_code="{{$ItemData->Item_code}}"
                                                                                                        data-Item_description="{{$ItemData->Item_description}}"
                                                                                                        data-Bar_code="{{$ItemData->Bar_code}}">
                                                                                                        Add <i class="fas fa-plus"></i>
                                                                                                    </a>
                                                                                                </td>
                                                                                            </tr>
                                                                                            @endforeach
                                                                                        </tbody>
                                                                                    </table>

                                                                                    <script>
                                                                                        $(document).ready(function() {
                                                                                        $('#ItemTable').DataTable();
                                                                                    });

                                                                                    </script>

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

                                        {{-- dynamicAdded table --}}
                                        <table class="table table-bordered" id="dynamicAdded">

                                        </table>

                                        {{-- table footer for total calculations --}}
                                        <table class="table table-bordered" id="green_total_row">
                                            <tbody>
                                                <tr class="table-success">
                                                    <td style="width:8%;"> </td>
                                                    <td style="width:8%;"><strong>
                                                            <p>TOTAL :</p>
                                                        </strong> </td>
                                                    <td style="width:5%;"> </td>
                                                    <td style="width:5%;"></td>

                                            <td class="total-unit-price text-center" style="width:8%;">
                                                        <p ><strong>0.00</strong></p>
                                                    </td>
                                                    <td class="total-total_weight text-center" style="width:8%;">
                                                        <p><strong></strong></p>
                                                    </td>
                                                    <td class="total-discount text-center" style="width:8%;">
                                                        <p><strong>0.00</strong></p>
                                                    </td>
                                                    <td class="total-value text-center" style="width:8%;">
                                                        <p><strong>0.00</strong></p>
                                                    </td>
                                                    <td style="width:11%;"></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        {{-- bottom values section  --}}
                                        <div class="row mt-3">
                                            <div class="row mt-1 justify-content-between">
                                                <div class="col-md-4">
                                                    <div class="input-group ">
                                                        <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon4">
                                                            CASH PAY :</div>
                                                        <input type="text" style="font-weight:bold;" class="form-control"
                                                            placeholder="CASH PAY :" id="cash_payment" name="cash_payment"
                                                            aria-label="CASH PAY :" aria-describedby="btnGroupAddon4" >
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon5">
                                                            GROSS AMOUNT :</div>
                                                        <input type="text" style="font-weight:bold;" class="form-control"
                                                            placeholder="GROSS AMOUNT :" id="total_amount" name="gross_amount"
                                                            aria-label="GROSS AMOUNT :" aria-describedby="btnGroupAddon5" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3 justify-content-between">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon6">
                                                            CREDITE :&nbsp;&nbsp;</div>
                                                        <input type="text" style="font-weight:bold;" class="form-control"
                                                            placeholder="CREDITE :" id="credite_payment" name="credite_payment"
                                                            aria-label="CREDITE :" aria-describedby="btnGroupAddon6" >
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon7">
                                                            DISCOUNT
                                                            :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        </div>
                                                        <input type="text" style="font-weight:bold;" class="form-control"
                                                            placeholder="DISCOUNT :" id="paid_discount" name="discount"
                                                            aria-label="DISCOUNT :" aria-describedby="btnGroupAddon7" required>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row mt-3 justify-content-between">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon8">
                                                            CHEQUE :&nbsp;&nbsp;</div>
                                                        <input type="text" style="font-weight:bold;" class="form-control"
                                                            placeholder="CHEQUE :" id="cheque_payment" name="cheque_payment"
                                                            aria-label="CHEQUE :" aria-describedby="btnGroupAddon8">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon9">
                                                            NET AMOUNT :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                                        <input type="text" style="font-weight:bold;" class="form-control"
                                                            placeholder="NET AMOUNT :" id="paid_amount" name="net_amount"
                                                            aria-label="NET AMOUNT :" aria-describedby="btnGroupAddon9" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- bottom buttons  --}}
                                        <div class="row">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-7">
                                                <br>
                                                <button type="submit" name="save" id="save"
                                                    class="btn btn-outline-info btn-lg shadow" >SAVE</button>
                                                <button type="button" name="submit_recall" id="submit_recall" style="display: none;"
                                                    class="btn btn-outline-info btn-lg shadow">ADD</button>
                                                <button type="button" name="print"
                                                    class="btn btn-outline-primary printReceipt btn-lg shadow">PRINT</button>
                                                <button type="button" name="pawn_delete" id="pawn_delete"
                                                    class="btn btn-outline-danger btn-lg shadow pawn_delete">DELETE</button>
                                                <button type="button" name="pawn_cancel" id="pawn_cancel"
                                                    class="btn btn-outline-warning btn-lg shadow pawn_cancel">CANCEL</button>
                                                <button type="reset" name="reset"
                                                    class="btn btn-outline-secondary btn-lg shadow">RESET</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addguarantor1Model" tabindex="-1" role="dialog" aria-labelledby="addguarantor1Model" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title m-2" id="addguarantor1Model">Search First Guarantor</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- Error Message Container -->
                                        <div class="errMsgContainer"></div>

                                        <!-- Search Form (currently not in use, commented out) -->
                                        <form action="" method="post" id="getItemCode">
                                            @csrf
                                            <div class="col-md-7">
                                                {{--
                                                <div class="input-group">
                                                    <div class="input-group-text" id="btnGroupAddonItem1">Customer Name :</div>
                                                    <input type="text" id="cus_name" name="cus_name" class="form-control" placeholder="Enter Customer Name :" aria-label="Customer Name :" aria-describedby="Cus1">
                                                </div>
                                                --}}
                                            </div>
                                        </form>

                                        <!-- Guarantor Table -->
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <div class="cus-table-data">
                                                    <table class="table table-bordered table-center table-hover mt-3" id="Guaranttableone">
                                                        <thead>
                                                            <tr class="table-secondary">
                                                                <th>Code</th>
                                                                <th>Name</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($SupplierData as $data)
                                                            <tr>
                                                                <td>{{ $data->Code  }}</td>
                                                                <td>
                                                                    <div class="item-description-wrapper">{{ $data->Name }}</div>
                                                                </td>
                                                                <td>
                                                                    <a href="#" onclick="fillGuarantOneCode('{{ $data->Code }}')" class="btn btn-outline-info btn-sm shadow" data-bs-dismiss="modal">
                                                                        Add <i class="fas fa-plus"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- DataTable Initialization -->
                                        <script>
                                            $(document).ready(function() {
                                                $('#Guaranttableone').DataTable();
                                            });
                                        </script>

                                        <!-- Fill Guarantor Code Function -->
                                        <script>
                                            function fillGuarantOneCode(Code) {
                                                document.getElementById('searchCustomer').value = Code;
                                            }
                                        </script>
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

{{-- ------------Add Suppliers model----------------- --}}
<div class="modal fade" id="addCustomerModel" tabindex="-1"
    role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title m-2" id="myLargeModalLabel">
                    Add Suppliers </h4>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="errMsgContainer"></div>
                                <form action="" method="post"
                                    id="addCustomer">
                                    @csrf
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Code
                                                    :</label>
                                                <input type="text"
                                                    name="code"
                                                    id="code"
                                                    value="{{ $maxSupplier+1 }}"
                                                    class="form-control"
                                                    placeholder="Customer Code"
                                                    required>
                                            </div>
                                            <div class="col-md-6">
                                                <div
                                                    class="form-group">
                                                    <label>Full name
                                                        :</label>
                                                    <input
                                                        type="text"
                                                        name="name"
                                                        id="name"
                                                        class="form-control"
                                                        placeholder="Full name"
                                                        required>
                                                </div>
                                            </div>
                                        </div>


                                        {{-- row for Address --}}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <textarea
                                                    class="form-control"
                                                    name="address1"
                                                    id="address1"
                                                    rows="3"
                                                    placeholder="Address 1"
                                                    required></textarea>
                                            </div>


                                        </div>


                                        {{-- row for Email --}}
                                        <div class="row mt-4">

                                            <div class="col-md-6">
                                                <label>Contact:</label>
                                                <input type="text"
                                                    name="contact1"
                                                    id="contact1"
                                                    class="form-control"
                                                    placeholder="Contact 1"
                                                    required>

                                            </div>
                                            <div class="col-md-6">
                                                <label>Email
                                                    :</label>
                                                <input type="text"
                                                    name="email"
                                                    id="email"
                                                    class="form-control"
                                                    placeholder="Email"
                                                    required>
                                            </div>

                                        </div>

                                        {{-- row for NIC and Driving Licence --}}



                                        {{-- row for PASSPORT and other --}}


                                        {{-- row for Blacklisted --}}
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    {{-- <div class="col-md-1">
                                                        <input type="checkbox" value="1" name="blacklisted" id="blacklisted" >
                                                    </div> --}}
                                                    <div
                                                        class="col-md-11">
                                                        <label>Mark
                                                            as
                                                            Active
                                                            or
                                                            Blacklisted</label>
                                                        <div
                                                            class=" form-group">
                                                            <select
                                                                class="select form-control"
                                                                name="status"
                                                                id="status"
                                                                aria-hidden="true"
                                                                required>
                                                                <option
                                                                    value="">
                                                                    Please
                                                                    Select
                                                                </option>
                                                                <option
                                                                    value="1"
                                                                    selected>
                                                                    Active
                                                                </option>
                                                                <option
                                                                    value="0">
                                                                    Blacklist
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div
                                            class="text-center mt-4">
                                            <button type="button"
                                                class="btn btn-success add_customer bg-success-light text-success me-2">Save</button>
                                            <button type="button"
                                                class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal"
                                                aria-label="Close">Close</button>
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
    var dateObj = new Date();
        document.getElementById('invoice_date').value = dateObj.toISOString().slice(0, 10);
</script>

{{-- CSRF Token --}}
<script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
</script>

{{-- Disable form auto-submit on Enter key press --}}
<script>
     document.getElementById('purchase_form').addEventListener('keydown', function (event) {
        if (event.keyCode == 13 || event.keyCode == 10) {
            event.preventDefault();
        }
    });
</script>

<script>
    // Custom validation to ensure only one payment type is filled
    document.getElementById('purchase_form').addEventListener('submit', function (event) {
        var cashPayment = document.getElementById('cash_payment').value.trim();
        var creditPayment = document.getElementById('credit_payment').value.trim();
        var chequePayment = document.getElementById('cheque_payment').value.trim();

        var filledCount = [cashPayment, creditPayment, chequePayment].filter(function (payment) {
            return payment !== '';
        }).length;

        if (filledCount !== 1) {
            alert('Please fill exactly one payment type.');
            event.preventDefault(); // Prevent form submission
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

{{-- add serial number pop up --}}
<script>
    $(document).ready(function () {
        const addSerialNoModal = $('#addSerialNoModal');
        const addAutoSerialNoModal = $('#addAutoSerialNoModal');

        let allSerialNumbersData = []; // Collect serial numbers for all rows

        // Manual Serial Number Modal
        $('#qty').on('keyup', function () {
            let qty = parseInt($(this).val());
            if (!qty || qty <= 0) return;
            let description = $('#item_description').val();

            $.ajax({
                url: "{{ route('check_item_has_serial_ajax') }}",
                method: 'GET',
                data: {
                    "_token": "{{ csrf_token() }}",
                    description: description
                },
                success: function (res) {
                    if (res.status === 'success') {
                        addSerialNoModal.modal('show');
                        addSerialNoModal.find('.modal-body').html(`
                            <input type="hidden" id="manual_s_item_code" value="${$('#item_code').val()}">
                            <input type="hidden" id="manual_item_qty" value="${qty}">
                            <input type="text" class="form-control text-center mb-3" id="manual_item_name" value="${description}" placeholder="Item Description" readonly>
                            ${[...Array(qty)].map((_, i) => `<input type="text" class="form-control mb-2" name="manual_serialNumber[]" placeholder="Serial Number ${i + 1}">`).join('')}
                        `);
                    }
                }
            });
        });

        $('#saveManualSerialNumbers').on('click', function () {
            let itemData = {
                sItemCode: $('#manual_s_item_code').val(),
                itemQuantity: $('#manual_item_qty').val(),
                itemName: $('#manual_item_name').val(),
                serialNumbers: $('input[name="manual_serialNumber[]"]').map(function () {
                    return $(this).val();
                }).get()
            };
            allSerialNumbersData.push(itemData);
            $('#itemSerialsField').val(JSON.stringify(allSerialNumbersData));
            addSerialNoModal.modal('hide');

            // ✅ Show alert after modal hides
            alert("Serial numbers saved successfully!");
        });


        // Auto Serial Number Modal
        $('#qty').on('keyup', function () {
            let qty = parseInt($(this).val());
            if (!qty || qty <= 0) return;
            let description = $('#item_description').val();

            $.ajax({
                url: "{{ route('check_item_has_AutoSerial_ajax') }}",
                method: 'GET',
                data: {
                    "_token": "{{ csrf_token() }}",
                    description: description
                },
                success: function (res) {
                    if (res.status === 'success') {
                        addAutoSerialNoModal.modal('show');

                        const now = new Date();
                        let invoiceNo = @json($maxInvoiceNo + 1);
                        const dateStr = now.getFullYear().toString() +
                            ('0' + (now.getMonth() + 1)).slice(-2) +
                            ('0' + now.getDate()).slice(-2) +
                            ('0' + now.getHours()).slice(-2) +
                            ('0' + now.getMinutes()).slice(-2) +
                            ('0' + now.getSeconds()).slice(-2);

                        const serialInputs = [...Array(qty)].map((_, i) => {
                            return `<input type="text" class="form-control mb-2" name="auto_serialNumber[]"
                                    value="SRL-${dateStr}-${i + 1}-${invoiceNo}" readonly>`;
                        }).join('');

                        addAutoSerialNoModal.find('.modal-body').html(`
                            <input type="hidden" id="auto_s_item_code" value="${$('#item_code').val()}">
                            <input type="hidden" id="auto_item_qty" value="${qty}">
                            <input type="text" class="form-control text-center mb-3" id="auto_item_name" value="${description}" placeholder="Item Description" readonly>
                            ${serialInputs}
                        `);
                    }
                }
            });
        });

        $('#saveAutoSerialNumbers').on('click', function () {
            let itemData = {
                sItemCode: $('#auto_s_item_code').val(),
                itemQuantity: $('#auto_item_qty').val(),
                itemName: $('#auto_item_name').val(),
                serialNumbers: $('input[name="auto_serialNumber[]"]').map(function () {
                    return $(this).val();
                }).get()
            };
            allSerialNumbersData.push(itemData);
            $('#itemSerialsField').val(JSON.stringify(allSerialNumbersData));
            addAutoSerialNoModal.modal('hide');

            // ✅ Show alert after modal hides
            alert("Serial numbers saved successfully!");
        });
    });
</script>

{{-- Item description show acording to item code --}}
<script>
        $(document).ready(function () {
            // Listen for changes in the code name fields
            $('#item_code').on('keyup', function () {
                setItemDetails();

            });
        });
</script>

{{-- script for submit recall purchase --}}
<script>
    $("#submit_recall").click(function () {
        var invoice_no = $('#invoice_no').val();
        var invoice_date = $('#invoice_date').val();
        var customer_nic = $('#customer_nic').val();
        var customer_name = $('#customer_name').val();
        var customer_phone = $('#customer_phone').val();
        var gross_amount = $('#total_amount').val();
        var discount = $('#paid_discount').val();
        var net_amount = $('#paid_amount').val();
        var inputs = $('#purchase_inputs').val();

        if (net_amount>0) {
            $.ajax({
                url: "{{ route('create_recall_purchase') }}",
                method: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    invoice_no: invoice_no,
                    invoice_date: invoice_date,
                    customer_nic: customer_nic,
                    customer_name: customer_name,
                    customer_phone: customer_phone,
                    gross_amount: gross_amount,
                    discount: discount,
                    net_amount: net_amount,
                    inputs: inputs
                },
                success: function (res) {
                    if (res.status == 'success') {

                        Command: toastr["success"]("Purchase Added ...!", "Success")
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

                        setTimeout(function() {
                            location.reload();
                        }, 1500);


                        }

                    if (res.status == 'not_found') {

                    }
                }
            });

        }else{
            alert("Please Enter Amount");
        }

    });

</script>

{{-- search and get invoice data using invoice _no  --}}
<script>
    $(document).ready(function () {
        // search receipt data
        $('#invoice_no').on('input', function (e) {
            e.preventDefault();
            setTimeout(function() {

            let search_receipt_no = $('#invoice_no').val();
            var search_string = $('#searchCustomer').val();

            if (search_receipt_no > 0) {
                // get t_pawn_details table data
                $.ajax({
                    url: "{{ route('find_purchase_invoice') }}",
                    method: 'GET',
                    data: {
                        search_receipt_no: search_receipt_no
                    },
                    success: function (res) {
                        $('#dynamicAdded').html(res);
                        if (res.status == 'not_found') {
                            $('#dynamicAdded').html(
                                `
                                <div  style ="text-align:center;">
                                <label class="text-danger mt-3 mb-3">Receipt Not Found ...!!</label>
                                </div>
                                `
                            );
                        }
                    }
                });

                // get t_pawn_sum table data
                $.ajax({
                    url: "{{ route('find_purchase_invoice_customer_data') }}",
                    method: 'GET',
                    data: {
                        search_receipt_no: search_receipt_no
                    },
                    success: function (response) {

                        if (response.status == 'success') {
                            let records = response.data;
                            let Invoice_no,Invoice_date, Customer_NIC, Customer_Name, Customer_Phone,
                                Customer_Address,Gross_Amount,Discount,Net_Amount;

                            records.forEach(record => {
                                Invoice_no = record.Invoice_no;
                                Invoice_date = record.Invoice_date;
                                Customer_NIC = record.Customer_NIC;
                                Customer_Name = record.Customer_Name;
                                Customer_Phone = record.Customer_Phone;
                                Customer_Address =record.Customer_Address;
                                Gross_Amount = record.Gross_Amount;
                                Discount = record.Discount;
                                Net_Amount = record.Net_Amount;

                                // Update the input values
                                $('#invoice_no').val(Invoice_no);
                                $('#invoice_date').val(Invoice_date);
                                $('#searchCustomer').val(Customer_NIC);
                                $('#customer_name').val(Customer_Name);
                                $('#customer_contact_1').val(Customer_Phone);
                                $('#total_amount').val(Gross_Amount);
                                $('#paid_discount').val(Discount);
                                $('#paid_amount').val(Net_Amount);

                                $('#green-total-unit-price').val(Gross_Amount);
                                $('#green-total-discount').val(Discount);
                                $('#green-total-value').val(Net_Amount);
                            });

                            $('.customer-data').html('');
                            $('.showCustomer').html('');
                            $('.showCustomer').html(
                                `
                                <div class="row mt-2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_name">Supplier Name : </label>
                                            <input class="form-control " type="text"
                                                value= "${Customer_Name}"
                                                id="customer_name" name="customer_name"  readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_nic">Code : </label>
                                            <input class="form-control " type="text"
                                                value= "${Customer_NIC}"
                                                id="customer_nic" name="customer_nic" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_phone">Supplier Tel : </label>
                                            <input class="form-control " type="text"
                                                value= "${Customer_Phone}"
                                                id="customer_phone" name="customer_phone" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_address">Address : </label>
                                            <input class="form-control " type="text"
                                                value= "${Customer_Address}"
                                                id="customer_address" name="customer_address" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `
                            );


                        } else if (response.status == 'not_found') {
                            $('.showCustomer').html('');
                            $('.customer-data').html(
                                `   <div  style ="text-align:center;">
                                    <label for="customer_name" class="text-danger">  Invoice Not Found ..!!
                                    </label>
                                    </div>
                                    `
                            );
                            $('.payment-history').html(``);
                        }
                    }
                });

                        } else {
                            $('#dynamicAdded').html('');
                            $('.customer-data').html('');
                            $('.payment-history').html('');
                            $('.showCustomer').html('');
                        }
                    }, 500);
        })
    });

</script>


{{-- select items using table row as a button --}}
<script>
    var table = document.getElementById("ItemTable");
    var rows = table.getElementsByTagName("tr");
    // Add a click event listener to each row
    for (var i = 0; i < rows.length; i++) {
        rows[i].addEventListener("click", function() {
            var item_code_add = this.cells[0].textContent;
            $('#item_code').val(item_code_add);
            setItemDetails();
            $("#searchItemModel").modal('hide');
            $('#item_name').val("");
            // $('#getItemCode').reset();
        });
    }
</script>

{{-- set item data function when inserting the item code or name --}}
<script>
    function setItemDetails() {
                // Listen for changes in the Weight and QTY fields
                let Item_code = $('#item_code').val();
                $.ajax({
                    url: "{{ route('show_select_item_description_ajax') }}",
                    method: 'GET',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        Item_code: Item_code
                    },
                    success: function (res) {
                        if (res.status == 'success') {

                            var itemDescriptionSelected = $('#item_description');
                            var itemUnit_price = $('#unit_price');
                            var item_s_code = $('#item_s_code');
                            // Change this to match your items select element
                            // Clear existing options
                            itemDescriptionSelected.empty();
                            itemUnit_price.empty();
                            item_s_code.empty();

                            $.each(res.data, function (index, item) {
                                itemDescriptionSelected.append($(
                                    '<option>', {
                                        value: item
                                            .Item_description,
                                        text: item
                                            .Item_description
                                    }));

                                itemUnit_price.val(item.purchasePrice);
                                item_s_code.val(item.Bar_code);
                            });

                        }
                    },
                    error: function (err) {
                        $('.errMsgContainer').html('');
                        let error = err.responseJSON;
                        $.each(error.errors, function (index, value) {
                            $('.errMsgContainer').append(
                                '<span class="text-danger">' +
                                value +
                                '<span>' + '<br>');
                        });
                    }
                });



    }
</script>

{{--  add item details to form when click Add button --}}
<script>
    $(document).ready(function () {
        // add item details to form
        $(document).on('click', '#add_item', function () {
            let id = $(this).data('id');
            let add_item_code = $(this).data('add_item_code');
            $('#item_code').val(add_item_code);
            setItemDetails();
        });
    });
</script>

{{-- search item data using item name --}}
<script>
    $('#item_name').on('keyup', function (e) {
                e.preventDefault();
                let search_string = $('#item_name').val();
                $.ajax({
                    url: "{{ route('search_items_purchase_price_ajax') }}",
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
</script>

{{--  get supplier data inserting Code --}}
<script>
        $(document).ready(function () {
            // search customer data
            $(document).on('keyup', function (e) {
                e.preventDefault();
                var search_string = $('#searchCustomer').val();
                // console.log(search_string);
                if (search_string != null) {
                    if (e.keyCode == 13 || e.keyCode == 10) {
                        $.ajax({
                            url: "{{ route('get_supplier_ajax') }}",
                            method: 'GET',
                            data: {
                                search_string: search_string
                            },
                            success: function (response) {
                                if (response.status == 'success') {
                                    let records = response.data;
                                    let Code,Name,Contact,Address;

                                    records.forEach(record => {
                                        Code = record.Code;
                                        Name = record.Name;
                                        Contact = record.Contact_1;
                                        Address = record.Address_1;

                                // Update the input values
                                $('#searchCustomer').val(Code);

                                $('.customer-data').html('');
                                $('.showCustomer').html('');

                            $('.showCustomer').html(
                                `
                                <div class="row mt-2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_name">Supplier Name : </label>
                                            <input class="form-control " type="text"
                                                value= "${Name}"
                                                id="customer_name" name="customer_name"  readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_nic">Code : </label>
                                            <input class="form-control " type="text"
                                                value= "${Code}"
                                                id="customer_nic" name="customer_nic" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_phone">Supplier Tel : </label>
                                            <input class="form-control " type="text"
                                                value= "${Contact}"
                                                id="customer_phone" name="customer_phone" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_address">Address : </label>
                                            <input class="form-control " type="text"
                                                value= "${Address}"
                                                id="customer_address" name="customer_address" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `);
                            });

                                }else if (response.status == 'not_found') {
                                    $('.showCustomer').html(
                                        `<div class="input-group">
                                            <p class="form-control text-danger text-center">
                                                Supplier Not Found ..!!
                                            </p>
                                         </div>`
                                    );
                                }
                            }
                        });
                    }
                }
            })
        });

</script>

{{-- add new supplier script --}}
<script>
        $(document).ready(function () {
            //add new customer
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
                    url: "{{ route('add_Suppliers_ajax') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        code: code,
                        title: title,
                        gender: gender,
                        name: name,
                        first_name: first_name,
                        middle_name: middle_name,
                        last_name: last_name,
                        address1: address1,
                        city1: city1,
                        address2: address2,
                        city2: city2,
                        contact1: contact1,
                        contact2: contact2,
                        email: email,
                        nic: nic,
                        driving_license: driving_license,
                        passport: passport,
                        other_identifications: other_identifications,
                        status: status
                    },

                    success: function (res) {
                        if (res.status == 'success') {
                            $("#addCustomerModel").modal('hide');
                            $('#addCustomer')[0].reset();
                            // $('.table').load(location.href+' .table');
                            Command: toastr["success"]("Customer Added ...!", "Success")
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
                            let data = res.telNo;
                            let cus_details = data[0];
                            let cus_tel = cus_details.Contact_1;
                            let cus_name = cus_details.First_name;

                            $('#searchCustomer').val(cus_tel);
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

{{-- clear form --}}
<script>
        $(document).ready(function () {
            //add new customer
            $(document).on('keyup', '#searchCustomer', function (e) {
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

{{-- add new row script --}}
<script type="text/javascript">
        var i = -1;
        let dataArray = [];
        let totalWeight = 0;
        let total_totalWeight = 0;
        let totalQty = 0;
        let totalValue = 0;
        let totalGross = 0;
        let totalDiscount = 0;

        $(".add-item").click(function () {
            i++;
            let customer_nic = $('#customer_nic').val();
            let invoice_no = $('#invoice_no').val();
            let invoice_date = $('#invoice_date').val();
            let item_code = $('#item_code').val();
            let item_s_code = $('#item_s_code').val();
            let item_description = $('#item_description').val();
            let qty = $('#qty').val();
            let unit_price = $('#unit_price').val();
            let saleprice = $('#saleprice').val();
            let Credit = $('#Credit').val();
            let discount = $('#discount').val();
            let discount_val = $('#discount_val').val();
            let net_value = $('#net_value').val();

            if (customer_nic == "" || invoice_no == "" || item_code == "" || qty == "" || unit_price == "" ||
                net_value == "") {
                alert("Please fill in all the fields.");
                return;
            } else {

                // Construct the newRowData object
                let newRowData = {
                    customer_nic: customer_nic,
                    invoice_no: invoice_no,
                    invoice_date: invoice_date,
                    item_code: item_code,
                    item_s_code: item_s_code,
                    item_description: item_description,
                    qty: qty,
                    unit_price: unit_price,
                    saleprice:saleprice,
                    Credit:Credit,
                    discount: discount,
                    discount_val: discount_val,
                    net_value: net_value,
                };

                // Add data to the array
                dataArray.push(newRowData);
                $("#dynamicAdded").prepend(
                    `
                   <tr>
    <td style="width:10%;">
        <input type="hidden" name="inputs[` + i + `][customer_nic]" value="` + customer_nic + `">
        <input type="hidden" name="inputs[` + i + `][invoice_no]" value="` + invoice_no + `">
        <input type="hidden" name="inputs[` + i + `][invoice_date]" value="` + invoice_date + `">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Item Code"
               id="dy_item_code" name="inputs[` + i + `][item_code]" value="` + item_code + `" readonly>
        <input class="form-control" type="hidden" style="text-align: center;" placeholder="Item Code"
               id="dy_item_s_code" name="inputs[` + i + `][item_s_code]" value="` + item_s_code + `" readonly>
    </td>
    <td>
        <select class="select form-control" style="text-align: center;" id="dy_item_description"
                name="inputs[` + i + `][item_description]" aria-hidden="true" readonly>
            <option value="">Please Select</option>
            @foreach($itemCode as $itemData)
                <option value="{{ $itemData->Item_description}}">{{ $itemData->Item_description}}</option>
            @endforeach
        </select>
    </td>

    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input class="form-control" type="text" style="text-align: center;" placeholder="Unit Price"
               id="dy_unit_price" name="inputs[` + i + `][unit_price]" value="` + unit_price + `" readonly>
    </td>

        <td>
        <input class="form-control" type="text" style="text-align: center;" placeholder="QTY"
               id="dy_qty" name="inputs[` + i + `][qty]" value="` + qty + `" readonly>
    </td>

    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input class="form-control" type="text" style="text-align: center;" placeholder="Net Value"
               id="dy_net_value" name="inputs[` + i + `][net_value]" value="` + net_value + `" readonly>
    </td>
    <td
        <button type="button" class="btn btn-outline-danger text-center shadow remove-input-field m-2">
            <i class="far fa-trash-alt me-1"></i> Delete
        </button>
    </td>
</tr>

                    `
                );
            }

            // set values in select option
            $('#dy_item_code').val(item_code);
            $('#dy_item_description').val(item_description);

            // totalWeight = parseFloat(totalWeight) + parseFloat(weight);
            totalGross = parseFloat(totalGross) + (parseFloat(unit_price)*qty);
            totalDiscount = parseInt(totalDiscount) + parseInt(discount_val);
            totalValue = parseFloat(totalValue) + parseFloat(net_value);
            setTotal();
            resetTableRow()
        });

        // Function to reset the input fields and dropdowns in the table row
        function resetTableRow() {
            // document.getElementById("item_code").value = "";
            document.getElementById("item_description").value = "";
            document.getElementById("qty").value = "";
            document.getElementById("unit_price").value = "0";
            document.getElementById("saleprice").value = "0";
            document.getElementById("Credit").value = "0";
            document.getElementById("discount").value = "0";
            document.getElementById("discount_val").value = "0";
            document.getElementById("net_value").value = "0";
        }

        // get total function
        function setTotal() {
            // $('.total-weight').html(
            //     `<p><strong>` + totalWeight + `</strong></p>`
            // );
            $('.total-unit-price').html(
                `<p><strong>` + totalGross + `</strong></p>`
            );
            $('.total-discount').html(
                `<p><strong>` + totalDiscount + `</strong></p>`
            );
            $('.total-value').html(
                `<p><strong>` + totalValue + `</strong></p>`
            );

            // let totalValueFormat = number_format(totalValue,2);
            document.getElementById("total_amount").value = totalGross;
            document.getElementById("paid_discount").value = totalDiscount;
            document.getElementById("paid_amount").value = totalValue;
            document.getElementById("credite_payment").value = totalValue;
        };

</script>

{{-- delete added item rows script --}}
<script>
        $(document).on('click', '.remove-input-field', function () {
            var row = $(this).parents('tr');
            var re_item_code = row.find('#dy_item_code');
            var re_item_description = row.find('#dy_item_description');
            var re_qty = row.find('#dy_qty');
            var re_unit_price = row.find('#dy_unit_price');
            var re_saleprice = row.find('#dy_saleprice');
            var re_Credit = row.find('#dy_Credit');
            var re_total_discount = row.find('#dy_discount_val');
            var re_net_value = row.find('#dy_net_value');

            // var re_weightValue = re_weight.val();
            var re_grossValue = re_unit_price.val()*re_qty.val();
            var re_discountValue = re_total_discount.val();
            var re_valueValue = re_net_value.val();

            // totalWeight = parseInt(totalWeight) - parseInt(re_weightValue);
            // total_totalWeight = parseInt(total_totalWeight) - parseInt(re_total_weightValue);
            totalGross = parseInt(totalGross) - parseInt(re_grossValue);
            totalDiscount = parseInt(totalDiscount) - parseInt(re_discountValue);
            totalValue = parseInt(totalValue) - parseInt(re_valueValue);

            // Remove data from the array
            var index = row.index();
            dataArray.splice(index, 1);

            $(this).parents('tr').remove();
            setTotal();
        });

</script>

{{-- change net value calculation when change the QTY field --}}
<script>
        $(document).on('keyup', '#qty', function () {
            let unit_price_value = $('#unit_price').val();
            let unit_qty = $('#qty').val();
            // let qty_decimal = Math.trunc(unit_qty);
            let net_value_row = unit_qty * unit_price_value;

            if (unit_qty != null) {
                $('#net_value').val(net_value_row);
            }
        })

</script>

{{-- change net value calculation when change the discount field --}}
<script>
        $(document).on('keyup', '#discount', function () {
            let unit_price_value = $('#unit_price').val();
            let unit_qty = $('#qty').val();
            // let qty_decimal = Math.trunc(unit_qty);
            let net_value_row = unit_qty * unit_price_value;

            let unit_discount = $('#discount').val();
            let discounted_value = net_value_row * (unit_discount / 100);
            let discounted_net_value = net_value_row - discounted_value;

            if (unit_discount > 0) {
                $('#discount_val').val(discounted_value);
                $('#net_value').val(discounted_net_value);
            } else {
                // $('#discount').val("0");
                $('#net_value').val(net_value_row);
            }
        })

</script>

{{-- change net value calculation when change the discount value field --}}
<script>
    $(document).on('keyup', '#discount_val', function () {
        let unit_price_value = $('#unit_price').val();
        let unit_qty = $('#qty').val();
        // let qty_decimal = Math.trunc(unit_qty);
        let net_value_row = unit_qty * unit_price_value;

        let unit_discount = $('#discount_val').val();
        let discounted_value = unit_discount;
        let discounted_net_value = net_value_row - discounted_value;

        if (unit_discount > 0) {
            // $('#discount_val').val(discounted_value);
            $('#net_value').val(discounted_net_value);
        } else {
            // $('#discount').val("0");
            $('#net_value').val(net_value_row);
        }
    })

</script>

<script>
    function calculateNetAmount() {
        let grossAmount = parseFloat($('#total_amount').val().replace(/,/g, '')) || 0;
        let discount = parseFloat($('#paid_discount').val().replace(/,/g, '')) || 0;

        let netAmount = grossAmount - discount;
        $('#paid_amount').val(netAmount.toFixed(2));
    }

    $(document).on('keyup', '#paid_discount, #total_amount', calculateNetAmount);
</script>


{{-- add new invoice script --}}
<script>
    $(document).ready(function () {
        $(document).on('click', '.add_invoice', function (e) {
                            e.preventDefault();
                            let invoice_no = $('#invoice_no').val();
                            let invoice_date = $('#invoice_date').val();
                            let customer_nic = $('#customer_nic').val();
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
                                    customer_nic: customer_nic,
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
                                        $('.table').load(location.href + ' .table');
                                        Command: toastr["success"]("Invoice Added ...!",
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
                                            '<span class="text-danger">' +
                                            value +
                                            '<span>' + '<br>');
                                    });
                                }
            })
        })
    });

</script>



<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/js/toastr.min.js"></script>

<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="assets/plugins/apexchart/chart-data.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
                    crossorigin="anonymous">
</script>

</body>

</html>
@endsection