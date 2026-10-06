<x-report-print title="Opening Hire Purchase Summary Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel" :landscape="true">
    <table>
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Reference No</th>
                <th>Agreement No</th>
                <th>Customer Name</th>
                <th>Customer NIC</th>
                <th>Schema Type</th>
                <th>Document Charge</th>
                <th>Down Payment</th>
                <th>Transport</th>
                <th>Instalment Amount</th>
                <th>No. of Instalment</th>
                <th>Instalment</th>
                <th>Gross Amount</th>
                <th>Discount</th>
                <th>Net Amount</th>
                <th>Cash Payment</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoice as $row)
            <tr>
                <td>{{ $row->invoice_no }}</td>
                <td>{{ $row->invoice_date }}</td>
                <td>{{ $row->reference_no }}</td>
                <td>{{ $row->agreement_no }}</td>
                <td>{{ $row->customer_name }}</td>
                <td>{{ $row->customer_nic }}</td>
                <td>{{ $row->schema_type }}</td>
                <td class="rp-numeric">{{ number_format($row->document_charge, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->down_payment, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->transport, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->instalment_amount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->no_of_instalment, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->instalment, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->gross_amount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->discount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->net_amount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($row->cash_payment, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="17">No results found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7">Total</td>
                <td class="rp-numeric">{{ $document_charge }}</td>
                <td class="rp-numeric">{{ $down_payment }}</td>
                <td class="rp-numeric">{{ $transport }}</td>
                <td class="rp-numeric">{{ $instalment_amount }}</td>
                <td class="rp-numeric">{{ $no_of_instalment }}</td>
                <td class="rp-numeric">{{ $instalment }}</td>
                <td class="rp-numeric">{{ $gross_amount }}</td>
                <td class="rp-numeric">{{ $discount }}</td>
                <td class="rp-numeric">{{ $net_amount }}</td>
                <td class="rp-numeric">{{ $cash_payment }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
