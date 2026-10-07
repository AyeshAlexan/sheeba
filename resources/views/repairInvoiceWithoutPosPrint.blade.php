<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GRN Print</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --tr-blue: #1677FF;
            --tr-navy: #14213D;
            --tr-bg: #F6F8FC;
            --tr-blue-light: #EAF3FF;
            --tr-border: #E5EAF2;
            --tr-text-secondary: #667085;
            --tr-success: #16A34A;
            --tr-danger: #EF4444;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            background: var(--tr-bg);
        }

        /* ── Toolbar (screen only — hidden on the real print via @media print below) ── */
        .no-print {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 24px;
            background: #fff;
            border-bottom: 1px solid var(--tr-border);
            margin-bottom: 28px;
            box-shadow: 0 2px 10px rgba(20, 33, 61, .04);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border: 1px solid transparent;
            border-radius: 999px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #fff;
            transition: background .15s, box-shadow .15s, transform .1s;
        }
        .btn:active { transform: scale(.97); }
        .btn-print  { background: var(--tr-blue); }
        .btn-print:hover  { background: #0e5fe0; box-shadow: 0 4px 12px rgba(22,119,255,.25); }
        .btn-rotate { background: #fff; color: var(--tr-navy); border-color: var(--tr-border); }
        .btn-rotate:hover { background: var(--tr-blue-light); border-color: var(--tr-blue); color: var(--tr-blue); }
        .btn-close  { background: #fff; color: var(--tr-text-secondary); border-color: var(--tr-border); }
        .btn-close:hover  { background: #FEF2F2; border-color: var(--tr-danger); color: var(--tr-danger); }
        #orientLabel {
            font-size: 12px;
            color: var(--tr-text-secondary);
            margin-left: 4px;
            padding: 6px 14px;
            background: var(--tr-bg);
            border-radius: 999px;
            font-style: normal;
            font-weight: 600;
        }

        /* ── Page wrapper (screen backdrop — not part of the print) ── */
        .page-wrapper {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
            padding: 8px 20px 40px;
        }

        /* ── Page: default = LANDSCAPE 20cm x 19cm ──
             Screen-only framing (shadow/radius) is added via the
             `@media screen` rule further down so the printed page itself
             is pixel-identical to before — field coordinates are untouched. */
        .page {
            position: relative;
            width: 20cm;
            height: 19cm;
            background: #fff;
            overflow: hidden;
            margin-bottom: 10px;
            page-break-after: always;
        }

        @media screen {
            .page {
                border-radius: 10px;
                box-shadow: 0 1px 2px rgba(20,33,61,.04), 0 16px 40px rgba(20,33,61,.12);
                outline: 1px solid var(--tr-border);
            }
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

    @if($isReprint ?? false)
    <div style="text-align:center; margin:0 0 10px; padding:6px; border:2px solid #c0392b; border-radius:4px; background:#fdecea; color:#c0392b; font-weight:bold; font-size:14px; letter-spacing:2px;">
        ⚠ REPRINT COPY
    </div>
    @endif

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