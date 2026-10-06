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
                <title>Opening Hire Purchase Summary Report</title>
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
                                <h3 class="page-title">Opening Hire Purchase Summary Report</h3>
                                <p class="page-subtitle">Opening hire purchase agreements with instalment, discount and payment totals.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="GET" id="openingHpSumFilterForm" class="row g-2 mb-3 align-items-end">
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
                                        <table class="table" id="t_opening_hire_purchase_sums" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Invoice No</th>
                                                    <th>Invoice Date</th>
                                                    <th>Reference No</th>
                                                    <th>Agreement No</th>
                                                    <th>Customer Name</th>
                                                    <th>Customer NIC</th>
                                                    <th>Schema Type</th>
                                                    <th>Doc. Charge Rate</th>
                                                    <th>Document Charge</th>
                                                    <th>Down Payment Rate</th>
                                                    <th>Down Payment</th>
                                                    <th>Transport</th>
                                                    <th>Instalment Rate</th>
                                                    <th>Instalment Amount</th>
                                                    <th>No. of Instalment</th>
                                                    <th>Instalment Due Date</th>
                                                    <th>Instalment</th>
                                                    <th>Gross Amount</th>
                                                    <th>Discount</th>
                                                    <th>Net Amount</th>
                                                    <th>Cash Payment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($invoice as $key => $row)
                                                <tr>
                                                    <td>{{ $row->invoice_no }}</td>
                                                    <td>{{ $row->invoice_date }}</td>
                                                    <td>{{ $row->reference_no }}</td>
                                                    <td>{{ $row->agreement_no }}</td>
                                                    <td>{{ $row->customer_name }}</td>
                                                    <td>{{ $row->customer_nic }}</td>
                                                    <td>{{ $row->schema_type }}</td>
                                                    <td class="text-end">{{ number_format($row->document_charge_rate, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->document_charge, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->down_payment_rate, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->down_payment, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->transport, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->instalment_rate, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->instalment_amount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->no_of_instalment, 2) }}</td>
                                                    <td>{{ $row->instalment_due_date }}</td>
                                                    <td class="text-end">{{ number_format($row->instalment, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->gross_amount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->discount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->net_amount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->cash_payment, 2) }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="21" class="text-center text-muted">No results found.</td></tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="8" class="text-end">Total</td>
                                                    <td class="text-end">{{ $document_charge }}</td>
                                                    <td></td>
                                                    <td class="text-end">{{ $down_payment }}</td>
                                                    <td class="text-end">{{ $transport }}</td>
                                                    <td></td>
                                                    <td class="text-end">{{ $instalment_amount }}</td>
                                                    <td class="text-end">{{ $no_of_instalment }}</td>
                                                    <td></td>
                                                    <td class="text-end">{{ $instalment }}</td>
                                                    <td class="text-end">{{ $gross_amount }}</td>
                                                    <td class="text-end">{{ $discount }}</td>
                                                    <td class="text-end">{{ $net_amount }}</td>
                                                    <td class="text-end">{{ $cash_payment }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="openingHpSumCustomPager"></div>
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
    var openingHpSumTable = $('#t_opening_hire_purchase_sums').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        pageLength: 15,
        lengthChange: false,
        scrollX: true,
    });

    $('#t_opening_hire_purchase_sums_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(openingHpSumTable, '#openingHpSumCustomPager');

    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        var dateObj = new Date();
        toDateInput.value = dateObj.toISOString().slice(0, 10);
    }
});

function printTablefun() {
    var params = $('#openingHpSumFilterForm').serialize();
    window.open('{{ route('OpeningHirepurchaseSumReport.print') }}?' + params, '_blank');
}
</script>

</body>
@endsection

</html>
