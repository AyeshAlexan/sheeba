<x-report-print title="Opening Hire Purchase Details Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel" :landscape="true">
    <table>
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Item Code</th>
                <th>Item Description</th>
                <th>Unit Price</th>
                <th>QTY</th>
                <th>Discount</th>
                <th>Net Value</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoice as $row)
            <tr>
                <td>{{ $row->invoice_no }}</td>
                <td>{{ $row->invoice_date }}</td>
                <td>{{ $row->item_code }}</td>
                <td>{{ $row->item_description }}</td>
                <td class="rp-numeric">{{ number_format($row->unit_price, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->qty, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->discount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->net_value, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="8">No results found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">Total</td>
                <td class="rp-numeric">{{ $totalGrossAmount }}</td>
                <td></td>
                <td class="rp-numeric">{{ $totalNetAmount }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
