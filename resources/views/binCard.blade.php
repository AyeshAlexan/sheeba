<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bin Card</title>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid rgb(8, 8, 8);
            padding: 5px;
        }

        .styled-table {
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            font-family: sans-serif;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        .styled-table thead tr {
            background-color: #009879;
            color: #ffffff;
            text-align: left;
        }

        .styled-table th, .styled-table td {
            padding: 12px 15px;
        }

        .styled-table tbody tr {
            border-bottom: 1px solid #dddddd;
        }

        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }

        .styled-table tbody tr:last-of-type {
            border-bottom: 2px solid #009879;
        }
    </style>
</head>

<body>
    <div class="d-flex justify-content-center profile-container">
        <div class="col-md-6 text-center sort-profile" id="sort-profile">
            <div class="row">
                <div class="col-md-6 text-center"><br />

                    <div>
                        <h2 style="text-align:center;"><b>BIN CARD</b></h2>
                        <hr/>

                        @if ($errors->any())
                        <div style="text-align: center; color:red">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div style="display: flex; text-align: center;">
                            <div style="flex: 20%;">
                                <button class="d-inline p-2 text-bg-primary">
                                    <a href="{{ route('home') }}" style="color:white; text-decoration:none;">Back</a>
                                </button>
                            </div>

                            <div style="flex: 60%; align-content: center;">
                                <form action="{{ route('get_bin_card_report') }}" method="GET">
                                    @csrf
                                    <label for="from_date">Date From :</label>
                                    <input type="date" name="from_date" id="from_date">
                                    &nbsp;&nbsp;

                                    <label for="date">To :</label>
                                    <input type="date" name="to_date" id="date"><br><br>

                                    <!-- Item Code Selection -->
                                    <label for="item_code">Item Code :</label>
                                    <input type="text" class="form-control d-inline-block" style="width:auto; display:inline-block;"
                                           id="item_code" name="item_code" list="item_code_list"
                                           autocomplete="off" placeholder="Search item code">
                                    <datalist id="item_code_list">
                                        @foreach($allItemData as $itemData)
                                            <option value="{{ $itemData->Item_code }}"></option>
                                        @endforeach
                                    </datalist>

                                    &nbsp;&nbsp;

                                    <!-- Item Description Selection -->
                                    <label for="item_description">Item Description :</label>
                                    <input type="text" class="form-control d-inline-block" style="width:auto; display:inline-block;"
                                           id="item_description" name="item_description" list="item_description_list"
                                           autocomplete="off" placeholder="Search item description">
                                    <datalist id="item_description_list">
                                        @foreach($allItemData as $itemData)
                                            <option value="{{ $itemData->Item_description }}"></option>
                                        @endforeach
                                    </datalist>
                                    <br><br>

                                    <button type="submit" id="submit_1">Submit</button>
                                </form>
                            </div>

                            <div style="flex: 20%; align-content: center;">
                                <button onclick="printTablefun()">Print</button>
                            </div>
                        </div>

                        <!-- Data Table -->
                        <table class="styled-table display" id="t_item_movements" style="background-color: transparent; border:1px solid rgb(7, 7, 7); margin-top:15px; width:80%; text-align: center; margin-left: auto; margin-right: auto;">
                            @if(isset($stockDetails) && $stockDetails != null)
                            <caption style="font-size: 18px; font-weight: bold;">
                                Item: {{ $itemName }}&nbsp;&nbsp;&nbsp;&nbsp;From: {{ $fromDate }}&nbsp;&nbsp;To: {{ $toDate }}
                            </caption>
                            @endif

                            <thead>
                                <tr class="styled-table">
                                    <th style="width:12%">DATE</th>
                                    <th style="width:5%">TR_NO</th>
                                    <th style="width:5%">INVOICE NO</th>
                                    <th style="width:10%">Transaction Type</th>
                                    <th style="width:20%">ITEM</th>
                                    <th style="width:10%">CUST. CODE</th>
                                    <th style="width:15%">CUSTOMER NAME</th>
                                    <th style="width:8%">&nbsp;&nbsp;IN&nbsp;&nbsp;</th>
                                    <th style="width:8%">&nbsp;&nbsp;OUT&nbsp;&nbsp;</th>
                                    <th style="width:8%">QUANTITY</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(isset($stockDetails) && $stockDetails != null)
                                    @foreach ($stockDetails as $data)
                                    <tr>
                                        <td>{{ $data->dDate }}</td>
                                        <td>{{ $data->trans_no }}</td>
                                        <td>{{ $data->invoice_no }}</td>
                                         <td>{{ $data->trans_code }}</td>
                                        <td>{{ $data->item_name }}</td>
                                        <td>{{ $data->customer_code }}</td>
                                        <td>{{ $data->customer_name }}</td>
                                        <td>{{ $data->qun_in }}</td>
                                        <td>{{ $data->qun_out }}</td>
                                        <td>
                                            @if($data->qun_in > $data->qun_out)
                                                {{ $data->qun_in }}
                                            @else
                                                - {{ $data->qun_out }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="7"><b> TOTAL QUANTITY</b></td>
                                    <td><b>{{ $quain ?? 0 }}</b></td>
                                    <td><b>-{{ $quaout ?? 0 }}</b></td>
                                    <td><b>{{ $balance ?? 0 }}</b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

<script>
    // Set default date for To-Date input
    document.getElementById('date').valueAsDate = new Date();

    // Global AJAX CSRF Setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Lookup map built from allItemData for two-way code <-> description sync
    const itemMap = @json($allItemData->map(function ($item) {
        return [
            'code' => $item->Item_code,
            'description' => $item->Item_description,
        ];
    }));

    $(document).ready(function () {
        // DataTables Initialization
        $('#t_item_movements').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
            pageLength: 1000 // Set default records per page to 25
        });

        // Item Code -> Item Description
        $('#item_code').on('input', function () {
            const typedCode = $(this).val();
            const match = itemMap.find(i => i.code === typedCode);

            if (match) {
                $('#item_description').val(match.description);
            } else if (typedCode === '') {
                $('#item_description').val('');
            }
        });

        // Item Description -> Item Code
        $('#item_description').on('input', function () {
            const typedDesc = $(this).val();
            const match = itemMap.find(i => i.description === typedDesc);

            if (match) {
                $('#item_code').val(match.code);
            } else if (typedDesc === '') {
                $('#item_code').val('');
            }
        });
    });

    // Print Table Function
    function printTablefun() {
        var divToPrint = document.getElementById("t_item_movements");
        var newWin = window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.print();
        newWin.close();
    }
</script>
</body>
</html>