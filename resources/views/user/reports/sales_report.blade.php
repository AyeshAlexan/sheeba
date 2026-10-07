@if($invoice->count() > 0)

@else
{{-- <p>No results found.</p> --}}

@endif



<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <div><button onClick="window.print()">Print --}}
    </button></div>

    <script>
        $('#t_invoice_sums').DataTable()
        $('.display').DataTable();

    </script>
    <title>Invoice</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid rgb(8, 8, 8);
            padding: 5px;
        }

    </style>

</head>


<body>

    {{-- <div class="card shadow p-3 mb-3 bg-body-tertiary rounded" > --}}
    <div class="d-flex justify-content-center profile-container">
        <div class='col-md-6 text-center sort-profile' id='sort-profile'>
            <div class='row'>
                <div class='col-md-6 text-center'><br />
                    <div styel="background-color: yellow;">

                        <h2 style="text-align:center; background-color:rgb(113, 105, 255);"><b>Sales Report</b></h2>
                        <hr />
                    </div>
                    <br><br>

                    <div style="display: flex; text-align: center;">


                        <div style="flex: 60%; align-content: center;">
                            <form action="" method="get">
                                <label for="date">From Date :</label>
                                <input type="date" name="from_date" id="from_date">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label for="date">To Date :</label>
                                <input type="date" name="to_date" id="to_date">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="submit" id="submit_1">
                                    Submit &nbsp;<i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                                <button onclick="printTablefun()">
                                    <strong> Print &nbsp;</strong><i class="fa-solid fa-print"></i>
                                </button>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <Button class="d-inline p-2 text-bg-primary">
                                    <a href="{{route("home")}}">
                                        Back
                                    </a>
                                    <i class="fa-solid fa-house"></i>
                                </Button>
                            </form>
                        </div>

                    </div>


                    <table class="display" id="t_invoice_sums"
                        style="background-color: transparent; border:1px solid rgb(7, 7, 7); margin-top:15px;">
                            @if($fromDate && $toDate)
                                <caption style="font-size: 18px; font-weight: bold;">
                                    Sales Reportsdfawergftwert&nbsp;&nbsp;&nbsp;&nbsp;From: {{$fromDate}}&nbsp;&nbsp;To: {{$toDate}}
                                <caption>
                            @endif
                        <thead class="styled-table">
                            <th>Invoice No</th>
                            <th>Invoice date</th>
                            <th>Customer NIC</th>
                            <th>Customer Name</th>
                            <th>Route</th>
                            <th>Salesman</th>
                            <th>Gross Amount</th>
                            <th>Discount</th>
                            <th>Net Amount</th>
                            <th>Cash Pay</th>
                            <th>Credite</th>
                            <th>Cheque</th>
                            {{-- <th>Operator</th>
                            <th>Branch</th> --}}
                        </thead>
                        <tbody>
                            @foreach ( $invoice as $key=>$invoice)
                            <tr>
                                <td>{{$invoice->Invoice_no}}</td>
                                <td>{{$invoice->Invoice_date}}</td>
                                <td>{{$invoice->Customer_NIC}}</td>
                                <td>{{$invoice->Customer_Name}}</td>
                                <td>{{$invoice->Route}}</td>
                                <td>{{$invoice->Salesmen}}</td>
                                <td>{{$invoice->Gross_Amount}}</td>
                                <td>{{$invoice->Discount}}</td>
                                <td>{{$invoice->Net_Amount}}</td>
                                 <td>{{$invoice->vat_amount}}</td>
                                <td>{{$invoice->Cash_Pay}}</td>
                                <td>{{$invoice->Credite}}</td>
                                <td>{{$invoice->Cheque}}</td>
                                {{-- <td>{{$invoice->OC}}</td>
                                <td>{{$invoice->BC}}</td> --}}
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="6" rowspan="2"><strong>Total</strong></td>
                                <td>Gross Amount:</td>
                                <td>Discount:</td>
                                <td>Net Amount:</td>
                                <td>Cash Pay:</td>
                                <td>Credite:</td>
                                <td>Cheque:</td>
                                {{-- <td></td>
                                <td></td> --}}
                            </tr>
                            <tr>

                                <td><strong>{{$totalGrossAmount}}</strong></td>
                                <td><strong>{{$totalDiscount}}</strong></td>
                                <td><strong>{{$totalNetAmount}}</strong></td>
                                <td><strong>{{$totalCashPay}}</strong></td>
                                <td><strong>{{$totalCredite}}</strong></td>
                                <td><strong>{{$totalCheque}}</strong></td>
                                {{-- <td></td>
                                <td></td> --}}
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
    </div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
</script>
<script type="text/javascript" charset="utf8"
    src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js">
</script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js">
</script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js">
</script>
<script>
    jQuery(document).ready(function ($) {
        $('#t_invoice_sums').DataTable( //database table name
            {
                dom: 'Bfrtip',
                "paging": false,
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    // 'print',
                ],
            }
        );

    });

</script>




<script>
    function printTablefun() {

        var divToPrint = document.getElementById("t_invoice_sums");
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }

</script>


{{-- form default date set for today --}}
<script>
    var dateObj = new Date();
    document.getElementById('to_date').value = dateObj.toISOString().slice(0, 10);

</script>


</html>