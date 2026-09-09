@foreach ($customer_get as $test)
<div class="row">
<div class="col">
<input class="form-control" type="hidden"
        {{-- name="inputs[0][customer_id]" --}}
        name="customer_id" />



<label for="customer_id">Customer Name :
    <input class="form-control form-control-lg" type="text"
        value= "{{$test['First_name']}}"
        id="customer_name"
        placeholder="Customer Name:"
        {{-- name="inputs[0][customer_name]" --}}
        name="customer_name"
        readonly/>
</label>
</div>
<div class="col">
<label for="customer_address">Customer Address :
    <input class="form-control form-control-lg" type="text"
        value= "{{$test['Address_1']}}"
        placeholder="Customer Address"
        {{-- name="inputs[0][customer_address]" --}}
        name="customer_address"
        id="customer_address" readonly/>
</label>
</div>
<div class="col">
<label for="customer_address">Phone Number :
    <input class="form-control form-control-lg" type="text"
        value= "{{$test['Contact_1']}}"
        placeholder="Customer Address"
        {{-- name="inputs[0][customer_contact_1]" --}}
        name="customer_contact_1"
        id="customer_contact_1" readonly/>
</label>
</div>
</div>
@endforeach
