(function () {
    function appendText(parent, tagName, className, text) {
        var element = document.createElement(tagName);
        element.className = className;
        element.textContent = text;
        parent.appendChild(element);
        return element;
    }

    window.stockReportPrintCustomize = function (printWindow, overrides) {
        var options = Object.assign({}, window.stockReportPrintOptions || {}, overrides || {});
        var body = printWindow.document.body;
        var tables = Array.from(body.querySelectorAll('table'));

        body.innerHTML = '';
        if (options.title === 'Customer Wish Item Sales') {
            body.classList.add('rp-customer-wish');
        }
        if ([
            'Sales Summary',
            'Salesman Invoice Report',
            'Salesman Total Invoice Report',
            'Stock Details Report',
            'Customer Details Report'
        ].indexOf(options.title) !== -1) {
            body.classList.add('rp-wide-report');
        }
        if (options.title === 'Salesman Invoice Report') {
            body.classList.add('rp-salesman-invoice');
        }
        var sheet = printWindow.document.createElement('main');
        sheet.className = 'rp-sheet';
        body.appendChild(sheet);

        var letterhead = printWindow.document.createElement('header');
        letterhead.className = 'rp-letterhead';
        sheet.appendChild(letterhead);

        var left = printWindow.document.createElement('div');
        left.className = 'rp-left';
        letterhead.appendChild(left);

        var logo = printWindow.document.createElement('img');
        logo.src = options.logo;
        logo.alt = 'Logo';
        left.appendChild(logo);

        var company = printWindow.document.createElement('div');
        left.appendChild(company);
        appendText(company, 'div', 'rp-company-name', options.company || '');
        appendText(company, 'div', 'rp-branch', options.branch || '');
        appendText(company, 'div', 'rp-date', new Intl.DateTimeFormat('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        }).format(new Date()));

        var middle = printWindow.document.createElement('div');
        middle.className = 'rp-middle';
        letterhead.appendChild(middle);
        appendText(middle, 'div', 'rp-title', options.title || 'Report');

        var spacer = printWindow.document.createElement('div');
        spacer.className = 'rp-spacer';
        letterhead.appendChild(spacer);

        var period = printWindow.document.createElement('div');
        period.className = 'rp-period';
        if (options.fromDate && options.toDate) {
            period.appendChild(printWindow.document.createTextNode('Period: '));
            appendText(period, 'strong', '', options.fromDate);
            period.appendChild(printWindow.document.createTextNode('  →  '));
            appendText(period, 'strong', '', options.toDate);
        } else {
            period.appendChild(printWindow.document.createTextNode('Period: '));
            appendText(period, 'strong', '', 'All Data');
        }
        sheet.appendChild(period);

        tables.forEach(function (table) {
            sheet.appendChild(table);
        });

        var style = printWindow.document.createElement('style');
        style.textContent = `
            * { box-sizing: border-box; }
            body { margin: 0; background: #fff; color: #172033; font: 12px "Segoe UI", Tahoma, Arial, sans-serif; }
            .rp-sheet { max-width: 1000px; margin: 24px auto; padding: 28px 32px; }
            .rp-letterhead { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; border-bottom: 2px solid #1677FF; padding-bottom: 14px; margin-bottom: 10px; }
            .rp-left { display: flex; align-items: center; gap: 12px; }
            .rp-left img { height: 52px; width: auto; object-fit: contain; }
            .rp-company-name { font-size: 15px; font-weight: 700; color: #14213D; }
            .rp-branch { margin-top: 2px; color: #667085; font-size: 11.5px; }
            .rp-date { margin-top: 2px; color: #98A2B3; font-size: 11px; }
            .rp-middle { flex: 1; text-align: center; }
            .rp-title { color: #14213D; font-size: 20px; font-weight: 700; }
            .rp-spacer { width: 52px; }
            .rp-period { margin: 0 0 18px; text-align: center; color: #667085; font-size: 12.5px; }
            .rp-period strong { color: #14213D; }
            table { width: 100% !important; border-collapse: collapse !important; margin: 0 0 18px !important; }
            thead th { padding: 9px 10px !important; border: 1px solid #14213D !important; background: #14213D !important; color: #fff !important; font-size: 11px !important; text-align: left !important; text-transform: uppercase; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            tbody td, tfoot td { padding: 7px 10px !important; border: 1px solid #E5EAF2 !important; vertical-align: middle; }
            tbody tr:nth-child(even) td { background: #F6F8FC !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            tfoot td { background: #EAF3FF !important; color: #14213D; font-weight: 700; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .text-end, .rp-numeric { text-align: right !important; }
            .rp-customer-wish table { table-layout: fixed !important; }
            .rp-customer-wish th:nth-child(1), .rp-customer-wish td:nth-child(1) { width: 10%; }
            .rp-customer-wish th:nth-child(2), .rp-customer-wish td:nth-child(2) { width: 14%; }
            .rp-customer-wish th:nth-child(3), .rp-customer-wish td:nth-child(3) { width: 18%; }
            .rp-customer-wish th:nth-child(4), .rp-customer-wish td:nth-child(4) { width: 48%; }
            .rp-customer-wish th:nth-child(5), .rp-customer-wish td:nth-child(5) { width: 10%; }
            .rp-customer-wish td:nth-child(4) { white-space: normal !important; overflow-wrap: anywhere; word-break: normal; }
            .rp-customer-wish td:nth-child(4) * { white-space: normal !important; overflow-wrap: anywhere; }
            .rp-wide-report table { table-layout: fixed !important; font-size: 8px !important; }
            .rp-wide-report th, .rp-wide-report td { padding: 4px 5px !important; white-space: normal !important; overflow-wrap: anywhere; word-break: normal; }
            .rp-wide-report tbody .text-end, .rp-wide-report tbody .rp-numeric { white-space: nowrap !important; }
            .rp-salesman-invoice table { table-layout: fixed !important; }
            .rp-salesman-invoice th:first-child, .rp-salesman-invoice td:first-child { width: 4% !important; white-space: nowrap !important; overflow-wrap: normal !important; word-break: keep-all !important; }
            .rp-salesman-invoice th:nth-child(2), .rp-salesman-invoice td:nth-child(2) { width: 10% !important; }
            .rp-salesman-invoice th:nth-child(3), .rp-salesman-invoice td:nth-child(3) { width: 18% !important; }
            .rp-salesman-invoice th:nth-child(n+4), .rp-salesman-invoice td:nth-child(n+4) { width: 8.5% !important; }
            .rp-no-print { display: none !important; }
            @page { size: A4 ${body.classList.contains('rp-wide-report') ? 'landscape' : 'portrait'}; margin: 10mm; }
            @media print {
                .rp-sheet { margin: 0; padding: 0; }
                table { page-break-inside: auto; }
                thead { display: table-header-group; }
                tfoot { display: table-row-group; }
                tr { page-break-inside: avoid; page-break-after: auto; }
                .rp-letterhead { page-break-after: avoid; }
            }
        `;
        printWindow.document.head.appendChild(style);
    };

    window.stockReportPrintTables = function (selectors) {
        var printWindow = window.open('', '_blank');
        if (!printWindow) {
            window.alert('Please allow pop-ups to print this report.');
            return;
        }

        printWindow.document.open();
        printWindow.document.write('<!DOCTYPE html><html><head><title>Report</title></head><body></body></html>');
        printWindow.document.close();

        selectors.forEach(function (selector) {
            var table = document.querySelector(selector);
            if (table) {
                var clone = table.cloneNode(true);
                if (window.jQuery && window.jQuery.fn.dataTable &&
                    window.jQuery.fn.dataTable.isDataTable(table)) {
                    var dataTable = window.jQuery(table).DataTable();
                    var body = clone.tBodies[0];
                    if (body) {
                        body.innerHTML = '';
                        dataTable.rows({ search: 'applied', order: 'applied' }).nodes().each(function (row) {
                            body.appendChild(row.cloneNode(true));
                        });
                    }
                }
                clone.querySelectorAll('tr').forEach(function (row) {
                    row.style.display = '';
                });
                var hasNoPrintColumns = clone.querySelector('.rp-no-print') !== null;
                clone.querySelectorAll('.rp-no-print').forEach(function (cell) {
                    cell.remove();
                });
                if (hasNoPrintColumns) {
                    clone.querySelectorAll('tfoot [colspan="7"]').forEach(function (cell) {
                        cell.colSpan = 6;
                    });
                }
                printWindow.document.body.appendChild(clone);
            }
        });

        window.stockReportPrintCustomize(printWindow);
        var printAfterLogoLoads = function () {
            printWindow.focus();
            printWindow.print();
        };
        var printLogo = printWindow.document.querySelector('.rp-left img');
        if (printLogo && !printLogo.complete) {
            printLogo.onload = printAfterLogoLoads;
            printLogo.onerror = printAfterLogoLoads;
        } else {
            window.setTimeout(printAfterLogoLoads, 300);
        }
    };
})();
