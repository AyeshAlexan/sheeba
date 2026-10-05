<?php

namespace App\Http\Controllers;
use App\Models\TItemMovement;
use App\Models\Item;
use App\Models\Company;
use App\Models\branchDel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StockDetailsReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
      public function index(Request $request)
        {
    $fromDate = $request->input('from_date');
    $toDate   = $request->input('to_date');

    [$stockData, $showInCodes, $showOutCodes] = $this->buildStockDetails($fromDate, $toDate);

    return view('reports.stockDetailsSummeryReport', [
        'stockData' => $stockData,
        'showInCodes' => $showInCodes,
        'showOutCodes' => $showOutCodes,
        'fromDate' => $fromDate,
        'toDate' => $toDate,
    ]);
        }

    public function print(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate   = $request->input('to_date');
        $branch_code = auth()->user()->BC;

        [$stockData, $showInCodes, $showOutCodes] = $this->buildStockDetails($fromDate, $toDate);

        return view('reports.print.stock-details', [
            'stockData'    => $stockData,
            'showInCodes'  => $showInCodes,
            'showOutCodes' => $showOutCodes,
            'fromDate'     => $fromDate,
            'toDate'       => $toDate,
            'companyData'  => Company::latest()->first(),
            'branchDel'    => branchDel::where('bccode', $branch_code)->first(),
        ]);
    }

    /**
     * @return array{0: \Illuminate\Support\Collection, 1: array, 2: array}
     */
    private function buildStockDetails($fromDate, $toDate)
    {
        $rawData = DB::table('t_item_movements')
        ->join('items', 't_item_movements.item_code', '=', 'items.Item_code')
        ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
            $q->whereBetween('t_item_movements.dDate', [$fromDate, $toDate]);
        })
        ->select(
            'items.Item_code',
            'items.Item_description',
            't_item_movements.trans_code',
            't_item_movements.qun_in',
            't_item_movements.qun_out',
            't_item_movements.Free_Issues',
            't_item_movements.dDate'
        )
        ->get();

        // A handful of rows have non-numeric junk in these quantity columns (bad
        // legacy data entry, e.g. "chq price"), which crashes Collection::sum()
        // under PHP 8's stricter string+int rules — normalize to 0 instead.
        foreach ($rawData as $row) {
            $row->qun_in       = is_numeric($row->qun_in) ? $row->qun_in : 0;
            $row->qun_out      = is_numeric($row->qun_out) ? $row->qun_out : 0;
            $row->Free_Issues  = is_numeric($row->Free_Issues) ? $row->Free_Issues : 0;
        }

        $allTransCodes = $rawData->pluck('trans_code')->unique()->values();

        // Determine which trans_code *directions* to show
        $showInCodes = [];
        $showOutCodes = [];

        foreach ($allTransCodes as $code) {
            if ($rawData->where('trans_code', $code)->sum('qun_in') > 0) {
                $showInCodes[] = $code;
            }
            if ($rawData->where('trans_code', $code)->sum('qun_out') > 0) {
                $showOutCodes[] = $code;
            }
        }

        $stockData = collect($rawData)->groupBy('Item_code')->map(function ($group) use ($allTransCodes) {
            $row = [
                'Item_code' => $group->first()->Item_code,
                'Item_description' => $group->first()->Item_description,
                'qun_in' => $group->sum('qun_in'),
                'qun_out' => $group->sum('qun_out'),
                'Free_Issues' => $group->sum('Free_Issues'),
                'dDate' => $group->max('dDate'),
            ];

            foreach ($allTransCodes as $code) {
                $row[$code . '_in'] = $group->where('trans_code', $code)->sum('qun_in');
                $row[$code . '_out'] = $group->where('trans_code', $code)->sum('qun_out');
            }

            return $row;
        })->values();

        return [$stockData, $showInCodes, $showOutCodes];
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}