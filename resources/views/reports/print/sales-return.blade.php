<x-report-print title="Sales Return Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Date</th>
                <th>Customer NIC</th>
                <th>Customer Name</th>
                <th>Gross Amt</th>
                <th>Discount</th>
                <th>Net Amt</th>
                <th>Cash Pay</th>
                <th>Credit</th>
                <th>Cheque</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoice as $inv)
            <tr>
                <td>{{ $inv->Invoice_no }}</td>
                <td>{{ $inv->Invoice_date }}</td>
                <td>{{ $inv->Customer_NIC }}</td>
                <td>{{ $inv->Customer_Name }}</td>
                <td class="rp-numeric">{{ number_format($inv->Gross_Amount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($inv->Discount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($inv->Net_Amount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($inv->Cash_Pay, 2) }}</td>
                <td class="rp-numeric">{{ number_format($inv->Credite, 2) }}</td>
                <td class="rp-numeric">{{ number_format($inv->Cheque, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="10">No results found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Grand Total</td>
                <td class="rp-numeric">{{ $totalGrossAmount }}</td>
                <td class="rp-numeric">{{ $totalDiscount }}</td>
                <td class="rp-numeric">{{ $totalNetAmount }}</td>
                <td class="rp-numeric">{{ $totalCashPay }}</td>
                <td class="rp-numeric">{{ $totalCredite }}</td>
                <td class="rp-numeric">{{ $totalCheque }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
