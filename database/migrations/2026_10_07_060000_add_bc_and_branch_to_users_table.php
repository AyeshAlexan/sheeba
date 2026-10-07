<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * No migration for these columns existed in the repo even though
     * UserController and nearly every other controller read/write
     * auth()->user()->BC — same stale-schema pattern as the other tables
     * fixed in this session.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('BC')->nullable()->after('role');
            $table->string('Branch')->nullable()->after('BC');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['BC', 'Branch']);
        });
    }
};
