<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Same seed philosophy as role_permissions: Admin starts fully
        // enabled (matching its existing "full access out of the box"
        // seed there), every other role starts opt-in/denied until an
        // admin deliberately turns actions on for them from the Role
        // Permissions screen. This is a data default, not a code
        // special-case — Permissions::canDo() treats every role, Admin
        // included, identically.
        $moduleActions = require config_path('module_actions.php');
        $roles = DB::table('userroles')->pluck('role_name');

        $rows = [];
        foreach ($roles as $role) {
            foreach ($moduleActions as $module => $actions) {
                foreach ($actions as $action) {
                    $rows[] = [
                        'role_name'  => $role,
                        'module_key' => $module,
                        'action_key' => $action,
                        'is_enabled' => $role === 'Admin',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        if (!empty($rows)) {
            DB::table('role_module_actions')->insert($rows);
        }
    }

    public function down()
    {
        DB::table('role_module_actions')->delete();
    }
};
