
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
{{-- <div><button onClick="window.print()">Print --}}

</button></div>




<script>
    // Select wrong element
// Error as #demo is the `div` element
$('#t_cheques').DataTable()

// Selector too broad.
// Error as `.display` is applied to both the div and the table
$('.display').DataTable();
</script>
<title>Customer Cheque Payment Report</title>

<style>
    .table {
        display: block;
        overflow-y: hidden;
        overflow-x: auto;
        scroll-behavior: smooth;
        }
    th,td{
        border: 1px solid rgb(8, 8, 8);
        padding: 2px;
    }
    </style>

</head>

<body>

{{-- <div class="card shadow p-3 mb-3 bg-body-tertiary rounded" > --}}
    <div class="d-flex justify-content-center profile-container">
        <div class='col-md-6 text-center sort-profile' id='sort-profile'>
        <div class='row'>
        <div class='col-md-6 text-center' ><br/>
            <div styel="background-color: yellow;">

                <h2 style="text-align:center; background-color:rgb(113, 105, 255);"><b>Customer Cheque Payment Report</b></h2><hr/>
            </div>

            <div style="display: flex; text-align: center;">
                <div style="flex: 60%; align-content: center;">
                    <form action="" method="GET">
                        @csrf
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
<br>
        <table class="display" id="t_sup_cheques" style="white-space: nowrap; border:1px solid rgb(7, 7, 7); margin-top:15px;width:80%;">
                <thead class="styled-table">
                   <tr>
                    <th>Transfer No</th>
                    <th>Release Date</th>
                    <th>Transfer Type</th>
                    <th>Bank</th>
                    <th>Branch Code</th>
                    <th>Cheques No</th>
                    <th>Account No</th>
                    <th>Amount</th>
                   </tr>
                </thead>
            <tbody>
            @php
                $totalAmount = 0;
            @endphp
            @foreach( $receipts as $key=>$invoice)
                <tr>
                    <td>{{$invoice->trans_no}}</td>
                    <td>{{$invoice->release_date}}</td>
                    <td>{{$invoice->trans_type}}</td>
                    <td>{{$invoice->bank}}</td>
                    <td>{{$invoice->branch_code}}</td>
                    <td>{{$invoice->cheques_no}}</td>
                    <td>{{$invoice->acc_no}}</td>
                    <td align="right">{{number_format($invoice->amount, 2)}}</td>
                </tr>
                @php
                    $totalAmount += $invoice->amount;
                @endphp
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td  colspan="7" align="center" ><b> Total Balance</b></td>
                    <td align="right"><strong>{{number_format($totalAmount, 2)}}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

        </div>
    </div>
</div>

</body>

    {{-- form default date set for today --}}
    <script>
        var dateObj = new Date();
        document.getElementById('to_date').value = dateObj.toISOString().slice(0, 10);
    </script>

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
        $('#t_sup_cheques').DataTable( //database table name
            {
                dom: 'Bfrtip',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print',


                ],
                pageLength: 100 // Set the default number of entries per page to 100
            }
        );

    });
    </script>

<script>
    var dateObj = new Date();
    document.getElementById('to_date').value = dateObj.toISOString().slice(0, 10);

</script>


<script>
    function printTablefun() {
        var divToPrint = document.getElementById("t_sup_cheques");
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
</script>



</html>
