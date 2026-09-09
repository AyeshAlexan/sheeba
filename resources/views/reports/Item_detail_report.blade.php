<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Item Details Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        .sub-table {
            font-size: 0.85em;
            margin: 0;
        }
        .sub-table thead {
            background-color: #e9ecef;
        }
        .sub-table th, .sub-table td {
            padding: 4px 8px !important;
            white-space: nowrap;
        }

        /* The clickable badge/button */
        .btn-set-toggle {
            cursor: pointer;
            background: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 3px 10px;
            font-size: 0.82em;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background 0.2s;
        }
        .btn-set-toggle:hover {
            background: #0b5ed7;
        }
        .btn-set-toggle .arrow {
            display: inline-block;
            transition: transform 0.25s;
            font-style: normal;
        }
        .btn-set-toggle.open .arrow {
            transform: rotate(90deg);
        }

        /* Sub-table wrapper — hidden by default */
        .set-item-detail {
            display: none;
            margin-top: 6px;
        }

        /* Highlight Set Item rows */
        tr.is-set-item > td {
            background-color: #f0f7ff !important;
        }

        /* Expanded child row */
        .child-row > td {
            background-color: #f8f9fa !important;
            padding: 10px 20px !important;
        }
    </style>
</head>
<body>

<div class="container my-4">
    <h3 class="text-center mb-4">Item Details Report</h3>

    <div class="text-end mb-2">
        <button class="btn btn-primary" onclick="printTablefun()">🖨️ Print Table</button>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered display nowrap" id="receiptTable" style="width:100%">
            <thead class="table-dark text-center">
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

                {{-- Main row --}}
                <tr class="{{ $item->category == 'Set Item' ? 'is-set-item' : '' }}"
                    data-item-id="{{ $item->id }}">
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
                            @php
                                $matchedPackages = $PackageItem->where('pkg_code', $item->Item_code);
                            @endphp

                            @if($matchedPackages->isNotEmpty())
                                {{-- Toggle Button --}}
                                <button class="btn-set-toggle"
                                        data-target="pkg-{{ $item->id }}">
                                    <span class="arrow">▶</span>
                                    Set Items
                                    <span class="badge bg-light text-dark ms-1">
                                        {{ $matchedPackages->count() }}
                                    </span>
                                </button>

                                {{-- Hidden sub-table --}}
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
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    $(document).ready(function () {

        // ── DataTable init ──────────────────────────────────────────
        $('#receiptTable').DataTable({
            dom: 'Bfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print'],
            scrollX: true,
            pageLength: 1000,
            columnDefs: [
                { targets: 0, searchable: false, orderable: false,
                  render: function (data, type, row, meta) { return meta.row + 1; }
                },
                { targets: 2, visible: false, searchable: false }
            ]
        });

        // ── Toggle sub-table on button click ───────────────────────
        $(document).on('click', '.btn-set-toggle', function () {
            const targetId = $(this).data('target');
            const $detail  = $('#' + targetId);
            const isOpen   = $detail.is(':visible');

            // Slide toggle
            $detail.slideToggle(250);

            // Rotate arrow & track open state
            $(this).toggleClass('open', !isOpen);
        });

    });

    // ── Print function ─────────────────────────────────────────────
    function printTablefun() {
        // Temporarily show all sub-tables for printing
        $('.set-item-detail').show();

        let printContent = document.getElementById("receiptTable").outerHTML;

        // Re-hide after capturing
        $('.set-item-detail').hide();

        let newWin = window.open("");
        newWin.document.write(`
            <html>
            <head>
                <title>Item Details Report</title>
                <link rel="stylesheet"
                      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
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
</body>
</html>