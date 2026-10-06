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
                <title>Sales Return Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">

                <style>
                    .srr-scope {
                        --srr-blue:    #1677FF;
                        --srr-green:   #16A34A;
                        --srr-amber:   #F59E0B;
                        --srr-red:     #EF4444;
                        --srr-purple:  #7C3AED;
                        --srr-teal:    #0D9488;
                        --srr-navy:    #14213D;
                        --srr-gray:    #667085;
                        --srr-border:  #E5EAF2;
                    }

                    /* ── Stat Cards ── */
                    .srr-stat-row  { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 20px; }
                    .srr-stat-card {
                        background: #fff; border-radius: 10px; border: 1px solid var(--srr-border);
                        padding: 14px 20px; flex: 1; min-width: 140px;
                    }
                    .srr-stat-label {
                        font-size: 11px; text-transform: uppercase; letter-spacing: .06em;
                        color: var(--srr-gray); margin-bottom: 6px; display: flex; align-items: center; gap: 6px;
                    }
                    .srr-stat-value   { font-size: 22px; font-weight: 700; line-height: 1; }
                    .srr-stat-value.blue   { color: var(--srr-blue); }
                    .srr-stat-value.green  { color: var(--srr-green); }
                    .srr-stat-value.amber  { color: var(--srr-amber); }
                    .srr-stat-value.red    { color: var(--srr-red); }
                    .srr-stat-value.purple { color: var(--srr-purple); }
                    .srr-stat-value.teal   { color: var(--srr-teal); }

                    .date-range-badge {
                        background: #f3f4f6; border: 1px solid var(--srr-border); border-radius: 20px;
                        padding: 4px 14px; font-size: 12px; color: var(--srr-gray);
                        display: flex; align-items: center; gap: 6px;
                    }

                    /* ── Clickable Invoice No ── */
                    .inv-link {
                        color: var(--srr-blue); font-weight: 600; cursor: pointer;
                        text-decoration: underline dotted; transition: color .15s; white-space: nowrap;
                    }
                    .inv-link:hover { color: var(--srr-purple); }

                    /* ── Per-row Print/POS buttons ── */
                    .btn-rp {
                        display: inline-flex; align-items: center; gap: 5px;
                        padding: 4px 10px; border-radius: 5px; font-size: 12px; font-weight: 600;
                        text-decoration: none; background: var(--srr-blue); color: #fff; transition: background .2s;
                    }
                    .btn-rp:hover { background: #0e5fe0; color: #fff; }
                    .btn-rp-pos { background: var(--srr-teal); }
                    .btn-rp-pos:hover { background: #0f766e; }

                    /* ══════════════════════════
                       DETAIL MODAL (namespaced — avoids Bootstrap's .modal)
                    ══════════════════════════ */
                    .srr-modal-overlay {
                        display: none; position: fixed; inset: 0;
                        background: rgba(0,0,0,0.50); z-index: 9998;
                        align-items: flex-start; justify-content: center;
                        padding: 50px 16px 20px;
                    }
                    .srr-modal-overlay.active { display: flex; }

                    .srr-modal {
                        background: #fff; border-radius: 14px;
                        width: 100%; max-width: 1000px; max-height: 85vh;
                        display: flex; flex-direction: column;
                        box-shadow: 0 24px 70px rgba(0,0,0,0.30); overflow: hidden;
                    }

                    .srr-modal-header {
                        background: linear-gradient(135deg, var(--srr-blue) 0%, var(--srr-purple) 100%);
                        color: #fff; padding: 16px 22px;
                        display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
                    }
                    .dh-left { display: flex; align-items: center; gap: 12px; }
                    .dh-icon {
                        background: rgba(255,255,255,0.20); border-radius: 8px;
                        width: 40px; height: 40px; display: flex;
                        align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0;
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

                    .detail-summary-strip {
                        background: #f8f7ff; border-bottom: 1px solid var(--srr-border);
                        padding: 12px 22px; display: flex; gap: 24px; flex-wrap: wrap; flex-shrink: 0;
                    }
                    .ds-item  { display: flex; flex-direction: column; }
                    .ds-label { font-size: 10px; text-transform: uppercase; letter-spacing: .06em; color: #888; }
                    .ds-value { font-size: 14px; font-weight: 700; color: var(--srr-navy); margin-top: 1px; }

                    .srr-modal-body { overflow-y: auto; padding: 18px 22px 22px; flex: 1; }

                    .detail-table { width: 100%; border-collapse: collapse; font-size: 13px; }
                    .detail-table thead th {
                        background: var(--srr-navy); color: #fff; padding: 9px 12px;
                        text-align: left; font-size: 11px; font-weight: 600;
                        text-transform: uppercase; letter-spacing: .04em; white-space: nowrap;
                    }
                    .detail-table thead th.r { text-align: right; }
                    .detail-table tbody td {
                        padding: 9px 12px; border-bottom: 1px solid #f0f0f0; color: #374151; vertical-align: middle;
                    }
                    .detail-table tbody td.r { text-align: right; }
                    .detail-table tbody tr:last-child td { border-bottom: none; }
                    .detail-table tbody tr:hover td { background: #f5f3ff; }
                    .detail-table tfoot td { background: var(--srr-blue); color: #fff; font-weight: 700; padding: 9px 12px; }
                    .detail-table tfoot td.r { text-align: right; }

                    .net-val { font-weight: 700; color: var(--srr-green); }

                    .modal-state { text-align: center; padding: 50px 20px; color: #9ca3af; }
                    .modal-state i { font-size: 38px; margin-bottom: 12px; display: block; }
                    .modal-state p { font-size: 14px; margin: 0; }
                    .spin { color: var(--srr-purple); animation: srrSpin .75s linear infinite; }
                    @keyframes srrSpin { to { transform: rotate(360deg); } }
                </style>
            </head>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <i class="fa-solid fa-rotate-left"></i>
                            </div>
                            <div>
                                <h3 class="page-title">Sales Return Report</h3>
                                <p class="page-subtitle">Track and manage sales return transactions.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0 srr-scope">
                        <div class="card">
                            <div class="card-body">

                                <div class="srr-stat-row">
                                    <div class="srr-stat-card">
                                        <div class="srr-stat-label"><i class="fa-solid fa-file-invoice-dollar"></i> Gross Amount</div>
                                        <div class="srr-stat-value blue">{{ $totalGrossAmount }}</div>
                                    </div>
                                    <div class="srr-stat-card">
                                        <div class="srr-stat-label"><i class="fa-solid fa-tag"></i> Discount</div>
                                        <div class="srr-stat-value amber">{{ $totalDiscount }}</div>
                                    </div>
                                    <div class="srr-stat-card">
                                        <div class="srr-stat-label"><i class="fa-solid fa-circle-check"></i> Net Amount</div>
                                        <div class="srr-stat-value green">{{ $totalNetAmount }}</div>
                                    </div>
                                    <div class="srr-stat-card">
                                        <div class="srr-stat-label"><i class="fa-solid fa-money-bill-wave"></i> Cash Pay</div>
                                        <div class="srr-stat-value purple">{{ $totalCashPay }}</div>
                                    </div>
                                    <div class="srr-stat-card">
                                        <div class="srr-stat-label"><i class="fa-solid fa-clock"></i> Credit</div>
                                        <div class="srr-stat-value red">{{ $totalCredite }}</div>
                                    </div>
                                    <div class="srr-stat-card">
                                        <div class="srr-stat-label"><i class="fa-solid fa-money-check"></i> Cheque</div>
                                        <div class="srr-stat-value teal">{{ $totalCheque }}</div>
                                    </div>
                                </div>

                                <form action="" method="GET" id="salesReturnFilterForm" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-magnifying-glass"></i> Search</button>
                                    </div>
                                </form>

                                @if($fromDate && $toDate)
                                <p class="text-muted small mb-2">
                                    {{ $fromDate }} &nbsp;→&nbsp; {{ $toDate }} &nbsp;•&nbsp; {{ $invoice->count() }} record(s)
                                </p>
                                @endif

                                <div class="modern-table-card">
                                    <div class="table-responsive">
                                        <table id="t_invoice_sums" class="table" style="width:100%;">
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
                                                @forelse($invoice as $inv)
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
                                                        <span class="inv-link"
                                                              onclick="openInvoiceDetail(this)"
                                                              data-invoice="{{ $inv->Invoice_no }}"
                                                              data-date="{{ $inv->Invoice_date }}"
                                                              data-customer="{{ $inv->Customer_Name }}"
                                                              data-nic="{{ $inv->Customer_NIC }}"
                                                              data-gross="{{ number_format($inv->Gross_Amount, 2) }}"
                                                              data-discount="{{ number_format($inv->Discount, 2) }}"
                                                              data-net="{{ number_format($inv->Net_Amount, 2) }}">
                                                            <i class="fa-solid fa-file-magnifying-glass" style="font-size:11px; margin-right:4px;"></i>
                                                            {{ $inv->Invoice_no }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $inv->Invoice_date }}</td>
                                                    <td>{{ $inv->Customer_NIC }}</td>
                                                    <td>{{ $inv->Customer_Name }}</td>
                                                    <td class="text-end">{{ number_format($inv->Gross_Amount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($inv->Discount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($inv->Net_Amount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($inv->Cash_Pay, 2) }}</td>
                                                    <td class="text-end">{{ number_format($inv->Credite, 2) }}</td>
                                                    <td class="text-end">{{ number_format($inv->Cheque, 2) }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="11" class="text-center text-muted">No results found.</td></tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" class="text-end">Grand Total</td>
                                                    <td class="text-end">{{ $totalGrossAmount }}</td>
                                                    <td class="text-end">{{ $totalDiscount }}</td>
                                                    <td class="text-end">{{ $totalNetAmount }}</td>
                                                    <td class="text-end">{{ $totalCashPay }}</td>
                                                    <td class="text-end">{{ $totalCredite }}</td>
                                                    <td class="text-end">{{ $totalCheque }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="salesReturnCustomPager"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
         </div>

        {{-- ══════════════════════════════════════════
             INVOICE DETAIL MODAL
        ══════════════════════════════════════════ --}}
        <div class="srr-modal-overlay srr-scope" id="detailModalOverlay" onclick="closeModalOutside(event)">
            <div class="srr-modal">

                <div class="srr-modal-header">
                    <div class="dh-left">
                        <div class="dh-icon"><i class="fa-solid fa-receipt"></i></div>
                        <div>
                            <p class="dh-title" id="modalTitle">Invoice Details</p>
                            <p class="dh-sub" id="modalSub">—</p>
                        </div>
                    </div>
                    <button class="btn-close-modal" onclick="closeModal()" title="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

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

                <div class="srr-modal-body" id="detailModalBody">
                    <div class="modal-state">
                        <i class="fa-solid fa-circle-notch spin"></i>
                        <p>Loading item details…</p>
                    </div>
                </div>

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
<script src="assets/js/dt-custom-pager.js"></script>

<script>
    $(document).ready(function () {
        $('#t_invoice_sums').DataTable({
            dom: 'Bfrtip',
            paging: false,
            buttons: ['copy', 'excel', 'csv', 'pdf'],
            columnDefs: [{ orderable: false, targets: 0 }]
        });
    });

    const DETAIL_URL = "{{ route('sales.return.details', ['invoiceNo' => '__INV__']) }}";

    function openInvoiceDetail(el) {
        const d = el.dataset;

        document.getElementById('ds-inv').textContent   = d.invoice;
        document.getElementById('ds-date').textContent  = d.date;
        document.getElementById('ds-nic').textContent   = d.nic;
        document.getElementById('ds-cust').textContent  = d.customer;
        document.getElementById('ds-gross').textContent = d.gross;
        document.getElementById('ds-disc').textContent  = d.discount;
        document.getElementById('ds-net').textContent   = d.net;

        document.getElementById('modalTitle').textContent = 'Invoice  #' + d.invoice;
        document.getElementById('modalSub').textContent   = d.customer + '   |   ' + d.date;

        document.getElementById('detailModalBody').innerHTML = `
            <div class="modal-state">
                <i class="fa-solid fa-circle-notch spin"></i>
                <p>Loading item details…</p>
            </div>`;
        document.getElementById('detailModalOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';

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

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    function printTablefun() {
        var params = $('#salesReturnFilterForm').serialize();
        window.open('{{ route('sales.return.report.print') }}?' + params, '_blank');
    }

    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        var dateObj = new Date();
        toDateInput.value = dateObj.toISOString().slice(0, 10);
    }
</script>

</body>
@endsection

</html>
