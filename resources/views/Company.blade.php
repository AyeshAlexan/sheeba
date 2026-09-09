
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
    /* Custom Styling for a Modern Look */
    .page-wrapper {
        background-color: #f8f9fa;
        padding-top: 20px;
    }

    .page-title {
        font-weight: 700;
        color: #334155;
        font-size: 1.5rem;
    }

    /* Table Styling */
    .card-body {
        background: #ffffff;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
    }

    #Company thead tr {
        background-color: #10b981 !important; /* Modern Emerald Green */
        color: white;
    }

    #Company thead th {
        border: none;
        padding: 15px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
    }

    #Company tbody td {
        padding: 12px 15px;
        vertical-align: middle;
        color: #475569;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Button Styling */
    .btn-success {
        background-color: #10b981;
        border-color: #10b981;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background-color: #059669;
        transform: translateY(-1px);
    }

    /* Modal Styling */
    .modal-content {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e2e8f0;
    }

    .form-label, .control-label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 5px;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 10px;
    }

    .form-control:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
</style>
            </head>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header">
                        <div class="row align-items-center">

                        </div>
                    </div>

                    <div class="container mt-2">
                        <div class="row">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">

                                </div>
                                <div class="pull-right mb-2" >
                                    <a class="btn btn-success card-body shadow p-3 mb-5" onClick="add()" href="javascript:void(0)"> Create Company</a>
                                </div>
                            </div>
                        </div>
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                             <div class="col">
                                <h3 class="page-title">Company</h3>
                                <hr>
                            </div>

                            <div class="card-body shadow p-3 mb-5 bg-body-tertiary rounded">
                              <div class="table-responsive">
                            <table class="table table-bordered" id="Company">
                                <thead>
                                    <tr style="background-color:hsl(147, 50%, 47%);">
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
                            order: [[0, 'desc']]
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