<?php

namespace Tests\Feature;

use App\Http\Controllers\StockAdjuestmentController;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StockAdjustmentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\CheckModuleAccess::class);
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        foreach ([
            'items' => 'Item_code Item_description saleprice',
            'stores' => 'Store_code Store_name',
            't_item_movements' => 'item_code qun_in qun_out Stock_adjuestment bc trans_code trans_no dDate storse_id',
            'stock_adjuestment_details' => 'Item_code Item_description QTY qtyOut Unit_price Net_value Store_code Invoice_no Invoice_date BC OC',
            'stock_adjuestment_sums' => 'Store_code Invoice_no Invoice_date BC OC Amount',
        ] as $table => $columns) {
            Schema::create($table, function (Blueprint $schema) use ($columns) {
                $schema->id();
                foreach (explode(' ', $columns) as $column) {
                    $schema->string($column)->nullable();
                }
                $schema->timestamps();
            });
        }
        $this->actingAs((new User)->forceFill(['id' => 1, 'BC' => 'B1', 'username' => 'tester']));
        DB::table('stores')->insert(['Store_code' => 'S']);
        foreach (['A', 'B'] as $code) {
            DB::table('items')->insert(['Item_code' => $code, 'saleprice' => 5]);
        }
        DB::table('t_item_movements')->insert(['item_code' => 'A', 'qun_in' => 10, 'bc' => 'B1']);
        DB::table('t_item_movements')->insert(['item_code' => 'A', 'qun_in' => 8, 'bc' => 'B2']);
    }

    private function saveLines(array $lines, int $number = 1)
    {
        return $this->post(route('Store_StockAdjuestment'), [
            'Store_code' => 'S', 'invoice_date' => '2026-11-18', 'invoice_no' => $number,
            'lines' => json_encode(array_map(fn ($row) =>
                array_combine(['item_code', 'action', 'quantity', 'balance'], $row), $lines)),
        ]);
    }

    private function balances()
    {
        return app(StockAdjuestmentController::class)->indexShow()->getData()['itemDetails']->pluck('QTY', 'Item_code')->all();
    }

    public function test_adjustments_post_only_changes_and_reopen_with_previewed_balances(): void
    {
        $this->assertEquals(['A' => 10, 'B' => 0], $this->balances());
        $this->saveLines([['A', 'increase', 5, 10], ['B', 'increase', 2.5, 0]])->assertSessionHasNoErrors();
        $this->assertEquals(['A' => 15, 'B' => 2.5], $this->balances());
        $this->saveLines([['A', 'reduce', 5, 15]], 2)->assertSessionHasNoErrors();
        $this->saveLines([['A', 'reduce', 5, 10]], 3)->assertSessionHasNoErrors();
        $this->assertEquals(5, $this->balances()['A']);
        $this->saveLines([['A', 'reduce', 5, 5]], 4)->assertSessionHasNoErrors();
        $this->assertEquals(['A' => 0, 'B' => 2.5], $this->balances());
    }

    public function test_shortage_stale_balance_and_repeated_submission_cannot_partially_post(): void
    {
        foreach ([[12, 10], [5, 9]] as [$qty, $balance]) {
            $this->saveLines([['B', 'increase', 1, 0], ['A', 'reduce', $qty, $balance]])->assertSessionHasErrors('lines');
            $this->assertDatabaseCount('stock_adjuestment_details', 0);
            $this->assertDatabaseCount('stock_adjuestment_sums', 0);
            $this->assertDatabaseCount('t_item_movements', 2);
        }
        $this->saveLines([['A', 'reduce', 5, 10]])->assertSessionHasNoErrors();
        $this->saveLines([['A', 'reduce', 5, 10]])->assertSessionHasErrors('invoice_no');
        $this->assertEquals(5, $this->balances()['A']);
    }
}
