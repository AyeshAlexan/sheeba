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
                <title>Item Wise Sales</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                /* ── Minimal, airy page chrome ── */
                .iw-card{ background:#fff; border:1px solid var(--tr-border); border-radius:14px; box-shadow:none; }
                .iw-card-body{ padding:22px 24px; }

                .iw-filter-row{ border-bottom:1px solid var(--tr-border); padding-bottom:18px; margin-bottom:18px; }
                .iw-filter-row .form-label{ color:var(--tr-text-secondary); font-size:11.5px; text-transform:uppercase; letter-spacing:.04em; }
                .iw-filter-row .form-control{ border-color:var(--tr-border); }

                .iw-chip-row{ display:flex; gap:8px; flex-wrap:wrap; margin-bottom:22px; }
                .iw-chip{
                    border:1px solid var(--tr-border); background:transparent; color:var(--tr-text-secondary);
                    border-radius:999px; padding:5px 14px; font-size:12.5px; font-weight:600; cursor:pointer;
                    transition:border-color .15s, color .15s;
                }
                .iw-chip:hover{ color:var(--tr-blue); border-color:var(--tr-blue); }

                /* ── Flat stat tiles with a colorful icon badge each ── */
                .iw-stat-row{ display:flex; gap:0; flex-wrap:wrap; margin-bottom:26px; border:1px solid var(--tr-border); border-radius:14px; overflow:hidden; }
                .iw-stat{ flex:1; min-width:170px; padding:16px 20px; border-right:1px solid var(--tr-border); display:flex; align-items:center; gap:12px; }
                .iw-stat:last-child{ border-right:none; }
                .iw-stat-icon{ width:34px; height:34px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:14px; flex:0 0 auto; }
                .iw-stat-icon.green{ background:#EAFBEF; color:var(--tr-success); }
                .iw-stat-icon.blue{ background:#EFF6FF; color:#2563EB; }
                .iw-stat-icon.purple{ background:#F3E8FF; color:#7C3AED; }
                .iw-stat-icon.orange{ background:#FFF7ED; color:#C2410C; }
                .iw-stat-label{ font-size:11px; text-transform:uppercase; letter-spacing:.05em; color:var(--tr-text-secondary); margin-bottom:3px; }
                .iw-stat-value{ font-size:19px; font-weight:700; color:var(--tr-navy); letter-spacing:-.01em; }

                .iw-section-title{ font-size:13px; font-weight:700; color:var(--tr-navy); text-transform:uppercase; letter-spacing:.04em; margin:28px 0 12px; display:flex; align-items:center; justify-content:space-between; }

                /* ── Rounded-corner "special" table card ── */
                .iw-table-card{ background:#fff; border:1px solid var(--tr-border); border-radius:16px; overflow:hidden; }
                .iw-table-card .dt-buttons{ padding:14px 14px 0; margin-bottom:10px !important; }
                .iw-table-card .dt-buttons .buttons-print{ display:none !important; }
                .iw-table-card table{ width:100% !important; border-collapse:separate !important; border-spacing:0 !important; margin:0 !important; }
                .iw-table-card thead th{
                    background:#fcfdfe; color:var(--tr-navy); font-size:11.5px; font-weight:800;
                    text-transform:uppercase; letter-spacing:.04em; text-align:center;
                    padding:14px 10px; border:none; border-bottom:1px solid var(--tr-border);
                }
                .iw-table-card tbody td{ padding:13px 10px; border:none; border-bottom:1px solid #f1f3f6; text-align:center; vertical-align:middle; font-size:13.5px; }
                .iw-table-card tbody tr:last-child td{ border-bottom:none; }
                .iw-table-card tbody tr:hover td{ background:var(--tr-bg); }
                .iw-table-card tfoot td{ border:none; border-top:1px solid var(--tr-border); background:#fcfdfe; }

                .iw-code-chip{ font-family: ui-monospace, "SF Mono", Consolas, monospace; font-size:12px; font-weight:600; color:var(--tr-blue); }

                .iw-pill{ display:inline-block; font-weight:700; border-radius:999px; padding:3px 12px; font-size:12.5px; }
                .iw-pill.qty{ background:#EFF6FF; color:#2563EB; }
                .iw-pill.value{ background:#F3F0FF; color:#7C3AED; }
                .iw-pill.pos{ background:#EAFBEF; color:var(--tr-success); }
                .iw-pill.neg{ background:#FEECEC; color:var(--tr-danger); }
                .iw-profit-badge{ display:inline-block; font-weight:700; border-radius:999px; padding:3px 12px; font-size:12.5px; }
                .iw-profit-badge.pos{ background:#EAFBEF; color:var(--tr-success); }
                .iw-profit-badge.neg{ background:#FEECEC; color:var(--tr-danger); }

                /* ── Overall Sales Summary — tile grid instead of a bare key/value table ── */
                .iw-summary-grid{ display:grid; grid-template-columns:repeat(auto-fit, minmax(150px, 1fr)); gap:12px; }
                .iw-summary-tile{ border:1px solid var(--tr-border); border-radius:12px; padding:14px 16px; background:#fff; }
                .iw-summary-icon{ width:30px; height:30px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:12px; margin-bottom:10px; }
                .iw-summary-icon.blue{ background:#EFF6FF; color:#2563EB; }
                .iw-summary-icon.green{ background:#EAFBEF; color:var(--tr-success); }
                .iw-summary-icon.orange{ background:#FFF7ED; color:#C2410C; }
                .iw-summary-icon.purple{ background:#F3E8FF; color:#7C3AED; }
                .iw-summary-icon.pink{ background:#FDF2F8; color:#DB2777; }
                .iw-summary-label{ font-size:11px; text-transform:uppercase; letter-spacing:.04em; color:var(--tr-text-secondary); margin-bottom:4px; }
                .iw-summary-value{ font-size:17px; font-weight:700; color:var(--tr-navy); }

                /* ── Quieter DataTables chrome: no duplicate search box, muted sort arrows ── */
                .iw-table-card .dataTables_filter{ display:none; }
                .iw-table-card thead th.sorting:after,
                .iw-table-card thead th.sorting_asc:after,
                .iw-table-card thead th.sorting_desc:after{ opacity:.35; font-size:12px; }
                .iw-table-card .dataTables_empty{ padding:36px 0 !important; color:var(--tr-text-secondary); font-size:13.5px; }
            </style>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Item Wise Sales</h3>
                                <p class="page-subtitle">View and analyse item wise sales with period, quantity, and value details.</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                                <i class="fas fa-print"></i> Print Report
                            </button>
                            <div class="dropdown">
                                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-download"></i> Export
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportItemsTable('copy')"><i class="fas fa-copy me-2"></i> Copy</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportItemsTable('csv')"><i class="fas fa-file-csv me-2"></i> CSV</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportItemsTable('excel')"><i class="fas fa-file-excel me-2"></i> Excel</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="exportItemsTable('pdf')"><i class="fas fa-file-pdf me-2"></i> PDF</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="iw-card">
                            <div class="iw-card-body">

                                <div class="iw-filter-row">
                                    <form action="" method="GET" id="iwFilterForm" class="row g-3 align-items-end">
                                        <div class="col-md-2">
                                            <label class="form-label mb-1">From Date</label>
                                            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label mb-1">To Date</label>
                                            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label mb-1">Item Code</label>
                                            <input type="text" name="item_code" class="form-control" list="iw_item_list" value="{{ $itemCode }}" placeholder="All Items">
                                            <datalist id="iw_item_list">
                                                @foreach($allItems as $it)
                                                <option value="{{ $it->Item_code }}">{{ $it->Item_description }}</option>
                                                @endforeach
                                            </datalist>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label mb-1">Search Item</label>
                                            <input type="text" id="iw_quick_search" class="form-control" placeholder="Enter item name or code…">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="iw-chip-row">
                                    <span class="iw-chip" data-range="this_month">This Month</span>
                                    <span class="iw-chip" data-range="last_month">Last Month</span>
                                    <span class="iw-chip" data-range="this_quarter">This Quarter</span>
                                    <span class="iw-chip" data-range="this_year">This Year</span>
                                </div>

                                <div class="iw-stat-row">
                                    <div class="iw-stat">
                                        <div class="iw-stat-icon green"><i class="fas fa-cube"></i></div>
                                        <div>
                                            <div class="iw-stat-label">Total Items</div>
                                            <div class="iw-stat-value">{{ $invoice->count() }}</div>
                                        </div>
                                    </div>
                                    <div class="iw-stat">
                                        <div class="iw-stat-icon blue"><i class="fas fa-layer-group"></i></div>
                                        <div>
                                            <div class="iw-stat-label">Total Quantity</div>
                                            <div class="iw-stat-value">{{ number_format($invoice->sum('Qty')) }}</div>
                                        </div>
                                    </div>
                                    <div class="iw-stat">
                                        <div class="iw-stat-icon orange"><i class="fas fa-dollar-sign"></i></div>
                                        <div>
                                            <div class="iw-stat-label">Total Value</div>
                                            <div class="iw-stat-value">{{ number_format($invoice->sum('total_Price'), 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="iw-stat">
                                        <div class="iw-stat-icon purple"><i class="fas fa-chart-line"></i></div>
                                        <div>
                                            <div class="iw-stat-label">Average Price</div>
                                            <div class="iw-stat-value">{{ number_format($invoice->avg('Unit_price'), 2) }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="iw-section-title">
                                    <span>Item Wise Sales Summary</span>
                                    <span class="text-muted" style="text-transform:none; letter-spacing:normal; font-weight:400;">Showing {{ $invoice->count() }} item(s)</span>
                                </div>

                                <div class="iw-table-card">
                                    <div class="table-responsive">
                                        <table id="t_invoice_deils" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Item Code</th>
                                                    <th>Item Name</th>
                                                    <th>Purchase Price</th>
                                                    <th>Quantity Sold</th>
                                                    <th>Free Issues</th>
                                                    <th>Unit Price</th>
                                                    <th>Total Value</th>
                                                    <th>Purchase Value</th>
                                                    <th>Discount</th>
                                                    <th>Profit</th>
                                                    <th>Branch</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoice as $i => $item)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td><span class="iw-code-chip">{{ $item->Item_code }}</span></td>
                                                    <td class="text-start">{{ $item->Item_description }}</td>
                                                    <td>{{ number_format($item->purchasePrice, 2) }}</td>
                                                    <td><span class="iw-pill qty">{{ number_format($item->Qty) }}</span></td>
                                                    <td>{{ number_format($item->Free_Issues) }}</td>
                                                    <td>{{ number_format($item->Unit_price, 2) }}</td>
                                                    <td><span class="iw-pill value">{{ number_format($item->total_Price, 2) }}</span></td>
                                                    <td>{{ number_format($item->purchase_price, 2) }}</td>
                                                    <td>{{ number_format($item->discount, 2) }}</td>
                                                    <td>
                                                        <span class="iw-pill {{ $item->profit >= 0 ? 'pos' : 'neg' }}">
                                                            {{ number_format($item->profit, 2) }}
                                                        </span>
                                                    </td>
                                                    <td>00{{ number_format($item->BC) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="fw-bold">
                                                    <td colspan="10" class="text-end">Total Profit</td>
                                                    <td>{{ $totalProfit }}</td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="itemWishItemsPager"></div>

                                <div class="iw-section-title"><span>Overall Sales Summary</span></div>
                                <div class="iw-summary-grid">
                                    <div class="iw-summary-tile">
                                        <div class="iw-summary-icon blue"><i class="fas fa-layer-group"></i></div>
                                        <div class="iw-summary-label">Total Qty</div>
                                        <div class="iw-summary-value">{{ $totalGrossAmount }}</div>
                                    </div>
                                    <div class="iw-summary-tile">
                                        <div class="iw-summary-icon orange"><i class="fas fa-gift"></i></div>
                                        <div class="iw-summary-label">Free Issues Qty</div>
                                        <div class="iw-summary-value">{{ $sumGrossAmountFree_Issues }}</div>
                                    </div>
                                    <div class="iw-summary-tile">
                                        <div class="iw-summary-icon green"><i class="fas fa-coins"></i></div>
                                        <div class="iw-summary-label">Total Unit Amount</div>
                                        <div class="iw-summary-value">{{ $totalUnit }}</div>
                                    </div>
                                    <div class="iw-summary-tile">
                                        <div class="iw-summary-icon pink"><i class="fas fa-tag"></i></div>
                                        <div class="iw-summary-label">Total Discount</div>
                                        <div class="iw-summary-value">{{ $totalDiscount }}</div>
                                    </div>
                                    <div class="iw-summary-tile">
                                        <div class="iw-summary-icon green"><i class="fas fa-check-circle"></i></div>
                                        <div class="iw-summary-label">Total Net Amount</div>
                                        <div class="iw-summary-value">{{ $totalNetAmount }}</div>
                                    </div>
                                    <div class="iw-summary-tile">
                                        <div class="iw-summary-icon purple"><i class="fas fa-file-invoice"></i></div>
                                        <div class="iw-summary-label">No. of Invoices</div>
                                        <div class="iw-summary-value">{{ $totalPawn }}</div>
                                    </div>
                                </div>

                                <div class="iw-section-title"><span>Detailed Sales Receipts</span></div>
                                <div class="iw-table-card">
                                    <div class="table-responsive">
                                        <table id="t_invoice_Receipts" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Invoice Number</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Discount</th>
                                                    <th>Net Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($recipts as $receipt)
                                                <tr>
                                                    <td>{{ $receipt->Invoice_no }}</td>
                                                    <td>{{ $receipt->QTY }}</td>
                                                    <td>{{ number_format($receipt->Unit_price, 2) }}</td>
                                                    <td>{{ number_format($receipt->Discount, 2) }}</td>
                                                    <td>{{ number_format($receipt->Net_value, 2) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="itemWishReceiptsPager"></div>

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
var itemsTable;

jQuery(document).ready(function ($) {
    itemsTable = $('#t_invoice_deils').DataTable({
        dom: 'Bt',
        buttons: ['copy', 'excel', 'csv', 'pdf', {
            extend: 'print',
            title: '',
            customize: function (win) {
                window.stockReportPrintCustomize(win);
            }
        }],
        pageLength: 15,
        lengthChange: false,
        columnDefs: [{ orderable: false, targets: 0 }],
        language: { emptyTable: '<i class="fas fa-inbox me-2"></i>No sales found for this period or item.' },
    });
    $('#t_invoice_deils_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(itemsTable, '#itemWishItemsPager');

    $('#iw_quick_search').on('input', function () {
        itemsTable.search($(this).val()).draw();
    });

    var receiptsTable = $('#t_invoice_Receipts').DataTable({
        dom: 'Bt',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        pageLength: 15,
        lengthChange: false,
        language: { emptyTable: '<i class="fas fa-inbox me-2"></i>No receipts found for this period or item.' },
    });
    $('#t_invoice_Receipts_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(receiptsTable, '#itemWishReceiptsPager');

    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        var dateObj = new Date();
        toDateInput.value = dateObj.toISOString().slice(0, 10);
    }

    // ── Quick date-range chips ──
    function fmt(d) { return d.toISOString().slice(0, 10); }

    $('.iw-chip').on('click', function () {
        var range = $(this).data('range');
        var now = new Date();
        var from, to;

        if (range === 'this_month') {
            from = new Date(now.getFullYear(), now.getMonth(), 1);
            to   = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        } else if (range === 'last_month') {
            from = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            to   = new Date(now.getFullYear(), now.getMonth(), 0);
        } else if (range === 'this_quarter') {
            var q = Math.floor(now.getMonth() / 3);
            from = new Date(now.getFullYear(), q * 3, 1);
            to   = new Date(now.getFullYear(), q * 3 + 3, 0);
        } else if (range === 'this_year') {
            from = new Date(now.getFullYear(), 0, 1);
            to   = new Date(now.getFullYear(), 11, 31);
        }

        $('#from_date').val(fmt(from));
        $('#to_date').val(fmt(to));
        $('#iwFilterForm').trigger('submit');
    });
});

function exportItemsTable(type) {
    if (!itemsTable) return;
    itemsTable.button('.buttons-' + type).trigger();
}

// Routes the "Print Report" button through DataTables' own print button
// (which renders a properly bordered/styled printable table) instead of
// dumping unstyled raw HTML into a blank window.
function printTablefun() {
    if (!itemsTable) { alert('Table is still loading, please try again in a moment.'); return; }
    itemsTable.button('.buttons-print').trigger();
}
</script>

<x-report-print-config title="Item Wise Sales" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel" />
</body>
@endsection

</html>
