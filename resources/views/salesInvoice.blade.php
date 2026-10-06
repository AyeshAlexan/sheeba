@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Create Invoice Update</title>
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
                                        var pdfLink = "{{ Session::get('pdfLink') }}";
                                        var newWindow = window.open(pdfLink, '_blank');

                                        // Wait for the new window load, then trigger the print function
                                        newWindow.onload = function () {
                                            newWindow.print();
                                        };
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
                                                        <div class="input-group-text" id="btnGroupAddon1">Customer Code :
                                                        </div>
                                                        <input type="text" id="searchCustomer" name="customer_nic"
                                                            class="form-control" placeholder="Enter Customer NIC"
                                                            required aria-label="Invoice Number:"
                                                            aria-describedby="btnGroupAddon1">
                                                        <div class="input-group-append">
                                                            
                                                            <div class="input-group-append">
                                                                <button type="button"
                                                                    class="btn btn-primary btn-lg form-control "
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#selectCustomerModel">
                                                                    <i class="fa fa-arrow-left" style="color: white"></i>
                                                                </button>
                                                            </div>
                                                            
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
                                                                                        @php
                                                    $userRole = auth()->user()->role; // Assuming you have 'role' field in your users table
                                                @endphp

                                                <input type="text" id="invoice_no" name="invoice_no"
                                                    value="{{ $maxInvoiceNo + 1 }}" 
                                                    class="form-control"
                                                    placeholder="Invoice Number:" 
                                                    aria-label="Invoice Number:" 
                                                    aria-describedby="btnGroupAddon2"
                                                    @if($userRole !== 'Admin') readonly @endif>


                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-8">
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

<!-- Add Serial Modal -->
<div class="modal fade" id="addSerialNoModal" tabindex="-1" role="dialog" aria-labelledby="addSerialNoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> <!-- Fixed class: modal-lg -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSerialNoModalLabel">Add Serial Numbers</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Placeholder: JS will populate content here -->
                <form id="serialNumbersForm"></form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="saveSerialNumbers">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Hidden field to hold serial data -->
<input type="hidden" name="itemSerials" id="itemSerialsField">

<script>
$(document).ready(function () {
    const addSerialNoModal = $('#addSerialNoModal');

    $('#qty').on('keyup', function () {
        let qty = parseInt($('#qty').val());
        let description = $('#item_description').val();
        let sItemCode = $('#item_code').val();

        if (!qty || qty <= 0 || !description) return;

        $.ajax({
            url: "{{ route('check_and_get_item_has_serial_ajax') }}",
            method: 'GET',
            data: {
                "_token": "{{ csrf_token() }}",
                description: description
            },
            success: function (res) {
                if (res.status === 'success') {
                    let records = res.data;

                    // Show modal and clear body
                    addSerialNoModal.modal('show');
                    let modalBody = addSerialNoModal.find('.modal-body');
                    modalBody.empty();

                    // Append hidden and item fields
                    modalBody.append(`
                        <form id="serialNumbersForm">
                            <input type="hidden" id="s_item_code" name=="s_item_code" value="${sItemCode}">
                            <input type="hidden" id="item_qty" name= value="${qty}">
                            <input type="text" class="form-control text-center" id="item_name" value="${description}" placeholder="Item Description"><br>
                        </form>
                    `);

                    // Append select dropdowns
                    for (let i = 1; i <= qty; i++) {
                        modalBody.find('form').append(`
                            <div class="input-group mb-2">
                                <div class="input-group-text">Serial Number ${i}</div>
                                <select name="serialNumber[]" class="form-select serialNumberSelect" required>
                                    <option value="" disabled selected>Select Serial Number ${i}</option>
                                    ${records.map(r => `<option value="${r.item_serial_no}">${r.item_serial_no}</option>`).join('')}
                                </select>
                            </div>
                        `);
                    }
                }
            }
        });
    });

    $('#saveSerialNumbers').on('click', function () {
        let selectedValues = [];

        $('select[name="serialNumber[]"]').each(function () {
            let val = $(this).val();
            if (!val) {
                alert("Please select all serial numbers before saving.");
                return false;
            }
            selectedValues.push(val);
        });

        if ((new Set(selectedValues)).size !== selectedValues.length) {
            alert("Selected serial numbers must be unique.");
            return;
        }

        // Collect item info
        let itemData = {
            sItemCode: $('#s_item_code').val(),
            itemQuantity: $('#item_qty').val(),
            itemName: $('#item_name').val(),
            serialNumbers: selectedValues
        };

        // Store in hidden input
        $('#itemSerialsField').val(JSON.stringify([itemData]));

        // Hide modal
        addSerialNoModal.modal('hide');
    });
});
</script>



                            {{-- dynamicAdded table --}}
                            <table class="table table-bordered">
                                <thead>
                                    <tr style="background-color: rgb(23, 126, 223);color:aliceblue">
                                        {{-- <th style="width:15%; text-align: center;">Category</th> --}}
                                        <th style="width:15%; text-align: center;">Item Code</th>
                                        <th style="width:20%; text-align: center;">Description</th>
                                        <th style="width:12%; text-align: center;">Unit Price</th>
                                        <th style="width:12%; text-align: center;">QTY</th>
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
                                                <input type="hidden" id="item_code" name="item_code" class="form-control" placeholder="item Code" required>
                                                <input type="text" id="item_s_code" name="item_s_code" class="form-control" placeholder="item Code" required>
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-success btn-lg form-control" data-bs-toggle="modal" data-bs-target="#searchItemModel">
                                                        <i class="fas fa-plus" style="color: white"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <select class="select form-control" id="item_description" name="item_description">
                                                <option value="">Select an item</option>
                                                @foreach( $itemCode as $itemData)
                                                    <option value="{{ $itemData->Item_description }}">{{ $itemData->Item_description }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" placeholder="Unit Price" id="unit_price" name="unit_price" value="0">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" placeholder="QTY" id="qty" name="qty">
                                        </td>
                                        <td>
                                            <input class="form-control" type="number" placeholder="Discount" id="discount" name="discount" min="0" max="100" maxlength="6" value="0">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" placeholder="Discount Val" id="discount_val" name="discount_val" value="0">
                                        </td>
                                         <td>
                                            <input class="form-control" type="text" placeholder="Net Value" id="net_value" name="net_value" value="0">
                                            <input class="form-control" type="hidden" placeholder="Last Price" id="last_price" name="last_price" value="0" >
                                            <input class="form-control" type="hidden" placeholder="Discount After Value" id="DiscountAfterValue" name="DiscountAfterValue" value="0" >
                                            <input type="hidden" id="user_role" value="{{ Auth::user()->role }}">

                                        </td>
                                        <td class="text-center">
                                            <button type="button" name="add" class="btn add-item btn-outline-info btn-lg shadow" id="add_item_btn"> Add <i class="fas fa-plus"></i></button>
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
                                       <div class="card-body ">
                                                                <div class="table-responsive ">
                                                                    <div class="table-data ">
                                                                        <table
                                                                            class="table table-bordered table-center table-hover mt-3"
                                                                            id="ItemTable">
                                                                            <thead>
                                                                                <tr class="table-secondary">
                                                                                    <th style="text-align: center">Code</th>
                                                                                    <th style="text-align: center">Name</th>
                                                                                    <th style="text-align: center">Sales Price</th>
                                                                                    <th style="text-align: center">Last Price</th>
                                                                                    <th style="text-align: center">Stock In Hand</th>
                                                                                    <th style="text-align: center">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                           <tbody>
                                                                                @foreach ($itemDetails as $key=>$ItemData)
                                                                                <tr >
                                                                                    <!--<td>-->
                                                                                    <!--    @if ($ItemData->QTY <= 0)-->
                                                                                            <!-- You can display a placeholder or keep it empty based on your needs -->
                                                                                    <!--        <span style="color: red;">Out of stock - {{$ItemData->Item_code}} </span>-->
                                                                                    <!--    @else-->
                                                                                    <!--        {{$ItemData->Item_code}}-->
                                                                                    <!--    @endif-->
                                                                                    <!--</td>-->
                                                                                      <td>
                                                                                        <div class="item-description-wrapper">
                                                                                            {{$ItemData->Item_code}}
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="item-description-wrapper">
                                                                                            {{$ItemData->Item_description}}
                                                                                        </div>
                                                                                    </td>
                                                                                    <td style="text-align: right">{{$ItemData->saleprice}}</td>
                                                                                    <td style="text-align: right;">
                                                                                        {{$ItemData->Credit}}
                                                                                    </td>
                                                                                    <td style="text-align: right; color: {{ $ItemData->QTY < 0 ? 'red' : 'black' }}">
                                                                                        {{$ItemData->QTY}}
                                                                                    </td>
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

                                                                        <script>
                                                                            $(document).ready(function() {
                                                                                $('#ItemTable').DataTable({
                                                                                    "lengthMenu": [ [100, 10, 25,1000], [100, 10, 25,1000] ]
                                                                                });
                                                                            });
                                                                        </script>


        
                                                                        <div class="ml-4 mb-3 mt-1">
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
                                    <tr class="table-info">
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
                                                aria-label="CASH PAY :" aria-describedby="btnGroupAddon4" required>
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
                                            <input type="number" style="font-weight:bold;" class="form-control"
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
                        <div class="input-group-text fw-bold" id="btnGroupAddon8">
                            BANK :
                        </div>
                        <input type="number"  class="form-control fw-bold"
                            placeholder="BANK :" id="BankCash1" name="BankCash" id="BankCash"
                            aria-label="BANK :" aria-describedby="btnGroupAddon8">
                    </div>
                </div>
                <div class="col-md-5"></div>
            </div>
            <p>
            &nbsp;&nbsp; 
            </p>
            <div class="row">
                <div class="col-md-4">
                <div class="input-group">
                        <div class="input-group-text fw-bold" id="btnGroupAddon10">
                            NOTE :
                        </div>
                          <textarea class="form-control" id="exampleFormControlTextarea1"  placeholder="NOTE :"
                         id="Note" name="Note" aria-label="NOTE :"  rows="3"></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <div class="input-group-text fw-bold" id="btnGroupAddon11">
                            WARRANTY PERIOD :
                        </div>
                        <input type="date" class="form-control fw-bold" placeholder="WARRANTY PERIOD :" id="Waranty_period" name="Waranty_period" aria-label="WARRANTY PERIOD :" aria-describedby="btnGroupAddon11">
                    </div>
                </div>
            </div>

                             
                     
                                <div class="row mt-3 justify-content-between shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                                    <div class="col-md-12">
                                        <div id="multi_cheques" class="mt-2" style="display: none;" >
                                            <div class="table-responsive">
                                                <div class="table-data">
                                                    <h5 style="color: rgb(224, 55, 13);font-weight: bold">Cheque Payment</h5>
                                                    <br>

                                                    
                                                    <table class="table table-bordered table-center table-hover" id="multi_cheques_table">
                                                        <thead >
                                                            <tr style="background-color: rgb(23, 126, 223)">
                                                                <th class="text-center">Cheque Date</th>
                                                                <th class="text-center">Bank Name</th>
                                                                <th class="text-center">Branch</th>
                                                                <th class="text-center">Customer Name</th>
                                                                <th class="text-center">Cheque No</th>
                                                                <th class="text-center">Ammount</th>
                                                                <th class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input type="date" id="cheque_date" name="cheque_date" class="form-control"
                                                                    placeholder="Account No"  aria-label="account_no" aria-describedby="btnGroupAddon1">
                                                                </td>

                                                                <td>
                                                                    <select class="select form-control " id="bank_name"
                                                                        name="bank_name" aria-hidden="true">
                                                                        <option value="">Select an item</option>
                                                                        @foreach( $bank as $data)
                                                                        <option value="{{ $data->description }}">
                                                                            {{ $data->description}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="select form-control " id="bank_branch"
                                                                        name="bank_branch" aria-hidden="true">
                                                                        <option value="">Select an item</option>
                                                                        @foreach( $bank_branch as $data)
                                                                        <option value="{{ $data->category_description }}">
                                                                            {{ $data->category_description}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select class="select form-control " id="account_no"
                                                                    name="account_no" aria-hidden="true">
                                                                    <option value="">Select an item</option>
                                                                    @foreach ($customerDetails as $data)
                                                                    <option value="{{ $data->First_name }}">
                                                                        {{ $data->First_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="cheque_no" name="cheque_no" class="form-control" placeholder="Cheque No"
                                                                     aria-label="cheque_no" aria-describedby="btnGroupAddon1">
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="cheque_ammount" name="cheque_ammount" class="form-control"
                                                                        placeholder=" Ammount"  aria-label="cheque_ammount"
                                                                        aria-describedby="btnGroupAddon1">
                                                                </td>
                                                                <td>
                                                                    <button type="button" name="add" id="add-Cheque-Payment"
                                                                        class="btn add-item  btn-outline-info btn-sm shadow"> Add
                                                                        <i class="fas fa-plus"></i></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            {{-- bottom buttons  --}}
                            <div class="row">
                                <div class="col-md-1">
                                    <input type="hidden" name="cheques_data" id="cheques_data">
                                    <input type="hidden" name="total_cheque_amount" id="total_cheque_amount">
                                </div>
                                <div class="col-md-10">
                                    <br>
                                    @if(\App\Support\Permissions::canDo('sales', 'add'))
                                    <button type="submit" name="save" class="btn btn-success btn-lg shadow">
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                    @endif

                                    @if(\App\Support\Permissions::canDo('sales', 'print'))
                                    <button type="button" name="print" class="btn btn-info btn-lg print_invoice shadow">
                                        <i class="fas fa-print"></i> Print
                                    </button>
                                    @endif

                                    @if(\App\Support\Permissions::canDo('sales', 'delete'))
                                        <button type="button" class="btn btn-danger btn-lg shadow" id="deleteInvoice">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    @endif

                                    @if(\App\Support\Permissions::canDo('sales', 'edit'))
                                        <button type="button" id="saveInvoiceBtn" class="btn btn-primary btn-lg shadow">
                                            <i class="fas fa-edit"></i> Update
                                        </button>
                                    @endif

                                    <button type="button" name="pawn_cancel" id="pawn_cancel" class="btn btn-warning btn-lg shadow">
                                        <i class="fas fa-times-circle"></i> Cancel
                                    </button>

                                    <button type="reset" name="reset" class="btn btn-secondary btn-lg shadow">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
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
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label>Code <span
                                                                                style="color:#FF0000; font-weight: bold; ">*</span>
                                                                            :</label>
                                                                        <input type="text" name="code"
                                                                            value=""
                                                                            id="code" class="form-control" placeholder="Customer Code"
                                                                            required>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label>Title <span
                                                                                style="color:#FF0000; font-weight: bold; ">*</span>
                                                                            :</label>
                                                                        <div class=" form-group">
                                                                            <select class="select form-control" name="title" id="title"
                                                                                aria-hidden="true" required>
                                                                                <option value="">Please Select
                                                                                </option>
                                                                                <option value="Mr.">Mr.</option>
                                                                                <option value="Mrs.">Mrs.
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label>Gender <span
                                                                                style="color:#FF0000; font-weight: bold; ">*</span>
                                                                            :</label>
                                                                        <div class=" form-group">
                                                                            <select class="select form-control" name="gender" id="gender"
                                                                                aria-hidden="true" required>
                                                                                <option value="">Please Select
                                                                                </option>
                                                                                <option value="Male">Male
                                                                                </option>
                                                                                <option value="Female">Female
                                                                                </option>
                                                                                <option value="Other">Other
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="row">
                                                                    <div class="form-group">
                                                                        <div class="row">
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
                                                                    <div class="col-md-12">
                                                                        <textarea class="form-control" name="address1" id="address1"
                                                                            rows="3" placeholder="Address-1:" required></textarea> <br>
                                                                    </div>
                                                                    <!--<div class="col-md-6">-->
                                                                    <!--    <textarea class="form-control" name="address2" id="address2"-->
                                                                    <!--        rows="3" placeholder="Address-2:"></textarea>-->
                                                                    <!--    <br>-->
                                                                    <!--    <textarea class="form-control" name="city2" id="city2" rows="1"-->
                                                                    <!--        placeholder="City-2 :"></textarea>-->
                                                                    <!--</div>-->
                                                                </div>

                                                                <div class="row mt-4">
                                                                    <div class="col-md-6">
                                                                        <label>Contact-1 <span
                                                                                style="color:#FF0000; font-weight: bold; ">*</span>
                                                                            :</label>
                                                                        <input type="text" name="contact1" id="contact1"
                                                                            class="form-control" placeholder="Contact 1" required>
                                                                    </div>
                                                                           <div class="col-md-6">
                                                                        <label>NIC :</label>
                                                                        <input type="text" name="nic" id="nic" class="form-control"
                                                                            placeholder="NIC" required>
                                                                    </div>
                                                              
                                                                </div>


                                                                <!--<div class="row mt-4">-->
                                                                <!--   <div class="col-md-6">-->
                                                                <!--        <label>Contact-2 :</label>-->
                                                                <!--        <input type="text" name="contact2" id="contact2"-->
                                                                <!--            class="form-control" placeholder="Contact 2">-->
                                                                <!--    </div>-->
                                                                <!--    <div class="col-md-6">-->
                                                                <!--        <label>Email :</label>-->
                                                                <!--        <input type="text" name="email" id="email" class="form-control"-->
                                                                <!--            placeholder="Email">-->
                                                                <!--    </div>-->
                                                                <!--</div>-->


                                                                <!--<div class="row mt-4">-->
                                                                <!--    <div class="col-md-6">-->
                                                                <!--        <label>Driving License :</label>-->
                                                                <!--        <input type="text" name="driving_license" id="driving_license"-->
                                                                <!--            class="form-control" placeholder="Driving Licence">-->
                                                                <!--    </div>-->
                                                                <!--    <div class="col-md-6">-->
                                                                <!--        <label>Passport :</label>-->
                                                                <!--        <input type="text" name="passport" id="passport"-->
                                                                <!--            class="form-control" placeholder="Passport">-->
                                                                <!--    </div>-->

                                                                <!--</div>-->


                                                                <div class="row mt-4">
                                                                    <!--<div class="col-md-6">-->
                                                                    <!--    <label>Other Identifications :</label>-->
                                                                    <!--    <input type="text" name="other_identifications"-->
                                                                    <!--        id="other_identifications" class="form-control"-->
                                                                    <!--        placeholder="Other Identifications">-->
                                                                    <!--</div>-->
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
                                                      <form action="" method="post" id="getItemCode">
                                                          @csrf
                                                          <div class="col"></div>
                                                          <div class="col-md-7">
                                                          </div>

                                                          <div class="col"></div>
                                                      </form>

                                                      {{-- -------------Customer Details Table -------------- --}}
                                                      <div class="card-body ">
                                                          <div class="table-responsive ">
                                                              <div class="cus-table-data ">
                                                                <table class="table table-bordered table-center table-hover mt-3" id="ItemTableCus">
                                                                    <thead>
                                                                        <tr class="table-secondary">
                                                                            <th>Code</th>
                                                                            <th>Name</th>
                                                                            <th>Address</th>
                                                                            <th>Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($customerDetails as $data)
                                                                        <tr>
                                                                            <td>{{ $data->Code }}</td>
                                                                            <td>
                                                                                <div class="item-description-wrapper">
                                                                                    {{ $data->First_name }}
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="item-description-wrapper">
                                                                                    {{ $data->Address_1 }}
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <a href="#" onclick="fillCustomerCode('{{ $data->Code }}')" class="btn btn-outline-info btn-sm shadow" name="add_cus" id="add_cus" data-bs-toggle="modal" data-bs-target="#selectCustomerModel" data-id="{{ $data->id }}" data-cus_code="{{ $data->Code }}" data-cus_name="{{ $data->Address_1 }}">
                                                                                    Add <i class="fas fa-plus"></i>
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            
                                                            
                                                                <script>
                                                                    $(document).ready(function() {
                                                                        $('#ItemTableCus').DataTable({
                                                                            "lengthMenu": [ [5, 10, 25], [5, 10, 25] ]
                                                                        });
                                                                    });
                                                                </script>

                                                                  <script>
                                                                      function fillCustomerCode(code) {
                                                                        document.getElementById('searchCustomer').value = code;
                                                                      }
                                                                  </script>

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
    // Custom validation to ensure only one payment type is filled
    document.getElementById('sales_form').addEventListener('submit', function (event) {
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
    
    {{-- print invoice script --}}
    <script>
        $(document).ready(function () {
            $(document).on('click', '.print_invoice', function (e) {
                e.preventDefault();
                let invoice_no = $('#invoice_no').val();
    
                if (confirm('Are you sure to print the invoice ?')) {
                    $.ajax({
                        url: "{{ route('print_sales_invoice_ajax') }}",
                        method: 'get',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            invoice_no: invoice_no
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



{{-- Item description show acording to item code --}}
<script>
    $(document).ready(function () {
        // Listen for changes in the code name fields
        $('#item_code').on('keyup', function () {
            setItemDetails();

        });
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
            $('#getItemCode').reset();
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
                            var last_price = $('#last_price');
                            // Change this to match your items select element
                            // Clear existing options
                            itemDescriptionSelected.empty();
                            itemUnit_price.empty();
                            item_s_code.empty();
                            last_price.empty();

                            $.each(res.data, function (index, item) {
                                itemDescriptionSelected.append($(
                                    '<option>', {
                                        value: item.Item_description,
                                        text: item.Item_description
                                    }));

                                itemUnit_price.val(item.saleprice);
                                item_s_code.val(item.Bar_code);
                                last_price.val(item.Credit);
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
                     <td style="width:18%;">
                                    <input type="text" class="form-control" style="text-align: center;"
                                           id="dy_item_description"
                                           name="inputs[` + i + `][item_description]"
                                           readonly>
                                </td>
                        </td>

                       

                        <td style="width:12%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="Unit Price"
                            id="dy_unit_price" name="inputs[` + i + `][unit_price]" value="` + unit_price + `" readonly>
                        </td>
                        
                         <td style="width:10%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="QTY"
                            id="dy_qty" name="inputs[` + i + `][qty]" value="` + qty + `" readonly>
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

<script>
    $(document).ready(function () {
        function calculateValues() {
            let unit_price = parseFloat($('#unit_price').val()) || 0;
            let qty = parseFloat($('#qty').val()) || 0;
            let discountPercent = parseFloat($('#discount').val()) || 0;
            let discountValue = parseFloat($('#discount_val').val()) || 0;
            
            // Calculate net value
            let net_value = qty * unit_price;
            let DiscountAfterValue = unit_price;

            // Apply discount percentage if present
            if (discountPercent > 0) {
                discountValue = (net_value * discountPercent) / 100;
                $('#discount_val').val(discountValue.toFixed(2));
            }

            // Apply discount value if provided
            if (discountValue > 0) {
                net_value -= discountValue;
                DiscountAfterValue = net_value / qty;
            }

            // Update fields
            $('#net_value').val(net_value.toFixed(2));
            $('#DiscountAfterValue').val(DiscountAfterValue.toFixed(2));

            // Check Add button condition
            checkPriceCondition();
        }

        function checkPriceCondition() {
            let lastPrice = parseFloat($("#last_price").val()) || 0;
            let discountAfterValue = parseFloat($("#DiscountAfterValue").val()) || 0;
            let userRole = $("#user_role").val(); // Get role from hidden input

            if (userRole === "Admin") {
                $("#add_item_btn").prop("disabled", false);
            } else {
                $("#add_item_btn").prop("disabled", lastPrice > discountAfterValue);
            }
        }

        // Trigger calculations on input fields
        $(document).on("input", "#qty, #unit_price, #discount, #discount_val, #last_price, #DiscountAfterValue", calculateValues);

        // Initial calculations
        calculateValues();
    });
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

          
                {{-- Here add only one payment  --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cashPayment = document.getElementById('cash_payment');
        const creditePayment = document.getElementById('credite_payment');
        const chequePayment = document.getElementById('cheque_payment');

        // Function to disable other inputs when one is filled
        function disableOtherInputs() {
            if (cashPayment.value !== "") {
                creditePayment.disabled = false;
                chequePayment.disabled = false;
            } else if (creditePayment.value !== "") {
                cashPayment.disabled = false;
                chequePayment.disabled = false;
            } else if (chequePayment.value !== "") {
                cashPayment.disabled = false;
                creditePayment.disabled = false;
            } else {
                // Enable all if none are filled
                cashPayment.disabled = false;
                creditePayment.disabled = false;
                chequePayment.disabled = false;
            }
        }

        // Event listeners for input changes
        cashPayment.addEventListener('input', disableOtherInputs);
        creditePayment.addEventListener('input', disableOtherInputs);
        chequePayment.addEventListener('input', disableOtherInputs);
    });
</script>

<script>
    $(document).ready(function() {
        // Listen for any input on the cheque_payment field
        $('#cheque_payment').on('input', function() {
            // Check if the input value is not empty
            if ($(this).val().trim() !== '') {
                // Show the table form if there is input
                $('#multi_cheques').show();
            } else {
                // Hide the table form if the input is empty
                $('#multi_cheques').hide();
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        let dataArray = [];
        let totalValue = 0;

        $("#add-Cheque-Payment").click(function () {
            let bank_name = $('#bank_name').val();
            let bank_branch = $('#bank_branch').val();
            let account_no = $('#account_no').val();
            let cheque_no = $('#cheque_no').val();
            let cheque_date = $('#cheque_date').val();
            let cheque_ammount = parseFloat($('#cheque_ammount').val());

            if (!bank_name || !bank_branch || !account_no || !cheque_no || isNaN(cheque_ammount)) {
                alert("Please fill in all fields correctly.");
                return;
            }

            let newRowDatacheque = {
                cheque_date:cheque_date,
                bank_name: bank_name,
                bank_branch: bank_branch,
                account_no: account_no,
                cheque_no: cheque_no,
                cheque_ammount: cheque_ammount,
            };

            dataArray.push(newRowDatacheque);

            $("#multi_cheques_table tbody").append(
                `<tr>
                    <td>${cheque_date}</td>
                    <td>${bank_name}</td>
                    <td>${bank_branch}</td>
                    <td>${account_no}</td>
                    <td>${cheque_no}</td>
                    <td>${cheque_ammount.toFixed(2)}</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-input-field">Delete</button></td>
                </tr>`
            );

            totalValue += cheque_ammount;
            updateTotal();
            resetInputFields();
        });

        function resetInputFields() {
            $('#cheque_date').val('');
            $('#bank_name').val('');
            $('#bank_branch').val('');
            $('#account_no').val('');
            $('#cheque_no').val('');
            $('#cheque_ammount').val('');
        }

        function updateTotal() {
            $('#cheque_payment').val(totalValue.toFixed(2));
            $('#total_cheque_amount').val(totalValue.toFixed(2));
        }

        $(document).on('click', '.remove-input-field', function () {
            let row = $(this).closest('tr');
            let amount = parseFloat(row.find('td:eq(4)').text());

            totalValue -= amount;
            updateTotal();

            let index = row.index();
            dataArray.splice(index, 1);
            row.remove();
        });

        // Handle form submission
        $("#sales_form").submit(function (e) {

            // Attach serialized cheque data
            $("#cheques_data").val(JSON.stringify(dataArray));
        });
    });
</script>


{{-- search and get invoice data using invoice _no  --}}
<script>
    $(document).ready(function () {
        $('#invoice_no').on('input', function (e) {
            e.preventDefault();
            setTimeout(function () {
                let search_receipt_no = $('#invoice_no').val();

                if (search_receipt_no > 0) {
                    // Fetch t_pawn_details table data
                    $.ajax({
                        url: "{{ route('find_sales_details_invoice') }}",
                        method: 'GET',
                        data: { search_receipt_no },
                        success: function (res) {
                            if (res.status === 'success') {
                                $('#dynamicAdded').html(res.data.map(data => `
                                    <tr>
                                        <td style="width:15%;"><input type="text" name="Item_code" class="form-control" value="${data.Item_code}" readonly></td>
                                        <td style="width:18%;"><input type="text" name="Item_description" class="form-control" value="${data.Item_description}" readonly></td>
                                        <td style="width:12%;"><input type="text" name="Unit_price" class="form-control" value="${data.Unit_price}" readonly></td>
                                        <td style="width:10%;"><input type="text" name="QTY" class="form-control QTY" value="${data.QTY}"></td>
                                         <td style="width:12%;"><input type="text" name="DiscountPercentage" class="form-control DiscountPercentage" value="${data.DiscountPercentage || 0}"></td>
                                        <td style="width:12%;"><input type="text" name="Discount" class="form-control Discount" value="${data.Discount}"></td>
                                        <td style="width:12%;"><input type="text" name="Net_value" class="form-control Net_value" value="${data.Net_value}" readonly></td>
                                        <td style="width:13%;">
                                            <button type="button" class="btn btn-outline-success text-center edit-row"><i class="far fa-edit me-1"></i> Edit</button>
                                            <button type="button" class="btn btn-outline-danger text-center remove-row"><i class="far fa-trash-alt me-1"></i> Delete</button>
                                        </td>
                                    </tr>
                                `).join(''));
                            } else {
                                $('#dynamicAdded').html(`<div class="text-danger text-center">Receipt Not Found ...!!</div>`);
                            }
                        },
                    });

                    // Fetch customer data
                    $.ajax({
                        url: "{{ route('find_sales_invoice_customer_data_sum') }}",
                        method: 'GET',
                        data: { search_receipt_no },
                        success: function (response) {
                            if (response.status === 'success') {
                                let records = response.data;
                                records.forEach(record => {
                                    // Update fields with the response data
                                    $('#invoice_no').val(record.Invoice_no);
                                    $('#invoice_date').val(record.Invoice_date);
                                    $('#searchCustomer').val(record.Customer_NIC);
                                    $('#customer_name').val(record.Customer_Name);
                                    $('#customer_contact_1').val(record.Customer_Phone);
                                    $('#total_amount').val(record.Gross_Amount);
                                    $('#paid_discount').val(record.Discount);
                                    $('#paid_amount').val(record.Net_Amount);
                                    $('#green-total-unit-price').val(record.Gross_Amount);
                                    $('#green-total-discount').val(record.Discount);
                                    $('#green-total-value').val(record.Net_Amount);
                                    $('#cash_payment').val(record.cash_payment);
                                    $('#credit_payment').val(record.credit_payment);
                                    $('#cheque_payment').val(record.cheque_payment);

                                });

                                $('.showCustomer').html(`
                                    <div class="row mt-2">
                                        <div class="col-md-3"><label>Supplier Name:</label>
                                            <input class="form-control" type="text" id="invoice_no" name="invoice_no" value="${records[0].Customer_Name}" >
                                        </div>
                                        <div class="col-md-3"><label>Code:</label>
                                            <input class="form-control" type="text" value="${records[0].Customer_NIC}" >
                                        </div>
                                        <div class="col-md-3"><label>Supplier Tel:</label>
                                            <input class="form-control" type="text" value="${records[0].Customer_Phone}">
                                        </div>
                                        <div class="col-md-3"><label>Address:</label>
                                            <input class="form-control" type="text" value="${records[0].Customer_Address}">
                                        </div>
                                    </div>
                                `);
                            } else {
                                $('.showCustomer').html(`<div class="text-danger text-center">Invoice Not Found ...!!</div>`);
                            }
                        }
                    });
                } else {
                    $('#dynamicAdded').empty();
                    $('.showCustomer').empty();
                }
            }, 500);
        });

        // Handle dynamic quantity and discount calculations
        $('#dynamicAdded').on('keyup', '.QTY, .Discount, .DiscountPercentage', function () {
            let row = $(this).closest('tr');
            let Unit_price = parseFloat(row.find('[name="Unit_price"]').val()) || 0;
            let QTY = parseFloat(row.find('[name="QTY"]').val()) || 0;
            let Discount = parseFloat(row.find('[name="Discount"]').val()) || 0;
            let DiscountPercentage = parseFloat(row.find('[name="DiscountPercentage"]').val()) || 0;

            // Calculate the total value
            let totalValue = Unit_price * QTY;
            // Calculate the discount based on the percentage or discount value
            let discountValue = (DiscountPercentage > 0) ? (totalValue * DiscountPercentage / 100) : Discount;
            // Calculate the net value
            let net_value = totalValue - discountValue;


            // Update the Net Value in the current row
            row.find('[name="Net_value"]').val(net_value.toFixed(2));
            row.find('[name="Discount"]').val(discountValue.toFixed(2));

            // Update the total fields (outside the table) dynamically
            let totalGross = 0;
            let totalDiscount = 0;
            let totalNet = 0;

            $('#dynamicAdded tr').each(function () {
                let rowGross = parseFloat($(this).find('[name="Unit_price"]').val()) * parseFloat($(this).find('[name="QTY"]').val());
                let rowDiscount = parseFloat($(this).find('[name="Discount"]').val()) || 0;
                let rowNet = parseFloat($(this).find('[name="Net_value"]').val()) || 0;

                totalGross += rowGross;
                totalDiscount += rowDiscount;
                totalNet += rowNet;
            });

            // Update total fields
            $('#total_amount').val(totalGross.toFixed(2));
            $('#paid_discount').val(totalDiscount.toFixed(2));
            $('#paid_amount').val(totalNet.toFixed(2));
            $('#green-total-unit-price').val(totalGross.toFixed(2));
            $('#green-total-discount').val(totalDiscount.toFixed(2));
            $('#green-total-value').val(totalNet.toFixed(2));
        });

        // Handle edit/save functionality
        $(document).on('click', '.edit-row', function () {
            let row = $(this).closest('tr');
            let button = $(this);

            if (button.text().trim() === 'Edit') {
                row.find('input').prop('readonly', false);
                button.html('<i class="far fa-save me-1"></i> Save').removeClass('btn-outline-success').addClass('btn-outline-primary');
            } else {
                row.find('input').prop('readonly', true);
                button.html('<i class="far fa-edit me-1"></i> Edit').removeClass('btn-outline-primary').addClass('btn-outline-success');
            }
        });
    });
</script>


<script>
    $('#saveInvoiceBtn').on('click', function () {
        let invoiceData = {
            invoice_no: $('#invoice_no').val(),
            invoice_date: $('#invoice_date').val(),
            customer_nic: $('#searchCustomer').val(),
            total_amount: $('#total_amount').val(),
            paid_discount: $('#paid_discount').val(),
            paid_amount: $('#paid_amount').val(),
            cash_payment: $('#cash_payment').val(),
            credit_payment: $('#credit_payment').val(),
            cheque_payment: $('#cheque_payment').val(),
            items: []
        };

        $('#dynamicAdded tr').each(function () {
            invoiceData.items.push({
                Item_code: $(this).find('[name="Item_code"]').val(),
                Item_description: $(this).find('[name="Item_description"]').val(),
                Unit_price: $(this).find('[name="Unit_price"]').val(),
                QTY: $(this).find('[name="QTY"]').val(),
                DiscountPercentage: $(this).find('[name="DiscountPercentage"]').val(),
                Discount: $(this).find('[name="Discount"]').val(),
                Net_value: $(this).find('[name="Net_value"]').val(),
            });
        });

        $.ajax({
            url: "{{ route('update_sales_invoice_data') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                invoice: invoiceData
            },
            success: function (res) {
                if (res.status === 'success') {
                    alert('Invoice updated successfully!');
                    // Refresh the page after alert
                    location.reload();
                } else {
                    alert(res.message || 'Something went wrong.');
                }
            },
            error: function (xhr) {
                alert('Error saving invoice.');
                console.log(xhr.responseText);
            }
        });
    });
</script>


<script>
    $('#deleteInvoice').on('click', function () {
    let invoiceNo = $('#invoice_no').val();

    if (!invoiceNo) {
        alert('No invoice number found.');
        return;
    }

    if (!confirm('Are you sure you want to delete this invoice?')) {
        return;
    }

    $.ajax({
        url: '/Sales-invoice/delete/' + invoiceNo,
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            alert(response.message);
            location.reload();
        },
        error: function (xhr) {
            alert('Error: ' + xhr.responseJSON.message);
        }
    });
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
                {{-- <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous">
</script>

</body>

</html>
@endsection