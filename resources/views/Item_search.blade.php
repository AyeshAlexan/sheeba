@foreach ($Item_get as $test)
<div class="row">
<div class="col">
<input class="form-control" type="hidden"
        name="inputs[0][customer_id]" />



<label for="category">Item Category :
    <input class="form-control form-control-lg" type="text"
        value= "{{$test['category']}}"
        id="category"
        placeholder="Customer ID:"
        name="inputs[0][category]" readonly/>
</label>
</div>
<div class="col">
<label for="Item_description">Item Name :
    <input class="form-control form-control-lg" type="text"
        value= "{{$test['Item_description']}}"
        placeholder="Item_description"
        name="inputs[0][Item_description]"
        id="Item_description" readonly/>
</label>
</div>
<div class="col">
<label for="purchasePrice">Item Puruchases Prices :
    <input class="form-control form-control-lg" type="text"
        value= "{{$test['purchasePrice']}}"
        placeholder="purchasePrice"
        name="inputs[0][purchasePrice]"
        id="purchasePrice" readonly/>
</label>
</div>
</div>
@endforeach