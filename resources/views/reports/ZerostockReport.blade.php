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
                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
                <title>Stock Zero Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                #t_item_movements thead th{
                    text-transform:uppercase; letter-spacing:.04em; font-size:11.5px !important;
                    color:var(--tr-text-secondary) !important; background:var(--tr-bg) !important;
                }
                #t_item_movements tbody tr:hover{ background:var(--tr-blue-light) !important; }
            </style>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Stock Zero</h3>
                                <p class="page-subtitle">Movements with zero quantity received — useful for spotting incomplete entries.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                <form method="GET" action="{{ route('ZerostockReport') }}" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="t_item_movements">
                                        @if($fromDate && $toDate)
                                        <caption>Stock Zero &nbsp;|&nbsp; {{ $fromDate }} &nbsp;→&nbsp; {{ $toDate }}</caption>
                                        @endif
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Item Code</th>
                                                <th>Quantity In</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($invoice as $row)
                                            <tr>
                                                <td>{{ $row->dDate }}</td>
                                                <td>{{ $row->item_code }}</td>
                                                <td>{{ $row->qun_in }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">No zero-quantity movements found for the selected dates.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div id="zeroStockCustomPager"></div>
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
    var zeroStockTable = $('#t_item_movements').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
        pageLength: 15,
        lengthChange: false,
    });

    $('#t_item_movements_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(zeroStockTable, '#zeroStockCustomPager');

    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        toDateInput.value = new Date().toISOString().slice(0, 10);
    }
});

function printTablefun() {
    var divToPrint = document.getElementById("t_item_movements");
    var newWin = window.open("");
    newWin.document.write(divToPrint.outerHTML);
    newWin.print();
    newWin.close();
}
</script>

</body>
@endsection

</html>
