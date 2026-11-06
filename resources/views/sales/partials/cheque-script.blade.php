<script>
var chequeList = [];

$(document).on('keydown', '#cheque_payment', function (e) {
    if (e.keyCode !== 13) return;
    e.preventDefault();
    var val = parseFloat($(this).val()) || 0;
    if (val <= 0) { alert('Please enter a cheque amount first.'); return; }
    openChequeModal();
});

$(document).on('click', '#openChequeModalBtn', function () {
    var val = parseFloat($('#cheque_payment').val()) || 0;
    if (val <= 0) { alert('Please enter a cheque amount first.'); return; }
    openChequeModal();
});

$(document).on('blur', '#cheque_payment', function () {
    var val = parseFloat($(this).val()) || 0;
    if (val > 0 && chequeList.length === 0) openChequeModal();
});

function openChequeModal() {
    if ($('#cheque_payment').prop('readonly')) return;
    var net    = parseFloat($('#paid_amount').val())     || 0;
    var cash   = parseFloat($('#cash_payment').val())    || 0;
    var credit = parseFloat($('#credite_payment').val()) || 0;
    var cheque = parseFloat($('#cheque_payment').val())  || 0;

    $('#modal_net_display').text(net.toFixed(2));
    $('#modal_cash_display').text(cash.toFixed(2));
    $('#modal_credit_display').text(credit.toFixed(2));

    var listedChequeTotal = chequeList.reduce(function (s, c) {
        return s + (parseFloat(c.amount) || 0);
    }, 0);
    var remaining = net - cash - credit - listedChequeTotal;
    $('#modal_remaining_display').text(remaining.toFixed(2));

    $('#cheque_amount_input').val(cheque > 0 ? cheque.toFixed(2) : (remaining > 0 ? remaining.toFixed(2) : ''));
    $('#cheque_release_date').val(new Date().toISOString().slice(0, 10));
    $('#cheque_bank, #cheque_bank_branch, #cheque_no, #cheque_acc_no').val('');
    $('#cheque_error').hide();
    bootstrap.Modal.getOrCreateInstance(document.getElementById('chequeDetailsModal')).show();
    $('#chequeDetailsModal').one('shown.bs.modal', function () { $('#cheque_bank').focus(); });
}

// Selecting a bank from the master list auto-fills its branch/account no —
// same pattern as Customer Payment / Supplier Payment.
$(document).on('change', '#cheque_bank', function () {
    var opt = $(this).find('option:selected');
    $('#cheque_bank_branch').val(opt.data('branch') || '');
    $('#cheque_acc_no').val(opt.data('account-no') || '');
});

$('#saveChequeDetails').on('click', function () {
    var bankId      = $('#cheque_bank').val();
    var bank        = ($('#cheque_bank').find('option:selected').data('bank-name') || '').toString().trim();
    var branch      = $('#cheque_bank_branch').val().trim();
    var chequeNo    = $('#cheque_no').val().trim();
    var accNo       = $('#cheque_acc_no').val().trim();
    var amount      = parseFloat($('#cheque_amount_input').val()) || 0;
    var releaseDate = $('#cheque_release_date').val();

    $('#cheque_error').hide();
    if (!bankId)      { $('#cheque_error').text('Please select a bank.').show();      $('#cheque_bank').focus();           return; }
    if (!chequeNo)    { $('#cheque_error').text('Cheque number is required.').show(); $('#cheque_no').focus();             return; }
    if (amount <= 0)  { $('#cheque_error').text('Amount must be greater than 0.').show(); $('#cheque_amount_input').focus(); return; }
    if (!releaseDate) { $('#cheque_error').text('Cheque date is required.').show();   $('#cheque_release_date').focus();   return; }

    chequeList.push({ bank, branch, chequeNo, accNo, amount, releaseDate });
    renderChequeTable();
    syncChequeHiddenFields();
    recalcCreditFromPayments();
    bootstrap.Modal.getOrCreateInstance(document.getElementById('chequeDetailsModal')).hide();
});

$('#chequeDetailsModal').on('keydown', function (e) {
    if (e.keyCode === 13) { e.preventDefault(); $('#saveChequeDetails').trigger('click'); }
});

function renderChequeTable() {
    if (!chequeList.length) {
        $('#chequeListDisplay').hide();
        $('#chequeListBody').html('');
        $('#totalChequeDisplay').text('0.00');
        return;
    }
    var rows = '', total = 0;
    chequeList.forEach(function (c, i) {
        total += parseFloat(c.amount) || 0;
        rows  += '<tr>' +
            '<td>' + escapeInvoiceText(c.bank) + '</td>' +
            '<td>' + escapeInvoiceText(c.branch || '-') + '</td>' +
            '<td><strong>' + escapeInvoiceText(c.chequeNo) + '</strong></td>' +
            '<td>' + escapeInvoiceText(c.accNo || '-') + '</td>' +
            '<td class="text-end">' + parseFloat(c.amount).toFixed(2) + '</td>' +
            '<td>' + escapeInvoiceText(c.releaseDate) + '</td>' +
            '<td class="text-center">' +
                '<button type="button" class="btn btn-outline-danger btn-sm remove-cheque" ' +
                    'data-index="' + i + '"><i class="far fa-trash-alt"></i></button>' +
            '</td></tr>';
    });
    $('#chequeListBody').html(rows);
    $('#totalChequeDisplay').text(total.toFixed(2));
    $('#cheque_payment').val(total.toFixed(2));
    $('#chequeListDisplay').show();
}

$(document).on('click', '.remove-cheque', function () {
    chequeList.splice(parseInt($(this).data('index')), 1);
    renderChequeTable();
    syncChequeHiddenFields();
    if (!chequeList.length) $('#cheque_payment').val('');
    recalcCreditFromPayments();
});

function syncChequeHiddenFields() {
    var total = chequeList.reduce(function (s, c) { return s + (parseFloat(c.amount) || 0); }, 0);
    $('#cheques_data').val(JSON.stringify(chequeList));
    $('#total_cheque_amount').val(total.toFixed(2));
}
</script>