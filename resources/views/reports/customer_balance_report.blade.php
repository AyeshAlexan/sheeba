@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Balance Report</title>
    <style>
        #t_item_movements_wrapper .dataTables_filter,
        #t_item_movements_wrapper .dataTables_length,
        #t_item_movements_wrapper .dataTables_info,
        #t_item_movements_wrapper .dataTables_paginate { display:none !important; }
        #balanceTableCustomPager { display:flex; justify-content:center; width:100%; margin-top:18px; }
    </style>
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
                            <h3 class="page-title">Customer Balance Report</h3>
                            <p class="page-subtitle">Customer ledger balances, filterable by customer and date range.</p>
                        </div>
                    </div>
                </div>

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

                        <form method="GET" action="{{ route('customer_balance_report') }}" class="row g-2 mb-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label mb-0 small">Customer Code</label>
                                <input type="text" class="form-control" id="customer" name="customer" value="{{ request('customer') }}" placeholder="e.g. 0012">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 small">From Date</label>
                                <input type="date" class="form-control" name="from_date" id="from_date" value="{{ $fromDate }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 small">To Date</label>
                                <input type="date" class="form-control" name="to_date" id="to_date" value="{{ $toDate }}">
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="{{ route('customer_balance_report') }}" class="btn btn-outline-secondary">Clear</a>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Apply</button>
                                <button type="button" onclick="printTablefun()" class="btn btn-outline-primary">
                                    <i class="fas fa-print"></i> Print
                                </button>
                            </div>
                        </form>

                        @if($fromDate && $toDate && $supName)
                            <h6 class="mb-3">{{$supName}} BALANCE REPORT &nbsp;&nbsp;From: {{$fromDate}}&nbsp;&nbsp;To: {{$toDate}}</h6>
                        @elseif ($fromDate && $toDate)
                            <h6 class="mb-3">CUSTOMER BALANCE REPORT &nbsp;&nbsp;From: {{$fromDate}}&nbsp;&nbsp;To: {{$toDate}}</h6>
                        @elseif ($supName)
                            <h6 class="mb-3">{{$supName}} BALANCE REPORT</h6>
                        @else
                            <h6 class="mb-3">ALL CUSTOMERS BALANCE REPORT</h6>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="t_item_movements">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Customer Code</th>
                                        <th>Customer</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalAmount = 0;
                                    @endphp
                                    @forelse ($customerData as $key=>$data)
                                    <tr>
                                        <td>{{$data->Code}}</td>
                                        <td>{{$data->First_name}}</td>
                                        <td class="text-end">
                                            {{number_format($data->total_cr_amount - $data->total_dr_amount, 2)}}
                                        </td>
                                    </tr>
                                    @php
                                        $totalAmount += $data->total_cr_amount - $data->total_dr_amount;
                                    @endphp
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No customer balances found for the selected filters.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="table-light fw-bold">
                                        <td colspan="2" class="text-center">Total Balance :</td>
                                        <td class="text-end">{{number_format($totalAmount, 2)}}</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div id="balanceTableCustomPager"></div>
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
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/dt-custom-pager.js"></script>
<script src="assets/js/script.js"></script>

<script>
    $(document).ready(function () {
        var balanceTable = $('#t_item_movements').DataTable({
            pageLength: 10,
            lengthChange: false,
            searching: false,
            ordering: true,
            info: false,
            dom: 't',
        });
        DTCustomPager.init(balanceTable, '#balanceTableCustomPager');
    });

    function printTablefun() {
        var divToPrint = document.getElementById("t_item_movements");
        var newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }

    var dateObj = new Date();
    if (!document.getElementById('to_date').value) {
        document.getElementById('to_date').value = dateObj.toISOString().slice(0, 10);
    }
</script>

</body>
@endsection

</html>
