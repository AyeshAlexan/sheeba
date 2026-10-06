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
                <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
                <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
                <title>Customer Cheque Payment Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">

                <style>
                    /* Namespaced to the app's palette — same hex values used
                       everywhere else, just kept under these variable names
                       since the rest of this page's CSS already references
                       them extensively. */
                    .cq-scope {
                        --indigo:       #1677FF;
                        --indigo-dark:  #0E5FE0;
                        --indigo-light: #EAF3FF;
                        --indigo-mid:   #BFDBFE;
                        --green:        #16A34A;
                        --green-light:  #EAFBEF;
                        --green-dark:   #128A3E;
                        --amber:        #F59E0B;
                        --amber-light:  #FEF9E7;
                        --amber-dark:   #7A5B08;
                        --red:          #EF4444;
                        --red-light:    #FEECEC;
                        --red-dark:     #991B1B;
                        --gray-50:      #F6F8FC;
                        --gray-100:     #F6F8FC;
                        --gray-200:     #E5EAF2;
                        --gray-300:     #D1D5DB;
                        --gray-400:     #98A2B3;
                        --gray-500:     #667085;
                        --gray-600:     #4B5563;
                        --gray-700:     #374151;
                        --gray-800:     #14213D;
                        --white:        #ffffff;
                        --radius-sm:    6px;
                        --radius-md:    10px;
                        --radius-lg:    14px;
                        font-family: 'Inter', 'Segoe UI', sans-serif;
                    }

                    /* ─── Summary strip ─── */
                    .cq-summary-strip {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                        gap: 14px;
                        margin-bottom: 20px;
                    }
                    .cq-stat-tile {
                        background: var(--white);
                        border: 1px solid var(--gray-200);
                        border-radius: var(--radius-md);
                        padding: 16px 18px;
                    }
                    .cq-stat-tile .s-label {
                        font-size: 11px; font-weight: 600; text-transform: uppercase;
                        letter-spacing: .06em; color: var(--gray-500); margin-bottom: 8px;
                        display: flex; align-items: center; gap: 6px;
                    }
                    .cq-stat-tile .s-value { font-size: 1.4rem; font-weight: 700; color: var(--gray-800); }
                    .cq-stat-tile .s-value.indigo { color: var(--indigo); }
                    .cq-stat-tile .s-value.green  { color: var(--green); }
                    .cq-stat-tile .s-value.amber  { color: var(--amber); }
                    .cq-stat-tile .s-value.red    { color: var(--red); }

                    /* ─── Buttons (namespaced — avoids colliding with Bootstrap's / the app's own .btn-* classes) ─── */
                    .cq-btn {
                        display: inline-flex; align-items: center; gap: 7px;
                        padding: 9px 20px; border-radius: var(--radius-sm);
                        font-size: 0.86rem; font-weight: 600; font-family: inherit;
                        border: none; cursor: pointer; text-decoration: none; white-space: nowrap;
                        transition: all .18s;
                    }
                    .cq-btn-primary { background: var(--indigo); color: #fff; }
                    .cq-btn-primary:hover { background: var(--indigo-dark); }
                    .cq-btn-neutral { background: var(--gray-600); color: #fff; }
                    .cq-btn-neutral:hover { background: var(--gray-700); }
                    .cq-btn-sm { padding: 6px 13px; font-size: 0.78rem; }
                    .cq-btn-action { background: var(--indigo-light); color: var(--indigo); border: 1.5px solid var(--indigo-mid); }
                    .cq-btn-action:hover { background: var(--indigo); color: #fff; border-color: var(--indigo); }
                    .cq-btn-done { background: var(--green-light); color: var(--green-dark); border: 1.5px solid #6ee7b7; cursor: not-allowed; }
                    .cq-btn-returned { background: var(--red-light); color: var(--red-dark); border: 1.5px solid #fca5a5; cursor: not-allowed; }

                    /* ─── Status badges (namespaced — avoids colliding with Bootstrap's .badge) ─── */
                    .cq-badge {
                        display: inline-flex; align-items: center; gap: 5px;
                        padding: 4px 11px; border-radius: 20px;
                        font-size: 0.72rem; font-weight: 700; white-space: nowrap;
                    }
                    .cq-badge-pending { background: var(--gray-100); color: var(--gray-600); border: 1px solid var(--gray-300); }
                    .cq-badge-deposit { background: var(--green-light); color: var(--green-dark); border: 1px solid #6ee7b7; }
                    .cq-badge-return  { background: var(--red-light); color: var(--red-dark); border: 1px solid #fca5a5; }

                    /* ─── Highlight today/future release rows ─── */
                    .highlight-today { background: #fffbeb !important; }
                    .highlight-today:hover td { background: #fef9e4 !important; }

                    /* ─── MODAL (namespaced — avoids colliding with Bootstrap's .modal/.modal-backdrop) ─── */
                    .cq-modal-backdrop {
                        display: none; position: fixed; inset: 0;
                        background: rgba(15, 23, 42, 0.55); z-index: 9000;
                        backdrop-filter: blur(3px); justify-content: center; align-items: center; padding: 20px;
                    }
                    .cq-modal-backdrop.active { display: flex; }
                    .cq-modal {
                        background: var(--white); border-radius: var(--radius-lg);
                        width: 540px; max-width: 100%;
                        box-shadow: 0 20px 60px rgba(0,0,0,.18);
                        animation: cqModalIn .22s cubic-bezier(.34,1.56,.64,1);
                        overflow: hidden;
                    }
                    @keyframes cqModalIn {
                        from { transform: scale(.88) translateY(20px); opacity: 0; }
                        to   { transform: scale(1)  translateY(0);     opacity: 1; }
                    }
                    .cq-modal-header {
                        padding: 20px 24px 16px; border-bottom: 1px solid var(--gray-200);
                        display: flex; align-items: center; justify-content: space-between;
                    }
                    .cq-modal-header-left { display: flex; align-items: center; gap: 12px; }
                    .cq-modal-header-icon {
                        width: 40px; height: 40px; background: var(--indigo-light); border-radius: var(--radius-md);
                        display: flex; align-items: center; justify-content: center; font-size: 16px; color: var(--indigo);
                    }
                    .cq-modal-header h3 { font-size: 1rem; font-weight: 700; color: var(--gray-800); margin: 0; }
                    .cq-modal-header p { font-size: 0.78rem; color: var(--gray-500); margin: 1px 0 0; }
                    .cq-modal-close {
                        width: 32px; height: 32px; border: none; background: none; border-radius: var(--radius-sm);
                        cursor: pointer; display: flex; align-items: center; justify-content: center;
                        font-size: 18px; color: var(--gray-400); transition: all .15s;
                    }
                    .cq-modal-close:hover { background: var(--red-light); color: var(--red); }
                    .cq-modal-body { padding: 22px 24px; }

                    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; border: 1px solid var(--gray-200); border-radius: var(--radius-md); overflow: hidden; margin-bottom: 22px; }
                    .info-item { padding: 10px 14px; border-bottom: 1px solid var(--gray-200); }
                    .info-item:nth-child(odd) { border-right: 1px solid var(--gray-200); }
                    .info-item:nth-last-child(1), .info-item:nth-last-child(2) { border-bottom: none; }
                    .info-item .i-label { font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: .6px; color: var(--gray-400); margin-bottom: 3px; }
                    .info-item .i-value { font-size: 0.875rem; font-weight: 600; color: var(--gray-800); }
                    .info-item.highlight .i-value { color: var(--indigo); font-size: 1.05rem; }

                    .form-group { margin-bottom: 18px; }
                    .form-group label { display: block; font-size: 0.82rem; font-weight: 600; color: var(--gray-700); margin-bottom: 7px; }
                    .form-group label span.req { color: var(--red); margin-left: 2px; }

                    .action-options { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 18px; }
                    .action-option { border: 2px solid var(--gray-200); border-radius: var(--radius-md); padding: 14px 16px; cursor: pointer; transition: all .18s; display: flex; align-items: center; gap: 12px; }
                    .action-option:hover { border-color: var(--gray-400); }
                    .action-option.selected-deposit { border-color: var(--indigo); background: var(--indigo-light); }
                    .action-option.selected-return { border-color: var(--red); background: var(--red-light); }
                    .action-option-icon { width: 36px; height: 36px; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; font-size: 15px; flex-shrink: 0; }
                    .icon-deposit { background: var(--indigo-light); color: var(--indigo); }
                    .icon-return  { background: var(--red-light);    color: var(--red); }
                    .selected-deposit .icon-deposit { background: var(--indigo); color: #fff; }
                    .selected-return  .icon-return  { background: var(--red);    color: #fff; }
                    .action-option-label { font-size: 0.875rem; font-weight: 700; color: var(--gray-700); }
                    .action-option-desc { font-size: 0.72rem; color: var(--gray-500); margin-top: 1px; }
                    .selected-deposit .action-option-label { color: var(--indigo); }
                    .selected-return  .action-option-label { color: var(--red); }

                    .cq-modal-footer { padding: 16px 24px 20px; border-top: 1px solid var(--gray-200); display: flex; justify-content: flex-end; gap: 10px; }

                    @media (max-width: 640px) {
                        .action-options { grid-template-columns: 1fr; }
                        .info-grid      { grid-template-columns: 1fr; }
                        .info-item:nth-child(odd) { border-right: none; }
                        .info-item:nth-last-child(2) { border-bottom: 1px solid var(--gray-200); }
                    }
                </style>
            </head>

            <body class="cq-scope">

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </div>
                            <div>
                                <h3 class="page-title">Customer Cheque Payment Report</h3>
                                <p class="page-subtitle">Track, deposit and manage customer cheques.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="card">
                            <div class="card-body">
                                @php
                                    $totalAmount   = $receipts->sum('amount');
                                    $totalDeposit  = $receipts->where('cheque_status','deposit')->count();
                                    $totalReturn   = $receipts->where('cheque_status','return')->count();
                                    $totalPending  = $receipts->whereNotIn('cheque_status',['deposit','return'])->count();
                                @endphp

                                <div class="cq-summary-strip">
                                    <div class="cq-stat-tile">
                                        <div class="s-label"><i class="fa-solid fa-layer-group"></i> Total Cheques</div>
                                        <div class="s-value indigo">{{ $receipts->count() }}</div>
                                    </div>
                                    <div class="cq-stat-tile">
                                        <div class="s-label"><i class="fa-solid fa-coins"></i> Total Amount</div>
                                        <div class="s-value indigo">{{ number_format($totalAmount, 2) }}</div>
                                    </div>
                                    <div class="cq-stat-tile">
                                        <div class="s-label"><i class="fa-solid fa-clock"></i> Pending</div>
                                        <div class="s-value amber">{{ $totalPending }}</div>
                                    </div>
                                    <div class="cq-stat-tile">
                                        <div class="s-label"><i class="fa-solid fa-check-circle"></i> Deposited</div>
                                        <div class="s-value green">{{ $totalDeposit }}</div>
                                    </div>
                                    <div class="cq-stat-tile">
                                        <div class="s-label"><i class="fa-solid fa-rotate-left"></i> Returned</div>
                                        <div class="s-value red">{{ $totalReturn }}</div>
                                    </div>
                                </div>

                                <form action="" method="GET" class="row g-2 mb-3 align-items-end filter-form">
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Customer Code</label>
                                        <input type="text" name="customer" id="customer" class="form-control" value="{{ request('customer') }}" placeholder="Customer code">
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Search</button>
                                    </div>
                                </form>

                                <p class="text-muted small mb-2">
                                    <i class="fa-solid fa-circle" style="color:var(--amber);font-size:8px;"></i>
                                    Yellow rows = today or future release date
                                </p>

                                <div class="modern-table-card">
                                    <div class="table-responsive">
                                        <table id="t_sup_cheques" class="table" style="width:100%;">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Transfer No</th>
                                                    <th>Customer</th>
                                                    <th>Release Date</th>
                                                    <th>Type</th>
                                                    <th>Bank</th>
                                                    <th>Branch</th>
                                                    <th>Cheque No</th>
                                                    <th>Account No</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $rowNum = 1; @endphp
                                                @foreach($receipts as $invoice)
                                                <tr>
                                                    <td>{{ $rowNum++ }}</td>
                                                    <td>{{ $invoice->trans_no }}</td>
                                                    <td>{{ $invoice->customer }}</td>
                                                    <td>{{ $invoice->release_date }}</td>
                                                    <td>{{ $invoice->trans_type }}</td>
                                                    <td>{{ $invoice->bank }}</td>
                                                    <td>{{ $invoice->branch_code }}</td>
                                                    <td>{{ $invoice->cheques_no }}</td>
                                                    <td>{{ $invoice->acc_no }}</td>
                                                    <td class="text-end">{{ number_format($invoice->amount, 2) }}</td>
                                                    <td>
                                                        @if($invoice->cheque_status === 'deposit')
                                                            <span class="cq-badge cq-badge-deposit"><i class="fa-solid fa-check"></i> Deposited</span>
                                                        @elseif($invoice->cheque_status === 'return')
                                                            <span class="cq-badge cq-badge-return"><i class="fa-solid fa-rotate-left"></i> Returned</span>
                                                        @else
                                                            <span class="cq-badge cq-badge-pending"><i class="fa-regular fa-clock"></i> Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(in_array($invoice->cheque_status, ['deposit', 'return']))
                                                            <button class="cq-btn cq-btn-sm {{ $invoice->cheque_status === 'deposit' ? 'cq-btn-done' : 'cq-btn-returned' }}" disabled>
                                                                <i class="fa-solid fa-lock"></i>
                                                                {{ $invoice->cheque_status === 'deposit' ? 'Deposited' : 'Returned' }}
                                                            </button>
                                                        @else
                                                            <button type="button"
                                                                class="cq-btn cq-btn-sm cq-btn-action open-modal"
                                                                data-id="{{ $invoice->id }}"
                                                                data-trans_no="{{ $invoice->trans_no }}"
                                                                data-customer="{{ $invoice->customer }}"
                                                                data-bank="{{ $invoice->bank }}"
                                                                data-branch="{{ $invoice->branch_code }}"
                                                                data-cheque_no="{{ $invoice->cheques_no }}"
                                                                data-acc_no="{{ $invoice->acc_no }}"
                                                                data-amount="{{ $invoice->amount }}"
                                                                data-release_date="{{ $invoice->release_date }}"
                                                                data-trans_type="{{ $invoice->trans_type }}">
                                                                <i class="fa-solid fa-money-bill-wave"></i>
                                                                Entry
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="9" class="text-center">Total Balance</td>
                                                    <td class="text-end">{{ number_format($totalAmount, 2) }}</td>
                                                    <td colspan="2"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div id="customerChequeCustomPager"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
         </div>

        {{-- ══════════════════════════════════════════
             CASH RECEIVED ENTRY MODAL
        ══════════════════════════════════════════ --}}
        <div class="cq-modal-backdrop cq-scope" id="cashModal">
            <div class="cq-modal">

                <div class="cq-modal-header">
                    <div class="cq-modal-header-left">
                        <div class="cq-modal-header-icon">
                            <i class="fa-solid fa-money-check-dollar"></i>
                        </div>
                        <div>
                            <h3>Cash Received Entry</h3>
                            <p>Select action type and confirm</p>
                        </div>
                    </div>
                    <button class="cq-modal-close" id="closeModal" title="Close">&times;</button>
                </div>

                <div class="cq-modal-body">

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="i-label">Transfer No</div>
                            <div class="i-value" id="m_trans_no">—</div>
                        </div>
                        <div class="info-item">
                            <div class="i-label">Customer</div>
                            <div class="i-value" id="m_customer">—</div>
                        </div>
                        <div class="info-item">
                            <div class="i-label">Bank</div>
                            <div class="i-value" id="m_bank">—</div>
                        </div>
                        <div class="info-item">
                            <div class="i-label">Branch</div>
                            <div class="i-value" id="m_branch">—</div>
                        </div>
                        <div class="info-item">
                            <div class="i-label">Cheque No</div>
                            <div class="i-value" id="m_cheque_no" style="font-family:monospace;">—</div>
                        </div>
                        <div class="info-item">
                            <div class="i-label">Release Date</div>
                            <div class="i-value" id="m_release_date">—</div>
                        </div>
                        <div class="info-item highlight" style="grid-column: span 2;">
                            <div class="i-label">Cheque Amount</div>
                            <div class="i-value" id="m_amount">—</div>
                        </div>
                    </div>

                    <form id="cashReceivedForm" method="POST" action="{{ route('customer-cheque.cash-received') }}">
                        @csrf
                        <input type="hidden" name="cheque_id"  id="f_cheque_id">
                        <input type="hidden" name="trans_no"   id="f_trans_no">
                        <input type="hidden" name="customer"   id="f_customer">
                        <input type="hidden" name="trans_type" id="f_trans_type">
                        <input type="hidden" name="action_type" id="f_action_type_hidden" value="">

                        <div class="form-group">
                            <label>Select Action <span class="req">*</span></label>
                            <div class="action-options">
                                <div class="action-option" id="opt_deposit" onclick="selectAction('deposit')">
                                    <div class="action-option-icon icon-deposit">
                                        <i class="fa-solid fa-arrow-down-to-bracket"></i>
                                    </div>
                                    <div>
                                        <div class="action-option-label">Deposit</div>
                                        <div class="action-option-desc">Mark cheque as deposited</div>
                                    </div>
                                </div>
                                <div class="action-option" id="opt_return" onclick="selectAction('return')">
                                    <div class="action-option-icon icon-return">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </div>
                                    <div>
                                        <div class="action-option-label">Return</div>
                                        <div class="action-option-desc">Mark cheque as returned</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="amount_section" style="display:none;">
                            <label for="f_dr_amount">
                                <i class="fa-solid fa-coins" style="font-size:12px;color:var(--indigo);"></i>
                                &nbsp;Deposit Amount (DR Amount) <span class="req">*</span>
                            </label>
                            <input type="number" class="form-control"
                                   name="dr_amount" id="f_dr_amount"
                                   step="0.01" min="0.01"
                                   placeholder="0.00">
                        </div>

                    </form>
                </div>

                <div class="cq-modal-footer">
                    <button type="button" class="cq-btn cq-btn-neutral" id="cancelModal">
                        <i class="fa-solid fa-xmark"></i> Cancel
                    </button>
                    <button type="button" class="cq-btn cq-btn-primary" id="confirmBtn" disabled>
                        <i class="fa-solid fa-floppy-disk"></i> Confirm
                    </button>
                </div>

            </div>
        </div>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/dt-custom-pager.js"></script>

<script>
$(document).ready(function () {

    /* ── Highlight today/future rows ── */
    var today = new Date(); today.setHours(0,0,0,0);
    var alertCount = 0;

    $('#t_sup_cheques tbody tr').each(function () {
        var raw = $(this).find('td:eq(3)').text().trim();
        if (raw) {
            var d = new Date(raw); d.setHours(0,0,0,0);
            if (d >= today) { alertCount++; $(this).addClass('highlight-today'); }
        }
    });

    if (alertCount > 0) {
        $('.page-header').after(
            '<div class="alert alert-warning" id="todayAlert">' +
            '<i class="fa-solid fa-bell"></i> ' + alertCount +
            ' cheque(s) with a release date of today or later are highlighted in yellow.' +
            ' <button onclick="$(\'#todayAlert\').remove()" style="margin-left:8px;background:none;border:none;cursor:pointer;font-size:16px;color:inherit;">&times;</button>' +
            '</div>'
        );
    }

    /* ── DataTable ── */
    var table = $('#t_sup_cheques').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'excel', 'csv', 'pdf'],
        pageLength: 15,
        lengthChange: false,
        order: [[3, 'asc']],
        columnDefs: [{ orderable: false, targets: [11] }]
    });

    $('#t_sup_cheques_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(table, '#customerChequeCustomPager');

    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        var dateObj = new Date();
        toDateInput.value = dateObj.toISOString().slice(0, 10);
    }

    /* ── Open modal ── */
    $(document).on('click', '.open-modal', function () {
        var b = $(this);
        $('#m_trans_no').text(b.data('trans_no'));
        $('#m_customer').text(b.data('customer'));
        $('#m_bank').text(b.data('bank'));
        $('#m_branch').text(b.data('branch'));
        $('#m_cheque_no').text(b.data('cheque_no'));
        $('#m_release_date').text(b.data('release_date'));
        $('#m_amount').text(parseFloat(b.data('amount'))
            .toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}));

        $('#f_cheque_id').val(b.data('id'));
        $('#f_trans_no').val(b.data('trans_no'));
        $('#f_customer').val(b.data('customer'));
        $('#f_trans_type').val(b.data('trans_type'));
        $('#f_dr_amount').val(parseFloat(b.data('amount')).toFixed(2));

        resetAction();
        $('#cashModal').addClass('active');
    });

    /* ── Close modal ── */
    $('#closeModal, #cancelModal').on('click', closeModal);
    $('#cashModal').on('click', function(e){ if($(e.target).is('#cashModal')) closeModal(); });
    $(document).on('keydown', function(e){ if(e.key === 'Escape') closeModal(); });

    function closeModal(){
        $('#cashModal').removeClass('active');
        resetAction();
    }

    /* ── Confirm button submit ── */
    $('#confirmBtn').on('click', function(){
        var action = $('#f_action_type_hidden').val();
        if(!action){ alert('Please select an action type.'); return; }
        if(action === 'deposit'){
            var amt = parseFloat($('#f_dr_amount').val());
            if(!amt || amt <= 0){ alert('Please enter a valid deposit amount.'); return; }
        }
        $('#cashReceivedForm').submit();
    });

});

/* ── Action selector ── */
function selectAction(type){
    $('#opt_deposit').removeClass('selected-deposit selected-return');
    $('#opt_return').removeClass('selected-deposit selected-return');
    $('#f_action_type_hidden').val(type);
    $('#confirmBtn').prop('disabled', false);

    if(type === 'deposit'){
        $('#opt_deposit').addClass('selected-deposit');
        $('#amount_section').slideDown(180);
        $('#f_dr_amount').prop('required', true);
    } else {
        $('#opt_return').addClass('selected-return');
        $('#amount_section').slideUp(180);
        $('#f_dr_amount').prop('required', false).val('');
    }
}

function resetAction(){
    $('#opt_deposit, #opt_return').removeClass('selected-deposit selected-return');
    $('#f_action_type_hidden').val('');
    $('#confirmBtn').prop('disabled', true);
    $('#amount_section').hide();
    $('#f_dr_amount').prop('required', false);
}

function printTablefun() {
    var params = $('.filter-form').serialize();
    window.open('{{ route('customer_cheque_payment_report.print') }}?' + params, '_blank');
}
</script>

</body>
@endsection

</html>
