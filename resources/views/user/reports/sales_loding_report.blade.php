<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Salesman Loding Report</title>

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    
    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

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

    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('#example').DataTable({
                "paging": true,       // Enable pagination
                "searching": true,    // Enable search
                "ordering": true,     // Enable column sorting
                "info": true,         // Display table info
                "lengthChange": true  // Allow user to change page length
            });
        });
    </script>

</head>

<body>

    <div class="d-flex justify-content-center profile-container">
        <div class='col-md-6 text-center sort-profile' id='sort-profile'>
            <div class='row'>
                <div class='col-md-6 text-center'>
                    <h2 style="text-align:center; background-color:rgb(113, 105, 255);"><b>Salesman Loding Report</b></h2>
                    <hr />
                    <br><br>
                    
                    <form action="" method="get">
                        <label for="date">From Date :</label>
                        <input type="date" name="from_date" id="from_date">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="date">To Date :</label>
                        <input type="date" name="to_date" id="to_date">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        {{-- <label for="salesman">Salesman :</label> --}}
                        {{-- <select class="select form-control" name="salesman" id="salesman" aria-hidden="true">
                            <option value="">Please Select</option>
                            @foreach($salesman_data as $salesmandata)
                            <option value="{{ $salesmandata->name}}">{{ $salesmandata->name }}</option>
                            @endforeach
                        </select> --}}
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="submit" id="submit_1">
                            Submit &nbsp;<i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                        <button type="button" onclick="printTablefun()">
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

                    <br><br>

                    <table id="example" class="table table-bordered mt-3" style="width: 80%; margin: 0 auto;">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Description</th>
                                <th>VAT Invoice </th>
                                <th>Free Issue(VAT Invoice)</th>
                                <th>Invoice</th>
                                <th>Free Issue(Invoice)</th>
                                <th>Total Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $items = [];
                            @endphp
               @foreach($combined_query as $row)
               @php
                   if (!empty($row->Item_code)) {
                       if (!isset($items[$row->Item_code])) {
                           $items[$row->Item_code] = [
                               'Item_description' => $row->Item_description ?? 'N/A',
                               'Free Issue Quantity' => 0,
                               'Invoice Quantity' => 0,
                               'Total Quantity' => 0,
                               'Free Issue Quantity Free' => 0,
                               'Invoice Quantity Free' => 0,
                           ];
                       }
                       if ($row->source == 'Free Issue') {
                           $items[$row->Item_code]['Free Issue Quantity'] += $row->total_qty;
                           $items[$row->Item_code]['Free Issue Quantity Free'] += $row->total_Free_Issues;
                       } elseif ($row->source == 'Invoice') {
                           $items[$row->Item_code]['Invoice Quantity'] += $row->total_qty;
                           $items[$row->Item_code]['Invoice Quantity Free'] += $row->total_Free_Issues;
                       }
           
                       // Correct calculation for Total Quantity
                       $items[$row->Item_code]['Total Quantity'] += ($row->total_qty ?? 0) + ($row->total_Free_Issues ?? 0);
                   }
               @endphp
           @endforeach
           

                            

                            @foreach($items as $itemCode => $item)
                                <tr>
                                    <td>{{ $itemCode }}</td>
                                    <td>{{ $item['Item_description'] }}</td>
                                    <td>{{ $item['Invoice Quantity'] }}</td> 
                                    <td>{{ $item['Invoice Quantity Free'] }}</td>
                                    <td>{{ $item['Free Issue Quantity'] }}</td>
                                    <td>{{ $item['Free Issue Quantity Free'] }}</td>
                                    <td>{{ $item['Total Quantity'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <script>
        function printTablefun() {
            var divToPrint = document.getElementById("example");
            newWin = window.open("");
            newWin.document.write(divToPrint.outerHTML);
            newWin.print();
            newWin.close();
        }
    </script>

    <!-- Set default date to today -->
    <script>
        var dateObj = new Date();
        document.getElementById('to_date').value = dateObj.toISOString().slice(0, 10);
    </script>

</body>

</html>