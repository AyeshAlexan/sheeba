</html>

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
                <title> Advance Payment </title>
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
                    <div class="card shadow">
                        <div class="col-md-9">
                            <h4 class="card-title m-3">Advance Payment </h4>
                        </div>
                        <hr style="height: 5px; color: blue;">

                    <div class="container mt-2">
                        <div class="row">

                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">

                                </div>
                                <div class="pull-right ">
                                    <a class="btn btn-success card-body shadow p-3 mb-2" onClick="add()" href="javascript:void(0)">
                                        Add Advance Payment <i class="fas fa-plus" style="color: white"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                            <div class=" p-3 mb-5  rounded">
							<div class="table-responsive">
                            <table class="table table-bordered" id="TPaymentVoucher">
                                <thead>
                                    <tr style="background-color:hsl(147, 50%, 47%);">
										<th>Date</th>
                                        <th>Voucher No</th>
                                        <th>Customer Name</th>
										<th>Description</th>
										<th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    </div>


<!-- boostrap employee model -->
<div class="modal fade" id="Store-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Add Advance Payment </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class=" p-3 mb-2 bg-body-tertiary rounded">
                    <form action="javascript:void(0)" id="StoreForm" name="StoreForm" class="form-horizontal"
                        method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">

                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="input-group-text" id="btnGroupAddon2">Payment No :
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <input type="text" id="invoice_no" name="invoice_no" value="{{$maxCustomer+1}}"
                                        class="form-control" placeholder="Invoice Number:" aria-label="Invoice Number:"
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
                                    <input type="date" id="date" name="date" value="" class="form-control"
                                        placeholder="Invoice Number:" aria-label="Invoice Number:"
                                        aria-describedby="btnGroupAddon2">
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Customer Name </span>
                                    </div>
                                    <!-- First dropdown -->
                                    <select class="select form-control" name="customer_name" id="customer_name"
                                        aria-hidden="true">
                                        <option value="">Please Select</option>
                                        @foreach($customer as $Data)
                                        <option value="{{ $Data->First_name}}">
                                            {{ $Data->First_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <!-- Second dropdown -->
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">NIC </span>
                                    </div>
                                    <select class="select form-control" name="cus_code" id="cus_code"
                                        aria-hidden="true">
                                        <option value="">Please Select</option>
                                        @foreach($customer as $Data)
                                        <option value="{{ $Data->NIC}}">
                                            {{ $Data->NIC }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3">Description</span>
                                    </div>
                                    <textarea id="description" name="description" class="form-control" required=""
                                        placeholder="Enter the Customer Payment Note "></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-7">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Payment Amount</span>
                                        </div>
                                        <input type="text" class="form-control" placeholder="Amount" id="amount"
                                            name="amount" aria-label="Amount (to the nearest dollar)">
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <input type="hidden" class="form-control" id="OC" name="OC"
                                        placeholder="Enter a Department Name" value="{{ Auth::user()->username}}"
                                        readonly>
                                </div>
                                <div class="col-md-6">
                                    <input type="hidden" class="form-control" id="BC" name="BC"
                                        placeholder="Enter a Department Name" value="{{ Auth::user()->BC}}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-offset-2 col-sm-10"><br />
                                <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

                </div>
                @include('layouts.footer')
            </div>
         </div>



<script>
    $(document).ready(function () {
        $('#Store-modal').on('show.bs.modal', function (e) {
            // Get the current date in the format YYYY-MM-DD
            var currentDate = new Date().toISOString().split('T')[0];
            // Set the current date as the value of the date input field
            $('#date').val(currentDate);
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#TPaymentVoucher').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('sales_customer_payment') }}",
            columns: [{
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'invoice_no',
                    name: 'invoice_no'
                },
                {
                    data: 'customer_name',
                    name: 'customer_name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                },
            ],
            order: [
                [0, 'desc']
            ]
        });
    });

    function add() {
        $('#StoreForm').trigger("reset");
        $('#StoreModal').html("Add Store");
        $('#Store-modal').modal('show');
        $('#id').val('');
    }

    function editFunc(id) {
        $.ajax({
            type: "POST",
            url: "{{ url('UpdatecustomerPayment') }}",
            data: {
                id: id
            },
            dataType: 'json',
            success: function (res) {
                $('#StoreModal').html("Edit Item");
                $('#Store-modal').modal('show');
                $('#id').val(res.id);
                $('#invoice_no').val(res.invoice_no);
                $('#date').val(res.date);
                $('#customer_name').val(res.customer_name);
                $('#cus_code').val(res.cus_code);
                $('#description').val(res.description);
                $('#amount').val(res.amount);
            }
        });
    }

    function deleteFunc(id) {
        if (confirm("Delete Record?") == true) {
            var id = id;
            // ajax
            $.ajax({
                type: "POST",
                url: "{{ url('DeletecustomerPayment') }}",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function (res) {
                    var oTable = $('#TPaymentVoucher').dataTable();
                    oTable.fnDraw(false);
                }
            });
        }
    }
    $('#StoreForm').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: "{{ url('addcustomerPayment')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                var pdfUrl = "{{ asset('public/assets/pdf/Advance_Payment_Invoice.pdf') }}";
                var newWindow = window.open(pdfUrl, '_blank');
                location.reload();
                $("#Store-modal").modal('hide');
                var oTable = $('#TPaymentVoucher').dataTable();
                oTable.fnDraw(false);
                $("#btn-save").html('Submit');
                $("#btn-save").attr("disabled", false);
            },
            error: function (data) {
                console.log(data);
            }
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Listen for changes in the Weight and QTY fields
        $('#customer_name').on('change', function () {
            // Get the selected value from the first dropdown
            let category = $('#customer_name').val();
            // Make an AJAX request to fetch data based on the selected value
            $.ajax({
                url: "{{ route('show_Customer_ajax') }}",
                method: 'GET',
                data: {
                    "_token": "{{ csrf_token() }}",
                    category: category
                },
                success: function (res) {
                    if (res.status == 'success') {
                        // Update the options of the second dropdown with the received data
                        var itemsCodeSelected = $('#cus_code');
                        itemsCodeSelected.empty();
                        $.each(res.data, function (index, item) {
                            itemsCodeSelected.append($('<option>', {
                                value: item.NIC,
                                text: item.NIC
                            }));
                        });
                    }
                },
                error: function (err) {
                    // Handle errors if the AJAX request fails
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
