<x-report-print title="Customer Cheque Payment Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel" :landscape="true">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Transfer No</th>
                <th>Customer</th>
                <th>Release Date</th>
                <th>Type</th>
                <th>Bank</th>
                <th>Branch</th>
                <th>Cheque No</th>
                <th>Account No</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $rowNum = 1; $totalAmount = 0; @endphp
            @forelse($receipts as $invoice)
            <tr>
                <td>{{ $rowNum++ }}</td>
                <td>{{ $invoice->trans_no }}</td>
                <td>{{ $invoice->customer }}</td>
                <td>{{ $invoice->release_date }}</td>
                <td>{{ $invoice->trans_type }}</td>
                <td>{{ $invoice->bank }}</td>
                <td>{{ $invoice->branch_code }}</td>
                <td>{{ $invoice->cheques_no }}</td>
                <td>{{ $invoice->acc_no }}</td>
                <td class="rp-numeric">{{ number_format($invoice->amount, 2) }}</td>
                <td>
                    @if($invoice->cheque_status === 'deposit') Deposited
                    @elseif($invoice->cheque_status === 'return') Returned
                    @else Pending
                    @endif
                </td>
            </tr>
            @php $totalAmount += $invoice->amount; @endphp
            @empty
            <tr><td colspan="11">No cheque payments found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9">Total Balance</td>
                <td class="rp-numeric">{{ number_format($totalAmount, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
