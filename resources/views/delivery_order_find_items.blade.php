@foreach ($invoiceData as $data)
<tr>
    <td style="width:12%;">
        <input type="hidden" name="inputs[{{ $loop->index }}][invoice_no]" value="{{ $data['Invoice_no'] }}">
        <input class="form-control item-code-cell" type="text" style="text-align: center;" placeholder="Item Code"
            name="inputs[{{ $loop->index }}][item_code]" value="{{ $data['Item_code'] }}" readonly>
    </td>

    <td style="width:16%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Item Description"
            name="inputs[{{ $loop->index }}][item_description]" value="{{ $data['Item_description'] }}" readonly>
    </td>

    <td style="width:10%;">
        <input class="form-control" type="number" step="0.001" style="text-align: center;" placeholder="QTY"
            name="inputs[{{ $loop->index }}][qty]" value="{{ $data['QTY'] }}">
    </td>

    <td style="width:12%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Unit Price"
            name="inputs[{{ $loop->index }}][unit_price]" value="{{ $data['Unit_price'] }}" readonly>
    </td>

    <td style="width:12%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Discount"
            name="inputs[{{ $loop->index }}][discount]" value="{{ $data['Discount'] }}" readonly>
    </td>

    <td style="width:13%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Net Value"
            name="inputs[{{ $loop->index }}][net_value]" value="{{ $data['Net_value'] }}" readonly>
    </td>

    <td style="width:15%;">
        <select class="form-control do-batch-select" name="inputs[{{ $loop->index }}][batch_no]"
            data-item-code="{{ $data['Item_code'] }}" data-invoice-batch="{{ $data['batch_no'] ?? '' }}">
            <option value="">(no batch)</option>
        </select>
    </td>
</tr>
@endforeach
