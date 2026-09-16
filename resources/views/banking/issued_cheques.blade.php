@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <title>Issued Cheques</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper">
            <div class="content container-fluid">
                <div class="page-header ph-flex">
                    <div class="ph-left">
                        <div class="ph-icon">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                        </div>
                        <div>
                            <h3 class="page-title">Issued Cheques</h3>
                            <p class="page-subtitle">Cheques recorded against a supplier payment but not yet handed over. Tick the ones that were actually given to the supplier and click Issue.</p>
                        </div>
                    </div>
                </div>

                <div id="alertBox"></div>

                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3 align-items-end">
                            <div class="col-md-3">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search by cheque no, supplier or bank" value="{{ $search }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-0 small">From Date</label>
                                <input type="date" id="fromDate" class="form-control" value="{{ $fromDate }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label mb-0 small">To Date</label>
                                <input type="date" id="toDate" class="form-control" value="{{ $toDate }}">
                            </div>
                            <div class="col-md-1">
                                <button type="button" id="searchBtn" class="btn btn-outline-secondary w-100">Search</button>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="button" id="issueBtn" class="btn btn-primary" disabled>
                                    <i class="fas fa-check"></i> Issue Selected
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>Cheque No</th>
                                        <th>Supplier</th>
                                        <th>Bank</th>
                                        <th>Cheque Date</th>
                                        <th>Amount</th>
                                        <th>Purchase No</th>
                                        <th>Pending Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cheques as $cheque)
                                    <tr>
                                        <td><input type="checkbox" class="chequeCheckbox" data-cheque-date="{{ $cheque->release_date }}" value="{{ $cheque->id }}"></td>
                                        <td>{{ $cheque->cheques_no }}</td>
                                        <td>{{ $cheque->supplier_name }} <br><small class="text-muted">{{ $cheque->supplier_code }}</small></td>
                                        <td>{{ $cheque->bank_name }} <br><small class="text-muted">{{ $cheque->bank_branch }}</small></td>
                                        <td>{{ $cheque->release_date }}</td>
                                        <td>
                                            {{ number_format((float) $cheque->amount, 2) }}
                                            @if($cheque->is_partial_payment)
                                                <span class="badge bg-warning text-dark">Partial</span>
                                            @endif
                                        </td>
                                        <td>{{ $cheque->trans_no }}</td>
                                        <td>{{ !is_null($cheque->pending_amount) ? number_format((float) $cheque->pending_amount, 2) : '—' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No pending cheques found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            var todayStr = "{{ now()->toDateString() }}";

            function toggleIssueBtn() {
                $('#issueBtn').prop('disabled', $('.chequeCheckbox:checked').length === 0);
            }

            // Block ticking a cheque before its own date has arrived.
            $(document).on('click', '.chequeCheckbox', function (e) {
                let chequeDate = $(this).data('cheque-date');
                if (chequeDate && chequeDate > todayStr) {
                    e.preventDefault();
                    alert('This cheque is dated ' + chequeDate + ' — it can\'t be actioned until then.');
                    return false;
                }
            });

            $('#checkAll').on('change', function () {
                let checked = $(this).is(':checked');
                $('.chequeCheckbox').each(function () {
                    let chequeDate = $(this).data('cheque-date');
                    if (!chequeDate || chequeDate <= todayStr) {
                        $(this).prop('checked', checked);
                    }
                });
                toggleIssueBtn();
            });

            $(document).on('change', '.chequeCheckbox', toggleIssueBtn);

            $('#searchBtn').on('click', function () {
                let params = new URLSearchParams();
                params.set('search', $('#searchInput').val());
                if ($('#fromDate').val()) params.set('from_date', $('#fromDate').val());
                if ($('#toDate').val()) params.set('to_date', $('#toDate').val());
                window.location.href = "{{ route('issued.cheques') }}?" + params.toString();
            });
            $('#searchInput').on('keypress', function (e) {
                if (e.which === 13) { $('#searchBtn').click(); }
            });

            $('#issueBtn').on('click', function () {
                let ids = $('.chequeCheckbox:checked').map(function () { return $(this).val(); }).get();
                if (ids.length === 0) return;
                if (!confirm('Mark ' + ids.length + ' cheque(s) as issued? This will deduct the amount from the linked bank balance.')) return;

                $(this).prop('disabled', true);

                $.ajax({
                    url: "{{ route('issued.cheques.mark') }}",
                    method: 'POST',
                    data: { cheque_ids: ids },
                    success: function (res) {
                        $('#alertBox').html('<div class="alert alert-success">' + res.message + '</div>');
                        setTimeout(function () { window.location.reload(); }, 800);
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong.';
                        $('#alertBox').html('<div class="alert alert-danger">' + msg + '</div>');
                        $('#issueBtn').prop('disabled', false);
                    }
                });
            });
        });
    </script>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
@endsection

</html>
