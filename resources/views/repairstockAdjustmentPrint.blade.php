<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Sample</title>
    <link rel="stylesheet" href="style.css">
    <style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.container {
    /* display: block; */
    /* width: 100%; */
    max-width: 280px;
    padding: 10px;
    /* margin: 50px auto 0; */
    /* box-shadow: 0 3px 10px rgb(0 0 0 / 0.2); */
}


.receipt_header {
    padding-bottom: 40px;
    border-bottom: 1px dashed #000;
    text-align: center;
}

.receipt_header h1 {
    font-size: 15px;
    margin-bottom: 5px;
    text-transform: uppercase;
}

.receipt_header h1 span {
    display: block;
    font-size: 25px;
}

.receipt_header h2 {
    font-size: 14px;
    color: #727070;
    font-weight: 300;
}

.receipt_header h2 span {
    display: block;
}

.receipt_body {
    margin-top: 25px;
}

table {
    width: 100%;
}

thead, tfoot {
    position: relative;
}

thead th:not(:last-child) {
    text-align: left;
}

thead th:last-child {
    text-align: right;
}

thead::after {
    content: '';
    width: 100%;
    border-bottom: 1px dashed #000;
    display: block;
    position: absolute;
}


tbody td:not(:last-child), tfoot td:not(:last-child) {
    text-align: left;
}

tbody td:last-child, tfoot td:last-child{
    text-align: right;
}

tbody tr:first-child td {
    padding-top: 15px;
}

tbody tr:last-child td {
    padding-bottom: 15px;
}

tfoot tr:first-child td {
    padding-top: 15px;
}

tfoot::before {
    content: '';
    width: 100%;
    border-top: 1px dashed #000;
    display: block;
    position: absolute;
}

tfoot tr:first-child td:first-child, tfoot tr:first-child td:last-child {
    font-weight: bold;
    font-size: 20px;
}

.date_recei {
    display: flex;
    justify-content: left;
    column-gap: px;
}

.date_time_con {
    display: flex;
    justify-content: center;
    column-gap: 15px;
}

.items {
    margin-top: 25px;
}
    </style>

    <style>
        .company-record {
    background-color: #f9f9f9; /* Light gray background for better readability */
    border: 1px solid #ddd; /* Light border for separation */
    border-radius: 8px; /* Rounded corners for modern look */
    padding: 20px; /* Add space inside the div */
    margin-bottom: 20px; /* Add space between records if there are multiple */
    font-family: Arial, sans-serif; /* Clean font */
}

.company-record h3 {
    color: #333; /* Dark text for visibility */
    font-size: 20px; /* Larger font size for emphasis */
    margin-bottom: 10px; /* Space between elements */
    align-content: center;
}

.company-record h2 {
    color: #555; /* Slightly lighter text */
    font-size: 15px;
    margin-bottom: 8px;
}

.company-record p {
    color: #666; /* Subtle gray for secondary text */
    font-size: 11px;
    margin-bottom: 8px;
}

.company-record h5 {
    color: #444; /* Consistent color tone */
    font-size: 10px;
}

.company-record a {
    color: #007BFF; /* Blue for links */
    text-decoration: none; /* Remove underline */
}

.company-record a:hover {
    text-decoration: underline; /* Add underline on hover for accessibility */
}

    </style>
</head>
<body>
    
<div class="container">
   
    <div class="company-record" style="text-align: center;">
        @foreach ($companyData as $comData)
        <h3><span>{{ $comData->name}}</span></h3>
        <h2><span>{{ $comData->address}}</span></h2>
        <p>{{ $comData->co_number}} | {{ $comData->fax_number}}</p> 
        @endforeach
    
        @foreach ($companyData as $comData)
        <h5>Email : <a href="mailto:{{ $comData->email}}">{{ $comData->email}}</a></h5>
        @endforeach
    </div>

    <div>
        <table style="width:100%">
            <thead>
                @foreach($pawnSumData as $sumData)
                <tr>
                    <td style="width: 50%;font-size:14px;font-weight: bold;">Date :&nbsp;&nbsp;{{ $sumData->Invoice_date}}</td>
                    <td style="width: 50%;font-size: 14px;font-weight: bold;text-align: center;">Inv.No :&nbsp;&nbsp;{{ $sumData->Invoice_no}}</td>
                </tr>
                @endforeach
            </thead>
        </table>
    </div>

    <div>
        <table>
            <thead>
                <tr style="background-color: #222121; color: #f7f7f7">
                    <th style="text-align: center; font-size: 13px; width: 20%;">Discr</th>
                    <th style="text-align: center; font-size: 13px; width: 20%;">S.Pr</th>
                    <th style="text-align: center; font-size: 13px; width: 20%;">Dis.Pr</th>
                    <th style="text-align: center; font-size: 13px; width: 15%;">QTY</th>
                    <th style="text-align: center; font-size: 13px; width: 25%;">S.Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pawnDetailsData as $DetailsData)
                <tr>
                    <td colspan="5" style="text-align: left; font-size: 13px; ">{{$DetailsData['Item_description']}}</td>
                </tr>
                <tr>
                    <td style="text-align: center; font-size: 13px; ">{{$DetailsData['Item_code']}}</td>
                    <td style="text-align: center; font-size: 13px; ">{{$DetailsData['Unit_price']}}</td>
                    <td style="text-align: center; font-size: 13px; ">{{$DetailsData['Discount']}}</td>
                    <td style="text-align: center; font-size: 13px; ">{{$DetailsData['QTY']}}</td>
                    <td style="text-align: center; font-size: 13px; ">{{$DetailsData['Net_value']}}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"><hr style="border-top: 1px dotted rgb(0, 0, 0);"></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="2" style="text-align: left; font-size: 16px; "><strong>Total Amt </strong></td>
                    <td colspan="2" style="text-align: right;">        
                         <strong>
                        @foreach($pawnSumData as $sumData)
                            {{ $sumData['Amount	'] }}<br>
                        @endforeach
                    </strong>
                </td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div class="receipt_body">        
        <div class="items">
          
            {{-- <tfoot>
                <tr>
                    <td colspan="5"><hr style="border-top: 1px dotted rgb(0, 0, 0);"></td>
                </tr>
                <tr>
                    
                    <td colspan="2" style=""><strong>Total Amount </strong></td>  
                    <td></td>
                    <td colspan="2"> &nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" style=""><strong>Card/Cheque/Credit</strong></td>  
                    <td></td>
                    <td colspan="2"> &nbsp;&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="2" style=""><strong>Gross Amount</strong></td>  
                    <td></td>
                    <td colspan="2"> &nbsp;&nbsp;</td>
                </tr>

                
                <tr>
                    <td colspan="2" style=""><strong>Cash Rvd</strong></td>  
                    <td></td>
                    <td colspan="2"> &nbsp;&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="2" style=" "><strong>balance</strong></td>  
                    <td></td>
                    <td colspan="2"> &nbsp;&nbsp;</td>
                </tr>
            </tfoot> --}}
{{-- 
                <tfoot>
                    <tr>
                        <td colspan="4"><hr></td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;</td>
                        <td>&nbsp;&nbsp;</td>
                         <td>&nbsp;&nbsp;</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" style="text-align: left;">
                            <strong style="font-size: 14px">Total Amount :</strong>
                        </td>
                        <td>
                            <strong>
                                @foreach($pawnSumData as $sumData)
                                    {{ $sumData['Net_Amount'] }}<br>
                                @endforeach
                            </strong>
                        </td>
                    </tr>
                    

                    <tr>
                        <td></td>
                        <td colspan="2" align="left"><Strong style="font-size: 14px">Card/Chq/credit :</Strong></td>
                        <td style="border-color: #000">      
                            @foreach($pawnSumData as $sumData)
                            <p><strong style="font-size: 13px">{{$sumData['Gross_Amount']}}</strong></p>
                            @endforeach
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td colspan="2" align="left"><Strong style="font-size: 14px">Gross Amount  :</Strong></td>
                        <td style="border-color: #000">       
                            @foreach($pawnSumData as $sumData)
                            <p><strong style="font-size: 13px">{{$sumData['Gross_Amount']}}</strong></p>
                            @endforeach
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td colspan="2" align="left"><Strong style="font-size: 14px">Cash Rvt :</Strong></td>
                        <td style="border-color: #000">      
                            @foreach($pawnSumData as $sumData)
                            <p><strong style="font-size: 13px">{{$sumData['Cash_Pay']}}</strong></p>
                            @endforeach
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td colspan="2" align="left"><Strong style="font-size: 14px">Balance :</Strong></td>
                        <td style="border-color: #000">      
                            @foreach($pawnSumData as $sumData)
                            <p><strong style="font-size: 13px">{{$sumData['cash_balance']}}</strong></p>
                            @endforeach
                        </td>
                    </tr>
                </tfoot> --}}
            </table>
        </div>
    </div>
</div>

</body>
</html>