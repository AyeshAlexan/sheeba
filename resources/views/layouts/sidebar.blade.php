<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/toastr.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/icons/feather/feather.css') }}">
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/theme-redesign.css') }}?v={{ filemtime(public_path('assets/css/theme-redesign.css')) }}">
    <script src="{{ asset('assets/js/dt-custom-pager.js') }}?v={{ filemtime(public_path('assets/js/dt-custom-pager.js')) }}"></script>


    <style>

    .item-description-wrapper {
        display: inline-block;
        max-width: 400px;
        white-space: normal;
    }

    .scroll {
        width: 100%;
        height: 100%;
        overflow-y: scroll;
        scrollbar-width: thin;
    }

    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-thumb {
        border-radius: 30px;
        background: -webkit-gradient(linear,left top,left bottom,from(#999999),to(#999999));
        box-shadow: inset 2px 2px 2px rgba(255,255,255,.25), inset -2px -2px 2px rgba(238, 237, 237, 0.25);
    }

    ::-webkit-scrollbar-track {
        background-color: #eeeeee;
        border-radius:10px;
        background: linear-gradient(to right,#eeeeee,#eeeeee 1px,#eeeeee 1px,#eeeeee);
    }

    </style>

</head>


<div class="sidebar shadow" id="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('home') }}" class="sidebar-brand-link">
            <span class="sidebar-brand-mark" aria-hidden="true">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" y1="22" x2="12" y2="12"/></svg>
            </span>
            <span class="sidebar-brand-text">
                <span class="sidebar-brand-title">Sheba</span>
                <span class="sidebar-brand-subtitle">Your business partner</span>
            </span>
        </a>
        <span class="sidebar-brand-pill" title="Branch">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="6" y1="3" x2="6" y2="15"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></svg>
            {{ optional(Auth::user())->Branch ?? optional(Auth::user())->BC ?? '-' }}
        </span>
    </div>
    <div class="scroll">
    <!--<div class="sidebar-inner slimscroll">-->
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                {{-- Main --}}
                <li class="menu-title"><span>Main</span></li>
                        <li class="{{ Request::is('home') ? 'active' : '' }}">
                        <a href="{{ route('home') }}">
                            <i class="fe fe-home nav-ico" aria-hidden="true"></i>
                            <span>Home</span>
                        </a>
                    </li>


                 <!-- e-commerce-app menu start -->


                {{-- User Management --}}
@if(\App\Support\Permissions::can('user_management'))
                <li class="submenu {{ Request::is('users') || Request::is('Userrole')  || Request::is('add_user') || Request::is('role-permissions')  ? 'active' : '' }} ">
                    <a href="#" class=" {{ Request::is('users') || Request::is('Userrole')  || Request::is('add_user') || Request::is('role-permissions') ? 'subdrop' : '' }} ">
                        <i class="fe fe-users nav-ico" aria-hidden="true"></i> <span> User Management</span>
                        <span class="menu-arrow"></span></a>
                    <ul style=" {{ Request::is('users') || Request::is('Userrole') || Request::is('add_user') || Request::is('role-permissions') ? 'display:block;' : '' }} ">
                        <li class="{{ Request::is('users') || Request::is('add_user') ? 'active ' : '' }}">
                            <a href="{{route("users")}}">
                                <i class="fe fe-user" aria-hidden="true"></i>
                                Users
                            </a>
                        </li>
                        <li class="{{ Request::is('Userrole') ? 'active ' : '' }}">
                            <a href="{{route("Userrole")}}">
                                <i class="fe fe-user-check" aria-hidden="true"></i>
                                Roles</a>
                        </li>
@if(\App\Support\Permissions::can('role_permissions'))
                        <li class="{{ Request::is('role-permissions') ? 'active ' : '' }}">
                            <a href="{{route("role_permissions")}}">
                                <i class="fe fe-shield" aria-hidden="true"></i>
                                Role Permissions</a>
                        </li>
@endif


                    </ul>
                </li>
@endif

                {{-- system --}}

@if(\App\Support\Permissions::can('system'))
                <li class="{{ Request::is('Company*') ? 'active' : '' }}">
                    <a href="#"><i class="fe fe-server nav-ico" aria-hidden="true"></i> <span>System</span>
                        <span class="menu-arrow"></span></a>

                    <ul style=" {{ Request::is('Company*') ? 'display:block;' : '' }}">
                        <li class="{{ Request::is('Company') ? 'active' : '' }}">
                            <a href="{{route("Company")}}">
                                <i class="fe fe-briefcase" aria-hidden="true"></i>
                                Company</a>
                        </li>
                        <li class="{{ Request::is('Company_branchdetails') ? 'active' : '' }}">
                            <a href="{{route("branchdetails")}}">
                                <i class="fe fe-git-branch" aria-hidden="true"></i>
                                Branch</a>
                        </li>
                    </ul>
                </li>
@endif

                {{-- Master --}}
@if(\App\Support\Permissions::can('master'))
                <li
                    class="dropdown {{ Request::is('master*')  || Request::is('SalesMan') || Request::is('Area')  || Request::is('Route') || Request::is('Bank_Branch') || Request::is('BankDeltails') || Request::is('MColor')  || Request::is('M_Make')  || Request::is('MBrand')  || Request::is('Category') || Request::is('Store') || Request::is('Department') ||Request::is('Suppliers') || Request::is('Item') || Request::is('Category') ? 'active' : '' }} ">
                    <a href="#"><i class="fe fe-layers nav-ico" aria-hidden="true"></i> <span> Master</span> <span class="menu-arrow"></span></a>
                    <ul
                        style=" {{ Request::is('master*') || Request::is('SalesMan')  || Request::is('Area')  || Request::is('Route') || Request::is('Bank_Branch') || Request::is('BankDeltails') || Request::is('MColor')  || Request::is('Category') || Request::is('Store') || Request::is('Department') || Request::is('M_Make')  || Request::is('MBrand') ||Request::is('Suppliers') || Request::is('Item') || Request::is('Category')  ? 'display:block;' : '' }}">
                        <li class="{{ Request::is('Department') ? 'active' : '' }}">
                            <a href="{{'Department'}}"><i class="fe fe-briefcase" aria-hidden="true"></i>Department</a>
                        </li>

                        <li class="{{ Request::is('Store') ? 'active' : '' }}">
                            <a href="{{route("Store")}}"><i class="fe fe-inbox" aria-hidden="true"></i>Store</a>
                        </li>

                        <li class="{{ Request::is('Category') ? 'active' : '' }}">
                            <a href="{{route("Category")}}"><i class="fe fe-tag" aria-hidden="true"></i>Category</a>
                        </li>

                        <li class="{{ Request::is('MBrand') ? 'active' : '' }}">
                            <a href="{{route("MBrand")}}"><i class="fe fe-award" aria-hidden="true"></i>Brands</a>
                        </li>
                        <li class="{{ Request::is('M_Make') ? 'active' : '' }}">
                            <a href="{{route("M_Make")}}"><i class="fe fe-settings" aria-hidden="true"></i>Make</a>
                        </li>
                        <li class="{{ Request::is('MColor') ? 'active' : '' }}">
                            <a href="{{route("MColor")}}"><i class="fe fe-droplet" aria-hidden="true"></i>Color</a>
                        </li>

                        <li class="{{ Request::is('Item') ? 'active' : '' }}">
                            <a href="{{route('Item')}}"><i class="fe fe-box" aria-hidden="true"></i>Item</a>
                        </li>
                        <li class="dropdown {{ Request::is('master_customers') ? 'active' : '' }}">
                            <a href="{{route("master_customers")}}">
                                <i class="fe fe-users" aria-hidden="true"></i>Customers</a>
                        </li>

						<li class="{{ Request::is('Suppliers') ? 'active' : '' }}">
                            <a href="{{('Suppliers')}}"><i class="fe fe-truck" aria-hidden="true"></i>Suppliers</a>
                        </li>
                        <li class="{{ Request::is('BankDeltails') ? 'active' : '' }}">
                            <a href="{{route("BankDeltails")}}"><i class="fe fe-credit-card" aria-hidden="true"></i>Bank</a>
                        </li>
                        <li class="{{ Request::is('Bank_Branch') ? 'active' : '' }}">
                            <a href="{{route('Bank_Branch')}}"><i class="fe fe-git-branch" aria-hidden="true"></i>Bank Branch</a>
                        </li>
                        <li class="{{ Request::is('ChequeBanks') ? 'active' : '' }}">
                            <a href="{{route('ChequeBanks')}}"><i class="fe fe-credit-card" aria-hidden="true"></i>Account</a>
                        </li>
                           {{-- Route --}}

                        <li class="{{ Request::is('Route') ? 'active' : '' }}">
                            <a href="{{route('Route')}}"><i class="fe fe-map" aria-hidden="true"></i>Route</a>
                        </li>

                        {{-- Area --}}
                        <li class="{{ Request::is('Area') ? 'active' : '' }}">
                            <a href="{{route('Area')}}"><i class="fe fe-map-pin" aria-hidden="true"></i>Area</a>
                        </li>
                        {{-- SalesMan --}}
                        <li class="{{ Request::is('SalesMan') ? 'active' : '' }}">
                            <a href="{{route('SalesMan')}}"><i class="fe fe-briefcase" aria-hidden="true"></i>SalesMan</a>
                        </li>

                    </ul>
                </li>
@endif


                {{-- Stock --}}

@if(\App\Support\Permissions::can('stock'))
                <li class="{{ Request::is('stock*') ? 'active' : '' }}">
                    <a href="#"><i class="fe fe-package nav-ico" aria-hidden="true"></i> <span>Stock</span>
                        <span class="menu-arrow"></span></a>

                    <ul style=" {{ Request::is('stock*') ? 'display:block;' : '' }}">
                        <li class="{{ Request::is('stock_open') ? 'active' : '' }}">
                            <a href="{{route("stock_open")}}">
                                <i class="fe fe-inbox" aria-hidden="true"></i>
                                Opening Stock</a>
                        </li>
                        <li class="{{ Request::is('stockAdjuestmentNew') ? 'active' : '' }}">
                          <a href="{{route("stockAdjuestmentNew")}}">
                                <i class="fe fe-sliders" aria-hidden="true"></i>
                                Stock Adjustment</a>
                        </li>

                        <li class="{{ Request::is('stock_damage') ? 'active' : '' }}">
                            <a href="{{route("stock_damage")}}">
                                <i class="fe fe-alert-triangle" aria-hidden="true"></i>
                                Damage Stock</a>
                        </li>

                        <li class="{{ Request::is('stock_movements') ? 'active' : '' }}">
                            <a href="{{route("stock_movements")}}">
                                <i class="fe fe-repeat" aria-hidden="true"></i>
                                Stock Movements</a>
                        </li>

                        <li class="{{ Request::is('stock_transfer') ? 'active' : '' }}">
                            <a href="{{route("stock_transfer")}}">
                                <i class="fe fe-truck" aria-hidden="true"></i>
                                Stock Transfer</a>
                        </li>
                    </ul>
                </li>
@endif




                {{-- Delivery Orders is retired — no sidebar entry. Historical
                     records stay reachable at route('delivery_orders_list')
                     directly for anyone who needs the archive. --}}

@if(\App\Support\Permissions::can('purchases'))
                <li class=" dropdown {{ Request::is('purchases*') ? 'active' : '' }} ">
                   <a href="#"><i class="fe fe-shopping-cart nav-ico" aria-hidden="true"></i> <span>Purchases</span>
                       <span class="menu-arrow"></span></a>

                   <ul style="{{ Request::is('purchases*') ? 'display:block;' : '' }} ">
                       <li class="{{ Request::is('purchases_order') ? 'active' : '' }}">
                           <a href="{{route("purchases_order")}}">
                               <i class="fe fe-file-text" aria-hidden="true"></i>Purshases Order</a>
                       </li>
                       <li class="{{ Request::is('purchases') ? 'active' : '' }}">
                           <a href="{{route("purchases")}}">
                               <i class="fe fe-truck" aria-hidden="true"></i>Purchases</a>
                       </li>

                       <li class="{{ Request::is('purchases_supplyer_payment') ? 'active' : '' }}">
                           <a href="{{route("purchases_supplyer_payment")}}">
                               <i class="fe fe-credit-card" aria-hidden="true"></i>
                               Supplyer Payment</a>
                       </li>

                       <li class="{{ Request::is('purchases_return') ? 'active' : '' }}">
                           <a href="{{route("purchases_return")}}">
                               <i class="fe fe-corner-up-left" aria-hidden="true"></i>
                               Purchases Return</a>
                       </li>
                   </ul>
               </li>
@endif

@if(\App\Support\Permissions::can('sales'))
                <li class=" dropdown {{ Request::is('sales*') ? 'active' : '' }} ">
                    <a href="#"><i class="fe fe-bar-chart-2 nav-ico" aria-hidden="true"></i> <span>Sales</span>
                        <span class="menu-arrow"></span></a>

                    <ul style="{{ Request::is('sales*') ? 'display:block;' : '' }} ">
                        {{-- <li class="{{ Request::is('sales_invoice') ? 'active' : '' }}">
                            <a href="{{route("sales_invoice")}}">
                                <i class="fe fe-chevron-right" aria-hidden="true"></i> Invoice Sales</a>
                        </li> --}}

                        <li class="{{ Request::is('sales_orders*') ? 'active' : '' }}">
                            <a href="{{ route('sales_orders') }}"><i class="fe fe-clipboard" aria-hidden="true"></i> Sales Order</a>
                        </li>
                        <li class="{{ Request::is('salesInvoice_withoutVat') ? 'active' : '' }}">
                            <a href="{{route("salesInvoice_withoutVat")}}">
                                <i class="fe fe-file-text" aria-hidden="true"></i> Sales Invoice</a>
                        </li>

                        <!--<li class="{{ Request::is('sales_create_invoice') ? 'active' : '' }}">-->
                        <!--    <a href="{{route("sales_create_invoice")}}">-->
                        <!--        <i class="fe fe-chevron-right" aria-hidden="true"></i> Vat Sales Invoice</a>-->
                        <!--</li>-->

                        <li class="{{ Request::is('sales_advance_payment') ? 'active' : '' }}">
                            <a href="{{route("sales_advance_payment")}}">
                                <i class="fe fe-dollar-sign" aria-hidden="true"></i>
                                Advance Payment</a>
                        </li>

                        <li class="{{ Request::is('sales_return') ? 'active' : '' }}">
                            <a href="{{route("sales_return")}}">
                                <i class="fe fe-corner-up-left" aria-hidden="true"></i>
                                Sales Return</a>
                        </li>

                        <li class="{{ Request::is('sales_quatation') ? 'active' : '' }}">
                            <a href="{{route("sales_quatation")}}">
                                <i class="fe fe-edit-3" aria-hidden="true"></i>
                                Quatation</a>
                        </li>
                        <li class="{{ Request::is('sales_customer_payment') ? 'active' : '' }}">
                            <a href="{{route("sales_customer_payment")}}">
                                <i class="fe fe-dollar-sign" aria-hidden="true"></i>
                                Customer Payment</a>
                        </li>

                           <li class="{{ Request::is('sales_customer_payment') ? 'active' : '' }}" target ="_ blank">
                          <a href="{{route("customer_opening_balance")}}">
                              <i class="fe fe-sliders" aria-hidden="true"></i>
                             Customer Balance</a>
                      </li>
                      
                      
                            <li class="{{ Request::is('invoiceDiscountEnter') ? 'active' : '' }}">
                    <a href="{{ route('invoiceDiscountEnter') }}" target="_blank">
                        <i class="fe fe-tag" aria-hidden="true"></i>
                        Customer Discount
                    </a>
                          </li>

                    </ul>
                </li>
@endif



@if(\App\Support\Permissions::can('vouchers'))
                @php
                    $vouchersOpen = Request::is('PaymentVoucher*') || Request::is('gentralreceipt*') || Request::is('PettyCash*');
                @endphp
                <li class="{{ $vouchersOpen ? 'active' : '' }}">
                <a href="#" class="{{ $vouchersOpen ? 'subdrop' : '' }}">
                    <i class="fe fe-bookmark nav-ico" aria-hidden="true"></i> <span> Vouchers</span> <span class="menu-arrow"></span></a>
                <ul
                style=" {{ $vouchersOpen ? 'display:block;' : '' }}"
                >
                    <li
                    class="{{ Request::is('PaymentVoucher*') ? 'active' : '' }}"
                    >
                        <a href="{{route("PaymentVoucher")}}">
                            <i class="fe fe-credit-card" aria-hidden="true"></i>
                            Payment Vouchers</a>
                    </li>

                    <li class="{{ Request::is('gentralreceipt*') ? 'active' : '' }}">
            <a href="{{ route('gentralreceipt') }}" >
                <i class="fe fe-file-text" aria-hidden="true"></i> Gentral Receipt
            </a>
        </li>

                    <li class="{{ Request::is('PettyCash*') ? 'active' : '' }}">
                        <a href="{{route("PettyCash")}}">
                            <i class="fe fe-credit-card" aria-hidden="true"></i>
                            Petty Cash</a>
                    </li>
                </ul>
                </li>
@endif

@if(\App\Support\Permissions::can('expense'))
                <li class="{{ Request::is('AddExpense*') ? 'active' : '' }}">
                    <a href="#"><i class="fe fe-file-minus nav-ico" aria-hidden="true"></i> <span>Expense</span>
                        <span class="menu-arrow"></span></a>

                    <ul style=" {{ Request::is('AddExpense*') ? 'display:block;' : '' }}">
                        <li class="{{ Request::is('AddExpense') ? 'active' : '' }}">
                            <a href="{{route("AddExpense")}}">
                                <i class="fe fe-plus-circle" aria-hidden="true"></i>
                                Add Expense</a>
                        </li>
                        <li class="{{ Request::is('CashOut') ? 'active' : '' }}">
                            <a href="{{route("CashOut")}}">
                                <i class="fe fe-dollar-sign" aria-hidden="true"></i>
                                Cash Out</a>
                        </li>
                    </ul>
                </li>
@endif


@if(\App\Support\Permissions::can('banking'))
                <li class="dropdown {{ Request::is('cheque-deposit') || Request::is('issued-cheques') || Request::is('cheque-return') || Request::is('banking-recognition') || Request::is('opening-cheques') ? 'active' : '' }}">
                    <a href="#">
                        <i class="fe fe-credit-card nav-ico" aria-hidden="true"></i> <span> Banking </span> <span
                            class="menu-arrow"></span></a>
                            <ul style="{{ Request::is('cheque-deposit') || Request::is('issued-cheques') || Request::is('cheque-return') || Request::is('banking-recognition') || Request::is('opening-cheques') ? 'display:block;' : '' }}">
                                <li class="{{ Request::is('opening-cheques') ? 'active' : '' }}">
                                    <a href="{{ route('opening.cheques') }}">
                                    <i class="fe fe-book-open" aria-hidden="true"></i>
                                    Opening Cheques</a>
                                </li>
                                <li class="{{ Request::is('cheque-deposit') ? 'active' : '' }}">
                                    <a href="{{ route('cheque.deposit') }}">
                                    <i class="fe fe-download" aria-hidden="true"></i>
                                    Cheque Deposit</a>
                                </li>
                                <li class="{{ Request::is('issued-cheques') ? 'active' : '' }}">
                                    <a href="{{ route('issued.cheques') }}">
                                    <i class="fe fe-send" aria-hidden="true"></i>
                                    Issued Cheques </a>
                                </li>
                                <li class="{{ Request::is('cheque-return') ? 'active' : '' }}">
                                    <a href="{{ route('cheque.return') }}">
                                    <i class="fe fe-corner-up-left" aria-hidden="true"></i>
                                    Cheque Return</a>
                                </li>
                                <li class="{{ Request::is('banking-recognition') ? 'active' : '' }}">
                                    <a href="{{ route('banking.recognition') }}">
                                    <i class="fe fe-check-circle" aria-hidden="true"></i>
                                    Banking Recognition</a>
                                </li>
                            </ul>
                 </li>
@endif


                {{-- Accounting --}}
@if(\App\Support\Permissions::can('accounting'))
                @php
                    $accountingOpen = request()->routeIs('account_category', 'account_type', 'chartofaccount');
                @endphp
                <li class="{{ $accountingOpen ? 'active' : '' }}">
                    <a href="#" class="{{ $accountingOpen ? 'subdrop' : '' }}">
                        <i class="fe fe-grid nav-ico" aria-hidden="true"></i> <span> Accounting</span> <span
                            class="menu-arrow"></span></a>
                            <ul style="{{ $accountingOpen ? 'display:block;' : '' }}">
                                <li class="{{ request()->routeIs('account_category') ? 'active' : '' }}">
                                    <a href="{{route("account_category")}}">
                                    <i class="fe fe-chevron-right" aria-hidden="true"></i>
                                   Category</a>
                                </li>
                                <li class="{{ request()->routeIs('account_type') ? 'active' : '' }}">
                                    <a href="{{route("account_type")}}">
                                    <i class="fe fe-chevron-right" aria-hidden="true"></i>
                                   Type</a>
                                </li>
                                <li class="{{ request()->routeIs('chartofaccount') ? 'active' : '' }}">
                                    <a href="{{route("chartofaccount")}}">
                                    <i class="fe fe-chevron-right" aria-hidden="true"></i>
                                   Chart Of Accounts</a>
                                </li>

                            </ul>
                 </li>
@endif

@if(\App\Support\Permissions::can('reports'))
                @php
                    $stockReportOpen = request()->routeIs('Item_detail_report', 'stock_report', 'Stock_valuation_report', 'bin_card', 'ZerostockReport', 'reports.stockTranferReport', 'reports.stockDetailsSummeryReport');
                    $salesReportOpen = request()->routeIs('Item_wish_sales_report', 'sales_report', 'sales_order_report', 'Invoice_detail_report', 'customer_wish_report', 'reports.salesmanInvoiceReport', 'reports.SalesmanInvoiceSumReport', 'Cash_in_out_report');
                    $purchaseOrderReportOpen = request()->routeIs('Purchase_order_report', 'Purchase_order_details_report');
                    $customerReportOpen = request()->routeIs('get_customer_details_report', 'customer_payment_report', 'customer_cheque_payment_report', 'Cust_Transferreport', 'customersalesWishReport', 'customer_balance_report', 'AdvancePaymentReport') || $purchaseOrderReportOpen;
                    $supplierReportOpen = request()->routeIs('get_supplier_details_report', 'supplyer_payment_report', 'supplyer_cheque_payment_report', 'supplier_account_report', 'supplyer_balance_report');
                    $salesReturnReportOpen = request()->routeIs('sales.return.report');
                    $journalOpen = request()->routeIs('daily.transactions', 'cash.book');
                    $reportsOpen = $stockReportOpen || $salesReportOpen || $customerReportOpen || $supplierReportOpen
                        || request()->routeIs('purchasing.report') || $salesReturnReportOpen
                        || request()->routeIs('cashInHandReport') || request()->routeIs('cashandChequeTransaction') || $journalOpen;

                    // A report group's own menu-toggle <li> (e.g. "Sales Report") used
                    // to render unconditionally even when every report inside it was
                    // hidden by canViewReport() — leaving an empty, clickable heading
                    // in the sidebar with nothing under it. Each group is now only
                    // shown at all if at least one of its reports is visible.
                    $canSee = fn (...$keys) => collect($keys)->contains(fn ($k) => \App\Support\Permissions::canViewReport($k));
                    $canSeeStockGroup = $canSee('Item_detail_report', 'stock_report', 'Stock_valuation_report', 'bin_card', 'ZerostockReport', 'reports.stockTranferReport', 'reports.stockDetailsSummeryReport');
                    $canSeeSalesGroup = $canSee('Item_wish_sales_report', 'sales_report', 'sales_order_report', 'Invoice_detail_report', 'customer_wish_report', 'reports.salesmanInvoiceReport', 'reports.SalesmanInvoiceSumReport', 'Cash_in_out_report');
                    $canSeePurchaseOrderGroup = $canSee('Purchase_order_report', 'Purchase_order_details_report');
                    $canSeeCustomerGroup = $canSee('get_customer_details_report', 'customer_payment_report', 'customer_cheque_payment_report', 'Cust_Transferreport', 'customersalesWishReport', 'customer_balance_report', 'AdvancePaymentReport') || $canSeePurchaseOrderGroup;
                    $canSeeSupplierGroup = $canSee('get_supplier_details_report', 'supplyer_payment_report', 'supplyer_cheque_payment_report', 'supplier_account_report', 'supplyer_balance_report');
                    $canSeeSalesReturnGroup = \App\Support\Permissions::canViewReport('sales.return.report');
                    $canSeeJournalGroup = $canSee('daily.transactions', 'cash.book');
                    $canSeeAnyReport = $canSeeStockGroup || $canSeeSalesGroup || $canSeeCustomerGroup || $canSeeSupplierGroup
                        || \App\Support\Permissions::canViewReport('purchasing.report') || $canSeeSalesReturnGroup
                        || \App\Support\Permissions::canViewReport('cashInHandReport') || \App\Support\Permissions::canViewReport('cashandChequeTransaction') || $canSeeJournalGroup;
                @endphp
            @if($canSeeAnyReport)
            <li class="{{ $reportsOpen ? 'active' : '' }}">
                <a href="#" class="{{ $reportsOpen ? 'subdrop' : '' }}"><i class="fe fe-trending-up nav-ico" aria-hidden="true"></i> <span>Reports</span> <span
                    class="menu-arrow"></span></a>
                <ul class="menu-sub" style="{{ $reportsOpen ? 'display:block;' : '' }}">
                  @if($canSeeStockGroup)
                  <li class="menu-item">
                    <a href="javascript:void(0);"  class="menu-link menu-toggle {{ $stockReportOpen ? 'subdrop' : '' }}">
                      <div class="text-truncate" data-i18n="Products">Stock Report<span
                        class="menu-arrow"></span> </div>
                    </a>
                    <ul class="menu-sub" style="{{ $stockReportOpen ? 'display:block;' : '' }}">

                        @if(\App\Support\Permissions::canViewReport('Item_detail_report'))
                        <li class="menu-item {{ request()->routeIs('Item_detail_report') ? 'active' : '' }}">
                            <a href="{{route("Item_detail_report")}}" target ="_ blank" class="menu-link">
                              <div class="text-truncate" data-i18n="All Customers">Item Details</div>
                            </a>
                          </li>
                        @endif
                          @if(\App\Support\Permissions::canViewReport('stock_report'))
                          <li class="menu-item {{ request()->routeIs('stock_report') ? 'active' : '' }}">
                            <a href="{{route("stock_report")}}" target ="_ blank" class="menu-link">
                              <div class="text-truncate" data-i18n="Customer Details">Stock In Hand</div>
                            </a>
                          </li>
                          @endif
                              @if(\App\Support\Permissions::canViewReport('Stock_valuation_report'))
                              <li class="menu-item {{ request()->routeIs('Stock_valuation_report') ? 'active' : '' }}">
                                <a href="{{route("Stock_valuation_report")}}" target ="_ blank" class="menu-link">
                                  <div class="text-truncate" data-i18n="Overview">Stock Valuation</div>
                                </a>
                              </li>
                              @endif
                              @if(\App\Support\Permissions::canViewReport('bin_card'))
                              <li class="menu-item {{ request()->routeIs('bin_card') ? 'active' : '' }}">
                                <a href="{{route("bin_card")}}" target ="_ blank" class="menu-link">
                                  <div class="text-truncate" data-i18n="Security">Bin Card</div>
                                </a>
                              </li>
                              @endif
                              @if(\App\Support\Permissions::canViewReport('ZerostockReport'))
                              <li class="menu-item {{ request()->routeIs('ZerostockReport') ? 'active' : '' }}">
                                <a href="{{route("ZerostockReport")}}" target ="_ blank" class="menu-link">
                                  <div class="text-truncate" data-i18n="Security">Stock Zero </div>
                                </a>
                              </li>
                              @endif

                                  @if(\App\Support\Permissions::canViewReport('reports.stockTranferReport'))
                                  <li class="menu-item {{ request()->routeIs('reports.stockTranferReport') ? 'active' : '' }}">
                                <a href="{{route("reports.stockTranferReport")}}" target ="_ blank" class="menu-link">
                                  <div class="text-truncate" data-i18n="Security">Stock Tranfer Report </div>
                                </a>
                              </li>
                                  @endif


                                   @if(\App\Support\Permissions::canViewReport('reports.stockDetailsSummeryReport'))
                                   <li class="menu-item {{ request()->routeIs('reports.stockDetailsSummeryReport') ? 'active' : '' }}">
                                <a href="{{route("reports.stockDetailsSummeryReport")}}" target ="_ blank" class="menu-link">
                                  <div class="text-truncate" data-i18n="Security">Stock Details Report </div>
                                </a>
                              </li>
                                   @endif
                    </ul>
                  </li>
                  @endif

                  @if($canSeeSalesGroup)
                  <li class="menu-item">
                    <a href="javascript:void(0);"  class="menu-link menu-toggle {{ $salesReportOpen ? 'subdrop' : '' }}">
                      <div class="text-truncate" data-i18n="Order">Sales Report<span
                        class="menu-arrow"></span></div>
                    </a>
                    <ul class="menu-sub" style="{{ $salesReportOpen ? 'display:block;' : '' }}">
                       @if(\App\Support\Permissions::canViewReport('Item_wish_sales_report'))
                       <li class="menu-item {{ request()->routeIs('Item_wish_sales_report') ? 'active' : '' }}">
                        <a href="{{route("Item_wish_sales_report")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order List">Item Wise Sales</div>
                        </a>
                      </li>
                      @endif
                      @if(\App\Support\Permissions::canViewReport('sales_report'))
                      <li class="menu-item {{ request()->routeIs('sales_report') ? 'active' : '' }}">
                        <a href="{{route("sales_report")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Sales Summery</div>
                        </a>
                      </li>
                      @endif
                      @if(\App\Support\Permissions::canViewReport('sales_order_report'))
                      <li class="menu-item {{ request()->routeIs('sales_order_report') ? 'active' : '' }}">
                        <a href="{{route("sales_order_report")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Sales Order Report</div>
                        </a>
                      </li>
                      @endif
                      @if(\App\Support\Permissions::canViewReport('Invoice_detail_report'))
                      <li class="menu-item {{ request()->routeIs('Invoice_detail_report') ? 'active' : '' }}">
                        <a href="{{route("Invoice_detail_report")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Sales Details</div>
                        </a>
                      </li>
                      @endif



                       @if(\App\Support\Permissions::canViewReport('customer_wish_report'))
                       <li class="menu-item {{ request()->routeIs('customer_wish_report') ? 'active' : '' }}">
                        <a href="{{route("customer_wish_report")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Customer Wish Item Sales</div>
                        </a>
                      </li>
                      @endif

                    @if(\App\Support\Permissions::canViewReport('reports.salesmanInvoiceReport'))
                    <li class="menu-item {{ request()->routeIs('reports.salesmanInvoiceReport') ? 'active' : '' }}">
                        <a href="{{route("reports.salesmanInvoiceReport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Salesman Invoice Report</div>
                        </a>
                      </li>
                      @endif

                                          @if(\App\Support\Permissions::canViewReport('reports.SalesmanInvoiceSumReport'))
                                          <li class="menu-item {{ request()->routeIs('reports.SalesmanInvoiceSumReport') ? 'active' : '' }}">
                        <a href="{{route("reports.SalesmanInvoiceSumReport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Salesman Total Invoice Report</div>
                        </a>
                      </li>
                                          @endif

                      @if(\App\Support\Permissions::canViewReport('Cash_in_out_report'))
                      <li class="menu-item {{ request()->routeIs('Cash_in_out_report') ? 'active' : '' }}">
                        <a href="{{route("Cash_in_out_report")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Cash In Out Report</div>
                        </a>
                      </li>
                      @endif
                    </ul>
                  </li>
                  @endif


                @if($canSeeCustomerGroup)
                <li class="menu-item">
                    <a href="javascript:void(0);"  class="menu-link menu-toggle {{ $customerReportOpen ? 'subdrop' : '' }}">
                      <div class="text-truncate" data-i18n="Order">Customer Report<span
                        class="menu-arrow"></span></div>
                    </a>
                    <ul class="menu-sub" style="{{ $customerReportOpen ? 'display:block;' : '' }}">
                        @if(\App\Support\Permissions::canViewReport('get_customer_details_report'))
                        <li class="menu-item {{ request()->routeIs('get_customer_details_report') ? 'active' : '' }}">
                        <a href="{{route("get_customer_details_report")}}" target ="_ blank"  class="menu-link">
                          <div class="text-truncate" data-i18n="Order List">Customer Details</div>
                        </a>
                      </li>
                        @endif
                        @if(\App\Support\Permissions::canViewReport('customer_payment_report'))
                        <li class="menu-item {{ request()->routeIs('customer_payment_report') ? 'active' : '' }}">
                        <a href="{{route("customer_payment_report")}}" target ="_ blank" class="menu-link">
                           <div class="text-truncate" data-i18n="Order Details">Customer Payment Report</div>
                        </a>
                       </li>
                       @endif
                        @if(\App\Support\Permissions::canViewReport('customer_cheque_payment_report'))
                        <li class="menu-item {{ request()->routeIs('customer_cheque_payment_report') ? 'active' : '' }}">
                        <a href="{{route("customer_cheque_payment_report")}}" target ="_ blank" class="menu-link">
                           <div class="text-truncate" data-i18n="Order Details">Customer Cheque Payment Report</div>
                        </a>
                       </li>
                       @endif
                        @if(\App\Support\Permissions::canViewReport('Cust_Transferreport'))
                        <li class="menu-item {{ request()->routeIs('Cust_Transferreport') ? 'active' : '' }}">
                        <a href="{{route("Cust_Transferreport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Customer Acccount</div>
                        </a>
                      </li>
                      @endif

                     @if(\App\Support\Permissions::canViewReport('customersalesWishReport'))
                     <li class="menu-item {{ request()->routeIs('customersalesWishReport') ? 'active' : '' }}">
                        <a href="{{route("customersalesWishReport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Customer Wish Sale</div>
                        </a>
                      </li>
                      @endif

                        @if(\App\Support\Permissions::canViewReport('customer_balance_report'))
                        <li class="menu-item {{ request()->routeIs('customer_balance_report') ? 'active' : '' }}">
                        <a href="{{route("customer_balance_report")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Customer Balance</div>
                        </a>
                      </li>
                      @endif
                        @if(\App\Support\Permissions::canViewReport('AdvancePaymentReport'))
                        <li class="menu-item {{ request()->routeIs('AdvancePaymentReport') ? 'active' : '' }}">
                        <a href="{{route("AdvancePaymentReport")}}" target ="_ blank"  class="menu-link">
                          <div class="text-truncate" data-i18n="Order List">Advance Payment Report</div>
                        </a>
                      </li>
                      @endif
                        @if(\App\Support\Permissions::canViewReport('Purchase_order_report') || \App\Support\Permissions::canViewReport('Purchase_order_details_report'))
                        <li class="menu-item">
                    <a href="javascript:void(0);"  class="menu-link menu-toggle {{ $purchaseOrderReportOpen ? 'subdrop' : '' }}">
                      <div class="text-truncate" data-i18n="Customer">Purchase order Report<span
                        class="menu-arrow"></span></div>
                    </a>
                        <ul class="menu-sub" style="{{ $purchaseOrderReportOpen ? 'display:block;' : '' }}">
                          @if(\App\Support\Permissions::canViewReport('Purchase_order_report'))
                          <li class="menu-item {{ request()->routeIs('Purchase_order_report') ? 'active' : '' }}">
                            <a href="{{route("Purchase_order_report")}}" target ="_ blank" class="menu-link">
                              <div class="text-truncate" data-i18n="All Customers">Summery</div>
                            </a>
                          </li>
                          @endif
                          @if(\App\Support\Permissions::canViewReport('Purchase_order_details_report'))
                          <li class="menu-item {{ request()->routeIs('Purchase_order_details_report') ? 'active' : '' }}">
                            <a href="{{route("Purchase_order_details_report")}}" target ="_ blank" class="menu-link">
                              <div class="text-truncate" data-i18n="Customer Details">Details</div>
                            </a>
                          </li>
                          @endif
                        </ul>
                  </li>
                  @endif
                    </ul>
                </li>
                @endif


                                @if($canSeeSupplierGroup)
                                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle {{ $supplierReportOpen ? 'subdrop' : '' }}">
                                <div class="text-truncate" data-i18n="Order">Supplier Report<span class="menu-arrow"></span></div>
                            </a>
                            <ul class="menu-sub" style="{{ $supplierReportOpen ? 'display:block;' : '' }}">
                                @if(\App\Support\Permissions::canViewReport('get_supplier_details_report'))
                                <li class="menu-item {{ request()->routeIs('get_supplier_details_report') ? 'active' : '' }}">
                                    <a href="{{route("get_supplier_details_report")}}" target="_ blank" class="menu-link">
                                        <div class="text-truncate" data-i18n="Order List">Supplier Details</div>
                                    </a>
                                </li>
                                @endif
                                @if(\App\Support\Permissions::canViewReport('supplyer_payment_report'))
                                <li class="menu-item {{ request()->routeIs('supplyer_payment_report') ? 'active' : '' }}">
                                    <a href="{{route('supplyer_payment_report')}}" target="_ blank" class="menu-link">
                                        <div class="text-truncate" data-i18n="Customer Details">Supplyer Payment Report</div>
                                    </a>
                                </li>
                                @endif
                                @if(\App\Support\Permissions::canViewReport('supplyer_cheque_payment_report'))
                                <li class="menu-item {{ request()->routeIs('supplyer_cheque_payment_report') ? 'active' : '' }}">
                                    <a href="{{route('supplyer_cheque_payment_report')}}" target="_ blank"
                                        class="menu-link">
                                        <div class="text-truncate" data-i18n="Customer Details">Supplyer Cheque Payment Report</div>
                                    </a>
                                </li>
                                @endif
                                @if(\App\Support\Permissions::canViewReport('supplier_account_report'))
                                <li class="menu-item {{ request()->routeIs('supplier_account_report') ? 'active' : '' }}">
                                    <a href="{{route('supplier_account_report')}}" target="_ blank" class="menu-link">
                                        <div class="text-truncate" data-i18n="Order Details">Supplier Acccount</div>
                                    </a>
                                </li>
                                @endif
                                @if(\App\Support\Permissions::canViewReport('supplyer_balance_report'))
                                <li class="menu-item {{ request()->routeIs('supplyer_balance_report') ? 'active' : '' }}">
                                    <a href="{{route("supplyer_balance_report")}}" target="_ blank" class="menu-link">
                                        <div class="text-truncate" data-i18n="Order Details">Supplier Balance</div>
                                    </a>
                                </li>
                                @endif




                            </ul>
                </li>
                @endif



                  @if(\App\Support\Permissions::canViewReport('purchasing.report'))
                  <li class="menu-item {{ Request::is('purchasing-report') ? 'active' : '' }}">
                    <a href="{{ route('purchasing.report') }}" class="menu-link">
                      <div class="text-truncate" data-i18n="Customer">Purchasing Report</div>
                    </a>
                  </li>
                  @endif


                  @if($canSeeSalesReturnGroup)
                  <li class="menu-item {{ $salesReturnReportOpen ? 'active' : '' }}">
                    <a href="{{ route('sales.return.report') }}" class="menu-link">
                      <div class="text-truncate" data-i18n="Order">Sales Return Report</div>
                    </a>
                  </li>
                  @endif


                @if(\App\Support\Permissions::canViewReport('cashInHandReport'))
                <li class="menu-item {{ request()->routeIs('cashInHandReport') ? 'active' : '' }}">
                        <a href="{{route("cashInHandReport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Cash In Hand</div>
                        </a>
                  </li>
                  @endif



                @if(\App\Support\Permissions::canViewReport('cashandChequeTransaction'))
                <li class="menu-item {{ request()->routeIs('cashandChequeTransaction') ? 'active' : '' }}">
                        <a href="{{route("cashandChequeTransaction")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Cash & Cheque Transaction</div>
                        </a>
                  </li>
                  @endif

                  @if($canSeeJournalGroup)
                  <li class="menu-item">
                       <a href="javascript:void(0);"  class="menu-link menu-toggle {{ $journalOpen ? 'subdrop' : '' }}">
                      <div class="text-truncate" data-i18n="Order">Journal<span
                        class="menu-arrow"></span></div>
                    </a>

                    <ul class="menu-sub" style="{{ $journalOpen ? 'display:block;' : '' }}">
                        @if(\App\Support\Permissions::canViewReport('daily.transactions'))
                        <li class="menu-item {{ request()->routeIs('daily.transactions') ? 'active' : '' }}">
                            <a href="{{route('daily.transactions')}}" target ="_ blank" class="menu-link">
                                <div class="text-truncate" data-i18n="Order Details">Daily Transactions</div>
                            </a>
                        </li>
                        @endif
                        @if(\App\Support\Permissions::canViewReport('cash.book'))
                        <li class="menu-item {{ request()->routeIs('cash.book') ? 'active' : '' }}">
                            <a href="{{route('cash.book')}}" target ="_ blank" class="menu-link">
                                <div class="text-truncate" data-i18n="Order Details">Cash Book</div>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                </ul>

              </li>
            @endif
@endif
            </ul>
        </div>
    </div>
    <div class="sidebar-footer">
        <div class="sidebar-profile">
            <span class="sidebar-profile-avatar">{{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 1)) }}</span>
            <span class="sidebar-profile-info">
                <span class="sidebar-profile-name">{{ Auth::user()->username ?? 'Admin' }}</span>
                <span class="sidebar-profile-role">{{ optional(Auth::user())->role_name ?? 'Super Admin' }}</span>
            </span>
            <button type="button" id="toggle_btn" class="sidebar-profile-toggle" aria-controls="sidebar-menu" aria-expanded="true" aria-label="Collapse sidebar" title="Collapse sidebar">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <div class="sidebar-profile-more">
                <button type="button" id="sidebar-logout-btn" class="sidebar-profile-more-btn" aria-label="Logout" title="Logout">
                    <i data-feather="log-out"></i>
                </button>
                <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
<script>
(() => {
    const toggle = document.getElementById('toggle_btn');

    function updateToggle() {
        const collapsed = document.body.classList.contains('mini-sidebar');
        const label = collapsed ? 'Expand sidebar' : 'Collapse sidebar';
        toggle.setAttribute('aria-expanded', String(!collapsed));
        toggle.setAttribute('aria-label', label);
        toggle.title = label;
    }

    toggle.addEventListener('click', () => {
        document.body.classList.toggle('mini-sidebar');
        updateToggle();
    });
    updateToggle();
})();

$(function() {
    var $scroll = $('#sidebar .scroll');
    var $active = $('#sidebar-menu li.active > a, #sidebar-menu li a.active').last();
    if ($scroll.length && $active.length) {
        var target = $active.offset().top - $scroll.offset().top + $scroll.scrollTop() - 150;
        $scroll.scrollTop(Math.max(target, 0));
    }
});
</script>
@yield('content')
