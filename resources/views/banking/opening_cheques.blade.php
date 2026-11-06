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
    <title>Opening Cheques</title>
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
                            <h3 class="page-title">Opening Cheques</h3>
                            <p class="page-subtitle">Cheques already held from customers before go-live. Adding one records it as an asset (Cheques in Hand) and reduces that customer's balance. Which bank account it gets deposited into is picked later, on Cheque Deposit.</p>
                        </div>
                    </div>
                    <div class="ph-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOpeningChequeModal">
                            <i class="fas fa-plus"></i> Add Opening Cheque
                        </button>
                    </div>
                </div>

                <div id="alertBox"></div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Customer</th>
                                        <th>Cheque No</th>
                                        <th>Drawee Bank</th>
                                        <th>Deposited To</th>
                                        <th>Cheque Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cheques as $cheque)
                                    <tr>
                                        <td>{{ $cheque->customer_name }} <br><small class="text-muted">{{ $cheque->customer_code }}</small></td>
                                        <td>{{ $cheque->cheques_no }}</td>
                                        <td>{{ $cheque->drawee_bank ?: '—' }}</td>
                                        <td>{{ $cheque->deposit_account ?: '—' }}</td>
                                        <td>{{ $cheque->release_date }}</td>
                                        <td>{{ number_format((float) $cheque->amount, 2) }}</td>
                                        <td>
                                            @if($cheque->cheque_status === 'PENDING')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($cheque->cheque_status === 'deposit')
                                                <span class="badge bg-success">Deposited</span>
                                            @elseif($cheque->cheque_status === 'return')
                                                <span class="badge bg-danger">Returned</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $cheque->cheque_status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($cheque->cheque_status === 'PENDING')
                                                <button type="button" class="btn btn-outline-danger btn-sm delete-opening-cheque" data-id="{{ $cheque->id }}">Delete</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No opening cheques recorded yet.</td>
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

    {{-- ════════════════════════════════════════════════════════════ --}}
    {{-- MODAL: Add Opening Cheque                                    --}}
    {{-- ════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="addOpeningChequeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-money-check-alt text-primary me-2"></i>Add Opening Cheque</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="errMsgContainer mb-2"></div>
                    <form id="addOpeningChequeForm">
                        @csrf
                        <div class="stock-info-card">
                            <div class="stock-info-grid-3">
                                <div class="si-field">
                                    <label>Customer <span class="text-danger">*</span></label>
                                    <div class="si-icon-wrap customer-code-wrap">
                                        <input type="text" id="oc_customer_display" class="form-control" placeholder="Select customer" readonly>
                                        <button type="button" class="btn btn-primary customer-picker" id="openOpeningChequeCustomerPicker" aria-label="Select customer">
                                            <i class="fas fa-arrow-left" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" id="oc_customer_code" name="customer_code">
                                </div>
                                <div class="si-field">
                                    <label>Cheque No <span class="text-danger">*</span></label>
                                    <input type="text" id="oc_cheques_no" name="cheques_no" class="form-control" placeholder="Cheque No" required>
                                </div>
                                <div class="si-field">
                                    <label>Cheque Date <span class="text-danger">*</span></label>
                                    <input type="date" id="oc_release_date" name="release_date" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <h6 class="mt-3 mb-2 text-muted">Cheque Details (whoever issued it)</h6>
                        <div class="stock-info-card">
                            <div class="stock-info-grid-3">
                                <div class="si-field">
                                    <label>Bank</label>
                                    <input type="text" id="oc_bank" name="bank" class="form-control" placeholder="e.g. Sampath Bank">
                                </div>
                                <div class="si-field">
                                    <label>Branch</label>
                                    <input type="text" id="oc_branch" name="branch" class="form-control" placeholder="Branch">
                                </div>
                                <div class="si-field">
                                    <label>Account No</label>
                                    <input type="text" id="oc_acc_no" name="acc_no" class="form-control" placeholder="Account No">
                                </div>
                            </div>
                        </div>
                        <div class="stock-info-card mt-3">
                            <div class="stock-info-grid-3">
                                <div class="si-field">
                                    <label>Amount <span class="text-danger">*</span></label>
                                    <input type="text" id="oc_amount" name="amount" class="form-control" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary">Save Opening Cheque</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════ --}}
    {{-- MODAL: Select Customer                                       --}}
    {{-- ════════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="selectOpeningChequeCustomerModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title m-2">Search Customer</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="stock-search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" id="openingChequeCustomerSearch" class="form-control" placeholder="Search by name..." aria-label="Search customers by name">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm" id="OpeningChequeCustomerTable">
                            <thead>
                                <tr class="table-secondary">
                                    <th>Code</th><th>Name</th><th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $c)
                                <tr>
                                    <td>{{ $c->Code }}</td>
                                    <td>{{ $c->First_name }}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="dt-act-btn select-opening-cheque-customer"
                                            data-customer-code="{{ $c->Code }}" data-customer-name="{{ $c->First_name }}" title="Select customer" aria-label="Select customer {{ $c->Code }}">
                                            <i class="fas fa-plus" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    $(document).ready(function () {
        var customerTable = $('#OpeningChequeCustomerTable').DataTable({
            paging: true, searching: true, ordering: true, info: false,
            lengthChange: false, pageLength: 5, dom: 't'
        });
        $('#openingChequeCustomerSearch').on('input', function () {
            customerTable.search(this.value).draw();
        });

        // Bootstrap doesn't cleanly support two stacked modals — hiding the
        // picker while the Add form is still open strips the body's modal
        // state and silently kills the Add modal too. So only one is ever
        // shown at a time: hide Add, show the picker; when the picker
        // closes (picked or cancelled), bring Add back.
        var addChequeModalEl = document.getElementById('addOpeningChequeModal');
        var pickerModalEl    = document.getElementById('selectOpeningChequeCustomerModal');

        $('#openOpeningChequeCustomerPicker').on('click', function () {
            bootstrap.Modal.getOrCreateInstance(addChequeModalEl).hide();
            bootstrap.Modal.getOrCreateInstance(pickerModalEl).show();
        });

        $(pickerModalEl).on('hidden.bs.modal', function () {
            bootstrap.Modal.getOrCreateInstance(addChequeModalEl).show();
        });

        $(document).on('click', '.select-opening-cheque-customer', function () {
            $('#oc_customer_code').val($(this).data('customer-code'));
            $('#oc_customer_display').val($(this).data('customer-code') + ' — ' + $(this).data('customer-name'));
            bootstrap.Modal.getOrCreateInstance(pickerModalEl).hide();
        });

        $('#addOpeningChequeForm').on('submit', function (e) {
            e.preventDefault();
            if (!$('#oc_customer_code').val()) {
                $('.errMsgContainer').html('<div class="alert alert-danger">Please select a customer.</div>');
                return;
            }
            $.ajax({
                url: "{{ route('opening.cheques.store') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    window.location.reload();
                },
                error: function (xhr) {
                    let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong.';
                    $('.errMsgContainer').html('<div class="alert alert-danger">' + msg + '</div>');
                }
            });
        });

        $(document).on('click', '.delete-opening-cheque', function () {
            if (!confirm('Remove this opening cheque? This will also reverse its accounting entries.')) return;
            let id = $(this).data('id');
            $.ajax({
                url: "{{ route('opening.cheques.destroy', ['id' => '__ID__']) }}".replace('__ID__', id),
                method: 'DELETE',
                success: function (res) {
                    $('#alertBox').html('<div class="alert alert-success">' + res.message + '</div>');
                    setTimeout(function () { window.location.reload(); }, 600);
                },
                error: function (xhr) {
                    let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong.';
                    $('#alertBox').html('<div class="alert alert-danger">' + msg + '</div>');
                }
            });
        });
    });
</script>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
@endsection

</html>
