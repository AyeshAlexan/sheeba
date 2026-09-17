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
                <title> Store Details</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
                <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
            </head>

            <body>


        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="page-header ph-flex">
                        <div class="ph-left">
                            <div class="ph-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21h18M6 21V8l6-4 6 4v13M10 21v-6h4v6"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Store Details</h3>
                                <p class="page-subtitle">Manage stores and warehouse locations</p>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid px-0">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <a id="addStoreBtn" class="btn btn-primary" onClick="add()" href="javascript:void(0)" style="display:none;">
                            <i class="fas fa-plus"></i> Add Store
                        </a>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                            <table class="table table-bordered" id="Store">
                                <thead>
                                    <tr>
                                        <th>Store Code</th>
										<th>Store Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                                </div>
                                <div id="StoreCustomPager"></div>
                            </div>
                        </div>
                    </div>


                    <!-- boostrap employee model -->
                    <div class="modal fade" id="Store-modal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Store Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
									<div class="card-body shadow p-3 mb-5 bg-body-tertiary rounded">
                                    <form action="javascript:void(0)"  id="StoreForm" name="StoreForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="id" id="id">
                                        <div class="form-group">
                                            <label for="name" class="control-label">Store Code</label>
                                                <div class="col-sm-12">
                                                <input type="number" class="form-control" id="Store_code" name="Store_code" placeholder="Enter a Store Code" maxlength="15" required="">
                                            </div>
                                        </div>  
                                        <div class="form-group">
                                            <label for="name" class="control-label">Store Name</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control" id="Store_name" name="Store_name" placeholder="Enter a Store Name" maxlength="20" required="">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="hidden" class="form-control" id="Branch"
                                                name="Branch" placeholder="Enter a Department Name"
                                                value="{{ Auth::user()->Branch}}" readonly>                                   
                                            </div>
       
                                            <div class="col-md-6">
                                               <input type="hidden" class="form-control" id="BranchCode"
                                               name="BranchCode" placeholder="Enter a Department Name"
                                               value="{{ Auth::user()->BC}}" readonly>
                                           </div>
                                       </div>
                                      
                                        <div class="col-sm-offset-2 col-sm-10"><br/>
                                            <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
                                        </div>
                                    </form>

                                </div>
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

                        var StoreTable = $('#Store').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ url('Store') }}",
                            columns: [
                                { data: 'Store_code', name: 'Store_code' },
                                { data: 'Store_name', name: 'Store_name' },
                                { data: 'action', name: 'action', orderable: false},
                            ],
                            order: [[0, 'desc']],
                            pageLength: 15,
                            lengthChange: false,
                            initComplete: function () {
                                dtFixToolbar(this, '#addStoreBtn');
                            }
                        });

                        $('#Store_wrapper').addClass('dt-collapsed');
                        DTCustomPager.init(StoreTable, '#StoreCustomPager');
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
                            url: "{{ url('Store_edit') }}",
                            data: { id: id },
                            dataType: 'json',
                            success: function(res){
                                $('#StoreModal').html("Edit Item");
                                $('#Store-modal').modal('show');
								$('#id').val(res.id);
                                $('#Store_code').val(res.Store_code);
                                $('#Store_name').val(res.Store_name);
                            }
                        });
                    }  
                     
                    function deleteFunc(id){
                        if (confirm("Delete Record?") == true) {
                            var id = id;
                            // ajax
                            $.ajax({
                                type:"POST",
                                url: "{{ url('Store_delete') }}",
                                data: { id: id },
                                dataType: 'json',
                                success: function(res){
                                    var oTable = $('#Store').dataTable();
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
                            url: "{{ url('Store_store')}}",
                            data: formData,
                            cache:false,
                            contentType: false,
                            processData: false,
                            success: (data) => {
                                $("#Store-modal").modal('hide');
                                var oTable = $('#Store').dataTable();
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