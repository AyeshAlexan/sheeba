@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

        <title>Receipt Type</title>
    </head>
    <body>
        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="card shadow ">
                        <div class="col-md-9">
                            <h4 class="card-title m-3">Receipt Type</h4>
                        </div>
                        <hr style="height: 5px; color: blue;">
                        {{-- alert section --}}
                        <div class="row">
                            <div class="col-sm-12">
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
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-8">
                                {{-- Receipt Form  --}}
                                <div class="card shadow p-3 mb-3 bg-body-tertiary rounded">
                                    <div class="errMsgContainer2"></div>
                                    <form action="" id="addReceipt" method="post" enctype="multipart/form-data" >
                                        @csrf
                                        <label for="">Receipt Type Name</label>
                                        <div class=" form-group">
                                            <input type="text" class="form-control" placeholder="Receipt Type Name"
                                                name="receiptname" id="receiptname" value="{{ old('receiptname') }}"
                                                required>
                                        </div>
                                        <div class="row">
                                            <div class="row">
                                                <div class="col">
                                                    <label for="">Rate 1</label>
                                                    <div class=" form-group">
                                                        <input type="text" class="form-control"
                                                            placeholder="First Months interest rate here"
                                                            id="rate1" name="rate1" value="{{ old('rate1') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <label for="">Period 1</label>
                                                    <div class=" form-group">
                                                        <input type="text" class="form-control"
                                                            placeholder="First Months Period here"
                                                            id="period1" name="period1" value="{{ old('period1') }}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <label for="">Rate 2</label>
                                                    <div class=" form-group">
                                                        <input type="text" class="form-control"
                                                            placeholder="Other Months interest rate here"
                                                            id="rate2" name="rate2" value="{{ old('rate2') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <label for="">Period 2</label>
                                                    <div class=" form-group">
                                                        <input type="text" class="form-control"
                                                            placeholder="Other Months Period here"
                                                            id="period2" name="period2" value="{{ old('period2') }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <label for="">Rate 3</label>
                                                    <div class=" form-group">
                                                        <input type="text" class="form-control"
                                                            placeholder="Other Months interest rate here"
                                                            id="rate3" name="rate3" value="{{ old('rate3') }}" required>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <label for="">Period 3</label>
                                                    <div class=" form-group">
                                                        <input type="text" class="form-control"
                                                            placeholder="Other Months Period here"
                                                            id="period3" name="period3" value="{{ old('period3') }}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <button class="btn btn-primary add_receipt" type="button">Save</button>
                                            </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col">
                                {{-- Receipt Table --}}
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <div class="table-data">
                                            <div class="card shadow p-3 mb-3 bg-body-tertiary rounded " >

                                            <table class="table table-center table-bordered  table-hover ">
                                                <thead>
                                                    <tr class="table-secondary">
                                                        <th>Receipt Type Name</th>
                                                        <th>Rate 01</th>
                                                        <th>Period 01</th>
                                                        <th>Rate 02</th>
                                                        <th>Period 02</th>
                                                        <th>Rate 03</th>
                                                        <th>Period 03</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($Recei_Add as $key=>$receipt)
                                                    <tr>
                                                        <td>{{$receipt->receiptname }}</td>
                                                        <td>{{$receipt->rate1 }}</td>
                                                        <td>{{$receipt->period1 }}</td>
                                                        <td>{{$receipt->rate2 }}</td>
                                                        <td>{{$receipt->period2 }}</td>
                                                        <td>{{$receipt->rate3 }}</td>
                                                        <td>{{$receipt->period3 }}</td>
                                                        <td>
                                                            <a href=""
                                                                class="btn btn-sm btn-success update_receipt_form bg-success-light text-success me-2"
                                                                data-bs-toggle="modal" data-bs-target="#updateReceiptModel"
                                                                data-id="{{$receipt->id}}"
                                                                data-receiptname="{{$receipt->receiptname}}"
                                                                data-rate1="{{$receipt->rate1}}"
                                                                data-period1="{{$receipt->period1}}"
                                                                data-rate2="{{$receipt->rate2}}"
                                                                data-period2="{{$receipt->period2}}"
                                                                data-rate3="{{$receipt->rate3}}"
                                                                data-period3="{{$receipt->period3}}"
                                                                >
                                                                <i class="far fa-edit me-1"></i> Edit
                                                            </a>
                                                            <a href="" class="btn btn-sm btn-danger delete_receipt bg-danger-light text-danger me-2"
                                                                        data-id="{{$receipt->id}}">
                                                                <i class="far fa-trash-alt me-1"></i> Delete
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            <div class="m-1">
                                                {!! $Recei_Add->links() !!}
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-md-1"></div>
                        </div>


                        {{-- ............Update Receipt model................................. --}}
                        <div class="modal fade" id="updateReceiptModel" tabindex="-1" role="dialog"
                            aria-labelledby="updateReceiptModelLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title m-2" id="updateReceiptModelLabel"> Edit Receipt </h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="errMsgContainer2"></div>
                                                        <form action="" method="post" id="updateReceipt">
                                                            @csrf
                                                            <div class="row">

                                                                <label for="">Receipt Type Name</label>
                                                                <input type="hidden" id="up_id" name="up_id">
                                                                <div class=" form-group">
                                                                    <input type="text" class="form-control" placeholder="Receipt Type Name"
                                                                        name="up_receiptname" id="up_receiptname"
                                                                        required>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <label for="">Rate 1</label>
                                                                        <div class=" form-group">
                                                                            <input type="text" class="form-control"
                                                                                    placeholder="First Months interest rate here"
                                                                                    id="up_rate1" name="up_rate1"  required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <label for="">Period 1</label>
                                                                        <div class=" form-group">
                                                                                <input type="text" class="form-control" placeholder=""
                                                                                id="up_period1" name="up_period1"  required>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                 <div class="row">
                                                                    <div class="col">
                                                                        <label for="">Rate 2</label>
                                                                        <div class=" form-group">
                                                                            <input type="text" class="form-control"
                                                                                    placeholder="First Months interest rate here"
                                                                                    id="up_rate2" name="up_rate2"  required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <label for="">Period 2</label>
                                                                        <div class=" form-group">
                                                                                <input type="text" class="form-control" placeholder=""
                                                                                id="up_period2" name="up_period2"  required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <label for="">Rate 3</label>
                                                                        <div class=" form-group">
                                                                            <input type="text" class="form-control"
                                                                                    placeholder="First Months interest rate here"
                                                                                    id="up_rate3" name="up_rate3"  required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <label for="">Period 3</label>
                                                                        <div class=" form-group">
                                                                                <input type="text" class="form-control" placeholder=""
                                                                                id="up_period3" name="up_period3"  required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="text-center mt-4">
                                                                <button type="button" class="btn btn-success update_receipt bg-success-light text-success me-2">Update</button>
                                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                                                            </div>
                                                    </form>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       </div>


                    </div>
                </div>
            </div>
        </div>
        {!! Toastr::message() !!}

        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>

        <script>
            $(document).ready(function(){
                //add new receipt
                $(document).on('click','.add_receipt', function(e){
                    e.preventDefault();
                    let receiptname = $('#receiptname').val();
                    let rate1 = $('#rate1').val();
                    let period1 = $('#period1').val();
                    let rate2 = $('#rate2').val();
                    let period2 = $('#period2').val();
                    let rate3 = $('#rate3').val();
                    let period3 = $('#period3').val();

                    $.ajax({
                        url:"{{ route('add_receipt_ajax') }}",
                        method: 'post',
                        data:{"_token": "{{ csrf_token() }}",
                        receiptname:receiptname,
                        rate1:rate1,
                        period1:period1,
                        rate2:rate2,
                        period2:period2,
                        rate3:rate3,
                        period3:period3},
                        success:function(res){
                            if(res.status=='success'){
                                $('#addReceipt')[0].reset();
                                $('.table').load(location.href+' .table');
                                Command: toastr["success"]("Receipt Added ...!", "Success")
                                    toastr.options = {
                                    "closeButton": true,
                                    "debug": false,
                                    "newestOnTop": false,
                                    "progressBar": true,
                                    "positionClass": "toast-top-right",
                                    "preventDuplicates": false,
                                    "onclick": null,
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "5000",
                                    "extendedTimeOut": "1000",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                    }
                            }
                        },error:function(err){
                            $('.errMsgContainer').html('');
                            let error = err.responseJSON;
                            $.each(error.errors,function(index, value){
                                $('.errMsgContainer').append('<span class="text-danger">'+value+'<span>'+'<br>');
                            });
                        }
                    })
                })

                  //show receipt details in update form
                $(document).on('click','.update_receipt_form',function(){
                    let id = $(this).data('id');
                    let receiptname = $(this).data('receiptname');
                    let rate1 = $(this).data('rate1');
                    let period1 = $(this).data('period1');
                    let rate2 = $(this).data('rate2');
                    let period2 = $(this).data('period2');
                    let rate3 = $(this).data('rate3');
                    let period3 = $(this).data('period3');

                    $('#up_id').val(id);
                    $('#up_receiptname').val(receiptname);
                    $('#up_rate1').val(rate1);
                    $('#up_period1').val(period1);
                    $('#up_rate2').val(rate2);
                    $('#up_period2').val(period2);
                    $('#up_rate3').val(rate3);
                    $('#up_period3').val(period3);

                });

                //update receipt details
                $(document).on('click','.update_receipt',function(e){
                            e.preventDefault();
                            let up_id = $('#up_id').val();
                            let up_receiptname = $('#up_receiptname').val();
                            let up_rate1 = $('#up_rate1').val();
                            let up_period1 = $('#up_period1').val();
                            let up_rate2 = $('#up_rate2').val();
                            let up_period2 = $('#up_period2').val();
                            let up_rate3 = $('#up_rate3').val();
                            let up_period3 = $('#up_period3').val();

                            $.ajax({
                                url:"{{ route('update_receipt_ajax') }}",
                                method: 'post',
                                data:{"_token": "{{ csrf_token() }}",
                                up_id:up_id,
                                up_receiptname:up_receiptname,
                                up_rate1:up_rate1,
                                up_rate2:up_rate2,
                                up_rate3:up_rate3,
                                up_period1:up_period1,
                                up_period2:up_period2,
                                up_period3:up_period3,
                                },

                                success:function(res){
                                    if(res.status=='success'){
                                        $("#updateReceiptModel").modal('hide');
                                        $('#updateReceipt')[0].reset();
                                        $('.table').load(location.href+' .table');
                                        Command: toastr["success"]("Receipt Datails Updated...", "Success")
                                            toastr.options = {
                                            "closeButton": true,
                                            "debug": false,
                                            "newestOnTop": false,
                                            "progressBar": true,
                                            "positionClass": "toast-top-right",
                                            "preventDuplicates": false,
                                            "onclick": null,
                                            "showDuration": "300",
                                            "hideDuration": "1000",
                                            "timeOut": "5000",
                                            "extendedTimeOut": "1000",
                                            "showEasing": "swing",
                                            "hideEasing": "linear",
                                            "showMethod": "fadeIn",
                                            "hideMethod": "fadeOut"
                                            }
                                    }
                                },error:function(err){
                                    $('.errMsgContaine2r').html('');
                                    let error = err.responseJSON;
                                    $.each(error.errors,function(index, value){
                                        $('.errMsgContainer2').append('<span class="text-danger">'+value+'<span>'+'<br>');

                                    });
                                }
                            })
                })

                //delete receipt data
                $(document).on('click','.delete_receipt',function(e){
                            e.preventDefault();
                            let receipt_id = $(this).data('id');

                            if(confirm('Are you sure to delete receipt ?')){
                                $.ajax({
                                    url:"{{ route('delete_receipt_ajax') }}",
                                    method: 'post',
                                    data:{"_token": "{{ csrf_token() }}",receipt_id:receipt_id},
                                    success:function(res){
                                        if(res.status=='success'){
                                            $('.table').load(location.href+' .table');
                                            Command: toastr["success"]("Receipt deleted...", "Success")
                                            toastr.options = {
                                            "closeButton": true,
                                            "debug": false,
                                            "newestOnTop": false,
                                            "progressBar": true,
                                            "positionClass": "toast-top-right",
                                            "preventDuplicates": false,
                                            "onclick": null,
                                            "showDuration": "300",
                                            "hideDuration": "1000",
                                            "timeOut": "5000",
                                            "extendedTimeOut": "1000",
                                            "showEasing": "swing",
                                            "hideEasing": "linear",
                                            "showMethod": "fadeIn",
                                            "hideMethod": "fadeOut"
                                            }
                                        }
                                    }
                                });
                            }
                })
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <script src="assets/js/feather.min.js"></script>
        <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/datatables.min.js"></script>
        <script src="assets/js/script.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
        <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>

    </body>
@endsection
</html>
