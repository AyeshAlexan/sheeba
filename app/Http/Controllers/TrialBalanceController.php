<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        $bc        =  auth()->user()->BC;
        $startDate = $request->input('start_date', date('Y-01-01'));
        $endDate   = $request->input('end_date',   date('Y-12-31'));

        // ── 1. Last day-end record before period start ──────────────────────────
        $dayEnd = DB::selectOne("
            SELECT
                close_date,
                closing_balance,
                cash_closing_balance,
                cheque_closing_balance,
                total_dr,
                total_cr
            FROM day_end_balances
            WHERE BC COLLATE utf8mb4_unicode_ci = ?
              AND close_date < ?
            ORDER BY close_date DESC
            LIMIT 1
        ", [$bc, $startDate]);

        // ── 2. Trial balance per account ────────────────────────────────────────
        $results = DB::select("
            SELECT
                c.code,
                c.account,
                c.accountsub,
                c.description,
                c.controlaccount,

                ROUND(IF(c.controlaccount COLLATE utf8mb4_unicode_ci
                    IN ('Asset','Expense'), c.opening_balance, 0), 2) AS ob_dr,
                ROUND(IF(c.controlaccount COLLATE utf8mb4_unicode_ci
                    IN ('Liability','Equity','Income'), c.opening_balance, 0), 2) AS ob_cr,

                ROUND(COALESCE(pa.period_dr, 0), 2) AS period_dr,
                ROUND(COALESCE(pa.period_cr, 0), 2) AS period_cr,

                ROUND(IF(c.controlaccount COLLATE utf8mb4_unicode_ci
                    IN ('Asset','Expense'), c.opening_balance, 0)
                    + COALESCE(pa.period_dr, 0), 2) AS closing_dr,
                ROUND(IF(c.controlaccount COLLATE utf8mb4_unicode_ci
                    IN ('Liability','Equity','Income'), c.opening_balance, 0)
                    + COALESCE(pa.period_cr, 0), 2) AS closing_cr

            FROM m_chartof_accounts c

            LEFT JOIN (
                SELECT
                    AccCode COLLATE utf8mb4_unicode_ci AS AccCode,
                    SUM(dr_amount) AS period_dr,
                    SUM(cr_amount) AS period_cr
                FROM t_account_trans
                WHERE Ddate BETWEEN ? AND ?
                  AND bc COLLATE utf8mb4_unicode_ci = ?
                GROUP BY AccCode
            ) pa ON pa.AccCode = c.code COLLATE utf8mb4_unicode_ci

            WHERE c.BC COLLATE utf8mb4_unicode_ci = ?
              AND (
                  c.opening_balance <> 0
                  OR pa.period_dr IS NOT NULL
                  OR pa.period_cr IS NOT NULL
              )
            ORDER BY c.code
        ", [$startDate, $endDate, $bc, $bc]);

        // ── 3. Footer totals ────────────────────────────────────────────────────
        $totals = [
            'ob_dr'      => array_sum(array_column($results, 'ob_dr')),
            'ob_cr'      => array_sum(array_column($results, 'ob_cr')),
            'period_dr'  => array_sum(array_column($results, 'period_dr')),
            'period_cr'  => array_sum(array_column($results, 'period_cr')),
            'closing_dr' => array_sum(array_column($results, 'closing_dr')),
            'closing_cr' => array_sum(array_column($results, 'closing_cr')),
        ];

        // ── 4. Cross-check TB net vs day-end closing balance ────────────────────
        $tbNet      = $totals['closing_dr'] - $totals['closing_cr'];
        $dayEndNet  = $dayEnd ? (float) $dayEnd->closing_balance : null;
        $crossCheck = $dayEndNet !== null
                        ? abs($tbNet - $dayEndNet) < 0.01
                        : null;

        return view('trial-balance', compact(
            'results', 'totals', 'bc', 'startDate', 'endDate',
            'dayEnd', 'tbNet', 'dayEndNet', 'crossCheck'
        ));
    }
}