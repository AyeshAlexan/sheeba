<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Invoice</title>
    <style>
        /* NOTE: this is rendered through DomPDF, which does NOT support
           CSS flexbox/grid or CSS custom properties (var(--x)) — every
           layout row below uses plain <table> markup (the only
           multi-column technique DomPDF renders reliably), and every
           color is a literal hex value instead of a var(). Brand colors:
           #1a5fb4 (brand), #123e78 (brand-dark), #1f2430 (ink),
           #6b7280 (muted), #d9dee6 (line), #f4f7fb (soft). */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #1f2430;
            background: #fff;
            padding: 22px 32px;
        }

        table.layout { width: 100%; border-collapse: collapse; }
        table.layout td { vertical-align: top; }

        /* ── Header ── */
        .header-wrap {
            padding-bottom: 14px;
            border-bottom: 3px solid #1a5fb4;
            margin-bottom: 14px;
        }
        .header-left img {
            height: 64px;
            width: 64px;
            object-fit: contain;
            border-radius: 8px;
            background: #f4f7fb;
            padding: 4px;
        }
        .company-block h2 {
            font-size: 19px;
            font-weight: 700;
            color: #123e78;
            letter-spacing: .3px;
        }
        .company-block p {
            margin: 2px 0;
            font-size: 11px;
            color: #6b7280;
        }
        .company-block .contact { color: #1f2430; font-weight: 500; }
        .company-block .email { color: #1a5fb4; }

        .header-right { text-align: right; }
        .invoice-title-right {
            font-size: 24px;
            font-weight: 800;
            color: #1a5fb4;
            letter-spacing: 2px;
        }
        .invoice-meta { margin-top: 8px; border-collapse: collapse; width: 100%; }
        .invoice-meta td { font-size: 11px; padding: 1px 0 1px 10px; text-align: right; }
        .invoice-meta td:first-child { color: #6b7280; font-weight: 600; padding-left: 0; }
        .invoice-meta td:last-child { font-weight: 700; color: #1f2430; }

        /* ── Payment Type / Bill To letterhead card ── */
        .meta-customer-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .meta-col {
            padding: 14px 18px;
            vertical-align: top;
            background: #f4f7fb;
            border-top: 1px solid #d9dee6;
            border-bottom: 1px solid #d9dee6;
        }
        .meta-col-payment { width: 38%; border-left: 1px solid #d9dee6; border-right: 1px solid #d9dee6; }
        .meta-col-billto { text-align: right; border-right: 1px solid #d9dee6; }
        .meta-col-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #6b7280;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .invoice-type-banner {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .5px;
            color: #123e78;
        }
        .meta-col-billto .customer-name { font-size: 14px; font-weight: 700; color: #1f2430; margin-bottom: 2px; }
        .meta-col-billto div { font-size: 11px; color: #6b7280; line-height: 1.5; }

        /* ── Items Table ── */
        table.items-table { width: 100%; border-collapse: collapse; }

        table.items-table thead tr th {
            background: #1a5fb4;
            color: #fff;
            padding: 7px 6px;
            font-size: 10.5px;
            font-weight: 700;
            text-align: center;
            letter-spacing: .3px;
            white-space: nowrap;
        }
        table.items-table thead tr th.left  { text-align: left; }
        table.items-table thead tr th.right { text-align: right; }

        table.items-table tbody tr td {
            padding: 6px 6px;
            font-size: 11.5px;
            vertical-align: middle;
            border-bottom: 1px solid #d9dee6;
        }
        table.items-table tbody tr td.center { text-align: center; }
        table.items-table tbody tr td.right  { text-align: right; }
        table.items-table tbody tr td.bold   { font-weight: 600; }
        table.items-table tbody tr:nth-child(even) { background-color: #f4f7fb; }

        /* ── Special footer rows ── */
        .subtotal-row td {
            border-top: 0 !important;
            border-bottom: none !important;
            padding: 4px 6px !important;
            font-size: 11px;
            background: #fff !important;
            color: #6b7280;
        }
        .total-row td {
            border-top: 2px solid #1a5fb4 !important;
            border-bottom: 2px solid #1a5fb4 !important;
            padding: 7px 6px !important;
            font-size: 13px;
            font-weight: 800;
            background: #f4f7fb !important;
            color: #123e78;
        }

        /* ── Balance section ── */
        .balance-section { width: 100%; border-collapse: collapse; margin-top: 10px; padding-top: 8px; }
        .balance-section td { vertical-align: top; }
        .balance-left { width: 55%; }
        .balance-right-cell { text-align: right; }
        .balance-right table { border-collapse: collapse; margin-left: auto; }
        .balance-right td { padding: 2px 6px; font-size: 11px; }
        .balance-right td:first-child { text-align: left;  color: #6b7280; }
        .balance-right td:last-child  { text-align: right; font-weight: 700; }
        .balance-right tr:last-child td {
            font-size: 13px;
            font-weight: 800;
            color: #123e78;
            border-top: 1.5px solid #d9dee6;
            padding-top: 5px;
        }
        .payment-summary {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #d9dee6;
            font-size: 11px;
        }
        .payment-summary-title { color: #6b7280; margin-bottom: 6px; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: .4px; }
        .pay-line {
            display: inline-block;
            border: 1px solid #1a5fb4;
            border-radius: 20px;
            padding: 4px 12px;
            margin-right: 6px;
            background: #eaf2fc;
            color: #123e78;
            font-size: 11px;
        }
        .pay-line strong { font-weight: 700; margin-right: 4px; }
        .pay-line.neutral { border-color: #bbb; background: #f5f5f5; color: #777; }

        /* ── Signatures ── */
        .signature-section { width: 100%; border-collapse: collapse; margin-top: 55px; margin-bottom: 10px; }
        .sig-box { text-align: center; width: 50%; padding: 0 20px; }
        .sig-box .sig-line {
            border-top: 1.5px solid #1f2430;
            padding-top: 6px;
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
        }

        /* ── Footer strip ── */
        .print-footer {
            margin-top: 18px;
            padding-top: 8px;
            border-top: 1px solid #d9dee6;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }

        /* ── Screen-only buttons ── */
        .no-print { margin-bottom: 14px; }
        .no-print button {
            padding: 7px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: bold;
            margin-right: 10px;
        }
        .btn-print { background: #1a5fb4; color: #fff; }
        .btn-close  { background: #555;    color: #fff; }

        @media print {
            @page { size: A4; margin: 10mm 12mm; }
            body  { padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    @if($isReprint ?? false)
    <div style="text-align:center; margin:0 0 10px; padding:6px; border:2px solid #c0392b; border-radius:4px; background:#fdecea; color:#c0392b; font-weight:bold; font-size:16px; letter-spacing:2px;">
        REPRINT COPY
    </div>
    @endif

    {{-- ── Screen Buttons ── --}}
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">Print Invoice</button>
        <button class="btn-close"  onclick="window.close()">Close</button>
    </div>

    {{-- ════════════════════════════
         HEADER — logo + company on the left, invoice title/meta on the right
    ════════════════════════════ --}}
    <div class="header-wrap">
        <table class="layout">
            <tr>
                <td style="width:65%;">
                    <table class="layout">
                        <tr>
                            <td style="width:76px;" class="header-left">
                                @php
                                    $logoPath = public_path('assets/images/image.jpg');
                                    $logoSrc = file_exists($logoPath)
                                        ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath))
                                        : asset('assets/images/image.jpg');
                                @endphp
                                <img src="{{ $logoSrc }}" alt="Logo">
                            </td>
                            <td style="padding-left:14px;">
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
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="header-right" style="width:35%;">
                    <div class="invoice-title-right">INVOICE</div>
                    @foreach($pawnSumData as $sumData)
                    <table class="invoice-meta">
                        <tr>
                            <td>Invoice #</td>
                            <td>{{ $sumData->Invoice_no }}</td>
                        </tr>
                        <tr>
                            <td>Date</td>
                            <td>{{ \Carbon\Carbon::parse($sumData->Invoice_date)->format('d M, Y') }}</td>
                        </tr>
                    </table>
                    @endforeach
                </td>
            </tr>
        </table>
    </div>

    {{-- ════════════════════════════
         PAYMENT TYPE / BILL TO CARD
    ════════════════════════════ --}}
    @foreach($pawnSumData as $sumData)
    <table class="meta-customer-row">
        <tr>
            <td class="meta-col meta-col-payment">
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
                <div class="meta-col-label">Payment Type</div>
                <div class="invoice-type-banner">{{ $invoiceType }}</div>
            </td>

            <td class="meta-col meta-col-billto">
                @if($customerData->isNotEmpty())
                    <div class="meta-col-label">Bill To</div>
                    @foreach($customerData as $cust)
                        <div class="customer-name">{{ $cust->First_name }}</div>
                        @if(!empty($cust->Address_1))<div>{{ $cust->Address_1 }}</div>@endif
                        @if(!empty($cust->Address_2))<div>{{ $cust->Address_2 }}</div>@endif
                        @if(!empty($cust->Contact_1))<div>{{ $cust->Contact_1 }}</div>@endif
                    @endforeach
                @elseif(!empty($sumData->Customer_Name))
                    {{-- No matching customer-master record, but a name was captured on the invoice itself --}}
                    <div class="meta-col-label">Bill To</div>
                    <div class="customer-name">{{ $sumData->Customer_Name }}</div>
                    @if(!empty($sumData->Customer_Phone))<div>{{ $sumData->Customer_Phone }}</div>@endif
                @else
                    {{-- True walk-in sale: no customer captured at all — show the cash paid amount instead --}}
                    <div class="meta-col-label">Cash Paid</div>
                    <div class="customer-name">Rs. {{ number_format($cashPaid, 2) }}</div>
                @endif
            </td>
        </tr>
    </table>
    @endforeach

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
                <td class="center">{{ str_pad($rowNo++, 2, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $item->Item_code }}</td>
                <td class="bold">{{ $item->Item_description }}</td>
                <td class="center bold">{{ $item->QTY }}</td>
                <td class="center">{{ number_format($item->Unit_price, 2) }}</td>
                <td class="center">{{ $item->Per }}</td>
                <td class="center">
                    @if($rowDiscPercentage > 0)
                        {{ number_format($rowDiscPercentage, 1) }}%
                    @else
                        &mdash;
                    @endif
                </td>
                <td class="right bold">{{ number_format($item->Net_value, 2) }}</td>
            </tr>
            @if(!empty($item->Free_Issues) && $item->Free_Issues > 0)
            <tr>
                <td></td>
                <td colspan="7" style="font-size:10px;color:#555;font-style:italic;
                                       padding-top:0;border-bottom:1px solid #d9dee6;">
                    &nbsp;&nbsp;Free Issue: {{ $item->Free_Issues }}
                </td>
            </tr>
            @endif
            @endforeach

            {{-- Spacer rows --}}
            @for($i = 0; $i < max(0, 6 - count($pawnDetailsData)); $i++)
            <tr style="height:22px;">
                <td></td><td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td>
            </tr>
            @endfor

            {{-- ── Gross Amount ── --}}
            @foreach($pawnSumData as $sumData)
            <tr class="subtotal-row">
                <td colspan="7" style="text-align:right;">Gross Amount</td>
                <td style="text-align:right;">{{ number_format($sumData->Gross_Amount, 2) }}</td>
            </tr>

            {{-- ── Discount ── --}}
            @if(!empty($sumData->Discount) && $sumData->Discount > 0)
            <tr class="subtotal-row">
                <td colspan="7" style="text-align:right;">
                    Discount
                    @if(!empty($sumData->Gross_Amount) && $sumData->Gross_Amount > 0)
                        ({{ number_format(($sumData->Discount / $sumData->Gross_Amount) * 100, 2) }}%)
                    @endif
                </td>
                <td style="text-align:right;">({{ number_format($sumData->Discount, 2) }})</td>
            </tr>
            @endif

            {{-- ── Total ── --}}
            <tr class="total-row">
                <td colspan="3" style="text-align:left;">Total</td>
                <td style="text-align:center;">{{ $totalQty }} PCS</td>
                <td></td><td></td><td></td>
                <td style="text-align:right;">{{ number_format($sumData->Net_Amount, 2) }}</td>
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

    <table class="balance-section">
        <tr>
            {{-- Amount in words --}}
            <td class="balance-left">
                <div style="font-size:10px;color:#6b7280;margin-bottom:3px;text-transform:uppercase;letter-spacing:.4px;">
                    Amount Chargeable (in words)
                </div>
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;line-height:1.5;color:#1f2430;">
                    {{ $amountInWords }}
                </div>
            </td>

            {{-- Balances --}}
            <td class="balance-right-cell">
                <div class="balance-right">
                    <table>
                        <tr>
                            <td>Previous Balance:</td>
                            <td>{{ number_format($balance - ($sumData->Credite ?? $sumData->Net_Amount), 2) }}</td>
                        </tr>
                        <tr>
                            <td>Current Balance:</td>
                            <td>{{ number_format($balance, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- $cashPaid / $creditDue / $chequePaid already computed above, next to
         the invoice-type banner (same $sumData, same values). --}}
    <div class="payment-summary">
        <div class="payment-summary-title">Payment Method</div>
        @if($cashPaid > 0)
            <span class="pay-line"><strong>Cash Pay</strong> Paid: {{ number_format($cashPaid, 2) }}</span>
        @endif
        @if($creditDue > 0)
            <span class="pay-line"><strong>Credit Pay</strong> Balance: {{ number_format($creditDue, 2) }}</span>
        @endif
        @if($chequePaid > 0)
            <span class="pay-line"><strong>Cheque Pay</strong> Amount: {{ number_format($chequePaid, 2) }}</span>
        @endif
        @if($cashPaid <= 0 && $creditDue <= 0 && $chequePaid <= 0)
            <span class="pay-line neutral">No payment recorded</span>
        @endif
    </div>

    @if(!empty($sumData->invoice_remark))
    <div style="margin-top:8px; border-top:1px solid #d9dee6; padding-top:8px;">
        <div style="font-size:10px;color:#6b7280;margin-bottom:3px;text-transform:uppercase;letter-spacing:.4px;">
            Invoice Remark
        </div>
        <div style="font-size:12px;font-weight:600;line-height:1.5;">
            {{ $sumData->invoice_remark }}
        </div>
    </div>
    @endif
    @endforeach

    {{-- ════════════════════════════
         SIGNATURES
    ════════════════════════════ --}}
    <table class="signature-section">
        <tr>
            <td class="sig-box">
                <div style="height:40px;"></div>
                <div class="sig-line">Authorised By</div>
            </td>
            <td class="sig-box">
                <div style="height:40px;"></div>
                <div class="sig-line">Received By</div>
            </td>
        </tr>
    </table>

    <div class="print-footer">Thank you for your business!</div>

    {{-- ── Auto Print ── --}}
    <script>
        window.onload = function () {
            window.print();
            window.onafterprint = function () { window.close(); };
        };
    </script>

</body>
</html>
