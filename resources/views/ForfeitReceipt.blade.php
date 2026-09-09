@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forfeit Receipt</title>
</head>
<body>

    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                {{-- ......................Top Section No and Date.............................. --}}
                <div class="row">
                    <div class="col">
                        <div class="card shadow">
                            <div class="col-md-9">
                                <h4 class="card-title m-3">Forfeit Receipt</h4>
                            </div>
                            <hr style="height: 5px; color: blue;">
                            <div class="card-body">
                                <form action="" method="post">
                                    @csrf
                                    <div class="row ">
                                        <div class="col">
                                            <label for="receipt_type">Branch</label>
                                            <select class="select form-control " name="" aria-hidden="true">
                                                <option value="">Please Select</option>
                                                <option value="Suppliers" >Suppliers</option>
                                                <option value="Customers">Customers</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="receipt_type">Receipt Type</label>
                                            <select class="select form-control " name="" aria-hidden="true">
                                                <option value="">Please Select</option>
                                                <option value="Suppliers" >Suppliers</option>
                                                <option value="Customers">Customers</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <label for="receipt_type">Date From</label>
                                            <input type="date" name="date" id="date" class="form-control">
                                        </div>
                                        <div class="col">
                                            <label for="receipt_type">Date To</label>
                                            <input type="date" name="date" id="date" class="form-control">
                                        </div>
                                        <div class="col">
                                            <button type="submit" class="btn btn-primary btn-lg ">Get Receipts</button>
                                        </div>
                                    </form>
                            </div>
                                    <div class="row">
                                        <h5>Recent Forfeit </h5>
                                    </div>
                                    <table class="table table-striped table-bordered" id="dynamicAdded">
                                        <thead>

                                        </thead>
                                        <tbody >
                                            <tr>
                                                <td>
                                                    <div class="row">
                                                                <div class="col-md-10">
                                                                    <h6>From 2022.10.10 to 2025.12.10</h6>
                                                                    <h4>#2 - A1 </h4>
                                                                </div>
                                                                <div class="col-md-2 text-right">
                                                                    <Button type="button" class="btn-primary"><i class="fa fa-print" aria-hidden="true"></i> </Button>
                                                                    <Button type="button" class="btn-primary"><i class="fa fa-eye" aria-hidden="true"></i> </Button>
                                                                </div>
                                                                <hr>
                                                                <div class="col-md-8">
                                                                    <p>Pujapitiya</p>
                                                                </div>
                                                                <div class="col-md-4 text-right">
                                                                    <p>Administrator 2023.07.18 08:19:20</p>
                                                                </div>

                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="row" id="">
                                                                <div class="col-md-10">
                                                                    <h6>From 2020.10.10 to 2020.12.10</h6>
                                                                    <h4>#1- A1 </h4>
                                                                </div>
                                                                <div class="col-md-2 text-right">
                                                                    <Button type="button" class="btn-primary"><i class="fa fa-print" aria-hidden="true"></i> </Button>
                                                                    <Button type="button" class="btn-primary"><i class="fa fa-eye" aria-hidden="true"></i> </Button>
                                                                    <button type="button" class="btn btn-outline-danger" id="remove-input-field"> Delete </button>
                                                                </div>
                                                                <hr>
                                                                <div class="col-md-8">
                                                                    <p>Pujapitiya</p>
                                                                </div>
                                                                <div class="col-md-4 text-right">
                                                                    <p>Administrator 2020.07.18 08:19:20</p>
                                                                </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <script>
        $(document).on('click', '#remove-input-field', function () {
            $("#dynamicAdded").parents('tr').remove();
        });
    </script>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="assets/plugins/select2/js/select2.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/datatables.min.js"></script>
</body>
</html>
@endsection
