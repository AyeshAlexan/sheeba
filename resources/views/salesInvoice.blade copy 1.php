@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Create Invoice</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <style>
        .item-description-wrapper {
            display: inline-block;
            max-width: 400px;
            white-space: normal;
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
                                <h4 class="card-title m-3">Create Invoice</h4>
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
                                        let confirmed = confirm("Are you sure? want to print receipt ?")
                                            if(confirmed){
                                                var pdfLink = "{{ Session::get('pdfLink') }}";
                                                var newWindow = window.open(pdfLink, '_blank');

                                                // Wait for the new window load, then trigger the print function
                                                newWindow.onload = function () {
                                                    newWindow.print();
                                                };
                                            }
                                    });

                                </script>
                                @endif


                                <form action="{{route('add_invoice')}}" method="post" id="sales_form">
                                    @csrf
                                    <div class="row ">
                                        <div class="row mb-1 form-group justify-content-between">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <div class="input-group-text" id="btnGroupAddon1">Customer NIC :
                                                        </div>
                                                        <select class="select form-control" name="customer_name" id="customer_name"
                                                                    aria-hidden="true">
                                                                    <option value="">Please Select</option>
                                                                    @foreach($Customerdetails as $Customerdata)
                                                                    <option value="{{ $Customerdata->First_name}}">
                                                                        {{ $Customerdata->First_name }}</option>
                                                                    @endforeach
                                                        </select>
            
                                                        <select class="select form-control" name="customer_nic" id="searchCustomer" aria-hidden="true" style="display: none;">
                                                        <option value="">Please Select</option>
                                                        @foreach($Customerdetails as $Customerdata)
                                                            <option value="{{ $Customerdata->Code }}">
                                                                {{ $Customerdata->Code }}
                                                            </option>
                                                        @endforeach
                                                        </select>
            
            
                                                        <div class="input-group-append">
                                                            <button type="button"
                                                                class="btn btn-primary btn-lg form-control "
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#selectCustomerModel">
                                                                <i class="fa fa-arrow-left" style="color: white"></i>
                                                            </button>
                                                        </div>
            
                                                        <div class="input-group-append">
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
                                                <div class="input-group-text" id="btnGroupAddon2">Invoice No :</div>
                                                <input type="text" id="invoice_no" name="invoice_no"
                                                    value="{{$maxInvoiceNo+1}}" class="form-control"
                                                    placeholder="Invoice Number:" aria-label="Invoice Number:"
                                                    aria-describedby="btnGroupAddon2">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <div class="input-group-text" id="btnGroupAddon2">Balance
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    :</div>
                                                <input type="text" id="customer_balance" name="customer_balance"
                                                    class="form-control" placeholder="Customer Balance:" aria-label="Customer Balance:"
                                                    aria-describedby="btnGroupAddon2">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                        </div>
                                        <div class="col">
                                            <div class="input-group">
                                                <div class="input-group-text" id="btnGroupAddon3">Date :
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div>
                                                <input type="date" id="invoice_date" name="invoice_date"
                                                    class="form-control" aria-label="Date:"
                                                    aria-describedby="btnGroupAddon3">
                                            </div>
                                      
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="row mt-1">
                                        <div class="col-md-5">

                                            <div class="input-group">
                                                <div class="input-group-text" id="btnGroupAddon10">Route &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                                </div>
                                                <select class="select form-control" name="Route" id="Route"
                                                aria-hidden="true">
                                                <option value="">Please Select</option>
                                                @foreach($area as $areaData)
                                                <option value="{{ $areaData->description}}">
                                                    {{ $areaData->description }}</option>
                                                @endforeach
                                            </select>
                                                <div class="input-group-append">
                                                    <button type="button" 
                                                        class="btn btn-success btn-lg form-control">
                                                        <i class="fas fa-plus" style="color: white"></i>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                        {{-- <div id="guarantor_1_data" class="col-md-4">
                                            <div class="input-group">
                                                <p class="form-control text-danger text-center">
                                                    Area Not Found ..!!
                                                </p>
                                            </div>
                                        </div> --}}
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-5">

                                            <div class="input-group">
                                                <div class="input-group-text" id="btnGroupAddon10"> Salesman &nbsp;:
                                                </div>
                                                <select class="select form-control" name="Salesmen" id="Salesmen"
                                                aria-hidden="true">
                                                <option value="">Please Select</option>
                                                @foreach($salesmandetails as $salesmandata)
                                                <option value="{{ $salesmandata->name}}">
                                                    {{ $salesmandata->name }}</option>
                                                @endforeach
                                            </select>
                                                <div class="input-group-append">
                                                    <button type="button"
                                                        class="btn btn-success btn-lg form-control "
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#addGuarantorModel">
                                                        <i class="fas fa-plus" style="color: white"></i>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                       
                                    </div>                                
                                    
                                    

                            </div>
                            
                            
                            {{--------------select Customer Model----------------- --}}
                            <div class="modal fade" id="selectCustomerModel" tabindex="-1" role="dialog"
                                aria-labelledby="selectCustomerModelLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title m-2" id="selectCustomerModelLabel"> Search Customer </h4>
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
                                                            {{-- -------------Customer Details Table -------------- --}}
                                                            <div class="card-body ">
                                                                <div class="table-responsive ">
                                                                    <div class="cus-table-data ">
                                                                        <table
                                                                            class="table table-bordered table-center table-hover mt-3"
                                                                            id="ItemTable">
                                                                            <thead>
                                                                                <tr class="table-secondary">
                                                                                    <th>Code</th>
                                                                                    <th>Name</th>
                                                                                    <th>Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($Customerdetails as $data)
                                                                                <tr>
                                                                                    <td>{{$data->Code }}</td>
                                                                                    <td>
                                                                                        <div class="item-description-wrapper">
                                                                                            {{$data->First_name}}
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <a href=""
                                                                                            class="btn btn-outline-info btn-sm shadow"
                                                                                            name="add_cus"
                                                                                            id="add_cus"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#selectCustomerModel"
                                                                                            data-id="{{$data->id}}"
                                                                                            data-cus_code="{{$data->Code}}"
                                                                                            data-cus_name="{{$data->Name}}">
                                                                                            Add <i class="fas fa-plus"></i>
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                        <div class="ml-4 mb-3 mt-1">
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

                            {{--------------Add serial Model----------------- --}}
                            <div class="modal fade" id="addSerialNoModal" tabindex="-1" role="dialog" aria-labelledby="addSerialNoModalLabel" aria-hidden="true">
                                <div class="modal-dialog model-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addSerialNoModalLabel">Add Serial Numbers</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Form to encapsulate input fields -->
                                            <form id="serialNumbersForm">
                                                <!-- Input fields go here -->

                                                <input type="text" class="form-control text-center" name="item_name" placeholder="Item Description"><br>
                                                <input type="text" class="form-control" name="serialNumber[]" placeholder="Serial Number 1">


                                                <!-- Repeat based on quantity -->

                                                {{-- Add item information to the modal --}}
                                                <input type="hidden" class="form-control" name="itemDescription[]" >
                                                <input type="hidden" class="form-control" name="itemQuantity[]" >
                                                <input type="hidden" name="itemSerials" id="itemSerialsField">

                                            </form>
                                        </div>
                                        <div class="modal-footer ">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                                aria-label="Close">Close</button>
                                            <button type="button" class="btn btn-success" id="saveSerialNumbers">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="itemSerials" id="itemSerialsField">


                            {{-- dynamicAdded table --}}
                            <table class="table table-bordered">
                                <thead class="thead-primary">
                                    <tr style="background: #3c899c">
                                        {{-- <th style="width:15%; text-align: center;">Category</th> --}}
                                        <th style="width:15%; text-align: center;">Item Code</th>
                                        <th style="width:20%; text-align: center;">Description</th>
                                        <th style="width:12%; text-align: center;">QTY</th>
                                        <th style="width:12%; text-align: center;">Unit Price</th>
                                        <th style="width:12%; text-align: center;">Free Issues</th>
                                        <th style="width:12%; text-align: center;">Discount (%)</th>
                                        <th style="width:12%; text-align: center;">Discount Val</th>
                                        <th style="width:13%; text-align: center;">Net Value</th>
                                        <th class="text-center" style="width:12%;">Action</th>
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
                                            <input class="form-control" type="text" placeholder="QTY" id="qty"
                                                name="qty">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" placeholder="Unit Price"
                                                id="unit_price" name="unit_price" value="0">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" placeholder="Free issues" id="Free_Issues"
                                                name="Free_Issues">
                                        </td>
                                        <td>
                                            <input class="form-control" type="number" placeholder="Discount"
                                                id="discount" name="discount" min="0" max="100" maxlength="6" value="0">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" placeholder="Discount Val"
                                                id="discount_val" name="discount_val" value="0">
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
                            <br>

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
                                                                            class="table table-bordered table-center table-hover mt-3"
                                                                            id="ItemTableCustomer">
                                                                            <thead>
                                                                                <tr class="table-secondary">
                                                                                    <th>Code</th>
                                                                                    <th>Name</th>
                                                                                    <th>Price</th>
                                                                                    <th>Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($itemDetails as $key=>$ItemData)
                                                                                <tr>
                                                                                    <td>{{$ItemData->Bar_code }}</td>
                                                                                    <td>
                                                                                        <div class="item-description-wrapper">
                                                                                            {{$ItemData->Item_description}}
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>{{$ItemData->saleprice}}</td>
                                                                                    <td>
                                                                                        <a href=""
                                                                                            class="btn btn-outline-info btn-sm shadow"
                                                                                            name="add_item"
                                                                                            id="add_item"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#searchItemModel"
                                                                                            data-id="{{$ItemData->id}}"
                                                                                            data-add_item_code="{{$ItemData->Item_code}}"
                                                                                            data-Item_description="{{$ItemData->Item_description}}">
                                                                                            Add <i class="fas fa-plus"></i>
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                        <div class="ml-4 mb-3 mt-1">
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


                            {{-- dynamicAdded table --}}
                            <table class="table table-bordered " id="dynamicAdded">
                            </table>

                            {{-- table footer for total calculations --}}
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="table-success">
                                        <td style="width:8%;"> </td>
                                        <td style="width:8%;"><strong>
                                                <p>TOTAL :</p>
                                            </strong> </td>
                                        <td style="width:5%;"> </td>
                                        <td style="width:5%;"></td>
                                        <td class="total-unit-price text-center" style="width:8%;">
                                            <p><strong>0.00</strong></p>
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
                                                aria-label="CASH PAY :" aria-describedby="btnGroupAddon4">
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
                                                aria-label="CREDITE :" aria-describedby="btnGroupAddon6">
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

                                <div class="row mt-3 justify-content-between">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-text">Serial Number</span>
                                            <textarea class="form-control" aria-label="With textarea" id="serial_numbers_text" name="serial_numbers_text"></textarea>
                                          </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon9">
                                                VAT %</div>
                                            <input type="text" style="font-weight:bold;" class="form-control"
                                                placeholder="VAT PRESENTAGE :" id="vat_presentage" name="vat_presentage"
                                                aria-label="VAT PRESENTAGE" aria-describedby="btnGroupAddon9">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon9">
                                                VAT AMOUNT :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                            <input type="text" style="font-weight:bold;" class="form-control"
                                                placeholder="VAT AMOUNT :" id="vat_amount" name="vat_amount"
                                                aria-label="VAT AMOUNT" aria-describedby="btnGroupAddon9" >
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3 justify-content-between">
                                    <div class="col-md-4">

                                    </div>
                                    <div class="col-md-3">

                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon9">
                                                AFTER VAT :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                            <input type="text" style="font-weight:bold;" class="form-control"
                                                placeholder="AFTER VAT AMOUNT :" id="after_vat_amount" name="after_vat_amount"
                                                aria-label="AFTER VAT AMOUNT :" aria-describedby="btnGroupAddon9" >
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- bottom buttons  --}}
                            <div class="row">
                                <div class="col-md-5"></div>
                                <div class="col-md-7">
                                    <br>
                                    <button type="submit" name="save"
                                        class="btn btn-outline-info btn-lg shadow">SAVE</button>
                                    <button type="button" name="print"
                                        class="btn btn-outline-primary print_invoice btn-lg shadow">PRINT</button>
                                    <button type="button" name="pawn_delete" id="pawn_delete"
                                        class="btn btn-outline-danger btn-lg shadow pawn_delete">DELETE</button>
                                    <button type="button" name="pawn_cancel" id="pawn_cancel"
                                        class="btn btn-outline-warning btn-lg shadow pawn_cancel">CANCEL</button>
                                    <button type="reset" name="reset"
                                        class="btn btn-outline-secondary btn-lg shadow">RESET</button>
                                </div>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footer')
    </div>


    {{--------------Add customer model----------------- --}}
<div class="modal fade" id="addCustomerModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title m-2" id="myLargeModalLabel"> Add Customers </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="errMsgContainer"></div>
                        <form action="" method="post" id="addCustomer">
                            @csrf
                                <div class="row">
                                    <div class="form-group">
                                        <div class="row">
                                      
                                            <div class="col">
                                                <label>Code <span
                                                    style="color:#FF0000; font-weight: bold; ">*</span>
                                                :</label>
                                            <input type="text" name="code"
                                                value=""
                                                id="code" class="form-control" placeholder="Customer Code"
                                                required>
                                            </div>

                                            <div class="col">
                                                <label>First Name <span
                                                        style="color:#FF0000; font-weight: bold; ">*</span>
                                                    :</label>
                                                <input type="text" name="first_name" id="first_name"
                                                    class="form-control" placeholder="First Name" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                        <textarea class="form-control" name="address1" id="address1"
                                            rows="3" placeholder="Address-1:" required></textarea> <br>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Customer Route  :</label>
                                        <select class="select form-control" name="address2" id="address2"
                                        aria-hidden="true" required>
                                        <option value="">Please Select</option>
                                        @foreach($Route as $areaData)
                                        <option value="{{ $areaData->description}}">
                                            {{ $areaData->description }}</option>
                                        @endforeach
                                    </select>
                                        <br>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                        <label>VAT Number <span
                                                style="color:#FF0000; font-weight: bold; ">*</span>
                                            :</label>
                                            <input type="text" name="passport" id="passport" class="form-control"
                                            placeholder="VAT Number" required>
                                    </div>

                               <div class="col-md-6">
                                        <label>Contact-1 <span
                                                style="color:#FF0000; font-weight: bold; ">*</span>
                                            :</label>
                                        <input type="text" name="contact1" id="contact1"
                                            class="form-control" placeholder="Contact 1" required>
                                    </div>

                                </div>


                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <label>NIC :</label>
                                        <input type="text" name="nic" id="nic" class="form-control"
                                            placeholder="NIC">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Email :</label>
                                        <input type="text" name="email" id="email" class="form-control"
                                            placeholder="Email">
                                    </div>
                                </div>


                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <label>Customer Cash Balance:</label>
                                        <input type="text" name="other_identifications"
                                            id="other_identifications" class="form-control"
                                            placeholder="Cash Balance ..">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-11">
                                                <label>Mark as Active or
                                                    Blacklisted <span
                                                        style="color:#FF0000; font-weight: bold; ">*</span>
                                                    :</label>
                                                <div class=" form-group">
                                                    <select class="select form-control" name="status"
                                                        id="status" aria-hidden="true" required>
                                                        <option value="">Please Select</option>
                                                        <option value="1" selected>
                                                            <span style="color:#16ec28;">
                                                                Active
                                                            </span>
                                                        </option>
                                                        <option value="0">
                                                            <span style="color:#df0c0c;">
                                                                Blacklist
                                                            </span>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Customer Route : </label>
                                            <textarea class="form-control" name="city1" id="city1" rows="1"
                                            placeholder=" Enter Your Route Details "></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="button"
                                        class="btn btn-success add_customer bg-success-light text-success me-2">Save</button>
                                    <button type="button" class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal" aria-label="Close">Close</button>
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
    
    <script>
    $(document).ready(function () {
        // Listen for changes in the customer_name field
        $('#customer_name').on('change', function () {
            let amount = $(this).val(); // Using $(this) to refer to the changed element
            $.ajax({
                url: "{{ route('get_customer') }}", // Make sure this is parsed by your server
                method: 'GET',
                data: {
                    "_token": "{{ csrf_token() }}",
                    amount: amount
                },
                success: function (res) {
                    if (res.status == 'success') {
                        var itemsSelected = $('#searchCustomer');
                        // Clear existing options
                        itemsSelected.empty();

                        $.each(res.data, function (index, item1) {
                            itemsSelected.append($('<option>', {
                                value: item1.Code,
                                text: item1.Code,
                            }));
                        });
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
            });
        });
    });
</script>


    <script>
        // Custom validation to ensure only one payment type is filled
        document.getElementById('sales_form').addEventListener('submit', function (event) {
            var cashPayment = document.getElementById('cash_payment').value.trim();
            var creditPayment = document.getElementById('credite_payment').value.trim();
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
    
    {{-- print invoice script --}}
    <script>
        $(document).ready(function () {
            $(document).on('click', '.print_invoice', function (e) {
                e.preventDefault();
                let sales_invoice_no = $('#invoice_no').val();

                if (confirm('Are you sure to print the invoice ?')) {
                    $.ajax({
                        url: "{{ route('print_sales_invoice_ajax') }}",
                        method: 'get',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            sales_invoice_no: sales_invoice_no
                        },
                        success: function (response) {
                                if (response.status == 'success') {
                                    // Open the PDF in a new tab for printing
                                    window.open(response.pdfUrl, '_blank');
                                } else {
                                    // Handle the error or show a message
                                    alert('Error printing invoice !!');
                                }
                        }
                    });
                }
            })
        });

    </script>   

{{-- Disable form auto-submit on Enter key press --}}
<script>
    document.getElementById('sales_form').addEventListener('keydown', function (event) {
       if (event.keyCode == 13 || event.keyCode == 10) {
           event.preventDefault();
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
        // Reference the modal
        const addSerialNoModal = $('#addSerialNoModal');
        let serialNumbersArray = [];
        let itemSerials = {};

        // Listen for changes in the qty
        $('#qty').on('keyup', function () {
            // Get the quantity value
            let qty = parseInt($('#qty').val());
            let description = $('#item_description').val();

                $.ajax({
                    url: "{{ route('check_and_get_item_has_serial_ajax') }}",
                    method: 'GET',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        description: description
                    },
                    success: function(res) {
                        if (res.status == 'success') {

                            let records = res.data;
                            let item_code, item_serial_no, item_description;

                            // Clear previous entries in the array
                            serialNumbersArray = [];

                            let itemDescription = $('#item_description').val();
                            let itemQuantity = $('#qty').val();
                            let sItemCode = $('#item_code').val();

                            // Show the modal
                            addSerialNoModal.modal('show');

                            // Clear previous input fields
                            addSerialNoModal.find('.modal-body').empty();

                            addSerialNoModal.find('.modal-body').append(
                                    `
                                    <input type="hidden" id="s_item_code" name="s_item_code">
                                    <input type="hidden" id="item_qty" name="item_qty">
                                    <input type="text" class="form-control text-center" id="item_name" name="item_name" placeholder="Item Description"><br>
                                    `
                                );

                            $('#s_item_code').val(sItemCode);
                            $('#item_qty').val(itemQuantity);
                            $('#item_name').val(itemDescription);

                            // Create new input fields based on quantity
                            for (let i = 1; i <= qty; i++) {
                                addSerialNoModal.find('.modal-body').append(
                                    `
                                    <div class="input-group">
                                        <div class="input-group-text" id="btnGroupAddon1">Serial Number ${i}&nbsp;&nbsp;:&nbsp;</div>
                                        <select name="serialNumber[]" class="form-select serialNumberSelect" >
                                            <option value="" selected disabled>Select Serial Number ${i}</option>
                                        </select>
                                    </div>
                                    `
                                );
                            }

                            let selectDropdown = document.querySelectorAll('.serialNumberSelect');

                            // Clear existing options
                            selectDropdown.innerHTML = '';
                            selectDropdown.forEach((selectDropdown, index) => {

                                records.forEach(record => {
                                    let option = document.createElement('option');
                                    option.value = record.item_serial_no; // Assuming item_serial_no is the value you want to assign
                                    option.text = record.item_serial_no; // Change this to the field you want to display

                                    selectDropdown.appendChild(option);
                                });
                            });
                        }
                    }
                });
        });

        // Handle save button click
        $('#saveSerialNumbers').on('click', function () {
            checkSelectedValues();

            // Check if there are duplicate function
            function checkSelectedValues() {
                let selectedValues = [];
                $('.serialNumberSelect').each(function () {
                    let selectedValue = $(this).val();
                    selectedValues.push(selectedValue);
                });

                // let selectDropdowns = document.querySelectorAll('.serialNumberSelect');

                // selectDropdowns.forEach((selectDropdown, index) => {
                //     let selectedValue = selectDropdown.value;
                //     selectedValues.push(selectedValue);
                // });

                // Check if there are duplicate values in the array
                let hasDuplicates = (new Set(selectedValues)).size !== selectedValues.length;

                if (hasDuplicates) {
                    alert('Selected values are not unique. Please select different values.');

                }else{
                    // Add the serial numbers for the current item to the object
                    let itemName = $('#item_name').val();
                    let itemQuantity = $('#item_qty').val();
                    let sItemCode = $('#s_item_code').val();

                    let serialNumbersArray = [];

                    // Get all serial numbers
                    let serialNumbers = [];
                    $('input[name="serialNumber[]"]').each(function () {
                        serialNumbers.push($(this).val());
                    });

                    console.log(serialNumbers);

                    let itemData = {
                        sItemCode: sItemCode,
                        itemQuantity: itemQuantity,
                        itemName: itemName,
                        serialNumbers: serialNumbers
                    };

                    // Add the item data to the main array
                    serialNumbersArray.push(itemData);

                    // Update the hidden field with the serialized array
                    $('#itemSerialsField').val(JSON.stringify(serialNumbersArray));

                    // itemSerials[itemDescription] = serialNumbersArray;
                    addSerialNoModal.modal('hide');

                }
            }
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

{{-- search and get invoice data using invoice _no  --}}
<script>
    $(document).ready(function () {
        // search receipt data
        $('#invoice_no').on('input', function (e) {
            e.preventDefault();
            let search_receipt_no = $('#invoice_no').val();
            var search_string = $('#searchCustomer').val();

            if (search_receipt_no > 0) {

                // get t_pawn_details table data
                $.ajax({
                    url: "{{ route('find_invoice') }}",
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
                    url: "{{ route('find_invoice_customer_data') }}",
                    method: 'GET',
                    data: {
                        search_receipt_no: search_receipt_no
                    },
                    success: function (response) {

                        if (response.status == 'success') {
                            let records = response.data;
                            let Invoice_no,Invoice_date, Customer_NIC, Customer_Name, Customer_Phone,
                                Customer_Address,Gross_Amount,Discount,Net_Amount,
                                vat_presentage,
                                vat_amount, after_vat_amount, 
                                Cash_Pay, Credite, Cheque,Route,Salesmen;

                            records.forEach(record => {
                                Invoice_no = record.Invoice_no;
                                Invoice_date = record.Invoice_date;
                                Customer_NIC = record.Customer_NIC;
                                Customer_Name = record.Customer_Name;
                                Customer_Phone = record.Customer_Phone;
                                Customer_Address =record.Customer_Address;
                                Gross_Amount = record.Gross_Amount;
                                Discount = record.Discount;
                                Salesmen = record.Salesmen;
                                Route = record.Route;
                                vat_presentage= record.vat_presentage;
                                vat_amount= record.vat_amount;
                                after_vat_amount= record.after_vat_amount;
                                Cash_Pay= record.Cash_Pay;
                                Credite= record.Credite;
                                Cheque= record.Cheque;
                                Net_Amount = record.Net_Amount;

                                // Update the input values
                                $('#invoice_no').val(Invoice_no);
                                $('#invoice_date').val(Invoice_date);
                                $('#searchCustomer').val(Customer_NIC);
                                $('#customer_name').val(Customer_Name);
                                $('#Route').val(Route);
                                $('#Salesmen').val(Salesmen);
                                $('#customer_contact_1').val(Customer_Phone);
                                $('#total_amount').val(Gross_Amount);
                                $('#paid_discount').val(Discount);
                                $('#paid_amount').val(Net_Amount);
                                $('#vat_presentage').val(vat_presentage);
                                $('#vat_amount').val(vat_amount);
                                $('#after_vat_amount').val(after_vat_amount);
                                $('#cash_payment').val(Cash_Pay);
                                $('#credite_payment').val(Credite);
                                $('#cheque_payment').val(Cheque);
                                $('#green-total-unit-price').val(Gross_Amount);
                                $('#green-total-discount').val(Discount);
                                $('#green-total-value').val(Net_Amount);


                            });

                            $('.showCustomer').html(
                                `
                                <div class="row mt-2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_name">Customer Name : </label>
                                            <input class="form-control " type="text"
                                                value= "${Customer_Name}"
                                                id="customer_name" name="customer_name"  readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_nic">NIC : </label>
                                            <input class="form-control " type="text"
                                                value= "${Customer_NIC}"
                                                id="customer_nic" name="customer_nic" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_phone">Customer Tel : </label>
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
                            $('.customer-data').html(
                                `
                                    <label for="customer_name" class="text-danger">  Customer Not Found ..!!
                                    </label>
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
                        }
        })
    });

</script>

{{-- select items using table row as a button --}}
{{-- <script>
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
            $('#getItemCode').reset();
        });
    }
</script> --}}

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
                                        value: item.Item_description,
                                        text: item.Item_description
                                    }));

                                itemUnit_price.val(item.saleprice);
                                item_s_code.val(item.Bar_code);
                            });
                        }
                    },
                    error: function (err) {
                        $('.errMsgContainer').html('');
                        let error = err.responseJSON;
                        $.each(error.errors, function (index, value) {
                            $('.errMsgContainer').append(
                                '<span class="text-danger">' + value + '<span>' + '<br>'
                            );
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

{{--  add customer to form when click Add button --}}
<script>
    $(document).ready(function () {
        // add customer to form
        $(document).on('click', '#add_cus', function () {
            let id = $(this).data('id');
            let cus_code = $(this).data('cus_code');
            let cus_name = $(this).data('cus_name');
            $('#customer_name').val(cus_name);
            $('#searchCustomer').val(cus_code);
            
            $.ajax({
                    url: "{{ route('get_customer_balance_ajax') }}",
                    method: 'GET',
                    data: {
                        cus_code: cus_code,
                    },
                    success: function (res) {
                        if (res.status == 'success') {

                            let balance = res.balance;

                            // Update the input values
                            $('#customer_balance').val(balance);


                        }else{
                            $('customer_balance').val(0);
                        }
                    }
            });
        });
    });
</script>

{{-- search customer using name --}}
<script>
    $('#cus_name').on('keyup', function (e) {
        e.preventDefault();
        let search_string = $('#cus_name').val();
        $.ajax({
            url: "{{ route('search_customer_invoice_ajax') }}",
            method: 'GET',
            data: {
                search_string: search_string
            },
                success: function (res) {
                    $('.cus-table-data').html(res);
                    if (res.status == 'not_found') {
                        $('.cus-table-data').html('<span class="text-danger">Nothing found...</span>');
                    }
                }
        });
    })
</script>


{{-- search item data using item name --}}
<script>
    $('#item_name').on('keyup', function (e) {
                e.preventDefault();
                let search_string = $('#item_name').val();
                $.ajax({
                    url: "{{ route('search_items_ajax') }}",
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

    {{--  get customer data inserting NIC --}}
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
                            url: "{{ route('get_customer_ajax') }}",
                            method: 'GET',
                            data: {
                                search_string: search_string
                            },
                            success: function (res) {
                                $('.showCustomer').html(res);

                                if (res.status == 'not_found') {
                                    $('.showCustomer').html(
                                        `<div class="input-group">
                                            <p class="form-control text-danger text-center">
                                                Customer Not Found ..!!
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

    {{-- add new customer script --}}
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
                    url: "{{ route('add_customer_ajax') }}",
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
            let Free_Issues = $('#Free_Issues').val();
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
                    Free_Issues:Free_Issues,
                    discount: discount,
                    discount_val: discount_val,
                    net_value: net_value,
                };

                // Add data to the array
                dataArray.push(newRowData);
                $("#dynamicAdded").prepend(
                    `
                    <tr>
                        <td style="width:15%;">
                            <input type="hidden" name="inputs[` + i + `][customer_nic]" value="` + customer_nic + `">
                            <input type="hidden" name="inputs[` + i + `][invoice_no]" value="` + invoice_no + `">
                            <input type="hidden" name="inputs[` + i + `][invoice_date]" value="` + invoice_date + `">
                            <input class="form-control" type="hidden" style="text-align: center;" placeholder="Item Code"
                                        id="dy_item_code" name="inputs[` + i + `][item_code]" value="` + item_code + `" readonly>
                            <input class="form-control" type="text" style="text-align: center;" placeholder="Item Code"
                                        id="dy_item_s_code" name="inputs[` + i + `][item_s_code]" value="` + item_s_code + `" readonly>
                        </td>

                        <td style="width:18%;">
                            <select class="select form-control" style="text-align: center;" id="dy_item_description"
                            name="inputs[` + i + `][item_description]" aria-hidden="true" readonly>
                                <option value="">Please Select</option>
                                @foreach($itemCode as $itemData)
                                    <option value="{{ $itemData->Item_description}}">{{ $itemData->Item_description}}</option>
                                @endforeach
                            </select>
                        </td>

                        <td style="width:10%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="QTY"
                            id="dy_qty" name="inputs[` + i + `][qty]" value="` + qty + `" readonly>
                        </td>

                        <td style="width:12%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="Unit Price"
                            id="dy_unit_price" name="inputs[` + i + `][unit_price]" value="` + unit_price + `" readonly>
                        </td>

                            <td style="width:10%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="Free_Issues"
                            id="dy_Free_Issues" name="inputs[` + i + `][Free_Issues]" value="` + Free_Issues + `" readonly>
                        </td>


                        <td style="width:12%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="Discount"
                            id="dy_discount" name="inputs[` + i + `][discount]" value="` + discount + `" readonly>
                        </td>

                        <td style="width:12%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="Discount Val"
                            id="dy_discount_val" name="inputs[` + i + `][discount_val]" value="` + discount_val + `" readonly>
                        </td>

                        <td style="width:13%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="Net Value"
                            id="dy_net_value" name="inputs[` + i + `][net_value]" value="` + net_value + `" readonly>
                        </td>

                        <td style="width:6%;">
                            <center>
                                <button type="button" class="btn btn-outline-danger text-center shadow remove-input-field m-2"> <i class="far fa-trash-alt me-1"></i> Delete </button>
                            </center>
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
            totalDiscount = parseFloat(totalDiscount) + parseFloat(discount_val);
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
            document.getElementById("Free_Issues").value = "0";
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

    {{-- script for add vat --}}
    <script>
        $(document).on('keyup', '#vat_presentage', function () {
            let vat_presentage = $('#vat_presentage').val();
            let before_vat_net = $('#paid_amount').val();
            // let qty_decimal = Math.trunc(unit_qty);
            let vat_amount = parseFloat((before_vat_net / 100) * vat_presentage);

            if (vat_amount != null) {
                $('#vat_amount').val(vat_amount);

                //adding to total value
                let after_vat_net = (parseFloat(before_vat_net) + vat_amount).toFixed(2);
                $('#after_vat_amount').val(after_vat_net);
            }
        })
    </script>

    {{-- delete added item rows script --}}
    <script>
        $(document).on('click', '.remove-input-field', function () {
            var row = $(this).parents('tr');
            var re_item_code = row.find('#dy_item_code');
            var re_item_description = row.find('#dy_item_description');
            var re_qty = row.find('#dy_qty');
            var re_unit_price = row.find('#dy_unit_price');
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

    {{-- change net value calculation when change the discount precentag field --}}
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
                                            '<span class="text-danger">' +
                                            value +
                                            '<span>' + '<br>');
                                    });
                                }
                            })
                        })
                    });

                </script>

                {{-- delete invoice script --}}
                <script>
                    $(document).ready(function () {
                        $(document).on('click', '.delete_invoice', function (e) {
                            e.preventDefault();
                            let invoice_id = $(this).data('id');

                            if (confirm('Are you sure to delete invoice ?')) {
                                $.ajax({
                                    url: "{{ route('delete_invoice_ajax') }}",
                                    method: 'post',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        invoice_id: invoice_id
                                    },
                                    success: function (res) {
                                        if (res.status == 'success') {
                                            $('.table').load(location.href + ' .table');
                                            Command: toastr["success"]("Invoice deleted...",
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
                                    }
                                });
                            }
                        })
                    });

                </script>

                
<script>
    $(document).ready(function() {
        $('#ItemTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthMenu": [100, 10, 15, 25],
            "language": {
                "paginate": {
                    "next": "Next", 
                    "previous": "Previous"
                }
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#ItemTableCustomer').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthMenu": [100, 10, 15, 25],
            "language": {
                "paginate": {
                    "next": "Next", 
                    "previous": "Previous"
                }
            }
        });
    });
</script>
        <!-- DataTables JS -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>


                <script src="assets/js/jquery-3.6.0.min.js"></script>
                <script src="assets/js/feather.min.js"></script>
                <script src="assets/js/toastr.min.js"></script>

                <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
                <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="assets/plugins/datatables/datatables.min.js"></script>
                <script src="assets/js/script.js"></script>
                <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
                <script src="assets/plugins/apexchart/chart-data.js"></script>
                {{-- <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script> --}}
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
                    crossorigin="anonymous">
                </script>

</body>

</html>
@endsection