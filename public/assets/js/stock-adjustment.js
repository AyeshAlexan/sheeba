$(function () {
    const table = document.querySelector('#stockAdjItemsTable');
    const form = table.closest('form');
    const rows = Array.from(table.tBodies[0].rows);
    const error = document.querySelector('#adjustmentError');

    function preview(row, submitting = false) {
        const action = row.querySelector('select').value;
        row.dataset.action = action;
        const input = row.querySelector('input');
        const current = Number(row.dataset.balance);
        const quantity = input.valueAsNumber;
        input.disabled = !action;
        if (!action) input.value = '';
        row.querySelector('.quantity-label').textContent = {
            increase: 'Increase by', reduce: 'Reduce by'
        }[action] || 'Quantity';

        let message = '';
        if (action && !input.validity.valid) {
            if (input.validity.rangeOverflow) message = 'Maximum 999,999.99.';
            else if (input.validity.stepMismatch) message = 'Use up to 2 decimal places.';
            else message = 'Enter a quantity above zero.';
        } else if (action && !input.value && submitting) {
            message = 'Enter a quantity.';
        } else if (action === 'reduce' && quantity > current) {
            message = 'Only ' + current + ' units available.';
        }
        const change = action === 'reduce' ? -quantity : quantity;
        row.querySelector('output').value = message || (action && !input.value) ? '—' : Number((current + (action ? change : 0)).toFixed(2));
        input.setAttribute('aria-invalid', String(Boolean(message)));
        row.querySelector('small').textContent = message;
        return message;
    }

    rows.forEach(row => preview(row));
    const grid = $(table).DataTable({
        dom: 't', pageLength: 10, info: false,
        columnDefs: [{ targets: [1, 2, 3, 4], searchable: false }, { targets: [2, 3, 4], orderable: false }]
    });
    $('#stockAdjItemsTable_wrapper').addClass('dt-collapsed');
    DTCustomPager.init(grid, '#stockAdjItemsCustomPager');
    $('#stockItemSearch').on('input', function () { grid.search(this.value).draw(); });
    $(table).on('change input', 'select, input', function () {
        error.textContent = '';
        preview(this.closest('tr'));
    });

    form.addEventListener('submit', function (event) {
        const changed = rows.filter(row => row.querySelector('select').value);
        for (const row of changed) {
            const message = preview(row, true);
            if (message) {
                event.preventDefault();
                error.textContent = row.dataset.code + ' — ' + message;
                return;
            }
        }
        if (!changed.length) {
            event.preventDefault();
            error.textContent = 'Choose an item to increase or reduce.';
            return;
        }
        form.elements.lines.value = JSON.stringify(changed.map(row => ({
            item_code: row.dataset.code,
            balance: row.dataset.balance,
            action: row.querySelector('select').value,
            quantity: row.querySelector('input').value
        })));
    });
});
