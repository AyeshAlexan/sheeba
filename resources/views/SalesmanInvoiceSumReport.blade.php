<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <meta charset="UTF-8">
    <title>Sales Summary - Salesman</title>
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
        .summary-table tbody tr {
            font-weight: 500;
        }
        .summary-table tbody tr:last-child {
            background-color: #f0f0f0;
            font-weight: 700;
            border-top: 2px solid #333;
        }
        .text-end {
            text-align: right;
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

<!-- Filter Form -->
<form action="" method="get" class="mb-4">
    <div class="row align-items-end g-3">
        <div class="col-md-3">
            <label for="from_date" class="form-label">From Date:</label>
            <input type="date" name="from_date" id="from_date" class="form-control"
                   value="{{ request('from_date') }}">
        </div>

        <div class="col-md-3">
            <label for="to_date" class="form-label">To Date:</label>
            <input type="date" name="to_date" id="to_date" class="form-control"
                   value="{{ request('to_date', now()->toDateString()) }}">
        </div>

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

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </div>
</form>

@if($invoice->count() > 0)

<!-- Salesman Summary Table -->
<h5 class="mt-4 mb-3">Salesman Summary</h5>
<div class="table-responsive mb-4">
    <table class="table table-bordered table-striped summary-table">
        <thead class="table-dark">
        <tr>
            <th>Salesman</th>
            <th class="text-end">Invoice Count</th>
            <th class="text-end">Gross Amount</th>
            <th class="text-end">Discount</th>
            <th class="text-end">Net Amount</th>
            <th class="text-end">Cash</th>
            <th class="text-end">Credit</th>
            <th class="text-end">Cheque</th>
            <th class="text-end">Paid Amount</th>
        </tr>
        </thead>
        <tbody>
        @php
            $summaryData = [];
            $totalGross = 0;
            $totalDiscount = 0;
            $totalNet = 0;
            $totalCash = 0;
            $totalCredit = 0;
            $totalCheque = 0;
            $totalPaid = 0;
            $totalInvoices = 0;

            foreach($invoice as $row) {
                $salesman = $row->Salesmen;
                if (!isset($summaryData[$salesman])) {
                    $summaryData[$salesman] = [
                        'count' => 0,
                        'gross' => 0,
                        'discount' => 0,
                        'net' => 0,
                        'cash' => 0,
                        'credit' => 0,
                        'cheque' => 0,
                        'paid' => 0,
                    ];
                }

                $summaryData[$salesman]['count']++;
                $summaryData[$salesman]['gross'] += $row->Gross_Amount;
                $summaryData[$salesman]['discount'] += $row->Discount;
                $summaryData[$salesman]['net'] += $row->Net_Amount;
                $summaryData[$salesman]['cash'] += $row->Cash_Pay;
                $summaryData[$salesman]['credit'] += $row->Credite;
                $summaryData[$salesman]['cheque'] += $row->Cheque;
                $summaryData[$salesman]['paid'] += $row->Paid_Amount;
            }

            foreach($summaryData as $salesman => $data) {
                $totalInvoices += $data['count'];
                $totalGross += $data['gross'];
                $totalDiscount += $data['discount'];
                $totalNet += $data['net'];
                $totalCash += $data['cash'];
                $totalCredit += $data['credit'];
                $totalCheque += $data['cheque'];
                $totalPaid += $data['paid'];
            }
        @endphp

        @foreach($summaryData as $salesman => $data)
            <tr>
                <td><strong>{{ $salesman }}</strong></td>
                <td class="text-end">{{ $data['count'] }}</td>
                <td class="text-end">{{ number_format($data['gross'], 2) }}</td>
                <td class="text-end">{{ number_format($data['discount'], 2) }}</td>
                <td class="text-end">{{ number_format($data['net'], 2) }}</td>
                <td class="text-end">{{ number_format($data['cash'], 2) }}</td>
                <td class="text-end">{{ number_format($data['credit'], 2) }}</td>
                <td class="text-end">{{ number_format($data['cheque'], 2) }}</td>
                <td class="text-end">{{ number_format($data['paid'], 2) }}</td>
            </tr>
        @endforeach

        <!-- Grand Total Row -->
        <tr>
            <td><strong>GRAND TOTAL</strong></td>
            <td class="text-end"><strong>{{ $totalInvoices }}</strong></td>
            <td class="text-end"><strong>{{ number_format($totalGross, 2) }}</strong></td>
            <td class="text-end"><strong>{{ number_format($totalDiscount, 2) }}</strong></td>
            <td class="text-end"><strong>{{ number_format($totalNet, 2) }}</strong></td>
            <td class="text-end"><strong>{{ number_format($totalCash, 2) }}</strong></td>
            <td class="text-end"><strong>{{ number_format($totalCredit, 2) }}</strong></td>
            <td class="text-end"><strong>{{ number_format($totalCheque, 2) }}</strong></td>
            <td class="text-end"><strong>{{ number_format($totalPaid, 2) }}</strong></td>
        </tr>
        </tbody>
    </table>
</div>

<!-- Detailed Invoice Table -->
<h5 class="mb-3">Detailed Invoices</h5>
<div class="table-responsive">
    <table id="t_redeem_sums" class="table table-bordered table-striped display nowrap w-100">
        <thead class="table-dark">
        <tr>
            <th>Invoice No</th>
            <th>Date</th>
            <th>Customer Name</th>
            <th>NIC</th>
            <th>Phone</th>
            <th>Salesman</th>
            <th>Gross Amount</th>
            <th>Discount</th>
            <th>Net Amount</th>
            <th>Cash Pay</th>
            <th>Credit</th>
            <th>Cheque</th>
            <th>Paid Amount</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoice as $row)
            <tr>
                <td>{{ $row->Invoice_no }}</td>
                <td>{{ $row->Invoice_date }}</td>
                <td>{{ $row->Customer_Name }}</td>
                <td>{{ $row->Customer_NIC }}</td>
                <td>{{ $row->Customer_Phone }}</td>
                <td>{{ $row->Salesmen }}</td>
                <td class="text-end">{{ number_format($row->Gross_Amount, 2) }}</td>
                <td class="text-end">{{ number_format($row->Discount, 2) }}</td>
                <td class="text-end">{{ number_format($row->Net_Amount, 2) }}</td>
                <td class="text-end">{{ number_format($row->Cash_Pay, 2) }}</td>
                <td class="text-end">{{ number_format($row->Credite, 2) }}</td>
                <td class="text-end">{{ number_format($row->Cheque, 2) }}</td>
                <td class="text-end">{{ number_format($row->Paid_Amount, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- Print Button -->
<div class="mt-3 text-center">
    <button onclick="printTablefun()" class="btn btn-success">
        <i class="fas fa-print"></i> Print
    </button>
</div>

@else
    <p class="text-center text-danger">No results found.</p>
@endif

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print'],
            scrollX: true,
            pageLength: 1000
        });
    });

    function printTablefun() {
        let summaryTable = document.querySelector('.summary-table').outerHTML;
        let detailTable = document.getElementById("t_redeem_sums").outerHTML;
        let newWin = window.open("");
        newWin.document.write("<html><head><title>Print</title>");
        newWin.document.write("<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'>");
        newWin.document.write("<style>.text-end { text-align: right; } table { margin-bottom: 20px; }</style>");
        newWin.document.write("</head><body>");
        newWin.document.write("<h4 class='text-center mb-3'>Sales Summary - Salesman</h4>");
        newWin.document.write("<h5 class='mt-4 mb-3'>Salesman Summary</h5>");
        newWin.document.write(summaryTable);
        newWin.document.write("<h5 class='mt-4 mb-3'>Detailed Invoices</h5>");
        newWin.document.write(detailTable);
        newWin.document.write("</body></html>");
        newWin.print();
        newWin.close();
    }
</script>

</body>
</html>