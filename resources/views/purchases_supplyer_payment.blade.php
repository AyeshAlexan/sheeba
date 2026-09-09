@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Supplyer Payments</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <style>
.make_payment_form[disabled] {
    background-color: #ccc !important;
    color: #666 !important;
    cursor: not-allowed;
    border: 1px solid #999 !important;
}
</style>

</head>


<style>
    #data_table tbody td {
    padding: 7px; /* Adjust the padding as needed */
    }
</style>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card shadow">
                            <div class="col-md-9">
                                <h4 class="card-title m-3">Supplyer Payments</h4>
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
                                <div class="alert alert-danger text-center" role="alert">
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


                                <form method="post" id="installment">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="input-group">
                                                    <div class="input-group-text" id="btnGroupAddon1">Supplier Code :
                                                    </div>
                                                    <input type="text" id="searchSupplier" name="supplier_code"
                                                        class="form-control" placeholder="Enter Supplier Code :"
                                                        required aria-label="Invoice Number:"
                                                        aria-describedby="btnGroupAddon1">
                                                        <select class="form-control" id="supplier_code_select" name="supplier_code_select" required>
                                                            <option value="">Select a Supplier</option>
                                                            @foreach($supplier as $data)
                                                                <option value="{{ $data->Code }}">{{ $data->Name }}</option>
                                                            @endforeach
                                                        </select>
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
                                            <div class="row">
                                                <br><br>
                                            </div>
                                        </div>

                                        <div class="col-md-3 payment-history">
                                        </div>

                                        <div class="col">
                                            <div class="row">
                                                <div class="input-group">
                                                    <div class="input-group-text" id="btnGroupAddon2">
                                                        Payment No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                                    </div>
                                                    <input type="text" id="payment_no"
                                                        name="payment_no" value="{{$maxInvoiceNo+1}}" class="form-control"
                                                        placeholder="Hire Purchase No" aria-label="Invoice Number"
                                                        aria-describedby="btnGroupAddon2" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-group">
                                                    <div class="input-group-text" id="btnGroupAddon2">
                                                        Payment Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                                    </div>
                                                    <input type="date" id="payment_date" name="payment_date" value=""
                                                        class="form-control" placeholder="Payment Date"
                                                        aria-label="Payment Date" aria-describedby="btnGroupAddon2"
                                                        required>
                                                </div>
                                            </div>
                                            <input type="hidden" id="supplier_name" name="supplier_name">
                                            <input type="hidden" id="supplier_phone" name="supplier_phone">
                                            {{-- <input type="text" id="invoice_no" name="invoice_no">
                                            <input type="text" id="invoice_date" name="invoice_date"> --}}


                                        </div>
                                    </div>

                                    <div class="row mt-4 mb-4">
                                        <div class="col">
                                            <div class="" id="errMsgContainer2"></div>
                                            <div class="table-responsive" id="data_tables">
                                                <div class="table-data">
                                                    <table class="table table-bordered table-center table-hover "
                                                        id="data_table">
                                                        <thead>
                                                            <tr class="table-secondary text-center">
                                                                <th>Supplier Name</th>
                                                                <th>Purchase No</th>
                                                                 <th>Reference No</th>
                                                                <th>Purchase Date</th>
                                                                <th>Amount</th>
                                                                <th>Amount Pay</th>
                                                                <th>Balance Payment</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr style="height: 50px">
                                                                <td></td>
                                                                <td></td>
                                                                 <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr style="height: 50px">
                                                                <td></td>
                                                                <td></td>
                                                                 <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr style="height: 50px">
                                                                <td></td>
                                                                <td></td>
                                                                 <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr style="height: 50px">
                                                                <td></td>
                                                                <td></td>
                                                                 <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>



                                        {{-- bottom buttons  --}}
                                        {{-- <div class="row">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-7">
                                                <br>
                                                <button type="submit" name="save" id="save"
                                                    class="btn btn-outline-info btn-lg shadow">Add</button>
                                                <button type="button" name="print"
                                                    class="btn btn-outline-primary printReceipt btn-lg shadow">PRINT</button>
                                                <button type="button" name="pawn_delete" id="pawn_delete"
                                                    class="btn btn-outline-danger btn-lg shadow pawn_delete">DELETE</button>
                                                <button type="button" name="pawn_cancel" id="pawn_cancel"
                                                    class="btn btn-outline-warning btn-lg shadow pawn_cancel">CANCEL</button>
                                                <button type="reset" name="reset"
                                                    class="btn btn-outline-secondary btn-lg shadow">RESET</button>
                                            </div>
                                        </div> --}}

                                        <div class="showModels"></div>

                                </form>

                            </div>

                            <div>
                                {{-- make payment model --}}
                                <div class="modal fade" tabindex="-1" id="makePaymentModel"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addSerialNoModalLabel">Make Supplier Payment</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="errMsgContainer2"></div>
                                                <!-- Form to encapsulate input fields -->
                                                <form id="makeSupPayment">
                                                    @csrf
                                                    <input type="hidden" value="{{$maxInvoiceNo+1}}" id="up_payment_no" name="payment_no">
                                                    <input type="hidden" id="up_supplier_code" name="supplier_code">
                                                    <input type="hidden" id="up_supplier_name" name="supplier_name">
                                                    <input type="hidden" id="up_supplier_phone" name="supplier_phone">

                                                    <!-- Input fields go here -->

                                                    <div class="table-responsive">
                                                        <div class="table-data">
                                                            <table class="table table-bordered table-center table-hover" id="multi_cheques_table">
                                                                <thead class="thead-light">
                                                                    <tr align="center">
                                                                        <th class="text-center">Purchase No</th>
                                                                        <th class="text-center">Purchase Date</th>
                                                                        <th class="text-center">Amount</th>
                                                                        <th class="text-center">Total Balance</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr align="center">
                                                                        <td>
                                                                            <input type="text" id="up_purchse_no" name="purchse_no"
                                                                            class="form-control" placeholder="Purchase No" aria-label="Purchase No"
                                                                            aria-describedby="btnGroupAddon2" required>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" id="up_purchse_date" name="purchse_date"
                                                                            class="form-control" placeholder="Purchase Date" aria-label="Purchase Date"
                                                                            aria-describedby="btnGroupAddon2" required>
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" id="up_amount" name="amount"
                                                                            class="form-control" placeholder="Amount" aria-label="Amount"
                                                                            aria-describedby="btnGroupAddon2" required>
                                                                        </td>
                                                                                <td>
                                                                            <input type="text" id="totalBalance" name="totalBalance"
                                                                            class="form-control" placeholder="Amount" aria-label="Amount"
                                                                            aria-describedby="btnGroupAddon2" readonly style="color: brown">
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-4">
                                                        <div class="col">
                                                            <div class="input-group">
                                                                <div class="input-group-text" id="btnGroupAddon2">
                                                                    Payment Date&nbsp;&nbsp;&nbsp;:
                                                                </div>
                                                                <input type="date" class="form-control" id="up_payment_date" placeholder="Payment Note"
                                                                    name="payment_date" rows="3">
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="input-group">
                                                                <div class="input-group-text" id="btnGroupAddon2">
                                                                    Payment Note&nbsp;&nbsp;&nbsp;:
                                                                </div>
                                                                <textarea class="form-control" id="up_payment_note" placeholder="Payment Note"
                                                                    name="payment_note" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="input-group">
                                                                <div class="input-group-text" id="btnGroupAddon2">
                                                                    Paying Amount&nbsp;:
                                                                </div>
                                                                <input type="text" id="up_paying_amount" name="paying_amount" class="form-control"
                                                                    placeholder="Paying Amount" aria-label="Paying Amount" aria-describedby="btnGroupAddon2"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- payment options --}}
                                                    <div class="row mt-4 ">
                                                        <div class="row mt-1 justify-content-between">
                                                            <div class="col">
                                                                <p>Payment Options</p>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2 justify-content-between">
                                                            <div class="col">
                                                                <div class="input-group ">
                                                                    <div class="input-group-text" style="font-weight:bold;"
                                                                        id="btnGroupAddon4">
                                                                        Cash Pay :</div>
                                                                    <input type="text" style="font-weight:bold;"
                                                                        class="form-control" placeholder="Cash Pay"
                                                                        id="cash_payment" name="cash_payment" aria-label="Cash Pay"
                                                                        aria-describedby="btnGroupAddon4">
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="input-group">
                                                                    <div class="input-group-text" style="font-weight:bold;"
                                                                        id="btnGroupAddon6">
                                                                        Card Pay&nbsp;:</div>
                                                                    <input type="text" style="font-weight:bold;"
                                                                        class="form-control" placeholder="Card Pay"
                                                                        id="card_payment" name="card_payment" aria-label="Card Pay"
                                                                        aria-describedby="btnGroupAddon6">
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="input-group">
                                                                    <div class="input-group-text" style="font-weight:bold;"
                                                                        id="btnGroupAddon8">
                                                                        Bank Tr.&nbsp;&nbsp;:</div>
                                                                    <input type="text" style="font-weight:bold;"
                                                                        class="form-control" placeholder="Bank Transfer"
                                                                        id="bank_transfer" name="bank_transfer"
                                                                        aria-label="Bank Transfer"
                                                                        aria-describedby="btnGroupAddon8">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-4 justify-content-between">
                                                            <div class="col">
                                                                <p>Cheque Payment</p>
                                                            </div>
                                                        </div>


                                                            <div id="multi_cheques" class="mt-2">
                                                                <div class="table-responsive">
                                                                    <div class="table-data">
                                                                        <table class="table table-bordered table-center table-hover" id="multi_cheques_table">
                                                                            <thead class="thead-light">
                                                                                <tr>
                                                                                    <th class="text-center">Bank Name</th>
                                                                                    <th class="text-center">Cheque Date</th>
                                                                                    <th class="text-center">Account No</th>
                                                                                    <th class="text-center">Cheque No</th>
                                                                                    <th class="text-center">Amount</th>
                                                                                    <th class="text-center">Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <select class="form-control" id="bank_name">
                                                                                            <option value="">Select Bank</option>
                                                                                            @foreach($bank as $data)
                                                                                                <option value="{{ $data->description }}">{{ $data->description }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="date" id="cheque_date" class="form-control" required>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" id="account_no" class="form-control" placeholder="Account No" required>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" id="cheque_no" class="form-control" placeholder="Cheque No" required>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="number" step="0.01" id="cheque_ammount" class="form-control" placeholder="Amount" required>
                                                                                    </td>
                                                                                    <td>
                                                                                        <button type="button" id="add-item"
                                                                                            class="btn add-item btn-outline-info btn-sm shadow">Add
                                                                                            <i class="fas fa-plus"></i>
                                                                                        </button>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>

                                                                        <!-- Dynamic rows will be inserted here -->
                                                                        <table class="table table-bordered mt-2">
                                                                            <tbody id="dynamicAdded"></tbody>
                                                                        </table>

                                                                        <!-- Show total -->
                                                                        <div class="mt-2 text-right">
                                                                            <span class="total-value"><strong>0.00</strong></span>
                                                                            <input type="hidden" id="total_cheque_amount" name="total_cheque_amount" value="0">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        {{-- dynamicAdded table --}}
                                                        <div class="table-responsive">
                                                            <div class="table-data">
                                                            <table class="table table-bordered " id="dynamicAdded">
                                                            </table>
                                                            </div>
                                                        </div>

                                                        {{-- table footer for total calculations --}}
                                                        <table class="table table-bordered" id="green_total_row">
                                                            <tbody>
                                                                <tr class="table-success">
                                                                    <td> </td>
                                                                    <td><strong>
                                                                            <p>TOTAL :</p>
                                                                        </strong>
                                                                    </td>
                                                                    <td></td>
                                                                    <td class="total-value text-center">
                                                                        <p><strong>0.00</strong></p>
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <input type="hidden" id="total_cheque_amount" name="total_cheque_amount">
                                                    </div>
                                            </div>
                                            <div class="modal-footer ">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                                    aria-label="Close">Close</button>
                                                <button type="button" class="btn btn-success" id="saveSupPayment">Save</button>
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
            @include('layouts.footer')
        </div>
    </div>

    {!! Toastr::message() !!}

    {{-- CSRF token script --}}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    {{-- form default date set for today --}}
    <script>
        var dateObj = new Date();
        document.getElementById('payment_date').value = dateObj.toISOString().slice(0, 10);
    </script>

    {{-- show multi-cheques section --}}
    {{-- <script>
        document.getElementById("cheque_payment_button").addEventListener("click", function(event) {
            event.preventDefault(); // Prevent default link behavior
            // Get the div where you want to insert content
            var multiChequesDiv = document.getElementById("multi_cheques");
            // Create your content
            var content = ``;
            // Insert the content into the div
            multiChequesDiv.innerHTML = content;

        });
    </script> --}}

    {{-- add new CHECK row script --}}
<script type="text/javascript">
    let dataArray = [];
    let totalValue = 0;

    // Function to check if empty
    function isEmptyOrSpaces(str) {
        return str === null || str.match(/^ *$/) !== null;
    }

    // Add item
    $(".add-item").click(function () {
        let bank_name = $('#bank_name').val();
        let cheque_date = $('#cheque_date').val();
        let account_no = $('#account_no').val();
        let cheque_no = $('#cheque_no').val();
        let cheque_ammount = $('#cheque_ammount').val();

        if (isEmptyOrSpaces(bank_name) || isEmptyOrSpaces(cheque_date) ||
            isEmptyOrSpaces(account_no) || isEmptyOrSpaces(cheque_no) ||
            isEmptyOrSpaces(cheque_ammount)) {
            alert("Please fill in all the fields.");
            return;
        }

        // Save to array
        let newRowData = {
            bank_name, cheque_date, account_no, cheque_no, cheque_ammount
        };
        dataArray.push(newRowData);

        // Append new row (readonly inputs so it can be submitted in a form)
        $("#dynamicAdded").prepend(`
            <tr>
                <td><input type="text" name="bank_name[]" class="form-control" value="${bank_name}" readonly></td>
                <td><input type="text" name="cheque_date[]" class="form-control" value="${cheque_date}" readonly></td>
                <td><input type="text" name="account_no[]" class="form-control" value="${account_no}" readonly></td>
                <td><input type="text" name="cheque_no[]" class="form-control" value="${cheque_no}" readonly></td>
                <td><input type="text" name="cheque_ammount[]" class="form-control row-amount" value="${cheque_ammount}" readonly></td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-input-field">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `);

        // Update totals
        totalValue = parseFloat(totalValue) + parseFloat(cheque_ammount);
        setTotal();
        resetTableRow();
    });

    // Reset input fields
    function resetTableRow() {
        document.getElementById("bank_name").value = "";
        document.getElementById("cheque_date").value = "";
        document.getElementById("account_no").value = "";
        document.getElementById("cheque_no").value = "";
        document.getElementById("cheque_ammount").value = "";
    }

    // Set total
    function setTotal() {
        $('.total-value').html(`<p><strong>${totalValue.toFixed(2)}</strong></p>`);
        document.getElementById("total_cheque_amount").value = totalValue.toFixed(2);
    }

    // Remove row & update total
    $(document).on('click', '.remove-input-field', function () {
        let row = $(this).closest('tr');
        let amount = parseFloat(row.find('.row-amount').val());
        totalValue = totalValue - amount;
        row.remove();
        setTotal();
    });
</script>

    {{-- delete added item rows script --}}
    <script>
        $(document).on('click', '.remove-input-field', function () {
            var row = $(this).parents('tr');
            var re_bank_name = row.find('#dy_bank_name');
            var re_bank_branch = row.find('#dy_bank_branch');
            var re_account_no = row.find('#dy_account_no');
            var re_cheque_no = row.find('#dy_cheque_no');
            var re_cheque_ammount = row.find('#dy_cheque_ammount');
            var re_totalValue = re_cheque_ammount.val();

            totalValue = parseInt(totalValue) - parseInt(re_totalValue);

            // Remove data from the array
            var index = row.index();
            dataArray.splice(index, 1);

            $(this).parents('tr').remove();
            setTotal();
        });
    </script>


    {{-- disable form submit when press enter key --}}
    <script>
        $('#installment').on('keyup keypress', function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13 || e.keyCode === 10) {
                e.preventDefault();
                return false;
            }
        });
    </script>


<script>
$(document).ready(function () {
    $('#supplier_code_select').on('change', function () {
        var search_string = $(this).val();

        $('#data_table tbody').empty();
        $('#supplier_name').val('');
        $('#supplier_phone').val('');

        if (!search_string) return;

        $.ajax({
            url: "{{ route('get_supplyer_purshases_data_using_no_ajax') }}",
            method: 'GET',
            data: { search_string: search_string },
            success: function (response) {
                if (response.status === 'success') {
                    let records = response.data;

                    records.forEach(record => {
                        let statusBadge = record.isPaid
                            ? '<span class="badge bg-success-light">Paid</span>'
                            : '<span class="badge bg-warning-light">Pending</span>';

                        let disabledAttr = record.isPaid ? 'disabled' : '';

                        let html = `
                            <tr>
                                <td>${record.Customer_Name}</td>
                                <td>${record.Invoice_no}</td>
                                <td>${record.Ref_no}</td>
                                <td>${record.Invoice_date}</td>
                                <td>${record.credit_payment}</td>
                                <td>${record.paid_amount}</td>
                                <td>${record.balance}</td>
                                <td class="text-center">${statusBadge}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-success make_payment_form"
                                            data-bs-toggle="modal"
                                            data-bs-target="#makePaymentModel"
                                            data-invoice_no='${record.Invoice_no}'
                                            data-invoice_date='${record.Invoice_date}'
                                            data-customer_code='${record.Customer_NIC}'
                                            data-customer_name='${record.Customer_Name}'
                                            data-customer_phone='${record.Customer_Phone}'
                                            data-credit_payment='${record.credit_payment}'
                                            data-total_balance='${record.balance}'
                                            ${disabledAttr}>
                                        Make Payment &nbsp;<i class="fas fa-plus"></i>
                                    </button>
                                </td>
                            </tr>`;
                        $('#data_table tbody').append(html);
                    });

                    // Set first supplier info
                    $('#supplier_name').val(records[0].Customer_Name);
                    $('#supplier_phone').val(records[0].Customer_Phone);

                } else {
                    $('#data_table tbody').html(`
                        <tr>
                            <td colspan="9" class="text-center text-danger">Invoice Not Found.!!</td>
                        </tr>`);
                }
            },
            error: function () {
                $('#data_table tbody').html(`
                    <tr>
                        <td colspan="9" class="text-center text-danger">Error fetching data.</td>
                    </tr>`);
            }
        });
    });
});
</script>




{{--  Set make payment form --}}
<script>
$(document).ready(function () {
    // Show supplier payment in modal form
    $(document).on('click', '.make_payment_form', function () {
        let invoice_no = $(this).data('invoice_no');
        let invoice_date = $(this).data('invoice_date');
        let supplier_code = $(this).data('customer_code');
        let supplier_name = $(this).data('customer_name');
        let supplier_phone = $(this).data('customer_phone');
        let credit_payment = $(this).data('credit_payment');
        let totalBalance = $(this).data('total_balance');

        let payment_date = $('#payment_date').val();

        $('#up_payment_date').val(payment_date);
        $('#up_supplier_code').val(supplier_code);
        $('#up_supplier_name').val(supplier_name);
        $('#up_supplier_phone').val(supplier_phone);
        $('#up_purchse_no').val(invoice_no);
        $('#up_purchse_date').val(invoice_date);
        $('#up_amount').val(credit_payment);
        $('#totalBalance').val(totalBalance);
    });

    // Save payment details
    $(document).on('click', '#saveSupPayment', function (e) {
        e.preventDefault();

        // Ensure dataArray is defined or remove this if not needed
        let dataArrayJSON = JSON.stringify(dataArray || []);

        $.ajax({
            url: "{{ route('make_supplyer_payment') }}",
            method: 'POST',
            data: {
                "_token": "{{ csrf_token() }}",
                payment_no: $('#payment_no').val(),
                payment_date: $('#up_payment_date').val(),
                supplier_code: $('#up_supplier_code').val(),
                supplier_name: $('#up_supplier_name').val(),
                supplier_phone: $('#up_supplier_phone').val(),
                purchse_no: $('#up_purchse_no').val(),
                purchse_date: $('#up_purchse_date').val(),
                payment_note: $('#up_payment_note').val(),
                amount: $('#up_paying_amount').val(),
                cash_payment: $('#cash_payment').val(),
                card_payment: $('#card_payment').val(),
                cheque_payment: $('#cheque_payment').val(),
                bank_transfer: $('#bank_transfer').val(),
                total_cheque_amount: $('#total_cheque_amount').val(),
                totalBalance: $('#totalBalance').val(),
                dataArray: dataArrayJSON,
            },
            success: function (res) {
                if (res.status == 'success') {
                    $("#makePaymentModel").modal('hide');
                    $('#makeSupPayment')[0].reset();
                    $('.table').load(location.href + ' .table');
                    toastr.success("Customer Details Updated...", "Success");
                    window.open(res.data, '_blank');

                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                }
            },
            error: function (err) {
                $('.errMsgContainer2').html('');
                let error = err.responseJSON;
                $.each(error.errors, function (index, value) {
                    $('.errMsgContainer2').append('<span class="text-danger">' + value + '<span><br>');
                });
            }
        });
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
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

</body>

</html>
@endsection
