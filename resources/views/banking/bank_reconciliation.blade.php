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
    <style>
        .recognition-card, .recognition-table { border: 1px solid #dce5ef; border-radius: 16px; background: #fff; }
        .recognition-card { padding: 20px; margin-bottom: 20px; }
        .recognition-card .form-label { color: #334155; font-weight: 600; }
        .recognition-actions { display: flex; flex-wrap: wrap; gap: 8px; }
        .recognition-save { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; border-top: 1px solid #e6edf5; margin-top: 18px; padding-top: 16px; }
        .recognition-table { overflow: hidden; margin-bottom: 18px; }
        .recognition-table header { display: flex; align-items: center; gap: 10px; padding: 16px 20px; }
        .recognition-table h4 { margin: 0; font-size: 17px; font-weight: 650; }
        .recognition-count, .recognition-status { display: inline-block; border-radius: 999px; padding: 4px 10px; font-size: 12px; font-weight: 600; background: #eef2f7; color: #334155; }
        .recognition-table--reconciled .recognition-count, .recognition-status--deposited { background: #e8f7ef; color: #14643c; }
        .recognition-status--returned { background: #fcebee; color: #9c2841; }
        .recognition-status--issued { background: #eaf2ff; color: #254f99; }
        .recognition-table .table { margin: 0; }
        .recognition-table .table th { background: #f5f8fc; color: #475569; font-size: 12px; font-weight: 650; white-space: nowrap; }
        .recognition-table .table th, .recognition-table .table td { padding: 12px 16px; vertical-align: middle; border-color: #e6edf5; }
        .recognition-amount { text-align: right; white-space: nowrap; font-variant-numeric: tabular-nums; }
        .recognition-table .reconcileCheckbox { width: 18px; height: 18px; margin: 0; vertical-align: middle; accent-color: #2563eb; }
    </style>
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

                <div id="alertBox" aria-live="polite"></div>

                <div class="recognition-card">
                        <form method="GET" action="{{ route('banking.recognition') }}" class="row g-3 align-items-end">
                            <div class="col-sm-6 col-lg-4">
                                <label for="recognitionBank" class="form-label small">Bank</label>
                                <select id="recognitionBank" name="cheque_bank_id" class="form-select">
                                    <option value="">All Banks</option>
                                    @foreach($banks as $bank)
                                    <option value="{{ $bank->id }}" {{ (string) $bankId === (string) $bank->id ? 'selected' : '' }}>
                                        {{ $bank->bank_name }} @if($bank->branch) ({{ $bank->branch }}) @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 col-lg">
                                <label for="recognitionFrom" class="form-label small">From Date</label>
                                <input id="recognitionFrom" type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                            </div>
                            <div class="col-sm-6 col-lg">
                                <label for="recognitionTo" class="form-label small">To Date</label>
                                <input id="recognitionTo" type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                            </div>
                            <div class="col-sm-6 col-lg-auto recognition-actions">
                                <button type="submit" class="btn btn-outline-secondary">Apply Filters</button>
                                @if($bankId || $fromDate || $toDate)
                                    <a href="{{ route('banking.recognition') }}" class="btn btn-link">Clear</a>
                                @endif
                            </div>
                        </form>

                        <div class="recognition-save">
                            <span id="pendingChanges" class="text-muted small" aria-live="polite">No unsaved changes</span>
                            <button type="button" id="saveBtn" class="btn btn-primary" disabled>
                                <i class="fas fa-save" aria-hidden="true"></i> <span>Save Changes</span>
                            </button>
                        </div>
                </div>

                        @foreach($cheques->partition(fn ($cheque) => !$cheque->is_reconciled) as $section => $sectionCheques)
                        <section class="recognition-table {{ $section === 1 ? 'recognition-table--reconciled' : '' }}" aria-labelledby="cheque-heading-{{ $section }}">
                        <header>
                            <h4 id="cheque-heading-{{ $section }}">{{ $section === 0 ? 'Unreconciled Cheques' : 'Reconciled Cheques' }}</h4>
                            <span class="recognition-count" aria-label="Cheque count">{{ $sectionCheques->count() }}</span>
                        </header>
                        <div class="table-responsive">
                            <table class="table table-hover" aria-labelledby="cheque-heading-{{ $section }}">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Bank</th>
                                        <th>Direction</th>
                                        <th>Status</th>
                                        <th>Cheque No</th>
                                        <th>Party</th>
                                        <th class="recognition-amount">Amount</th>
                                        <th class="text-center">Reconciled</th>
                                    </tr>
                                </thead>
                                <tbody id="cheques-{{ $section }}">
                                    @foreach($sectionCheques as $cheque)
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
                                        <td><span class="recognition-status recognition-status--{{ strtolower($cheque->status_label) }}">{{ $cheque->status_label }}</span></td>
                                        <td>{{ $cheque->cheques_no }}</td>
                                        <td>{{ $cheque->party }}</td>
                                        <td class="recognition-amount">{{ number_format((float) $cheque->amount, 2) }}</td>
                                        <td class="text-center">
                                            <input type="checkbox" class="reconcileCheckbox"
                                                aria-label="Reconciled status for cheque {{ $cheque->cheques_no }}"
                                                data-direction="{{ $cheque->direction }}"
                                                data-id="{{ $cheque->id }}"
                                                data-initial="{{ $cheque->is_reconciled ? '1' : '0' }}"
                                                {{ $cheque->is_reconciled ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="empty-cheque-row {{ $sectionCheques->isNotEmpty() ? 'd-none' : '' }}">
                                        <td colspan="8" class="text-center text-muted">No cheques found for the selected filters.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        </section>
                        @endforeach
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
                let count = 0;
                $('.reconcileCheckbox').each(function () {
                    if (($(this).prop('checked') ? '1' : '0') !== $(this).data('initial').toString()) {
                        count++;
                    }
                });
                $('#saveBtn').prop('disabled', count === 0);
                $('#pendingChanges').text(count === 0 ? 'No unsaved changes' : count + (count === 1 ? ' unsaved change' : ' unsaved changes'));
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
                $btn.find('span').text('Saving…');
                $('#alertBox').empty();
                const $checkboxes = $('.reconcileCheckbox').prop('disabled', true);

                $.ajax({
                    url: "{{ route('banking.recognition.save') }}",
                    method: 'POST',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify({ changes: changes }),
                    success: function (res) {
                        $('#alertBox').append($('<div>', { class: 'alert alert-success', text: res.message }));
                        $checkboxes.each(function () {
                            const $checkbox = $(this);
                            const state = this.checked ? '1' : '0';
                            if (state !== $checkbox.data('initial').toString()) {
                                $('#cheques-' + state + ' .empty-cheque-row').before($checkbox.closest('tr'));
                                $checkbox.data('initial', state);
                            }
                        });
                        $('.empty-cheque-row').each(function () {
                            const $emptyRow = $(this);
                            const $rows = $emptyRow.siblings().get();
                            $rows.sort((a, b) => a.cells[0].textContent.localeCompare(b.cells[0].textContent));
                            $emptyRow.before($rows).toggleClass('d-none', $rows.length > 0);
                            $emptyRow.closest('.recognition-table').find('.recognition-count').text($rows.length);
                        });
                    },
                    error: function (xhr) {
                        let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong.';
                        $('#alertBox').append($('<div>', { class: 'alert alert-danger', text: msg }));
                    },
                    complete: function () {
                        $checkboxes.prop('disabled', false);
                        $btn.find('span').text('Save Changes');
                        refreshSaveBtn();
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
