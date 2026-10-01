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
                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
                <title>Bin Card</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                #t_item_movements thead th{
                    text-transform:uppercase; letter-spacing:.04em; font-size:11.5px !important;
                    color:var(--tr-text-secondary) !important; background:var(--tr-bg) !important;
                }
                #t_item_movements tbody tr:hover{ background:var(--tr-blue-light) !important; }
            </style>

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
                                <h3 class="page-title">Bin Card</h3>
                                <p class="page-subtitle">Every movement for a single item — in, out, and running balance.</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary" onclick="printTablefun()">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>

                    <div class="container-fluid px-0">
                        <div class="card">
                            <div class="card-body">
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                </div>
                                @endif

                                <form action="{{ route('get_bin_card_report') }}" method="GET" class="row g-2 mb-3 align-items-end">
                                    <div class="col-md-2">
                                        <label class="form-label mb-0 small">From Date</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label mb-0 small">To Date</label>
                                        <input type="date" name="to_date" id="date" class="form-control" value="{{ $toDate ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Item Code</label>
                                        <input type="text" class="form-control" id="item_code" name="item_code" list="item_code_list" autocomplete="off" placeholder="Search item code" value="{{ $itemCode ?? request('item_code') }}">
                                        <datalist id="item_code_list">
                                            @foreach($allItemData as $itemData)
                                            <option value="{{ $itemData->Item_code }}"></option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-0 small">Item Description</label>
                                        <input type="text" class="form-control" id="item_description" name="item_description" list="item_description_list" autocomplete="off" placeholder="Search item description" value="{{ request('item_description') }}">
                                        <datalist id="item_description_list">
                                            @foreach($allItemData as $itemData)
                                            <option value="{{ $itemData->Item_description }}"></option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="t_item_movements">
                                        @if(isset($stockDetails) && $stockDetails != null)
                                        <caption>Item: {{ $itemName }} &nbsp;|&nbsp; {{ $fromDate }} &nbsp;→&nbsp; {{ $toDate }}</caption>
                                        @endif
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Tr No</th>
                                                <th>Invoice No</th>
                                                <th>Transaction Type</th>
                                                <th>Item</th>
                                                <th>Cust. Code</th>
                                                <th>Customer Name</th>
                                                <th>In</th>
                                                <th>Out</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($stockDetails) && $stockDetails != null)
                                                @foreach ($stockDetails as $data)
                                                <tr>
                                                    <td>{{ $data->dDate }}</td>
                                                    <td>{{ $data->trans_no }}</td>
                                                    <td>{{ $data->invoice_no }}</td>
                                                    <td>{{ $data->trans_code }}</td>
                                                    <td>{{ $data->item_name }}</td>
                                                    <td>{{ $data->customer_code }}</td>
                                                    <td>{{ $data->customer_name }}</td>
                                                    <td>{{ $data->qun_in }}</td>
                                                    <td>{{ $data->qun_out }}</td>
                                                    <td>
                                                        @if($data->qun_in > $data->qun_out)
                                                            {{ $data->qun_in }}
                                                        @else
                                                            -{{ $data->qun_out }}
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            <tr class="fw-bold" style="background-color:#f4f6f9;">
                                                <td colspan="7" class="text-end">Total Quantity</td>
                                                <td>{{ $quain ?? 0 }}</td>
                                                <td>-{{ $quaout ?? 0 }}</td>
                                                <td>{{ $balance ?? 0 }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div id="binCardCustomPager"></div>
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
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/dt-custom-pager.js"></script>

<script>
    var toDateInput = document.getElementById('date');
    if (!toDateInput.value) {
        toDateInput.valueAsDate = new Date();
    }

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const itemMap = @json($allItemData->map(function ($item) {
        return [
            'code' => $item->Item_code,
            'description' => $item->Item_description,
        ];
    }));

    $(document).ready(function () {
        var binCardTable = $('#t_item_movements').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
            pageLength: 15,
            lengthChange: false
        });

        $('#t_item_movements_wrapper').addClass('dt-collapsed');
        DTCustomPager.init(binCardTable, '#binCardCustomPager');

        $('#item_code').on('input', function () {
            const typedCode = $(this).val();
            const match = itemMap.find(i => i.code === typedCode);
            if (match) {
                $('#item_description').val(match.description);
            } else if (typedCode === '') {
                $('#item_description').val('');
            }
        });

        $('#item_description').on('input', function () {
            const typedDesc = $(this).val();
            const match = itemMap.find(i => i.description === typedDesc);
            if (match) {
                $('#item_code').val(match.code);
            } else if (typedDesc === '') {
                $('#item_code').val('');
            }
        });
    });

    function printTablefun() {
        var divToPrint = document.getElementById("t_item_movements");
        var newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
</script>

</body>
@endsection

</html>
