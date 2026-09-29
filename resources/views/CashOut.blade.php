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
    <title>Cash Out</title>
</head>
<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header ph-flex">
                    <div class="ph-left">
                        <div class="ph-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
                        </div>
                        <div>
                            <h3 class="page-title">Cash Out</h3>
                            <p class="page-subtitle">Record a cash disbursement from the register</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success text-center" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{url('store-form')}}">
                            @csrf

                            <div class="stock-info-card">
                                <div class="stock-info-grid-3">
                                    <div class="si-field">
                                        <label>Cash Out No <span class="text-danger">*</span></label>
                                        <div class="si-icon-wrap">
                                            <i class="fas fa-hashtag"></i>
                                            <input type="number" id="Cashout_no" name="Cashout_no" class="form-control" required aria-label="Cash Out No">
                                        </div>
                                    </div>
                                    <div class="si-field">
                                        <label>Date</label>
                                        <div class="si-icon-wrap">
                                            <i class="fas fa-calendar-alt"></i>
                                            <input type="date" id="Cashout_date" name="Cashout_date" class="form-control" required aria-label="Date">
                                        </div>
                                    </div>
                                    <div class="si-field">
                                        <label>Title <span class="text-danger">*</span></label>
                                        <div class="si-icon-wrap">
                                            <i class="fas fa-tag"></i>
                                            <input type="text" id="Account" name="Account" class="form-control" required aria-label="Title">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="stock-info-card mt-3">
                                <div class="stock-info-grid-2">
                                    <div class="si-field">
                                        <label>Description <span class="text-danger">*</span></label>
                                        <textarea name="Cashout_note" id="Cashout_note" class="form-control" rows="3" required placeholder="Enter a description..."></textarea>
                                    </div>
                                    <div class="si-field">
                                        <label>Cash Out Amount <span class="text-danger">*</span></label>
                                        <div class="si-icon-wrap">
                                            <i class="fas fa-coins"></i>
                                            <input type="text" id="Cashout_amount" name="Cashout_amount" class="form-control" required placeholder="0.00" aria-label="Cash Out Amount">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" name="save" id="save" class="btn btn-outline-info btn-lg shadow">
                                    <i class="fas fa-save"></i> SAVE</button>
                                <button type="button" name="pawn_cancel" id="pawn_cancel"
                                    class="btn btn-outline-warning btn-lg shadow pawn_cancel">
                                    <i class="fas fa-times"></i> CANCEL</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>

  <script>
    var dateObj = new Date();
    document.getElementById('Cashout_date').value = dateObj.toISOString().slice(0, 10);
  </script>

  <script src="assets/js/jquery-3.6.0.min.js"></script>
  <script src="assets/js/feather.min.js"></script>
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
