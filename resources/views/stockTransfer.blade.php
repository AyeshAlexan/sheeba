@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Stock Transfer </title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header ph-flex">
                    <div class="ph-left">
                        <div class="ph-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 3h5v5"/><path d="M8 21H3v-5"/><path d="M21 3 14 10"/><path d="m3 21 7-7"/></svg>
                        </div>
                        <div>
                            <h3 class="page-title">Create Stock Transfer</h3>
                            <p class="page-subtitle">Move stock between branches or stores</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-secondary" onclick="if(confirm('Reset this form and start over?')) window.location.reload();">
                        <i class="fas fa-redo-alt"></i> Reset
                    </button>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
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
                                        // Replace 'your_pdf_link_here' with the actual variable containing the PDF link
                                        var pdfLink = "{{ Session::get('pdfLink') }}";
                                        var newWindow = window.open(pdfLink, '_blank');

                                        // Wait for the new window load, then trigger the print function
                                        newWindow.onload = function () {
                                            newWindow.print();
                                        };
                                    });

                                </script>
                                @endif


                                <form action="{{route('add_stock_transfer')}}" method="post" id="sales_form">
                                    @csrf
                                    <div class="stock-top-row">
                                        <div style="flex:1; min-width:340px; display:flex; flex-direction:column; gap:14px;">
                                            <div class="d-flex gap-3 flex-wrap">
                                                <div style="flex:1; min-width:140px;">
                                                    <label class="control-label">From Store</label>
                                                    <select class="select form-control" id="Branch_Code" name="Branch_Code" aria-hidden="true">
                                                        <option value="">Select an item</option>
                                                        @foreach( $branchName as $branchData)
                                                        <option value="{{ $branchData->Store_code  }}">{{ $branchData->Store_code }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div style="flex:1; min-width:140px;">
                                                    <label class="control-label">&nbsp;</label>
                                                    <select class="select form-control" id="Branch_Name" name="Branch_Name" aria-hidden="true">
                                                        <option value="">Select an item</option>
                                                        @foreach( $branchName as $branchData)
                                                        <option value="{{ $branchData->Store_name  }}">{{ $branchData->Store_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-3 flex-wrap">
                                                <div style="flex:1; min-width:140px;">
                                                    <label class="control-label">To Store</label>
                                                    <select class="select form-control" id="To_Branch_Code" name="To_Branch_Code" aria-hidden="true">
                                                        <option value="">Select an item</option>
                                                        @foreach( $branchName as $branchData)
                                                        <option value="{{ $branchData->Store_code  }}">{{ $branchData->Store_code }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div style="flex:1; min-width:140px;">
                                                    <label class="control-label">&nbsp;</label>
                                                    <select class="select form-control" id="To_Branch_Name" name="To_Branch_Name" aria-hidden="true">
                                                        <option value="">Select an item</option>
                                                        @foreach( $branchName as $branchData)
                                                        <option value="{{ $branchData->Store_name  }}">{{ $branchData->Store_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="stock-info-col">
                                            <div class="stock-info-box">
                                                <div class="stock-info-icon"><i class="fas fa-file-alt"></i></div>
                                                <div>
                                                    <div class="stock-info-label">Invoice No :</div>
                                                    <input type="text" id="invoice_no" name="invoice_no"
                                                        value="{{$maxInvoiceNo+1}}" class="stock-info-input"
                                                        placeholder="Invoice Number" aria-label="Invoice Number">
                                                </div>
                                            </div>
                                            <div class="stock-info-box">
                                                <div class="stock-info-icon"><i class="fas fa-calendar-alt"></i></div>
                                                <div>
                                                    <div class="stock-info-label">Date :</div>
                                                    <input type="date" id="invoice_date" name="invoice_date"
                                                        class="stock-info-input" aria-label="Date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="customer-data"></div>
                                    <div class="showCustomer"></div>

                            {{-- Item Details card --}}
                            <div class="card">
                                <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-list" style="color:var(--tr-blue);"></i>
                                        <strong style="font-size:15px;color:var(--tr-navy);">Item Details</strong>
                                    </div>
                                    <div style="position:relative;">
                                        <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--tr-text-muted); font-size:12px;"></i>
                                        <input type="text" id="stockItemSearch" class="form-control" style="padding-left:32px; width:260px;" placeholder="Search item code or description...">
                                    </div>
                                </div>
                            {{-- dynamicAdded table --}}
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width:15%; text-align: center;">Item Code</th>
                                        <th style="width:20%; text-align: center;">Description</th>
                                        <th style="width:12%; text-align: center;">QTY</th>
                                        <th style="width:12%; text-align: center;">Unit Price</th>
                                        <th style="width:12%; text-align: center;">Discount (%)</th>
                                        <th style="width:12%; text-align: center;">Discount Val</th>
                                        <th style="width:13%; text-align: center;">Net Value</th>
                                        <th class="text-center" style="width:12%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        {{-- <td>
                                                        <select class="select form-control"
                                                            id="item_category" name="item_category" aria-hidden="true">
                                                            <option value="">Select a category</option>
                                                            @foreach($itemCategory as $categoryData)
                                                            <option value="{{ $categoryData->Category_name }}">
                                        {{ $categoryData->Category_name	}}</option>
                                        @endforeach
                                        </select>
                                        </td> --}}
                                        <td id="showItems">
                                            <div class="stock-item-search-row">
                                                <div class="stock-item-search-wrap">
                                                    <i class="fas fa-search"></i>
                                                    <input type="text" id="item_code" name="item_code" class="form-control"
                                                        placeholder="Enter item code..." required aria-label="item Code">
                                                </div>
                                                <button type="button" class="stock-item-search-btn"
                                                    data-bs-toggle="modal" data-bs-target="#searchItemModel" title="Browse items">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>


                                        </td>
                                        <td>
                                            <select class="select form-control " id="item_description"
                                                name="item_description" aria-hidden="true">
                                                <option value="">Select an item</option>
                                                @foreach( $itemCode as $itemData)
                                                <option value="{{ $itemData->Item_description }}">
                                                    {{ $itemData->Item_description}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" placeholder="QTY" id="qty"
                                                name="qty">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" placeholder="Unit Price"
                                                id="unit_price" name="unit_price" value="0">
                                        </td>
                                        <td>
                                            <input class="form-control" type="number" placeholder="Discount"
                                                id="discount" name="discount" min="0" max="100" maxlength="6" value="0">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" placeholder="Discount Val"
                                                id="discount_val" name="discount_val" value="0">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" placeholder="Net Value"
                                                id="net_value" name="net_value" value="0">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" name="add"
                                                class="btn add-item  btn-outline-info btn-lg shadow"> Add
                                                <i class="fas fa-plus"></i></button>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <br>

                            {{--------------search Item Model----------------- --}}
                            <div class="modal fade" id="searchItemModel" tabindex="-1" role="dialog"
                                aria-labelledby="searchItemModelLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title m-2" id="searchItemModelLabel"> Search Item </h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            {{-- -------------Item Details Table -------------- --}}
                                                            <div class="card-body ">
                                                                <div class="table-responsive ">
                                                                    <div class="table-data ">
                                                                        <table
                                                                            class="table table-bordered table-center table-hover mt-3"
                                                                            id="ItemTable">
                                                                            <thead>
                                                                                <tr class="table-secondary">
                                                                                    <th>Code</th>
                                                                                    <th>Name</th>
                                                                                    <th>Price</th>
                                                                                    <th>Action</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($itemDetails as $key=>$ItemData)
                                                                                <tr>
                                                                                    <td>{{$ItemData->Item_code }}</td>
                                                                                    <td>{{$ItemData->Item_description}}</td>
                                                                                    <td>{{$ItemData->saleprice}}</td>
                                                                                    <td>
                                                                                        <a href=""
                                                                                            class="btn btn-outline-info btn-sm shadow"
                                                                                            name="add_item"
                                                                                            id="add_item"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#searchItemModel"
                                                                                            data-id="{{$ItemData->id}}"
                                                                                            data-add_item_code="{{$ItemData->Item_code}}"
                                                                                            data-Item_description="{{$ItemData->Item_description}}">
                                                                                            Add <i class="fas fa-plus"></i>
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            {{-- dynamicAdded table --}}
                            <table class="table table-bordered " id="dynamicAdded">
                            </table>

                            {{-- table footer for total calculations --}}
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="stock-total-row">
                                        <td style="width:8%;"> </td>
                                        <td style="width:8%;"><strong>TOTAL :</strong></td>
                                        <td style="width:5%;"> </td>
                                        <td style="width:5%;"></td>
                                        <td class="total-unit-price text-center" style="width:8%;">
                                            <span class="stock-total-badge">0.00</span>
                                        </td>
                                        <td class="total-total_weight text-center" style="width:8%;">
                                        </td>
                                        <td class="total-discount text-center" style="width:8%;">
                                            <span class="stock-total-badge">0.00</span>
                                        </td>
                                        <td class="total-value text-center" style="width:8%;">
                                            <span class="stock-total-badge">0.00</span>
                                        </td>
                                        <td style="width:11%;"></td>
                                    </tr>
                                </tbody>
                            </table>
                                </div>
                            </div>

                            {{-- bottom values section  --}}
                            <div class="row mt-3">
                                <div class="row mt-1 justify-content-between">
                                    <div class="col-md-4">
                                        <div class="input-group ">
                                            <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon4">
                                                CASH PAY :</div>
                                            <input type="number" style="font-weight:bold;" class="form-control"
                                                placeholder="CASH PAY :" id="cash_payment" name="cash_payment"
                                                aria-label="CASH PAY :" aria-describedby="btnGroupAddon4">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon5">
                                                GROSS AMOUNT :</div>
                                            <input type="number" style="font-weight:bold;" class="form-control"
                                                placeholder="GROSS AMOUNT :" id="total_amount" name="gross_amount"
                                                aria-label="GROSS AMOUNT :" aria-describedby="btnGroupAddon5" />
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3 justify-content-between">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon6">
                                                CREDITE :&nbsp;&nbsp;</div>
                                            <input type="number" style="font-weight:bold;" class="form-control"
                                                placeholder="CREDITE :" id="credite_payment" name="credite_payment"
                                                aria-label="CREDITE :" aria-describedby="btnGroupAddon6">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon7">
                                                DISCOUNT
                                                :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </div>
                                            <input type="number" style="font-weight:bold;" class="form-control"
                                                placeholder="DISCOUNT :" id="paid_discount" name="discount"
                                                aria-label="DISCOUNT :" aria-describedby="btnGroupAddon7" required>
                                        </div>
                                    </div>
                                </div>


                                <div class="row mt-3 justify-content-between">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon8">
                                                CHEQUE :&nbsp;&nbsp;</div>
                                            <input type="number" style="font-weight:bold;" class="form-control"
                                                placeholder="CHEQUE :" id="cheque_payment" name="cheque_payment"
                                                aria-label="CHEQUE :" aria-describedby="btnGroupAddon8">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <div class="input-group-text" style="font-weight:bold;" id="btnGroupAddon9">
                                                NET AMOUNT :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                                            <input type="number" style="font-weight:bold;" class="form-control"
                                                placeholder="NET AMOUNT :" id="paid_amount" name="net_amount"
                                                aria-label="NET AMOUNT :" aria-describedby="btnGroupAddon9" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3 justify-content-between">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <span class="input-group-text">Serial Number</span>
                                            <textarea class="form-control" aria-label="With textarea" id="serial_number" name="serial_number"></textarea>
                                          </div>
                                    </div>

                                </div>
                            </div>

                            {{-- bottom buttons  --}}
                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" name="save" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save
                                </button>
                                <button type="button" name="print" class="btn printReceipt btn-outline-primary">
                                    <i class="fas fa-print"></i> Print
                                </button>
                                <button type="button" name="pawn_delete" id="pawn_delete"
                                    class="btn btn-outline-danger pawn_delete d-none">DELETE</button>
                                <button type="button" name="pawn_cancel" id="pawn_cancel"
                                    class="btn btn-outline-warning pawn_cancel d-none"
                                    onclick="if(confirm('Discard this form and start over?')) window.location.reload();">CANCEL</button>
                            </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footer')
    </div>


    {{--------------Add customer model----------------- --}}
    <div class="modal fade" id="addBranchModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title m-2" id="myLargeModalLabel"> Add Customers </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="errMsgContainer2"></div>
                                    <form action="" id="updateBranch" method="POST">
                                        @csrf
                                        <input type="hidden" id="up_id" name="up_id">
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1"
                                                class="form-label">
                                                BC Code </label>
                                            <input type="text" class="form-control"
                                                id="up_bccode" name="up_bccode"
                                                placeholder="Branch Code" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea1"
                                                class="form-label">Name</label>
                                            <input type="text" class="form-control" id="up_name"
                                                name="up_name" placeholder="Branch Name"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleFormControlTextarea1"
                                                class="form-label">Address</label>
                                            <input type="text" class="form-control"
                                                id="up_address" name="up_address"
                                                placeholder="Branch Address" required>
                                        </div>
                                        <label for="exampleFormControlInput1"
                                            class="form-label">Contact</label>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input type="text" class="form-control"
                                                    id="up_contact1" name="up_contact1"
                                                    placeholder="Contact 1" required>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control"
                                                    id="up_contact2" name="up_contact2"
                                                    placeholder="Contact 2">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="exampleFormControlInput1"
                                                class="form-label">
                                                Date Of Joined
                                            </label>
                                            <input type="date" class="form-control" id="up_date"
                                                name="up_date">
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button class="btn btn-success update_branch"
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




    </div>

    {!! Toastr::message() !!}

    {{-- filter already-added item rows by code/description --}}
    <script>
        $(document).on('keyup', '#stockItemSearch', function () {
            let q = $(this).val().toLowerCase();
            $('#dynamicAdded tr').each(function () {
                let text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(q) !== -1);
            });
        });
    </script>

    {{-- form default date set for today --}}
    <script>
        var dateObj = new Date();
        document.getElementById('invoice_date').value = dateObj.toISOString().slice(0, 10);

    </script>

    {{-- CSRF Token --}}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>
{{--
Branch Add --}}
<script>
    $(document).ready(function () {
        //add new branch
        $(document).on('click', '.add_branch', function (e) {
            e.preventDefault();
            let bccode = $('#bccode').val();
            let name = $('#name').val();
            let address = $('#address').val();
            let contact1 = $('#contact1').val();
            let contact2 = $('#contact2').val();
            let date = $('#date').val();
            $.ajax({
                url: "{{ route('add_branch_ajax') }}",
                method: 'post',
                data: {
                    bccode: bccode,
                    name: name,
                    address: address,
                    contact1: contact1,
                    contact2: contact2,
                    date: date
                },
                success: function (res) {
                    if (res.status == 'success') {
                        $("#addBranchModel").modal('hide');
                        $('#addBranch')[0].reset();
                        $('.table').load(location.href + ' .table');
                        Command: toastr["success"]("Branch Added ...!", "Success")
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

    });
</script>
<script>
    // Custom validation to ensure only one payment type is filled
    document.getElementById('sales_form').addEventListener('submit', function (event) {
        var cashPayment = document.getElementById('cash_payment').value.trim();
        var creditPayment = document.getElementById('credit_payment').value.trim();
        var chequePayment = document.getElementById('cheque_payment').value.trim();

        var filledCount = [cashPayment, creditPayment, chequePayment].filter(function (payment) {
            return payment !== '';
        }).length;

        if (filledCount !== 1) {
            alert('Please fill exactly one payment type.');
            event.preventDefault(); // Prevent form submission
        }
    });
</script>


    {{-- value auto calculation --}}
    <script>
        $(document).ready(function () {
            // Listen for changes in the amount and advance fields
            $('#amount, #advance').on('input', function () {
                // Get values from the Weight and QTY fields
                let amount = parseFloat($('#amount').val()) || 0;
                let advance = parseInt($('#advance').val()) || 0;

                // Calculate the value
                let balance = (amount - advance).toFixed(2);

                // Update the Value field
                $('#balance').val(balance);
            });
        });

    </script>



{{-- Item description show acording to item code --}}
<script>
    $(document).ready(function () {
        // Listen for changes in the code name fields
        $('#item_code').on('keyup', function () {
            setItemDetails();

        });
    });
</script>

{{-- search and get invoice data using invoice _no  --}}
<script>
    $(document).ready(function () {
        // search receipt data
        $('#invoice_no').on('input', function (e) {
            e.preventDefault();
            let search_receipt_no = $('#invoice_no').val();
            var search_string = $('#searchCustomer').val();

            if (search_receipt_no > 0) {

                // get t_pawn_details table data
                $.ajax({
                    url: "{{ route('find_invoice') }}",
                    method: 'GET',
                    data: {
                        search_receipt_no: search_receipt_no
                    },
                    success: function (res) {
                        $('#dynamicAdded').html(res);
                        if (res.status == 'not_found') {
                            $('#dynamicAdded').html(
                                `
                                <div  style ="text-align:center;">
                                <label class="text-danger mt-3 mb-3">Receipt Not Found ...!!</label>
                                </div>
                                `
                            );
                        }
                    }
                });

                // get t_pawn_sum table data
                $.ajax({
                    url: "{{ route('find_invoice_customer_data') }}",
                    method: 'GET',
                    data: {
                        search_receipt_no: search_receipt_no
                    },
                    success: function (response) {

                        if (response.status == 'success') {
                            let records = response.data;
                            let Invoice_no,Invoice_date, Customer_NIC, Customer_Name, Customer_Phone,
                                Customer_Address,Gross_Amount,Discount,Net_Amount;

                            records.forEach(record => {
                                Invoice_no = record.Invoice_no;
                                Invoice_date = record.Invoice_date;
                                Customer_NIC = record.Customer_NIC;
                                Customer_Name = record.Customer_Name;
                                Customer_Phone = record.Customer_Phone;
                                Customer_Address =record.Customer_Address;
                                Gross_Amount = record.Gross_Amount;
                                Discount = record.Discount;
                                Net_Amount = record.Net_Amount;

                                // Update the input values
                                $('#invoice_no').val(Invoice_no);
                                $('#invoice_date').val(Invoice_date);
                                $('#searchCustomer').val(Customer_NIC);
                                $('#customer_name').val(Customer_Name);
                                $('#customer_contact_1').val(Customer_Phone);
                                $('#total_amount').val(Gross_Amount);
                                $('#paid_discount').val(Discount);
                                $('#paid_amount').val(Net_Amount);

                                $('#green-total-unit-price').val(Gross_Amount);
                                $('#green-total-discount').val(Discount);
                                $('#green-total-value').val(Net_Amount);



                            });

                            $('.showCustomer').html(
                                `
                                <div class="row mt-2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_name">Customer Name : </label>
                                            <input class="form-control " type="text"
                                                value= "${Customer_Name}"
                                                id="customer_name" name="customer_name"  readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_nic">NIC : </label>
                                            <input class="form-control " type="text"
                                                value= "${Customer_NIC}"
                                                id="customer_nic" name="customer_nic" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_phone">Customer Tel : </label>
                                            <input class="form-control " type="text"
                                                value= "${Customer_Phone}"
                                                id="customer_phone" name="customer_phone" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                            <label for="customer_address">Address : </label>
                                            <input class="form-control " type="text"
                                                value= "${Customer_Address}"
                                                id="customer_address" name="customer_address" readonly required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                `
                            );


                        } else if (response.status == 'not_found') {
                            $('.customer-data').html(
                                `
                                    <label for="customer_name" class="text-danger">  Customer Not Found ..!!
                                    </label>
                                    `
                            );
                            $('.payment-history').html(``);
                        }
                    }
                });

                        } else {
                            $('#dynamicAdded').html('');
                            $('.customer-data').html('');
                            $('.payment-history').html('');
                        }
        })
    });

</script>

{{-- select items using table row as a button --}}
<script>
    var table = document.getElementById("ItemTable");
    var rows = table.getElementsByTagName("tr");
    // Add a click event listener to each row
    for (var i = 0; i < rows.length; i++) {
        rows[i].addEventListener("click", function() {
            var item_code_add = this.cells[0].textContent;
            $('#item_code').val(item_code_add);
            setItemDetails();
            $("#searchItemModel").modal('hide');
            $('#item_name').val("");
            $('#getItemCode').reset();
        });
    }
</script>

{{-- set item data function when inserting the item code or name --}}
<script>
    function setItemDetails() {
                // Listen for changes in the Weight and QTY fields
                let Item_code = $('#item_code').val();
                $.ajax({
                    url: "{{ route('show_select_item_description_ajax') }}",
                    method: 'GET',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        Item_code: Item_code
                    },
                    success: function (res) {
                        if (res.status == 'success') {

                            var itemDescriptionSelected = $(
                                '#item_description');
                            var itemUnit_price = $('#unit_price');
                            // Change this to match your items select element
                            // Clear existing options
                            itemDescriptionSelected.empty();
                            itemUnit_price.empty();

                            $.each(res.data, function (index, item) {
                                itemDescriptionSelected.append($(
                                    '<option>', {
                                        value: item
                                            .Item_description,
                                        text: item
                                            .Item_description
                                    }));

                                itemUnit_price.val(item.saleprice);
                            });

                        }
                    },
                    error: function (err) {
                        $('.errMsgContainer').html('');
                        let error = err.responseJSON;
                        $.each(error.errors, function (index, value) {
                            $('.errMsgContainer').append(
                                '<span class="text-danger">' +
                                value +
                                '<span>' + '<br>');
                        });
                    }
                });

                //getting weight
                $.ajax({
                    url: "{{ route('get_weight_ajax') }}",
                    method: 'GET',
                   success: function (res) {
                        if (res.status == 'success') {
                            // Process the data returned from the PHP script here
                            let weight = res.data;
                            $('#qty').val(weight);

                            //set net price
                            let unit_price_value = $('#unit_price').val();
                            let unit_qty = $('#qty').val();
                            let net_value_row = unit_qty * unit_price_value;
                            if (unit_qty != null) {
                                $('#net_value').val(net_value_row);
                            }
                        }

                    },
                    error: function (err) {
                        // Handle errors here
                        console.error('Error:', err);
                    }
                });

    }
</script>

{{--  add item details to form when click Add button --}}
<script>
    $(document).ready(function () {
        // add item details to form
        $(document).on('click', '#add_item', function () {
            let id = $(this).data('id');
            let add_item_code = $(this).data('add_item_code');
            $('#item_code').val(add_item_code);
            setItemDetails();
        });
    });
</script>

{{-- search item data using item name --}}
<script>
    $('#item_name').on('keyup', function (e) {
                e.preventDefault();
                let search_string = $('#item_name').val();
                $.ajax({
                    url: "{{ route('search_items_ajax') }}",
                    method: 'GET',
                    data: {
                        search_string: search_string
                    },
                    success: function (res) {
                        $('.table-data').html(res);
                        if (res.status == 'not_found') {
                            $('.table-data').html('<span class="text-danger">' +
                                'Nothing found...' + '</span>');
                        }
                    }
                });
            })
</script>



        {{--  get Branch data inserting NIC --}}
        <script>
            $(document).ready(function () {
                // search customer data
                $(document).on('keyup', function (e) {
                    e.preventDefault();
                    var search_string = $('#searchCustomer').val();
                    // console.log(search_string);
                    if (search_string != null) {
                        if (e.keyCode == 13 || e.keyCode == 10) {
                            $.ajax({
                                url: "{{ route('get_branchdetails_ajax') }}",
                                method: 'GET',
                                data: {
                                    search_string: search_string
                                },
                                success: function (res) {
                                    $('.showCustomer').html(res);

                                    if (res.status == 'not_found') {
                                        $('.showCustomer').html(
                                            `<div class="input-group">
                                                <p class="form-control text-danger text-center">
                                                    Branch Not Found ..!!
                                                </p>
                                             </div>`
                                        );
                                    }
                                }
                            });
                        }
                    }
                })
            });

        </script>

    {{-- add new customer script --}}
 <script></script>

    {{-- clear form --}}
    <script>
        $(document).ready(function () {
            //add new customer
            $(document).on('keyup', '#searchCustomer', function (e) {
                e.preventDefault();
                $('#customer_name').val("");
                $('#customer_phone').val("");
                $('#receipt_date').val("");
                $('#brand').val("");
                $('#device_model').val("");
                $('#imei_number').val("");
                $('#amount').val("");
                $('#advance').val("");
                $('#balance').val("");
                $('#problem_reported').val("");
                $('#status').val("");
            })
        });

    </script>

    {{-- add new row script --}}
    <script type="text/javascript">
        var i = -1;
        let dataArray = [];
        let totalWeight = 0;
        let total_totalWeight = 0;
        let totalQty = 0;
        let totalValue = 0;
        let totalGross = 0;
        let totalDiscount = 0;

        $(".add-item").click(function () {
            i++;
            let customer_nic = $('#customer_nic').val();
            let invoice_no = $('#invoice_no').val();
            let invoice_date = $('#invoice_date').val();
            let item_code = $('#item_code').val();
            let item_description = $('#item_description').val();
            let qty = $('#qty').val();
            let unit_price = $('#unit_price').val();
            let discount = $('#discount').val();
            let discount_val = $('#discount_val').val();
            let net_value = $('#net_value').val();

            if (customer_nic == "" || invoice_no == "" || item_code == "" || qty == "" || unit_price == "" ||
                net_value == "") {
                alert("Please fill in all the fields.");
                return;
            } else {

                // Construct the newRowData object
                let newRowData = {
                    customer_nic: customer_nic,
                    invoice_no: invoice_no,
                    invoice_date: invoice_date,
                    item_code: item_code,
                    item_description: item_description,
                    qty: qty,
                    unit_price: unit_price,
                    discount: discount,
                    discount_val: discount_val,
                    net_value: net_value,
                };

                // Add data to the array
                dataArray.push(newRowData);
                $("#dynamicAdded").prepend(
                    `
                    <tr>
                        <td style="width:15%;">
                            <input type="hidden" name="inputs[` + i + `][customer_nic]" value="` + customer_nic + `">
                            <input type="hidden" name="inputs[` + i + `][invoice_no]" value="` + invoice_no + `">
                            <input type="hidden" name="inputs[` + i + `][invoice_date]" value="` + invoice_date + `">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="Item Code"
                                        id="dy_item_code" name="inputs[` + i + `][item_code]" value="` + item_code + `" readonly>
                        </td>

                        <td style="width:18%;">
                            <select class="select form-control" style="text-align: center;" id="dy_item_description"
                            name="inputs[` + i + `][item_description]" aria-hidden="true" readonly>
                                <option value="">Please Select</option>
                                @foreach($itemCode as $itemData)
                                    <option value="{{ $itemData->Item_description}}">{{ $itemData->Item_description}}</option>
                                @endforeach
                            </select>
                        </td>

                        <td style="width:10%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="QTY"
                            id="dy_qty" name="inputs[` + i + `][qty]" value="` + qty + `" readonly>
                        </td>

                        <td style="width:12%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="Unit Price"
                            id="dy_unit_price" name="inputs[` + i + `][unit_price]" value="` + unit_price + `" readonly>
                        </td>

                        <td style="width:12%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="Discount"
                            id="dy_discount" name="inputs[` + i + `][discount]" value="` + discount + `" readonly>
                        </td>

                        <td style="width:12%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="Discount Val"
                            id="dy_discount_val" name="inputs[` + i + `][discount_val]" value="` + discount_val + `" readonly>
                        </td>

                        <td style="width:13%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="Net Value"
                            id="dy_net_value" name="inputs[` + i + `][net_value]" value="` + net_value + `" readonly>
                        </td>

                        <td style="width:6%;">
                            <center>
                                <button type="button" class="btn btn-outline-danger text-center shadow remove-input-field m-2"> <i class="far fa-trash-alt me-1"></i> Delete </button>
                            </center>
                        </td>

                    </tr>
                    `
                );
            }

            // set values in select option
            $('#dy_item_code').val(item_code);
            $('#dy_item_description').val(item_description);

            // totalWeight = parseFloat(totalWeight) + parseFloat(weight);
            totalGross = parseFloat(totalGross) + (parseFloat(unit_price)*qty);
            totalDiscount = parseFloat(totalDiscount) + parseFloat(discount_val);
            totalValue = parseFloat(totalValue) + parseFloat(net_value);
            setTotal();
            resetTableRow()
        });

        // Function to reset the input fields and dropdowns in the table row
        function resetTableRow() {
            // document.getElementById("item_code").value = "";
            document.getElementById("item_description").value = "";
            document.getElementById("qty").value = "";
            document.getElementById("unit_price").value = "0";
            document.getElementById("discount").value = "0";
            document.getElementById("discount_val").value = "0";
            document.getElementById("net_value").value = "0";
        }

        // get total function
        function setTotal() {
            // $('.total-weight').html(
            //     `<p><strong>` + totalWeight + `</strong></p>`
            // );
            $('.total-unit-price').html(
                `<p><strong>` + totalGross + `</strong></p>`
            );
            $('.total-discount').html(
                `<p><strong>` + totalDiscount + `</strong></p>`
            );
            $('.total-value').html(
                `<p><strong>` + totalValue + `</strong></p>`
            );

            // let totalValueFormat = number_format(totalValue,2);
            document.getElementById("total_amount").value = totalGross;
            document.getElementById("paid_discount").value = totalDiscount;
            document.getElementById("paid_amount").value = totalValue;
        };

    </script>

    {{-- delete added item rows script --}}
    <script>
        $(document).on('click', '.remove-input-field', function () {
            var row = $(this).parents('tr');
            var re_item_code = row.find('#dy_item_code');
            var re_item_description = row.find('#dy_item_description');
            var re_qty = row.find('#dy_qty');
            var re_unit_price = row.find('#dy_unit_price');
            var re_total_discount = row.find('#dy_discount_val');
            var re_net_value = row.find('#dy_net_value');

            // var re_weightValue = re_weight.val();
            var re_grossValue = re_unit_price.val()*re_qty.val();
            var re_discountValue = re_total_discount.val();
            var re_valueValue = re_net_value.val();

            // totalWeight = parseInt(totalWeight) - parseInt(re_weightValue);
            // total_totalWeight = parseInt(total_totalWeight) - parseInt(re_total_weightValue);
            totalGross = parseInt(totalGross) - parseInt(re_grossValue);
            totalDiscount = parseInt(totalDiscount) - parseInt(re_discountValue);
            totalValue = parseInt(totalValue) - parseInt(re_valueValue);

            // Remove data from the array
            var index = row.index();
            dataArray.splice(index, 1);

            $(this).parents('tr').remove();
            setTotal();
        });

    </script>

    {{-- change net value calculation when change the QTY field --}}
    <script>
        $(document).on('keyup', '#qty', function () {
            let unit_price_value = $('#unit_price').val();
            let unit_qty = $('#qty').val();
            // let qty_decimal = Math.trunc(unit_qty);
            let net_value_row = unit_qty * unit_price_value;

            if (unit_qty != null) {
                $('#net_value').val(net_value_row);
            }
        })

    </script>

    {{-- change net value calculation when change the discount precentag field --}}
    <script>
        $(document).on('keyup', '#discount', function () {
            let unit_price_value = $('#unit_price').val();
            let unit_qty = $('#qty').val();
            // let qty_decimal = Math.trunc(unit_qty);
            let net_value_row = unit_qty * unit_price_value;

            let unit_discount = $('#discount').val();
            let discounted_value = net_value_row * (unit_discount / 100);
            let discounted_net_value = net_value_row - discounted_value;

            if (unit_discount > 0) {
                $('#discount_val').val(discounted_value);
                $('#net_value').val(discounted_net_value);
            } else {
                // $('#discount').val("0");
                $('#net_value').val(net_value_row);
            }
        })

    </script>

    {{-- change net value calculation when change the discount value field --}}
    <script>
            $(document).on('keyup', '#discount_val', function () {
                let unit_price_value = $('#unit_price').val();
                let unit_qty = $('#qty').val();
                // let qty_decimal = Math.trunc(unit_qty);
                let net_value_row = unit_qty * unit_price_value;

                let unit_discount = $('#discount_val').val();
                let discounted_value = unit_discount;
                let discounted_net_value = net_value_row - discounted_value;

                if (unit_discount > 0) {
                    // $('#discount_val').val(discounted_value);
                    $('#net_value').val(discounted_net_value);
                } else {
                    // $('#discount').val("0");
                    $('#net_value').val(net_value_row);
                }
            })

    </script>

                {{-- add new invoice script --}}
                <script>
                    $(document).ready(function () {
                        $(document).on('click', '.add_invoice', function (e) {
                            e.preventDefault();
                            let invoice_no = $('#invoice_no').val();
                            let invoice_date = $('#invoice_date').val();
                            let customer_nic = $('#customer_nic').val();
                            let customer_name = $('#customer_name').val();
                            let customer_phone = $('#customer_phone').val();
                            let brand = $('#brand').val();
                            let device_model = $('#device_model').val();
                            let imei_number = $('#imei_number').val();
                            let receipt_date = $('#receipt_date').val();
                            let completed_on = $('#completed_on').val();
                            let status = $('#status').val();
                            let amount = $('#amount').val();
                            let advance = $('#advance').val();
                            let balance = $('#balance').val();

                            $.ajax({
                                url: "{{ route('add_invoice_ajax') }}",
                                method: 'post',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    invoice_no: invoice_no,
                                    invoice_date: invoice_date,
                                    customer_nic: customer_nic,
                                    customer_name: customer_name,
                                    customer_phone: customer_phone,
                                    brand: brand,
                                    device_model: device_model,
                                    imei_number: imei_number,
                                    receipt_date: receipt_date,
                                    completed_on: completed_on,
                                    status: status,
                                    amount: amount,
                                    advance: advance,
                                    balance: balance,
                                },

                                success: function (res) {
                                    if (res.status == 'success') {
                                        $("#addInvoiceModel").modal('hide');
                                        $('#addInvoice')[0].reset();
                                        $('.table').load(location.href + ' .table');
                                        Command: toastr["success"]("Invoice Added ...!",
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

                                        // get customer data
                                        // let data = res.telNo;
                                        // let cus_details = data[0];
                                        // let cus_tel = cus_details.Contact_1;
                                        // let cus_name = cus_details.First_name;

                                        // $('#searchCustomer').val(cus_tel);
                                        // $('#customer_name').val(cus_name);
                                    }
                                },
                                error: function (err) {
                                    $('.errMsgContainer').html('');
                                    let error = err.responseJSON;
                                    $.each(error.errors, function (index, value) {
                                        $('.errMsgContainer').append(
                                            '<span class="text-danger">' +
                                            value +
                                            '<span>' + '<br>');
                                    });
                                }
                            })
                        })
                    });

                </script>

                {{-- delete invoice script --}}
                <script>
                    $(document).ready(function () {
                        $(document).on('click', '.delete_invoice', function (e) {
                            e.preventDefault();
                            let invoice_id = $(this).data('id');

                            if (confirm('Are you sure to delete invoice ?')) {
                                $.ajax({
                                    url: "{{ route('delete_invoice_ajax') }}",
                                    method: 'post',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        invoice_id: invoice_id
                                    },
                                    success: function (res) {
                                        if (res.status == 'success') {
                                            $('.table').load(location.href + ' .table');
                                            Command: toastr["success"]("Invoice deleted...",
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
                                    }
                                });
                            }
                        })
                    });

                </script>

<script>
    $(document).ready(function () {
        // Listen for changes in the Weight and QTY fields
        $('#Branch_Code').on('change', function () {
            let amount = $('#Branch_Code').val();
            $.ajax({
                        url: "{{ route('show_Branch_Details_ajax') }}",
                        method: 'GET',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            amount: amount
                        },
                        success: function (res) {
                            if (res.status == 'success') {

                                var itemsSelected = $('#Branch_Name');
                                itemsSelected.empty();

                                $.each(res.data, function (index, item1) {
                                    itemsSelected.append($('<option>', {
                                        value: item1.Store_name ,
                                        text: item1.Store_name ,
                                    }));
                                });

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
</script>


<script>
    $(document).ready(function () {
        // Listen for changes in the Weight and QTY fields
        $('#To_Branch_Code').on('change', function () {
            let amount = $('#To_Branch_Code').val();
            $.ajax({
                        url: "{{ route('show_Branch_Details_ajax_two') }}",
                        method: 'GET',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            amount: amount
                        },
                        success: function (res) {
                            if (res.status == 'success') {

                                var itemsSelected = $('#To_Branch_Name');
                                itemsSelected.empty();

                                $.each(res.data, function (index, item1) {
                                    itemsSelected.append($('<option>', {
                                        value: item1.Store_name ,
                                        text: item1.Store_name ,
                                    }));
                                });

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
</script>


<script>
    $(document).ready(function () {
        $('#ItemTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthChange: false,
            ordering: true,
            info: true,
            autoWidth: false,
            language: {
                search: "Search:",
                zeroRecords: "No matching items found"
            }
        });
    });
</script>


                <script src="assets/js/jquery-3.6.0.min.js"></script>
                <script src="assets/js/feather.min.js"></script>
                <script src="assets/js/toastr.min.js"></script>

                <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
                <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
                <script src="assets/plugins/datatables/datatables.min.js"></script>
                <script src="assets/js/script.js"></script>
                <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
                <script src="assets/plugins/apexchart/chart-data.js"></script>
                {{-- <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script> --}}
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
                    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
                    crossorigin="anonymous">
                </script>

</body>

</html>
@endsection