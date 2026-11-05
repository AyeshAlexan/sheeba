<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SalesOrderConversionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        $tables = [
            'items' => 'Item_code Batchwise',
            'customers' => 'Code NIC credit_limit BC First_name Address_1 Address_2 Contact_1',
            'companies' => 'name address co_number one_number two_number email',
            'branch_dels' => 'bccode',
            'role_permissions' => 'role_name module_key is_enabled',
            'role_module_actions' => 'role_name module_key action_key is_enabled',
            't_without_vat_sales_sums' => 'Invoice_no Invoice_date ref_no customer_type Customer_NIC Customer_Name Customer_Phone Route Salesmen Gross_Amount Discount Net_Amount customer_balance Cash_Pay Half_Payment Credite Cheque invoice_remark BC OC confirmed_at confirmed_by',
            't_without_vat_sales_details' => 'Invoice_no Invoice_date Item_code Item_s_code batch_no Item_description QTY Per Unit_price DiscountPercentage Discount Net_value Free_Issues Salesmen OC BC',
            't_item_movements' => 'trans_no trans_code item_code batch_no qun_in qun_out Free_Issues dDate bc',
            't_account_trans' => 'trance_type Ddate AccCode Description dr_amount cr_amount trance_no no BC OC',
            't_cus_sale_trances' => 'no customer dr_trnce_code dr_trnce_no cr_trnce_code cr_trnce_no dr_amount cr_amount trance_type trance_no Display_Ref dDate BC OC',
        ];
        foreach ($tables as $name => $columns) {
            Schema::create($name, function (Blueprint $table) use ($columns) {
                $table->id();
                foreach (explode(' ', $columns) as $column) {
                    $table->string($column)->nullable();
                }
                $table->timestamps();
            });
        }
        (require database_path('migrations/2026_11_04_120000_create_sales_orders_table.php'))->up();
        DB::table('role_permissions')->insert(['role_name' => 'Tester', 'module_key' => 'sales', 'is_enabled' => 1]);
        foreach (['save', 'add', 'edit', 'print'] as $action) {
            DB::table('role_module_actions')->insert(['role_name' => 'Tester', 'module_key' => 'sales', 'action_key' => $action, 'is_enabled' => 1]);
        }
        $user = new User;
        $user->forceFill(['id' => 1, 'role' => 'Tester', 'BC' => 'B1', 'username' => 'tester']);
        $this->actingAs($user);
        DB::table('items')->insert(['Item_code' => 'ITEM1', 'Batchwise' => 0]);
        DB::table('t_item_movements')->insert(['item_code' => 'ITEM1', 'qun_in' => 10, 'qun_out' => 0, 'bc' => 'B1']);
    }

    private function payload($qty = 2): array
    {
        return ['customer_nic' => 'C1', 'customer_name' => 'Customer', 'invoice_no' => '1',
            'invoice_date' => '2026-11-04', 'cash_payment' => $qty * 90,
            'inputs' => [['invoice_no' => '999', 'invoice_date' => '2020-01-01', 'item_code' => 'ITEM1',
                'item_description' => 'Item', 'batch_no' => '', 'qty' => $qty, 'unit_price' => 100,
                'discount' => 10, 'net_value' => 1]]];
    }

    public function test_order_has_no_financial_or_stock_effect_and_can_be_edited(): void
    {
        $this->post(route('sales_orders.save'), $this->payload())->assertRedirect(route('sales_orders'));
        $this->assertDatabaseCount('sales_orders', 1);
        $this->assertDatabaseCount('t_item_movements', 1);
        $this->assertDatabaseCount('t_account_trans', 0);
        $this->assertDatabaseCount('t_without_vat_sales_sums', 0);
        $this->post(route('sales_orders.save'), $this->payload(3) + ['sales_order_id' => 1])->assertRedirect();
        $this->assertEquals(3, json_decode(DB::table('sales_orders')->value('payload'), true)['inputs'][0]['qty']);
    }

    public function test_invoice_finalizes_once_with_corrected_quantity_and_recalculated_totals(): void
    {
        $this->post(route('sales_orders.save'), $this->payload());
        $response = $this->post(route('add_salesInvoice_withoutVat'), $this->payload(3) + ['sales_order_id' => 1]);
        $response->assertSessionHasNoErrors()->assertSessionMissing('error')->assertSessionHas('done');
        $this->assertDatabaseHas('t_without_vat_sales_sums', ['Invoice_no' => '1', 'Net_Amount' => 270, 'Discount' => 30, 'confirmed_by' => 'tester']);
        $this->assertDatabaseHas('t_without_vat_sales_details', ['Invoice_no' => '1', 'Invoice_date' => '2026-11-04', 'QTY' => 3]);
        $this->assertDatabaseHas('t_item_movements', ['trans_code' => 'CONFIRM_INVOICE', 'qun_out' => 3]);
        $this->assertDatabaseHas('sales_orders', ['id' => 1, 'invoice_no' => '1']);
        $this->post(route('add_salesInvoice_withoutVat'), $this->payload(3) + ['sales_order_id' => 1])->assertSessionHas('error');
        $this->assertDatabaseCount('t_without_vat_sales_sums', 1);
        $this->assertDatabaseCount('t_item_movements', 2);
        $this->post(route('sales_orders.save'), $this->payload() + ['sales_order_id' => 1])->assertStatus(409);
    }

    public function test_short_stock_rolls_back_entire_conversion_including_duplicate_lines(): void
    {
        $payload = $this->payload(6);
        $payload['inputs'][] = $payload['inputs'][0];
        $payload['cash_payment'] = 1080;
        $this->post(route('sales_orders.save'), $payload);
        $this->post(route('add_salesInvoice_withoutVat'), $payload + ['sales_order_id' => 1])->assertSessionHas('error');
        $this->assertDatabaseCount('t_item_movements', 1);
        $this->assertDatabaseCount('t_without_vat_sales_sums', 0);
        $this->assertDatabaseCount('t_account_trans', 0);
        $this->assertDatabaseHas('sales_orders', ['id' => 1, 'invoice_no' => null]);
    }

    public function test_other_branch_order_cannot_be_converted(): void
    {
        $this->post(route('sales_orders.save'), $this->payload());
        DB::table('sales_orders')->update(['bc' => 'B2']);
        $this->get(route('sales_orders.show', 1))->assertNotFound();
        $this->post(route('add_salesInvoice_withoutVat'), $this->payload() + ['sales_order_id' => 1])->assertSessionHas('error');
        $this->assertDatabaseCount('t_without_vat_sales_sums', 0);
    }

    public function test_payment_failure_rolls_back_stock_and_order_conversion(): void
    {
        $this->post(route('sales_orders.save'), $this->payload());
        $payload = $this->payload();
        $payload['cash_payment'] = 0;
        $payload['cheque_payment'] = 180;
        $payload['cheques_data'] = json_encode([['amount' => 180, 'chequeNo' => '123']]);
        // The cheque table is deliberately absent in this isolated test database.
        $this->post(route('add_salesInvoice_withoutVat'), $payload + ['sales_order_id' => 1])->assertSessionHas('error');
        $this->assertDatabaseCount('t_item_movements', 1);
        $this->assertDatabaseCount('t_without_vat_sales_sums', 0);
        $this->assertDatabaseCount('t_account_trans', 0);
        $this->assertDatabaseHas('sales_orders', ['id' => 1, 'invoice_no' => null]);
    }

    public function test_order_print_is_labelled_and_calculates_discount(): void
    {
        $this->post(route('sales_orders.save'), $this->payload());
        DB::table('companies')->insert(['name' => 'Sheeba Test', 'address' => 'Test Road', 'co_number' => '0111234567']);
        DB::table('customers')->insert(['Code' => 'C1', 'BC' => 'B1', 'First_name' => 'Order Customer', 'Address_1' => 'Customer Road']);
        $this->get(route('sales_orders.print', 1))->assertOk()
            ->assertSee('Sales Order #1')->assertSee('SALES ORDER')->assertSee('180.00')
            ->assertSee('Sheeba Test')->assertSee('Customer Road')->assertSee('10.0%')
            ->assertSee('Authorised By')->assertSee('Received By')->assertSee('LKR ONE HUNDRED EIGHTY ONLY')
            ->assertDontSee('Cash Paid:')->assertDontSee('Previous Balance:')->assertDontSee('Current Balance:')
            ->assertDontSee('Payment Method')->assertDontSee('No payment recorded');
    }

    public function test_invoice_cannot_add_items_outside_the_sales_order(): void
    {
        $this->post(route('sales_orders.save'), $this->payload());
        $payload = $this->payload();
        $payload['inputs'][] = $payload['inputs'][0];
        $payload['cash_payment'] = 360;
        $this->post(route('add_salesInvoice_withoutVat'), $payload + ['sales_order_id' => 1])->assertSessionHas('error');
        $this->assertDatabaseCount('t_without_vat_sales_sums', 0);
        $this->assertDatabaseCount('t_item_movements', 1);
    }

    public function test_invoice_screen_is_order_loading_and_payments_without_manual_item_entry(): void
    {
        $this->get(route('salesInvoice_withoutVat'))->assertOk()
            ->assertSee('Load Sales Order No')->assertSee('Save Invoice')
            ->assertSee('id="cash_payment"', false)->assertSee('id="invoiceLines"', false)
            ->assertDontSee('id="item_code"', false)->assertDontSee('id="searchCustomer"', false)
            ->assertDontSee('id="saveInvoiceBtn"', false);
    }

    public function test_sales_order_form_has_item_entry_and_no_payment_or_invoice_recall_controls(): void
    {
        $this->view('salesOrder', ['errors' => new \Illuminate\Support\ViewErrorBag, 'recentOrders' => collect(), 'itemCode' => collect(),
            'Customerdetails' => collect(), 'itemDetails' => collect(), 'Route' => collect(),
            'area' => collect(), 'salesmandetails' => collect(), 'maxCustomer' => '0001', 'maxInvoiceNo' => 0])
            ->assertSee('id="item_code"', false)->assertSee('Save Sales Order')
            ->assertDontSee('id="cash_payment"', false)->assertDontSee('id="Recall_Invoice"', false);
    }

    public function test_stock_from_other_branches_cannot_be_sold(): void
    {
        DB::table('t_item_movements')->update(['bc' => 'B2']);
        $this->post(route('sales_orders.save'), $this->payload());
        $this->post(route('add_salesInvoice_withoutVat'), $this->payload() + ['sales_order_id' => 1])->assertSessionHas('error');
        $this->assertDatabaseCount('t_without_vat_sales_sums', 0);
        $this->assertDatabaseCount('t_item_movements', 1);
    }

    public function test_half_payment_and_credit_use_corrected_invoice_total(): void
    {
        $this->post(route('sales_orders.save'), $this->payload());
        $payload = $this->payload(3);
        $payload['cash_payment'] = 0;
        $payload['half_payment'] = 50;
        $this->post(route('add_salesInvoice_withoutVat'), $payload + ['sales_order_id' => 1])
            ->assertSessionMissing('error')->assertSessionHas('done');
        $this->assertDatabaseHas('t_without_vat_sales_sums', ['Half_Payment' => 50, 'Credite' => 220, 'Net_Amount' => 270]);
        $original = json_decode(DB::table('sales_orders')->value('payload'), true);
        $this->assertEquals(2, $original['inputs'][0]['qty']);
        $this->assertEquals(180, $original['net_amount']);
    }

    public function test_final_invoice_number_is_allocated_when_saving(): void
    {
        $this->post(route('sales_orders.save'), $this->payload());
        $this->post(route('sales_orders.save'), $this->payload());
        $this->post(route('add_salesInvoice_withoutVat'), $this->payload() + ['sales_order_id' => 1])->assertSessionHas('done');
        // The second browser still displays the old suggested number.
        $this->post(route('add_salesInvoice_withoutVat'), $this->payload() + ['sales_order_id' => 2])->assertSessionMissing('error')->assertSessionHas('done');
        $this->assertDatabaseHas('sales_orders', ['id' => 2, 'invoice_no' => '2']);
        $this->assertDatabaseCount('t_without_vat_sales_sums', 2);
    }

    public function test_separate_confirmation_step_has_been_removed(): void
    {
        $this->get('/confirm_invoice')->assertNotFound();
        $this->post('/confirm_invoice', ['invoice_no' => '1'])->assertNotFound();
        $this->post('/unconfirm_invoice_ajax', ['invoice_no' => '1'])->assertNotFound();
        $this->get(route('salesInvoice_withoutVat'))->assertOk()
            ->assertDontSee('Confirm Invoice')->assertSee('id="savedInvoiceNotice"', false);
        $this->assertArrayNotHasKey('confirm_invoices', config('modules'));
    }

    public function test_final_invoice_print_keeps_invoice_labels_and_payment_details(): void
    {
        $this->post(route('sales_orders.save'), $this->payload());
        $this->post(route('add_salesInvoice_withoutVat'), $this->payload() + ['sales_order_id' => 1])->assertSessionHas('done');
        $this->get(route('sales.invoice.print', ['invoice_no' => '1', 'branch_code' => 'B1', 'print_type' => 'normal']))
            ->assertOk()->assertSee('INVOICE')->assertSee('Invoice #')->assertSee('Print Invoice')
            ->assertSee('Payment Method')->assertSee('Cash Paid:')->assertSee('Current Balance:')
            ->assertDontSee('SALES ORDER')->assertDontSee('This is an order, not an invoice.');
    }
}
