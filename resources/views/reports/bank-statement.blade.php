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
    <title>Bank Statement — {{ $bank->bank_name }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        .bs-scope {
            --bs-blue:   #1677FF;
            --bs-green:  #16A34A;
            --bs-red:    #EF4444;
            --bs-navy:   #14213D;
            --bs-gray:   #667085;
            --bs-border: #E5EAF2;
        }
        .bs-summary-row { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 20px; }
        .bs-summary-card {
            background: #fff; border: 1px solid var(--bs-border); border-radius: 12px;
            padding: 18px 22px; flex: 1; min-width: 190px;
        }
        .bs-summary-card.highlight {
            border: 1.5px solid var(--bs-blue);
            background: linear-gradient(135deg, #EAF3FF 0%, #fff 100%);
        }
        .bs-summary-label {
            font-size: 11.5px; text-transform: uppercase; letter-spacing: .06em;
            color: var(--bs-gray); margin-bottom: 8px; display: flex; align-items: center; gap: 7px;
        }
        .bs-summary-value { font-size: 24px; font-weight: 800; line-height: 1; color: var(--bs-navy); }
        .bs-summary-value.green { color: var(--bs-green); }
        .bs-summary-value.red   { color: var(--bs-red); }
        .bs-summary-sub { font-size: 11.5px; color: var(--bs-gray); margin-top: 6px; }

        #bankStatementTable tr.opening-row td { background: #FFF8E1 !important; font-weight: 700; color: var(--bs-navy); }
        #bankStatementTable tfoot .balance-row td { background: #EAF3FF !important; font-weight: 800; font-size: 14px; }
        .negative-balance { color: var(--bs-red); }
        .positive-balance { color: var(--bs-green); }
    </style>
</head>

<body>

<div class="main-wrapper">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header ph-flex">
                <div class="ph-left">
                    <div class="ph-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div>
                        <h3 class="page-title">Bank Statement — {{ $bank->bank_name }}</h3>
                        <p class="page-subtitle">{{ $bank->branch }} · A/C {{ $bank->account_no }} — opening amount, every cheque movement, and the running balance for this bank account.</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" onclick="printStatement()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <a href="{{ route('ChequeBanks') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> Back to Banks</a>
                </div>
            </div>

            <div class="container-fluid px-0 bs-scope">
                <div class="card">
                    <div class="card-body">

                        {{-- ── Balance Summary Strip ── --}}
                        <div class="bs-summary-row">
                            <div class="bs-summary-card">
                                <div class="bs-summary-label"><i class="fa-solid fa-lock-open"></i> Opening Balance</div>
                                <div class="bs-summary-value">{{ number_format($periodOpening, 2) }}</div>
                                <div class="bs-summary-sub">{{ $fromDate ? 'as of ' . $fromDate : 'since account was added' }}</div>
                            </div>
                            <div class="bs-summary-card">
                                <div class="bs-summary-label"><i class="fa-solid fa-arrow-down"></i> Total In (DR)</div>
                                <div class="bs-summary-value green">{{ number_format($sumDr, 2) }}</div>
                                <div class="bs-summary-sub">for the selected period</div>
                            </div>
                            <div class="bs-summary-card">
                                <div class="bs-summary-label"><i class="fa-solid fa-arrow-up"></i> Total Out (CR)</div>
                                <div class="bs-summary-value red">{{ number_format($sumCr, 2) }}</div>
                                <div class="bs-summary-sub">for the selected period</div>
                            </div>
                            <div class="bs-summary-card highlight">
                                <div class="bs-summary-label"><i class="fa-solid fa-wallet"></i> Closing Balance</div>
                                <div class="bs-summary-value {{ $closingBalance < 0 ? 'red' : 'green' }}">{{ number_format($closingBalance, 2) }}</div>
                                <div class="bs-summary-sub">Opening + DR − CR</div>
                            </div>
                        </div>

                        <form action="" method="GET" id="bankStatementFilterForm" class="row g-2 mb-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label mb-0 small">From Date</label>
                                <input type="date" class="form-control" name="from_date" value="{{ $fromDate }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 small">To Date</label>
                                <input type="date" class="form-control" name="to_date" value="{{ $toDate }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-magnifying-glass"></i> Search</button>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table id="bankStatementTable" class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>DR Amount</th>
                                        <th>CR Amount</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="opening-row">
                                        <td>{{ $fromDate ?: optional($bank->created_at)->format('Y-m-d') ?? '-' }}</td>
                                        <td><i class="fa-solid fa-lock-open"></i> Opening Balance</td>
                                        <td></td>
                                        <td>{{ number_format($periodOpening, 2) }}</td>
                                        <td>0.00</td>
                                        <td>{{ number_format($periodOpening, 2) }}</td>
                                    </tr>
                                    @forelse($movements as $row)
                                    <tr>
                                        <td>{{ $row['date'] }}</td>
                                        <td>{{ $row['description'] }}</td>
                                        <td>
                                            @if($row['status'] === 'RETURNED' || $row['status'] === 'return')
                                                <span class="badge bg-secondary">{{ $row['status_label'] }} (no net effect)</span>
                                            @elseif($row['status'])
                                                <span class="badge bg-info text-dark">{{ $row['status_label'] }}</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($row['dr'], 2) }}</td>
                                        <td>{{ number_format($row['cr'], 2) }}</td>
                                        <td>{{ number_format($row['balance'], 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No movements found for the selected period.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end">Total</td>
                                        <td>{{ number_format($sumDr, 2) }}</td>
                                        <td>{{ number_format($sumCr, 2) }}</td>
                                        <td></td>
                                    </tr>
                                    <tr class="balance-row">
                                        <td colspan="5" class="text-end">Closing Balance</td>
                                        <td class="{{ $closingBalance < 0 ? 'negative-balance' : 'positive-balance' }}">
                                            {{ number_format($closingBalance, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footer')
    </div>
</div>

<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/feather.min.js') }}"></script>
<script src="{{ asset('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>

<script>
function printStatement() {
    var params = $('#bankStatementFilterForm').serialize();
    window.open('{{ route('bank.statement.print', $bank->id) }}?' + params, '_blank');
}
</script>

</body>
@endsection

</html>
