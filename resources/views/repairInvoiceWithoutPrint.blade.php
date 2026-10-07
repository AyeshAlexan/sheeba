<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Invoice</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            background: #fff;
            padding: 20px 30px;
        }

        /* ── Header ── */
        .header-wrap {
            position: relative;
            min-height: 65px;
            margin-bottom: 8px;
        }
        .logo-center {
            position: absolute;
            right: 0;
            text-align: center;
        }
        .logo-center img {
            height: 55px;
            width: auto;
            display: block;
            margin: 0 auto 2px;
        }
        .logo-center .brand-name {
            font-size: 9px;
            letter-spacing: 1px;
            color: #555;
        }
        .invoice-title-right {
            font-size: 22px;
            font-weight: bold;

        }

        /* ── Company Info ── */
        .company-block { margin-bottom: 10px; }
        .company-block h2 { font-size: 17px; font-weight: bold; margin-bottom: 2px; }
        .company-block p  { font-size: 11px; line-height: 1.5; color: #222; }

        /* ── Meta / Customer row ── */
        .meta-customer-row {
            display: flex;
            justify-content: space-between;
            border-top: 1.5px solid #000;
            border-bottom: 1.5px solid #000;
            padding: 6px 0;
            margin-bottom: 10px;
        }
        .meta-left table { border-collapse: collapse; }
        .meta-left td {
            font-size: 11px;
            padding: 1px 10px 1px 0;
            vertical-align: top;
        }
        .meta-left td:first-child {
            font-weight: bold;
            white-space: nowrap;
            color: #444;
            font-size: 10px;
        }
        .meta-left td:last-child { font-weight: bold; font-size: 12px; }

        .meta-right {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            line-height: 1.6;
        }
        .meta-right .customer-name { font-size: 14px; font-weight: bold; }

        /* ── Invoice type banner (Cash / Credit / Cheque) ── */
        .invoice-type-banner {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 1.5px;
            color: #176b43;
            background: #eefbf3;
            border: 1px solid #198754;
            border-radius: 3px;
            padding: 5px 0;
            margin-bottom: 10px;
        }

        /* ── Items Table ── */
        table.items-table { width: 100%; border-collapse: collapse; }

        table.items-table thead tr th {
            background: #fff;
            border-top: 1.5px solid #000;
            border-bottom: 1.5px solid #000;
            padding: 5px 6px;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
        }
        table.items-table thead tr th.left  { text-align: left; }
        table.items-table thead tr th.right { text-align: right; }

        table.items-table tbody tr td {
            padding: 5px 6px;
            font-size: 11px;
            vertical-align: middle;
            border-bottom: 0.5px solid #ddd;
        }
        table.items-table tbody tr td.center { text-align: center; }
        table.items-table tbody tr td.right  { text-align: right; }
        table.items-table tbody tr td.bold   { font-weight: bold; }
        table.items-table tbody tr:nth-child(even) { background-color: #f9f9f9; }

        /* ── Special footer rows ── */
        .subtotal-row td {
            border-top: 0 !important;
            border-bottom: none !important;
            padding: 4px 6px !important;
            font-size: 11px;
            background: #fff !important;
        }
        .roundoff-row td {
            padding: 3px 6px !important;
            font-size: 11px;
            border-bottom: none !important;
            font-style: italic;
            background: #fff !important;
        }
        .total-row td {
            border-top: 1.5px solid #000 !important;
            border-bottom: 1.5px solid #000 !important;
            padding: 5px 6px !important;
            font-size: 12px;
            font-weight: bold;
            background: #fff !important;
        }

        /* ── Balance section ── */
        .balance-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 8px;
            border-top: 1px solid #ccc;
            padding-top: 6px;
        }
        .balance-right table { border-collapse: collapse; }
        .balance-right td { padding: 2px 6px; font-size: 11px; }
        .balance-right td:first-child { text-align: left;  color: #444; }
        .balance-right td:last-child  { text-align: right; font-weight: bold; }
        .balance-right tr:last-child td {
            font-size: 13px;
            font-weight: bold;
            border-top: 1.5px solid #000;
            padding-top: 4px;
        }
        .payment-summary {
            margin-top: 8px;
            border-top: 1px solid #ccc;
            padding-top: 6px;
            font-size: 11px;
        }
        .payment-summary-title { color: #444; margin-bottom: 5px; font-weight: bold; }
        .pay-line {
            display: inline-flex;
            align-items: center;
            border: 1px solid #198754;
            border-radius: 4px;
            padding: 4px 10px;
            margin-right: 6px;
            background: #eefbf3;
            color: #176b43;
            font-size: 11px;
        }
        .pay-line strong { font-weight: bold; margin-right: 2px; }
        .pay-line.neutral { border-color: #bbb; background: #f5f5f5; color: #777; }

        /* ── Signatures ── */
        .signature-section {
            display: flex;
            justify-content: space-around;
            margin-top: 50px;
            margin-bottom: 10px;
        }
        .sig-box { text-align: center; min-width: 160px; }
        .sig-box .sig-line {
            border-top: 1.5px solid #000;
            padding-top: 5px;
            font-size: 11px;
            font-weight: bold;
        }

        /* ── Screen-only buttons ── */
        .no-print { margin-bottom: 14px; display: flex; gap: 10px; }
        .no-print button {
            padding: 7px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
        }
        .btn-print { background: #e68405; color: #fff; }
        .btn-close  { background: #555;    color: #fff; }

        @media print {
            @page { size: A4; margin: 10mm 12mm; }
            body  { padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
    <style>
        .company-block h2 {
            margin: 0 0 5px 0;
            font-size: 20px;
            color: #2c3e50;
            font-weight: 600;
        }
        .company-block p {
            margin: 3px 0;
            font-size: 14px;
            color: #555;
        }
        .company-block .contact {
            font-weight: 500;
            color: #000;
        }
        .company-block .email {
            color: #007bff;
            font-style: italic;
        }
    </style>
</head>
<body>

    @if($isReprint ?? false)
    <div style="text-align:center; margin:0 0 10px; padding:6px; border:2px solid #c0392b; border-radius:4px; background:#fdecea; color:#c0392b; font-weight:bold; font-size:16px; letter-spacing:2px;">
        ⚠ REPRINT COPY
    </div>
    @endif

    {{-- ── Screen Buttons ── --}}
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨 Print Invoice</button>
        <button class="btn-close"  onclick="window.close()">✕ Close</button>
    </div>

    {{-- ════════════════════════════
         HEADER
    ════════════════════════════ --}}
    <div class="header-wrap">
        {{-- INVOICE left --}}


        {{-- Logo right --}}
        <div class="logo-center">
            <img src="{{ asset('images/image.jpg') }}" alt="Logo">
            <div class="invoice-title-right">INVOICE</div>
        </div>
    </div>

    {{-- ════════════════════════════
         COMPANY INFO
    ════════════════════════════ --}}
    @foreach($companyData as $comData)
    <div class="company-block">
        <h2>{{ $comData->name }}</h2>
        <p>{{ $comData->address }}</p>

        <p class="contact">
            {{ $comData->co_number }}
            @if(!empty($comData->one_number)) | {{ $comData->one_number }} @endif
            @if(!empty($comData->two_number)) | {{ $comData->two_number }} @endif
        </p>

        @if(!empty($comData->email))
            <p class="email">{{ $comData->email }}</p>
        @endif
    </div>
    @endforeach

    {{-- ════════════════════════════
         META + CUSTOMER ROW
    ════════════════════════════ --}}
    @foreach($pawnSumData as $sumData)
    <div class="meta-customer-row">

        <div class="meta-left">
            <table>
                <tr>
                    <td>INVOICE #:</td>
                    <td>{{ $sumData->Invoice_no }}</td>
                </tr>
                <tr>
                    <td>INVOICE DATE:</td>
                    <td>{{ \Carbon\Carbon::parse($sumData->Invoice_date)->format('d M, Y') }}</td>
                </tr>
            </table>
        </div>

        <div class="meta-right">
            @foreach($customerData as $cust)
                <div class="customer-name">{{ $cust->First_name }}</div>
                @if(!empty($cust->Address_1))<div>{{ $cust->Address_1 }}</div>@endif
                @if(!empty($cust->Address_2))<div>{{ $cust->Address_2 }}</div>@endif
                @if(!empty($cust->Contact_1))<div>{{ $cust->Contact_1 }}</div>@endif
            @endforeach
        </div>

    </div>

    @php
        $cashPaid   = (float) ($sumData->Cash_Pay ?? 0) + (float) ($sumData->Half_Payment ?? 0);
        $creditDue  = (float) ($sumData->Credite ?? 0);
        $chequePaid = (float) ($sumData->Cheque ?? 0);

        $typeParts = [];
        if ($cashPaid > 0)   $typeParts[] = 'CASH';
        if ($creditDue > 0)  $typeParts[] = 'CREDIT';
        if ($chequePaid > 0) $typeParts[] = 'CHEQUE';
        $invoiceType = count($typeParts) ? implode(' / ', $typeParts) . ' INVOICE' : 'INVOICE';
    @endphp
    <div class="invoice-type-banner">{{ $invoiceType }}</div>
    @endforeach

    {{-- ════════════════════════════
         ITEMS TABLE
    ════════════════════════════ --}}
    @php $rowNo = 1; $totalQty = 0; $totalAmt = 0; @endphp

{{-- ════════════════════════════
     ITEMS TABLE
════════════════════════════ --}}
@php $rowNo = 1; $totalQty = 0; $totalAmt = 0; @endphp

<table class="items-table">
    <thead>
        <tr>
            <th style="width:30px;">No</th>
            <th class="left" style="width:90px;">Item Code</th>
            <th class="left">Description</th>
            <th style="width:65px;">Quantity</th>
            <th style="width:75px;">Rate</th>
            <th style="width:50px;">per</th>
            <th style="width:65px;">Disc. (%)</th>
            <th class="right" style="width:90px;">Amount</th>
        </tr>
    </thead>
    <tbody>

        @foreach($pawnDetailsData as $item)
        @php
            $totalQty += $item->QTY;
            $totalAmt += $item->Net_value;

            $rowGross = $item->QTY * $item->Unit_price;
            $rowDiscPercentage = 0;
            if ($rowGross > 0 && $item->Net_value < $rowGross) {
                $rowDiscPercentage = (($rowGross - $item->Net_value) / $rowGross) * 100;
            }
        @endphp
        <tr>
            <td class="center" style=" font-size: 14px;">{{ str_pad($rowNo++, 2, '0', STR_PAD_LEFT) }}</td>
            <td style=" font-size: 14px;">{{ $item->Item_code }}</td>
            <td class="bold" style=" font-size: 14px;">{{ $item->Item_description }}</td>
            <td class="center bold" style=" font-size: 14px;">{{ $item->QTY }}</td>
            <td class="center" style=" font-size: 14px;">{{ number_format($item->Unit_price, 2) }}</td>
            <td class="center" style=" font-size: 14px;">{{ $item->Per }}</td>
            <td class="center" style=" font-size: 14px;">
                @if($rowDiscPercentage > 0)
                    {{ number_format($rowDiscPercentage, 1) }}%
                @else
                    &mdash;
                @endif
            </td>
            <td class="right bold" style=" font-size: 14px;">{{ number_format($item->Net_value, 2) }}</td>
        </tr>
        @if(!empty($item->Free_Issues) && $item->Free_Issues > 0)
        <tr>
            <td></td>
            <td colspan="7" style="font-size:10px;color:#555;font-style:italic;
                                   padding-top:0;border-bottom:0.5px solid #ddd;">
                &nbsp;&nbsp;Free Issue: {{ $item->Free_Issues }}
            </td>
        </tr>
        @endif
        @endforeach

        {{-- Spacer rows --}}
        @for($i = 0; $i < max(0, 8 - count($pawnDetailsData)); $i++)
        <tr style="height:24px;">
            <td></td><td></td><td></td><td></td>
            <td></td><td></td><td></td><td></td>
        </tr>
        @endfor

        {{-- ── Gross Amount ── --}}
        @foreach($pawnSumData as $sumData)
        <tr class="subtotal-row">
            <td colspan="7" style="text-align:right;color:#555;font-size: 14px;">Gross Amount</td>
            <td style="text-align:right;font-size: 14px;">{{ number_format($sumData->Gross_Amount, 2) }}</td>
        </tr>

        {{-- ── Discount ── --}}
        @if(!empty($sumData->Discount) && $sumData->Discount > 0)
        <tr class="subtotal-row">
            <td colspan="7" style="text-align:right;color:#555;font-size: 14px;">
                Discount
                @if(!empty($sumData->Gross_Amount) && $sumData->Gross_Amount > 0)
                    ({{ number_format(($sumData->Discount / $sumData->Gross_Amount) * 100, 2) }}%)
                @endif
            </td>
            <td style="text-align:right;font-size: 14px;">({{ number_format($sumData->Discount, 2) }})</td>
        </tr>
        @endif

        {{-- ── Total ── --}}
        <tr class="total-row">
            <td colspan="3" style="text-align:left;font-size: 14px;">Total</td>
            <td style="text-align:center;font-size: 14px;">{{ $totalQty }} PCS</td>
            <td></td><td></td><td></td>
            <td style="text-align:right;font-size: 14px;">{{ number_format($sumData->Net_Amount, 2) }}</td>
        </tr>
        @endforeach

    </tbody>
</table>

    {{-- ════════════════════════════
         AMOUNT IN WORDS + BALANCE
    ════════════════════════════ --}}
    @foreach($pawnSumData as $sumData)

    @php
        $amt     = (int) round((float) $sumData->Net_Amount);
        $ones    = [
            '', 'ONE', 'TWO', 'THREE', 'FOUR', 'FIVE', 'SIX', 'SEVEN', 'EIGHT', 'NINE',
            'TEN', 'ELEVEN', 'TWELVE', 'THIRTEEN', 'FOURTEEN', 'FIFTEEN', 'SIXTEEN',
            'SEVENTEEN', 'EIGHTEEN', 'NINETEEN'
        ];
        $tensArr = [
            '', '', 'TWENTY', 'THIRTY', 'FORTY', 'FIFTY',
            'SIXTY', 'SEVENTY', 'EIGHTY', 'NINETY'
        ];
        $words = '';
        $tmp   = $amt;

        if ($tmp >= 10000000) {
            $cr     = (int)($tmp / 10000000);
            $words .= ($cr < 20 ? $ones[$cr] : $tensArr[(int)($cr/10)] . ($cr%10 ? ' '.$ones[$cr%10] : '')) . ' CRORE ';
            $tmp   %= 10000000;
        }
        if ($tmp >= 100000) {
            $lk     = (int)($tmp / 100000);
            $words .= ($lk < 20 ? $ones[$lk] : $tensArr[(int)($lk/10)] . ($lk%10 ? ' '.$ones[$lk%10] : '')) . ' LAKH ';
            $tmp   %= 100000;
        }
        if ($tmp >= 1000) {
            $th     = (int)($tmp / 1000);
            $words .= ($th < 20 ? $ones[$th] : $tensArr[(int)($th/10)] . ($th%10 ? ' '.$ones[$th%10] : '')) . ' THOUSAND ';
            $tmp   %= 1000;
        }
        if ($tmp >= 100) {
            $words .= $ones[(int)($tmp/100)] . ' HUNDRED ';
            $tmp   %= 100;
        }
        if ($tmp >= 20) {
            $words .= $tensArr[(int)($tmp/10)] . ' ';
            $tmp   %= 10;
        }
        if ($tmp > 0) {
            $words .= $ones[$tmp] . ' ';
        }

        $amountInWords = $amt === 0 ? 'LKR ZERO ONLY' : 'LKR ' . trim($words) . ' ONLY';
    @endphp

    <div class="balance-section">

        {{-- Amount in words --}}
        <div style="max-width:55%;">
            <div style="font-size:12px;color:#555;margin-bottom:3px;">
                Amount Chargeable (in words)
            </div>
            <div style="font-size:12px;font-weight:bold;text-transform:uppercase;line-height:1.5;">
                {{ $amountInWords }}
            </div>
        </div>

        {{-- Balances --}}
        <div class="balance-right">
            <table>
                <tr>
                    <td>Previous Balance:</td>
                    <td style=" font-size: 14px;">{{ number_format($balance - ($sumData->Credite ?? $sumData->Net_Amount), 2) }}</td>
                </tr>
                <tr>
                    <td>Current Balance:</td>
                    <td style=" font-size: 14px;">{{ number_format($balance, 2) }}</td>
                </tr>
            </table>
        </div>

    </div>

    {{-- $cashPaid / $creditDue / $chequePaid already computed above, next to
         the invoice-type banner (same $sumData, same values). --}}
    <div class="payment-summary">
        <div class="payment-summary-title">Payment Method</div>
        @if($cashPaid > 0)
            <span class="pay-line"><strong>Cash Pay</strong> &nbsp;Cash Paid: {{ number_format($cashPaid, 2) }}</span>
        @endif
        @if($creditDue > 0)
            <span class="pay-line"><strong>Credit Pay</strong> &nbsp;Credit Balance: {{ number_format($creditDue, 2) }} | Paid: {{ number_format($cashPaid, 2) }}</span>
        @endif
        @if($chequePaid > 0)
            <span class="pay-line"><strong>Cheque Pay</strong> &nbsp;Cheque Amount: {{ number_format($chequePaid, 2) }}</span>
        @endif
        @if($cashPaid <= 0 && $creditDue <= 0 && $chequePaid <= 0)
            <span class="pay-line neutral">No payment recorded</span>
        @endif
    </div>
    
    
    <div class="balance-section">
              <div class="balance-left">
                  
            <div style="font-size:12px;margin-bottom:3px;">
                Invoice Remark
            </div>
            <div style="font-size:12px;font-weight:bold;;line-height:1.5;">
                {{ $sumData->invoice_remark }}
            </div>
   
        </div>
    </div>
    @endforeach

    {{-- ════════════════════════════
         SIGNATURES
    ════════════════════════════ --}}
    <div class="signature-section">
        <div class="sig-box">
            <div style="height:40px;"></div>
            <div class="sig-line">Authorised By</div>
        </div>
        <div class="sig-box">
            <div style="height:40px;"></div>
            <div class="sig-line">Received By</div>
        </div>
    </div>

    {{-- ── Auto Print ── --}}
    <script>
        window.onload = function () {
            window.print();
            window.onafterprint = function () { window.close(); };
        };
    </script>

</body>
</html>