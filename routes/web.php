<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Company;
use App\Models\BankDetails;
use App\Models\BankBranch;
use App\Models\Technician;


use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BankDeltailsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Account_typeController;
use App\Http\Controllers\AccountCategoryController;
use App\Http\Controllers\MBrandController;
use App\Http\Controllers\MColorController;
use App\Http\Controllers\M_MakeController;
use App\Http\Controllers\MBankController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\bankbranchController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\ChartofAccountController;
use App\Http\Controllers\SalesInvoicewithoutVatController;
 use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerChequePaymentReportController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\CashandChequeTransactionController;
use App\Http\Controllers\TrialBalanceController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $data = Company::all();
    return view('welcome')
    ->with("Company", $data);
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/view_users', [App\Http\Controllers\UserController::class, 'viewUsers'])->name('view_users');
Route::get('/delete_user/{id}', [App\Http\Controllers\UserController::class, 'deleteUsers'])->name('delete_user');
Route::get('/edit_user/{id}', [App\Http\Controllers\UserController::class, 'editUsers'])->name('edit_user');

// dashboard routes
Route::get('/users', [App\Http\Controllers\UserController::class, 'showUsers'])->name('users');

Route::get('/add_user', [App\Http\Controllers\UserController::class, 'showAddUser'])->name('add_user');
Route::post('/register_user', [App\Http\Controllers\UserController::class, 'AddUser'])->name('register_user');
Route::get('/roles', [App\Http\Controllers\UserController::class, 'showRoles'])->name('roles');


//Add Customer Route
Route::post('/addCustomer', [App\Http\Controllers\CustomerController::class, 'addCustomer'])->name('addCustomer');
//Edit View Customer Route
Route::get('/master_edit_customers/{id}', [App\Http\Controllers\CustomerController::class, 'indexEdit'])->name('master_edit_customers');
// Show Customers view
Route::get('/master_customers', [App\Http\Controllers\CustomerController::class, 'index'])->name('master_customers');
//Get Customer by Code
Route::post('/getCustomer', [App\Http\Controllers\CustomerController::class, 'getByID'])->name('getCustomer');
//Update Customer by Code
Route::post('/update_customer/{id}', [App\Http\Controllers\CustomerController::class, 'updateCustomer'])->name('update_customer');
//delte customer
Route::get('/delete_customer/{id}', [App\Http\Controllers\CustomerController::class, 'destroy'])->name('delete_customer');

//Add customer ajex
Route::post('/add_customer_ajax', [App\Http\Controllers\CustomerController::class, 'create'])->name('add_customer_ajax');
//delete customer ajex
Route::post('/delete_customer_ajax', [App\Http\Controllers\CustomerController::class, 'delete'])->name('delete_customer_ajax');
//update customer ajex
Route::post('/update_customer_ajax', [App\Http\Controllers\CustomerController::class, 'update'])->name('update_customer_ajax');
//pagination branch ajax
Route::get('/customer_pagination', [App\Http\Controllers\CustomerController::class, 'pagination']);
//search customer ajax
Route::get('/search_customer_ajax', [App\Http\Controllers\CustomerController::class, 'search'])->name('search_customer_ajax');
//get customer ajax
Route::get('/get_customer_ajax', [App\Http\Controllers\CustomerController::class, 'get'])->name('get_customer_ajax');

//get_customer_balance_ajax
Route::get('/get_customer_balance_ajax', [App\Http\Controllers\CustomerController::class, 'getCustomerBalance'])->name('get_customer_balance_ajax');



//show branch details
Route::get('/Company_branchdetails', [App\Http\Controllers\branchdetailsController ::class, 'index'])->name('branchdetails');

//add branch
Route::post('/add_branchdetails', [App\Http\Controllers\branchdetailsController::class, 'add_branch'])->name('add_branch');
//delete branch
Route::get('/delete_branch/{id}', [App\Http\Controllers\branchdetailsController::class, 'destroy'])->name('delete_branch');



// show item condition
Route::get('master_item_condition', [App\Http\Controllers\itemConditionController ::class, 'index'])->name('master_item_condition');
// add item condition
Route::post('/add_itemcondition', [App\Http\Controllers\itemConditionController::class, 'add_itemcondition'])->name('add_itemcondition');
// delete item Condition
Route::get('/delete_itemcondition/{id}', [App\Http\Controllers\itemConditionController::class, 'destroy'])->name('delete_itemcondition');
// add item condition ajax
Route::post('/add_itemcondition_ajax', [App\Http\Controllers\itemConditionController::class, 'create'])->name('add_itemcondition_ajax');
// update item condition ajax
Route::post('/update_condition_ajax', [App\Http\Controllers\itemConditionController::class, 'update'])->name('update_condition_ajax');

// User Role
Route::get('/Userrole', [App\Http\Controllers\UserRoleController::class, 'index'])->name('Userrole');
Route::post('role_store', [App\Http\Controllers\UserRoleController::class, 'role_store']);
Route::post('role_edit', [App\Http\Controllers\UserRoleController::class, 'role_edit']);
Route::post('role_delete', [App\Http\Controllers\UserRoleController::class, 'role_delete']);



// delete branch
Route::post('/delete_condition_ajax', [App\Http\Controllers\itemConditionController::class, 'delete'])->name('delete_condition_ajax');

//Accont Category
Route::get('account_category', [AccountCategoryController::class, 'index'])->name('account_category');
Route::post('/add_Account_Category_ajax', [App\Http\Controllers\AccountCategoryController::class, 'createCateAccount'])->name('add_Account_Category_ajax');
Route::post('/update_Account_Category_ajax', [App\Http\Controllers\AccountCategoryController::class, 'updateCateAccount'])->name('update_Account_Category_ajax');
Route::post('/delete_Account_Category_ajax', [App\Http\Controllers\AccountCategoryController::class, 'deleteCateAccount'])->name('delete_Account_Category_ajax');

//Account Type
Route::get('account_type', [Account_typeController::class, 'index'])->name('account_type');
Route::post('add_Account_Type_ajax', [Account_typeController::class, 'createAccountype'])->name('add_Account_Type_ajax');
Route::post('update_Account_Type_ajax', [Account_typeController::class, 'updateAccountype'])->name('update_Account_Type_ajax');
Route::post('delete_Account_Type_ajax', [Account_typeController::class, 'deleteAccountype'])->name('delete_Account_Type_ajax');

// Chart Of Account
Route::get('chartofaccount', [ChartofAccountController::class, 'index'])->name('chartofaccount');
Route::post('ChartofAccount_store', [ChartofAccountController::class, 'ChartofAccountStore'])->name('ChartofAccount_store');
Route::post('ChartofAccount_edit', [ChartofAccountController::class, 'ChartofAccountEdit'])->name('ChartofAccount_edit');
Route::post('ChartofAccount_delete', [ChartofAccountController::class, 'ChartofAccountDelete'])->name('ChartofAccount_delete');


Route::get('PaymentVoucher', [App\Http\Controllers\PaymentVoucherController::class, 'index'])->name('PaymentVoucher');
Route::post('/addPaymentVoucher', [App\Http\Controllers\PaymentVoucherController::class,'addPaymentVoucher'])->name('addPaymentVoucher');
Route::post('/UpdatePaymentVoucher', [App\Http\Controllers\PaymentVoucherController::class,'UpdatePaymentVoucher'])->name('UpdatePaymentVoucher');
Route::post('/DeletePaymentVoucher', [App\Http\Controllers\PaymentVoucherController::class,'DeletePaymentVoucher'])->name('DeletePaymentVoucher');

//Daily Transactions / Cash Book
Route::get('daily-transactions', [App\Http\Controllers\DailyTransactionController::class, 'index'])->name('daily.transactions');
Route::get('cash-book', [App\Http\Controllers\CashBookController::class, 'index'])->name('cash.book');
Route::get('/show_voucher_ajax',  [App\Http\Controllers\PaymentVoucherController::class, 'GetVoucher'])->name('show_voucher_ajax');
Route::get('/show_dr_voucher_ajax',  [App\Http\Controllers\PaymentVoucherController::class, 'GetDRVoucher'])->name('show_dr_voucher_ajax');

///PettyCash
Route::get('/PettyCash', [App\Http\Controllers\PettyCashController::class, 'index'])->name('PettyCash');
Route::post('/addPettycash', [App\Http\Controllers\PettyCashController::class,'addPettycash'])->name('addPettycash');
Route::post('/UpdatePettycash', [App\Http\Controllers\PettyCashController::class,'UpdatePettycash'])->name('UpdatePettycash');
Route::post('/DeletePettycash', [App\Http\Controllers\PettyCashController::class,'DeletePettycash'])->name('DeletePettycash');

// Show Stock_Adjestment
Route::get('/stock_adjestment', [App\Http\Controllers\StockAdjestmentController::class, 'index'])->name('stock_adjestment');

// Show Damage Stock
Route::get('/stock_damage', [App\Http\Controllers\DamageStockController::class, 'index'])->name('stock_damage');



// Show job Sheet page
Route::get('/repair_jobsheet', [App\Http\Controllers\JobSheetsController::class, 'index'])->name('repair_jobsheet');
// create_jobsheet
Route::post('/create_jobsheet', [App\Http\Controllers\JobSheetsController::class, 'store'])->name('create_jobsheet');

// show all jobs
Route::get('/repair_jobs', [App\Http\Controllers\AllJobsController::class, 'index'])->name('repair_jobs');
// delete jobs ajax
Route::post('/delete_jobs_ajax', [App\Http\Controllers\AllJobsController::class, 'destroy'])->name('delete_jobs_ajax');
//update job ajex
Route::post('/update_job_ajax', [App\Http\Controllers\AllJobsController::class, 'update'])->name('update_job_ajax');
//pagination branch ajax
Route::get('/jobs_pagination', [App\Http\Controllers\AllJobsController::class, 'pagination']);
//search jobs ajax
Route::get('/search_jobs_ajax', [App\Http\Controllers\AllJobsController::class, 'search'])->name('search_jobs_ajax');


// Show repair jobs return
Route::get('/repair_jobs_return', [App\Http\Controllers\JobsReturnController::class, 'index'])->name('repair_jobs_return');
// add_job return_ajax
Route::post('/add_job_return_ajax', [App\Http\Controllers\JobsReturnController::class, 'create'])->name('add_job_return_ajax');



// repair_jobs_return
//Route::get('/repair_jobs_return', [App\Http\Controllers\JobsReturnController::class, 'jobsReturn'])->name('repair_jobs_return');



// Show rapair page
Route::get('/repair', [App\Http\Controllers\RepairController::class, 'index'])->name('repair');



// Show repair invoice page
Route::get('/sales_invoice', [App\Http\Controllers\InvoiceController::class, 'index'])->name('sales_invoice');
// add_invoice
Route::post('/add_invoice', [App\Http\Controllers\InvoiceController::class, 'createInvoice'])->name('add_invoice');

//get job ajax
Route::get('/get_job_ajax', [App\Http\Controllers\InvoiceController::class, 'get'])->name('get_job_ajax');
// add_invoice_ajax
Route::post('/add_invoice_ajax', [App\Http\Controllers\InvoiceController::class, 'create'])->name('add_invoice_ajax');
// delete_invoice_ajax
Route::post('/delete_invoice_ajax', [App\Http\Controllers\InvoiceController::class, 'destroy'])->name('delete_invoice_ajax');
// index for sales_create_invoice
Route::get('/sales_create_invoice', [App\Http\Controllers\InvoiceController::class, 'indexInvoice'])->name('sales_create_invoice');

// show_select_category_item_ajax
Route::get('/show_select_category_item_ajax', [App\Http\Controllers\InvoiceController::class, 'setItemsCode'])->name('show_select_category_item_ajax');
// show_select_item_description_ajax
Route::get('/show_select_item_description_ajax', [App\Http\Controllers\InvoiceController::class, 'setItemDescription'])->name('show_select_item_description_ajax');

//search and find_invoice
Route::get('/find_invoice', [App\Http\Controllers\InvoiceController::class, 'findInvoice'])->name('find_invoice');
//search and find invoice
Route::get('/find_invoice_customer_data', [App\Http\Controllers\InvoiceController::class, 'findInvoiceCustomerData'])->name('find_invoice_customer_data');
//check_and_get_item_has_serial_ajax
Route::get('/check_and_get_item_has_serial_ajax', [App\Http\Controllers\InvoiceController::class,'isSerial'])->name('check_and_get_item_has_serial_ajax');
Route::get('/get_customer',  [App\Http\Controllers\InvoiceController::class, 'GetCustomer'])->name('get_customer');
//search customer ajax
Route::get('/search_customer_invoice_ajax', [App\Http\Controllers\InvoiceController::class, 'searchCustomer'])->name('search_customer_invoice_ajax');
//print_sales_invoice_ajax
Route::get('/print_sales_invoice_ajax', [App\Http\Controllers\InvoiceController::class, 'print'])->name('print_sales_invoice_ajax');


//search and find_invoice_stock_transfer
Route::get('/find_stock_transfer_invoice', [App\Http\Controllers\StockTransferController::class, 'findInvoice'])->name('find_stock_transfer_invoice');
//search and find invoice_stock_transfer
Route::get('/find_stock_transfer_invoice_customer_data', [App\Http\Controllers\StockTransferController::class, 'findInvoiceCustomerData'])->name('find_stock_transfer_invoice_customer_data');



//Reports
Route::get('/sales_report', [App\Http\Controllers\SalereportController::class, 'index'])->name('sales_report');
//daily report
Route::get('/daily_report', [App\Http\Controllers\DailyreportController::class,'index'])->name('daily_report');

//get_weight_ajax
// Route::get('/get_weight_ajax', [App\Http\Controllers\GetWeightController::class,'index'])->name('get_weight_ajax');

//get_serial_port_weight_ajax
Route::get('/get_weight_ajax', [App\Http\Controllers\SerialPortReadController::class,'getWeight'])->name('get_weight_ajax');



// Item
Route::get('Item', [ItemController::class, 'index'])->name('Item');
//add branch ajax
Route::post('/addItem', [App\Http\Controllers\ItemController::class, 'create'])->name('add_Item_ajax');
//update branch ajax
Route::post('/updateItem', [App\Http\Controllers\ItemController::class, 'update'])->name('update_Item_ajax');
//delete branch ajax
Route::post('/deleteItem', [App\Http\Controllers\ItemController::class, 'delete'])->name('delete_Item_ajax');
//Show Select Branch
Route::get('/show_select_Branch_ajax', [App\Http\Controllers\ItemController::class, 'getUser'])->name('show_select_Branch_ajax');
// search_items_ajax
Route::get('/search_items_ajax', [App\Http\Controllers\ItemController::class, 'search'])->name('search_items_ajax');
// search_items_purchase_price_ajax
Route::get('/search_items_purchase_price_ajax', [App\Http\Controllers\ItemController::class, 'searchPurchasePrice'])->name('search_items_purchase_price_ajax');


// Usercontroller
Route::post('/register_user', [App\Http\Controllers\UserController::class, 'AddUser'])->name('register_user');
Route::post('/delete_user_ajax', [App\Http\Controllers\UserController::class, 'delete'])->name('delete_user_ajax');
Route::post('/update_user_ajax', [App\Http\Controllers\UserController::class, 'update'])->name('update_user_ajax');
Route::get('/searchUser', [App\HttpDetpartmentsearch\Controllers\UserController::class, 'Usersearch'])->name('search_User_ajax');
Route::get('/search_user_ajax', [App\Http\Controllers\UserController::class, 'search'])->name('search_user_ajax');
Route::get('/show_select_up_user_ajax', [App\Http\Controllers\UserController::class, 'getUser'])->name('show_select_up_user_ajax');
Route::get('/add_user', [App\Http\Controllers\UserController::class, 'showAddUser'])->name('add_user');
Route::get('/show_select_user_ajax', [App\Http\Controllers\UserController::class, 'getUser'])->name('show_select_user_ajax');


// Show Stock Transfer
Route::get('stock_transfer', [App\Http\Controllers\StockTransferController::class, 'index'])->name('stock_transfer');

Route::get('/show_Branch_Details_ajax',  [App\Http\Controllers\StockTransferController::class, 'GetBranchDeatails'])->name('show_Branch_Details_ajax');

Route::post('/add_stock_transfer', [App\Http\Controllers\StockTransferController::class, 'createtockTransfer'])->name('add_stock_transfer');
//get job ajax
Route::get('/get_job_ajax', [App\Http\Controllers\StockTransferController::class, 'get'])->name('get_job_ajax');
// add_invoice_ajax
Route::post('/add_stock_transfer_ajax', [App\Http\Controllers\StockTransferController::class, 'create'])->name('add_stock_transfer_ajax');
// delete_Purchases_ajax
Route::post('/delete_stock_transfer_ajax', [App\Http\Controllers\StockTransferController::class, 'destroy'])->name('delete_stock_transfer_ajax');
// index for sales_create_invoice
Route::get('/sales_create_stock_transfer', [App\Http\Controllers\StockTransferController::class, 'indexPurchases'])->name('sales_create_stock_transfer');

// show_select_category_item_ajax
Route::get('/show_select_category_item_ajax', [App\Http\Controllers\StockTransferController::class, 'setItemsCode'])->name('show_select_category_item_ajax');
// show_select_item_description_ajax
Route::get('/show_select_item_description_ajax', [App\Http\Controllers\StockTransferController::class, 'setItemDescription'])->name('show_select_item_description_ajax');
// search_items_ajax
Route::get('/search_items_ajax_stock_t', [App\Http\Controllers\StockTransferController::class, 'search'])->name('search_items_ajax_stock_t');
//get customer ajax
Route::get('/get_branchdetails_ajax', [App\Http\Controllers\branchdetailsController::class, 'getBranch'])->name('get_branchdetails_ajax');


// 2023.10.25
// Show repair invoice page
Route::get('/purchases', [App\Http\Controllers\PurchasesController::class, 'index'])->name('purchases');
// add_invoice
Route::post('/add_Purchases', [App\Http\Controllers\PurchasesController::class, 'createPurchases'])->name('add_Purchases');
//create_recall_purchase
Route::post('/create_recall_purchase', [App\Http\Controllers\PurchasesController::class, 'createRecallPurchases'])->name('create_recall_purchase');

//search and find_purchase_invoice
Route::get('/find_purchase_invoice', [App\Http\Controllers\PurchasesController::class, 'findInvoice'])->name('find_purchase_invoice');
//search and find invoice
Route::get('/find_purchase_invoice_customer_data', [App\Http\Controllers\PurchasesController::class, 'findInvoiceCustomerData'])->name('find_purchase_invoice_customer_data');

//search and find_purchase_invoice
Route::get('/find_sales_purchase_invoice', [App\Http\Controllers\PurchasesController::class, 'findSalesInvoice'])->name('find_sales_purchase_invoice');
//search and find invoice
Route::get('/find_sales_purchase_invoice_customer_data', [App\Http\Controllers\PurchasesController::class, 'findSalesInvoiceCustomerData'])->name('find_sales_purchase_invoice_customer_data');

//get job ajax
Route::get('/get_job_ajax', [App\Http\Controllers\PurchasesController::class, 'get'])->name('get_job_ajax');
// add_invoice_ajax
Route::post('/add_Purchases_ajax', [App\Http\Controllers\PurchasesController::class, 'create'])->name('add_Purchases_ajax');
// delete_Purchases_ajax
Route::post('/delete_Purchases_ajax', [App\Http\Controllers\PurchasesController::class, 'destroy'])->name('delete_Purchases_ajax');
// index for sales_create_invoice
Route::get('/sales_create_Purchases', [App\Http\Controllers\PurchasesController::class, 'indexPurchases'])->name('sales_create_Purchases');

// show_select_category_item_ajax
Route::get('/show_select_category_item_ajax', [App\Http\Controllers\PurchasesController::class, 'setItemsCode'])->name('show_select_category_item_ajax');
// show_select_item_description_ajax
Route::get('/show_select_item_description_ajax', [App\Http\Controllers\PurchasesController::class, 'setItemDescription'])->name('show_select_item_description_ajax');
//check_item_has_serial_ajax
Route::get('/check_item_has_serial_ajax', [App\Http\Controllers\PurchasesController::class,'isSerial'])->name('check_item_has_serial_ajax');


// get stock report
Route::get('/stock_report', [App\Http\Controllers\StockReportController::class, 'index'])->name('stock_report');
//filter_stock_by_date
Route::get('/filter_stock_by_date', [App\Http\Controllers\StockReportController::class, 'filter'])->name('filter_stock_by_date');

//bincard
Route::get('/bin_card', [App\Http\Controllers\BinCardController::class, 'index'])->name('bin_card');
//get_bin_card_report
Route::get('/get_bin_card_report', [App\Http\Controllers\BinCardController::class, 'get'])->name('get_bin_card_report');

// Purchases Return
Route::get('/purchases_return', [App\Http\Controllers\PurchasesReturnController::class, 'index'])->name('purchases_return');
Route::post('/add_purchases_return', [App\Http\Controllers\PurchasesReturnController::class, 'createPurchasesReturn'])->name('add_purchases_return');


// 2023.11.14
Route::get('/stock_open', [App\Http\Controllers\OpeningStockController::class, 'index'])->name('stock_open');
// add_opening_stock
Route::post('add_openingStock', [App\Http\Controllers\OpeningStockController::class, 'createOpeningStock'])->name('add_openingStock');
Route::get('/show_select_Store_ajax',  [App\Http\Controllers\OpeningStockController::class, 'GetStore'])->name('show_select_Store_ajax');

// Master Form
// Category
Route::get('Category', [CategoryController::class, 'index'])->name('Category');
//add Category ajax
Route::post('/addCategory', [App\Http\Controllers\CategoryController::class, 'create'])->name('add_Category_ajax');
//update Category ajax
Route::post('/updateCategory', [App\Http\Controllers\CategoryController::class, 'update'])->name('update_Category_ajax');
//delete Category ajax
Route::post('/deleteCategory', [App\Http\Controllers\CategoryController::class, 'delete'])->name('delete_Category_ajax');
//Show Select Branch
Route::get('/show_select_Branch_ajax', [App\Http\Controllers\CategoryController::class, 'getUser'])->name('show_select_Branch_ajax');
//search branch ajax
// Route::get('/searchDepartmentdetails', [App\Http\Controllers\DepartmentController::class, 'search'])->name('search_Department_ajax');
// //pagination branch ajax
// Route::get('/Department_pagination', [App\Http\Controllers\DepartmentController::class, 'pagination']);

// Store
Route::get('Store', [StoreController::class, 'index'])->name('Store');
Route::post('Store_store', [StoreController::class, 'store_Item']);
Route::post('Store_edit', [StoreController::class, 'edit_store']);
Route::post('Store_delete', [StoreController::class, 'destroy_store']);


//Bank
Route::get('BankDeltails', [BankController::class, 'index'])->name('BankDeltails');
Route::post('Bank_store', [BankController::class, 'Bank_store']);
Route::post('Bank_edit', [BankController::class, 'Bank_edit']);
Route::post('Bank_delete', [BankController::class, 'Bank_delete']);

//Account (company bank accounts used to issue/deposit cheques)
Route::get('ChequeBanks', [App\Http\Controllers\ChequeBankController::class, 'index'])->name('ChequeBanks');
Route::post('ChequeBank_store', [App\Http\Controllers\ChequeBankController::class, 'store'])->name('ChequeBank_store');
Route::post('ChequeBank_toggle', [App\Http\Controllers\ChequeBankController::class, 'toggleActive'])->name('ChequeBank_toggle');

//Banking > Issued Cheques (supplier cheques prepared but not yet handed over)
Route::get('issued-cheques', [App\Http\Controllers\IssuedChequeController::class, 'index'])->name('issued.cheques');
Route::post('issued-cheques/mark-issued', [App\Http\Controllers\IssuedChequeController::class, 'markIssued'])->name('issued.cheques.mark');

//Banking > Cheque Deposit (both supplier-issued and customer-received cheques)
Route::get('cheque-deposit', [App\Http\Controllers\ChequeDepositController::class, 'index'])->name('cheque.deposit');
Route::post('cheque-deposit/process', [App\Http\Controllers\ChequeDepositController::class, 'deposit'])->name('cheque.deposit.process');

//Banking > Cheque Return (bounced supplier and customer cheques)
Route::get('cheque-return', [App\Http\Controllers\ChequeReturnController::class, 'index'])->name('cheque.return');
Route::post('cheque-return/process', [App\Http\Controllers\ChequeReturnController::class, 'returnCheque'])->name('cheque.return.process');


Route::get('/SchemaType', [App\Http\Controllers\SchemaController::class, 'index'])->name('SchemaType');
Route::post('/addSchemaType', [App\Http\Controllers\SchemaController::class, 'createSchemaType'])->name('add_SchemaType_ajax');
Route::post('/updateSchemaType', [App\Http\Controllers\SchemaController::class, 'updateSchemaType'])->name('update_SchemaType_ajax');
Route::post('/deleteSchema', [App\Http\Controllers\SchemaController::class, 'deleteSchema'])->name('delete_Schema_ajax');


Route::get('Department', [DepartmentController::class, 'index'])->name('Department');
//add branch ajax
Route::post('/addDepartment', [App\Http\Controllers\DepartmentController::class, 'create'])->name('add_Department_ajax');
//update branch ajax
Route::post('/updateDepartment', [App\Http\Controllers\DepartmentController::class, 'update'])->name('update_Department_ajax');
//delete branch ajax
Route::post('/deleteDepartment', [App\Http\Controllers\DepartmentController::class, 'delete'])->name('delete_Department_ajax');
//Show Select Branch
Route::get('/show_select_Branch_ajax', [App\Http\Controllers\DepartmentController::class, 'getUser'])->name('show_select_Branch_ajax');
//search branch ajax
// Route::get('/searchDepartmentdetails', [App\Http\Controllers\DepartmentController::class, 'search'])->name('search_Department_ajax');
// //pagination branch ajax
// Route::get('/Department_pagination', [App\Http\Controllers\DepartmentController::class, 'pagination']);

// MBrand
Route::get('/MBrand', [App\Http\Controllers\MBrandController::class, 'index'])->name('MBrand');
Route::post('Brand_store', [MBrandController::class, 'store_Brand']);
Route::post('Brand_edit', [MBrandController::class, 'edit_Brand']);
Route::post('Brand_delete', [MBrandController::class, 'destroy_Brand']);

Route::get('/MColor', [App\Http\Controllers\MColorController::class, 'index'])->name('MColor');
Route::post('store_Color', [MColorController::class, 'store_Color']);
Route::post('Color_edit', [MColorController::class, 'edit_Item']);
Route::post('Color_delete', [MColorController::class, 'destroy_Item']);

Route::get('/M_Make', [App\Http\Controllers\M_MakeController::class, 'index'])->name('M_Make');
Route::post('Make_store', [M_MakeController::class, 'store_Make']);
Route::post('Make_edit', [M_MakeController::class, 'edit_Make']);
Route::post('Make_delete', [M_MakeController::class, 'destroy_Make']);


// add_Route_ajax
Route::get('/Route', [App\Http\Controllers\MRouteController::class, 'index'])->name('Route');
Route::post('/add_Route_ajax', [App\Http\Controllers\MRouteController::class, 'AddRoute'])->name('add_Route_ajax');
Route::post('/update_Route_ajax', [App\Http\Controllers\MRouteController::class, 'UpdateRoute'])->name('update_Route_ajax');
Route::post('/delete_Route_ajax', [App\Http\Controllers\MRouteController::class, 'DeleteRoute'])->name('delete_Route_ajax');

// add_Route_ajax
Route::get('/Area', [App\Http\Controllers\MAreaController::class, 'index'])->name('Area');
Route::post('/add_Area_ajax', [App\Http\Controllers\MAreaController::class, 'AddArea'])->name('add_Area_ajax');
Route::post('/update_Area_ajax', [App\Http\Controllers\MAreaController::class, 'UpdateArea'])->name('update_Area_ajax');
Route::post('/delete_Area_ajax', [App\Http\Controllers\MAreaController::class, 'DeleteArea'])->name('delete_Area_ajax');

// add_Route_ajax
Route::get('/SalesMan', [App\Http\Controllers\MSalesmanController::class, 'index'])->name('SalesMan');
Route::post('/add_SalesMan_ajax', [App\Http\Controllers\MSalesmanController::class, 'createSalesMan'])->name('add_SalesMan_ajax');
Route::post('/update_SalesMan_ajax', [App\Http\Controllers\MSalesmanController::class, 'updateSalesMan'])->name('update_SalesMan_ajax');
Route::post('/delete_SalesMan_ajax', [App\Http\Controllers\MSalesmanController::class, 'deleteSalesMan'])->name('delete_SalesMan_ajax');
Route::get('/search_SalesMan_ajax', [App\Http\Controllers\MSalesmanController::class, 'searchSalesMan'])->name('search_SalesMan_ajax');

// MGuarantor
Route::get('/MGuarantor', [App\Http\Controllers\MGuarantorController::class, 'index'])->name('MGuarantor');
//Add MGuarantor ajex
Route::post('/add_guarantor_ajax', [App\Http\Controllers\MGuarantorController::class, 'create'])->name('add_guarantor_ajax');
//delete MGuarantor ajex
Route::post('/delete_guarantor_ajax', [App\Http\Controllers\MGuarantorController::class, 'delete'])->name('delete_guarantor_ajax');
//update MGuarantor ajex
Route::post('/update_guarantor_ajax', [App\Http\Controllers\MGuarantorController::class, 'update'])->name('update_guarantor_ajax');
//search MGuarantor ajax
Route::get('/search_guarantor_ajax', [App\Http\Controllers\MGuarantorController::class, 'search'])->name('search_guarantor_ajax');
//get MGuarantor ajax
Route::get('/get_guarantor_ajax', [App\Http\Controllers\MGuarantorController::class, 'get'])->name('get_guarantor_ajax');
//get_guarantor_1_data_using_code_ajax
Route::get('/get_guarantor_1_data_using_code_ajax', [App\Http\Controllers\MGuarantorController::class, 'getGuarantor1Data'])->name('get_guarantor_1_data_using_code_ajax');
//get_guarantor_2_data_using_code_ajax
Route::get('/get_guarantor_2_data_using_code_ajax', [App\Http\Controllers\MGuarantorController::class, 'getGuarantor2Data'])->name('get_guarantor_2_data_using_code_ajax');


//add branch ajax
Route::post('/addBranchdetails', [App\Http\Controllers\branchdetailsController::class, 'create'])->name('add_branch_ajax');
//update branch ajax
Route::post('/updateBranchdetails', [App\Http\Controllers\branchdetailsController::class, 'update'])->name('update_branch_ajax');
//delete branch ajax
Route::post('/deleteBranchdetails', [App\Http\Controllers\branchdetailsController::class, 'delete'])->name('delete_branch_ajax');
//search branch ajax
Route::get('/searchBranchdetails', [App\Http\Controllers\branchdetailsController::class, 'search'])->name('search_branch_ajax');
//pagination branch ajax
Route::get('/branch_pagination', [App\Http\Controllers\branchdetailsController::class, 'pagination']);


// Company
Route::get('/Company', [App\Http\Controllers\CompanyController::class, 'index'])->name('Company');
Route::post('Companystore', [CompanyController::class, 'Companystore']);
Route::post('Companyedit', [CompanyController::class, 'Companyedit']);
Route::post('Companydelete', [CompanyController::class, 'Companydestroy']);


//Item
Route::get('Item', [ItemController::class, 'index'])->name('Item');
Route::post('Itemstore', [ItemController::class, 'Itemstore']);
Route::post('Itemedit', [ItemController::class, 'Itemedit']);
Route::post('Itemdelete', [ItemController::class, 'Itemdelete']);

//get customer ajax
Route::get('/get_Item_ajax', [App\Http\Controllers\ItemController::class, 'get'])->name('get_Item_ajax');

// Technician
Route::get('/Technician', [App\Http\Controllers\TechnicianController::class, 'index'])->name('Technician');
//Add Technician ajex
Route::post('/add_Technician_ajax', [App\Http\Controllers\TechnicianController::class, 'create'])->name('add_Technician_ajax');
//delete Technician ajex
Route::post('/delete_Technician_ajax', [App\Http\Controllers\TechnicianController::class, 'delete'])->name('delete_Technician_ajax');
//update Technician ajex
Route::post('/update_Technician_ajax', [App\Http\Controllers\TechnicianController::class, 'update'])->name('update_Technician_ajax');
//search Technician ajax
Route::get('/search_Technician_ajax', [App\Http\Controllers\TechnicianController::class, 'search'])->name('search_Technician_ajax');



// Suppliers
Route::get('/Suppliers', [App\Http\Controllers\SuppliersController::class, 'index'])->name('Suppliers');
//Add Suppliers ajex
Route::post('/add_Suppliers_ajax', [App\Http\Controllers\SuppliersController::class, 'create'])->name('add_Suppliers_ajax');
//delete Suppliers ajex
Route::post('/delete_Suppliers_ajax', [App\Http\Controllers\SuppliersController::class, 'delete'])->name('delete_Suppliers_ajax');
//update Suppliers ajex
Route::post('/update_Suppliers_ajax', [App\Http\Controllers\SuppliersController::class, 'update'])->name('update_Suppliers_ajax');
//search Suppliers ajax
Route::get('/search_Suppliers_ajax', [App\Http\Controllers\SuppliersController::class, 'search'])->name('search_Suppliers_ajax');
//get supplier ajax
Route::get('/get_supplier_ajax', [App\Http\Controllers\SuppliersController::class, 'get'])->name('get_supplier_ajax');

//bankbranch
Route::get('Bank_Branch', [bankbranchController::class, 'index'])->name('Bank_Branch');
Route::post('mainstore', [bankbranchController::class, 'mainstore']);
Route::post('mainedit', [bankbranchController::class, 'mainedit']);
Route::post('maindelete', [bankbranchController::class, 'maindestroy']);



Route::get('/testing', [App\Http\Controllers\testingController::class, 'index'])->name('testing');



//Hire Purchasing section
Route::get('/hire_purchase_opening', [App\Http\Controllers\OpeningHirePurchaseController::class, 'index'])->name('hire_purchase_opening');
// create opening hire purchase
Route::post('/create_opening_hire_purchase', [App\Http\Controllers\OpeningHirePurchaseController::class, 'create'])->name('create_opening_hire_purchase');
//get_customer_data_using_code_ajax
Route::get('/get_customer_data_using_code_ajax', [App\Http\Controllers\OpeningHirePurchaseController::class, 'getCustomerData'])->name('get_customer_data_using_code_ajax');
// show_select_schema_details_ajax
Route::get('/show_select_schema_details_ajax', [App\Http\Controllers\OpeningHirePurchaseController::class, 'getSchemaDetails'])->name('show_select_schema_details_ajax');
// print_open_hp_invoice_ajax
Route::get('/print_open_hp_invoice_ajax', [App\Http\Controllers\OpeningHirePurchaseController::class, 'print'])->name('print_open_hp_invoice_ajax');


//HirePurchase
Route::get('/hire_purchase', [App\Http\Controllers\HirePurchaseController::class, 'index'])->name('hire_purchase');
//create hire purchase
Route::post('/create_hire_purchase', [App\Http\Controllers\HirePurchaseController::class, 'create'])->name('create_hire_purchase');
//search and find hp Invoice
Route::get('/find_hp_invoice', [App\Http\Controllers\HirePurchaseController::class, 'findHPInvoice'])->name('find_hp_invoice');
//search and find invoice
Route::get('/find_hp_invoice_customer_data', [App\Http\Controllers\HirePurchaseController::class, 'findHPInvoiceSumData'])->name('find_hp_invoice_customer_data');
// print_hp_invoice_ajax
Route::get('/print_hp_invoice_ajax', [App\Http\Controllers\HirePurchaseController::class, 'print'])->name('print_hp_invoice_ajax');



//OpeningHirepurchaseReportController
Route::get('/reports.OpeningHirepurchaseReport', [App\Http\Controllers\OpeningHirepurchaseReportController::class, 'index'])->name('OpeningHirepurchaseReport');
// OpeningHirepurchaseReportController
Route::get('/reports.OpeningHirepurchaseSumReport', [App\Http\Controllers\OpeningHirepurchaseSumReportController::class, 'index'])->name('OpeningHirepurchaseSumReport');


//HirepurchaseReportController
Route::get('/reports.HirepurchaseDetailsReport', [App\Http\Controllers\HirepurchaseDetailsReportController::class, 'index'])->name('HirepurchaseDetailsReport');
//HirepurchaseReportController
Route::get('/reports.HirepurchaseSumReport', [App\Http\Controllers\HirepurchaseSumReportController::class, 'index'])->name('HirepurchaseSumReport');


// Show Sales Quatation
Route::get('/sales_quatation', [App\Http\Controllers\SalesQuatationController::class, 'index'])->name('sales_quatation');
Route::post('/add_sales_quatation', [App\Http\Controllers\SalesQuatationController::class, 'createQuatation'])->name('add_sales_quatation');

// AddExpense
Route::get('/AddExpense', [App\Http\Controllers\AddExpenseController::class, 'index'])->name('AddExpense');
Route::post('/AddExpense', [App\Http\Controllers\AddExpenseController::class, 'storeExpense'])->name('AddExpense');

// CashOut
Route::get('/CashOut', [App\Http\Controllers\CashOutController::class, 'index'])->name('CashOut');
Route::post('/store-form', [App\Http\Controllers\CashOutController::class, 'storeCashOut'])->name('store-form');


// Reports
//Purchase Summary
Route::get('/Purchasereport', [App\Http\Controllers\PurchasereportController::class,'index'])->name('Purchasereport');
// Item Purchase Order
Route::get('/Purchase_detail_report', [App\Http\Controllers\PurchasedetailreportController::class,'index'])->name('Purchase_detail_report');
// Invoice Details
Route::get('/Invoice_detail_report', [App\Http\Controllers\InvoicedetailreportController::class,'index'])->name('Invoice_detail_report');
//Reports
Route::get('/reports.sales_report', [App\Http\Controllers\SalereportController::class, 'index'])->name('sales_report');
//daily report
Route::get('/daily_report', [App\Http\Controllers\DailyreportController::class,'index'])->name('daily_report');
//Purchase Return
Route::get('/reports.Purchase_return_report', [App\Http\Controllers\PurchasereturnreportController::class,'index'])->name('Purchase_return_report');
//Purchase Order
Route::get('/reports.Purchase_order_report', [App\Http\Controllers\PurchaseorderreportController::class,'index'])->name('Purchase_order_report');
// Item Purchase Order
Route::get('/reports.Item_purchase_report', [App\Http\Controllers\ItemPurchasereportController::class,'index'])->name('Item_purchase_report');

// // Invoice Details
// Route::get('/reports.Purchase_detail_report', [App\Http\Controllers\InvoicedetailreportController::class,'index'])->name('Purchase_detail_report');

//Purchase detail report
Route::get('/reports.Purchase_detail_report', [App\Http\Controllers\PurchasedetailreportController::class,'index'])->name('Purchase_detail_report');


// Invoice Details
Route::get('/reports.ZerostockReport', [App\Http\Controllers\ZerostockreportController::class,'index'])->name('ZerostockReport');
//SalessummaryreportController
Route::get('/reports.Sales_summary_report', [App\Http\Controllers\SalessummaryreportController::class,'index'])->name('Sales_summary_report');
//Purchase Summary
Route::get('/reports.Purchase_Summary_report', [App\Http\Controllers\PurchasereportController::class,'index'])->name('Purchase_Summary_report');

//Purchasing Report (consolidated — replaces the summary/detail/item-wish reports above)
Route::get('/purchasing-report', [App\Http\Controllers\PurchasingReportController::class, 'index'])->name('purchasing.report');
Route::get('/purchasing-report/detail', [App\Http\Controllers\PurchasingReportController::class, 'detail'])->name('purchasing.report.detail');
//Purchase detail report
Route::get('/reports.Purchase_Detail_report', [App\Http\Controllers\PurchasedetailreportController::class,'index'])->name('Purchase_Detail_report');
//PurchaseorderdetailreportController
Route::get('/reports.Purchase_order_details_report', [App\Http\Controllers\PurchaseorderdetailreportController::class,'index'])->name('Purchase_order_details_report');
//ItemdetailreportControler
Route::get('/reports.Item_detail_report', [App\Http\Controllers\ItemdetailreportControler::class,'index'])->name('Item_detail_report');
// get stock report
Route::get('/stock_report', [App\Http\Controllers\StockReportController::class, 'index'])->name('stock_report');
//filter_stock_by_date
Route::get('/filter_stock_by_date', [App\Http\Controllers\StockReportController::class, 'filter'])->name('filter_stock_by_date');




// Purchases Return
Route::get('/purchases_order', [App\Http\Controllers\PurshasesOrderController::class, 'index'])->name('purchases_order');
Route::post('/add_purchases_order', [App\Http\Controllers\PurshasesOrderController::class, 'createPurchasesorder'])->name('add_purchases_order');
//Purchase Report
Route::get('/Purchasereport', [App\Http\Controllers\PurchasereportController::class,'index'])->name('Purchasereport');



// Show Sales Return
Route::get('/sales_return', [App\Http\Controllers\SalesReturnController::class, 'index'])->name('sales_return');
Route::post('/add_salesReturn', [App\Http\Controllers\SalesReturnController::class, 'createSalesReturn'])->name('add_salesReturn');


//index of installment payment
Route::get('/hire_purchase_installment', [App\Http\Controllers\InstallmentPaymentController::class, 'index'])->name('hire_purchase_installment');
//get_installemnt_data_using_no_ajax
Route::get('/get_installemnt_data_using_no_ajax', [App\Http\Controllers\InstallmentPaymentController::class, 'findInvoice'])->name('get_installemnt_data_using_no_ajax');
//create_paying_installment
Route::post('/create_paying_installment', [App\Http\Controllers\InstallmentPaymentController::class, 'create'])->name('create_paying_installment');


//index of Early Settlement  payment
Route::get('/hire_purchase_early_settlement', [App\Http\Controllers\EarlySettlementController::class, 'index'])->name('hire_purchase_early_settlement');


//Stockvaluationreport
Route::get('/reports.Stock_valuation_report', [App\Http\Controllers\StockvaluationreportControler::class,'index'])->name('Stock_valuation_report');
//filter_stock_by_date
Route::get('/filter_stock_by_FilterStoctValuation', [App\Http\Controllers\StockvaluationreportControler::class, 'FilterStoctValuation'])->name('filter_stock_by_FilterStoctValuation');



// Dashboard
Route::get('/AdminDashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('AdminDashboard');
Route::get('/SalesmanDashboard', [App\Http\Controllers\SalesDashboardController::class, 'index'])->name('SalesmanDashboard');
Route::get('/HirePuruchaseDashboard', [App\Http\Controllers\HirePuruchaseDashboardController::class, 'index'])->name('HirePuruchaseDashboard');


//index of installment payment
Route::get('/hire_purchase_installment', [App\Http\Controllers\InstallmentPaymentController::class, 'index'])->name('hire_purchase_installment');
//get_installemnt_data_using_no_ajax
Route::get('/get_installemnt_data_using_no_ajax', [App\Http\Controllers\InstallmentPaymentController::class, 'findInvoice'])->name('get_installemnt_data_using_no_ajax');
//create_paying_installment
Route::post('/create_paying_installment', [App\Http\Controllers\InstallmentPaymentController::class, 'create'])->name('create_paying_installment');

//  Purchase Item wish report
Route::get('/reports.Purchase_wish_sales_report', [App\Http\Controllers\PurchasewishreportController::class,'index'])->name('Purchase_wish_sales_report');
// Item Wish Sales Controller
Route::get('/reports.Item_wish_sales_report', [App\Http\Controllers\ItemwishsalesController::class, 'index'])->name('Item_wish_sales_report');

//Cash in hand report
Route::get('/reports.Cash_in_out_report', [App\Http\Controllers\CashinoutController::class,'index'])->name('Cash_in_out_report');

//Cash in hand report
Route::get('/CashTransferreport', [App\Http\Controllers\CashTransferreportController::class,'index'])->name('CashTransferreport');

//Cust_Transferreport
Route::get('/Cust_Transferreport', [App\Http\Controllers\Cust_TransferreportController::class,'index'])->name('Cust_Transferreport');


//Show Customer Payment
Route::get('/sales_advance_payment', [App\Http\Controllers\AdvancePaymentController::class, 'index'])->name('sales_advance_payment');
Route::post('/addcustomerPayment', [App\Http\Controllers\AdvancePaymentController::class,'addcustomerPayment'])->name('addcustomerPayment');
Route::post('/UpdatecustomerPayment', [App\Http\Controllers\AdvancePaymentController::class,'UpdatecustomerPayment'])->name('UpdatecustomerPayment');
Route::post('/DeletecustomerPayment', [App\Http\Controllers\AdvancePaymentController::class,'DeletecustomerPayment'])->name('DeletecustomerPayment');
Route::get('/show_Customer_ajax',  [App\Http\Controllers\AdvancePaymentController::class, 'CustomerSearch'])->name('show_Customer_ajax');


//Hirepurchase sum report
Route::get('/HirepurchaseSumReport', [App\Http\Controllers\HirepurchaseSumReportController::class, 'index'])->name('HirepurchaseSumReport');

//Hirepurchase Arrears Report
Route::get('/HirepurchaseArrearsReport', [App\Http\Controllers\HirepurchaseArrearsReportController::class, 'index'])->name('HirepurchaseArrearsReport');

// HirepurchaseDetailReport
Route::get('/HirepurchaseDetailReport', [App\Http\Controllers\HirepurchaseDetailReportController::class, 'index'])->name('HirepurchaseDetailReport');

//filter_stock_by_date
Route::get('/filter_stock_by_FilterStoctValuation', [App\Http\Controllers\StockvaluationreportControler::class, 'FilterStoctValuation'])->name('filter_stock_by_FilterStoctValuation');

//AdvancePaymentReport
Route::get('/AdvancePaymentReport', [App\Http\Controllers\AdvancePaymentReportController::class, 'index'])->name('AdvancePaymentReport');




//Add MGuarantor ajex
Route::post('/add_down_payment_ajax', [App\Http\Controllers\DownPaymentController::class, 'store'])->name('add_down_payment_ajax');


// HirepurchaseDetailReport
Route::get('/hire_purchase_list', [App\Http\Controllers\GetHirePuruchaseDetailsController::class, 'index'])->name('hire_purchase_list');


// HptocashSales index
Route::get('/hire_purchase_to_cash_sale_conversion', [App\Http\Controllers\CashSalesConversionController::class, 'index'])->name('hire_purchase_to_cash_sale_conversion');
// create Hp to cash Sales
Route::post('/create_hp_to_cash_sales', [App\Http\Controllers\CashSalesConversionController::class, 'create'])->name('create_hp_to_cash_sales');



Route::get('/find_opening_stock_ajax',  [App\Http\Controllers\OpeningStockController::class, 'findInvoice'])->name('find_opening_stock_ajax');
//..............CustomerPayment.....................
// sales_customer_payment
Route::get('/sales_customer_payment', [App\Http\Controllers\CustomerPaymentController::class, 'index'])->name('sales_customer_payment');
//find customer purshases
Route::get('/get_customer_sales_data_using_no_ajax', [App\Http\Controllers\CustomerPaymentController::class, 'findCustomerPayment'])->name('get_customer_sales_data_using_no_ajax');
//make_customer_payment
Route::POST('/make_customer_payment', [App\Http\Controllers\CustomerPaymentController::class, 'create'])->name('make_customer_payment');

Route::get('/find_opening_stock_store_data', [App\Http\Controllers\OpeningStockController::class, 'findInvoiceStoreData'])->name('find_opening_stock_store_data');
//supplyer_payment_report index
Route::get('/customer_payment_report', [App\Http\Controllers\CustomerPaymentReportController::class, 'index'])->name('customer_payment_report');
//supplyer_payment_report_by_date
Route::get('/customer_payment_report_by_date', [App\Http\Controllers\CustomerPaymentReportController::class, 'filter'])->name('customer_payment_report_by_date');
//supplyer_cheque_payment_report
Route::get('/customer_cheque_payment_report', [App\Http\Controllers\CustomerChequePaymentReportController::class, 'index'])->name('customer_cheque_payment_report');
//salesman_loding_report
Route::get('/sales_loding_report', [App\Http\Controllers\SalesLodingReport::class, 'index'])->name('sales_loding_report');

Route::get('/customer_opening_balance', [App\Http\Controllers\CustomerOpeningBalanceController::class, 'index'])->name('customer_opening_balance');
Route::post('/addCustomerOpeningBalance', [App\Http\Controllers\CustomerOpeningBalanceController::class,'addCustomerBalace'])->name('addCustomerOpeningBalance');
Route::post('/UpdateCustomerOpeningBalance', [App\Http\Controllers\CustomerOpeningBalanceController::class,'UpdateCustomerBalace'])->name('UpdateCustomerOpeningBalance');
Route::post('/DeleteCustomerOpeningBalance', [App\Http\Controllers\CustomerOpeningBalanceController::class,'DeleteCustomerBalace'])->name('DeleteCustomerOpeningBalance');
Route::get('/show_CustomerCode_ajax',  [App\Http\Controllers\CustomerOpeningBalanceController::class, 'GetCustomerCode'])->name('show_CustomerCode_ajax');



Route::get('/salesInvoice_withoutVat', [App\Http\Controllers\SalesInvoicewithoutVatController::class, 'index'])->name('salesInvoice_withoutVat');
Route::post('/add_salesInvoice_withoutVat', [App\Http\Controllers\SalesInvoicewithoutVatController::class, 'add_salesInvoice'])->name('add_salesInvoice_withoutVat');


Route::get('/sales_loding_report', [App\Http\Controllers\SalesLodingReportController::class, 'index'])->name('sales_loding_report');


Route::get('stockAdjuestment', [App\Http\Controllers\StockAdjuestmentController::class, 'index'])->name('stockAdjuestment');
Route::post('add_StockAdjuestment', [App\Http\Controllers\StockAdjuestmentController::class, 'AddStockAdjuestment'])->name('add_StockAdjuestment');
Route::get('/sales_report_withoutVat', [App\Http\Controllers\SalereportController::class, 'indexVat'])->name('sales_report_withoutVat');




Route::get('/show_select_item_description_ajax_data', [App\Http\Controllers\StockAdjuestmentController::class, 'setItemDescriptionShow'])->name('show_select_item_description_ajax_data');

Route::get('/stockAdjuestmentNew', [App\Http\Controllers\StockAdjuestmentController::class, 'indexShow'])->name('stockAdjuestmentNew');



Route::post('/Store_StockAdjuestment', [App\Http\Controllers\StockAdjuestmentController::class, 'Store_StockAdjuestment'])->name('Store_StockAdjuestment');


Route::get('/find_invoice_customer_data_without_vat', [App\Http\Controllers\SalesInvoicewithoutVatController::class, 'FindInvoicewithoutVatSum'])->name('find_invoice_customer_data_without_vat');
Route::get('/find_invoice_without_vat', [App\Http\Controllers\SalesInvoicewithoutVatController::class, 'FindInvoicewithoutVatdetails'])->name('find_invoice_without_vat');
Route::get('/print_sales_invoice_ajax_without_vat', [App\Http\Controllers\SalesInvoicewithoutVatController::class, 'printwithoutvat'])->name('print_sales_invoice_ajax_without_vat');





Route::get('/reports.salesmanInvoiceReport', [App\Http\Controllers\MSalesmanController::class, 'SalesmanInvoiceReport'])->name('reports.salesmanInvoiceReport');


Route::get('/show_Branch_Details_ajax_two',  [App\Http\Controllers\StockTransferController::class, 'GetBranchDeatailsTwo'])->name('show_Branch_Details_ajax_two');

Route::get('/reports.stockTranferReport',  [App\Http\Controllers\StockTransferController::class, 'StockTranferReport'])->name('reports.stockTranferReport');



Route::get('reports.stockDetailsSummeryReport', [App\Http\Controllers\StockDetailsReportController::class, 'index'])->name('reports.stockDetailsSummeryReport');


Route::get('find_sales_details_invoice', [App\Http\Controllers\RecallEditSalesInvoiceController::class, 'FindInvoiceDetails'])->name('find_sales_details_invoice');
Route::get('find_sales_invoice_customer_data_sum', [App\Http\Controllers\RecallEditSalesInvoiceController::class, 'RecallSalesInvoiceDataSum'])->name('find_sales_invoice_customer_data_sum');
Route::post('update_sales_invoice_data', [App\Http\Controllers\RecallEditSalesInvoiceController::class, 'UpdateSaleData'])->name('update_sales_invoice_data');
Route::delete('/Sales-invoice/delete/{invoiceNo}', [App\Http\Controllers\RecallEditSalesInvoiceController::class, 'DeleteSalesInvoice']);


//get_customer_details_report
Route::get('/get_customer_details_report', [App\Http\Controllers\CustomerController::class, 'customerDetailsReportIndex'])->name('get_customer_details_report');

//customer_account_report
Route::get('/customer_account_report', [App\Http\Controllers\CustomerAccountReportController::class,'index'])->name('customer_account_report');

Route::get('reports.paymentvoucherreport', [App\Http\Controllers\PaymentVoucherReportController::class,'index'])->name('paymentvoucherreport');

Route::get('/customersalesWishReport', [App\Http\Controllers\CustomersalesWishReportController::class, 'index'])->name('customersalesWishReport');

//customer_balance_report
Route::get('/customer_balance_report', [App\Http\Controllers\CustomerBalanceReportController::class, 'index'])->name('customer_balance_report');


Route::get('/reports.salesman_invoice_report', [App\Http\Controllers\MSalesmanController::class, 'SalesmanInvoiceReport'])->name('reports.salesman_invoice_report');


// purchases_supplyer_payment
Route::get('/purchases_supplyer_payment', [App\Http\Controllers\SupplyerPaymentController::class, 'index'])->name('purchases_supplyer_payment');
Route::post('/AddSupplierPayment', [App\Http\Controllers\SupplyerPaymentController::class, 'AddSupplierpayment'])->name('AddSupplierPayment');
//find supplyer purshases
Route::get('/get_supplyer_purshases_data_using_no_ajax', [App\Http\Controllers\SupplyerPaymentController::class, 'findSupplierPayment'])->name('get_supplyer_purshases_data_using_no_ajax');
//create_supplyer_payment
Route::POST('/make_supplyer_payment', [App\Http\Controllers\SupplyerPaymentController::class, 'create'])->name('make_supplyer_payment');








//supplier_account_report
Route::get('/supplier_account_report', [App\Http\Controllers\SupplierAccountReportController::class,'index'])->name('supplier_account_report');

Route::get('/supplier_Report.supplyer_payment_report', [App\Http\Controllers\SupplierAccountReportController::class,'SupplierPaymentIndex'])->name('supplyer_payment_report');

Route::get('/supplier_Report.supplyer_balance_report', [App\Http\Controllers\SupplierAccountReportController::class,'SupplierBalanceIndex'])->name('supplyer_balance_report');

Route::get('/supplier_Report.supplyer_cheque_payment_report', [App\Http\Controllers\SupplierAccountReportController::class,'IndexSupCheque'])->name('supplyer_cheque_payment_report');

Route::get('/get_supplier_details_report', [App\Http\Controllers\SupplierAccountReportController::class, 'supplierDetailsReportIndex'])->name('get_supplier_details_report');


Route::get('/check_item_has_AutoSerial_ajax', [App\Http\Controllers\OpeningStockController::class,'AutoSerial'])->name('check_item_has_AutoSerial_ajax');


Route::get('/reports.SalesmanInvoiceSumReport', [App\Http\Controllers\MSalesmanController::class,'SalesmanInvoiceSumReport'])->name('reports.SalesmanInvoiceSumReport');




Route::delete('/Sales-invoice/delete-item', [SalesInvoicewithoutVatController::class, 'deleteSalesInvoiceItem'])
    ->name('delete_sales_invoice_item');
    
    
       // ── User pages ──
Route::get('/users',         [UserController::class, 'showUsers'])->name('users');
Route::get('/add_user',      [UserController::class, 'showAddUser'])->name('add_user');
Route::post('/add_user',     [UserController::class, 'AddUser'])->name('add_user_post');
Route::get('/edit_user/{id}',[UserController::class, 'editUsers'])->name('edit_user');
Route::get('/delete_user/{id}',[UserController::class,'deleteUsers'])->name('delete_user');

// ── AJAX endpoints ──
Route::post('/add_user_ajax',       [UserController::class, 'addUserAjax'])->name('add_user_ajax');
Route::post('/delete_user_ajax',    [UserController::class, 'delete'])->name('delete_user_ajax');
Route::post('/update_user_ajax',    [UserController::class, 'update'])->name('update_user_ajax');
Route::get('/search_user_ajax',     [UserController::class, 'search'])->name('search_user_ajax');
Route::get('/show_select_up_user',  [UserController::class, 'getUser'])->name('show_select_up_user_ajax');

// ── Role AJAX ──
Route::post('/add_role_ajax',       [UserController::class, 'addRole'])->name('add_role_ajax');



// Show Category Page
Route::get('/Category', [CategoryController::class, 'index'])->name('Category');

// Add Category (AJAX)
Route::post('/Category_store', [CategoryController::class, 'store'])->name('Category_store');

// Update Category (AJAX)
Route::post('/Category_edit', [CategoryController::class, 'update'])->name('Category_edit');

// Delete Category (AJAX)
Route::post('/Category_delete', [CategoryController::class, 'destroy'])->name('Category_delete');


        Route::post('ItemsByCategory', [ItemController::class, 'ItemsByCategory']);
Route::post('ItemBulkStore',   [ItemController::class, 'ItemBulkStore']);
Route::post('ItemAutoSearch', [ItemController::class, 'ItemAutoSearch']);

Route::post('ItemBulkStore',   [ItemController::class, 'ItemBulkStore']);

Route::post('ItemSearch',       [ItemController::class, 'ItemSearch']);    // ← new
Route::post('PackageStore',     [ItemController::class, 'PackageStore']);  // ← new



// Sales Invoice Print Route
Route::get('/sales/invoice/print', [SalesInvoicewithoutVatController::class, 'printInvoice'])
    ->name('sales.invoice.print');
    
    
    

Route::get('/print-invoice', [App\Http\Controllers\SalereportController::class, 'printInvoice'])->name('print.invoice');





        Route::get('/dashboard/sales-chart-data',   [App\Http\Controllers\HomeController::class, 'getSalesChartData'])  ->name('dashboard.salesChartData');
    Route::get('/dashboard/invoice-chart-data', [App\Http\Controllers\HomeController::class, 'getInvoiceChartData'])->name('dashboard.invoiceChartData');
    Route::get('/dashboard/multiline-data',     [App\Http\Controllers\HomeController::class, 'getMultiLineData'])   ->name('dashboard.multiLineData');

        // AJAX – chart period switching (called by JS tab buttons)
    Route::get('/dashboard/sales-chart-data',
        [App\Http\Controllers\HomeController::class, 'getSalesChartData'])->name('dashboard.salesChartData');

    Route::get('/dashboard/invoice-chart-data',
        [App\Http\Controllers\HomeController::class, 'getInvoiceChartData'])->name('dashboard.invoiceChartData');

    Route::get('/dashboard/multiline-data',
        [App\Http\Controllers\HomeController::class, 'getMultiLineData'])->name('dashboard.multiLineData');
        
        
 Route::get('/reports/customer-cheque', [CustomerChequePaymentReportController::class, 'index'])
     ->name('customer-cheque.index');

Route::post('/reports/customer-cheque/cash-received', [CustomerChequePaymentReportController::class, 'cashReceived'])
     ->name('customer-cheque.cash-received');
     
     
     
     
     
     Route::prefix('customer-payment')->middleware(['auth'])->group(function () {

    // Page
    Route::get('/',         [CustomerPaymentController::class, 'index'])
         ->name('customer_payment');

    // ── Invoice Wish ────────────────────────────────────────────────────
    // Fetch per-invoice data for a customer
    Route::get('/search',   [CustomerPaymentController::class, 'findCustomerPayment'])
         ->name('get_customer_sales_data_using_no_ajax');

    // Save invoice-level payment
    Route::post('/pay',     [CustomerPaymentController::class, 'create'])
         ->name('make_customer_payment');

    // ── Total Credit Payment ─────────────────────────────────────────────
    // Fetch cr_amount SUM − dr_amount SUM balance for a customer
    Route::get('/total-credit-balance', [CustomerPaymentController::class, 'getTotalCreditBalance'])
         ->name('get_customer_total_credit_balance_ajax');

    // Save total-credit payment (uses customer_code as reference)
    Route::post('/make-total-credit',   [CustomerPaymentController::class, 'makeTotalCreditPayment'])
         ->name('make_customer_total_credit_payment');

});

Route::get('/cashInHandReport',          [App\Http\Controllers\CashInHandReportController::class, 'index'])->name('cashInHandReport');
Route::post('/cash-in-hand/day-end', [App\Http\Controllers\CashInHandReportController::class, 'dayEndClose'])->name('cashInHand.dayEndClose');



Route::get('/search-customers-return', [App\Http\Controllers\SalesReturnController::class, 'searchCustomers'])->name('search-customers-return');
Route::get('/sales-return/print/{invoice_no}', [App\Http\Controllers\SalesReturnController::class, 'printInvoice'])->name('sales.return.print');


Route::middleware(['auth'])->group(function () {

    // ... your existing routes ...

    Route::get('/sales-return-report',
        [App\Http\Controllers\SalesReturnReportController::class, 'index'])
        ->name('sales.return.report');

        Route::get('/sales-return-details/{invoiceNo}',
            [App\Http\Controllers\SalesReturnReportController::class, 'getDetails'])
            ->name('sales.return.details');
});



Route::get('/gentralreceipt', [App\Http\Controllers\GentralReceiptController::class, 'index'])->name('gentralreceipt');
Route::post('/addGentralReceipt', [App\Http\Controllers\GentralReceiptController::class,'addGentralReceipt'])->name('addGentralReceipt');
Route::post('/UpdateGentralReceipt', [App\Http\Controllers\GentralReceiptController::class,'UpdateGentralReceipt'])->name('UpdateGentralReceipt');
Route::post('/DeleteGentralReceipt', [App\Http\Controllers\GentralReceiptController::class,'DeleteGentralReceipt'])->name('DeleteGentralReceipt');


Route::get('/print-invoice-SalesReturn', [App\Http\Controllers\SalesReturnReportController::class, 'SalesReturnprintInvoice'])->name('print.invoice-SalesReturn');



Route::middleware(['auth'])->group(function () {
    Route::get('/cashandChequeTransaction', [CashandChequeTransactionController::class, 'index'])
        ->name('cashandChequeTransaction');

    Route::post('/cash-in-hand/day-end-close', [CashandChequeTransactionController::class, 'dayEndChequeClose'])
        ->name('CashandChequeTransaction.dayEndClose');
});


// With auth middleware (recommended for accounting pages)
Route::middleware(['auth'])->group(function () {
    Route::get('/trial-balance.index', [TrialBalanceController::class, 'index'])
        ->name('trial-balance.index');
});



Route::get('/customer_wish_report', [App\Http\Controllers\SalereportController::class, 'CustomerWishReport'])->name('customer_wish_report');
Route::get('/reports/customer-wish-report/data', [App\Http\Controllers\SalereportController::class, 'CustomerWishReportData'])->name('reports.customer_wish_report.data');

Route::get('/show_select_item_description_ajax_binCard', [App\Http\Controllers\BinCardController::class, 'SetItemDescriptionBin'])->name('show_select_item_description_ajax_binCard');



Route::get('/invoiceDiscountEnter', [App\Http\Controllers\InvoiceDiscountEnterController::class, 'index'])->name('invoiceDiscountEnter');



Route::get('/invoice-discount', [App\Http\Controllers\InvoiceDiscountEnterController::class, 'index'])->name('invoice.discount.index');
Route::get('/search-customers', [App\Http\Controllers\InvoiceDiscountEnterController::class, 'searchCustomer'])->name('customers.search');
Route::get('/get-customer-invoices', [App\Http\Controllers\InvoiceDiscountEnterController::class, 'getCustomerInvoices'])->name('invoices.by-customer');
Route::post('/invoices/apply-discount', [App\Http\Controllers\InvoiceDiscountEnterController::class, 'applyDiscount'])->name('invoices.apply-discount');
Route::get('/customers/balance', [App\Http\Controllers\InvoiceDiscountEnterController::class, 'getCustomerBalance'])->name('customers.balance');
Route::post('/invoices/apply-transport', [App\Http\Controllers\InvoiceDiscountEnterController::class, 'applyTransport'])->name('invoices.apply-transport');


Route::get('/get_customer_ajax_return_nic', [App\Http\Controllers\CustomerController::class, 'getReturnCustomer'])->name('get_customer_ajax_return_nic');