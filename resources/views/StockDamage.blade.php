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
                <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
                <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
                <title>Stock Damage</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                .sd-search-results{
                    position:absolute; z-index:20; background:var(--tr-white); border:1px solid var(--tr-border);
                    border-radius:9px; box-shadow:0 8px 24px rgba(20,33,61,.10); width:100%; max-height:220px;
                    overflow-y:auto; display:none;
                }
                .sd-search-results .sd-result-item{ padding:9px 14px; cursor:pointer; font-size:13.5px; }
                .sd-search-results .sd-result-item:hover{ background:var(--tr-bg); }
                .sd-search-wrap{ position:relative; }
                #sdItemsTable td, #sdItemsTable th{ vertical-align:middle; }

                .sd-code-chip{
                    font-family: ui-monospace, "SF Mono", Consolas, monospace; font-size:12.5px; font-weight:600;
                    background:#FEE2E2; color:#B91C1C; border-radius:999px; padding:4px 12px; display:inline-block;
                }
                .sd-qty-chip{
                    font-weight:700; color:#B45309; background:#FEF3C7; border-radius:999px; padding:4px 12px;
                    display:inline-block; font-size:13px;
                }
                .sd-value{ font-weight:700; color:var(--tr-navy); font-variant-numeric:tabular-nums; }
                .sd-available{ font-weight:600; font-variant-numeric:tabular-nums; }
                .sd-available.low{ color:#DC2626; }
                #StockDamageTable thead th{
                    background:var(--tr-bg); text-transform:uppercase; letter-spacing:.04em; font-size:11.5px;
                    color:var(--tr-text-secondary);
                }
                #StockDamageTable td{ font-size:13.5px; vertical-align:middle; }
                #StockDamageTable tbody tr:hover{ background:var(--tr-blue-light); }

                .sd-items-card{
                    background:#f5f9ff; border:1px solid #dfeaf6; border-radius:16px;
                    padding:14px; margin-top:6px; box-shadow:0 10px 25px rgba(42,92,171,.04);
                }
                #sdItemsTable{
                    width:100%; table-layout:fixed; border:1px solid #d9e3ee; border-radius:12px;
                    overflow:hidden; background:#fff; border-collapse:separate; border-spacing:0;
                }
                #sdItemsTable thead th{
                    background:#fff; color:#2b3e5b; font-size:12px; font-weight:800;
                    padding:12px 10px; border-bottom:1px solid #d9e3ee; text-align:center;
                }
                #sdItemsTable tbody td{ padding:10px 8px; border-color:#edf1f5; vertical-align:middle; }
                #sdItemsTable .form-control{ min-height:42px; border:1px solid #d7e3f1; border-radius:10px; }
                .sd-search-wrap input, #sd_item_select{ height:42px; border:1px solid #d7e3f1; border-radius:10px; }
            </style>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Stock Damage</h3>
                                <p class="page-subtitle">Record broken, expired, or lost stock — reduces stock-on-hand automatically. For a customer returning purchased goods, use Sales Return instead.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addDamage()">
                            <i class="fas fa-plus"></i> Add Stock Damage
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success"><p>{{ $message }}</p></div>
                        @endif

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="StockDamageTable">
                                        <thead>
                                            <tr>
                                                <th>Damage No</th>
                                                <th>Date</th>
                                                <th>Store</th>
                                                <th>Qty Damaged</th>
                                                <th>Reason</th>
                                                <th>Total Value</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div id="StockDamageCustomPager"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
         </div>

        <!-- Add/Edit Stock Damage modal -->
        <div class="modal fade" id="sd-modal" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sdModalTitle">Add Stock Damage</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="javascript:void(0)" id="sdForm">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Damage No</label>
                                    <input type="text" class="form-control" id="sd_damage_no" name="damage_no" value="{{ $nextDamageNo }}" readonly>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Date</label>
                                    <input type="date" class="form-control" id="sd_damage_date" name="damage_date" required>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label class="control-label">Store</label>
                                    <select class="form-control" id="sd_store_code" name="store_code" required>
                                        <option value="">Select Store</option>
                                        @foreach($stores as $store)
                                        <option value="{{ $store->Store_code }}">{{ $store->Store_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-7 form-group sd-search-wrap">
                                    <label class="control-label">Search Item (code, name, or barcode)</label>
                                    <input type="text" class="form-control" id="sd_item_search" placeholder="Type to search…" autocomplete="off">
                                    <div class="sd-search-results" id="sd_search_results"></div>
                                </div>
                                <div class="col-md-5 form-group">
                                    <label class="control-label">Or Pick From List</label>
                                    <select class="form-control" id="sd_item_select">
                                        <option value="">Select an item…</option>
                                        @foreach($items as $it)
                                        <option value="{{ $it->Item_code }}" data-desc="{{ $it->Item_description }}" data-price="{{ $it->purchasePrice }}">
                                            {{ $it->Item_code }} — {{ $it->Item_description }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="sd-items-card">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="sdItemsTable">
                                    <colgroup>
                                        <col style="width:10%">
                                        <col style="width:20%">
                                        <col style="width:9%">
                                        <col style="width:9%">
                                        <col style="width:10%">
                                        <col style="width:22%">
                                        <col style="width:10%">
                                        <col style="width:5%">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>Item Code</th>
                                            <th>Description</th>
                                            <th>Unit Price</th>
                                            <th>Available</th>
                                            <th>Qty Damaged</th>
                                            <th>Reason</th>
                                            <th>Net Value</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="sdItemsBody">
                                        <tr id="sdEmptyRow">
                                            <td colspan="8" class="text-center text-muted">No items added yet — search or pick from the list above.</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="text-end"><strong>Total</strong></td>
                                            <td colspan="2"><strong id="sdTotalValue">0.00</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            </div>

                            <button type="submit" class="btn btn-primary" id="sd-save-btn">
                                <i class="fas fa-check"></i> Save
                            </button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>

                    <script src="assets/js/dt-custom-pager.js"></script>
                    <script>
                    $(document).ready(function () {
                        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                        $('#sd_damage_date').val(new Date().toISOString().slice(0, 10));

                        var StockDamageTable = $('#StockDamageTable').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ url('stock_damage') }}",
                            columns: [
                                {
                                    data: 'Damage_no', name: 'Damage_no',
                                    render: function (data) { return '<span class="sd-code-chip">' + data + '</span>'; }
                                },
                                { data: 'Damage_date', name: 'Damage_date' },
                                {
                                    data: 'Store_code', name: 'Store_code',
                                    render: function (data) { return data || '<span class="text-muted">—</span>'; }
                                },
                                {
                                    data: 'Total_qty', name: 'Total_qty',
                                    render: function (data) { return '<span class="sd-qty-chip">' + parseFloat(data).toFixed(2) + '</span>'; }
                                },
                                {
                                    data: 'reasons', name: 'reasons', orderable: false, searchable: false,
                                    render: function (data) { return data || '<span class="text-muted">—</span>'; }
                                },
                                {
                                    data: 'Total_value', name: 'Total_value',
                                    render: function (data) { return '<span class="sd-value">' + parseFloat(data).toFixed(2) + '</span>'; }
                                },
                                { data: 'action', name: 'action', orderable: false, searchable: false },
                            ],
                            order: [[1, 'desc']],
                            pageLength: 15,
                            lengthChange: false,
                        });

                        $('#StockDamageTable_wrapper').addClass('dt-collapsed');
                        DTCustomPager.init(StockDamageTable, '#StockDamageCustomPager');

                        // ── Item search ──────────────────────────────────
                        var searchTimer;
                        $('#sd_item_search').on('input', function () {
                            var q = $(this).val();
                            clearTimeout(searchTimer);
                            if (q.length < 2) { $('#sd_search_results').hide(); return; }
                            searchTimer = setTimeout(function () {
                                $.ajax({
                                    type: 'POST',
                                    url: "{{ url('ItemSearch') }}",
                                    data: { q: q },
                                    success: function (items) {
                                        var $box = $('#sd_search_results').empty();
                                        if (!items.length) {
                                            $box.append('<div class="sd-result-item text-muted">No items found</div>');
                                        } else {
                                            items.forEach(function (it) {
                                                var $row = $('<div class="sd-result-item"></div>')
                                                    .text(it.Item_code + ' — ' + it.Item_description)
                                                    .data('item', it);
                                                $box.append($row);
                                            });
                                        }
                                        $box.show();
                                    }
                                });
                            }, 300);
                        });

                        $(document).on('click', '.sd-result-item', function () {
                            var it = $(this).data('item');
                            if (!it) return;
                            addItemRow(it.Item_code, it.Item_description, it.purchasePrice || 0);
                            $('#sd_item_search').val('');
                            $('#sd_search_results').hide();
                        });

                        // ── Item dropdown (alternative to search) ────────
                        $('#sd_item_select').on('change', function () {
                            var $opt = $(this).find('option:selected');
                            var code = $opt.val();
                            if (!code) return;
                            addItemRow(code, $opt.data('desc'), $opt.data('price') || 0);
                            $(this).val('');
                        });

                        $(document).on('click', function (e) {
                            if (!$(e.target).closest('.sd-search-wrap').length) {
                                $('#sd_search_results').hide();
                            }
                        });

                        function addItemRow(code, description, price) {
                            $('#sdEmptyRow').remove();
                            var rowId = 'row_' + Date.now() + Math.floor(Math.random() * 1000);
                            var row = '<tr id="' + rowId + '">' +
                                '<td>' + code + '<input type="hidden" class="sd-code" value="' + code + '"></td>' +
                                '<td><input type="hidden" class="sd-desc" value="' + description + '">' + description + '</td>' +
                                '<td><input type="number" step="0.01" min="0" class="form-control sd-price" value="' + parseFloat(price).toFixed(2) + '" required></td>' +
                                '<td class="sd-available text-end">…</td>' +
                                '<td><input type="number" step="0.01" min="0.01" class="form-control sd-qty" value="1" required></td>' +
                                '<td><input type="text" class="form-control sd-reason" placeholder="e.g. Broken in storage" required></td>' +
                                '<td class="sd-net text-end">0.00</td>' +
                                '<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger" onclick="$(\'#' + rowId + '\').remove(); recalcTotal();"><i class="far fa-trash-alt"></i></button></td>' +
                                '</tr>';
                            $('#sdItemsBody').append(row);
                            recalcTotal();

                            var $row = $('#' + rowId);
                            $.ajax({
                                type: 'POST',
                                url: "{{ url('stock_damage/item-balance') }}",
                                data: { item_code: code },
                                success: function (res) {
                                    var balance = parseFloat(res.balance) || 0;
                                    $row.find('.sd-available')
                                        .text(balance.toFixed(2))
                                        .toggleClass('low', balance <= 0)
                                        .attr('data-balance', balance);
                                    $row.find('.sd-qty').attr('max', balance);
                                }
                            });
                        }

                        $(document).on('input', '.sd-qty, .sd-price', function () {
                            var $row = $(this).closest('tr');
                            var qty = parseFloat($row.find('.sd-qty').val()) || 0;
                            var price = parseFloat($row.find('.sd-price').val()) || 0;
                            $row.find('.sd-net').text((qty * price).toFixed(2));
                            recalcTotal();
                        });

                        window.recalcTotal = function () {
                            var total = 0;
                            $('.sd-net').each(function () { total += parseFloat($(this).text()) || 0; });
                            $('#sdTotalValue').text(total.toFixed(2));
                        };

                        window.addDamage = function () {
                            $('#sdForm')[0].reset();
                            $('#sdModalTitle').text('Add Stock Damage');
                            $('#sdItemsBody').html('<tr id="sdEmptyRow"><td colspan="7" class="text-center text-muted">No items added yet — search above to add one.</td></tr>');
                            $('#sd_damage_date').val(new Date().toISOString().slice(0, 10));
                            $('#sdTotalValue').text('0.00');
                            $('#sd-modal').modal('show');
                        };

                        window.editFunc = function (id) {
                            $.ajax({
                                type: 'POST',
                                url: "{{ url('stock_damage/edit') }}",
                                data: { id: id },
                                success: function (res) {
                                    $('#sdModalTitle').text('Edit Stock Damage');
                                    $('#sd_damage_no').val(res.sum.Damage_no);
                                    $('#sd_damage_date').val(res.sum.Damage_date);
                                    $('#sd_store_code').val(res.sum.Store_code);
                                    $('#sdItemsBody').empty();
                                    res.details.forEach(function (d) {
                                        addItemRow(d.Item_code, d.Item_description, d.Unit_price);
                                        var $last = $('#sdItemsBody tr').last();
                                        $last.find('.sd-qty').val(d.QTY).trigger('input');
                                        $last.find('.sd-reason').val(d.Reason);
                                    });
                                    $('#sd-modal').modal('show');
                                }
                            });
                        };

                        window.deleteFunc = function (id) {
                            if (!confirm('Delete this damage record? This restores the stock quantity.')) return;
                            $.ajax({
                                type: 'POST',
                                url: "{{ url('stock_damage/delete') }}",
                                data: { id: id },
                                success: function () { StockDamageTable.ajax.reload(); }
                            });
                        };

                        $('#sdForm').submit(function (e) {
                            e.preventDefault();

                            var items = [];
                            $('#sdItemsBody tr').each(function () {
                                var $row = $(this);
                                if (!$row.find('.sd-code').length) return;
                                items.push({
                                    item_code: $row.find('.sd-code').val(),
                                    item_description: $row.find('.sd-desc').val(),
                                    unit_price: $row.find('.sd-price').val(),
                                    qty: $row.find('.sd-qty').val(),
                                    reason: $row.find('.sd-reason').val(),
                                });
                            });

                            if (!items.length) {
                                alert('Add at least one item before saving.');
                                return;
                            }

                            $.ajax({
                                type: 'POST',
                                url: "{{ url('stock_damage/store') }}",
                                data: {
                                    damage_no: $('#sd_damage_no').val(),
                                    damage_date: $('#sd_damage_date').val(),
                                    store_code: $('#sd_store_code').val(),
                                    items: items,
                                },
                                success: function () {
                                    window.location.reload();
                                },
                                error: function (xhr) {
                                    var msg = 'Something went wrong while saving.';
                                    if (xhr.responseJSON) {
                                        if (xhr.responseJSON.errors) {
                                            msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                                        } else if (xhr.responseJSON.message) {
                                            msg = xhr.responseJSON.message;
                                        }
                                    }
                                    alert(msg);
                                }
                            });
                        });
                    });
                    </script>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>

</body>
@endsection

</html>
