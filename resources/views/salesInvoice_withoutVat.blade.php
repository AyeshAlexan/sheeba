@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
<!DOCTYPE html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}"><title>Sales Invoice</title>
@include('sales.partials.styles')
<style>
#invoiceLines input{min-width:80px}#invoiceLines td{vertical-align:middle}
#invoiceDetails[hidden]{display:none!important}.invoice-summary{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px}
</style>
</head><body>
<div class="main-wrapper"><div class="page-wrapper"><div class="content container-fluid">
<div class="card sales-shell">
    <div class="sales-header"><div class="sales-header-icon"><i class="fas fa-receipt"></i></div>
        <div class="sales-header-copy"><h4>Sales Invoice</h4><p>Load a sales order, review its details and enter payments. Saving finalizes the invoice and reduces stock.</p></div>
    </div>
    <div class="card-body">
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @if($errors->any())<div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if(session('done'))
            <div class="alert alert-success text-center"><p>{{ session('done') }}</p>
                <a class="btn btn-warning" href="{{ session('print_url') }}" target="_blank">Print A4 Invoice</a>
                <a class="btn btn-secondary" href="{{ session('pos_print_url') }}" target="_blank">Print POS Receipt</a>
            </div>
        @endif
        <div class="recall-panel">
            <div class="recall-panel-head">
                <div class="recall-panel-icon"><i class="fas fa-history"></i></div>
                <div>
                    <label>Load or Recall</label>
                    <p>Load an open sales order to convert it into an invoice, or recall a saved invoice to view or print it</p>
                </div>
            </div>
            <div class="recall-row">
                <div class="recall-row-label"><i class="fas fa-dolly"></i> Load Sales Order No</div>
                <div class="recall-row-body">
                    <div class="sales-input-action recall-input">
                        <i class="fas fa-hashtag"></i>
                        <input type="number" min="1" id="load_order_no" list="openOrders" placeholder="Sales Order No">
                    </div>
                    <datalist id="openOrders">@foreach($recentOrders as $order)<option value="{{ $order->id }}">Sales Order #{{ $order->id }}</option>@endforeach</datalist>
                    <button type="button" id="loadOrderBtn" class="recall-load-btn">
                        <i class="fas fa-redo"></i> Load Order
                    </button>
                </div>
            </div>
            <div class="recall-row">
                <div class="recall-row-label"><i class="fas fa-receipt"></i> Recall Saved Invoice No</div>
                <div class="recall-row-body">
                    <div class="sales-input-action recall-input">
                        <i class="fas fa-hashtag"></i>
                        <input type="text" id="recall_invoice_no" placeholder="Invoice No to view or print">
                    </div>
                    <button type="button" id="recallInvoiceBtn" class="recall-secondary-btn">
                        <i class="fas fa-eye"></i> View Invoice
                    </button>
                </div>
            </div>
            <p id="orderStatus" class="recall-status" role="status">Load an order to review its customer, items and payments.</p>
        </div>
        <form id="sales_form" action="{{ route('add_salesInvoice_withoutVat') }}" method="post">
            @csrf
            <input type="hidden" id="sales_order_id" name="sales_order_id">
            <input type="hidden" id="customer_nic" name="customer_nic">
            <input type="hidden" id="customer_name" name="customer_name">
            <input type="hidden" id="customer_phone" name="customer_phone">
            <input type="hidden" id="customer_type" name="customer_type">
            <input type="hidden" id="Route" name="Route"><input type="hidden" id="Salesmen" name="Salesmen">
            <input type="hidden" id="ref_no" name="ref_no"><input type="hidden" id="customer_balance" name="customer_balance" value="0">
            <input type="hidden" id="cheques_data" name="cheques_data"><input type="hidden" id="total_cheque_amount" name="total_cheque_amount">
            <div id="invoiceDetails" hidden>
                <div class="sales-form-panel invoice-summary">
                    <div class="si-field"><label>Sales Order No</label><span id="loadedOrderNo"></span></div>
                    <div class="si-field"><label>Customer</label><span id="loadedCustomer"></span></div>
                    <div class="si-field"><label for="invoice_no">Invoice No</label><input class="form-control" id="invoice_no" name="invoice_no" value="{{ $maxInvoiceNo + 1 }}" readonly></div>
                    <div class="si-field"><label for="invoice_date">Invoice Date</label><input class="form-control" id="invoice_date" name="invoice_date" type="date" value="{{ now()->toDateString() }}" required></div>
                </div>
                <div class="sales-table-card mt-3">
                    <div class="sales-table-head"><div class="sales-table-title"><i class="fas fa-list"></i> Order Items</div></div>
                    <p class="text-muted" id="itemEditHint">Correct quantities, prices or discounts here. Add new items through Sales Order.</p>
                    <div class="table-responsive"><table class="table" id="invoiceLines"><thead><tr>
                        <th>Item Code</th><th>Description</th><th>Batch</th><th>Unit Price</th><th>QTY</th><th>Discount (%)</th><th>Net Value</th><th>Action</th>
                    </tr></thead><tbody></tbody><tfoot><tr><th colspan="6">Net Total</th><th id="itemsNetTotal">0.00</th><th></th></tr></tfoot></table></div>
                </div>
                @include('sales.partials.payments')
                <div id="savedInvoiceNotice" class="alert alert-info mt-3" role="status" hidden></div>
                <div class="mt-3 d-flex gap-2 flex-wrap">
                    @if(\App\Support\Permissions::canDo('sales', 'add') && \App\Support\Permissions::canDo('sales', 'save'))
                        <button type="submit" id="finalizeInvoiceBtn" class="btn btn-primary btn-lg">Save Invoice</button>
                    @endif
                    <a class="btn btn-outline-secondary" href="{{ route('salesInvoice_withoutVat') }}">Start Over</a>
                    @if(\App\Support\Permissions::canDo('sales', 'print'))
                        <a id="savedInvoicePrint" class="btn btn-warning" hidden target="_blank">Print A4 Invoice</a>
                        <a id="savedInvoicePosPrint" class="btn btn-secondary" hidden target="_blank">Print POS Receipt</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
</div></div></div>
@include('sales.partials.cheque-modal')
@include('layouts.footer')
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script>
window.salesInvoiceConfig = {
    orderUrl: @json(url('/sales_orders')),
    recallSummaryUrl: @json(route('find_sales_invoice_customer_data_sum')),
    recallDetailsUrl: @json(route('find_sales_details_invoice')),
    printUrl: @json(route('sales.invoice.print')),
    branch: @json(auth()->user()->BC),
    nextInvoice: @json($maxInvoiceNo + 1),
    initialOrder: @json(old('sales_order_id', request('order'))),
    previous: @json(old('inputs') ? old() : null)
};
function escapeInvoiceText(value) { return $('<div>').text(value == null ? '' : String(value)).html(); }
</script>
@include('sales.partials.cheque-script')
<script src="{{ asset('assets/js/sales-invoice.js') }}?v={{ filemtime(public_path('assets/js/sales-invoice.js')) }}"></script>
<script src="{{ asset('assets/js/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
</body></html>
@endsection
