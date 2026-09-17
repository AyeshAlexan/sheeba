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
                <title> User Role </title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
                <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
                <style>
                    /* ══════════════════════════════════════════════════
                       ANIMATION & TRANSITIONS
                    ══════════════════════════════════════════════════ */
                    @media (prefers-reduced-motion: no-preference) {
                        @keyframes urFadeUp {
                            from { opacity: 0; transform: translateY(14px); }
                            to   { opacity: 1; transform: translateY(0); }
                        }
                        .page-header.ph-flex, .card {
                            animation: urFadeUp .5s cubic-bezier(.16,1,.3,1) both;
                        }
                        .page-header.ph-flex { animation-delay: 0s; }
                        .card { animation-delay: .1s; }
                    }
                    @media (prefers-reduced-motion: reduce) {
                        .page-header.ph-flex, .card, .modal-content { animation: none !important; }
                    }

                    .btn-primary { transition: background .15s ease, border-color .15s ease, transform .1s ease; }
                    .btn-primary:active { transform: scale(.97); }
                    #Userrole tbody tr { transition: background .15s ease; }
                    .dataTables_wrapper .dataTables_paginate .paginate_button { transition: background .15s ease, border-color .15s ease, color .15s ease; }
                </style>
            </head>

            <body>


        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41L11 3.83V3H3v8h.83l9.58 9.59a2 2 0 0 0 2.83 0l4.35-4.35a2 2 0 0 0 0-2.83z"/><circle cx="6.5" cy="6.5" r="1"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">User Roles</h3>
                                <p class="page-subtitle">Define roles and permission levels for system users</p>
                            </div>
                        </div>
                        <a class="btn btn-primary" onClick="add()" href="javascript:void(0)">
                            <i class="fas fa-plus"></i> Add Role
                        </a>
                    </div>

                    <div class="container-fluid px-0">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="Userrole">
                                        <thead>
                                            <tr>
                                                <th>Role Code</th>
                                                <th>Role Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div id="UserroleCustomPager"></div>
                            </div>
                        </div>


                    <!-- boostrap employee model -->
                    <div class="modal fade" id="Store-modal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add User Roles</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="javascript:void(0)"  id="StoreForm" name="StoreForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="id" id="id">
                                        <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Role Code</label>
                                                <div class="col-sm-12">
                                                <input type="number" class="form-control" id="role_code" name="role_code" placeholder="Enter a Role Code" maxlength="15" required="">
                                            </div>
                                        </div>  
                                        <div class="form-group">
                                            <label for="name" class="col-sm-4 control-label">Role Name</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control" id="role_name" name="role_name" placeholder="Enter a Role Name" maxlength="20" required="">
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
                </div>
                @include('layouts.footer')
            </div>
         </div>
                    <!-- end bootstrap model -->
                    <script src="assets/js/dt-custom-pager.js"></script>
                    <script type="text/javascript">
                    $(document).ready( function () {
                        $.ajaxSetup({
                            headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        var UserroleTable = $('#Userrole').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ url('Userrole') }}",
                            columns: [
                                { data: 'role_code', name: 'role_code' },
                                { data: 'role_name', name: 'role_name' },
                                { data: 'action', name: 'action', orderable: false},
                            ],
                            order: [[0, 'desc']],
                            pageLength: 15,
                            lengthChange: false,
                        });

                        $('#Userrole_wrapper').addClass('dt-collapsed');
                        DTCustomPager.init(UserroleTable, '#UserroleCustomPager');
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
                            url: "{{ url('role_edit') }}",
                            data: { id: id },
                            dataType: 'json',
                            success: function(res){
                                $('#StoreModal').html("Edit Item");
                                $('#Store-modal').modal('show');
								$('#id').val(res.id);
                                $('#role_code').val(res.role_code);
                                $('#role_name').val(res.role_name);
                                $('#BC').val(res.BC);
                                $('#OC').val(res.OC);
                            }
                        });
                    }  
                     
                    function deleteFunc(id){
                        if (confirm("Delete Record?") == true) {
                            var id = id;
                            // ajax
                            $.ajax({
                                type:"POST",
                                url: "{{ url('role_delete') }}",
                                data: { id: id },
                                dataType: 'json',
                                success: function(res){
                                    var oTable = $('#Userrole').dataTable();
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
                            url: "{{ url('role_store')}}",
                            data: formData,
                            cache:false,
                            contentType: false,
                            processData: false,
                            success: (data) => {
                                $("#Store-modal").modal('hide');
                                var oTable = $('#Userrole').dataTable();
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