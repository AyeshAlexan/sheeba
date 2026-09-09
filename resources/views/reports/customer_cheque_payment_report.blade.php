{{-- resources/views/reports/customer_cheque_payment_report.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Cheque Payment Report</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --indigo:       #4f46e5;
            --indigo-dark:  #4338ca;
            --indigo-light: #eef2ff;
            --indigo-mid:   #c7d2fe;
            --green:        #059669;
            --green-light:  #d1fae5;
            --green-dark:   #065f46;
            --amber:        #d97706;
            --amber-light:  #fef3c7;
            --amber-dark:   #92400e;
            --red:          #dc2626;
            --red-light:    #fee2e2;
            --red-dark:     #991b1b;
            --gray-50:      #f9fafb;
            --gray-100:     #f3f4f6;
            --gray-200:     #e5e7eb;
            --gray-300:     #d1d5db;
            --gray-400:     #9ca3af;
            --gray-500:     #6b7280;
            --gray-600:     #4b5563;
            --gray-700:     #374151;
            --gray-800:     #1f2937;
            --white:        #ffffff;
            --shadow-sm:    0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.05);
            --shadow-md:    0 4px 12px rgba(0,0,0,.1);
            --shadow-lg:    0 20px 60px rgba(0,0,0,.18);
            --radius-sm:    6px;
            --radius-md:    10px;
            --radius-lg:    14px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gray-100);
            color: var(--gray-800);
            min-height: 100vh;
            padding: 28px 20px;
        }

        /* ─── Page wrapper ─── */
        .page-wrapper {
            max-width: 1350px;
            margin: 0 auto;
        }

        /* ─── Page Header ─── */
        .page-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: var(--radius-lg);
            padding: 28px 32px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            box-shadow: var(--shadow-md);
        }

        .page-header-left { display: flex; align-items: center; gap: 16px; }

        .page-header-icon {
            width: 52px; height: 52px;
            background: rgba(255,255,255,0.15);
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; color: #fff;
        }

        .page-header h1 {
            font-size: 1.4rem; font-weight: 700;
            color: #fff; letter-spacing: .3px;
        }

        .page-header p {
            font-size: 0.82rem; color: rgba(255,255,255,.7);
            margin-top: 2px;
        }

        .header-badges { display: flex; gap: 10px; flex-wrap: wrap; }

        .header-badge {
            background: rgba(255,255,255,.15);
            border: 1px solid rgba(255,255,255,.25);
            color: #fff; font-size: 0.78rem; font-weight: 600;
            padding: 5px 14px; border-radius: 20px;
            display: flex; align-items: center; gap: 6px;
        }

        /* ─── Card ─── */
        .card {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--gray-200);
        }

        /* ─── Filter Section ─── */
        .filter-card {
            padding: 22px 28px;
            margin-bottom: 20px;
        }

        .filter-title {
            font-size: 0.75rem; font-weight: 600;
            color: var(--gray-500); text-transform: uppercase;
            letter-spacing: .8px; margin-bottom: 16px;
            display: flex; align-items: center; gap: 8px;
        }

        .filter-form {
            display: flex; flex-wrap: wrap;
            align-items: flex-end; gap: 16px;
        }

        .filter-group { display: flex; flex-direction: column; gap: 5px; }

        .filter-group label {
            font-size: 0.78rem; font-weight: 600;
            color: var(--gray-600);
        }

        .filter-group input[type="date"] {
            padding: 9px 13px;
            border: 1.5px solid var(--gray-300);
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            color: var(--gray-800);
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }

        .filter-group input[type="date"]:focus {
            border-color: var(--indigo);
            box-shadow: 0 0 0 3px rgba(79,70,229,.12);
        }

        /* ─── Buttons ─── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 20px; border-radius: var(--radius-sm);
            font-size: 0.86rem; font-weight: 600;
            font-family: 'Inter', sans-serif;
            border: none; cursor: pointer;
            text-decoration: none; white-space: nowrap;
            transition: all .18s;
        }

        .btn-primary   { background: var(--indigo);   color: #fff; }
        .btn-primary:hover { background: var(--indigo-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(79,70,229,.35); }

        .btn-success   { background: var(--green);    color: #fff; }
        .btn-success:hover { background: #047857; transform: translateY(-1px); }

        .btn-neutral   { background: var(--gray-600); color: #fff; }
        .btn-neutral:hover { background: var(--gray-700); transform: translateY(-1px); }

        .btn-sm {
            padding: 6px 13px;
            font-size: 0.78rem;
        }

        .btn-action {
            background: var(--indigo-light);
            color: var(--indigo);
            border: 1.5px solid var(--indigo-mid);
        }
        .btn-action:hover {
            background: var(--indigo);
            color: #fff;
            border-color: var(--indigo);
            transform: translateY(-1px);
        }

        .btn-done {
            background: var(--green-light);
            color: var(--green-dark);
            border: 1.5px solid #6ee7b7;
            cursor: not-allowed;
        }

        .btn-returned {
            background: var(--red-light);
            color: var(--red-dark);
            border: 1.5px solid #fca5a5;
            cursor: not-allowed;
        }

        /* ─── Alert Banners ─── */
        .alert {
            padding: 13px 18px; border-radius: var(--radius-md);
            margin-bottom: 18px; font-size: 0.875rem; font-weight: 500;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background: var(--green-light); color: var(--green-dark); border: 1px solid #6ee7b7; }
        .alert-error   { background: var(--red-light);   color: var(--red-dark);   border: 1px solid #fca5a5; }
        .alert-warning { background: var(--amber-light); color: var(--amber-dark); border: 1px solid #fde68a; }

        /* ─── Summary Strip ─── */
        .summary-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .summary-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            padding: 18px 20px;
            box-shadow: var(--shadow-sm);
        }

        .summary-card .s-label {
            font-size: 0.72rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: .7px; color: var(--gray-500); margin-bottom: 8px;
            display: flex; align-items: center; gap: 6px;
        }

        .summary-card .s-value {
            font-size: 1.5rem; font-weight: 700; color: var(--gray-800);
        }

        .summary-card .s-value.indigo { color: var(--indigo); }
        .summary-card .s-value.green  { color: var(--green); }
        .summary-card .s-value.amber  { color: var(--amber); }
        .summary-card .s-value.red    { color: var(--red); }

        /* ─── Table Card ─── */
        .table-card { padding: 0; overflow: hidden; }

        .table-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--gray-200);
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
        }

        .table-card-header h2 {
            font-size: 0.95rem; font-weight: 700; color: var(--gray-800);
            display: flex; align-items: center; gap: 8px;
        }

        .table-responsive { overflow-x: auto; }

        #t_sup_cheques {
            width: 100% !important;
            border-collapse: collapse;
            font-size: 0.855rem;
        }

        #t_sup_cheques thead tr {
            background: var(--gray-50);
            border-bottom: 2px solid var(--gray-200);
        }

        #t_sup_cheques thead th {
            padding: 13px 14px;
            font-size: 0.72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .7px;
            color: var(--gray-500); white-space: nowrap;
            border: none;
        }

        #t_sup_cheques tbody tr {
            border-bottom: 1px solid var(--gray-200);
            transition: background .12s;
        }

        #t_sup_cheques tbody tr:hover { background: #f8f9ff; }

        #t_sup_cheques tbody td {
            padding: 12px 14px;
            color: var(--gray-700);
            border: none; vertical-align: middle;
        }

        /* Highlight today/future */
        .highlight-today {
            background: #fffbeb !important;
            border-left: 3px solid var(--amber) !important;
        }
        .highlight-today:hover { background: #fef9e4 !important; }

        /* Total footer row */
        #t_sup_cheques tfoot tr {
            background: var(--indigo-light);
            border-top: 2px solid var(--indigo-mid);
        }

        #t_sup_cheques tfoot td {
            padding: 14px;
            font-size: 0.9rem; font-weight: 700;
            color: var(--indigo);
            border: none;
        }

        /* ─── Badges ─── */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 11px; border-radius: 20px;
            font-size: 0.72rem; font-weight: 700;
            white-space: nowrap;
        }

        .badge-pending  { background: var(--gray-100);   color: var(--gray-600);   border: 1px solid var(--gray-300); }
        .badge-deposit  { background: var(--green-light); color: var(--green-dark); border: 1px solid #6ee7b7; }
        .badge-return   { background: var(--red-light);   color: var(--red-dark);   border: 1px solid #fca5a5; }

        /* ─── DataTables overrides ─── */
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border: 1.5px solid var(--gray-300) !important;
            border-radius: var(--radius-sm) !important;
            padding: 6px 10px !important;
            font-size: 0.82rem !important;
            font-family: 'Inter', sans-serif !important;
            outline: none;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--indigo) !important;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1) !important;
        }

        .dt-buttons { margin-bottom: 14px !important; }

        .dt-button {
            background: var(--white) !important;
            border: 1.5px solid var(--gray-300) !important;
            color: var(--gray-700) !important;
            border-radius: var(--radius-sm) !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            padding: 6px 14px !important;
            transition: all .15s !important;
            box-shadow: none !important;
        }

        .dt-button:hover {
            background: var(--gray-100) !important;
            border-color: var(--gray-400) !important;
        }

        .dataTables_info, .dataTables_paginate {
            font-size: 0.8rem !important;
            color: var(--gray-500) !important;
            padding: 14px 0 !important;
        }

        .paginate_button {
            border-radius: var(--radius-sm) !important;
            font-family: 'Inter', sans-serif !important;
        }

        .paginate_button.current, .paginate_button.current:hover {
            background: var(--indigo) !important;
            border-color: var(--indigo) !important;
            color: #fff !important;
        }

        /* ─── MODAL ─── */
        .modal-backdrop {
            display: none;
            position: fixed; inset: 0;
            background: rgba(15, 23, 42, 0.55);
            z-index: 9000;
            backdrop-filter: blur(3px);
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .modal-backdrop.active { display: flex; }

        .modal {
            background: var(--white);
            border-radius: var(--radius-lg);
            width: 540px; max-width: 100%;
            box-shadow: var(--shadow-lg);
            animation: modalIn .22s cubic-bezier(.34,1.56,.64,1);
            overflow: hidden;
        }

        @keyframes modalIn {
            from { transform: scale(.88) translateY(20px); opacity: 0; }
            to   { transform: scale(1)  translateY(0);     opacity: 1; }
        }

        .modal-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid var(--gray-200);
            display: flex; align-items: center; justify-content: space-between;
        }

        .modal-header-left {
            display: flex; align-items: center; gap: 12px;
        }

        .modal-header-icon {
            width: 40px; height: 40px;
            background: var(--indigo-light);
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; color: var(--indigo);
        }

        .modal-header h3 {
            font-size: 1rem; font-weight: 700; color: var(--gray-800);
        }

        .modal-header p {
            font-size: 0.78rem; color: var(--gray-500); margin-top: 1px;
        }

        .modal-close {
            width: 32px; height: 32px; border: none; background: none;
            border-radius: var(--radius-sm); cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: var(--gray-400);
            transition: all .15s;
        }
        .modal-close:hover { background: var(--red-light); color: var(--red); }

        .modal-body { padding: 22px 24px; }

        /* Info grid inside modal */
        .info-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 0;
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-md);
            overflow: hidden;
            margin-bottom: 22px;
        }

        .info-item {
            padding: 10px 14px;
            border-bottom: 1px solid var(--gray-200);
        }

        .info-item:nth-child(odd) { border-right: 1px solid var(--gray-200); }
        .info-item:nth-last-child(1),
        .info-item:nth-last-child(2) { border-bottom: none; }

        .info-item .i-label {
            font-size: 0.68rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: .6px; color: var(--gray-400); margin-bottom: 3px;
        }

        .info-item .i-value {
            font-size: 0.875rem; font-weight: 600; color: var(--gray-800);
        }

        .info-item.highlight .i-value {
            color: var(--indigo); font-size: 1.05rem;
        }

        /* Select Field */
        .form-group { margin-bottom: 18px; }

        .form-group label {
            display: block; font-size: 0.82rem; font-weight: 600;
            color: var(--gray-700); margin-bottom: 7px;
        }

        .form-group label span.req { color: var(--red); margin-left: 2px; }

        .form-control {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid var(--gray-300);
            border-radius: var(--radius-sm);
            font-size: 0.9rem; font-family: 'Inter', sans-serif;
            color: var(--gray-800); background: var(--white);
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--indigo);
            box-shadow: 0 0 0 3px rgba(79,70,229,.12);
        }

        /* Action type cards */
        .action-options {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 12px; margin-bottom: 18px;
        }

        .action-option {
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-md);
            padding: 14px 16px;
            cursor: pointer;
            transition: all .18s;
            display: flex; align-items: center; gap: 12px;
        }

        .action-option:hover { border-color: var(--gray-400); }

        .action-option.selected-deposit {
            border-color: var(--indigo);
            background: var(--indigo-light);
        }

        .action-option.selected-return {
            border-color: var(--red);
            background: var(--red-light);
        }

        .action-option-icon {
            width: 36px; height: 36px; border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; flex-shrink: 0;
        }

        .icon-deposit { background: var(--indigo-light); color: var(--indigo); }
        .icon-return  { background: var(--red-light);    color: var(--red); }

        .selected-deposit .icon-deposit { background: var(--indigo); color: #fff; }
        .selected-return  .icon-return  { background: var(--red);    color: #fff; }

        .action-option-label {
            font-size: 0.875rem; font-weight: 700; color: var(--gray-700);
        }

        .action-option-desc {
            font-size: 0.72rem; color: var(--gray-500); margin-top: 1px;
        }

        .selected-deposit .action-option-label { color: var(--indigo); }
        .selected-return  .action-option-label { color: var(--red); }

        .modal-footer {
            padding: 16px 24px 20px;
            border-top: 1px solid var(--gray-200);
            display: flex; justify-content: flex-end; gap: 10px;
        }

        /* ─── Print ─── */
        @media print {
            body { background: white; padding: 0; }
            .filter-card, .dt-buttons, .dataTables_filter,
            .dataTables_length, .dataTables_info, .dataTables_paginate,
            .modal-backdrop, .page-header .header-badges,
            .summary-strip { display: none !important; }
            .page-wrapper { max-width: 100%; }
            .card { box-shadow: none; border: 1px solid #ccc; border-radius: 0; }
            #t_sup_cheques tbody tr:hover { background: transparent; }
        }

        @media (max-width: 640px) {
            .action-options { grid-template-columns: 1fr; }
            .info-grid      { grid-template-columns: 1fr; }
            .info-item:nth-child(odd) { border-right: none; }
            .info-item:nth-last-child(2) { border-bottom: 1px solid var(--gray-200); }
        }
    </style>
</head>
<body>
<div class="page-wrapper">

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div class="page-header-left">
            <div class="page-header-icon">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
            <div>
                <h1>Customer Cheque Payment Report</h1>
                <p>Track, deposit and manage customer cheques</p>
            </div>
        </div>
        <div class="header-badges">
            <div class="header-badge">
                <i class="fa-solid fa-calendar-days" style="font-size:12px;"></i>
                {{ now()->format('d M Y') }}
            </div>
            <div class="header-badge">
                <i class="fa-solid fa-hashtag" style="font-size:12px;"></i>
                {{ $receipts->count() }} Records
            </div>
        </div>
    </div>

    {{-- ── Alerts ── --}}
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-error">
        <i class="fa-solid fa-triangle-exclamation"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- ── Summary Strip ── --}}
    @php
        $totalAmount   = $receipts->sum('amount');
        $totalDeposit  = $receipts->where('cheque_status','deposit')->count();
        $totalReturn   = $receipts->where('cheque_status','return')->count();
        $totalPending  = $receipts->whereNotIn('cheque_status',['deposit','return'])->count();
        $depositAmount = $receipts->where('cheque_status','deposit')->sum('amount');
    @endphp

    <div class="summary-strip">
        <div class="summary-card">
            <div class="s-label">
                <i class="fa-solid fa-layer-group" style="font-size:11px;"></i>
                Total Cheques
            </div>
            <div class="s-value indigo">{{ $receipts->count() }}</div>
        </div>
        <div class="summary-card">
            <div class="s-label">
                <i class="fa-solid fa-coins" style="font-size:11px;"></i>
                Total Amount
            </div>
            <div class="s-value indigo">{{ number_format($totalAmount, 2) }}</div>
        </div>
        <div class="summary-card">
            <div class="s-label">
                <i class="fa-solid fa-clock" style="font-size:11px;"></i>
                Pending
            </div>
            <div class="s-value amber">{{ $totalPending }}</div>
        </div>
        <div class="summary-card">
            <div class="s-label">
                <i class="fa-solid fa-check-circle" style="font-size:11px;"></i>
                Deposited
            </div>
            <div class="s-value green">{{ $totalDeposit }}</div>
        </div>
        <div class="summary-card">
            <div class="s-label">
                <i class="fa-solid fa-rotate-left" style="font-size:11px;"></i>
                Returned
            </div>
            <div class="s-value red">{{ $totalReturn }}</div>
        </div>
    </div>

    {{-- ── Filter Card ── --}}
    <div class="card filter-card">
        <div class="filter-title">
            <i class="fa-solid fa-sliders" style="font-size:11px;"></i>
            Filter Records
        </div>
        <form action="" method="GET" class="filter-form">
            <div class="filter-group">
                <label>From Date</label>
                <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}">
            </div>
            <div class="filter-group">
                <label>To Date</label>
                <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass"></i> Search
            </button>
            <button type="button" class="btn btn-success" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Print
            </button>
            <a href="{{ route('home') }}" class="btn btn-neutral">
                <i class="fa-solid fa-house"></i> Back
            </a>
        </form>
    </div>

    {{-- ── Table Card ── --}}
    <div class="card table-card">
        <div class="table-card-header">
            <h2>
                <i class="fa-solid fa-table" style="font-size:14px;color:var(--indigo);"></i>
                Cheque Listing
            </h2>
            <div style="font-size:0.78rem;color:var(--gray-500);">
                <i class="fa-solid fa-circle" style="color:var(--amber);font-size:8px;"></i>
                Yellow rows = today or future release date
            </div>
        </div>

        <div style="padding:16px 24px 0;">
            <div class="table-responsive">
                <table id="t_sup_cheques" class="display nowrap">
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
                            <th style="text-align:right;">Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $rowNum = 1; @endphp
                        @foreach($receipts as $invoice)
                        <tr>
                            <td style="color:var(--gray-400);font-size:0.78rem;">{{ $rowNum++ }}</td>
                            <td>
                                <span style="font-weight:600;color:var(--indigo);">
                                    {{ $invoice->trans_no }}
                                </span>
                            </td>
                            <td>{{ $invoice->customer }}</td>
                            <td>
                                <span style="display:flex;align-items:center;gap:6px;white-space:nowrap;">
                                    <i class="fa-regular fa-calendar" style="font-size:11px;color:var(--gray-400);"></i>
                                    {{ $invoice->release_date }}
                                </span>
                            </td>
                            <td>{{ $invoice->trans_type }}</td>
                            <td>{{ $invoice->bank }}</td>
                            <td>{{ $invoice->branch_code }}</td>
                            <td>
                                <span style="font-family:monospace;font-size:0.82rem;font-weight:600;">
                                    {{ $invoice->cheques_no }}
                                </span>
                            </td>
                            <td>{{ $invoice->acc_no }}</td>
                            <td align="right">
                                <span style="font-weight:700;">
                                    {{ number_format($invoice->amount, 2) }}
                                </span>
                            </td>
                            <td>
                                @if($invoice->cheque_status === 'deposit')
                                    <span class="badge badge-deposit">
                                        <i class="fa-solid fa-check" style="font-size:9px;"></i> Deposited
                                    </span>
                                @elseif($invoice->cheque_status === 'return')
                                    <span class="badge badge-return">
                                        <i class="fa-solid fa-rotate-left" style="font-size:9px;"></i> Returned
                                    </span>
                                @else
                                    <span class="badge badge-pending">
                                        <i class="fa-regular fa-clock" style="font-size:9px;"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if(in_array($invoice->cheque_status, ['deposit', 'return']))
                                    <button class="btn btn-sm {{ $invoice->cheque_status === 'deposit' ? 'btn-done' : 'btn-returned' }}" disabled>
                                        <i class="fa-solid fa-lock" style="font-size:10px;"></i>
                                        {{ $invoice->cheque_status === 'deposit' ? 'Deposited' : 'Returned' }}
                                    </button>
                                @else
                                    <button type="button"
                                        class="btn btn-sm btn-action open-modal"
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
                                        <i class="fa-solid fa-money-bill-wave" style="font-size:11px;"></i>
                                        Entry
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9" align="center">
                                <i class="fa-solid fa-sigma" style="font-size:12px;"></i>
                                &nbsp; TOTAL BALANCE
                            </td>
                            <td align="right">{{ number_format($totalAmount, 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div style="height:16px;"></div>
    </div>

</div>{{-- end page-wrapper --}}


{{-- ══════════════════════════════════════════
     CASH RECEIVED ENTRY MODAL
══════════════════════════════════════════ --}}
<div class="modal-backdrop" id="cashModal">
    <div class="modal">

        {{-- Header --}}
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="modal-header-icon">
                    <i class="fa-solid fa-money-check-dollar"></i>
                </div>
                <div>
                    <h3>Cash Received Entry</h3>
                    <p>Select action type and confirm</p>
                </div>
            </div>
            <button class="modal-close" id="closeModal" title="Close">
                &times;
            </button>
        </div>

        {{-- Body --}}
        <div class="modal-body">

            {{-- Cheque Info Grid --}}
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

            {{-- Form --}}
            <form id="cashReceivedForm" method="POST" action="{{ route('customer-cheque.cash-received') }}">
                @csrf
                <input type="hidden" name="cheque_id"  id="f_cheque_id">
                <input type="hidden" name="trans_no"   id="f_trans_no">
                <input type="hidden" name="customer"   id="f_customer">
                <input type="hidden" name="trans_type" id="f_trans_type">
                <input type="hidden" name="action_type" id="f_action_type_hidden" value="">

                {{-- Action Type Selector --}}
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

                {{-- DR Amount (Deposit only) --}}
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

        {{-- Footer --}}
        <div class="modal-footer">
            <button type="button" class="btn btn-neutral" id="cancelModal">
                <i class="fa-solid fa-xmark"></i> Cancel
            </button>
            <button type="button" class="btn btn-primary" id="confirmBtn" disabled>
                <i class="fa-solid fa-floppy-disk"></i> Confirm
            </button>
        </div>

    </div>
</div>


{{-- Scripts --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

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
        $('body').prepend(
            '<div class="alert alert-warning" style="max-width:1350px;margin:0 auto 16px;" id="todayAlert">' +
            '<i class="fa-solid fa-bell"></i> ' + alertCount +
            ' cheque(s) with a release date of today or later are highlighted in yellow.' +
            '<button onclick="$(\'#todayAlert\').remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;font-size:16px;color:inherit;">&times;</button>' +
            '</div>'
        );
    }

    /* ── DataTable ── */
    var table = $('#t_sup_cheques').DataTable({
        dom: 'Bfrtip',
        buttons: [
            { extend:'copy',  text:'<i class="fa-regular fa-copy"></i> Copy' },
            { extend:'excel', text:'<i class="fa-solid fa-file-excel"></i> Excel' },
            { extend:'pdf',   text:'<i class="fa-solid fa-file-pdf"></i> PDF' },
            { extend:'print', text:'<i class="fa-solid fa-print"></i> Print' }
        ],
        pageLength: 25,
        order: [[3, 'asc']],
        columnDefs: [{ orderable: false, targets: [11] }]
    });

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

        // reset action selection
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
</script>
<script>
    var dateObj = new Date();
    document.getElementById('to_date').value = dateObj.toISOString().slice(0, 10);

</script>

</body>
</html>