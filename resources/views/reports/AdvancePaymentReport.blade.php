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
                <title>Advance Payment Report</title>
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
                                <i class="fa-solid fa-file-lines"></i>
                            </div>
                            <div>
                                <h3 class="page-title">Advance Payment Report</h3>
                                <p class="page-subtitle">Advance payments received from customers.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="GET" id="advancePaymentFilterForm" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-magnifying-glass"></i> Search</button>
                                    </div>
                                </form>

                                <div class="modern-table-card">
                                    <div class="table-responsive">
                                        <table class="table" id="t_hire_purchase_sums" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Invoice No</th>
                                                    <th>Date</th>
                                                    <th>Customer Name</th>
                                                    <th>Customer Code</th>
                                                    <th>Description</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($invoice as $key => $row)
                                                <tr>
                                                    <td>{{ $row->invoice_no }}</td>
                                                    <td>{{ $row->date }}</td>
                                                    <td>{{ $row->customer_name }}</td>
                                                    <td>{{ $row->cus_code }}</td>
                                                    <td>{{ $row->description }}</td>
                                                    <td class="text-end">{{ number_format($row->amount, 2) }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="6" class="text-center text-muted">No results found.</td></tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" class="text-end">Total Amount</td>
                                                    <td class="text-end">{{ $amount }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="advancePaymentCustomPager"></div>
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
    var advancePaymentTable = $('#t_hire_purchase_sums').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        pageLength: 15,
        lengthChange: false,
    });

    $('#t_hire_purchase_sums_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(advancePaymentTable, '#advancePaymentCustomPager');

    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        var dateObj = new Date();
        toDateInput.value = dateObj.toISOString().slice(0, 10);
    }
});

function printTablefun() {
    var params = $('#advancePaymentFilterForm').serialize();
    window.open('{{ route('AdvancePaymentReport.print') }}?' + params, '_blank');
}
</script>

</body>
@endsection

</html>
