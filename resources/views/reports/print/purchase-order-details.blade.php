<x-report-print title="Purchase Order Details Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Item Category</th>
                <th>Item Code</th>
                <th>Item Description</th>
                <th>QTY</th>
                <th>Unit Price</th>
                <th>Net Value</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoice as $row)
            <tr>
                <td>{{ $row->Invoice_no }}</td>
                <td>{{ $row->Invoice_date }}</td>
                <td>{{ $row->Item_category }}</td>
                <td>{{ $row->Item_code }}</td>
                <td>{{ $row->Item_description }}</td>
                <td class="rp-numeric">{{ number_format($row->QTY, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->Unit_price, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->Net_value, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="8">No results found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">Total</td>
                <td class="rp-numeric">{{ $totalGrossAmount }}</td>
                <td class="rp-numeric">{{ $totalUnit }}</td>
                <td class="rp-numeric">{{ $totalNetAmount }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
