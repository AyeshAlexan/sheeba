@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap 5 --}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- jQuery --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>

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
        :root {
            --primary:    #1a3c5e;
            --primary-lt: #2d6a9f;
            --accent:     #f0a500;
            --accent-lt:  #ffc94d;
            --success:    #198754;
            --danger:     #dc3545;
            --bg-page:    #f0f4f8;
            --bg-card:    #ffffff;
            --border:     #d0dae6;
            --text-main:  #1e293b;
            --text-muted: #64748b;
            --radius:     10px;
            --shadow:     0 4px 20px rgba(26,60,94,.10);
            --shadow-lg:  0 8px 32px rgba(26,60,94,.16);
        }

        body { background: var(--bg-page); color: var(--text-main); }

        /* ── Page Header ──────────────────────────────────── */
        .page-header-bar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-lt) 100%);
            border-radius: var(--radius);
            padding: 22px 28px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-lg);
        }
        .page-header-bar h3 {
            color: #fff;
            font-size: 1.35rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: .3px;
        }
        .page-header-bar .subtitle {
            color: rgba(255,255,255,.65);
            font-size: .82rem;
            margin-top: 2px;
        }
        .btn-add {
            background: var(--accent);
            color: var(--primary);
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-weight: 700;
            font-size: .9rem;
            transition: background .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 3px 10px rgba(240,165,0,.35);
            white-space: nowrap;
        }
        .btn-add:hover { background: var(--accent-lt); transform: translateY(-2px); box-shadow: 0 6px 18px rgba(240,165,0,.4); }
        .btn-add i { margin-right: 7px; }

        /* ── Alert ────────────────────────────────────────── */
        .alert-float {
            position: fixed; top: 20px; right: 20px;
            z-index: 9999; min-width: 280px;
            border-radius: 10px;
            box-shadow: 0 4px 18px rgba(0,0,0,.18);
            display: none;
            animation: slideIn .35s ease;
        }
        @keyframes slideIn { from { opacity:0; transform:translateX(40px); } to { opacity:1; transform:translateX(0); } }

        /* ── Card / Table ─────────────────────────────────── */
        .card-table {
            background: var(--bg-card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .card-table .table-responsive { padding: 0; }
        .card-table table thead tr th {
            background: var(--primary);
            color: #fff;
            font-size: .82rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            border: none;
            padding: 14px 16px;
            white-space: nowrap;
        }
        .card-table table tbody tr { transition: background .15s; }
        .card-table table tbody tr:hover { background: #f0f7ff; }
        .card-table table tbody td {
            vertical-align: middle;
            font-size: .9rem;
            padding: 12px 16px;
            border-color: var(--border);
            color: var(--text-main);
        }

        /* badges */
        .badge-code {
            background: #eef4fb; color: var(--primary);
            border: 1px solid #c4d9ef;
            border-radius: 6px; font-size: .78rem;
            padding: 4px 10px; font-weight: 600;
        }
        .amount-cell { font-weight: 700; color: var(--primary); }

        /* action buttons */
        .btn-action {
            border: none; border-radius: 7px;
            padding: 6px 12px; font-size: .82rem;
            font-weight: 600; cursor: pointer;
            transition: opacity .15s, transform .12s;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .btn-action:hover { opacity: .85; transform: translateY(-1px); }
        .btn-edit  { background: #e8f4fd; color: #1565c0; }
        .btn-delete{ background: #fde8e8; color: #c62828; }

        /* ── Modal ────────────────────────────────────────── */
        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-lt) 100%);
            color: #fff;
            border-bottom: none;
            border-radius: calc(var(--radius) - 1px) calc(var(--radius) - 1px) 0 0;
            padding: 18px 24px;
        }
        .modal-header .modal-title { font-weight: 700; font-size: 1.05rem; }
        .modal-header .btn-close { filter: invert(1) brightness(2); opacity: .8; }
        .modal-content { border: none; border-radius: var(--radius); box-shadow: var(--shadow-lg); overflow: hidden; }
        .modal-body { padding: 24px; }
        .modal-footer { border-top: 1px solid var(--border); padding: 14px 24px; }

        /* voucher strip */
        .voucher-strip {
            background: linear-gradient(90deg, #f8fafc, #eef4fb);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px 18px;
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 20px;
            gap: 16px;
            flex-wrap: wrap;
        }
        .voucher-strip label { font-size: .78rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 2px; display: block; }
        .voucher-strip .val { font-size: 1.05rem; font-weight: 700; color: var(--primary); }
        .voucher-strip input.form-control { max-width: 140px; font-weight: 700; color: var(--primary); background: #fff; border: 1px solid var(--border); }

        /* form labels */
        .form-label-custom { font-size: .84rem; font-weight: 600; color: var(--text-main); margin-bottom: 5px; }
        .form-control, .select2-container--bootstrap-5 .select2-selection { border-radius: 8px !important; border: 1px solid var(--border) !important; font-size: .9rem; }
        .form-control:focus { border-color: var(--primary-lt) !important; box-shadow: 0 0 0 3px rgba(45,106,159,.15) !important; }

        /* amount highlight */
        #amount { font-size: 1.05rem; font-weight: 700; color: var(--primary); }

        /* btn save */
        .btn-save {
            background: linear-gradient(135deg, var(--primary), var(--primary-lt));
            color: #fff; border: none;
            padding: 10px 30px; border-radius: 8px;
            font-weight: 700; font-size: .95rem;
            box-shadow: 0 3px 12px rgba(26,60,94,.25);
            transition: opacity .2s, transform .15s;
        }
        .btn-save:hover { opacity: .9; transform: translateY(-1px); }
        .btn-cancel {
            background: #f1f5f9; color: var(--text-muted);
            border: 1px solid var(--border); padding: 10px 22px;
            border-radius: 8px; font-weight: 600; font-size: .9rem;
            transition: background .15s;
        }
        .btn-cancel:hover { background: #e2e8f0; }

        /* section divider inside modal */
        .section-label {
            font-size: .72rem; font-weight: 700; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 1px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 6px; margin-bottom: 16px; margin-top: 4px;
        }

        /* Select2 tweaks */
        .select2-container { width: 100% !important; }
        .select2-container--bootstrap-5 .select2-selection--single { height: 40px !important; }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered { line-height: 38px !important; }

        /* DataTable tweaks */
        div.dataTables_wrapper div.dataTables_filter input { border-radius: 8px; border: 1px solid var(--border); padding: 6px 12px; }
        div.dataTables_wrapper div.dataTables_length select { border-radius: 8px; border: 1px solid var(--border); }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--primary) !important; color: #fff !important; border-color: var(--primary) !important; border-radius: 6px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #eef4fb !important; color: var(--primary) !important; border-color: var(--border) !important; border-radius: 6px;
        }

        /* error block */
        #error-messages { border-radius: 8px; font-size: .88rem; }
    </style>
</head>

<div class="main-wrapper">
    <div class="page-wrapper">
        <div class="content container-fluid py-4">

            {{-- ── Floating Alert ──────────────────────────── --}}
            <div class="alert alert-success alert-float" id="success-message" role="alert">
                <i class="fas fa-check-circle me-2"></i><span id="success-text"></span>
            </div>
            <div class="alert alert-danger alert-float" id="error-toast" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><span id="error-text"></span>
            </div>

            {{-- ── Page Header ─────────────────────────────── --}}
            <div class="page-header-bar">
                <div>
                    <h3><i class="fas fa-wallet me-2" style="opacity:.8;"></i>Customer Opening Balance</h3>
                    <div class="subtitle">Manage and track customer opening balances</div>
                </div>
                <button class="btn-add" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add Customer Balance
                </button>
            </div>

            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- ── Table Card ──────────────────────────────── --}}
            <div class="card-table">
                <div class="table-responsive p-3">
                    <table class="table table-bordered w-100" id="TCustomerBalace">
                        <thead>
                            <tr>
                                <th><i class="fas fa-calendar me-1"></i>Date</th>
                                <th><i class="fas fa-hashtag me-1"></i>Invoice No</th>
                                <th><i class="fas fa-id-badge me-1"></i>Customer Code</th>
                                <th><i class="fas fa-user me-1"></i>Customer Name</th>
                                <th><i class="fas fa-align-left me-1"></i>Description</th>
                                <th><i class="fas fa-coins me-1"></i>Amount</th>
                                <th style="text-align:center;">Actions</th>
                            </tr>
                        </thead>
                    </table>
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
                            <label class="form-label-custom">Customer Name <span class="text-danger">*</span></label>
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
                            <label class="form-label-custom">Customer Code</label>
                            <input type="text" class="form-control" placeholder="Auto-filled" id="customerCode" name="customerCode" readonly
                                   style="background:#f8fafc; font-weight:700; color: var(--primary);">
                        </div>
                    </div>

                    {{-- ── Transaction Section ────────────── --}}
                    <div class="section-label">Transaction Details</div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label-custom">Description</label>
                            <textarea id="description" name="description" class="form-control"
                                      rows="3" placeholder="Enter a description or note…"></textarea>
                        </div>
                    </div>
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label-custom">Amount (LKR) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text" style="background:var(--primary);color:#fff;border-color:var(--primary);">
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
                <button type="button" class="btn-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="submit" class="btn-save" id="btn-save" form="StoreForm">
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
        <div class="modal-content" style="border-radius:12px; overflow:hidden; border:none; box-shadow:var(--shadow-lg);">
            <div class="modal-body text-center p-4">
                <div style="width:64px;height:64px;background:#fde8e8;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="fas fa-trash-alt" style="font-size:1.6rem;color:#c62828;"></i>
                </div>
                <h5 style="font-weight:700;margin-bottom:8px;">Delete Record?</h5>
                <p style="color:var(--text-muted);font-size:.9rem;">This action cannot be undone. The balance and its transaction will be permanently removed.</p>
                <input type="hidden" id="delete-id">
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <button class="btn-cancel" data-bs-dismiss="modal" style="min-width:110px;">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button class="btn-action btn-delete" id="confirm-delete-btn" style="min-width:110px; font-size:.9rem; padding:9px 18px;">
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
    $('#TCustomerBalace').DataTable({
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
              render: d => d ? `<span style="color:var(--text-muted);font-size:.85rem;">${d}</span>` : '—' },
            { data: 'amount',       name: 'amount',
              render: d => `<span class="amount-cell">LKR ${parseFloat(d).toLocaleString('en-LK', {minimumFractionDigits:2})}</span>` },
            { data: 'action',       name: 'action', orderable: false,
              render: (d, t, row) =>
                `<div class="d-flex gap-2 justify-content-center">
                   <button class="btn-action btn-edit"   onclick="editFunc(${row.id})"><i class="fas fa-edit"></i> Edit</button>
                   <button class="btn-action btn-delete" onclick="confirmDelete(${row.id})"><i class="fas fa-trash-alt"></i> Delete</button>
                 </div>`
            },
        ],
        order: [[0, 'desc']],
        language: {
            processing: '<div class="spinner-border text-primary" style="width:2rem;height:2rem;" role="status"></div>',
            search: '<i class="fas fa-search me-1"></i>',
            searchPlaceholder: 'Search records…',
        }
    });
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
<script src="assets/js/script.js"></script>
@endsection