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
                <style>
                    .dt-section-head {
                        display: flex; align-items: center; justify-content: space-between;
                        padding: 14px 18px; border-bottom: 1px solid #E5EAF2;
                    }
                    .dt-section-title { font-size: 15px; font-weight: 700; color: #14213D; margin: 0; }
                    .dt-section-sub { font-size: 12px; color: #667085; margin: 2px 0 0; }
                    .dt-section-total { font-size: 13px; font-weight: 700; text-align: right; }
                    .dt-section-total .ok   { color: #16A34A; }
                    .dt-section-total .warn { color: #DC2626; }
                    .dt-card { margin-bottom: 22px; }
                    .dt-card.gl  { border-left: 4px solid #1D4ED8; }
                    .dt-card.sub { border-left: 4px solid #6D28D9; }
                    .dt-card.bank{ border-left: 4px solid #64748B; }
                </style>
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
                                <p class="page-subtitle">Every financial transaction across all accounts, split into three separate lists below — each is its own self-contained record, not meant to total against the others. For Cash/Cheque In Hand only, see the Cash Book report.</p>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid px-0">
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

                        {{-- ── 1. General Ledger ───────────────────────────────── --}}
                        <div class="card dt-card gl">
                            <div class="dt-section-head">
                                <div>
                                    <p class="dt-section-title">General Ledger</p>
                                    <p class="dt-section-sub">Real double-entry transactions — sales, payments, vouchers, cheques. DR and CR below should always match.</p>
                                </div>
                                <div class="dt-section-total">
                                    DR {{ number_format((float) $glTotalDr, 2) }} &nbsp; / &nbsp; CR {{ number_format((float) $glTotalCr, 2) }}
                                    @if(abs($glTotalDr - $glTotalCr) < 0.01)
                                        <span class="ok"><i class="fas fa-check-circle"></i> Balanced</span>
                                    @else
                                        <span class="warn"><i class="fas fa-triangle-exclamation"></i> Out of balance</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width:9%;">Date</th>
                                                <th style="width:16%;">Reference</th>
                                                <th>Description / Account</th>
                                                <th style="width:12%;" class="text-end">DR Amount</th>
                                                <th style="width:12%;" class="text-end">CR Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($glRows as $row)
                                            <tr>
                                                <td>{{ $row->Ddate }}</td>
                                                <td>
                                                    @if($row->reference_url)
                                                        <a href="{{ $row->reference_url }}" target="_blank">{{ $row->reference_label }}</a>
                                                    @else
                                                        {{ $row->reference_label }}
                                                    @endif
                                                </td>
                                                <td>{{ $row->Description }} @if($row->logic_summary)<br><small class="text-muted">{{ $row->logic_summary }}</small>@endif</td>
                                                <td class="text-end">{{ number_format((float) $row->dr_amount, 2) }}</td>
                                                <td class="text-end">{{ number_format((float) $row->cr_amount, 2) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No general ledger transactions for the selected dates.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- ── 2. Supplier Ledger ──────────────────────────────── --}}
                        <div class="card dt-card sub">
                            <div class="dt-section-head">
                                <div>
                                    <p class="dt-section-title">Supplier Ledger</p>
                                    <p class="dt-section-sub">Purchases invoiced (DR) and payments made (CR) to suppliers. These two columns aren't expected to match on any single day.</p>
                                </div>
                                <div class="dt-section-total">
                                    DR {{ number_format((float) $subTotalDr, 2) }} &nbsp; / &nbsp; CR {{ number_format((float) $subTotalCr, 2) }}
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width:9%;">Date</th>
                                                <th style="width:16%;">Reference</th>
                                                <th>Supplier</th>
                                                <th style="width:12%;" class="text-end">DR Amount</th>
                                                <th style="width:12%;" class="text-end">CR Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($subRows as $row)
                                            <tr>
                                                <td>{{ $row->Ddate }}</td>
                                                <td>
                                                    @if($row->reference_url)
                                                        <a href="{{ $row->reference_url }}" target="_blank">{{ $row->reference_label }}</a>
                                                    @else
                                                        {{ $row->reference_label }}
                                                    @endif
                                                </td>
                                                <td>{{ $row->logic_summary }}</td>
                                                <td class="text-end">{{ number_format((float) $row->dr_amount, 2) }}</td>
                                                <td class="text-end">{{ number_format((float) $row->cr_amount, 2) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No supplier purchases/payments for the selected dates.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- ── 3. Banking Actions ──────────────────────────────── --}}
                        <div class="card dt-card bank">
                            <div class="dt-section-head">
                                <div>
                                    <p class="dt-section-title">Banking Actions</p>
                                    <p class="dt-section-sub">Cheque issued / deposited / returned, and day-end closes — shown for visibility only. The actual money movement for these is already recorded above, in the General Ledger.</p>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width:9%;">Date</th>
                                                <th style="width:16%;">Action</th>
                                                <th>Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($bankingRows as $row)
                                            <tr>
                                                <td>{{ $row->Ddate }}</td>
                                                <td>{{ $row->reference_label }}</td>
                                                <td>{{ $row->Description }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">No banking actions for the selected dates.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
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
