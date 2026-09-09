@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Salesman Wise Invoice Report</h4>
    <p><strong>From:</strong> {{ $fromDate }} | <strong>To:</strong> {{ $toDate }}</p>
    @if($salesman)
        <p><strong>Salesman:</strong> {{ $salesman }}</p>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Route</th>
                <th>Salesman</th>
                <th>Item Code</th>
                <th>Item Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Net Value</th>
                <th>Gross Amount</th>
                <th>Net Amount</th>
                <th>Cash</th>
                <th>Credit</th>
                <th>Cheque</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $row)
                <tr>
                    <td>{{ $row->Invoice_no }}</td>
                    <td>{{ $row->Invoice_date }}</td>
                    <td>{{ $row->Customer_Name }}</td>
                    <td>{{ $row->Customer_Phone }}</td>
                    <td>{{ $row->Route }}</td>
                    <td>{{ $row->Salesmen }}</td>
                    <td>{{ $row->Item_code }}</td>
                    <td>{{ $row->Item_description }}</td>
                    <td>{{ $row->QTY }}</td>
                    <td>{{ number_format($row->Unit_price, 2) }}</td>
                    <td>{{ number_format($row->Item_Discount, 2) }}</td>
                    <td>{{ number_format($row->Net_value, 2) }}</td>
                    <td>{{ number_format($row->Gross_Amount, 2) }}</td>
                    <td>{{ number_format($row->Net_Amount, 2) }}</td>
                    <td>{{ number_format($row->Cash_Pay, 2) }}</td>
                    <td>{{ number_format($row->Credite, 2) }}</td>
                    <td>{{ number_format($row->Cheque, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="17" class="text-center">No records found for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
