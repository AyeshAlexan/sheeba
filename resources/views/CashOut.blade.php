@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <title>ADD Expense</title>

    <style>
        .form-style-2{
            max-width: 1000px;
            padding: 30px 15px 10px 20px;
            font: 14px Arial, Helvetica, sans-serif;
        }
        .form-style-2-heading{
            font-weight: bold;
            font-style: italic;
            border-bottom: 2px solid #ddd;
            margin-bottom: 20px;
            font-size: 15px;
            padding-bottom: 3px;
        }
        .form-style-2 label{
            display: block;
            margin: 0px 0px 15px 0px;
        }
        .form-style-2 label > span{
            width: 100px;
            font-weight: bold;
            float: left;
            padding-top: 8px;
            padding-right: 5px;
        }
        .form-style-2 span.required{
            color:red;
        }
        .form-style-2 .tel-number-field{
            width: 40px;
            text-align: center;
        }
        .form-style-2 input.input-field, .form-style-2 .select-field{
            width: 48%;	
        }
        .form-style-2 input.input-field, 
        .form-style-2 .tel-number-field, 
        .form-style-2 .textarea-field, 
         .form-style-2 .select-field{
            box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            border: 1px solid #C2C2C2;
            box-shadow: 1px 1px 4px #EBEBEB;
            -moz-box-shadow: 1px 1px 4px #EBEBEB;
            -webkit-box-shadow: 1px 1px 4px #EBEBEB;
            border-radius: 3px;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            padding: 7px;
            outline: none;
        }
        .form-style-2 .input-field:focus, 
        .form-style-2 .tel-number-field:focus, 
        .form-style-2 .textarea-field:focus,  
        .form-style-2 .select-field:focus{
            border: 1px solid #0C0;
        }
        .form-style-2 .textarea-field{
            height:100px;
            width: 55%;
        }
        .form-style-2 input[type=submit],
        .form-style-2 input[type=button]{
            border: none;
            padding: 8px 15px 8px 15px;
            background: #FF8500;
            color: #fff;
            box-shadow: 1px 1px 4px #DADADA;
            -moz-box-shadow: 1px 1px 4px #DADADA;
            -webkit-box-shadow: 1px 1px 4px #DADADA;
            border-radius: 3px;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
        }
        .form-style-2 input[type=submit]:hover,
        .form-style-2 input[type=button]:hover{
            background: #EA7B00;
            color: #fff;
        }
        </style>
</head>
<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">


<div class="container mt-4">
    @if(session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
    @endif





      <div class="card-body">
        <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{url('store-form')}}">
            @csrf
          <div class="form-group">
            <label for="exampleInputEmail1">date</label>
            <input type="date" id="Cashout_date" name="Cashout_date" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Cashout_no</label>
            <input type="number" id="Cashout_no" name="Cashout_no" class="form-control" required="">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">Cashout amount</label>
            <input type="text" id="Cashout_amount" name="Cashout_amount" class="form-control" required="">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">Title</label>
            <input type="text" id="Account" name="Account" class="form-control" required="">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Description</label>
            <textarea name="Cashout_note" id="Cashout_note" class="form-control" required=""></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    var dateObj = new Date();
    document.getElementById('Cashout_date').value = dateObj.toISOString().slice(0, 10);

</script>

  <script src="assets/js/jquery-3.6.0.min.js"></script>
  <script src="assets/js/feather.min.js"></script>
  {{-- <script src="assets/plugins/select2/js/select2.min.js"></script> --}}
  <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
  <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="assets/plugins/datatables/datatables.min.js"></script>
  <script src="assets/js/script.js"></script>
  <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
  <script src="assets/plugins/apexchart/chart-data.js"></script>
  <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
@endsection
</html>

