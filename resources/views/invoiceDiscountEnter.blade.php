{{-- @extends('layouts.app') --}}
@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice Discount Entry</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        /* ===========================================================
           Invoice Discount Entry — design tokens
           A blue-led "ledger" aesthetic: cool slate-blue paper surface,
           deep navy text, a strong azure accent, and every monetary
           figure set in tabular monospace so columns of numbers line
           up like a real statement.
        =========================================================== */
        :root{
            --idc-paper:      #EFF4FA;
            --idc-surface:    #FFFFFF;
            --idc-border:     #DCE6F2;
            --idc-border-soft:#E9F0F9;
            --idc-ink:        #14233D;
            --idc-ink-soft:   #4A6082;
            --idc-muted:      #8496AF;
            --idc-accent:     #1565D8;
            --idc-accent-dark:#0D4AA8;
            --idc-accent-light:#3B82F6;
            --idc-accent-bg:  #E8F0FE;
            --idc-navy:       #0B2A54;
            --idc-good:       #1478A0;
            --idc-good-bg:    #E4F3F8;
            --idc-due:        #C0362C;
            --idc-due-bg:     #FBEAE8;
            --idc-radius:     10px;
            --idc-shadow:     0 1px 2px rgba(11,42,84,.05), 0 8px 22px rgba(11,42,84,.08);
        }

        html, body.nk-body{
            background: var(--idc-paper) !important;
            color: var(--idc-ink);
        }

        .idc-wrap{
            max-width: 1180px;
            margin: 0 auto;
            padding: 28px 20px 60px;
        }

        /* ---------- Page header ---------- */
        .idc-header{
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 16px;
            margin-bottom: 24px;
            border-bottom: 2px solid var(--idc-navy);
            position: relative;
        }
        .idc-header::after{
            content:"";
            position:absolute;
            left:0; bottom:-2px;
            width: 72px;
            height: 2px;
            background: var(--idc-accent-light);
        }
        .idc-header-left{
            display:flex;
            align-items:center;
            gap: 14px;
        }
        .idc-header-icon{
            width: 44px;
            height: 44px;
            flex: none;
            border-radius: 10px;
            background: linear-gradient(140deg, var(--idc-accent), var(--idc-accent-dark));
            display:flex;
            align-items:center;
            justify-content:center;
            color:#fff;
            font-size: 18px;
            box-shadow: 0 6px 16px rgba(21,101,216,.32);
        }
        .idc-eyebrow{
            font-size: 11px;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--idc-accent);
            font-weight: 700;
            margin: 0 0 4px;
        }
        .page-title{
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.01em;
            color: var(--idc-ink);
            margin: 0;
        }
        .idc-header-meta{
            font-size: 12px;
            color: var(--idc-muted);
            text-align: right;
        }
        .idc-header-meta i{ color: var(--idc-accent); margin-right: 5px; }

        /* ---------- Cards ---------- */
        .idc-card{
            background: var(--idc-surface);
            border: 1px solid var(--idc-border);
            border-radius: var(--idc-radius);
            box-shadow: var(--idc-shadow);
            margin-bottom: 20px;
            overflow: visible;
        }
        .idc-card .card-body{ padding: 22px 24px; }
        .idc-card-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding: 16px 24px;
            border-bottom: 1px solid var(--idc-border-soft);
        }
        .idc-card-header h5{
            margin:0;
            font-size: 14px;
            font-weight: 700;
            letter-spacing:.02em;
            text-transform: uppercase;
            color: var(--idc-ink-soft);
            display:flex;
            align-items:center;
            gap:9px;
        }
        .idc-card-header h5 i{
            color: var(--idc-accent);
            font-size: 13px;
        }
        .idc-card-header .idc-count{
            font-size:12px;
            color: var(--idc-accent);
            background: var(--idc-accent-bg);
            padding: 3px 10px;
            border-radius: 20px;
            font-weight:500;
        }
        .idc-card-header .idc-count:empty{ display:none; }

        /* ---------- Form fields ---------- */
        .form-label{
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .02em;
            color: var(--idc-ink-soft);
            margin-bottom: 6px;
            display:flex;
            align-items:center;
            gap:6px;
        }
        .form-label i{ color: var(--idc-accent-light); font-size: 11px; }
        .idc-card .form-control{
            border: 1px solid var(--idc-border);
            border-radius: 7px;
            padding: 9px 12px;
            font-size: 14px;
            color: var(--idc-ink);
            background: #FCFCFA;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .idc-card .form-control:focus{
            border-color: var(--idc-accent);
            box-shadow: 0 0 0 3px rgba(15,76,129,.12);
            background: #fff;
        }
        .idc-card .form-control[readonly]{
            background: var(--idc-border-soft);
            color: var(--idc-ink-soft);
        }
        #customer_outstanding_balance,
        #discount_current_balance,
        #selected_customer_code{
            font-weight: 500;
        }

        /* ---------- Autocomplete dropdown ---------- */
        #customer_list{
            border: 1px solid var(--idc-border);
            border-radius: 8px;
            margin-top: 4px;
            box-shadow: var(--idc-shadow);
            overflow: hidden;
            max-height: 280px;
            overflow-y: auto;
        }
        #customer_list .list-group-item{
            border: none;
            border-bottom: 1px solid var(--idc-border-soft);
            padding: 10px 14px;
            font-size: 13.5px;
            color: var(--idc-ink);
        }
        #customer_list .list-group-item:last-child{ border-bottom: none; }
        #customer_list .list-group-item-action:hover{
            background: var(--idc-good-bg);
        }
        #customer_list .list-group-item strong{
            color: var(--idc-accent-dark);
            margin-right: 4px;
        }
        #customer_list .list-group-item i{
            color: var(--idc-accent);
            margin-right: 8px;
            width: 14px;
        }

        .idc-input-icon{ position: relative; }
        .idc-input-icon i{
            position: absolute;
            left: 13px;
            top: 38px;
            color: var(--idc-muted);
            font-size: 13px;
            pointer-events: none;
        }
        .idc-input-icon .form-control{ padding-left: 34px; }

        /* ---------- Table ---------- */
        .table-responsive{ border-radius: 8px; }
        #invoices_table{
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }
        #invoices_table thead th{
            background: linear-gradient(90deg, var(--idc-navy), var(--idc-accent-dark));
            color: #EAF1FD;
            font-size: 11px;
            letter-spacing: .06em;
            text-transform: uppercase;
            font-weight: 600;
            border: none;
            padding: 12px 16px;
            white-space: nowrap;
        }
        #invoices_table thead th:first-child{ border-top-left-radius: 8px; }
        #invoices_table thead th:last-child{ border-top-right-radius: 8px; text-align:center; }
        #invoices_table tbody td{
            padding: 13px 16px;
            font-size: 13.5px;
            color: var(--idc-ink);
            border-bottom: 1px solid var(--idc-border-soft);
            border-top: none;
            vertical-align: middle;
        }
        #invoices_table tbody tr:hover td{ background: #FBFAF6; }
        #invoices_table tbody tr:last-child td{ border-bottom: none; }

        /* money columns line up like a ledger */
        #invoices_table td:nth-child(3),
        #invoices_table td:nth-child(4),
        #invoices_table td:nth-child(5),
        #invoices_table td:nth-child(6){
            font-size: 13px;
            text-align: right;
            font-variant-numeric: tabular-nums;
        }
        #invoices_table td:last-child{ text-align: center; }
        #invoices_table td:first-child{
            font-weight: 600;
            color: var(--idc-accent-dark);
        }

        .balance-cell.is-clear{ color: var(--idc-good); font-weight: 600; }
        .balance-cell.is-due{ color: var(--idc-due); font-weight: 600; }
        .balance-cell i{ margin-right: 5px; font-size: 11px; }

        /* ---------- Buttons ---------- */
        .btn-primary,
        #confirm_discount_btn,
        #confirm_transport_btn{
            background: var(--idc-accent);
            border-color: var(--idc-accent);
            font-weight: 600;
            font-size: 13px;
            letter-spacing: .01em;
            border-radius: 7px;
            padding: 8px 16px;
        }
        .btn-primary:hover,
        #confirm_discount_btn:hover,
        #confirm_transport_btn:hover{
            background: var(--idc-accent-dark);
            border-color: var(--idc-accent-dark);
        }
        .btn-secondary{
            background: transparent;
            border-color: var(--idc-border);
            color: var(--idc-ink-soft);
            font-weight: 600;
            font-size: 13px;
            border-radius: 7px;
        }
        .btn-secondary:hover{
            background: var(--idc-border-soft);
            color: var(--idc-ink);
        }
        .edit-discount-btn{
            background: transparent;
            border: 1px solid var(--idc-accent);
            color: var(--idc-accent);
            font-weight: 600;
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 6px;
        }
        .edit-discount-btn i{ margin-right: 6px; }
        .edit-discount-btn:hover{
            background: var(--idc-accent);
            color: #fff;
        }
        .transport-payment-btn{
            background: transparent;
            border: 1px solid var(--idc-good);
            color: var(--idc-good);
            font-weight: 600;
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 6px;
            margin-left: 6px;
        }
        .transport-payment-btn i{ margin-right: 6px; }
        .transport-payment-btn:hover{
            background: var(--idc-good);
            color: #fff;
        }
        .modal-title{ display:flex; align-items:center; gap:9px; }
        .modal-title i{
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: var(--idc-accent-bg);
            color: var(--idc-accent);
            display:inline-flex;
            align-items:center;
            justify-content:center;
            font-size: 13px;
        }
        #confirm_discount_btn i, #confirm_transport_btn i, .btn-secondary i{ margin-right: 6px; }
        .idc-empty-state{
            text-align: center;
            color: var(--idc-muted);
            font-size: 13.5px;
            padding: 40px 16px !important;
        }
        .idc-empty-state i{
            display:block;
            font-size: 22px;
            margin-bottom: 8px;
            color: var(--idc-border);
        }

        /* ---------- Modal ---------- */
        .modal-content{
            border: none;
            border-radius: var(--idc-radius);
            box-shadow: 0 20px 60px rgba(15,20,30,.25);
        }
        .modal-header{
            border-bottom: 1px solid var(--idc-border-soft);
            padding: 18px 24px;
        }
        .modal-title{
            font-size: 15px;
            font-weight: 700;
            color: var(--idc-ink);
        }
        .modal-body{ padding: 22px 24px; }
        .modal-footer{
            border-top: 1px solid var(--idc-border-soft);
            padding: 16px 24px;
        }
        #discount_invoice_no_display,
        #transport_invoice_no_display{
            font-weight: 600;
            color: var(--idc-accent-dark);
        }
        #discount_amount_input,
        #transport_amount_input{
            font-size: 15px;
        }
        #discount_error,
        #transport_error{
            background: var(--idc-due-bg);
            color: var(--idc-due);
            border-radius: 6px;
            padding: 8px 12px;
            font-weight: 500;
        }

        @media (max-width: 767px){
            .idc-header{ flex-direction: column; align-items: flex-start; }
            .idc-header-meta{ text-align: left; }
        }
    </style>
</head>

<body class="nk-body bg-lighter npc-default has-sidebar no-touch nk-nio-theme">
<div class="main-wrapper">
    <div class="page-wrapper">
<div class="content container-fluid">
<div class="idc-wrap">

    <div class="idc-header">
        <div class="idc-header-left">
            <div class="idc-header-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
            <div>
                <p class="idc-eyebrow">Accounts Receivable</p>
                <h4 class="page-title">Invoice Discount Entry</h4>
            </div>
        </div>
        <div class="idc-header-meta">
            <i class="fa-regular fa-calendar"></i><span id="idc-today"></span>
        </div>
    </div>

    <!-- Customer Search Card -->
    <div class="idc-card">
        <div class="idc-card-header">
            <h5><i class="fa-solid fa-magnifying-glass"></i>Customer Lookup</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6 position-relative idc-input-icon">
                    <label for="customer_search" class="form-label"><i class="fa-solid fa-user"></i>Search Customer (Code, Name, NIC, or Phone)</label>
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="customer_search" class="form-control" autocomplete="off" placeholder="Type to search...">
                    <div id="customer_list" class="list-group position-absolute w-100 shadow" style="z-index: 1000; display: none;"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><i class="fa-solid fa-id-card"></i>Selected Customer Code</label>
                    <input type="text" id="selected_customer_code" class="form-control" readonly>
                </div>
                <div class="col-md-3">
                    <label class="form-label"><i class="fa-solid fa-scale-balanced"></i>Total Outstanding Balance</label>
                    <input type="text" id="customer_outstanding_balance" class="form-control" readonly>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices Table Card -->
    <div class="idc-card">
        <div class="idc-card-header">
            <h5><i class="fa-solid fa-file-lines"></i>Customer Invoices</h5>
            <span class="idc-count" id="idc-invoice-count"></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0" id="invoices_table">
                    <thead>
                        <tr>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Gross Amount</th>
                            <th>Discount</th>
                            <th>Net Amount</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7" class="idc-empty-state">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Select a customer to display invoices
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Apply Discount Modal -->
<div class="modal fade" id="discountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa-solid fa-tag"></i>Apply Discount</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="discount_invoice_id">
        <input type="hidden" id="discount_invoice_no">
        <input type="hidden" id="discount_invoice_date">

        <div class="mb-3">
            <label class="form-label"><i class="fa-solid fa-hashtag"></i>Invoice No</label>
            <input type="text" id="discount_invoice_no_display" class="form-control" readonly>
        </div>
        <div class="mb-3" style="display: none">
            <label class="form-label">Current Balance</label>
            <input type="text" id="discount_current_balance" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label"><i class="fa-solid fa-percent"></i>Discount Amount</label>
            <input type="number" step="0.01" min="0.01" id="discount_amount_input" class="form-control">
        </div>
        <div id="discount_error" class="text-danger small" style="display:none;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i>Cancel</button>
        <button type="button" class="btn btn-primary" id="confirm_discount_btn"><i class="fa-solid fa-check"></i>Confirm</button>
      </div>
    </div>
  </div>
</div>

<!-- Transport Payment Modal -->
<div class="modal fade" id="transportModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fa-solid fa-truck"></i>Transport Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="transport_invoice_id">
        <input type="hidden" id="transport_invoice_no">
        <input type="hidden" id="transport_invoice_date">

        <div class="mb-3">
            <label class="form-label"><i class="fa-solid fa-hashtag"></i>Invoice No</label>
            <input type="text" id="transport_invoice_no_display" class="form-control" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label"><i class="fa-solid fa-money-bill-transfer"></i>Transport Amount</label>
            <input type="number" step="0.01" min="0.01" id="transport_amount_input" class="form-control">
        </div>
        <div id="transport_error" class="text-danger small" style="display:none;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i>Cancel</button>
        <button type="button" class="btn btn-primary" id="confirm_transport_btn"><i class="fa-solid fa-check"></i>Confirm</button>
      </div>
    </div>
  </div>
</div>
    </div>
</div>

{{-- ════ Scripts ════ --}}
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/apexchart/apexcharts.min.js"></script>

<script src="assets/js/feather.min.js"></script>
<script src="assets/js/toastr.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    let timer = null;

    // Header date stamp
    $('#idc-today').text(new Date().toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }));

    // Customer Search Autocomplete
    $('#customer_search').on('keyup', function () {
        clearTimeout(timer);
        let query = $(this).val();

        if (query.length < 2) {
            $('#customer_list').hide();
            return;
        }

        timer = setTimeout(function () {
            $.ajax({
                url: "{{ route('customers.search') }}",
                type: "GET",
                data: { term: query },
                success: function (data) {
                    let dropdown = $('#customer_list');
                    dropdown.empty().show();

                    if (data.length === 0) {
                        dropdown.append('<div class="list-group-item">No customers found</div>');
                        return;
                    }

                    $.each(data, function (key, customer) {
                        let displayName = customer.First_name || customer.Name;
                        dropdown.append(`
                            <a href="#" class="list-group-item list-group-item-action select-customer"
                               data-code="${customer.Code}"
                               data-name="${displayName}">
                                <i class="fa-solid fa-user"></i><strong>${customer.Code}</strong> - ${displayName} (NIC: ${customer.NIC || 'N/A'})
                            </a>
                        `);
                    });
                }
            });
        }, 300);
    });

    // Handle Customer Selection
    $(document).on('click', '.select-customer', function (e) {
        e.preventDefault();
        let code = $(this).data('code');
        let name = $(this).data('name');

        $('#customer_search').val(`${code} - ${name}`);
        $('#selected_customer_code').val(code);
        $('#customer_list').hide();

        fetchInvoices(code);
        fetchCustomerBalance(code);
    });

    // Fetch Invoices Function
    function fetchInvoices(customerCode) {
        $.ajax({
            url: "{{ route('invoices.by-customer') }}",
            type: "GET",
            data: { customer_code: customerCode },
            success: function (invoices) {
                let tbody = $('#invoices_table tbody');
                tbody.empty();
                $('#idc-invoice-count').text(invoices.length ? invoices.length + ' invoice' + (invoices.length === 1 ? '' : 's') : '');

                if (invoices.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="7" class="idc-empty-state">
                                <i class="fa-solid fa-file-circle-xmark"></i>
                                No invoices found for this customer.
                            </td>
                        </tr>
                    `);
                    return;
                }

                $.each(invoices, function (index, invoice) {
                    let balance = parseFloat(invoice.Balance || 0);
                    let balanceClass = balance > 0 ? 'is-due' : 'is-clear';
                    let balanceIcon = balance > 0 ? 'fa-triangle-exclamation' : 'fa-circle-check';

                    tbody.append(`
                        <tr data-id="${invoice.id}">
                            <td>${invoice.Invoice_no}</td>
                            <td>${invoice.Invoice_date}</td>
                            <td>${parseFloat(invoice.Gross_Amount || 0).toFixed(2)}</td>
                            <td class="discount-cell">${parseFloat(invoice.after_customer_discount || 0).toFixed(2)}</td>
                            <td class="net-cell">${parseFloat(invoice.Net_Amount || 0).toFixed(2)}</td>
                            <td class="balance-cell ${balanceClass}"><i class="fa-solid ${balanceIcon}"></i>${balance.toFixed(2)}</td>
                            <td>
                             <button class="btn btn-sm edit-discount-btn"
                                data-id="${invoice.id}"
                                data-invoice-no="${invoice.Invoice_no}"
                                data-invoice-date="${invoice.Invoice_date}"
                                data-balance="${invoice.Balance}">
                                <i class="fa-solid fa-tag"></i>Apply Discount
                            </button>
                             <button class="btn btn-sm transport-payment-btn"
                                data-id="${invoice.id}"
                                data-invoice-no="${invoice.Invoice_no}"
                                data-invoice-date="${invoice.Invoice_date}">
                                <i class="fa-solid fa-truck"></i>Transport
                            </button>
                            </td>
                        </tr>
                    `);
                });
            }
        });
    }

    // Fetch Customer Balance Function
    function fetchCustomerBalance(customerCode) {
        $.ajax({
            url: "{{ route('customers.balance') }}",
            type: "GET",
            data: { customer_code: customerCode },
            success: function (res) {
                let field = $('#customer_outstanding_balance');
                field.val(res.outstanding_balance);

                field.removeClass('text-danger text-success fw-bold');
                if (parseFloat(res.outstanding_balance) > 0) {
                    field.addClass('text-danger fw-bold');
                } else if (parseFloat(res.advance) > 0) {
                    field.addClass('text-success fw-bold');
                }
            }
        });
    }

    // Open discount modal
    $(document).on('click', '.edit-discount-btn', function () {
        $('#discount_invoice_id').val($(this).data('id'));
        $('#discount_invoice_no').val($(this).data('invoice-no'));
        $('#discount_invoice_date').val($(this).data('invoice-date'));
        $('#discount_invoice_no_display').val($(this).data('invoice-no'));
        $('#discount_current_balance').val(parseFloat($(this).data('advance')).toFixed(2));
        $('#discount_amount_input').val('');
        $('#discount_error').hide().text('');

        new bootstrap.Modal(document.getElementById('discountModal')).show();
    });

    // Confirm discount submission
    $('#confirm_discount_btn').on('click', function () {
        let amount = parseFloat($('#discount_amount_input').val());

        if (!amount || amount <= 0) {
            $('#discount_error').text('Enter a valid discount amount.').show();
            return;
        }

        let customerCode = $('#selected_customer_code').val();
        let btn = $(this);
        btn.prop('disabled', true).text('Applying...');

        $.ajax({
            url: "{{ route('invoices.apply-discount') }}",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                invoice_id: $('#discount_invoice_id').val(),
                invoice_no: $('#discount_invoice_no').val(),
                invoice_date: $('#discount_invoice_date').val(),
                customer_code: customerCode,
                discount_amount: amount
            },
            success: function (res) {
                btn.prop('disabled', false).text('Confirm');

                if (!res.success) {
                    $('#discount_error').text(res.message).show();
                    return;
                }

                let row = $(`tr[data-id="${$('#discount_invoice_id').val()}"]`);
                row.find('.discount-cell').text(res.discount);
                row.find('.net-cell').text(res.net_amount);
                let balanceCell = row.find('.balance-cell');
                let stillDue = parseFloat(res.balance) > 0;
                let balanceIconClass = stillDue ? 'fa-triangle-exclamation' : 'fa-circle-check';
                balanceCell.html(`<i class="fa-solid ${balanceIconClass}"></i>${res.balance}`);
                balanceCell.toggleClass('is-due', stillDue);
                balanceCell.toggleClass('is-clear', !stillDue);
                row.find('.edit-discount-btn').data('balance', res.balance);

                bootstrap.Modal.getInstance(document.getElementById('discountModal')).hide();
                toastr.success(res.message);

                // Refresh outstanding balance since a new ledger entry was posted
                fetchCustomerBalance(customerCode);
            },
            error: function (xhr) {
                btn.prop('disabled', false).text('Confirm');
                let msg = xhr.responseJSON?.message || 'Something went wrong.';
                $('#discount_error').text(msg).show();
            }
        });
    });

    // Open transport payment modal
    $(document).on('click', '.transport-payment-btn', function () {
        $('#transport_invoice_id').val($(this).data('id'));
        $('#transport_invoice_no').val($(this).data('invoice-no'));
        $('#transport_invoice_date').val($(this).data('invoice-date'));
        $('#transport_invoice_no_display').val($(this).data('invoice-no'));
        $('#transport_amount_input').val('');
        $('#transport_error').hide().text('');

        new bootstrap.Modal(document.getElementById('transportModal')).show();
    });

    // Confirm transport payment submission
    $('#confirm_transport_btn').on('click', function () {
        let amount = parseFloat($('#transport_amount_input').val());

        if (!amount || amount <= 0) {
            $('#transport_error').text('Enter a valid transport amount.').show();
            return;
        }

        let customerCode = $('#selected_customer_code').val();
        let btn = $(this);
        btn.prop('disabled', true).text('Saving...');

        $.ajax({
            url: "{{ route('invoices.apply-transport') }}",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                invoice_id: $('#transport_invoice_id').val(),
                invoice_no: $('#transport_invoice_no').val(),
                invoice_date: $('#transport_invoice_date').val(),
                customer_code: customerCode,
                transport_amount: amount
            },
            success: function (res) {
                btn.prop('disabled', false).text('Confirm');

                if (!res.success) {
                    $('#transport_error').text(res.message).show();
                    return;
                }

                bootstrap.Modal.getInstance(document.getElementById('transportModal')).hide();
                toastr.success(res.message);

                // Refresh outstanding balance since a new ledger entry was posted
                fetchCustomerBalance(customerCode);
            },
            error: function (xhr) {
                btn.prop('disabled', false).text('Confirm');
                let msg = xhr.responseJSON?.message || 'Something went wrong.';
                $('#transport_error').text(msg).show();
            }
        });
    });

    // Close autocomplete on click outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#customer_search, #customer_list').length) {
            $('#customer_list').hide();
        }
    });
});
</script>
</body>
</html>
@endsection