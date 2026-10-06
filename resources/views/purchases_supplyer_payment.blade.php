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
        .purchase-payment-table-card { background:#f5f9ff; border:1px solid #dfeaf6; border-radius:16px; margin-top:18px; padding:14px; box-shadow:0 10px 25px rgba(42,92,171,.04); }
        .purchase-payment-table-card > .card-body { padding:0; }
        #data_table { width:100% !important; border:1px solid #d9e3ee; border-radius:12px; overflow:hidden; background:#fff; border-collapse:separate; border-spacing:0; }
        #data_table thead th { background:#fff; color:#2b3e5b; font-size:12px; font-weight:800; padding:12px 10px; border-bottom:1px solid #d9e3ee; text-align:center; }
        #data_table tbody td { padding:10px 8px; border-color:#edf1f5; vertical-align:middle; }
        #data_table tbody tr:hover { background:#f7fbff; }
    </style>
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
                <div class="page-header ph-flex">
                    <div class="ph-left">
                        <div class="ph-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 9V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-2M13 12h8m0 0-3-3m3 3-3 3"/></svg>
                        </div>
                        <div>
                            <h3 class="page-title">Supplier Payment</h3>
                            <p class="page-subtitle">Settle outstanding purchase invoices with a supplier</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-secondary" onclick="if(confirm('Reset this form and start over?')) window.location.reload();">
                        <i class="fas fa-redo-alt"></i> Reset
                    </button>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
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
                                <div class="save-success-toast">
                                    <div class="toast-check"><i class="fas fa-check"></i></div>
                                    <div>
                                        <div class="toast-text">Saved!</div>
                                        <div class="toast-subtext">{{ Session::get('done') }}</div>
                                    </div>
                                </div>
                                <script>
                                    setTimeout(function () {
                                        var t = document.querySelector('.save-success-toast');
                                        if (t) t.remove();
                                    }, 4000);
                                </script>
                                @endif


                                <form method="post" id="installment" onsubmit="return false;">
                                    @csrf
                                    <div class="stock-info-card">
                                        <div class="stock-info-grid-3">
                                            <div class="si-field">
                                                <label>Supplier <span class="text-danger">*</span></label>
                                                <div class="stock-item-search-row">
                                                    <div class="stock-item-search-wrap">
                                                        <i class="fas fa-search"></i>
                                                        <input type="text" id="supplier_code_select" name="supplier_code_select"
                                                            class="form-control" placeholder="Enter Supplier Code"
                                                            required aria-label="Supplier Code">
                                                    </div>
                                                    <button type="button" class="stock-item-search-btn"
                                                        data-bs-toggle="modal" data-bs-target="#addguarantor1Model" title="Search supplier">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="si-field">
                                                <label>Payment No.</label>
                                                <div class="si-icon-wrap">
                                                    <i class="fas fa-hashtag"></i>
                                                    <input type="text" id="payment_no"
                                                        name="payment_no" value="{{$maxInvoiceNo+1}}" class="form-control"
                                                        placeholder="Payment No" aria-label="Payment No">
                                                </div>
                                            </div>
                                            <div class="si-field">
                                                <label>Payment Date</label>
                                                <div class="si-icon-wrap">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <input type="date" id="payment_date" name="payment_date" value=""
                                                        class="form-control" aria-label="Payment Date" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="supplier_name" name="supplier_name">
                                    <input type="hidden" id="supplier_phone" name="supplier_phone">

                                    <div class="mt-3 mb-4">
                                        <div class="" id="errMsgContainer2"></div>

                                        <div id="data_empty_state" class="card">
                                            <div class="stock-empty-state">
                                                <i class="fas fa-hand-holding-usd"></i>
                                                Select a supplier above to view their outstanding purchases.
                                            </div>
                                        </div>

                                        <div class="card purchase-payment-table-card d-none" id="data_tables">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-center table-hover"
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
                                                            <table class="table table-bordered table-center table-hover" id="purchase_summary_table">
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

                                                    <div class="stock-info-card mt-4">
                                                        <div class="stock-info-grid-3">
                                                            <div class="si-field">
                                                                <label>Payment Date</label>
                                                                <div class="si-icon-wrap">
                                                                    <i class="fas fa-calendar-alt"></i>
                                                                    <input type="date" class="form-control" id="up_payment_date"
                                                                        name="payment_date" aria-label="Payment Date">
                                                                </div>
                                                            </div>
                                                            <div class="si-field">
                                                                <label>Payment Note</label>
                                                                <div class="si-icon-wrap">
                                                                    <i class="fas fa-sticky-note" style="top:22px; transform:none;"></i>
                                                                    <textarea class="form-control" id="up_payment_note" placeholder="Payment Note"
                                                                        name="payment_note" rows="2" style="padding-left:38px !important;"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="si-field">
                                                                <label>Paying Amount <span class="text-danger">*</span></label>
                                                                <div class="si-icon-wrap">
                                                                    <i class="fas fa-wallet"></i>
                                                                    <input type="text" id="up_paying_amount" name="paying_amount" class="form-control"
                                                                        placeholder="0.00" aria-label="Paying Amount" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- payment options --}}
                                                    <h5 class="mt-4 mb-2"><i class="fas fa-cash-register text-primary me-2"></i>Payment Options</h5>
                                                    <div class="stock-info-card">
                                                        <div class="stock-info-grid-3">
                                                            <div class="si-field">
                                                                <label>Cash Pay</label>
                                                                <div class="si-icon-wrap">
                                                                    <i class="fas fa-money-bill-wave"></i>
                                                                    <input type="text" class="form-control" placeholder="0.00"
                                                                        id="cash_payment" name="cash_payment" aria-label="Cash Pay">
                                                                </div>
                                                            </div>
                                                            <div class="si-field">
                                                                <label>Card Pay</label>
                                                                <div class="si-icon-wrap">
                                                                    <i class="fas fa-credit-card"></i>
                                                                    <input type="text" class="form-control" placeholder="0.00"
                                                                        id="card_payment" name="card_payment" aria-label="Card Pay">
                                                                </div>
                                                            </div>
                                                            <div class="si-field">
                                                                <label>Bank Transfer</label>
                                                                <div class="si-icon-wrap">
                                                                    <i class="fas fa-university"></i>
                                                                    <input type="text" class="form-control" placeholder="0.00"
                                                                        id="bank_transfer" name="bank_transfer"
                                                                        aria-label="Bank Transfer">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <h5 class="mt-4 mb-2"><i class="fas fa-money-check-alt text-primary me-2"></i>Cheque Payment</h5>

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
                                                                                            <option value="" data-account-no="">Select Bank</option>
                                                                                            @foreach($chequeBanks as $data)
                                                                                                <option value="{{ $data->id }}" data-label="{{ $data->bank_name }}{{ $data->branch ? ' - '.$data->branch : '' }}" data-account-no="{{ $data->account_no }}">{{ $data->bank_name }}{{ $data->branch ? ' - '.$data->branch : '' }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="date" id="cheque_date" class="form-control" required>
                                                                                    </td>
                                                                                    <td>
                                                                                        <input type="text" id="account_no" class="form-control" placeholder="Select a bank first" readonly required>
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
            <div class="modal fade" id="addguarantor1Model" tabindex="-1" role="dialog" aria-labelledby="addguarantor1Model" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title m-2" id="addguarantor1ModelLabel">Search Supplier</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                            <div class="table-responsive">
                                <table class="table table-bordered table-center table-hover" id="Guaranttableone">
                                    <thead>
                                        <tr class="table-secondary">
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($supplier as $data)
                                        <tr>
                                            <td>{{ $data->Code }}</td>
                                            <td><div class="item-description-wrapper">{{ $data->Name }}</div></td>
                                            <td class="text-center">
                                                <a href="javascript:void(0)" onclick="fillSupplierPaymentCode('{{ $data->Code }}')" class="dt-act-btn dt-act-edit" data-bs-dismiss="modal" title="Add">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div id="GuaranttableoneCustomPager"></div>
                            </div>

                            <script src="assets/js/dt-custom-pager.js"></script>
                            <script>
                                $(document).ready(function() {
                                    var GuaranttableoneDt = $('#Guaranttableone').DataTable({
                                        pageLength: 10,
                                        lengthChange: false,
                                        dom: 'ft',
                                    });
                                    DTCustomPager.init(GuaranttableoneDt, '#GuaranttableoneCustomPager');
                                });
                            </script>

                            <script>
                                function fillSupplierPaymentCode(code) {
                                    document.getElementById('supplier_code_select').value = code;
                                    $('#supplier_code_select').trigger('change');
                                }
                            </script>
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

    // Auto-fill the account number when a cheque bank is selected
    $(document).on('change', '#bank_name', function () {
        let selected = $(this).find('option:selected');
        $('#account_no').val(selected.data('account-no') || '');
    });

    // Add item
    $(".add-item").click(function () {
        let cheque_bank_id = $('#bank_name').val();
        let selectedOption = $('#bank_name').find('option:selected');
        let bank_name = selectedOption.data('label') || '';
        let cheque_date = $('#cheque_date').val();
        let account_no = $('#account_no').val();
        let cheque_no = $('#cheque_no').val();
        let cheque_ammount = $('#cheque_ammount').val();

        if (isEmptyOrSpaces(cheque_bank_id) || isEmptyOrSpaces(cheque_date) ||
            isEmptyOrSpaces(account_no) || isEmptyOrSpaces(cheque_no) ||
            isEmptyOrSpaces(cheque_ammount)) {
            alert("Please fill in all the fields.");
            return;
        }

        // Save to array (rows are prepended to the DOM, so unshift here keeps
        // array order matching DOM/row.index() order)
        let newRowData = {
            cheque_bank_id, bank_name, cheque_date, account_no, cheque_no, cheque_ammount
        };
        dataArray.unshift(newRowData);

        // Append new row (readonly inputs so it can be submitted in a form)
        $("#dynamicAdded").prepend(`
            <tr>
                <td><input type="text" name="bank_name[]" class="form-control" value="${bank_name}" readonly></td>
                <td><input type="text" name="cheque_date[]" class="form-control" value="${cheque_date}" readonly></td>
                <td><input type="text" name="account_no[]" class="form-control" value="${account_no}" readonly></td>
                <td><input type="text" name="cheque_no[]" class="form-control" value="${cheque_no}" readonly></td>
                <td><input type="text" name="cheque_ammount[]" class="form-control row-amount" value="${cheque_ammount}" readonly></td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-input-field" title="Delete">
                        <i class="fas fa-trash"></i>
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
        let amount = parseFloat(row.find('.row-amount').val()) || 0;
        totalValue = totalValue - amount;

        let index = row.index();
        dataArray.splice(index, 1);

        row.remove();
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
    function showResultsTable() {
        $('#data_empty_state').addClass('d-none');
        $('#data_tables').removeClass('d-none').removeClass('stock-reveal');
        void document.getElementById('data_tables').offsetWidth; // restart animation
        $('#data_tables').addClass('stock-reveal');
    }

    function showEmptyState() {
        $('#data_tables').addClass('d-none');
        $('#data_empty_state').removeClass('d-none');
    }

    $('#supplier_code_select').on('keyup', function (e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            $(this).trigger('change');
        }
    });

    $('#supplier_code_select').on('change', function () {
        var search_string = $(this).val();

        $('#data_table tbody').empty();
        $('#supplier_name').val('');
        $('#supplier_phone').val('');

        if (!search_string) {
            showEmptyState();
            return;
        }

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

                    showResultsTable();

                } else {
                    $('#data_table tbody').html(`
                        <tr>
                            <td colspan="9" class="text-center text-danger">Invoice Not Found.!!</td>
                        </tr>`);
                    showResultsTable();
                }
            },
            error: function () {
                $('#data_table tbody').html(`
                    <tr>
                        <td colspan="9" class="text-center text-danger">Error fetching data.</td>
                    </tr>`);
                showResultsTable();
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
                let error = err.responseJSON || {};
                if (error.message) {
                    $('.errMsgContainer2').append('<span class="text-danger">' + error.message + '</span><br>');
                    alert(error.message);
                } else if (error.errors) {
                    $.each(error.errors, function (index, value) {
                        $('.errMsgContainer2').append('<span class="text-danger">' + value + '<span><br>');
                    });
                } else {
                    alert('Something went wrong while saving the payment.');
                }
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
