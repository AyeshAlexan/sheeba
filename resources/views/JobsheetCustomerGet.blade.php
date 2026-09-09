@foreach ($customer_get as $test)
<label for="customer_name"> Customer Name :
    <div class="input-group">
        <input type="text" class="form-control  form-control-lg"
            value= "{{$test['First_name']}}"
            name="customer_name" id="customer_name"
            placeholder="Enter Customer Name" required readonly/>
    </div>
</label>

@endforeach
