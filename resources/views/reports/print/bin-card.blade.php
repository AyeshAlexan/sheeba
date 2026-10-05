<x-report-print title="Bin Card{{ $itemName ? ' — ' . $itemName : '' }}" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Tr No</th>
                <th>Invoice No</th>
                <th>Transaction Type</th>
                <th>Item</th>
                <th>Cust. Code</th>
                <th>Customer Name</th>
                <th>In</th>
                <th>Out</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stockDetails as $data)
            <tr>
                <td>{{ $data->dDate }}</td>
                <td>{{ $data->trans_no }}</td>
                <td>{{ $data->invoice_no }}</td>
                <td>{{ $data->trans_code }}</td>
                <td>{{ $data->item_name }}</td>
                <td>{{ $data->customer_code }}</td>
                <td>{{ $data->customer_name }}</td>
                <td>{{ $data->qun_in }}</td>
                <td>{{ $data->qun_out }}</td>
                <td>
                    @if($data->qun_in > $data->qun_out)
                        {{ $data->qun_in }}
                    @else
                        -{{ $data->qun_out }}
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="10">No movements found.</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7">Total Quantity</td>
                <td>{{ $quain ?? 0 }}</td>
                <td>-{{ $quaout ?? 0 }}</td>
                <td>{{ $balance ?? 0 }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
