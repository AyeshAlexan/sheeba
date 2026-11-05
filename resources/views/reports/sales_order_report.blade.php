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
                <title>Sales Order Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                .sr-stat-row{ display:flex; gap:10px; margin-bottom:16px; flex-wrap:wrap; }
                .sr-stat{
                    flex:1; min-width:140px; background:var(--tr-bg); border:1px solid var(--tr-border);
                    border-radius:12px; padding:12px 16px;
                }
                .sr-stat-label{
                    font-size:11px; text-transform:uppercase; letter-spacing:.04em; color:var(--tr-text-secondary);
                    margin-bottom:4px; display:flex; align-items:center; gap:6px;
                }
                .sr-stat-value{ font-size:19px; font-weight:700; color:var(--tr-navy); }
                .sr-stat-value.success{ color:var(--tr-success); }
                .sr-stat-value.warning{ color:var(--tr-warning); }
                .sr-stat-value.danger{ color:var(--tr-danger); }

                #t_sales_orders thead th{
                    text-transform:uppercase; letter-spacing:.04em; font-size:11.5px !important;
                    color:var(--tr-text-secondary) !important; background:var(--tr-bg) !important;
                }
                #t_sales_orders tbody tr:hover{ background:var(--tr-blue-light) !important; }
                .so-status-badge{
                    display:inline-flex; align-items:center; gap:5px; padding:3px 10px;
                    border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap;
                }
                .so-status-open{ background:#fff3cd; color:#8a6116; }
                .so-status-invoiced{ background:#d1e7dd; color:#0a5c36; }
            </style>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 11l3 3 8-8"/><path d="M20 12v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Sales Order Report</h3>
                                <p class="page-subtitle">Draft and converted sales orders — items, totals and invoice status.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTableFun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                <div class="sr-stat-row">
                                    <div class="sr-stat">
                                        <div class="sr-stat-label"><i class="fas fa-list-ol"></i> Total Orders</div>
                                        <div class="sr-stat-value">{{ $rows->count() }}</div>
                                    </div>
                                    <div class="sr-stat">
                                        <div class="sr-stat-label"><i class="fas fa-hourglass-half"></i> Open</div>
                                        <div class="sr-stat-value warning">{{ $openCount }}</div>
                                    </div>
                                    <div class="sr-stat">
                                        <div class="sr-stat-label"><i class="fas fa-circle-check"></i> Invoiced</div>
                                        <div class="sr-stat-value success">{{ $invoicedCount }}</div>
                                    </div>
                                    <div class="sr-stat">
                                        <div class="sr-stat-label"><i class="fas fa-file-invoice-dollar"></i> Gross Amount</div>
                                        <div class="sr-stat-value">{{ $totalGross }}</div>
                                    </div>
                                    <div class="sr-stat">
                                        <div class="sr-stat-label"><i class="fas fa-tag"></i> Discount</div>
                                        <div class="sr-stat-value warning">{{ $totalDiscount }}</div>
                                    </div>
                                    <div class="sr-stat">
                                        <div class="sr-stat-label"><i class="fas fa-sack-dollar"></i> Net Amount</div>
                                        <div class="sr-stat-value success">{{ $totalNet }}</div>
                                    </div>
                                </div>

                                <form action="" method="GET" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-2">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Customer NIC / Name</label>
                                        <input type="text" name="customer" class="form-control" value="{{ $customer }}" placeholder="Customer NIC or name">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">All</option>
                                            <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Open</option>
                                            <option value="invoiced" {{ $status === 'invoiced' ? 'selected' : '' }}>Invoiced</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table id="t_sales_orders" class="table table-bordered table-hover" style="width:100%;">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="rp-no-print">Action</th>
                                                <th>Order No</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Customer NIC</th>
                                                <th>Customer Name</th>
                                                <th class="text-end">Items</th>
                                                <th class="text-end">Gross Amt</th>
                                                <th class="text-end">Discount</th>
                                                <th class="text-end">Net Amt</th>
                                                <th>Created By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rows as $row)
                                            <tr>
                                                <td class="rp-no-print" style="white-space:nowrap;">
                                                    <a href="{{ route('sales_orders.print', $row->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-print"></i> Print
                                                    </a>
                                                    @if(!$row->invoice_no)
                                                        <a href="{{ route('salesInvoice_withoutVat', ['order' => $row->id]) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-file-invoice"></i> Invoice
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>{{ $row->id }}</td>
                                                <td>{{ \Illuminate\Support\Carbon::parse($row->created_at)->format('Y-m-d') }}</td>
                                                <td>
                                                    @if($row->invoice_no)
                                                        <span class="so-status-badge so-status-invoiced">Invoiced #{{ $row->invoice_no }}</span>
                                                    @else
                                                        <span class="so-status-badge so-status-open">Open</span>
                                                    @endif
                                                </td>
                                                <td>{{ $row->customer_nic }}</td>
                                                <td>{{ $row->customer_name }}</td>
                                                <td class="text-end">{{ $row->item_count }}</td>
                                                <td class="text-end">{{ number_format($row->gross_amount, 2) }}</td>
                                                <td class="text-end">{{ number_format($row->discount, 2) }}</td>
                                                <td class="text-end">{{ number_format($row->net_amount, 2) }}</td>
                                                <td>{{ $row->created_by }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="fw-bold" style="background-color:#f4f6f9;">
                                                <td colspan="7" class="text-end">Grand Total</td>
                                                <td class="text-end">{{ $totalGross }}</td>
                                                <td class="text-end">{{ $totalDiscount }}</td>
                                                <td class="text-end">{{ $totalNet }}</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div id="salesOrderReportCustomPager"></div>
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
<script src="assets/js/dt-custom-pager.js"></script>

<script>
$(document).ready(function () {
    var salesOrderReportTable = $('#t_sales_orders').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        columnDefs: [{ orderable: false, targets: 0 }],
        pageLength: 15,
        lengthChange: false,
    });

    $('#t_sales_orders_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(salesOrderReportTable, '#salesOrderReportCustomPager');

    var today = new Date().toISOString().slice(0, 10);
    if (!document.getElementById('to_date').value)   document.getElementById('to_date').value   = today;
});

function printTableFun() {
    window.stockReportPrintTables(['#t_sales_orders']);
}
</script>

<x-report-print-config title="Sales Order Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel" />
</body>
@endsection

</html>
