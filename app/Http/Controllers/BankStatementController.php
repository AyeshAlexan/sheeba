<?php

namespace App\Http\Controllers;

use App\Models\ChequeBank;
use App\Models\TSupCheque;
use App\Models\TCusCheque;
use App\Models\Company;
use App\Models\branchDel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BankStatementController extends Controller
{
    public function show(Request $request, $id)
    {
        $bank = ChequeBank::findOrFail($id);

        [$movements, $periodOpening, $fromDate, $toDate] = $this->buildStatement($bank, $request);

        $sumDr = $movements->sum('dr');
        $sumCr = $movements->sum('cr');
        $closingBalance = $periodOpening + $sumDr - $sumCr;

        return view('reports.bank-statement', [
            'bank'           => $bank,
            'movements'      => $movements,
            'periodOpening'  => $periodOpening,
            'sumDr'          => $sumDr,
            'sumCr'          => $sumCr,
            'closingBalance' => $closingBalance,
            'fromDate'       => $fromDate,
            'toDate'         => $toDate,
        ]);
    }

    public function print(Request $request, $id)
    {
        $bank = ChequeBank::findOrFail($id);

        [$movements, $periodOpening, $fromDate, $toDate] = $this->buildStatement($bank, $request);

        $sumDr = $movements->sum('dr');
        $sumCr = $movements->sum('cr');
        $closingBalance = $periodOpening + $sumDr - $sumCr;

        return view('reports.print.bank-statement', [
            'bank'           => $bank,
            'movements'      => $movements,
            'periodOpening'  => $periodOpening,
            'sumDr'          => $sumDr,
            'sumCr'          => $sumCr,
            'closingBalance' => $closingBalance,
            'fromDate'       => $fromDate,
            'toDate'         => $toDate,
            'companyData'    => Company::latest()->first(),
            'branchDel'      => branchDel::where('bccode', auth()->user()->BC)->first(),
        ]);
    }

    /**
     * Every cheque movement that has actually affected
     * cheque_banks.current_amount for this bank, in date order — the bank's
     * Opening Amount itself is kept OUT of this list (it's a summary figure,
     * not a transaction) so it's never double-counted in DR totals; the view
     * displays it as a separate first row, same convention as Cash In Hand.
     *
     * Cheques currently RETURNED/return net to zero balance impact (the
     * issue/deposit decrement or increment was reversed when returned), so
     * they're shown for reference but contribute 0 to DR/CR either way.
     *
     * When a date range is given, everything before "from_date" (including
     * the bank's own Opening Amount) is folded into a single Opening Balance
     * figure for the period, and only movements inside the range are listed
     * with a running balance that continues from that figure.
     *
     * @return array{0: Collection, 1: float, 2: ?string, 3: ?string}
     */
    private function buildStatement(ChequeBank $bank, Request $request): array
    {
        $fromDate = $request->input('from_date');
        $toDate   = $request->input('to_date');

        $all = new Collection();

        $supplierCheques = TSupCheque::where('cheque_bank_id', $bank->id)->get();
        foreach ($supplierCheques as $cheque) {
            $outstanding = in_array($cheque->cheque_status, ['ISSUED', 'DEPOSITED']);

            $all->push([
                'date'        => optional($cheque->status_changed_at)->format('Y-m-d') ?? '-',
                'description' => "Cheque Issued #{$cheque->cheques_no} — {$cheque->supplier_name}",
                'dr'          => 0,
                'cr'          => $outstanding ? (float) $cheque->amount : 0,
                'status'      => $cheque->cheque_status,
                'status_label' => ucfirst(strtolower($cheque->cheque_status)),
            ]);
        }

        $customerCheques = TCusCheque::leftJoin('customers', 'customers.Code', '=', 't_cus_cheques.trans_no')
            ->where('t_cus_cheques.cheque_bank_id', $bank->id)
            ->select('t_cus_cheques.*', 'customers.Name as customer_name')
            ->get();
        foreach ($customerCheques as $cheque) {
            $deposited = $cheque->cheque_status === 'deposit';
            $party     = $cheque->customer_name ?: $cheque->trans_no;

            $all->push([
                'date'        => optional($cheque->status_changed_at)->format('Y-m-d') ?? '-',
                'description' => "Cheque Deposited #{$cheque->cheques_no} — {$party}",
                'dr'          => $deposited ? (float) $cheque->amount : 0,
                'cr'          => 0,
                'status'      => $cheque->cheque_status,
                'status_label' => $deposited ? 'Deposited' : 'Returned',
            ]);
        }

        $all = $all->sortBy('date')->values();

        // The bank's own Opening Amount always counts as "before" every
        // cheque movement, regardless of the exact date it was recorded on.
        $periodOpening = (float) $bank->opening_amount;
        $displayed     = $all;

        if ($fromDate) {
            $periodOpening += (float) $all->filter(fn ($row) => $row['date'] !== '-' && $row['date'] < $fromDate)
                ->sum(fn ($row) => $row['dr'] - $row['cr']);

            $displayed = $all->filter(function ($row) use ($fromDate, $toDate) {
                if ($row['date'] === '-') {
                    return false;
                }
                if ($row['date'] < $fromDate) {
                    return false;
                }
                if ($toDate && $row['date'] > $toDate) {
                    return false;
                }
                return true;
            })->values();
        }

        $running = $periodOpening;
        $displayed = $displayed->map(function ($row) use (&$running) {
            $running += $row['dr'] - $row['cr'];
            $row['balance'] = $running;
            return $row;
        });

        return [$displayed, $periodOpening, $fromDate, $toDate];
    }
}
