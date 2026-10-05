<x-report-print title="Stock In Hand" :fromDate="$fromDate" :toDate="$toDate">
    <table>
        <thead>
            <tr>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stockDetails as $item)
            @php $qty = $item->total_qun_in - $item->total_qun_out - $item->total_free_issues; @endphp
            <tr>
                <td>{{ $item->Item_code }}</td>
                <td>{{ $item->Item_description }}</td>
                <td>{{ $qty }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total Balance</td>
                <td>{{ $balance }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
