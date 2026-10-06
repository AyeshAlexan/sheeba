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
                <title>Supplier Payment Report</title>
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
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Supplier Payment Report</h3>
                                <p class="page-subtitle">Every supplier payment — cash, cheque, card, and bank.</p>
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
                                <div class="alert alert-danger">
                                    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                </div>
                                @endif

                                <form action="{{ route('supplyer_payment_report') }}" method="GET" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Supplier Code</label>
                                        <input type="text" name="supplier" class="form-control" value="{{ request('supplier') }}" placeholder="Supplier code">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Submit</button>
                                    </div>
                                </form>

                                <div class="modern-table-card">
                                    <div class="table-responsive">
                                        <table class="table" id="t_item_movements" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Payment No</th>
                                                    <th>Payment Date</th>
                                                    <th>Supplier Code</th>
                                                    <th>Supplier Name</th>
                                                    <th>Supplier Phone</th>
                                                    <th>Amount</th>
                                                    <th>Purchase No</th>
                                                    <th>Note</th>
                                                    <th>Cash</th>
                                                    <th>Card</th>
                                                    <th>Cheque</th>
                                                    <th>Bank</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($payments as $payment)
                                                <tr>
                                                    <td>{{ $payment->Payment_no }}</td>
                                                    <td>{{ $payment->Payment_date }}</td>
                                                    <td>{{ $payment->Supplier_Code }}</td>
                                                    <td>{{ $payment->Supplier_Name }}</td>
                                                    <td>{{ $payment->Supplier_Phone }}</td>
                                                    <td class="text-end">{{ number_format($payment->Payment_Amount, 2) }}</td>
                                                    <td>{{ $payment->Purchase_no }}</td>
                                                    <td>{{ $payment->Payment_note }}</td>
                                                    <td class="text-end">{{ number_format($payment->cash_payment, 2) }}</td>
                                                    <td class="text-end">{{ number_format($payment->card_payment, 2) }}</td>
                                                    <td class="text-end">{{ number_format($payment->cheque_payment, 2) }}</td>
                                                    <td class="text-end">{{ number_format($payment->bank_transfer, 2) }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="12" class="text-center text-muted">No payments found.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="supplierPaymentCustomPager"></div>
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
    var supplierPaymentTable = $('#t_item_movements').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        pageLength: 15,
        lengthChange: false,
    });

    $('#t_item_movements_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(supplierPaymentTable, '#supplierPaymentCustomPager');

    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        var dateObj = new Date();
        toDateInput.value = dateObj.toISOString().slice(0, 10);
    }
});

function printTablefun() {
    var params = $('form[action="{{ route('supplyer_payment_report') }}"]').serialize();
    window.open('{{ route('supplyer_payment_report.print') }}?' + params, '_blank');
}
</script>

</body>
@endsection

</html>
