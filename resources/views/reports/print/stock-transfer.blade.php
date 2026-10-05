<x-report-print title="Stock Transfer Report" :fromDate="$fromDate" :toDate="$toDate">
    <table>
        <thead>
            <tr>
                <th>Transaction Date</th>
                <th>Transaction No</th>
                <th>Transaction Type</th>
                <th>Item Code</th>
                <th>Qty In</th>
                <th>Qty Out</th>
                <th>Store</th>
                <th>From Store</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recipts as $receipts)
            <tr>
                <td>{{ $receipts->dDate }}</td>
                <td>{{ $receipts->trans_no }}</td>
                <td>{{ $receipts->trans_code }}</td>
                <td>{{ $receipts->item_code }}</td>
                <td>{{ $receipts->qun_in }}</td>
                <td>{{ $receipts->qun_out }}</td>
                <td>{{ $receipts->storse_id }}</td>
                <td>{{ $receipts->From_store }}</td>
            </tr>
            @empty
            <tr><td colspan="8">No stock transfers found.</td></tr>
            @endforelse
        </tbody>
        @if($recipts->count())
        <tfoot>
            <tr>
                <td colspan="4">Total</td>
                <td>{{ number_format($recipts->sum('qun_in'), 2) }}</td>
                <td>{{ number_format($recipts->sum('qun_out'), 2) }}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="4">Total Balance</td>
                <td colspan="2">{{ number_format($recipts->sum('qun_in') - $recipts->sum('qun_out'), 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>
</x-report-print>
