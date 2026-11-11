/* Shared Flatpickr setup for Invoice/Order "Date" fields (Sales Invoice, Sales Invoice
   without VAT, Sales Order). Replaces the native <input type="date"> calendar with a
   custom-styled popup. The underlying input keeps name="invoice_date" and a plain
   "Y-m-d" value, so form submission and backend validation are unaffected. No limit is
   placed on how far into the past (or future) a date can be picked — backdating last
   week's invoices/orders is intentional and fully supported. */
(function () {
    function addFooterButtons(instance) {
        var footer = document.createElement('div');
        footer.className = 'invoice-date-footer';

        var todayBtn = document.createElement('button');
        todayBtn.type = 'button';
        todayBtn.className = 'fp-today-btn';
        todayBtn.textContent = 'Today';
        todayBtn.addEventListener('click', function () {
            instance.setDate(new Date(), true);
            instance.close();
        });

        var clearBtn = document.createElement('button');
        clearBtn.type = 'button';
        clearBtn.className = 'fp-clear-btn';
        clearBtn.textContent = 'Clear';
        clearBtn.addEventListener('click', function () {
            instance.clear();
            instance.close();
        });

        footer.appendChild(todayBtn);
        footer.appendChild(clearBtn);
        instance.calendarContainer.appendChild(footer);
    }

    function initOne(el) {
        if (!el || el._flatpickr || typeof flatpickr === 'undefined') return;
        var existing = el.value;
        el.setAttribute('autocomplete', 'off');
        flatpickr(el, {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'j M Y',
            altInputClass: 'form-control invoice-date-input',
            allowInput: true,
            disableMobile: true,
            defaultDate: existing || new Date(),
            onReady: function (selectedDates, dateStr, instance) {
                addFooterButtons(instance);
            }
        });
    }

    function init(selector) {
        document.querySelectorAll(selector || '#invoice_date').forEach(initOne);
    }

    // Programmatically set a date-picker field's value (keeps the visible
    // alt-input in sync, unlike a plain `.val()`/`.value =` assignment).
    window.setInvoiceDateValue = function (input, dateStr) {
        if (!input) return;
        if (input._flatpickr) {
            input._flatpickr.setDate(dateStr, true);
        } else {
            input.value = dateStr;
        }
    };

    // Lock/unlock a date-picker field (disables the visible alt-input too,
    // since disabling only the hidden underlying input leaves it clickable).
    window.lockInvoiceDatePicker = function (input) {
        if (!input) return;
        input.disabled = true;
        if (input._flatpickr) {
            input._flatpickr.set('clickOpens', false);
            if (input._flatpickr.altInput) input._flatpickr.altInput.disabled = true;
        }
    };
    window.unlockInvoiceDatePicker = function (input) {
        if (!input) return;
        input.disabled = false;
        if (input._flatpickr) {
            input._flatpickr.set('clickOpens', true);
            if (input._flatpickr.altInput) input._flatpickr.altInput.disabled = false;
        }
    };

    window.initInvoiceDatePicker = init;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { init(); });
    } else {
        init();
    }
})();
