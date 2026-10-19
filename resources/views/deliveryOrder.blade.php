@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Delivery Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        .do-table-card { background:#f5f9ff; border:1px solid #dfeaf6; border-radius:16px; margin-top:18px; padding:14px; box-shadow:0 10px 25px rgba(42,92,171,.04); }
        .do-table-card > .card-body { padding:0; }
        .do-item-details-table { width:100%; min-width:900px; table-layout:fixed; border:1px solid #d9e3ee; border-radius:12px; overflow:hidden; background:#fff; border-collapse:separate; border-spacing:0; }
        .do-item-details-table thead th { background:#fff; color:#2b3e5b; font-size:12px; font-weight:800; padding:12px 10px; border-bottom:1px solid #d9e3ee; text-align:center; }
        .do-item-details-table tbody td { padding:10px 8px; border-color:#edf1f5; vertical-align:middle; }
        .do-item-details-table .form-control { min-height:42px; border:1px solid #d7e3f1; border-radius:10px; }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header ph-flex">
                    <div class="ph-left">
                        <div class="ph-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h15v13H3z"/><path d="M16 8h4l2 4v4h-6"/><circle cx="7.5" cy="19.5" r="1.5"/><circle cx="17.5" cy="19.5" r="1.5"/></svg>
                        </div>
                        <div>
                            <h3 class="page-title">Create Delivery Order</h3>
                            <p class="page-subtitle">Record goods dispatched against an invoice</p>
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
                                @if (session('done'))
                                <div class="alert alert-success text-center" role="alert">
                                    {{ session('done') }} &#10004;
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

                                <form action="{{ route('add_delivery_order') }}" method="post" id="do_form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3 form-group">
                                            <label>DO No.</label>
                                            <input type="text" id="do_no" name="do_no" class="form-control" value="{{ $maxDoNo + 1 }}" readonly>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label>DO Date</label>
                                            <input type="date" id="do_date" name="do_date" class="form-control" value="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label>Invoice No.</label>
                                            <div class="d-flex" style="gap:8px;">
                                                <input type="text" id="invoice_no_lookup" name="invoice_no" class="form-control" placeholder="Enter Invoice No, then click Find">
                                                <button type="button" id="find_invoice_btn" class="btn btn-outline-primary">Find</button>
                                            </div>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label>Invoice Date</label>
                                            <input type="text" id="invoice_date" name="invoice_date" class="form-control" readonly>
                                        </div>
                                    </div>

                                    <div class="do-table-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3 form-group">
                                                    <label>Customer NIC</label>
                                                    <input type="text" id="customer_nic" name="customer_nic" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label>Customer Name</label>
                                                    <input type="text" id="customer_name" name="customer_name" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label>Customer Phone</label>
                                                    <input type="text" id="customer_phone" name="customer_phone" class="form-control" readonly>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label>Net Amount</label>
                                                    <input type="text" id="net_amount" name="net_amount" class="form-control" readonly>
                                                    <input type="hidden" id="gross_amount" name="gross_amount">
                                                    <input type="hidden" id="discount" name="discount">
                                                </div>
                                            </div>

                                            <table class="do-item-details-table">
                                                <thead>
                                                    <tr>
                                                        <th>Item Code</th>
                                                        <th>Description</th>
                                                        <th>QTY</th>
                                                        <th>Unit Price</th>
                                                        <th>Discount</th>
                                                        <th>Net Value</th>
                                                        <th>Batch</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="dynamicAdded">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row mt-3 justify-content-center">
                                        <div class="col-md-4 form-group">
                                            <label>Delivered By</label>
                                            <input type="text" id="delivered_by" name="delivered_by" class="form-control" placeholder="Driver / dispatcher name">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Received By</label>
                                            <input type="text" id="received_by" name="received_by" class="form-control" placeholder="Customer / signee name">
                                        </div>
                                    </div>

                                    <div class="text-end mt-3">
                                        <button type="submit" class="btn btn-primary">Save Delivery Order</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>

<script>
    $(document).ready(function () {
        // Lookup only fires when the user deliberately asks for it (Find button
        // or Enter), never mid-typing on blur — avoids firing on a half-typed
        // invoice number and showing a false "not found" that then gets
        // overwritten once the full number is entered.
        var lookupToken = 0;

        function clearInvoiceFields() {
            $('#invoice_date, #customer_nic, #customer_name, #customer_phone, #gross_amount, #discount, #net_amount').val('');
        }

        function findInvoiceForDo() {
            var invoiceNo = $('#invoice_no_lookup').val().trim();
            if (!invoiceNo) {
                return;
            }

            var myToken = ++lookupToken;
            $('#dynamicAdded').html('<tr><td colspan="7" class="text-center text-muted">Searching...</td></tr>');
            clearInvoiceFields();

            $.ajax({
                url: "{{ route('find_invoice_for_do_items') }}",
                method: 'GET',
                data: { search_invoice_no: invoiceNo },
                success: function (res) {
                    if (myToken !== lookupToken) { return; } // a newer lookup superseded this one
                    if (res && typeof res === 'object' && res.status === 'not_found') {
                        $('#dynamicAdded').html('<tr><td colspan="7" class="text-center text-danger">Invoice Not Found ...!!</td></tr>');
                    } else {
                        $('#dynamicAdded').html(res);
                        $('.do-batch-select').each(function () {
                            loadItemBatches($(this));
                        });
                    }
                },
                error: function () {
                    if (myToken !== lookupToken) { return; }
                    $('#dynamicAdded').html('<tr><td colspan="7" class="text-center text-danger">Error looking up invoice.</td></tr>');
                }
            });

            $.ajax({
                url: "{{ route('find_invoice_for_do_customer') }}",
                method: 'GET',
                data: { search_invoice_no: invoiceNo },
                success: function (response) {
                    if (myToken !== lookupToken) { return; }
                    if (response.status == 'success') {
                        var record = response.data[0];
                        $('#invoice_date').val(record.Invoice_date);
                        $('#customer_nic').val(record.Customer_NIC);
                        $('#customer_name').val(record.Customer_Name);
                        $('#customer_phone').val(record.Customer_Phone);
                        $('#gross_amount').val(record.Gross_Amount);
                        $('#discount').val(record.Discount);
                        $('#net_amount').val(record.Net_Amount);
                    } else {
                        alert('Invoice not found for this branch.');
                    }
                }
            });
        }

        $('#find_invoice_btn').on('click', findInvoiceForDo);
        $('#invoice_no_lookup').on('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                findInvoiceForDo();
            }
        });

        // Load sellable batches (remaining qty > 0) for a row's item into its select.
        function loadItemBatches(selectEl) {
            var itemCode = selectEl.data('item-code');
            selectEl.html('<option value="">(no batch)</option>');

            $.get("{{ url('get_item_batches_ajax') }}", { item_code: itemCode, only_available: 1 }, function (res) {
                if (res && res.data) {
                    $.each(res.data, function (i, b) {
                        selectEl.append($('<option>', {
                            value: b.batch_no,
                            text: b.batch_no + ' (Qty ' + b.qty_remaining + ')'
                        }));
                    });
                }
            });
        }
    });
</script>

@endsection
