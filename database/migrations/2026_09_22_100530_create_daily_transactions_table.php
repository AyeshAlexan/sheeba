<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('ref_no')->unique();
            $table->date('date');
            $table->string('dr_code');
            $table->string('dr_label');
            $table->string('cr_code');
            $table->string('cr_label');
            $table->text('description');
            $table->decimal('amount', 15, 2);

            // Optional link to a customer/supplier so this entry can also
            // post to their account ledger, satisfying "automatically
            // reflects in customer/supplier accounts" without forcing
            // every entry to have one.
            $table->string('party_type')->nullable();  // 'customer' | 'supplier' | null
            $table->string('party_code')->nullable();
            $table->string('party_side')->nullable();   // 'dr' | 'cr'

            $table->string('BC')->nullable();
            $table->string('OC')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_transactions');
    }
};
