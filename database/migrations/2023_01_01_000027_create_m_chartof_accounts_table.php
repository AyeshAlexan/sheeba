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
            'CREATE TABLE `m_chartof_accounts` (

  `id` bigint(20) UNSIGNED NOT NULL,
  `account` varchar(50) DEFAULT NULL,
  `accountsub` varchar(60) DEFAULT NULL,
  `code` varchar(60) DEFAULT NULL,
  `description` varchar(60) DEFAULT NULL,
  `opening_balance` int(11) DEFAULT NULL,
  `controlaccount` varchar(10) DEFAULT NULL,
  `bankaccount` varchar(10) DEFAULT NULL,
  `BC` varchar(60) DEFAULT NULL,
  `OC` varchar(60) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;',
            'ALTER TABLE `m_chartof_accounts`
  ADD PRIMARY KEY (`id`);',
            'ALTER TABLE `m_chartof_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;'
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }

        DB::table('m_chartof_accounts')->insert([
            ['id' => 45, 'account' => 'Current Assets', 'accountsub' => 'Assets', 'code' => '201-001', 'description' => 'Cash in Hand', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'admin', 'created_at' => '2023-12-22 19:20:01', 'updated_at' => '2026-05-12 01:23:04'],
            ['id' => 46, 'account' => 'Administrative & Establishment', 'accountsub' => 'Expenses', 'code' => 'C-0012', 'description' => 'Banking', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'admin', 'created_at' => '2023-12-22 19:20:40', 'updated_at' => '2026-06-03 04:32:55'],
            ['id' => 47, 'account' => 'Administrative & Establishment', 'accountsub' => 'Expenses', 'code' => 'C-003', 'description' => 'Phone Bill', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'admin', 'created_at' => '2023-12-22 19:21:00', 'updated_at' => '2026-06-03 04:33:08'],
            ['id' => 48, 'account' => 'Administrative & Establishment', 'accountsub' => 'Expenses', 'code' => 'C-004', 'description' => 'Travelling Allowance', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'admin', 'created_at' => '2023-12-22 19:22:29', 'updated_at' => '2026-06-03 04:33:16'],
            ['id' => 49, 'account' => 'Administrative & Establishment', 'accountsub' => 'Expenses', 'code' => 'C-005', 'description' => 'Head Office Return', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'admin', 'created_at' => '2023-12-22 19:24:21', 'updated_at' => '2026-06-03 04:33:26'],
            ['id' => 50, 'account' => 'Administrative & Establishment', 'accountsub' => 'Expenses', 'code' => 'C-006', 'description' => 'Advance Payment', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'admin', 'created_at' => '2023-12-22 19:27:15', 'updated_at' => '2026-06-03 04:36:17'],
            ['id' => 52, 'account' => 'Administrative & Establishment', 'accountsub' => 'Expenses', 'code' => 'C-007', 'description' => 'Water Bill', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'admin', 'created_at' => '2023-12-22 19:30:52', 'updated_at' => '2026-06-03 04:36:28'],
            ['id' => 53, 'account' => 'Administrative & Establishment', 'accountsub' => 'Expenses', 'code' => 'C-002', 'description' => 'Staff Salary Allowance', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'admin', 'created_at' => '2023-12-22 19:33:25', 'updated_at' => '2026-06-03 04:35:55'],
            ['id' => 54, 'account' => 'Administrative & Establishment', 'accountsub' => 'Expenses', 'code' => 'C-001', 'description' => 'Elecricity Bill', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'admin', 'created_at' => '2023-12-22 19:36:29', 'updated_at' => '2026-06-03 04:36:03'],
            ['id' => 55, 'account' => 'Administrative & Establishment', 'accountsub' => 'Expenses', 'code' => 'C-101', 'description' => 'Sundry', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'admin', 'created_at' => '2023-12-22 19:48:56', 'updated_at' => '2026-06-03 04:36:39'],
            ['id' => 56, 'account' => 'Administrative & Establishment', 'accountsub' => 'Expenses', 'code' => 'C-102', 'description' => 'Western Union Paid', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'admin', 'created_at' => '2023-12-22 19:51:16', 'updated_at' => '2026-06-03 04:37:19'],
            ['id' => 57, 'account' => 'Administrative & Establishment', 'accountsub' => 'Expenses', 'code' => 'C-103', 'description' => 'Others', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'admin', 'created_at' => '2023-12-22 19:53:51', 'updated_at' => '2026-06-03 04:37:05'],
            ['id' => 59, 'account' => 'Administrative & Establishment', 'accountsub' => 'Income', 'code' => 'C-105', 'description' => 'Income Receivab', 'opening_balance' => 1000, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'admin', 'created_at' => '2024-04-29 13:29:28', 'updated_at' => '2026-06-03 04:36:56'],
            ['id' => 60, 'account' => 'Finance & Other Expenses', 'accountsub' => 'Expenses', 'code' => 'C-104', 'description' => 'Indra J/W', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'testing', 'created_at' => '2026-05-15 00:13:23', 'updated_at' => '2026-06-03 04:32:32'],
            ['id' => 61, 'account' => 'Current Assets', 'accountsub' => 'Assets', 'code' => '201-123', 'description' => 'Cheques', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'testing', 'created_at' => '2026-05-15 01:49:34', 'updated_at' => '2026-06-03 02:48:25'],
            ['id' => 62, 'account' => 'Finance & Other Expenses', 'accountsub' => 'Expenses', 'code' => 'C-014', 'description' => 'Purchasing', 'opening_balance' => 0, 'controlaccount' => null, 'bankaccount' => null, 'BC' => '001', 'OC' => 'testing', 'created_at' => '2026-05-18 04:14:02', 'updated_at' => '2026-06-03 04:32:46'],
        ]);
    }

    public function down()
    {
        DB::unprepared('DROP TABLE IF EXISTS `m_chartof_accounts`');
    }
};
