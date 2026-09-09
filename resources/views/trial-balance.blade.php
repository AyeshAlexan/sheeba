

<style>
.tb-page { background: #f8f9fb; min-height: 100vh; padding: 2rem 1.5rem; }
.tb-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
.tb-header h4 { font-size: 20px; font-weight: 600; color: #1a1a2e; margin: 0; }
.tb-header small { font-size: 13px; color: #6b7280; }
.tb-card { background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; padding: 1.25rem 1.5rem; margin-bottom: 1.25rem; }
.tb-filter { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; }
.tb-filter .field { display: flex; flex-direction: column; gap: 4px; }
.tb-filter label { font-size: 12px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: .04em; }
.tb-filter input { height: 36px; border: 1px solid #d1d5db; border-radius: 8px; padding: 0 10px; font-size: 13px; color: #111827; background: #fff; outline: none; }
.tb-filter input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.btn-gen { height: 36px; padding: 0 20px; background: #6366f1; color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; }
.btn-gen:hover { background: #4f46e5; }
.btn-print { height: 36px; padding: 0 16px; background: #fff; color: #374151; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; cursor: pointer; display: flex; align-items: center; gap: 6px; }
.btn-print:hover { background: #f9fafb; }
.summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px; margin-bottom: 1.25rem; }
.summary-card { background: #fff; border-radius: 10px; border: 1px solid #e5e7eb; padding: .85rem 1rem; }
.summary-card .s-label { font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: .05em; color: #9ca3af; margin-bottom: 4px; }
.summary-card .s-value { font-size: 18px; font-weight: 600; color: #111827; }
.summary-card .s-sub { font-size: 11px; color: #6b7280; margin-top: 2px; }
.summary-card.s-green { border-left: 3px solid #10b981; }
.summary-card.s-red   { border-left: 3px solid #ef4444; }
.summary-card.s-blue  { border-left: 3px solid #6366f1; }
.summary-card.s-amber { border-left: 3px solid #f59e0b; }
.dayend-bar { display: flex; flex-wrap: wrap; gap: 16px; align-items: center; padding: .75rem 1.25rem; border-radius: 10px; margin-bottom: 1.25rem; font-size: 13px; }
.dayend-bar.matched   { background: #ecfdf5; border: 1px solid #6ee7b7; color: #065f46; }
.dayend-bar.mismatched{ background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }
.dayend-bar.norecord  { background: #fffbeb; border: 1px solid #fcd34d; color: #92400e; }
.dayend-bar .d-item { display: flex; flex-direction: column; gap: 1px; }
.dayend-bar .d-item span:first-child { font-size: 11px; font-weight: 500; text-transform: uppercase; letter-spacing: .04em; opacity: .7; }
.dayend-bar .d-item span:last-child  { font-weight: 600; font-size: 14px; }
.dayend-bar .check-badge { margin-left: auto; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.dayend-bar.matched   .check-badge { background: #d1fae5; color: #065f46; }
.dayend-bar.mismatched .check-badge { background: #fee2e2; color: #991b1b; }
.tb-table-wrap { overflow-x: auto; border-radius: 10px; border: 1px solid #e5e7eb; }
.tb-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.tb-table thead tr:first-child th { background: #1e1b4b; color: #fff; font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: .04em; padding: 10px 12px; text-align: center; white-space: nowrap; }
.tb-table thead tr:first-child th.text-left { text-align: left; }
.tb-table thead tr:second-child th,
.tb-table thead tr:nth-child(2) th { background: #312e81; color: #c7d2fe; font-size: 11px; font-weight: 500; text-align: right; padding: 7px 12px; }
.tb-table tbody tr { border-bottom: 1px solid #f3f4f6; }
.tb-table tbody tr:hover { background: #f9fafb; }
.tb-table tbody td { padding: 8px 12px; color: #111827; white-space: nowrap; }
.tb-table tbody td.num { text-align: right; font-variant-numeric: tabular-nums; color: #374151; }
.tb-table tbody td.num.closing { font-weight: 600; color: #111827; }
.tb-table tbody td.zero { text-align: right; color: #d1d5db; }
.tb-table tfoot tr.total-row td { background: #f0f4ff; font-weight: 600; font-size: 13px; color: #1e1b4b; padding: 10px 12px; border-top: 2px solid #c7d2fe; }
.tb-table tfoot tr.total-row td.num { text-align: right; font-variant-numeric: tabular-nums; }
.tb-table tfoot tr.balance-row td { padding: 8px 12px; text-align: right; background: #fff; }
.badge-type { display: inline-block; padding: 2px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.ba { background: #eff6ff; color: #1d4ed8; }
.bl { background: #fffbeb; color: #92400e; }
.be { background: #f0fdf4; color: #166534; }
.bi { background: #ecfeff; color: #0e7490; }
.bx { background: #fef2f2; color: #991b1b; }
.bs { background: #f9fafb; color: #374151; }
.balanced-badge   { background: #d1fae5; color: #065f46; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.unbalanced-badge { background: #fee2e2; color: #991b1b; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.tb-sep { width: 1px; background: rgba(255,255,255,.15); margin: 0 4px; }
@media print {
  .tb-filter, .btn-print { display: none !important; }
  .tb-page { background: #fff; padding: 0; }
  .tb-card, .tb-table-wrap { border: none; border-radius: 0; }
  .tb-table thead tr:first-child th { background: #1e1b4b !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>


<div class="tb-page">

  <!-- Header -->
  <div class="tb-header">
    <h4>Trial Balance</h4>
    <small>Branch <strong>{{ $bc }}</strong> &nbsp;&middot;&nbsp;
      {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
      &nbsp;&ndash;&nbsp;
      {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
    </small>
  </div>

  <!-- Filter card -->
  <div class="tb-card">
    <form method="GET" action="{{ route('trial-balance.index') }}">
      <div class="tb-filter">
        <div class="field">
          <label>Branch (BC)</label>
          <input type="text" name="bc" value="{{ $bc }}" style="width:100px">
        </div>
        <div class="field">
          <label>Start Date</label>
          <input type="date" name="start_date" value="{{ $startDate }}">
        </div>
        <div class="field">
          <label>End Date</label>
          <input type="date" name="end_date" value="{{ $endDate }}">
        </div>
        <button type="submit" class="btn-gen">Generate</button>
        @if(count($results) > 0)
        <button type="button" class="btn-print" onclick="window.print()">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z"/></svg>
          Print
        </button>
        @endif
      </div>
    </form>
  </div>

  <!-- Summary cards -->
  @if(count($results) > 0)
  <div class="summary-grid">
    <div class="summary-card s-blue">
      <div class="s-label">Opening Dr</div>
      <div class="s-value">{{ number_format($totals['ob_dr'], 2) }}</div>
    </div>
    <div class="summary-card s-amber">
      <div class="s-label">Opening Cr</div>
      <div class="s-value">{{ number_format($totals['ob_cr'], 2) }}</div>
    </div>
    <div class="summary-card s-blue">
      <div class="s-label">Period Dr</div>
      <div class="s-value">{{ number_format($totals['period_dr'], 2) }}</div>
    </div>
    <div class="summary-card s-amber">
      <div class="s-label">Period Cr</div>
      <div class="s-value">{{ number_format($totals['period_cr'], 2) }}</div>
    </div>
    <div class="summary-card s-green">
      <div class="s-label">Closing Dr</div>
      <div class="s-value">{{ number_format($totals['closing_dr'], 2) }}</div>
    </div>
    <div class="summary-card s-red">
      <div class="s-label">Closing Cr</div>
      <div class="s-value">{{ number_format($totals['closing_cr'], 2) }}</div>
    </div>
  </div>
  @endif

  <!-- Day-end cross-check bar -->
  @if($dayEnd)
    @php $barClass = $crossCheck ? 'matched' : 'mismatched'; @endphp
    <div class="dayend-bar {{ $barClass }}">
      <div class="d-item">
        <span>Last day-end</span>
        <span>{{ \Carbon\Carbon::parse($dayEnd->close_date)->format('d M Y') }}</span>
      </div>
      <div class="tb-sep"></div>
      <div class="d-item">
        <span>Cash</span>
        <span>{{ number_format($dayEnd->cash_closing_balance, 2) }}</span>
      </div>
      <div class="d-item">
        <span>Cheque</span>
        <span>{{ number_format($dayEnd->cheque_closing_balance, 2) }}</span>
      </div>
      <div class="d-item">
        <span>Day-end closing</span>
        <span>{{ number_format($dayEnd->closing_balance, 2) }}</span>
      </div>
      <div class="d-item">
        <span>Day-end Dr</span>
        <span>{{ number_format($dayEnd->total_dr, 2) }}</span>
      </div>
      <div class="d-item">
        <span>Day-end Cr</span>
        <span>{{ number_format($dayEnd->total_cr, 2) }}</span>
      </div>
      <div class="d-item">
        <span>TB net</span>
        <span>{{ number_format($tbNet, 2) }}</span>
      </div>
      <div class="check-badge">
        @if($crossCheck)
          ✓ Matches day-end
        @else
          ✗ Mismatch by {{ number_format(abs($tbNet - $dayEndNet), 2) }}
        @endif
      </div>
    </div>
  @else
    <div class="dayend-bar norecord">
      No day-end record found before {{ $startDate }} for branch {{ $bc }}.
    </div>
  @endif

  <!-- Trial balance table -->
  <div class="tb-table-wrap">
    <table class="tb-table">
      <thead>
        <tr>
          <th rowspan="2" class="text-left">Code</th>
          <th rowspan="2" class="text-left">Account</th>
          <th rowspan="2" class="text-left">Sub</th>
          <th rowspan="2" class="text-left">Description</th>
          <th rowspan="2">Type</th>
          <th colspan="2">Opening Balance</th>
          <th colspan="2">Period Movement</th>
          <th colspan="2">Closing Balance</th>
        </tr>
        <tr>
          <th>Dr</th><th>Cr</th>
          <th>Dr</th><th>Cr</th>
          <th>Dr</th><th>Cr</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($results as $row)
        <tr>
          <td>{{ $row->code }}</td>
          <td>{{ $row->account }}</td>
          <td>{{ $row->accountsub }}</td>
          <td>{{ $row->description }}</td>
          <td style="text-align:center">
            @php
              $cls = match(strtolower($row->controlaccount)) {
                'asset'     => 'ba',
                'liability' => 'bl',
                'equity'    => 'be',
                'income'    => 'bi',
                'expense'   => 'bx',
                default     => 'bs',
              };
            @endphp
            <span class="badge-type {{ $cls }}">{{ $row->controlaccount }}</span>
          </td>
          <td class="{{ $row->ob_dr > 0 ? 'num' : 'zero' }}">{{ $row->ob_dr > 0 ? number_format($row->ob_dr, 2) : '-' }}</td>
          <td class="{{ $row->ob_cr > 0 ? 'num' : 'zero' }}">{{ $row->ob_cr > 0 ? number_format($row->ob_cr, 2) : '-' }}</td>
          <td class="{{ $row->period_dr > 0 ? 'num' : 'zero' }}">{{ $row->period_dr > 0 ? number_format($row->period_dr, 2) : '-' }}</td>
          <td class="{{ $row->period_cr > 0 ? 'num' : 'zero' }}">{{ $row->period_cr > 0 ? number_format($row->period_cr, 2) : '-' }}</td>
          <td class="{{ $row->closing_dr > 0 ? 'num closing' : 'zero' }}">{{ $row->closing_dr > 0 ? number_format($row->closing_dr, 2) : '-' }}</td>
          <td class="{{ $row->closing_cr > 0 ? 'num closing' : 'zero' }}">{{ $row->closing_cr > 0 ? number_format($row->closing_cr, 2) : '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="11" style="text-align:center;color:#9ca3af;padding:2rem">
          No records found for the selected period and branch.
        </td></tr>
        @endforelse
      </tbody>
      @if(count($results) > 0)
      <tfoot>
        <tr class="total-row">
          <td colspan="5">Total</td>
          <td class="num">{{ number_format($totals['ob_dr'], 2) }}</td>
          <td class="num">{{ number_format($totals['ob_cr'], 2) }}</td>
          <td class="num">{{ number_format($totals['period_dr'], 2) }}</td>
          <td class="num">{{ number_format($totals['period_cr'], 2) }}</td>
          <td class="num">{{ number_format($totals['closing_dr'], 2) }}</td>
          <td class="num">{{ number_format($totals['closing_cr'], 2) }}</td>
        </tr>
        @php
          $balanced = abs($totals['closing_dr'] - $totals['closing_cr']) < 0.01;
        @endphp
        <tr class="balance-row">
          <td colspan="11">
            @if($balanced)
              <span class="balanced-badge">✓ Balanced</span>
            @else
              <span class="unbalanced-badge">
                ✗ Out of balance by
                {{ number_format(abs($totals['closing_dr'] - $totals['closing_cr']), 2) }}
              </span>
            @endif
          </td>
        </tr>
      </tfoot>
      @endif
    </table>
  </div>

</div>