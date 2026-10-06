<x-report-print title="Supplier Cheque Payment Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Transfer No</th>
                <th>Release Date</th>
                <th>Transfer Type</th>
                <th>Bank</th>
                <th>Branch Code</th>
                <th>Cheque No</th>
                <th>Account No</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            @forelse ($receipts as $invoice)
            <tr>
                <td>{{ $invoice->trans_no }}</td>
                <td>{{ $invoice->release_date }}</td>
                <td>{{ $invoice->trans_type }}</td>
                <td>{{ $invoice->bank }}</td>
                <td>{{ $invoice->branch_code }}</td>
                <td>{{ $invoice->cheques_no }}</td>
                <td>{{ $invoice->acc_no }}</td>
                <td class="rp-numeric">{{ number_format($invoice->amount, 2) }}</td>
            </tr>
            @php $totalAmount += $invoice->amount; @endphp
            @empty
            <tr><td colspan="8">No results found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7">Total Balance</td>
                <td class="rp-numeric">{{ number_format($totalAmount, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
