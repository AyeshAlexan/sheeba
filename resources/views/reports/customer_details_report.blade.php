@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Details Report</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        #customerDetailsTable td:nth-child(5) { min-width: 220px; white-space: normal; }
        #customerDetailsTable_wrapper .dataTables_filter { display: none; }
        #customerDetailsTable_wrapper .dataTables_length,
        #customerDetailsTable_wrapper .dataTables_info,
        #customerDetailsTable_wrapper .dataTables_paginate { display: none !important; }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header ph-flex">
                    <div class="ph-left">
                        <div class="ph-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></svg>
                        </div>
                        <div>
                            <h3 class="page-title">Customer Details Report</h3>
                            <p class="page-subtitle">Customer contact and identification details.</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-secondary" onclick="printCustomerDetailsReport()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="" method="GET" class="row g-2 mb-3 align-items-end">
                            <div class="col-md-3">
                                <label for="from_date" class="form-label mb-0 small">From Date</label>
                                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ $fromDate }}">
                            </div>
                            <div class="col-md-3">
                                <label for="to_date" class="form-label mb-0 small">To Date</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ $toDate }}">
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-filter"></i> Filter</button>
                                <a href="{{ route('get_customer_details_report') }}" class="btn btn-outline-secondary"><i class="fas fa-undo"></i> Clear</a>
                            </div>
                        </form>

                        <div class="modern-table-card">
                            <div class="table-responsive">
                                <table class="table" id="customerDetailsTable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>NIC</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Address</th>
                                            <th>Contact</th>
                                            <th>Email</th>
                                            <th>Driving License</th>
                                            <th>Passport</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customers as $customer)
                                        <tr>
                                            <td>{{ $customer->Code }}</td>
                                            <td>{{ $customer->NIC }}</td>
                                            <td>{{ trim($customer->Title . ' ' . $customer->First_name . ' ' . $customer->Middle_name . ' ' . $customer->Last_name) }}</td>
                                            <td>{{ $customer->Gender }}</td>
                                            <td>{{ $customer->Address_1 }}</td>
                                            <td>{{ $customer->Contact_1 }}</td>
                                            <td>{{ $customer->Email }}</td>
                                            <td>{{ $customer->Driving_license }}</td>
                                            <td>{{ $customer->Passport }}</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="9" class="text-center text-muted">No customers found.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="customerDetailsCustomPager"></div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="{{ asset('assets/js/dt-custom-pager.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script>
        jQuery(function ($) {
            var customerDetailsTable = $('#customerDetailsTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5'],
                pageLength: 15,
                lengthChange: false,
                language: { emptyTable: 'No customers found.' }
            });

            $('#customerDetailsTable_wrapper').addClass('dt-collapsed');
            DTCustomPager.init(customerDetailsTable, '#customerDetailsCustomPager');
        });

        function printCustomerDetailsReport() {
            var params = $('form[action=""]').serialize();
            window.open('{{ route('get_customer_details_report.print') }}?' + params, '_blank');
        }
    </script>
</body>
@endsection
</html>
