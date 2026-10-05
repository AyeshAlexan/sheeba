<x-report-print title="Stock Valuation" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Purchase Price</th>
                <th>Quantity</th>
                <th>Total Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stockDetails as $stock)
            @php
                $qty       = $stock->total_qun_in - $stock->total_qun_out;
                $lineTotal = $qty * $stock->purchasePrice;
            @endphp
            <tr>
                <td>{{ $stock->Item_code }}</td>
                <td>{{ $stock->Item_description }}</td>
                <td>{{ number_format($stock->purchasePrice, 2) }}</td>
                <td>{{ $qty }}</td>
                <td>{{ number_format($lineTotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Grand Totals</td>
                <td>{{ number_format($sumPurchase, 2) }}</td>
                <td>{{ $balance }}</td>
                <td>{{ number_format($grandTotal, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</x-report-print>
