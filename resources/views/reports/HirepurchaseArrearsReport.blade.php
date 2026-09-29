@if($invoice->count() > 0)
{{-- <h2>Purchases Report</h2> --}}
@else
@endif

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <script>
        // Select wrong element
        // Error as #demo is the `div` element
        $('#t_hire_purchase_sums').DataTable()

        // Selector too broad.
        // Error as `.display` is applied to both the div and the table
        $('.display').DataTable();
    </script>

    <title>Hirepurchase Arrears Report </title>
    <style>
        .table {
            display: block;
            overflow-y: hidden;
            overflow-x: auto;
            scroll-behavior: smooth;
        }

        .table thead {
            display: table-header-group;
            vertical-align: middle;
            border-color: inherit;
            color: white;
            background: darkcyan;
        }

        tr {
            display: table-row;
            vertical-align: inherit;
            border-color: inherit;
        }

        table th {
            padding: 16px;
            text-align: inherit;
            border-bottom: 1px solid black;
            color: blue !important;
        }

        tbody {
            display: table-row-group;
            vertical-align: middle;
            border-color: inherit;
        }

        table:not(.tr-caption-container) {
            min-width: 100%;
            border-radius: 3px;
        }

        /* Make sure date inputs are visible */
        input[type="date"] {
            opacity: 1;
            /* Add any other necessary styles */
        }


        h2 {
            width: 100%;
            margin: 0;
            /* Remove default margin */
            padding: 0;
            /* Remove default padding */
            text-align: center;
            /* Center the text if needed */
            background-color: rgb(113, 105, 255);
            /* Background color */
            color: white;
            /* Text color */
        }

        #t_hire_purchase_sums {
            overflow-x: auto;
            /* Add horizontal scroll if needed */
            /* Add any other styling properties as needed */
        }

        /* Your existing styles */

        @media only screen and (max-width: 600px) {

            /* Styles for small screens */
            table {
                width: 100%;
            }

            th,
            td {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }

            th {
                text-align: left;
            }
        }

        #t_hire_purchase_sums th,
        #t_hire_purchase_sums td {
            width: auto;
            /* Adjust column widths as needed */
        }

    </style>

</head>

<body>

    {{-- <div class="card shadow p-3 mb-3 bg-body-tertiary rounded" > --}}
    <div class="d-flex justify-content-center profile-container">
        <div class='col-md-6 text-center sort-profile' id='sort-profile'>
            <div class='row'>
                <div class='col-md-6 text-center'><br />
                    <div styel="background-color: yellow; width:100%;">

                        <h2 style="text-align:center;  background-color:rgb(113, 105, 255);">
                            <b>Hirepurchase Arrears Report </b></h2>
                        <hr />
                    </div>

                    <div style="display: flex; text-align: center;">
                        <div style="flex: 60%; align-content: center;">
                            <form action="" method="GET">
                                @csrf
                                <label for="date">From Date :</label>
                                <input type="date" name="from_date" id="from_date" value="{{ $fromDate }}">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label for="date">To Date :</label>
                                <input type="date" name="to_date" id="to_date" value="{{ $toDate }}">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label for="scheme">Scheme :</label>
                                <select name="scheme" id="scheme">
                                    <option value="">All</option>
                                    @foreach($schemaData as $s)
                                        <option value="{{ $s->SchemaType }}" @selected($scheme == $s->SchemaType)>{{ $s->SchemaType }}</option>
                                    @endforeach
                                </select>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label for="customer">Customer Code :</label>
                                <input type="text" name="customer" id="customer" value="{{ $customer }}" placeholder="Customer code">
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


                    <table class="display responsive" id="myTable"
                        style="white-space: nowrap; border:1px solid rgb(7, 7, 7); margin-top:15px;width:100%;">

                            @if($fromDate && $toDate)
                                <caption style="font-size: 18px; font-weight: bold;">
                                    Hirepurchase Arrears Report&nbsp;&nbsp;&nbsp;&nbsp;From: {{$fromDate}}&nbsp;&nbsp;To: {{$toDate}}
                                <caption>
                            @endif
                        <thead class="styled-table">

                            <th>Cus.. NIC</th>
                            <th>Cus.. Name</th>
                            <th>Sales Date</th>
                            <th>Arrears Date</th>
                            <th>Invoice No</th>
                            <th>Agreement No</th>
                            <th>Instal.. Amount</th>
                            <th>Paid Amount</th>
                            <th>Arrears Amount</th>
                            <th>Penalty Charges</th>
                            <th>Total Arrears </th>

                        </thead>
                        <tbody>

                            @php
                                $totalInstalmentAmount = 0;
                                $totalAmountPay = 0;
                                $totalRemainingAmount = 0;
                                $totalPanaltyCharage = 0;
                                $totalPanaltyCharage = 0;
                                $totalArrearsAmount = 0;
                            @endphp

                            @foreach ( $invoice as $key=>$invoice)

                                @php
                                    // Check if schema_type exists in the schema table
                                    $schemaTypeData = $schemaData->where('SchemaType', $invoice->schema_type)->first();
                                    if ($schemaTypeData){
                                        $penalty_rate = $schemaTypeData->PanaltyCharage;
                                    }else{

                                    }
                                        // 'Schema Data Not Found'
                                    // endif
                                @endphp

                                <tr>
                                    <td>{{$invoice->customer_code}}</td>
                                    <td>{{$invoice->customer_name}}</td>
                                    <td>{{$invoice->invoice_date}}</td>
                                    <td>{{$invoice->instalment_date}}</td>
                                    <td>{{$invoice->invoice_no}}</td>
                                    <td>{{$invoice->agreement_no}}</td>
                                    <td>{{$invoice->instalment_amount}}</td>
                                    <td>{{$invoice->amount_pay}}</td>
                                    <td>{{number_format($invoice->instalment_amount - $invoice->amount_pay, 2)}}</td>
                                    <td>{{number_format(($invoice->instalment_amount/100) * $penalty_rate, 2)}}</td>
                                    <td>{{number_format(($invoice->instalment_amount - $invoice->amount_pay)+(($invoice->instalment_amount/100)*$penalty_rate), 2)}}</td>
                                </tr>

                                @php
                                    $totalInstalmentAmount += $invoice->instalment_amount;
                                    $totalAmountPay += $invoice->amount_pay;
                                    $totalRemainingAmount += $invoice->instalment_amount - $invoice->amount_pay;
                                    $totalPanaltyCharage += ($invoice->instalment_amount/100)*$penalty_rate;
                                    $totalArrearsAmount += ($invoice->instalment_amount - $invoice->amount_pay)+(($invoice->instalment_amount/100)*$penalty_rate);
                                @endphp

                            @endforeach

                        </tbody>

                        <tfoot>
                           <tr>
                                <td colspan="6" style="text-align: center"><strong>Total:</strong></td>
                                <td><strong>{{number_format($totalInstalmentAmount, 2)}}</strong></td>
                                <td><strong>{{number_format($totalAmountPay, 2)}}</strong></td>
                                <td><strong>{{number_format($totalRemainingAmount, 2)}}</strong></td>
                                <td><strong>{{number_format($totalPanaltyCharage, 2)}}</strong></td>
                                <td><strong>{{number_format($totalArrearsAmount, 2)}}</strong></td>
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
        $('#myTable').DataTable( //database table name
            {
                dom: 'Bfrtip',
                "paging": false,
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print',
                ],
            }
        );

    });

</script>


<script>
    function printTablefun() {
        var divToPrint = document.getElementById("myTable");
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
</script>

{{-- default To Date to today only if nothing was submitted --}}
<script>
    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        var dateObj = new Date();
        toDateInput.value = dateObj.toISOString().slice(0, 10);
    }
</script>

</html>
