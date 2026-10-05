<x-report-print title="Stock Details Report" :fromDate="$fromDate" :toDate="$toDate" :landscape="true" :companyData="$companyData" :branchDel="$branchDel">
    <table class="rp-wide-table">
        <thead>
            <tr>
                <th>Item Code</th>
                <th>Description</th>
                @foreach($showInCodes as $code)
                <th class="rp-numeric">{{ $code }} In</th>
                @endforeach
                @foreach($showOutCodes as $code)
                <th class="rp-numeric">{{ $code }} Out</th>
                @endforeach
                <th class="rp-numeric">Total In</th>
                <th class="rp-numeric">Total Out</th>
                <th class="rp-numeric">Stock Balance</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stockData as $row)
            <tr>
                <td>{{ $row['Item_code'] }}</td>
                <td>{{ $row['Item_description'] }}</td>
                @foreach($showInCodes as $code)
                <td class="rp-numeric">{{ $row[$code . '_in'] ?? '-' }}</td>
                @endforeach
                @foreach($showOutCodes as $code)
                <td class="rp-numeric">{{ $row[$code . '_out'] ?? '-' }}</td>
                @endforeach
                <td class="rp-numeric">{{ $row['qun_in'] }}</td>
                <td class="rp-numeric">{{ $row['qun_out'] }}</td>
                <td class="rp-numeric">{{ $row['qun_in'] - $row['qun_out'] - $row['Free_Issues'] }}</td>
                <td>{{ $row['dDate'] }}</td>
            </tr>
            @empty
            <tr><td colspan="{{ 6 + count($showInCodes) + count($showOutCodes) }}">No stock movements found.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-report-print>
