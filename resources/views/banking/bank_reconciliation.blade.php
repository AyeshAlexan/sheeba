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
    <title>Banking Recognition</title>
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
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div>
                            <h3 class="page-title">Banking Recognition</h3>
                            <p class="page-subtitle">Issued, Deposited and Returned cheques — tick each one once you've confirmed it against the real bank statement.</p>
                        </div>
                    </div>
                </div>

                <div id="alertBox"></div>

                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('banking.recognition') }}" class="row g-2 mb-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label mb-0 small">Bank</label>
                                <select name="cheque_bank_id" class="form-control">
                                    <option value="">All Banks</option>
                                    @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}" {{ (string) $bankId === (string) $bank->id ? 'selected' : '' }}>
                                        {{ $bank->bank_name }} @if($bank->branch) ({{ $bank->branch }}) @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 small">From Date</label>
                                <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label mb-0 small">To Date</label>
                                <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-secondary w-100"><i class="fas fa-filter"></i> Filter</button>
                            </div>
                        </form>

                        <div class="mb-3 text-end">
                            <button type="button" id="saveBtn" class="btn btn-primary" disabled>
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Bank</th>
                                        <th>Direction</th>
                                        <th>Status</th>
                                        <th>Cheque No</th>
                                        <th>Party</th>
                                        <th>Amount</th>
                                        <th class="text-center">Reconciled</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cheques as $cheque)
                                    <tr>
                                        <td>{{ $cheque->release_date }}</td>
                                        <td>{{ $cheque->bank_name }}</td>
                                        <td>
                                            @if($cheque->direction === 'supplier')
                                                <span class="badge bg-warning text-dark">Supplier</span>
                                            @else
                                                <span class="badge bg-info text-dark">Customer</span>
                                            @endif
                                        </td>
                                        <td>{{ $cheque->status_label }}</td>
                                        <td>{{ $cheque->cheques_no }}</td>
                                        <td>{{ $cheque->party }}</td>
                                        <td>{{ number_format((float) $cheque->amount, 2) }}</td>
                                        <td class="text-center">
                                            <input type="checkbox" class="reconcileCheckbox" style="width:18px;height:18px;"
                                                data-direction="{{ $cheque->direction }}"
                                                data-id="{{ $cheque->id }}"
                                                data-initial="{{ $cheque->is_reconciled ? '1' : '0' }}"
                                                {{ $cheque->is_reconciled ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No cheques found for the selected filters.</td>
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

            // Ticking a box is just a draft on screen — nothing is saved
            // until the Save button is pressed. The Save button only
            // enables once at least one box differs from how it loaded.
            function refreshSaveBtn() {
                let dirty = false;
                $('.reconcileCheckbox').each(function () {
                    if (($(this).prop('checked') ? '1' : '0') !== $(this).data('initial').toString()) {
                        dirty = true;
                    }
                });
                $('#saveBtn').prop('disabled', !dirty);
            }

            $(document).on('change', '.reconcileCheckbox', refreshSaveBtn);

            $('#saveBtn').on('click', function () {
                let changes = [];
                $('.reconcileCheckbox').each(function () {
                    let checked = $(this).prop('checked') ? '1' : '0';
                    if (checked !== $(this).data('initial').toString()) {
                        changes.push({
                            direction: $(this).data('direction'),
                            id: $(this).data('id'),
                            reconciled: checked === '1'
                        });
                    }
                });

                if (changes.length === 0) return;

                let $btn = $(this);
                $btn.prop('disabled', true);

                $.ajax({
                    url: "{{ route('banking.recognition.save') }}",
                    method: 'POST',
                    data: { changes: changes },
                    success: function (res) {
                        $('#alertBox').html('<div class="alert alert-success">' + res.message + '</div>');
                        $('.reconcileCheckbox').each(function () {
                            $(this).data('initial', $(this).prop('checked') ? '1' : '0');
                        });
                        refreshSaveBtn();
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong.';
                        $('#alertBox').html('<div class="alert alert-danger">' + msg + '</div>');
                        $btn.prop('disabled', false);
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
