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
                <title>Color Details</title>
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
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="13.5" cy="6.5" r=".5"/><circle cx="17.5" cy="10.5" r=".5"/><circle cx="8.5" cy="7.5" r=".5"/><circle cx="6.5" cy="12.5" r=".5"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.26-.296-.436-.694-.436-1.125a1.64 1.64 0 0 1 1.648-1.64h1.936c2.31 0 4.19-1.87 4.19-4.187C22 6.13 17.5 2 12 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Color Details</h3>
                                <p class="page-subtitle">Manage product colors</p>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid px-0">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <a id="addColorBtn" class="btn btn-primary" onClick="add()" href="javascript:void(0)" style="display:none;">
                            <i class="fas fa-plus"></i> Add Color
                        </a>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                            <table class="table table-bordered" id="MColor">
                                <thead>
                                    <tr>
                                        <th>Color Code</th>
										<th>Color Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                                </div>
                                <div id="ColorCustomPager"></div>
                            </div>
                        </div>
                    </div>


                    <!-- boostrap employee model -->
                    <div class="modal fade" id="Store-modal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Color Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
									<div class="card-body shadow p-3 mb-5 bg-body-tertiary rounded">
                                    <form action="javascript:void(0)"  id="StoreForm" name="StoreForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="id" id="id">
                                        <div class="form-group">
                                            <label for="name" class="control-label">Color Code</label>
                                                <div class="col-sm-12">
                                                <input type="number" class="form-control" id="Color_code" name="Color_code" value="00{{ $maxCustomer+1}}" placeholder="Enter a Color Code" maxlength="15" required="">
                                            </div>
                                        </div>  
                                        <div class="form-group">
                                            <label for="name" class="control-label">Color Name</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control" id="Color_name" name="Color_name" placeholder="Enter a Color Name" maxlength="20" required="">
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

                        var MColorTable = $('#MColor').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ url('MColor') }}",
                            columns: [
                                { data: 'Color_code', name: 'Color_code' },
                                { data: 'Color_name', name: 'Color_name' },
                                { data: 'action', name: 'action', orderable: false},
                            ],
                            order: [[0, 'desc']],
                            pageLength: 15,
                            lengthChange: false,
                            initComplete: function () {
                                dtFixToolbar(this, '#addColorBtn');
                            }
                        });

                        $('#MColor_wrapper').addClass('dt-collapsed');
                        DTCustomPager.init(MColorTable, '#ColorCustomPager');
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
                            url: "{{ url('Color_edit') }}",
                            data: { id: id },
                            dataType: 'json',
                            success: function(res){
                                $('#StoreModal').html("Edit Item");
                                $('#Store-modal').modal('show');
								$('#id').val(res.id);
                                $('#Color_code').val(res.Color_code);
                                $('#Color_name').val(res.Color_name);
                            }
                        });
                    }  
                     
                    function deleteFunc(id){
                        if (confirm("Delete Record?") == true) {
                            var id = id;
                            // ajax
                            $.ajax({
                                type:"POST",
                                url: "{{ url('Color_delete') }}",
                                data: { id: id },
                                dataType: 'json',
                                success: function(res){
                                    var oTable = $('#MColor').dataTable();
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
                            url: "{{ url('store_Color')}}",
                            data: formData,
                            cache:false,
                            contentType: false,
                            processData: false,
                            success: (data) => {
                                $("#Store-modal").modal('hide');
                                var oTable = $('#MColor').dataTable();
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