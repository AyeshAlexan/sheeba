<x-report-print title="Customer Payment Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel" :landscape="true">
    <table>
        <thead>
            <tr>
                <th>Payment No</th>
                <th>Payment Date</th>
                <th>Sales No</th>
                <th>Customer Code</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Note</th>
                <th>Amount</th>
                <th>Cash</th>
                <th>Cheque</th>
                <th>Card</th>
                <th>Bank</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAmount = 0; $totalCashAmount = 0; $totalChequeAmount = 0; $totalCardAmount = 0; $totalBankAmount = 0;
            @endphp
            @forelse($paymentDetails as $data)
            <tr>
                <td>{{ $data->Payment_no }}</td>
                <td>{{ $data->Payment_date }}</td>
                <td>{{ $data->Sales_no }}</td>
                <td>{{ $data->Customer_Code }}</td>
                <td>{{ $data->Customer_Name }}</td>
                <td>{{ $data->Customer_Phone }}</td>
                <td>{{ $data->Payment_note }}</td>
                <td class="rp-numeric">{{ number_format($data->Payment_Amount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($data->cash_payment, 2) }}</td>
                <td class="rp-numeric">{{ number_format($data->cheque_payment, 2) }}</td>
                <td class="rp-numeric">{{ number_format($data->card_payment, 2) }}</td>
                <td class="rp-numeric">{{ number_format($data->bank_transfer, 2) }}</td>
            </tr>
            @php
                $totalAmount += $data->Payment_Amount;
                $totalCashAmount += $data->cash_payment;
                $totalChequeAmount += $data->cheque_payment;
                $totalCardAmount += $data->card_payment;
                $totalBankAmount += $data->bank_transfer;
            @endphp
            @empty
            <tr><td colspan="12">No payments found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7">Total Balance</td>
                <td class="rp-numeric">{{ number_format($totalAmount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($totalCashAmount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($totalChequeAmount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($totalCardAmount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($totalBankAmount, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
