<style>
.item-description-wrapper { display: inline-block; max-width: 400px; white-space: normal; }

/* "Batch" tag — shown next to the item name wherever a batch-tracked item appears */
.batch-tag {
    display:inline-flex; align-items:center; gap:4px;
    background:#e8f0fe; color:#2d6ce9;
    border-radius:20px; padding:1px 9px; margin-left:6px;
    font-size:10.5px; font-weight:700; white-space:nowrap; vertical-align:middle;
}
.batch-tag i { font-size:9px; }
#dynamicAdded thead th { background: #F8FAFC; color: #1f2d3d; font-weight: 600; text-align: left; }
#dynamicAdded {
    table-layout: fixed;
    background: #fff;
}
#dynamicAdded td {
    padding: 12px 10px;
    border-color: #e2e8f0;
    vertical-align: middle;
}
#dynamicAdded input.form-control {
    min-height: 42px;
    border: 1px solid #d7e3f1;
    border-radius: 6px;
    text-align: left;
}
.sales-dynamic-table,
.sales-totals-table {
    width: 100% !important;
    margin: 10px 0 0 !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 12px;
    overflow: hidden;
}
.sales-totals-table {
    background: #fff;
    margin-top: 8px !important;
}
.sales-totals-table .table-success,
.sales-totals-table .table-success > td {
    background: #F8FAFC !important;
    color: #1f2d3d;
    border-color: #e2e8f0 !important;
    border-top: 2px solid #d9e3ee !important;
}
/* Keep the entry row, added rows, and totals on one shared eight-column grid. */
#salesItemTable,
#dynamicAdded,
.sales-totals-table {
    table-layout: fixed;
    border-collapse: separate;
    border-spacing: 0;
    box-sizing: border-box;
}
#salesItemTable th:nth-child(1), #salesItemTable td:nth-child(1),
#dynamicAdded td:nth-child(1), .sales-totals-table td:nth-child(1) { width: 15% !important; }
#salesItemTable th:nth-child(2), #salesItemTable td:nth-child(2),
#dynamicAdded td:nth-child(2), .sales-totals-table td:nth-child(2) { width: 19% !important; }
#salesItemTable th:nth-child(3), #salesItemTable td:nth-child(3),
#dynamicAdded td:nth-child(3), .sales-totals-table td:nth-child(3) { width: 10% !important; }
#salesItemTable th:nth-child(4), #salesItemTable td:nth-child(4),
#dynamicAdded td:nth-child(4), .sales-totals-table td:nth-child(4) { width: 10% !important; }
#salesItemTable th:nth-child(5), #salesItemTable td:nth-child(5),
#dynamicAdded td:nth-child(5), .sales-totals-table td:nth-child(5) { width: 8% !important; }
#salesItemTable th:nth-child(6), #salesItemTable td:nth-child(6),
#dynamicAdded td:nth-child(6), .sales-totals-table td:nth-child(6) { width: 10% !important; }
#salesItemTable th:nth-child(7), #salesItemTable td:nth-child(7),
#dynamicAdded td:nth-child(7), .sales-totals-table td:nth-child(7) { width: 14% !important; }
#salesItemTable th:nth-child(8), #salesItemTable td:nth-child(8),
#dynamicAdded td:nth-child(8), .sales-totals-table td:nth-child(8) { width: 14% !important; }
.sales-dynamic-table td:last-child,
.sales-totals-table td:last-child { text-align: center; }
.sales-dynamic-table .remove-new-row,
.sales-dynamic-table .remove-row { margin-left: auto; margin-right: auto; }
.sales-dynamic-table .sales-row-delete {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    padding: 0;
    margin: 0 auto;
    color: #dc3545;
    background: #fff;
    border: 1px solid #dc3545;
    border-radius: 10px;
}
.sales-dynamic-table .sales-row-delete:hover {
    color: #fff;
    background: #dc3545;
}
.recall-section { background: #f8f9fa; border-radius: 6px; padding: 10px 14px; }

/* Recall Sales Order panel */
.recall-panel {
    background: linear-gradient(135deg, #f5f9ff 0%, #eef4fc 100%);
    border: 1px solid #dfeaf6;
    border-radius: 16px;
    padding: 18px 20px;
    margin: 18px 18px 0 !important;
    width: calc(100% - 36px);
    box-sizing: border-box;
    box-shadow: 0 8px 20px rgba(42, 92, 171, 0.05);
}
.recall-panel-head {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}
.recall-panel-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #8aa9ff, #5b7fe0);
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 6px 14px rgba(91, 127, 224, 0.3);
}
.recall-panel-icon i { font-size: 15px; }
.recall-panel-head label {
    display: block;
    font-size: 14px;
    font-weight: 800;
    color: #1f2d3d;
    margin: 0;
}
.recall-panel-head p {
    margin: 2px 0 0;
    font-size: 12.5px;
    color: #5a6f8b;
}
.recall-panel-body {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}
.recall-input {
    flex: 1;
    min-width: 220px;
    min-height: 46px;
}
.recall-load-btn {
    background: linear-gradient(135deg, #34c38f, #26a978);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 0 22px;
    min-height: 46px;
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 8px 18px rgba(38, 169, 120, 0.25);
    transition: filter .15s, transform .1s;
    flex-shrink: 0;
}
.recall-load-btn:hover { filter: brightness(1.06); color: #fff; }
.recall-load-btn:active { transform: translateY(1px); }
.recall-load-btn:disabled { opacity: .6; cursor: not-allowed; filter: none; }
.recall-status {
    margin: 10px 2px 0;
    font-size: 13px;
    font-weight: 600;
    color: #5a6f8b;
    min-height: 18px;
}
.recall-status:empty { margin: 0; min-height: 0; }
.recall-status.is-success { color: #1e8d5e; }
.recall-status.is-warn { color: #a66a1d; }
.recall-status.is-error { color: #d43636; }
.recall-actions {
    display: flex;
    gap: 10px;
    margin-top: 12px;
    flex-wrap: wrap;
}
.recall-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none;
    transition: filter .15s, background .15s;
}
.recall-action-primary {
    background: linear-gradient(135deg, #4c8cf7, #2d6ce9);
    color: #fff;
    box-shadow: 0 8px 16px rgba(45, 108, 233, 0.22);
}
.recall-action-primary:hover { color: #fff; filter: brightness(1.06); }
.recall-action-secondary {
    background: #fff;
    border: 1px solid #d7e3f1;
    color: #2b3e5b;
}
.recall-action-secondary:hover { background: #f5f9ff; color: #2b3e5b; }
.recall-edit-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    background: #fff7e6;
    border: 1px solid #f3d99a;
    color: #8a6116;
}
.recall-edit-badge i { font-size: 12px; }

/* Recall panel — multi-row variant (e.g. "Load Sales Order" + "Recall Saved Invoice") */
.recall-row { margin-top: 16px; padding-top: 16px; border-top: 1px dashed #dfeaf6; }
.recall-row:first-of-type { margin-top: 0; padding-top: 0; border-top: none; }
.recall-row-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #2b3b4f;
    margin-bottom: 8px;
}
.recall-row-label i { color: #2d6ce9; font-size: 13px; width: 14px; text-align: center; }
.recall-row-body {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}
.recall-secondary-btn {
    background: #fff;
    border: 1px solid #d7e3f1;
    color: #2b3e5b;
    border-radius: 10px;
    padding: 0 22px;
    min-height: 46px;
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    transition: background .15s, filter .15s;
}
.recall-secondary-btn:hover { background: #f5f9ff; }
.recall-secondary-btn:disabled { opacity: .6; cursor: not-allowed; }

/* "Not Enough Stock" modal */
.stock-warning-modal { border-radius: 16px; overflow: hidden; border: none; }
.stock-warning-modal .modal-header {
    background: #fdecec;
    border-bottom: 1px solid #f6cfcf;
    padding: 16px 20px;
}
.stock-warning-modal .modal-header .modal-title {
    display: flex;
    align-items: center;
    font-size: 15px;
    font-weight: 800;
    color: #a32d2d;
}
.stock-warning-modal .modal-body { padding: 24px 20px 10px; }
.stock-warning-icon {
    width: 56px;
    height: 56px;
    margin: 0 auto 14px;
    border-radius: 50%;
    background: #fdecec;
    color: #d43636;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}
.stock-warning-item {
    font-size: 14px;
    font-weight: 700;
    color: #1f2d3d;
    margin-bottom: 16px;
}
.stock-warning-figures {
    display: flex;
    gap: 10px;
    margin-bottom: 16px;
}
.stock-warning-figure {
    flex: 1;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 8px;
}
.stock-warning-figure span {
    display: block;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .03em;
    color: #7f8ea3;
    margin-bottom: 4px;
}
.stock-warning-figure strong { font-size: 18px; color: #1e8d5e; }
.stock-warning-figure.requested strong { color: #d43636; }
.stock-warning-note { font-size: 12.5px; color: #5a6f8b; margin-bottom: 4px; }
.stock-warning-modal .modal-footer { border-top: none; padding: 10px 20px 22px; }

/* Modern sales invoice header and form shell */
.sales-shell {
    border: 1px solid #dfeaf6 !important;
    background: #edf3fa;
    border-radius: 18px !important;
    overflow: hidden;
    box-shadow: 0 10px 35px rgba(31, 56, 88, 0.08) !important;
}
.sales-shell > .card-body {
    padding-top: 10px;
}
/* Compact customer picker table and reference-style pager. */
#selectCustomerModel .modal-dialog {
    max-width: 800px;
}
#selectCustomerModel .modal-content {
    border: 1px solid #dfeaf6;
    border-radius: 16px;
    overflow: hidden;
}
#selectCustomerModel .modal-header {
    padding: 14px 18px;
    background: #f5f9ff;
    border-bottom: 1px solid #dfeaf6;
}
#selectCustomerModel .modal-body {
    padding: 16px 18px 20px;
}
#selectCustomerModel .stock-search-wrap {
    max-width: 360px;
    margin-bottom: 14px !important;
}
#selectCustomerModel #ItemTable {
    width: 100% !important;
    table-layout: fixed;
    margin: 0 !important;
    border: 1px solid #d9e3ee;
    border-radius: 10px;
    overflow: hidden;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 13px;
}
#selectCustomerModel #ItemTable thead th {
    background: #fff;
    color: #2b3e5b;
    border-bottom: 1px solid #d9e3ee;
    padding: 10px 12px;
    font-size: 12px;
    font-weight: 800;
    text-align: left;
}
#selectCustomerModel #ItemTable tbody td {
    padding: 8px 12px;
    border-color: #edf1f5;
    color: #314765;
    vertical-align: middle;
}
#selectCustomerModel #ItemTable th:first-child,
#selectCustomerModel #ItemTable td:first-child { width: 24%; }
#selectCustomerModel #ItemTable th:nth-child(2),
#selectCustomerModel #ItemTable td:nth-child(2) { width: 56%; }
#selectCustomerModel #ItemTable th:last-child,
#selectCustomerModel #ItemTable td:last-child { width: 20%; text-align: center; }
#selectCustomerModel #ItemTable .dt-act-btn {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    border-radius: 8px;
    color: #2d6ce9;
    border: 1px solid #bcd3f7;
    background: #f5f9ff;
}
#selectCustomerModel #ItemTable .dt-act-btn:hover {
    color: #fff;
    background: #2d6ce9;
}
#selectCustomerModel #ItemTableCustomPager {
    display: flex;
    justify-content: center;
    width: 100%;
    margin-top: 18px;
}
#selectCustomerModel #ItemTableCustomPager .dt-custom-pager-row {
    margin-top: 0;
    width: 100%;
    justify-content: center;
}
#selectCustomerModel #ItemTableCustomPager .dt-custom-pager {
    padding: 6px 10px;
    gap: 2px;
}
.sales-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px 22px;
    background: rgba(255,255,255,0.28);
    border-bottom: 1px solid rgba(108, 149, 190, 0.25);
}
.sales-header-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #4f8ef7, #2d6ce9);
    color: #fff;
    box-shadow: 0 8px 18px rgba(45,108,233,0.22);
}
.sales-header-icon i { font-size: 18px; }
.sales-header-copy h4 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    letter-spacing: -0.02em;
    color: #1f2d3d;
}
.sales-header-copy p {
    margin: 4px 0 0;
    color: #5a6f8b;
    font-size: 15px;
}
.sales-form-panel {
    background: #f5f9ff;
    border: 1px solid #dfeaf6;
    border-radius: 14px;
    margin: 10px 18px 0 !important;
    width: calc(100% - 36px);
    box-sizing: border-box;
    padding: 18px 18px 12px;
}
.sales-field-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 16px;
    align-items: end;
}
.sales-field-grid-no-recall {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}
.sales-field-grid-with-recall {
    grid-template-columns: repeat(4, minmax(0, 1fr));
}
.sales-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 0;
}
.sales-field label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #2b3b4f;
    margin: 0;
}
.sales-field label i {
    color: #2d6ce9;
    font-size: 13px;
    width: 14px;
    text-align: center;
}
.sales-field label span {
    color: #d43636;
}
.sales-input-action,
.sales-icon-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    border: 1px solid #d7e3f1;
    border-radius: 10px;
    padding: 0 10px;
    min-height: 46px;
    box-shadow: inset 0 1px 2px rgba(15,23,42,0.02);
    width: 100%;
}
.sales-input-action i,
.sales-icon-wrap i {
    color: #7f8ea3;
    font-size: 14px;
}
.sales-input-action input,
.sales-icon-wrap input,
.sales-textbox,
.sales-select {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    outline: none !important;
    color: #24384e;
    font-size: 14px;
    flex: 1;
    width: 100%;
    min-width: 0;
}
.sales-input-action input::placeholder,
.sales-icon-wrap input::placeholder,
.sales-textbox::placeholder { color: #97a6b7; }
.sales-input-action .btn {
    border-radius: 10px !important;
    min-width: 38px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
}
.sales-input-action .btn.btn-primary {
    background: linear-gradient(135deg, #4c8cf7, #2d6ce9) !important;
    border-color: #2d6ce9 !important;
}
.sales-input-action .btn.btn-success {
    background: linear-gradient(135deg, #4c8cf7, #2d6ce9) !important;
    border-color: #2d6ce9 !important;
}
.sales-date-wrap {
    position: relative;
    display: flex;
    align-items: center;
    background: #fff;
    border: 1px solid #d7e3f1;
    border-radius: 10px;
    padding: 0 10px 0 36px;
    min-height: 46px;
    width: 100%;
}
.sales-date-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #7f8ea3;
    font-size: 14px;
    z-index: 1;
}
.sales-date-wrap input {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    outline: none !important;
    flex: 1;
    color: #24384e;
    width: 100%;
    appearance: none;
    -webkit-appearance: none;
}
.sales-date-wrap input::-webkit-calendar-picker-indicator {
    opacity: 0;
    position: absolute;
    right: 8px;
    width: 20px;
    height: 20px;
    cursor: pointer;
}
.sales-table-card {
    background: #fff;
    border: 1px solid #dfeaf6;
    border-radius: 16px;
    margin: 18px !important;
    width: calc(100% - 36px);
    box-sizing: border-box;
    padding: 14px 14px 10px;
    box-shadow: 0 10px 25px rgba(42, 92, 171, 0.04);
}
.sales-table-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 8px 6px 14px;
}
.sales-table-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    font-weight: 800;
    color: #234b7d;
}
.sales-table-title i {
    color: #2d6ce9;
    font-size: 18px;
}
.sales-table-toolbar {
    display: flex;
    justify-content: flex-end;
    width: 100%;
    max-width: 380px;
}
.sales-search-box {
    position: relative;
    width: 100%;
    max-width: 360px;
}
.sales-search-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #7e8fa7;
}
.sales-search-box input {
    width: 100%;
    height: 42px;
    border: 1px solid #d7e3f1;
    background: #fff;
    border-radius: 10px;
    padding: 0 14px 0 38px;
    color: #28405b;
    box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.02);
}
#salesItemTable {
    width: 100%;
    border: 1px solid #d9e3ee;
    border-radius: 12px;
    overflow: hidden;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.7);
}
#salesItemTable thead th {
    font-size: 13px;
    font-weight: 600;
    background: #F8FAFC;
    color: #1f2d3d;
    padding: 12px 10px;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
}
#salesItemTable tbody td {
    font-size: 13.5px;
    color: #314765;
    padding: 10px 8px;
    border-bottom: 1px solid #e2e8f0;
    background: #fff;
    vertical-align: middle;
}
#salesItemTable tbody tr:last-child td { border-bottom: none; }
.sales-item-input {
    background: #fff;
    border: 1px solid #d7e3f1;
    border-radius: 6px;
    min-height: 38px;
    padding: 0 10px;
    text-align: left;
}
.sales-add-btn {
    background: linear-gradient(135deg, #4c8cf7, #2d6ce9);
    color: #fff;
    border: none;
    border-radius: 8px;
    min-width: 44px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 16px rgba(45,108,233,0.18);
}
.sales-add-btn:hover { color: #fff; }
.sales-delete-btn {
    background: linear-gradient(135deg, #ff6b6b, #eb4d4d);
    color: #fff;
    border: none;
    border-radius: 8px;
    min-width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
#addNewItemRow {
    background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
    color: #1a1a1a;
    font-weight: 700;
    border: none;
    border-radius: 30px;
    padding: 10px 30px;
    font-size: 14px;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 15px rgba(247,151,30,0.5);
    animation: pulseAddBtn 1.6s ease-in-out infinite;
    cursor: pointer;
    transition: filter 0.2s;
}
#addNewItemRow:hover {
    filter: brightness(1.08);
    animation-play-state: paused;
}

/* Cheque list table styling */
#chequeListDisplay {
    background: #f0f7ff;
    border-radius: 8px;
    padding: 10px 14px;
    margin-top: 8px;
    border: 1px solid #b8d4f5;
}
#chequeListDisplay table thead th {
    background: #458ada;
    color: #fff;
    font-size: 12px;
    padding: 6px 8px;
}
#chequeListDisplay table tbody td {
    font-size: 12px;
    padding: 5px 8px;
    vertical-align: middle;
}

/* Cost guard badge on net value field */
#cg_net_badge {
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 4px;
    margin-left: 6px;
    display: none;
}
#cg_net_badge.below { background: #fef3cd; color: #856404; display: inline-block; }
#cg_net_badge.above { background: #d1e7dd; color: #0a5c36; display: inline-block; }

.payment-method-panel {
    border: 1px solid #d9e2ec;
    border-radius: 8px;
    background: #f8fafc;
    padding: 12px 14px;
    margin: 16px 0 4px;
}
.payment-method-title {
    color: #26364a;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 9px;
}
.payment-method-options {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.payment-method-option {
    align-items: center;
    background: #fff;
    border: 1px solid #d9e2ec;
    border-radius: 6px;
    color: #6b7785;
    display: inline-flex;
    gap: 7px;
    min-width: 105px;
    padding: 7px 11px;
    transition: .15s ease;
}
.payment-method-option.active {
    background: #e8f5ee;
    border-color: #31a66a;
    color: #187344;
    font-weight: 700;
}
.payment-method-option input { accent-color: #198754; margin: 0; }

.customer-detail-panel {
    background: linear-gradient(180deg, #edf5ff 0%, #e7f0ff 100%);
    border: 1px solid #cfe0f8;
    border-radius: 18px;
    box-shadow: 0 12px 28px rgba(42, 92, 171, 0.08);
    padding: 12px 10px 14px;
    margin: 14px 18px 0 !important;
    width: calc(100% - 36px) !important;
    box-sizing: border-box;
    width: 100%;
}
.customer-detail-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 0;
    align-items: stretch;
    border-radius: 14px;
    overflow: hidden;
    width: 100%;
}
.customer-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
    min-width: 0;
    padding: 0 8px;
}
.customer-field + .customer-field {
    border-left: none;
}
.customer-field label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #2c4f7c;
    font-size: 14px;
    font-weight: 700;
    min-height: 24px;
    margin: 0;
    padding: 0 4px;
}
.customer-field label i {
    color: #2d6ce9;
    width: 16px;
    text-align: center;
}
.customer-card {
    display: flex;
    flex-direction: column;
    min-width: 0;
    padding: 0 8px;
    border-right: 1px solid rgba(206, 223, 244, 0.95);
}
.customer-card:last-child { border-right: none; }
.customer-label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #2c4f7c;
    font-size: 14px;
    font-weight: 700;
    min-height: 32px;
    padding: 4px 4px 10px;
}
.customer-label i {
    color: #2d6ce9;
    font-size: 15px;
    width: 16px;
    text-align: center;
    flex-shrink: 0;
}
.customer-value,
.customer-balance-box,
.wholesale-toggle-wrap {
    min-height: 42px;
    border: 1px solid #d8e5f4;
    background: #fff;
    border-radius: 12px;
    display: flex;
    align-items: center;
    padding: 0 10px;
    box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.02);
}
.customer-value {
    color: #1d2e44;
    font-size: 17px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.customer-balance-box {
    overflow: hidden;
    width: 100%;
}
.customer-balance-box .balance-label {
    background: linear-gradient(135deg, #2d6ce9, #4d8ef7);
    color: #fff;
    width: 60px;
    min-height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: 800;
    margin-left: -10px;
    margin-right: 10px;
    padding: 0 8px;
}
.customer-balance-box .balance-amount {
    flex: 1;
    font-size: 15px;
    font-weight: 800;
    color: #d73d3d;
    letter-spacing: 0.01em;
}
.customer-balance-box .balance-amount.text-success { color: #1e8d5e !important; }
.wholesale-toggle-wrap {
    justify-content: flex-start;
    gap: 10px;
}
.wholesale-toggle-wrap input {
    width: 18px;
    height: 18px;
    accent-color: #2d6ce9;
    margin: 0;
}
.wholesale-toggle-wrap label {
    margin: 0;
    font-size: 15px;
    font-weight: 600;
    color: #2c4f7c;
}

/* Cheque Details modal */
.cheque-modal { border-radius: 16px; overflow: hidden; border: none; }
.cheque-modal .modal-header {
    background: #f5f9ff;
    border-bottom: 1px solid #dfeaf6;
    padding: 16px 20px;
}
.cheque-modal-title { display: flex; align-items: center; gap: 12px; }
.cheque-modal-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #4c8cf7, #2d6ce9);
    color: #fff;
    flex-shrink: 0;
    box-shadow: 0 6px 14px rgba(45, 108, 233, 0.25);
}
.cheque-modal .modal-title { font-size: 15px; font-weight: 800; color: #1f2d3d; margin: 0; }
.cheque-modal .modal-body { padding: 20px; }
.cheque-summary-strip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-bottom: 18px;
}
.cheque-summary-figure {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 9px 10px;
}
.cheque-summary-figure span {
    display: block;
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .03em;
    color: #7f8ea3;
    margin-bottom: 3px;
}
.cheque-summary-figure strong { font-size: 15px; color: #1f2d3d; }
.cheque-summary-figure.remaining strong { color: #d43636; }
.cheque-field-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
}
.cheque-field { display: flex; flex-direction: column; gap: 7px; min-width: 0; }
.cheque-field label {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 12px;
    font-weight: 700;
    color: #2b3b4f;
    margin: 0;
}
.cheque-field label i { color: #2d6ce9; font-size: 12px; width: 13px; text-align: center; }
.cheque-field .sales-icon-wrap { min-height: 44px; }
.cheque-field select.sales-select {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    outline: none !important;
    color: #24384e;
    font-size: 14px;
    flex: 1;
    width: 100%;
}
.cheque-error { font-size: 12.5px; color: #d43636; font-weight: 600; margin-top: 10px; }
.cheque-modal .modal-footer { border-top: 1px solid #edf1f5; padding: 14px 20px 18px; gap: 10px; }
.cheque-modal .modal-footer .recall-load-btn,
.cheque-modal .modal-footer .recall-secondary-btn { flex: 1; justify-content: center; }

/* A finalized/recalled invoice is strictly view-only — grey out every field
   inside it regardless of readonly/disabled state so it's unmistakable at a
   glance, not just functionally blocked. */
.invoice-view-locked .form-control,
.invoice-view-locked input,
.invoice-view-locked select,
.invoice-view-locked textarea {
    background: #eef1f5 !important;
    color: #6b7785 !important;
    cursor: not-allowed !important;
    opacity: 1 !important;
}
.invoice-view-locked .stock-item-search-btn,
.invoice-view-locked button[type="submit"] {
    display: none !important;
}
</style>