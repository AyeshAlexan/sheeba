@foreach ($customer_get as $test)
	<div class="customer-detail-panel">
		<div class="customer-detail-grid">
			<div class="customer-field">
				<label><i class="fas fa-user"></i> Customer Name</label>
				<div class="customer-value" id="customer_name">{{ $test['First_name'] }}</div>
				<input type="hidden" value="{{ $test['Code'] }}" id="customer_nic" name="customer_nic">
			</div>

			<div class="customer-field">
				<label><i class="fas fa-phone-alt"></i> Customer Tel</label>
				<div class="customer-value" id="customer_phone">{{ $test['Contact_1'] }}</div>
			</div>

			<div class="customer-field">
				<label><i class="fas fa-map-marker-alt"></i> Address</label>
				<div class="customer-value" id="customer_address">{{ $test['Address_1'] }}</div>
			</div>

			<div class="customer-field">
				<label><i class="fas fa-wallet"></i> BalanceRs.</label>
				<div class="customer-balance-box">
					<span class="balance-label">Rs.</span>
					@if($advance !== null && $advance < 0)
						<span class="balance-amount text-success">{{ number_format(abs($advance), 2) }}</span>
					@else
						<span class="balance-amount">{{ number_format($advance ?? 0, 2) }}</span>
					@endif
				</div>
			</div>

			<div class="customer-field">
				<label><i class="fas fa-tags"></i> Wholesale customers</label>
				<div class="wholesale-toggle-wrap">
					<input type="checkbox" id="customer_toggle">
					<label for="customer_toggle">Wholesale</label>
				</div>
				<input id="customer_type_display" type="hidden" value="Retail" name="customer_type" readonly>
			</div>
		</div>
	</div>

	<script>
		const checkbox = document.getElementById('customer_toggle');
		const displayInput = document.getElementById('customer_type_display');
		if (checkbox && displayInput) {
			checkbox.addEventListener('change', function() {
				displayInput.value = this.checked ? 'Wholesale' : 'Retail';
			});
		}
	</script>
@endforeach
