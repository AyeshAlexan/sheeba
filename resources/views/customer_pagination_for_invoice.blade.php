<table class="table table-bordered table-center table-hover mt-3" id="customer_data">
    <thead>
        <tr class="table-secondary">
            <th>Code</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($customers as $data)
        <tr>
            <td>{{$data->Code }}</td>
            <td>
                <div class="item-description-wrapper">
                    {{$data->Name}}
                </div>
            </td>
            <td>
                <a href="" class="btn btn-outline-info btn-sm shadow"
                    name="add_cus" id="add_cus" data-bs-toggle="modal"
                    data-bs-target="#selectCustomerModel"
                    data-id="{{$data->id}}"
                    data-cus_code="{{$data->Code}}"
                    data-cus_name="{{$data->Name}}">
                    Add <i class="fas fa-plus"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

