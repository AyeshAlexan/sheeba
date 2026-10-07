@if($invoice->count() > 0)
{{-- <h2>Purchases Report</h2> --}}





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


<title>Purchases Summary Report</title>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('css/reports-modern.css') }}">

</head>

<body>

    <div class="report-page">
        <div class="report-card">
            <div class="report-topbar">
                <h1 class="report-title"><i class="fa-solid fa-file-lines"></i> Purchases Summary Report</h1>
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
                </div>
            </form>

            <div class="table-scroll">
        <table class="display modern-table" id="t_purchases_sums">
                <thead class="styled-table">
                    <th>Invoice No</th>
                    <th>Purchase No</th>
                    <th>Invoice date</th>
                    <th>Customer NIC</th>
                    <th>Customer Name</th>
                    <th>Gross Amount</th>
                    <th>Discount</th>
                    <th>Net Amount</th>
                    <th>Operator</th>
                    <th>Branch</th>
                </thead>
            <tbody>
            @foreach ( $invoice as $key=>$invoice)
                <tr>
                    <td>{{$invoice->Invoice_no}}</td>
                     <td>{{$invoice->Ref_no}}</td>
                    <td>{{$invoice->Invoice_date}}</td>
                    <td>{{$invoice->Customer_NIC}}</td>
                    <td>{{$invoice->Customer_Name}}</td>
                    <td>{{$invoice->Gross_Amount}}</td>
                    <td>{{$invoice->Discount}}</td>
                    <td>{{$invoice->Net_Amount}}</td>
                    <td>{{$invoice->OC}}</td>
                    <td>{{$invoice->BC}}</td>
                </tr>
            @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="5" rowspan="2"><strong>Total</strong></td>
                    <td><strong>{{$totalGrossAmount}}</strong></td>
                    <td><strong>{{$totalDiscount}}</strong></td>
                    <td><strong>{{$totalNetAmount}}</strong></td>
                    <td></td>
                    <td></td>
                </tr>
               
            </tfoot>
        </table>
            </div>
        </div>
    </div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('js/reports-modern.js') }}"></script>

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
    $('#t_purchases_sums').DataTable( //database table name
        {
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
                'print',


            ],
        }
    );

});
</script>




<script>
    function printTablefun()
    {

       var divToPrint=document.getElementById("t_purchases_sums");
       newWin= window.open("");
       newWin.document.write(divToPrint.outerHTML);
       newWin.print();
       newWin.close();
    }

    function printtbodyfun()
    {
       var divToPrint=document.getElementById("t_purchases_sums");
       newWin= window.open("");
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
