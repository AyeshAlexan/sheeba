<x-report-print title="Supplier Balance Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Supplier Code</th>
                <th>Supplier</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            @forelse ($supplierData as $data)
            <tr>
                <td>{{ $data->Code }}</td>
                <td>{{ $data->Name }}</td>
                <td class="rp-numeric">{{ number_format($data->total_dr_amount - $data->total_cr_amount, 2) }}</td>
            </tr>
            @php $totalAmount += $data->total_dr_amount - $data->total_cr_amount; @endphp
            @empty
            <tr><td colspan="3">No supplier balances found for the selected filters.</td></tr>
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
