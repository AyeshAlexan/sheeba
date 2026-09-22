@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
          <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" >
                <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
                <title>Daily Transactions</title>
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
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Daily Transactions</h3>
                                <p class="page-subtitle">Every financial transaction across all accounts — sales, payments, receipts, vouchers, purchases and supplier payments — shown automatically. For Cash/Cheque In Hand only, see the Cash Book report.</p>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                <form method="GET" action="{{ route('daily.transactions') }}" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </form>

                                <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Reference</th>
                                        <th>Description</th>
                                        <th>Double Entry</th>
                                        <th>DR Amount</th>
                                        <th>CR Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $row)
                                    <tr>
                                        <td>{{ $row->Ddate }}</td>
                                        <td>
                                            @if($row->reference_url)
                                                <a href="{{ $row->reference_url }}" target="_blank">{{ $row->reference_label }}</a>
                                            @else
                                                {{ $row->reference_label }}
                                            @endif
                                        </td>
                                        <td>{{ $row->Description }}</td>
                                        <td>{{ $row->logic_summary }}</td>
                                        <td>{{ number_format((float) $row->dr_amount, 2) }}</td>
                                        <td>{{ number_format((float) $row->cr_amount, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No transactions found for the selected dates.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if($transactions->count())
                                <tfoot>
                                    <tr class="fw-bold" style="background-color:#f4f6f9;">
                                        <td colspan="4" class="text-end">Total</td>
                                        <td>{{ number_format((float) $totalDr, 2) }}</td>
                                        <td>{{ number_format((float) $totalCr, 2) }}</td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                                </div>
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
<script src="assets/js/script.js"></script>

</body>
@endsection

</html>
