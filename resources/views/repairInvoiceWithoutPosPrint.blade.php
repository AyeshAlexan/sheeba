<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GRN Print</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            background: #f0f0f0;
        }

        /* ── Toolbar ── */
        .no-print {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #fff;
            border-bottom: 1px solid #ddd;
            margin-bottom: 14px;
        }
        .btn {
            padding: 6px 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            color: #fff;
        }
        .btn-print  { background: #1a3a8c; }
        .btn-print:hover  { background: #142d6e; }
        .btn-rotate { background: #2e7d32; }
        .btn-rotate:hover { background: #1b5e20; }
        .btn-close  { background: #888; }
        .btn-close:hover  { background: #666; }
        #orientLabel {
            font-size: 11px;
            color: #555;
            margin-left: 6px;
            font-style: italic;
        }

        /* ── Page wrapper ── */
        .page-wrapper {
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        /* ── Page: default = LANDSCAPE 20cm x 19cm ── */
        .page {
            position: relative;
            width: 20cm;
            height: 19cm;
            background: #fff;
            overflow: hidden;
            margin-bottom: 10px;
            page-break-after: always;
        }

        /* ── Fields ── */
        .field {
            position: absolute;
            white-space: nowrap;
            font-size: 11px;
            font-family: Arial, sans-serif;
            color: #000;
        }

        .InvoiceNo     { top: 1.3cm; left: 15.5cm; font-size: 13px; font-weight: bold; }
        .CustomerName  { top: 3.6cm; left: 2.2cm; }
        .CustomerAddr  { top: 4.2cm; left: 2.2cm; }
        .DateDayMonth  { top: 5.5cm; left: 16.5cm; }
        .DateYear      { top: 5.5cm; left: 17.9cm; }

        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
            .page-wrapper { display: block; }
        }
    </style>

    <style id="printStyle">
        @media print {
            @page { size: 20cm 19cm landscape; margin: 0; }
            body  { width: 20cm; }
        }
    </style>
</head>
<body>

    <!-- Toolbar -->
    <div class="no-print">
        <button class="btn btn-print"  onclick="window.print()">🖨 Print</button>
        <button class="btn btn-rotate" onclick="toggleRotate()">🔄 Rotate</button>
        <button class="btn btn-close"  onclick="window.close()">✕ Close</button>
        <span id="orientLabel">⟶ Landscape &nbsp;(20 × 19 cm)</span>
    </div>

    <div class="page-wrapper">
        <div class="page" id="page">

            {{-- ── Invoice Number ── --}}
            @foreach ($pawnSumData as $sumData)
                <span class="field InvoiceNo">{{ $sumData->Invoice_no }}</span>
            @endforeach

            {{-- ── Customer Details ── --}}
            @foreach ($customerData as $cust)
                <span class="field CustomerName">{{ $cust->First_name }}</span>
                @if (!empty($cust->Address_1))
                    <span class="field CustomerAddr">{{ $cust->Address_1 }}</span>
                @endif
            @endforeach

            {{-- ── Date ── --}}
            @foreach ($pawnSumData as $sumData)
                @php $dateObj = \Carbon\Carbon::parse($sumData->Invoice_date); @endphp
                <span class="field DateDayMonth">{{ $dateObj->format('d/m') }}</span>
                <span class="field DateYear">{{ $dateObj->format('y') }}</span>
            @endforeach

            {{-- ── Item Rows (Dynamic - Unlimited Records) ── --}}
            @foreach ($pawnDetailsData as $item)
                @php
                    $topPosition = 6.5 + ($loop->index * 0.6);
                @endphp

                <span class="field" style="top: {{ $topPosition }}cm; left: 1.5cm;">{{ $item->Item_code }}</span>
                <span class="field" style="top: {{ $topPosition }}cm; left: 3.5cm;">{{ $item->QTY }}</span>
                <span class="field" style="top: {{ $topPosition }}cm; left: 5cm;">{{ $item->Item_description }}</span>
                <span class="field" style="top: {{ $topPosition }}cm; left: 13cm;">{{ number_format($item->Unit_price, 2) }}</span>
                <span class="field" style="top: {{ $topPosition }}cm; left: 16cm;">{{ number_format($item->Net_value, 2) }}</span>
            @endforeach

            {{-- ── Summary (Dynamic Position After Items) ── --}}
            @foreach ($pawnSumData as $sumData)
                @php
                    // Calculate summary position: items + gap
                    $summaryStartTop = 6 + (count($pawnDetailsData) * 0.6) + 0.8;
                @endphp

                <span class="field" style="top: {{ $summaryStartTop }}cm; left: 5cm;font-weight: bold;">Gross Amount</span>
                <span class="field" style="top: {{ $summaryStartTop }}cm; left: 16.2cm;">{{ number_format($sumData->Gross_Amount, 2) }}</span>

                @if (!empty($sumData->Discount) && $sumData->Discount > 0)
                    <span class="field" style="top: {{ $summaryStartTop + 0.6 }}cm; left: 5cm; ">Discount</span>
                    <span class="field" style="top: {{ $summaryStartTop + 0.6 }}cm; left: 16.2cm;">{{ number_format($sumData->Discount, 2) }}</span>
                @endif

                <span class="field" style="top: {{ $summaryStartTop + 1.2 }}cm; left: 5cm; font-weight: bold;">NET AMOUNT</span>
                <span class="field" style="top: {{ $summaryStartTop + 1.2 }}cm; left: 16.2cm; font-weight: bold;">{{ number_format($sumData->Net_Amount, 2) }}</span>

                @php
                    $cashPaid = (float) ($sumData->Cash_Pay ?? 0) + (float) ($sumData->Half_Payment ?? 0);
                    $creditDue = (float) ($sumData->Credite ?? 0);
                    $chequePaid = (float) ($sumData->Cheque ?? 0);
                @endphp
                <span class="field" style="top: {{ $summaryStartTop + 1.8 }}cm; left: 5cm;">Payment:</span>
                <span class="field" style="top: {{ $summaryStartTop + 1.8 }}cm; left: 7cm;">{{ $cashPaid > 0 ? '[x]' : '[ ]' }} Cash Pay</span>
                <span class="field" style="top: {{ $summaryStartTop + 1.8 }}cm; left: 10cm;">{{ $creditDue > 0 ? '[x]' : '[ ]' }} Credit Pay</span>
                <span class="field" style="top: {{ $summaryStartTop + 1.8 }}cm; left: 13cm;">{{ $chequePaid > 0 ? '[x]' : '[ ]' }} Cheque Pay</span>

            @endforeach

        </div>
    </div>

    <script>
        let isPortrait = false;

        const page        = document.getElementById('page');
        const orientLabel = document.getElementById('orientLabel');
        const printStyle  = document.getElementById('printStyle');

        function toggleRotate() {
            isPortrait = !isPortrait;
            if (isPortrait) {
                orientLabel.textContent = '↕ Portrait  (19 × 20 cm)';
                printStyle.textContent = `
                    @media print {
                        @page { size: 19cm 20cm portrait; margin: 0; }
                        body  { width: 19cm; }
                    }`;
            } else {
                orientLabel.textContent = '⟶ Landscape  (20 × 19 cm)';
                printStyle.textContent = `
                    @media print {
                        @page { size: 20cm 19cm landscape; margin: 0; }
                        body  { width: 20cm; }
                    }`;
            }
        }
    </script>

</body>
</html>