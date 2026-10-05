<x-report-print title="Customer Wish Sale" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel" :landscape="true">
    <table>
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Customer NIC</th>
                <th>Customer Name</th>
                <th>Route</th>
                <th>Salesman</th>
                <th>Gross Amount</th>
                <th>Discount</th>
                <th>Net Amount</th>
                <th>Cash Pay</th>
                <th>Credit</th>
                <th>Cheque</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice as $row)
            <tr>
                <td>{{ $row->Invoice_no }}</td>
                <td>{{ $row->Invoice_date }}</td>
                <td>{{ $row->Customer_NIC }}</td>
                <td>{{ $row->Customer_Name }}</td>
                <td>{{ $row->Route }}</td>
                <td>{{ $row->Salesmen }}</td>
                <td class="rp-numeric">{{ number_format($row->Gross_Amount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->Discount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->Net_Amount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->Cash_Pay, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->Credite, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->Cheque, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="12">No invoices found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">Total</td>
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
