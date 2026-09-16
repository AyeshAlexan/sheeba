<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Payment Receipt</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            background: #fff;
            padding: 20px 30px;
        }

        .header-wrap {
            position: relative;
            min-height: 50px;
            margin-bottom: 8px;
        }
        .receipt-title-right {
            position: absolute;
            right: 0;
            top: 0;
            font-size: 20px;
            font-weight: bold;
        }

        .company-block { margin-bottom: 10px; }
        .company-block h2 { font-size: 17px; font-weight: bold; margin-bottom: 2px; }
        .company-block p  { font-size: 11px; line-height: 1.5; color: #222; }

        .meta-row {
            display: flex;
            justify-content: space-between;
            border-top: 1.5px solid #000;
            border-bottom: 1.5px solid #000;
            padding: 6px 0;
            margin-bottom: 14px;
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

        .meta-right { text-align: right; font-size: 12px; line-height: 1.6; }
        .meta-right .supplier-name { font-size: 14px; font-weight: bold; }

        table.amount-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        table.amount-table th, table.amount-table td {
            border: 1px solid #999;
            padding: 6px 8px;
            font-size: 11px;
            text-align: left;
        }
        table.amount-table th { background: #f3f3f3; }
        table.amount-table td.amount { text-align: right; }

        .total-row td {
            font-weight: bold;
            font-size: 13px;
            border-top: 1.5px solid #000;
        }

        .note-block { margin-top: 16px; font-size: 11px; }
        .note-block .label { font-weight: bold; color: #444; }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
        }
        .signatures div { text-align: center; width: 40%; }
        .signatures .line { border-top: 1px solid #000; padding-top: 4px; font-size: 11px; }
    </style>
</head>
<body>

    <div class="header-wrap">
        <div class="receipt-title-right">PAYMENT RECEIPT</div>
    </div>

    @if($companyData)
    <div class="company-block">
        <h2>{{ $companyData->name }}</h2>
        <p>{{ $companyData->address }}</p>
        <p class="contact">
            {{ $companyData->co_number }}
            @if(!empty($companyData->one_number)) | {{ $companyData->one_number }} @endif
            @if(!empty($companyData->two_number)) | {{ $companyData->two_number }} @endif
        </p>
        @if(!empty($companyData->email))
            <p class="email">{{ $companyData->email }}</p>
        @endif
    </div>
    @endif

    @foreach($SupplierPaymentData as $payment)
    <div class="meta-row">
        <div class="meta-left">
            <table>
                <tr>
                    <td>PAYMENT NO:</td>
                    <td>{{ $payment->Payment_no }}</td>
                </tr>
                <tr>
                    <td>PAYMENT DATE:</td>
                    <td>{{ \Carbon\Carbon::parse($payment->Payment_date)->format('d M, Y') }}</td>
                </tr>
                <tr>
                    <td>PURCHASE NO:</td>
                    <td>{{ $payment->Purchase_no }}</td>
                </tr>
            </table>
        </div>
        <div class="meta-right">
            <div class="supplier-name">{{ $payment->Supplier_Name }}</div>
            <div>{{ $payment->Supplier_Code }}</div>
            <div>{{ $payment->Supplier_Phone }}</div>
        </div>
    </div>

    <table class="amount-table">
        <thead>
            <tr>
                <th>Payment Method</th>
                <th class="amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            @if($payment->cash_payment > 0)
            <tr><td>Cash</td><td class="amount">{{ number_format($payment->cash_payment, 2) }}</td></tr>
            @endif
            @if($payment->card_payment > 0)
            <tr><td>Card</td><td class="amount">{{ number_format($payment->card_payment, 2) }}</td></tr>
            @endif
            @if($payment->cheque_payment > 0)
            <tr><td>Cheque</td><td class="amount">{{ number_format($payment->cheque_payment, 2) }}</td></tr>
            @endif
            @if($payment->bank_transfer > 0)
            <tr><td>Bank Transfer</td><td class="amount">{{ number_format($payment->bank_transfer, 2) }}</td></tr>
            @endif
            <tr class="total-row">
                <td>Total Paid</td>
                <td class="amount">{{ number_format($payment->Payment_Amount, 2) }}</td>
            </tr>
            @if(!is_null($payment->totalBalance))
            <tr>
                <td>Remaining Balance</td>
                <td class="amount">{{ number_format($payment->totalBalance, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    @if(!empty($payment->Payment_note))
    <div class="note-block">
        <span class="label">Note:</span> {{ $payment->Payment_note }}
    </div>
    @endif
    @endforeach

    <div class="signatures">
        <div>
            <div class="line">Authorised By</div>
        </div>
        <div>
            <div class="line">Received By</div>
        </div>
    </div>

</body>
</html>
