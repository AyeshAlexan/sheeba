<x-report-print title="Supplier Payment Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Payment No</th>
                <th>Payment Date</th>
                <th>Supplier Code</th>
                <th>Supplier Name</th>
                <th>Supplier Phone</th>
                <th>Amount</th>
                <th>Purchase No</th>
                <th>Note</th>
                <th>Cash</th>
                <th>Card</th>
                <th>Cheque</th>
                <th>Bank</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAmount = 0; @endphp
            @forelse ($payments as $payment)
            <tr>
                <td>{{ $payment->Payment_no }}</td>
                <td>{{ $payment->Payment_date }}</td>
                <td>{{ $payment->Supplier_Code }}</td>
                <td>{{ $payment->Supplier_Name }}</td>
                <td>{{ $payment->Supplier_Phone }}</td>
                <td class="rp-numeric">{{ number_format($payment->Payment_Amount, 2) }}</td>
                <td>{{ $payment->Purchase_no }}</td>
                <td>{{ $payment->Payment_note }}</td>
                <td class="rp-numeric">{{ number_format($payment->cash_payment, 2) }}</td>
                <td class="rp-numeric">{{ number_format($payment->card_payment, 2) }}</td>
                <td class="rp-numeric">{{ number_format($payment->cheque_payment, 2) }}</td>
                <td class="rp-numeric">{{ number_format($payment->bank_transfer, 2) }}</td>
            </tr>
            @php $totalAmount += $payment->Payment_Amount; @endphp
            @empty
            <tr><td colspan="12">No payments found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">Total</td>
                <td class="rp-numeric">{{ number_format($totalAmount, 2) }}</td>
                <td colspan="6"></td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
