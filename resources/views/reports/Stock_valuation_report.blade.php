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
                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
                <title>Stock Valuation Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                tr.qty-positive{ background-color:#EAFBEF !important; color:#0F5132; }
                tr.qty-zero{ background-color:#FEF9E7 !important; color:#7A5B08; }
                tr.qty-negative{ background-color:#FEECEC !important; color:#842029; }

                .legend{ display:flex; gap:10px; margin-bottom:16px; flex-wrap:wrap; }
                .legend-pill{
                    display:inline-flex; align-items:center; gap:7px; font-size:12.5px; font-weight:600;
                    padding:6px 14px; border-radius:999px; color:var(--tr-navy); background:var(--tr-bg);
                    border:1px solid var(--tr-border);
                }
                .legend-dot{ width:9px; height:9px; border-radius:50%; flex:0 0 auto; }

                #myTable thead th{
                    text-transform:uppercase; letter-spacing:.04em; font-size:11.5px !important;
                    color:var(--tr-text-secondary) !important; background:var(--tr-bg) !important;
                }
                #myTable tbody tr:hover{ background:var(--tr-blue-light) !important; }
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
                                <h3 class="page-title">Stock Valuation</h3>
                                <p class="page-subtitle">Stock-on-hand valued at purchase price for every item.</p>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('filter_stock_by_FilterStoctValuation') }}" method="GET" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Item Code</label>
                                        <input type="text" name="item_code" class="form-control" value="{{ $itemCode }}" placeholder="Item code">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </form>

                                <div class="legend">
                                    <span class="legend-pill"><span class="legend-dot" style="background:var(--tr-success);"></span>Positive Stock</span>
                                    <span class="legend-pill"><span class="legend-dot" style="background:var(--tr-warning);"></span>Zero Stock</span>
                                    <span class="legend-pill"><span class="legend-dot" style="background:var(--tr-danger);"></span>Negative Stock</span>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered w-100" id="myTable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>MODEL</th>
                                                <th>Item Name</th>
                                                <th class="text-end">Purchase Price</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-end">Total Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stockDetails as $stock)
                                            @php
                                                $qty       = $stock->total_qun_in - $stock->total_qun_out;
                                                $lineTotal = $qty * $stock->purchasePrice;
                                                $rowClass  = $qty > 0 ? 'qty-positive' : ($qty == 0 ? 'qty-zero' : 'qty-negative');
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td>{{ $stock->Item_code }}</td>
                                                <td>{{ $stock->Item_description }}</td>
                                                <td class="text-end">{{ number_format($stock->purchasePrice, 2) }}</td>
                                                <td class="text-center">{{ $qty }}</td>
                                                <td class="text-end">{{ number_format($lineTotal, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="fw-bold" style="background-color:#f4f6f9;" id="grand-total-row">
                                                <td colspan="2">GRAND TOTALS</td>
                                                <td class="text-end">{{ number_format($sumPurchase, 2) }}</td>
                                                <td class="text-center">{{ $balance }}</td>
                                                <td class="text-end text-primary">{{ number_format($grandTotal, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div id="stockValuationCustomPager"></div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="assets/js/script.js"></script>

<script>
    $(document).ready(function () {

        if (!$.fn.DataTable.isDataTable('#myTable')) {
            var stockValuationTable = $('#myTable').DataTable({
                dom: 'Bfrtip',
                paging: true,
                pageLength: 15,
                lengthChange: false,
                ordering: true,
                info: true,

                buttons: [
                    // ── EXCEL ──────────────────────────────────────────────
                    {
                        extend: 'excel',
                        className: 'btn btn-success btn-sm',
                        text: '<i class="fa-solid fa-file-excel"></i> Excel',
                        filename: 'stock-valuation',
                        exportOptions: {
                            rows: ':visible'
                        },
                        customize: function (xlsx) {
                            // Get the sheet
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];

                            // Build footer cell values from tfoot
                            var footerCells = [];
                            $('#myTable tfoot tr td').each(function () {
                                footerCells.push($(this).text().trim());
                            });

                            // Append a new <row> at the end of <sheetData>
                            var lastRow = $('row', sheet).length;
                            var newRow = '<row r="' + (lastRow + 1) + '">';

                            var colLetters = ['A', 'B', 'C', 'D', 'E'];
                            // tfoot has colspan="2" on first cell, so map correctly:
                            // Cell 0 → A (text "GRAND TOTALS"), B is empty, C, D, E
                            var mappedValues = [
                                footerCells[0], // "GRAND TOTALS" → col A
                                '',             // col B (merged in HTML, empty in Excel)
                                footerCells[1], // sumPurchase → col C
                                footerCells[2], // balance → col D
                                footerCells[3]  // grandTotal → col E
                            ];

                            mappedValues.forEach(function (val, i) {
                                var cellRef = colLetters[i] + (lastRow + 1);
                                // Numeric check — remove commas before testing
                                var numeric = val.replace(/,/g, '');
                                if (!isNaN(parseFloat(numeric)) && numeric !== '') {
                                    newRow += '<c r="' + cellRef + '" s="63"><v>' + parseFloat(numeric) + '</v></c>';
                                } else {
                                    newRow += '<c r="' + cellRef + '" t="inlineStr" s="63"><is><t>' + val + '</t></is></c>';
                                }
                            });

                            newRow += '</row>';
                            $('sheetData', sheet).append(newRow);
                        }
                    },

                    // ── CSV ───────────────────────────────────────────────
                    {
                        extend: 'csv',
                        className: 'btn btn-info btn-sm text-black',
                        text: '<i class="fa-solid fa-file-csv"></i> CSV',
                        filename: 'stock-valuation',
                        bom: true,
                        customize: function (csv) {
                            // Collect tfoot values
                            var footerCells = [];
                            $('#myTable tfoot tr td').each(function () {
                                footerCells.push($(this).text().trim());
                            });
                            // tfoot has colspan="2" on first cell
                            var footerRow = [
                                footerCells[0], // "GRAND TOTALS"
                                '',             // empty for col B (colspan)
                                footerCells[1], // sumPurchase
                                footerCells[2], // balance
                                footerCells[3]  // grandTotal
                            ].join(',');

                            return csv + '\n' + footerRow;
                        }
                    },

                    // ── PDF ───────────────────────────────────────────────
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger btn-sm',
                        text: '<i class="fa-solid fa-file-pdf"></i> PDF',
                        filename: 'stock-valuation',
                        customize: function (doc) {
                            // Collect tfoot cell texts
                            var footerCells = [];
                            $('#myTable tfoot tr td').each(function () {
                                footerCells.push($(this).text().trim());
                            });

                            // Build a footer row matching the 5-column table
                            // tfoot has colspan="2" on first td
                            var footerRow = [
                                { text: footerCells[0], colSpan: 2, bold: true, fillColor: '#e9ecef', alignment: 'left' },
                                {},                    // placeholder for colSpan
                                { text: footerCells[1], bold: true, fillColor: '#e9ecef', alignment: 'right' },
                                { text: footerCells[2], bold: true, fillColor: '#e9ecef', alignment: 'center' },
                                { text: footerCells[3], bold: true, fillColor: '#e9ecef', alignment: 'right', color: '#0d6efd' }
                            ];

                            // Push footer row into the PDF table body
                            doc.content[1].table.body.push(footerRow);
                        }
                    },

                    // ── PRINT ─────────────────────────────────────────────
                    {
                        extend: 'print',
                        className: 'btn btn-dark btn-sm',
                        text: '<i class="fa-solid fa-print"></i> Print',
                        customize: function (win) {

                            // ── Inject print styles ──────────────────────────
                            $(win.document.head).append(`
                                <style>
                                    @page {
                                        size: A4 landscape;
                                        margin: 15mm 10mm;
                                    }
                                    body {
                                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                                        font-size: 12px;
                                        color: #212529;
                                        background: #fff;
                                    }
                                    h1.dt-print-view {
                                        text-align: center;
                                        font-size: 20px;
                                        font-weight: 700;
                                        color: #7169ff;
                                        padding: 10px 0 6px;
                                        margin-bottom: 16px;
                                        border-bottom: 3px solid #7169ff;
                                        letter-spacing: 0.5px;
                                    }
                                    table {
                                        width: 100% !important;
                                        border-collapse: collapse;
                                        margin-top: 8px;
                                    }
                                    table thead tr {
                                        background-color: #009879 !important;
                                        color: #ffffff !important;
                                        -webkit-print-color-adjust: exact;
                                        print-color-adjust: exact;
                                    }
                                    table thead th {
                                        padding: 8px 10px;
                                        font-size: 14px;
                                        font-weight: 600;
                                        border: 1px solid #007a60;
                                        text-align: left;
                                    }
                                    table tbody tr {
                                        border-bottom: 1px solid #dee2e6;
                                    }
                                    table tbody tr:nth-child(even) {
                                        background-color: #f9f9f9;
                                        -webkit-print-color-adjust: exact;
                                        print-color-adjust: exact;
                                    }
                                    table tbody td {
                                        padding: 6px 10px;
                                        font-size: 16px;
                                        border: 1px solid #dee2e6;
                                        vertical-align: middle;
                                    }
                                    /* Row color coding */
                                    tr.qty-positive {
                                        background-color: #d4edda !important;
                                        color: #155724 !important;
                                        -webkit-print-color-adjust: exact;
                                        print-color-adjust: exact;
                                    }
                                    tr.qty-zero {
                                        background-color: #fff3cd !important;
                                        color: #856404 !important;
                                        -webkit-print-color-adjust: exact;
                                        print-color-adjust: exact;
                                    }
                                    tr.qty-negative {
                                        background-color: #f8d7da !important;
                                        color: #721c24 !important;
                                        -webkit-print-color-adjust: exact;
                                        print-color-adjust: exact;
                                    }
                                    /* Footer / grand totals row */
                                    tfoot tr {
                                        background-color: #e9ecef !important;
                                        font-weight: 700 !important;
                                        -webkit-print-color-adjust: exact;
                                        print-color-adjust: exact;
                                    }
                                    tfoot td {
                                        padding: 8px 10px;
                                        font-size: 12px;
                                        border: 1px solid #adb5bd;
                                        color: #212529;
                                    }
                                    /* Align numbers */
                                    td:nth-child(3),
                                    td:nth-child(5),
                                    th:nth-child(3),
                                    th:nth-child(5) {
                                        text-align: right !important;
                                    }
                                    td:nth-child(4),
                                    th:nth-child(4) {
                                        text-align: center !important;
                                    }
                                </style>
                            `);

                            // ── Clone & append tfoot ─────────────────────────
                            var $tfoot = $('#myTable tfoot').clone();
                            $(win.document.body).find('table').append($tfoot);
                        }
                    }
                ]
            });
        }

        $('#myTable_wrapper').addClass('dt-collapsed');
        DTCustomPager.init($('#myTable').DataTable(), '#stockValuationCustomPager');

        // Set default To Date if empty
        var today = new Date().toISOString().split('T')[0];
        if (!$('#to_date').val()) {
            $('#to_date').val(today);
        }

    });
</script>

</body>
@endsection

</html>
