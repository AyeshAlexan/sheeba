<x-report-print :title="'Bank Statement — ' . $bank->bank_name" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <p style="margin-bottom:10px; color:#667085; font-size:12px;">
        {{ $bank->branch }} · A/C {{ $bank->account_no }}
    </p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Status</th>
                <th>DR Amount</th>
                <th>CR Amount</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $fromDate ?: optional($bank->created_at)->format('Y-m-d') ?? '-' }}</td>
                <td>Opening Balance</td>
                <td></td>
                <td class="rp-numeric">{{ number_format($periodOpening, 2) }}</td>
                <td class="rp-numeric">0.00</td>
                <td class="rp-numeric">{{ number_format($periodOpening, 2) }}</td>
            </tr>
            @foreach($movements as $row)
            <tr>
                <td>{{ $row['date'] }}</td>
                <td>{{ $row['description'] }}</td>
                <td>{{ $row['status_label'] }}</td>
                <td class="rp-numeric">{{ number_format($row['dr'], 2) }}</td>
                <td class="rp-numeric">{{ number_format($row['cr'], 2) }}</td>
                <td class="rp-numeric">{{ number_format($row['balance'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td class="rp-numeric">{{ number_format($sumDr, 2) }}</td>
                <td class="rp-numeric">{{ number_format($sumCr, 2) }}</td>
                <td></td>
            </tr>
            <tr>
                <td colspan="5">Closing Balance</td>
                <td class="rp-numeric">{{ number_format($closingBalance, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
