<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Print Jobsheet</title>

    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
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
            margin-bottom: 30px;
        }

        #logo {
            text-align: center;
            margin-bottom: 10px;
        }

        #logo img {
            width: 90px;
        }

        .border {
            border-top: 1px solid #5e6163;
            border-bottom: 1px solid #5e6163;
            text-align: center;
            color: #5e6163;
            margin: 0 0 20px 0;
            line-height: 1.4em;
        }

        h1 {

            color: #5e6163;
            font-size: 2.5em;
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
            background: #F5F5F5;
        }

        table th,
        table td {
            text-align: center;
        }

        table th {
            padding: 5px 20px;
            color: #5D6975;
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


</head>



<body>
    <header class="clearfix">
        {{-- <div id="logo">
            <img src=" {{'data:image/png;base64,'.base64_encode(file_get_contents(public_path('assets/pdf/logo.png')))}} " alt="logo" style="width: 30px; height: 30px">
        </div> --}}

        <div class="border">
            @foreach ($companyData as $comData)
            <h1>{{ $comData->name}}</h1> 
            <div class="subhead" style="text-align: center; font-size: 20px; color: #3d3d3d; font-weight: bold;">REPAIRING CENTER</div>
            <div class="subhead">Address : {{ $comData->address}}</div>
            <div class="subhead">Phone : {{ $comData->co_number}}  ,  {{ $comData->fax_number}}</div>
            {{-- <div class="subhead">Fax : {{ $comData->co_number}}</div> --}}
            <div class="subhead">Email : <a href="mailto:{{ $comData->email}}">{{ $comData->email}}</a></div>
            @endforeach
            <br>
        </div>

        <div id="company" class="clearfix">
            @foreach ($jobSheetData as $data)
            <div>DATE :&nbsp;{{ $data->Date}}</div>
            <div>JOB NO :&nbsp;{{ $data->Job_no}}</div>
            @endforeach
        </div>

        <div id="project">
            @foreach ($jobSheetData as $data)
            <div><span>Name &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;{{ $data->Customer_Name}}</span></div>
            <div><span>Tel &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;{{ $data->Customer_Phone}}</span>
            </div>
            <div><span>Email &nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;</span> <a
                    href="mailto:{{ $data->Email}}">{{ $data->Email}}</a></div>
            <div><span>Address :&nbsp;{{ $data->Address_1}}</span></div>
            <div><span>NIC &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;{{ $data->NIC}}</span></div>
            @endforeach
        </div>
    </header>
    <main>
        <table>
            <tbody>
                @foreach ($jobSheetData as $data)
                <tr>
                    <td class="unit" style="width: 25%">Brand :</td>
                    <td class="unit">{{ $data->Brand}}</td>
                </tr>
                <tr>
                    <td class="unit">IMEI Number :</td>
                    <td class="unit">{{ $data->IMEI_Number}}</td>
                </tr>
                <tr>
                    <td class="unit">Model :</td>
                    <td class="unit">{{ $data->Device_Model}}</td>
                </tr>
                <tr>
                    <td class="unit">Items :</td>
                    <td class="qty">{{ $data->Item}}</td>
                </tr>
                <tr>
                    <td class="unit">Problems :</td>
                    <td class="qty">{{ $data->Problem}}</td>
                </tr>
                <tr>
                    <td class="unit">Password :</td>
                    <td class="qty">{{ $data->Password}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- TOTAL Balance section --}}
        <table>
            <tbody>
                @foreach ($jobSheetData as $data)
                {{-- <tr>
                <td colspan="4">BALANCE :</td>
                <td class="total">{{ $data->Amount}}</td>
                </tr>

                <tr>
                    <td colspan="4">AMOUNT PAID :</td>
                    <td class="total">{{ $data->Advance}}</td>
                </tr> --}}
                <tr>
                    <td class="grand qty" style="width: 11%">Asstimate:</td>
                    <td class="grand qty">{{ $data->Amount}}</td>
                    <td class="grand qty"></td>
                    <td class="grand qty"></td>
                    <td class="grand qty" style="width: 11%">Advance:</td>
                    <td class="grand qty">{{ $data->Advance}}</td>
                    <td class="grand qty"></td>
                    <td class="grand qty"></td>
                    <td class="grand total" style="width: 11%">Balance:</td>
                    <td class="grand total">{{ $data->Balance}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
<br>
        {{-- TERMS AND CONDITIONS --}}
        <div id="notices">
            <div>TERMS AND CONDITIONS:</div>
            <ul>
                <li>
                    <div class="notice">No responsibility is assumed for devices that have fallen on the ground or
                        fallen into water.</div>
                </li>
                <li>
                    <div class="notice">Our company does not bear any responsibility for damaged parts or malfunctions
                        that may occur during repairs.</div>
                </li>
                <li>
                    <div class="notice">After repair, it is your responsibility to check the function and condition of
                        the device and take it with you.</div>
                </li>
                <li>
                    <div class="notice">We arent responsible if things like phone numbers, pictures, videos, etc. on
                        your phone are destroyed during the repair.</div>
                </li>
                <li>
                    <div class="notice">Devices that delivered for repair must be taken within 21 days maximum. Our
                        company does not take any responsibility for equipment that exceeds that time.</div>
                </li>
                <li>
                    <div class="notice">No device will be returned without the receipt provided to you.</div>
                </li>
                <li>
                    <div class="notice">If the repaired error occurs again, you must inform us, within two days.</div>
                </li>
            </ul>
        </div>

        <br>

        <div id="project">
            <div><span>................................................</span></div>
            <div><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Customer Signature</span></div>
        </div>

        <div id="company" class="clearfix">
            <div><span>..............................</span></div>
            <div><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Signature</span></div>
        </div>

    </main>
    <footer>
        Invoice was created on a computer and is valid without the signature and seal.
    </footer>
</body>

</html>
