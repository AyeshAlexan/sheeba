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
                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                <title>Customer Sales Wish Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
                <style>
                    .select2-container { width: 100% !important; }
                    .select2-container .select2-selection--single {
                        height: calc(2.25rem + 2px) !important;
                        padding: 0.3rem 0.75rem !important;
                        border: 1px solid #dee2e6 !important;
                        border-radius: 6px !important;
                    }
                    .select2-container .select2-selection--single .select2-selection__rendered { line-height: 1.7 !important; }
                    .select2-container .select2-selection--single .select2-selection__arrow { height: 2.3rem !important; }
                </style>
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
                                <h3 class="page-title">Customer Sales Wish Report</h3>
                                <p class="page-subtitle">Invoice-level sales for a chosen customer, with gross/discount/net and payment-mode totals.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="GET" id="customerSalesWishFilterForm" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0 small">Customer</label>
                                        <select class="form-control" name="Customer" id="Customer">
                                            <option value="">Please Select</option>
                                            @foreach ($Customer as $key => $CustomerDetails)
                                            <option value="{{ $CustomerDetails->Code }}" {{ request('Customer') == $CustomerDetails->Code ? 'selected' : '' }}>
                                                {{ $CustomerDetails->First_name }}
                                            </option>
                                            @endforeach
                                        </select>
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
                                        <table class="table" id="t_invoice_sums" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Invoice No</th>
                                                    <th>Invoice Date</th>
                                                    <th>Customer NIC</th>
                                                    <th>Customer Name</th>
                                                    <th>Route</th>
                                                    <th>Salesman</th>
                                                    <th>Gross Amount</th>
                                                    <th>Discount</th>
                                                    <th>Net Amount</th>
                                                    <th>Cash Pay</th>
                                                    <th>Credite</th>
                                                    <th>Cheque</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($invoice as $key => $row)
                                                <tr>
                                                    <td>{{ $row->Invoice_no }}</td>
                                                    <td>{{ $row->Invoice_date }}</td>
                                                    <td>{{ $row->Customer_NIC }}</td>
                                                    <td>{{ $row->Customer_Name }}</td>
                                                    <td>{{ $row->Route }}</td>
                                                    <td>{{ $row->Salesmen }}</td>
                                                    <td class="text-end">{{ number_format($row->Gross_Amount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->Discount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->Net_Amount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->Cash_Pay, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->Credite, 2) }}</td>
                                                    <td class="text-end">{{ number_format($row->Cheque, 2) }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="12" class="text-center text-muted">No results found.</td></tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="6" class="text-end">Total</td>
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
                                </div>
                                <div id="customerSalesWishCustomPager"></div>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/dt-custom-pager.js"></script>

<script>
jQuery(document).ready(function ($) {
    $('#Customer').select2({
        placeholder: 'Please Select',
        allowClear: true,
        width: 'resolve'
    });

    var customerSalesWishTable = $('#t_invoice_sums').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        pageLength: 15,
        lengthChange: false,
    });

    $('#t_invoice_sums_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(customerSalesWishTable, '#customerSalesWishCustomPager');

    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        var dateObj = new Date();
        toDateInput.value = dateObj.toISOString().slice(0, 10);
    }
});

function printTablefun() {
    var params = $('#customerSalesWishFilterForm').serialize();
    window.open('{{ route('customersalesWishReport.print') }}?' + params, '_blank');
}
</script>

</body>
@endsection

</html>
