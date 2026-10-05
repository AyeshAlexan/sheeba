<x-report-print title="Customer Balance Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Customer Code</th>
                <th>Customer</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            @forelse ($customerData as $data)
            <tr>
                <td>{{ $data->Code }}</td>
                <td>{{ $data->First_name }}</td>
                <td class="rp-numeric">{{ number_format($data->total_cr_amount - $data->total_dr_amount, 2) }}</td>
            </tr>
            @php $totalAmount += $data->total_cr_amount - $data->total_dr_amount; @endphp
            @empty
            <tr><td colspan="3">No customer balances found for the selected filters.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total Balance</td>
                <td class="rp-numeric">{{ number_format($totalAmount, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
