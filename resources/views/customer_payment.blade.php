@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Customer Payments</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
</head>

<style>
    /* ── Tab nav ─────────────────────────────────────────────── */
    .payment-tabs .nav-link {
        font-weight: 600;
        font-size: 14px;
        color: #555;
        border-radius: 6px 6px 0 0;
        padding: 10px 22px;
        display: flex;
        align-items: center;
        gap: 7px;
        border: 1px solid transparent;
    }
    .payment-tabs .nav-link.active#tab-invoice {
        background: #28a745;
        color: #fff;
        border-color: #28a745 #28a745 #fff;
    }
    .payment-tabs .nav-link.active#tab-totalcredit {
        background: #1565c0;
        color: #fff;
        border-color: #1565c0 #1565c0 #fff;
    }
    .payment-tabs .nav-link:not(.active):hover { background: #f1f3f5; }

    /* ── Tab pane panel ─────────────────────────────────────── */
    .tab-panel-card {
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 6px 6px 6px;
        padding: 18px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    #pane-invoice      .tab-panel-card { border-top: 4px solid #28a745; }
    #pane-totalcredit  .tab-panel-card { border-top: 4px solid #1565c0; }

    /* ── Table padding ──────────────────────────────────────── */
    #data_table tbody td,
    #total_credit_table tbody td { padding: 7px; }

    /* ── Balance badge ──────────────────────────────────────── */
    .total-balance-badge {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 8px;
        padding: 9px 16px;
        font-size: 14px;
        font-weight: bold;
        color: #856404;
        display: inline-block;
        margin-bottom: 12px;
    }
    .total-balance-badge span { color: #c0392b; font-size: 17px; }
</style>

<body>
<div class="main-wrapper">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow">

                        <div class="d-flex align-items-center px-3 pt-3">
                            <h4 class="card-title mb-0">Customer Payments</h4>
                        </div>
                        <hr style="height:4px;background:blue;margin:10px 0 0;">

                        <div class="card-body">

                            {{-- Alerts --}}
                            @if (session('delete'))
                                <div class="alert alert-danger text-center">{{session('delete')}} &#10004;</div>
                            @endif
                            @if (session('added'))
                                <div class="alert alert-success text-center">{{session('added')}} &#10004;</div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger text-center">
                                    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                </div>
                            @endif
                            @if (Session::has('done'))
                                <div class="alert alert-success text-center"><p class="mb-0">{{ Session::get('done') }}</p></div>
                            @endif

                            {{-- Shared controls --}}
                            <form method="post" id="installment">
                                @csrf
                                <div class="row g-2 mb-4">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-text">Customer Code :</span>
                                            <input type="text" id="searchCustomer" name="Customer_code"
                                                class="form-control" placeholder="Enter Customer Code" required>
                                            <button type="button" class="btn btn-primary"
                                                data-bs-toggle="modal" data-bs-target="#selectCustomerModel">
                                                <i class="fa fa-arrow-left"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-text">Payment No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</span>
                                            <input type="text" id="payment_no" name="payment_no"
                                                value="{{$maxInvoiceNo+1}}" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-text">Payment Date&nbsp;&nbsp;&nbsp;:</span>
                                            <input type="date" id="payment_date" name="payment_date"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="customer_name"  name="customer_name">
                                <input type="hidden" id="customer_phone" name="customer_phone">

                                {{-- ── Nav Tabs ──────────────────────────────────── --}}
                                <ul class="nav nav-tabs payment-tabs" id="paymentTabNav" role="tablist">
                                       <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-totalcredit"
                                            data-bs-toggle="tab" data-bs-target="#pane-totalcredit"
                                            type="button" role="tab" aria-selected="false">
                                            <i class="fas fa-coins"></i>
                                            Total Credit Payment
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="tab-invoice"
                                            data-bs-toggle="tab" data-bs-target="#pane-invoice"
                                            type="button" role="tab" aria-selected="true">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                            Invoice Wish Payment
                                        </button>
                                    </li>

                                </ul>

                                {{-- ── Tab Panes ─────────────────────────────────── --}}
                                <div class="tab-content" id="paymentTabContent">

                                    {{-- Tab 1: Invoice Wish --}}
                                    <div class="tab-pane fade show active" id="pane-invoice"
                                        role="tabpanel" aria-labelledby="tab-invoice">
                                        <div class="tab-panel-card">
                                            <div id="errMsgContainer_invoice"></div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-center table-hover" id="data_table">
                                                    <thead>
                                                        <tr class="table-success">
                                                            <th>Customer Name</th>
                                                            <th>Invoice No</th>
                                                            <th>Invoice Date</th>
                                                            <th>Credit Payment</th>
                                                            <th>Amount Pay</th>
                                                            <th>Balance</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @for ($i = 0; $i < 3; $i++)
                                                        <tr style="height:48px"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                                        @endfor
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Tab 2: Total Credit --}}
                                    <div class="tab-pane fade" id="pane-totalcredit"
                                        role="tabpanel" aria-labelledby="tab-totalcredit">
                                        <div class="tab-panel-card">
                                            <div id="total_balance_display" style="display:none;" class="total-balance-badge">
                                                Total Customer Balance :&nbsp;<span id="total_balance_value">0.00</span>
                                            </div>
                                            <div id="errMsgContainer_total"></div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-center table-hover" id="total_credit_table">
                                                    <thead>
                                                        <tr class="table-primary">
                                                            <th>Customer Code</th>
                                                            <th>Customer Name</th>
                                                            <th>Total CR Amount</th>
                                                            <th>Total DR Amount (Paid)</th>
                                                            <th>Balance</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @for ($i = 0; $i < 2; $i++)
                                                        <tr style="height:48px"><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                                                        @endfor
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                </div>{{-- /tab-content --}}
                                <div class="showModels"></div>
                            </form>

                        </div>{{-- /card-body --}}

                        {{-- ════════════════════════════════════════════════════ --}}
                        {{-- MODAL: Invoice Wish Make Payment                    --}}
                        {{-- ════════════════════════════════════════════════════ --}}
                        <div class="modal fade" tabindex="-1" id="makePaymentModel" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-file-invoice-dollar me-2"></i>Make Payment — Invoice Wish
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="errMsgContainer2 mb-2"></div>
                                        <form id="makeSupPayment">
                                            @csrf
                                            <input type="hidden" value="{{$maxInvoiceNo+1}}" id="up_payment_no" name="payment_no">
                                            <input type="hidden" id="up_customer_code"  name="customer_code">
                                            <input type="hidden" id="up_customer_name"  name="customer_name">
                                            <input type="hidden" id="up_customer_phone" name="customer_phone">

                                            <div class="table-responsive mb-3">
                                                <table class="table table-bordered">
                                                    <thead class="thead-light">
                                                        <tr align="center">
                                                            <th>Invoice No</th><th>Invoice Date</th>
                                                            <th>Credit Amount</th><th>Balance Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr align="center">
                                                            <td><input type="text" id="up_sales_no"   name="sales_no"   class="form-control" placeholder="Invoice No"   required></td>
                                                            <td><input type="text" id="up_sales_date" name="sales_date" class="form-control" placeholder="Invoice Date" required></td>
                                                            <td><input type="text" id="up_amount"     name="amount"     class="form-control" placeholder="Amount"       required></td>
                                                            <td><input type="text" id="balance_credit_payment" name="balance_credit_payment"
                                                                style="color:#ee1212;font-weight:bold;" class="form-control" placeholder="Balance" required></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="row g-2 mb-3">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Payment Date&nbsp;:</span>
                                                        <input type="date" class="form-control" id="up_payment_date" name="payment_date">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Payment Note&nbsp;:</span>
                                                        <textarea class="form-control" id="up_payment_note" name="payment_note" rows="1"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Paying Amount :</span>
                                                        <input type="text" id="up_paying_amount" name="paying_amount" class="form-control" placeholder="Paying Amount" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="mb-2"><strong>Payment Options</strong></p>
                                            <div class="row g-2 mb-3">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-text fw-bold">Cash Pay&nbsp;:</span>
                                                        <input type="text" class="form-control fw-bold" id="cash_payment" name="cash_payment" placeholder="Cash Pay">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-text fw-bold">Card Pay&nbsp;:</span>
                                                        <input type="text" class="form-control fw-bold" id="card_payment" name="card_payment" placeholder="Card Pay">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-text fw-bold">Bank Tr.&nbsp;&nbsp;:</span>
                                                        <input type="text" class="form-control fw-bold" id="bank_transfer" name="bank_transfer" placeholder="Bank Transfer">
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="mb-2"><strong>Cheque Payment</strong></p>
                                            <div class="table-responsive mb-2">
                                                <table class="table table-bordered" id="multi_cheques_table_show">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Bank Name</th><th>Cheque Date</th>
                                                            <th>Cheque No</th><th>Amount</th><th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <select class="form-control" id="bank_name" name="bank_name">
                                                                    <option value="">Select Bank</option>
                                                                    @foreach($bank as $b)
                                                                        <option value="{{ $b->description }}">{{ $b->description }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="date" id="cheque_date"    name="cheque_date"    class="form-control"></td>
                                                            <td><input type="text" id="cheque_no"      name="cheque_no"      class="form-control" placeholder="Cheque No"></td>
                                                            <td><input type="text" id="cheque_ammount" name="cheque_ammount" class="form-control" placeholder="Amount"></td>
                                                            <td><button type="button" id="add-item" class="btn btn-outline-info btn-sm">Add <i class="fas fa-plus"></i></button></td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                                                            <td class="total-value text-center"><strong>0</strong></td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <table class="table table-bordered mb-2">
                                                <tbody>
                                                    <tr class="table-success">
                                                        <td></td><td><strong>TOTAL :</strong></td><td></td>
                                                        <td class="total-value text-center"><strong>0.00</strong></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <input type="number" id="total_cheque_amount" name="total_cheque_amount" class="form-control" readonly>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-success" id="saveSupPayment">
                                            <i class="fas fa-save me-1"></i> Save Payment
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ════════════════════════════════════════════════════ --}}
                        {{-- MODAL: Total Credit Make Payment                    --}}
                        {{-- ════════════════════════════════════════════════════ --}}
                        <div class="modal fade" tabindex="-1" id="makeTotalCreditPaymentModel" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header" style="background:#1565c0;color:#fff;">
                                        <h5 class="modal-title">
                                            <i class="fas fa-coins me-2"></i>Make Total Credit Payment
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="errMsgContainer_tcp" class="mb-2"></div>
                                        <form id="makeTotalCreditPaymentForm">
                                            @csrf
                                            <input type="hidden" value="{{$maxInvoiceNo+1}}" id="tcp_payment_no"    name="payment_no">
                                            <input type="hidden" id="tcp_customer_code"  name="customer_code">
                                            <input type="hidden" id="tcp_customer_name"  name="customer_name">
                                            <input type="hidden" id="tcp_customer_phone" name="customer_phone">
                                            <input type="hidden" id="tcp_total_cr"       name="total_cr">
                                            <input type="hidden" id="tcp_total_dr"       name="total_dr">

                                            <div class="table-responsive mb-3">
                                                <table class="table table-bordered">
                                                    <thead class="thead-light">
                                                        <tr align="center">
                                                            <th>Customer Code</th><th>Customer Name</th>
                                                            <th>Total CR Amount</th><th>Total Paid (DR)</th>
                                                            <th>Outstanding Balance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr align="center">
                                                            <td><input type="text" id="tcp_disp_code"    class="form-control" readonly></td>
                                                            <td><input type="text" id="tcp_disp_name"    class="form-control" readonly></td>
                                                            <td><input type="text" id="tcp_disp_cr"      class="form-control" readonly></td>
                                                            <td><input type="text" id="tcp_disp_dr"      class="form-control" readonly></td>
                                                            <td><input type="text" id="tcp_disp_balance" class="form-control"
                                                                style="color:#ee1212;font-weight:bold;" readonly></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="row g-2 mb-3">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Payment Date&nbsp;:</span>
                                                        <input type="date" class="form-control" id="tcp_payment_date" name="payment_date">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Payment Note&nbsp;:</span>
                                                        <textarea class="form-control" id="tcp_payment_note" name="payment_note" rows="1"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-text">Paying Amount :</span>
                                                        <input type="text" id="tcp_paying_amount" name="paying_amount" class="form-control" placeholder="Paying Amount" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="mb-2"><strong>Payment Options</strong></p>
                                            <div class="row g-2 mb-3">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-text fw-bold">Cash Pay&nbsp;:</span>
                                                        <input type="text" class="form-control fw-bold" id="tcp_cash_payment" name="cash_payment" placeholder="Cash Pay">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-text fw-bold">Card Pay&nbsp;:</span>
                                                        <input type="text" class="form-control fw-bold" id="tcp_card_payment" name="card_payment" placeholder="Card Pay">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <span class="input-group-text fw-bold">Bank Tr.&nbsp;&nbsp;:</span>
                                                        <input type="text" class="form-control fw-bold" id="tcp_bank_transfer" name="bank_transfer" placeholder="Bank Transfer">
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="mb-2"><strong>Cheque Payment</strong></p>
                                            <div class="table-responsive mb-2">
                                                <table class="table table-bordered" id="tcp_cheques_table_show">
                                                    <thead class="thead-light">
                                                        <tr>
                                                            <th>Bank Name</th><th>Cheque Date</th>
                                                            <th>Cheque No</th><th>Amount</th><th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <select class="form-control" id="tcp_bank_name" name="bank_name">
                                                                    <option value="">Select Bank</option>
                                                                    @foreach($bank as $b)
                                                                        <option value="{{ $b->description }}">{{ $b->description }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="date" id="tcp_cheque_date"   class="form-control"></td>
                                                            <td><input type="text" id="tcp_cheque_no"     class="form-control" placeholder="Cheque No"></td>
                                                            <td><input type="text" id="tcp_cheque_amount" class="form-control" placeholder="Amount"></td>
                                                            <td><button type="button" id="tcp_add_cheque" class="btn btn-outline-info btn-sm">Add <i class="fas fa-plus"></i></button></td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                                                            <td class="tcp-total-value text-center"><strong>0</strong></td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            <table class="table table-bordered mb-2">
                                                <tbody>
                                                    <tr class="table-primary">
                                                        <td></td><td><strong>TOTAL :</strong></td><td></td>
                                                        <td class="tcp-total-value text-center"><strong>0.00</strong></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <input type="number" id="tcp_total_cheque_amount" name="total_cheque_amount" class="form-control" readonly>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="saveTotalCreditPayment">
                                            <i class="fas fa-save me-1"></i> Save Total Credit Payment
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>{{-- /card --}}
                </div>
            </div>
        </div>
        @include('layouts.footer')
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════ --}}
{{-- MODAL: Select Customer                                       --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="selectCustomerModel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title m-2">Search Customer</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mt-2" id="ItemTableCus">
                        <thead>
                            <tr class="table-secondary">
                                <th>Code</th><th>Name</th><th>Address</th><th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customerDetails as $data)
                            <tr>
                                <td>{{ $data->Code }}</td>
                                <td>{{ $data->First_name }}</td>
                                <td>{{ $data->Address_1 }}</td>
                                <td>
                                    <a href="#" onclick="fillCustomerCode('{{ $data->Code }}')"
                                        class="btn btn-outline-info btn-sm shadow"
                                        data-bs-toggle="modal" data-bs-target="#selectCustomerModel">
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
    </div>
</div>

{!! Toastr::message() !!}

{{-- ════════════════════════════════════════════════════════════ --}}
{{-- SCRIPTS                                                      --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<script type="text/javascript">
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
</script>

<script>
    document.getElementById('payment_date').value = new Date().toISOString().slice(0, 10);
</script>

<script>
    $(document).ready(function () {
        $('#ItemTableCus').DataTable({ lengthMenu: [[5,10,25],[5,10,25]] });
    });
    function fillCustomerCode(code) {
        document.getElementById('searchCustomer').value = code;
    }
</script>

<script>
    $('#installment').on('keyup keypress', function (e) {
        if ((e.keyCode || e.which) === 13) { e.preventDefault(); return false; }
    });
</script>

{{-- Invoice Wish cheques --}}
<script>
let dataArray  = [];
let totalValue = 0;
function isEmptyOrSpaces(str) { return str === null || str.match(/^ *$/) !== null; }

$(document).on("click", "#add-item", function () {
    let bank_name      = $('#bank_name').val();
    let cheque_date    = $('#cheque_date').val();
    let cheque_no      = $('#cheque_no').val();
    let cheque_ammount = $('#cheque_ammount').val();
    if (isEmptyOrSpaces(bank_name)||isEmptyOrSpaces(cheque_date)||isEmptyOrSpaces(cheque_no)||isEmptyOrSpaces(cheque_ammount)) {
        alert("Please fill all cheque fields."); return;
    }
    dataArray.push({ bank_name, cheque_date, cheque_no, cheque_ammount });
    $("#multi_cheques_table_show tbody").append(`
        <tr>
            <td><input type="text" value="${bank_name}"      name="bank_name[]"      class="form-control" readonly></td>
            <td><input type="date" value="${cheque_date}"    name="cheque_date[]"    class="form-control" readonly></td>
            <td><input type="text" value="${cheque_no}"      name="cheque_no[]"      class="form-control" readonly></td>
            <td><input type="text" value="${cheque_ammount}" name="cheque_ammount[]" class="form-control" readonly></td>
            <td><button type="button" class="btn btn-outline-danger btn-sm remove-input-field"><i class="far fa-trash-alt"></i></button></td>
        </tr>`);
    totalValue = parseFloat(totalValue) + parseFloat(cheque_ammount);
    setTotal();
    $('#bank_name').val(''); $('#cheque_date').val(''); $('#cheque_no').val(''); $('#cheque_ammount').val('');
});
$(document).on("click", ".remove-input-field", function () {
    let row = $(this).closest("tr");
    totalValue -= parseFloat(row.find("input[name='cheque_ammount[]']").val());
    setTotal(); row.remove();
});
function setTotal() {
    $(".total-value").html(`<strong>${totalValue.toFixed(2)}</strong>`);
    $("#total_cheque_amount").val(totalValue.toFixed(2));
}
</script>

{{-- Total Credit cheques --}}
<script>
let tcpDataArray  = [];
let tcpTotalValue = 0;
$(document).on("click", "#tcp_add_cheque", function () {
    let bank_name   = $('#tcp_bank_name').val();
    let cheque_date = $('#tcp_cheque_date').val();
    let cheque_no   = $('#tcp_cheque_no').val();
    let cheque_amt  = $('#tcp_cheque_amount').val();
    if (!bank_name||!cheque_date||!cheque_no||!cheque_amt) { alert("Please fill all cheque fields."); return; }
    tcpDataArray.push({ bank_name, cheque_date, cheque_no, cheque_ammount: cheque_amt });
    $("#tcp_cheques_table_show tbody").append(`
        <tr>
            <td><input type="text" value="${bank_name}"   name="tcp_bank_name[]"     class="form-control" readonly></td>
            <td><input type="date" value="${cheque_date}" name="tcp_cheque_date[]"   class="form-control" readonly></td>
            <td><input type="text" value="${cheque_no}"   name="tcp_cheque_no[]"     class="form-control" readonly></td>
            <td><input type="text" value="${cheque_amt}"  name="tcp_cheque_amount[]" class="form-control" readonly></td>
            <td><button type="button" class="btn btn-outline-danger btn-sm tcp-remove-cheque"><i class="far fa-trash-alt"></i></button></td>
        </tr>`);
    tcpTotalValue = parseFloat(tcpTotalValue) + parseFloat(cheque_amt);
    setTcpTotal();
    $('#tcp_bank_name').val(''); $('#tcp_cheque_date').val(''); $('#tcp_cheque_no').val(''); $('#tcp_cheque_amount').val('');
});
$(document).on("click", ".tcp-remove-cheque", function () {
    let row = $(this).closest("tr");
    tcpTotalValue -= parseFloat(row.find("input[name='tcp_cheque_amount[]']").val());
    setTcpTotal(); row.remove();
});
function setTcpTotal() {
    $(".tcp-total-value").html(`<strong>${tcpTotalValue.toFixed(2)}</strong>`);
    $("#tcp_total_cheque_amount").val(tcpTotalValue.toFixed(2));
}
</script>

{{-- Search: populate both tabs --}}
<script>
$(document).ready(function () {
    $('#searchCustomer').on('keyup', function (e) {
        var search_string = $(this).val();
        if (!search_string || (e.keyCode != 13 && e.keyCode != 10)) return;

        // Tab 1
        $.ajax({
            url: "{{ route('get_customer_sales_data_using_no_ajax') }}",
            method: 'GET',
            data: { search_string },
            success: function (response) {
                $('#data_table tbody').html('');
                if (response.status == 'success') {
                    response.data.forEach(record => {
                        let credit  = parseFloat(record.credit_payment) || 0;
                        let paid    = parseFloat(record.paid_amount)    || 0;
                        let balance = credit - paid;
                        let dis = balance <= 0 ? 'disabled style="pointer-events:none;opacity:0.6;"' : '';
                        $('#data_table tbody').append(`
                            <tr>
                                <td>${record.Customer_Name}</td>
                                <td>${record.Invoice_no}</td>
                                <td>${record.Invoice_date}</td>
                                <td>${credit.toFixed(2)}</td>
                                <td>${paid.toFixed(2)}</td>
                                <td style="color:#ee1212;font-weight:bold;">${balance.toFixed(2)}</td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-success make_payment_form ${balance<=0?'disabled':''}"
                                        ${dis}
                                        data-bs-toggle="modal" data-bs-target="#makePaymentModel"
                                        data-invoice_no='${record.Invoice_no}'
                                        data-invoice_date='${record.Invoice_date}'
                                        data-customer_code='${record.Customer_NIC}'
                                        data-customer_name='${record.Customer_Name}'
                                        data-customer_phone='${record.Customer_Phone}'
                                        data-credit_payment='${credit.toFixed(2)}'
                                        data-balance_credit_payment='${balance.toFixed(2)}'>
                                        Make Payment&nbsp;<i class="fas fa-plus"></i>
                                    </a>
                                </td>
                            </tr>`);
                    });
                    $('#customer_name').val(response.data[0]?.Customer_Name  || '');
                    $('#customer_phone').val(response.data[0]?.Customer_Phone || '');
                } else {
                    $('#data_table tbody').append(`
                        <tr><td colspan="7" class="text-center text-danger"
                            style="font-size:16px;background:rgb(236,206,206);">
                            <strong>No invoices found or fully paid!</strong>
                        </td></tr>`);
                }
            },
            error: function () { alert("Error fetching invoice data."); }
        });

        // Tab 2
        loadTotalCreditSection(search_string);
    });
});

function loadTotalCreditSection(customer_code) {
    $('#total_credit_table tbody').html('');
    $('#total_balance_display').hide();
    $.ajax({
        url: "{{ route('get_customer_total_credit_balance_ajax') }}",
        method: 'GET',
        data: { search_string: customer_code },
        success: function (response) {
            if (response.status == 'success') {
                let d = response.data;
                let cr = parseFloat(d.total_cr_amount) || 0;
                let dr = parseFloat(d.total_dr_amount) || 0;
                let balance = cr - dr;
                let dis = balance <= 0 ? 'disabled style="pointer-events:none;opacity:0.6;"' : '';
                $('#total_credit_table tbody').append(`
                    <tr>
                        <td>${d.customer_code}</td>
                        <td>${d.customer_name}</td>
                        <td>${cr.toFixed(2)}</td>
                        <td>${dr.toFixed(2)}</td>
                        <td style="color:#ee1212;font-weight:bold;">${balance.toFixed(2)}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary make_total_credit_payment ${balance<=0?'disabled':''}"
                                ${dis}
                                data-bs-toggle="modal" data-bs-target="#makeTotalCreditPaymentModel"
                                data-customer_code='${d.customer_code}'
                                data-customer_name='${d.customer_name}'
                                data-customer_phone='${d.customer_phone}'
                                data-total_cr='${cr.toFixed(2)}'
                                data-total_dr='${dr.toFixed(2)}'
                                data-balance='${balance.toFixed(2)}'>
                                Make Payment&nbsp;<i class="fas fa-plus"></i>
                            </a>
                        </td>
                    </tr>`);
                $('#total_balance_value').text(balance.toFixed(2));
                $('#total_balance_display').show();
            } else {
                $('#total_credit_table tbody').append(`
                    <tr><td colspan="6" class="text-center text-muted">No outstanding balance found.</td></tr>`);
            }
        }
    });
}
</script>

{{-- Invoice Wish: fill modal + save --}}
<script>
$(document).ready(function () {
    $(document).on('click', '.make_payment_form', function () {
        $('#up_payment_date').val($('#payment_date').val());
        $('#up_customer_code').val($(this).data('customer_code'));
        $('#up_customer_name').val($(this).data('customer_name'));
        $('#up_customer_phone').val($(this).data('customer_phone'));
        $('#up_sales_no').val($(this).data('invoice_no'));
        $('#up_sales_date').val($(this).data('invoice_date'));
        $('#up_amount').val($(this).data('credit_payment'));
        $('#balance_credit_payment').val($(this).data('balance_credit_payment'));
    });

    $(document).on('click', '#saveSupPayment', function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('make_customer_payment') }}",
            method: 'POST',
            data: {
                "_token":            "{{ csrf_token() }}",
                payment_no:          $('#payment_no').val(),
                payment_date:        $('#up_payment_date').val(),
                customer_code:       $('#up_customer_code').val(),
                customer_name:       $('#up_customer_name').val(),
                customer_phone:      $('#up_customer_phone').val(),
                sales_no:            $('#up_sales_no').val(),
                sales_date:          $('#up_sales_date').val(),
                payment_note:        $('#up_payment_note').val(),
                paying_amount:       $('#up_paying_amount').val(),
                amount:              $('#up_amount').val(),
                cash_payment:        $('#cash_payment').val(),
                card_payment:        $('#card_payment').val(),
                bank_transfer:       $('#bank_transfer').val(),
                total_cheque_amount: $('#total_cheque_amount').val(),
                dataArray:           JSON.stringify(dataArray),
            },
            success: function (res) {
                if (res.status == 'success') {
                    $("#makePaymentModel").modal('hide');
                    $('#makeSupPayment')[0].reset();
                    toastr.success("Payment saved successfully!", "Success");
                    setTimeout(() => window.location.reload(), 1200);
                }
            },
            error: function (err) {
                $('.errMsgContainer2').html('');
                let error = err.responseJSON;
                if (error && error.errors) {
                    $.each(error.errors, function (i, v) {
                        $('.errMsgContainer2').append('<span class="text-danger">'+v+'</span><br>');
                    });
                }
            }
        });
    });
});
</script>

{{-- Total Credit: fill modal + save --}}
<script>
$(document).ready(function () {
    $(document).on('click', '.make_total_credit_payment', function () {
        $('#tcp_payment_date').val($('#payment_date').val());
        $('#tcp_customer_code').val($(this).data('customer_code'));
        $('#tcp_customer_name').val($(this).data('customer_name'));
        $('#tcp_customer_phone').val($(this).data('customer_phone'));
        $('#tcp_total_cr').val($(this).data('total_cr'));
        $('#tcp_total_dr').val($(this).data('total_dr'));
        $('#tcp_disp_code').val($(this).data('customer_code'));
        $('#tcp_disp_name').val($(this).data('customer_name'));
        $('#tcp_disp_cr').val($(this).data('total_cr'));
        $('#tcp_disp_dr').val($(this).data('total_dr'));
        $('#tcp_disp_balance').val($(this).data('balance'));
        tcpDataArray = []; tcpTotalValue = 0; setTcpTotal();
        $('#tcp_cheques_table_show tbody tr:gt(0)').remove();
    });

    $(document).on('click', '#saveTotalCreditPayment', function (e) {
        e.preventDefault();
        let paying_amount = $('#tcp_paying_amount').val();
        let payment_date  = $('#tcp_payment_date').val();
        if (!paying_amount) { alert("Please enter the paying amount."); return; }
        if (!payment_date)  { alert("Please select the payment date.");  return; }
        $.ajax({
            url: "{{ route('make_customer_total_credit_payment') }}",
            method: 'POST',
            data: {
                "_token":            "{{ csrf_token() }}",
                payment_no:          $('#tcp_payment_no').val(),
                payment_date,
                customer_code:       $('#tcp_customer_code').val(),
                customer_name:       $('#tcp_customer_name').val(),
                customer_phone:      $('#tcp_customer_phone').val(),
                total_cr:            $('#tcp_total_cr').val(),
                total_dr:            $('#tcp_total_dr').val(),
                payment_note:        $('#tcp_payment_note').val(),
                paying_amount,
                cash_payment:        $('#tcp_cash_payment').val(),
                card_payment:        $('#tcp_card_payment').val(),
                bank_transfer:       $('#tcp_bank_transfer').val(),
                total_cheque_amount: $('#tcp_total_cheque_amount').val(),
                dataArray:           JSON.stringify(tcpDataArray),
            },
            success: function (res) {
                if (res.status == 'success') {
                    $("#makeTotalCreditPaymentModel").modal('hide');
                    $('#makeTotalCreditPaymentForm')[0].reset();
                    toastr.success("Total Credit Payment saved!", "Success");
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    toastr.error(res.message || "Save failed.", "Error");
                }
            },
            error: function (err) {
                $('#errMsgContainer_tcp').html('');
                let error = err.responseJSON;
                if (error && error.errors) {
                    $.each(error.errors, function (i, v) {
                        $('#errMsgContainer_tcp').append('<span class="text-danger">'+v+'</span><br>');
                    });
                } else if (error && error.message) {
                    $('#errMsgContainer_tcp').html('<span class="text-danger">'+error.message+'</span>');
                }
            }
        });
    });
});
</script>

<script src="assets/js/feather.min.js"></script>
<script src="assets/js/toastr.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>
</body>
</html>
@endsection