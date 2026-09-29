<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f2f5;
            padding: 0;
            margin: 0;
        }

        /* ── Header Banner ── */
        .page-header {
            background: linear-gradient(135deg, #6c3fc5 0%, #4a2fa0 50%, #3b24a8 100%);
            color: #fff;
            padding: 22px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .page-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .header-icon {
            background: rgba(255,255,255,0.18);
            border-radius: 10px;
            width: 46px;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .header-title {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            line-height: 1.2;
        }

        .header-subtitle {
            font-size: 13px;
            opacity: 0.82;
            margin: 0;
        }

        .header-meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .meta-badge {
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ── Main body padding ── */
        .main-body { padding: 22px 26px; }

        /* ── Stat Cards ── */
        .stat-row {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            padding: 14px 20px;
            flex: 1;
            min-width: 140px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }

        .stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #888;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .stat-label i { font-size: 12px; }

        .stat-value {
            font-size: 22px;
            font-weight: 700;
            line-height: 1;
        }

        .stat-value.blue   { color: #2563eb; }
        .stat-value.green  { color: #16a34a; }
        .stat-value.amber  { color: #d97706; }
        .stat-value.red    { color: #dc2626; }
        .stat-value.purple { color: #7c3aed; }
        .stat-value.teal   { color: #0d9488; }

        /* ── Filter card ── */
        .filter-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .filter-card-title {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .filter-row {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        .filter-group { display: flex; align-items: center; gap: 8px; }

        .filter-group label {
            font-size: 13px;
            color: #374151;
            font-weight: 500;
            white-space: nowrap;
        }

        .filter-group input[type="date"] {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 7px 11px;
            font-size: 13px;
            color: #111;
            outline: none;
            transition: border-color .2s;
        }

        .filter-group input[type="date"]:focus { border-color: #7c3aed; }

        .btn-search {
            background: #4f46e5;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: background .2s;
        }

        .btn-search:hover { background: #4338ca; }

        .btn-print-top {
            background: #10b981;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: background .2s;
        }

        .btn-print-top:hover { background: #059669; }

        .btn-back {
            background: #374151;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            text-decoration: none;
            transition: background .2s;
        }

        .btn-back:hover { background: #1f2937; color: #fff; }

        /* ── Table card ── */
        .table-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 18px 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }

        .table-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .table-card-title {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .table-card-title i { color: #7c3aed; }

        .date-range-badge {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 12px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* DataTable overrides */
        table.dataTable thead th {
            background: #4f46e5 !important;
            color: #fff !important;
            font-size: 12px !important;
            padding: 10px 12px !important;
            border: none !important;
            white-space: nowrap;
            font-weight: 600 !important;
        }

        table.dataTable tbody td {
            font-size: 13px !important;
            padding: 9px 12px !important;
            border-bottom: 1px solid #f0f0f0 !important;
            vertical-align: middle;
            color: #374151;
        }

        table.dataTable tbody tr:hover td {
            background: #f5f3ff !important;
        }

        table.dataTable tfoot td {
            background: #1e1b4b !important;
            color: #fff !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            padding: 10px 12px !important;
            border: none !important;
        }

        /* Row action buttons */
        .btn-rp {
            font-size: 11px;
            padding: 4px 9px;
            border-radius: 5px;
            border: 1px solid #d1d5db;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #374151;
            background: #f9fafb;
            transition: all .15s;
        }

        .btn-rp:hover { background: #4f46e5; color: #fff; border-color: #4f46e5; }

        .btn-rp-pos {
            background: #eff6ff;
            color: #2563eb;
            border-color: #bfdbfe;
            margin-left: 4px;
        }

        .btn-rp-pos:hover { background: #2563eb; color: #fff; border-color: #2563eb; }

        /* DataTable built-in buttons */
        .dt-button {
            background: #f3f4f6 !important;
            border: 1px solid #d1d5db !important;
            color: #374151 !important;
            border-radius: 5px !important;
            font-size: 12px !important;
            padding: 5px 12px !important;
        }

        .dt-button:hover { background: #e5e7eb !important; }

        @media print {
            .page-header .header-meta,
            .filter-card,
            .stat-row,
            .dt-buttons,
            .dataTables_filter,
            .dataTables_info,
            .btn-rp, .btn-rp-pos,
            .table-card-header { display: none !important; }
            body { background: #fff; }
            .main-body { padding: 0; }
            .table-card { border: none; padding: 0; box-shadow: none; }
        }
    </style>
</head>
<body>

    {{-- ── Header ── --}}
    <div class="page-header">
        <div class="page-header-left">
            <div class="header-icon"><i class="fa-solid fa-chart-line"></i></div>
            <div>
                <p class="header-title">Sales Report</p>
                <p class="header-subtitle">Track and manage invoice sales transactions</p>
            </div>
        </div>
        <div class="header-meta">
            <div class="meta-badge">
                <i class="fa-solid fa-calendar-day"></i>
                {{ \Carbon\Carbon::now()->format('d M Y') }}
            </div>
            <div class="meta-badge">
                <i class="fa-solid fa-hashtag"></i>
                {{ $invoice->count() }} Records
            </div>
        </div>
    </div>

    <div class="main-body">

        {{-- ── Stat Cards ── --}}
        <div class="stat-row">
            <div class="stat-card">
                <div class="stat-label"><i class="fa-solid fa-file-invoice-dollar"></i> Gross Amount</div>
                <div class="stat-value blue">{{ $totalGrossAmount }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="fa-solid fa-tag"></i> Discount</div>
                <div class="stat-value amber">{{ $totalDiscount }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="fa-solid fa-circle-check"></i> Net Amount</div>
                <div class="stat-value green">{{ $totalNetAmount }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="fa-solid fa-money-bill-wave"></i> Cash Pay</div>
                <div class="stat-value purple">{{ $totalCashPay }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="fa-solid fa-clock"></i> Credit</div>
                <div class="stat-value red">{{ $totalCredite }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label"><i class="fa-solid fa-money-check"></i> Cheque</div>
                <div class="stat-value teal">{{ $totalCheque }}</div>
            </div>
        </div>

        {{-- ── Filter Bar ── --}}
        <div class="filter-card">
            <div class="filter-card-title">
                <i class="fa-solid fa-sliders"></i> Filter Records
            </div>
            <form action="" method="get" style="margin:0;">
                <div class="filter-row">
                    <div class="filter-group">
                        <label>From Date</label>
                        <input type="date" name="from_date" id="from_date" value="{{ $fromDate }}">
                    </div>
                    <div class="filter-group">
                        <label>To Date</label>
                        <input type="date" name="to_date" id="to_date" value="{{ $toDate }}">
                    </div>
                    <div class="filter-group">
                        <label>Customer NIC</label>
                        <input type="text" name="customer" id="customer" value="{{ $customer }}" placeholder="Customer NIC">
                    </div>
                    <div class="filter-group">
                        <label>Salesman</label>
                        <input type="text" name="salesman" id="salesman" value="{{ $salesman }}" placeholder="Salesman">
                    </div>
                    <button type="submit" class="btn-search">
                        <i class="fa-solid fa-magnifying-glass"></i> Search
                    </button>
                    <button type="button" class="btn-print-top" onclick="printTableFun()">
                        <i class="fa-solid fa-print"></i> Print
                    </button>
                    <a href="{{ route('home') }}" class="btn-back">
                        <i class="fa-solid fa-house"></i> Back
                    </a>
                </div>
            </form>
        </div>

        {{-- ── Table Card ── --}}
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">
                    <i class="fa-solid fa-table-list"></i> Invoice Listing
                </div>
                @if($fromDate && $toDate)
                <div class="date-range-badge">
                    <i class="fa-solid fa-calendar-range"></i>
                    {{ $fromDate }} &mdash; {{ $toDate }}
                    &nbsp;&bull;&nbsp; {{ $invoice->count() }} record(s)
                </div>
                @endif
            </div>

            <table id="t_invoice_sums" class="display" style="width:100%;">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Invoice No</th>
                        <th>Date</th>
                        <th>Customer NIC</th>
                        <th>Customer Name</th>
                        <th>Route</th>
                        <th>Salesman</th>
                        <th>Gross Amt</th>
                        <th>Discount</th>
                        <th>Net Amt</th>
                        <th>Cash Pay</th>
                        <th>Credit</th>
                        <th>Cheque</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice as $inv)
                    <tr>
                        <td style="white-space:nowrap;">
                            <a href="{{ route('print.invoice', [
                                'invoice_no'  => $inv->Invoice_no,
                                'branch_code' => $inv->BC,
                                'print_type'  => 'normal'
                            ]) }}" target="_blank" class="btn-rp">
                                <i class="fa-solid fa-print"></i> Print
                            </a>
                            <a href="{{ route('print.invoice', [
                                'invoice_no'  => $inv->Invoice_no,
                                'branch_code' => $inv->BC,
                                'print_type'  => 'pos'
                            ]) }}" target="_blank" class="btn-rp btn-rp-pos">
                                <i class="fa-solid fa-receipt"></i> POS
                            </a>
                        </td>
                        <td>{{ $inv->Invoice_no }}</td>
                        <td>{{ $inv->Invoice_date }}</td>
                        <td>{{ $inv->Customer_NIC }}</td>
                        <td>{{ $inv->Customer_Name }}</td>
                        <td>{{ $inv->Route }}</td>
                        <td>{{ $inv->Salesmen }}</td>
                        <td>{{ number_format($inv->Gross_Amount, 2) }}</td>
                        <td>{{ number_format($inv->Discount, 2) }}</td>
                        <td>{{ number_format($inv->Net_Amount, 2) }}</td>
                        <td>{{ number_format($inv->Cash_Pay, 2) }}</td>
                        <td>{{ number_format($inv->Credite, 2) }}</td>
                        <td>{{ number_format($inv->Cheque, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7">Grand Total</td>
                        <td>{{ $totalGrossAmount }}</td>
                        <td>{{ $totalDiscount }}</td>
                        <td>{{ $totalNetAmount }}</td>
                        <td>{{ $totalCashPay }}</td>
                        <td>{{ $totalCredite }}</td>
                        <td>{{ $totalCheque }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>{{-- /table-card --}}

    </div>{{-- /main-body --}}

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#t_invoice_sums').DataTable({
                dom: 'Bfrtip',
                paging: false,
                buttons: ['copy', 'excel', 'csv', 'pdf'],
                columnDefs: [{ orderable: false, targets: 0 }]
            });
        });
    </script>

    <script>
        function printTableFun() {
            var table = document.getElementById('t_invoice_sums');
            var win   = window.open('', '', 'width=1200,height=750');
            win.document.write(`
                <html><head><title>Sales Report</title>
                <style>
                    body { font-family: 'Segoe UI', Arial, sans-serif; padding: 24px; font-size: 12px; color: #222; }
                    h3   { margin-bottom: 4px; font-size: 18px; color: #4f46e5; }
                    p    { color: #888; margin-bottom: 16px; font-size: 11px; }
                    table { width: 100%; border-collapse: collapse; }
                    th { background: #4f46e5; color: #fff; padding: 8px 10px; text-align: left; font-size: 11px; }
                    td { padding: 7px 10px; border-bottom: 1px solid #eee; }
                    tr:nth-child(even) td { background: #f9f9f9; }
                    tfoot td { background: #1e1b4b; color: #fff; font-weight: bold; }
                    a { display: none; }
                </style>
                </head><body>
                <h3>Sales Report</h3>
                <p>Printed: ${new Date().toLocaleDateString()}</p>
                ${table.outerHTML}
                </body></html>
            `);
            win.document.close();
            setTimeout(() => { win.print(); win.close(); }, 400);
        }
    </script>

    <script>
        var today = new Date().toISOString().slice(0, 10);
        if (!document.getElementById('to_date').value)   document.getElementById('to_date').value   = today;
        if (!document.getElementById('from_date').value) document.getElementById('from_date').value = today;
    </script>

</body>
</html>