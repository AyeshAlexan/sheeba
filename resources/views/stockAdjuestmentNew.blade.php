@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Stock Adjustment</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <style>
        .stock-table-card { background:#f5f9ff; border:1px solid #dfeaf6; border-radius:16px; margin-top:18px; padding:14px; box-shadow:0 10px 25px rgba(42,92,171,.04); }
        .stock-table-card > .card-body { padding:0; }
        .stock-item-details-table { width:100%; table-layout:fixed; border:1px solid #d9e3ee; border-radius:12px; overflow:hidden; background:#fff; border-collapse:separate; border-spacing:0; }
        .stock-item-details-table thead th { background:#fff; color:#2b3e5b; font-size:12px; font-weight:800; padding:12px 10px; border-bottom:1px solid #d9e3ee; text-align:center; }
        .stock-item-details-table tbody td { padding:10px 8px; border-color:#edf1f5; vertical-align:middle; }
        .stock-item-details-table .form-control { min-height:42px; border:1px solid #d7e3f1; border-radius:10px; }
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
                            <p class="page-subtitle">Adjust manual stock counts against system quantities</p>
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
                                    <div class="stock-info-card">
                                        <div class="stock-info-grid">
                                            <div class="si-field">
                                                <label>Store Code</label>
                                                <select class="select form-control" id="Store_code" name="Store_code" aria-hidden="true">
                                                    @foreach($storeDta as $Data)
                                                    <option selected="selected" value="{{ $Data->Store_code}}">{{ $Data->Store_code}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="si-field">
                                                <label>Store Name</label>
                                                <select class="select form-control" id="StoreDescription" name="StoreDescription" aria-hidden="true">
                                                    @foreach($storeDta as $Data)
                                                    <option selected="selected" value="{{ $Data->Store_name}}">{{ $Data->Store_name}}</option>
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
                                                        class="form-control" aria-label="Date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                            {{-- Item Details card --}}
                            <div class="card stock-table-card">
                                <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-list" style="color:var(--tr-blue);"></i>
                                        <strong style="font-size:15px;color:var(--tr-navy);">Item Details</strong>
                                    </div>
                                    <div class="stock-search-wrap">
                                        <i class="fas fa-search"></i>
                                        <input type="text" id="stockItemSearch" class="form-control" placeholder="Search item code or description...">
                                    </div>
                                </div>
                            {{-- dynamicAdded table --}}
                            <table class="table table-bordered stock-item-details-table" id="stockAdjItemsTable">
                                <thead class="thead-light">
                                    <tr>
                                        {{-- <th style="width:15%; text-align: center;">Category</th> --}}
                                        <th style="width:10%; text-align: center;">Item Code</th>
                                        <th style="width:25%; text-align: center;">Description</th>
                                        <th style="width:10%; text-align: center;">Price</th>
                                        <th style="width:10%; text-align: center;">Total Qty</th>
                                        <th style="width:15%; text-align: center;">Manual Stock</th>
                                        <th style="width:10%; text-align: center;">Stock Variance</th>
                                        <th style="width:20%; text-align: center;">Net Value</th>
                                    </tr>
                                </thead>
                              <tbody>
                                @foreach ($itemDetails as $key => $ItemData)
                                <tr>
                                    <td>
                                        <input type="text" name="item_code[]" class="form-control" placeholder="item Code" value="{{ $ItemData->Item_code }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="item_description[]" class="form-control" placeholder="item Description" value="{{ $ItemData->Item_description }}" readonly>
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" placeholder="Sale Price" name="saleprice[]" value="{{ $ItemData->saleprice }}" readonly>
                                    </td>
                                    <td>
                                        <input class="form-control qty" type="text" placeholder="QTY" name="qty[]" value="{{ $ItemData->QTY }}" readonly>
                                    </td>
                                    <td>
                                        <input class="form-control qtyOut" type="number" placeholder="QTY Out" name="qtyOut[]" value="">
                                    </td>
                                    <td>
                                        <input class="form-control stockAdjustment" type="text" placeholder="Stock Adjustment" name="Stock_adjuestment[]" value="0" readonly>
                                    </td>
                                    <td>
                                        <input class="form-control netValue" type="text" placeholder="Net Value" name="net_value[]" value="0" readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                            </table>
                            <div id="stockAdjItemsCustomPager"></div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <button type="submit" name="save" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save
                                </button>
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




    
    <script src="assets/js/dt-custom-pager.js"></script>
    <script>
        $(document).ready(function () {
            // The item code/description cells hold their data in readonly
            // <input value="..."> attributes, not plain cell text, so
            // DataTables' default text-based search would never match them —
            // filter against the actual input values instead.
            $.fn.dataTable.ext.search.push(function (settings, searchData, dataIndex, rowData, counter) {
                if (settings.nTable.id !== 'stockAdjItemsTable') {
                    return true;
                }
                var q = $('#stockItemSearch').val().toLowerCase();
                if (!q) {
                    return true;
                }
                var row = stockAdjItemsDt.row(dataIndex).node();
                var itemCode = $(row).find('input[name="item_code[]"]').val() || '';
                var itemDescription = $(row).find('input[name="item_description[]"]').val() || '';
                return (itemCode + ' ' + itemDescription).toLowerCase().indexOf(q) !== -1;
            });

            var stockAdjItemsDt = $('#stockAdjItemsTable').DataTable({
                dom: 't',
                pageLength: 10,
                lengthChange: false,
                ordering: true,
                info: false,
            });

            $('#stockAdjItemsTable_wrapper').addClass('dt-collapsed');
            DTCustomPager.init(stockAdjItemsDt, '#stockAdjItemsCustomPager');

            $(document).on('keyup', '#stockItemSearch', function () {
                stockAdjItemsDt.draw();
            });
        });
    </script>

    {{-- form default date set for today --}}
    <script>
        var dateObj = new Date();
        document.getElementById('invoice_date').value = dateObj.toISOString().slice(0, 10);
    </script>

    {{-- CSRF Token --}}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>


<script>
$(document).ready(function() {
    $('.qtyOut').on('input', function () {
        var $row = $(this).closest('tr');

        var qty = parseFloat($row.find('.qty').val()) || 0;
        var qtyOut = parseFloat($(this).val()) || 0;

        let adjustment = 0;

        // ✅ If both are negative, add their absolute values
        if (qty < 0 && qtyOut < 0) {
            adjustment = Math.abs(qty) - qtyOut;
        } else {
            // ✅ All other cases, regular subtraction
            adjustment = qty - qtyOut;
        }

        $row.find('.stockAdjustment').val(adjustment);

        // Net value calculation
        var salePrice = parseFloat($row.find('input[name="saleprice[]"]').val()) || 0;
        var netValue = adjustment * salePrice;
        $row.find('.netValue').val(netValue.toFixed(2));
    });
});
</script>






                <script src="assets/js/jquery-3.6.0.min.js"></script>
                <script src="assets/js/feather.min.js"></script>
                <script src="assets/js/toastr.min.js"></script>

                <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
                <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="assets/plugins/datatables/datatables.min.js"></script>
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