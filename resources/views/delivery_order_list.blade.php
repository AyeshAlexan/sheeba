@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Delivery Order List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .do-list-card { background:#fff; border:1px solid #dfeaf6; border-radius:16px; margin-top:18px; padding:18px; box-shadow:0 10px 25px rgba(42,92,171,.04); }
        .do-list-filter .form-control { min-height:42px; border:1px solid #d7e3f1; border-radius:10px; }
        .do-list-table { width:100%; border-collapse:separate; border-spacing:0; border:1px solid #d9e3ee; border-radius:12px; overflow:hidden; }
        .do-list-table thead th { background:#2d6ce9; color:#fff; font-size:12px; font-weight:700; padding:12px 10px; text-align:center; }
        .do-list-table tbody td { padding:10px 8px; text-align:center; border-bottom:1px solid #edf1f5; vertical-align:middle; }
        .do-list-table tbody tr:nth-child(even) { background:#f5f9ff; }
        .do-list-empty { text-align:center; padding:40px 10px; color:#8a97ab; font-size:14px; }
    </style>
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header ph-flex">
                    <div class="ph-left">
                        <div class="ph-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h15v13H3z"/><path d="M16 8h4l2 4v4h-6"/><circle cx="7.5" cy="19.5" r="1.5"/><circle cx="17.5" cy="19.5" r="1.5"/></svg>
                        </div>
                        <div>
                            <h3 class="page-title">Delivery Order List</h3>
                            <p class="page-subtitle">Browse and reprint past delivery orders</p>
                        </div>
                    </div>
                    <a href="{{ route('delivery_orders') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Delivery Order
                    </a>
                </div>

                <div class="do-list-card">
                    <form method="get" class="row do-list-filter mb-3">
                        <div class="col-md-3 form-group">
                            <label>From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label>To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                        </div>
                        <div class="col-md-3 form-group d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>

                    @if ($deliveryOrders->count() > 0)
                        <table class="do-list-table">
                            <thead>
                                <tr>
                                    <th>DO No</th>
                                    <th>DO Date</th>
                                    <th>Invoice No</th>
                                    <th>Customer</th>
                                    <th>Net Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deliveryOrders as $do)
                                <tr>
                                    <td>{{ $do->DO_no }}</td>
                                    <td>{{ $do->DO_date }}</td>
                                    <td>{{ $do->Invoice_no }}</td>
                                    <td>{{ $do->Customer_Name }}</td>
                                    <td>{{ number_format($do->Net_Amount, 2) }}</td>
                                    <td>
                                        <a href="{{ route('sales.invoice.print', ['invoice_no' => $do->Invoice_no, 'branch_code' => $do->BC, 'print_type' => 'normal']) }}" class="btn btn-sm btn-outline-secondary" target="_blank">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $deliveryOrders->links() }}
                        </div>
                    @else
                        <div class="do-list-empty">
                            <i class="fas fa-truck" style="font-size:28px; display:block; margin-bottom:10px; color:#c6d4e6;"></i>
                            No delivery orders found{{ ($fromDate && $toDate) ? ' for this date range' : ' yet' }}.
                            <div class="mt-2">
                                <a href="{{ route('delivery_orders') }}">Create your first Delivery Order</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>

@endsection
