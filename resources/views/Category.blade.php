@extends('layouts.topnavbar')
@extends('layouts.sidebar')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- ✅ CSS -->
<link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet">

<!-- ✅ JS -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

<!-- ✅ SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="main-wrapper">
<div class="page-wrapper">
<div class="content container-fluid">

    <h3 class="page-title">Category Details</h3>
    <hr>

    <button class="btn btn-warning mb-3" onclick="add()">Add Category</button>

    <div class="card p-3">
        <table class="table table-bordered" id="MBrand">
            <thead>
                <tr style="background-color: gray">
                    <th>Code</th>
                    <th>Name</th>
                    <th>List Code</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

</div>
</div>
</div>

<!-- ✅ MODAL -->
<div class="modal fade" id="Store-modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="StoreModal">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="StoreForm">

                    <input type="hidden" id="id" name="id">

                    <div class="mb-2">
                        <label>Code</label>
                        <input type="number" class="form-control" id="code" name="code" value="00{{ $maxCustomer+1 }}" required>
                    </div>

                    <div class="mb-2">
                        <label>Name</label>
                        <input type="text" class="form-control" id="description" name="description" required>
                    </div>

                    <div class="mb-2">
                        <label>List Code</label>
                        <input type="text" class="form-control" id="Cate_code" name="Cate_code" required>
                    </div>

                    <input type="hidden" name="Branch" value="{{ Auth::user()->Branch }}">
                    <input type="hidden" name="BranchCode" value="{{ Auth::user()->BC }}">

                    <button type="submit" id="btn-save" class="btn btn-primary mt-3">Save</button>

                </form>
            </div>

        </div>
    </div>
</div>

<!-- ✅ SCRIPT -->
<script>
$(document).ready(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#MBrand').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('Category') }}",
        columns: [
            { data: 'code' },
            { data: 'description' },
            { data: 'Cate_code' },
            { data: 'action', orderable:false }
        ]
    });

});


// ✅ ADD
function add(){
    $('#StoreForm')[0].reset();
    $('#StoreModal').text("Add Category");

    let modal = new bootstrap.Modal(document.getElementById('Store-modal'));
    modal.show();
}


// ✅ EDIT
function editFunc(id){
    $.post("{{ url('Category_edit') }}",{id:id},function(res){

        $('#StoreModal').text("Edit Category");

        let modal = new bootstrap.Modal(document.getElementById('Store-modal'));
        modal.show();

        $('#id').val(res.id);
        $('#code').val(res.code);
        $('#description').val(res.description);
        $('#Cate_code').val(res.Cate_code);

    },'json');
}


// ✅ DELETE
function deleteFunc(id){
    Swal.fire({
        title: "Are you sure?",
        icon: "warning",
        showCancelButton: true
    }).then((result)=>{
        if(result.isConfirmed){

            $.post("{{ url('Category_delete') }}",{id:id},function(){

                Swal.fire({
                    icon:'success',
                    title:'Deleted!',
                    timer:1500,
                    showConfirmButton:false
                });

                $('#MBrand').DataTable().ajax.reload();

            });
        }
    });
}


// ✅ SUBMIT
$('#StoreForm').submit(function(e){
    e.preventDefault();

    $('#btn-save').text('Saving...').prop('disabled',true);

    $.ajax({
        type:'POST',
        url:"{{ url('Category_store') }}",
        data:new FormData(this),
        contentType:false,
        processData:false,

        success:function(){

            let modalEl = document.getElementById('Store-modal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();

            Swal.fire({
                icon:'success',
                title:'Success!',
                text:'Category saved successfully'
            }).then(()=>{
                location.reload(); // ✅ reload after OK
            });

        },

        error:function(){
            Swal.fire({
                icon:'error',
                title:'Error!',
                text:'Something went wrong'
            });

            $('#btn-save').text('Save').prop('disabled',false);
        }
    });
});
</script>

@endsection