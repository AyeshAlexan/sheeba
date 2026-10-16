<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('role_report_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role_name');
            $table->string('report_key');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();

            $table->unique(['role_name', 'report_key']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_report_permissions');
    }
};
