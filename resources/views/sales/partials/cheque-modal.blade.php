<div class="modal fade" id="chequeDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background:#458ada;">
                <h5 class="modal-title text-white fw-bold">
                    <i class="fas fa-money-check-alt me-2"></i> Cheque Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- Summary bar --}}
                <div class="alert alert-info py-2 mb-3" style="font-size:13px;">
                    <strong>Net Amount :</strong>
                    <span id="modal_net_display">0.00</span> &nbsp;|&nbsp;
                    <strong>Cash :</strong>
                    <span id="modal_cash_display">0.00</span> &nbsp;|&nbsp;
                    <strong>Credit :</strong>
                    <span id="modal_credit_display">0.00</span> &nbsp;|&nbsp;
                    <strong>Remaining :</strong>
                    <span id="modal_remaining_display" class="fw-bold text-danger">0.00</span>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="fw-bold">Bank Name <span class="text-danger">*</span></label>
                        <input type="text" id="cheque_bank" class="form-control"
                            placeholder="e.g. Bank of Ceylon">
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Bank Branch</label>
                        <input type="text" id="cheque_bank_branch" class="form-control"
                            placeholder="e.g. Kandy">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="fw-bold">Cheque No <span class="text-danger">*</span></label>
                        <input type="text" id="cheque_no" class="form-control"
                            placeholder="Cheque Number">
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Account No</label>
                        <input type="text" id="cheque_acc_no" class="form-control"
                            placeholder="Account Number">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <label class="fw-bold">Amount <span class="text-danger">*</span></label>
                        <input type="text" id="cheque_amount_input" class="form-control"
                            placeholder="Amount">
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Cheque Date <span class="text-danger">*</span></label>
                        <input type="date" id="cheque_release_date" class="form-control">
                    </div>
                </div>

                <div id="cheque_error" class="text-danger mt-1" style="display:none;font-size:13px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="saveChequeDetails">
                    <i class="fas fa-plus-circle me-1"></i> Add Cheque
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

