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
                            <i class="fa fa-home"></i>
                            <span>Home</span>
                        </a>
                    </li>


                 <!-- e-commerce-app menu start -->


                {{-- User Management --}}
                <li class="submenu {{ Request::is('users') || Request::is('Userrole')  || Request::is('add_user')  ? 'active' : '' }} ">
                    <a href="#" class=" {{ Request::is('users') || Request::is('Userrole')  || Request::is('add_user') ? 'subdrop' : '' }} ">
                        <i class="fa fa-users"></i> <span> User Management</span>
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


                    </ul>
                </li>

                {{-- system --}}

                <li class="{{ Request::is('Company*') ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-th-list"></i> <span>System</span>
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

                {{-- Master --}}
                <li
                    class="dropdown {{ Request::is('master*')  || Request::is('SalesMan') || Request::is('Area')  || Request::is('Route') || Request::is('Bank_Branch') || Request::is('BankDeltails') || Request::is('MGuarantor') || Request::is('SchemaType')  || Request::is('MColor')  || Request::is('M_Make')  || Request::is('MBrand')  || Request::is('Category') || Request::is('Store') || Request::is('Department') ||Request::is('Suppliers') || Request::is('Item') || Request::is('Category') ? 'active' : '' }} ">
                    <a href="#"><i class="fa fa-sitemap"></i> <span> Master</span> <span class="menu-arrow"></span></a>
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


                {{-- Stock --}}

                <li class="{{ Request::is('stock*') ? 'active' : '' }}">
                    <a href="#"><i class="fa fas fa-database"></i> <span>Stock</span>
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


                <li class=" dropdown {{ Request::is('purchases*') ? 'active' : '' }} ">
                   <a href="#"><i class="fa fa-shopping-cart"></i> <span>Purchases</span>
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

                <li class=" dropdown {{ Request::is('sales*') ? 'active' : '' }} ">
                    <a href="#"><i class="fa fa-th-list"></i> <span>Sales</span>
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



                <li
                 {{-- class="{{ Request::is('PaymentVoucher*') ? 'active' : '' }}||
                {{ Request::is('PaymentVoucher*') ? 'active' : '' }} " --}}
                >
                <a href="#">
                    <i class="fa fa-barcode"></i> <span> Vouchers</span> <span class="menu-arrow"></span></a>
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

                <li class="{{ Request::is('AddExpense*') ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-th-list"></i> <span>Expense</span>
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


                <li class="">
                    <a href="#">
                        <i class="fa fa-credit-card"></i> <span> Banking </span> <span
                            class="menu-arrow"></span></a>
                            <ul style="">
                                <li class="">
                                    <a href="">
                                    <i class="fa fa-angle-right"></i>
                                    Cheque Deposit</a>
                                </li>
                                <li class="">
                                    <a href="">
                                    <i class="fa fa-angle-right"></i>
                                    Issued Cheques </a>
                                </li>
                                <li class="">
                                    <a href="">
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


                {{-- Accounting --}}
                <li class="">
                    <a href="#">
                        <i class="fa fa-table"></i> <span> Accounting</span> <span
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

            <li class="menu-item">
                <a href="javascript:void(0);"  class="menu-link menu-toggle">
                  <i class='menu-icon tf-icons bx bx-cart-alt'></i>
                  <a href="#"><i class="fa fa-chart-area"></i> <span>Reports</span> <span
                    class="menu-arrow"></span></a>
                </a>
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



                  <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                      <div class="text-truncate" data-i18n="Customer">Purchase Report<span
                        class="menu-arrow"></span></div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="{{route("Purchase_wish_sales_report")}}" target ="_ blank" class="menu-link">
                              <div class="text-truncate" data-i18n="All Customers">Item wish Purchase </div>
                            </a>
                        </li>
                      <li class="menu-item">
                        <a href="{{route("Purchasereport")}}" target ="_ blank" class="menu-link">
                          <div class="text-truncate" data-i18n="All Customers">Purchase Summery</div>
                        </a>
                      </li>
                      <li class="menu-item">
                        <a href="{{route('Purchase_detail_report')}}" target ="_ blank" class="menu-link menu-toggle">
                          <div class="text-truncate" data-i18n="Customer Details">Purchase Details</div>
                        </a>
                      </li>
                    </ul>
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
            </ul>
        </div>
    </div>
</div>
@yield('content')