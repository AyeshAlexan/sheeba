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
            max-width: 960px;
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

        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #333; padding: 6px 8px; }
        thead th { background-color: rgb(113, 105, 255); color: #fff; text-align: center; }

        /* Opening balance row highlight */
        tr.opening-row td { background-color: #fff8dc; font-weight: bold; }

        /* Day-end close panel */
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

        tfoot td { font-weight: bold; }
        .balance-row { background-color: #e3f2fd; }
        .negative-balance { color: #dc3545; }
        .positive-balance { color: #198754; }

        @media print {
            .no-print { display: none !important; }
            .report-wrapper { box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="report-wrapper">

    {{-- ── Title ── --}}
    <h2 class="report-title"><b>Cash In Hand Report</b></h2>
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

    {{-- ── Main Table ── --}}
    <table id="T_account_trans" style="margin-top:10px;">
        <caption style="font-size:16px; font-weight:bold; caption-side:top; text-align:center;">
            Cash In Hand Report
            @if($fromDate && $toDate)
                &nbsp;|&nbsp; From: {{ $fromDate }} &nbsp; To: {{ $toDate }}
            @endif
        </caption>

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
            {{-- ── Opening Balance Row ── --}}
            <tr class="opening-row">
                <td>{{ $fromDate }}</td>
                <td>-</td>
                <td><i class="fa-solid fa-lock-open"></i> Opening Balance</td>
                <td id="totalBalance">{{ $openingBalanceFmt }}</td>
                <td>0.00</td>
            </tr>

            {{-- ── Transactions ── --}}
            @forelse($invoice as $inv)
            <tr>
                <td>{{ $inv->Ddate }}</td>
                <td>{{ $inv->trance_no }}</td>
                <td>{{ $inv->trance_type }}</td>
                <td>{{ $inv->dr_amount }}</td>
                <td>{{ $inv->cr_amount }}</td>

            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No transactions found.</td>
            </tr>
            @endforelse
        </tbody>

        <tfoot>
            {{-- DR / CR label row --}}
            <tr>
                <td colspan="3">Total</td>
                <td>DR Total:</td>
                <td>CR Total:</td>
            </tr>
            {{-- DR / CR amount row --}}
            <tr>
                <td colspan="3"></td>
                <td id="totalDrAmount">{{ $totalDrAmount }}</td>
                <td id="totalCrAmount">{{ $totalCrAmount }}</td>
            </tr>
            {{-- Balance row: CR - DR --}}
            <tr class="balance-row">
                <td colspan="3">Balance Total: (DR - CR)</td>
                <td colspan="2">
                    <span id="totalBalance"
                          class="{{ str_starts_with($totalBalance, '-') ? 'negative-balance' : 'positive-balance' }}">
                        {{ $totalBalance }}
                    </span>
                </td>
            </tr>
        </tfoot>
    </table>

    {{-- ── Day End Close Panel ── --}}
    <div class="day-end-panel no-print">
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
                <strong
                    class="{{ str_starts_with($totalBalance, '-') ? 'negative-balance' : 'positive-balance' }}">
                    {{ $totalBalance }}
                </strong>
                as the opening balance for the next day.
            </p>
            <form action="{{ route('cashInHand.dayEndClose') }}" method="POST"
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
    var table = $('#T_account_trans').DataTable({
        dom: 'Bfrtip',
        paging: false,
        buttons: ['copy', 'excel', 'csv', 'pdf'],
    });


});

// Print function
function printTablefun() {
    var divToPrint = document.getElementById("T_account_trans");
    var newWin = window.open("");
    newWin.document.write('<html><head><title>Cash In Hand Report</title>');
    newWin.document.write('<style>table{border-collapse:collapse;width:100%}th,td{border:1px solid #333;padding:6px}.negative-balance{color:red}.positive-balance{color:green}</style>');
    newWin.document.write('</head><body>');
    newWin.document.write(divToPrint.outerHTML);
    newWin.document.write('</body></html>');
    newWin.print();
    newWin.close();
}

// Confirm before day end close
function confirmClose() {
    return confirm("Are you sure you want to close the day end?\n\nThis will save the closing balance as tomorrow's opening balance.");
}

// Default to_date = today
document.getElementById('to_date').value = new Date().toISOString().slice(0, 10);
</script>

</body>
</html>