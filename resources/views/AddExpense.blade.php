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
    <title>Add Expense</title>

    <style>
        .exp-card {
            max-width: 820px;
            margin: 28px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 12px 32px rgba(20, 33, 61, .08), 0 2px 8px rgba(20, 33, 61, .04);
            padding: 44px 48px 40px;
        }
        .exp-card h2 {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            color: var(--tr-text);
            margin: 0 0 18px;
        }
        .exp-divider {
            border: none;
            border-top: 1px solid var(--tr-border);
            margin: 0 0 32px;
        }
        .exp-field { margin-bottom: 22px; }
        .exp-field label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: var(--tr-text-secondary);
            margin-bottom: 7px;
            letter-spacing: .01em;
        }
        .exp-field .form-control,
        .exp-field select.form-control {
            border: 1px solid var(--tr-border);
            border-radius: 10px;
            padding: 11px 14px;
            font-size: 14px;
            color: var(--tr-text);
            background: #fff;
            min-height: 46px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .exp-field textarea.form-control { min-height: 110px; resize: vertical; }
        .exp-field .form-control:focus {
            border-color: var(--tr-blue);
            box-shadow: 0 0 0 3px var(--tr-blue-light);
            outline: none;
        }
        .exp-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .exp-grid-2 {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 20px;
        }
        @media (max-width: 767px) {
            .exp-card { padding: 30px 22px; margin: 16px; }
            .exp-grid-3, .exp-grid-2 { grid-template-columns: 1fr; }
        }

        .exp-actions {
            display: flex;
            justify-content: center;
            gap: 14px;
            margin-top: 12px;
        }
        .exp-btn {
            min-width: 130px;
            padding: 11px 22px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: .02em;
            border: 1.5px solid transparent;
            background: #fff;
            transition: background .15s ease, color .15s ease, transform .12s ease, box-shadow .15s ease;
        }
        .exp-btn:hover { transform: translateY(-1px); }
        .exp-btn-save   { border-color: var(--tr-blue); color: var(--tr-blue); }
        .exp-btn-save:hover   { background: var(--tr-blue); color: #fff; box-shadow: 0 6px 16px rgba(22,119,255,.28); }
        .exp-btn-delete { border-color: var(--tr-danger); color: var(--tr-danger); }
        .exp-btn-delete:hover { background: var(--tr-danger); color: #fff; box-shadow: 0 6px 16px rgba(239,68,68,.25); }
        .exp-btn-cancel { border-color: var(--tr-warning); color: var(--tr-warning); }
        .exp-btn-cancel:hover { background: var(--tr-warning); color: #fff; box-shadow: 0 6px 16px rgba(245,158,11,.25); }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">

                <div class="exp-card">
                    @if(session('status'))
                        <div class="alert alert-success text-center" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h2>Add Expense</h2>
                    <hr class="exp-divider">

                    <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{url('AddExpense')}}">
                        @csrf

                        <div class="exp-grid-3">
                            <div class="exp-field">
                                <label>Expense No</label>
                                <input type="text" id="Expense_no" name="Expense_no"
                                    value="00{{ $maxCustomer+1}}" class="form-control" aria-label="Expense No">
                            </div>
                            <div class="exp-field">
                                <label>Date</label>
                                <input type="date" id="Expense_date" name="Expense_date"
                                    class="form-control" aria-label="Date">
                            </div>
                            <div class="exp-field">
                                <label>Expense For <span class="text-danger">*</span></label>
                                <select id="ExpenseType" name="ExpenseType" class="form-control" required="required" data-error="Please specify your need.">
                                    <option value="" selected disabled>-- Select Your Expense For --</option>
                                    <option value="Request order status">Request order status</option>
                                    <option value="Haven't received cashback yet">Haven't received cashback yet</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="exp-grid-2">
                            <div class="exp-field">
                                <label>Expense Note <span class="text-danger">*</span></label>
                                <textarea name="Expense_note" id="Expense_note" class="form-control" required placeholder="Enter the expense note..."></textarea>
                            </div>
                            <div class="exp-field">
                                <label>Amount</label>
                                <input type="number" step="0.01" id="Expense_Amount" name="Expense_Amount"
                                    value="" class="form-control" placeholder="0.00" aria-label="Amount">
                            </div>
                        </div>

                        <div class="exp-actions">
                            <button type="submit" name="save" id="save" class="exp-btn exp-btn-save">SAVE</button>
                            <button type="button" name="pawn_delete" id="pawn_delete" class="exp-btn exp-btn-delete pawn_delete">DELETE</button>
                            <button type="button" name="pawn_cancel" id="pawn_cancel" class="exp-btn exp-btn-cancel pawn_cancel">CANCEL</button>
                        </div>
                    </form>
                </div>

            </div>
            @include('layouts.footer')
        </div>
    </div>

    {{-- form default date set for today --}}
    <script>
        var dateObj = new Date();
        document.getElementById('Expense_date').value = dateObj.toISOString().slice(0, 10);
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
