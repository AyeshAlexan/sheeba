@if($invoice->count() > 0)

@else
<p>No results found.</p>

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

    <title>Cash In Hand Report</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/reports-modern.css') }}">

</head>

<body>

    <div class="report-page">
        <div class="report-card">
            <div class="report-topbar">
                <h1 class="report-title"><i class="fa-solid fa-file-lines"></i> Cash In Hand Report</h1>
                <a href="{{ route('home') }}" class="btn btn-back"><i class="fa-solid fa-house"></i> Back</a>
            </div>

            <form action="" method="get" class="report-filters">
                <div class="field">
                    <label for="from_date">From Date</label>
                    <input type="date" name="from_date" id="from_date" value="{{ request('from_date') }}">
                </div>
                <div class="field">
                    <label for="to_date">To Date</label>
                    <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}">
                </div>
                <div class="field field-actions">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                    <button type="button" onclick="printTablefun()" class="btn btn-secondary"><i class="fa-solid fa-print"></i> Print</button>
                </div>
            </form>

            <div class="table-scroll">
                    <table class="display modern-table" id="T_account_trans">

                        @if($fromDate && $toDate)
                            <caption>
                                Cash In Hand Report&nbsp;&nbsp;&nbsp;&nbsp;From: {{$fromDate}}&nbsp;&nbsp;To: {{$toDate}}
                            <caption>
                        @endif


                        <thead class="styled-table">

                            <th>Date</th>
                            <th>No</th>
                            <th>Transaction</th>
                            <th>DR Amount</th>
                            <th>CR Amount</th>
                            {{-- <th>Balance</th> --}}

                        </thead>
                        <tbody>
                            @foreach ( $invoice as $key=>$invoice)
                            <tr>
                                <td>{{$invoice->Ddate}}</td>
                                <td>{{$invoice->trance_no}}</td>
                                <td>{{$invoice->trance_type}}</td>
                                <td>{{$invoice->dr_amount}}</td>
                                <td>{{$invoice->cr_amount}}</td>
                                {{-- <td></td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" rowspan="2"><strong>Total</strong></td>
                                <td id="drTotal">DR Total:</td>
                                <td id="crTotal">CR Total:</td>
                            </tr>


                            <tr>
                                <td><strong id="totalDrAmount"></strong></td>
                                <td><strong id="totalCrAmount"></strong></td>
                                {{-- <td><strong id="totalBalance"></strong></td> --}}
                            </tr>

                            <tr>
                                <td colspan="3"><strong>Balance Total:</strong></td>
                                <td colspan="2" > <strong id="totalBalance"></strong></td>
                            </tr>
                        </tfoot>

                    </table>
            </div>
        </div>
    </div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('js/reports-modern.js') }}"></script>
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
        var table = $('#T_account_trans').DataTable({
            dom: 'Bfrtlip',
            pageLength: 15,
            lengthMenu: [10, 15, 25, 50, 100],
            language: {
                lengthMenu: '_MENU_',
                paginate: { previous: '‹', next: '›' }
            },
            buttons: [
                'copy',
                'excel',
                'csv',
                'pdf',
                // 'print',
            ],
        });

        // Calculate and update totals
        function updateTotals() {
            var totalDrAmount = 0;
            var totalCrAmount = 0;
            var totalBalance = 0;

            table.rows().every(function () {
                var data = this.data();
                totalDrAmount += parseFloat(data[3]) ||
                0; // Assuming the DR Amount is in the 4th column
                totalCrAmount += parseFloat(data[4]) ||
                0; // Assuming the CR Amount is in the 5th column
                // Update other totals if needed

                totalBalance = totalCrAmount - totalDrAmount;
            });

            // Update footer values
            $('#totalDrAmount').text(totalDrAmount.toFixed(2));
            $('#totalCrAmount').text(totalCrAmount.toFixed(2));
            $('#totalBalance').text(totalBalance.toFixed(2));
        }

        // Initial update
        updateTotals();

        // Re-calculate totals when the table is drawn
        table.on('draw', function () {
            updateTotals();
        });
    });

</script>

<script>
    function printTablefun() {
        var divToPrint = document.getElementById("T_account_trans");
        newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
</script>


{{-- form default date set for today --}}
<script>
    var toDateInput = document.getElementById('to_date');
    if (!toDateInput.value) {
        var dateObj = new Date();
        toDateInput.value = dateObj.toISOString().slice(0, 10);
    }
</script>

</html>
