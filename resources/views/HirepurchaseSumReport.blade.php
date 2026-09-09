@if($invoice->count() > 0)
{{-- <h2>Purchases Report</h2> --}}
@else
<p>No results found.</p>

@endif

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <script>
        $('#t_hire_purchase_sums').DataTable()
        $('.display').DataTable();

    </script>

    <title>Hirepurchase Sum Report</title>

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
            width: 122%;
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

    </style>
</head>

<body>
    {{-- <div class="card shadow p-3 mb-3 bg-body-tertiary rounded" > --}}
    <div class="d-flex justify-content-center profile-container">
        <div class='col-md-6 text-center sort-profile' id='sort-profile'>
            <div class='row'>
                <div class='col-md-6 text-center'><br />
                    <div styel="background-color: yellow; width:100%;">

                        <h2 style="text-align:center;  background-color:rgb(113, 105, 255);"><b>Hirepurchase Summary
                                Report</b></h2>
                        <hr />
                    </div>
                    <br><br>
                    <div style="display: flex; text-align: center;">

                            <div style="flex: 60%; align-content: center;">
                                <form action="" method="get">
                                    <label for="from_date">From Date:</label>
                                    <input type="date" name="from_date" id="from_date">

                                    <label for="to_date">To Date:</label>
                                    <input type="date" name="to_date" id="to_date">

                                    <button type="submit">
                                        <strong>
                                            Search &nbsp;<i class="fa-solid fa-magnifying-glass"></i>
                                        </strong>
                                    </button>
                                    <button onclick="printTablefun()">
                                        <strong> Print &nbsp;</strong><i class="fa-solid fa-print"></i>
                                    </button>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <Button><a href="{{route("home")}}">Back</a>
                                        <i class="fa-solid fa-house"></i>
                                    </Button>

                                </form>
                            </div>

                        </div>


                    <table class="display" id="t_hire_purchase_sums"
                        style="white-space: nowrap; border:1px solid rgb(7, 7, 7); margin-top:15px;width:100%;">
                        <thead class="styled-table">

                            <th>Cus. NIC</th>
                            <th>Cus. Name</th>
                            <th>Date</th>
                            <th>Invoice No</th>
                            <th>Agreement No</th>
                            <th>Item Gross Am..</th>
                            <th>Item Disc..</th>
                            <th>Item Net Am..</th>
                            <th>Down Payment</th>
                            <th>Service Charge</th>
                            <th>Final Am.</th>
                            <th>No Of Instalment</th>
                            <th>Instalment Am..</th>
                            <th>Final Gross</th>

                        </thead>
                        <tbody>
                            @foreach ( $invoice as $key=>$invoice)
                            <tr>
                                <td>{{$invoice->customer_nic}}</td>
                                <td>{{$invoice->customer_name}}</td>
                                <td>{{$invoice->invoice_date}}</td>
                                <td>{{$invoice->invoice_no}}</td>
                                <td>{{$invoice->agreement_no}}</td>
                                <td>{{$invoice->gross_amount}}</td>
                                <td>{{$invoice->discount}}</td>
                                <td>{{$invoice->net_amount}}</td>
                                <td>{{$invoice->cash_payment}}</td>
                                <td>{{$invoice->document_charge}}</td>
                                <td>{{$invoice->due_amount}}</td>
                                <td>{{$invoice->no_of_instalment}}</td>
                                <td>{{$invoice->instalment}}</td>
                                <td>{{$invoice->final_gross_amount}}</td>
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="5" rowspan="2"><strong>Total</strong></td>
                                <td>Item Gross Tot.:</td>
                                <td>Item Disco. Tot.:</td>
                                <td>Item Net Tot.:</td>
                                <td>Down Payment:</td>
                                <td>Service Charge</td>
                                <td>Final Am.:</td>
                                <td></td>
                                <td>Instalment Am..</td>
                                <td>Final Gross:</td>




                            </tr>
                            <tr>
                                <td>{{$gross_amount}}</td>
                                <td>{{$discount}}</td>
                                <td>{{$net_amount}}</td>
                                <td>{{$cash_payment}}</td>
                                <td>{{$document_charge}}</td>
                                <td>{{$total_due_amount}}</td>
                                <td></td>
                                <td>{{$instalment_amount}}</td>
                                <td>{{$final_gross_amount}}</td>


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

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

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
        $('#t_hire_purchase_sums').DataTable( //database table name
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

        var divToPrint = document.getElementById("t_hire_purchase_sums");
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
