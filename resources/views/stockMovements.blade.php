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
                <title>Stock Movements</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                .sm-type-chip{
                    font-size:11.5px; font-weight:700; border-radius:999px; padding:4px 12px; display:inline-block;
                    white-space:nowrap;
                }
                .sm-type-GRN, .sm-type-Purchase{ background:#DBEAFE; color:#1D4ED8; }
                .sm-type-Sales, .sm-type-SALES_OUT_VAT{ background:#D1FAE5; color:#047857; }
                .sm-type-Stock_Damage{ background:#FEE2E2; color:#B91C1C; }
                .sm-type-Sales_Return{ background:#FEF3C7; color:#B45309; }
                .sm-type-Stock_Adjustment{ background:#EDE9FE; color:#6D28D9; }
                .sm-type-Opening_Stock{ background:#E5E7EB; color:#374151; }
                .sm-type-default{ background:#E5E7EB; color:#374151; }
                .sm-in{ color:#047857; font-weight:700; font-variant-numeric:tabular-nums; }
                .sm-out{ color:#B91C1C; font-weight:700; font-variant-numeric:tabular-nums; }
            </style>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" y1="22" x2="12" y2="12"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Stock Movements</h3>
                                <p class="page-subtitle">Every stock-affecting event in one place — Purchases, Sales, Stock Damage, Sales Return, Adjustments — for checking, not entering. Add or edit from each feature's own page.</p>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                <form method="GET" action="{{ route('stock_movements') }}" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-2">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Type</label>
                                        <select name="type" class="form-control">
                                            <option value="">All Types</option>
                                            @foreach($typeOptions as $code => $label)
                                            <option value="{{ $code }}" {{ $type === $code ? 'selected' : '' }}>{{ $label }} ({{ $code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Item Code</label>
                                        <input type="text" name="item_code" class="form-control" value="{{ $itemCode }}" placeholder="Item code">
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
                                                <th>Type</th>
                                                <th>Reference</th>
                                                <th>Item Code</th>
                                                <th>Item Description</th>
                                                <th>Qty In</th>
                                                <th>Qty Out</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($rows as $row)
                                            <tr>
                                                <td>{{ $row->dDate }}</td>
                                                <td>
                                                    <span class="sm-type-chip sm-type-{{ str_replace(' ', '_', $row->type_label) }}">{{ $row->type_label }}</span>
                                                </td>
                                                <td>{{ $row->trans_no }}</td>
                                                <td>{{ $row->item_code }}</td>
                                                <td>{{ $row->Item_description }}</td>
                                                <td class="sm-in">{{ $row->qun_in > 0 ? number_format($row->qun_in, 2) : '' }}</td>
                                                <td class="sm-out">{{ $row->qun_out > 0 ? number_format($row->qun_out, 2) : '' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No stock movements found for the selected filters.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        @if($rows->count())
                                        <tfoot>
                                            <tr class="fw-bold" style="background-color:#f4f6f9;">
                                                <td colspan="5" class="text-end">Total</td>
                                                <td class="sm-in">{{ number_format($totalIn, 2) }}</td>
                                                <td class="sm-out">{{ number_format($totalOut, 2) }}</td>
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
