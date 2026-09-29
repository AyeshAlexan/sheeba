<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stock Report</title>

    <style>
        :root {
            --primary-color: #009879;
            --secondary-color: #7169ff;
            --bg-gray: #f3f3f3;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding: 20px;
        }

        .report-header {
            background-color: var(--secondary-color);
            color: white;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            margin-bottom: 0;
        }

        .filter-container {
            background: white;
            padding: 20px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }

        .filter-container input[type="date"] {
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.95em;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .styled-table thead tr {
            background-color: var(--primary-color);
            color: #ffffff;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
            border: 1px solid #eeeeee;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:hover {
            background-color: #e9ecef;
            transition: background-color 0.2s ease;
        }

        .styled-table tfoot {
            font-weight: bold;
            background-color: #eee;
        }

        /* Color-coded row classes */
        .row-positive { background-color: #d4edda !important; } /* green  — qty > 0 */
        .row-zero     { background-color: #fff3cd !important; } /* yellow — qty = 0 */
        .row-negative { background-color: #f8d7da !important; } /* red    — qty < 0 */

        button, .btn-back {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: opacity 0.2s;
            text-decoration: none;
        }

        #submit_1    { background-color: var(--primary-color); color: white; }
        .btn-print   { background-color: #444; color: white; }
        .btn-back    { background-color: #007bff; color: white; display: inline-block; }

        button:hover { opacity: 0.8; }

        /* Legend */
        .legend {
            display: flex;
            gap: 20px;
            margin-bottom: 10px;
            font-size: 0.85em;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .legend-box {
            width: 16px;
            height: 16px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }

        @media print {
            .filter-container, button, .btn-back,
            .dataTables_filter, .dataTables_length, .legend {
                display: none !important;
            }
            .styled-table {
                box-shadow: none;
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="d-flex justify-content-center profile-container">
        <div class='col-md-6 text-center sort-profile' id='sort-profile'>
            <div class='row'>
                <div class='col-md-6 text-center'>
                    <br />

                    @if ($errors->any())
                        <div class="alert alert-danger text-center" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <h2 style="text-align:center; background-color:rgb(113, 105, 255); color:white; padding:10px; border-radius:6px;">
                        <b>Stock Report</b>
                    </h2>
                    <hr />

                    <div style="display: flex; text-align: center;">
                        <div style="flex: 60%; align-content: center;">
                            <form action="{{ route('filter_stock_by_date') }}" method="GET">
                                @csrf
                                <label for="from_date">From Date :</label>
                                <input type="date" name="from_date" id="from_date" value="{{ $fromDate }}">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label for="to_date">To Date :</label>
                                <input type="date" name="to_date" id="to_date" value="{{ $toDate }}">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label for="item_code">Item Code :</label>
                                <input type="text" name="item_code" id="item_code" value="{{ $itemCode }}" placeholder="Item code">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label for="category">Category :</label>
                                <input type="text" name="category" id="category" value="{{ $category }}" placeholder="Category">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="submit" id="submit_1">
                                    Submit &nbsp;<i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                                <button type="button" class="btn-print" onclick="printTablefun()">
                                    <strong>Print &nbsp;</strong><i class="fa-solid fa-print"></i>
                                </button>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="{{ route('home') }}" class="btn-back">
                                    Back &nbsp;<i class="fa-solid fa-house"></i>
                                </a>
                            </form>
                        </div>
                    </div>

                    {{-- Color legend --}}
                    <div class="legend" style="margin-top: 15px;">
                        <div class="legend-item">
                            <div class="legend-box" style="background-color:#d4edda;"></div>
                            <span>Positive Stock</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-box" style="background-color:#fff3cd;"></div>
                            <span>Zero Stock</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-box" style="background-color:#f8d7da;"></div>
                            <span>Negative Stock</span>
                        </div>
                    </div>

                    <table class="display styled-table" id="t_item_movements"
                        style="background-color: transparent; margin-top:15px;">

                        @if($fromDate && $toDate)
                            <caption style="font-size: 18px; font-weight: bold;">
                                Stock Report &nbsp;&nbsp; From: {{ $fromDate }} &nbsp;&nbsp; To: {{ $toDate }}
                            </caption>
                        @endif

                        <thead>
                            <tr>
                                <th style="text-align:center; width:5%;">ITEM CODE</th>
                                <th style="text-align:center; width:10%;">ITEM NAME</th>
                                <th style="text-align:center; width:10%;">QUANTITY</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($stockDetails as $item)
                                @php
                                    $qty = $item->total_qun_in
                                         - $item->total_qun_out
                                         - $item->total_free_issues;

                                    $rowClass = $qty > 0 ? 'row-positive'
                                              : ($qty < 0 ? 'row-negative' : 'row-zero');
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td>{{ $item->Item_code }}</td>
                                    <td>{{ $item->Item_description }}</td>
                                    <td style="text-align:center;">{{ $qty }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <td></td>
                                <td><b>Total Balance</b></td>
                                <td style="text-align:center;"><b>{{ $balance }}</b></td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

<script>
    jQuery(document).ready(function ($) {
        $('#t_item_movements').DataTable({
            dom: 'Bfrtip',
            "paging": false,
            buttons: [
                'copy',
                'excel',
                'csv',
                'pdf',
            ],
        });
    });
</script>

<script>
    function printTablefun() {
        var divToPrint = document.getElementById("t_item_movements");
        var newWin = window.open("");
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