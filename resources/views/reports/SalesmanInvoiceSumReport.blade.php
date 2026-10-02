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
                <title>Salesman Total Invoice Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                .iw-section-title{ font-size:14px; font-weight:700; color:var(--tr-navy); margin:20px 0 10px; display:flex; align-items:center; gap:8px; }
                .iw-section-title i{ color:var(--tr-blue); }

                #salesmanSummaryTable thead th, #t_redeem_sums thead th{
                    text-transform:uppercase; letter-spacing:.04em; font-size:11.5px !important;
                    color:var(--tr-text-secondary) !important; background:var(--tr-bg) !important;
                }
                #salesmanSummaryTable tbody tr:hover, #t_redeem_sums tbody tr:hover{ background:var(--tr-blue-light) !important; }
                #salesmanSummaryTable tbody tr.grand-total{ background:var(--tr-bg) !important; font-weight:700; }
            </style>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="7" r="4"/><path d="M1 21v-1a8 8 0 0 1 16 0v1"/><path d="M17 11a4 4 0 1 0 0-8"/><path d="M23 21v-1a8 8 0 0 0-5-7.4"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Salesman Total Invoice Report</h3>
                                <p class="page-subtitle">Per-salesman totals, plus every invoice behind those numbers.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="GET" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date', now()->toDateString()) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0 small">Salesman</label>
                                        <select class="form-control" name="Salesmen">
                                            <option value="">Please Select</option>
                                            @foreach($salesmen as $sm)
                                            <option value="{{ $sm->name }}" {{ request('Salesmen') == $sm->name ? 'selected' : '' }}>{{ $sm->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </form>

                                @if($invoice->count() > 0)
                                @php
                                    $summaryData = [];
                                    $totalGross = $totalDiscount = $totalNet = $totalCash = $totalCredit = $totalCheque = $totalPaid = $totalInvoices = 0;
                                    foreach($invoice as $row) {
                                        $salesman = $row->Salesmen;
                                        if (!isset($summaryData[$salesman])) {
                                            $summaryData[$salesman] = ['count'=>0,'gross'=>0,'discount'=>0,'net'=>0,'cash'=>0,'credit'=>0,'cheque'=>0,'paid'=>0];
                                        }
                                        $summaryData[$salesman]['count']++;
                                        $summaryData[$salesman]['gross'] += $row->Gross_Amount;
                                        $summaryData[$salesman]['discount'] += $row->Discount;
                                        $summaryData[$salesman]['net'] += $row->Net_Amount;
                                        $summaryData[$salesman]['cash'] += $row->Cash_Pay;
                                        $summaryData[$salesman]['credit'] += $row->Credite;
                                        $summaryData[$salesman]['cheque'] += $row->Cheque;
                                        $summaryData[$salesman]['paid'] += $row->Paid_Amount;
                                    }
                                    foreach($summaryData as $data) {
                                        $totalInvoices += $data['count'];
                                        $totalGross += $data['gross'];
                                        $totalDiscount += $data['discount'];
                                        $totalNet += $data['net'];
                                        $totalCash += $data['cash'];
                                        $totalCredit += $data['credit'];
                                        $totalCheque += $data['cheque'];
                                        $totalPaid += $data['paid'];
                                    }
                                @endphp

                                <div class="iw-section-title"><i class="fas fa-users"></i> Salesman Summary</div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="salesmanSummaryTable" style="width:100%;">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Salesman</th>
                                                <th class="text-end">Invoice Count</th>
                                                <th class="text-end">Gross Amount</th>
                                                <th class="text-end">Discount</th>
                                                <th class="text-end">Net Amount</th>
                                                <th class="text-end">Cash</th>
                                                <th class="text-end">Credit</th>
                                                <th class="text-end">Cheque</th>
                                                <th class="text-end">Paid Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($summaryData as $salesman => $data)
                                            <tr>
                                                <td><strong>{{ $salesman }}</strong></td>
                                                <td class="text-end">{{ $data['count'] }}</td>
                                                <td class="text-end">{{ number_format($data['gross'], 2) }}</td>
                                                <td class="text-end">{{ number_format($data['discount'], 2) }}</td>
                                                <td class="text-end">{{ number_format($data['net'], 2) }}</td>
                                                <td class="text-end">{{ number_format($data['cash'], 2) }}</td>
                                                <td class="text-end">{{ number_format($data['credit'], 2) }}</td>
                                                <td class="text-end">{{ number_format($data['cheque'], 2) }}</td>
                                                <td class="text-end">{{ number_format($data['paid'], 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="grand-total">
                                                <td>GRAND TOTAL</td>
                                                <td class="text-end">{{ $totalInvoices }}</td>
                                                <td class="text-end">{{ number_format($totalGross, 2) }}</td>
                                                <td class="text-end">{{ number_format($totalDiscount, 2) }}</td>
                                                <td class="text-end">{{ number_format($totalNet, 2) }}</td>
                                                <td class="text-end">{{ number_format($totalCash, 2) }}</td>
                                                <td class="text-end">{{ number_format($totalCredit, 2) }}</td>
                                                <td class="text-end">{{ number_format($totalCheque, 2) }}</td>
                                                <td class="text-end">{{ number_format($totalPaid, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="iw-section-title"><i class="fas fa-receipt"></i> Detailed Invoices</div>
                                <div class="table-responsive">
                                    <table id="t_redeem_sums" class="table table-bordered table-hover" style="width:100%;">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Invoice No</th>
                                                <th>Date</th>
                                                <th>Customer Name</th>
                                                <th>NIC</th>
                                                <th>Phone</th>
                                                <th>Salesman</th>
                                                <th>Gross Amount</th>
                                                <th>Discount</th>
                                                <th>Net Amount</th>
                                                <th>Cash Pay</th>
                                                <th>Credit</th>
                                                <th>Cheque</th>
                                                <th>Paid Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoice as $row)
                                            <tr>
                                                <td>{{ $row->Invoice_no }}</td>
                                                <td>{{ $row->Invoice_date }}</td>
                                                <td>{{ $row->Customer_Name }}</td>
                                                <td>{{ $row->Customer_NIC }}</td>
                                                <td>{{ $row->Customer_Phone }}</td>
                                                <td>{{ $row->Salesmen }}</td>
                                                <td class="text-end">{{ number_format($row->Gross_Amount, 2) }}</td>
                                                <td class="text-end">{{ number_format($row->Discount, 2) }}</td>
                                                <td class="text-end">{{ number_format($row->Net_Amount, 2) }}</td>
                                                <td class="text-end">{{ number_format($row->Cash_Pay, 2) }}</td>
                                                <td class="text-end">{{ number_format($row->Credite, 2) }}</td>
                                                <td class="text-end">{{ number_format($row->Cheque, 2) }}</td>
                                                <td class="text-end">{{ number_format($row->Paid_Amount, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div id="salesmanSumDetailPager"></div>
                                @else
                                <p class="text-muted text-center py-4">No results found.</p>
                                @endif
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
<script src="assets/js/dt-custom-pager.js"></script>

<script>
    $(document).ready(function () {
        if ($('#salesmanSummaryTable').length) {
            $('#salesmanSummaryTable').DataTable({
                dom: 'Bfrtip',
                paging: false,
                info: false,
                searching: false,
                buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5'],
            });
        }

        if ($('#t_redeem_sums').length) {
            var detailTable = $('#t_redeem_sums').DataTable({
                dom: 'Bfrtip',
                buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print'],
                pageLength: 15,
                lengthChange: false,
            });

            $('#t_redeem_sums_wrapper').addClass('dt-collapsed');
            DTCustomPager.init(detailTable, '#salesmanSumDetailPager');
        }
    });

    function printTablefun() {
        let summaryTable = document.getElementById('salesmanSummaryTable');
        let detailTable = document.getElementById('t_redeem_sums');
        let newWin = window.open("");
        newWin.document.write("<html><head><title>Print</title>");
        newWin.document.write("<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'>");
        newWin.document.write("<style>table{margin-bottom:20px;} .text-end{text-align:right;}</style>");
        newWin.document.write("</head><body>");
        newWin.document.write("<h4 class='text-center mb-3'>Salesman Total Invoice Report</h4>");
        if (summaryTable) { newWin.document.write("<h5>Salesman Summary</h5>"); newWin.document.write(summaryTable.outerHTML); }
        if (detailTable) { newWin.document.write("<h5>Detailed Invoices</h5>"); newWin.document.write(detailTable.outerHTML); }
        newWin.document.write("</body></html>");
        newWin.print();
        newWin.close();
    }
</script>

</body>
@endsection

</html>
