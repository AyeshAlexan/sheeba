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
  background: #000000;    
  color: #FFF; 
  border-color: #dd1818;             
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

table.meta, table.balance { float: right; width: 36%; }
table.meta:after, table.balance:after { clear: both; content: ""; display: table; }



/* table meta */

table.meta th { width: 40%; }
table.meta td { width: 60%; }


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
    @if($isReprint ?? false)
    <div style="text-align:center; margin:0 0 10px; padding:6px; border:2px solid #c0392b; border-radius:4px; background:#fdecea; color:#c0392b; font-weight:bold; font-size:16px; letter-spacing:2px;">
        ⚠ REPRINT COPY
    </div>
    @endif
    <header>
        <address style="font-family: Georgia, serif;" >
            @foreach ($companyData as $comData)
            <h1 style="font-size: 18px">{{ $comData->name }}</h1>
             @endforeach
             @foreach ($branchDel as $comData)
            <p class="subhead">Address: {{ $comData->address }}</p>
            @endforeach
            @foreach ($companyData as $comData)
            <p class="subhead">Phone: {{ $comData->co_number }} </p>
            <p class="subhead">VAT:  {{ $comData->fax_number }}</p>
            <p class="subhead">Email: <a href="mailto:{{ $comData->email }}">{{ $comData->email }}</a></p>
            <p class="subhead">{{ $comData->Note }}</p>
        @endforeach
        </address>
        <span>     
             <img src="{{ public_path('images/logo2.jpg') }}" alt="Logo"
               height="60x"
               width="80px"/>
            </span>
    </header>
		<article>
			<address class="norm" style="line-height: 0.1;">
                @foreach ($customerData as $sumData)
                <p>Customer: {{ $sumData->First_name }}</p>
                <p>Tel: {{ $sumData->Contact_1 }}</p>
                <p>Address: {{ $sumData->Address_1 }}</p>
                <p>VAT Number: {{ $sumData->Passport }}</p>
            @endforeach
			</address>
			
			<table class="meta">
				<tr>
					<th><span >Invoice </span></th>
                    @foreach ($pawnSumData as $sumData)
					<td style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;"><span >{{ $sumData->Invoice_no }}</span></td>
                    @endforeach
				</tr>
				<tr>
					<th><span >Date</span></th>
                    @foreach ($pawnSumData as $sumData)
					<td style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;"><span >{{ $sumData->Invoice_date }}</span></td>
                    @endforeach
				</tr>
				<tr>
					<th><span >Ref No : </span></th>
                    @foreach ($pawnSumData as $sumData)
					<td style="border-width: 1px; padding: 0.5em; position: relative; text-align: left;border-radius: 0.25em; border-style: solid;"><span id="prefix" ><span >{{ $sumData->ref_no }}</span></td>
                    @endforeach
				</tr>
			</table>
			
			
			   <h4 class="card-title m-3"></h4>
			   
		   <table class="inventory">
                <thead>
                    <tr >
                        <th colspan="2" style="align-content: center;background-color: aliceblue;color: rgb(0, 0, 5);font-size: 18px; " > INVOICE </th>
                    </tr>
                </thead>
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
                            <td style="text-align: center">{{ $DetailsData['Item_code'] }}</td>
                            <td>{{ $DetailsData['Item_description'] }}</td>
                            <td style="text-align: center">{{ $DetailsData['Unit_price'] }}</td>
                             
                            <td style="text-align: center">{{ $DetailsData['QTY'] }}</td>
                            <td style="text-align: center">{{ $DetailsData['Free_Issues'] }}</td>
                            <td style="text-align: center">{{ $DetailsData['Discount'] ?? '0' }}</td>
                            <td style="text-align: center">{{ $DetailsData['Net_value'] }}</td>
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
                <tr>
                    <td colspan="4"></td>
                    <td colspan="2" style="text-align: right;font-size: 16px; font-weight: bold;">Sub Total:</td>
                    <td style="text-align: right;font-size: 16px; font-weight: bold;">{{ $sumData['Gross_Amount'] }}</td>
                </tr>


                @if (!empty($sumData['Discount']) && $sumData['Discount'] > 0)
                <tr>
                    <td colspan="4"></td>
                    <td colspan="2" style="text-align: right;font-size: 16px; font-weight: bold;">Discount:</td>
                    <td style="text-align: right;font-size: 16px; font-weight: bold;">{{ $sumData['Discount'] }}</td>
                </tr>
               @endif
               
                <tr>
                    <td colspan="4"></td>
                    <td colspan="2" style="text-align: right;font-size: 16px; font-weight: bold;">VAT Amount:</td>
                    <td style="text-align: right;font-size: 16px; font-weight: bold;">{{ $sumData['vat_amount'] }}</td>
                </tr>

            
                <tr class="total">
                    <td colspan="4"></td>
                    <td colspan="2" style="text-align: right;font-size: 16px; font-weight: bold;">Total Due :</td>
                    <td style="text-align: right;font-size: 16px; font-weight: bold;">{{ $sumData['after_vat_amount'] }}</td>
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