<x-report-print title="Advance Payment Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Customer Name</th>
                <th>Customer Code</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice as $row)
            <tr>
                <td>{{ $row->invoice_no }}</td>
                <td>{{ $row->date }}</td>
                <td>{{ $row->customer_name }}</td>
                <td>{{ $row->cus_code }}</td>
                <td>{{ $row->description }}</td>
                <td class="rp-numeric">{{ number_format($row->amount, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="6">No advance payments found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">Total Amount</td>
                <td class="rp-numeric">{{ $amount }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
