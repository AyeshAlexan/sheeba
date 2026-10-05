<x-report-print title="Customer Details Report" :fromDate="$fromDate" :toDate="$toDate" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>NIC</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Driving License</th>
                <th>Passport</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->Code }}</td>
                <td>{{ $customer->NIC }}</td>
                <td>{{ trim($customer->Title . ' ' . $customer->First_name . ' ' . $customer->Middle_name . ' ' . $customer->Last_name) }}</td>
                <td>{{ $customer->Gender }}</td>
                <td>{{ $customer->Address_1 }}</td>
                <td>{{ $customer->Contact_1 }}</td>
                <td>{{ $customer->Email }}</td>
                <td>{{ $customer->Driving_license }}</td>
                <td>{{ $customer->Passport }}</td>
            </tr>
            @empty
            <tr><td colspan="9">No customers found.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-report-print>
