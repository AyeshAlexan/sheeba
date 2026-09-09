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
			body {
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				text-align: center;
				color: #777;
			}

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
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

            .tr_details{
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
				font-size: 45px;
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

        <h1 class="text-center mb-4">Print Receipt</h1>
        <table class="table table-bordered">
            <tbody>
                <tr class="tr_details">
                    <td style="width:8%;">
                        <label for="receipt_no">Receipt Number : </label>
                    </td>
                    <td style="width:8%;">
                        @foreach($pawnSumData as $sumData)
                        <p>{{$sumData['Receipt_Number']}}</p>
                        @endforeach
                    </td>
                </tr>
                <tr class="tr_details">
                    <td style="width:8%;">
                        <label for="receipt_type">Receipt Type : </label>
                    </td>
                    <td style="width:8%;">
                        @foreach($pawnSumData as $sumData)
                        <p>{{$sumData['Receipt_Type']}}</p>
                        @endforeach
                    </td>
                </tr>
                <tr class="tr_details">
                    <td style="width:8%;">
                        <label for="date">Date : </label>
                    </td>
                    <td style="width:8%;">
                        @foreach($pawnSumData as $sumData)
                        <p>{{$sumData['Receipt_Date']}}</p>
                        @endforeach
                    </td>
                </tr>
                <tr class="tr_details">
                    <td style="width:8%;">
                        <label for="customer_nic"> NIC :</label>
                    </td>
                    <td style="width:8%;">
                        @foreach($pawnSumData as $sumData)
                        <p>{{$sumData['Customer_NIC']}}</p>
                        @endforeach
                    </td>
                </tr>
                <tr class="tr_details">
                    <td style="width:8%;">
                        <label for="customer_name">Name :</label>
                    </td>
                    <td style="width:8%;">
                        @foreach($pawnSumData as $sumData)
                        <p>{{$sumData['Customer_Name']}}</p>
                        @endforeach
                    </td>
                </tr>
                <tr class="tr_details">
                    <td style="width:8%;">
                        <label for="customer_address">Address :</label>
                    </td>
                    <td style="width:8%;">
                        @foreach($pawnSumData as $sumData)
                        <p>{{$sumData['Customer_Address']}}</p>
                        @endforeach
                    </td>
                </tr>
                <tr class="tr_details">
                    <td style="width:8%;">
                        <label for="customer_phone">Phone :</label>
                    </td>
                    <td style="width:8%;">
                        @foreach($pawnSumData as $sumData)
                        <p>{{$sumData['Customer_Phone']}}</p>
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>

		<div class="invoice-box">
			<table>

				<tr class="heading">
                    <td>Category</td>
                    <td>Articles</td>
                    <td>Condition</td>
                    <td>Karatage</td>
                    <td>Weight</td>
                    <td>QTY</td>
                    <td>Value</td>
				</tr>

                @foreach($pawnDetailsData as $DetailsData)
				<tr class="item">
					<td>{{$DetailsData['Category']}}</td>
                    <td>{{$DetailsData['Articles']}}</td>
                    <td>{{$DetailsData['Condition']}}</td>
                    <td>{{$DetailsData['Karatage']}}</td>
                    <td>{{$DetailsData['Weight']}}</td>
					<td>{{$DetailsData['QTY']}}</td>
                    <td>{{$DetailsData['Value']}}</td>
				</tr>
                @endforeach

				<tr class="total">
					<td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total:</td>
					<td><b> @foreach($pawnSumData as $sumData)
                        {{$sumData['Amount']}}
                        @endforeach </b></td>
				</tr>
			</table>
		</div>
	</body>
</html>
