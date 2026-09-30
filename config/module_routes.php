<?php

// Maps sidebar navigation entries to the module keys defined in
// config/modules.php, by ROUTE NAME. Used by CheckModuleAccess middleware
// to block direct URL access to a page a role can't see in the sidebar.
//
// Scope: this covers the primary navigation pages listed in the sidebar —
// the pages a role would actually click into. It does NOT attempt to map
// every ajax/store/edit endpoint in the app (hundreds of routes, many
// unnamed) — those remain reachable if a user already has the page open,
// consistent with how this app enforces access nowhere else today either.
// Unmapped routes are allowed through unchanged.
return [
    'user_management' => ['users', 'add_user', 'add_user_ajax', 'update_user_ajax', 'delete_user_ajax', 'search_user_ajax', 'Userrole'],
    'role_permissions' => ['role_permissions', 'role_permissions.save'],
    'system' => ['Company', 'branchdetails'],
    'master' => [
        'Store', 'Category', 'MBrand', 'M_Make', 'MColor', 'Item', 'master_customers',
        'SchemaType', 'MGuarantor', 'BankDeltails', 'Bank_Branch', 'ChequeBanks',
        'Route', 'Area', 'SalesMan',
    ],
    'stock' => ['stock_open', 'stockAdjuestment', 'stockAdjuestmentNew', 'stock_transfer', 'stock_damage', 'stock_movements'],
    'purchases' => ['purchases_order', 'purchases', 'purchases_supplyer_payment', 'purchases_return'],
    'sales' => [
        'salesInvoice_withoutVat', 'sales_advance_payment', 'sales_return', 'sales_quatation',
        'sales_customer_payment', 'customer_opening_balance', 'invoiceDiscountEnter',
    ],
    'vouchers' => ['PaymentVoucher', 'gentralreceipt', 'PettyCash'],
    'expense' => ['AddExpense', 'CashOut'],
    'banking' => ['cheque.deposit', 'issued.cheques', 'cheque.return'],
    'accounting' => ['account_category', 'account_type', 'chartofaccount'],
    'reports' => [
        'Item_detail_report', 'stock_report', 'Stock_valuation_report', 'bin_card', 'ZerostockReport',
        'reports.stockTranferReport', 'reports.stockDetailsSummeryReport', 'Item_wish_sales_report',
        'sales_report', 'Invoice_detail_report', 'customer_wish_report', 'reports.salesmanInvoiceReport',
        'reports.SalesmanInvoiceSumReport', 'Cash_in_out_report', 'get_customer_details_report',
        'customer_payment_report', 'customer_cheque_payment_report', 'Cust_Transferreport',
        'customersalesWishReport', 'customer_balance_report', 'AdvancePaymentReport',
        'Purchase_order_report', 'Purchase_order_details_report', 'get_supplier_details_report',
        'supplyer_payment_report', 'supplyer_cheque_payment_report', 'supplier_account_report',
        'supplyer_balance_report', 'purchasing.report', 'OpeningHirepurchaseReport',
        'OpeningHirepurchaseSumReport', 'sales.return.report', 'cashInHandReport',
        'cashandChequeTransaction', 'daily.transactions', 'cash.book',
    ],
];
