<div class="modal fade" id="chequeDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content cheque-modal">
            <div class="modal-header">
                <div class="cheque-modal-title">
                    <div class="cheque-modal-icon"><i class="fas fa-money-check-alt"></i></div>
                    <h5 class="modal-title">Cheque Details</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- Summary strip --}}
                <div class="cheque-summary-strip">
                    <div class="cheque-summary-figure">
                        <span>Net Amount</span>
                        <strong id="modal_net_display">0.00</strong>
                    </div>
                    <div class="cheque-summary-figure">
                        <span>Cash</span>
                        <strong id="modal_cash_display">0.00</strong>
                    </div>
                    <div class="cheque-summary-figure">
                        <span>Credit</span>
                        <strong id="modal_credit_display">0.00</strong>
                    </div>
                    <div class="cheque-summary-figure remaining">
                        <span>Remaining</span>
                        <strong id="modal_remaining_display">0.00</strong>
                    </div>
                </div>

                <div class="cheque-field-grid">
                    <div class="cheque-field">
                        <label><i class="fas fa-university"></i>Bank <span class="text-danger">*</span></label>
                        <div class="sales-icon-wrap">
                            <i class="fas fa-landmark"></i>
                            <select id="cheque_bank" class="sales-select">
                                <option value="" data-bank-name="" data-branch="" data-account-no="">Select Bank</option>
                                @foreach($chequeBanks ?? [] as $b)
                                    <option value="{{ $b->id }}"
                                        data-bank-name="{{ $b->bank_name }}"
                                        data-branch="{{ $b->branch }}"
                                        data-account-no="{{ $b->account_no }}">
                                        {{ $b->bank_name }}{{ $b->branch ? ' - '.$b->branch : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="cheque-field">
                        <label><i class="fas fa-map-marker-alt"></i>Bank Branch</label>
                        <div class="sales-icon-wrap">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" id="cheque_bank_branch" class="sales-textbox"
                                placeholder="Select a bank first" readonly>
                        </div>
                    </div>
                    <div class="cheque-field">
                        <label><i class="fas fa-hashtag"></i>Cheque No <span class="text-danger">*</span></label>
                        <div class="sales-icon-wrap">
                            <i class="fas fa-hashtag"></i>
                            <input type="text" id="cheque_no" class="sales-textbox" placeholder="Cheque Number">
                        </div>
                    </div>
                    <div class="cheque-field">
                        <label><i class="fas fa-credit-card"></i>Account No</label>
                        <div class="sales-icon-wrap">
                            <i class="fas fa-credit-card"></i>
                            <input type="text" id="cheque_acc_no" class="sales-textbox"
                                placeholder="Select a bank first" readonly>
                        </div>
                    </div>
                    <div class="cheque-field">
                        <label><i class="fas fa-coins"></i>Amount <span class="text-danger">*</span></label>
                        <div class="sales-icon-wrap">
                            <i class="fas fa-coins"></i>
                            <input type="text" id="cheque_amount_input" class="sales-textbox" placeholder="Amount">
                        </div>
                    </div>
                    <div class="cheque-field">
                        <label><i class="fas fa-calendar-alt"></i>Cheque Date <span class="text-danger">*</span></label>
                        <div class="sales-icon-wrap">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="date" id="cheque_release_date" class="sales-textbox">
                        </div>
                    </div>
                </div>

                <div id="cheque_error" class="cheque-error" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="recall-load-btn" id="saveChequeDetails">
                    <i class="fas fa-plus-circle"></i> Add Cheque
                </button>
                <button type="button" class="recall-secondary-btn" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
