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
                <title> Bank Details</title>
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
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            </div>
                            <div>
                                <h3 class="page-title">Bank Details</h3>
                                <p class="page-subtitle">Manage banks used for cheque and account transactions</p>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid px-0">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <a id="addBankBtn" class="btn btn-primary" onClick="add()" href="javascript:void(0)" style="display:none;">
                            <i class="fas fa-plus"></i> Add Bank
                        </a>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                            <table class="table table-bordered" id="BankDeltails">
                                <thead>
                                    <tr>
                                        <th>Bank Code</th>
										<th>Bank Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                                </div>
                                <div id="BankCustomPager"></div>
                            </div>
                        </div>
                    </div>


                    <!-- boostrap employee model -->
                    <div class="modal fade" id="Store-modal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="StoreModal">Add Bank</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="javascript:void(0)"  id="StoreForm" name="StoreForm" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="id" id="id">
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Bank Code</label>
                                            <input type="number" class="form-control" id="code" name="code" placeholder="Enter a Bank Code" maxlength="15" required="">
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Bank Name</label>
                                            <input type="text" class="form-control" id="description" name="description" placeholder="Enter a Bank Name" maxlength="20" required="">
                                        </div>

                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Operator</label>
                                                <input type="text" class="form-control" id="OC"
                                                name="OC" placeholder="Operator"
                                                value="{{ Auth::user()->username}}" readonly>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Branch</label>
                                               <input type="text" class="form-control" id="BC"
                                               name="BC" placeholder="Branch"
                                               value="{{ Auth::user()->Branch}}" readonly>
                                           </div>
                                       </div>

                                        <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
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

                        var BankDeltailsTable = $('#BankDeltails').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ url('BankDeltails') }}",
                            columns: [
                                { data: 'code', name: 'code' },
                                { data: 'description', name: 'description' },
                                { data: 'action', name: 'action', orderable: false},
                            ],
                            order: [[0, 'desc']],
                            pageLength: 15,
                            lengthChange: false,
                            initComplete: function () {
                                dtFixToolbar(this, '#addBankBtn');
                            }
                        });

                        $('#BankDeltails_wrapper').addClass('dt-collapsed');
                        DTCustomPager.init(BankDeltailsTable, '#BankCustomPager');
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
                            url: "{{ url('Bank_edit') }}",
                            data: { id: id },
                            dataType: 'json',
                            success: function(res){
                                $('#StoreModal').html("Edit Item");
                                $('#Store-modal').modal('show');
								$('#id').val(res.id);
                                $('#code').val(res.code);
                                $('#description').val(res.description);
                                $('#OC').val(res.OC);
                                $('#BC').val(res.BC);
                            }
                        });
                    }  
                     
                    function deleteFunc(id){
                        if (confirm("Delete Record?") == true) {
                            var id = id;
                            // ajax
                            $.ajax({
                                type:"POST",
                                url: "{{ url('Bank_delete') }}",
                                data: { id: id },
                                dataType: 'json',
                                success: function(res){
                                    var oTable = $('#BankDeltails').dataTable();
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
                            url: "{{ url('Bank_store')}}",
                            data: formData,
                            cache:false,
                            contentType: false,
                            processData: false,
                            success: (data) => {
                                $("#Store-modal").modal('hide');
                                var oTable = $('#BankDeltails').dataTable();
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


