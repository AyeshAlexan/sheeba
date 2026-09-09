@php
    $inputs = [];
@endphp
@foreach ($invoiceData as $data)
@php
        $newRowData = [
            'invoice_no' => $data['Invoice_no'],
            'invoice_date' => $data['Invoice_date'],
            'item_code' => $data['Item_code'],
            'item_description' => $data['Item_description'],
            'qty' => $data['QTY'],
            'unit_price' => $data['Unit_price'],
            'discount' => $data['Discount'],
            'discount_val' => $data['Discount'],
            'net_value' => $data['Net_value'],
        ];
        // Push the new row data to the $inputs array
        $inputs[] = $newRowData;


@endphp

<tr>
    <td style="width:12%;">
        <input type="hidden" name="customer_nic" >
        <input type="hidden" name="invoice_no" value="{{ $data['Invoice_no']}}">
        <input type="hidden" name="invoice_date" value="{{ $data['Invoice_date']}}">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Item Code"
                    id="dy_item_code" name="item_code" value="{{ $data['Item_code']}}" readonly>
    </td>

    <td style="width:18%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Item Description"
        id="dy_item_description" name="item_description" value="{{ $data['Item_description']}}" readonly>
    </td>

    <td style="width:10%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="QTY"
        id="dy_qty" name="qty" value="{{$data['QTY']}}" readonly>
    </td>

    <td style="width:12%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Unit Price"
        id="dy_unit_price" name="unit_price" value="{{$data['Unit_price']}}" readonly>
    </td>

    <td style="width:12%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Discount"
        id="dy_discount" name="discount" value="{{$data['Discount']}}" readonly>
    </td>

    <td style="width:12%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Discount Val"
        id="dy_discount_val" name="discount_val" value="{{$data['Discount']}}" readonly>
    </td>

    <td style="width:13%;">
        <input class="form-control" type="text" style="text-align: center;" placeholder="Net Value"
        id="dy_net_value" name="net_value" value="{{$data['Net_value']}}" readonly>
    </td>

    <td style="width:16%;">
        <center>
            &nbsp;
            {{-- <button type="button" class="btn btn-outline-danger text-center shadow remove-input-field m-2"> <i class="far fa-trash-alt me-1"></i> Delete </button> --}}
        </center>
    </td>
</tr>
@endforeach

<input type="hidden" id="purchase_inputs" name="purchase_inputs" value="{{ json_encode($inputs) }}">


