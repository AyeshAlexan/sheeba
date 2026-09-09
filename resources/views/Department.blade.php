@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <title>Department</title>

    <style>
        p {
          font-weight: bold;
          font-family: "Noto Sans, sans-serif";
          size:"6";
          color: rgb(3, 80, 3)
        }

        h1{
          font-family: "Times New Roman", Times, serif;
          size:"6";
          color: rgb(4, 58, 13)
        }

        input::placeholder {
          font-weight: bold;
          opacity: 0.5;
          color: rgb(4, 58, 13)
        }

        input[type="text"]{
          background-color: rgb(206, 235, 219);
          padding: 10px 15px;
          border-radius: 3px;
        }

        hr{
          color: rgb(3, 31, 3)
        }
  </style>

</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                    <div class="card shadow ">

                        <div class="col-md-9">
                            <h4 class="card-title m-3">Department</h4>
                        </div>
                        <hr style="height: 5px; color: blue;">
                        <div class="row justify-content-md-center">
                                    {{-- error showing section --}}
                                    @if (session('delete'))
                                        <div class="alert alert-danger text-center" role="alert">
                                            {{ session('delete') }} &#10004;
                                        </div>
                                    @endif
                                    @if (session('added'))
                                    <div class="alert alert-success text-center" role="alert">
                                        {{ session('added') }} &#10004;
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

                                    {{-- --------------search and add branch button-------- --}}
                                        <div class="row ">
                                            {{-- ........search area.......... --}}
                                            <div class="col-md-6">
                                                <div class="top-nav-search">
                                                    <form>
                                                        <input type="text" name="search" id="search"
                                                            class="form-control" placeholder="Search here">
                                                        <button class="btn" type="button"><i
                                                                class="fas fa-search"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                            {{-- .............add branch button.......... --}}
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn float-end m-2" style="background-color:hsl(59, 75%, 49%);"
                                                    data-bs-toggle="modal" data-bs-target="#addDepartmentModel">
                                                    <i data-feather="plus"></i>
                                                    Add Department
                                                </button>
                                            </div>
                                        </div>

                                    {{-- ------------- Table branch -------------- --}}
                                        <div class="card-body ">
                                            <div class="table-responsive ">
                                                <div class="table-data ">
                                                    <table class="table table-bordered table-center table-hover mt-3"
                                                        id="DepartmentTable">
                                                        <thead>
                                                            <tr style="background-color:hsl(147, 50%, 47%);">
                                                                <th>Department Code</th>
                                                                <th>Department Name</th>
                                                                <th>Branch</th>
                                                                <th>Branch Code</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($DepartmentData as $key=>$Department)
                                                            <tr>
                                                                <th>{{$Department->code }}</th>
                                                                <td>{{$Department->description  }}</td>
                                                                <td>{{$Department->Branch}}</td>
                                                                <td>{{$Department->BranchCode}}</td>
                                                                <td>
                                                                <a href=""
                                                                        class="btn btn-sm btn-success update_Department_form bg-success-light text-success me-2"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#updateDepartmentModel"
                                                                        data-id="{{$Department->id}}"
                                                                        data-code="{{$Department->code}}"
                                                                        data-description="{{$Department->description}}"
                                                                        {{-- data-Branch="{{$Department->Branch}}"
                                                                        data-Branch="{{$Department->BranchCode}}" --}}
                                                                        >
                                                                        <i class="far fa-edit me-1"></i> Edit
                                                                    </a>
                                                                    <a href=""
                                                                        class="btn btn-sm btn-danger delete_Department bg-danger-light text-danger me-2"
                                                                        data-id="{{$Department->id}}">
                                                                        <i class="far fa-trash-alt me-1"></i> Delete
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    <div class="ml-4 mb-3 mt-1">
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
    {!! Toastr::message() !!}


     {{-- ------------- model for add branch -------------- --}}
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addDepartmentModelLabel"
        aria-hidden="true" id="addDepartmentModel">
        <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h4 class="modal-title" id="addDepartmentModelLabel">Add Department </h4>
                 <button type="button" class="btn-close" data-bs-dismiss="modal"
                     aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-12">
                         <div class="card">
                             <div class="card-body">
                                 <div class="errMsgContainer"></div>
                                 <form action="" id="addDepartment" method="POST"
                                     enctype="multipart/form-data">
                                     @csrf
                                     <div class="mb-3">
                                         <label for="exampleFormControlInput1"
                                             class="form-label">
                                             Department Code</label>
                                         <input type="text" class="form-control" id="code"
                                             name="code" placeholder="Enter a Department Code"
                                             value="00{{ $maxCustomer+1}}" required>
                                     </div>
                                     <div class="mb-3">
                                         <label for="exampleFormControlTextarea1"
                                             class="form-label"> Department Name</label>
                                         <input type="text" class="form-control" id="description"
                                             name="description" placeholder="Enter a Department Name"
                                             value="" required>
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
                             </div>
                         </div>
                     </div>
                     <div class="text-center mt-4">
                         <button class="btn btn-success add_Department"
                             type="button">Save</button>
                         <input class="btn btn-outline-warning" type="reset" value="Reset">
                         <button type="button" id="closeModel"
                             class="btn btn-outline-secondary" data-bs-dismiss="modal"
                             aria-label="Close">Close</button>
                     </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
    </div>

 {{-- ------------- model for update Department -------------- --}}
 <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="updateDepartmentModelLabel"
     aria-hidden="true" id="updateDepartmentModel">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h4 class="modal-title" id="updateDepartmentModelLabel">Update Department </h4>
                 <button type="button" class="btn-close" data-bs-dismiss="modal"
                     aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-md-12">
                         <div class="card">
                             <div class="card-body">
                                 <div class="errMsgContainer2"></div>
                                 <form action="" id="updateDepartment" method="POST">
                                     @csrf
                                     <input type="hidden" id="up_id" name="up_id">
                                     <div class="mb-3">
                                         <label for="exampleFormControlInput1"
                                             class="form-label">
                                             Department Code </label>
                                         <input type="text" class="form-control"
                                             id="up_code" name="up_code"
                                             placeholder="Enter a Branch Code" required>
                                     </div>
                                     <div class="mb-3">
                                         <label for="exampleFormControlTextarea1"
                                             class="form-label">Department Name</label>
                                         <input type="text" class="form-control" id="up_description"
                                             name="up_description" placeholder="Enter a Department Name"
                                             required>
                                     </div>
                                     {{-- <div class="row">
                                     <div class="col-md-6">
                                         <label for="exampleFormControlTextarea1"
                                             class="form-label">Branch Name</label>
                                             <select class="select form-control" name="up_Branch" id="up_Branch"
                                             aria-hidden="true">
                                             <option value="">Please Select</option>
                                             @foreach($Branch as $BranchData)
                                             <option value="{{ $BranchData->name}}">
                                                 {{ $BranchData->name }}</option>
                                             @endforeach
                                         </select>
                                     </div>

                                     <div class="col-md-6">
                                            <select hidden name="up_BranchCode" id="up_BranchCode"
                                            aria-hidden="true">
                                            <option value="">Please Select</option>
                                            @foreach($Branch as $BranchData)
                                            <option value="{{ $BranchData->bccode}}">
                                                {{ $BranchData->bccode }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                </div>

                             </div>
                         </div>
                     </div>
                     <div class="text-center mt-4">
                         <button class="btn btn-success update_Department"
                             type="button">Update</button>
                         <input class="btn btn-outline-warning" type="reset" value="Reset">
                         <button type="button" id="closeModel"
                             class="btn btn-outline-secondary" data-bs-dismiss="modal"
                             aria-label="Close">Close</button>
                     </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>
 </div>




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
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>

    {{-- CSRF token script --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(document).ready(function () {

                        //add new branch
                        $(document).on('click', '.add_Department', function (e) {
                e.preventDefault();
                let code = $('#code').val();
                let description = $('#description').val();
                let Branch = $('#Branch').val();
                let BranchCode = $('#BranchCode').val();
                $.ajax({
                    url: "{{ route('add_Department_ajax') }}",
                    method: 'post',
                    data: {
                        code: code,
                        description: description,
                        Branch: Branch,
                        BranchCode: BranchCode,
                    },
                    success: function (res) {
                        if (res.status == 'success') {
                            $("#addDepartmentModel").modal('hide');
                            $('#addDepartment')[0].reset();
                            $('.table').load(location.href + ' .table');
                            Command: toastr["success"]("Department Added ...!", "Success")
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



            //show Department details in update form
            $(document).on('click', '.update_Department_form', function () {
                let id = $(this).data('id');
                let code = $(this).data('code');
                let description  = $(this).data('description');
                let Branch  = $(this).data('Branch');
                let BranchCode  = $(this).data('BranchCode');

                $('#up_id').val(id);
                $('#up_code').val(code);
                $('#up_description').val(description);
                $('#up_Branch').val(Branch);
                $('#up_BranchCode').val(BranchCode);


            });

            //update branch data
            $(document).on('click', '.update_Department', function (e) {
                e.preventDefault();
                let up_id = $('#up_id').val();
                let up_code = $('#up_code').val();
                let up_description = $('#up_description').val();
                let up_Branch = $('#up_Branch').val();
                let up_BranchCode = $('#up_BranchCode').val();
                $.ajax({
                    url: "{{ route('update_Department_ajax') }}",
                    method: 'post',
                    data: {
                        up_id: up_id,
                        up_code: up_code,
                        up_description: up_description,
                        up_Branch: up_Branch,
                        up_BranchCode: up_BranchCode,
                    },
                    success: function (res) {
                        if (res.status == 'success') {
                            $("#updateDepartmentModel").modal('hide');
                            $('#updateDepartment')[0].reset();
                            $('.table').load(location.href + ' .table');
                            Command: toastr["success"]("Department datails updated...",
                                "Success")
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
                        $('.errMsgContaine2r').html('');
                        let error = err.responseJSON;
                        $.each(error.errors, function (index, value) {
                            $('.errMsgContainer2').append(
                                '<span class="text-danger">' + value +
                                '<span>' + '<br>');

                        });
                    }
                })
            })

            //delete Department data
            $(document).on('click', '.delete_Department', function (e) {
                e.preventDefault();
                let Department_id = $(this).data('id');

                if (confirm('Are you sure to delete Department ?')) {
                    $.ajax({
                        url: "{{ route('delete_Department_ajax') }}",
                        method: 'post',
                        data: {
                            Department_id: Department_id
                        },
                        success: function (res) {
                            if (res.status == 'success') {
                                $('.table').load(location.href + ' .table');
                                Command: toastr["success"]("Department deleted...", "Success")
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


{{-- <script>
    $(document).ready(function () {
        // Listen for changes in the Weight and QTY fields
        $('#Branch').on('change', function () {
            let category = $('#Branch').val();
            $.ajax({
                        url: "{{ route('show_select_Branch_ajax') }}",
                        method: 'GET',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            category: category
                        },
                        success: function (res) {
                            if (res.status == 'success') {

                                var itemsCodeSelected = $('#BranchCode');
                                // var itemDescriptionSelected = $('#item_description');
                                // var itemUnit_price = $('#unit_price');
                                // Change this to match your items select element
                                // Clear existing options
                                itemsCodeSelected.empty();
                                // itemDescriptionSelected.empty();
                                // itemUnit_price.empty();

                                $.each(res.data, function (index, item) {
                                    itemsCodeSelected.append($('<option>', {
                                        value: item.bccode,
                                        text: item.bccode
                                    }));
                                });

                                // // Add a default option for item description
                                // itemDescriptionSelected.append($('<option>', {
                                //     value: '',
                                //     text: 'Select an item'
                                // }));

                                // $.each(res.data, function (index, item) {
                                //     itemDescriptionSelected.append($('<option>', {
                                //         value: item.Item_description,
                                //         text: item.Item_description
                                //     }));
                                // });

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
                    });
        });
    });
</script> --}}


</body>
@endsection
</html>
