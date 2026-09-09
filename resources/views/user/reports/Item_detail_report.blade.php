<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Item Details Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- DataTables & Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>

    <div class="container my-4">
        <h3 class="text-center">Item Details Report</h3>

        <!-- Optional: Custom Print Button -->
        <div class="text-end mb-2">
            <button class="btn btn-primary" onclick="printTablefun()">Print Table</button>
        </div>

        <!-- Receipt Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered display nowrap" id="receiptTable" style="width:100%">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Item Code</th>
                        <th>Item Description</th>
                        <th>Purchase Price</th>
                        <th>Wholesale Price</th>
                        <th>Selling Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice as $key => $item)
                        <tr>
                            <td></td> <!-- Will be filled by DataTables -->
                            <td>{{ $item->Bar_code }}</td>
                            <td>{{ $item->Item_description }}</td>
                            <td>{{ $item->purchasePrice }}</td>
                            <td>{{ $item->wholesaleprice }}</td>
                            <td>{{ $item->creditprice }}</td>
                            
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
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'pdfHtml5',
                    'print'
                ],
                scrollX: true,
                pageLength: 1000,
                columnDefs: [{
                    targets: 0,
                    searchable: false,
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                }]
            });
        });

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