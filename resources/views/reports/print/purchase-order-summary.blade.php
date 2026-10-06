<x-report-print title="Purchase Order Summary Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Supplier Code</th>
                <th>Supplier Name</th>
                <th>Supplier Phone</th>
                <th>Gross Amount</th>
                <th>Discount</th>
                <th>Net Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoice as $row)
            <tr>
                <td>{{ $row->Invoice_no }}</td>
                <td>{{ $row->Invoice_date }}</td>
                <td>{{ $row->Supplier_Code }}</td>
                <td>{{ $row->Supplier_Name }}</td>
                <td>{{ $row->Supplier_Phone }}</td>
                <td class="rp-numeric">{{ number_format($row->Gross_Amount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->Discount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->Net_Amount, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="8">No results found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">Total</td>
                <td class="rp-numeric">{{ $totalGrossAmount }}</td>
                <td class="rp-numeric">{{ $totalDiscount }}</td>
                <td class="rp-numeric">{{ $totalNetAmount }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
