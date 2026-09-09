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
            <div>ADVANCE PAYMENT INVOICE</div>
        </div>

        <div id="company" class="clearfix" style="font-size: 14px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
            @foreach ($advance_payment as $Data)
            <div style=" font-size: 14px;">Invoice No :&nbsp;{{ $Data->invoice_no}}</div>
            <div style=" font-size: 14px;">Date :&nbsp;{{ $Data->date}}</div>
            @endforeach
        </div>

        <div id="project" style=" font-size: 14px; font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">
            @foreach ($advance_payment_cus_data as $Data)
                <div><span style=" font-size: 14px;">Customer&nbsp;:&nbsp;{{ $Data->First_name}}</span></div>
                <div><span style=" font-size: 14px;">Tel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;{{ $Data->Contact_1}}</span></div>
                <div><span style=" font-size: 14px;">Address&nbsp;&nbsp;&nbsp;:&nbsp;{{ $Data->Address_1}}</span></div>
                <div><span style=" font-size: 14px;">NIC&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;{{ $Data->NIC}}</span></div>
            @endforeach
        </div>

    </header>

    <div class="">
        <font size="2"  >
        <table >
            <tr class="heading">
                <!--<td>Category</td>-->
                <td style="width:50%; text-align: center;"><b>DESCRIPTION</b></td>
                <td style="width:25%; text-align: center;"><b>&nbsp;</b></td>
                <td style="width:25%; text-align: right;"><b>AMOUNT</b></td>
            </tr>

            @foreach($advance_payment as $Data)
            <tr class="item">
                <td style="text-align: center;">{{$Data['description']}}</td>
                <td style="text-align: center;">&nbsp;</td>
                <td style="text-align: right;">{{ number_format($Data['amount'], 2) }}</td>
            </tr>
            @endforeach

            <br>

            <tr class="total">
                <td style="border-top: 1px solid #1a1a1a; border-top-style: solid;"></td>
                <td style="border-top: 1px solid #1a1a1a; border-top-style: solid; text-align: right;">
                    Total Amount :
                </td>
                <td style="border-top: 1px solid #1a1a1a; border-top-style: solid; text-align: right;">
                    @foreach($advance_payment as $Data)
                        {{ number_format($Data['amount'], 2) }}
                    @endforeach
                </td>
            </tr>

        </table>
    </font>

    </div>
    <br>
    <div class="border">
    <div class="clearfix">THANK YOU & COME AGAIN !</div>
    </div>
</body>

</html>
