
<h2 class="text-center">All Stock Report</h2>

<div class="text-center mb-3">
    <a href="{{ route('home') }}" class="btn btn-info">Back</a>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <meta charset="UTF-8">
    <title>Stock Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

    <!-- DataTables & Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body {
            font-family: cursive;
             font-size: 14px;
        }

        h2, th {
            font-weight: bold;
            font-family: cursive;
        }

        table.dataTable th, table.dataTable td {
            white-space: nowrap;
            font-size: 14px;
        }

        .btn, label {
           font-family: cursive;
        }
    </style>
</head>

<body class="p-3">

<!-- Date Filter Form -->
<div class="d-flex justify-content-center">
<form action="" method="get" class="mb-4 d-flex gap-3 align-items-end">
    <div>
        <label for="from_date" class="form-label">From Date:</label>
        <input type="date" name="from_date" id="from_date" class="form-control"
               value="{{ request('from_date') }}">
    </div>

    <div>
        <label for="to_date" class="form-label">To Date:</label>
        <input type="date" name="to_date" id="to_date" class="form-control"
               value="{{ request('to_date', date('Y-m-d')) }}">
    </div>

    <button type="submit" class="btn btn-primary">Search</button>
</form>

    </div>

<!-- Receipt Table -->
<div class="d-flex justify-content-center">
    <div class="table-responsive" style="width: 100%;">
<table class="table table-striped table-bordered display nowrap" id="receiptTable" style="width: 100%;">
    <thead class="table-dark text-center">
        <tr>
            <th>Item Code</th>
            <th>Description</th>

            @foreach($showInCodes as $code)
                <th>{{ $code }} In</th>
            @endforeach

            @foreach($showOutCodes as $code)
                <th>{{ $code }} Out</th>
            @endforeach

            <th>Total In</th>
            <th>Total Out</th>
            <th>stock Balance</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stockData as $row)
            <tr>
                <td>{{ $row['Item_code'] }}</td>
                <td>{{ $row['Item_description'] }}</td>

                @foreach($showInCodes as $code)
                    <td>{{ $row[$code . '_in'] ??'-'}}</td>
                @endforeach

                @foreach($showOutCodes as $code)
                    <td>{{ $row[$code . '_out'] ?? '-' }}</td>
                @endforeach

                <td>{{ $row['qun_in'] }}</td>
                <td>{{ $row['qun_out'] }}</td>
                <th>{{ $row['qun_in'] - $row['qun_out']-$row['Free_Issues'] }}</th>
                <td>{{ $row['dDate'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>




    </div>
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
    $('#receiptTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print'],
        scrollX: true,
        pageLength: 1000, // default rows per page
        lengthMenu: [
            [50, 100, 500, 1000],     // values
            ['50', '100', '500', '1000']  // labels shown in dropdown
        ]
    });

    calculateTotals();
});


    function calculateTotals() {
        let pawnTotal = 0, totalWt = 0, amountTotal = 0;

        $('#receiptTable tbody tr').each(function () {
            const pawn = parseFloat($(this).find('td:eq(7)').text()) || 0;
            const total = parseFloat($(this).find('td:eq(8)').text()) || 0;
            const amount = parseFloat($(this).find('td:eq(9)').text()) || 0;

            pawnTotal += pawn;
            totalWt += total;
            amountTotal += amount;
        });

        $('#totalPawnWt').text(pawnTotal.toFixed(2));
        $('#totalTotalWt').text(totalWt.toFixed(2));
        $('#totalAmount').text(amountTotal.toFixed(2));
    }

    function printTablefun() {
        let printContent = document.getElementById("receiptTable").outerHTML;
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

<script>
    var showAll = false;
$('.btn-secondary').on('click', function(e) {
    e.preventDefault();
    if(!showAll) {
        table.page.len(-1).draw();
        $(this).text('Paginate');
    } else {
        table.page.len(10).draw();
        $(this).text('All');
    }
    showAll = !showAll;
});

</script>
</body>
</html>