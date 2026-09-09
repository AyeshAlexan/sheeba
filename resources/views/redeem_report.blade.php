@if($redeem->count() > 0)
    <h2>Powning Recipts</h2>
    <ul>
        @foreach ( $redeem as $key=>$redeem)

        @endforeach
    </ul>

@else
    <p>No results found.</p>

@endif



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <div><button onClick="window.print()">Print
    </button></div>



    <script>
        // Select wrong element
// Error as #demo is the `div` element
$('#receipts').DataTable()

 // Selector too broad.
 // Error as `.display` is applied to both the div and the table
 $('.display').DataTable();
    </script>
    <title>Recipts</title>
</head>

<body>
    <form action="" method="get">
        <label for="from_date">From Date:</label>
        <input type="date" name="from_date" id="from_date">

        <label for="to_date">To Date:</label>
        <input type="date" name="to_date" id="to_date">

        <button type="submit">Search</button>
    </form>



    <div class="card shadow p-3 mb-3 bg-body-tertiary rounded" >
        <div class="table">
            <table class="display" id="receipts">
                <thead>
                    <tr class="table-secondary">
                        <th>Customer_NIC</th>
                        <th>Customer_Address</th>
                        <th>Customer_Phone</th>
                        <th>Receipt_Type</th>
                        <th>Receipt_Number</th>
                        <th>Receipt_Date</th>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Articles</th>
                        <th>Condition</th>
                        <th>Karatage</th>
                        <th>Weight</th>
                        <th>QTY</th>
                        <th>Value</th> 

                    </tr>
                </thead>
                <tbody>
                @foreach ( $redeem as $key=>$redeem)
                    <tr>
                        <td>{{$redeem->Customer_NIC }}</td>
                        <td>{{$redeem->Customer_Address}}</td>
                        <td>{{$redeem->Customer_Phone }}</td>
                        <td>{{$redeem->Receipt_Type }}</td>
                        <td>{{$redeem->Receipt_Date }}</td>
                        <td>{{$redeem->Date }}</td>
                        <td>{{$redeem->Category}}</td>
                        <td>{{redeem->Articles}}</td>
                        <td>{{$redeem->Condition }}</td>
                        <td>{{$redeem->Karatage }}</td>
                        <td>{{$redeem->Weight }}</td>
                        <td>{{$redeem->QTY}}</td>
                        <td>{{$redeem->Value}}</td>
                    </tr>
                @endforeach
                </tbody>
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
            $('#receipts').DataTable( //database table name
                {
                    dom: 'Bfrtip',
                    buttons: [


                    ],
                }
            );

        });
    </script>

</html>
