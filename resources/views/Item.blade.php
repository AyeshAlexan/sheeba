@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Item Details</title>
</head>

<style>
    /* ── Item modal: section cards ── */
    .item-section-card {
        border: 1px solid var(--tr-border); border-radius: 14px;
        padding: 18px; margin-bottom: 18px; background: var(--tr-white);
    }
    .item-section-card h6 {
        font-size: 13px; font-weight: 700; color: var(--tr-navy);
        text-transform: uppercase; letter-spacing: .03em;
        margin-bottom: 14px; display: flex; align-items: center; gap: 8px;
    }
    .item-section-card h6 i { color: var(--tr-blue); font-size: 12px; }

    /* Quick-create links next to Category / Department labels */
    .quick-create-link {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 11.5px; font-weight: 600; color: var(--tr-blue);
        text-decoration: none !important; background: var(--tr-blue-light);
        border-radius: 20px; padding: 2px 10px; margin-left: 6px;
        vertical-align: middle; transition: opacity .15s;
    }
    .quick-create-link:hover { opacity: .75; }

    /* ── Category Preview (collapsed by default — click header to expand) ── */
    #category-items-preview {
        display:none; border:1px solid var(--tr-border);
        border-radius:12px; background:var(--tr-white); overflow:hidden;
    }
    #category-items-preview .preview-header {
        width:100% !important; box-sizing:border-box !important; border:none !important;
        background:var(--tr-blue) !important; color:#fff; padding:8px 14px !important;
        margin:0 !important; border-radius:0 !important; height:auto !important;
        font-weight:600; font-size:12.5px; display:flex !important; cursor:pointer;
        justify-content:space-between; align-items:center; text-align:left;
    }
    #category-items-preview table thead th { background:var(--tr-blue-light); font-size:12px; }
    #cat-item-count { background:#fff; color:var(--tr-blue); border-radius:10px; padding:1px 8px; font-size:12px; font-weight:700; }
    .preview-toggle-icon { font-size:11px; transition:transform .15s; }
    #category-items-preview.expanded .preview-toggle-icon { transform:rotate(180deg); }
    #category-items-preview .preview-body { padding:8px; }

    /* ── Small helper text under a field ── */
    .item-field-hint { display:block; font-size:10.5px; color:var(--tr-text-secondary); margin-top:4px; }

    /* ── "Batch" tag — shown next to the item name wherever a batch-tracked item appears ── */
    .batch-tag {
        display:inline-flex; align-items:center; gap:4px;
        background:var(--tr-blue-light); color:var(--tr-blue);
        border-radius:20px; padding:1px 9px; margin-left:6px;
        font-size:10.5px; font-weight:700; white-space:nowrap; vertical-align:middle;
    }
    .batch-tag i { font-size:9px; }

    /* ── Tabs (pill style) ── */
    .item-mode-tabs {
        display: flex; gap: 4px; background: var(--tr-bg); border: none;
        border-radius: 12px; padding: 4px; margin-bottom: 20px;
    }
    .item-mode-tabs .nav-item { flex: 1; }
    .item-mode-tabs .nav-link {
        border: none !important; border-radius: 9px !important;
        color: var(--tr-text-secondary); font-weight: 600; font-size: 13.5px;
        text-align: center; padding: 9px 14px; width: 100%;
        transition: background .15s, color .15s;
    }
    .item-mode-tabs .nav-link.active {
        background: var(--tr-white) !important; color: var(--tr-blue) !important;
        box-shadow: 0 2px 8px rgba(20,33,61,.08);
    }

    /* ── Package Section ── */
    #package-add-section { display:none; margin-top:10px; }

    .pkg-header-box {
        background: var(--tr-blue-light);
        border:1px solid var(--tr-border); border-radius:12px; padding:16px; margin-bottom:16px;
    }
    .pkg-header-box label { font-size:13px; font-weight:600; color:var(--tr-navy); }

    .pkg-name-input {
        font-size:15px !important; font-weight:600 !important;
        background:#fff !important; border:2px solid var(--tr-blue) !important;
        border-radius:9px !important; padding:8px 14px !important; color:var(--tr-navy) !important;
    }
    .pkg-name-input:focus { box-shadow:0 0 0 3px rgba(22,119,255,.15) !important; outline:none; }

    /* ── Search ── */
    .pkg-search-wrapper { position:relative; }
    .pkg-search-input {
        background:var(--tr-white) !important; border:1px solid var(--tr-border) !important;
        border-radius:9px !important; font-size:13px !important; padding:8px 12px !important;
    }
    .pkg-search-input:focus { border-color:var(--tr-blue) !important; outline:none; }

    /* ── Autocomplete ── */
    .pkg-ac-dropdown {
        display:none; position:absolute; top:100%; left:0; right:0; z-index:9999;
        background:#fff; border:1px solid var(--tr-border); border-radius:0 0 10px 10px;
        max-height:220px; overflow-y:auto; box-shadow:0 6px 20px rgba(0,0,0,.12);
    }
    .pkg-ac-item {
        padding:8px 12px; cursor:pointer; font-size:12px; border-bottom:1px solid var(--tr-border);
        display:flex; align-items:center; gap:8px; transition:background .15s;
    }
    .pkg-ac-item:hover { background:var(--tr-blue-light); }
    .pkg-ac-item .ac-code {
        background:var(--tr-blue); color:#fff; border-radius:4px;
        padding:2px 7px; font-size:11px; white-space:nowrap; flex-shrink:0;
    }
    .pkg-ac-item .ac-desc { flex:1; color:var(--tr-text); }
    .pkg-ac-item .ac-price { color:var(--tr-navy); font-weight:700; white-space:nowrap; flex-shrink:0; }
    .pkg-ac-empty,.pkg-ac-loading { padding:10px 12px; font-size:12px; color:var(--tr-text-muted); text-align:center; }

    /* ── Package table ── */
    .pkg-items-table thead th {
        background:#F8FAFC; color:var(--tr-navy);
        font-size:12px; padding:8px 10px; white-space:nowrap;
    }
    .pkg-items-table tbody td { padding:6px 10px; vertical-align:middle; }
    .pkg-items-table tbody tr:hover { background:var(--tr-bg); }
    .pkg-items-table .code-badge {
        background:var(--tr-blue-light); color:var(--tr-blue);
        border-radius:4px; padding:2px 8px; font-size:11px; font-weight:600; white-space:nowrap;
    }

    /* ── Price inputs inside table — override global padding ── */
    .pkg-price-input {
        background:var(--tr-white) !important;
        font-size:13px !important;
        padding:4px 8px !important;
        border-radius:7px !important;
        width:110px !important;
        border:1px solid var(--tr-border) !important;
        box-sizing:border-box;
    }
    .pkg-price-input:focus {
        border-color:var(--tr-blue) !important;
        outline:none;
        box-shadow:0 0 0 2px rgba(22,119,255,.12) !important;
    }
    .pkg-price-input.is-invalid { border-color:var(--tr-danger) !important; }

    /* ── Qty input inside package table ── */
    .pkg-qty-input {
        background:var(--tr-white) !important;
        font-size:13px !important;
        padding:4px 4px !important;
        border-radius:7px !important;
        width:65px !important;
        border:1px solid var(--tr-border) !important;
        box-sizing:border-box;
        text-align:center;
    }
    .pkg-qty-input:focus {
        border-color:var(--tr-blue) !important;
        outline:none;
        box-shadow:0 0 0 2px rgba(22,119,255,.12) !important;
    }
    /* hide browser number spinners for cleaner look */
    .pkg-qty-input::-webkit-inner-spin-button,
    .pkg-qty-input::-webkit-outer-spin-button { -webkit-appearance:none; margin:0; }
    .pkg-qty-input[type=number] { -moz-appearance:textfield; }

    .pkg-empty-row td { text-align:center; color:var(--tr-text-muted); font-size:13px; padding:24px !important; }
    .btn-remove-row {
        background:#FEECEC; color:var(--tr-danger); border:none;
        border-radius:50%; width:26px; height:26px; font-size:12px; cursor:pointer;
    }

    /* ── Summary bar ── */
    .pkg-summary-bar {
        background:var(--tr-bg);
        border:1px solid var(--tr-border); border-radius:12px;
        padding:12px 16px; display:flex; align-items:center;
        gap:20px; margin-top:12px; flex-wrap:wrap;
    }
    .pkg-summary-bar .s-stat { text-align:center; min-width:110px; }
    .pkg-summary-bar .s-label { font-size:11px; color:var(--tr-text-secondary); font-weight:600; }
    .pkg-summary-bar .s-value { font-size:17px; font-weight:700; color:var(--tr-navy); }

    #pkg-save-btn {
        background:var(--tr-success); color:#fff; border:none;
        border-radius:9px; padding:10px 28px; font-size:14.5px;
        font-weight:600; cursor:pointer; transition:background .2s;
    }
    #pkg-save-btn:hover { background:#128A3E; }
    #pkg-save-btn:disabled { background:#aaa; cursor:not-allowed; }

    /* ── Item_set_bulk preview badge ── */
    .bulk-preview {
        font-size:11px; color:var(--tr-text-secondary); margin-top:6px;
        background:var(--tr-bg); border:1px dashed var(--tr-border);
        border-radius:6px; padding:6px 10px; word-break:break-all;
        display:none;
    }
    .bulk-preview span { color:var(--tr-navy); font-weight:700; }

    /* ── Feature toggle cards (Sales by Decimals / Serial Number / Batch Tracked / Inactive) ── */
    .feature-toggle-grid {
        display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
        grid-auto-rows:1fr; gap:10px; margin-top:4px;
    }
    .feature-toggle-card {
        display:flex; align-items:flex-start; gap:12px;
        border:1px solid var(--tr-border); border-radius:12px;
        padding:12px 14px; cursor:pointer; background:var(--tr-white);
        transition:border-color .15s, background .15s, box-shadow .15s;
        user-select:none; height:100%;
    }
    .feature-toggle-card:hover { border-color:var(--tr-blue); }
    .feature-toggle-card .ftc-icon {
        flex:0 0 auto; width:38px; height:38px; border-radius:10px;
        background:var(--tr-bg); color:var(--tr-text-secondary);
        display:flex; align-items:center; justify-content:center;
        font-size:16px; transition:background .15s, color .15s;
    }
    .feature-toggle-card .ftc-title {
        font-size:13.5px; font-weight:700; color:var(--tr-navy);
        display:flex; align-items:center; gap:6px;
    }
    .feature-toggle-card .ftc-desc {
        font-size:11.5px; color:var(--tr-text-secondary); margin-top:2px; line-height:1.4;
    }
    .feature-toggle-card.active {
        border-color:var(--tr-blue); background:var(--tr-blue-light);
        box-shadow:0 0 0 1px var(--tr-blue) inset;
    }
    .feature-toggle-card.active .ftc-icon { background:var(--tr-blue); color:#fff; }
    .feature-toggle-card .ftc-check {
        margin-left:auto; flex:0 0 auto; font-size:16px; color:var(--tr-border); margin-top:2px;
    }
    .feature-toggle-card.active .ftc-check { color:var(--tr-blue); }

    /* ── Item modal chrome: header / body / footer ── */
    .item-modal-content { border-radius:16px; border:none; overflow:hidden; box-shadow:0 20px 50px rgba(20,33,61,.18); }
    .item-modal-header {
        background:var(--tr-navy); border-bottom:none; padding:18px 24px;
    }
    .item-modal-header-left { display:flex; align-items:center; gap:14px; }
    .item-modal-header-icon {
        width:42px; height:42px; border-radius:11px; flex:0 0 auto;
        background:rgba(255,255,255,.12); color:#fff;
        display:flex; align-items:center; justify-content:center; font-size:18px;
    }
    .item-modal-header h5.modal-title { color:#ffffff !important; font-weight:700; }
    .item-modal-header-subtitle { color:rgba(255,255,255,.65); font-size:12px; }
    .item-modal-header .btn-close {
        filter:invert(1) grayscale(100%) brightness(200%); opacity:.75;
        width:34px; height:34px; border-radius:50%;
        display:flex; align-items:center; justify-content:center;
        background-color:rgba(255,255,255,.1); transition:background-color .15s, opacity .15s;
    }
    .item-modal-header .btn-close:hover { opacity:1; background-color:rgba(255,255,255,.18); }
    .item-modal-body { background:var(--tr-bg); padding:22px 24px 0; max-height:72vh; overflow-y:auto; }

    .item-section-hint { font-size:11.5px; color:var(--tr-text-secondary); margin:-8px 0 12px; }

    /* Custom caret so <select class="form-control"> is visually distinguishable from a text input */
    select.form-control {
        appearance:none; -webkit-appearance:none; -moz-appearance:none;
        background-image:url("data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='%2362748A' d='M4 6l4 4 4-4H4z'/%3E%3C/svg%3E");
        background-repeat:no-repeat; background-position:right 12px center; background-size:14px;
        padding-right:34px !important;
    }

    .item-modal-footer-actions {
        display:flex; justify-content:flex-end; gap:10px;
        position:sticky; bottom:0; margin:20px -24px 0; padding:16px 24px;
        background:var(--tr-bg); border-top:1px solid var(--tr-border);
    }
    .item-modal-footer-actions .btn { border-radius:9px; padding:9px 22px; font-weight:600; font-size:13.5px; }

    /* ── Image upload dropzone ── */
    .item-image-upload { display:flex; align-items:center; gap:20px; flex-wrap:wrap; }
    .item-image-dropzone {
        position:relative; width:100%; max-width:220px; height:140px;
        border:1.5px dashed var(--tr-border); border-radius:12px;
        background:var(--tr-white); cursor:pointer; overflow:hidden;
        display:flex; align-items:center; justify-content:center; flex:0 0 auto;
        transition:border-color .15s, background .15s;
    }
    .item-image-dropzone:hover { border-color:var(--tr-blue); background:var(--tr-blue-light); }
    .item-image-dropzone #preview-image {
        display:none; width:100%; height:100%; object-fit:cover;
    }
    .item-image-upload-hint {
        flex:1 1 220px; font-size:12px; color:var(--tr-text-secondary); line-height:1.6;
    }
    .item-image-upload-hint strong { color:var(--tr-navy); display:block; font-size:13px; margin-bottom:3px; }
    .item-image-placeholder {
        display:flex; flex-direction:column; align-items:center; gap:4px;
        color:var(--tr-text-secondary); text-align:center; padding:0 12px;
    }
    .item-image-placeholder i { font-size:22px; color:var(--tr-blue); margin-bottom:2px; }
    .item-image-placeholder span { font-size:12.5px; font-weight:600; color:var(--tr-navy); }
    .item-image-placeholder small { font-size:10.5px; }
</style>

<body>
<div class="main-wrapper">
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header ph-flex">
                <div class="ph-left">
                    <div class="ph-icon">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" y1="22" x2="12" y2="12"/></svg>
                    </div>
                    <div>
                        <h3 class="page-title">Item Details</h3>
                        <p class="page-subtitle">Manage products, prices, and item bundles</p>
                    </div>
                </div>
                <a class="btn btn-primary" onclick="add()" href="javascript:void(0)">
                    <i class="fas fa-plus"></i> Add Item
                </a>
            </div>

            <div class="container-fluid px-0">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success"><p>{{ $message }}</p></div>
                            @endif

                            <div class="card">
                                <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="Item">
                                        <thead>
                                            <tr>
                                                <th>Action</th><th>Category</th><th>Department</th>
                                                <th>Code</th><th>BarCode</th><th>Name</th><th>Per</th>
                                                <th>Purchase Price</th><th>Sales Price</th>
                                                <th>Border Price</th><th>Recorder Quantity</th>
                                                <th>Reorder Level</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div id="itemCustomPager"></div>
                                </div>
                            </div>
            </div>
        </div>
        @include('layouts.footer')
    </div>
</div>


{{-- ============================================================
     MAIN ADD / EDIT ITEM MODAL
============================================================ --}}
<div class="modal fade" id="Item-modal" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content item-modal-content">
            <div class="modal-header item-modal-header">
                <div class="item-modal-header-left">
                    <div class="item-modal-header-icon"><i class="fas fa-box"></i></div>
                    <div>
                        <h5 class="modal-title mb-0" id="ItemModal">Add Item</h5>
                        <p class="item-modal-header-subtitle mb-0">Fill in the product details below</p>
                    </div>
                </div>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body item-modal-body">

                {{-- ── Mode Tabs ── --}}
                <ul class="nav nav-tabs item-mode-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-single"
                           href="javascript:void(0)" onclick="switchMode('single')">
                            <i class="fa fa-plus-circle me-1"></i> Single Item
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-package"
                           href="javascript:void(0)" onclick="switchMode('package')">
                            <i class="fa fa-box-open me-1"></i> Set Items
                        </a>
                    </li>
                </ul>


                {{-- ══════════════════════════════════
                     SINGLE ITEM FORM
                ══════════════════════════════════ --}}
                <div id="single-item-section">
                    <div id="error-messages" class="alert alert-danger" style="display:none;"></div>

                    <form action="javascript:void(0)" id="ItemForm" name="ItemForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">

                        <div class="item-section-card">
                            <h6><i class="fas fa-info-circle"></i> Basic Information</h6>
                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label class="form-label">Category
                                        <a href="{{ ('Category') }}" target="_blank" class="quick-create-link"><i class="fas fa-plus"></i> New Category <i class="fas fa-external-link-alt" style="font-size:9px;"></i></a>
                                    </label>
                                    <select class="form-control" name="category" id="category">
                                        <option value="">Please Select</option>
                                        @foreach($Category as $categoryData)
                                            <option value="{{ $categoryData->description }}"
                                                    data-code="{{ $categoryData->Cate_code }}">
                                                {{ $categoryData->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-6">
                                    <label class="form-label">Department
                                        <a href="{{ ('Department') }}" target="_blank" class="quick-create-link"><i class="fas fa-plus"></i> New Department <i class="fas fa-external-link-alt" style="font-size:9px;"></i></a>
                                    </label>
                                    <select class="select form-control" name="Department" id="Department">
                                        <option value="">Please Select</option>
                                        @foreach($Department as $DepartmentData)
                                            <option value="{{ $DepartmentData->description }}">
                                                {{ $DepartmentData->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div id="category-items-preview" class="mb-3">
                                <button type="button" class="preview-header" id="category-preview-toggle">
                                    <span id="preview-category-name">Items in Category</span>
                                    <span class="d-flex align-items-center gap-2">
                                        <span id="cat-item-count">0 items</span>
                                        <i class="fas fa-chevron-down preview-toggle-icon"></i>
                                    </span>
                                </button>
                                <div class="preview-body" id="category-preview-body" style="display:none;">
                                    <div class="table-responsive" style="max-height:200px;overflow-y:auto;">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th><th>Item Code</th>
                                                    <th>Description</th><th>Purchase Price</th><th>Sale Price</th>
                                                </tr>
                                            </thead>
                                            <tbody id="cat-items-body">
                                                <tr><td colspan="5" class="text-center text-muted">No items found</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-sm-6">
                                    <label class="form-label">Item Code <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="single_cate_code" class="form-control"
                                               placeholder="Category Code" readonly>
                                        <input type="text" id="single_item_number" class="form-control"
                                               placeholder="Item Number">
                                    </div>
                                    <small class="item-field-hint">Auto-filled category prefix + the number you type, e.g. SS-IC001</small>
                                    <input type="hidden" name="Item_code" id="Item_code">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label">Model No</label>
                                    <input type="text" class="form-control" id="Bar_code"
                                           name="Bar_code" placeholder="Bar Code" maxlength="50">
                                </div>
                            </div>

                            <div class="mb-0">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="Item_description"
                                       name="Item_description" placeholder="Description" maxlength="150" required>
                            </div>
                        </div>

                        <div class="item-section-card">
                            <h6><i class="fas fa-layer-group"></i> Classification</h6>
                            <div class="row g-3 mb-3">
                                <div class="col-sm-4">
                                    <label class="form-label">Brand</label>
                                    <select class="form-control" name="Brand" id="Brand">
                                        <option value="">Please Select</option>
                                        @foreach($Brand as $b)
                                            <option value="{{ $b->Brand_name }}">{{ $b->Brand_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Color</label>
                                    <select class="form-control" name="Color" id="Color">
                                        <option value="">Please Select</option>
                                        @foreach($Color as $c)
                                            <option value="{{ $c->Color_name }}">{{ $c->Color_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label">Make</label>
                                    <select class="form-control" name="Make" id="Make">
                                        <option value="">Please Select</option>
                                        @foreach($Make as $m)
                                            <option value="{{ $m->Make_name }}">{{ $m->Make_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Per (Unit of Measure)</label>
                                <input type="text" class="form-control" id="Per"
                                       name="Per" placeholder="e.g. PCS, KG, BOX" maxlength="150">
                            </div>
                        </div>

                        <div class="item-section-card">
                            <h6><i class="fas fa-image"></i> Product Image</h6>
                            <div class="item-image-upload">
                                <label for="inputImage" class="item-image-dropzone">
                                    <img id="preview-image" src="" alt="">
                                    <div id="image-dropzone-placeholder" class="item-image-placeholder">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span>Click to upload an image</span>
                                        <small>PNG, JPG up to a few MB</small>
                                    </div>
                                </label>
                                <input type="file" name="image" id="inputImage" class="d-none" accept="image/*">
                                <div class="item-image-upload-hint">
                                    <strong>Product photo</strong>
                                    Used on item lists and printed documents where enabled. Square images work best — they'll be cropped to fit the thumbnail.
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="item-section-card">
                                    <h6><i class="fas fa-tag"></i> Prices <span class="text-danger">*</span></h6>
                                    <div class="mb-2">
                                        <label class="form-label">Purchase Price <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="purchasePrice"
                                               name="purchasePrice" placeholder="Purchase Price" maxlength="20" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Sales Price <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="saleprice"
                                               name="saleprice" placeholder="Sale Price" maxlength="20" required>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label">Border Price</label>
                                        <input type="text" class="form-control" id="Credit"
                                               name="Credit" placeholder="Border price" maxlength="15">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="item-section-card">
                                    <h6><i class="fas fa-sliders-h"></i> Options</h6>
                                    <div class="mb-2">
                                        <label class="form-label">Reorder Level</label>
                                        <input type="text" class="form-control" id="ReorderLevel"
                                               name="ReorderLevel" placeholder="Reorder Level" maxlength="25">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Recorder Quantity</label>
                                        <input type="text" class="form-control" id="RecorderQuantitiy"
                                               name="RecorderQuantitiy" placeholder="Recorder Quantity" maxlength="25">
                                    </div>
                                    <input type="checkbox" class="d-none" value="1" id="SaleDecimal" name="SaleDecimal">
                                    <input type="checkbox" class="d-none" value="1" id="Serialnumber" name="Serialnumber">
                                    <input type="checkbox" class="d-none" value="1" id="Batchwise" name="Batchwise">
                                    <input type="checkbox" class="d-none" value="1" id="Inactive" name="Inactive">

                                    <div class="feature-toggle-grid">
                                        <div class="feature-toggle-card" data-target="SaleDecimal">
                                            <div class="ftc-icon"><i class="fas fa-calculator"></i></div>
                                            <div>
                                                <div class="ftc-title">Sales by Decimals</div>
                                                <div class="ftc-desc">Allow fractional quantities (e.g. 1.5) when selling this item.</div>
                                            </div>
                                            <i class="fas fa-check-circle ftc-check"></i>
                                        </div>

                                        <div class="feature-toggle-card" data-target="Serialnumber">
                                            <div class="ftc-icon"><i class="fas fa-barcode"></i></div>
                                            <div>
                                                <div class="ftc-title">Serial Number</div>
                                                <div class="ftc-desc">Track each unit individually by its own serial number.</div>
                                            </div>
                                            <i class="fas fa-check-circle ftc-check"></i>
                                        </div>

                                        <div class="feature-toggle-card" data-target="Batchwise">
                                            <div class="ftc-icon"><i class="fas fa-cubes"></i></div>
                                            <div>
                                                <div class="ftc-title">Batch Tracked</div>
                                                <div class="ftc-desc">Batch items use GRN pricing instead of fixed master prices.</div>
                                            </div>
                                            <i class="fas fa-check-circle ftc-check"></i>
                                        </div>

                                        <div class="feature-toggle-card" data-target="Inactive">
                                            <div class="ftc-icon"><i class="fas fa-ban"></i></div>
                                            <div>
                                                <div class="ftc-title">Inactive</div>
                                                <div class="ftc-desc">Hide this item from sales and purchase screens.</div>
                                            </div>
                                            <i class="fas fa-check-circle ftc-check"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="item-section-card" id="itemBatchesRow" style="display:none;">
                            <h6><i class="fas fa-cubes"></i> Batches</h6>
                            <p class="item-section-hint">Created automatically when stock is received via Purchases / GRN.</p>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0" id="itemBatchesTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Batch No</th>
                                            <th>Purchase Price</th>
                                            <th>Sale Price</th>
                                            <th>Qty Remaining</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td colspan="4" class="text-muted">No batches yet.</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <input type="hidden" name="Branch"     id="Branch"     value="{{ Auth::user()->Branch }}">
                        <input type="hidden" name="BranchCode" id="BranchCode" value="{{ Auth::user()->BC }}">

                        <div class="item-modal-footer-actions">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="btn-save">
                                <i class="fas fa-check me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>{{-- end #single-item-section --}}


                {{-- ══════════════════════════════════════════════════════
                     PACKAGE / SET ITEMS SECTION
                     ─────────────────────────────────────────────────────
                     User fills:
                       • pkg_name      → saved as Item_description in items table
                       • pkg_code      → saved as Item_code in items table
                       • qty per row   → saved to package_items.qty
                       • purchasePrice, saleprice, Credit typed in summary bar
                       • Item_set_bulk → built automatically as "CODE(QTY)|…"
                         and saved to items.Item_set_bulk (VARCHAR 750)
                ══════════════════════════════════════════════════════ --}}
                <div id="package-add-section">

                    <div id="pkg-error-msg"   class="alert alert-danger"  style="display:none;"></div>
                    <div id="pkg-success-msg" class="alert alert-success" style="display:none;"></div>

                    <div class="alert alert-info py-2 mb-3" style="font-size:13px;">
                        <i class="fa fa-box-open me-1"></i>
                        Create a <strong>named bundle</strong>. The bundle name becomes the
                        <strong>Item Description</strong> and the code becomes the
                        <strong>Item Code</strong> — both saved to the items table.
                        Each row's <strong>Qty</strong> is saved to <code>package_items.qty</code>
                        and the full set is saved to <code>items.Item_set_bulk</code>.
                    </div>

                    {{-- ── Header: Name + Code ── --}}
                    <div class="pkg-header-box">
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <label>Set Item Name <span class="text-danger">*</span>
                                    <small class="fw-normal text-muted">(→ Item_description)</small>
                                </label>
                                <input type="text" id="pkg_name" class="form-control pkg-name-input"
                                       placeholder="e.g. Starter Kit, Office Bundle…" maxlength="150">
                            </div>
                            <div class="col-sm-4">
                                <label>Set Item Code <span class="text-danger">*</span>
                                    <small class="fw-normal text-muted">(→ Item_code)</small>
                                </label>
                                <input type="text" id="pkg_code" class="form-control pkg-name-input"
                                       placeholder="e.g. PKG-001" maxlength="25">
                            </div>
                        </div>
                    </div>

                    {{-- ── Item search box ── --}}
                    <div class="mb-3">
                        <label style="font-size:13px;font-weight:600;color:#14213D;">
                            <i class="fa fa-search me-1"></i> Search &amp; Add Existing Items to Bundle
                        </label>
                        <div class="pkg-search-wrapper">
                            <input type="text" id="pkg_item_search" class="form-control pkg-search-input"
                                   placeholder="Type item name, code, or barcode…"
                                   autocomplete="off">
                            <div id="pkg-ac-dropdown" class="pkg-ac-dropdown"></div>
                        </div>
                        <small class="text-muted" style="font-size:11px;">
                            Items added here are stored in the <strong>package_items</strong> table (with qty).
                            The combined set is also stored in <strong>items.Item_set_bulk</strong>.
                        </small>
                    </div>

                    {{-- ── Package items table ── --}}
                    <div class="table-responsive">
                        <table class="table table-bordered pkg-items-table">
                            <thead>
                                <tr>
                                    <th style="width:36px;">#</th>
                                    <th style="min-width:100px;">Item Code</th>
                                    <th>Description</th>
                                    {{-- Qty column — now editable ── --}}
                                    <th style="width:80px;">Qty</th>
                                    <th style="width:46px;"></th>
                                </tr>
                            </thead>
                            <tbody id="pkgItemsBody">
                                <tr class="pkg-empty-row" id="pkg-placeholder">
                                    <td colspan="5">
                                        <i class="fa fa-box-open me-2 text-muted"></i>
                                        No items added yet — use the search above.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- ── Item_set_bulk live preview ── --}}
                    <div class="bulk-preview" id="bulk-preview-box">
                        <strong>Item_set_bulk:</strong> <span id="bulk-preview-text"></span>
                    </div>

                    {{-- ────────────────────────────────────────────────────
                         SUMMARY BAR
                         purchasePrice, saleprice, Credit → saved to items table
                    ──────────────────────────────────────────────────── --}}
                    <div class="pkg-summary-bar">

                        <div class="s-stat">
                            <div class="s-label">Items in Package</div>
                            <div class="s-value" id="pkg-item-count">0</div>
                        </div>

                        {{-- Purchase Price ──────────────────────────── --}}
                        <div class="s-stat">
                            <div class="s-label">Total Purchase Price
                                <small class="d-block text-muted" style="font-size:10px;">→ purchasePrice</small>
                            </div>
                            <input type="text"
                                   id="pkg-total-pp"
                                   class="pkg-price-input text-center mt-1"
                                   value="0.00"
                                   placeholder="0.00"
                                   style="width:100px !important;">
                        </div>

                        {{-- Sale Price ──────────────────────────────── --}}
                        <div class="s-stat">
                            <div class="s-label">Total Sale Unit Price
                                <small class="d-block text-muted" style="font-size:10px;">→ saleprice</small>
                            </div>
                            <input type="text"
                                   id="pkg-total-sp"
                                   class="pkg-price-input text-center mt-1"
                                   value="0.00"
                                   placeholder="0.00"
                                   style="width:100px !important;">
                        </div>

                        {{-- Border Price ─────────────────────────────── --}}
                        <div class="s-stat">
                            <div class="s-label">Total Border Price
                                <small class="d-block text-muted" style="font-size:10px;">→ Credit</small>
                            </div>
                            <input type="text"
                                   id="pkg-total-cr"
                                   class="pkg-price-input text-center mt-1"
                                   value="0.00"
                                   placeholder="0.00"
                                   style="width:100px !important;">
                        </div>

                        <div class="ms-auto">
                            <button id="pkg-save-btn" onclick="savePackage()">
                                <i class="fa fa-save me-1"></i> Save Package
                            </button>
                        </div>
                    </div>
                    {{-- /summary bar --}}

                </div>{{-- end #package-add-section --}}

            </div>{{-- /modal-body --}}
            <div class="modal-footer"></div>
        </div>
    </div>
</div>


{{-- ============================================================
     SCRIPTS
============================================================ --}}
<script src="assets/js/dt-custom-pager.js"></script>
<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // ── DataTable ─────────────────────────────────────────────
    // Starts collapsed: 15 rows, no length/info/pagination chrome, just
    // a "View More" button — clicking it reveals the normal DataTables
    // pagination footer so the rest can be paged through. The search
    // box (top-right, part of 'lfrtip') always stays visible and still
    // hits the server (serverSide: true), so it searches the FULL
    // dataset regardless of collapsed/expanded state.
    var itemsTable = $('#Item').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('Item') }}",
        columns: [
            { data: 'action',            name: 'action',            orderable: false },
            { data: 'category',          name: 'category' },
            { data: 'Department',        name: 'Department' },
            { data: 'Item_code',         name: 'Item_code' },
            { data: 'Bar_code',          name: 'Bar_code' },
            { data: 'Item_description',  name: 'Item_description' },
            { data: 'Per',               name: 'Per' },
            { data: 'purchasePrice',     name: 'purchasePrice' },
            { data: 'saleprice',         name: 'saleprice' },
            { data: 'Credit',            name: 'Credit' },
            { data: 'RecorderQuantitiy', name: 'RecorderQuantitiy' },
            { data: 'ReorderLevel',      name: 'ReorderLevel' },
        ],
        order: [[0, 'desc']],
        paging: true,
        pageLength: 15,
        lengthChange: false,
    });

    $('#Item_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(itemsTable, '#itemCustomPager');

    // ── Package autocomplete search ───────────────────────────
    let pkgSearchTimer = null;

    $('#pkg_item_search').on('input', function () {
        let q = $(this).val().trim();
        clearTimeout(pkgSearchTimer);
        if (q.length < 2) { $('#pkg-ac-dropdown').hide(); return; }

        $('#pkg-ac-dropdown')
            .html('<div class="pkg-ac-loading"><i class="fa fa-spinner fa-spin me-1"></i>Searching…</div>')
            .show();

        pkgSearchTimer = setTimeout(function () {
            $.ajax({
                type: 'POST',
                url: "{{ url('ItemSearch') }}",
                data: { q: q },
                dataType: 'json',
                success: function (items) {
                    if (!items.length) {
                        $('#pkg-ac-dropdown')
                            .html('<div class="pkg-ac-empty">No items found for "' + q + '"</div>')
                            .show();
                        return;
                    }
                    let html = '';
                    $.each(items, function (i, item) {
                        html += `<div class="pkg-ac-item"
                                      data-code="${item.Item_code}"
                                      data-desc="${item.Item_description}"
                                      onclick="pkgSelectItem(this)">
                            <span class="ac-code">${item.Item_code}</span>
                            <span class="ac-desc">${item.Item_description}</span>
                        </div>`;
                    });
                    $('#pkg-ac-dropdown').html(html).show();
                },
                error: function () {
                    $('#pkg-ac-dropdown')
                        .html('<div class="pkg-ac-empty text-danger">Search failed. Try again.</div>')
                        .show();
                }
            });
        }, 300);
    });

    // ── Listen for qty changes to keep bulk preview updated ───
    // Uses event delegation because rows are added dynamically
    $('#pkgItemsBody').on('input change', '.pkg-qty-input', function () {
        updateBulkPreview();
    });

    // Close dropdown on outside click
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.pkg-search-wrapper').length) {
            $('#pkg-ac-dropdown').hide();
        }
    });
});


// ════════════════════════════════════════════════════════
// buildBulkString
// Reads all current rows and builds "CODE(QTY)|CODE(QTY)…"
// ════════════════════════════════════════════════════════
function buildBulkString() {
    let parts = [];
    $('#pkgItemsBody tr:not(#pkg-placeholder)').each(function () {
        let code = $(this).data('code');
        let qty  = parseInt($(this).find('.pkg-qty-input').val()) || 1;
        parts.push(code + '(' + qty + ')');
    });
    return parts.join('|');
}

// ════════════════════════════════════════════════════════
// updateBulkPreview  — shows the live Item_set_bulk value
// ════════════════════════════════════════════════════════
function updateBulkPreview() {
    let bulk = buildBulkString();
    if (bulk) {
        $('#bulk-preview-text').text(bulk);
        $('#bulk-preview-box').show();
    } else {
        $('#bulk-preview-box').hide();
    }
}


// ════════════════════════════════════════════════════════
// PACKAGE — select item from autocomplete
// ════════════════════════════════════════════════════════
let pkgRowIndex   = 0;
let pkgAddedCodes = [];

function pkgSelectItem(el) {
    let code = $(el).data('code');
    let desc = $(el).data('desc');

    // Prevent duplicate
    if (pkgAddedCodes.includes(String(code))) {
        $('#pkg-ac-dropdown').hide();
        $('#pkg_item_search').val('').focus();
        $('#pkg-error-msg')
            .html(`<i class="fa fa-exclamation-triangle me-1"></i>"${desc}" is already in the package.`)
            .show();
        setTimeout(() => $('#pkg-error-msg').fadeOut(), 2500);
        return;
    }

    pkgAddedCodes.push(String(code));
    pkgRowIndex++;
    let idx = pkgRowIndex;

    $('#pkg-placeholder').remove();

    let rowNum = $('#pkgItemsBody tr').length + 1;

    // ── Qty is now an editable <input type="number"> ──────────
    let row = `<tr id="pkg-row-${idx}" data-row="${idx}" data-code="${code}">
        <td class="text-center align-middle fw-bold text-muted" style="font-size:13px;">${rowNum}</td>
        <td class="align-middle"><span class="code-badge">${code}</span></td>
        <td class="align-middle" style="font-size:13px;">${desc}</td>
        <td class="text-center align-middle">
            <input type="number"
                   class="pkg-qty-input"
                   value="1"
                   min="1"
                   max="9999"
                   title="Quantity">
        </td>
        <td class="text-center align-middle">
            <button class="btn-remove-row" onclick="removePkgRow(${idx},'${code}')" title="Remove">
                <i class="fa fa-times"></i>
            </button>
        </td>
    </tr>`;

    $('#pkgItemsBody').append(row);
    $('#pkg-ac-dropdown').hide();
    $('#pkg_item_search').val('').focus();
    $('#pkg-error-msg').hide();

    // Update count in summary bar and live preview
    $('#pkg-item-count').text($('#pkgItemsBody tr:not(#pkg-placeholder)').length);
    renumberPkgRows();
    updateBulkPreview();
}


// ════════════════════════════════════════════════════════
// PACKAGE — remove row
// ════════════════════════════════════════════════════════
function removePkgRow(idx, code) {
    $(`#pkg-row-${idx}`).remove();
    pkgAddedCodes = pkgAddedCodes.filter(c => c !== String(code));

    if ($('#pkgItemsBody tr').length === 0) {
        $('#pkgItemsBody').html(
            '<tr class="pkg-empty-row" id="pkg-placeholder">' +
            '<td colspan="5"><i class="fa fa-box-open me-2 text-muted"></i>' +
            'No items added yet — use the search above.</td></tr>'
        );
    }

    $('#pkg-item-count').text($('#pkgItemsBody tr:not(#pkg-placeholder)').length);
    renumberPkgRows();
    updateBulkPreview();
}

function renumberPkgRows() {
    $('#pkgItemsBody tr:not(#pkg-placeholder)').each(function (i) {
        $(this).find('td:first').text(i + 1);
    });
}


// ════════════════════════════════════════════════════════
// PACKAGE — SAVE
//
// What gets saved:
//   packages table  → package_name, pkg_code, Branch, BranchCode
//   package_items   → package_id, pkg_code, item_code, item_description, qty
//   items table     → Item_code        = pkg_code
//                     Item_description = pkg_name
//                     purchasePrice    = #pkg-total-pp  (manual input)
//                     saleprice        = #pkg-total-sp  (manual input)
//                     Credit           = #pkg-total-cr  (manual input)
//                     Item_set_bulk    = "SS-MA008(2)|BOWL-001(1)" (auto-built)
//                     Branch, BranchCode
// ════════════════════════════════════════════════════════
function savePackage() {
    let pkgName       = $('#pkg_name').val().trim();
    let pkgCode       = $('#pkg_code').val().trim();
    let purchasePrice = $('#pkg-total-pp').val().trim();
    let salePrice     = $('#pkg-total-sp').val().trim();
    let borderPrice   = $('#pkg-total-cr').val().trim();

    $('#pkg-error-msg').hide();

    // ── Validation ───────────────────────────────────────
    let errors = [];
    if (!pkgName)                              errors.push('Set Item Name (Item_description) is required.');
    if (!pkgCode)                              errors.push('Set Item Code (Item_code) is required.');
    if (!purchasePrice || isNaN(purchasePrice)) errors.push('Total Purchase Price must be a valid number.');
    if (!salePrice     || isNaN(salePrice))    errors.push('Total Sale Unit Price must be a valid number.');

    if (errors.length) {
        $('#pkg-error-msg')
            .html(errors.map(e => `<p class="mb-0"><i class="fa fa-exclamation-triangle me-1"></i>${e}</p>`).join(''))
            .show();
        return;
    }

    // ── Collect package item rows (with qty) ─────────────
    let bundleItems  = [];
    let bulkParts    = [];   // → Item_set_bulk

    $('#pkgItemsBody tr:not(#pkg-placeholder)').each(function () {
        let code = $(this).data('code');
        let desc = $(this).find('td:eq(2)').text().trim();
        let qty  = parseInt($(this).find('.pkg-qty-input').val()) || 1;

        bundleItems.push({
            item_code:        code,
            item_description: desc,
            qty:              qty,   // saved to package_items.qty
        });

        // e.g. "SS-MA008(2)"
        bulkParts.push(code + '(' + qty + ')');
    });

    // Final Item_set_bulk string: "SS-MA008(2)|BOWL-001(1)"
    let itemSetBulk = bulkParts.join('|');

    $('#pkg-save-btn')
        .html('<span class="spinner-border spinner-border-sm me-1"></span>Saving…')
        .attr('disabled', true);

    $.ajax({
        type: 'POST',
        url: "{{ url('PackageStore') }}",
        data: JSON.stringify({
            // ── packages + package_items tables ──
            package_name:  pkgName,
            pkg_code:      pkgCode,
            items:         bundleItems,      // each item now includes qty

            // ── items table ──
            purchasePrice: purchasePrice,    // Total Purchase Price (manual)
            saleprice:     salePrice,        // Total Sale Unit Price (manual)
            Credit:        borderPrice || '0',
            Item_set_bulk: itemSetBulk,      // "SS-MA008(2)|BOWL-001(1)"

            Branch:        "{{ Auth::user()->Branch }}",
            BranchCode:    "{{ Auth::user()->BC }}",
        }),
        contentType: 'application/json',
        dataType: 'json',
        success: function (res) {
            $('#pkg-success-msg')
                .html('<i class="fa fa-check-circle me-1"></i> ' + res.message)
                .show();
            setTimeout(function () {
                $('#Item-modal').modal('hide');
                location.reload();
            }, 1600);
        },
        error: function (data) {
            let errHtml = '<p>Error saving package.</p>';
            if (data.responseJSON) {
                if (data.responseJSON.errors) {
                    errHtml = '';
                    $.each(data.responseJSON.errors, function (k, v) {
                        errHtml += '<p>' + (Array.isArray(v) ? v[0] : v) + '</p>';
                    });
                } else if (data.responseJSON.message) {
                    errHtml = '<p>' + data.responseJSON.message + '</p>';
                }
            }
            $('#pkg-error-msg').html(errHtml).show();
            $('#pkg-save-btn')
                .html('<i class="fa fa-save me-1"></i> Save Package')
                .attr('disabled', false);
        }
    });
}


// ════════════════════════════════════════════════════════
// PACKAGE — reset
// ════════════════════════════════════════════════════════
function resetPackageSection() {
    $('#pkg_name').val('');
    $('#pkg_code').val('');
    $('#pkg_item_search').val('');
    $('#pkg-ac-dropdown').hide();
    $('#pkg-error-msg').hide();
    $('#pkg-success-msg').hide();
    $('#pkg-total-pp').val('0.00');
    $('#pkg-total-sp').val('0.00');
    $('#pkg-total-cr').val('0.00');
    $('#pkg-item-count').text('0');
    $('#bulk-preview-box').hide();
    $('#bulk-preview-text').text('');
    pkgRowIndex   = 0;
    pkgAddedCodes = [];
    $('#pkgItemsBody').html(
        '<tr class="pkg-empty-row" id="pkg-placeholder">' +
        '<td colspan="5"><i class="fa fa-box-open me-2 text-muted"></i>' +
        'No items added yet — use the search above.</td></tr>'
    );
    $('#pkg-save-btn').html('<i class="fa fa-save me-1"></i> Save Package').attr('disabled', false);
}


// ════════════════════════════════════════════════════════
// MODAL OPEN / RESET
// ════════════════════════════════════════════════════════
function add() {
    $('#ItemForm').trigger('reset');
    $('#ItemModal').html('Add Item');
    $('#id').val('');
    $('#category-items-preview').hide().removeClass('expanded');
    $('#category-preview-body').hide();
    $('#single_cate_code').val('');
    $('#single_item_number').val('');
    $('#Item_code').val('');
    $('#error-messages').hide();
    resetPackageSection();
    switchMode('single');
    resetImagePreview();
    syncFeatureToggleCards();
    $('#itemBatchesRow').hide();
    $('#Item-modal').modal('show');
}

// ════════════════════════════════════════════════════════
// FEATURE TOGGLE CARDS (Sales by Decimals / Serial Number / Batch Tracked / Inactive)
// ════════════════════════════════════════════════════════
function syncFeatureToggleCards() {
    $('.feature-toggle-card').each(function () {
        let checkbox = $('#' + $(this).data('target'));
        $(this).toggleClass('active', checkbox.is(':checked'));
    });
}

// ════════════════════════════════════════════════════════
// IMAGE UPLOAD PREVIEW
// ════════════════════════════════════════════════════════
function resetImagePreview() {
    $('#preview-image').attr('src', '').hide();
    $('#image-dropzone-placeholder').show();
}

$(document).on('change', '#inputImage', function () {
    let file = this.files && this.files[0];
    if (!file) { resetImagePreview(); return; }

    let reader = new FileReader();
    reader.onload = function (e) {
        $('#preview-image').attr('src', e.target.result).show();
        $('#image-dropzone-placeholder').hide();
    };
    reader.readAsDataURL(file);
});

$(document).on('click', '#category-preview-toggle', function () {
    $('#category-items-preview').toggleClass('expanded');
    $('#category-preview-body').slideToggle(150);
});

$(document).on('click', '.feature-toggle-card', function () {
    let checkbox = $('#' + $(this).data('target'));
    checkbox.prop('checked', !checkbox.is(':checked'));
    syncFeatureToggleCards();

    if ($(this).data('target') === 'Batchwise' && !checkbox.is(':checked')) {
        $('#itemBatchesRow').hide();
    }
});

$(document).ready(function () {
    syncFeatureToggleCards();
});


// ════════════════════════════════════════════════════════
// EDIT
// ════════════════════════════════════════════════════════
function editFunc(id) {
    $.ajax({
        type: 'POST',
        url: "{{ url('Itemedit') }}",
        data: { id: id },
        dataType: 'json',
        success: function (res) {
            switchMode('single');
            $('#ItemModal').html('Edit Item');
            resetImagePreview();
            $('#Item-modal').modal('show');
            $('#id').val(res.id);
            $('#category').val(res.category);
            $('#Department').val(res.Department);
            $('#Item_code').val(res.Item_code);
            $('#Bar_code').val(res.Bar_code);
            $('#Item_description').val(res.Item_description);
            $('#Brand').val(res.Brand);
            $('#Color').val(res.Color);
            $('#Make').val(res.Make);
            $('#purchasePrice').val(res.purchasePrice);
            $('#saleprice').val(res.saleprice);
            $('#Credit').val(res.Credit);
            $('#Per').val(res.Per);
            $('#ReorderLevel').val(res.ReorderLevel);
            $('#RecorderQuantitiy').val(res.RecorderQuantitiy);
            $('#Serialnumber').prop('checked', res.Serialnumber == 1);
            $('#SaleDecimal').prop('checked',  res.SaleDecimal  == 1);
            $('#Inactive').prop('checked',     res.Inactive     == 1);
            $('#Batchwise').prop('checked',    res.Batchwise    == 1);
            syncFeatureToggleCards();

            let parts = res.Item_code ? res.Item_code.split('-') : [];
            if (parts.length >= 2) {
                $('#single_cate_code').val(parts[0]);
                $('#single_item_number').val(parts.slice(1).join('-'));
            }
            $('#category-items-preview').removeClass('expanded');
            $('#category-preview-body').hide();
            loadCategoryItems(res.category, '#cat-items-body',
                '#category-items-preview', '#preview-category-name', '#cat-item-count');

            if (res.Batchwise == 1) {
                $('#itemBatchesRow').show();
                $.get("{{ url('get_item_batches_ajax') }}", { item_code: res.Item_code }, function (batchRes) {
                    let rows = '';
                    if (batchRes.data && batchRes.data.length) {
                        batchRes.data.forEach(function (b) {
                            rows += '<tr><td>' + b.batch_no + '</td><td>' + b.purchase_price +
                                '</td><td>' + b.sale_price + '</td><td>' + b.qty_remaining + '</td></tr>';
                        });
                    } else {
                        rows = '<tr><td colspan="4" class="text-muted">No batches yet.</td></tr>';
                    }
                    $('#itemBatchesTable tbody').html(rows);
                });
            } else {
                $('#itemBatchesRow').hide();
            }
        }
    });
}


// ════════════════════════════════════════════════════════
// DELETE
// ════════════════════════════════════════════════════════
function deleteFunc(id) {
    if (confirm('Delete Record?')) {
        $.ajax({
            type: 'POST',
            url: "{{ url('Itemdelete') }}",
            data: { id: id },
            dataType: 'json',
            success: function () { $('#Item').dataTable().fnDraw(false); }
        });
    }
}


// ════════════════════════════════════════════════════════
// SINGLE ITEM SUBMIT
// ════════════════════════════════════════════════════════
$('#ItemForm').submit(function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    $('#btn-save').html('Saving…').attr('disabled', true);
    $.ajax({
        type: 'POST',
        url: "{{ url('Itemstore') }}",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function () {
            $('#Item-modal').modal('hide');
            $('#Item').dataTable().fnDraw(false);
            $('#btn-save').html('Save Changes').attr('disabled', false);
            location.reload();
        },
        error: function (data) {
            var errors = data.responseJSON ? data.responseJSON.errors : {};
            var html = '';
            $.each(errors, function (k, v) { html += '<p>' + v[0] + '</p>'; });
            if (!html) html = '<p>An unexpected error occurred.</p>';
            $('#error-messages').html(html).show();
            $('#btn-save').html('Save Changes').attr('disabled', false);
        }
    });
});


// ════════════════════════════════════════════════════════
// SINGLE — item code generator
// ════════════════════════════════════════════════════════
$('#category').change(function () {
    let code = $(this).find(':selected').data('code');
    $('#single_cate_code').val(code || '');
    generateSingleItemCode();
    $('#category-items-preview').removeClass('expanded');
    $('#category-preview-body').hide();
    if (this.value) {
        loadCategoryItems(this.value, '#cat-items-body',
            '#category-items-preview', '#preview-category-name', '#cat-item-count');
    } else {
        $('#category-items-preview').hide();
    }
});

$('#single_item_number').on('keyup change', generateSingleItemCode);

function generateSingleItemCode() {
    let cate = $('#single_cate_code').val();
    let num  = $('#single_item_number').val();
    $('#Item_code').val(cate && num ? cate + '-' + num : '');
}


// ════════════════════════════════════════════════════════
// CATEGORY ITEMS LOADER
// ════════════════════════════════════════════════════════
function loadCategoryItems(category, tbodySelector, previewSelector, labelSelector, countSelector) {
    $(tbodySelector).html(
        '<tr><td colspan="5" class="text-center">' +
        '<div class="spinner-border spinner-border-sm text-primary"></div></td></tr>'
    );
    $(previewSelector).show();

    $.ajax({
        type: 'POST',
        url: "{{ url('ItemsByCategory') }}",
        data: { category: category },
        dataType: 'json',
        success: function (items) {
            $(labelSelector).text('Items in: ' + category);
            $(countSelector).text(items.length + ' item' + (items.length !== 1 ? 's' : ''));
            if (!items.length) {
                $(tbodySelector).html(
                    '<tr><td colspan="5" class="text-center text-muted">No items in this category yet</td></tr>'
                );
                return;
            }
            let rows = '';
            $.each(items, function (i, item) {
                rows += `<tr>
                    <td>${i + 1}</td>
                    <td><span class="badge" style="background:#1677FF">${item.Item_code}</span></td>
                    <td>${item.Item_description}</td>
                    <td>${item.purchasePrice}</td>
                    <td>${item.saleprice}</td>
                </tr>`;
            });
            $(tbodySelector).html(rows);
        },
        error: function () {
            $(tbodySelector).html(
                '<tr><td colspan="5" class="text-center text-danger">Failed to load</td></tr>'
            );
        }
    });
}


// ════════════════════════════════════════════════════════
// TAB SWITCHER
// ════════════════════════════════════════════════════════
function switchMode(mode) {
    $('#single-item-section').hide();
    $('#package-add-section').hide();
    $('#tab-single, #tab-package').removeClass('active');

    if (mode === 'single') {
        $('#single-item-section').show();
        $('#tab-single').addClass('active');
    } else {
        $('#package-add-section').show();
        $('#tab-package').addClass('active');
        setTimeout(() => $('#pkg_name').focus(), 150);
    }
}
</script>

<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="assets/plugins/apexchart/chart-data.js"></script>
</body>
@endsection