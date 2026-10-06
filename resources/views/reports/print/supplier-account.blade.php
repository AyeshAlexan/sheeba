<x-report-print title="Supplier Account Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>No</th>
                <th>Supplier</th>
                <th>Transaction</th>
                <th>DR Amount</th>
                <th>CR Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoice as $row)
            <tr>
                <td>{{ $row->dDate }}</td>
                <td>{{ $row->trance_no }}</td>
                <td>{{ $row->supplier }}</td>
                <td>{{ $row->cr_trnce_code }}</td>
                <td class="rp-numeric">{{ number_format($row->dr_amount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->cr_amount, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="6">No results found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Total</td>
                <td class="rp-numeric">{{ $totalDrAmount }}</td>
                <td class="rp-numeric">{{ $totalCrAmount }}</td>
            </tr>
            <tr>
                <td colspan="4">Balance</td>
                <td colspan="2" class="rp-numeric">{{ $totalBalance }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
