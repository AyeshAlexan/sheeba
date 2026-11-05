<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('bc');
            $table->json('payload');
            $table->string('invoice_no')->nullable();
            $table->string('created_by');
            $table->timestamps();
            $table->index(['bc', 'invoice_no']);
        });
    }
    public function down() { Schema::dropIfExists('sales_orders'); }
};
