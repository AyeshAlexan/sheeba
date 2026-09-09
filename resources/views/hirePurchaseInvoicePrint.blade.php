<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>invoice</title>


    <!-- Favicon -->
    {{-- <link rel="icon" href="./images/favicon.png" type="image/x-icon" /> --}}

    <!-- Invoice styling -->
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        a {
            color: #5D6975;
            text-decoration: underline;
        }

        body {
            position: relative;
            width: 17cm;
            height: 29.7cm;

            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 16px;
            font-family: Arial;
        }

        .subhead {
            font-size: 17px;
        }

        header {
            padding: 10px 0;
            margin-bottom: 10px;
        }

        #logo {
            text-align: center;
            margin-bottom: 10px;
        }

        #logo img {
            width: 90px;
        }

        .border {
            border-top: 1px solid #000000;
            border-bottom: 1px solid #000000;
            text-align: center;
            color: #000000;
            margin: 0 0 20px 0;
            line-height: 1.4em;
        }

        h1 {

            color: #5e6163;
            font-size: 1.5em;
            margin-bottom: 5px;
            font-weight: normal;
            text-align: center;

            /* background: url(assets/pdf/dimension.png); */
        }

        #project {
            float: left;
        }

        #project span {
            /* color: #2f3030; */
            text-align: left;
            width: 60px;
            margin-right: 10px;
            display: inline-block;
            font-size: 16px;
        }

        #company {
            float: right;
            text-align: left;
        }

        #project div,
        #company div {
            white-space: nowrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;

        }

        table tr:nth-child(2n-1) td {
            background: #ffffff;
        }

        table th,
        table td {
            text-align: center;
        }

        table th {
            padding: 5px 20px;
            color: #ffffff;
            border-bottom: 1px solid #C1CED9;
            white-space: nowrap;
            font-weight: normal;
        }

        table .service,
        table .desc {
            text-align: left;
        }

        table td {
            padding: 5px;
            text-align: left;
        }

        table td.service,
        table td.desc {
            vertical-align: top;
        }

        table td.unit,
        table td.total {
            font-size: 1.2em;
        }

        .qty {
            font-size: 1.1em;
        }

        table td.grand {
            border-top: 1px solid #494d52;
            border-bottom: 1px solid #494d52;
            ;
        }

        #notices .notice {
            color: #5D6975;
            font-size: 1.1em;
        }

        footer {
            color: #5D6975;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #C1CED9;
            padding: 8px 0;
            text-align: center;
        }

    </style>


    <style>
        /* body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            text-align: center;
            color: #777;
        } */

        body h1 {
            font-weight: 300;
            margin-bottom: 0px;
            padding-bottom: 0px;
            color: #000;
        }

        body h3 {
            font-weight: 300;
            margin-top: 10px;
            margin-bottom: 20px;
            font-style: italic;
            color: #555;
        }

        body a {
            color: #06f;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 10px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 14px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .tr_details {
            max-width: 800px;
            border-bottom: none;
            line-height: 1px;
            color: #222121;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }


        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 35px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

    </style>

</head>

<body>
    <header class="clearfix">
        {{-- <div id="logo">
            <img src=" {{'data:image/png;base64,'.base64_encode(file_get_contents(public_path('assets/pdf/logo.png')))}}
        " alt="logo" style="width: 30px; height: 30px">
        </div> --}}

        <div class="border">
            @foreach ($companyData as $comData)
            <h1>{{ $comData->name}}</h1>
            <!--<div class="subhead" style="text-align: center; font-size: 20px; color: #3d3d3d; font-weight: bold;">-->
            <!--    REPAIRING CENTER</div>-->
            <div class="subhead">Address : {{ $comData->address}}</div>
            <div class="subhead">Phone : {{ $comData->co_number}} , {{ $comData->fax_number}}</div>
            {{-- <div class="subhead">Fax : {{ $comData->co_number}}
        </div> --}}
        <div class="subhead">Email : <a href="mailto:{{ $comData->email}}">{{ $comData->email}}</a></div>
        @endforeach
        <br>
        </div>

        <div style="text-align: center;">
            <div>HIRE PURCHASE INVOICE</div>
        </div>

        <div id="company" class="clearfix" style="font-size: 14px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
            @foreach ($hirePurchaseSumData as $sumData)
            <div style=" font-size: 14px;">Invoice No :&nbsp;{{ $sumData->invoice_no}}</div>
            <div style=" font-size: 14px;">Date :&nbsp;{{ $sumData->invoice_date}}</div>
            <!--<div>Job No :&nbsp;{{ $sumData->Job_no}}</div>-->
            @endforeach
        </div>

        <div id="project" style=" font-size: 14px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
            @foreach ($hirePurchaseSumData as $sumData)
                <div><span style=" font-size: 14px;">Customer :&nbsp;{{ $sumData->customer_name}}</span></div>
                <div><span style=" font-size: 14px;">Tel &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;{{ $sumData->customer_phone}}</span></div>
                <div><span style=" font-size: 14px;">Address &nbsp;&nbsp;&nbsp;:&nbsp;{{ $sumData->customer_address}}</span></div>
                <!--<div><span>NIC &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;{{ $sumData->NIC}}</span></div>-->
            @endforeach
        </div>

    </header>

    <div class="">
        <font size="2"  >
        <table >
            <tr class="heading">
                <!--<td>Category</td>-->
                <td style="width:10%; text-align: center;"><b>CODE</b></td>
                <td style="width:45%; text-align: center;"><b>PRODUCT NAME</b></td>
                <td style="width:5%; text-align: center;"><b>QTY</b></td>
                <td style="width:13%; text-align: center;"><b>PRICE</b></td>

                {{-- @php
                    // Initialize the variable
                    $discount = 0;
                    $advance = 0;
                @endphp

                @foreach($hirePurchaseDetailsData as $DetailsData)
                    // Add the discount to the variable
                    @php
                        $discount += $DetailsData['Discount'];
                    @endphp
                @endforeach --}}


                {{-- @if ($discount != null) --}}
                <td style=" width:13%; text-align: center;"><b>DISCOUNT</b></td>
                {{-- @else
                <td>&nbsp;</td>
                @endif --}}

                <td style=" width:13%; text-align: center;"><b>AMOUNT</b></td>
            </tr>

            @foreach($hirePurchaseDetailsData as $DetailsData)
            <tr class="item">
                <td style="text-align: center;">{{$DetailsData['Item_s_code']}}</td>
                <td style="text-align: center;">{{$DetailsData['item_description']}}</td>
                <td style="text-align: center;">{{$DetailsData['qty']}}</td>
                <td style="text-align: right;">{{ number_format($DetailsData['unit_price'], 2) }}</td>
                {{-- @if ($discount != null) --}}
                <td style="text-align: right;">{{ number_format($DetailsData['discount'], 2) }}</td>
                {{-- @else
                <td>&nbsp;</td>
                @endif --}}
                <td style="text-align: right;">{{ number_format($DetailsData['net_value'], 2) }}</td>

            </tr>
            @endforeach

            @foreach ($hirePurchaseSumData as $sumData)
            <tr class="item">
                <td>Serial No:</td>
                <td colspan="4">&nbsp;{{ $sumData->serial_number}} </td>
            </tr>
            @endforeach

            <br>

            <tr class="total">
                <td style="border-top: 1px solid #1a1a1a; border-top-style: solid;"></td>
                <td style="border-top: 1px solid #1a1a1a; border-top-style: solid;"></td>

                <td colspan ="2" style="border-top: 1px solid #1a1a1a; border-top-style: solid;">Total Amount	:</td>
                <td colspan ="2" style="text-align: right; border-top: 1px solid #1a1a1a; border-top-style: solid;">
                        @foreach($hirePurchaseSumData as $sumData)
                            {{ number_format($hirePurchaseDetailsNetTotal, 2) }}
                        @endforeach
                </td>
            </tr>

            <tr class="total">
                <td></td>
                <td></td>

                <td colspan ="2">Service Charges :</td>
                <td colspan ="2" style="text-align: right;">
                    @foreach($hirePurchaseSumData as $sumData)
                        {{ number_format($sumData['document_charge'], 2) }}
                        @endforeach

                </td>
            </tr>

            <tr class="total">
                <td></td>
                <td></td>

                <td colspan ="2">Transport :</td>
                <td colspan ="2" style="text-align: right;">
                    @foreach($hirePurchaseSumData as $sumData)
                        {{ number_format($sumData['transport'], 2) }}
                    @endforeach
                </td>
            </tr>
            <tr class="total">
                <td></td>
                <td></td>

                <td  colspan ="2">Instalment  Amount :</td>
                <td colspan ="2" style="text-align: right;">
                    @foreach($hirePurchaseSumData as $sumData)
                        {{ number_format($sumData['instalment_amount'], 2) }}
                    @endforeach
                </td>
            </tr>
            <tr class="total">
                <td></td>
                <td></td>

                <td  colspan ="2">Down Payment :</td>
                <td colspan ="2" style="text-align: right;">
                    @foreach($hirePurchaseSumData as $sumData)
                       (- {{ number_format($sumData['down_payment'], 2) }} )
                    @endforeach
                </td>
            </tr>
            <tr class="total">
                <td style="border-top: 1px solid #1a1a1a; border-top-style: solid;"></td>
                <td style="border-top: 1px solid #1a1a1a; border-top-style: solid;"></td>

                <td colspan ="2" style="border-top: 1px solid #1a1a1a; border-top-style: solid;"><b>Net Amount	:</b></td>
                <td colspan ="2" style="text-align: right; border-top: 1px solid #1a1a1a; border-top-style: solid;">
                    <b>
                        @foreach($hirePurchaseSumData as $sumData)
                            {{ number_format($sumData['due_amount'], 2) }}
                        @endforeach
                    </b>
                </td>
            </tr>
        </table>
    </font>

    <font size="2"  >
    <div style="text-align: center;">
        <div>INSTALLENT DETAILS</div>
    </div>

    <table border="1px" align="center">
        <tr class="heading" >
            {{-- <th style=" width:20%; text-align: center;">&nbsp;</th> --}}
            <td style=" width:20%; text-align: center;">INSTALLENT DATE</td>
            <td style=" width:20%; text-align: center;">INSTALLENT AMOUNT</td>
            <td style=" width:20%; text-align: center;">AMOUNT PAY</td>
            {{-- <td style=" width:20%; text-align: center;">&nbsp;</td> --}}
        </tr>

        @foreach ($installmentData as $installment)
        <tr align="center">
            {{-- <td style=" width:20%; text-align: center;">&nbsp;</td> --}}
            <td style=" width:20%; text-align: center;">{{$installment['instalment_date']}}</td>
            <td style=" width:20%; text-align: center;">{{number_format($installment['instalment_amount'], 2)}}</td>
            <td style=" width:20%; text-align: center;">&nbsp;</td>
            {{-- <td style=" width:20%; text-align: center;">&nbsp;</td> --}}
        </tr>
        @endforeach

    </table>
    </font>



    </div>
    <br>
    <div class="border">
    <div class="clearfix">THANK YOU & COME AGAIN !</div>
    </div>
</body>

</html>
