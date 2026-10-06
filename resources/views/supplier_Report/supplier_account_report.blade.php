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
                <title>Supplier Account Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3v18h18"/><path d="M18 9l-5 5-4-4-4 4"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Supplier Account Report</h3>
                                <p class="page-subtitle">Supplier purchase transactions with debit/credit totals.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <form action="" method="GET" id="supplierAccountFilterForm" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Supplier Code</label>
                                        <input type="text" class="form-control" id="supplier" name="supplier" value="{{ request('supplier') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Submit</button>
                                    </div>
                                </form>

                                @if($fromDate && $toDate)
                                <p class="text-muted small mb-2">{{ $fromDate }} &nbsp;→&nbsp; {{ $toDate }}</p>
                                @endif

                                <div class="modern-table-card">
                                    <div class="table-responsive">
                                        <table class="table" id="t_item_movements" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>No</th>
                                                    <th>Supplier</th>
                                                    <th>Transaction</th>
                                                    <th>DR Amount</th>
                                                    <th>CR Amount</th>
                                                    <th>Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($invoice as $key => $row)
                                                <tr>
                                                    <td>{{ $row->dDate }}</td>
                                                    <td>{{ $row->trance_no }}</td>
                                                    <td>{{ $row->supplier }}</td>
                                                    <td>{{ $row->cr_trnce_code }}</td>
                                                    <td class="text-end">{{ number_format($row->dr_amount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->cr_amount, 2) }}</td>
                                                    <td></td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="7" class="text-center text-muted">No results found.</td></tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" rowspan="2"><strong>Total</strong></td>
                                                    <td id="drTotal">DR Total:</td>
                                                    <td id="crTotal">CR Total:</td>
                                                    <td id="balanceTotal">Balance Total:</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end"><strong id="totalDrAmount">{{ $totalDrAmount }}</strong></td>
                                                    <td class="text-end"><strong id="totalCrAmount">{{ $totalCrAmount }}</strong></td>
                                                    <td class="text-end"><strong id="totalBalance">{{ $totalBalance }}</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="supplierAccountCustomPager"></div>
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
    var supplierAccountTable = $('#t_item_movements').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        pageLength: 15,
        lengthChange: false,
    });

    $('#t_item_movements_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(supplierAccountTable, '#supplierAccountCustomPager');

    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        var dateObj = new Date();
        toDateInput.value = dateObj.toISOString().slice(0, 10);
    }
});

function printTablefun() {
    var params = $('#supplierAccountFilterForm').serialize();
    window.open('{{ route('supplier_account_report.print') }}?' + params, '_blank');
}
</script>

</body>
@endsection

</html>
