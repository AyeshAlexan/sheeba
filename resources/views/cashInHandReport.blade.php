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
                <title>Cash In Hand Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">

                <style>
                    .cih-scope {
                        --cih-blue:    #1677FF;
                        --cih-green:   #16A34A;
                        --cih-amber:   #F59E0B;
                        --cih-red:     #EF4444;
                        --cih-navy:    #14213D;
                        --cih-gray:    #667085;
                        --cih-border:  #E5EAF2;
                    }

                    /* ── Balance summary strip ── */
                    .cih-summary-row { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 20px; }
                    .cih-summary-card {
                        background: #fff; border: 1px solid var(--cih-border); border-radius: 12px;
                        padding: 18px 22px; flex: 1; min-width: 190px;
                    }
                    .cih-summary-card.highlight {
                        border: 1.5px solid var(--cih-blue);
                        background: linear-gradient(135deg, #EAF3FF 0%, #fff 100%);
                    }
                    .cih-summary-label {
                        font-size: 11.5px; text-transform: uppercase; letter-spacing: .06em;
                        color: var(--cih-gray); margin-bottom: 8px; display: flex; align-items: center; gap: 7px;
                    }
                    .cih-summary-value { font-size: 26px; font-weight: 800; line-height: 1; color: var(--cih-navy); }
                    .cih-summary-value.green { color: var(--cih-green); }
                    .cih-summary-value.red   { color: var(--cih-red); }
                    .cih-summary-sub { font-size: 11.5px; color: var(--cih-gray); margin-top: 6px; }

                    /* ── Opening balance / balance rows ── */
                    #T_account_trans tr.opening-row td {
                        background: #FFF8E1 !important; font-weight: 700; color: var(--cih-navy);
                    }
                    #T_account_trans tfoot .balance-row td {
                        background: #EAF3FF !important; font-weight: 800; font-size: 14px;
                    }
                    .negative-balance { color: var(--cih-red); }
                    .positive-balance { color: var(--cih-green); }

                    /* ── Day End Close panel ── */
                    .day-end-panel {
                        border: 1.5px dashed var(--cih-red);
                        border-radius: 12px; padding: 18px 22px; margin-top: 22px; background: #FEF4F4;
                    }
                    .day-end-panel h5 { color: var(--cih-red); font-weight: 700; font-size: 15px; margin-bottom: 12px; }
                    .badge-closed { background: var(--cih-green); color: #fff; padding: 5px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; }
                    .badge-open   { background: var(--cih-amber); color: #000; padding: 5px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; }
                    .last-closed-box {
                        background: #EAFBEF; border-left: 4px solid var(--cih-green);
                        padding: 10px 16px; border-radius: 6px; margin-bottom: 12px; font-size: 13.5px; color: var(--cih-navy);
                    }
                </style>
            </head>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <i class="fa-solid fa-cash-register"></i>
                            </div>
                            <div>
                                <h3 class="page-title">Cash In Hand Report</h3>
                                <p class="page-subtitle">Daily cash ledger — opening balance, transactions, and closing balance.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0 cih-scope">

                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif
                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <div class="card">
                            <div class="card-body">

                                {{-- ── Balance Summary Strip ── --}}
                                <div class="cih-summary-row">
                                    <div class="cih-summary-card">
                                        <div class="cih-summary-label"><i class="fa-solid fa-lock-open"></i> Opening Balance</div>
                                        <div class="cih-summary-value">{{ $openingBalanceFmt }}</div>
                                        <div class="cih-summary-sub">as of {{ $fromDate }}</div>
                                    </div>
                                    <div class="cih-summary-card">
                                        <div class="cih-summary-label"><i class="fa-solid fa-arrow-down"></i> Total Cash In (DR)</div>
                                        <div class="cih-summary-value green">{{ $totalDrAmount }}</div>
                                        <div class="cih-summary-sub">includes opening balance</div>
                                    </div>
                                    <div class="cih-summary-card">
                                        <div class="cih-summary-label"><i class="fa-solid fa-arrow-up"></i> Total Cash Out (CR)</div>
                                        <div class="cih-summary-value red">{{ $totalCrAmount }}</div>
                                        <div class="cih-summary-sub">for the selected period</div>
                                    </div>
                                    <div class="cih-summary-card highlight">
                                        <div class="cih-summary-label"><i class="fa-solid fa-wallet"></i> Closing / Net Balance</div>
                                        <div class="cih-summary-value {{ str_starts_with($totalBalance, '-') ? 'red' : 'green' }}">{{ $totalBalance }}</div>
                                        <div class="cih-summary-sub">Opening + DR − CR</div>
                                    </div>
                                </div>

                                <form action="" method="GET" id="cashInHandFilterForm" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" class="form-control" name="from_date" id="from_date" value="{{ $fromDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" class="form-control" name="to_date" id="to_date" value="{{ $toDate }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-magnifying-glass"></i> Search</button>
                                    </div>
                                </form>

                                <div class="modern-table-card">
                                    <div class="table-responsive">
                                        <table id="T_account_trans" class="table" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>No</th>
                                                    <th>Transaction</th>
                                                    <th>DR Amount</th>
                                                    <th>CR Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="opening-row">
                                                    <td>{{ $fromDate }}</td>
                                                    <td>-</td>
                                                    <td><i class="fa-solid fa-lock-open"></i> Opening Balance</td>
                                                    <td class="text-end" id="openingBalanceCell">{{ $openingBalanceFmt }}</td>
                                                    <td class="text-end">0.00</td>
                                                </tr>
                                                @forelse($invoice as $inv)
                                                <tr>
                                                    <td>{{ $inv->Ddate }}</td>
                                                    <td>{{ $inv->trance_no }}</td>
                                                    <td>{{ $inv->trance_type }}</td>
                                                    <td class="text-end">{{ number_format($inv->dr_amount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($inv->cr_amount, 2) }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No transactions found.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-end">Total (incl. Opening Balance)</td>
                                                    <td class="text-end" id="totalDrAmount">{{ $totalDrAmount }}</td>
                                                    <td class="text-end" id="totalCrAmount">{{ $totalCrAmount }}</td>
                                                </tr>
                                                <tr class="balance-row">
                                                    <td colspan="3" class="text-end">Balance Total: (DR − CR)</td>
                                                    <td colspan="2" class="text-end">
                                                        <span id="totalBalance"
                                                              class="{{ str_starts_with($totalBalance, '-') ? 'negative-balance' : 'positive-balance' }}">
                                                            {{ $totalBalance }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="cashInHandCustomPager"></div>

                                {{-- ── Day End Close Panel ── --}}
                                <div class="day-end-panel">
                                    <h5><i class="fa-solid fa-calendar-check"></i> Day End Close</h5>

                                    @if($lastClosed)
                                    <div class="last-closed-box">
                                        <i class="fa-solid fa-circle-check text-success"></i>
                                        Last closed: <strong>{{ $lastClosed->close_date->format('Y-m-d') }}</strong>
                                        &nbsp;|&nbsp; Closing Balance: <strong>{{ number_format($lastClosed->closing_balance, 2) }}</strong>
                                        &nbsp;|&nbsp; Closed at: {{ $lastClosed->closed_at->format('Y-m-d H:i') }}
                                    </div>
                                    @endif

                                    @if($todayIsClosed)
                                        <span class="badge-closed">
                                            <i class="fa-solid fa-lock"></i> {{ $toDate }} is already closed
                                        </span>
                                    @else
                                        <p class="mb-2 text-muted" style="font-size:13px;">
                                            Closing this day will save the balance
                                            <strong class="{{ str_starts_with($totalBalance, '-') ? 'negative-balance' : 'positive-balance' }}">
                                                {{ $totalBalance }}
                                            </strong>
                                            as the opening balance for the next day.
                                        </p>
                                        <form action="{{ route('cashInHand.dayEndClose') }}" method="POST" onsubmit="return confirmClose()">
                                            @csrf
                                            <input type="hidden" name="close_date" value="{{ $toDate }}">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa-solid fa-door-closed"></i> Close Day End for {{ $toDate }}
                                            </button>
                                            <span class="badge-open ms-2">
                                                <i class="fa-solid fa-lock-open"></i> Day is Open
                                            </span>
                                        </form>
                                    @endif
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
    var cashInHandTable = $('#T_account_trans').DataTable({
        dom: 'Bfrtip',
        paging: false,
        buttons: ['copy', 'excel', 'csv', 'pdf'],
    });
});

function printTablefun() {
    var params = $('#cashInHandFilterForm').serialize();
    window.open('{{ route('cashInHandReport.print') }}?' + params, '_blank');
}

function confirmClose() {
    return confirm("Are you sure you want to close the day end?\n\nThis will save the closing balance as tomorrow's opening balance.");
}

var toDateInput = document.getElementById('to_date');
if (!toDateInput.value) {
    toDateInput.value = new Date().toISOString().slice(0, 10);
}
</script>

</body>
@endsection

</html>
