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
                <title>Stock Details Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                #receiptTable thead th{
                    text-transform:uppercase; letter-spacing:.04em; font-size:11.5px !important;
                    color:var(--tr-text-secondary) !important; background:var(--tr-bg) !important;
                }
                #receiptTable tbody tr:hover{ background:var(--tr-blue-light) !important; }
            </style>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Stock Details Report</h3>
                                <p class="page-subtitle">Every item's movement broken down by transaction type — In/Out per type, plus running balance.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                <form method="GET" action="{{ route('reports.stockDetailsSummeryReport') }}" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="receiptTable" style="width:100%;">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Item Code</th>
                                                <th>Description</th>
                                                @foreach($showInCodes as $code)
                                                <th>{{ $code }} In</th>
                                                @endforeach
                                                @foreach($showOutCodes as $code)
                                                <th>{{ $code }} Out</th>
                                                @endforeach
                                                <th>Total In</th>
                                                <th>Total Out</th>
                                                <th>Stock Balance</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($stockData as $row)
                                            <tr>
                                                <td>{{ $row['Item_code'] }}</td>
                                                <td>{{ $row['Item_description'] }}</td>
                                                @foreach($showInCodes as $code)
                                                <td>{{ $row[$code . '_in'] ?? '-' }}</td>
                                                @endforeach
                                                @foreach($showOutCodes as $code)
                                                <td>{{ $row[$code . '_out'] ?? '-' }}</td>
                                                @endforeach
                                                <td>{{ $row['qun_in'] }}</td>
                                                <td>{{ $row['qun_out'] }}</td>
                                                <td class="fw-bold">{{ $row['qun_in'] - $row['qun_out'] - $row['Free_Issues'] }}</td>
                                                <td>{{ $row['dDate'] }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="{{ 6 + count($showInCodes) + count($showOutCodes) }}" class="text-center text-muted">No stock movements found for the selected dates.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div id="stockDetailsCustomPager"></div>
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
        var stockDetailsTable = $('#receiptTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print'],
            scrollX: true,
            pageLength: 15,
            lengthChange: false
        });

        $('#receiptTable_wrapper').addClass('dt-collapsed');
        DTCustomPager.init(stockDetailsTable, '#stockDetailsCustomPager');
    });

    function printTablefun() {
        let printContent = document.getElementById("receiptTable").outerHTML;
        let newWin = window.open("");
        newWin.document.write("<html><head><title>Print</title>");
        newWin.document.write("<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'>");
        newWin.document.write("</head><body>");
        newWin.document.write(printContent);
        newWin.document.write("</body></html>");
        newWin.print();
        newWin.close();
    }
</script>

</body>
@endsection

</html>
