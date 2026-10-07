{{-- @if($recipts->count() > 0)
    <h2 class="text-center">Pawning Receipts</h2>
    <div class="text-center mb-3">
        <a href="{{ route('home') }}" class="btn btn-secondary">Back</a>
    </div>
@else
    <p class="text-center text-danger">No results found.</p>
@endif --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <meta charset="UTF-8">
    <title>Sales Summery - SalesMan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- DataTables & Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        table.dataTable th, table.dataTable td {
            white-space: nowrap;
        }
    </style>
</head>
<body class="p-3">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="text-center flex-grow-1 mb-0">Sales Summary - Salesman</h3>

    <a href="{{ route('home') }}" class="btn btn-info ms-3">
        <i class="fa-solid fa-house"></i> Back
    </a>
</div>


    <!-- Date Filter Form -->
<form action="" method="get" class="mb-4">
    <div class="row align-items-end g-3">
        <!-- From Date -->
        <div class="col-md-3">
            <label for="from_date" class="form-label">From Date:</label>
            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
        </div>

        <!-- To Date -->
        <div class="col-md-3">
            <label for="to_date" class="form-label">To Date:</label>
            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date', now()->toDateString()) }}">
        </div>



        <!-- Salesman -->
        <div class="col-md-4">
            <label for="Salesmen" class="form-label">Salesman:</label>
            <select class="form-select" name="Salesmen" id="Salesmen">
                <option value="">Please Select</option>
                @foreach($salesmen as $salesman)
                    <option value="{{ $salesman->name }}" {{ request('Salesmen') == $salesman->name ? 'selected' : '' }}>
                        {{ $salesman->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Submit Button -->
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </div>
</form>

    <!-- Receipt Table -->
<div class="table-responsive">
    <table id="t_redeem_sums" class="table table-bordered table-striped display nowrap w-100" style="width: 80%">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Item Code</th>
                <th>Description</th>
                <th>Total Qty</th>
                <th>Total Free Issues</th>
                <th>Avg. Unit Price</th>
                <th>Total Line Discount</th>
                <th>Total Net Value</th>
                <th>Total Invoice Amount</th>
                <th>Total Invoice Discount</th>
                <th>Total Invoice Net Amount</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($invoice as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->Item_code }}</td>
                <td>{{ $row->Item_description }}</td>
                <td>{{ $row->Total_Qty }}</td>
                <td>{{ $row->Total_Free }}</td>
                <td>{{ number_format($row->Avg_Unit_Price, 2) }}</td>
                <td>{{ number_format($row->Total_Line_Discount, 2) }}</td>
                <td>{{ number_format($row->Total_Net_Value, 2) }}</td>
                <td>{{ number_format($row->Total_Invoice_Amount, 2) }}</td>
                <td>{{ number_format($row->Total_Invoice_Discount, 2) }}</td>
                <td>{{ number_format($row->Total_Invoice_Net_Amount, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


    <!-- Print Button -->
    <div class="mt-3 text-center">
        <button onclick="printTablefun()" class="btn btn-success">Print</button>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables and Export Scripts -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        $(document).ready(function () {
            $('#t_redeem_sums').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'pdfHtml5',
                    'print'
                ],
                scrollX: true,
                pageLength: 1000
            });
        });

        function printTablefun() {
            let printContent = document.getElementById("t_redeem_sums").outerHTML;
            let newWin = window.open("");
            newWin.document.write("<html><head><title>Print</title>");
            newWin.document.write("<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'>");
            newWin.document.write("</head><body>");
            newWin.document.write(printContent);
            newWin.document.write("</body></html>");
            newWin.print();
            newWin.close();
        }
    </script>
</body>
</html>
{{-- @endif --}}