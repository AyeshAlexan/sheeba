<?php

// Every report route listed in config/module_routes.php['reports'], grouped
// and labelled for the per-user "Report Selection" popup on the Users page.
// Keep this in sync with resources/views/layouts/sidebar.blade.php — each
// key here is the route name used for that report's sidebar link.
return [
    'Stock Reports' => [
        'Item_detail_report' => 'Item Details',
        'stock_report' => 'Stock In Hand',
        'Stock_valuation_report' => 'Stock Valuation',
        'bin_card' => 'Bin Card',
        'ZerostockReport' => 'Stock Zero',
        'reports.stockTranferReport' => 'Stock Transfer Report',
        'reports.stockDetailsSummeryReport' => 'Stock Details Report',
    ],
    'Sales Reports' => [
        'Item_wish_sales_report' => 'Item Wise Sales',
        'sales_report' => 'Sales Summary',
        'Invoice_detail_report' => 'Sales Details',
        'customer_wish_report' => 'Customer Wish Item Sales',
        'reports.salesmanInvoiceReport' => 'Salesman Invoice Report',
        'reports.SalesmanInvoiceSumReport' => 'Salesman Total Invoice Report',
        'Cash_in_out_report' => 'Cash In Out Report',
        'sales.return.report' => 'Sales Return Report',
    ],
    'Customer Reports' => [
        'get_customer_details_report' => 'Customer Details',
        'customer_payment_report' => 'Customer Payment Report',
        'customer_cheque_payment_report' => 'Customer Cheque Payment Report',
        'Cust_Transferreport' => 'Customer Account',
        'customersalesWishReport' => 'Customer Wish Sale',
        'customer_balance_report' => 'Customer Balance',
        'AdvancePaymentReport' => 'Advance Payment Report',
    ],
    'Purchase Reports' => [
        'Purchase_order_report' => 'Purchase Order Summary',
        'Purchase_order_details_report' => 'Purchase Order Details',
        'purchasing.report' => 'Purchasing Report',
    ],
    'Supplier Reports' => [
        'get_supplier_details_report' => 'Supplier Details',
        'supplyer_payment_report' => 'Supplier Payment Report',
        'supplyer_cheque_payment_report' => 'Supplier Cheque Payment Report',
        'supplier_account_report' => 'Supplier Account',
        'supplyer_balance_report' => 'Supplier Balance',
    ],
    'Hire Purchase Reports' => [
        'OpeningHirepurchaseReport' => 'Opening Hire Purchase Details',
        'OpeningHirepurchaseSumReport' => 'Opening Hire Purchase Sum',
    ],
    'Cash & Banking Reports' => [
        'cashInHandReport' => 'Cash In Hand',
        'cashandChequeTransaction' => 'Cash & Cheque Transaction',
        'daily.transactions' => 'Daily Transactions',
        'cash.book' => 'Cash Book',
    ],
];
