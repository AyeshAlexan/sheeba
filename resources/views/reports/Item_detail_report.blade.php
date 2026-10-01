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
                <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
                <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
                <title>Item Details Report</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                .sub-table{ font-size:.85em; margin:0; }
                .sub-table thead{ background-color:#e9ecef; }
                .sub-table th, .sub-table td{ padding:4px 8px !important; white-space:nowrap; }
                .btn-set-toggle{
                    cursor:pointer; background:var(--tr-blue); color:#fff; border:none; border-radius:6px;
                    padding:3px 10px; font-size:.82em; display:inline-flex; align-items:center; gap:5px; transition:background .2s;
                }
                .btn-set-toggle:hover{ opacity:.9; }
                .btn-set-toggle .arrow{ display:inline-block; transition:transform .25s; font-style:normal; }
                .btn-set-toggle.open .arrow{ transform:rotate(90deg); }
                .set-item-detail{ display:none; margin-top:6px; }
                tr.is-set-item > td{ background-color:var(--tr-blue-light) !important; }
                .child-row > td{ background-color:#f8f9fa !important; padding:10px 20px !important; }

                #receiptTable thead th{
                    text-transform:uppercase; letter-spacing:.04em; font-size:11.5px !important;
                    color:var(--tr-text-secondary) !important; background:var(--tr-bg) !important;
                }
                #receiptTable tbody tr:hover{ background:var(--tr-blue-light) !important; }
            </style>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Item Details Report</h3>
                                <p class="page-subtitle">Every item in the catalog — pricing and, for Set Items, which items make up the set.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print Table
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered display nowrap" id="receiptTable" style="width:100%">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Category</th>
                                                <th style="display:none">Id</th>
                                                <th>Item Code</th>
                                                <th>Item Description</th>
                                                <th>Purchase Price</th>
                                                <th>Sales Price</th>
                                                <th>Border Price</th>
                                                <th>Item Set</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoice as $key => $item)
                                            <tr class="{{ $item->category == 'Set Item' ? 'is-set-item' : '' }}" data-item-id="{{ $item->id }}">
                                                <td></td>
                                                <td>{{ $item->category }}</td>
                                                <td style="display:none">{{ $item->id }}</td>
                                                <td>{{ $item->Item_code }}</td>
                                                <td>{{ $item->Item_description }}</td>
                                                <td class="text-end">{{ $item->purchasePrice }}</td>
                                                <td class="text-end">{{ $item->saleprice }}</td>
                                                <td class="text-end">{{ $item->Credit }}</td>
                                                <td>
                                                    @if($item->category == 'Set Item')
                                                        @php $matchedPackages = $PackageItem->where('pkg_code', $item->Item_code); @endphp
                                                        @if($matchedPackages->isNotEmpty())
                                                        <button class="btn-set-toggle" data-target="pkg-{{ $item->id }}">
                                                            <span class="arrow">▶</span> Set Items
                                                            <span class="badge bg-light text-dark ms-1">{{ $matchedPackages->count() }}</span>
                                                        </button>
                                                        <div class="set-item-detail" id="pkg-{{ $item->id }}">
                                                            <table class="table table-sm table-bordered sub-table mt-2">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Set Item Code</th>
                                                                        <th>Item Code</th>
                                                                        <th>Description</th>
                                                                        <th>Qty</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($matchedPackages as $i => $pkg)
                                                                    <tr>
                                                                        <td>{{ $i + 1 }}</td>
                                                                        <td>{{ $pkg->pkg_code }}</td>
                                                                        <td>{{ $pkg->item_code }}</td>
                                                                        <td>{{ $pkg->item_description }}</td>
                                                                        <td>{{ $pkg->qty }}</td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @else
                                                        <span class="text-muted fst-italic">No packages</span>
                                                        @endif
                                                    @else
                                                    <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div id="itemDetailCustomPager"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
         </div>

                    <script>
                    $(document).ready(function () {
                        var itemDetailTable = $('#receiptTable').DataTable({
                            dom: 'Bfrtip',
                            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print'],
                            scrollX: true,
                            pageLength: 15,
                            lengthChange: false,
                            columnDefs: [
                                { targets: 0, searchable: false, orderable: false,
                                  render: function (data, type, row, meta) { return meta.row + 1; }
                                },
                                { targets: 2, visible: false, searchable: false }
                            ]
                        });

                        $('#receiptTable_wrapper').addClass('dt-collapsed');
                        DTCustomPager.init(itemDetailTable, '#itemDetailCustomPager');

                        $(document).on('click', '.btn-set-toggle', function () {
                            const targetId = $(this).data('target');
                            const $detail  = $('#' + targetId);
                            const isOpen   = $detail.is(':visible');
                            $detail.slideToggle(250);
                            $(this).toggleClass('open', !isOpen);
                        });
                    });

                    function printTablefun() {
                        $('.set-item-detail').show();
                        let printContent = document.getElementById("receiptTable").outerHTML;
                        $('.set-item-detail').hide();

                        let newWin = window.open("");
                        newWin.document.write(`
                            <html>
                            <head>
                                <title>Item Details Report</title>
                                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
                                <style>
                                    body { padding: 20px; font-family: sans-serif; }
                                    table { width: 100%; border-collapse: collapse; }
                                    th, td { border: 1px solid #ccc; padding: 6px 10px; font-size: 12px; }
                                    thead { background-color: #343a40; color: #fff; }
                                    .sub-table thead { background-color: #e9ecef; color: #000; }
                                    .btn-set-toggle { display: none; }
                                    .set-item-detail { display: block !important; }
                                    tr.is-set-item > td { background-color: #f0f7ff !important; }
                                </style>
                            </head>
                            <body>
                                <h3 style="text-align:center; margin-bottom:16px;">Item Details Report</h3>
                                ${printContent}
                            </body>
                            </html>
                        `);
                        newWin.document.close();
                        newWin.focus();
                        newWin.print();
                        newWin.close();
                    }
                    </script>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/dt-custom-pager.js"></script>

</body>
@endsection

</html>
