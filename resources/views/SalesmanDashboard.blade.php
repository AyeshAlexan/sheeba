{{-- @extends('layouts.app') --}}
@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Dashboard - SalesMan </title>

</head>

<body class="nk-body bg-lighter npc-default has-sidebar no-touch nk-nio-theme">
    <div class="main-wrapper">

        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">

                     {{-- Showing Pawning Total and Receipt Count --}}
                    <div class="col-xl-4 col-sm-6 col-12">
                        <div class="shadow p-3 mb-5 bg-body-tertiary rounded">
                            <div class="card-body">
                                <div class="dash-widget-header">
                                    <span class="dash-widget-icon bg-1">
                                        <i class="fas fa-dollar-sign"></i>
                                    </span>
                                    <div class="dash-count">
                                        <div class="dash-title">Total Sales</div>
                                        <div class="dash-title"></div>
                                        <div class="dash-counts">
                                            <div class="dash-title">Sales Invoice:</div>
                                        <h5></h5>
                                        </div>

                                    </div>
                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar bg-5" role="progressbar" style="width: 75%"
                                        aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                                   {{-- Showing Redeem Total and Receipt Count --}}
                    <div class="col-xl-4 col-sm-6 col-12">
                        <div class="shadow p-3 mb-5 bg-body-tertiary rounded">
                            <div class="card-body">
                                <div class="dash-widget-header">
                                    <span class="dash-widget-icon bg-2">
                                        <i class="fas fa-shopping-cart"></i>
                                    </span>
                                    <div class="dash-count">
                                        <div class="dash-title">Total Puruchases</div>
                                        <div class="dash-title"></div>
                                        <div class="dash-counts">
                                            <div class="dash-title">Puruchase Invoice:</div>
                                        <h5></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar bg-6" role="progressbar" style="width: 65%"
                                        aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-sm-6 col-12">
                        <div class="shadow p-3 mb-5 bg-body-tertiary rounded">
                            <div class="card-body">
                                <div class="dash-widget-header">
                                    <span class="dash-widget-icon bg-2">
                                        <i class="fas fa-shopping-cart"></i>
                                    </span>
                                    <div class="dash-count">
                                        <div class="dash-title">Total Stocking</div>
                                        <div class="dash-title"></div>
                                        <div class="dash-counts">
                                            <div class="dash-title"></div>
                                        <h5></h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress progress-sm mt-3">
                                    <div class="progress-bar bg-6" role="progressbar" style="width: 65%"
                                        aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-7 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Sales & Repair Analytics</h5>
                                    <div class="dropdown">
                                        <button class="btn btn-white btn-sm dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            Monthly
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item">Daliy</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item">Weekly</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item">Monthly</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item">Yearly</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between flex-wrap flex-md-nowrap">
                                    <div class="w-md-100 d-flex align-items-center mb-3 flex-wrap flex-md-nowrap">
                                        <div>
                                            <span>Sales Total </span>
                                            <p class="h5 text-secondary me-5">Rs:</p>
                                        </div>
                                        <div>

                                            <span>Repair Total </span>
                                            <p class="h5 text-info me-5">Rs:</p>
                                        </div>
                                        <div>
                                            <span>Repair</span>
                                            <p class="h5 text-success me-5">Rs:</p>
                                        </div>

                                    </div>
                                </div>
                                <div id="sales_chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="card-title">Sales & Repair Analytics</h6>
                                    <div class="dropdown">
                                        <button class="btn btn-white btn-sm dropdown-toggle" type="button"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            Monthly
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item">Daliy</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item">Weekly</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item">Monthly</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item">Yearly</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="invoice_chart"></div>
                                <div class="text-center text-muted">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="mt-4">
                                                <p class="mb-2 text-truncate"><i
                                                        class="fas fa-circle text-primary me-1"></i> Sales</p>
                                                <h5></h5>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mt-4">
                                                <p class="mb-2 text-truncate"><i
                                                        class="fas fa-circle text-success me-1"></i>Repair</p>
                                                <h5></h5>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mt-4">
                                                <p class="mb-2 text-truncate"><i
                                                        class="fas fa-circle text-danger me-1"></i> Cash</p>
                                                <h5></h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title">Cash payment</h5>
                                    </div>
                                    <div class="col-auto">
                                        <a href="invoices.html" class="btn-right btn btn-sm btn-outline-primary">
                                            View All
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="progress progress-md rounded-pill mb-3">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 56%"
                                            aria-valuenow="56" aria-valuemin="0" aria-valuemax="100"></div>
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 10%"
                                            aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 14%"
                                            aria-valuenow="14" aria-valuemin="0" aria-valuemax="100"></div>
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 20%"
                                            aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-auto">
                                            <i class="fas fa-circle text-success me-1"></i> Paid
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-circle text-warning me-1"></i> Unpaid
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-circle text-danger me-1"></i> Overdue
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-circle text-info me-1"></i> Draft
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-stripped table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No oF Receipt</th>
                                                <th>Customer NIC</th>
                                                <th>Customer Name</th>
                                                <th>Customer Address</th>
                                                <th>Customer Phone</th>
                                                <th>Receipt Type</th>
                                                <th>Receipt Number</th>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Total Amount</th>
                                                <th>Interest</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title">Sales Payment</h5>
                                    </div>
                                    <div class="col-auto">
                                        <a href="estimates.html" class="btn-right btn btn-sm btn-outline-primary">
                                            View All
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="progress progress-md rounded-pill mb-3">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 39%"
                                            aria-valuenow="39" aria-valuemin="0" aria-valuemax="100"></div>
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 35%"
                                            aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 26%"
                                            aria-valuenow="26" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-auto">
                                            <i class="fas fa-circle text-success me-1"></i> Sent
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-circle text-warning me-1"></i> Draft
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-circle text-danger me-1"></i> Expired
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Receipt_Number</th>
                                                <th>Date</th>
                                                <th>Number</th>
                                                <th>Original_Pawn_Amount</th>
                                                <th>Payable_Pawn_Amount</th>
                                                <th>Paid_Interest</th>
                                                <th>Payable_Interest</th>
                                                <th>Stamp_Fee</th>
                                                <th>Document_Charges</th>
                                                <th>Advance_Balance</th>
                                                <th>Discount</th>
                                                <th>Payable_Total</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
@endsection