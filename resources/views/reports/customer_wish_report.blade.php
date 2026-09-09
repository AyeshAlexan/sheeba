<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Sales Report</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background-color: #f4f6fb;
        }
        .page-wrapper {
            max-width: 1140px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem;
        }
        .page-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .page-header-bar h4 {
            font-weight: 700;
            color: #2e3a59;
            margin: 0;
        }
        .page-header-bar .text-muted {
            font-size: 0.85rem;
        }
        .search-card {
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e3e6f0;
        }
        .input-group-text {
            background-color: #fff;
            border-right: none;
        }
        .search-input {
            border-left: none;
        }
        .search-input:focus {
            border-color: #ced4da;
            box-shadow: none;
        }
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            background: #fff;
        }
        .table thead {
            background-color: #4e73df;
            color: white;
        }
        .table thead th {
            border-bottom: none;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f3f9;
            transition: background-color 0.2s ease;
        }
        .table tbody td {
            font-size: 0.9rem;
            vertical-align: middle;
        }
        .result-summary {
            font-size: 0.85rem;
            color: #6c757d;
            padding: 0.75rem 0.25rem;
        }
        .badge.bg-light {
            font-weight: 500;
            font-size: 0.8rem;
        }
        code {
            background: #f1f3f9;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            font-size: 0.85rem;
            color: #4e73df;
        }
        .items-cell {
            line-height: 1.7;
        }
        .items-cell .item-line {
            display: block;
            white-space: nowrap;
        }
        .items-cell .item-line + .item-line {
            margin-top: 2px;
            padding-top: 2px;
            border-top: 1px dashed #e3e6f0;
        }
    </style>
</head>
<body>

<div class="page-wrapper">

    <div class="page-header-bar">
        <h4><i class="fas fa-receipt me-2 text-primary"></i>Item Sales Report</h4>
        <span class="text-muted">{{ now()->format('jS F Y, h:i A') }}</span>
    </div>

    <div class="card p-4 mb-4 search-card">
        <form action="{{ url()->current() }}" method="GET" class="m-0">
            <div class="row align-items-center g-3">
                <div class="col-md-5 col-lg-4">
                   <label for="customer_nic" class="form-label fw-bold text-secondary small">Search by Code, First Name, or NIC</label>
<div class="input-group">
    <span class="input-group-text text-muted" id="search-addon">
        <i class="fas fa-search"></i>
    </span>
    <input type="text"
           id="customer_nic"
           name="customer_nic"
           class="form-control search-input"
           placeholder="Enter Code, First Name, or NIC..."
           aria-describedby="search-addon"
           value="{{ $currentNic ?? request('customer_nic') }}">
</div>
                </div>
                <div class="col-md-6 col-lg-4 d-flex align-items-end">
                    <div class="w-100 btn-group">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <a href="{{ url()->current() }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-undo me-2"></i>Clear
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-info px-4">
                        <i class="fa-solid fa-house"></i> Back
                    </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @if(($currentNic ?? request('customer_nic')))
        <div class="result-summary">
            <i class="fas fa-info-circle me-1"></i>
            Showing {{ $reportData->count() }} invoice(s) for NIC
            <strong>{{ $currentNic ?? request('customer_nic') }}</strong>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="py-3 px-4">Invoice No</th>
                    <th class="py-3">Customer NIC</th>
                    <th class="py-3">Customer Name</th>
                    <th class="py-3">Items</th>
                    <th class="py-3 px-4 text-end">Total QTY</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $row)
                    <tr>
                        <td class="py-3 px-4 fw-semibold text-primary">{{ $row->Invoice_no }}</td>
                        <td><span class="badge bg-light text-dark border p-2">{{ $row->Customer_NIC }}</span></td>
                        <td class="fw-medium">{{ $row->Customer_Name }}</td>
                        <td class="items-cell">
                            @foreach(explode('||', $row->items_combined) as $itemLine)
                                <span class="item-line fw-bold">{{ $itemLine }}</span>
                            @endforeach
                        </td>
                        <td class="py-3 px-4 text-end fw-bold">{{ $row->total_qty }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-2x mb-3 d-block text-black-50"></i>
                            No records found for this NIC.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($reportData, 'links'))
        <div class="mt-4 d-flex justify-content-center">
            {{ $reportData->links() }}
        </div>
    @endif

</div>

<!-- Bootstrap JS (needed for any dropdowns/collapse you might add later) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
