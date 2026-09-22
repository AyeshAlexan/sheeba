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
        .purchase-table-card { background:#f5f9ff; border:1px solid #dfeaf6; border-radius:16px; margin-top:18px; padding:14px; box-shadow:0 10px 25px rgba(42,92,171,.04); }
        .purchase-table-card > .card-body { padding:0; }
        .purchase-item-table, .purchase-total-table { width:100%; table-layout:fixed; border:1px solid #d9e3ee; border-radius:12px; overflow:hidden; background:#fff; border-collapse:separate; border-spacing:0; }
        .purchase-item-table thead th { background:#fff; color:#2b3e5b; font-size:12px; font-weight:800; padding:12px 10px; border-bottom:1px solid #d9e3ee; text-align:center; }
        .purchase-item-table tbody td { padding:10px 8px; border-color:#edf1f5; vertical-align:middle; }
        .purchase-item-table .form-control { min-height:42px; border:1px solid #d7e3f1; border-radius:10px; }
        .purchase-item-table .add-item { background:linear-gradient(135deg,#4c8cf7,#2d6ce9); color:#fff; border:0; border-radius:9px; min-height:38px; min-width:100px; }
        .purchase-total-table { margin-top:10px; }
        .purchase-total-table td { background:#edf4ff !important; color:#234b7d; border-color:#d9e3ee !important; font-weight:700; }
        .purchase-total-table .stock-total-badge { background:transparent !important; color:inherit !important; padding:0 !important; border-radius:0 !important; }
        .purchase-item-table th:nth-child(1), .purchase-item-table td:nth-child(1), .purchase-total-table td:nth-child(1) { width:15% !important; }
        .purchase-item-table th:nth-child(2), .purchase-item-table td:nth-child(2), .purchase-total-table td:nth-child(2) { width:20% !important; }
        .purchase-item-table th:nth-child(3), .purchase-item-table td:nth-child(3), .purchase-total-table td:nth-child(3) { width:12% !important; }
        .purchase-item-table th:nth-child(4), .purchase-item-table td:nth-child(4), .purchase-total-table td:nth-child(4) { width:12% !important; }
        .purchase-item-table th:nth-child(5), .purchase-item-table td:nth-child(5), .purchase-total-table td:nth-child(5) { width:12% !important; }
        .purchase-item-table th:nth-child(6), .purchase-item-table td:nth-child(6), .purchase-total-table td:nth-child(6) { width:12% !important; }
        .purchase-item-table th:nth-child(7), .purchase-item-table td:nth-child(7), .purchase-total-table td:nth-child(7) { width:13% !important; }
        .purchase-item-table th:nth-child(8), .purchase-item-table td:nth-child(8), .purchase-total-table td:nth-child(8) { width:12% !important; }
        .purchase-table-card .stock-search-wrap { max-width:360px; }
        .purchase-table-card .stock-search-wrap input { height:42px; border:1px solid #d7e3f1; border-radius:10px; }
        #searchItemModel .modal-dialog { max-width:900px; }
        #searchItemModel .modal-content { border:1px solid #dfeaf6; border-radius:16px; overflow:hidden; }
        #searchItemModel .modal-header { padding:14px 18px; background:#f5f9ff; border-bottom:1px solid #dfeaf6; }
        #searchItemModel .modal-body { padding:16px 18px 20px; max-height:65vh !important; }
        #searchItemModel #ItemTable { width:100% !important; table-layout:fixed; margin:0 !important; border:1px solid #d9e3ee; border-radius:10px; overflow:hidden; border-collapse:separate; border-spacing:0; font-size:13px; }
        #searchItemModel #ItemTable thead th { background:#fff; color:#2b3e5b; border-bottom:1px solid #d9e3ee; padding:10px 12px; font-size:12px; font-weight:800; text-align:left; }
        #searchItemModel #ItemTable tbody td { padding:8px 12px; border-color:#edf1f5; color:#314765; vertical-align:middle; }
        #searchItemModel #ItemTable th:first-child, #searchItemModel #ItemTable td:first-child { width:0; padding:0; border:0; }
        #searchItemModel #ItemTable th:nth-child(2), #searchItemModel #ItemTable td:nth-child(2) { width:16%; }
        #searchItemModel #ItemTable th:nth-child(3), #searchItemModel #ItemTable td:nth-child(3) { width:14%; }
        #searchItemModel #ItemTable th:nth-child(4), #searchItemModel #ItemTable td:nth-child(4) { width:32%; }
        #searchItemModel #ItemTable th:nth-child(5), #searchItemModel #ItemTable td:nth-child(5) { width:16%; text-align:right; }
        #searchItemModel #ItemTable th:nth-child(6), #searchItemModel #ItemTable td:nth-child(6) { width:12%; text-align:right; }
        #searchItemModel #ItemTable th:last-child, #searchItemModel #ItemTable td:last-child { width:10%; text-align:center; }
        #searchItemModel #ItemTable th:not(:first-child), #searchItemModel #ItemTable td:not(:first-child) { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        #searchItemModel #ItemTable .dt-act-btn { width:32px; height:32px; display:inline-flex; align-items:center; justify-content:center; padding:0; border-radius:8px; color:#2d6ce9; border:1px solid #bcd3f7; background:#f5f9ff; }
        #searchItemModel #ItemTable .dt-act-btn:hover { color:#fff; background:#2d6ce9; }
        #searchItemModel #ItemTableCustomPager { display:flex; justify-content:center; width:100%; margin-top:18px; }
        #searchItemModel #ItemTableCustomPager .dt-custom-pager-row { margin-top:0; width:100%; justify-content:center; }
    </style>
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
                <div class="page-header ph-flex">
                    <div class="ph-left">
                        <div class="ph-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        </div>
                        <div>
                            <h3 class="page-title">Purchase Invoice</h3>
                            <p class="page-subtitle">Record incoming stock purchases from suppliers</p>
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
                                <div class="alert alert-danger" role="alert">
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


                                <form action="{{route('add_Purchases')}}" method="post" id="purchase_form">
                                    @csrf
                                    <div class="row ">
                                    <div class="stock-info-card">
                                        <div class="stock-info-grid-p">
                                            <div class="si-field">
                                                <label>Supplier Code <span class="text-danger">*</span></label>
                                                <div class="stock-item-search-row">
                                                    <div class="stock-item-search-wrap">
                                                        <i class="fas fa-search"></i>
                                                        <input type="text" id="searchCustomer" name="customer_nic"
                                                            class="form-control" placeholder="Enter Supplier Code"
                                                            required aria-label="Supplier Code">
                                                    </div>
                                                    <button type="button" class="stock-item-search-btn"
                                                        data-bs-toggle="modal" data-bs-target="#addguarantor1Model" title="Search supplier">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="stock-item-search-btn"
                                                        data-bs-toggle="modal" data-bs-target="#addCustomerModel" title="Add new supplier">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="si-field">
                                                <label>No.</label>
                                                <div class="si-icon-wrap">
                                                    <i class="fas fa-file-alt"></i>
                                                    <input type="text" id="invoice_no" name="invoice_no"
                                                        value="{{$maxInvoiceNo+1}}" class="form-control"
                                                        placeholder="Invoice Number" aria-label="Invoice Number">
                                                </div>
                                            </div>
                                            <div class="si-field">
                                                <label>Purchase No.</label>
                                                <div class="si-icon-wrap">
                                                    <i class="fas fa-receipt"></i>
                                                    <input type="text" id="sales_invoice_no" name="sales_invoice_no"
                                                        class="form-control"
                                                        placeholder="Stock Sales No" aria-label="Stock Sales No">
                                                </div>
                                            </div>
                                            <div class="si-field">
                                                <label>Date</label>
                                                <div class="si-icon-wrap">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <input type="date" id="invoice_date" name="invoice_date"
                                                        class="form-control" aria-label="Date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="customer-data"></div>
                                    <div class="showCustomer"></div>
                                    <div class="payment-history" style="display:none;"></div>

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





                                        {{-- Item Details card --}}
                                        <div class="card purchase-table-card">
                                            <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-list" style="color:var(--tr-blue);"></i>
                                                    <strong style="font-size:15px;color:var(--tr-navy);">Item Details</strong>
                                                </div>
                                                <div class="stock-search-wrap">
                                                    <i class="fas fa-search"></i>
                                                    <input type="text" id="stockItemSearch" class="form-control" placeholder="Search item code or description...">
                                                </div>
                                            </div>
                                        <div class="table-responsive">
                                        <table class="table table-bordered purchase-item-table" >
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="text-align:center;">Item Code</th>
                                                    <th style="text-align: center;">Description</th>
                                                    <th style="text-align: center;">GRN Price</th>
                                                    <th style="text-align: center;">QTY</th>
                                                    <th style="text-align: center;">Net Value</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr>
                                                    <td id="showItems">
                                                        <div class="stock-item-search-row">
                                                            <input type="hidden" id="item_code" name="item_code" class="form-control">
                                                            <div class="stock-item-search-wrap">
                                                                <i class="fas fa-search"></i>
                                                                <input type="text" id="item_s_code" name="item_s_code" class="form-control"
                                                                    placeholder="Enter item code..." required aria-label="item Code">
                                                            </div>
                                                            <button type="button" class="stock-item-search-btn"
                                                                data-bs-toggle="modal" data-bs-target="#searchItemModel" title="Browse items">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
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
                                            </div>
                                        </div>

                                        <div id="addSerial"></div>

                                        {{--------------search Item Model----------------- --}}
                                        <div class="modal fade" id="searchItemModel" tabindex="-1" role="dialog"
                                            aria-labelledby="searchItemModelLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="searchItemModelLabel">Search Item</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                                                        <div class="errMsgContainer"></div>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-center table-hover"
                                                                id="ItemTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="display:none;">Code</th>
                                                                        <th>Code</th>
                                                                        <th>Bar Code</th>
                                                                        <th>Name</th>
                                                                        <th>Credit Price</th>
                                                                        <th>Price</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($itemDetails as $key=>$ItemData)
                                                                    <tr>
                                                                        <td style="display:none;">{{$ItemData->Item_code }}</td>
                                                                        <td>{{$ItemData->Item_code }}</td>
                                                                        <td>{{$ItemData->Bar_code}}</td>
                                                                        <td>{{$ItemData->Item_description}}</td>
                                                                        <td style="text-align: right">{{$ItemData->Credit}}</td>
                                                                        <td style="text-align: right">{{$ItemData->purchasePrice}}</td>
                                                                        <td>
                                                                            <a href="javascript:void(0)"
                                                                                class="dt-act-btn dt-act-edit"
                                                                                name="add_item"
                                                                                id="add_item"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#searchItemModel"
                                                                                data-id="{{$ItemData->id}}"
                                                                                data-add_item_code="{{$ItemData->Item_code}}"
                                                                                data-Item_description="{{$ItemData->Item_description}}"
                                                                                data-Bar_code="{{$ItemData->Bar_code}}"
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
                                        <script src="assets/js/dt-custom-pager.js"></script>
                                        <script>
                                            $(document).ready(function() {
                                                var ItemTableDt = $('#ItemTable').DataTable({
                                                    pageLength: 10,
                                                    lengthChange: false,
                                                    dom: 'ft',
                                                });
                                                DTCustomPager.init(ItemTableDt, '#ItemTableCustomPager');
                                            });
                                        </script>

                                        {{-- dynamicAdded table --}}
                                        <table class="table table-bordered purchase-item-table" id="dynamicAdded">

                                        </table>

                                        {{-- table footer for total calculations --}}
                                        <table class="table table-bordered purchase-total-table" id="green_total_row">
                                            <tbody>
                                                    <tr class="stock-total-row">
                                                    <td> </td>
                                                    <td><strong>TOTAL :</strong></td>
                                                    <td class="total-unit-price text-center">
                                                        <span class="stock-total-badge">0.00</span>
                                                    </td>
                                                    <td></td>
                                                    <td class="total-value text-center">
                                                        <span class="stock-total-badge">0.00</span>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        {{-- bottom values section  --}}
                                        <div class="stock-info-card mt-3">
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
                                                    <label>Credit</label>
                                                    <div class="si-icon-wrap">
                                                        <i class="fas fa-credit-card"></i>
                                                        <input type="text" class="form-control" placeholder="0.00"
                                                            id="credite_payment" name="credite_payment" aria-label="Credit">
                                                    </div>
                                                </div>
                                                <div class="si-field">
                                                    <label>Cheque</label>
                                                    <div class="si-icon-wrap">
                                                        <i class="fas fa-money-check-alt"></i>
                                                        <input type="text" class="form-control" placeholder="0.00"
                                                            id="cheque_payment" name="cheque_payment" aria-label="Cheque">
                                                    </div>
                                                </div>
                                                <div class="si-field">
                                                    <label>Gross Amount</label>
                                                    <div class="si-icon-wrap">
                                                        <i class="fas fa-coins"></i>
                                                        <input type="text" class="form-control" placeholder="0.00"
                                                            id="total_amount" name="gross_amount" aria-label="Gross Amount">
                                                    </div>
                                                </div>
                                                <div class="si-field">
                                                    <label>Discount</label>
                                                    <div class="si-icon-wrap">
                                                        <i class="fas fa-percent"></i>
                                                        <input type="text" class="form-control" placeholder="0.00"
                                                            id="paid_discount" name="discount" aria-label="Discount" required>
                                                    </div>
                                                </div>
                                                <div class="si-field">
                                                    <label>Net Amount</label>
                                                    <div class="si-icon-wrap">
                                                        <i class="fas fa-wallet"></i>
                                                        <input type="text" class="form-control" placeholder="0.00"
                                                            id="paid_amount" name="net_amount" aria-label="Net Amount" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- bottom buttons  --}}
                                        <div class="d-flex gap-2 mt-2">
                                            <button type="submit" name="save" id="save" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Save
                                            </button>
                                            <button type="button" name="submit_recall" id="submit_recall" style="display: none;" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Add
                                            </button>
                                            <button type="button" name="print" class="btn printReceipt btn-outline-primary" onclick="window.print();">
                                                <i class="fas fa-print"></i> Print
                                            </button>
                                            <button type="button" name="pawn_delete" id="pawn_delete" class="btn btn-outline-danger pawn_delete d-none">DELETE</button>
                                            <button type="button" name="pawn_cancel" id="pawn_cancel" class="btn btn-outline-warning pawn_cancel"
                                                onclick="if(confirm('Discard this form and start over?')) window.location.reload();">
                                                <i class="fas fa-times"></i> Cancel
                                            </button>
                                        </div>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addguarantor1Model" tabindex="-1" role="dialog" aria-labelledby="addguarantor1Model" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title m-2" id="addguarantor1Model">Search First Guarantor</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body" style="max-height:70vh; overflow-y:auto;">
                        <!-- Error Message Container -->
                        <div class="errMsgContainer"></div>

                        <!-- Guarantor Table -->
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
                                    @foreach ($SupplierData as $data)
                                    <tr>
                                        <td>{{ $data->Code  }}</td>
                                        <td>
                                            <div class="item-description-wrapper">{{ $data->Name }}</div>
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)" onclick="fillGuarantOneCode('{{ $data->Code }}')" class="dt-act-btn dt-act-edit" data-bs-dismiss="modal" title="Add">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div id="GuaranttableoneCustomPager"></div>
                        </div>

                        <!-- DataTable Initialization -->
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

{{-- filter already-added item rows by code/description --}}
<script>
    $(document).on('keyup', '#stockItemSearch', function () {
        let q = $(this).val().toLowerCase();
        $('#dynamicAdded tr').each(function () {
            let text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(q) !== -1);
        });
    });
</script>

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
    $(document).on('click', '#ItemTable tbody tr', function () {
        var item_code_add = this.cells[0].textContent;
        $('#item_code').val(item_code_add);
        setItemDetails();
        $("#searchItemModel").modal('hide');
        $('#item_name').val("");
    });
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