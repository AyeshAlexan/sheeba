@if($invoice->count() > 0)

@else
    <p class="no-result">No results found.</p>
@endif

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash In Hand Report</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/buttons/2.1.2/css/buttons.dataTables.min.css">

    <style>
        body {
            font-family: "Segoe UI", Tahoma, sans-serif;
            background-color: #f9f9f9;
            margin: 20px;
        }

        form {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        form label {
            font-weight: bold;
            margin-right: 8px;
        }

        form input, form select, form button {
            margin-right: 10px;
            padding: 6px 10px;
        }

        form button {
            background: #7169ff;
            border: none;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
        }

        form button a {
            text-decoration: none;
            color: #fff;
        }

        form button:hover {
            background: #000005;
        }

        h2 {
            text-align: center;
            background: linear-gradient(90deg, #7169ff, #4c43d7);
            color: #fff;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        table.dataTable {
            border-collapse: collapse;
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
        }

        table.dataTable thead {
            background-color: #010108;
            color: #fff;
        }

        table.dataTable th, table.dataTable td {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table.dataTable tfoot {
            background: #e9e9e9;
            font-weight: bold;
        }

        .no-result {
            color: red;
            font-weight: bold;
            text-align: center;
        }

        @media print {
            body {
                background: #fff;
            }
            form, .dt-buttons {
                display: none;
            }
            table {
                font-size: 12px;
            }
            tfoot {
                display: table-row-group !important;
            }
        }
    </style>
</head>
<body>

        <h2>Customer Account Report</h2>
    <form action="" method="get">
<select name="customer" id="customer" class="form-control">
    <option value="">-- Select Customer --</option>
    @foreach($Customerdata as $customer)
        <option value="{{ $customer->Code }}"
            {{ request('customer') == $customer->Code ? 'selected' : '' }}>
            {{ $customer->First_name }} - {{ $customer->Code }}
        </option>
    @endforeach
</select>



        <label for="from_date">From Date:</label>
        <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}">

        <label for="to_date">To Date:</label>
        <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}">

        <button type="submit">Search</button>
        <button><a href="{{ ('home') }}">Back</a></button>
    </form>


<table id="T_account_trans" class="display" style="margin-top:15px;">
    <thead>
        <tr>
            <th>Date</th>
            <th>No</th>
            <th>Customer Code</th>
            <th>Customer Name</th>
            <th>Transaction</th>
            <th>DR Amount</th>
            <th>CR Amount</th>
            <th>Balance</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($invoice as $row)
            @php
                // Find the customer name based on code
                $customerName = $Customerdata->firstWhere('Code', $row->customer)->First_name ?? '';
            @endphp
            <tr>
                <td>{{ $row->dDate }}</td>
                <td>{{ $row->trance_no }}</td>
                <td>{{ $row->customer }}</td>
                <td>{{ $customerName }}</td>
                <td>{{ $row->cr_trnce_code }}</td>
                <td>{{ number_format($row->dr_amount, 2) }}</td>
                <td>{{ number_format($row->cr_amount, 2) }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" rowspan="2"><strong>Total</strong></td>
            <td id="drTotal">DR Total:</td>
            <td id="crTotal">CR Total:</td>
            <td id="balanceTotal">Balance Total:</td>
        </tr>
        <tr>
            <td><strong id="totalDrAmount">{{ $totalDrAmount }}</strong></td>
            <td><strong id="totalCrAmount">{{ $totalCrAmount }}</strong></td>
            <td><strong id="totalBalance">{{ $totalBalance }}</strong></td>
        </tr>
    </tfoot>
</table>


    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function () {
        $('#T_account_trans').DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[100, 250, 500, -1], [100, 250, 500, "All"]],
            pageLength: -1, // default "All" rows
            buttons: [
                { extend: 'print', text: '🖨 Print' },
                { extend: 'excel', text: '📊 Excel' },
                { extend: 'csv', text: '📑 CSV' },
                { extend: 'pdf', text: '📄 PDF' },
                { extend: 'copy', text: '📋 Copy' }
            ]
        });

        // set default "to_date" to today if empty
        if (!$('#to_date').val()) {
            let today = new Date().toISOString().slice(0, 10);
            $('#to_date').val(today);
        }
    });
</script>


<!-- Add these after jQuery -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#customer').select2({
        placeholder: "-- Select Customer --",
        allowClear: true,
        width: 'resolve'
    });
});
</script>
</body>
</html>