@if($invoice->count() > 0)
{{-- <h2>Purchases Report</h2> --}}





@else
<p>No results found.</p>

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
    // Select wrong element
// Error as #demo is the `div` element
$('#t_purchase_order_sums').DataTable()

// Selector too broad.
// Error as `.display` is applied to both the div and the table
$('.display').DataTable();
</script>
<title>Purchases Order Summary Report</title>
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
   color:blue!important;
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
</style>

</head>

<body>
    <br><br>
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

                <h2 style="text-align:center; background-color:rgb(113, 105, 255);"><b>Purchases Order Summary Report</b></h2><hr/>
            </div>


        <table class="display" id="t_purchase_order_sums" style="white-space: nowrap; border:1px solid rgb(7, 7, 7); margin-top:15px;width:50%;">
                <thead class="styled-table">
                    <th>Invoice No</th>
                    <th>Invoice date</th>
                    <th>Supplier Code</th>
                    <th>Supplier Name</th>
                    <th>Supplier Phone</th>
                    <th>Gross Amount</th>
                    <th>Discount</th>
                    <th>Net Amount</th>
                    <th>Operator</th>
                    <th>Branch</th>
                </thead>
            <tbody>
            @foreach ( $invoice as $key=>$invoice)
                <tr>
                    <td>{{$invoice->Invoice_no}}</td>
                    <td>{{$invoice->Invoice_date}}</td>
                    <td>{{$invoice->Supplier_Code}}</td>
                    <td>{{$invoice->Supplier_Name}}</td>
                    <td>{{$invoice->Supplier_Phone}}</td>
                    <td>{{$invoice->Gross_Amount}}</td>
                    <td>{{$invoice->Discount}}</td>
                    <td>{{$invoice->Net_Amount}}</td>
                    <td>{{$invoice->OC}}</td>
                    <td>{{$invoice->BC}}</td>
                </tr>
            @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="4" rowspan="2"><strong>Total</strong></td>
                    <td></td>
                    <td>Gross Amount:</td>
                    <td>Discount:</td>
                    <td>Net Amount:</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>


                    <td></td>
                    <td><strong>{{$totalGrossAmount}}</strong></td>
                    <td><strong>{{$totalDiscount}}</strong></td>
                    <td><strong>{{$totalNetAmount}}</strong></td>
                    <td></td>
                    <td></td>
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
    $('#t_purchase_order_sums').DataTable( //database table name
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

       var divToPrint=document.getElementById("t_purchases_sums");
       newWin= window.open("");
       newWin.document.write(divToPrint.outerHTML);
       newWin.print();
       newWin.close();
    }

    function printtbodyfun()
    {
       var divToPrint=document.getElementById("t_purchases_sums");
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
