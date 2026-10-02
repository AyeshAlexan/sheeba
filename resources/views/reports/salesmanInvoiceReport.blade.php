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
                <title>Salesman Invoice Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                #t_redeem_sums thead th{
                    text-transform:uppercase; letter-spacing:.04em; font-size:11.5px !important;
                    color:var(--tr-text-secondary) !important; background:var(--tr-bg) !important;
                }
                #t_redeem_sums tbody tr:hover{ background:var(--tr-blue-light) !important; }
            </style>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Salesman Invoice Report</h3>
                                <p class="page-subtitle">Item-wise sales totals for a single salesman over a date range.</p>
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
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date', now()->toDateString()) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0 small">Salesman</label>
                                        <select class="form-control" name="Salesmen">
                                            <option value="">Please Select</option>
                                            @foreach($salesmen as $sm)
                                            <option value="{{ $sm->name }}" {{ request('Salesmen') == $sm->name ? 'selected' : '' }}>{{ $sm->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table id="t_redeem_sums" class="table table-bordered table-hover" style="width:100%;">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Item Code</th>
                                                <th>Description</th>
                                                <th>Total Qty</th>
                                                <th>Total Free Issues</th>
                                                <th>Avg. Unit Price</th>
                                                <th>Total Line Discount</th>
                                                <th>Total Net Value</th>
                                                <th>Total Invoice Amount</th>
                                                <th>Total Invoice Discount</th>
                                                <th>Total Invoice Net Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoice as $index => $row)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $row->Item_code }}</td>
                                                <td>{{ $row->Item_description }}</td>
                                                <td>{{ $row->Total_Qty }}</td>
                                                <td>{{ $row->Total_Free }}</td>
                                                <td>{{ number_format($row->Avg_Unit_Price, 2) }}</td>
                                                <td>{{ number_format($row->Total_Line_Discount, 2) }}</td>
                                                <td>{{ number_format($row->Total_Net_Value, 2) }}</td>
                                                <td>{{ number_format($row->Total_Invoice_Amount, 2) }}</td>
                                                <td>{{ number_format($row->Total_Invoice_Discount, 2) }}</td>
                                                <td>{{ number_format($row->Total_Invoice_Net_Amount, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div id="salesmanInvoiceCustomPager"></div>
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
        var salesmanTable = $('#t_redeem_sums').DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print'],
            pageLength: 15,
            lengthChange: false,
        });

        $('#t_redeem_sums_wrapper').addClass('dt-collapsed');
        DTCustomPager.init(salesmanTable, '#salesmanInvoiceCustomPager');
    });

    function printTablefun() {
        let printContent = document.getElementById("t_redeem_sums").outerHTML;
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
