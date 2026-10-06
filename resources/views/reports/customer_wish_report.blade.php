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
                <title>Customer Wish Item Sales</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                .items-cell{ line-height:1.7; }
                .items-cell .item-line{ display:block; white-space:nowrap; }
                .items-cell .item-line + .item-line{ margin-top:2px; padding-top:2px; border-top:1px dashed var(--tr-border); }

                #cwItemsTable thead th{
                    text-transform:uppercase; letter-spacing:.04em; font-size:11.5px !important;
                    color:var(--tr-text-secondary) !important; background:var(--tr-bg) !important;
                }
                #cwItemsTable tbody tr:hover{ background:var(--tr-blue-light) !important; }
            </style>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Customer Wish Item Sales</h3>
                                <p class="page-subtitle">Every invoice for a given customer, with the exact items purchased on each one.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printCustomerWishReport()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="GET" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-5">
                                        <label class="form-label mb-0 small">Search by Code, First Name, or NIC</label>
                                        <input type="text" name="customer_nic" class="form-control" placeholder="Enter Code, First Name, or NIC…" value="{{ $currentNic ?? request('customer_nic') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                    <div class="col-md-2">
                                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary w-100"><i class="fas fa-undo"></i> Clear</a>
                                    </div>
                                </form>

                                @if(($currentNic ?? request('customer_nic')))
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-circle-info"></i>
                                    Showing {{ $reportData->count() }} invoice(s) for NIC <strong>{{ $currentNic ?? request('customer_nic') }}</strong>
                                </p>
                                @endif

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="cwItemsTable" style="width:100%;">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Invoice No</th>
                                                <th>Customer NIC</th>
                                                <th>Customer Name</th>
                                                <th>Items</th>
                                                <th>Total QTY</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($reportData as $row)
                                            <tr>
                                                <td>{{ $row->Invoice_no }}</td>
                                                <td>{{ $row->Customer_NIC }}</td>
                                                <td>{{ $row->Customer_Name }}</td>
                                                <td class="items-cell">
                                                    @foreach(explode('||', $row->items_combined) as $itemLine)
                                                    <span class="item-line">{{ $itemLine }}</span>
                                                    @endforeach
                                                </td>
                                                <td>{{ $row->total_qty }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No records found for this NIC.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div id="customerWishCustomPager"></div>
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
jQuery(document).ready(function ($) {
    var cwTable = $('#cwItemsTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        pageLength: 15,
        lengthChange: false,
    });

    $('#cwItemsTable_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(cwTable, '#customerWishCustomPager');
});

function printCustomerWishReport() {
    window.stockReportPrintTables(['#cwItemsTable']);
}
</script>

<x-report-print-config title="Customer Wish Item Sales" :companyData="$companyData" :branchDel="$branchDel" />
</body>
@endsection

</html>
