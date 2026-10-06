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
                <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
                <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.2/css/buttons.dataTables.min.css">
                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                <title>Customer Account Report</title>
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
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3v18h18"/><path d="M18 9l-5 5-4-4-4 4"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Customer Account Report</h3>
                                <p class="page-subtitle">Customer sales transactions with debit/credit totals and running balance.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                <form action="" method="GET" id="custAccountFilterForm" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0 small">Customer</label>
                                        <select name="customer" id="customer" class="form-control">
                                            <option value="">-- Select Customer --</option>
                                            @foreach($Customerdata as $customer)
                                                <option value="{{ $customer->Code }}"
                                                    {{ request('customer') == $customer->Code ? 'selected' : '' }}>
                                                    {{ $customer->First_name }} - {{ $customer->Code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                                    </div>
                                    <div class="col-md-2 d-flex gap-2">
                                        <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i> Search</button>
                                    </div>
                                </form>

                                <div class="modern-table-card">
                                    <div class="table-responsive">
                                        <table id="T_account_trans" class="table" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>No</th>
                                                    <th>Customer Code</th>
                                                    <th>Customer Name</th>
                                                    <th>Transaction</th>
                                                    <th>DR Amount</th>
                                                    <th>CR Amount</th>
                                                    <th>Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoice as $row)
                                                    @php
                                                        $customerName = $Customerdata->firstWhere('Code', $row->customer)->First_name ?? '';
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $row->dDate }}</td>
                                                        <td>{{ $row->trance_no }}</td>
                                                        <td>{{ $row->customer }}</td>
                                                        <td>{{ $customerName }}</td>
                                                        <td>{{ $row->cr_trnce_code }}</td>
                                                        <td class="text-end">{{ number_format($row->dr_amount, 2) }}</td>
                                                        <td class="text-end">{{ number_format($row->cr_amount, 2) }}</td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" rowspan="2"><strong>Total</strong></td>
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
                                <div id="custAccountCustomPager"></div>
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
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/dt-custom-pager.js"></script>

<script>
$(document).ready(function () {
    $('#customer').select2({
        placeholder: "-- Select Customer --",
        allowClear: true,
        width: 'resolve'
    });

    var custAccountTable = $('#T_account_trans').DataTable({
        dom: 'Bfrtip',
        lengthMenu: [[100, 250, 500, -1], [100, 250, 500, "All"]],
        pageLength: -1,
        buttons: ['copy', 'excel', 'csv', 'pdf']
    });

    $('#T_account_trans_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(custAccountTable, '#custAccountCustomPager');

    if (!$('#to_date').val()) {
        let today = new Date().toISOString().slice(0, 10);
        $('#to_date').val(today);
    }
});

function printTablefun() {
    var params = $('#custAccountFilterForm').serialize();
    window.open('{{ route('Cust_Transferreport.print') }}?' + params, '_blank');
}
</script>

</body>
@endsection

</html>
