<x-report-print title="Cash In Hand Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>No</th>
                <th>Transaction</th>
                <th>DR Amount</th>
                <th>CR Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $fromDate }}</td>
                <td>-</td>
                <td>Opening Balance</td>
                <td class="rp-numeric">{{ $openingBalanceFmt }}</td>
                <td class="rp-numeric">0.00</td>
            </tr>
            @foreach ($invoice as $inv)
            <tr>
                <td>{{ $inv->Ddate }}</td>
                <td>{{ $inv->trance_no }}</td>
                <td>{{ $inv->trance_type }}</td>
                <td class="rp-numeric">{{ number_format($inv->dr_amount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($inv->cr_amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total (incl. Opening Balance)</td>
                <td class="rp-numeric">{{ $totalDrAmount }}</td>
                <td class="rp-numeric">{{ $totalCrAmount }}</td>
            </tr>
            <tr>
                <td colspan="3">Balance (DR − CR)</td>
                <td colspan="2" class="rp-numeric">{{ $totalBalance }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
