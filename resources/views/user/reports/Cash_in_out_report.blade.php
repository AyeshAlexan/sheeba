@if($invoice->count() > 0)

@else
    <p>No results found.</p>

@endif



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <div><button onClick="window.print()">Print --}}
        <link rel="stylesheet" href="href=" https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> <link
        rel="stylesheet" href="vendor/DataTables/datatables.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="vendor/jquery/jquery-1.11.2.min.js" type="text/javascript"></script>
    <script src="vendor/DataTables/datatables.min.js" type="text/javascript"></script>
    <title>Cash in Hand Report</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    </button></div>

    <script>
        // Select wrong element
// Error as #demo is the `div` element
$('#t_invoice_sum').DataTable()

 // Selector too broad.
 // Error as `.display` is applied to both the div and the table
 $('.display').DataTable();
    </script>
    <title>Invoice Summary Report</title>
    <style>
        table{
            border-collapse: collapse;
            width: 100%;
        }
    th,td{
        border: 1px solid rgb(8, 8, 8);
        padding: 5px;
    }

    </style>

</head>
<br><br><br><br>

<body>
    <form action="" method="get">
        <label for="from_date">From Date:</label>
        <input type="date" name="from_date" id="from_date">

        <label for="to_date">To Date:</label>
        <input type="date" name="to_date" id="to_date">

        <button type="submit">Search</button>
        {{-- <button onclick="printTablefun()">Print</button> --}}
        <Button ><a href="{{route("home")}}"> Back</a></Button>

    </form>



    {{-- <div class="card shadow p-3 mb-3 bg-body-tertiary rounded" > --}}
        <div class="d-flex justify-content-center profile-container">
            <div class='col-md-6 text-center sort-profile' id='sort-profile'>
            <div class='row'>
            <div class='col-md-6 text-center' ><br/>
                <div styel="background-color: yellow;">

                    <h2 style="text-align:center; background-color:rgb(113, 105, 255);"><b>Cash in Hand Report</b></h2><hr/>
                </div>


            <table class="display" id="t_invoice_sum" style="background-color: transparent; border:1px solid rgb(7, 7, 7); margin-top:15px;">
                    <thead class="styled-table">
                        <th>Details</th>
                        <th>Invoice Cash In</th>
                        <th>Cash Out</th>

                    </thead>
                    <tbody>
                        <tr>
                            <td >Sales</td>
                            <td style align="right">{{$totalCashPay}}</td>
                            <td style align="right"></td>
                        </tr>
                        <tr>
                            <td >Supplier Payment </td>
                            <td style align="right"></td>
                            <td style align="right">{{$totalPayment_Amount}}</td>
                        </tr>
                        <tr>
                            <td >Supplier Payment </td>
                            <td style align="right"></td>
                            <td style align="right"></td>
                        </tr>

                        <tr>
                            <td style align="right" ><b>Total</td>

                            <td style align="right"><b>{{$totalCashPay}}</td>

                            <td style align="right"><b>{{$totalPayment_Amount}}</td>
                        </tr>


      </tbody>

      <tfoot>
        <tr>
            <td style align="right" ><b></td>

            <td style align="right"><b>Balance</td>

            <td style align="right"><b>{{$totalbalance}}<b>

                    {{-- {{$result}} --}}
            </td>
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
    jQuery(document).ready(function($) {
        $('#t_invoice_sum').DataTable( //database table name
            {
                dom: 'Bfrtip',
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
        function printTablefun()
        {

           var divToPrint=document.getElementById("t_invoice_sums");
           newWin= window.open("");
           newWin.document.write(divToPrint.outerHTML);
           newWin.print();
           newWin.close();
        }

        function printtbodyfun()
        {
           var divToPrint=document.getElementById("t_invoice_sums");
           newWin= window.open("");
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
