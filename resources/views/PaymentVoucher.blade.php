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
    <title>Payment Voucher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .vch-header { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; margin-bottom:20px; flex-wrap:wrap; }
        .vch-header-left { display:flex; align-items:center; gap:14px; }
        .vch-header-icon { width:44px; height:44px; border-radius:12px; background:var(--tr-blue-light); color:var(--tr-blue); display:flex; align-items:center; justify-content:center; font-size:18px; flex:none; }
        .vch-header h3 { font-size:22px; font-weight:800; color:var(--tr-text); margin:0; }
        .vch-header p { font-size:13px; color:var(--tr-text-secondary); margin:2px 0 0; }
        .vch-add-btn { background:var(--tr-success); border-color:var(--tr-success); color:#fff; font-weight:700; border-radius:10px; padding:10px 18px; }
        .vch-add-btn:hover { background:#128a3e; border-color:#128a3e; color:#fff; }

        .vch-filter-card { background:#fff; border:1px solid var(--tr-border); border-radius:14px; padding:16px 18px; margin-bottom:18px; box-shadow:0 1px 2px rgba(20,33,61,.04); }
        .vch-filter-row { display:grid; grid-template-columns: 1.3fr 1fr 1fr 1fr; gap:14px; align-items:center; }
        @media (max-width: 992px) { .vch-filter-row { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 576px) { .vch-filter-row { grid-template-columns: 1fr; } }

        .vch-daterange { display:flex; align-items:center; gap:8px; border:1px solid var(--tr-border); border-radius:10px; padding:0 10px; background:#fff; }
        .vch-daterange i { color:var(--tr-text-muted); font-size:13px; }
        .vch-daterange input[type="date"] { border:none; padding:9px 2px; font-size:13px; color:var(--tr-text); min-width:0; flex:1; background:transparent; }
        .vch-daterange input[type="date"]:focus { outline:none; }
        .vch-daterange span.sep { color:var(--tr-text-muted); }

        .vch-select-wrap { position:relative; }
        .vch-select-wrap i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--tr-text-muted); font-size:12px; pointer-events:none; }
        .vch-select-wrap select { width:100%; padding:10px 12px 10px 32px !important; border:1px solid var(--tr-border); border-radius:10px; font-size:13px; color:var(--tr-text); background:#fff; appearance:none; }

        .vch-search-wrap { position:relative; }
        .vch-search-wrap i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--tr-text-muted); font-size:12px; }
        .vch-search-wrap input { width:100%; padding:10px 12px 10px 32px !important; border:1px solid var(--tr-border); border-radius:10px; font-size:13px; }

        .vch-table-card { background:#fff; border:1px solid var(--tr-border); border-radius:14px; padding:20px 20px 16px; box-shadow:0 1px 2px rgba(20,33,61,.04); }
        .vch-table-card .table-responsive { border:1px solid var(--tr-border); border-radius:12px; overflow:hidden; }
        .vch-table-card table.vch-table { margin-bottom:0; }
        .vch-table thead th { background:#fff !important; color:var(--tr-text-secondary); font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.03em; border-bottom:1px solid var(--tr-border) !important; padding:14px 16px; }
        .vch-table tbody td { padding:13px 16px; border-color:var(--tr-border); vertical-align:middle; font-size:13.5px; color:var(--tr-text); }
        .vch-table tbody tr:hover { background:#FAFBFC; }

        .vch-acct-badge { display:inline-flex; align-items:center; gap:8px; }
        .vch-acct-icon { width:26px; height:26px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-size:11px; flex:none; }
        .vch-acct-icon.is-cash { background:#E6F7EC; color:var(--tr-success); }
        .vch-acct-icon.is-bank { background:var(--tr-blue-light); color:var(--tr-blue); }

        .vch-amount-pill { display:inline-block; background:#E6F7EC; color:var(--tr-success); font-weight:700; padding:5px 12px; border-radius:20px; font-size:13px; }

        .vch-table-card .dataTables_wrapper .dataTables_filter,
        .vch-table-card .dataTables_wrapper .dataTables_length,
        .vch-table-card .dataTables_wrapper .dataTables_info,
        .vch-table-card .dataTables_wrapper .dataTables_paginate { display:none; }
    </style>
</head>

<body>

    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">

                <div class="vch-header">
                    <div class="vch-header-left">
                        <div class="vch-header-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <div>
                            <h3>Payment Voucher</h3>
                            <p>Track and manage your payment vouchers</p>
                        </div>
                    </div>
                    <a class="btn vch-add-btn" onClick="add()" href="javascript:void(0)"><i class="fas fa-plus"></i> Add Payment Voucher</a>
                </div>

                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                <div class="vch-filter-card">
                    <div class="vch-filter-row">
                        <div class="vch-daterange">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="date" id="filterFromDate">
                            <span class="sep">&ndash;</span>
                            <input type="date" id="filterToDate">
                        </div>
                        <div class="vch-select-wrap">
                            <i class="fas fa-receipt"></i>
                            <select id="filterVoucher">
                                <option value="">All Vouchers</option>
                                @foreach($voucherNos as $v)
                                    <option value="{{ $v }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="vch-select-wrap">
                            <i class="fas fa-building-columns"></i>
                            <select id="filterAccount">
                                <option value="">All Accounts</option>
                                @foreach($Amount as $DepartmentData)
                                    <option value="{{ $DepartmentData->code }}">{{ $DepartmentData->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="vch-search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" id="filterSearch" class="form-control" placeholder="Search by description, voucher no...">
                        </div>
                    </div>
                </div>

                <div class="vch-table-card">
                    <div class="table-responsive">
                        <table class="table table-bordered vch-table" id="TPaymentVoucher">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Voucher No</th>
                                    <th>DR Account</th>
                                    <th>CR Account</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="TPaymentVoucherCustomPager"></div>
                </div>

            </div>
            @include('layouts.footer')
        </div>
    </div>

    <!-- boostrap employee model -->
    <div class="modal fade" id="Store-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment Voucher </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)"  id="StoreForm" name="StoreForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                      <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-text" id="btnGroupAddon2">Voucher No :
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                                <input type="text" id="invoice_no" name="invoice_no"
                                value="{{$maxCustomer+1}}" class="form-control"
                                    placeholder="Invoice Number:" aria-label="Invoice Number:"
                                    aria-describedby="btnGroupAddon2">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-text" id="btnGroupAddon2">Date :
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                                <input type="date" id="date" name="date"
                                    value="" class="form-control"
                                    placeholder="Invoice Number:" aria-label="Invoice Number:"
                                    aria-describedby="btnGroupAddon2">
                            </div>
                        </div>
                    </div>

                    <br>
                    <br>
                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">CR Account</label>
                            <div class="col-lg-7">
                              <div class="input-group">
                                  <select   class="select form-control" name="cramount" id="cramount"
                                  aria-hidden="true">
                                  <option value="">Please Select</option>
                                  @foreach($Amount as $DepartmentData)
                                  <option value="{{ $DepartmentData->description}}">
                                      {{ $DepartmentData->description }}</option>
                                  @endforeach
                              </select>

                              </div>
                            </div>
                            <div class="col-lg-3">
                                <select
                                class="select form-control" name="crcode" id="crcode"
                                aria-hidden="true">
                                <option value="">Please Select</option>
                                @foreach($Amount as $DepartmentData)
                                <option value="{{ $DepartmentData->code}}">
                                    {{ $DepartmentData->code }}</option>
                                @endforeach
                            </select>
                            </div>
                          </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">DR Account</label>
                            <div class="col-lg-7">
                              <div class="input-group">
                                 <select
                                  class="select form-control" name="dramount" id="dramount"
                                 aria-hidden="true">
                                 <option value="">Please Select</option>
                                 @foreach($Amount as $DepartmentData)
                                 <option value="{{ $DepartmentData->description}}">
                                     {{ $DepartmentData->description }}</option>
                                 @endforeach
                             </select>
                              </div>
                            </div>

                            <div class="col-lg-3">
                                <select
                                class="select form-control" name="drcode" id="drcode"
                                aria-hidden="true">
                                <option value="">Please Select</option>
                                @foreach($Amount as $DepartmentData)
                                <option value="{{ $DepartmentData->code}}">
                                    {{ $DepartmentData->code }}</option>
                                @endforeach
                            </select>
                            </div>
                          </div>

                        <div class="form-group row">
                            <label class="col-form-label col-lg-2">Description</label>
                            <div class="col-lg-7">
                              <div class="input-group">
                                 <textarea id="description" name="description" class="form-control" required="" placeholder="Enter the Expense Note "></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-form-label col-lg-2">Amount </label>
                            <div class="col-lg-7">
                              <div class="input-group">
                                <input type="number" class="form-control" placeholder="Amount"  id="amount" name="amount"
                                 aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                </div>
                              </div>
                            </div>
                          </div>

                        <div class="row">
                            <div class="col-md-6">
                                <input type="hidden" class="form-control" id="OC"
                                name="OC" placeholder="Enter a Department Name"
                                value="{{ Auth::user()->username}}" readonly>
                            </div>

                            <div class="col-md-6">
                               <input type="hidden" class="form-control" id="BC"
                               name="BC" placeholder="Enter a Department Name"
                               value="{{ Auth::user()->BC}}" readonly>
                           </div>
                       </div>

                        <div class="col-sm-offset-2 col-sm-10"><br/>
                            <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
                        </div>
                    </form>

                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>

     <script>
        $(document).ready(function () {
            $('#Store-modal').on('show.bs.modal', function (e) {
                var currentDate = new Date().toISOString().split('T')[0];
                $('#date').val(currentDate);
            });
        });
    </script>



    <!-- end bootstrap model -->
    <script src="assets/js/dt-custom-pager.js"></script>
    <script type="text/javascript">
    var acctMeta = {};
    @foreach($Amount as $DepartmentData)
    acctMeta["{{ addslashes($DepartmentData->description) }}"] = {{ $DepartmentData->bankaccount ? 'true' : 'false' }};
    @endforeach

    function acctRender(data) {
        if (!data) return '';
        var isBank = !!acctMeta[data];
        var icon = isBank ? 'fa-building-columns' : 'fa-wallet';
        var cls = isBank ? 'is-bank' : 'is-cash';
        return '<span class="vch-acct-badge"><span class="vch-acct-icon ' + cls + '"><i class="fas ' + icon + '"></i></span>' + data + '</span>';
    }

    function amountRender(data) {
        var n = parseFloat(data) || 0;
        return '<span class="vch-amount-pill">LKR ' + n.toLocaleString('en-LK', {minimumFractionDigits: 2}) + '</span>';
    }

    function toLocalISODate(d) {
        var y = d.getFullYear();
        var m = String(d.getMonth() + 1).padStart(2, '0');
        var day = String(d.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + day;
    }

    $(document).ready( function () {
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // If we arrived here from a "which voucher was this?" link
        // (e.g. Cash & Cheque Transaction report), filter straight
        // down to that one voucher instead of showing the full list.
        let urlParams = new URLSearchParams(window.location.search);
        let jumpToVoucher = urlParams.get('voucher');

        if (jumpToVoucher) {
            $('#filterSearch').val(jumpToVoucher);
        } else {
            var today = new Date();
            var firstDay = toLocalISODate(new Date(today.getFullYear(), today.getMonth(), 1));
            var lastDay = toLocalISODate(new Date(today.getFullYear(), today.getMonth() + 1, 0));
            $('#filterFromDate').val(firstDay);
            $('#filterToDate').val(lastDay);
        }

        var table = $('#TPaymentVoucher').DataTable({
            processing: true,
            serverSide: true,
            dom: 't',
            ajax: {
                url: "{{ url('PaymentVoucher') }}",
                data: function (d) {
                    d.from_date = $('#filterFromDate').val();
                    d.to_date = $('#filterToDate').val();
                    d.voucher_no = $('#filterVoucher').val();
                    d.account = $('#filterAccount').val();
                }
            },
            columns: [
                { data: 'date', name: 'date' },
                { data: 'invoice_no', name: 'invoice_no' },
                { data: 'dramount', name: 'dramount', render: acctRender },
                { data: 'cramount', name: 'cramount', render: acctRender },
                { data: 'description', name: 'description' },
                { data: 'amount', name: 'amount', render: amountRender },
                { data: 'action', name: 'action', orderable: false},
            ],
            order: [[0, 'desc']],
            language: { emptyTable: 'No payment vouchers found.' },
            search: jumpToVoucher ? { search: jumpToVoucher } : undefined
        });

        DTCustomPager.init(table, '#TPaymentVoucherCustomPager');

        $('#filterFromDate, #filterToDate, #filterVoucher, #filterAccount').on('change', function () {
            table.draw();
        });
        $('#filterSearch').on('keyup', function () {
            table.search(this.value).draw();
        });
    });

    function add(){
        $('#StoreForm').trigger("reset");
        $('#StoreModal').html("Add Store");
        $('#Store-modal').modal('show');
        $('#id').val('');
    }

    function editFunc(id){
        $.ajax({
            type:"POST",
            url: "{{ url('UpdatePaymentVoucher') }}",
            data: { id: id },
            dataType: 'json',
            success: function(res){
                $('#StoreModal').html("Edit Item");
                $('#Store-modal').modal('show');
                $('#id').val(res.id);
                $('#invoice_no').val(res.invoice_no);
                $('#date').val(res.date);
                $('#cramount').val(res.cramount);
                $('#crcode').val(res.crcode);
                $('#dramount').val(res.dramount);
                $('#drcode').val(res.drcode);
                $('#description').val(res.description);
                $('#amount').val(res.amount);
            }
        });
    }


    function deleteFunc(id){
        if (confirm("Delete Record?") == true) {
            var id = id;
            // ajax
            $.ajax({
                type:"POST",
                url: "{{ url('DeletePaymentVoucher') }}",
                data: { id: id },
                dataType: 'json',
                success: function(res){
                    var oTable = $('#TPaymentVoucher').dataTable();
                    oTable.fnDraw(false);
                }
            });
        }
    }

    $('#StoreForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type:'POST',
            url: "{{ url('addPaymentVoucher')}}",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#Store-modal").modal('hide');
                var oTable = $('#TPaymentVoucher').dataTable();
                oTable.fnDraw(false);
                $("#btn-save").html('Submit');
                $("#btn-save"). attr("disabled", false);
            },
            error: function(xhr){
                let msg = 'Something went wrong while saving the voucher.';
                if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.description) {
                    msg = xhr.responseJSON.errors.description[0];
                }
                alert(msg);
            }
        });
    });
    </script>
<script>
    $(document).ready(function () {
        $('#cramount').on('change', function () {
            let category = $('#cramount').val();
            $.ajax({
                        url: "{{ route('show_voucher_ajax') }}",
                        method: 'GET',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            category: category
                        },
                        success: function (res) {
                            if (res.status == 'success') {
                                var itemsCodeSelected = $('#crcode');
                                itemsCodeSelected.empty();
                                $.each(res.data, function (index, item) {
                                    itemsCodeSelected.append($('<option>', {
                                        value: item.code,
                                        text: item.code
                                    }));
                                });
                            }
                        },
                        error: function (err) {
                            $('.errMsgContainer').html('');
                            let error = err.responseJSON;
                            $.each(error.errors, function (index, value) {
                                $('.errMsgContainer').append(
                                    '<span class="text-danger">' + value +
                                    '<span>' + '<br>');
                            });
                        }
                    });
        });
    });
</script>


<script>
    $(document).ready(function () {
        $('#dramount').on('change', function () {
            let amount = $('#dramount').val();
            $.ajax({
                        url: "{{ route('show_dr_voucher_ajax') }}",
                        method: 'GET',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            amount: amount
                        },
                        success: function (res) {
                            if (res.status == 'success') {
                                var itemsSelected = $('#drcode');
                                itemsSelected.empty();
                                $.each(res.data, function (index, item1) {
                                    itemsSelected.append($('<option>', {
                                        value: item1.code,
                                        text: item1.code,
                                    }));
                                });
                            }
                        },
                        error: function (err) {
                            $('.errMsgContainer').html('');
                            let error = err.responseJSON;
                            $.each(error.errors, function (index, value) {
                                $('.errMsgContainer').append(
                                    '<span class="text-danger">' + value +
                                    '<span>' + '<br>');
                            });
                        }
                    });
        });
    });
</script>




<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>

<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="assets/plugins/apexchart/chart-data.js"></script>

</body>
@endsection

</html>
