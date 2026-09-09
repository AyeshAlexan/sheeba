@foreach ($get_Workers as $test)
<div class="row">
<div class="col">
<input class="form-control" type="hidden"
        name="inputs[0][customer_id]" />

<label for="customer_id">Store Name :
    <input class="form-control form-control-lg" type="text"
        value= "{{$test['Address']}}"
        id="Works_name"
        placeholder="Customer ID:"
        name="inputs[0][Works_name]" readonly/>
</label>
</div>
</div>
@endforeach
