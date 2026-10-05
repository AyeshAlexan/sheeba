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
                <title>Sales Summary</title>
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

                #t_invoice_sums thead th{
                    text-transform:uppercase; letter-spacing:.04em; font-size:11.5px !important;
                    color:var(--tr-text-secondary) !important; background:var(--tr-bg) !important;
                }
                #t_invoice_sums tbody tr:hover{ background:var(--tr-blue-light) !important; }
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
                                <h3 class="page-title">Sales Summary</h3>
                                <p class="page-subtitle">Invoice-level sales totals — gross, discount, net, and payment split.</p>
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
                                        <div class="sr-stat-label"><i class="fas fa-file-invoice-dollar"></i> Gross Amount</div>
                                        <div class="sr-stat-value">{{ $totalGrossAmount }}</div>
                                    </div>
                                    <div class="sr-stat">
                                        <div class="sr-stat-label"><i class="fas fa-tag"></i> Discount</div>
                                        <div class="sr-stat-value warning">{{ $totalDiscount }}</div>
                                    </div>
                                    <div class="sr-stat">
                                        <div class="sr-stat-label"><i class="fas fa-circle-check"></i> Net Amount</div>
                                        <div class="sr-stat-value success">{{ $totalNetAmount }}</div>
                                    </div>
                                    <div class="sr-stat">
                                        <div class="sr-stat-label"><i class="fas fa-money-bill-wave"></i> Cash Pay</div>
                                        <div class="sr-stat-value">{{ $totalCashPay }}</div>
                                    </div>
                                    <div class="sr-stat">
                                        <div class="sr-stat-label"><i class="fas fa-clock"></i> Credit</div>
                                        <div class="sr-stat-value danger">{{ $totalCredite }}</div>
                                    </div>
                                    <div class="sr-stat">
                                        <div class="sr-stat-label"><i class="fas fa-money-check"></i> Cheque</div>
                                        <div class="sr-stat-value">{{ $totalCheque }}</div>
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
                                        <label class="form-label mb-0 small">Customer NIC</label>
                                        <input type="text" name="customer" class="form-control" value="{{ $customer }}" placeholder="Customer NIC">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Salesman</label>
                                        <input type="text" name="salesman" class="form-control" value="{{ $salesman }}" placeholder="Salesman">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table id="t_invoice_sums" class="table table-bordered table-hover" style="width:100%;">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="rp-no-print">Action</th>
                                                <th>Invoice No</th>
                                                <th>Date</th>
                                                <th>Customer NIC</th>
                                                <th>Customer Name</th>
                                                <th>Route</th>
                                                <th>Salesman</th>
                                                <th class="text-end">Gross Amt</th>
                                                <th class="text-end">Discount</th>
                                                <th class="text-end">Net Amt</th>
                                                <th class="text-end">Cash Pay</th>
                                                <th class="text-end">Credit</th>
                                                <th class="text-end">Cheque</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoice as $inv)
                                            <tr>
                                                <td class="rp-no-print" style="white-space:nowrap;">
                                                    <a href="{{ route('print.invoice', [
                                                        'invoice_no'  => $inv->Invoice_no,
                                                        'branch_code' => $inv->BC,
                                                        'print_type'  => 'normal'
                                                    ]) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-print"></i> Print
                                                    </a>
                                                    <a href="{{ route('print.invoice', [
                                                        'invoice_no'  => $inv->Invoice_no,
                                                        'branch_code' => $inv->BC,
                                                        'print_type'  => 'pos'
                                                    ]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-receipt"></i> POS
                                                    </a>
                                                </td>
                                                <td>{{ $inv->Invoice_no }}</td>
                                                <td>{{ $inv->Invoice_date }}</td>
                                                <td>{{ $inv->Customer_NIC }}</td>
                                                <td>{{ $inv->Customer_Name }}</td>
                                                <td>{{ $inv->Route }}</td>
                                                <td>{{ $inv->Salesmen }}</td>
                                                <td class="text-end">{{ number_format($inv->Gross_Amount, 2) }}</td>
                                                <td class="text-end">{{ number_format($inv->Discount, 2) }}</td>
                                                <td class="text-end">{{ number_format($inv->Net_Amount, 2) }}</td>
                                                <td class="text-end">{{ number_format($inv->Cash_Pay, 2) }}</td>
                                                <td class="text-end">{{ number_format($inv->Credite, 2) }}</td>
                                                <td class="text-end">{{ number_format($inv->Cheque, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="fw-bold" style="background-color:#f4f6f9;">
                                                <td colspan="7" class="text-end">Grand Total</td>
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
                                <div id="salesSummaryCustomPager"></div>
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
    var salesSummaryTable = $('#t_invoice_sums').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        columnDefs: [{ orderable: false, targets: 0 }],
        pageLength: 15,
        lengthChange: false,
    });

    $('#t_invoice_sums_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(salesSummaryTable, '#salesSummaryCustomPager');

    var today = new Date().toISOString().slice(0, 10);
    if (!document.getElementById('to_date').value)   document.getElementById('to_date').value   = today;
});

function printTableFun() {
    window.stockReportPrintTables(['#t_invoice_sums']);
}
</script>

<x-report-print-config title="Sales Summary" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel" />
</body>
@endsection

</html>
