<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PurchaseInvoiceTest extends TestCase
{
    public function test_purchase_commits_before_pdf_generation_and_can_be_printed_by_its_branch(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');

        $this->createTables([
            't_purchases_sums' => 'Invoice_no Ref_no Invoice_date Customer_NIC Customer_Name Customer_Phone Gross_Amount Net_Amount cash_payment credit_payment cheque_payment BC OC',
            't_purchases_details' => 'Invoice_no Ref_no Invoice_date Item_code Item_s_code Item_description QTY Unit_price Net_value BC OC',
            't_sup_purchase_trances' => 'no supplier dr_trnce_code dr_trnce_no dr_amount cr_trnce_code cr_trnce_no cr_amount trance_type trance_no dDate bc oc',
            't_item_movements' => 'trans_no dDate trans_code item_code batch_no qun_in bc',
            'items' => 'Item_code purchasePrice',
            't_cus_cheques' => 'trans_order_no',
            'role_permissions' => 'role_name module_key is_enabled',
        ]);
        DB::table('role_permissions')->insert(['role_name' => 'Buyer', 'module_key' => 'purchases', 'is_enabled' => true]);

        $pdfUrl = route('purchases.print', ['invoiceNo' => 2]);
        $this->get($pdfUrl)->assertRedirect(route('login'));

        $buyer = (new User)->forceFill(['id' => 1, 'BC' => 'B1', 'username' => 'buyer', 'role' => 'Buyer']);
        $this->actingAs($buyer)->from(route('purchases'))->post(route('add_Purchases'), [
            'invoice_no' => 2,
            'invoice_date' => '2026-11-25',
            'customer_nic' => 'S1',
            'gross_amount' => 20,
            'net_amount' => 20,
            'inputs' => [[
                'customer_nic' => 'S1', 'invoice_no' => 2, 'invoice_date' => '2026-11-25',
                'item_code' => 'A1', 'item_description' => 'Sample item', 'qty' => 2, 'unit_price' => 10, 'net_value' => 20,
            ]],
        ])->assertRedirect(route('purchases'))->assertSessionHasNoErrors()->assertSessionHas('pdfLink', $pdfUrl);

        $this->assertDatabaseHas('t_purchases_sums', ['Invoice_no' => 2, 'BC' => 'B1']);
        $this->assertDatabaseHas('t_item_movements', ['trans_no' => 2, 'trans_code' => 'GRN', 'item_code' => 'A1', 'qun_in' => 2]);

        $this->createTables(['companies' => 'name', 'suppliers' => 'Code']);
        $pdf = $this->get($pdfUrl)->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $pdf->getContent());

        $this->actingAs((clone $buyer)->forceFill(['BC' => 'B2']))->get($pdfUrl)->assertNotFound();
        $this->actingAs((clone $buyer)->forceFill(['role' => 'Other']))->getJson($pdfUrl)->assertForbidden();
    }

    private function createTables(array $tables): void
    {
        foreach ($tables as $name => $columns) {
            Schema::create($name, function (Blueprint $table) use ($columns) {
                $table->id();
                foreach (explode(' ', $columns) as $column) {
                    $table->string($column)->nullable();
                }
                $table->timestamps();
            });
        }
    }
}
