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
                <title>Sales Details Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                #t_invoice_deils thead th{
                    text-transform:uppercase; letter-spacing:.04em; font-size:11.5px !important;
                    color:var(--tr-text-secondary) !important; background:var(--tr-bg) !important;
                }
                #t_invoice_deils tbody tr:hover{ background:var(--tr-blue-light) !important; }
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
                                <h3 class="page-title">Sales Details Report</h3>
                                <p class="page-subtitle">Every line item sold — category, quantity, price, and discount.</p>
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
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="t_invoice_deils" style="width:100%;">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Invoice No</th>
                                                <th>Invoice Date</th>
                                                <th>Category</th>
                                                <th>Item Code</th>
                                                <th>Description</th>
                                                <th>QTY</th>
                                                <th>Unit Price</th>
                                                <th>Discount</th>
                                                <th>Net Value</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoice as $key=>$item)
                                            <tr>
                                                <td>{{ $item->Invoice_no }}</td>
                                                <td>{{ $item->Invoice_date }}</td>
                                                <td>{{ $item->Item_category }}</td>
                                                <td>{{ $item->Item_s_code }}</td>
                                                <td>{{ $item->Item_description }}</td>
                                                <td>{{ $item->QTY }}</td>
                                                <td>{{ $item->Unit_price }}</td>
                                                <td>{{ $item->Discount }}</td>
                                                <td>{{ $item->Net_value }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="fw-bold" style="background-color:#f4f6f9;">
                                                <td colspan="5" class="text-end">Total</td>
                                                <td>{{ $totalQTY }}</td>
                                                <td>{{ $totalGrossAmount }}</td>
                                                <td>{{ $totalDiscount }}</td>
                                                <td>{{ $totalNetAmount }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div id="salesDetailsCustomPager"></div>
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
    var salesDetailsTable = $('#t_invoice_deils').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf', {
            extend: 'print',
            className: 'buttons-print d-none',
            title: '',
            customize: function (win) {
                window.stockReportPrintCustomize(win);
            }
        }],
        pageLength: 15,
        lengthChange: false,
    });

    $('#t_invoice_deils_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(salesDetailsTable, '#salesDetailsCustomPager');

    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        var dateObj = new Date();
        toDateInput.value = dateObj.toISOString().slice(0, 10);
    }
});

function printTablefun() {
    $('#t_invoice_deils').DataTable().button('.buttons-print').trigger();
}
</script>

<x-report-print-config title="Sales Details Report" :fromDate="request('from_date')" :toDate="request('to_date')" :companyData="$companyData" :branchDel="$branchDel" />
</body>
@endsection

</html>
