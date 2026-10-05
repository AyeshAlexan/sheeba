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
                <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
                <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
                <title>Stock Transfer Report</title>
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
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Stock Transfer Report</h3>
                                <p class="page-subtitle">Stock moved between stores, by store and date range.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('reports.stockTranferReport') }}" method="GET" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date', date('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0 small">Store</label>
                                        <select class="form-control" id="Store_code" name="storse_id" required>
                                            <option value="">Please Select</option>
                                            @foreach($storeDta as $Data)
                                            <option value="{{ $Data->Store_code }}" {{ $storse_id == $Data->Store_code ? 'selected' : '' }}>{{ $Data->Store_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </form>

                                <div class="modern-table-card">
                                    <div class="table-responsive">
                                        <table class="table" id="receiptTable">
                                            <thead>
                                                <tr>
                                                    <th>Transaction Date</th>
                                                    <th>Transaction No</th>
                                                    <th>Transaction Type</th>
                                                    <th>Item Code</th>
                                                    <th>Qty In</th>
                                                    <th>Qty Out</th>
                                                    <th>Store</th>
                                                    <th>From Store</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($recipts as $receipts)
                                                <tr>
                                                    <td>{{ $receipts->dDate }}</td>
                                                    <td>{{ $receipts->trans_no }}</td>
                                                    <td>{{ $receipts->trans_code }}</td>
                                                    <td>{{ $receipts->item_code }}</td>
                                                    <td>{{ $receipts->qun_in }}</td>
                                                    <td>{{ $receipts->qun_out }}</td>
                                                    <td>{{ $receipts->storse_id }}</td>
                                                    <td>{{ $receipts->From_store }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            @if($recipts->count())
                                            <tfoot>
                                                <tr class="fw-bold">
                                                    <td colspan="4" class="text-end">Total</td>
                                                    <td>{{ number_format($recipts->sum('qun_in'), 2) }}</td>
                                                    <td>{{ number_format($recipts->sum('qun_out'), 2) }}</td>
                                                    <td colspan="2"></td>
                                                </tr>
                                                <tr class="fw-bold">
                                                    <td colspan="4" class="text-end">Total Balance</td>
                                                    <td colspan="2">{{ number_format($recipts->sum('qun_in') - $recipts->sum('qun_out'), 2) }}</td>
                                                    <td colspan="2"></td>
                                                </tr>
                                            </tfoot>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                                <div id="stockTransferCustomPager"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
         </div>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="assets/js/script.js"></script>

<script>
    var stockTransferTable;

    $(document).ready(function () {
        stockTransferTable = $('#receiptTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5'],
            pageLength: 15,
            lengthChange: false,
            language: { emptyTable: 'No stock transfers found for the selected filters.' }
        });

        $('#receiptTable_wrapper').addClass('dt-collapsed');
        DTCustomPager.init(stockTransferTable, '#stockTransferCustomPager');
    });

    // Opens a dedicated, server-rendered print page (logo + branch + title +
    // date range + clean table) in a new tab, carrying forward the currently
    // applied filters.
    function printTablefun() {
        var params = $('form[action="{{ route('reports.stockTranferReport') }}"]').serialize();
        window.open('{{ route('reports.stockTranferReport.print') }}?' + params, '_blank');
    }
</script>

</body>
@endsection

</html>
