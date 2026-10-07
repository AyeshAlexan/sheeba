<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Schema reconstructed directly from a real production dump
 * (smarto_sheeba1.sql) — exact column types/defaults/indexes, no guessing.
 */
return new class extends Migration
{
    public function up()
    {
        $statements = [
            'CREATE TABLE `userroles` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `role_code` varchar(255) NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `BC` varchar(255) DEFAULT NULL,
  `OC` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `userroles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userroles_role_code_unique` (`role_code`);',
            'ALTER TABLE `userroles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }

        // Real production roles (note the trailing space on 'Operator ' —
        // existing code already trims it when matching, kept verbatim here).
        DB::table('userroles')->insert([
            ['id' => 1, 'role_code' => '001', 'role_name' => 'Admin', 'BC' => null, 'OC' => null, 'created_at' => '2023-12-12 01:52:50', 'updated_at' => '2023-12-12 01:52:50'],
            ['id' => 2, 'role_code' => '002', 'role_name' => 'Operator ', 'BC' => '002', 'OC' => 'admin', 'created_at' => '2023-12-12 01:53:08', 'updated_at' => '2023-12-12 01:53:08'],
            ['id' => 3, 'role_code' => '003', 'role_name' => 'Manager', 'BC' => '002', 'OC' => 'admin', 'created_at' => '2023-12-12 01:54:50', 'updated_at' => '2023-12-12 01:54:50'],
        ]);
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `userroles`');
    }
};
