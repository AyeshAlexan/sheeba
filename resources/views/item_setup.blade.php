@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

    <title>Item Setup</title>
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="card shadow ">
                    <div class="col-md-9">
                        <h4 class="card-title m-3">Item Setup</h4>
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
                                <div class="errMsgContainer"></div>
                                <form id="addItem" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <label for="">Code</label>
                                    <div class=" form-group">
                                        <input type="text" class="form-control" placeholder="Item Code" name="item_code"
                                            id="item_code" value="{{ old('item_code') }}" required>
                                    </div>
                                    <div class="row">
                                        <label for="">Item Description</label>
                                        <div class=" form-group">
                                            <input type="text" class="form-control" placeholder="Item Description"
                                                id="item_description" name="item_description"
                                                value="{{ old('item_description') }}" required>
                                        </div>


                                        <label for="">Category</label>
                                        <div class=" form-group">
                                            <select class="select form-control" id="item_category" name="item_category" aria-hidden="true" required>
                                                <option value="" >Please select</option>
                                                @foreach($item_category as $categoryData)
                                                <option value="{{ $categoryData->category}}">{{ $categoryData->category}}</option>
                                                @endforeach
                                            </select>

                                            {{-- <input type="text" class="form-control" placeholder="Item Category"
                                                id="item_category" name="item_category"
                                                value="{{ old('item_category') }}" required> --}}
                                        </div>
                                        <label for="">Sub Category</label>
                                        <div class=" form-group">
                                            <input type="text" class="form-control" placeholder="Item Sub Category"
                                                id="item_sub_category" name="item_sub_category"
                                                value="{{ old('item_sub_category') }}" required>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-primary add_item" type="button">Save</button>
                                        </div>
                                </form>
                            </div>
                        </div>

                        {{-- Receipt Table --}}
                        <div class="card shadow p-3 mb-3 bg-body-tertiary rounded">
                            <div class="table">
                                <table class="table table-center table-hover ">
                                    <thead>
                                        <tr class="table-secondary">
                                            <th>Code</th>
                                            <th>Item Description</th>
                                            <th>Category</th>
                                            <th>Sub Category</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item as $key=>$items)
                                        <tr>
                                            <td>{{$items->code}}</td>
                                            <td>{{$items->ItemDesc}}</td>
                                            <td>{{$items->Category}}</td>
                                            <td>{{$items->subcategory}}</td>
                                            <td>
                                                <a href=""
                                                    class="btn btn-sm btn-success update_item_form bg-success-light text-success me-2"
                                                    data-bs-toggle="modal" data-bs-target="#updateItemModel"
                                                    data-id ="{{$items->id}}"
                                                    data-code ="{{$items->code}}"
                                                    data-itemdesc ="{{$items->ItemDesc}}"
                                                    data-category ="{{$items->Category}}"
                                                    data-subcategory ="{{$items->subcategory}}">
                                                    <i class="far fa-edit me-1"></i> Edit
                                                </a>

                                                <a href=""
                                                    class="btn btn-sm btn-danger delete_item bg-danger-light text-danger me-2"
                                                    data-id="{{$items->id}}">
                                                    <i class="far fa-trash-alt me-1"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="m-1">
                                    {{-- {!! $itemSetup->links() !!} --}}
                                </div>
                            </div>
                        </div>

                        {{-- ............Update Item model................................. --}}
                        <div class="modal fade" id="updateItemModel" tabindex="-1" role="dialog"
                            aria-labelledby="updateItemModelLabel" aria-hidden="true">
                            <div class="modal-dialog ">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title m-2" id="updateItemModelLabel"> Edit Item Setup </h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="errMsgContainer2"></div>
                                                        <form action="" method="post" id="updateItem">
                                                            @csrf

                                                                <label for="">Code</label>
                                                                <input type="hidden" id="up_id" name="up_id">
                                                                <div class=" form-group">
                                                                    <input type="text" class="form-control" placeholder="Receipt Type Name"
                                                                        name="up_item_code" id="up_item_code"
                                                                        required>
                                                                </div>

                                                                <label for="">Item Description</label>
                                                                <div class=" form-group">
                                                                        <input type="text" class="form-control"
                                                                            placeholder="First Months interest rate here"
                                                                            id="up_item_description" name="up_item_description"  required>
                                                                </div>
                                                                <label for="">Category</label>
                                                                <div class=" form-group">
                                                                    <select class="select form-control" id="up_item_category" name="up_item_category" aria-hidden="true" required>
                                                                        <option value="" >Please select</option>
                                                                        @foreach($item_category as $categoryData)
                                                                        <option value="{{ $categoryData->category}}">{{ $categoryData->category}}</option>
                                                                        @endforeach
                                                                    </select>

                                                                        {{-- <input type="text" class="form-control"
                                                                            placeholder="Other Months interest rate here"
                                                                            id="up_item_category" name="up_item_category"  required> --}}
                                                                </div>
                                                                <label for="">Sub Category</label>
                                                                <div class=" form-group">
                                                                        <input type="text" class="form-control" placeholder="Sub Category"
                                                                        id="up_item_sub_category" name="up_item_sub_category"  required>
                                                                </div>

                                                            <div class="text-center mt-4">
                                                                <button type="button" class="btn btn-success update_item bg-success-light text-success me-2">Update</button>
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
    </div>
    {!! Toastr::message() !!}

    {{-- SCRF token --}}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            //add new receipt
            $(document).on('click', '.add_item', function (e) {
                e.preventDefault();
                let code = $('#item_code').val();
                let description = $('#item_description').val();
                let category = $('#item_category').val();
                let sub_category = $('#item_sub_category').val();

                $.ajax({
                    url: "{{ route('add_item_ajax') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        code: code,
                        description: description,
                        category: category,
                        sub_category: sub_category
                    },
                    success: function (res) {
                        if (res.status == 'success') {
                            $('#addItem')[0].reset();
                            $('.table').load(location.href + ' .table');
                            Command: toastr["success"]("Item Added ...!", "Success")
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
                    },
                    error: function (err) {
                        $('.errMsgContainer').html('');
                        let error = err.responseJSON;
                        $.each(error.errors, function (index, value) {
                            $('.errMsgContainer').append(
                                '<span class="text-danger">' + value +
                                '<span>' + '<br>');
                        });
                    }
                })
            })

            //show item details in update form
            $(document).on('click','.update_item_form',function(){
                    let id = $(this).data('id');
                    let code = $(this).data('code');
                    let description = $(this).data('itemdesc');
                    let category = $(this).data('category');
                    let sub_category = $(this).data('subcategory');

                    $('#up_id').val(id);
                    $('#up_item_code').val(code);
                    $('#up_item_description').val(description);
                    $('#up_item_category').val(category);
                    $('#up_item_sub_category').val(sub_category);
            });

            //update receipt details
            $(document).on('click','.update_item',function(e){
                            e.preventDefault();
                            let up_id = $('#up_id').val();
                            let up_code = $('#up_item_code').val();
                            let up_description = $('#up_item_description').val();
                            let up_category = $('#up_item_category').val();
                            let up_sub_category = $('#up_item_sub_category').val();

                            $.ajax({
                                url:"{{ route('update_item_ajax') }}",
                                method: 'post',
                                data:{"_token": "{{ csrf_token() }}",
                                up_id:up_id,
                                up_code:up_code,
                                up_description:up_description,
                                up_category:up_category,
                                up_sub_category:up_sub_category,
                                },

                                success:function(res){
                                    if(res.status=='success'){
                                        $("#updateItemModel").modal('hide');
                                        $('#updateItem')[0].reset();
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

            //delete item data
            $(document).on('click', '.delete_item', function (e) {
                e.preventDefault();
                let item_id = $(this).data('id');

                if (confirm('Are you sure to delete item ?')) {
                    $.ajax({
                        url: "{{ route('delete_item_ajax') }}",
                        method: 'post',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            item_id: item_id
                        },
                        success: function (res) {
                            if (res.status == 'success') {
                                $('.table').load(location.href + ' .table');
                                Command: toastr["success"]("item deleted...", "Success")
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/datatables.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>

</body>
@endsection

</html>
