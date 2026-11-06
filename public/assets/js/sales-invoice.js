/* Sales Invoice only converts an order; item entry belongs to Sales Order. */
(function ($) {
    'use strict';
    var config = window.salesInvoiceConfig;
    var viewingSaved = false;
    var requestVersion = 0;
    var previousUsed = false;

    function number(value) { return parseFloat(value) || 0; }
    function money(value) { return Math.round((value + Number.EPSILON) * 100) / 100; }
    function updatePaymentMethodIndicators() {
        var methods = { cash: number($('#cash_payment').val()) + number($('#half_payment').val()) > 0,
            credit: number($('#credite_payment').val()) > 0, cheque: number($('#cheque_payment').val()) > 0 };
        Object.keys(methods).forEach(function (method) {
            var option = $('.payment-method-option[data-method="' + method + '"]');
            option.toggleClass('active', methods[method]).find('input').prop('checked', methods[method]);
        });
    }
    window.recalcCreditFromPayments = function () {
        if (!viewingSaved) {
            var remainder = money(number($('#paid_amount').val()) - number($('#cash_payment').val()) -
                number($('#half_payment').val()) - number($('#cheque_payment').val()));
            $('#credite_payment').val(Math.max(0, remainder).toFixed(2));
        }
        updatePaymentMethodIndicators();
    };
    function recalculate() {
        var gross = 0, discount = 0, net = 0;
        $('#invoiceLines tbody tr').each(function () {
            var row = $(this);
            var lineGross = number(row.find('[name$="[qty]"]').val()) * number(row.find('[name$="[unit_price]"]').val());
            var lineDiscount = money(lineGross * number(row.find('[name$="[discount]"]').val()) / 100);
            var lineNet = money(lineGross - lineDiscount);
            row.find('[name$="[net_value]"]').val(lineNet.toFixed(2));
            gross += lineGross; discount += lineDiscount; net += lineNet;
        });
        $('#total_amount').val(money(gross).toFixed(2));
        $('#paid_discount').val(money(discount).toFixed(2));
        $('#paid_amount').val(money(net).toFixed(2));
        $('#itemsNetTotal').text(money(net).toFixed(2));
        window.recalcCreditFromPayments();
    }
    function input(row, index, key, value, editable, hardLock) {
        var cell = $('<td>').appendTo(row);
        var field = $('<input>', {class: 'form-control', name: 'inputs[' + index + '][' + key + ']',
            type: editable ? 'number' : 'text', readonly: !editable, 'aria-label': key}).val(value == null ? '' : value).appendTo(cell);
        if (editable) field.attr({step: 'any', min: key === 'qty' ? '0.001' : '0', required: true});
        if (key === 'discount') field.attr('max', 100);
        // A finalized/saved invoice is read-only end to end — disable (not just
        // readonly) so the field can't be focused/typed into at all.
        if (hardLock) field.prop('disabled', true);
    }
    function renderLines(lines, locked) {
        var body = $('#invoiceLines tbody').empty();
        Object.values(lines).forEach(function (line, index) {
            var row = $('<tr>').appendTo(body);
            input(row, index, 'item_code', line.item_code, false, locked);
            input(row, index, 'item_description', line.item_description, false, locked);
            input(row, index, 'batch_no', line.batch_no, false, locked);
            input(row, index, 'unit_price', line.unit_price, !locked, locked);
            input(row, index, 'qty', line.qty, !locked, locked);
            input(row, index, 'discount', line.discount || 0, !locked, locked);
            input(row, index, 'net_value', line.net_value || 0, false, locked);
            var action = $('<td>').appendTo(row);
            if (!locked) $('<button>', {type:'button', class:'btn btn-outline-danger remove-order-line', text:'Remove'}).appendTo(action);
            $('<input>', {type:'hidden', name:'inputs[' + index + '][Free_Issues]'}).val(line.Free_Issues || 0).appendTo(action);
            $('<input>', {type:'hidden', name:'inputs[' + index + '][Per]'}).val(line.Per || '').appendTo(action);
        });
    }
    function clearForm() {
        viewingSaved = false;
        $('#invoiceDetails').prop('hidden', true).removeClass('invoice-view-locked');
        $('#savedInvoiceNotice').prop('hidden', true).text('');
        $('#sales_order_id').val('');
        $('#invoiceLines tbody').empty();
        $('#cash_payment, #half_payment, #cheque_payment').prop({readonly: false, disabled: false});
        $('#cash_payment, #half_payment, #cheque_payment').val('');
        $('#invoice_no').val(config.nextInvoice);
        $('#invoice_date').prop({readonly: false, disabled: false});
        $('#invoice_remark').prop({readonly: false, disabled: false});
        chequeList = []; renderChequeTable(); syncChequeHiddenFields();
        $('#savedInvoicePrint, #savedInvoicePosPrint').prop('hidden', true);
        $('#finalizeInvoiceBtn, #openChequeModalBtn, #itemEditHint').show().prop('disabled', false);
    }
    function header(payload, orderNo) {
        $('#loadedOrderNo').text(orderNo || '-');
        $('#loadedCustomer').text((payload.customer_name || '') + ' (' + payload.customer_nic + ')');
        ['customer_nic','customer_name','customer_phone','customer_type','Route','Salesmen','ref_no','invoice_remark'].forEach(function (key) {
            $('#' + key).val(payload[key] || '');
        });
    }
    $('#loadOrderBtn').on('click', function () {
        var orderNo = $('#load_order_no').val();
        if (!orderNo) return;
        var version = ++requestVersion;
        clearForm();
        $('#orderStatus').removeClass('is-success is-warn is-error').text('Loading sales order...');
        $.get(config.orderUrl + '/' + encodeURIComponent(orderNo)).done(function (res) {
            if (version !== requestVersion) return;
            if (res.invoice_no !== null) {
                $('#orderStatus').addClass('is-warn').text('Sales Order #' + res.id + ' is already linked to Invoice #' + res.invoice_no + '. Use Recall Saved Invoice to view it.');
                return;
            }
            var payload = res.payload;
            var previous = !previousUsed && config.previous && String(config.previous.sales_order_id) === String(res.id) ? config.previous : null;
            if (previous) { payload = previous; previousUsed = true; }
            $('#sales_order_id').val(res.id);
            header(payload, res.id);
            renderLines(payload.inputs, false);
            $('#invoiceDetails').prop('hidden', false);
            if (previous) {
                $('#invoice_date').val(previous.invoice_date);
                ['cash_payment','half_payment','cheque_payment'].forEach(function (key) { $('#' + key).val(previous[key] || ''); });
                try { chequeList = JSON.parse(previous.cheques_data || '[]'); } catch (e) { chequeList = []; }
                renderChequeTable(); syncChequeHiddenFields();
            }
            recalculate();
            $('#orderStatus').addClass('is-success').text('Sales Order #' + res.id + ' loaded. Review the items and enter payments below.');
        }).fail(function (xhr) {
            if (version === requestVersion) $('#orderStatus').addClass('is-error').text(xhr.responseJSON?.message || 'Unable to load sales order.');
        });
    });
    $('#recallInvoiceBtn').on('click', function () {
        var no = $('#recall_invoice_no').val().trim();
        if (!no) return;
        var version = ++requestVersion;
        clearForm(); $('#orderStatus').removeClass('is-success is-warn is-error').text('Loading saved invoice...');
        $.when($.get(config.recallSummaryUrl, {search_receipt_no:no}), $.get(config.recallDetailsUrl, {search_receipt_no:no})).done(function (summaryResponse, linesResponse) {
            if (version !== requestVersion) return;
            var summary = summaryResponse[0], details = linesResponse[0];
            if (summary.status !== 'success' || details.status !== 'success') { $('#orderStatus').addClass('is-error').text('Invoice not found.'); return; }
            var invoice = summary.data[0]; viewingSaved = true;
            header({customer_nic:invoice.Customer_NIC, customer_name:invoice.Customer_Name, customer_phone:invoice.Customer_Phone, invoice_remark:invoice.invoice_remark}, summary.sales_order_id || '-');
            $('#invoice_no').val(invoice.Invoice_no); $('#invoice_date').val(invoice.Invoice_date).prop('readonly', true);
            renderLines(details.data.map(function (line) { return {item_code:line.Item_code,item_description:line.Item_description,
                batch_no:line.batch_no,unit_price:line.Unit_price,qty:line.QTY,discount:line.DiscountPercentage,net_value:line.Net_value}; }), true);
            $('#total_amount').val(invoice.Gross_Amount); $('#paid_discount').val(invoice.Discount); $('#paid_amount').val(invoice.Net_Amount);
            $('#itemsNetTotal').text(number(invoice.Net_Amount).toFixed(2));
            $('#cash_payment').val(invoice.Cash_Pay); $('#half_payment').val(invoice.Half_Payment); $('#cheque_payment').val(invoice.Cheque); $('#credite_payment').val(invoice.Credite);
            // disabled (not just readonly) so a finalized invoice's payment/date/remark
            // fields can't be focused or typed into at all, and visually grey out via
            // the .invoice-view-locked class below — readonly alone still looks editable.
            $('#cash_payment, #half_payment, #cheque_payment, #invoice_remark, #invoice_date').prop({readonly: true, disabled: true});
            $('#finalizeInvoiceBtn, #openChequeModalBtn, #itemEditHint').hide().prop('disabled', true);
            $('#invoiceDetails').addClass('invoice-view-locked');
            ['savedInvoicePrint','savedInvoicePosPrint'].forEach(function (id, index) {
                var params = new URLSearchParams({invoice_no:invoice.Invoice_no, branch_code:config.branch, print_type:index ? 'pos':'normal'});
                $('#' + id).attr('href', config.printUrl + '?' + params.toString()).prop('hidden', false);
            });
            $('#invoiceDetails').prop('hidden', false); updatePaymentMethodIndicators();
            var savedNotice = invoice.confirmed_at
                ? 'Saved Invoice #' + invoice.Invoice_no + ' ? view only. This is a final invoice; stock and payments have already been recorded. Use the buttons below to print it.'
                : 'Earlier Saved Invoice #' + invoice.Invoice_no + ' ? view only. This record predates the Sales Order workflow. Opening it does not change stock or payments.';
            $('#orderStatus').addClass('is-success').text(savedNotice);
            $('#savedInvoiceNotice').text(savedNotice).prop('hidden', false);
        }).fail(function () { if (version === requestVersion) $('#orderStatus').addClass('is-error').text('Unable to load saved invoice.'); });
    });
    $(document).on('input', '#invoiceLines input[type=number]', recalculate);
    $(document).on('click', '.remove-order-line', function () { $(this).closest('tr').remove(); recalculate(); });
    $(document).on('input change', '#cash_payment, #half_payment', function () {
        if (number($(this).val()) > 0) $(this.id === 'cash_payment' ? '#half_payment' : '#cash_payment').val('');
        window.recalcCreditFromPayments();
    });
    $(document).on('input change', '#cheque_payment', window.recalcCreditFromPayments);
    $('#load_order_no, #recall_invoice_no').on('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); $(this.id === 'load_order_no' ? '#loadOrderBtn' : '#recallInvoiceBtn').click(); }
    });
    $('#sales_form').on('submit', function (e) {
        if (viewingSaved) {
            e.preventDefault();
            alert('This invoice is already finalized and cannot be edited or re-saved. Use "Start Over" to create a new one.');
            return;
        }
        if (!$('#sales_order_id').val() || !$('#invoiceLines tbody tr').length) {
            e.preventDefault(); alert('Load a sales order with at least one item.'); return;
        }
        syncChequeHiddenFields();
        var paid = number($('#cash_payment').val()) + number($('#half_payment').val()) + number($('#cheque_payment').val());
        if (money(paid - number($('#paid_amount').val())) > 0) {
            e.preventDefault(); alert('Payments cannot exceed the net amount.'); return;
        }
        if (number($('#cheque_payment').val()) > 0 && !chequeList.length) {
            e.preventDefault(); openChequeModal(); return;
        }
        $('#finalizeInvoiceBtn').prop('disabled', true).text('Saving Invoice...');
    });
    if (config.initialOrder) { $('#load_order_no').val(config.initialOrder); $('#loadOrderBtn').click(); }
})(jQuery);
