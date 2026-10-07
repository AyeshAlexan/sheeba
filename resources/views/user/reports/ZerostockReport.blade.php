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
        // Select wrong element
        // Error as #demo is the `div` element
        $('#t_item_movements').DataTable()

        // Selector too broad.
        // Error as `.display` is applied to both the div and the table
        $('.display').DataTable();

    </script>
    <title>Zero Stock Report</title>
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

    </style>


</head>


<body>



    {{-- <div class="card shadow p-3 mb-3 bg-body-tertiary rounded" > --}}
    <div class="d-flex justify-content-center profile-container">
        <div class='col-md-6 text-center sort-profile' id='sort-profile'>
            <div class='row'>
                <div class='col-md-6 text-center'><br />
                    <div styel="background-color: yellow;">

                        <h2 style="text-align:center; background-color:rgb(113, 105, 255);"><b>Zero Stock Report</b>
                        </h2>
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



                    <table class="display" id="t_item_movements"
                        style="background-color: transparent; border:1px solid rgb(7, 7, 7); margin-top:15px;">

                        @if($fromDate && $toDate)
                                <caption style="font-size: 18px; font-weight: bold;">
                                    Zero Stock Report&nbsp;&nbsp;&nbsp;&nbsp;From: {{$fromDate}}&nbsp;&nbsp;To: {{$toDate}}
                                <caption>
                        @endif

                        <thead class="styled-table">
                            <th style="text-align:center;">Date</th>
                            <th style="text-align:center;">ITEM CODE</th>
                            <th style="text-align:center;">QUANTITY IN</th>

                        </thead>
                        <tbody>
                            @foreach ( $invoice as $key=>$invoice)
                            <tr>
                                <td>{{$invoice->dDate}}</td>
                                <td>{{$invoice->item_code}}</td>
                                <td>{{$invoice->qun_in}}</td>
                                {{-- <td>{{$invoice->Item_category}}</td>
                                <td>{{$invoice->Item_code}}</td>
                                <td>{{$invoice->Item_description}}</td>
                                <td>{{$invoice->QTY}}</td>
                                <td>{{$invoice->Unit_price}}</td>
                                <td>{{$invoice->Discount}}</td>
                                <td>{{$invoice->Net_value}}</td>
                                <td>{{$invoice->OC}}</td>
                                <td>{{$invoice->BC}}</td> --}}
                            </tr>
                            @endforeach
                        </tbody>

                        {{-- <tfoot>
                    <tr>
                        <td colspan="1" rowspan="2"><strong>Total</strong></td>
                        <td>QTY:</td>
                        {{--  --}}
                        {{-- <td></td>
                        <td><td>
                        <td></td> --}}
                        {{-- </tr>

                </tfoot> --}}
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
        $('#t_item_movements').DataTable( //database table name
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

        var divToPrint = document.getElementById("t_item_movements");
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }

</script>
<script>
    var dateObj = new Date();
    document.getElementById('to_date').value = dateObj.toISOString().slice(0, 10);

</script>

</html>
