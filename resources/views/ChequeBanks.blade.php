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
                <link  href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
                <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
                <title> Account</title>
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
                                <h3 class="page-title">Account</h3>
                                <p class="page-subtitle">Your company's own bank accounts used to issue and deposit cheques</p>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid px-0">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <a id="addChequeBankBtn" class="btn btn-primary" onClick="add()" href="javascript:void(0)" style="display:none;">
                            <i class="fas fa-plus"></i> Add Account
                        </a>

                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                            <table class="table table-bordered" id="ChequeBanksTable">
                                <thead>
                                    <tr>
                                        <th>Bank Name</th>
                                        <th>Branch</th>
                                        <th>Account No</th>
                                        <th>Opening Amount</th>
                                        <th>Current Balance</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- boostrap modal -->
                    <div class="modal fade" id="Store-modal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="StoreModal">Add Account</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="card-body shadow p-3 mb-5 bg-body-tertiary rounded">
                                    <form action="javascript:void(0)"  id="StoreForm" name="StoreForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="bank_name" class="control-label">Bank Name</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="e.g. Commercial Bank" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="branch" class="control-label">Branch</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control" id="branch" name="branch" placeholder="e.g. Colombo 07">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="account_no" class="control-label">Account No</label>
                                                <div class="col-sm-12">
                                                <input type="text" class="form-control" id="account_no" name="account_no" placeholder="Account number" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="opening_amount" class="control-label">Opening Amount</label>
                                                <div class="col-sm-12">
                                                <input type="number" step="0.01" class="form-control" id="opening_amount" name="opening_amount" placeholder="0.00">
                                                <small class="text-muted">This is only used once, when the account is first added. After that, the balance updates automatically from cheque transactions.</small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" id="OC"
                                                name="OC" placeholder="Operator"
                                                value="{{ Auth::user()->username}}" readonly>
                                            </div>

                                            <div class="col-md-6">
                                               <input type="text" class="form-control" id="BC"
                                               name="BC" placeholder="Branch"
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
                    <!-- end bootstrap modal -->
                    <script type="text/javascript">
                    $(document).ready( function () {
                        $.ajaxSetup({
                            headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $('#ChequeBanksTable').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ url('ChequeBanks') }}",
                            columns: [
                                { data: 'bank_name', name: 'bank_name' },
                                { data: 'branch', name: 'branch' },
                                { data: 'account_no', name: 'account_no' },
                                { data: 'opening_amount', name: 'opening_amount' },
                                { data: 'current_amount', name: 'current_amount' },
                                { data: 'status_badge', name: 'status_badge', orderable: false },
                                { data: 'action', name: 'action', orderable: false},
                            ],
                            order: [[0, 'desc']],
                            initComplete: function () {
                                dtFixToolbar(this, '#addChequeBankBtn');
                            }
                        });
                    });

                    function add(){
                        $('#StoreForm').trigger("reset");
                        $('#StoreModal').html("Add Account");
                        $('#Store-modal').modal('show');
                    }

                    function toggleActive(id){
                        $.ajax({
                            type:"POST",
                            url: "{{ url('ChequeBank_toggle') }}",
                            data: { id: id },
                            dataType: 'json',
                            success: function(res){
                                var oTable = $('#ChequeBanksTable').dataTable();
                                oTable.fnDraw(false);
                            }
                        });
                    }

                    $('#StoreForm').submit(function(e) {
                        e.preventDefault();
                        var formData = new FormData(this);
                        $.ajax({
                            type:'POST',
                            url: "{{ url('ChequeBank_store')}}",
                            data: formData,
                            cache:false,
                            contentType: false,
                            processData: false,
                            success: (data) => {
                                $("#Store-modal").modal('hide');
                                var oTable = $('#ChequeBanksTable').dataTable();
                                oTable.fnDraw(false);
                                $("#btn-save").html('Submit');
                                $("#btn-save").attr("disabled", false);
                            },
                            error: function(data){
                                console.log(data);
                            }
                        });
                    });
                    </script>

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
