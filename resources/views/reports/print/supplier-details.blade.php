<x-report-print title="Supplier Details Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $data)
            <tr>
                <td>{{ $data->Code }}</td>
                <td>{{ $data->Name }}</td>
                <td>{{ $data->Address_1 }}</td>
                <td>{{ $data->Contact_1 }}</td>
                <td>{{ $data->Email }}</td>
            </tr>
            @empty
            <tr><td colspan="5">No results found.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-report-print>
