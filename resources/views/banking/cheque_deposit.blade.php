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
    <title>Cheque Deposit</title>
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
                            <h3 class="page-title">Cheque Deposit</h3>
                            <p class="page-subtitle">Cheques ready to be banked &mdash; issued supplier cheques and received customer cheques. Tick the ones that have gone to the bank and click Deposit.</p>
                        </div>
                    </div>
                </div>

                <div id="alertBox"></div>

                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3 align-items-end">
                            <div class="col-md-3">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search by cheque no, party or bank" value="{{ $search }}">
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
                                <button type="button" id="depositBtn" class="btn btn-primary" disabled>
                                    <i class="fas fa-university"></i> Deposit Selected
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th><input type="checkbox" id="checkAll"></th>
                                        <th>Direction</th>
                                        <th>Cheque No</th>
                                        <th>Party</th>
                                        <th>Bank</th>
                                        <th>Cheque Date</th>
                                        <th>Amount</th>
                                        <th>Pending Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cheques as $cheque)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="chequeCheckbox"
                                                data-direction="{{ $cheque->direction }}"
                                                data-cheque-date="{{ $cheque->release_date }}"
                                                value="{{ $cheque->id }}">
                                        </td>
                                        <td>
                                            @if($cheque->direction === 'supplier')
                                                <span class="badge bg-warning text-dark">Supplier (Issued)</span>
                                            @else
                                                <span class="badge bg-info text-dark">Customer (Received)</span>
                                            @endif
                                        </td>
                                        <td>{{ $cheque->cheques_no }}</td>
                                        <td>{{ $cheque->party }} <br><small class="text-muted">{{ $cheque->party_code }}</small></td>
                                        <td>{{ $cheque->bank_name }}</td>
                                        <td>{{ $cheque->release_date }}</td>
                                        <td>
                                            {{ number_format((float) $cheque->amount, 2) }}
                                            @if($cheque->is_partial_payment)
                                                <span class="badge bg-warning text-dark">Partial</span>
                                            @endif
                                        </td>
                                        <td>{{ !is_null($cheque->pending_amount) ? number_format((float) $cheque->pending_amount, 2) : '—' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No cheques waiting to be deposited.</td>
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

            function toggleDepositBtn() {
                $('#depositBtn').prop('disabled', $('.chequeCheckbox:checked').length === 0);
            }

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
                toggleDepositBtn();
            });

            $(document).on('change', '.chequeCheckbox', toggleDepositBtn);

            $('#searchBtn').on('click', function () {
                let params = new URLSearchParams();
                params.set('search', $('#searchInput').val());
                if ($('#fromDate').val()) params.set('from_date', $('#fromDate').val());
                if ($('#toDate').val()) params.set('to_date', $('#toDate').val());
                window.location.href = "{{ route('cheque.deposit') }}?" + params.toString();
            });
            $('#searchInput').on('keypress', function (e) {
                if (e.which === 13) { $('#searchBtn').click(); }
            });

            $('#depositBtn').on('click', function () {
                let supplierIds = [];
                let customerIds = [];

                $('.chequeCheckbox:checked').each(function () {
                    if ($(this).data('direction') === 'supplier') {
                        supplierIds.push($(this).val());
                    } else {
                        customerIds.push($(this).val());
                    }
                });

                let total = supplierIds.length + customerIds.length;
                if (total === 0) return;
                if (!confirm('Mark ' + total + ' cheque(s) as deposited?')) return;

                $(this).prop('disabled', true);

                $.ajax({
                    url: "{{ route('cheque.deposit.process') }}",
                    method: 'POST',
                    data: { supplier_ids: supplierIds, customer_ids: customerIds },
                    success: function (res) {
                        $('#alertBox').html('<div class="alert alert-success">' + res.message + '</div>');
                        setTimeout(function () { window.location.reload(); }, 800);
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong.';
                        $('#alertBox').html('<div class="alert alert-danger">' + msg + '</div>');
                        $('#depositBtn').prop('disabled', false);
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
