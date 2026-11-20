@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Stock Adjustment</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <style>
        .stock-table-card { background:#fff; border:1px solid #d9e3ee; border-radius:16px; margin-top:18px; padding:16px; }
        .stock-table-card > .card-body { padding:0; }
        .adjustment-toolbar { position:sticky; top:var(--tr-header-h, 0px); z-index:5; background:#fff; padding:8px 0; }
        #adjustmentError:empty { display:none; }
        .adjustment-grid-scroll { overflow-x:auto; border:1px solid #dce4ed; border-radius:12px; }
        #stockAdjItemsTable { width:100%; min-width:650px; margin:0; border-collapse:separate; border-spacing:0; }
        #stockAdjItemsTable thead th { background:#edf3fa; color:#354961; font-weight:600; font-size:.8rem; padding:12px 24px 12px 14px; white-space:nowrap; }
        #stockAdjItemsTable tbody td { padding:10px 14px; border:0; border-top:1px solid #e4eaf1; color:#20354d; vertical-align:middle; }
        #stockAdjItemsTable tbody tr:focus-within td { background:#f4f8fd; }
        #stockAdjItemsTable .item-name { display:block; font-weight:600; white-space:normal; overflow-wrap:anywhere; }
        #stockAdjItemsTable .item-code { display:block; color:#51647b; font: .78rem/1.6 monospace; overflow-wrap:anywhere; }
        #stockAdjItemsTable .balance { text-align:right; font-variant-numeric:tabular-nums; }
        #stockAdjItemsTable output { display:inline-block; font-weight:700; border-radius:6px; padding:3px 9px; background:#eff3f7; }
        #stockAdjItemsTable tr[data-action="increase"] output { background:#def3e8; color:#185e3c; }
        #stockAdjItemsTable tr[data-action="reduce"] output { background:#fff0d7; color:#784900; }
        #stockAdjItemsTable .form-control, #stockAdjItemsTable .form-select { min-height:36px; border-radius:7px; font-size:.875rem; }
        #stockAdjItemsTable .adjustment-action { min-width:9rem; }
        #stockAdjItemsTable label { display:block; margin:0; max-width:8rem; }
        #stockAdjItemsTable .quantity-label { font-size:.75rem; color:#52657b; }
        #stockAdjItemsTable tr[data-action=""] .quantity-label { position:absolute; width:1px; height:1px; overflow:hidden; clip-path:inset(50%); }
        #stockAdjItemsTable input:disabled { background:#f5f7fa; }
        #stockAdjItemsTable small { white-space:normal; }
        .stock-table-card .stock-search-wrap { max-width:360px; }
        .stock-table-card .stock-search-wrap input { height:42px; border:1px solid #d7e3f1; border-radius:10px; }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header ph-flex">
                    <div class="ph-left">
                        <div class="ph-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                        </div>
                        <div>
                            <h3 class="page-title">Create Stock Adjustment</h3>
                            <p class="page-subtitle">Choose Increase stock or Reduce stock and enter how many units.</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-secondary" onclick="if(confirm('Reset this form and start over?')) window.location.reload();">
                        <i class="fas fa-redo-alt"></i> Reset
                    </button>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                {{-- alerts section --}}
                                @if (session('delete'))
                                <div class="alert alert-danger text-center" role="alert">
                                    {{session('delete')}} &#10004;
                                </div>
                                @endif
                                @if (session('added'))
                                <div class="alert alert-success text-center" role="alert">
                                    {{session('added')}} &#10004;
                                </div>
                                @endif
                                @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                @if (session('success') || Session::has('done'))
                                <div class="save-success-toast">
                                    <div class="toast-check"><i class="fas fa-check"></i></div>
                                    <div>
                                        <div class="toast-text">Saved!</div>
                                        <div class="toast-subtext">{{ session('success') ?? Session::get('done') }}</div>
                                    </div>
                                </div>
                                <script>
                                    setTimeout(function () {
                                        var t = document.querySelector('.save-success-toast');
                                        if (t) t.remove();
                                    }, 4000);
                                </script>
                                @endif


                        <form action="{{route('Store_StockAdjuestment')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="lines">
                                    <div class="stock-info-card">
                                        <div class="stock-info-grid">
                                            <div class="si-field">
                                                <label>Store Code</label>
                                                <select class="select form-control" id="Store_code" name="Store_code" aria-hidden="true">
                                                    @foreach($storeDta as $Data)
                                                    <option value="{{ $Data->Store_code }}" @selected(old('Store_code', optional($storeDta->last())->Store_code) == $Data->Store_code)>{{ $Data->Store_code }} — {{ $Data->Store_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="si-field">
                                                <label>Invoice No.</label>
                                                <div class="si-icon-wrap">
                                                    <i class="fas fa-file-alt"></i>
                                                    <input type="text" id="invoice_no" name="invoice_no"
                                                        value="{{$maxInvoiceNo+1}}" class="form-control"
                                                        placeholder="Invoice Number" aria-label="Invoice Number">
                                                </div>
                                            </div>
                                            <div class="si-field">
                                                <label>Date</label>
                                                <div class="si-icon-wrap">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <input type="date" id="invoice_date" name="invoice_date"
                                                        value="{{ old('invoice_date', now()->toDateString()) }}" required class="form-control" aria-label="Date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                            {{-- Item Details card --}}
                            <div class="card stock-table-card">
                                <div class="card-body">
                                <div class="adjustment-toolbar mb-3">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-list" style="color:var(--tr-blue);"></i>
                                        <strong style="font-size:15px;color:var(--tr-navy);">Item Details</strong>
                                    </div>
                                    <div class="stock-search-wrap ms-auto">
                                        <i class="fas fa-search"></i>
                                        <input type="text" id="stockItemSearch" class="form-control" placeholder="Search item code or description...">
                                    </div>
                                    <button type="submit" name="save" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save adjustment
                                    </button>
                                </div>
                                <p id="adjustmentError" class="text-danger mt-2 mb-0" role="alert"></p>
                                </div>
                            {{-- dynamicAdded table --}}
                            <div class="adjustment-grid-scroll" role="region" aria-label="Stock adjustment items" tabindex="0">
                            <table class="table" id="stockAdjItemsTable">
                                <thead><tr>
                                    <th>Item</th><th class="balance">Current stock</th>
                                    <th>Action</th><th>Quantity</th><th class="balance">New stock</th>
                                </tr></thead>
                                <tbody>
                                @php($previous = collect(old('lines', []))->keyBy('item_code'))
                                @foreach ($itemDetails as $item)
                                    @php($line = $previous->get($item->Item_code, []))
                                    <tr data-code="{{ $item->Item_code }}" data-balance="{{ $item->QTY }}">
                                        <td data-order="{{ $item->Item_code }}">
                                            <span class="item-name">{{ $item->Item_description }}</span>
                                            <span class="item-code">{{ $item->Item_code }}</span>
                                        </td>
                                        <td class="balance">{{ $item->QTY }}</td>
                                        <td><select class="form-select adjustment-action" aria-label="Action for {{ $item->Item_code }}">
                                            <option value="">Choose action</option>
                                            <option value="increase" @selected(($line['action'] ?? '') === 'increase')>Increase stock</option>
                                            <option value="reduce" @selected(($line['action'] ?? '') === 'reduce')>Reduce stock</option>
                                        </select></td>
                                        <td><label> <span class="quantity-label">Quantity</span>
                                            <input type="number" class="form-control adjustment-quantity" aria-describedby="quantity-error-{{ $loop->index }}" min="0.01" max="999999.99" step="0.01" value="{{ $line['quantity'] ?? '' }}">
                                        </label>
                                        <small id="quantity-error-{{ $loop->index }}" aria-live="polite" class="text-danger d-block"></small></td>
                                        <td class="balance"><output>{{ $item->QTY }}</output></td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                            </div>
                            <div id="stockAdjItemsCustomPager"></div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <button type="button" name="print" class="btn printReceipt btn-outline-primary" onclick="window.print();">
                                    <i class="fas fa-print"></i> Print
                                </button>
                                <button type="button" name="pawn_cancel" id="pawn_cancel"
                                    class="btn btn-outline-warning pawn_cancel"
                                    onclick="if(confirm('Discard this form and start over?')) window.location.reload();">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footer')
    </div>

  




    </div>




    
                <script src="assets/js/jquery-3.6.0.min.js"></script>
                <script src="assets/js/feather.min.js"></script>
                <script src="assets/js/toastr.min.js"></script>

                <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
                <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="assets/plugins/datatables/datatables.min.js"></script>
                <script src="assets/js/dt-custom-pager.js"></script>
                <script src="assets/js/stock-adjustment.js"></script>
                <script src="assets/js/script.js"></script>
                <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
                <script src="assets/plugins/apexchart/chart-data.js"></script>
                {{-- <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script> --}}
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
                    crossorigin="anonymous">
                </script>

</body>

</html>
@endsection
