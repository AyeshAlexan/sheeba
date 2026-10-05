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
                <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
                <title>Stock In Hand</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                .row-positive{ background-color:#EAFBEF !important; }
                .row-zero{ background-color:#FEF9E7 !important; }
                .row-negative{ background-color:#FEECEC !important; }

                .legend{ display:flex; gap:10px; margin-bottom:16px; flex-wrap:wrap; }
                .legend-pill{
                    display:inline-flex; align-items:center; gap:7px; font-size:12.5px; font-weight:600;
                    padding:6px 14px; border-radius:999px; color:var(--tr-navy); background:var(--tr-bg);
                    border:1px solid var(--tr-border);
                }
                .legend-dot{ width:9px; height:9px; border-radius:50%; flex:0 0 auto; }
            </style>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" y1="22" x2="12" y2="12"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Stock In Hand</h3>
                                <p class="page-subtitle">Current stock balance for every item, colour-coded by whether it's positive, zero, or negative.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                </div>
                                @endif

                                <form action="{{ route('filter_stock_by_date') }}" method="GET" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-2">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Item Code</label>
                                        <input type="text" name="item_code" class="form-control" value="{{ $itemCode }}" placeholder="Item code">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Category</label>
                                        <input type="text" name="category" class="form-control" value="{{ $category }}" placeholder="Category">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </form>

                                <div class="legend">
                                    <span class="legend-pill"><span class="legend-dot" style="background:var(--tr-success);"></span>Positive Stock</span>
                                    <span class="legend-pill"><span class="legend-dot" style="background:var(--tr-warning);"></span>Zero Stock</span>
                                    <span class="legend-pill"><span class="legend-dot" style="background:var(--tr-danger);"></span>Negative Stock</span>
                                </div>

                                <div class="modern-table-card">
                                    <div class="table-responsive">
                                        <table class="table" id="t_item_movements">
                                            @if($fromDate && $toDate)
                                            <caption>Stock In Hand &nbsp;|&nbsp; {{ $fromDate }} &nbsp;→&nbsp; {{ $toDate }}</caption>
                                            @endif
                                            <thead>
                                                <tr>
                                                    <th>Item Code</th>
                                                    <th>Item Name</th>
                                                    <th class="text-center">Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($stockDetails as $item)
                                                @php
                                                    $qty = $item->total_qun_in - $item->total_qun_out - $item->total_free_issues;
                                                    $rowClass = $qty > 0 ? 'row-positive' : ($qty < 0 ? 'row-negative' : 'row-zero');
                                                @endphp
                                                <tr class="{{ $rowClass }}">
                                                    <td>{{ $item->Item_code }}</td>
                                                    <td>{{ $item->Item_description }}</td>
                                                    <td class="text-center">{{ $qty }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="fw-bold">
                                                    <td colspan="2" class="text-end">Total Balance</td>
                                                    <td class="text-center">{{ $balance }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="stockInHandCustomPager"></div>
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
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="assets/js/script.js"></script>

<script>
var stockInHandTable;

jQuery(document).ready(function ($) {
    stockInHandTable = $('#t_item_movements').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        pageLength: 15,
        lengthChange: false,
    });

    $('#t_item_movements_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(stockInHandTable, '#stockInHandCustomPager');

    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        toDateInput.value = new Date().toISOString().slice(0, 10);
    }
});

// Opens a dedicated, server-rendered print page (logo + branch + title +
// date range + clean table) in a new tab, carrying forward the currently
// applied filters.
function printTablefun() {
    var params = $('form[action="{{ route('filter_stock_by_date') }}"]').serialize();
    window.open('{{ route('stock_report.print') }}?' + params, '_blank');
}
</script>

</body>
@endsection

</html>
