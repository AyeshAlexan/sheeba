@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Early Settlement</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
</head>


<style>
    #data_table tbody td {
    padding: 7px; /* Adjust the padding as needed */
}
</style>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card shadow">
                            <div class="col-md-9">
                                <h4 class="card-title m-3">Early Settlement Payment</h4>
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


                                <form action="{{route('create_paying_installment')}}" method="post" id="installment">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="row">
                                                <div class="input-group">
                                                    <div class="input-group-text" id="btnGroupAddon1">No :
                                                    </div>
                                                    <input type="text" id="invoice_no" name="invoice_no"
                                                        class="form-control" placeholder="Enter Invoice No " required
                                                        aria-label="Invoice Number:" aria-describedby="btnGroupAddon1">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-success btn-lg form-control "
                                                            data-bs-toggle="modal" data-bs-target="#addCustomerModel">
                                                            <i class="fas fa-plus" style="color: white"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <br><br>
                                            </div>


                                        </div>

                                        <div class="col-md-3 payment-history">
                                        </div>

                                        <div class="col">
                                            <div class="row">
                                                <div class="input-group">
                                                    <div class="input-group-text" id="btnGroupAddon2">
                                                        ES No&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                                    </div>
                                                    <input type="text" id="instalment_invoice_no"
                                                        name="instalment_invoice_no"
                                                        {{-- value="{{$maxInvoiceNo+1}}" --}}
                                                        class="form-control"
                                                        placeholder="Early Settlement No" aria-label="Invoice Number"
                                                        aria-describedby="btnGroupAddon2" required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-group">
                                                    <div class="input-group-text" id="btnGroupAddon2">
                                                        ES Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:
                                                    </div>
                                                    <input type="date" id="invoice_date" name="invoice_date" value=""
                                                        class="form-control" placeholder="Invoice Date"
                                                        aria-label="Invoice Number" aria-describedby="btnGroupAddon2"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="input-group">
                                                    <div class="input-group-text" id="btnGroupAddon2">
                                                        Agreement No&nbsp;:
                                                    </div>
                                                    <input type="text" id="agreement_no" name="agreement_no" value=""
                                                        class="form-control" placeholder="Agreement No"
                                                        aria-label="Agreement Number" aria-describedby="btnGroupAddon2">
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="row mt-4 mb-4">
                                        <div class="col">

                                            <div class="table-responsive" id="data_tables">
                                                <div class="table-data">
                                                    <table class="table table-bordered table-center table-hover "
                                                        id="data_table">
                                                        <thead>
                                                            <tr class="table-secondary">
                                                                <th>Customer Name</th>
                                                                <th>Instalment Date</th>
                                                                <th>Amount</th>
                                                                <th>Amount Pay</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>

                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>

                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>

                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="row mt-4">
                                        {{------------- column 1 section ------------}}
                                        <div class="col-md-4">
                                            <div class="input-group mt-1">
                                                <div class="input-group-text"
                                                    style="font-weight:bold; font-size: 14px;"
                                                    id="btnGroupAddon9">
                                                    Final Gross &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </div>
                                                <input type="text" style="font-weight:bold;"
                                                    class="form-control" placeholder="Final Gross Amount"
                                                    id="final_gross_amount" name="final_gross_amount"
                                                    aria-describedby="btnGroupAddon9" required readonly />
                                            </div>

                                            <div class="input-group mt-1">
                                                <div class="input-group-text"
                                                    style="font-weight:bold; font-size: 14px;"
                                                    id="btnGroupAddon9">
                                                    Final Amount &nbsp;&nbsp;: </div>
                                                <input type="text" style="font-weight:bold;"
                                                    class="form-control" placeholder="Final Amount"
                                                    id="final_amount" name="final_amount"
                                                    aria-label="Final Amount"
                                                    aria-describedby="btnGroupAddon9" required readonly />
                                            </div>
                                        </div>
                                        {{------------- column 2 section ------------}}
                                        <div class="col-md-4">
                                            <div class="input-group advance_payment mt-1">
                                                <div class="input-group">
                                                    <div class="input-group-text" style="font-weight:bold; font-size: 14px;" id="btnGroupAddon4">
                                                        Advance Pay.. :
                                                    </div>
                                                    <input type="text" style="font-weight:bold;"
                                                        class="form-control" placeholder="Advance Payment"
                                                        id="advance_ammount" name="advance_ammount" value="0.00"
                                                        aria-label="Cash Pay" aria-describedby="btnGroupAddon4" readonly>
                                                    <div class="input-group-append">
                                                        <button type="button" id="get_advance_payment" class="btn btn-secondary btn-lg form-control">
                                                            <i class="fas fa-level-down" style="color: white"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{------------- column 3 section ------------}}
                                        <div class="col-md-4">


                                            <div class="input-group">
                                                <div class="input-group-text"
                                                        style="font-weight:bold; font-size: 14px;"
                                                        id="btnGroupAddon5">
                                                        Settlement Amount&nbsp;:
                                                </div>
                                                <input type="text" id="paying_amount" name="paying_amount" value=""
                                                        class="form-control" placeholder="Settlement Amount"
                                                        aria-label="Invoice Number" aria-describedby="btnGroupAddon2"
                                                        required>
                                            </div>





                                            <div class="input-group mt-1">
                                                <div class="input-group-text"
                                                    style="font-weight:bold; font-size: 14px;"
                                                    id="btnGroupAddon5">
                                                    Gross Amount &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</div>
                                                <input type="text" style="font-weight:bold;"
                                                    class="form-control" placeholder="Gross Amount"
                                                    id="total_amount" name="gross_amount"
                                                    aria-label="Gross Amount"
                                                    aria-describedby="btnGroupAddon5" />
                                            </div>
                                            <input type="hidden" name="fixed_gross_amount"
                                                id="fixed_gross_amount">



                                            <div class="input-group mt-1">
                                                <div class="input-group-text"
                                                    style="font-weight:bold; font-size: 14px;"
                                                    id="btnGroupAddon7">
                                                    Discount
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    :
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
                                                    Amount
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    :
                                                </div>
                                                <input type="text" style="font-weight:bold;"
                                                    class="form-control" placeholder="Net Amount"
                                                    id="net_amount" name="net_amount"
                                                    aria-label="Net Amount"
                                                    aria-describedby="btnGroupAddon9" required />
                                            </div>
                                            <input type="hidden" name="fixed_net_amount"
                                                id="fixed_net_amount">


                                        </div>
                                        {{-------------END OF column 3 section ------------}}

                                    </div>

                                        {{-- payment options --}}
                                        <div class="row ">
                                            <div class="row mt-1 justify-content-between">
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
                                                <button type="submit" name="save" id="save"
                                                    class="btn btn-outline-info btn-lg shadow">Add</button>
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

    {!! Toastr::message() !!}


    {{-- CSRF token script --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    {{-- form default date set for today --}}
    <script>
        var dateObj = new Date();
        document.getElementById('invoice_date').value = dateObj.toISOString().slice(0, 10);
    </script>

    {{-- disable form submit when press enter key --}}
    <script>
        $('#installment').on('keyup keypress', function (e) {
            var keyCode = e.keyCode || e.which;
            if (keyCode === 13 || e.keyCode === 10) {
                e.preventDefault();
                return false;
            }
        });
    </script>

    {{--  get invoice data inserting no --}}
    <script>
        $(document).ready(function () {

            // Search customer data when pressing Enter in the invoice_no field
            $('#invoice_no').on('keyup', function (e) {
                var search_string = $(this).val();

                if (search_string != null && (e.keyCode == 13 || e.keyCode == 10)) {
                    $.ajax({
                        url: "{{ route('get_installemnt_data_using_no_ajax') }}",
                        method: 'GET',
                        data: {
                            search_string: search_string
                        },
                        success: function (response) {
                            if (response.status == 'success') {
                                $('#data_table tbody').html("");
                                let records = response.data;
                                let hp_details = response.hp_data;

                                let invoice_date, document_charge, down_payment,
                                net_amount, instalment_rate, instalment_amount, no_of_instalment;

                                hp_details.forEach(record => {
                                    invoice_date = record.invoice_date;
                                    document_charge = record.document_charge;
                                    down_payment = record.down_payment;
                                    net_amount = record.net_amount;
                                    instalment_rate = record.instalment_rate;
                                    instalment_amount = record.instalment_amount;
                                    no_of_instalment = record.no_of_instalment;
                                });


                                records.forEach(record => {
                                    let html = `
                                                <tr height="5 px">
                                                    <td>${record.customer_name}</td>
                                                    <td>${record.instalment_date}</td>
                                                    <td>${record.instalment_amount}</td>
                                                    <td>${record.amount_pay}</td>
                                                </tr>`;
                                    let agreement_no = record.agreement_no;
                                    $('#data_table tbody').append(html);
                                    $('#agreement_no').val('');
                                    $('#agreement_no').val(agreement_no);
                                });

                            } else if (response.status == 'not_found') {
                                $('#data_table tbody').html("");
                                let html2 = `
                                <tr>
                                    <td colspan="4" class="text-center text-danger">Invoice Not Found.!!</td>
                                </tr>`;
                                $('#data_table tbody').append(html2);
                            }
                        }
                    });
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
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

</body>

</html>
@endsection
