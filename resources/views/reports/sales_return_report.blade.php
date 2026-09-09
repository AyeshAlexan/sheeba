<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Return Report</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">

    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f0f2f5;
            padding: 0; margin: 0;
        }

        /* ── Header ── */
        .page-header {
            background: linear-gradient(135deg, #1b8fac 0%, #100ce4 50%, #09adb3 100%);
            color: #fff; padding: 22px 30px;
            display: flex; align-items: center;
            justify-content: space-between; flex-wrap: wrap; gap: 12px;
        }
        .page-header-left { display: flex; align-items: center; gap: 14px; }
        .header-icon {
            background: rgba(255,255,255,0.18); border-radius: 10px;
            width: 46px; height: 46px; display: flex;
            align-items: center; justify-content: center; font-size: 20px;
        }
        .header-title    { font-size: 22px; font-weight: 700; margin: 0; line-height: 1.2; }
        .header-subtitle { font-size: 13px; opacity: .82; margin: 0; }
        .header-meta     { display: flex; gap: 10px; flex-wrap: wrap; }
        .meta-badge {
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px; padding: 5px 14px; font-size: 13px;
            display: flex; align-items: center; gap: 6px;
        }

        /* ── Layout ── */
        .main-body { padding: 22px 26px; }

        /* ── Stat Cards ── */
        .stat-row  { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 20px; }
        .stat-card {
            background: #fff; border-radius: 10px; border: 1px solid #e5e7eb;
            padding: 14px 20px; flex: 1; min-width: 140px;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
        }
        .stat-label {
            font-size: 11px; text-transform: uppercase; letter-spacing: .06em;
            color: #888; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;
        }
        .stat-label i { font-size: 12px; }
        .stat-value   { font-size: 22px; font-weight: 700; line-height: 1; }
        .stat-value.blue   { color: #2563eb; }
        .stat-value.green  { color: #16a34a; }
        .stat-value.amber  { color: #d97706; }
        .stat-value.red    { color: #dc2626; }
        .stat-value.purple { color: #7c3aed; }
        .stat-value.teal   { color: #0d9488; }

        /* ── Filter Card ── */
        .filter-card {
            background: #fff; border: 1px solid #e5e7eb; border-radius: 10px;
            padding: 16px 20px; margin-bottom: 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }
        .filter-card-title {
            font-size: 12px; font-weight: 600; color: #6b7280;
            text-transform: uppercase; letter-spacing: .06em;
            margin-bottom: 12px; display: flex; align-items: center; gap: 6px;
        }
        .filter-row   { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
        .filter-group { display: flex; align-items: center; gap: 8px; }
        .filter-group label {
            font-size: 13px; color: #374151; font-weight: 500; white-space: nowrap;
        }
        .filter-group input[type="date"] {
            border: 1px solid #d1d5db; border-radius: 6px;
            padding: 7px 11px; font-size: 13px; color: #111;
            outline: none; transition: border-color .2s;
        }
        .filter-group input[type="date"]:focus { border-color: #7c3aed; }

        .btn-search {
            background: #4f46e5; color: #fff; border: none; border-radius: 6px;
            padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; gap: 7px; transition: background .2s;
        }
        .btn-search:hover { background: #4338ca; }

        .btn-print-top {
            background: #10b981; color: #fff; border: none; border-radius: 6px;
            padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; gap: 7px; transition: background .2s;
        }
        .btn-print-top:hover { background: #059669; }

        .btn-back {
            background: #374151; color: #fff; border: none; border-radius: 6px;
            padding: 8px 18px; font-size: 13px; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; gap: 7px;
            text-decoration: none; transition: background .2s;
        }
        .btn-back:hover { background: #1f2937; color: #fff; }

        /* ── Table Card ── */
        .table-card {
            background: #fff; border: 1px solid #e5e7eb; border-radius: 10px;
            padding: 18px 20px; box-shadow: 0 1px 4px rgba(0,0,0,.05);
        }
        .table-card-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 14px; flex-wrap: wrap; gap: 8px;
        }
        .table-card-title {
            font-size: 15px; font-weight: 700; color: #111827;
            display: flex; align-items: center; gap: 8px;
        }
        .table-card-title i { color: #7c3aed; }
        .date-range-badge {
            background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 20px;
            padding: 4px 14px; font-size: 12px; color: #6b7280;
            display: flex; align-items: center; gap: 6px;
        }

        /* ── DataTable overrides ── */
        table.dataTable thead th {
            background: #4f46e5 !important; color: #fff !important;
            font-size: 12px !important; padding: 10px 12px !important;
            border: none !important; white-space: nowrap; font-weight: 600 !important;
        }
        table.dataTable tbody td {
            font-size: 13px !important; padding: 9px 12px !important;
            border-bottom: 1px solid #f0f0f0 !important;
            vertical-align: middle; color: #374151;
        }
        table.dataTable tbody tr:hover td { background: #f5f3ff !important; }
        table.dataTable tfoot td {
            background: #1e1b4b !important; color: #fff !important;
            font-size: 13px !important; font-weight: 700 !important;
            padding: 10px 12px !important; border: none !important;
        }
        .dt-button {
            background: #f3f4f6 !important; border: 1px solid #d1d5db !important;
            color: #374151 !important; border-radius: 5px !important;
            font-size: 12px !important; padding: 5px 12px !important;
        }
        .dt-button:hover { background: #e5e7eb !important; }

        /* ── Clickable Invoice No ── */
        .inv-link {
            color: #4f46e5; font-weight: 600; cursor: pointer;
            text-decoration: underline dotted; transition: color .15s;
            white-space: nowrap;
        }
        .inv-link:hover { color: #7c3aed; }

        /* ══════════════════════════
           DETAIL MODAL
        ══════════════════════════ */
        .detail-modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.50); z-index: 9998;
            align-items: flex-start; justify-content: center;
            padding: 50px 16px 20px;
        }
        .detail-modal-overlay.active { display: flex; }

        .detail-modal {
            background: #fff; border-radius: 14px;
            width: 100%; max-width: 1000px; max-height: 85vh;
            display: flex; flex-direction: column;
            box-shadow: 0 24px 70px rgba(0,0,0,0.30); overflow: hidden;
        }

        /* Modal — header */
        .detail-modal-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #fff; padding: 16px 22px;
            display: flex; align-items: center; justify-content: space-between;
            flex-shrink: 0;
        }
        .dh-left { display: flex; align-items: center; gap: 12px; }
        .dh-icon {
            background: rgba(255,255,255,0.20); border-radius: 8px;
            width: 40px; height: 40px; display: flex;
            align-items: center; justify-content: center; font-size: 17px;
            flex-shrink: 0;
        }
        .dh-title { font-size: 16px; font-weight: 700; margin: 0; }
        .dh-sub   { font-size: 12px; opacity: .80; margin: 0; }
        .btn-close-modal {
            background: rgba(255,255,255,0.20); border: none; color: #fff;
            width: 34px; height: 34px; border-radius: 8px; cursor: pointer;
            font-size: 16px; display: flex; align-items: center; justify-content: center;
            transition: background .2s; flex-shrink: 0;
        }
        .btn-close-modal:hover { background: rgba(255,255,255,0.38); }

        /* Modal — summary strip */
        .detail-summary-strip {
            background: #f8f7ff; border-bottom: 1px solid #e5e7eb;
            padding: 12px 22px; display: flex; gap: 24px;
            flex-wrap: wrap; flex-shrink: 0;
        }
        .ds-item  { display: flex; flex-direction: column; }
        .ds-label { font-size: 10px; text-transform: uppercase; letter-spacing: .06em; color: #888; }
        .ds-value { font-size: 14px; font-weight: 700; color: #1e1b4b; margin-top: 1px; }

        /* Modal — body */
        .detail-modal-body { overflow-y: auto; padding: 18px 22px 22px; flex: 1; }

        /* Modal — detail table */
        .detail-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .detail-table thead th {
            background: #1e1b4b; color: #fff; padding: 9px 12px;
            text-align: left; font-size: 11px; font-weight: 600;
            text-transform: uppercase; letter-spacing: .04em;
            white-space: nowrap;
        }
        .detail-table thead th.r { text-align: right; }
        .detail-table tbody td {
            padding: 9px 12px; border-bottom: 1px solid #f0f0f0; color: #374151;
            vertical-align: middle;
        }
        .detail-table tbody td.r { text-align: right; }
        .detail-table tbody tr:last-child td { border-bottom: none; }
        .detail-table tbody tr:hover td { background: #f5f3ff; }
        .detail-table tfoot td {
            background: #4f46e5; color: #fff;
            font-weight: 700; padding: 9px 12px;
        }
        .detail-table tfoot td.r { text-align: right; }

        .net-val { font-weight: 700; color: #16a34a; }

        /* Modal — loading / empty / error */
        .modal-state {
            text-align: center; padding: 50px 20px; color: #9ca3af;
        }
        .modal-state i {
            font-size: 38px; margin-bottom: 12px; display: block;
        }
        .modal-state p { font-size: 14px; margin: 0; }
        .spin { color: #7c3aed; animation: spin .75s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Print ── */
        @media print {
            .page-header .header-meta, .filter-card, .stat-row,
            .dt-buttons, .dataTables_filter, .dataTables_info,
            .table-card-header, .detail-modal-overlay { display: none !important; }
            body { background: #fff; }
            .main-body { padding: 0; }
            .table-card { border: none; padding: 0; box-shadow: none; }
            .inv-link { color: #222 !important; text-decoration: none !important; cursor: default; }
        }

        .btn-rp {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    background: #4f46e5;
    color: #fff;
    transition: background .2s;
}
.btn-rp:hover { background: #4338ca; color: #fff; }

.btn-rp-pos {
    background: #0d9488;
}
.btn-rp-pos:hover { background: #0f766e; }
    </style>
</head>
<body>

{{-- ── Page Header ── --}}
<div class="page-header">
    <div class="page-header-left">
        <div class="header-icon"><i class="fa-solid fa-rotate-left"></i></div>
        <div>
            <p class="header-title">Sales Return Report</p>
            <p class="header-subtitle">Track and manage sales return transactions</p>
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
                            <a href="{{ route('print.invoice-SalesReturn', [
                                'invoice_no'  => $inv->Invoice_no,
                                'branch_code' => $inv->BC,
                                'print_type'  => 'normal'
                            ]) }}" target="_blank" class="btn-rp">
                                <i class="fa-solid fa-print"></i> Print
                            </a>
                            <a href="{{ route('print.invoice-SalesReturn', [
                                'invoice_no'  => $inv->Invoice_no,
                                'branch_code' => $inv->BC,
                                'print_type'  => 'pos'
                            ]) }}" target="_blank" class="btn-rp btn-rp-pos">
                                <i class="fa-solid fa-receipt"></i> POS
                            </a>
                        </td>
                    <td>
                        {{-- Clickable Invoice No --}}
                        <span class="inv-link"
                              onclick="openInvoiceDetail(this)"
                              data-invoice="{{ $inv->Invoice_no }}"
                              data-date="{{ $inv->Invoice_date }}"
                              data-customer="{{ $inv->Customer_Name }}"
                              data-nic="{{ $inv->Customer_NIC }}"
                              data-gross="{{ number_format($inv->Gross_Amount, 2) }}"
                              data-discount="{{ number_format($inv->Discount, 2) }}"
                              data-net="{{ number_format($inv->Net_Amount, 2) }}">
                            <i class="fa-solid fa-file-magnifying-glass"
                               style="font-size:11px; margin-right:4px;"></i>
                            {{ $inv->Invoice_no }}
                        </span>
                    </td>
                    <td>{{ $inv->Invoice_date }}</td>
                    <td>{{ $inv->Customer_NIC }}</td>
                    <td>{{ $inv->Customer_Name }}</td>
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
                    <td colspan="5">Grand Total</td>
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


{{-- ══════════════════════════════════════════
     INVOICE DETAIL MODAL
══════════════════════════════════════════ --}}
<div class="detail-modal-overlay" id="detailModalOverlay"
     onclick="closeModalOutside(event)">

    <div class="detail-modal">

        {{-- Header --}}
        <div class="detail-modal-header">
            <div class="dh-left">
                <div class="dh-icon"><i class="fa-solid fa-receipt"></i></div>
                <div>
                    <p class="dh-title" id="modalTitle">Invoice Details</p>
                    <p class="dh-sub"   id="modalSub">—</p>
                </div>
            </div>
            <button class="btn-close-modal" onclick="closeModal()" title="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Summary Strip --}}
        <div class="detail-summary-strip">
            <div class="ds-item">
                <span class="ds-label"><i class="fa-solid fa-hashtag" style="font-size:9px;"></i> Invoice No</span>
                <span class="ds-value" id="ds-inv">—</span>
            </div>
            <div class="ds-item">
                <span class="ds-label"><i class="fa-solid fa-calendar" style="font-size:9px;"></i> Date</span>
                <span class="ds-value" id="ds-date">—</span>
            </div>
            <div class="ds-item">
                <span class="ds-label"><i class="fa-solid fa-id-card" style="font-size:9px;"></i> NIC</span>
                <span class="ds-value" id="ds-nic">—</span>
            </div>
            <div class="ds-item">
                <span class="ds-label"><i class="fa-solid fa-user" style="font-size:9px;"></i> Customer</span>
                <span class="ds-value" id="ds-cust">—</span>
            </div>
            <div class="ds-item">
                <span class="ds-label"><i class="fa-solid fa-file-invoice-dollar" style="font-size:9px;"></i> Gross Amt</span>
                <span class="ds-value" id="ds-gross" style="color:#2563eb;">—</span>
            </div>
            <div class="ds-item">
                <span class="ds-label"><i class="fa-solid fa-tag" style="font-size:9px;"></i> Discount</span>
                <span class="ds-value" id="ds-disc" style="color:#d97706;">—</span>
            </div>
            <div class="ds-item">
                <span class="ds-label"><i class="fa-solid fa-circle-check" style="font-size:9px;"></i> Net Amt</span>
                <span class="ds-value" id="ds-net" style="color:#16a34a;">—</span>
            </div>
        </div>

        {{-- Detail Body --}}
        <div class="detail-modal-body" id="detailModalBody">
            <div class="modal-state">
                <i class="fa-solid fa-circle-notch spin"></i>
                <p>Loading item details…</p>
            </div>
        </div>

    </div>
</div>{{-- /modal --}}


{{-- ── Scripts ── --}}
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
    /* ── DataTable ── */
    $(document).ready(function () {
        $('#t_invoice_sums').DataTable({
            dom: 'Bfrtip',
            paging: false,
            buttons: ['copy', 'excel', 'csv', 'pdf'],
            columnDefs: [{ orderable: false, targets: 0 }]
        });
    });

    /* ── Detail Modal ── */
    // Route template — Laravel renders the named route with a placeholder
    const DETAIL_URL = "{{ route('sales.return.details', ['invoiceNo' => '__INV__']) }}";

    function openInvoiceDetail(el) {
        const d = el.dataset;

        // 1. Fill summary strip immediately (no AJAX wait)
        document.getElementById('ds-inv').textContent   = d.invoice;
        document.getElementById('ds-date').textContent  = d.date;
        document.getElementById('ds-nic').textContent   = d.nic;
        document.getElementById('ds-cust').textContent  = d.customer;
        document.getElementById('ds-gross').textContent = d.gross;
        document.getElementById('ds-disc').textContent  = d.discount;
        document.getElementById('ds-net').textContent   = d.net;

        // 2. Modal header
        document.getElementById('modalTitle').textContent = 'Invoice  #' + d.invoice;
        document.getElementById('modalSub').textContent   = d.customer + '   |   ' + d.date;

        // 3. Show overlay with spinner
        document.getElementById('detailModalBody').innerHTML = `
            <div class="modal-state">
                <i class="fa-solid fa-circle-notch spin"></i>
                <p>Loading item details…</p>
            </div>`;
        document.getElementById('detailModalOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';

        // 4. Fetch details
        const url = DETAIL_URL.replace('__INV__', encodeURIComponent(d.invoice));

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(data => renderDetailTable(data))
        .catch(err  => {
            document.getElementById('detailModalBody').innerHTML = `
                <div class="modal-state">
                    <i class="fa-solid fa-triangle-exclamation" style="color:#dc2626;"></i>
                    <p>Failed to load details.<br>
                       <small style="color:#6b7280;">${err.message}</small></p>
                </div>`;
        });
    }

    function renderDetailTable(rows) {
        if (!rows || rows.length === 0) {
            document.getElementById('detailModalBody').innerHTML = `
                <div class="modal-state">
                    <i class="fa-solid fa-box-open" style="color:#d1d5db;"></i>
                    <p>No item details found for this invoice.</p>
                </div>`;
            return;
        }

        let totalQty = 0, totalDisc = 0, totalNet = 0;
        rows.forEach(r => {
            totalQty  += parseFloat(r.QTY)        || 0;
            totalDisc += parseFloat(r.Discount)    || 0;
            totalNet  += parseFloat(r.Net_value)   || 0;
        });

        const bodyRows = rows.map((r, i) => `
            <tr>
                <td>${i + 1}</td>
                <td><strong>${r.Item_code ?? '—'}</strong></td>
                <td>${r.Item_description ?? '—'}</td>
                <td class="r">${fmt(r.QTY)}</td>
                <td class="r">${fmt(r.Unit_price)}</td>
                <td class="r">${fmt(r.Discount)}</td>
                <td class="r net-val">${fmt(r.Net_value)}</td>
            </tr>`).join('');

        document.getElementById('detailModalBody').innerHTML = `
            <table class="detail-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Item Code</th>
                        <th>Description</th>
                        <th class="r">QTY</th>
                        <th class="r">Unit Price</th>
                        <th class="r">Discount</th>
                        <th class="r">Net Value</th>
                    </tr>
                </thead>
                <tbody>${bodyRows}</tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">Total  ( ${rows.length} item${rows.length > 1 ? 's' : ''} )</td>
                        <td class="r">${totalQty.toFixed(2)}</td>
                        <td></td>
                        <td class="r">${totalDisc.toFixed(2)}</td>
                        <td class="r">${totalNet.toFixed(2)}</td>
                    </tr>
                </tfoot>
            </table>`;
    }

    /* Helper: safe number format */
    function fmt(val) {
        const n = parseFloat(val);
        return isNaN(n) ? '0.00' : n.toFixed(2);
    }

    function closeModal() {
        document.getElementById('detailModalOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }

    function closeModalOutside(e) {
        if (e.target === document.getElementById('detailModalOverlay')) closeModal();
    }

    // Close on Escape
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>

<script>
    /* ── Print main table ── */
    function printTableFun() {
        const table = document.getElementById('t_invoice_sums');
        const win   = window.open('', '', 'width=1200,height=750');
        win.document.write(`
            <html><head><title>Sales Return Report</title>
            <style>
                body  { font-family:'Segoe UI',Arial,sans-serif; padding:24px; font-size:12px; color:#222; }
                h3    { margin-bottom:4px; font-size:18px; color:#4f46e5; }
                p     { color:#888; margin-bottom:16px; font-size:11px; }
                table { width:100%; border-collapse:collapse; }
                th    { background:#4f46e5; color:#fff; padding:8px 10px; text-align:left; font-size:11px; }
                td    { padding:7px 10px; border-bottom:1px solid #eee; }
                tr:nth-child(even) td { background:#f9f9f9; }
                tfoot td { background:#1e1b4b; color:#fff; font-weight:bold; }
                .inv-link { color:#222 !important; text-decoration:none !important; cursor:default; }
            </style>
            </head><body>
            <h3>Sales Return Report</h3>
            <p>Printed: ${new Date().toLocaleDateString()}</p>
            ${table.outerHTML}
            </body></html>`);
        win.document.close();
        setTimeout(() => { win.print(); win.close(); }, 400);
    }
</script>

<script>
    /* ── Default today's date ── */
    const today = new Date().toISOString().slice(0, 10);
    if (!document.getElementById('to_date').value)   document.getElementById('to_date').value   = today;
    if (!document.getElementById('from_date').value) document.getElementById('from_date').value = today;
</script>

</body>
</html>