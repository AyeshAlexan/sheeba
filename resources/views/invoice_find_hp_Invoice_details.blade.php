@foreach ($invoiceData as $data)

<tr>
    <td style="width:15%;">
        <input type="hidden" name="inputs[` + i + `][customer_nic]" value="` + customer_nic + `">
        <input type="hidden" name="inputs[` + i + `][invoice_no]" value="{{ $data['invoice_no']}}">
        <input type="hidden" name="inputs[` + i + `][invoice_date]" value="{{ $data['invoice_date']}}">
        <input class="form-control" type="hidden" style="text-align: center;" placeholder="Item Code"
                    id="dy_item_code" name="inputs[` + i + `][item_code]" value="{{ $data['item_code']}}" readonly>
        <input class="form-control" type="text" style="text-align: center;" placeholder="Item Code"
                    id="dy_item_s_code" name="inputs[` + i + `][item_s_code]" value="{{ $data['Item_s_code']}}" readonly>
    </td>

    <td style="width:18%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Item Description"
        id="dy_item_description" name="inputs[` + i + `][item_description]" value="{{ $data['item_description']}}" readonly>
    </td>

    <td style="width:12%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Unit Price"
        id="dy_unit_price" name="inputs[` + i + `][unit_price]" value="{{$data['unit_price']}}" readonly>
    </td>

    <td style="width:10%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="QTY"
        id="dy_qty" name="inputs[` + i + `][qty]" value="{{$data['qty']}}" readonly>
    </td>

    <td style="width:12%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Discount"
        id="dy_discount" name="inputs[` + i + `][discount]" value="{{$data['discount']}}" readonly>
    </td>

    <td style="width:12%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Discount Val"
        id="dy_discount_val" name="inputs[` + i + `][discount_val]" value="{{$data['discount']}}" readonly>
    </td>

    <td style="width:13%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Net Value"
        id="dy_net_value" name="inputs[` + i + `][net_value]" value="{{$data['net_value']}}" readonly>
    </td>

    <td style="width:6%;">
        <center>
            
            {{-- <button type="button" class="btn btn-outline-danger text-center shadow remove-input-field m-2"> <i class="far fa-trash-alt me-1"></i> Delete </button> --}}
        </center>
    </td>

</tr>


@endforeach




