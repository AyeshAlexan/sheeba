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
                <title>Supplier Balance Report</title>
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
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Supplier Balance Report</h3>
                                <p class="page-subtitle">Supplier ledger balances, filterable by supplier and date range.</p>
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

                                <form action="" method="GET" id="supplierBalanceFilterForm" class="row g-2 mb-3 align-items-end">
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

                                @if($fromDate && $toDate && $supName)
                                    <h6 class="mb-3">{{ $supName }} BALANCE REPORT &nbsp;&nbsp;From: {{ $fromDate }}&nbsp;&nbsp;To: {{ $toDate }}</h6>
                                @elseif ($fromDate && $toDate)
                                    <h6 class="mb-3">SUPPLIER BALANCE REPORT &nbsp;&nbsp;From: {{ $fromDate }}&nbsp;&nbsp;To: {{ $toDate }}</h6>
                                @elseif ($supName)
                                    <h6 class="mb-3">{{ $supName }} BALANCE REPORT</h6>
                                @else
                                    <h6 class="mb-3">ALL SUPPLIERS BALANCE REPORT</h6>
                                @endif

                                <div class="modern-table-card">
                                    <div class="table-responsive">
                                        <table class="table" id="t_item_movements" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Supplier Code</th>
                                                    <th>Supplier</th>
                                                    <th>Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $totalAmount = 0; @endphp
                                                @forelse ($supplierData as $key => $data)
                                                <tr>
                                                    <td>{{ $data->Code }}</td>
                                                    <td>{{ $data->Name }}</td>
                                                    <td class="text-end">
                                                        {{ number_format($data->total_dr_amount - $data->total_cr_amount, 2) }}
                                                    </td>
                                                </tr>
                                                @php $totalAmount += $data->total_dr_amount - $data->total_cr_amount; @endphp
                                                @empty
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">No supplier balances found for the selected filters.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="2" class="text-center">Total Balance :</td>
                                                    <td class="text-end">{{ number_format($totalAmount, 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="supplierBalanceCustomPager"></div>
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
    var supplierBalanceTable = $('#t_item_movements').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        pageLength: 15,
        lengthChange: false,
    });

    $('#t_item_movements_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(supplierBalanceTable, '#supplierBalanceCustomPager');

    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        var dateObj = new Date();
        toDateInput.value = dateObj.toISOString().slice(0, 10);
    }
});

function printTablefun() {
    var params = $('#supplierBalanceFilterForm').serialize();
    window.open('{{ route('supplyer_balance_report.print') }}?' + params, '_blank');
}
</script>

</body>
@endsection

</html>
