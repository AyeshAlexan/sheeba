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
    {{-- <div><button onClick="window.print()">Print</button></div> --}}
    <script>
        $('#t_invoice_deils').DataTable()
        $('.display').DataTable();
    </script>

    <title>Item Wish Sales Report</title>

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

                        <h2 style="text-align:center; background-color:rgb(113, 105, 255);">
                            <b>Item Wish Sales Report</b></h2>
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

                    <table class="display" id="t_invoice_deils"
                        style="background-color: transparent; border:1px solid rgb(7, 7, 7); margin-top:15px;">
                         @if($fromDate && $toDate)
                                <caption style="font-size: 18px; font-weight: bold;">
                                    Item Wish Sales Report&nbsp;&nbsp;&nbsp;&nbsp;From: {{$fromDate}}&nbsp;&nbsp;To: {{$toDate}}
                                <caption>
                        @endif
                            <thead>
                                <tr>
                                    <th>Item Code</th>
                                    <th>Item Description</th>
                                    <th>Purchase Price</th>
                                    <th>Quantity</th>
                                    <th>Free Issues</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                    <th>Purchase Price</th>
                                    <th>Profit</th>
                                    <th>Branch Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice as $item)
                                    <tr>
                                        <td>{{ $item->Item_code }}</td>
                                        <td>{{ $item->Item_description }}</td>
                                        <td>{{ number_format($item->purchasePrice, 2) }}</td>
                                        <td>{{ number_format($item->Qty) }}</td>
                                        <td>{{ number_format($item->Free_Issues) }}</td>
                                        <td>{{ number_format($item->Unit_price, 2) }}</td>
                                        <td>{{ number_format($item->total_Price, 2) }}</td>
                                        <td>{{ number_format($item->purchase_price, 2) }}</td>
                                        <td>{{ number_format($item->profit, 2) }}</td>
                                        <td>00{{ number_format($item->BC) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <td colspan="7"><strong>Total:</strong></td>
                                <td ><strong>{{ $totalProfit }}</strong></p></td>
                                <td></td>
                            </tfoot>
                        </table>
                
                        <!-- Overall Sales Summary -->
                        <h3>Overall Sales Summary</h3>
                        <table class="table table-bordered" id="t_invoice_Summary">
                            <tr>
                                <th>Total Qty</th>
                                <td>{{ $totalGrossAmount }}</td>
                            </tr>
                            <tr>
                                <th>Total Unit Amount</th>
                                <td>{{ $totalUnit }}</td>
                            </tr>
                            <tr>
                                <th>Total Discount</th>
                                <td>{{ $totalDiscount }}</td>
                            </tr>
                            <tr>
                                <th>Total Net Amount</th>
                                <td>{{ $totalNetAmount }}</td>
                            </tr>
                            <tr>
                                <th>Total Number of Invoices</th>
                                <td>{{ $totalPawn }}</td>
                            </tr>
                        </table>
                
                        <!-- Detailed Sales Receipts -->
                        <h3>Detailed Sales Receipts</h3>
                        <table class="table table-bordered" id="t_invoice_Receipts">
                            <thead>
                                <tr>
                                    <th>Invoice Number</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Discount</th>
                                    <th>Net Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recipts as $receipt)
                                    <tr>
                                        <td>{{ $receipt->Invoice_no }}</td>
                                        <td>{{ $receipt->QTY }}</td>
                                        <td>{{ number_format($receipt->Unit_price, 2) }}</td>
                                        <td>{{ number_format($receipt->Discount, 2) }}</td>
                                        <td>{{ number_format($receipt->Net_value, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
        $('#t_invoice_deils').DataTable( //database table name
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
    jQuery(document).ready(function ($) {
        $('#t_invoice_Receipts').DataTable( //database table name
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
    jQuery(document).ready(function ($) {
        $('#t_invoice_Summary').DataTable( //database table name
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
        var divToPrint = document.getElementById("t_invoice_deils");
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