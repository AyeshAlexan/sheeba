<x-report-print title="Customer Account" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>No</th>
                <th>Customer Code</th>
                <th>Customer Name</th>
                <th>Transaction</th>
                <th>DR Amount</th>
                <th>CR Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice as $row)
            @php $customerName = $Customerdata->firstWhere('Code', $row->customer)->First_name ?? ''; @endphp
            <tr>
                <td>{{ $row->dDate }}</td>
                <td>{{ $row->trance_no }}</td>
                <td>{{ $row->customer }}</td>
                <td>{{ $customerName }}</td>
                <td>{{ $row->cr_trnce_code }}</td>
                <td class="rp-numeric">{{ number_format($row->dr_amount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->cr_amount, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="7">No transactions found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">Total</td>
                <td class="rp-numeric">{{ $totalDrAmount }}</td>
                <td class="rp-numeric">{{ $totalCrAmount }}</td>
            </tr>
            <tr>
                <td colspan="6">Balance Total</td>
                <td class="rp-numeric">{{ $totalBalance }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
