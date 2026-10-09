@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<title>Create Sales Invoice</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

<link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<style>
.item-description-wrapper { display: inline-block; max-width: 400px; white-space: normal; }

/* "Batch" tag — shown next to the item name wherever a batch-tracked item appears */
.batch-tag {
    display:inline-flex; align-items:center; gap:4px;
    background:#e8f0fe; color:#2d6ce9;
    border-radius:20px; padding:1px 9px; margin-left:6px;
    font-size:10.5px; font-weight:700; white-space:nowrap; vertical-align:middle;
}
.batch-tag i { font-size:9px; }
#dynamicAdded thead th { background: #458ada; color: #fff; text-align: center; }
#dynamicAdded {
    table-layout: fixed;
    background: #fff;
}
#dynamicAdded td {
    padding: 12px 10px;
    border-color: #d9e3ee;
    vertical-align: middle;
}
#dynamicAdded td:nth-child(1) { width: 22%; }
#dynamicAdded td:nth-child(2) { width: 20%; }
#dynamicAdded td:nth-child(3) { width: 12%; }
#dynamicAdded td:nth-child(4) { width: 12%; }
#dynamicAdded td:nth-child(5) { width: 18%; }
#dynamicAdded td:nth-child(6) { width: 18%; }
#dynamicAdded input.form-control {
    min-height: 42px;
    border: 1px solid #d7e3f1;
    border-radius: 10px;
    text-align: center;
}
.sales-dynamic-table,
.sales-totals-table {
    width: 100% !important;
    margin: 10px 0 0 !important;
    border: 1px solid #d9e3ee !important;
    border-radius: 12px;
    overflow: hidden;
}
.sales-totals-table {
    background: #fff;
    margin-top: 8px !important;
}
.sales-totals-table .table-success,
.sales-totals-table .table-success > td {
    background: #edf4ff !important;
    color: #234b7d;
    border-color: #d9e3ee !important;
}
/* Keep the entry row, added rows, and totals on one shared six-column grid. */
#salesItemTable,
#dynamicAdded,
.sales-totals-table {
    table-layout: fixed;
}
#salesItemTable th:nth-child(1), #salesItemTable td:nth-child(1),
#dynamicAdded td:nth-child(1), .sales-totals-table td:nth-child(1) { width: 22% !important; }
#salesItemTable th:nth-child(2), #salesItemTable td:nth-child(2),
#dynamicAdded td:nth-child(2), .sales-totals-table td:nth-child(2) { width: 20% !important; }
#salesItemTable th:nth-child(3), #salesItemTable td:nth-child(3),
#dynamicAdded td:nth-child(3), .sales-totals-table td:nth-child(3) { width: 12% !important; }
#salesItemTable th:nth-child(4), #salesItemTable td:nth-child(4),
#dynamicAdded td:nth-child(4), .sales-totals-table td:nth-child(4) { width: 12% !important; }
#salesItemTable th:nth-child(5), #salesItemTable td:nth-child(5),
#dynamicAdded td:nth-child(5), .sales-totals-table td:nth-child(5) { width: 18% !important; }
#salesItemTable th:nth-child(6), #salesItemTable td:nth-child(6),
#dynamicAdded td:nth-child(6), .sales-totals-table td:nth-child(6) { width: 18% !important; }
.sales-dynamic-table td:last-child,
.sales-totals-table td:last-child { text-align: center; }
.sales-dynamic-table .remove-new-row,
.sales-dynamic-table .remove-row { margin-left: auto; margin-right: auto; }
.sales-dynamic-table .sales-row-delete {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    padding: 0;
    margin: 0 auto;
    color: #dc3545;
    background: #fff;
    border: 1px solid #dc3545;
    border-radius: 10px;
}
.sales-dynamic-table .sales-row-delete:hover {
    color: #fff;
    background: #dc3545;
}
.recall-section { background: #f8f9fa; border-radius: 6px; padding: 10px 14px; }

/* Modern sales invoice header and form shell */
.sales-shell {
    border: 1px solid #dfeaf6 !important;
    background: #edf3fa;
    border-radius: 18px !important;
    overflow: hidden;
    box-shadow: 0 10px 35px rgba(31, 56, 88, 0.08) !important;
}
.sales-shell > .card-body {
    padding-top: 10px;
}
/* Compact customer picker table and reference-style pager. */
#selectCustomerModel .modal-dialog {
    max-width: 800px;
}
#selectCustomerModel .modal-content {
    border: 1px solid #dfeaf6;
    border-radius: 16px;
    overflow: hidden;
}
#selectCustomerModel .modal-header {
    padding: 14px 18px;
    background: #f5f9ff;
    border-bottom: 1px solid #dfeaf6;
}
#selectCustomerModel .modal-body {
    padding: 16px 18px 20px;
}
#selectCustomerModel .stock-search-wrap {
    max-width: 360px;
    margin-bottom: 14px !important;
}
#selectCustomerModel #ItemTable {
    width: 100% !important;
    table-layout: fixed;
    margin: 0 !important;
    border: 1px solid #d9e3ee;
    border-radius: 10px;
    overflow: hidden;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 13px;
}
#selectCustomerModel #ItemTable thead th {
    background: #fff;
    color: #2b3e5b;
    border-bottom: 1px solid #d9e3ee;
    padding: 10px 12px;
    font-size: 12px;
    font-weight: 800;
    text-align: left;
}
#selectCustomerModel #ItemTable tbody td {
    padding: 8px 12px;
    border-color: #edf1f5;
    color: #314765;
    vertical-align: middle;
}
#selectCustomerModel #ItemTable th:first-child,
#selectCustomerModel #ItemTable td:first-child { width: 24%; }
#selectCustomerModel #ItemTable th:nth-child(2),
#selectCustomerModel #ItemTable td:nth-child(2) { width: 56%; }
#selectCustomerModel #ItemTable th:last-child,
#selectCustomerModel #ItemTable td:last-child { width: 20%; text-align: center; }
#selectCustomerModel #ItemTable .dt-act-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    border-radius: 8px;
    color: #2d6ce9;
    border: 1px solid #bcd3f7;
    background: #f5f9ff;
}
#selectCustomerModel #ItemTable .dt-act-btn:hover {
    color: #fff;
    background: #2d6ce9;
}
#selectCustomerModel #ItemTableCustomPager {
    display: flex;
    justify-content: center;
    width: 100%;
    margin-top: 18px;
}
#selectCustomerModel #ItemTableCustomPager .dt-custom-pager-row {
    margin-top: 0;
    width: 100%;
    justify-content: center;
}
#selectCustomerModel #ItemTableCustomPager .dt-custom-pager {
    padding: 6px 10px;
    gap: 2px;
}
.sales-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px 22px;
    background: rgba(255,255,255,0.28);
    border-bottom: 1px solid rgba(108, 149, 190, 0.25);
}
.sales-header-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #4f8ef7, #2d6ce9);
    color: #fff;
    box-shadow: 0 8px 18px rgba(45,108,233,0.22);
}
.sales-header-icon i { font-size: 18px; }
.sales-header-copy h4 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    letter-spacing: -0.02em;
    color: #1f2d3d;
}
.sales-header-copy p {
    margin: 4px 0 0;
    color: #5a6f8b;
    font-size: 15px;
}
.sales-form-panel {
    background: #f5f9ff;
    border: 1px solid #dfeaf6;
    border-radius: 14px;
    margin: 10px 18px 0 !important;
    width: calc(100% - 36px);
    box-sizing: border-box;
    padding: 18px 18px 12px;
}
.sales-field-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 16px;
    align-items: end;
}
.sales-field-grid-no-recall {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}
.sales-field-grid-with-recall {
    grid-template-columns: repeat(4, minmax(0, 1fr));
}
.sales-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}
.sales-field label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #2b3b4f;
    margin: 0;
}
.sales-field label i {
    color: #2d6ce9;
    font-size: 13px;
    width: 14px;
    text-align: center;
}
.sales-field label span {
    color: #d43636;
}
.sales-input-action,
.sales-icon-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border: 1px solid #d7e3f1;
    border-radius: 10px;
    padding: 0 10px;
    min-height: 46px;
    box-shadow: inset 0 1px 2px rgba(15,23,42,0.02);
    width: 100%;
}
.sales-input-action i,
.sales-icon-wrap i {
    color: #7f8ea3;
    font-size: 14px;
}
.sales-input-action input,
.sales-icon-wrap input,
.sales-textbox,
.sales-select {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    outline: none !important;
    color: #24384e;
    font-size: 14px;
    flex: 1;
    width: 100%;
    min-width: 0;
}
.sales-input-action input::placeholder,
.sales-icon-wrap input::placeholder,
.sales-textbox::placeholder { color: #97a6b7; }
.sales-input-action .btn {
    border-radius: 10px !important;
    min-width: 38px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
}
.sales-input-action .btn.btn-primary {
    background: linear-gradient(135deg, #4c8cf7, #2d6ce9) !important;
    border-color: #2d6ce9 !important;
}
.sales-input-action .btn.btn-success {
    background: linear-gradient(135deg, #4c8cf7, #2d6ce9) !important;
    border-color: #2d6ce9 !important;
}
.sales-date-wrap {
    position: relative;
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid #d7e3f1;
    border-radius: 10px;
    padding: 0 10px 0 36px;
    min-height: 46px;
    width: 100%;
}
.sales-date-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #7f8ea3;
    font-size: 14px;
    z-index: 1;
}
.sales-date-wrap input {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    outline: none !important;
    flex: 1;
    color: #24384e;
    width: 100%;
    appearance: none;
    -webkit-appearance: none;
}
.sales-date-wrap input::-webkit-calendar-picker-indicator {
    opacity: 0;
    position: absolute;
    right: 8px;
    width: 20px;
    height: 20px;
    cursor: pointer;
}
.sales-table-card {
    background: #fff;
    border: 1px solid #dfeaf6;
    border-radius: 16px;
    margin: 18px !important;
    width: calc(100% - 36px);
    box-sizing: border-box;
    padding: 14px 14px 10px;
    box-shadow: 0 10px 25px rgba(42, 92, 171, 0.04);
}
.sales-table-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 8px 6px 14px;
}
.sales-table-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    font-weight: 800;
    color: #234b7d;
}
.sales-table-title i {
    color: #2d6ce9;
    font-size: 18px;
}
.sales-table-toolbar {
    display: flex;
    justify-content: flex-end;
    width: 100%;
    max-width: 380px;
}
.sales-search-box {
    position: relative;
    width: 100%;
    max-width: 360px;
}
.sales-search-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #7e8fa7;
}
.sales-search-box input {
    width: 100%;
    height: 42px;
    border: 1px solid #d7e3f1;
    background: #fff;
    border-radius: 10px;
    padding: 0 14px 0 38px;
    color: #28405b;
    box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.02);
}
#salesItemTable {
    width: 100%;
    border: 1px solid #d9e3ee;
    border-radius: 12px;
    overflow: hidden;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.7);
}
#salesItemTable thead th {
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.01em;
    background: #fff;
    color: #2b3e5b;
    padding: 12px 10px;
    border-bottom: 1px solid #d9e3ee;
    text-align: center;
}
#salesItemTable tbody td {
    font-size: 13px;
    color: #314765;
    padding: 10px 8px;
    border-bottom: 1px solid #edf1f5;
    background: #fff;
    vertical-align: middle;
}
#salesItemTable tbody tr:last-child td { border-bottom: none; }
#salesItemTable th:first-child,
#salesItemTable td:first-child { width: 22% !important; }
#salesItemTable th:nth-child(2),
#salesItemTable td:nth-child(2) { width: 20% !important; }
.sales-item-input {
    background: #fff;
    border: 1px solid #d7e3f1;
    border-radius: 8px;
    min-height: 38px;
    padding: 0 10px;
}
.sales-add-btn {
    background: linear-gradient(135deg, #4c8cf7, #2d6ce9);
    color: #fff;
    border: none;
    border-radius: 8px;
    min-width: 44px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 16px rgba(45,108,233,0.18);
}
.sales-add-btn:hover { color: #fff; }
.sales-delete-btn {
    background: linear-gradient(135deg, #ff6b6b, #eb4d4d);
    color: #fff;
    border: none;
    border-radius: 8px;
    min-width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
#addNewItemRow {
    background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
    color: #1a1a1a;
    font-weight: 700;
    border: none;
    border-radius: 30px;
    padding: 10px 30px;
    font-size: 14px;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 15px rgba(247,151,30,0.5);
    animation: pulseAddBtn 1.6s ease-in-out infinite;
    cursor: pointer;
    transition: filter 0.2s;
}
#addNewItemRow:hover {
    filter: brightness(1.08);
    animation-play-state: paused;
}

/* Cheque list table styling */
#chequeListDisplay {
    background: #f0f7ff;
    border-radius: 8px;
    padding: 10px 14px;
    margin-top: 8px;
    border: 1px solid #b8d4f5;
}
#chequeListDisplay table thead th {
    background: #458ada;
    color: #fff;
    font-size: 12px;
    padding: 6px 8px;
}
#chequeListDisplay table tbody td {
    font-size: 12px;
    padding: 5px 8px;
    vertical-align: middle;
}

/* Cost guard badge on net value field */
#cg_net_badge {
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 4px;
    margin-left: 6px;
    display: none;
}
#cg_net_badge.below { background: #fef3cd; color: #856404; display: inline-block; }
#cg_net_badge.above { background: #d1e7dd; color: #0a5c36; display: inline-block; }

.payment-method-panel {
    border: 1px solid #d9e2ec;
    border-radius: 8px;
    background: #f8fafc;
    padding: 12px 14px;
    margin: 16px 0 4px;
}
.payment-method-title {
    color: #26364a;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 9px;
}
.payment-method-options {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.payment-method-option {
    align-items: center;
    background: #fff;
    border: 1px solid #d9e2ec;
    border-radius: 6px;
    color: #6b7785;
    display: inline-flex;
    gap: 7px;
    min-width: 105px;
    padding: 7px 11px;
    transition: .15s ease;
}
.payment-method-option.active {
    background: #e8f5ee;
    border-color: #31a66a;
    color: #187344;
    font-weight: 700;
}
.payment-method-option input { accent-color: #198754; margin: 0; }

.customer-detail-panel {
    background: linear-gradient(180deg, #edf5ff 0%, #e7f0ff 100%);
    border: 1px solid #cfe0f8;
    border-radius: 18px;
    box-shadow: 0 12px 28px rgba(42, 92, 171, 0.08);
    padding: 12px 10px 14px;
    margin: 14px 18px 0 !important;
    width: calc(100% - 36px) !important;
    box-sizing: border-box;
    width: 100%;
}
.customer-detail-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 0;
    align-items: stretch;
    border-radius: 14px;
    overflow: hidden;
    width: 100%;
}
.customer-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-width: 0;
    padding: 0 8px;
}
.customer-field + .customer-field {
    border-left: none;
}
.customer-field label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #2c4f7c;
    font-size: 14px;
    font-weight: 700;
    min-height: 24px;
    margin: 0;
    padding: 0 4px;
}
.customer-field label i {
    color: #2d6ce9;
    width: 16px;
    text-align: center;
}
.customer-card {
    display: flex;
    flex-direction: column;
    min-width: 0;
    padding: 0 8px;
    border-right: 1px solid rgba(206, 223, 244, 0.95);
}
.customer-card:last-child { border-right: none; }
.customer-label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #2c4f7c;
    font-size: 14px;
    font-weight: 700;
    min-height: 32px;
    padding: 4px 4px 10px;
}
.customer-label i {
    color: #2d6ce9;
    font-size: 15px;
    width: 16px;
    text-align: center;
    flex-shrink: 0;
}
.customer-value,
.customer-balance-box,
.wholesale-toggle-wrap {
    min-height: 42px;
    border: 1px solid #d8e5f4;
    background: #fff;
    border-radius: 12px;
    display: flex;
    align-items: center;
    padding: 0 10px;
    box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.02);
}
.customer-value {
    color: #1d2e44;
    font-size: 17px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.customer-balance-box {
    overflow: hidden;
    width: 100%;
}
.customer-balance-box .balance-label {
    background: linear-gradient(135deg, #2d6ce9, #4d8ef7);
    color: #fff;
    width: 60px;
    min-height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: 800;
    margin-left: -10px;
    margin-right: 10px;
    padding: 0 8px;
}
.customer-balance-box .balance-amount {
    flex: 1;
    font-size: 15px;
    font-weight: 800;
    color: #d73d3d;
    letter-spacing: 0.01em;
}
.customer-balance-box .balance-amount.text-success { color: #1e8d5e !important; }
.wholesale-toggle-wrap {
    justify-content: flex-start;
    gap: 10px;
}
.wholesale-toggle-wrap input {
    width: 18px;
    height: 18px;
    accent-color: #2d6ce9;
    margin: 0;
}
.wholesale-toggle-wrap label {
    margin: 0;
    font-size: 15px;
    font-weight: 600;
    color: #2c4f7c;
}
</style>
</head>
<body>
<div class="main-wrapper">
<div class="page-wrapper">
<div class="content container-fluid">
<div class="row">
<div class="col-sm-12">
<div class="card sales-shell">
    <div class="sales-header">
        <div class="sales-header-icon">
            <i class="fas fa-receipt"></i>
        </div>
        <div class="sales-header-copy">
            <h4>Create Sales Invoice</h4>
            <p>Record and manage customer sales transactions</p>
        </div>
    </div>

    <div class="card-body">

        {{-- ── Alerts ── --}}
        @if(session('delete'))
            <div class="alert alert-danger text-center">{{ session('delete') }} &#10004;</div>
        @endif
        @if(session('added'))
            <div class="alert alert-success text-center">{{ session('added') }} &#10004;</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }} &#10008;</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        @if(Session::has('done'))
            <div class="alert alert-success text-center">
                <p>{{ Session::get('done') }}</p>
                <a href="{{ Session::get('print_url') }}" target="_blank"
                   class="btn btn-sm btn-warning mt-1">🖨 Print A4 Invoice</a>
                <a href="{{ Session::get('pos_print_url') }}" target="_blank"
                   class="btn btn-sm btn-secondary mt-1">🖨 Print POS Receipt</a>
            </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════
        MAIN FORM
        ═══════════════════════════════════════════════════════════ --}}
        <form action="{{ route('add_salesInvoice_withoutVat') }}" method="post" id="sales_form">
        @csrf

        <div class="sales-form-panel">
            <div class="sales-field-grid sales-field-grid-with-recall">
                <div class="sales-field">
                    <label><i class="fas fa-user-tag"></i>Customer Code <span>*</span></label>
                    <div class="sales-input-action">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchCustomer" name="customer_nic"
                            class="sales-textbox" placeholder="Enter Customer NIC/Code" required>
                        <button type="button" class="btn btn-primary"
                            data-bs-toggle="modal" data-bs-target="#selectCustomerModel" title="Search customer">
                            <i class="fa fa-arrow-left" style="color:white"></i>
                        </button>
                        <button type="button" class="btn btn-success"
                            data-bs-toggle="modal" data-bs-target="#addCustomerModel" title="Add new customer">
                            <i class="fas fa-plus" style="color:white"></i>
                        </button>
                    </div>
                </div>

                <div class="sales-field">
                    <label><i class="fas fa-history"></i>Recall In No</label>
                    <div class="sales-icon-wrap">
                        <i class="fas fa-history"></i>
                        <input type="text" id="Recall_Invoice" class="sales-textbox"
                            placeholder="Invoice Number to Recall"
                            value="{{ $maxInvoiceNo+1 }}">
                    </div>
                </div>

                <div class="sales-field">
                    <label><i class="fas fa-file-invoice"></i>Invoice No</label>
                    <div class="sales-icon-wrap">
                        <i class="fas fa-file-invoice"></i>
                        <input type="text" id="invoice_no" name="invoice_no"
                            value="{{ $maxInvoiceNo+1 }}" class="sales-textbox" readonly>
                    </div>
                </div>

                <div class="sales-field">
                    <label><i class="fas fa-calendar-alt"></i>Date</label>
                    <div class="sales-date-wrap">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" id="invoice_date" name="invoice_date" class="sales-textbox">
                    </div>
                </div>
            </div>
        </div>

        <div class="showCustomer mb-3"></div>

        <div class="sales-table-card">
            <div class="sales-table-head">
                <div class="sales-table-title">
                    <i class="fas fa-list"></i>
                    <span>Item Details</span>
                </div>
                <div class="sales-table-toolbar">
                    <div class="sales-search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="stockItemSearch" placeholder="Search item code or description...">
                    </div>
                </div>
            </div>

            <table id="salesItemTable">
                <thead>
                    <tr>
                        <th style="width:18%;">Item Code</th>
                        <th style="width:22%;">Description</th>
                        <th style="width:12%;">Batch</th>
                        <th style="width:12%;">GRN Price</th>
                        <th style="width:12%;">QTY</th>
                        <th style="width:18%;">Net Value</th>
                        <th style="width:18%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="sales-input-action" style="padding-right: 4px; min-height: 42px;">
                                <i class="fas fa-search"></i>
                                <input type="text" id="item_code" name="item_code"
                                    class="sales-textbox" placeholder="Enter item code...">
                                <input type="hidden" id="item_s_code" name="item_s_code">
                                <input type="hidden" id="Per" name="Per">
                                <input type="hidden" id="item_purchase_price" value="0">
                                <button type="button" class="btn btn-success sales-add-btn"
                                    data-bs-toggle="modal" data-bs-target="#searchItemModel" title="Search item">
                                    <i class="fas fa-plus" style="color:white"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <select class="form-control sales-select" id="item_description" name="item_description">
                                <option value="">Select an item</option>
                                @foreach($itemCode as $itemData)
                                    <option value="{{ $itemData->Item_description }}">
                                        {{ $itemData->Item_description }}{{ $itemData->Batchwise ? ' 📦 Batch' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="form-control sales-select" id="item_batch" name="item_batch">
                                <option value="">(no batch)</option>
                            </select>
                        </td>
                        <td><input class="form-control sales-item-input" type="text" placeholder="0" id="unit_price" name="unit_price" value="0"></td>
                        <td><input class="form-control sales-item-input" type="text" placeholder="QTY" id="qty" name="qty"></td>
                        <td><input class="form-control sales-item-input" type="text" placeholder="0" id="net_value" name="net_value" value="0"></td>
                        <td class="text-center">
                            <button type="button" class="btn add-item sales-add-btn" style="min-width: 110px; border-radius: 10px; font-weight: 700;">
                                Add <i class="fas fa-plus" style="margin-left:6px;"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        {{-- ── Dynamic added items table ── --}}
        <table class="table table-bordered sales-dynamic-table" id="dynamicAdded"></table>

{{-- ── Totals footer ── --}}
        <table class="table table-bordered sales-totals-table">
            <tbody>
                <tr class="table-success">
                    <td style="width:22%;"><strong>TOTAL :</strong></td>
                    <td style="width:20%;"></td>
                    <td class="total-unit-price text-center" style="width:12%;"><strong>0.00</strong></td>
                    <td style="width:12%;"></td>
                    <td class="total-value text-center" style="width:18%;"><strong>0.00</strong></td>
                    <td class="text-center" style="width:18%;"></td>
                </tr>
            </tbody>
        </table>
        </div>

{{-- ══ Payment section ══ --}}
<div class="stock-info-card mt-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <h5 class="mb-0"><i class="fas fa-cash-register text-primary me-2"></i>Payment Method</h5>
        <div class="payment-method-options" id="paymentMethodOptions">
            <label class="payment-method-option" data-method="cash">
                <input type="checkbox" id="payment_method_cash" disabled>
                <span>Cash</span>
            </label>
            <label class="payment-method-option" data-method="credit">
                <input type="checkbox" id="payment_method_credit" disabled>
                <span>Credit</span>
            </label>
            <label class="payment-method-option" data-method="cheque">
                <input type="checkbox" id="payment_method_cheque" disabled>
                <span>Cheque</span>
            </label>
        </div>
    </div>
    <p class="text-muted small mb-0">Payment method is updated automatically from the amounts entered below.</p>
</div>

<div class="stock-info-card mt-3">
    <div class="stock-info-grid-2">
        <div class="si-field">
            <label>Cash Pay</label>
            <div class="si-icon-wrap">
                <i class="fas fa-money-bill-wave"></i>
                <input type="text" class="form-control" id="cash_payment"
                    name="cash_payment" placeholder="0.00">
            </div>
        </div>
        <div class="si-field">
            <label>Credit</label>
            <div class="si-icon-wrap">
                <i class="fas fa-credit-card"></i>
                <input type="text" class="form-control" id="credite_payment"
                    name="credite_payment" placeholder="0.00">
            </div>
        </div>
        <div class="si-field">
            <label>Half Payment</label>
            <div class="si-icon-wrap">
                <i class="fas fa-hand-holding-usd"></i>
                <input type="text" class="form-control" id="half_payment"
                    name="half_payment" placeholder="0.00">
            </div>
        </div>
        <div class="si-field">
            <label>Discount <span class="text-danger">*</span></label>
            <div class="si-icon-wrap">
                <i class="fas fa-percent"></i>
                <input type="text" class="form-control" id="paid_discount"
                    name="discount" placeholder="0.00" required>
            </div>
        </div>
        <div class="si-field">
            <label>Cheque</label>
            <div class="stock-item-search-row">
                <div class="si-icon-wrap flex-grow-1">
                    <i class="fas fa-money-check-alt"></i>
                    <input type="text" class="form-control" id="cheque_payment"
                        name="cheque_payment" placeholder="Amount, then Enter or click +">
                </div>
                <button type="button" class="stock-item-search-btn" id="openChequeModalBtn" title="Add cheque details">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="stock-info-grid-2 mt-3">
        <div class="si-field">
            <label>Net Amount <span class="text-danger">*</span></label>
            <div class="si-icon-wrap">
                <i class="fas fa-wallet"></i>
                <input type="text" class="form-control" id="paid_amount"
                    name="net_amount" placeholder="0.00" required>
            </div>
        </div>
        <div class="si-field">
            <label>Gross Amount</label>
            <div class="si-icon-wrap">
                <i class="fas fa-coins"></i>
                <input type="text" class="form-control" id="total_amount"
                    name="gross_amount" placeholder="0.00" readonly>
            </div>
        </div>
        <div class="si-field">
            <label>Invoice Remark</label>
            <div class="si-icon-wrap">
                <i class="fas fa-sticky-note"></i>
                <input type="text" class="form-control" id="invoice_remark"
                    name="invoice_remark" placeholder="Invoice Remark">
            </div>
        </div>
    </div>

    {{-- ══ CHEQUE LIST ══ --}}
    <div id="chequeListDisplay" style="display:none; margin-top:16px;">
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-1" id="chequeListTable">
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>Bank Branch</th>
                        <th>Cheque No</th>
                        <th>Acc No</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody id="chequeListBody"></tbody>
            </table>
        </div>
        <div class="text-end pe-1">
            <strong>Total Cheques : <span id="totalChequeDisplay"
                style="color:#185FA5;">0.00</span></strong>
        </div>
    </div>
    {{-- ══ END CHEQUE LIST ══ --}}
</div>{{-- end payment section --}}

{{-- ── Action Buttons ── --}}
<div class="row mt-3">
    <div class="col-md-12">
        <input type="hidden" name="cheques_data"        id="cheques_data">
        <input type="hidden" name="total_cheque_amount" id="total_cheque_amount">

        <div class="d-flex gap-2 flex-wrap">
        @if(\App\Support\Permissions::canDo('sales', 'add'))
        <button type="submit" name="save" class="btn btn-outline-info btn-lg shadow">
            <i class="fas fa-save"></i> Save
        </button>
        @endif
        @if(\App\Support\Permissions::canDo('sales', 'print'))
        <button type="button" class="btn btn-outline-primary print_invoice btn-lg shadow">
            <i class="fas fa-print"></i> Print
        </button>
        @endif

        @if(\App\Support\Permissions::canDo('sales', 'delete'))
        <button type="button" class="btn btn-outline-danger pawn_delete btn-lg shadow" id="deleteInvoice">
            <i class="fas fa-trash-alt"></i> Delete
        </button>
        @endif
        @if(\App\Support\Permissions::canDo('sales', 'edit'))
        <button type="button" id="saveInvoiceBtn" class="btn btn-primary btn-lg shadow">
            <i class="fas fa-edit"></i> Update
        </button>
        @endif

        <button type="button" id="pawn_cancel" class="btn btn-outline-warning pawn_cancel btn-lg shadow"
            onclick="if(confirm('Discard this form and start over?')) window.location.reload();">
            <i class="fas fa-times-circle"></i> Cancel
        </button>
        <button type="reset" name="reset" class="btn btn-outline-secondary btn-lg shadow" id="resetBtn">
            <i class="fas fa-undo"></i> Reset
        </button>
        </div>
    </div>
</div>

</form>
</div>{{-- card-body --}}
</div>{{-- card --}}
</div>
</div>
</div>
</div>
</div>

{{-- ══════════════════════════════════════════════════════════
SELECT CUSTOMER MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="selectCustomerModel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title m-2">Search Customer</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height:65vh; overflow-y:auto;">
                <div class="stock-search-wrap mb-3">
                    <i class="fas fa-search"></i>
                    <input type="text" id="cus_name" class="form-control" placeholder="Search by name...">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm" id="ItemTable">
                        <thead>
                            <tr class="table-secondary">
                                <th>Code</th><th>Name</th><th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($Customerdetails as $data)
                            <tr>
                                <td>{{ $data->Code }}</td>
                                <td><div class="item-description-wrapper">{{ $data->First_name }}</div></td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" class="dt-act-btn dt-act-edit"
                                        id="add_cus"
                                        data-bs-toggle="modal" data-bs-target="#selectCustomerModel"
                                        data-id="{{ $data->id }}"
                                        data-cus_code="{{ $data->Code }}"
                                        data-cus_name="{{ $data->First_name }}"
                                        title="Add">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div id="ItemTableCustomPager"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
SEARCH ITEM MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="searchItemModel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title m-2">Search Item</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height:65vh; overflow-y:auto;">
                <div class="stock-search-wrap mb-3">
                    <i class="fas fa-search"></i>
                    <input type="text" id="item_name" class="form-control" placeholder="Search by name...">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm" id="ItemTableCustomer">
                        <thead>
                            <tr class="table-secondary">
                                <th>Code</th><th>Name</th><th>Price</th>
                                <th>Wholesale Price</th><th>Stock</th><th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($itemDetails as $ItemData)
                            <tr>
                                <td>{{ $ItemData->Item_code }}</td>
                                <td><div class="item-description-wrapper">{{ $ItemData->Item_description }}
                                    @if($ItemData->Batchwise)
                                        <span class="batch-tag" title="This item is batch tracked"><i class="fas fa-cubes"></i> Batch</span>
                                    @endif
                                </div></td>
                                <td>{{ $ItemData->saleprice }}</td>
                                <td>{{ $ItemData->Credit }}</td>
                                <td>
                                    <div class="{{ $ItemData->total_qun_in - $ItemData->total_qun_out < 0 ? 'text-danger' : '' }}">
                                        {{ $ItemData->total_qun_in - $ItemData->total_qun_out }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="javascript:void(0)" class="dt-act-btn dt-act-edit"
                                        id="add_item"
                                        data-bs-toggle="modal" data-bs-target="#searchItemModel"
                                        data-id="{{ $ItemData->id }}"
                                        data-add_item_code="{{ $ItemData->Item_code }}"
                                        data-Item_description="{{ $ItemData->Item_description }}"
                                        data-purchase_price="{{ $ItemData->purchasePrice }}"
                                        title="Add">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div id="ItemTableCustomerCustomPager"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
ADD CUSTOMER MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="addCustomerModel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title m-2">Add Customer</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                    <div class="errMsgContainer"></div>
                    <form id="addCustomer">
                        @csrf
                        <div class="row mb-2">
                            <div class="col">
                                <label>Code <span class="text-danger">*</span> :</label>
                                <input type="text" name="code" id="code" class="form-control" placeholder="Customer Code" required>
                            </div>
                            <div class="col">
                                <label>First Name <span class="text-danger">*</span> :</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <textarea class="form-control" name="address1" id="address1" rows="3" placeholder="Address-1:" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label>Customer Route :</label>
                                <select class="form-control" name="address2" id="address2" required>
                                    <option value="">Please Select</option>
                                    @foreach($Route as $areaData)
                                        <option value="{{ $areaData->description }}">{{ $areaData->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label>VAT Number <span class="text-danger">*</span> :</label>
                                <input type="text" name="passport" id="passport" class="form-control" placeholder="VAT Number" required>
                            </div>
                            <div class="col-md-6">
                                <label>Contact-1 <span class="text-danger">*</span> :</label>
                                <input type="text" name="contact1" id="contact1" class="form-control" placeholder="Contact 1" required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label>NIC :</label>
                                <input type="text" name="nic" id="nic" class="form-control" placeholder="NIC">
                            </div>
                            <div class="col-md-6">
                                <label>Email :</label>
                                <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label>Customer Cash Balance :</label>
                                <input type="text" name="other_identifications" id="other_identifications" class="form-control" placeholder="Cash Balance">
                            </div>
                            <div class="col-md-6">
                                <label>Status <span class="text-danger">*</span> :</label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="">Please Select</option>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Blacklist</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <label>Customer Route :</label>
                                <textarea class="form-control" name="city1" id="city1" rows="1" placeholder="Enter Route Details"></textarea>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-success add_customer me-2">Save</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
CHEQUE DETAILS MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="chequeDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background:#458ada;">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-money-check-alt me-2"></i> Cheque Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- Summary bar --}}
                <div class="alert alert-info py-2 mb-3" style="font-size:13px;">
                    <strong>Net Amount :</strong>
                    <span id="modal_net_display">0.00</span> &nbsp;|&nbsp;
                    <strong>Cash :</strong>
                    <span id="modal_cash_display">0.00</span> &nbsp;|&nbsp;
                    <strong>Credit :</strong>
                    <span id="modal_credit_display">0.00</span> &nbsp;|&nbsp;
                    <strong>Remaining :</strong>
                    <span id="modal_remaining_display" class="fw-bold text-danger">0.00</span>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="fw-bold">Bank Name <span class="text-danger">*</span></label>
                        <input type="text" id="cheque_bank" class="form-control"
                            placeholder="e.g. Bank of Ceylon">
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Bank Branch</label>
                        <input type="text" id="cheque_bank_branch" class="form-control"
                            placeholder="e.g. Kandy">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="fw-bold">Cheque No <span class="text-danger">*</span></label>
                        <input type="text" id="cheque_no" class="form-control"
                            placeholder="Cheque Number">
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Account No</label>
                        <input type="text" id="cheque_acc_no" class="form-control"
                            placeholder="Account Number">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="fw-bold">Amount <span class="text-danger">*</span></label>
                        <input type="text" id="cheque_amount_input" class="form-control"
                            placeholder="Amount">
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Cheque Date <span class="text-danger">*</span></label>
                        <input type="date" id="cheque_release_date" class="form-control">
                    </div>
                </div>

                <div id="cheque_error" class="text-danger mt-1" style="display:none;font-size:13px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="saveChequeDetails">
                    <i class="fas fa-plus-circle me-1"></i> Add Cheque
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
COST GUARD PASSWORD MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="costGuardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header" style="background:#A32D2D;">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-lock me-2"></i> Cost Guard — Manager Required
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning py-2 mb-3" style="font-size:13px;">
                    Net value <strong id="cg_net_display">0.00</strong> is
                    <span class="text-danger fw-bold">below purchase cost</span>
                    <strong id="cg_cost_display">0.00</strong>.
                    <br>Manager password required to proceed.
                </div>
                <label class="fw-bold">Manager Password <span class="text-danger">*</span></label>
                <input type="password" id="cg_password" class="form-control mt-1"
                    placeholder="Enter manager password" autocomplete="off">
                <div id="cg_error" class="text-danger mt-1" style="display:none;font-size:13px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="cg_confirm_btn">
                    <i class="fas fa-check me-1"></i> Confirm &amp; Add
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')
{!! Toastr::message() !!}

{{-- ══════════════════════════════════════════════════════════════════════
SCRIPTS
══════════════════════════════════════════════════════════════════════ --}}

{{-- Today's date --}}
<script>
document.getElementById('invoice_date').value = new Date().toISOString().slice(0, 10);
</script>

{{-- CSRF for Ajax --}}
<script>
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
</script>

{{-- ── Disable Enter key on form EXCEPT cheque field ── --}}
<script>
document.getElementById('sales_form').addEventListener('keydown', function (e) {
    if (e.keyCode === 13) {
        if (e.target.id === 'cheque_payment') return;
        e.preventDefault();
    }
});
</script>

{{-- ══════════════════════════════════════════════════════════
CHEQUE PAYMENT — Enter key opens modal
══════════════════════════════════════════════════════════ --}}
<script>
var chequeList = [];

$(document).on('keydown', '#cheque_payment', function (e) {
    if (e.keyCode !== 13) return;
    e.preventDefault();
    var val = parseFloat($(this).val()) || 0;
    if (val <= 0) { alert('Please enter a cheque amount first.'); return; }
    openChequeModal();
});

$(document).on('click', '#openChequeModalBtn', function () {
    var val = parseFloat($('#cheque_payment').val()) || 0;
    if (val <= 0) { alert('Please enter a cheque amount first.'); return; }
    openChequeModal();
});

$(document).on('blur', '#cheque_payment', function () {
    var val = parseFloat($(this).val()) || 0;
    if (val > 0 && chequeList.length === 0) openChequeModal();
});

function openChequeModal() {
    var net    = parseFloat($('#paid_amount').val())     || 0;
    var cash   = parseFloat($('#cash_payment').val())    || 0;
    var credit = parseFloat($('#credite_payment').val()) || 0;
    var cheque = parseFloat($('#cheque_payment').val())  || 0;

    $('#modal_net_display').text(net.toFixed(2));
    $('#modal_cash_display').text(cash.toFixed(2));
    $('#modal_credit_display').text(credit.toFixed(2));

    var listedChequeTotal = chequeList.reduce(function (s, c) {
        return s + (parseFloat(c.amount) || 0);
    }, 0);
    var remaining = net - cash - credit - listedChequeTotal;
    $('#modal_remaining_display').text(remaining.toFixed(2));

    $('#cheque_amount_input').val(cheque > 0 ? cheque.toFixed(2) : (remaining > 0 ? remaining.toFixed(2) : ''));
    $('#cheque_release_date').val(new Date().toISOString().slice(0, 10));
    $('#cheque_bank, #cheque_bank_branch, #cheque_no, #cheque_acc_no').val('');
    $('#cheque_error').hide();
    $('#chequeDetailsModal').modal('show');
    $('#chequeDetailsModal').one('shown.bs.modal', function () { $('#cheque_bank').focus(); });
}

$('#saveChequeDetails').on('click', function () {
    var bank        = $('#cheque_bank').val().trim();
    var branch      = $('#cheque_bank_branch').val().trim();
    var chequeNo    = $('#cheque_no').val().trim();
    var accNo       = $('#cheque_acc_no').val().trim();
    var amount      = parseFloat($('#cheque_amount_input').val()) || 0;
    var releaseDate = $('#cheque_release_date').val();

    $('#cheque_error').hide();
    if (!bank)        { $('#cheque_error').text('Bank name is required.').show();     $('#cheque_bank').focus();           return; }
    if (!chequeNo)    { $('#cheque_error').text('Cheque number is required.').show(); $('#cheque_no').focus();             return; }
    if (amount <= 0)  { $('#cheque_error').text('Amount must be greater than 0.').show(); $('#cheque_amount_input').focus(); return; }
    if (!releaseDate) { $('#cheque_error').text('Cheque date is required.').show();   $('#cheque_release_date').focus();   return; }

    chequeList.push({ bank, branch, chequeNo, accNo, amount, releaseDate });
    renderChequeTable();
    syncChequeHiddenFields();
    recalcCreditFromPayments();
    $('#chequeDetailsModal').modal('hide');
});

$('#chequeDetailsModal').on('keydown', function (e) {
    if (e.keyCode === 13) { e.preventDefault(); $('#saveChequeDetails').trigger('click'); }
});

function renderChequeTable() {
    if (!chequeList.length) {
        $('#chequeListDisplay').hide();
        $('#chequeListBody').html('');
        $('#totalChequeDisplay').text('0.00');
        return;
    }
    var rows = '', total = 0;
    chequeList.forEach(function (c, i) {
        total += parseFloat(c.amount) || 0;
        rows  += '<tr>' +
            '<td>' + c.bank + '</td>' +
            '<td>' + (c.branch  || '-') + '</td>' +
            '<td><strong>' + c.chequeNo + '</strong></td>' +
            '<td>' + (c.accNo  || '-') + '</td>' +
            '<td class="text-end">' + parseFloat(c.amount).toFixed(2) + '</td>' +
            '<td>' + c.releaseDate + '</td>' +
            '<td class="text-center">' +
                '<button type="button" class="btn btn-outline-danger btn-sm remove-cheque" ' +
                    'data-index="' + i + '"><i class="far fa-trash-alt"></i></button>' +
            '</td></tr>';
    });
    $('#chequeListBody').html(rows);
    $('#totalChequeDisplay').text(total.toFixed(2));
    $('#cheque_payment').val(total.toFixed(2));
    $('#chequeListDisplay').show();
}

$(document).on('click', '.remove-cheque', function () {
    chequeList.splice(parseInt($(this).data('index')), 1);
    renderChequeTable();
    syncChequeHiddenFields();
    if (!chequeList.length) $('#cheque_payment').val('');
    recalcCreditFromPayments();
});

function syncChequeHiddenFields() {
    var total = chequeList.reduce(function (s, c) { return s + (parseFloat(c.amount) || 0); }, 0);
    $('#cheques_data').val(JSON.stringify(chequeList));
    $('#total_cheque_amount').val(total.toFixed(2));
}
</script>

{{-- ══════════════════════════════════════════════════════════
PAYMENT SPLIT — CREDIT auto-calculates as remainder
══════════════════════════════════════════════════════════ --}}
<script>
function recalcCreditFromPayments() {
    var net    = parseFloat($('#paid_amount').val())    || 0;
    var cash   = parseFloat($('#cash_payment').val())   || 0;
    var half   = parseFloat($('#half_payment').val())   || 0;
    var cheque = parseFloat($('#cheque_payment').val()) || 0;
    var credit = net - cash - half - cheque;
    $('#credite_payment').val(credit >= 0 ? credit.toFixed(2) : '0.00');
    updatePaymentMethodIndicators();
}

function updatePaymentMethodIndicators() {
    var cash = parseFloat($('#cash_payment').val()) || 0;
    var half = parseFloat($('#half_payment').val()) || 0;
    var credit = parseFloat($('#credite_payment').val()) || 0;
    var cheque = parseFloat($('#cheque_payment').val()) || 0;

    var methods = {
        cash: cash > 0 || half > 0,
        credit: credit > 0,
        cheque: cheque > 0
    };

    Object.keys(methods).forEach(function (method) {
        var option = $('.payment-method-option[data-method="' + method + '"]');
        option.toggleClass('active', methods[method]);
        option.find('input').prop('checked', methods[method]);
    });
}

$(document).on('keyup change', '#cash_payment', function () {
    if ((parseFloat($(this).val()) || 0) > 0) $('#half_payment').val('');
    recalcCreditFromPayments();
});
$(document).on('keyup change', '#half_payment', function () {
    if ((parseFloat($(this).val()) || 0) > 0) $('#cash_payment').val('');
    recalcCreditFromPayments();
});
$(document).on('change',       '#cheque_payment',  function () { recalcCreditFromPayments(); });

$(document).on('keyup', '#credite_payment', function () {
    var net    = parseFloat($('#paid_amount').val())    || 0;
    var cash   = parseFloat($('#cash_payment').val())   || 0;
    var half   = parseFloat($('#half_payment').val())   || 0;
    var cheque = parseFloat($('#cheque_payment').val()) || 0;
    var credit = parseFloat($(this).val())              || 0;
    var total  = cash + half + cheque + credit;
    $('#payment-over-warning').remove();
    if (total > net) {
        $(this).addClass('is-invalid');
        $(this).after('<small id="payment-over-warning" class="text-danger">Total exceeds net by ' + (total - net).toFixed(2) + '</small>');
    } else {
        $(this).removeClass('is-invalid');
    }
    updatePaymentMethodIndicators();
});

$(document).on('keyup change', '#cheque_payment', updatePaymentMethodIndicators);
updatePaymentMethodIndicators();
</script>

{{-- ══════════════════════════════════════════════════════════
FORM SUBMIT VALIDATION
══════════════════════════════════════════════════════════ --}}
<script>
document.getElementById('sales_form').addEventListener('submit', function (e) {
    syncChequeHiddenFields();

    var cash   = parseFloat(document.getElementById('cash_payment').value)    || 0;
    var half   = parseFloat(document.getElementById('half_payment').value)    || 0;
    var credit = parseFloat(document.getElementById('credite_payment').value) || 0;
    var cheque = parseFloat(document.getElementById('cheque_payment').value)  || 0;
    var net    = parseFloat(document.getElementById('paid_amount').value)     || 0;

    if (cash <= 0 && half <= 0 && credit <= 0 && cheque <= 0) {
        alert('Please enter at least one payment amount (Cash, Half Payment, Credit, or Cheque).');
        e.preventDefault();
        return;
    }
    if (cash > 0 && half > 0) {
        alert('Use either Cash Pay or Half Payment, not both.');
        e.preventDefault();
        return;
    }
    if (cheque > 0 && chequeList.length === 0) {
        alert('Cheque amount entered but no cheque details added.\nPlease add cheque details.');
        e.preventDefault();
        openChequeModal();
        return;
    }
});
</script>

{{-- ══════════════════════════════════════════════════════════
PRINT INVOICE
══════════════════════════════════════════════════════════ --}}
<script>
$(document).on('click', '.print_invoice', function (e) {
    e.preventDefault();
    let no = $('#invoice_no').val();
    if (confirm('Are you sure to print the invoice?')) {
        $.get("{{ route('print_sales_invoice_ajax_without_vat') }}", { sales_invoice_no: no })
        .done(function (res) {
            if (res.status === 'success') window.open(res.pdfUrl, '_blank');
            else alert('Error printing invoice!');
        });
    }
});
</script>

{{-- ══════════════════════════════════════════════════════════
ITEM CODE AUTO-FILL  +  COST GUARD BADGE
══════════════════════════════════════════════════════════ --}}
<script>
function updateCostBadge() {
    var purchasePrice = parseFloat($('#item_purchase_price').val()) || 0;
    var qty           = parseFloat($('#qty').val())                 || 0;
    var netValue      = parseFloat($('#net_value').val())           || 0;
    var totalCost     = purchasePrice * qty;
    var badge         = $('#cg_net_badge');

    if (purchasePrice <= 0) { badge.hide(); return; }

    if (netValue < totalCost) {
        badge.text('⚠ below cost').removeClass('above').addClass('below').show();
    } else {
        badge.text('✓ ok').removeClass('below').addClass('above').show();
    }
}

function setItemDetails() {
    var code        = $('#item_code').val();
    var isWholesale = $('#customer_toggle').is(':checked');

    $.get("{{ route('show_select_item_description_ajax') }}", { Item_code: code })
    .done(function (res) {
        if (res.status === 'success') {
            $('#item_description').empty();
            $.each(res.data, function (i, item) {
                $('#item_description').append(
                    $('<option>', { value: item.Item_description, text: item.Item_description })
                );
                $('#unit_price').val(isWholesale ? item.Credit : item.saleprice);
                $('#item_s_code').val(item.Bar_code);
                $('#Per').val(item.Per);
                $('#item_purchase_price').val(item.purchasePrice || 0);
            });
            updateCostBadge();
            loadItemBatches(code);
        }
    });
}

// Load sellable batches (remaining qty > 0) for the selected item into #item_batch.
// Non-batch-tracked items simply come back empty, leaving "(no batch)" selected.
function loadItemBatches(itemCode) {
    var batchSelect = $('#item_batch');
    batchSelect.html('<option value="">(no batch)</option>');

    $.get("{{ url('get_item_batches_ajax') }}", { item_code: itemCode, only_available: 1 })
        .done(function (res) {
            if (res.data && res.data.length) {
                res.data.forEach(function (b) {
                    batchSelect.append($('<option>', {
                        value: b.batch_no,
                        'data-sale-price': b.sale_price,
                        text: b.batch_no + ' (Qty ' + b.qty_remaining + ', Rs.' + b.sale_price + ')'
                    }));
                });
            }
        });
}

$(document).on('change', '#item_batch', function () {
    var price = $(this).find(':selected').data('sale-price');
    if (price !== undefined && price !== '') {
        $('#unit_price').val(price);
        updateCostBadge();
    }
});

$('#item_code').on('keyup', function () { setItemDetails(); });

$('#customer_toggle').on('change', function () {
    if ($('#item_code').val()) setItemDetails();
});

// Update badge whenever qty, discount, or net value changes
$(document).on('keyup', '#qty, #unit_price, #discount, #discount_val', function () {
    updateCostBadge();
});
</script>

{{-- ══════════════════════════════════════════════════════════
ITEM MODAL SELECTION
══════════════════════════════════════════════════════════ --}}
<script>
let $activeNewRow = null;

$(document).on('click', '#add_item', function () {
    var code          = $(this).data('add_item_code');
    var purchasePrice = $(this).data('purchase_price') || 0;

    if ($activeNewRow) {
        $activeNewRow.find('.new-item-code').val(code).trigger('keyup');
        $activeNewRow = null;
    } else {
        $('#item_code').val(code);
        $('#item_purchase_price').val(purchasePrice);
        setItemDetails();
    }
});
</script>

{{-- ══════════════════════════════════════════════════════════
CUSTOMER MODAL SELECTION
══════════════════════════════════════════════════════════ --}}
<script>
function loadCustomerDetails(searchText) {
    if (!searchText || !searchText.trim()) return;

    $.get("{{ route('get_customer_ajax') }}", { search_string: searchText.trim() })
    .done(function (res) {
        if (res && res.status === 'not_found') {
            $('.showCustomer').html('<div class="customer-detail-panel"><div class="text-danger text-center fw-bold">Customer Not Found!</div></div>');
            return;
        }

        $('.showCustomer').html(res);

        var name = $('.showCustomer').find('#customer_name').val() || $('.showCustomer').find('#customer_name').text().trim();
        var phone = $('.showCustomer').find('#customer_phone').val() || $('.showCustomer').find('#customer_phone').text().trim();
        var address = $('.showCustomer').find('#customer_address').val() || $('.showCustomer').find('#customer_address').text().trim();

        $('#customer_name_display').val(name);
        $('#customer_phone').val(phone);
        $('#customer_address').val(address);

        $.get("{{ route('get_customer_balance_ajax') }}", { cus_code: searchText.trim() })
        .done(function (bal) {
            $('#customer_balance').val(bal.status === 'success' ? bal.balance : 0);
        });
    });
}

$(document).on('click', '#add_cus', function () {
    var cus_code = $(this).data('cus_code');
    $('#searchCustomer').val(cus_code);
    loadCustomerDetails(cus_code);
});

$(document).on('keydown', '#searchCustomer', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        loadCustomerDetails($(this).val());
    }
});

$(document).on('blur', '#searchCustomer', function () {
    var search = $(this).val();
    if (search && search.trim() && !$(this).data('justSelected')) {
        loadCustomerDetails(search);
    }
    $(this).removeData('justSelected');
});
</script>


{{-- ── Add new customer via Ajax ── --}}
<script>
$(document).on('click', '.add_customer', function (e) {
    e.preventDefault();
    $.ajax({
        url:    "{{ route('add_customer_ajax') }}",
        method: 'post',
        data: {
            "_token":              "{{ csrf_token() }}",
            code:                  $('#code').val(),
            first_name:            $('#first_name').val(),
            address1:              $('#address1').val(),
            city1:                 $('#city1').val(),
            address2:              $('#address2').val(),
            contact1:              $('#contact1').val(),
            email:                 $('#email').val(),
            nic:                   $('#nic').val(),
            passport:              $('#passport').val(),
            other_identifications: $('#other_identifications').val(),
            status:                $('#status').val()
        },
        success: function (res) {
            if (res.status === 'success') {
                $('#addCustomerModel').modal('hide');
                $('#addCustomer')[0].reset();
                toastr.success('Customer Added!', 'Success');
                var cus = res.telNo[0];
                $('#searchCustomer').val(cus.Contact_1);
            }
        },
        error: function (err) {
            $('.errMsgContainer').html('');
            $.each(err.responseJSON.errors, function (i, v) {
                $('.errMsgContainer').append('<span class="text-danger">' + v + '</span><br>');
            });
        }
    });
});
</script>

{{-- ══════════════════════════════════════════════════════════
QTY / DISCOUNT CALCULATIONS (top entry row)
══════════════════════════════════════════════════════════ --}}
<script>
$(document).on('keyup', '#qty', function () {
    var price = parseFloat($('#unit_price').val()) || 0;
    var qty   = parseFloat($(this).val())          || 0;
    $('#net_value').val((qty * price).toFixed(2));
    updateCostBadge();
});

$(document).on('keyup', '#discount', function () {
    var price   = parseFloat($('#unit_price').val()) || 0;
    var qty     = parseFloat($('#qty').val())         || 0;
    var gross   = price * qty;
    var discPct = parseFloat($(this).val())           || 0;
    var discVal = gross * (discPct / 100);
    $('#discount_val').val(discVal.toFixed(2));
    $('#net_value').val((gross - discVal).toFixed(2));
    updateCostBadge();
});

$(document).on('keyup', '#discount_val', function () {
    var price   = parseFloat($('#unit_price').val()) || 0;
    var qty     = parseFloat($('#qty').val())         || 0;
    var gross   = price * qty;
    var discVal = parseFloat($(this).val())           || 0;
    $('#net_value').val((gross - discVal).toFixed(2));
    updateCostBadge();
});
</script>

{{-- ══════════════════════════════════════════════════════════
COST GUARD — PASSWORD & PENDING ADD LOGIC
══════════════════════════════════════════════════════════ --}}
<script>
// ── Set your manager password here (or inject from server-side config) ──
var COST_GUARD_PASSWORD = '{{ config("app.cost_guard_password", "1001") }}';
var pendingAddPayload   = null;

// Build a lookup map: item_code => purchasePrice  (rendered once by Blade)
var itemCostMap = {};
@foreach($itemDetails as $ItemData)
    itemCostMap['{{ $ItemData->Item_code }}'] = {{ floatval($ItemData->purchasePrice) }};
@endforeach

// ── Open cost guard modal ──
function openCostGuard(netValue, totalCost) {
    $('#cg_net_display').text(parseFloat(netValue).toFixed(2));
    $('#cg_cost_display').text(parseFloat(totalCost).toFixed(2));
    $('#cg_password').val('');
    $('#cg_error').hide();
    $('#costGuardModal').modal('show');
    $('#costGuardModal').one('shown.bs.modal', function () { $('#cg_password').focus(); });
}

// ── Confirm password click ──
$('#cg_confirm_btn').on('click', function () {
    var entered = $('#cg_password').val();
    if (entered === COST_GUARD_PASSWORD) {
        $('#costGuardModal').modal('hide');
        commitAddItem();
    } else {
        $('#cg_error').text('Incorrect password. Please try again.').show();
        $('#cg_password').val('').focus();
    }
});

// ── Allow Enter key in cost guard modal ──
$('#costGuardModal').on('keydown', function (e) {
    if (e.keyCode === 13) { e.preventDefault(); $('#cg_confirm_btn').trigger('click'); }
});

// ── Cancel clears payload ──
$('#costGuardModal').on('hidden.bs.modal', function () {
    // If user closed modal without confirming, discard payload
    if (pendingAddPayload) { pendingAddPayload = null; }
});
</script>

{{-- ══════════════════════════════════════════════════════════
ADD ITEM TO NEW INVOICE (top row Add button)
══════════════════════════════════════════════════════════ --}}
<script>
var rowIndex   = -1;
var newInvoice = { totalGross: 0, totalDiscount: 0, totalValue: 0 };

$('.add-item').click(function () {
    var customer_nic     = $('#searchCustomer').val();
    var invoice_no       = $('#invoice_no').val();
    var invoice_date     = $('#invoice_date').val();
    var item_code        = $('#item_code').val();
    var item_s_code      = $('#item_s_code').val();
    var Per              = $('#Per').val();
    var item_description = $('#item_description').val();
    var qty              = parseFloat($('#qty').val())          || 0;
    var unit_price       = parseFloat($('#unit_price').val())   || 0;
    var Free_Issues      = $('#Free_Issues').val();
    var discount         = $('#discount').val();
    var discount_val     = parseFloat($('#discount_val').val()) || 0;
    var net_value        = parseFloat($('#net_value').val())    || 0;
    var batch_no         = $('#item_batch').val();

    if (!customer_nic || !invoice_no || !item_code || !qty || !unit_price || !net_value) {
        alert('Please fill in all required fields.');
        return;
    }

    // Get purchase price from map
    var purchasePrice = itemCostMap[item_code] || 0;
    var totalCost     = purchasePrice * qty;

    // Store payload for use after optional password check
    pendingAddPayload = {
        customer_nic, invoice_no, invoice_date,
        item_code, item_s_code, Per, item_description,
        qty, unit_price, Free_Issues, discount,
        discount_val, net_value, batch_no
    };

    if (purchasePrice > 0 && net_value < totalCost) {
        // Block — show cost guard
        openCostGuard(net_value, totalCost);
    } else {
        // No guard needed
        commitAddItem();
    }
});

// ── Actual row-add logic (called after guard passes or directly) ──
function commitAddItem() {
    if (!pendingAddPayload) return;
    var d = pendingAddPayload;
    pendingAddPayload = null;

    rowIndex++;
    $('#dynamicAdded').prepend(`
    <tr>
        <td style="width:15%;">
            <input type="hidden" name="inputs[${rowIndex}][customer_nic]"  value="${d.customer_nic}">
            <input type="hidden" name="inputs[${rowIndex}][invoice_no]"    value="${d.invoice_no}">
            <input type="hidden" name="inputs[${rowIndex}][invoice_date]"  value="${d.invoice_date}">
            <input class="form-control" type="text" style="text-align:center;"
                name="inputs[${rowIndex}][item_code]" value="${d.item_code}" readonly>
            <input type="hidden" name="inputs[${rowIndex}][item_s_code]"   value="${d.item_s_code}">
            <input type="hidden" name="inputs[${rowIndex}][Per]"           value="${d.Per}">
        </td>
                <td style="width:18%;">
                    <input class="form-control" type="text"
                        name="inputs[${rowIndex}][item_description]"
                        value="${d.item_description || ''}"
                        list="item_desc_list_${rowIndex}"
                        autocomplete="off">
                    <datalist id="item_desc_list_${rowIndex}">
                        @foreach($itemCode as $itemData)
                        <option value="{{ $itemData->Item_description }}">
                        @endforeach
                    </datalist>
                </td>
        <td style="width:10%;">
            <input class="form-control" type="text" style="text-align:center;"
                name="inputs[${rowIndex}][batch_no]" value="${d.batch_no || ''}" readonly>
        </td>
        <td style="width:12%;">
            <input class="form-control" type="text" style="text-align:center;"
                name="inputs[${rowIndex}][unit_price]" value="${d.unit_price}" readonly>
        </td>
        <td style="width:12%;">
            <input class="form-control" type="text" style="text-align:center;"
                name="inputs[${rowIndex}][qty]" value="${d.qty}" readonly>
            <input type="hidden" name="inputs[${rowIndex}][Free_Issues]" value="${d.Free_Issues || 0}">
            <input type="hidden" name="inputs[${rowIndex}][discount]" value="${d.discount || 0}">
            <input type="hidden" name="inputs[${rowIndex}][discount_val]" value="${d.discount_val || 0}">
        </td>
        <td style="width:18%;">
            <input class="form-control" type="text" style="text-align:center;"
                name="inputs[${rowIndex}][net_value]" value="${d.net_value || 0}" readonly>
        </td>
        <td style="width:8%;" class="text-center">
            <button type="button" class="btn sales-row-delete shadow remove-new-row" title="Delete item" aria-label="Delete item">
                <i class="far fa-trash-alt"></i>
            </button>
        </td>
    </tr>`);

    newInvoice.totalGross    += d.unit_price * d.qty;
    newInvoice.totalDiscount += d.discount_val;
    newInvoice.totalValue    += d.net_value;
    updateNewInvoiceTotals();
    resetTopRow();
}

function updateNewInvoiceTotals() {
    $('.total-unit-price').html('<strong>' + newInvoice.totalGross.toFixed(2)    + '</strong>');
    $('.total-discount').html('<strong>'   + newInvoice.totalDiscount.toFixed(2) + '</strong>');
    $('.total-value').html('<strong>'      + newInvoice.totalValue.toFixed(2)    + '</strong>');
    $('#total_amount').val(newInvoice.totalGross.toFixed(2));
    $('#paid_discount').val(newInvoice.totalDiscount.toFixed(2));
    $('#paid_amount').val(newInvoice.totalValue.toFixed(2));

    var cash   = parseFloat($('#cash_payment').val())   || 0;
    var cheque = parseFloat($('#cheque_payment').val()) || 0;
    if (cash === 0 && cheque === 0) {
        $('#credite_payment').val(newInvoice.totalValue.toFixed(2));
    } else {
        recalcCreditFromPayments();
    }
}

function resetTopRow() {
    $('#item_code').val('');
    $('#item_purchase_price').val('0');
    $('#item_description').val('');
    $('#qty').val('');
    $('#unit_price').val('0');
    $('#Free_Issues').val('0');
    $('#discount').val('0');
    $('#discount_val').val('0');
    $('#net_value').val('0');
    $('#item_batch').html('<option value="">(no batch)</option>');
    $('#cg_net_badge').hide();
}

$(document).on('click', '.remove-new-row', function () {
    var row     = $(this).closest('tr');
    var up      = parseFloat(row.find('[name$="[unit_price]"]').val())   || 0;
    var qty     = parseFloat(row.find('[name$="[qty]"]').val())          || 0;
    var discVal = parseFloat(row.find('[name$="[discount_val]"]').val()) || 0;
    var netVal  = parseFloat(row.find('[name$="[net_value]"]').val())    || 0;
    newInvoice.totalGross    -= up * qty;
    newInvoice.totalDiscount -= discVal;
    newInvoice.totalValue    -= netVal;
    row.remove();
    updateNewInvoiceTotals();
});
</script>

{{-- ══════════════════════════════════════════════════════════
DATATABLES
══════════════════════════════════════════════════════════ --}}
<script src="assets/js/dt-custom-pager.js"></script>
<script>
$(document).ready(function () {
    var CustomerPickerTable = $('#ItemTable').DataTable({
        paging: true, searching: true, ordering: true, info: false,
        lengthChange: false, pageLength: 10, dom: 't',
    });
    DTCustomPager.init(CustomerPickerTable, '#ItemTableCustomPager');
    $('#cus_name').on('keyup', function () {
        CustomerPickerTable.search($(this).val()).draw();
    });

    var ItemPickerTable = $('#ItemTableCustomer').DataTable({
        paging: true, searching: true, ordering: true, info: false,
        lengthChange: false, pageLength: 10, dom: 't',
    });
    DTCustomPager.init(ItemPickerTable, '#ItemTableCustomerCustomPager');
    $('#item_name').on('keyup', function () {
        ItemPickerTable.search($(this).val()).draw();
    });
});
</script>

{{-- ══════════════════════════════════════════════════════════
RECALL / EDIT / UPDATE / DELETE
══════════════════════════════════════════════════════════ --}}
<script>
$(document).ready(function () {

    function recalcTotals() {
        var totalGross = 0, totalDiscount = 0, totalNet = 0;
        $('#dynamicAdded tr:not(#addItemBtnRow)').each(function () {
            var up  = parseFloat($(this).find('[name="Unit_price"]').val()) || 0;
            var qty = parseFloat($(this).find('[name="QTY"]').val())        || 0;
            var dis = parseFloat($(this).find('[name="Discount"]').val())   || 0;
            var net = parseFloat($(this).find('[name="Net_value"]').val())  || 0;
            totalGross    += up * qty;
            totalDiscount += dis;
            totalNet      += net;
        });
        $('#total_amount').val(totalGross.toFixed(2));
        $('#paid_discount').val(totalDiscount.toFixed(2));
        $('#paid_amount').val(totalNet.toFixed(2));
        $('.total-unit-price').html('<strong>' + totalGross.toFixed(2)    + '</strong>');
        $('.total-discount').html('<strong>'   + totalDiscount.toFixed(2) + '</strong>');
        $('.total-value').html('<strong>'      + totalNet.toFixed(2)      + '</strong>');

        var cash   = parseFloat($('#cash_payment').val())   || 0;
        var cheque = parseFloat($('#cheque_payment').val()) || 0;
        if (cash === 0 && cheque === 0) {
            $('#credite_payment').val(totalNet.toFixed(2));
        } else {
            recalcCreditFromPayments();
        }
    }

    function buildExistingRow(d) {
        return `
        <tr data-id="${d.id}" data-isnew="0">
            <td><input type="text" name="Item_code"        class="form-control" value="${d.Item_code        || ''}" readonly></td>
            <td><input type="text" name="Item_description" class="form-control" value="${d.Item_description || ''}" readonly></td>
            <td><input type="text" name="Unit_price"       class="form-control" value="${d.Unit_price       || 0}"  readonly></td>
            <td><input type="text" name="QTY"              class="form-control QTY"              value="${d.QTY              || 0}"></td>
            <td>
                <input type="text" name="Net_value" class="form-control Net_value" value="${d.Net_value || 0}" readonly>
                <input type="hidden" name="Free_Issues" value="${d.Free_Issues || 0}">
                <input type="hidden" name="DiscountPercentage" value="${d.DiscountPercentage || 0}">
                <input type="hidden" name="Discount" value="${d.Discount || 0}">
            </td>
            <td>
                ${canEditSales ? '<button type="button" class="btn btn-outline-success btn-sm edit-row mb-1"><i class="far fa-edit me-1"></i> Edit</button>' : ''}
                <button type="button" class="btn sales-row-delete remove-row" title="Delete item" aria-label="Delete item">
                    <i class="far fa-trash-alt"></i>
                </button>
            </td>
        </tr>`;
    }

    function buildNewRow() {
        return `
        <tr data-id="" data-isnew="1">
            <td>
                <div class="input-group">
                    <input type="text" name="Item_code" class="form-control new-item-code" placeholder="Item Code">
                    <button type="button" class="btn btn-success btn-sm open-item-modal"
                        data-bs-toggle="modal" data-bs-target="#searchItemModel">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </td>
            <td><input type="text" name="Item_description"   class="form-control"               placeholder="Description"></td>
            <td><input type="text" name="Unit_price"         class="form-control new-unit-price" placeholder="Unit Price" value="0"></td>
            <td><input type="text" name="QTY"                class="form-control QTY"            placeholder="QTY"        value="0"></td>
            <td>
                <input type="text" name="Net_value" class="form-control Net_value" placeholder="Net Val" value="0" readonly>
                <input type="hidden" name="Free_Issues" value="0">
                <input type="hidden" name="DiscountPercentage" value="0">
                <input type="hidden" name="Discount" value="0">
            </td>
            <td>
                <button type="button" class="btn sales-row-delete remove-row" title="Delete item" aria-label="Delete item">
                    <i class="far fa-trash-alt"></i>
                </button>
            </td>
        </tr>`;
    }

    $(document).on('click', '.open-item-modal', function () {
        $activeNewRow = $(this).closest('tr');
    });

    // ── RECALL ──
    $('#Recall_Invoice').on('input', function () {
        setTimeout(function () {
            var no = $('#Recall_Invoice').val();
            if (!(parseInt(no) > 0)) {
                $('#dynamicAdded').empty();
                $('.showCustomer').empty();
                return;
            }

            $.get("{{ route('find_sales_details_invoice') }}", { search_receipt_no: no })
            .done(function (res) {
                if (res.status === 'success') {
                    var html = res.data.map(d => buildExistingRow(d)).join('');
                    if (canEditSales) {
                        html += `<tr id="addItemBtnRow">
                            <td colspan="6" class="text-center py-2">
                                <button type="button" id="addNewItemRow">
                                    <i class="fas fa-plus-circle me-2"></i> Add New Item to This Invoice
                                </button>
                            </td>
                        </tr>`;
                    }
                    $('#dynamicAdded').html(html);
                    recalcTotals();
                    lockRecalledForm();
                } else {
                    $('#dynamicAdded').html(
                        `<tr><td colspan="6" class="text-danger text-center fw-bold py-3">Invoice Items Not Found</td></tr>`
                    );
                }
            });

            $.get("{{ route('find_sales_invoice_customer_data_sum') }}", { search_receipt_no: no })
            .done(function (res) {
                if (res.status === 'success') {
                    var r = res.data[0];
                    $('#invoice_no').val(r.Invoice_no);
                    $('#invoice_date').val(r.Invoice_date);
                    $('#searchCustomer').val(r.Customer_NIC);
                    $('#Route').val(r.Route);
                    $('#Salesmen').val(r.Salesmen);
                    $('#total_amount').val(r.Gross_Amount);
                    $('#paid_discount').val(r.Discount);
                    $('#paid_amount').val(r.Net_Amount);
                    $('#cash_payment').val(r.Cash_Pay  > 0 ? r.Cash_Pay  : '');
                    $('#half_payment').val(r.Half_Payment > 0 ? r.Half_Payment : '');
                    $('#credite_payment').val(r.Credite > 0 ? r.Credite  : '');
                    $('#cheque_payment').val(r.Cheque   > 0 ? r.Cheque   : '');

                    $('.showCustomer').html(`
                    <div class="row mt-2 mb-3 p-2" style="background:#f0f4ff;border-radius:6px;">
                        <div class="col-md-3">
                            <label class="fw-bold">Customer Name:</label>
                            <input class="form-control" type="text"
                                id="customer_name" name="customer_name"
                                value="${r.Customer_Name || ''}">
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Code:</label>
                            <input class="form-control" type="text"
                                id="Customer_Code" name="Customer_Code"
                                value="${r.Customer_NIC || ''}">
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Phone:</label>
                            <input class="form-control" type="text"
                                value="${r.Customer_Phone || ''}">
                        </div>
                        <div class="col-md-3">
                            <label class="fw-bold">Address:</label>
                            <input class="form-control" type="text"
                                value="${r.Customer_Address || ''}">
                        </div>
                    </div>`);
                    lockRecalledForm();
                } else {
                    $('.showCustomer').html(
                        `<div class="text-danger text-center fw-bold py-2">Invoice Header Not Found</div>`
                    );
                }
            });
        }, 500);
    });

    // ── Recall lock ──────────────────────────────────────────────────
    // Once an existing invoice is recalled, its fields are locked against
    // editing unless this role has the "sales edit" permission
    // (Permissions::canDo('sales', 'edit')) — matching the same permission
    // that already gates the Update button's visibility.
    const canEditSales = @json(\App\Support\Permissions::canDo('sales', 'edit'));

    function lockRecalledForm() {
        if (canEditSales) {
            return;
        }
        ['#invoice_date', '#searchCustomer', '#Route', '#Salesmen', '#total_amount',
         '#paid_discount', '#paid_amount', '#cash_payment', '#half_payment',
         '#credite_payment', '#cheque_payment'].forEach(function (sel) {
            $(sel).prop('readonly', true).prop('disabled', true);
        });
        $('#dynamicAdded input').prop('readonly', true);
        $('#dynamicAdded .edit-row, #addItemBtnRow').remove();
    }

    // ── Add new item row in recalled table ──
    $(document).on('click', '#addNewItemRow', function () {
        $('#addItemBtnRow').before(buildNewRow());
    });

    // ── Auto-fill item in new recalled row ──
    $(document).on('keyup', '.new-item-code', function () {
        var input = $(this);
        var row   = input.closest('tr');
        var code  = input.val().trim();
        if (!code) return;

        $.get("{{ route('show_select_item_description_ajax') }}", { Item_code: code })
        .done(function (res) {
            if (res.status === 'success' && res.data.length) {
                var item = res.data[0];
                row.find('[name="Item_description"]').val(item.Item_description);
                row.find('[name="Unit_price"]').val(item.saleprice);
                row.find('.QTY').trigger('keyup');
            }
        });
    });

    // ── Live recalc in recalled rows ──
    $(document).on('keyup',
        '#dynamicAdded .QTY, #dynamicAdded .Free_Issues, ' +
        '#dynamicAdded .DiscountPercentage, #dynamicAdded .Discount, ' +
        '#dynamicAdded .new-unit-price',
        function () {
            var row   = $(this).closest('tr');
            var up    = parseFloat(row.find('[name="Unit_price"]').val())         || 0;
            var qty   = parseFloat(row.find('[name="QTY"]').val())                || 0;
            var discP = parseFloat(row.find('[name="DiscountPercentage"]').val()) || 0;
            var discV = parseFloat(row.find('[name="Discount"]').val())           || 0;
            var gross    = up * qty;
            var discount = (discP > 0) ? (gross * discP / 100) : discV;
            var net      = gross - discount;
            row.find('[name="Discount"]').val(discount.toFixed(2));
            row.find('[name="Net_value"]').val(net.toFixed(2));
            recalcTotals();
        }
    );

    // ── Edit / Save toggle ──
    $(document).on('click', '.edit-row', function () {
        var row = $(this).closest('tr');
        var btn = $(this);
        if (btn.text().trim().startsWith('Edit')) {
            row.find('[name="QTY"],[name="Free_Issues"],[name="DiscountPercentage"],[name="Discount"],[name="Unit_price"]')
                .prop('readonly', false);
            btn.html('<i class="far fa-save me-1"></i> Save')
                .removeClass('btn-outline-success').addClass('btn-outline-primary');
        } else {
            row.find('input').prop('readonly', true);
            btn.html('<i class="far fa-edit me-1"></i> Edit')
                .removeClass('btn-outline-primary').addClass('btn-outline-success');
            recalcTotals();
        }
    });

    // ── Delete row from recalled table ──
    $(document).on('click', '.remove-row', function () {
        var row   = $(this).closest('tr');
        var rowId = row.data('id');
        var isNew = parseInt(row.data('isnew')) === 1;

        if (!isNew && rowId) {
            if (!confirm('Remove this item from the invoice in the database?')) return;
            $.ajax({
                url:  "{{ route('delete_sales_invoice_item') }}",
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}', id: rowId },
                success: function (res) {
                    if (res.status === 'success') { row.remove(); recalcTotals(); }
                    else alert('Could not remove item. Please try again.');
                },
                error: function () { alert('Server error while removing item.'); }
            });
        } else {
            row.remove();
            recalcTotals();
        }
    });

    // ── UPDATE BUTTON ──
    $('#saveInvoiceBtn').on('click', function () {
        var invoiceNo = $('#invoice_no').val();
        if (!invoiceNo) { alert('Please recall an invoice first.'); return; }

        var items = [];
        $('#dynamicAdded tr:not(#addItemBtnRow)').each(function () {
            var itemCode = $(this).find('[name="Item_code"]').val();
            if (!itemCode) return;
            items.push({
                id:                 $(this).data('id') || '',
                is_new:             parseInt($(this).data('isnew')) === 1,
                Item_code:          itemCode,
                Item_description:   $(this).find('[name="Item_description"]').val(),
                Unit_price:         $(this).find('[name="Unit_price"]').val()          || 0,
                QTY:                $(this).find('[name="QTY"]').val()                 || 0,
                Free_Issues:        $(this).find('[name="Free_Issues"]').val()         || 0,
                DiscountPercentage: $(this).find('[name="DiscountPercentage"]').val()  || 0,
                Discount:           $(this).find('[name="Discount"]').val()            || 0,
                Net_value:          $(this).find('[name="Net_value"]').val()           || 0,
            });
        });

        if (!items.length) { alert('No items to save. Please add at least one item.'); return; }

        var invoiceData = {
            invoice_no:      invoiceNo,
            invoice_date:    $('#invoice_date').val(),
            customer_nic:    $('#searchCustomer').val(),
            customer_name:   $('#customer_name').val()      || '',
            Route:           $('#Route').val(),
            Salesmen:        $('#Salesmen').val(),
            total_amount:    $('#total_amount').val()       || 0,
            paid_discount:   $('#paid_discount').val()      || 0,
            paid_amount:     $('#paid_amount').val()        || 0,
            cash_payment:    $('#cash_payment').val()       || 0,
            half_payment:    $('#half_payment').val()       || 0,
            credite_payment: $('#credite_payment').val()    || 0,
            cheque_payment:  $('#cheque_payment').val()     || 0,
            cheques_data:    $('#cheques_data').val()       || '[]',
            items:           items
        };

        if (!confirm('Update invoice #' + invoiceNo + ' with all changes?')) return;

        $.ajax({
            url:  "{{ route('update_sales_invoice_data') }}",
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', invoice: invoiceData },
            success: function (res) {
                if (res.status === 'success') {
                    $('#addItemBtnRow').hide();
                    alert('Invoice #' + invoiceNo + ' updated successfully!');
                    location.reload();
                } else {
                    alert(res.message || 'Something went wrong. Please try again.');
                }
            },
            error: function (xhr) {
                alert('Server error while updating invoice.');
                console.error(xhr.responseText);
            }
        });
    });

    // ── DELETE ENTIRE INVOICE ──
    $('#deleteInvoice').on('click', function () {
        var invoiceNo = $('#Recall_Invoice').val();
        if (!invoiceNo) { alert('Please enter a Recall Invoice No first.'); return; }
        if (!confirm('Delete ENTIRE invoice #' + invoiceNo + '?\n\nThis cannot be undone!')) return;

        $.ajax({
            url:  '/Sales-invoice/delete/' + invoiceNo,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                alert(res.message);
                location.reload();
            },
            error: function (xhr) {
                alert('Error: ' + (xhr.responseJSON ? xhr.responseJSON.message : 'Unknown error'));
            }
        });
    });

    // ── RESET button clears cheque list too ──
    $('#resetBtn').on('click', function () {
        chequeList = [];
        $('#cheques_data').val('');
        $('#total_cheque_amount').val('');
        $('#chequeListBody').html('');
        $('#totalChequeDisplay').text('0.00');
        $('#chequeListDisplay').hide();
        $('#cg_net_badge').hide();
        newInvoice = { totalGross: 0, totalDiscount: 0, totalValue: 0 };
    });

}); // end document.ready
</script>

{{-- External scripts --}}
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>
<script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/js/script.js"></script>

</body>
</html>
@endsection