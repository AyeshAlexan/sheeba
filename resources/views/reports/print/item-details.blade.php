<x-report-print title="Item Details Report" :companyData="$companyData" :branchDel="$branchDel">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Item Code</th>
                <th>Item Description</th>
                <th>Purchase Price</th>
                <th>Sales Price</th>
                <th>Border Price</th>
                <th>Item Set</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoice as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->category }}</td>
                <td>{{ $item->Item_code }}</td>
                <td>{{ $item->Item_description }}</td>
                <td>{{ $item->purchasePrice }}</td>
                <td>{{ $item->saleprice }}</td>
                <td>{{ $item->Credit }}</td>
                <td>
                    @if($item->category == 'Set Item')
                        @php $matchedPackages = $PackageItem->where('pkg_code', $item->Item_code); @endphp
                        @if($matchedPackages->isNotEmpty())
                        <table class="rp-sub-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Set Item Code</th>
                                    <th>Item Code</th>
                                    <th>Description</th>
                                    <th>Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($matchedPackages as $package)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $package->pkg_code }}</td>
                                    <td>{{ $package->item_code }}</td>
                                    <td>{{ $package->item_description }}</td>
                                    <td>{{ $package->qty }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        No packages
                        @endif
                    @else
                    -
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8">No items found.</td></tr>
            @endforelse
        </tbody>
    </table>

</x-report-print>
