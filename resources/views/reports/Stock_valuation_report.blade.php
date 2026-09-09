<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Valuation Report</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .report-header {
            background-color: #7169ff;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }
        .card {
            border: none;
            border-radius: 10px;
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .dt-buttons {
            margin-bottom: 15px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .dt-buttons a.dt-button,
        .dt-buttons button.dt-button {
            display: inline-flex !important;
            align-items: center;
            gap: 5px;
        }

        /* Row color coding */
        tr.qty-positive {
            background-color: #d4edda !important;
            color: #155724;
        }
        tr.qty-zero {
            background-color: #fff3cd !important;
            color: #856404;
        }
        tr.qty-negative {
            background-color: #f8d7da !important;
            color: #721c24;
        }

        /* Legend badges */
        .legend-box {
            display: inline-block;
            width: 16px;
            height: 16px;
            border-radius: 3px;
            margin-right: 5px;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container-fluid p-4">

    <div class="report-header text-center">
        <h2><i class="fa-solid fa-boxes-stacked me-2"></i><b>Stock Valuation Report</b></h2>
    </div>

    <div class="card p-4 shadow-sm mb-4">
        <form action="{{ route('filter_stock_by_FilterStoctValuation') }}" method="GET" class="row g-3 align-items-center justify-content-center">
            <div class="col-md-3">
                <label class="form-label fw-bold">From Date:</label>
                <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">To Date:</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
            </div>
            <div class="col-md-auto mt-auto">
                <button type="submit" class="btn btn-primary px-4 mt-2">
                    <i class="fa fa-filter"></i> Filter Report
                </button>
            </div>
        </form>
    </div>

    {{-- Color legend --}}
    <div class="mb-3 d-flex gap-4 align-items-center px-1">
        <span><span class="legend-box" style="background:#d4edda; border:1px solid #c3e6cb;"></span> Positive Stock</span>
        <span><span class="legend-box" style="background:#fff3cd; border:1px solid #ffeeba;"></span> Zero Stock</span>
        <span><span class="legend-box" style="background:#f8d7da; border:1px solid #f5c6cb;"></span> Negative Stock</span>
    </div>

    <div class="table-container">
        <table class="table table-hover styled-table w-100" id="myTable">
            <thead>
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

                        if ($qty > 0) {
                            $rowClass = 'qty-positive';
                        } elseif ($qty == 0) {
                            $rowClass = 'qty-zero';
                        } else {
                            $rowClass = 'qty-negative';
                        }
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
                <tr class="table-secondary fw-bold" id="grand-total-row">
                    <td colspan="2">GRAND TOTALS</td>
                    <td class="text-end">{{ number_format($sumPurchase, 2) }}</td>
                    <td class="text-center">{{ $balance }}</td>
                    <td class="text-end text-primary">{{ number_format($grandTotal, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function () {

        if (!$.fn.DataTable.isDataTable('#myTable')) {
            $('#myTable').DataTable({
                dom: 'Bfrtip',
                paging: true,
                pageLength: 5000,
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

        // Set default To Date if empty
        var today = new Date().toISOString().split('T')[0];
        if (!$('#to_date').val()) {
            $('#to_date').val(today);
        }

    });
</script>

</body>
</html>