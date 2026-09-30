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
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/theme-redesign.css') }}?v={{ filemtime(public_path('assets/css/theme-redesign.css')) }}">


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
    <div class="scroll">
    <!--<div class="sidebar-inner slimscroll">-->
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                {{-- Main --}}
                <li class="menu-title"><span>Main</span></li>
                        <li class="{{ Request::is('home') ? 'active' : '' }}">
                        <a href="{{ route('home') }}">
                            <svg class="nav-svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            <span>Home</span>
                        </a>
                    </li>


                 <!-- e-commerce-app menu start -->


                {{-- User Management --}}
@if(\App\Support\Permissions::can('user_management'))
                <li class="submenu {{ Request::is('users') || Request::is('Userrole')  || Request::is('add_user') || Request::is('role-permissions')  ? 'active' : '' }} ">
                    <a href="#" class=" {{ Request::is('users') || Request::is('Userrole')  || Request::is('add_user') || Request::is('role-permissions') ? 'subdrop' : '' }} ">
                        <svg class="nav-svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg> <span> User Management</span>
                        <span class="menu-arrow"></span></a>
                    <ul style=" {{ Request::is('users') || Request::is('add_user') ? 'display:block;' : '' }} ">
                        <li class="{{ Request::is('users') || Request::is('add_user') ? 'active ' : '' }}">
                            <a href="{{route("users")}}">
                                <i class="fa fa-angle-right"></i>
                                Users
                            </a>
                        </li>
                        <li class="{{ Request::is('Userrole') ? 'active ' : '' }}">
                            <a href="{{route("Userrole")}}">
                                <i class="fa fa-angle-right"></i>
                                Roles</a>
                        </li>
@if(\App\Support\Permissions::can('role_permissions'))
                        <li class="{{ Request::is('role-permissions') ? 'active ' : '' }}">
                            <a href="{{route("role_permissions")}}">
                                <i class="fa fa-angle-right"></i>
                                Role Permissions</a>
                        </li>
@endif


                    </ul>
                </li>
@endif

                {{-- system --}}

@if(\App\Support\Permissions::can('system'))
                <li class="{{ Request::is('Company*') ? 'active' : '' }}">
                    <a href="#"><svg class="nav-svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg> <span>System</span>
                        <span class="menu-arrow"></span></a>

                    <ul style=" {{ Request::is('Company*') ? 'display:block;' : '' }}">
                        <li class="{{ Request::is('Company') ? 'active' : '' }}">
                            <a href="{{route("Company")}}">
                                <i class="fa fa-angle-right"></i>
                                Company</a>
                        </li>
                        <li class="{{ Request::is('Company_branchdetails') ? 'active' : '' }}">
                            <a href="{{route("branchdetails")}}">
                                <i class="fa fa-angle-right"></i>
                                Branch</a>
                        </li>
                    </ul>
                </li>
@endif

                {{-- Master --}}
@if(\App\Support\Permissions::can('master'))
                <li
                    class="dropdown {{ Request::is('master*')  || Request::is('SalesMan') || Request::is('Area')  || Request::is('Route') || Request::is('Bank_Branch') || Request::is('BankDeltails') || Request::is('MGuarantor') || Request::is('SchemaType')  || Request::is('MColor')  || Request::is('M_Make')  || Request::is('MBrand')  || Request::is('Category') || Request::is('Store') || Request::is('Department') ||Request::is('Suppliers') || Request::is('Item') || Request::is('Category') ? 'active' : '' }} ">
                    <a href="#"><svg class="nav-svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="9" y="3" width="6" height="6" rx="1"/><rect x="3" y="15" width="6" height="6" rx="1"/><rect x="15" y="15" width="6" height="6" rx="1"/><path d="M12 9v3M12 12H6v3M12 12h6v3"/></svg> <span> Master</span> <span class="menu-arrow"></span></a>
                    <ul
                        style=" {{ Request::is('master*') || Request::is('SalesMan')  || Request::is('Area')  || Request::is('Route') || Request::is('Bank_Branch') || Request::is('BankDeltails') || Request::is('MGuarantor') || Request::is('SchemaType')  || Request::is('MColor')  || Request::is('Category') || Request::is('Store') || Request::is('Department') || Request::is('M_Make')  || Request::is('MBrand') ||Request::is('Suppliers') || Request::is('Item') || Request::is('Category')  ? 'display:block;' : '' }}">
                        <li class="{{ Request::is('Department') ? 'active' : '' }}">
                            <a href="{{'Department'}}"><i class="fa fa-angle-right"></i>Department</a>
                        </li>

                        <li class="{{ Request::is('Store') ? 'active' : '' }}">
                            <a href="{{route("Store")}}"><i class="fa fa-angle-right"></i>Store</a>
                        </li>

                        <li class="{{ Request::is('Category') ? 'active' : '' }}">
                            <a href="{{route("Category")}}"><i class="fa fa-angle-right"></i>Category</a>
                        </li>

                        <li class="{{ Request::is('MBrand') ? 'active' : '' }}">
                            <a href="{{route("MBrand")}}"><i class="fa fa-angle-right"></i>Brands</a>
                        </li>
                        <li class="{{ Request::is('M_Make') ? 'active' : '' }}">
                            <a href="{{route("M_Make")}}"><i class="fa fa-angle-right"></i>Make</a>
                        </li>
                        <li class="{{ Request::is('MColor') ? 'active' : '' }}">
                            <a href="{{route("MColor")}}"><i class="fa fa-angle-right"></i>Color</a>
                        </li>

                        <li class="{{ Request::is('Item') ? 'active' : '' }}">
                            <a href="{{route('Item')}}"><i class="fa fa-angle-right"></i>Item</a>
                        </li>
                        <li class="dropdown {{ Request::is('master_customers') ? 'active' : '' }}">
                            <a href="{{route("master_customers")}}">
                                <i class="fa fa-angle-right"></i>Customers</a>
                        </li>

						<li class="{{ Request::is('Suppliers') ? 'active' : '' }}">
                            <a href="{{('Suppliers')}}"><i class="fa fa-angle-right"></i>Suppliers</a>
                        </li>
                       <li class="{{ Request::is('SchemaType') ? 'active' : '' }}">
                            <a href="{{route("SchemaType")}}"><i class="fa fa-angle-right"></i>Schema Type</a>
                        </li>

                        <li class="{{ Request::is('MGuarantor') ? 'active' : '' }}">
                            <a href="{{route("MGuarantor")}}"><i class="fa fa-angle-right"></i>Guarantor</a>
                        </li>

                        <li class="{{ Request::is('BankDeltails') ? 'active' : '' }}">
                            <a href="{{route("BankDeltails")}}"><i class="fa fa-angle-right"></i>Bank</a>
                        </li>
                        <li class="{{ Request::is('Bank_Branch') ? 'active' : '' }}">
                            <a href="{{route('Bank_Branch')}}"><i class="fa fa-angle-right"></i>Bank Branch</a>
                        </li>
                        <li class="{{ Request::is('ChequeBanks') ? 'active' : '' }}">
                            <a href="{{route('ChequeBanks')}}"><i class="fa fa-angle-right"></i>Account</a>
                        </li>
                           {{-- Route --}}

                        <li class="{{ Request::is('Route') ? 'active' : '' }}">
                            <a href="{{route('Route')}}"><i class="fa fa-angle-right"></i>Route</a>
                        </li>

                        {{-- Area --}}
                        <li class="{{ Request::is('Area') ? 'active' : '' }}">
                            <a href="{{route('Area')}}"><i class="fa fa-angle-right"></i>Area</a>
                        </li>
                        {{-- SalesMan --}}
                        <li class="{{ Request::is('SalesMan') ? 'active' : '' }}">
                            <a href="{{route('SalesMan')}}"><i class="fa fa-angle-right"></i>SalesMan</a>
                        </li>

                    </ul>
                </li>
@endif


                {{-- Stock --}}

@if(\App\Support\Permissions::can('stock'))
                <li class="{{ Request::is('stock*') ? 'active' : '' }}">
                    <a href="#"><svg class="nav-svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" y1="22" x2="12" y2="12"/></svg> <span>Stock</span>
                        <span class="menu-arrow"></span></a>

                    <ul style=" {{ Request::is('stock*') ? 'display:block;' : '' }}">
                        <li class="{{ Request::is('stock_open') ? 'active' : '' }}">
                            <a href="{{route("stock_open")}}">
                                <i class="fa fa-angle-right"></i>
                                Opening Stock</a>
                        </li>
                       <li class="{{ Request::is('stockAdjuestment') ? 'active' : '' }}">
                          <a href="{{route("stockAdjuestment")}}">
                                <i class="fa fa-angle-right"></i>
                                Stock Adjestment</a>
                        </li>

                        <li class="{{ Request::is('stockAdjuestmentNew') ? 'active' : '' }}">
                          <a href="{{route("stockAdjuestmentNew")}}">
                                <i class="fa fa-angle-right"></i>
                                Stock Adjestment New</a>
                        </li>

                        <li class="{{ Request::is('stock_damage') ? 'active' : '' }}">
                            <a href="">
                                <i class="fa fa-angle-right"></i>
                                Damage Stock</a>
                        </li>

                        <li class="{{ Request::is('stock_transfer') ? 'active' : '' }}">
                            <a href="{{route("stock_transfer")}}">
                                <i class="fa fa-angle-right"></i>
                                Stock Transfer</a>
                        </li>
                    </ul>
                </li>
@endif


@if(\App\Support\Permissions::can('purchases'))
                <li class=" dropdown {{ Request::is('purchases*') ? 'active' : '' }} ">
                   <a href="#"><svg class="nav-svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg> <span>Purchases</span>
                       <span class="menu-arrow"></span></a>

                   <ul style="{{ Request::is('purchases*') ? 'display:block;' : '' }} ">
                       <li class="{{ Request::is('purchases_order') ? 'active' : '' }}">
                           <a href="{{route("purchases_order")}}">
                               <i class="fa fa-angle-right"></i>Purshases Order</a>
                       </li>
                       <li class="{{ Request::is('purchases') ? 'active' : '' }}">
                           <a href="{{route("purchases")}}">
                               <i class="fa fa-angle-right" ></i>Purchases</a>
                       </li>

                       <li class="{{ Request::is('purchases_supplyer_payment') ? 'active' : '' }}">
                           <a href="{{route("purchases_supplyer_payment")}}">
                               <i class="fa fa-angle-right"></i>
                               Supplyer Payment</a>
                       </li>

                       <li class="{{ Request::is('purchases_return') ? 'active' : '' }}">
                           <a href="{{route("purchases_return")}}">
                               <i class="fa fa-angle-right"></i>
                               Purchases Return</a>
                       </li>
                   </ul>
               </li>
@endif

@if(\App\Support\Permissions::can('sales'))
                <li class=" dropdown {{ Request::is('sales*') ? 'active' : '' }} ">
                    <a href="#"><svg class="nav-svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg> <span>Sales</span>
                        <span class="menu-arrow"></span></a>

                    <ul style="{{ Request::is('sales*') ? 'display:block;' : '' }} ">
                        {{-- <li class="{{ Request::is('sales_invoice') ? 'active' : '' }}">
                            <a href="{{route("sales_invoice")}}">
                                <i class="fa fa-angle-right"></i> Invoice Sales</a>
                        </li> --}}

                        <li class="{{ Request::is('salesInvoice_withoutVat') ? 'active' : '' }}">
                            <a href="{{route("salesInvoice_withoutVat")}}">
                                <i class="fa fa-angle-right"></i> Sales Invoice</a>
                        </li>

                        <!--<li class="{{ Request::is('sales_create_invoice') ? 'active' : '' }}">-->
                        <!--    <a href="{{route("sales_create_invoice")}}">-->
                        <!--        <i class="fa fa-angle-right"></i> Vat Sales Invoice</a>-->
                        <!--</li>-->

                        <li class="{{ Request::is('sales_advance_payment') ? 'active' : '' }}">
                            <a href="{{route("sales_advance_payment")}}">
                                <i class="fa fa-angle-right"></i>
                                Advance Payment</a>
                        </li>

                        <li class="{{ Request::is('sales_return') ? 'active' : '' }}">
                            <a href="{{route("sales_return")}}">
                                <i class="fa fa-angle-right"></i>
                                Sales Return</a>
                        </li>

                        <li class="{{ Request::is('sales_quatation') ? 'active' : '' }}">
                            <a href="{{route("sales_quatation")}}">
                                <i class="fa fa-angle-right"></i>
                                Quatation</a>
                        </li>
                        <li class="{{ Request::is('sales_customer_payment') ? 'active' : '' }}">
                            <a href="{{route("sales_customer_payment")}}">
                                <i class="fa fa-angle-right"></i>
                                Customer Payment</a>
                        </li>

                           <li class="{{ Request::is('sales_customer_payment') ? 'active' : '' }}" target ="_ blank">
                          <a href="{{route("customer_opening_balance")}}">
                              <i class="fa fa-angle-right"></i>
                             Customer Balance</a>
                      </li>
                      
                      
                            <li class="{{ Request::is('invoiceDiscountEnter') ? 'active' : '' }}">
                    <a href="{{ route('invoiceDiscountEnter') }}" target="_blank">
                        <i class="fa fa-angle-right"></i>
                        Customer Discount
                    </a>
                          </li>

                    </ul>
                </li>
@endif



@if(\App\Support\Permissions::can('vouchers'))
                <li
                 {{-- class="{{ Request::is('PaymentVoucher*') ? 'active' : '' }}||
                {{ Request::is('PaymentVoucher*') ? 'active' : '' }} " --}}
                >
                <a href="#">
                    <svg class="nav-svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M2 9a3 3 0 1 0 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 1 0 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/></svg> <span> Vouchers</span> <span class="menu-arrow"></span></a>
                <ul
                style=" {{ Request::is('PaymentVoucher*') ? 'display:block;' : '' }}"
                >
                    <li
                    class="{{ Request::is('PaymentVoucher') ? 'active' : '' }}"
                    >
                        <a href="{{route("PaymentVoucher")}}">
                            <i class="fa fa-angle-right"></i>
                            Payment Vouchers</a>
                    </li>
                    
                                             <li>
            <a href="{{ route('gentralreceipt') }}" >
                <i class="fa fa-receipt"></i> Gentral Receipt
            </a>
        </li>
        
                    <li class="{{ Request::is('PettyCash') ? 'active' : '' }}">
                        <a href="{{route("PettyCash")}}">
                            <i class="fa fa-angle-right"></i>
                            Petty Cash</a>
                    </li>
                </ul>
                </li>
@endif

@if(\App\Support\Permissions::can('expense'))
                <li class="{{ Request::is('AddExpense*') ? 'active' : '' }}">
                    <a href="#"><svg class="nav-svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/></svg> <span>Expense</span>
                        <span class="menu-arrow"></span></a>

                    <ul style=" {{ Request::is('AddExpense*') ? 'display:block;' : '' }}">
                        <li class="{{ Request::is('AddExpense') ? 'active' : '' }}">
                            <a href="{{route("AddExpense")}}">
                                <i class="fa fa-angle-right"></i>
                                Add Expense</a>
                        </li>
                        <li class="{{ Request::is('CashOut') ? 'active' : '' }}">
                            <a href="{{route("CashOut")}}">
                                <i class="fa fa-angle-right"></i>
                                Cash Out</a>
                        </li>
                    </ul>
                </li>
@endif


@if(\App\Support\Permissions::can('banking'))
                <li class="dropdown {{ Request::is('cheque-deposit') || Request::is('issued-cheques') || Request::is('cheque-return') ? 'active' : '' }}">
                    <a href="#">
                        <svg class="nav-svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg> <span> Banking </span> <span
                            class="menu-arrow"></span></a>
                            <ul style="{{ Request::is('cheque-deposit') || Request::is('issued-cheques') || Request::is('cheque-return') ? 'display:block;' : '' }}">
                                <li class="{{ Request::is('cheque-deposit') ? 'active' : '' }}">
                                    <a href="{{ route('cheque.deposit') }}">
                                    <i class="fa fa-angle-right"></i>
                                    Cheque Deposit</a>
                                </li>
                                <li class="{{ Request::is('issued-cheques') ? 'active' : '' }}">
                                    <a href="{{ route('issued.cheques') }}">
                                    <i class="fa fa-angle-right"></i>
                                    Issued Cheques </a>
                                </li>
                                <li class="{{ Request::is('cheque-return') ? 'active' : '' }}">
                                    <a href="{{ route('cheque.return') }}">
                                    <i class="fa fa-angle-right"></i>
                                    Cheque Return</a>
                                </li>
                                <li class="">
                                    <a href="">
                                    <i class="fa fa-angle-right"></i>
                                    Banking Recognition</a>
                                </li>
                            </ul>
                 </li>
@endif


                {{-- Accounting --}}
@if(\App\Support\Permissions::can('accounting'))
                <li class="">
                    <a href="#">
                        <svg class="nav-svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="9" x2="9" y2="21"/><line x1="15" y1="9" x2="15" y2="21"/></svg> <span> Accounting</span> <span
                            class="menu-arrow"></span></a>
                            <ul style="">
                                <li class="">
                                    <a href="{{route("account_category")}}">
                                    <i class="fa fa-angle-right"></i>
                                   Category</a>
                                </li>
                                <li class="">
                                    <a href="{{route("account_type")}}">
                                    <i class="fa fa-angle-right"></i>
                                   Type</a>
                                </li>
                                <li class="">
                                    <a href="{{route("chartofaccount")}}">
                                    <i class="fa fa-angle-right"></i>
                                   Chart Of Accounts</a>
                                </li>

                            </ul>
                 </li>
@endif

@if(\App\Support\Permissions::can('reports'))
            <li class="">
                <a href="#"><svg class="nav-svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg> <span>Reports</span> <span
                    class="menu-arrow"></span></a>
                <ul class="menu-sub">
                  <li class="menu-item">
                    <a href="javascript:void(0);"  class="menu-link menu-toggle">
                      <div class="text-truncate" data-i18n="Products">Stock Report<span
                        class="menu-arrow"></span> </div>
                    </a>
                    <ul class="menu-sub">

                        <li class="menu-item">
                            <a href="{{route("Item_detail_report")}}" target ="_ blank" class="menu-link">
                              <div class="text-truncate" data-i18n="All Customers">Item Details</div>
                            </a>
                          </li>
                          <li class="menu-item">
                            <a href="{{route("stock_report")}}" target ="_ blank" class="menu-link menu-toggle">
                              <div class="text-truncate" data-i18n="Customer Details">Stock In Hand</div>
                            </a>
                              <li class="menu-item">
                                <a href="{{route("Stock_valuation_report")}}" target ="_ blank" class="menu-link">
                                  <div class="text-truncate" data-i18n="Overview">Stock Valuation</div>
                                </a>
                              </li>
                              <li class="menu-item">
                                <a href="{{route("bin_card")}}" target ="_ blank" class="menu-link">
                                  <div class="text-truncate" data-i18n="Security">Bin Card</div>
                                </a>
                              </li>
                              <li class="menu-item">
                                <a href="{{route("ZerostockReport")}}" target ="_ blank" class="menu-link">
                                  <div class="text-truncate" data-i18n="Security">Stock Zero </div>
                                </a>
                              </li>

                                  <li class="menu-item">
                                <a href="{{route("reports.stockTranferReport")}}" target ="_ blank" class="menu-link">
                                  <div class="text-truncate" data-i18n="Security">Stock Tranfer Report </div>
                                </a>
                              </li>


                                   <li class="menu-item">
                                <a href="{{route("reports.stockDetailsSummeryReport")}}" target ="_ blank" class="menu-link">
                                  <div class="text-truncate" data-i18n="Security">Stock Details Report </div>
                                </a>
                              </li>
                    </ul>
                  </li>

                  <li class="menu-item">
                    <a href="javascript:void(0);"  class="menu-link menu-toggle">
                      <div class="text-truncate" data-i18n="Order">Sales Report<span
                        class="menu-arrow"></span></div>
                    </a>
                    <ul class="menu-sub">
                       <li class="menu-item">
                        <a href="{{route("Item_wish_sales_report")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order List">Items wish Sales</div>
                        </a>
                      </li>
                      <li class="menu-item">
                        <a href="{{route("sales_report")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Sales Summery</div>
                        </a>
                      </li>
                      <li class="menu-item">
                        <a href="{{route("Invoice_detail_report")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Sales Details</div>
                        </a>
                      </li>
                      
                      
                      
                       <li class="menu-item">
                        <a href="{{route("customer_wish_report")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Customer Wish Item Sales</div>
                        </a>
                      </li>
                      
                    <li class="menu-item">
                        <a href="{{route("reports.salesmanInvoiceReport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Salesman Invoice Report</div>
                        </a>
                      </li>
                      
                                          <li class="menu-item">
                        <a href="{{route("reports.SalesmanInvoiceSumReport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Salesman Total Invoice Report</div>
                        </a>
                      </li>

                      <li class="menu-item">
                        <a href="{{route("Cash_in_out_report")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Cash In Out Report</div>
                        </a>
                      </li>
                    </ul>
                  </li>


                <li class="menu-item">
                    <a href="javascript:void(0);"  class="menu-link menu-toggle">
                      <div class="text-truncate" data-i18n="Order">Customer Report<span
                        class="menu-arrow"></span></div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                        <a href="{{route("get_customer_details_report")}}" target ="_ blank"  class="menu-link">
                          <div class="text-truncate" data-i18n="Order List">Customer Details</div>
                        </a>
                      </li>
                        <li class="menu-item">
                        <a href="{{route("customer_payment_report")}}" target ="_ blank" class="menu-link">
                           <div class="text-truncate" data-i18n="Order Details">Customer Payment Report</div>
                        </a>
                       </li>
                        <li class="menu-item">
                        <a href="{{route("customer_cheque_payment_report")}}" target ="_ blank" class="menu-link">
                           <div class="text-truncate" data-i18n="Order Details">Customer Cheque Payment Report</div>
                        </a>
                       </li>
                        <li class="menu-item">
                        <a href="{{route("Cust_Transferreport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Customer Acccount</div>
                        </a>
                      </li>

                     <li class="menu-item">
                        <a href="{{route("customersalesWishReport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Customer Wish Sale</div>
                        </a>
                      </li>

                        <li class="menu-item">
                        <a href="{{route("customer_balance_report")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Customer Balance</div>
                        </a>
                      </li>
                        <li class="menu-item">
                        <a href="{{route("AdvancePaymentReport")}}" target ="_ blank"  class="menu-link">
                          <div class="text-truncate" data-i18n="Order List">Advance Payment Report</div>
                        </a>
                      </li>
                        <li class="menu-item">
                    <a href="javascript:void(0);"  class="menu-link menu-toggle">
                      <div class="text-truncate" data-i18n="Customer">Purchase order Report<span
                        class="menu-arrow"></span></div>
                    </a>
                        <ul class="menu-sub">
                          <li class="menu-item">
                            <a href="{{route("Purchase_order_report")}}" target ="_ blank" class="menu-link">
                              <div class="text-truncate" data-i18n="All Customers">Summery</div>
                            </a>
                          </li>
                          <li class="menu-item">
                            <a href="{{route("Purchase_order_details_report")}}" target ="_ blank" class="menu-link menu-toggle">
                              <div class="text-truncate" data-i18n="Customer Details">Details</div>
                            </a>
                          </li>
                        </ul>
                  </li>
                    </ul>
                </li>
                
                
                                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <div class="text-truncate" data-i18n="Order">Supplier Report<span class="menu-arrow"></span></div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item">
                                    <a href="{{route("get_supplier_details_report")}}" target="_ blank" class="menu-link">
                                        <div class="text-truncate" data-i18n="Order List">Supplier Details</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{route('supplyer_payment_report')}}" target="_ blank" class="menu-link menu-toggle">
                                        <div class="text-truncate" data-i18n="Customer Details">Supplyer Payment Report</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{route('supplyer_cheque_payment_report')}}" target="_ blank"
                                        class="menu-link menu-toggle">
                                        <div class="text-truncate" data-i18n="Customer Details">Supplyer Cheque Payment Report</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{route('supplier_account_report')}}" target="_ blank" class="menu-link">
                                        <div class="text-truncate" data-i18n="Order Details">Supplier Acccount</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="{{route("supplyer_balance_report")}}" target="_ blank" class="menu-link">
                                        <div class="text-truncate" data-i18n="Order Details">Supplier Balance</div>
                                    </a>
                                </li>




                            </ul>
                </li>



                  <li class="menu-item {{ Request::is('purchasing-report') ? 'active' : '' }}">
                    <a href="{{ route('purchasing.report') }}" class="menu-link">
                      <div class="text-truncate" data-i18n="Customer">Purchasing Report</div>
                    </a>
                  </li>



                  <li class="menu-item">
                    <a href="javascript:void(0);"  class="menu-link menu-toggle">
                      <div class="text-truncate" data-i18n="Order">Opening Hire Purchase Report<span
                        class="menu-arrow"></span></div>
                    </a>
                    <ul class="menu-sub">
                      <li class="menu-item">
                        <a href="{{route("OpeningHirepurchaseReport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order List">Opening Hire Purchase details</div>
                        </a>
                      </li>
                      <li class="menu-item">
                        <a href="{{route("OpeningHirepurchaseSumReport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Opening Hire Purchase Sum</div>
                        </a>
                      </li>
                      <!--<li class="menu-item">-->
                      <!--  <a href="{{route("Invoice_detail_report")}}" class="menu-link">-->
                      <!--    <div class="text-truncate" data-i18n="Order Details">Sales Details</div>-->
                      <!--  </a>-->
                      <!--</li>-->
                    </ul>
                  </li>
                  
                  
                  
                  <li class="menu-item">
                       <a href="javascript:void(0);"  class="menu-link menu-toggle">
                      <div class="text-truncate" data-i18n="Order">Sales Return Report<span
                        class="menu-arrow"></span></div>
                    </a>

                    <ul style="">
                        <li class="">
                            <a href="{{route("sales.return.report")}}">
                                <i class="fa fa-angle-right"></i>
                                Sales Return</a>
                        </li>
                    </ul>
                </li>
                  
                  
                <li class="menu-item">
                        <a href="{{route("cashInHandReport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Cash In Hand</div>
                        </a>
                  </li>



                <li class="menu-item">
                        <a href="{{route("cashandChequeTransaction")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Cash & Cheque Transaction</div>
                        </a>
                  </li>

                  <li class="menu-item">
                       <a href="javascript:void(0);"  class="menu-link menu-toggle">
                      <div class="text-truncate" data-i18n="Order">Journal<span
                        class="menu-arrow"></span></div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{route('daily.transactions')}}" target ="_ blank" class="menu-link">
                                <div class="text-truncate" data-i18n="Order Details">Daily Transactions</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{route('cash.book')}}" target ="_ blank" class="menu-link">
                                <div class="text-truncate" data-i18n="Order Details">Cash Book</div>
                            </a>
                        </li>
                    </ul>
                </li>
                  <!--<li class="menu-item">-->
                  <!--      <a href="{{route("CashTransferreport")}}" target ="_ blank" class="menu-link">-->
                  <!--        <div class="text-truncate" data-i18n="Order Details">Cash In Hand</div>-->
                  <!--      </a>-->
                  <!--</li>-->

                  <li class="menu-item">
                    <a href="javascript:void(0);"  class="menu-link menu-toggle">
                      <div class="text-truncate" data-i18n="Order">Customer Report<span
                        class="menu-arrow"></span></div>
                    </a>
                    <ul class="menu-sub">
                      <li class="menu-item">
                        <a href="{{route("OpeningHirepurchaseReport")}}" target ="_ blank"  class="menu-link">
                          <div class="text-truncate" data-i18n="Order List">Customer Details</div>
                        </a>
                      </li>
                      <li class="menu-item">
                        <a href="{{route("OpeningHirepurchaseSumReport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Customer Balance</div>
                        </a>
                      </li>
                      <li class="menu-item">
                        <a href="{{route("Cust_Transferreport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Customer Acccount</div>
                        </a>
                      </li>
                      <li class="menu-item">
                        <a href="{{route("OpeningHirepurchaseSumReport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Customer Sale</div>
                        </a>
                      </li>
                      <li class="menu-item">
                        <a href="{{route("OpeningHirepurchaseSumReport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="Order Details">Customer Payment</div>
                        </a>
                      </li>
                      <li class="menu-item">
                        <a href="{{route("AdvancePaymentReport")}}" target ="_ blank"  class="menu-link">
                          <div class="text-truncate" data-i18n="Order List">Advance Payment Report</div>
                        </a>
                      </li>



                  <li class="menu-item">
                    <a href="javascript:void(0);"  class="menu-link menu-toggle">
                      <div class="text-truncate" data-i18n="Customer">Purchase order Report<span
                        class="menu-arrow"></span></div>
                    </a>
                    <ul class="menu-sub">
                      <li class="menu-item">
                        <a href="{{route("Purchase_order_report")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="All Customers">Summery</div>
                        </a>
                      </li>
                      <li class="menu-item">
                        <a href="{{route("Purchase_order_details_report")}}" target ="_ blank" class="menu-link menu-toggle">
                          <div class="text-truncate" data-i18n="Customer Details">Details</div>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <!--<li><a href="#"><i class="fa fa-angle-right"></i> Register Report</a></li>-->
                  <!--<li><a href="#"><i class="fa fa-angle-right"></i> Sell Payment Report</a></li>-->
                  <!--<li><a href="#"><i class="fa fa-angle-right"></i> Purchase Payment Report</a></li>-->
                  <!--<li><a href="#"><i class="fa fa-angle-right"></i> Product Sell Report</a></li>-->
                  <!--<li><a href="#"><i class="fa fa-angle-right"></i> Items Report</a></li>-->
                  <!--<li><a href="#"><i class="fa fa-angle-right"></i> Purchase & Sale</a></li>-->
                  <!--<li><a href="#"><i class="fa fa-angle-right"></i> Trending Products</a></li>-->
                  <!--<li><a href="#"><i class="fa fa-angle-right"></i> Stock Adjustment Report</a></li>-->
                  <!--<li><a href="#"><i class="fa fa-angle-right"></i> Lot Report</a></li>-->
                  <!--<li><a href="#"><i class="fa fa-angle-right"></i> Stock Report</a></li>-->
                  <!--<li><a href="#"><i class="fa fa-angle-right"></i> Customer Groups Report</a></li>-->
                  <!--<li><a href="#"><i class="fa fa-angle-right"></i> Supplier & Customer Report</a></li>-->
                  <!--<li><a href=""><i class="fa fa-angle-right"></i> Tax Report</a></li>-->
                  <!--<li><a href=""><i class="fa fa-angle-right"></i> Activity Log</a></li>-->

                </ul>

              </li>
@endif
            </ul>
        </div>
    </div>
</div>
@yield('content')