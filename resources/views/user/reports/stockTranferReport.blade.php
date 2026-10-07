
<h2 class="text-center">Stock Tranfer Report</h2>

<div class="text-center mb-3">
    <a href="{{ route('home') }}" class="btn btn-secondary">Back</a>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <meta charset="UTF-8">
    <title>Stock Tranfer Report</title>
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

        <div>
        <label for="to_date" class="form-label">Store :</label>
       <select class="form-control select" id="Store_code" name="storse_id" required>
         <option>Please Select</option>
            @foreach($storeDta as $Data)
             <option value="{{ $Data->Store_code }}">{{ $Data->Store_name }}</option>
              @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Search</button>
</form>

    </div>

<!-- Receipt Table -->
<div class="d-flex justify-content-center">
    <div class="table-responsive" style="width: 70%;">

        <div class="row" >
            <div class="col-md-2"></div>
            <div class="col-md-2"><Span> </Span><input type="text" value="{{$storse_id}}" class="form-control"></div>
            <div class="col-md-1"></div>
            <div class="col-md-2"><input type="text" value="{{$fromDate}}" class="form-control"></div>
            <div class="col-md-1"></div>
            <div class="col-md-2"><input type="text" value="{{$toDate}}" class="form-control"></div>
        </div>
        <br>

        <table class="table table-striped table-bordered display nowrap" id="receiptTable">
            <thead class="table-dark text-center">
                <tr>
                    <th>Transcation Date</th>
                    <th>Transcation No</th>
                    <th>Transcation Type</th>
                    <th>Item Code</th>
                    <th>Qty In</th>
                    <th>Qty Out</th>
                    <th>Store</th>
                    <th>From Store</th>  
                </tr>
            </thead>
            <tbody>
                @foreach ($recipts as $receipts)
                <tr>
                    <td>{{ $receipts->dDate }}</td>
                    <td>{{ $receipts->trans_no }}</td>
                    <td>{{ $receipts->trans_code }}</td>
                    <td>{{ $receipts->item_code }}</td>
                    <td>{{ $receipts->qun_in }}</td>
                    <td>{{ $receipts->qun_out }}</td>
                    <td>{{ $receipts->storse_id }}</td>
                    <td>{{ $receipts->From_store }}</td>                 
                </tr>
                @endforeach
            </tbody>
            <tfoot class="fw-bold table-light">
            <tr>
                <td colspan="4" class="text-end">Total:</td>
                <td class="text-end">
                    {{ number_format($recipts->sum('qun_in'), 2) }}
                </td>
                <td class="text-end">
                    {{ number_format($recipts->sum('qun_out'), 2) }}
                </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="4" class="text-end">Total Balance:</td>
                <td class="text-end" colspan="2">
                    {{ number_format($recipts->sum('qun_in') - $recipts->sum('qun_out'), 2) }}
                </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>


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
            pageLength: 1000
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
</body>
</html>
