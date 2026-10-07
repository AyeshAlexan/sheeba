<?php

// Maps "module_key.action_key" (action keys from config/module_actions.php)
// to route NAMES, the same way config/module_routes.php maps whole pages to
// modules. Used by CheckModuleAccess middleware to reject a save/edit/
// delete/print request server-side, not just hide the button client-side.
//
// Same scope note as module_routes.php: this covers the save/update/delete/
// print endpoints for pages that already have their buttons gated in the
// UI (see the per-page @if(Permissions::canDo(...)) wrappers). It grows
// alongside that UI work, section by section — unmapped routes are allowed
// through unchanged, consistent with how this app enforces access nowhere
// else today either.
return [
    // ── Sales ──────────────────────────────────────────────────────────
    'sales.save' => [
        'add_invoice', 'add_invoice_ajax', 'update_sales_invoice_data',
        'update_vat_invoice_data',
        'add_salesInvoice_withoutVat',
        'add_salesReturn',
        'add_sales_quatation',
        'addcustomerPayment', 'UpdatecustomerPayment',
        'make_customer_payment',
        'addCustomerOpeningBalance', 'UpdateCustomerOpeningBalance',
        'invoices.apply-discount', 'invoices.apply-transport',
    ],
    'sales.add' => [
        'add_invoice', 'add_invoice_ajax',
        'add_salesInvoice_withoutVat',
        'add_salesReturn',
        'add_sales_quatation',
        'addcustomerPayment',
        'make_customer_payment',
        'addCustomerOpeningBalance',
    ],
    'sales.edit' => [
        'update_sales_invoice_data',
        'update_vat_invoice_data',
        'UpdatecustomerPayment',
        'UpdateCustomerOpeningBalance',
        'invoices.apply-discount', 'invoices.apply-transport',
    ],
    'sales.delete' => [
        'delete_invoice_ajax',
        'delete_vat_invoice',
        'DeletecustomerPayment',
        'DeleteCustomerOpeningBalance',
    ],
    'sales.print' => [
        'print_sales_invoice_ajax',
        'sales.return.print',
    ],
];
