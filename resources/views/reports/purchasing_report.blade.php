@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <title>Purchasing Report</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header ph-flex">
                    <div class="ph-left">
                        <div class="ph-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        </div>
                        <div>
                            <h3 class="page-title">Purchasing Report</h3>
                            <p class="page-subtitle">All purchases, filterable by purchase number, supplier, product, product code and date range.</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('purchasing.report') }}" class="row g-2 mb-3 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label mb-0 small">Purchase No</label>
                                <input type="text" name="purchase_no" class="form-control" value="{{ $purchaseNo }}" placeholder="e.g. 12">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-0 small">Supplier</label>
                                <input type="text" name="supplier" class="form-control" list="supplierList" value="{{ $supplier }}" placeholder="Code or name">
                                <datalist id="supplierList">
                                    @foreach($suppliers as $s)
                                        <option value="{{ $s->Name }}">{{ $s->Code }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-0 small">Product</label>
                                <input type="text" name="product" class="form-control" list="productList" value="{{ $product }}" placeholder="Type or pick">
                                <datalist id="productList">
                                    @foreach($items as $i)
                                        <option value="{{ $i->Item_description }}">{{ $i->Item_code }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-0 small">Product Code</label>
                                <input type="text" name="product_code" class="form-control" value="{{ $productCode }}" placeholder="e.g. IT-001">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-0 small">From Date</label>
                                <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-0 small">To Date</label>
                                <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                            </div>
                            <div class="col-md-12 text-end">
                                <a href="{{ route('purchasing.report') }}" class="btn btn-outline-secondary">Clear</a>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Apply Filters</button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Purchase No</th>
                                        <th>Date</th>
                                        <th>Supplier</th>
                                        <th>Qty</th>
                                        <th>Total Cost</th>
                                        <th>Outstanding</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchases as $row)
                                    <tr>
                                        <td>
                                            <a href="javascript:void(0)" class="viewDetail" data-invoice="{{ $row->Invoice_no }}">
                                                {{ $row->Invoice_no }}
                                            </a>
                                        </td>
                                        <td>{{ $row->Invoice_date }}</td>
                                        <td>{{ $row->supplier_name }} <br><small class="text-muted">{{ $row->supplier_code }}</small></td>
                                        <td>{{ number_format((float) $row->total_qty, 2) }}</td>
                                        <td>{{ number_format((float) $row->Net_Amount, 2) }}</td>
                                        <td>
                                            @if($row->outstanding < 0)
                                                <span class="text-danger">Overpaid {{ number_format(abs($row->outstanding), 2) }}</span>
                                            @else
                                                {{ number_format($row->outstanding, 2) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($row->payment_status === 'Paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($row->payment_status === 'Partial')
                                                <span class="badge bg-warning text-dark">Partial</span>
                                            @elseif($row->payment_status === 'Unpaid')
                                                <span class="badge bg-danger">Unpaid</span>
                                            @else
                                                <span class="badge bg-secondary">Cash</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No purchases found for the selected filters.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="table-light fw-bold">
                                        <td colspan="3">Totals (filtered)</td>
                                        <td>{{ number_format($totals['qty'], 2) }}</td>
                                        <td>{{ number_format($totals['net'], 2) }}</td>
                                        <td>{{ number_format($totals['outstanding'], 2) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>

    <!-- Purchase detail modal -->
    <div class="modal fade" id="purchaseDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Purchase Detail <span id="detailInvoiceNo"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="detailHeader" class="mb-3"></div>
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Item Code</th>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Discount</th>
                                <th>Net Value</th>
                            </tr>
                        </thead>
                        <tbody id="detailLines"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            $(document).on('click', '.viewDetail', function () {
                let invoiceNo = $(this).data('invoice');
                $('#detailInvoiceNo').text('#' + invoiceNo);
                $('#detailHeader').html('Loading...');
                $('#detailLines').html('');

                $.ajax({
                    url: "{{ route('purchasing.report.detail') }}",
                    method: 'GET',
                    data: { invoice_no: invoiceNo },
                    success: function (res) {
                        let h = res.header;
                        $('#detailHeader').html(
                            '<strong>Supplier:</strong> ' + (h.supplier_name || '') + ' (' + (h.Customer_NIC || '') + ')' +
                            '<br><strong>Date:</strong> ' + h.Invoice_date +
                            '<br><strong>Gross:</strong> ' + parseFloat(h.Gross_Amount || 0).toFixed(2) +
                            ' &nbsp; <strong>Discount:</strong> ' + parseFloat(h.Discount || 0).toFixed(2) +
                            ' &nbsp; <strong>Net:</strong> ' + parseFloat(h.Net_Amount || 0).toFixed(2)
                        );

                        let rows = '';
                        res.lines.forEach(function (l) {
                            rows += '<tr>' +
                                '<td>' + (l.Item_code || '') + '</td>' +
                                '<td>' + (l.Item_description || '') + '</td>' +
                                '<td>' + (l.QTY || 0) + '</td>' +
                                '<td>' + parseFloat(l.Unit_price || 0).toFixed(2) + '</td>' +
                                '<td>' + parseFloat(l.Discount || 0).toFixed(2) + '</td>' +
                                '<td>' + parseFloat(l.Net_value || 0).toFixed(2) + '</td>' +
                                '</tr>';
                        });
                        $('#detailLines').html(rows || '<tr><td colspan="6" class="text-center text-muted">No line items found.</td></tr>');

                        $('#purchaseDetailModal').modal('show');
                    },
                    error: function () {
                        $('#detailHeader').html('<span class="text-danger">Failed to load details.</span>');
                        $('#purchaseDetailModal').modal('show');
                    }
                });
            });
        });
    </script>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
@endsection

</html>
