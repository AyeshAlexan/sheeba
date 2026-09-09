<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash In Hand Report</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">

    <style>
        body { background: #f4f6f9; }

        .report-wrapper {
            max-width: 1080px;
            margin: 30px auto;
            background: #fff;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }

        h2.report-title {
            background-color: rgb(113, 105, 255);
            color: #fff;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
        }

        /* ── Section headers ── */
        .section-header-cash {
            background: linear-gradient(135deg, #1565c0, #42a5f5);
            color: #fff;
            padding: 8px 14px;
            border-radius: 5px;
            font-size: 15px;
            font-weight: 600;
            margin: 18px 0 8px;
        }
        .section-header-cheque {
            background: linear-gradient(135deg, #e65100, #ffa726);
            color: #fff;
            padding: 8px 14px;
            border-radius: 5px;
            font-size: 15px;
            font-weight: 600;
            margin: 28px 0 8px;
        }

        table { border-collapse: collapse; width: 100%; margin-bottom: 0; }
        th, td { border: 1px solid #333; padding: 6px 8px; }

        thead.cash-head th   { background-color: #1565c0; color: #fff; text-align: center; }
        thead.cheque-head th { background-color: #e65100; color: #fff; text-align: center; }

        /* Opening balance row */
        tr.opening-row td { background-color: #fff8dc; font-weight: bold; }

        tfoot td { font-weight: bold; }
        .balance-row  { background-color: #e3f2fd; }
        .negative-balance { color: #dc3545; }
        .positive-balance { color: #198754; }

        /* ── Combined summary box ── */
        .combined-box {
            background: linear-gradient(135deg, #f3e5f5, #e8eaf6);
            border: 2px solid #7c4dff;
            border-radius: 8px;
            padding: 16px 22px;
            margin-top: 28px;
        }
        .combined-box h5 { color: #4a148c; margin-bottom: 12px; }
        .combined-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
        .combined-card {
            background: #fff;
            border-radius: 6px;
            padding: 12px 16px;
            text-align: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .combined-card .label { font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: .5px; }
        .combined-card .value { font-size: 20px; font-weight: 700; margin-top: 4px; }
        .combined-card.cash-card   .value { color: #1565c0; }
        .combined-card.cheque-card .value { color: #e65100; }
        .combined-card.total-card  .value { color: #4a148c; }

        /* ── Day end panel ── */
        .day-end-panel {
            border: 2px dashed #dc3545;
            border-radius: 6px;
            padding: 14px 18px;
            margin-top: 20px;
            background: #fff5f5;
        }
        .day-end-panel h5 { color: #dc3545; }

        .badge-closed { background: #198754; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 13px; }
        .badge-open   { background: #ffc107; color: #000; padding: 4px 10px; border-radius: 20px; font-size: 13px; }

        .last-closed-box {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 8px 14px;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 14px;
        }

        @media print {
            .no-print { display: none !important; }
            .report-wrapper { box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="report-wrapper">

    {{-- ── Title ── --}}
    <h2 class="report-title"><b>Cash And Cheque Transaction Report</b></h2>
    <hr>

    {{-- ── Alerts ── --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show no-print" role="alert">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show no-print" role="alert">
            <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── Date Filter Form ── --}}
    <form action="" method="get" class="d-flex align-items-center gap-2 flex-wrap mb-3 no-print">
        <label for="from_date"><strong>From Date:</strong></label>
        <input type="date" class="form-control form-control-sm" style="width:160px"
               name="from_date" id="from_date" value="{{ $fromDate }}">

        <label for="to_date"><strong>To Date:</strong></label>
        <input type="date" class="form-control form-control-sm" style="width:160px"
               name="to_date" id="to_date" value="{{ $toDate }}">

        <button type="submit" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-magnifying-glass"></i> Search
        </button>
        <button type="button" class="btn btn-sm btn-secondary" onclick="printTablefun()">
            <i class="fa-solid fa-print"></i> Print
        </button>
        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-dark">
            <i class="fa-solid fa-house"></i> Back
        </a>
    </form>

    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- ── SECTION 1 : CASH (201-001) ── --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="section-header-cash">
        <i class="fa-solid fa-money-bill-wave"></i>&nbsp; Cash In Hand &nbsp;
        <small style="opacity:.8; font-size:12px;">(AccCode: 201-001)</small>
    </div>

    <table id="cash_table">
        <caption style="font-size:15px; font-weight:bold; caption-side:top; text-align:center;">
            Cash Transactions
            @if($fromDate && $toDate)
                &nbsp;|&nbsp; {{ $fromDate }} &nbsp;→&nbsp; {{ $toDate }}
            @endif
        </caption>

        <thead class="cash-head">
            <tr>
                <th>Date</th>
                <th>No</th>
                <th>Transaction</th>
                <th>DR Amount</th>
                <th>CR Amount</th>
            </tr>
        </thead>

        <tbody>
            {{-- Opening Balance --}}
            <tr class="opening-row">
                <td>{{ $fromDate }}</td>
                <td>-</td>
                <td><i class="fa-solid fa-lock-open"></i> Opening Balance (Cash)</td>
                <td>{{ $cashOpeningBalanceFmt }}</td>
                <td>0.00</td>
            </tr>

            @forelse($cashInvoice as $inv)
            <tr>
                <td>{{ $inv->Ddate }}</td>
                <td>{{ $inv->trance_no }}</td>
                   <td>{{ $inv->trance_type }}-{{ $inv->Description }}</td>
                <td>{{ $inv->dr_amount }}</td>
                <td>{{ $inv->cr_amount }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No cash transactions found.</td>
            </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td>DR Total:</td>
                <td>CR Total:</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td>{{ $cashTotalDrFmt }}</td>
                <td>{{ $cashTotalCrFmt }}</td>
            </tr>
            <tr class="balance-row">
                <td colspan="3">Cash Balance (DR − CR)</td>
                <td colspan="2">
                    <span class="{{ str_starts_with($cashBalanceFmt, '-') ? 'negative-balance' : 'positive-balance' }}">
                        {{ $cashBalanceFmt }}
                    </span>
                </td>
            </tr>
        </tfoot>
    </table>


    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- ── SECTION 2 : CHEQUE (201-123) ── --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="section-header-cheque">
        <i class="fa-solid fa-money-check"></i>&nbsp; Cheque In Hand &nbsp;
        <small style="opacity:.8; font-size:12px;">(AccCode: 201-123)</small>
    </div>

    <table id="cheque_table">
        <caption style="font-size:15px; font-weight:bold; caption-side:top; text-align:center;">
            Cheque Transactions
            @if($fromDate && $toDate)
                &nbsp;|&nbsp; {{ $fromDate }} &nbsp;→&nbsp; {{ $toDate }}
            @endif
        </caption>

        <thead class="cheque-head">
            <tr>
                <th>Date</th>
                <th>No</th>
                <th>Transaction</th>
                <th>DR Amount</th>
                <th>CR Amount</th>
            </tr>
        </thead>

        <tbody>
            {{-- Opening Balance --}}
            <tr class="opening-row">
                <td>{{ $fromDate }}</td>
                <td>-</td>
                <td><i class="fa-solid fa-lock-open"></i> Opening Balance (Cheque)</td>
                <td>{{ $chequeOpeningBalanceFmt }}</td>
                <td>0.00</td>
            </tr>

            @forelse($chequeInvoice as $inv)
            <tr>
                <td>{{ $inv->Ddate }}</td>
                <td>{{ $inv->trance_no }}</td>
                <td>{{ $inv->trance_type }}-{{ $inv->Description }}</td>
                <td>{{ $inv->dr_amount }}</td>
                <td>{{ $inv->cr_amount }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No cheque transactions found.</td>
            </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td>DR Total:</td>
                <td>CR Total:</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td>{{ $chequeTotalDrFmt }}</td>
                <td>{{ $chequeTotalCrFmt }}</td>
            </tr>
            <tr class="balance-row">
                <td colspan="3">Cheque Balance (DR − CR)</td>
                <td colspan="2">
                    <span class="{{ str_starts_with($chequeBalanceFmt, '-') ? 'negative-balance' : 'positive-balance' }}">
                        {{ $chequeBalanceFmt }}
                    </span>
                </td>
            </tr>
        </tfoot>
    </table>


    {{-- ════════════════════════════════════════════════════════════════ --}}
    {{-- ── COMBINED SUMMARY ── --}}
    {{-- ════════════════════════════════════════════════════════════════ --}}
    <div class="combined-box">
        <h5><i class="fa-solid fa-calculator"></i> Combined Balance Summary</h5>
        <div class="combined-grid">
            <div class="combined-card cash-card">
                <div class="label"><i class="fa-solid fa-money-bill-wave"></i> Cash Balance</div>
                <div class="value {{ str_starts_with($cashBalanceFmt, '-') ? 'negative-balance' : '' }}">
                    {{ $cashBalanceFmt }}
                </div>
                <small class="text-muted">AccCode: 201-001</small>
            </div>
            <div class="combined-card cheque-card">
                <div class="label"><i class="fa-solid fa-money-check"></i> Cheque Balance</div>
                <div class="value {{ str_starts_with($chequeBalanceFmt, '-') ? 'negative-balance' : '' }}">
                    {{ $chequeBalanceFmt }}
                </div>
                <small class="text-muted">AccCode: 201-123</small>
            </div>
            <div class="combined-card total-card">
                <div class="label"><i class="fa-solid fa-sigma"></i> Total (Cash + Cheque)</div>
                <div class="value {{ str_starts_with($combinedBalanceFmt, '-') ? 'negative-balance' : '' }}">
                    {{ $combinedBalanceFmt }}
                </div>
                <small class="text-muted">Combined</small>
            </div>
        </div>
    </div>


    {{-- ── Day End Close Panel ── --}}
    <div class="day-end-panel no-print">
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
            <form action="{{ route('CashandChequeTransaction.dayEndClose') }}" method="POST"
                  onsubmit="return confirmClose()">
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

</div>{{-- /.report-wrapper --}}

{{-- ── Scripts ── --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

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
    var cash   = document.getElementById("cash_table").outerHTML;
    var cheque = document.getElementById("cheque_table").outerHTML;
    var newWin = window.open("");
    newWin.document.write('<html><head><title>Cash In Hand Report</title>');
    newWin.document.write('<style>table{border-collapse:collapse;width:100%;margin-bottom:20px}th,td{border:1px solid #333;padding:6px}.negative-balance{color:red}.positive-balance{color:green}h3{margin-top:20px}</style>');
    newWin.document.write('</head><body>');
    newWin.document.write('<h3>Cash (201-001)</h3>' + cash);
    newWin.document.write('<h3>Cheque (201-123)</h3>' + cheque);
    newWin.document.write('</body></html>');
    newWin.print();
    newWin.close();
}

function confirmClose() {
    return confirm("Are you sure you want to close the day end?\n\nThis will save the closing balances (Cash + Cheque) as tomorrow's opening balances.");
}

document.getElementById('to_date').value = new Date().toISOString().slice(0, 10);
</script>

</body>
</html>
