@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
<title>Create Sales Order</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>

<link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

@include('sales.partials.styles')
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
            <h4>Create Sales Order</h4>
            <p>Prepare customer orders, items and discounts without payments</p>
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


        {{-- ══════════════════════════════════════════════════════════
        MAIN FORM
        ═══════════════════════════════════════════════════════════ --}}
        <form action="{{ route('sales_orders.save') }}" method="post" id="sales_form">
        @csrf
        <input type="hidden" name="sales_order_id" id="sales_order_id">
        <div class="recall-panel">
            <div class="recall-panel-head">
                <div class="recall-panel-icon"><i class="fas fa-history"></i></div>
                <div>
                    <label for="load_order_no">Recall Sales Order</label>
                    <p>Load an existing draft order to review or continue editing it</p>
                </div>
            </div>
            <div class="recall-panel-body">
                <div class="sales-input-action recall-input">
                    <i class="fas fa-hashtag"></i>
                    <input type="number" min="1" id="load_order_no" list="sales_order_numbers" placeholder="Enter sales order number">
                </div>
                <button type="button" id="loadOrderBtn" class="recall-load-btn">
                    <i class="fas fa-redo"></i> Load Order
                </button>
            </div>
            <datalist id="sales_order_numbers">
                @foreach($recentOrders as $recentOrder)
                    <option value="{{ $recentOrder->id }}">{{ $recentOrder->invoice_no ? 'Invoiced: '.$recentOrder->invoice_no : 'Open' }}</option>
                @endforeach
            </datalist>
            <p id="orderStatus" class="recall-status" role="status"></p>
            @if(session('saved_order_id'))
                <div class="recall-actions">
                    <a class="recall-action-btn recall-action-primary" href="{{ route('salesInvoice_withoutVat', ['order' => session('saved_order_id')]) }}">
                        <i class="fas fa-file-invoice"></i> Create Sales Invoice
                    </a>
                    <a class="recall-action-btn recall-action-secondary" target="_blank" href="{{ route('sales_orders.print', session('saved_order_id')) }}">
                        <i class="fas fa-print"></i> Print Sales Order
                    </a>
                </div>
            @endif
            <div id="recallLoadedActions" class="recall-actions" hidden>
                <span class="recall-edit-badge"><i class="fas fa-pen"></i> Editing — change items below and hit Save</span>
                <a id="recallLoadedPrint" class="recall-action-btn recall-action-secondary" target="_blank">
                    <i class="fas fa-print"></i> Print Sales Order
                </a>
            </div>
        </div>

        <div class="sales-form-panel">
            <div class="sales-field-grid sales-field-grid-no-recall">
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
                    <label><i class="fas fa-file-invoice"></i>Sales Order No</label>
                    <div class="sales-icon-wrap">
                        <i class="fas fa-file-invoice"></i>
                        <input type="text" id="invoice_no" name="invoice_no"
                            value="{{ $nextOrderNo }}" class="sales-textbox" readonly>
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
                        <th>Item Code</th>
                        <th>Description</th>
                        <th>Batch</th>
                        <th>GRN Price</th>
                        <th>QTY</th>
                        <th>Discount (%)</th>
                        <th>Net Value</th>
                        <th>Action</th>
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
                        <td>
                            <input class="form-control sales-item-input" type="number" placeholder="0%" id="discount" name="discount" min="0" max="100" step="0.01" value="0" aria-label="Item discount percentage">
                            <input type="hidden" id="discount_val" name="discount_val" value="0">
                        </td>
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
                    <td><strong>TOTAL :</strong></td>
                    <td></td>
                    <td></td>
                    <td class="total-unit-price text-center"><strong>0.00</strong></td>
                    <td></td>
                    <td class="total-discount text-center"><strong>0.00</strong></td>
                    <td class="total-value text-center"><strong>0.00</strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        </div>

{{-- ══ Payment section ══ --}}
<div class="stock-info-card mt-3">
    <div class="stock-info-grid-2">
        <div class="si-field"><label>Gross Amount</label><input class="form-control" id="total_amount" name="gross_amount" readonly></div>
        <div class="si-field"><label>Discount Total</label><input class="form-control" id="paid_discount" name="discount" readonly></div>
        <div class="si-field"><label>Net Amount</label><input class="form-control" id="paid_amount" name="net_amount" readonly></div>
        <div class="si-field"><label>Order Remark</label><input class="form-control" id="invoice_remark" name="invoice_remark"></div>
    </div>
</div>

{{-- ── Action Buttons ── --}}
<div class="row mt-3">
    <div class="col-md-12">


        <div class="d-flex gap-2 flex-wrap">
        @if(\App\Support\Permissions::canDo('sales', 'add'))
        <button type="submit" name="save" class="btn btn-outline-info btn-lg shadow">
            <i class="fas fa-save"></i> Save Sales Order
        </button>
        @endif
        <button type="button" id="pawn_cancel" class="btn btn-outline-warning pawn_cancel btn-lg shadow"
            onclick="if(confirm('Discard this form and start over?')) window.location.reload();">
            <i class="fas fa-times-circle"></i> Cancel
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
                    <input type="text" id="cus_search_box" class="form-control" placeholder="Search by name..." autocomplete="off" autocorrect="off" spellcheck="false">
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
                    <input type="text" id="item_search_box" class="form-control" placeholder="Search by name..." autocomplete="off" autocorrect="off" spellcheck="false">
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


{{-- ══════════════════════════════════════════════════════════
FORM SUBMIT VALIDATION
══════════════════════════════════════════════════════════ --}}
<script>
document.getElementById('sales_form').addEventListener('submit', function (e) {
    if ($('#sales_form').data('order-load-pending')) {
        e.preventDefault(); alert('Load an open sales order successfully, or start over before saving.'); return;
    }
    if (!$('#searchCustomer').val().trim() || !$('#dynamicAdded tr').length) {
        e.preventDefault(); alert('Select a customer and add at least one item before saving the order.');
    }
});
</script>

{{-- ══════════════════════════════════════════════════════════
PRINT INVOICE
══════════════════════════════════════════════════════════ --}}
<script>
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
            calculateEntryRow();
            updateCostBadge();
            loadItemBatches(code);
        }
    });
}

// Load every batch (including depleted ones) for the selected item into #item_batch.
// Depleted batches (qty_remaining <= 0) still show up, but greyed out and disabled —
// so they stay visible for context without being pickable. Non-batch-tracked items
// simply come back empty, leaving "(no batch)" selected.
//
// setItemDetails() can fire more than once in quick succession for the same item
// (typing in the item code field and picking it from the search modal both trigger
// it), so two overlapping calls here used to both append their own copy of the list
// on top of each other. A simple request token makes sure only the response from the
// most recent call is ever allowed to populate the dropdown.
var batchLoadToken = 0;
function loadItemBatches(itemCode) {
    var batchSelect = $('#item_batch');
    batchSelect.html('<option value="">(no batch)</option>');

    var myToken = ++batchLoadToken;
    $.get("{{ url('get_item_batches_ajax') }}", { item_code: itemCode })
        .done(function (res) {
            if (myToken !== batchLoadToken) { return; } // a newer call superseded this one
            batchSelect.html('<option value="">(no batch)</option>');
            if (res.data && res.data.length) {
                res.data.forEach(function (b) {
                    var isEmpty = parseFloat(b.qty_remaining) <= 0;
                    batchSelect.append($('<option>', {
                        value: b.batch_no,
                        'data-sale-price': b.sale_price,
                        disabled: false,
                        style: isEmpty ? 'color:#aaa;' : '',
                        text: b.batch_no + ' (Qty ' + b.qty_remaining + ', Rs.' + b.sale_price + ')' + (isEmpty ? ' — Out of stock' : '')
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

function calculateEntryRow() {
    var price = parseFloat($('#unit_price').val()) || 0;
    var qty = parseFloat($('#qty').val()) || 0;
    var discountPercentage = parseFloat($('#discount').val()) || 0;
    var gross = price * qty;
    var discountValue = gross * discountPercentage / 100;

    $('#discount_val').val(discountValue.toFixed(2));
    $('#net_value').val((gross - discountValue).toFixed(2));
    updateCostBadge();
}

$(document).on('input', '#qty, #unit_price, #discount', calculateEntryRow);
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
    var discount         = parseFloat($('#discount').val()) || 0;
    var discount_val     = parseFloat($('#discount_val').val()) || 0;
    var net_value        = parseFloat($('#net_value').val())    || 0;
    var batch_no         = $('#item_batch').val();

    if (discount < 0 || discount > 100) {
        alert('Discount percentage must be between 0 and 100.');
        return;
    }

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
    var d = Object.assign({}, pendingAddPayload);
    ['item_code', 'item_description', 'batch_no', 'customer_nic', 'item_s_code', 'Per'].forEach(function (key) {
        d[key] = String(d[key] || "").replace(/[&<>"']/g, function (c) { return {"&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;", "'":"&#39;"}[c]; });
    });
    pendingAddPayload = null;

    rowIndex++;
    $('#dynamicAdded').prepend(`
    <tr>
        <td>
            <input type="hidden" name="inputs[${rowIndex}][customer_nic]"  value="${d.customer_nic}">
            <input type="hidden" name="inputs[${rowIndex}][invoice_no]"    value="${d.invoice_no}">
            <input type="hidden" name="inputs[${rowIndex}][invoice_date]"  value="${d.invoice_date}">
            <input class="form-control" type="text"
                name="inputs[${rowIndex}][item_code]" value="${d.item_code}" readonly>
            <input type="hidden" name="inputs[${rowIndex}][item_s_code]"   value="${d.item_s_code}">
            <input type="hidden" name="inputs[${rowIndex}][Per]"           value="${d.Per}">
        </td>
                <td>
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
        <td>
            <input class="form-control" type="text"
                name="inputs[${rowIndex}][batch_no]" value="${d.batch_no || ''}" readonly>
        </td>
        <td>
            <input class="form-control" type="text"
                name="inputs[${rowIndex}][unit_price]" value="${d.unit_price}" min="0" step="any">
        </td>
        <td>
            <input class="form-control" type="text"
                name="inputs[${rowIndex}][qty]" value="${d.qty}" min="0.001" step="any">
            <input type="hidden" name="inputs[${rowIndex}][Free_Issues]" value="${d.Free_Issues || 0}">

            <input type="hidden" name="inputs[${rowIndex}][discount_val]" value="${d.discount_val || 0}">
        </td>
        <td>
            <input class="form-control" type="number" name="inputs[${rowIndex}][discount]" value="${d.discount || 0}" min="0" max="100" step="any" aria-label="Discount percentage">
        </td>
        <td>
            <input class="form-control" type="text"
                name="inputs[${rowIndex}][net_value]" value="${d.net_value || 0}" readonly>
        </td>
        <td class="text-center">
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
    $('#cus_search_box').on('keyup', function () {
        CustomerPickerTable.search($(this).val()).draw();
    });
    // Browsers sometimes autofill any input whose id/name looks like a
    // "name" field with the saved login username — clear it and re-run
    // an empty search whenever the modal opens so a stale filter never
    // hides every row.
    $('#selectCustomerModel').on('shown.bs.modal', function () {
        $('#cus_search_box').val('');
        CustomerPickerTable.search('').draw();
    });

    var ItemPickerTable = $('#ItemTableCustomer').DataTable({
        paging: true, searching: true, ordering: true, info: false,
        lengthChange: false, pageLength: 10, dom: 't',
    });
    DTCustomPager.init(ItemPickerTable, '#ItemTableCustomerCustomPager');
    $('#item_search_box').on('keyup', function () {
        ItemPickerTable.search($(this).val()).draw();
    });
    $('#searchItemModel').on('shown.bs.modal', function () {
        $('#item_search_box').val('');
        ItemPickerTable.search('').draw();
    });
});
</script>

{{-- ══════════════════════════════════════════════════════════
RECALL / EDIT / UPDATE / DELETE
══════════════════════════════════════════════════════════ --}}


{{-- External scripts --}}
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>
<script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/js/script.js"></script>

<script>
$(function () {
    function recalculateOrderRows() {
        newInvoice = {totalGross: 0, totalDiscount: 0, totalValue: 0};
        $('#dynamicAdded tr').each(function () {
            var row = $(this);
            var qty = parseFloat(row.find('[name$="[qty]"]').val()) || 0;
            var price = parseFloat(row.find('[name$="[unit_price]"]').val()) || 0;
            var discount = parseFloat(row.find('[name$="[discount]"]').val()) || 0;
            var gross = qty * price;
            var reduction = Math.round(gross * discount) / 100;
            var net = Math.round((gross - reduction) * 100) / 100;
            row.find('[name$="[discount_val]"]').val(reduction.toFixed(2));
            row.find('[name$="[net_value]"]').val(net.toFixed(2));
            newInvoice.totalGross += gross;
            newInvoice.totalDiscount += reduction;
            newInvoice.totalValue += net;
        });
        updateNewInvoiceTotals();
    }
    $(document).on('input', '#dynamicAdded [name$="[qty]"], #dynamicAdded [name$="[unit_price]"], #dynamicAdded [name$="[discount]"]', recalculateOrderRows);
    $('#loadOrderBtn').on('click', function () {
        var no = $('#load_order_no').val();
        if (!no) return;
        $('#sales_form').data('order-load-pending', true);
        $('#sales_form button[type=submit]').prop('disabled', true);
        var btn = $(this).prop('disabled', true);
        $('#orderStatus').removeClass('is-success is-warn is-error').text('Loading sales order...');
        $('#recallLoadedActions').prop('hidden', true);
        $.get(@json(url('/sales_orders')) + '/' + encodeURIComponent(no)).done(function (res) {
            if (res.invoice_no !== null) {
                $('#orderStatus').removeClass('is-success is-error').addClass('is-warn')
                    .text('This order is locked and linked to Invoice #' + res.invoice_no);
                $('#sales_form').data('order-load-pending', false);
                $('#sales_form button[type=submit]').prop('disabled', false);
                return;
            }
            var p = res.payload;
            var previous = @json(old('inputs') ? old() : null);
            if (previous && String(previous.sales_order_id) === String(res.id)) p = previous;
            $('#sales_order_id').val(res.id);
            $('#invoice_no').val(res.id);
            $('#searchCustomer').val(p.customer_nic);
            loadCustomerDetails(p.customer_nic);
            $('#invoice_date').val(p.invoice_date);
            ['Route', 'Salesmen', 'ref_no', 'customer_type', 'invoice_remark'].forEach(function (key) {
                $('#sales_form [name="' + key + '"]').val(p[key] || '');
            });
            $('#dynamicAdded').empty();
            newInvoice = {totalGross: 0, totalDiscount: 0, totalValue: 0};
            Object.values(p.inputs).forEach(function (line) {
                var gross = Number(line.qty) * Number(line.unit_price);
                var reduction = Math.round(gross * Number(line.discount || 0)) / 100;
                pendingAddPayload = Object.assign({}, line, {
                    customer_nic: p.customer_nic,
                    invoice_no: $('#invoice_no').val(), invoice_date: $('#invoice_date').val(),
                    qty: Number(line.qty), unit_price: Number(line.unit_price),
                    discount: Number(line.discount || 0), discount_val: reduction,
                    net_value: Math.round((gross - reduction) * 100) / 100
                });
                commitAddItem();
            });
            recalculateOrderRows();
            $('#sales_form').data('order-load-pending', false);
            $('#sales_form button[type=submit]').prop('disabled', false);
            $('#orderStatus').removeClass('is-warn is-error').addClass('is-success')
                .text('Sales Order #' + res.id + ' loaded. You can edit the item details before saving.');
            $('#recallLoadedPrint').attr('href', @json(url('/sales_orders')) + '/' + res.id + '/print');
            $('#recallLoadedActions').prop('hidden', false);
        }).fail(function (xhr) {
            $('#orderStatus').removeClass('is-success is-warn').addClass('is-error')
                .text(xhr.responseJSON?.message || 'Unable to load sales order.');
            $('#sales_form').data('order-load-pending', false);
            $('#sales_form button[type=submit]').prop('disabled', false);
        }).always(function () { btn.prop('disabled', false); });
    });
    $('#load_order_no').on('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); $('#loadOrderBtn').click(); }
    });
    var unsaved = @json(old('inputs') && !old('sales_order_id') ? old() : null);
    if (unsaved) {
        $('#searchCustomer').val(unsaved.customer_nic); loadCustomerDetails(unsaved.customer_nic);
        $('#invoice_date').val(unsaved.invoice_date);
        $('#invoice_remark').val(unsaved.invoice_remark || '');
        Object.values(unsaved.inputs).forEach(function (line) {
            var gross = Number(line.qty) * Number(line.unit_price);
            var discount = Math.round(gross * Number(line.discount || 0)) / 100;
            pendingAddPayload = Object.assign({}, line, {invoice_no:'New', invoice_date:unsaved.invoice_date,
                customer_nic:unsaved.customer_nic, qty:Number(line.qty),unit_price:Number(line.unit_price),
                discount:Number(line.discount || 0),discount_val:discount, net_value:Math.round((gross-discount)*100)/100});
            commitAddItem();
        });
        recalculateOrderRows();
    }
    var initialOrder = @json(old('sales_order_id', session('saved_order_id', request('order'))));
    if (initialOrder) { $('#load_order_no').val(initialOrder); $('#loadOrderBtn').click(); }
});
</script>
</body>
</html>
@endsection
