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
                <title>Cash & Cheque Transaction Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">

                <style>
                    .cct-scope {
                        --cct-blue:    #1677FF;
                        --cct-green:   #16A34A;
                        --cct-amber:   #F59E0B;
                        --cct-red:     #EF4444;
                        --cct-navy:    #14213D;
                        --cct-gray:    #667085;
                        --cct-border:  #E5EAF2;
                    }

                    /* ── Combined balance summary ── */
                    .cct-summary-row { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 20px; }
                    .cct-summary-card {
                        background: #fff; border: 1px solid var(--cct-border); border-radius: 12px;
                        padding: 18px 22px; flex: 1; min-width: 190px;
                    }
                    .cct-summary-card.highlight {
                        border: 1.5px solid var(--cct-blue);
                        background: linear-gradient(135deg, #EAF3FF 0%, #fff 100%);
                    }
                    .cct-summary-label {
                        font-size: 11.5px; text-transform: uppercase; letter-spacing: .06em;
                        color: var(--cct-gray); margin-bottom: 8px; display: flex; align-items: center; gap: 7px;
                    }
                    .cct-summary-value { font-size: 24px; font-weight: 800; line-height: 1; color: var(--cct-navy); }
                    .cct-summary-value.green { color: var(--cct-green); }
                    .cct-summary-value.red   { color: var(--cct-red); }
                    .cct-summary-sub { font-size: 11.5px; color: var(--cct-gray); margin-top: 6px; }

                    /* ── Section headers ── */
                    .cct-section-header {
                        display: flex; align-items: center; gap: 8px;
                        font-size: 14.5px; font-weight: 700; color: var(--cct-navy);
                        margin: 22px 0 10px;
                    }
                    .cct-section-header small { font-weight: 500; color: var(--cct-gray); font-size: 12px; }
                    .cct-section-header.cash i   { color: var(--cct-blue); }
                    .cct-section-header.cheque i { color: var(--cct-amber); }

                    /* ── Opening balance / balance rows ── */
                    .cct-table tr.opening-row td {
                        background: #FFF8E1 !important; font-weight: 700; color: var(--cct-navy);
                    }
                    .cct-table tfoot .balance-row td {
                        background: #EAF3FF !important; font-weight: 800; font-size: 14px;
                    }
                    .negative-balance { color: var(--cct-red); }
                    .positive-balance { color: var(--cct-green); }

                    /* ── Day End Close panel ── */
                    .day-end-panel {
                        border: 1.5px dashed var(--cct-red);
                        border-radius: 12px; padding: 18px 22px; margin-top: 26px; background: #FEF4F4;
                    }
                    .day-end-panel h5 { color: var(--cct-red); font-weight: 700; font-size: 15px; margin-bottom: 12px; }
                    .badge-closed { background: var(--cct-green); color: #fff; padding: 5px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; }
                    .badge-open   { background: var(--cct-amber); color: #000; padding: 5px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; }
                    .last-closed-box {
                        background: #EAFBEF; border-left: 4px solid var(--cct-green);
                        padding: 10px 16px; border-radius: 6px; margin-bottom: 12px; font-size: 13.5px; color: var(--cct-navy);
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
                                <i class="fa-solid fa-sack-dollar"></i>
                            </div>
                            <div>
                                <h3 class="page-title">Cash & Cheque Transaction Report</h3>
                                <p class="page-subtitle">Combined cash and cheque ledger — opening balance, transactions, and closing balance for both.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0 cct-scope">

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

                                {{-- ── Combined Balance Summary ── --}}
                                <div class="cct-summary-row">
                                    <div class="cct-summary-card">
                                        <div class="cct-summary-label"><i class="fa-solid fa-money-bill-wave"></i> Cash Balance</div>
                                        <div class="cct-summary-value {{ str_starts_with($cashBalanceFmt, '-') ? 'red' : 'green' }}">{{ $cashBalanceFmt }}</div>
                                        <div class="cct-summary-sub">AccCode: 201-001</div>
                                    </div>
                                    <div class="cct-summary-card">
                                        <div class="cct-summary-label"><i class="fa-solid fa-money-check"></i> Cheque Balance</div>
                                        <div class="cct-summary-value {{ str_starts_with($chequeBalanceFmt, '-') ? 'red' : 'green' }}">{{ $chequeBalanceFmt }}</div>
                                        <div class="cct-summary-sub">AccCode: 201-123</div>
                                    </div>
                                    <div class="cct-summary-card highlight">
                                        <div class="cct-summary-label"><i class="fa-solid fa-calculator"></i> Combined Total</div>
                                        <div class="cct-summary-value {{ str_starts_with($combinedBalanceFmt, '-') ? 'red' : 'green' }}">{{ $combinedBalanceFmt }}</div>
                                        <div class="cct-summary-sub">Cash + Cheque</div>
                                    </div>
                                </div>

                                <form action="" method="GET" id="cashChequeFilterForm" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" class="form-control" name="from_date" id="from_date" value="{{ $fromDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" class="form-control" name="to_date" id="to_date" value="{{ $toDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Search</label>
                                        <input type="text" class="form-control" name="search" id="search" value="{{ $search }}" placeholder="Description or reference no.">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-magnifying-glass"></i> Search</button>
                                    </div>
                                </form>

                                {{-- ════════ CASH ════════ --}}
                                <div class="cct-section-header cash">
                                    <i class="fa-solid fa-money-bill-wave"></i> Cash In Hand
                                    <small>(AccCode: 201-001) &nbsp;•&nbsp; {{ $fromDate }} → {{ $toDate }}</small>
                                </div>

                                <div class="modern-table-card">
                                    <div class="table-responsive">
                                        <table id="cash_table" class="table cct-table" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>No</th>
                                                    <th>Transaction Type</th>
                                                    <th>Description</th>
                                                    <th>Double Entry</th>
                                                    <th>DR Amount</th>
                                                    <th>CR Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="opening-row">
                                                    <td>{{ $fromDate }}</td>
                                                    <td>-</td>
                                                    <td><i class="fa-solid fa-lock-open"></i> Opening Balance (Cash)</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-end">{{ $cashOpeningBalanceFmt }}</td>
                                                    <td class="text-end">0.00</td>
                                                </tr>
                                                @forelse($cashInvoice as $inv)
                                                <tr>
                                                    <td>{{ $inv->Ddate }}</td>
                                                    <td>{{ $inv->trance_no }}</td>
                                                    <td>
                                                        @if($inv->reference_url)
                                                            <a href="{{ $inv->reference_url }}" target="_blank">{{ $inv->reference_label }}</a>
                                                        @else
                                                            {{ $inv->reference_label }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $inv->Description }}</td>
                                                    <td>{{ $inv->logic_summary }}</td>
                                                    <td class="text-end">{{ number_format($inv->dr_amount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($inv->cr_amount, 2) }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">No cash transactions found.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" class="text-end">Total</td>
                                                    <td class="text-end">{{ $cashTotalDrFmt }}</td>
                                                    <td class="text-end">{{ $cashTotalCrFmt }}</td>
                                                </tr>
                                                <tr class="balance-row">
                                                    <td colspan="5" class="text-end">Cash Balance (DR − CR)</td>
                                                    <td colspan="2" class="text-end">
                                                        <span class="{{ str_starts_with($cashBalanceFmt, '-') ? 'negative-balance' : 'positive-balance' }}">
                                                            {{ $cashBalanceFmt }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                {{-- ════════ CHEQUE ════════ --}}
                                <div class="cct-section-header cheque">
                                    <i class="fa-solid fa-money-check"></i> Cheque In Hand
                                    <small>(AccCode: 201-123) &nbsp;•&nbsp; {{ $fromDate }} → {{ $toDate }}</small>
                                </div>

                                <div class="modern-table-card">
                                    <div class="table-responsive">
                                        <table id="cheque_table" class="table cct-table" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>No</th>
                                                    <th>Transaction Type</th>
                                                    <th>Description</th>
                                                    <th>Double Entry</th>
                                                    <th>DR Amount</th>
                                                    <th>CR Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="opening-row">
                                                    <td>{{ $fromDate }}</td>
                                                    <td>-</td>
                                                    <td><i class="fa-solid fa-lock-open"></i> Opening Balance (Cheque)</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-end">{{ $chequeOpeningBalanceFmt }}</td>
                                                    <td class="text-end">0.00</td>
                                                </tr>
                                                @forelse($chequeInvoice as $inv)
                                                <tr>
                                                    <td>{{ $inv->Ddate }}</td>
                                                    <td>{{ $inv->trance_no }}</td>
                                                    <td>
                                                        @if($inv->reference_url)
                                                            <a href="{{ $inv->reference_url }}" target="_blank">{{ $inv->reference_label }}</a>
                                                        @else
                                                            {{ $inv->reference_label }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $inv->Description }}</td>
                                                    <td>{{ $inv->logic_summary }}</td>
                                                    <td class="text-end">{{ number_format($inv->dr_amount, 2) }}</td>
                                                    <td class="text-end">{{ number_format($inv->cr_amount, 2) }}</td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">No cheque transactions found.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" class="text-end">Total</td>
                                                    <td class="text-end">{{ $chequeTotalDrFmt }}</td>
                                                    <td class="text-end">{{ $chequeTotalCrFmt }}</td>
                                                </tr>
                                                <tr class="balance-row">
                                                    <td colspan="5" class="text-end">Cheque Balance (DR − CR)</td>
                                                    <td colspan="2" class="text-end">
                                                        <span class="{{ str_starts_with($chequeBalanceFmt, '-') ? 'negative-balance' : 'positive-balance' }}">
                                                            {{ $chequeBalanceFmt }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                {{-- ── Day End Close Panel ── --}}
                                <div class="day-end-panel">
                                    <h5><i class="fa-solid fa-calendar-check"></i> Day End Close</h5>

                                    @if($lastClosed)
                                    <div class="last-closed-box">
                                        <i class="fa-solid fa-circle-check text-success"></i>
                                        Last closed: <strong>{{ $lastClosed->close_date->format('Y-m-d') }}</strong>
                                        &nbsp;|&nbsp; Closing Balance: <strong>{{ number_format($lastClosed->closing_balance, 2) }}</strong>
                                        @if(isset($lastClosed->cash_closing_balance))
                                            &nbsp;|&nbsp; Cash: <strong>{{ number_format($lastClosed->cash_closing_balance, 2) }}</strong>
                                            &nbsp;|&nbsp; Cheque: <strong>{{ number_format($lastClosed->cheque_closing_balance, 2) }}</strong>
                                        @endif
                                        &nbsp;|&nbsp; Closed at: {{ $lastClosed->closed_at->format('Y-m-d H:i') }}
                                    </div>
                                    @endif

                                    @if($todayIsClosed)
                                        <span class="badge-closed">
                                            <i class="fa-solid fa-lock"></i> {{ $toDate }} is already closed
                                        </span>
                                    @else
                                        <p class="mb-2 text-muted" style="font-size:13px;">
                                            Closing this day will save:
                                            <strong class="{{ str_starts_with($cashBalanceFmt,   '-') ? 'negative-balance' : 'positive-balance' }}">Cash: {{ $cashBalanceFmt }}</strong>
                                            &nbsp;+&nbsp;
                                            <strong class="{{ str_starts_with($chequeBalanceFmt,'-') ? 'negative-balance' : 'positive-balance' }}">Cheque: {{ $chequeBalanceFmt }}</strong>
                                            &nbsp;=&nbsp;
                                            <strong class="{{ str_starts_with($combinedBalanceFmt,'-') ? 'negative-balance' : 'positive-balance' }}">Total: {{ $combinedBalanceFmt }}</strong>
                                            as the opening balance for the next day.
                                        </p>
                                        <form action="{{ route('CashandChequeTransaction.dayEndClose') }}" method="POST" onsubmit="return confirmClose()">
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
    $('#cash_table').DataTable({
        dom: 'Bfrtip',
        paging: false,
        buttons: ['copy', 'excel', 'csv', 'pdf'],
    });
    $('#cheque_table').DataTable({
        dom: 'Bfrtip',
        paging: false,
        buttons: ['copy', 'excel', 'csv', 'pdf'],
    });
});

function printTablefun() {
    var params = $('#cashChequeFilterForm').serialize();
    window.open('{{ route('cashandChequeTransaction.print') }}?' + params, '_blank');
}

function confirmClose() {
    return confirm("Are you sure you want to close the day end?\n\nThis will save the closing balances (Cash + Cheque) as tomorrow's opening balances.");
}

var toDateInput = document.getElementById('to_date');
if (!toDateInput.value) {
    toDateInput.value = new Date().toISOString().slice(0, 10);
}
</script>

</body>
@endsection

</html>
