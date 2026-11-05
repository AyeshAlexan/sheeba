<div class="stock-info-card mt-3 ">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <h5 class="mb-0"><i class="fas fa-cash-register text-primary me-2"></i>Payment Method</h5>
        <div class="payment-method-options" id="paymentMethodOptions">
            <label class="payment-method-option" data-method="cash">
                <input type="checkbox" id="payment_method_cash" disabled>
                <span>Cash</span>
            </label>
            <label class="payment-method-option" data-method="credit">
                <input type="checkbox" id="payment_method_credit" disabled>
                <span>Credit</span>
            </label>
            <label class="payment-method-option" data-method="cheque">
                <input type="checkbox" id="payment_method_cheque" disabled>
                <span>Cheque</span>
            </label>
        </div>
    </div>
    <p class="text-muted small mb-0">Payment method is updated automatically from the amounts entered below.</p>
</div>

<div class="stock-info-card mt-3">
    <div class="stock-info-grid-2 ">
        <div class="si-field">
            <label>Cash Pay</label>
            <div class="si-icon-wrap">
                <i class="fas fa-money-bill-wave"></i>
                <input type="text" class="form-control" id="cash_payment"
                    name="cash_payment" placeholder="0.00">
            </div>
        </div>
        <div class="si-field">
            <label>Credit</label>
            <div class="si-icon-wrap">
                <i class="fas fa-credit-card"></i>
                <input type="text" class="form-control" readonly id="credite_payment"
                    name="credite_payment" placeholder="0.00">
            </div>
        </div>
        <div class="si-field">
            <label>Half Payment</label>
            <div class="si-icon-wrap">
                <i class="fas fa-hand-holding-usd"></i>
                <input type="text" class="form-control" id="half_payment"
                    name="half_payment" placeholder="0.00">
            </div>
        </div>
        <div class="si-field">
            <label>Item Discount Total</label>
            <div class="si-icon-wrap">
                <i class="fas fa-percent"></i>
                <input type="text" class="form-control" readonly id="paid_discount"
                    name="discount" placeholder="0.00" value="0.00" readonly>
            </div>
        </div>
        <div class="si-field">
            <label>Cheque</label>
            <div class="stock-item-search-row">
                <div class="si-icon-wrap flex-grow-1">
                    <i class="fas fa-money-check-alt"></i>
                    <input type="text" class="form-control" id="cheque_payment"
                        name="cheque_payment" placeholder="Amount, then Enter or click +">
                </div>
                <button type="button" class="stock-item-search-btn" id="openChequeModalBtn" title="Add cheque details">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="stock-info-grid-2 mt-3">
        <div class="si-field">
            <label>Net Amount <span class="text-danger">*</span></label>
            <div class="si-icon-wrap">
                <i class="fas fa-wallet"></i>
                <input type="text" class="form-control" readonly id="paid_amount"
                    name="net_amount" placeholder="0.00" required>
            </div>
        </div>
        <div class="si-field">
            <label>Gross Amount</label>
            <div class="si-icon-wrap">
                <i class="fas fa-coins"></i>
                <input type="text" class="form-control" id="total_amount"
                    name="gross_amount" placeholder="0.00" readonly>
            </div>
        </div>
        <div class="si-field">
            <label>Invoice Remark</label>
            <div class="si-icon-wrap">
                <i class="fas fa-sticky-note"></i>
                <input type="text" class="form-control" id="invoice_remark"
                    name="invoice_remark" placeholder="Invoice Remark">
            </div>
        </div>
    </div>

    {{-- ══ CHEQUE LIST ══ --}}
    <div id="chequeListDisplay" style="display:none; margin-top:16px;">
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-1" id="chequeListTable">
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>Bank Branch</th>
                        <th>Cheque No</th>
                        <th>Acc No</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody id="chequeListBody"></tbody>
            </table>
        </div>
        <div class="text-end pe-1">
            <strong>Total Cheques : <span id="totalChequeDisplay"
                style="color:#185FA5;">0.00</span></strong>
        </div>
    </div>
    {{-- ══ END CHEQUE LIST ══ --}}
</div>{{-- end payment section --}}