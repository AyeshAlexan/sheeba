<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        /* heading */


table { font-size: 75%; table-layout: fixed; width: 100%; }
th {
  background: transparent;
  color: #000;              /* Set text color to black or any color you prefer */
  border: 1px solid #dd1818;
}


header:after { clear: both; content: ""; display: table; }

header address { float: left; font-size: 95%; font-style: normal; line-height: 1.25; margin: 0 1em 1em 0; }
article address.norm h4 {
	font-size: 125%;
	font-weight: bold;
}
article address.norm { float: left; font-size: 95%; font-style: normal; font-weight: normal;  line-height: 0.9; margin: 0 1em 1em 0; }
header address p { margin: 0 0 0.25em; line-height: 0.8; }
header span, header img { display: block; float: right; }
header span { margin: 0 0 1em 1em; max-height: 25%; max-width: 60%; position: relative; }
header input { cursor: pointer; -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)"; height: 100%; left: 0; opacity: 0; position: absolute; top: 0; width: 100%; }
/* article */

article, article address, table.meta, table.inventory { margin: 0 0 3em; }
article:after { clear: both; content: ""; display: table; }
article h1 { clip: rect(0 0 0 0); position: absolute; }

article address { float: left; font-size: 125%; font-weight: bold; }

/* table meta & balance */

table.meta, table.balance { float: right; width: 28%; }
table.meta:after, table.balance:after { clear: both; content: ""; display: table; }



/* table meta */




table.inventory { clear: both; width: 100%; }
table.inventory th:first-child {
	width:50px;
}
table.inventory th:nth-child(2) {
	width:300px;
}
table.inventory th { font-weight: bold; text-align: center; }

/* table balance */

table.balance th, table.balance td { width: 50%; }
table.balance td { text-align: right; }

/* aside */

aside h1 { border: none; border-width: 0 0 1px; margin: 0 0 1em; }
aside h1 { border-color: #999; border-bottom-style: solid; }
    </style>
</head>
<body>
    <header>
        <address style="font-family: Georgia, serif;" >
            @foreach ($companyData as $comData)
            <h1 style="font-size: 18px">{{ $comData->name }}</h1>
            <p class="subhead">{{ $comData->Note }}</p>
            <p class="subhead">Address: {{ $comData->address }}</p>
            <p class="subhead">Phone: {{ $comData->co_number }} </p>
            <p class="subhead">Email: <a href="mailto:{{ $comData->email }}">{{ $comData->email }}</a></p>

        @endforeach
        </address>
        <span>
  <img src="{{ asset('images/image.jpg') }}" alt="Logo">
            </span>
    </header>

    <div style="text-align: center; margin: 0;">
    <h2 style="font-size: 24px; font-weight: bold; margin: 4px 0; text-transform: uppercase; letter-spacing: 1px;">Invoice</h2>
</div>


		<article>
			<address class="norm" style="line-height: 0.1;">
                @foreach ($customerData as $sumData)
                 <p style="font-size:15px;">Customer: {{ $sumData->First_name }}</p>
                <p>Tel: {{ $sumData->Contact_1 }}</p>
                <p style="font-size:15px;">Address: {{ $sumData->Address_1 }}</p>
            @endforeach
			</address>

			<table class="meta">
				<tr>
    <th style="font-size: 16px; width: 150px;"><span>Invoice</span></th>

    @foreach ($pawnSumData as $sumData)
    <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: left; border-radius: 0.25em; border-style: solid;">
        <span style="font-size: 16px;">{{ $sumData->Invoice_no }}</span>
    </td>
    @endforeach
</tr>
<tr>
    <th><span style="font-size: 16px;">Date</span></th>
    @foreach ($pawnSumData as $sumData)
    <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: left; border-radius: 0.25em; border-style: solid;">
        <span style="font-size: 16px;">{{ $sumData->Invoice_date }}</span>
    </td>
    @endforeach
</tr>
<tr>
    <th><span style="font-size: 16px;">Sales Rep :</span></th>
    @foreach ($pawnSumData as $sumData)
    <td style="border-width: 1px; padding: 0.5em; position: relative; text-align: left; border-radius: 0.25em; border-style: solid;">
        <span style="font-size: 16px;">{{ $sumData->Salesmen }}</span>
    </td>
    @endforeach
</tr>

			</table>


			<table class="inventory">
                <thead>
                    <tr  style="background-color: rgb(230, 132, 5)">
                        <th>CODE</th>
                        <th>PRODUCT NAME</th>
                        <th>ITEM PRICE</th>

                        <th>QTY</th>
                         <th>FREE ISSUE</th>
                        <th>DISCOUNT</th>
                        <th>AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 0; @endphp
                    @foreach($pawnDetailsData as $DetailsData)
                        <tr>
                            <td style="width: 10%;text-align: center">{{ $DetailsData['Item_code'] }}</td>
                            <td style="width: 30%;font-size:14px">
                                    {{ $DetailsData['Item_description'] }}
                            </td>

                            <td style="width: 10%;text-align: center;font-size:14px">{{ $DetailsData['Unit_price'] }}</td>

                            <td style="width: 10%;text-align: center;font-size:14px">{{ $DetailsData['QTY'] }}</td>
                            <td style="width: 10%;text-align: center;font-size:14px">{{ $DetailsData['Free_Issues'] }}</td>
                            <td style="width: 15%;text-align: center;font-size:14px">{{ $DetailsData['Discount'] ?? '0' }}</td>
                            <td style="width: 15%;text-align: center;font-size:14px">{{ $DetailsData['Net_value'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                       <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                @foreach ($pawnSumData as $sumData)
                    @php
                        $cashPaid = (float) ($sumData['Cash_Pay'] ?? 0) + (float) ($sumData['Half_Payment'] ?? 0);
                        $creditDue = (float) ($sumData['Credite'] ?? 0);
                        $chequePaid = (float) ($sumData['Cheque'] ?? 0);
                    @endphp
                    <tr>
                        <td colspan="4" rowspan="2" style="vertical-align: middle;">
                            <p style="font-size:14px"><strong><u>OP Balance:</u></strong>  Rs. {{ number_format($balance - ($sumData['Credite'] ?? $sumData['Net_Amount']), 2) }}</p>
                            <p style="font-size:14px"><strong><u>Total Balance:</u></strong> Rs. {{ number_format($balance, 2) }}</p>
                        </td>
                        <td colspan="2"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="font-size:13px;">
                            <strong>Payment Method:</strong>
                            <span style="color:{{ $cashPaid > 0 ? '#176b43' : '#777' }};">{{ $cashPaid > 0 ? '[x]' : '[ ]' }} Cash Pay</span>
                            <span style="color:{{ $creditDue > 0 ? '#176b43' : '#777' }};">{{ $creditDue > 0 ? '[x]' : '[ ]' }} Credit Pay</span>
                            <span style="color:{{ $chequePaid > 0 ? '#176b43' : '#777' }};">{{ $chequePaid > 0 ? '[x]' : '[ ]' }} Cheque Pay</span>
                        </td>
                        <td colspan="3" style="font-size:13px;text-align:right;">
                            Cash Paid: {{ number_format($cashPaid, 2) }} | Credit Balance: {{ number_format($creditDue, 2) }}
                        </td>
                    </tr>
                @endforeach


                @foreach ($pawnSumData as $sumData)
                <tr>
                    <td colspan="4"></td>
                    <td colspan="2" style="text-align: right;font-size: 16px; font-weight: bold;">Gross Amount:</td>
                    <td style="text-align: right;font-size: 16px; font-weight: bold;">{{ $sumData['Gross_Amount'] }}</td>
                </tr>


                @if (!empty($sumData['Discount']) && $sumData['Discount'] > 0)
                <tr>
                    <td colspan="4"></td>
                    <td colspan="2" style="text-align: right;font-size: 16px; font-weight: bold;">Discount:</td>
                    <td style="text-align: right;font-size: 16px; font-weight: bold;">{{ $sumData['Discount'] }}</td>
                </tr>
               @endif

                <tr class="total">
                    <td colspan="4"></td>
                    <td colspan="2" style="text-align: right;font-size: 16px; font-weight: bold;">Net Amount :</td>
                    <td style="text-align: right;font-size: 16px; font-weight: bold;">{{ $sumData['Net_Amount'] }}</td>
                </tr>
            @endforeach
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td></td>
            </tr>

            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            <tr>

                <td style=" line-height: 0.8em; text-align: center;">........................................</td>
                <td style="line-height: 0.8em;"><b></b></td>
                <td style=" line-height: 0.8em; text-align: center;">........................................</td>
                <td style="line-height: 0.8em;"><b></b></td>
                <td style=" line-height: 0.8em; text-align: center;">........................................</td>
                <td style="line-height: 0.8em; text-align: right;"></td>
            </tr>




            <tr  style="height: 100px;">
                <td style="line-height: 0.8em; text-align: center;">Signature</td>
                <td style="line-height: 0.8em;"><b></b></td>
                <td style="line-height: 0.8em; text-align: center;">Checked By Office Staff</td>
                <td style="line-height: 0.8em;"><b></b></td>
                <td style="line-height: 0.8em; text-align: center;">Received By - Customer</td>
                <td style="line-height: 0.8em; text-align: right;"></td>

            </tr>

			</table>
		</article>
</body>
</html>