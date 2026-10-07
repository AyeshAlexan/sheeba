@if($invoice->count() > 0)
{{-- <h2>Purchases Report</h2> --}}





@else
<p>No results found.</p>

@endif



<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
<meta charset="UTF-">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
{{-- <div><button onClick="window.print()">Print --}}

</button></div>




<title>Hirepurchase Details Report</title>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('css/reports-modern.css') }}">

</head>

<body>

    <div class="report-page">
        <div class="report-card">
            <div class="report-topbar">
                <h1 class="report-title"><i class="fa-solid fa-file-lines"></i> Hirepurchase Details Report</h1>
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
        <table class="display modern-table" id="t_hire_purchase_details">
                <thead class="styled-table">
                    <th>Invoice_no</th>
                    <th>Invoice_date</th>
                    <th>MODEL</th>
                    <th>Item_description</th>
                    <th>Unit Price</th>
                    <th>QTY</th>
                    <th>Discount</th>
                    <th>Net_value</th>
                    <th>OC</th>
                    <th>BC</th>
                </thead>
            <tbody>
            @foreach ( $invoice as $key=>$invoice)
                <tr>
                    <td>{{$invoice->invoice_no}}</td>
                    <td>{{$invoice->invoice_date}}</td>
                    <td>{{$invoice->item_code}}</td>
                    <td>{{$invoice->item_description}}</td>
                    <td>{{$invoice->unit_price}}</td>
                    <td>{{$invoice->qty}}</td>
                    <td>{{$invoice->discount}}</td>
                    <td>{{$invoice->net_value}}</td>
                    <td>{{$invoice->oc}}</td>
                    <td>{{$invoice->bc}}</td>


                </tr>
            @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="5" rowspan="2"><strong>Total</strong></td>
                    <td>QTY:</td>
                    <td></td>
                    {{-- <td>Discount:</td> --}}
                    <td>Net_value:</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>

                        <td><strong>{{$totalGrossAmount}}</strong></td>
                        <td><strong>{{$totalUnit}}</strong></td>
                        {{-- <td><strong>{{$totalDiscount}}</strong></td> --}}
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
    $('#t_hire_purchase_details').DataTable( //database table name
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

       var divToPrint=document.getElementById("t_hire_purchase_details");
       newWin= window.open("");
       newWin.document.write(divToPrint.outerHTML);
       newWin.print();
       newWin.close();
    }

    function printtbodyfun()
    {
       var divToPrint=document.getElementById("t_hire_purchase_details");
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
