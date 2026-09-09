@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Create Opening Hire Purchase</title>
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
                                <h4 class="card-title m-3">Opening Hire Purchase</h4>
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
                                <div class="alert alert-danger text-center" role="alert">
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


                                <form action="{{route("create_opening_hire_purchase")}}" method="post">
                                    @csrf
                                    <div class="row ">
                                        <div class="row mb-1 form-group justify-content-between">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <div class="input-group-text" id="btnGroupAddon1">Customer NIC :
                                                        </div>
                                                        <input type="text" id="customer_code" name="customer_code"
                                                            class="form-control" placeholder="Enter Customer NIC :"
                                                            required aria-label="Invoice Number:"
                                                            aria-describedby="btnGroupAddon1">
                                                        <div class="input-group-append">
                                                            <button type="button"
                                                                class="btn btn-success btn-lg form-control "
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#addCustomerModel">
                                                                <i class="fas fa-plus" style="color: white"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div id="guarantor_data" class="col-md-4 payment-history">
                                                </div>

                                                <div class="col">
                                                    <div class="input-group">
                                                        <div class="input-group-text" id="btnGroupAddon2">
                                                            No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :
                                                        </div>
                                                        <input type="text" id="invoice_no" name="invoice_no" value="{{$maxInvoiceNo+1}}"
                                                            class="form-control" placeholder="Hire Purchase No"
                                                            aria-label="Invoice Number:"
                                                            aria-describedby="btnGroupAddon2">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-9">
                                                </div>

                                                <div class="col">
                                                    <div class="input-group">
                                                        <div class="input-group-text" id="btnGroupAddon3">
                                                            Date&nbsp;&nbsp;&nbsp; :
                                                        </div>
                                                        <input type="date" id="invoice_date" name="invoice_date"
                                                            class="form-control" aria-label="Date:"
                                                            aria-describedby="btnGroupAddon3">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-5">

                                                    <div class="input-group">
                                                        <div class="input-group-text" id="btnGroupAddon10">Guarantor 1 Code &nbsp;:
                                                        </div>
                                                        <input type="text" id="guarantor_1_code" name="guarantor_1_code"
                                                            class="form-control" placeholder="Enter Guarantor Code :"
                                                            required aria-label="Invoice Number:"
                                                            aria-describedby="btnGroupAddon10">
                                                        <div class="input-group-append">
                                                            <button type="button"
                                                                class="btn btn-success btn-lg form-control "
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#addGuarantorModel">
                                                                <i class="fas fa-plus" style="color: white"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div id="guarantor_1_data" class="col-md-4">
                                                    <div class="input-group">
                                                        <p class="form-control text-danger text-center">
                                                            Guarantor Not Found ..!!
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="input-group">
                                                        <div class="input-group-text" id="btnGroupAddon5">Ref No :</div>
                                                        <input type="text" id="ref_no" name="ref_no"
                                                            class="form-control" aria-label="Date:"
                                                            aria-describedby="btnGroupAddon5">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-5">
                                                <div class="input-group">
                                                    <div class="input-group-text" id="btnGroupAddon11">Guarantor 2 Code :
                                                    </div>
                                                    <input type="text" id="guarantor_2_code" name="guarantor_2_code"
                                                        class="form-control" placeholder="Enter Guarantor Code :"
                                                        required aria-label="Invoice Number:"
                                                        aria-describedby="btnGroupAddon11">
                                                    <div class="input-group-append">
                                                        <button type="button"
                                                            class="btn btn-success btn-lg form-control "
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#addGuarantorModel">
                                                            <i class="fas fa-plus" style="color: white"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                </div>
                                                <div id="guarantor_2_data" class="col-md-4">
                                                    <div class="input-group">
                                                        <p class="form-control text-danger text-center">
                                                            Guarantor Not Found ..!!
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="input-group">
                                                        <div class="input-group-text" id="btnGroupAddon5">Agreement No :</div>
                                                        <input type="text" id="agreement_no" name="agreement_no"
                                                            class="form-control" aria-label="agreement_no:"
                                                            aria-describedby="btnGroupAddon5">
                                                    </div>
                                                </div>
                                            </div>



                                        </div>

                                        {{-- heading inputs --}}
                                        <div class="row form-group">
                                            <div class="col-md-4 ">
                                            </div>
                                            <div class="row">

                                                <div class="customer-data">

                                                </div>
                                                <div class="showCustomer">

                                                </div>

                                            </div>
                                        </div>

                                        {{-- dynamicAdded table --}}
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    {{-- <th style="width:15%; text-align: center;">Category</th> --}}
                                                    <th style="width:15%; text-align: center;">Item Code</th>
                                                    <th style="width:20%; text-align: center;">Description</th>
                                                    <th style="width:12%; text-align: center;">Unit Price</th>
                                                    <th style="width:12%; text-align: center;">QTY</th>
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

                                                        <div class="input-group">

                                                            <input type="text" id="item_code" name="item_code"
                                                                class="form-control" placeholder="item Code" required
                                                                aria-label="item Code"
                                                                aria-describedby="btnGroupAddon1">
                                                            <div class="input-group-append">
                                                                <button type="button"
                                                                    class="btn btn-success btn-lg form-control "
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#searchItemModel">
                                                                    <i class="fas fa-plus" style="color: white"></i>
                                                                </button>
                                                            </div>
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
                                                        <input class="form-control" type="text" placeholder="Unit Price"
                                                            id="unit_price" name="unit_price" value="0">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="text" placeholder="QTY"
                                                            id="qty" name="qty">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="number" placeholder="Discount"
                                                            id="discount" name="discount" min="0" max="100"
                                                            maxlength="6" value="0">
                                                    </td>
                                                    <td>
                                                        <input class="form-control" type="text"
                                                            placeholder="Discount Val" id="discount_val"
                                                            name="discount_val" value="0">
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
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title m-2" id="searchItemModelLabel"> Search
                                                            Item </h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="errMsgContainer"></div>
                                                                        <form action="" method="post" id="getItemCode">
                                                                            @csrf
                                                                            <div class="col"></div>
                                                                            <div class="col-md-7">
                                                                                <div class="input-group">
                                                                                    <div class="input-group-text"
                                                                                        id="btnGroupAddonItem1">Item
                                                                                        Name :</div>
                                                                                    <input type="text" id="item_name"
                                                                                        name="item_name"
                                                                                        class="form-control"
                                                                                        placeholder="Enter Item Name :"
                                                                                        aria-label="Item Name :"
                                                                                        aria-describedby="Item1">
                                                                                </div>
                                                                            </div>

                                                                            <div class="col"></div>
                                                                        </form>

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
                                                                                                {{-- <th>Purchase Price</th> --}}
                                                                                                <th>Price</th>
                                                                                                {{-- <th>Branch</th>
                                                                                    <th>BC</th> --}}
                                                                                                <th>Action</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            @foreach ($itemDetails as $key=>$ItemData)
                                                                                            <tr>
                                                                                                <td>{{$ItemData->Item_code }}
                                                                                                </td>
                                                                                                <td>{{$ItemData->Item_description}}
                                                                                                </td>
                                                                                                {{-- <td>{{$ItemData->purchasePrice}}
                                                                                                </td> --}}
                                                                                                <td>{{$ItemData->saleprice}}
                                                                                                </td>
                                                                                                {{-- <td>{{$ItemData->Branch}}
                                                                                                </td>
                                                                                                <td>{{$ItemData->BranchCode}}
                                                                                                </td>
                                                                                                --}}

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
                                                                                                        Add <i
                                                                                                            class="fas fa-plus"></i>
                                                                                                    </a>
                                                                                                </td>
                                                                                            </tr>
                                                                                            @endforeach
                                                                                        </tbody>
                                                                                    </table>
                                                                                    {!! $itemDetails->links() !!}
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
                                        <table class="table table-bordered" id="dynamicAdded">
                                        </table>

                                        {{-- table footer for total calculations --}}
                                        <table class="table table-bordered" id="green_total_row">
                                            <tbody>
                                                <tr class="table-success">
                                                    <td style="width:8%;"> </td>
                                                    <td style="width:8%;"><strong>
                                                            <p>TOTAL :</p>
                                                        </strong> </td>
                                                    <td style="width:5%;"> </td>

                                                    <td class="total-unit-price text-center" style="width:8%;">
                                                        <p><strong>0.00</strong></p>
                                                    </td>
                                                    <td style="width:5%;"></td>
                                                    <td class="total-total_weight text-center" style="width:8%;">
                                                        <p><strong></strong></p>
                                                    </td>
                                                    <td class="total-discount text-center" style="width:8%;">
                                                        <p><strong>0.00</strong></p>
                                                    </td>
                                                    <td class="total-value text-center" style="width:8%;">
                                                        <p><strong>0.00</strong></p>
                                                    </td>
                                                    <td style="width:11%;"></td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        {{-- bottom values Schema Type, Document Char, Down Payment, Transport   --}}
                                        <div class="row mt-4">
                                            <div class="row mt-1 justify-content-between">
                                                <div class="col-md-4">

                                                    <div class="input-group ">
                                                        <div class="input-group-text"
                                                            style="font-weight:bold; font-size: 14px;"
                                                            id="inputGroup-sizing-sm">
                                                            Schema Type &nbsp;&nbsp;&nbsp; : </div>
                                                        <select class="select form-control " id="schema_type"
                                                            name="schema_type" aria-hidden="true">
                                                            <option value="">Select an item</option>
                                                            @foreach($schemaDetails as $data)
                                                            <option value="{{ $data->SchemaType }}">
                                                                {{ $data->SchemaType}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="input-group-text"
                                                            style="font-weight:bold; font-size: 14px;"
                                                            id="btnGroupAddon6">
                                                            Document Char :</div>
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Document Char"
                                                            id="document_charge_rate" name="document_charge_rate"
                                                            aria-label="Document Char"
                                                            aria-describedby="btnGroupAddon6">
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Document Char"
                                                            id="document_charge" name="document_charge"
                                                            aria-label="Document Char"
                                                            aria-describedby="btnGroupAddon6">
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="input-group-text"
                                                            style="font-weight:bold; font-size: 14px;"
                                                            id="btnGroupAddon8">
                                                            Down Payment &nbsp;: </div>
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Down Payment :"
                                                            id="down_payment_rate" name="down_payment_rate"
                                                            aria-label="Down Payment :"
                                                            aria-describedby="btnGroupAddon8">
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Down Payment :"
                                                            id="down_payment" name="down_payment"
                                                            aria-label="Down Payment :"
                                                            aria-describedby="btnGroupAddon8">
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="input-group-text"
                                                            style="font-weight:bold; font-size: 14px;"
                                                            id="btnGroupAddon7">
                                                            Transport
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            :
                                                        </div>
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Transport" id="transport"
                                                            name="transport" aria-label="Transport"
                                                            aria-describedby="btnGroupAddon7">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <div class="input-group-text"
                                                            style="font-weight:bold; font-size: 14px;"
                                                            id="btnGroupAddon5">
                                                            Int.Rate
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            :</div>
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Int.Rate" id="int_rate"
                                                            name="int_rate" aria-label="Int.Rate"
                                                            aria-describedby="btnGroupAddon5" />
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Int.amount"
                                                            id="int_amount" name="int_amount" aria-label="Int.amount"
                                                            aria-describedby="btnGroupAddon5" />
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="input-group-text"
                                                            style="font-weight:bold; font-size: 14px;"
                                                            id="btnGroupAddon7">
                                                            No Of inst &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                                        </div>
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="No Of instalment"
                                                            id="no_of_inst" name="no_of_inst" aria-label="No Of inst"
                                                            aria-describedby="btnGroupAddon7">
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="input-group-text"
                                                            style="font-weight:bold; font-size: 14px;"
                                                            id="btnGroupAddon9">
                                                            Inst. Due Date: </div>
                                                        <input type="date" style="font-weight:bold;"
                                                            class="form-control" placeholder="Inst. Due Date :"
                                                            id="inst_due_date" name="inst_due_date"
                                                            aria-label="Inst. Due Date :"
                                                            aria-describedby="btnGroupAddon9">
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="input-group-text"
                                                            style="font-weight:bold; font-size: 14px;"
                                                            id="btnGroupAddon5">
                                                            Instalment&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</div>
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Instalment"
                                                            id="instalment" name="instalment" aria-label="Instalment"
                                                            aria-describedby="btnGroupAddon5" />
                                                    </div>

                                                </div>
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <div class="input-group-text"
                                                            style="font-weight:bold; font-size: 14px;"
                                                            id="btnGroupAddon5">
                                                            Gross Amount :</div>
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Gross Amount"
                                                            id="total_amount" name="gross_amount"
                                                            aria-label="Gross Amount"
                                                            aria-describedby="btnGroupAddon5" />
                                                    </div>
                                                    <input type="hidden" name="fixed_gross_amount" id="fixed_gross_amount">
                                                    <div class="input-group mt-1">
                                                        <div class="input-group-text"
                                                            style="font-weight:bold; font-size: 14px;"
                                                            id="btnGroupAddon7">
                                                            Discount
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                                        </div>
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Discount"
                                                            id="paid_discount" name="discount" aria-label="Discount"
                                                            aria-describedby="btnGroupAddon7" required>
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="input-group-text"
                                                            style="font-weight:bold; font-size: 14px;"
                                                            id="btnGroupAddon9">
                                                            Net Amount &nbsp;&nbsp;&nbsp;&nbsp;: </div>
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Net Amount"
                                                            id="net_amount" name="net_amount" aria-label="Net Amount"
                                                            aria-describedby="btnGroupAddon9" required />
                                                    </div>
                                                    <input type="hidden" name="fixed_net_amount" id="fixed_net_amount">

                                                </div>
                                            </div>
                                        </div>

                                        {{-- payment options --}}
                                        <div class="row mt-1">
                                            <div class="row mt-4 justify-content-between">
                                                <div class="col">
                                                    <p>Payment Options</p>
                                                </div>
                                            </div>
                                            <div class="row mt-2 justify-content-between">
                                                <div class="col-md-4">
                                                    <div class="input-group ">
                                                        <div class="input-group-text" style="font-weight:bold;"
                                                            id="btnGroupAddon4">
                                                            Cash Pay :</div>
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Cash Pay"
                                                            id="cash_payment" name="cash_payment" aria-label="Cash Pay"
                                                            aria-describedby="btnGroupAddon4">
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="input-group-text" style="font-weight:bold;"
                                                            id="btnGroupAddon6">
                                                            Card Pay&nbsp;:</div>
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Card Pay"
                                                            id="card_payment" name="card_payment" aria-label="Card Pay"
                                                            aria-describedby="btnGroupAddon6">
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="input-group-text" style="font-weight:bold;"
                                                            id="btnGroupAddon8">
                                                            Cheque&nbsp;&nbsp;&nbsp;:</div>
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Cheque"
                                                            id="cheque_payment" name="cheque_payment"
                                                            aria-label="Cheque" aria-describedby="btnGroupAddon8">
                                                    </div>

                                                    <div class="input-group mt-1">
                                                        <div class="input-group-text" style="font-weight:bold;"
                                                            id="btnGroupAddon8">
                                                            Bank Tr.&nbsp;&nbsp;:</div>
                                                        <input type="text" style="font-weight:bold;"
                                                            class="form-control" placeholder="Bank Transfer"
                                                            id="bank_transfer" name="bank_transfer"
                                                            aria-label="Bank Transfer"
                                                            aria-describedby="btnGroupAddon8">
                                                    </div>

                                                </div>
                                                <div class="col-md-4"></div>
                                                <div class="col-md-4"></div>
                                            </div>



                                        </div>

                                        {{-- bottom buttons  --}}
                                        <div class="row">
                                            <div class="col-md-5"></div>
                                            <div class="col-md-7">
                                                <br>
                                                <button type="submit" name="save"
                                                    class="btn btn-outline-info btn-lg shadow">SAVE</button>
                                                <button type="button" name="print"
                                                    class="btn btn-outline-primary print_invoice btn-lg shadow">PRINT</button>
                                                <button type="button" name="pawn_delete" id="pawn_delete"
                                                    class="btn btn-outline-danger btn-lg shadow pawn_delete">DELETE</button>
                                                <button type="button" name="pawn_cancel" id="pawn_cancel"
                                                    class="btn btn-outline-warning btn-lg shadow pawn_cancel">CANCEL</button>
                                                <button type="reset" name="reset"
                                                    class="btn btn-outline-secondary btn-lg shadow">RESET</button>
                                            </div>
                                        </div>

                                </form>


    {{-- ------------Add Guarantor model----------------- --}}
    <div class="modal fade" id="addGuarantorModel" tabindex="-1"
        role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title m-2" id="myLargeModalLabel">
                    Add Guarantor </h4>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="errMsgContainer"></div>
                                <form action="" method="post"
                                    id="addGuarantor">
                                    @csrf
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Code
                                                    :</label>
                                                <input type="text"
                                                    name="g_code"
                                                    id="g_code"
                                                    value="{{ $maxGuarantor+1 }}"
                                                    class="form-control"
                                                    placeholder="Customer Code"
                                                    required>
                                            </div>
                                            <div class="col-md-6">
                                                <div
                                                    class="form-group">
                                                    <label>Full name
                                                        :</label>
                                                    <input
                                                        type="text"
                                                        name="g_name"
                                                        id="g_name"
                                                        class="form-control"
                                                        placeholder="Full name"
                                                        required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <textarea
                                                    class="form-control"
                                                    name="g_address1"
                                                    id="g_address1"
                                                    rows="3"
                                                    placeholder="Address 1"
                                                    required></textarea>
                                            </div>


                                        </div>

                                        <div class="row mt-4">
                                                <div
                                                    class="col-md-6">
                                                    <label>Contact:</label>
                                                    <input
                                                        type="text"
                                                        name="g_contact1"
                                                        id="g_contact1"
                                                        class="form-control"
                                                        placeholder="Contact 1"
                                                        required>
                                                </div>
                                                <div
                                                    class="col-md-6">
                                                    <label>Email:</label>
                                                    <input
                                                        type="text"
                                                        name="g_email"
                                                        id="g_email"
                                                        class="form-control"
                                                        placeholder="Email"
                                                        required>
                                                </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div
                                                        class="col-md-11">
                                                        <label>Mark
                                                            as
                                                            Active
                                                            or
                                                            Blacklisted</label>
                                                        <div class=" form-group">
                                                            <select
                                                                class="select form-control"
                                                                name="g_status"
                                                                id="g_status"
                                                                aria-hidden="true"
                                                                required>
                                                                    <option value="">Please Select</option>
                                                                    <option value="1" selected>Active</option>
                                                                    <option value="0">Blacklist</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="text-center mt-4">
                                            <button type="button"
                                                class="btn btn-success add_guarantor bg-success-light text-success me-2">Save</button>
                                            <button type="button"
                                                class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal"
                                                aria-label="Close">Close</button>
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
            @include('layouts.footer')
        </div>


        {{--------------Add customer model----------------- --}}
        <div class="modal fade" id="addCustomerModel" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
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
                                        <div class="errMsgContainer"></div>
                                        <form action="" method="post" id="addCustomer">
                                            @csrf
                                            <div class="row">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Code <span
                                                                style="color:#FF0000; font-weight: bold; ">*</span>
                                                            :</label>
                                                        <input type="text" name="code" value="{{ $maxCustomer+1}}"
                                                            id="code" class="form-control" placeholder="Customer Code"
                                                            required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Title <span
                                                                style="color:#FF0000; font-weight: bold; ">*</span>
                                                            :</label>
                                                        <div class=" form-group">
                                                            <select class="select form-control" name="title" id="title"
                                                                aria-hidden="true" required>
                                                                <option value="">Please Select
                                                                </option>
                                                                <option value="Mr.">Mr.</option>
                                                                <option value="Mrs.">Mrs.
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Gender <span
                                                                style="color:#FF0000; font-weight: bold; ">*</span>
                                                            :</label>
                                                        <div class=" form-group">
                                                            <select class="select form-control" name="gender"
                                                                id="gender" aria-hidden="true" required>
                                                                <option value="">Please Select
                                                                </option>
                                                                <option value="Male">Male
                                                                </option>
                                                                <option value="Female">Female
                                                                </option>
                                                                <option value="Other">Other
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col">
                                                                <label>First Name <span
                                                                        style="color:#FF0000; font-weight: bold; ">*</span>
                                                                    :</label>
                                                                <input type="text" name="first_name" id="first_name"
                                                                    class="form-control" placeholder="First Name"
                                                                    required>
                                                            </div>
                                                            <div class="col">
                                                                <label>Middle Name :</label>
                                                                <input type="text" name="middle_name" id="middle_name"
                                                                    class="form-control" placeholder="Middle Name">
                                                            </div>
                                                            <div class="col">
                                                                <label>Last Name
                                                                    :</label>
                                                                <input type="text" name="last_name" id="last_name"
                                                                    class="form-control" placeholder="Last Name"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <textarea class="form-control" name="address1" id="address1"
                                                            rows="3" placeholder="Address-1:" required></textarea> <br>
                                                        <textarea class="form-control" name="city1" id="city1" rows="1"
                                                            placeholder="City-1 :"></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <textarea class="form-control" name="address2" id="address2"
                                                            rows="3" placeholder="Address-2:"></textarea>
                                                        <br>
                                                        <textarea class="form-control" name="city2" id="city2" rows="1"
                                                            placeholder="City-2 :"></textarea>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-md-6">
                                                        <label>Contact-1 <span
                                                                style="color:#FF0000; font-weight: bold; ">*</span>
                                                            :</label>
                                                        <input type="text" name="contact1" id="contact1"
                                                            class="form-control" placeholder="Contact 1" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Contact-2 :</label>
                                                        <input type="text" name="contact2" id="contact2"
                                                            class="form-control" placeholder="Contact 2">
                                                    </div>
                                                </div>


                                                <div class="row mt-4">
                                                    <div class="col-md-6">
                                                        <label>NIC :</label>
                                                        <input type="text" name="nic" id="nic" class="form-control"
                                                            placeholder="NIC" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Email :</label>
                                                        <input type="text" name="email" id="email" class="form-control"
                                                            placeholder="Email">
                                                    </div>
                                                </div>


                                                <div class="row mt-4">
                                                    <div class="col-md-6">
                                                        <label>Driving License :</label>
                                                        <input type="text" name="driving_license" id="driving_license"
                                                            class="form-control" placeholder="Driving Licence">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Passport :</label>
                                                        <input type="text" name="passport" id="passport"
                                                            class="form-control" placeholder="Passport">
                                                    </div>

                                                </div>


                                                <div class="row mt-4">
                                                    <div class="col-md-6">
                                                        <label>Other Identifications :</label>
                                                        <input type="text" name="other_identifications"
                                                            id="other_identifications" class="form-control"
                                                            placeholder="Other Identifications">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-11">
                                                                <label>Mark as Active or
                                                                    Blacklisted <span
                                                                        style="color:#FF0000; font-weight: bold; ">*</span>
                                                                    :</label>
                                                                <div class=" form-group">
                                                                    <select class="select form-control" name="status"
                                                                        id="status" aria-hidden="true" required>
                                                                        <option value="">Please Select</option>
                                                                        <option value="1" selected>
                                                                            <span style="color:#16ec28;">
                                                                                Active
                                                                            </span>
                                                                        </option>
                                                                        <option value="0">
                                                                            <span style="color:#df0c0c;">
                                                                                Blacklist
                                                                            </span>
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="text-center mt-4">
                                                    <button type="button"
                                                        class="btn btn-success add_customer bg-success-light text-success me-2">Save</button>
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal" aria-label="Close">Close</button>
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

    {!! Toastr::message() !!}

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

    {{-- add guarantor script --}}
    <script>
        $(document).ready(function () {
            //add new guarantor
            $(document).on('click', '.add_guarantor', function (e) {
                e.preventDefault();
                let code = $('#g_code').val();
                let title = $('#g_title').val();
                let gender = $('#g_gender').val();
                let name = $('#g_name').val();
                let address1 = $('#g_address1').val();
                let address2 = $('#g_address2').val();
                let contact1 = $('#g_contact1').val();
                let contact2 = $('#g_contact2').val();
                let email = $('#g_email').val();
                let nic = $('#g_nic').val();
                let driving_license = $('#g_driving_license').val();
                let passport = $('#g_passport').val();
                let other_identifications = $('#g_other_identifications').val();
                let status = $('#g_status').val();

                $.ajax({
                    url: "{{ route('add_guarantor_ajax') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        code: code,
                        title: title,
                        gender: gender,
                        name: name,
                        address1: address1,
                        address2: address2,
                        contact1: contact1,
                        contact2: contact2,
                        email: email,
                        nic: nic,
                        driving_license: driving_license,
                        passport: passport,
                        other_identifications: other_identifications,
                        status: status
                    },

                    success: function (res) {
                        if (res.status == 'success') {
                            $("#addGuarantorModel").modal('hide');
                            $('#addGuarantor')[0].reset();
                            $('.table').load(location.href + ' .table');
                            Command: toastr["success"]("Guarantor Added ...!", "Success")
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

    {{-- Item description show acording to item code --}}
    <script>
        $(document).ready(function () {
            // Listen for changes in the code name fields
            $('#item_code').on('keyup', function () {
                setItemDetails();

            });
        });

    </script>

    {{-- details show acording to schema type --}}
    <script>
        $(document).ready(function () {
            // Listen for changes in the Weight and QTY fields
            $('#schema_type').on('change', function () {
                let schema_type = $('#schema_type').val();
                $.ajax({
                    url: "{{ route('show_select_schema_details_ajax') }}",
                    method: 'GET',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        schema_type: schema_type
                    },
                    success: function (res) {
                        if (res.status == 'success') {

                            let records = res.data;
                            let Code, Schema_Type, In_Rate, Document_Charage, Down_Payment;

                            records.forEach(record => {
                                Code = record.Code;
                                Schema_Type = record.SchemaType;
                                instalment_rate = record.InRate;
                                doc_charge_rate = record.DocumentCharage;
                                down_payment_rate = record.DownPayment;

                                // Update the input values
                                $('#int_rate').val(instalment_rate + ' %');
                                $('#document_charge_rate').val(doc_charge_rate + ' %');
                                $('#down_payment_rate').val(down_payment_rate + ' %');

                                // Calculate all charges
                                let gross_amount = parseFloat($('#fixed_gross_amount').val()) || 0;
                                let doc_charge_amount = parseFloat((gross_amount / 100) * doc_charge_rate) || 0;
                                $('#document_charge').val(doc_charge_amount.toFixed(2));
                                let instalment_amount = parseFloat((gross_amount / 100) * instalment_rate) || 0;
                                $('#int_amount').val(instalment_amount.toFixed(2));
                                let down_payment_amount = parseFloat((gross_amount / 100) * down_payment_rate) || 0;
                                $('#down_payment').val(down_payment_amount.toFixed(2));

                                // Recalculate gross amount and net amount
                                let discount_amount = parseFloat($('#paid_discount').val()) || 0;
                                let net_amount = parseFloat($('#net_amount').val()) || 0;
                                let transport_amount = parseFloat($('#transport').val()) || 0;

                                // Check if the input is a valid number
                                if (isNaN(gross_amount) || isNaN(doc_charge_amount) || isNaN(instalment_amount)
                                    || isNaN(down_payment_amount) || isNaN(discount_amount) || isNaN(net_amount) || isNaN(transport_amount)) {
                                    console.error('Invalid input. Please enter valid numbers.');
                                    return;
                                }

                                let final_gross = gross_amount + doc_charge_amount + instalment_amount + down_payment_amount + transport_amount;
                                $('#total_amount').val(final_gross.toFixed(2));
                                let final_net = final_gross - discount_amount;
                                $('#net_amount').val(final_net.toFixed(2));
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

    {{-- calculate instalment when chang inst No --}}
    <script>
        $(document).ready(function () {
            // Listen for changes in the Weight and QTY fields
            $('#no_of_inst').on('keyup', function () {
                let number_of_instalment = parseFloat($('#no_of_inst').val());
                let fixed_gross_amount = parseFloat($('#fixed_gross_amount').val());
                let fixed_net_amount = parseFloat($('#fixed_net_amount').val());
                let document_charge = parseFloat($('#document_charge').val());
                let down_payment = parseFloat($('#down_payment').val());
                let int_amount = parseFloat($('#int_amount').val());
                let transport = parseFloat($('#transport').val() || 0);
                let discount_amount = parseFloat($('#paid_discount').val()) || 0;

                // Check if the input is a valid number
                if (isNaN(number_of_instalment) || isNaN(fixed_gross_amount) || isNaN(fixed_net_amount) || isNaN(document_charge) ||
                    isNaN(down_payment) || isNaN(int_amount || isNaN(transport))) {
                    console.error('Invalid input. Please enter valid numbers.');
                    return;
                }
                // Calculate total
                let total = fixed_net_amount + document_charge + down_payment + int_amount + transport;
                let instalment = total / number_of_instalment;
                $('#instalment').val(instalment.toFixed(2));
                let total_gross_set = fixed_gross_amount + document_charge + down_payment + int_amount + transport;

                $('#total_amount').val(total_gross_set.toFixed(2));

                let final_net = total_gross_set - discount_amount;
                $('#net_amount').val(final_net.toFixed(2));

                //calculate due date
                let today = new Date();
                let due_date = new Date(today.setMonth(today.getMonth() + number_of_instalment));
                // Format the due date as yyyy-mm-dd
                let formatted_due_date = due_date.toISOString().split('T')[0];
                $('#inst_due_date').val(formatted_due_date);
            });
        });
    </script>

     {{-- calculate due date when chang  amount --}}
     <script>
    //     $(document).ready(function () {
    //         // Listen for changes in the Weight and QTY fields
    //         $('#transport').on('keyup', function () {
    //             const calculated_gross = parseFloat($('#total_amount').val());
    //             let transport = parseFloat($('#transport').val() || 0);

    //             // Check if the input is a valid number
    //             if (isNaN(calculated_gross) || isNaN(transport)) {
    //                 console.error('Invalid input. Please enter valid numbers.');
    //                 return;
    //             }
    //             // Calculate total
    //             let total_gross = calculated_gross + transport;
    //             $('#total_amount').val(total_gross.toFixed(2));
    //         });
    //     });
    // </script>



    {{-- search and get invoice data using invoice _no  --}}
    <script>
        $(document).ready(function () {
            // search receipt data
            $('#invoice_no').on('input', function (e) {
                e.preventDefault();

                setTimeout(function () {

                    let search_receipt_no = $('#invoice_no').val();
                    var search_string = $('#customer_code').val();

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
                                    let Invoice_no, Invoice_date, Customer_NIC,
                                        Customer_Name,
                                        Customer_Phone,
                                        Customer_Address, Gross_Amount, Discount,
                                        Net_Amount;

                                    records.forEach(record => {
                                        Invoice_no = record.Invoice_no;
                                        Invoice_date = record.Invoice_date;
                                        Customer_NIC = record.Customer_NIC;
                                        Customer_Name = record
                                        .Customer_Name;
                                        Customer_Phone = record
                                            .Customer_Phone;
                                        Customer_Address = record
                                            .Customer_Address;
                                        Gross_Amount = record.Gross_Amount;
                                        Discount = record.Discount;
                                        Net_Amount = record.Net_Amount;

                                        // Update the input values
                                        $('#invoice_no').val(Invoice_no);
                                        $('#invoice_date').val(
                                        Invoice_date);
                                        $('#customer_code').val(
                                            Customer_NIC);
                                        $('#customer_name').val(
                                            Customer_Name);
                                        $('#customer_contact_1').val(
                                            Customer_Phone);
                                        $('#total_amount').val(
                                        Gross_Amount);
                                        $('#paid_discount').val(Discount);
                                        $('#net_amount').val(Net_Amount);

                                        $('#green-total-unit-price').val(
                                            Gross_Amount);
                                        $('#green-total-discount').val(
                                            Discount);
                                        $('#green-total-value').val(
                                            Net_Amount);
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

                }, 500);

            })

        });

    </script>

     {{-- print invoice script --}}
     <script>
        $(document).ready(function () {
            $(document).on('click', '.print_invoice', function (e) {
                e.preventDefault();
                let hp_invoice_no = $('#invoice_no').val();

                if (confirm('Are you sure to print the invoice ?')) {
                    $.ajax({
                        url: "{{ route('print_open_hp_invoice_ajax') }}",
                        method: 'get',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            hp_invoice_no: hp_invoice_no
                        },
                        success: function (response) {
                                if (response.status == 'success') {
                                    // Open the PDF in a new tab for printing
                                    window.open(response.pdfUrl, '_blank');
                                } else {
                                    // Handle the error or show a message
                                    alert('Error printing invoice !!');
                                }
                        }
                    });
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
            rows[i].addEventListener("click", function () {
                var item_code_add = this.cells[0].textContent;
                $('#item_code').val(item_code_add);
                setItemDetails();
                $("#searchItemModel").modal('hide');
                $('#item_name').val("");
                // $('#getItemCode').reset();
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
            // $.ajax({
            //     url: "{{ route('get_weight_ajax') }}",
            //     method: 'GET',

            //     success: function (res) {
            //         if (res.status == 'success') {
            //     // success: function (data) {
            //         // Process the data returned from the PHP script here
            //         let weight = res.data;
            //         $('#qty').val(weight);

            //         //set net price
            //         let unit_price_value = $('#unit_price').val();
            //         let unit_qty = $('#qty').val();
            //         let net_value_row = unit_qty * unit_price_value;
            //             if (unit_qty != null) {
            //                 $('#net_value').val(net_value_row);
            //             }
            //         }

            //     },
            //     error: function (err) {
            //         console.error('Error:', err);
            //     }
            // });

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

    {{--  get customer data inserting customer CODE --}}
    <script>
        $(document).ready(function () {
            // search customer data

            $(document).on('keyup', function (e) {
                e.preventDefault();
                var search_string = $('#customer_code').val();
                // console.log(search_string);
                if (search_string != null) {
                    if (e.keyCode == 13 || e.keyCode == 10) {
                        $.ajax({
                            url: "{{ route('get_customer_data_using_code_ajax') }}",
                            method: 'GET',
                            data: {
                                search_string: search_string
                            },
                            success: function (response) {
                                if (response.status == 'success') {
                                    $('.showCustomer').html("");
                                    let records = response.data;
                                    let Customer_Code, Customer_NIC, Customer_Name,
                                        Customer_Phone, Customer_Address;

                                    records.forEach(record => {
                                        Customer_Code = record.Code;
                                        Customer_NIC = record.NIC;
                                        Customer_Name = record.First_name;
                                        Customer_Phone = record.Contact_1;
                                        Customer_Address = record.Address_1;

                                        // Update the input values
                                        $('#customer_code').val(Customer_NIC);
                                    });

                                    $('.showCustomer').html(
                                        `
                                <div class="row mt-3">
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

                                }
                                // $('.showCustomer').html(res);
                                else if (response.status == 'not_found') {
                                    $('.showCustomer').html(
                                        `<div class="input-group">
                                        <p class="form-control text-danger text-center">
                                            Customer Not Found ..!!
                                        </p>
                                     </div>`
                                    );
                                }
                            }
                        });
                    }
                } else {
                    $('.showCustomer').html("");
                }
            })
        });

    </script>

    {{--  get guarantor 1 data inserting guarantor1 CODE --}}
    <script>
        $(document).ready(function () {
            // search customer data
            $(document).on('keyup', function (e) {
                e.preventDefault();
                var search_string = $('#guarantor_1_code').val();
                // console.log(search_string);
                if (search_string != null) {
                    if (e.keyCode == 13 || e.keyCode == 10) {
                        $.ajax({
                            url: "{{ route('get_guarantor_1_data_using_code_ajax') }}",
                            method: 'GET',
                            data: {
                                search_string: search_string
                            },
                            success: function (response) {
                                if (response.status == 'success') {
                                    $('#guarantor_1_data').html("");
                                    let records = response.data;
                                    let guarantor_code, guarantor_name;

                                    records.forEach(record => {
                                        guarantor_code = record.Code;
                                        guarantor_name = record.Name;

                                        // Update the input values
                                        $('#guarantor_1_code').val(guarantor_code);
                                    });

                                    $('#guarantor_1_data').html(
                                        `
                                        <input class="form-control " type="hidden"
                                                value= "${guarantor_code}"
                                                id="guarantor_1_code"
                                                name="guarantor_1_code">
                                        <input class="form-control " type="text"
                                                value= "${guarantor_name}"
                                                id="guarantor_1_name"
                                                name="guarantor_1_name">
                                        `
                                    );
                                }
                                else if (response.status == 'not_found') {
                                    $('#guarantor_1_data').html(
                                        `   <div class="input-group">
                                                <p class="form-control text-danger text-center">
                                                    Guarantor Not Found ..!!
                                                </p>
                                            </div>
                                        `
                                    );
                                }
                            }
                        });
                    }
                } else {
                    $('#guarantor_1_data').html("");
                }
            })
        });

    </script>

    {{--  get guarantor 2 data inserting guarantor1 CODE --}}
    <script>
        $(document).ready(function () {
            // search customer data
            $(document).on('keyup', function (e) {
                e.preventDefault();
                var search_string = $('#guarantor_2_code').val();
                // console.log(search_string);
                if (search_string != null) {
                    if (e.keyCode == 13 || e.keyCode == 10) {
                        $.ajax({
                            url: "{{ route('get_guarantor_2_data_using_code_ajax') }}",
                            method: 'GET',
                            data: {
                                search_string: search_string
                            },
                            success: function (response) {
                                if (response.status == 'success') {
                                    $('#guarantor_2_data').html("");
                                    let records = response.data;
                                    let guarantor_code, guarantor_name;

                                    records.forEach(record => {
                                        guarantor_code = record.Code;
                                        guarantor_name = record.Name;

                                        // Update the input values
                                        $('#guarantor_2_code').val(guarantor_code);
                                    });

                                    $('#guarantor_2_data').html(
                                        `
                                        <input class="form-control " type="hidden"
                                                value= "${guarantor_code}"
                                                id="guarantor_2_code"
                                                name="guarantor_2_code">
                                        <input class="form-control " type="text"
                                                value= "${guarantor_name}"
                                                id="guarantor_2_name"
                                                name="guarantor_2_name">
                                        `
                                    );
                                }
                                else if (response.status == 'not_found') {
                                    $('#guarantor_2_data').html(
                                        `   <div class="input-group">
                                                <p class="form-control text-danger text-center">
                                                    Guarantor Not Found ..!!
                                                </p>
                                            </div>
                                        `
                                    );
                                }
                            }
                        });
                    }
                } else {
                    $('#guarantor_2_data').html("");
                }
            })
        });

    </script>

    {{-- add new customer script --}}
    <script>
        $(document).ready(function () {
            //add new customer
            $(document).on('click', '.add_customer', function (e) {
                e.preventDefault();
                let code = $('#code').val();
                let title = $('#title').val();
                let gender = $('#gender').val();
                let name = $('#name').val();
                let first_name = $('#first_name').val();
                let middle_name = $('#middle_name').val();
                let last_name = $('#last_name').val();
                let address1 = $('#address1').val();
                let city1 = $('#city1').val();
                let address2 = $('#address2').val();
                let city2 = $('#city2').val();
                let contact1 = $('#contact1').val();
                let contact2 = $('#contact2').val();
                let email = $('#email').val();
                let nic = $('#nic').val();
                let driving_license = $('#driving_license').val();
                let passport = $('#passport').val();
                let other_identifications = $('#other_identifications').val();
                let status = $('#status').val();

                $.ajax({
                    url: "{{ route('add_customer_ajax') }}",
                    method: 'post',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        code: code,
                        title: title,
                        gender: gender,
                        name: name,
                        first_name: first_name,
                        middle_name: middle_name,
                        last_name: last_name,
                        address1: address1,
                        city1: city1,
                        address2: address2,
                        city2: city2,
                        contact1: contact1,
                        contact2: contact2,
                        email: email,
                        nic: nic,
                        driving_license: driving_license,
                        passport: passport,
                        other_identifications: other_identifications,
                        status: status
                    },

                    success: function (res) {
                        if (res.status == 'success') {
                            $("#addCustomerModel").modal('hide');
                            $('#addCustomer')[0].reset();
                            // $('.table').load(location.href+' .table');
                            Command: toastr["success"]("Customer Added ...!", "Success")
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
                            let data = res.telNo;
                            let cus_details = data[0];
                            let cus_tel = cus_details.Contact_1;
                            let cus_name = cus_details.First_name;

                            $('#customer_code').val(cus_tel);
                            // $('#customer_name').val(cus_name);
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

    {{-- clear form --}}
    <script>
        $(document).ready(function () {
            //add new customer
            $(document).on('keyup', '#customer_code', function (e) {
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

                        <td style="width:12%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="Unit Price"
                            id="dy_unit_price" name="inputs[` + i + `][unit_price]" value="` + unit_price + `" readonly>
                        </td>

                        <td style="width:10%;">
                            <input class="form-control" type="text" style="text-align: center;" placeholder="QTY"
                            id="dy_qty" name="inputs[` + i + `][qty]" value="` + qty + `" readonly>
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
            totalGross = parseFloat(totalGross) + (parseFloat(unit_price) * qty);
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
            document.getElementById("total_amount").value = parseFloat(totalGross).toFixed(2);
            document.getElementById("fixed_gross_amount").value = parseFloat(totalGross).toFixed(2);
            document.getElementById("paid_discount").value = parseFloat(totalDiscount).toFixed(2);
            document.getElementById("net_amount").value = parseFloat(totalValue).toFixed(2);
            document.getElementById("fixed_net_amount").value = parseFloat(totalValue).toFixed(2);
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
            var re_grossValue = re_unit_price.val() * re_qty.val();
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

    {{-- change net value calculation when change the discount field --}}
    <script>
        $(document).on('keyup', '#discount', function () {
            let unit_price_value = $('#unit_price').val();
            let unit_qty = $('#qty').val();
            // let qty_decimal = Math.trunc(unit_qty);
            let net_value_row = unit_qty * unit_price_value;

            let unit_discount = $('#discount').val();
            let discounted_value = parseFloat(net_value_row * (unit_discount / 100)).toFixed(2);
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

                            // $('#customer_code').val(cus_tel);
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
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

</body>

</html>
@endsection
