<table
class="table table-bordered table-center table-hover mt-3"
id="ItemTable">
<thead>
    <tr class="table-secondary">
        <th>Code</th>
        <th>Name</th>
        <th>Price</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @foreach ($itemDetails as $key=>$ItemData)
    <tr>
        <td>{{$ItemData->Bar_code }}</td>
        <td>
            <div class="item-description-wrapper">
                {{$ItemData->Item_description}}
            </div>
        </td>
        <td>{{$ItemData->purchasePrice}}</td>
        <td>
            <a href=""
                class="btn btn-outline-info btn-sm shadow"
                name="add_item"
                id="add_item"
                data-bs-toggle="modal"
                data-bs-target="#searchItemModel"
                data-id="{{$ItemData->id}}"
                data-add_item_code="{{$ItemData->Item_code}}"
                data-Item_description="{{$ItemData->Item_description}}">
                Add <i class="fas fa-plus"></i>
            </a>
        </td>
    </tr>
    @endforeach
</tbody>
</table>


{{-- set item data function when inserting the item code or name --}}
<script>
    function setItemDetails() {
                // Listen for changes in the Weight and QTY fields
                let Item_code = $('#item_code').val();
                $.ajax({
                    url: "{{ route('show_select_item_description_ajax') }}",
                    method: 'GET',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        Item_code: Item_code
                    },
                    success: function (res) {
                        if (res.status == 'success') {

                            var itemDescriptionSelected = $('#item_description');
                            var itemUnit_price = $('#unit_price');
                             var item_s_code = $('#item_s_code');
                            // Change this to match your items select element
                            // Clear existing options
                            itemDescriptionSelected.empty();
                            itemUnit_price.empty();
                            item_s_code.empty();

                            $.each(res.data, function (index, item) {
                                itemDescriptionSelected.append($(
                                    '<option>', {
                                        value: item
                                            .Item_description,
                                        text: item
                                            .Item_description
                                    }));

                                itemUnit_price.val(item.purchasePrice);
                                item_s_code.val(item.Bar_code);
                            });

                        }
                    },
                    error: function (err) {
                        $('.errMsgContainer').html('');
                        let error = err.responseJSON;
                        $.each(error.errors, function (index, value) {
                            $('.errMsgContainer').append(
                                '<span class="text-danger">' +
                                value +
                                '<span>' + '<br>');
                        });
                    }
                });

    }
</script>
