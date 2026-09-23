@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- jQuery + Bootstrap 5 --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- DataTables --}}
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">

    <title>Customer Opening Balance</title>

    <style>
        /* ── Alert toasts ─────────────────────────────────── */
        .alert-float {
            position: fixed; top: 20px; right: 20px;
            z-index: 9999; min-width: 280px;
            border-radius: 10px;
            box-shadow: 0 4px 18px rgba(0,0,0,.18);
            display: none;
            animation: slideIn .35s ease;
        }
        @keyframes slideIn { from { opacity:0; transform:translateX(40px); } to { opacity:1; transform:translateX(0); } }

        /* ── Table cell accents ──────────────────────────── */
        .badge-code {
            background: var(--tr-blue-light); color: var(--tr-blue);
            border: 1px solid #c4d9ef;
            border-radius: 6px; font-size: .78rem;
            padding: 4px 10px; font-weight: 600;
        }
        .amount-cell { font-weight: 700; color: var(--tr-navy); }

        /* ── Voucher strip (inside modal) ────────────────── */
        .voucher-strip {
            background: var(--tr-bg);
            border: 1px solid var(--tr-border);
            border-radius: 10px;
            padding: 12px 18px;
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
            gap: 16px;
            flex-wrap: wrap;
        }
        .voucher-strip label { font-size: .78rem; color: var(--tr-text-secondary); font-weight: 600; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px; display: block; }
        .voucher-strip input.form-control { max-width: 140px; font-weight: 700; color: var(--tr-navy); background: #fff; }

        .section-label {
            font-size: .72rem; font-weight: 700; color: var(--tr-text-secondary);
            text-transform: uppercase; letter-spacing: 1px;
            border-bottom: 1px solid var(--tr-border);
            padding-bottom: 6px; margin-bottom: 16px; margin-top: 4px;
        }

        /* Select2 sizing to match .form-control height */
        .select2-container { width: 100% !important; }
        .select2-container--bootstrap-5 .select2-selection--single { height: 42px !important; border-radius: 10px !important; }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered { line-height: 40px !important; }

        #error-messages { border-radius: 8px; font-size: .88rem; }
    </style>
</head>

<div class="main-wrapper">
    <div class="page-wrapper">
        <div class="content container-fluid">

            {{-- ── Floating Alerts ─────────────────────────── --}}
            <div class="alert alert-success alert-float" id="success-message" role="alert">
                <i class="fas fa-check-circle me-2"></i><span id="success-text"></span>
            </div>
            <div class="alert alert-danger alert-float" id="error-toast" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><span id="error-text"></span>
            </div>

            <div class="page-header ph-flex">
                <div class="ph-left">
                    <div class="ph-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.59 13.41L11 3.83V3H3v8h.83l9.58 9.59a2 2 0 0 0 2.83 0l4.35-4.35a2 2 0 0 0 0-2.83z"/><circle cx="6.5" cy="6.5" r="1"/></svg>
                    </div>
                    <div>
                        <h3 class="page-title">Customer Opening Balance</h3>
                        <p class="page-subtitle">Manage and track customer opening balances</p>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add Customer Balance
                </button>
            </div>

            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="TCustomerBalace">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice No</th>
                                    <th>Customer Code</th>
                                    <th>Customer Name</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th style="text-align:center;">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="TCustomerBalaceCustomPager"></div>
                </div>
            </div>

        </div>{{-- /content --}}
        @include('layouts.footer')
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     ADD / EDIT MODAL
═══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="Store-modal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">
                    <i class="fas fa-plus-circle me-2"></i>Add Customer Opening Balance
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="javascript:void(0)" id="StoreForm" name="StoreForm" method="POST" enctype="multipart/form-data">
                    <div id="error-messages" class="alert alert-danger mb-3" style="display:none;" role="alert"></div>
                    <input type="hidden" name="id" id="id">

                    {{-- ── Voucher Strip ──────────────────── --}}
                    <div class="voucher-strip">
                        <div>
                            <label>Voucher No.</label>
                            <?php $formattedNumber = str_pad($maxCustomer + 1, 4, '0', STR_PAD_LEFT); ?>
                            <input type="text" id="invoice_no" name="invoice_no"
                                   value="<?= $formattedNumber ?>"
                                   class="form-control val" style="width:120px;">
                        </div>
                        <div>
                            <label>Date</label>
                            <input type="date" id="date" name="date" class="form-control" style="width:165px;">
                        </div>
                    </div>

                    {{-- ── Customer Section ──────────────── --}}
                    <div class="section-label">Customer Details</div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <select class="form-control" name="customerName" id="customerName">
                                <option value="">— Select Customer —</option>
                                @foreach($Customer as $DepartmentData)
                                    <option value="{{ $DepartmentData->First_name }}">
                                        {{ $DepartmentData->First_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Customer Code</label>
                            <input type="text" class="form-control" placeholder="Auto-filled" id="customerCode" name="customerCode" readonly
                                   style="background:var(--tr-bg); font-weight:700; color: var(--tr-navy);">
                        </div>
                    </div>

                    {{-- ── Transaction Section ────────────── --}}
                    <div class="section-label">Transaction Details</div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control"
                                      rows="3" placeholder="Enter a description or note…"></textarea>
                        </div>
                    </div>
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label">Amount (LKR) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:var(--tr-blue);color:#fff;border-color:var(--tr-blue);">
                                    <i class="fas fa-rupee-sign"></i>
                                </span>
                                <input type="number" class="form-control" placeholder="0.00"
                                       id="amount" name="amount" min="0" step="0.01">
                            </div>
                        </div>
                    </div>

                    {{-- Hidden user fields --}}
                    <input type="hidden" id="OC" name="OC" value="{{ Auth::user()->username }}">
                    <input type="hidden" id="BC" name="BC" value="{{ Auth::user()->BC }}">
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="submit" class="btn btn-primary" id="btn-save" form="StoreForm">
                    <i class="fas fa-save me-1"></i>Save Changes
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     DELETE CONFIRM MODAL
═══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="delete-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="border-radius:12px; overflow:hidden; border:none;">
            <div class="modal-body text-center p-4">
                <div style="width:64px;height:64px;background:#FEECEC;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="fas fa-trash-alt" style="font-size:1.6rem;color:var(--tr-danger);"></i>
                </div>
                <h5 style="font-weight:700;margin-bottom:8px;">Delete Record?</h5>
                <p style="color:var(--tr-text-secondary);font-size:.9rem;">This action cannot be undone. The balance and its transaction will be permanently removed.</p>
                <input type="hidden" id="delete-id">
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal" style="min-width:110px;">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button class="dt-act-btn dt-act-delete" id="confirm-delete-btn" style="min-width:110px; width:auto; height:auto; border-radius:8px; font-size:.9rem; padding:9px 18px;">
                        <i class="fas fa-trash-alt me-1"></i>Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     SCRIPTS
═══════════════════════════════════════════════════════════ --}}
<script src="assets/js/dt-custom-pager.js"></script>
<script>
// ── CSRF setup ──────────────────────────────────────────────
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

// ── Select2 init ─────────────────────────────────────────────
$(document).ready(function () {
    $('#customerName').select2({
        theme: 'bootstrap-5',
        placeholder: '— Search or select customer —',
        allowClear: true,
        dropdownParent: $('#Store-modal'),
        width: '100%'
    });
});

// ── Auto-fill date on modal open ─────────────────────────────
$('#Store-modal').on('show.bs.modal', function () {
    if (!$('#id').val()) {
        $('#date').val(new Date().toISOString().split('T')[0]);
    }
});

// ── DataTable ─────────────────────────────────────────────────
$(document).ready(function () {
    var TCustomerBalaceTable = $('#TCustomerBalace').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('customer_opening_balance') }}",
        columns: [
            { data: 'date',         name: 'date' },
            { data: 'invoice_no',   name: 'invoice_no' },
            { data: 'customerCode', name: 'customerCode',
              render: d => `<span class="badge-code">${d || '—'}</span>` },
            { data: 'customerName', name: 'customerName' },
            { data: 'description',  name: 'description',
              render: d => d ? `<span style="color:var(--tr-text-secondary);font-size:.85rem;">${d}</span>` : '—' },
            { data: 'amount',       name: 'amount',
              render: d => `<span class="amount-cell">LKR ${parseFloat(d).toLocaleString('en-LK', {minimumFractionDigits:2})}</span>` },
            { data: 'action',       name: 'action', orderable: false,
              render: (d, t, row) =>
                `<div class="dt-actions justify-content-center">
                   <a href="javascript:void(0)" class="dt-act-btn dt-act-edit" title="Edit" onclick="editFunc(${row.id})"><i class="far fa-edit"></i></a>
                   <a href="javascript:void(0)" class="dt-act-btn dt-act-delete" title="Delete" onclick="confirmDelete(${row.id})"><i class="far fa-trash-alt"></i></a>
                 </div>`
            },
        ],
        order: [[0, 'desc']],
        pageLength: 15,
        lengthChange: false,
        language: {
            processing: '<div class="spinner-border text-primary" style="width:2rem;height:2rem;" role="status"></div>',
        }
    });

    $('#TCustomerBalace_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(TCustomerBalaceTable, '#TCustomerBalaceCustomPager');
});

// ── Open Add Modal ────────────────────────────────────────────
function openAddModal() {
    $('#StoreForm')[0].reset();
    $('#customerName').val(null).trigger('change');
    $('#id').val('');
    $('#modalTitle').html('<i class="fas fa-plus-circle me-2"></i>Add Customer Opening Balance');
    $('#btn-save').html('<i class="fas fa-save me-1"></i>Save Changes');
    $('#error-messages').hide();
    $('#Store-modal').modal('show');
}

// ── Edit ──────────────────────────────────────────────────────
function editFunc(id) {
    $.ajax({
        type: 'POST',
        url: "{{ url('UpdateCustomerOpeningBalance') }}",
        data: { id },
        dataType: 'json',
        success: function (res) {
            $('#modalTitle').html('<i class="fas fa-edit me-2"></i>Edit Customer Opening Balance');
            $('#btn-save').html('<i class="fas fa-save me-1"></i>Update Changes');
            $('#id').val(res.id);
            $('#invoice_no').val(res.invoice_no);
            $('#date').val(res.date);
            $('#customerCode').val(res.customerCode);

            // Select2: set value
            if (!$('#customerName option[value="'+res.customerName+'"]').length) {
                $('#customerName').append(new Option(res.customerName, res.customerName, true, true));
            }
            $('#customerName').val(res.customerName).trigger('change');

            $('#description').val(res.description);
            $('#amount').val(res.amount);
            $('#error-messages').hide();
            $('#Store-modal').modal('show');
        },
        error: function () {
            showToast('error', 'Failed to load record. Please try again.');
        }
    });
}

// ── Delete confirm ────────────────────────────────────────────
function confirmDelete(id) {
    $('#delete-id').val(id);
    $('#delete-modal').modal('show');
}

$('#confirm-delete-btn').on('click', function () {
    const id = $('#delete-id').val();
    $('#delete-modal').modal('hide');
    $.ajax({
        type: 'POST',
        url: "{{ url('DeleteCustomerOpeningBalance') }}",
        data: { id },
        dataType: 'json',
        success: function () {
            $('#TCustomerBalace').DataTable().ajax.reload(null, false);
            showToast('success', 'Record deleted successfully.');
        },
        error: function () {
            showToast('error', 'Failed to delete. Please try again.');
        }
    });
});

// ── Form Submit ───────────────────────────────────────────────
$('#StoreForm').on('submit', function (e) {
    e.preventDefault();
    $('#btn-save').html('<span class="spinner-border spinner-border-sm me-2"></span>Saving…').prop('disabled', true);
    const formData = new FormData(this);
    $.ajax({
        type: 'POST',
        url: "{{ url('addCustomerOpeningBalance') }}",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function () {
            $('#Store-modal').modal('hide');
            $('#TCustomerBalace').DataTable().ajax.reload(null, false);
            $('#btn-save').html('<i class="fas fa-save me-1"></i>Save Changes').prop('disabled', false);
            showToast('success', 'Customer opening balance saved successfully!');
        },
        error: function (xhr) {
            const errors = xhr.responseJSON?.errors || {};
            let html = '<ul class="mb-0">';
            $.each(errors, (k, v) => { html += `<li>${v[0]}</li>`; });
            html += '</ul>';
            $('#error-messages').html(html).show();
            $('#btn-save').html('<i class="fas fa-save me-1"></i>Save Changes').prop('disabled', false);
        }
    });
});

// ── Auto-fill customer code ────────────────────────────────────
$('#customerName').on('change', function () {
    const category = $(this).val();
    if (!category) { $('#customerCode').val(''); return; }
    $.ajax({
        url: "{{ route('show_CustomerCode_ajax') }}",
        method: 'GET',
        data: { "_token": "{{ csrf_token() }}", category },
        success: function (res) {
            if (res.status === 'success' && res.data.length > 0) {
                $('#customerCode').val(res.data[0].Code);
            } else {
                $('#customerCode').val('');
            }
        }
    });
});

// ── Toast helper ──────────────────────────────────────────────
function showToast(type, msg) {
    if (type === 'success') {
        $('#success-text').text(msg);
        $('#success-message').show();
        setTimeout(() => $('#success-message').fadeOut('slow'), 3500);
    } else {
        $('#error-text').text(msg);
        $('#error-toast').show();
        setTimeout(() => $('#error-toast').fadeOut('slow'), 3500);
    }
}
</script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/js/script.js"></script>
@endsection
