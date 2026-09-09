@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Opening Stock</title>
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
                                <h4 class="card-title m-3">Create Stock Adjestment</h4>
                            </div>
                            <hr size="6" style="color: blue">
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


                        <form action="{{route('Store_StockAdjuestment')}}" method="post">
                                    @csrf
                                    <div class="row ">
                                        <div class="row mb-1 form-group justify-content-between">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                                <label for="customer_nic"><span style="font-weight:bold;">Store Code :</span>
                                                                    <select class="select form-control "
                                                                    {{-- name="inputs[0][articles]" --}}
                                                                    id="Store_code" name="Store_code" aria-hidden="true" class="form-control" >
                                                                    @foreach($storeDta as $Data)
                                                                    <option selected="selected" value="{{ $Data->Store_code}}">
                                                                        {{ $Data->Store_code}}</option>
                                                                    @endforeach                            
                                                                    </select>
                                                        </div>
                    
                                                        <div class="col-md-6">
                                                            <label for=""><span style="font-weight:bold;">Store Name :</span>
                                                            <select class="select form-control "
                                                            {{-- name="inputs[0][articles]" --}}
                                                            id="StoreDescription" name="StoreDescription" aria-hidden="true" class="form-control" >
                                                            @foreach($storeDta as $Data)
                                                            <option selected="selected" value="{{ $Data->Store_name}}">
                                                                {{ $Data->Store_name}}</option>
                                                            @endforeach                            
                                                            </select>
                                                        </div>   
                                                    </div>
                                                </div>
                                        <div class="col-md-1">
                                        </div>
                                        <div class="col">
                                            <div class="input-group">
                                                <div class="input-group-text" id="btnGroupAddon2">Invoice No :</div>
                                                <input type="text" id="invoice_no" name="invoice_no"
                                                    value="{{$maxInvoiceNo+1}}" class="form-control"
                                                    placeholder="Invoice Number:" aria-label="Invoice Number:"
                                                    aria-describedby="btnGroupAddon2">
                                                    
                                                  
                                            </div>
                             
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-8">
                                        </div>
                                        <div class="col">

                                            <div class="input-group">
                                                <div class="input-group-text" id="btnGroupAddon3">Date :
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div>
                                                <input type="date" id="invoice_date" name="invoice_date"
                                                    class="form-control" aria-label="Date:"
                                                    aria-describedby="btnGroupAddon3">
                                            </div>
                                        </div>
                                    </div>
                         </div>

                            {{-- dynamicAdded table --}}
                            <table class="table table-bordered">
                                <thead style="background-color: rgb(83, 129, 197)">
                                    <tr>
                                        {{-- <th style="width:15%; text-align: center;">Category</th> --}}
                                        <th style="width:10%; text-align: center;">Item Code</th>
                                        <th style="width:25%; text-align: center;">Description</th>
                                        <th style="width:10%; text-align: center;">Price</th>
                                        <th style="width:10%; text-align: center;">Total Qty</th>
                                        <th style="width:15%; text-align: center;">Manual Stock</th>
                                        <th style="width:10%; text-align: center;">Stock Variance</th>
                                        <th style="width:20%; text-align: center;">Net Value</th>
                                    </tr>
                                </thead>
                              <tbody>
                                @foreach ($itemDetails as $key => $ItemData)
                                <tr>
                                    <td>
                                        <input type="text" name="item_code[]" class="form-control" placeholder="item Code" value="{{ $ItemData->Item_code }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="item_description[]" class="form-control" placeholder="item Description" value="{{ $ItemData->Item_description }}" readonly>
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" placeholder="Sale Price" name="saleprice[]" value="{{ $ItemData->saleprice }}" readonly>
                                    </td>
                                    <td>
                                        <input class="form-control qty" type="text" placeholder="QTY" name="qty[]" value="{{ $ItemData->QTY }}" readonly>
                                    </td>
                                    <td>
                                        <input class="form-control qtyOut" type="number" placeholder="QTY Out" name="qtyOut[]" value="">
                                    </td>
                                    <td>
                                        <input class="form-control stockAdjustment" type="text" placeholder="Stock Adjustment" name="Stock_adjuestment[]" value="0" readonly>
                                    </td>
                                    <td>
                                        <input class="form-control netValue" type="text" placeholder="Net Value" name="net_value[]" value="0" readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                            </table>
                            <br>

                            <br>
                            <div class="row">
                                <div class="col-md-2">

                                </div>
                                <div class="col-md-7">
                                    <br>
                                    <button type="submit" name="save"
                                        class="btn btn-outline-info btn-lg shadow">SAVE</button>
                                    <button type="button" name="print"
                                        class="btn btn-outline-primary printReceipt btn-lg shadow">PRINT</button>
                                    <button type="button" name="pawn_delete" id="pawn_delete"
                                        class="btn btn-outline-danger btn-lg shadow pawn_delete">DELETE</button>
                                    <button type="button" name="pawn_cancel" id="pawn_cancel"
                                        class="btn btn-outline-warning btn-lg shadow pawn_cancel">CANCEL</button>
                                    <button type="reset" name="reset"
                                        class="btn btn-outline-secondary btn-lg shadow">RESET</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footer')
    </div>

  




    </div>




    
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


<script>
$(document).ready(function() {
    $('.qtyOut').on('input', function () {
        var $row = $(this).closest('tr');

        var qty = parseFloat($row.find('.qty').val()) || 0;
        var qtyOut = parseFloat($(this).val()) || 0;

        let adjustment = 0;

        // ✅ If both are negative, add their absolute values
        if (qty < 0 && qtyOut < 0) {
            adjustment = Math.abs(qty) - qtyOut;
        } else {
            // ✅ All other cases, regular subtraction
            adjustment = qty - qtyOut;
        }

        $row.find('.stockAdjustment').val(adjustment);

        // Net value calculation
        var salePrice = parseFloat($row.find('input[name="saleprice[]"]').val()) || 0;
        var netValue = adjustment * salePrice;
        $row.find('.netValue').val(netValue.toFixed(2));
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