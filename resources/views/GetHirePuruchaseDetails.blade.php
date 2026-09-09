@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Invoice Sales</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card shadow">
                            <div class="col-md-9">
                                <h4 class="card-title m-3">Hirepurchase List</h4>
                            </div>
                            <hr style="height: 5px; color: blue;">
                            <div class="card-body">
                                {{-- alerts section --}}
                                @if (session('delete'))
                                <div class="alert alert-danger text-center" role="alert">
                                    {{session('delete')}} &#10004;
                                </div>
                                @endif
                                @if (session('added'))
                                <div class="alert alert-success text-center" role="alert">
                                    {{session('added')}} &#10004;
                                </div>
                                @endif
                                @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                @if (Session::has('done'))
                                <div class="alert alert-success text-center">
                                    <p>{{ Session::get('done') }}</p>
                                </div>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        var pdfLink = "{{ Session::get('pdfLink') }}";
                                        window.open(pdfLink, '_blank');
                                    });

                                </script>
                                @endif
                                <div class="row ">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="pending_repair_tab">
                                            <div class="row mb-4">
                                                <div class="col-md-8 mb-12">
                                                    <!--<a target="_blank" class="btn  btn-primary pull-right"-->
                                                    <!--    data-bs-toggle="modal" data-bs-target="#addInvoiceModel">-->
                                                    <!--    <i class="fa fa-plus"></i> Add </a>-->


                                                    <a href="{{route("hire_purchase")}}"  class="btn  btn-primary pull-right">
                                                        <i class="fa fa-plus"></i> Create Hirepurchase </a>

                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                               <table class="table table-bordered table-striped ajax_view dataTable"
                                                    style="width: 1523px;">
                                                    <thead>
                                                        <tr role="row">
                                                            <th>Invoice No</th>
                                                            <th>Reference No</th>
                                                            <th>Agreement No</th>
                                                            <th>Invoice Date</th>
                                                            <th>Customer Code</th>
                                                            <th>guarantor code</th>
                                                            <th>guarantor code</th>
                                                            <th>Customer Name</th>
                                                            <th>Customer Nic</th>
                                                            <th>Customer Phone</th>
                                                            <th>Customer Address</th>
                                                            <th>Document Charge Rate</th>
                                                            <th>Document Charge</th>
                                                            <th>Down Payment Rate</th>
                                                            <th>Down Payment</th>
                                                            <th>Transport</th>
                                                            <th>Instalment Rate</th>
                                                            <th>Instalment Amount</th>
                                                            <th>No of Instalment</th>
                                                            <th>Instalment due date	</th>
                                                            <th>Instalment</th>
                                                            <th>Due amount</th>
                                                            <th>Gross amount</th>
                                                            <th>Discount</th>
                                                            <th>Cash Payment</th>
                                                            <th>Card Payment</th>
                                                            <th>Bank Transfer</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ( $Hirepuruchase as $key=>$Details)
                                                        <tr>
                                                            <td>{{$Details->invoice_no}}</td>
                                                            <td>{{$Details->reference_no}}</td>
                                                            <td>{{$Details->agreement_no}}</td>
                                                            <td>{{$Details->invoice_date}}</td>
                                                            <td>{{$Details->customer_code}}</td>
                                                            <td>{{$Details->guarantor_1_code}}</td>
                                                            <td>{{$Details->guarantor_2_code}}</td>
                                                            <td>{{$Details->customer_name}}</td>
                                                            <td>{{$Details->customer_nic}}</td>
                                                            <td>{{$Details->customer_phone}}</td>
                                                            <td>{{$Details->customer_address}}</td>
                                                            <td>{{$Details->document_charge_rate}}</td>
                                                            <td>{{$Details->document_charge}}</td>
                                                            <td>{{$Details->down_payment_rate}}</td>
                                                            <td>{{$Details->down_payment}}</td>
                                                            <td>{{$Details->transport}}</td>
                                                            <td>{{$Details->instalment_rate}}</td>
                                                            <td>{{$Details->instalment_amount}}</td>
                                                            <td>{{$Details->no_of_instalment}}</td>
                                                            <td>{{$Details->instalment_due_date}}</td>
                                                            <td>{{$Details->instalment}}</td>
                                                            <td>{{$Details->due_amount}}</td>
                                                            <td>{{$Details->gross_amount}}</td>
                                                            <td>{{$Details->discount}}</td>
                                                            <td>{{$Details->cash_payment}}</td>
                                                            <td>{{$Details->card_payment}}</td>
                                                            <td>{{$Details->bank_transfer}}</td>
                                                            <td>
                                                                <a href="javascript:void(0)" data-toggle="tooltip" onClick="editFunc" data-original-title="update" class="edit btn btn-outline-success edit" >
                                                                    <i class="far fa-edit me-1"></i> Edit
                                                                    </a>
                                                                <a href="javascript:void(0);" id="delete-compnay" onClick="deleteFunc" data-toggle="tooltip" data-original-title="itemdelete" class="delete btn btn-outline-danger">
                                                                        <i class="far fa-trash-alt me-1"></i>Delete </a>
                                                            </td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>

    {{-- ............Add Job Invoice model................................. --}}

    </div>


    {!! Toastr::message() !!}


    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/datatables.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

</body>

</html>
@endsection
