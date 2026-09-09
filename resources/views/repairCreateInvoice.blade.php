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


                                <form action="{{route('add_invoice')}}" method="post">
                                    @csrf
                                    <div class="row ">
                                        <div class="row mb-1 form-group justify-content-between">
                                            <div class="row">
                                                <div class="col-md-4">


                                                    <div class="input-group">
                                                        <div class="input-group-text" id="btnGroupAddon1">Customer No :</div>
                                                        <input type="text" id="job_no" name="job_no"
                                                            class="form-control" placeholder="Enter Job No :" required
                                                            aria-label="Invoice Number:" aria-describedby="btnGroupAddon1">
                                                        <div class="input-group-append">
                                                            <button type="button"
                                                                class="btn btn-success btn-lg form-control "
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#addJobModel">
                                                                <i class="fas fa-plus" style="color: white"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-md-2 payment-history">
                                                </div>

                                                {{-- <div class="col">
                                                    <div class=" form-group ">
                                                        <label for="receipt_type">Receipt Type:
                                                            <select class="form-select " id="receipt_type"
                                                                name="receipt_type" aria-hidden="true" required>
                                                                <option value="">Please Select</option>
                                                                @foreach( as $receiptData)
                                                                <option value="{{ $receiptData->receiptname }}"
                                                                    data-rate="{{ $receiptData->rate1 }}">
                                                                    {{ $receiptData->receiptname}}</option>
                                                                @endforeach
                                                            </select>
                                                        </label>
                                                    </div>
                                                </div> --}}

                                                <div class="col-md-2">
                                                </div>

                                                <div class="col">
                                                    <div class="input-group">
                                                        <div class="input-group-text" id="btnGroupAddon2">Invoice No :</div>
                                                        <input type="text" id="invoice_no" name="invoice_no" value="{{$maxInvoiceNo+1}}"
                                                        class="form-control" placeholder="Invoice Number:"
                                                        aria-label="Invoice Number:" aria-describedby="btnGroupAddon2">
                                                    </div>
                                                    {{-- <label for="receipt_no">Invoice No:
                                                        <input class="form-control" type="text" value="{{$maxInvoiceNo+1}}"
                                                            placeholder="Invoice Number:" id="invoice_no" name="invoice_no"
                                                            >
                                                    </label> --}}
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-8">
                                                </div>
                                                <div class="col">

                                                    <div class="input-group">
                                                        <div class="input-group-text" id="btnGroupAddon3">Date : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div>
                                                        <input type="date" id="invoice_date" name="invoice_date"
                                                        class="form-control" aria-label="Date:" aria-describedby="btnGroupAddon3">
                                                    </div>


                                                    {{-- <label for="date">Date:
                                                        <input class="form-control " type="date"
                                                            id="invoice_date" name="invoice_date" required>
                                                    </label> --}}
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

                                            </div>
                                        </div>

                                        {{-- dynamicAdded table --}}
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    {{-- <th style="width:15%; text-align: center;">Category</th> --}}
                                                    <th style="width:15%; text-align: center;">Item Code</th>
                                                    <th style="width:20%; text-align: center;">Description</th>
                                                    <th style="width:10%; text-align: center;">QTY</th>
                                                    <th style="width:12%; text-align: center;">Unit Price</th>
                                                    <th style="width:12%; text-align: center;">Discount (%)</th>
                                                    <th style="width:12%; text-align: center;">Discount Val</th>
                                                    <th style="width:13%; text-align: center;">Net Value</th>
                                                    <th class="text-center" style="width:12%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr>
                                                    {{-- <td>
                                                        <select class="select form-control"
                                                            id="item_category" name="item_category" aria-hidden="true">
                                                            <option value="">Select a category</option>
                                                            @foreach($itemCategory as $categoryData)
                                                            <option value="{{ $categoryData->Category_name }}">
                                                                {{ $categoryData->Category_name	}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td> --}}
                                                    <td id="showItems">
                                                        <input class="form-control" type="text" placeholder="item Code"
                                                            id="item_code" name="item_code" >
                                                    </td>
                                                    <td>
                                                        <select class="select form-control "
                                                            id="item_description" name="item_description" aria-hidden="true">
                                                            <option value="">Select an item</option>
                                                            @foreach( $itemCode as $itemData)
                                                            <option value="{{ $itemData->Item_description }}">
                                                                {{ $itemData->Item_description}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="text" placeholder="QTY"
                                                            id="qty" name="qty" >
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="text" placeholder="Unit Price"
                                                            id="unit_price" name="unit_price" value="0">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="number" placeholder="Discount"
                                                            id="discount" name="discount"
                                                            min="0" max="100" maxlength="6" value="0">
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
                                                        <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon4">CASH PAY :</div>
                                                        <input type="text" style="font-weight:bold;" class="form-control"
                                                            placeholder="CASH PAY :" id="cash_payment" name="cash_payment"
                                                            aria-label="CASH PAY :" aria-describedby="btnGroupAddon4">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon5">GROSS AMOUNT :</div>
                                                        <input type="text" style="font-weight:bold;" class="form-control"
                                                            placeholder="GROSS AMOUNT :" id="total_amount" name="total_amount"
                                                            aria-label="GROSS AMOUNT :" aria-describedby="btnGroupAddon5"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-3 justify-content-between">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon6">CREDITE :&nbsp;&nbsp;</div>
                                                        <input type="text" style="font-weight:bold;" class="form-control"
                                                            placeholder="CREDITE :" id="credite_payment" name="credite_payment"
                                                            aria-label="CREDITE :" aria-describedby="btnGroupAddon6">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon7">DISCOUNT :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                                        <input type="text" style="font-weight:bold;" class="form-control"
                                                        placeholder="DISCOUNT :" id="paid_discount" name="paid_discount"
                                                        aria-label="DISCOUNT :" aria-describedby="btnGroupAddon7" required>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row mt-3 justify-content-between">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon8">CHEQUE :&nbsp;&nbsp;</div>
                                                        <input type="text" style="font-weight:bold;" class="form-control"
                                                            placeholder="CHEQUE :" id="cheque_payment" name="cheque_payment"
                                                            aria-label="CHEQUE :" aria-describedby="btnGroupAddon8">
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon9">NET AMOUNT :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                                        <input type="text" style="font-weight:bold;" class="form-control"
                                                        placeholder="NET AMOUNT :" id="paid_amount" name="paid_amount"
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
                                                <button type="submit" name="save"
                                                    class="btn btn-outline-info btn-lg shadow">SAVE</button>
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

                                </form>
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
    <script>
        var dateObj = new Date();
        document.getElementById('invoice_date').value = dateObj.toISOString().slice(0,10);
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

                                    $('.customer-data').html(
                                        `
                                        <div class="row mt-2">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                    <label for="customer_name">Customer Name: </label>
                                                    <input class="form-control " type="text"
                                                        id="customer_name" name="customer_name" readonly required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                    <label for="customer_phone">Customer Tel: </label>
                                                    <input class="form-control " type="text"
                                                        id="customer_phone" name="customer_phone" readonly required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                    <label for="brand">Brand:</label>
                                                    <input class="form-control " type="text"
                                                        id="brand" name="brand" readonly required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                    <label for="device_model">Device Model:</label>
                                                    <input class="form-control " type="text"
                                                        id="device_model" name="device_model" readonly required>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                    <label for="receipt_date">Reported On:</label>
                                                    <input class="form-control datetimepicker" type="date"
                                                        id="receipt_date" name="receipt_date" readonly required>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group">
                                                    <label for="completed_on">Completed On:</label>
                                                    <input class="form-control datetimepicker" type="date"
                                                        id="completed_on" name="completed_on"  required>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group">
                                                    <label for="imei_number">IMEI Number:</label>
                                                    <input class="form-control " type="text"
                                                        id="imei_number" name="imei_number" readonly required>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-group">
                                                    <label for="status">Status: </label>
                                                    <input class="form-control " type="text"
                                                        id="status" name="status" readonly required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        `
                                    );

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

                                    // set complete date to today
                                    document.getElementById('completed_on').value = dateObj.toISOString().slice(0,10);
                                }

                                if (res.status == 'not_found') {
                                    $('.customer-data').html(
                                        `   <div class="form-group text-center m-1">
                                            <label for="customer_name" class="text-danger "> Customer Not Found...! </label>
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
            let job_no = $('#job_no').val();
            let invoice_no = $('#invoice_no').val();
            let invoice_date = $('#invoice_date').val();
            let item_code = $('#item_code').val();
            let item_description = $('#item_description').val();
            let qty = $('#qty').val();
            let unit_price = $('#unit_price').val();
            let discount = $('#discount').val();
            let discount_val = $('#discount_val').val();
            let net_value = $('#net_value').val();

            if (job_no == "" || invoice_no == "" || item_code == "" ||  qty == "" || unit_price == "" || net_value == "") {
                alert("Please fill in all the fields.");
                return;
            } else {

                // Construct the newRowData object
                let newRowData = {
                    job_no: job_no,
                    invoice_no: invoice_no,
                    invoice_date:invoice_date,
                    item_code: item_code,
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
                            <input type="hidden" name="inputs[` + i + `][job_no]" value="` + job_no + `">
                            <input type="hidden" name="inputs[` + i + `][invoice_no]" value="` + invoice_no + `">
                            <input type="hidden" name="inputs[` + i + `][invoice_date]" value="` + invoice_date + `">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="Item Code"
                                        id="dy_item_code" name="inputs[` + i + `][item_code]" value="` + item_code + `" readonly>
                        </td>

                        <td style="width:18%;">
                            <select class="select form-control" style="text-align: center;" id="dy_item_description"
                            name="inputs[` + i + `][item_description]" aria-hidden="true" disabled readonly>
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
            totalGross = parseFloat(totalGross) + parseFloat(unit_price);
            totalDiscount = parseInt(totalDiscount) + parseInt(discount_val);
            totalValue = parseFloat(totalValue) + parseFloat(net_value);
            setTotal();
            resetTableRow()
        });

        // Function to reset the input fields and dropdowns in the table row
        function resetTableRow() {
            document.getElementById("item_code").value = "";
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
            var re_total_discount  =  row.find('#dy_discount_val');
            var re_net_value = row.find('#dy_net_value');


            // var re_weightValue = re_weight.val();
            var re_grossValue = re_unit_price.val();
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
            let qty_decimal = Math.trunc(unit_qty);
            let net_value_row = qty_decimal * unit_price_value ;

            if (qty_decimal != null) {
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
            let net_value_row = unit_qty * unit_price_value ;

            let unit_discount = $('#discount').val();
            let discounted_value = net_value_row * (unit_discount/100);
            let discounted_net_value = net_value_row  - discounted_value;

            if (unit_discount > 0) {
                $('#discount_val').val(discounted_value);
                $('#net_value').val(discounted_net_value);
            }else{
                // $('#discount').val("0");
                $('#net_value').val(net_value_row);
            }
        })

    </script>

    {{-- Items code show acording to category
    <script>
        $(document).ready(function () {
            // Listen for changes in the Weight and QTY fields
            $('#item_category').on('change', function () {
                let category = $('#item_category').val();
                $.ajax({
                            url: "{{ route('show_select_category_item_ajax') }}",
                            method: 'GET',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                category: category
                            },
                            success: function (res) {
                                if (res.status == 'success') {

                                    var itemsCodeSelected = $('#item_code');
                                    var itemDescriptionSelected = $('#item_description');
                                    var itemUnit_price = $('#unit_price');
                                    // Change this to match your items select element
                                    // Clear existing options
                                    itemsCodeSelected.empty();
                                    itemDescriptionSelected.empty();
                                    itemUnit_price.empty();

                                    // Add a default option for item code
                                    itemsCodeSelected.append($('<option>', {
                                        value: '',
                                        text: 'Select an item'
                                    }));
                                    $.each(res.data, function (index, item) {
                                        itemsCodeSelected.append($('<option>', {
                                            value: item.Item_code,
                                            text: item.Item_code
                                        }));
                                    });

                                    // Add a default option for item description
                                    itemDescriptionSelected.append($('<option>', {
                                        value: '',
                                        text: 'Select an item'
                                    }));

                                    $.each(res.data, function (index, item) {
                                        itemDescriptionSelected.append($('<option>', {
                                            value: item.Item_description,
                                            text: item.Item_description
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
    </script> --}}


    {{-- Item description show acording to item code --}}
    <script>
        $(document).ready(function () {
            // Listen for changes in the Weight and QTY fields
            $('#item_code').on('keyup', function () {
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
                                    // Change this to match your items select element
                                    // Clear existing options
                                    itemDescriptionSelected.empty();
                                    itemUnit_price.empty();

                                    $.each(res.data, function (index, item) {
                                        itemDescriptionSelected.append($('<option>', {
                                            value: item.Item_description,
                                            text: item.Item_description
                                        }));

                                        itemUnit_price.val(item.saleprice);
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
    <script src="assets/js/toastr.min.js"></script>

    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/datatables.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    {{-- <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

</body>

</html>
@endsection
