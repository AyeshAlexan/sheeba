<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Delivery Order</title>

    <style>
        .clearfix:after { content:""; display:table; clear:both; font-family:'Helvetica Neue','Helvetica',Helvetica,Arial,sans-serif; }
        body { position:relative; width:17cm; height:29.7cm; margin:0 auto; color:#001028; background:#FFFFFF; font-family:Arial,sans-serif; font-size:16px; }
        .subhead { font-size:17px; }
        header { padding:10px 0; margin-bottom:10px; }
        .border { border-top:1px solid #000000; border-bottom:1px solid #000000; text-align:center; color:#000000; margin:0 0 20px 0; line-height:1.4em; }
        h1 { color:#5e6163; font-size:1.5em; margin-bottom:5px; font-weight:normal; text-align:center; }
        #project { float:left; }
        #company { float:right; text-align:left; }
        #project div, #company div { white-space:nowrap; }
        table { width:100%; border-collapse:collapse; border-spacing:0; margin-bottom:20px; }
        table th, table td { text-align:center; }
        table th { padding:5px 20px; background:#eee; border-bottom:1px solid #C1CED9; white-space:nowrap; font-weight:normal; }
        table td { padding:5px; text-align:left; }
        table td.grand { border-top:1px solid #494d52; border-bottom:1px solid #494d52; }
        footer { color:#5D6975; width:100%; height:30px; position:absolute; bottom:0; border-top:1px solid #C1CED9; padding:8px 0; text-align:center; }
        .signatures { margin-top:60px; }
        .signatures td { border-top:1px solid #000; text-align:center; padding-top:8px; }
    </style>
</head>

<body>
    <header class="clearfix">
        <div class="border">
            @foreach ($companyData as $comData)
            <h1>{{ $comData->name }}</h1>
            <div class="subhead">Address : {{ $comData->address }}</div>
            <div class="subhead">Phone : {{ $comData->co_number }} , {{ $comData->fax_number }}</div>
            <div class="subhead">Email : <a href="mailto:{{ $comData->email }}">{{ $comData->email }}</a></div>
            @endforeach
            <br>
        </div>

        <div style="text-align: center;">
            <div>DELIVERY ORDER</div>
        </div>

        <div id="company" class="clearfix" style="font-size: 14px;">
            @foreach ($doSumData as $sumData)
            <div>DO No :&nbsp;{{ $sumData->DO_no }}</div>
            <div>DO Date :&nbsp;{{ $sumData->DO_date }}</div>
            <div>Invoice No :&nbsp;{{ $sumData->Invoice_no }}</div>
            @endforeach
        </div>

        <div id="project" style="font-size: 14px;">
            @foreach ($doSumData as $sumData)
            <div>Customer :&nbsp;{{ $sumData->Customer_Name }}</div>
            <div>Phone :&nbsp;{{ $sumData->Customer_Phone }}</div>
            @endforeach
        </div>
    </header>

    <div>
        <font size="2">
        <table>
            <tr class="heading">
                <td>PRODUCT CODE</td>
                <td>PRODUCT NAME</td>
                <td>BATCH</td>
                <td>QTY</td>
                <td>ITEM PRICE</td>
                <td style="text-align: right;">AMOUNT</td>
            </tr>

            @foreach ($doDetailsData as $line)
            <tr>
                <td>{{ $line->Item_code }}</td>
                <td>{{ $line->Item_description }}</td>
                <td>{{ $line->batch_no }}</td>
                <td>{{ $line->QTY }}</td>
                <td>{{ $line->Unit_price }}</td>
                <td style="text-align: right;">{{ $line->Net_value }}</td>
            </tr>
            @endforeach

            <tr class="total">
                <td class="grand"></td>
                <td class="grand"></td>
                <td class="grand"></td>
                <td colspan="2" class="grand"><b>Net Amount :</b></td>
                <td class="grand" style="text-align: right;">
                    <b>
                        @foreach ($doSumData as $sumData)
                            {{ $sumData->Net_Amount }}
                        @endforeach
                    </b>
                </td>
            </tr>
        </table>
        </font>
    </div>

    <table class="signatures">
        <tr>
            <td style="width:50%;">
                @foreach ($doSumData as $sumData)
                    {{ $sumData->Delivered_By }}
                @endforeach
                <br>Delivered By
            </td>
            <td style="width:50%;">
                @foreach ($doSumData as $sumData)
                    {{ $sumData->Received_By }}
                @endforeach
                <br>Received By
            </td>
        </tr>
    </table>

    <br>
    <div class="border">
        <div class="clearfix">THANK YOU & COME AGAIN !</div>
    </div>
</body>

</html>
