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

    </button></div>

    <script>
        // Select wrong element
        // Error as #demo is the `div` element
        $('#T_account_trans').DataTable()

        // Selector too broad.
        // Error as `.display` is applied to both the div and the table
        $('.display').DataTable();

    </script>
    <title>Cash In Hand Report</title>
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

                        <h2 style="text-align:center; background-color:rgb(113, 105, 255);"><b>Cash In Hand Report</b>
                        </h2>
                        <hr />
                    </div>
                    <br>
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


                    <table class="display" id="T_account_trans"
                        style="background-color: transparent; border:1px solid rgb(7, 7, 7); margin-top:15px;">

                        @if($fromDate && $toDate)
                            <caption style="font-size: 18px; font-weight: bold;">
                                Cash In Hand Report&nbsp;&nbsp;&nbsp;&nbsp;From: {{$fromDate}}&nbsp;&nbsp;To: {{$toDate}}
                            <caption>
                        @endif


                        <thead class="styled-table">

                            <th>Date</th>
                            <th>No</th>
                            <th>Transaction</th>
                            <th>DR Amount</th>
                            <th>CR Amount</th>
                            {{-- <th>Balance</th> --}}

                        </thead>
                        <tbody>
                            @foreach ( $invoice as $key=>$invoice)
                            <tr>
                                <td>{{$invoice->Ddate}}</td>
                                <td>{{$invoice->trance_no}}</td>
                                <td>{{$invoice->trance_type}}</td>
                                <td>{{$invoice->dr_amount}}</td>
                                <td>{{$invoice->cr_amount}}</td>
                                {{-- <td></td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" rowspan="2"><strong>Total</strong></td>
                                <td id="drTotal">DR Total:</td>
                                <td id="crTotal">CR Total:</td>
                            </tr>


                            <tr>
                                <td><strong id="totalDrAmount"></strong></td>
                                <td><strong id="totalCrAmount"></strong></td>
                                {{-- <td><strong id="totalBalance"></strong></td> --}}
                            </tr>

                            <tr>
                                <td colspan="3"><strong>Balance Total:</strong></td>
                                <td colspan="2" > <strong id="totalBalance"></strong></td>
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
        var table = $('#T_account_trans').DataTable({
            dom: 'Bfrtip',
            "paging": false,
            buttons: [
                'copy',
                'excel',
                'csv',
                'pdf',
                // 'print',
            ],
        });

        // Calculate and update totals
        function updateTotals() {
            var totalDrAmount = 0;
            var totalCrAmount = 0;
            var totalBalance = 0;

            table.rows().every(function () {
                var data = this.data();
                totalDrAmount += parseFloat(data[3]) ||
                0; // Assuming the DR Amount is in the 4th column
                totalCrAmount += parseFloat(data[4]) ||
                0; // Assuming the CR Amount is in the 5th column
                // Update other totals if needed

                totalBalance = totalCrAmount - totalDrAmount;
            });

            // Update footer values
            $('#totalDrAmount').text(totalDrAmount.toFixed(2));
            $('#totalCrAmount').text(totalCrAmount.toFixed(2));
            $('#totalBalance').text(totalBalance.toFixed(2));
        }

        // Initial update
        updateTotals();

        // Re-calculate totals when the table is drawn
        table.on('draw', function () {
            updateTotals();
        });
    });

</script>

<script>
    function printTablefun() {
        var divToPrint = document.getElementById("T_account_trans");
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
