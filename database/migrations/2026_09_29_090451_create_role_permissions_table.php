<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // "Sales User" doesn't exist as a role yet — the client's spec
        // names it explicitly, but the app only has Admin/Manager/Operator.
        if (!DB::table('userroles')->where('role_name', 'Sales User')->exists()) {
            DB::table('userroles')->insert([
                'role_code' => '004',
                'role_name' => 'Sales User',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $modules = array_keys(require config_path('modules.php'));

        // Admin: full access out of the box. Every other existing role
        // (Manager, Operator) starts with nothing enabled — deny by
        // default until an admin deliberately turns modules on for them,
        // rather than guessing what they should be able to see.
        $roles = DB::table('userroles')->pluck('role_name');

        $rows = [];
        foreach ($roles as $role) {
            foreach ($modules as $module) {
                $rows[] = [
                    'role_name'  => $role,
                    'module_key' => $module,
                    'is_enabled' => $role === 'Admin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Sales User gets a sensible starting point matching the client's
        // "day-to-day sales/billing operations" description, adjustable
        // afterwards from the Role Permissions screen.
        foreach ($rows as &$row) {
            if ($row['role_name'] === 'Sales User' && in_array($row['module_key'], ['sales', 'vouchers', 'master', 'reports'])) {
                $row['is_enabled'] = true;
            }
        }
        unset($row);

        DB::table('role_permissions')->insert($rows);
    }

    public function down()
    {
        DB::table('role_permissions')->delete();
        DB::table('userroles')->where('role_name', 'Sales User')->delete();
    }
};
