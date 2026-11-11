<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayEndBalance extends Model
{
    protected $fillable = [
        'BC',
        'close_date',
        'closing_balance',         // Combined  (cash + cheque)
        'cash_closing_balance',    // 201-001   Cash only
        'cheque_closing_balance',  // 201-123   Cheque only
        'total_dr',
        'total_cr',
        'closed_by',
        'closed_at',
    ];

    protected $casts = [
        'close_date' => 'date',
        'closed_at'  => 'datetime',
    ];

    /**
     * Get the opening balance for a branch and date.
     *
     * @param  string       $branchCode
     * @param  string       $date        Y-m-d  — returns the closing balance of the last closed day BEFORE this date
     * @param  string|null  $accCode     '201-001' | '201-123' | null (combined)
     * @return float
     */
    public static function getOpeningBalance(string $branchCode, string $date, ?string $accCode = null): float
    {
        $record = self::where('BC', $branchCode)
            ->where('close_date', '<', $date)
            ->orderBy('close_date', 'desc')
            ->first();

        if (!$record) {
            return (float) (MChartofAccount::where('code', $accCode)->value('opening_balance') ?? 0);
        }

        return match ($accCode) {
            '201-001' => (float) ($record->cash_closing_balance   ?? $record->closing_balance ?? 0),
            '201-123' => (float) ($record->cheque_closing_balance ?? 0),
            default   => (float)  $record->closing_balance,
        };
    }

    /**
     * Check whether a given date has already been closed for a branch.
     *
     * @param  string|null  $accCode  '201-001' (cash), '201-123' (cheque), or null to check both are closed.
     */
    public static function isDateClosed(string $branchCode, string $date, ?string $accCode = null): bool
    {
        $query = self::where('BC', $branchCode)->where('close_date', $date);

        return match ($accCode) {
            '201-001' => $query->whereNotNull('cash_closing_balance')->exists(),
            '201-123' => $query->whereNotNull('cheque_closing_balance')->exists(),
            default   => $query->whereNotNull('cash_closing_balance')->whereNotNull('cheque_closing_balance')->exists(),
        };
    }
}