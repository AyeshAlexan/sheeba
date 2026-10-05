<x-report-print title="Stock Zero" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Item Code</th>
                <th>Quantity In</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($invoice as $row)
            <tr>
                <td>{{ $row->dDate }}</td>
                <td>{{ $row->item_code }}</td>
                <td>{{ $row->qun_in }}</td>
            </tr>
            @empty
            <tr><td colspan="3">No zero-quantity movements found.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-report-print>
