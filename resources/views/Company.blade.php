
@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
          <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" >
                <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
                {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script> --}}
                <link  href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
                <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
                <title>Company</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
                <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
                <style>
    /* Company page — green accent (this page's identity colour), blue
       stays the app's dominant colour everywhere else. */
    :root {
        --co-green:      #16A34A;
        --co-green-light:#EAFBEF;
        --co-navy:       #14213D;
        --co-border:     #E5EAF2;
        --co-surface:    #F6F8FC;
        --co-text-sub:   #667085;
    }

    .co-header {
        display: flex; align-items: flex-start; justify-content: space-between;
        flex-wrap: wrap; gap: 14px; margin-bottom: 22px;
    }
    .co-header-left { display: flex; align-items: center; }
    .co-icon {
        width: 46px; height: 46px; border-radius: 12px;
        background: var(--co-green-light); color: var(--co-green);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; margin-right: 14px;
    }
    .co-header h3.page-title { font-size: 1.4rem; font-weight: 700; color: var(--co-navy); margin: 0; }
    .co-header p { font-size: .83rem; color: var(--co-text-sub); margin-top: 2px; }

    .co-total-row {
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
        border-bottom: 1px solid var(--co-border); padding-bottom: 20px; flex-wrap: wrap;
    }
    .co-total-row-left { display: flex; align-items: center; gap: 12px; }
    .co-stat-dot {
        width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
        background: var(--co-green-light); color: var(--co-green);
        display: flex; align-items: center; justify-content: center;
    }
    .co-stat-num { font-size: 1.4rem; font-weight: 700; color: var(--co-navy); line-height: 1; }
    .co-stat-lbl { font-size: .75rem; color: var(--co-text-sub); margin-top: 2px; font-weight: 500; }

    /* Search box (DataTables' own filter, relocated next to the stat) */
    .co-total-row .dataTables_filter label {
        display: flex; align-items: center; gap: 0; margin: 0;
        font-size: 0; /* hide the "Search:" text label, keep just the input */
    }
    .co-total-row .dataTables_filter input {
        border: 1.5px solid var(--co-border); border-radius: 9px;
        padding: 9px 14px; font-size: .83rem; color: #475569; background: #fff;
        width: 230px; outline: none;
    }
    .co-total-row .dataTables_filter input:focus { border-color: var(--co-green); }

    /* "Show N entries" as its own dedicated row, above the (scrollable,
       many-column) table — kept out of table-responsive so it's always
       visible without needing to scroll sideways to find it. */
    .co-show-row {
        display: flex; align-items: center; gap: 8px;
        padding: 14px 24px; font-size: .8rem; color: var(--co-text-sub); font-weight: 500;
    }
    /* NOTE: initComplete() below moves .dataTables_length out of
       #Company_wrapper and into #companyShowRow (.co-show-row), so these
       rules must target its new location — a selector scoped to
       #Company_wrapper here would silently never match. */
    .co-show-row .dataTables_length {
        display: flex !important; align-items: center !important; gap: 8px;
    }
    .co-show-row .dataTables_length label {
        display: flex !important; align-items: center !important; gap: 8px !important;
        flex-direction: row !important; flex-wrap: nowrap !important;
        margin: 0 !important; white-space: nowrap;
    }
    .co-show-row .dataTables_length select {
        border: 1.5px solid var(--co-border) !important; border-radius: 8px !important;
        padding: 6px 30px 6px 12px !important; font-size: .85rem !important; color: #334155 !important;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23667085' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 8px center !important;
        background-size: 15px !important;
        -webkit-appearance: none; -moz-appearance: none; appearance: none !important;
        min-width: 68px; cursor: pointer;
    }
    .co-show-row .dataTables_length select:focus {
        outline: none !important; border-color: var(--co-green) !important;
        box-shadow: 0 0 0 3px rgba(22,163,74,.10) !important;
    }

    .btn-success {
        background: var(--co-green) !important; border-color: var(--co-green) !important;
        font-weight: 600 !important; transition: all .2s ease;
    }
    .btn-success:hover { background: #128A3E !important; border-color: #128A3E !important; transform: translateY(-1px); }

    /* Flat table header, matching the rest of the app's table style */
    #Company thead tr { background: var(--co-surface) !important; }
    #Company thead th {
        background: var(--co-surface) !important;
        border: none !important; border-bottom: 1px solid var(--co-border) !important;
        padding: 13px 16px !important; font-weight: 600 !important;
        text-transform: uppercase !important; font-size: .72rem !important; letter-spacing: .05em !important;
        color: var(--co-navy) !important;
    }
    #Company tbody td { padding: 12px 16px !important; vertical-align: middle !important; color: #475569 !important; }

    /* Pagination — rounded, current page filled in green (this page's accent).
       Scoped with #Company_wrapper (the table's unique DataTables wrapper id)
       so this always outranks the app-wide blue pagination rule in
       theme-redesign.css, regardless of stylesheet load order. */
    #Company_wrapper .dataTables_info { color: var(--co-text-sub) !important; font-size: .8rem !important; padding-top: 0 !important; }
    #Company_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important; border: 1.5px solid var(--co-border) !important;
        padding: 6px 12px !important; margin-left: 6px !important; color: var(--co-text-sub) !important;
        background: #fff !important;
    }
    #Company_wrapper .dataTables_paginate .paginate_button.current,
    #Company_wrapper .dataTables_paginate .paginate_button.current:link,
    #Company_wrapper .dataTables_paginate .paginate_button.current:visited,
    #Company_wrapper .dataTables_paginate .paginate_button.current:hover,
    #Company_wrapper .dataTables_paginate .paginate_button.current:active,
    #Company_wrapper .dataTables_paginate .paginate_button.current:focus,
    #Company_wrapper .dataTables_paginate span .paginate_button.current,
    #Company_wrapper .page-item.active .page-link {
        background: var(--co-green) !important;
        border-color: var(--co-green) !important;
        color: #fff !important;
        box-shadow: none !important;
    }
    #Company_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: var(--co-green-light) !important; border-color: var(--co-green) !important; color: var(--co-navy) !important;
    }
    #Company_wrapper .dataTables_paginate .paginate_button.disabled { opacity: .5 !important; }

    .modal-header { background: var(--co-surface) !important; border-bottom: 1px solid var(--co-border) !important; }
    .form-control:focus { border-color: var(--co-green) !important; box-shadow: 0 0 0 3px rgba(22,163,74,.10) !important; }

    /* ══════════════════════════════════════════════════
       ANIMATION & TRANSITIONS
    ══════════════════════════════════════════════════ */
    @media (prefers-reduced-motion: no-preference) {
        @keyframes coFadeUp {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .co-header, .card { animation: coFadeUp .5s cubic-bezier(.16,1,.3,1) both; }
        .co-header { animation-delay: 0s; }
        .card { animation-delay: .1s; }
    }
    @media (prefers-reduced-motion: reduce) {
        .co-header, .card, .modal-content { animation: none !important; }
    }

    .btn-success { transition: background .15s ease, border-color .15s ease, transform .1s ease; }
    .btn-success:active { transform: scale(.97); }
    #Company tbody tr { transition: background .15s ease; }
    .co-stat-dot { transition: transform .15s ease; }
    .co-total-row:hover .co-stat-dot { transform: scale(1.06); }
    #Company_wrapper .dataTables_paginate .paginate_button { transition: background .15s ease, border-color .15s ease, color .15s ease; }
</style>
            </head>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="co-header">
                        <div class="co-header-left">
                            <div class="co-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21h18M6 21V8l6-4 6 4v13M10 21v-6h4v6"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Company</h3>
                                <p>Manage your company profile and business details</p>
                            </div>
                        </div>
                        <a class="btn btn-success" onClick="add()" href="javascript:void(0)">
                            <i class="fas fa-plus"></i> Create Company
                        </a>
                    </div>

                    <div class="container-fluid px-0">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-body co-total-row">
                                <div class="co-total-row-left">
                                    <div class="co-stat-dot">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21h18M6 21V8l6-4 6 4v13M10 21v-6h4v6"/></svg>
                                    </div>
                                    <div>
                                        <div class="co-stat-num">{{ $totalCompanies }}</div>
                                        <div class="co-stat-lbl">Total Companies</div>
                                    </div>
                                </div>
                                <div id="companySearchSlot"></div>
                            </div>

                            <div class="co-show-row" id="companyShowRow"></div>

                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="Company">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Company code</th>
                                                <th>Company Number</th>
                                                <th>Address</th>
                                                <th>Contact Number</th>
                                                <th>Fax Number</th>
                                                <th>One Number</th>
                                                <th>Two Number</th>
                                                <th>Email</th>
                                                <th>Note</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- boostrap employee model -->
                    <div class="modal fade" id="Item-modal" >
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Company</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="javascript:void(0)"  id="ItemForm" name="ItemForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="id" id="id">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label"> Company Code</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control" id="co_code" name="co_code" placeholder="" maxlength="15" required="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Company Name </label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control" id="name" name="name" placeholder="" maxlength="50" required="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Address</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control" id="address" name="address" placeholder="" maxlength="100" required="">
                                            </div>
                                        </div>
                                            <div class="form-group">
                                                <label style="font-weight: bold">Contact Numbers</label>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="co_number" id="co_number" placeholder="Primary Number" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="one_number" id="one_number" placeholder="Secondary Number">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control" name="two_number" id="two_number" placeholder="Optional Number">
                                                    </div>
                                                </div>
                                            </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Fax Number</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control" id="fax_number" name="fax_number" placeholder="" required="" maxlength="15">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Email</label>
                                                <div class="col-sm-12">
                                                <input type="email" class="form-control" id="email" name="email" placeholder="" required="" maxlength="50">
                                            </div>
                                        </div>
                                          <div class="mb-3">
                                            <label for="exampleFormControlTextarea1" class="form-label"> Extra Note</label>
                                            <textarea class="form-control" id="Note" name="Note" rows="3"></textarea>
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
                    <!-- end bootstrap model -->
                    <script type="text/javascript">
                    $(document).ready( function () {
                        $.ajaxSetup({
                            headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $('#Company').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ url('Company') }}",
                            columns: [
                                { data: 'id', name: 'id' },
                                { data: 'co_code', name: 'co_code' },
                                { data: 'name', name: 'name' },
                                { data: 'address', name: 'address' },
                                { data: 'co_number', name: 'co_number' },
                                 { data: 'one_number', name: 'one_number' },
                                { data: 'two_number', name: 'two_number' },

                                { data: 'fax_number', name: 'fax_number' },
                                { data: 'email', name: 'email' },
                                { data: 'Note', name: 'Note' },
                                { data: 'action', name: 'action', orderable: false},
                            ],
                            order: [[0, 'desc']],
                            initComplete: function () {
                                var $wrapper = $('#Company_wrapper');
                                // "Show N entries" — its own dedicated row above
                                // the table, so it stays visible even though the
                                // table itself scrolls sideways (11 columns).
                                $wrapper.find('.dataTables_length').appendTo('#companyShowRow');
                                // Search box — next to the Total Companies stat.
                                $wrapper.find('.dataTables_filter input')
                                    .attr('placeholder', 'Search...');
                                $wrapper.find('.dataTables_filter').appendTo('#companySearchSlot');
                            }
                        });
                    });

                    function add(){
                        $('#ItemForm').trigger("reset");
                        $('#ItemModal').html("Add Item");
                        $('#Item-modal').modal('show');
                        $('#id').val('');
                    }

                    function editFunc(id){
                        $.ajax({
                            type:"POST",
                            url: "{{ url('Companyedit') }}",
                            data: { id: id },
                            dataType: 'json',
                            success: function(res){
                                $('#ItemModal').html("Edit Item");
                                $('#Item-modal').modal('show');
                                $('#id').val(res.id);
                                $('#co_code').val(res.co_code);
                                $('#name').val(res.name);
                                $('#address').val(res.address);
                                $('#co_number').val(res.co_number);
                                $('#fax_number').val(res.fax_number);
                                $('#email').val(res.email);
                                $('#two_number').val(res.two_number);
                                $('#one_number').val(res.one_number);
                                $('#Note').val(res.Note);
                            }
                        });
                    }

                    function deleteFunc(id){
                        if (confirm("Delete Record?") == true) {
                            var id = id;
                            // ajax
                            $.ajax({
                                type:"POST",
                                url: "{{ url('Companydelete') }}",
                                data: { id: id },
                                dataType: 'json',
                                success: function(res){
                                    var oTable = $('#Company').dataTable();
                                    oTable.fnDraw(false);
                                }
                            });
                        }
                    }

                    $('#ItemForm').submit(function(e) {
                        e.preventDefault();
                        var formData = new FormData(this);
                        $.ajax({
                            type:'POST',
                            url: "{{ url('Companystore')}}",
                            data: formData,
                            cache:false,
                            contentType: false,
                            processData: false,
                            success: (data) => {
                                $("#Item-modal").modal('hide');
                                var oTable = $('#Company').dataTable();
                                oTable.fnDraw(false);
                                $("#btn-save").html('Submit');
                                $("#btn-save"). attr("disabled", false);
                            },
                            error: function(data){
                                console.log(data);
                            }
                        });
                    });
                    </script>

<script> data-cfasync="false" src="../../../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js">
</script>
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
{{-- <script src="assets/plugins/select2/js/select2.min.js"></script> --}}
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>

<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="assets/plugins/apexchart/chart-data.js"></script>
</body>
@endsection
</html>