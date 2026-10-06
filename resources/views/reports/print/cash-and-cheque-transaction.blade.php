<x-report-print title="Cash & Cheque Transaction Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel" :landscape="true">
    <h3 style="margin:0 0 6px; font-size:14px; color:#14213D;">Cash Transactions (201-001)</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>No</th>
                <th>Transaction Type</th>
                <th>Description</th>
                <th>Double Entry</th>
                <th>DR Amount</th>
                <th>CR Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $fromDate }}</td>
                <td>-</td>
                <td>Opening Balance (Cash)</td>
                <td></td>
                <td></td>
                <td class="rp-numeric">{{ $cashOpeningBalanceFmt }}</td>
                <td class="rp-numeric">0.00</td>
            </tr>
            @forelse ($cashInvoice as $inv)
            <tr>
                <td>{{ $inv->Ddate }}</td>
                <td>{{ $inv->trance_no }}</td>
                <td>{{ $inv->reference_label }}</td>
                <td>{{ $inv->Description }}</td>
                <td>{{ $inv->logic_summary }}</td>
                <td class="rp-numeric">{{ number_format($inv->dr_amount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($inv->cr_amount, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="7">No cash transactions found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">Total</td>
                <td class="rp-numeric">{{ $cashTotalDrFmt }}</td>
                <td class="rp-numeric">{{ $cashTotalCrFmt }}</td>
            </tr>
            <tr>
                <td colspan="5">Cash Balance (DR − CR)</td>
                <td colspan="2" class="rp-numeric">{{ $cashBalanceFmt }}</td>
            </tr>
        </tfoot>
    </table>

    <h3 style="margin:18px 0 6px; font-size:14px; color:#14213D;">Cheque Transactions (201-123)</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>No</th>
                <th>Transaction Type</th>
                <th>Description</th>
                <th>Double Entry</th>
                <th>DR Amount</th>
                <th>CR Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $fromDate }}</td>
                <td>-</td>
                <td>Opening Balance (Cheque)</td>
                <td></td>
                <td></td>
                <td class="rp-numeric">{{ $chequeOpeningBalanceFmt }}</td>
                <td class="rp-numeric">0.00</td>
            </tr>
            @forelse ($chequeInvoice as $inv)
            <tr>
                <td>{{ $inv->Ddate }}</td>
                <td>{{ $inv->trance_no }}</td>
                <td>{{ $inv->reference_label }}</td>
                <td>{{ $inv->Description }}</td>
                <td>{{ $inv->logic_summary }}</td>
                <td class="rp-numeric">{{ number_format($inv->dr_amount, 2) }}</td>
                <td class="rp-numeric">{{ number_format($inv->cr_amount, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="7">No cheque transactions found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">Total</td>
                <td class="rp-numeric">{{ $chequeTotalDrFmt }}</td>
                <td class="rp-numeric">{{ $chequeTotalCrFmt }}</td>
            </tr>
            <tr>
                <td colspan="5">Cheque Balance (DR − CR)</td>
                <td colspan="2" class="rp-numeric">{{ $chequeBalanceFmt }}</td>
            </tr>
            <tr>
                <td colspan="5">Combined Total (Cash + Cheque)</td>
                <td colspan="2" class="rp-numeric">{{ $combinedBalanceFmt }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
