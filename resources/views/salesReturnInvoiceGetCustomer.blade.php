@foreach ($customer_get as $test)
    <div class="row mt-3 p-3 rounded shadow-sm border border-success" style="background: linear-gradient(135deg, #f0fff4, #e6ffed);">
        <div class="row g-3 align-items-end">

            <div class="col-md-2">
                <label class="form-label fw-semibold text-secondary">
                    <i class="bi bi-person-fill me-1 text-success"></i>Customer Name
                </label>
                <input class="form-control border-success shadow-sm" type="text" value="{{ $test['First_name'] }}" id="customer_name" name="customer_name" readonly>
                <input type="hidden" value="{{ $test['Code'] }}" id="customer_nic" name="customer_nic">
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold text-secondary">
                    <i class="bi bi-telephone-fill me-1 text-success"></i>Customer Tel
                </label>
                <input class="form-control border-success shadow-sm" type="text" value="{{ $test['Contact_1'] }}" id="customer_phone" name="customer_phone" readonly>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold text-secondary">
                    <i class="bi bi-geo-alt-fill me-1 text-success"></i>Address
                </label>
                <input class="form-control border-success shadow-sm" type="text" value="{{ $test['Address_1'] }}" id="customer_address" name="customer_address" readonly>
            </div>

     <div class="col-md-3">
    @if($advance < 0)
        <label class="form-label fw-semibold text-success">
            <i class="bi bi-cash-coin me-1"></i> Advance Paid
        </label>
        <div class="input-group">
            <span class="input-group-text bg-success text-white border-success">Rs.</span>
            <input class="form-control fw-bold text-success border-success shadow-sm"
                   type="text"
                   style="background-color: #fde8e8;"
                   value="{{ number_format(abs($advance), 2) }}"
                   name="advance_amount"
                   readonly>
        </div>
    @else
        <label class="form-label fw-semibold text-danger">
            <i class="bi bi-cash-coin me-1"></i> Balance
        </label>
        <div class="input-group">
            <span class="input-group-text bg-danger text-white border-danger">Rs.</span>
            <input class="form-control fw-bold text-danger border-danger shadow-sm"
                   type="text"
                   style="background-color: #d4edda;"
                   value="{{ number_format($advance, 2) }}"
                   name="advance_amount"
                   readonly>
        </div>
    @endif
</div>

      <div class="col-md-2">
    <div class="form-check">
        <input class="form-check-input border-success shadow-sm" type="checkbox" id="customer_toggle">

        <label class="form-check-label fw-semibold text-secondary" for="customer_toggle">
            <i class="bi bi-tag-fill me-1 text-success"></i>Wholesale customers
        </label>

        <input id="customer_type_display"
               class="form-control fw-bold text-success border-success shadow-sm"
               type="hidden"
               value="Retail"
               name="customer_type"
               style="background-color: #d4edda;"
               readonly>
    </div>
</div>

<script>
    const checkbox = document.getElementById('customer_toggle');
    const displayInput = document.getElementById('customer_type_display');

    checkbox.addEventListener('change', function() {
        if (this.checked) {
            displayInput.value = 'Wholesale';
        } else {
            displayInput.value = 'Retail';
        }
    });
</script>

        </div>
    </div>
@endforeach