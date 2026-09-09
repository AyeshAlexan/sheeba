

<div class="col ">
    <div class="row">
        <div class="col">
            <label for="receipt_type">Receipt Type :
                <input class="form-control"  type="text" placeholder="Receipt type"
                    name="receipt_type"
                    @foreach ($receiptData as $receipt)
                    value="{{$receipt['Receipt_Type']}}"
                    @endforeach
                    id="receipt_type" readonly/>
            </label>
        </div>
        <div class="col">
                <label for="date">Date :
                    <input class="form-control" type="date"
                    id="date"
                    @foreach ($receiptData as $receipt)
                    value="{{ $receipt['Receipt_Date'] }}"
                    @endforeach
                    name="redeem_date" required>
                </label>
        </div>
        <div class="col">
            <label for="redeem_no">Redeem Number :
                <input class="form-control" type="text" placeholder="Redeem Number:"
                value="{{ $maxRedeem+1 }}"
                id="redeem_no" name="redeem_no" required>
            </label>
        </div>
    </div>
</div>
<h5 class="mt-3 mb-2">Receipt Info</h5>
<table class="table table-bordered text-center" id="dynamicAdded">
    <thead class="thead-light">
        <tr>
            <th>Final Date</th>
            <th>Receipt Date Time</th>
            <th>Period (Days)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <p id="date-final"></p>
            </td>
            <td>
                <p>
                @foreach ($receiptData as $receipt)
                   {{ $receipt['Receipt_Date'] }}
                @endforeach
                </p>
            </td>
            <td>
                <p id="date-period"></p>
            </td>
        </tr>
    </tbody>
</table>
<br>
<h5 class="mt-3 mb-2">Customer Info</h5>
<table class="table table-bordered text-center" id="dynamicAdded">
    <thead class="thead-light">
        <tr>
            <th>Customer Address </th>
            <th>Customer Contact Number</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach ($customerData as $customer )
            <td><h5>{{ $customer['Address_1'] }}</h5></td>
            <td><h5>{{ $customer['Contact_1'] }}</h5></td>
            @endforeach
        </tr>
    </tbody>
    <thead class="thead-light">
        <tr>
                <th>Customer Name </th>
                <th>Customer ID</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach ($customerData as $customer )
            <td><h5>{{ $customer['Name'] }}</h5></td>
            <td><h5>{{ $customer['Code'] }}</h5></td>
            @endforeach
        </tr>
    </tbody>
</table>
<br>


<div class="row" style="align-content: center;">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-outline-info form-control" type="button"
                data-bs-toggle="modal" data-bs-target="#viewArticleDetailsModel">
                <i class="far fa-eye me-1"></i>
                    View Article Details
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="col-md-12">
            <button class="btn btn-outline-info form-control" type="button"
            data-bs-toggle="modal" data-bs-target="#viewPaymentHistoryModel">
            <i class="far fa-eye me-1"></i>
                Payment History
            </button>
        </div>
    </div>
</div>
<br>

<h5 class="mt-3 mb-2">Redeem Details</h5>
<table class="table table-bordered text-center" id="dynamicAdded">
    <thead class="thead-light">
        <tr>
            <th>Original Pawn Amount</th>
            <th>Payable Pawn Amount</th>
            <th>Paid Interest</th>
            <th>Payable Interest</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                @foreach ($receiptData as $receipt)
                <p>
                    <input class="form-control text-center"  type="text" placeholder="Original Pawn Amount"
                    name="original_pawn_amount"
                    value="{{$receipt['Amount']}}"
                    id="original_pawn_amount" readonly/>
                </p>
                @endforeach
            </td>
            <td>
                @foreach ($receiptData as $receipt)
                <p>
                    <input class="form-control text-center"  type="text" placeholder="Payable Pawn Amount"
                    name="original_pawn_amount"
                    value="{{$receipt['Total_Amount']}}"
                    id="payable_pawn_amount" readonly/>
                </p>
                @endforeach
            </td>
            <td>

                <p>
                    <input class="form-control text-center" id="interest" type="text" placeholder="Paid Interest"
                    name="paid_interest"
                    value="" readonly/>
                </p>

            </td>
            <td>
                <p>
                    <input class="form-control text-center" id="payable_interest" type="text" placeholder="Payable Interest"
                    name="payable_interest"
                    value="" readonly/>
                </p>
            </td>
        </tr>
    <thead class="thead-light">
        <tr>
            <th>
                Stamp Fee
            </th>
            <th>
                Document Charges
            </th>
            <th>
                Advance Balance
            </th>
        </tr>
    </thead>
        <tr>
            <td>
                <p>
                    <input class="form-control text-center" id="stamp_fee" type="text" placeholder="Stamp Fee"
                    name="stamp_fee" value="" readonly/>
                </p>
            </td>
            <td>
                <p>
                    <input class="form-control text-center" id="document_charges" type="text" placeholder="Document Charges"
                    name="document_charges" value="" readonly/>
                </p>
            </td>
            <td>
                <p>
                    <input class="form-control text-center" id="advance_balance" type="text" placeholder="Advance Balance"
                    name="advance_balance" value="" readonly/>
                </p>
            </td>

        </tr>
    </tbody>
</table>
<br>


{{-- Functions for date and time  calculation --}}
<script>
    $(document).ready(function () {

        // form default date set for today
        document.getElementById('date').valueAsDate = new Date();

        calculateFinalDate();
        interestCalculations()

         // run interest calculation when change the amount field
         $('#date, #date-period').on('keyup, change',function(e){
                e.preventDefault();

                calculateFinalDate();
                interestCalculations()
        })

        // Function to calculate and display the final date
        function calculateFinalDate() {
            let receiptDate = new Date("@foreach ($receiptData as $receipt){{ $receipt['Receipt_Date'] }}@endforeach");
            let today_str = $('#date').val();
            let today_format = new Date(today_str);

            // Calculate the time difference in milliseconds
            let timeDiffMilliseconds = today_format - receiptDate;
            // Convert milliseconds to days
            let date_range_days = Math.floor(timeDiffMilliseconds / (1000 * 60 * 60 * 24));
            // Display the date range
            let DateRangeDisplay = document.getElementById("date-period");
            DateRangeDisplay.textContent = date_range_days;
        }

        function interestCalculations(){
            let receiptDate = new Date("@foreach ($receiptData as $receipt){{ $receipt['Receipt_Date'] }}@endforeach");
            let today_str = $('#date').val();
            let today_format = new Date(today_str);

            // Calculate the time difference in milliseconds
            let timeDiffMilliseconds = today_format - receiptDate;
            // Convert milliseconds to days
            let date_range_days = Math.floor(timeDiffMilliseconds / (1000 * 60 * 60 * 24));

            // getting period details
            let period1 = 0;
            let period2 = 0;
            let period3 = 0;
            @foreach ($receiptTypeData as $receiptType)
                period1 += {{ $receiptType['period1'] }};
            @endforeach;
            @foreach ($receiptTypeData as $receiptType)
                period2 += {{ $receiptType['period2'] }};
            @endforeach;
            @foreach ($receiptTypeData as $receiptType)
                period3 += {{ $receiptType['period3'] }};
            @endforeach;

            // getting rates details
            let rate1 = 0;
            let rate2 = 0;
            let rate3 = 0;
            @foreach ($receiptTypeData as $receiptType)
                rate1 += {{ $receiptType['rate1'] }};
            @endforeach;
            @foreach ($receiptTypeData as $receiptType)
                rate2 += {{ $receiptType['rate2'] }};
            @endforeach;
            @foreach ($receiptTypeData as $receiptType)
                rate3 += {{ $receiptType['rate3'] }};
            @endforeach;

            // Calculate the interest
            let amount = parseFloat( {{  $receiptData[0]['Amount'] }} ) || 0;

            // calculate the interest checking the periods
            if (date_range_days <= period1) {
                let interest = ((amount / 100) * rate1).toFixed(2);
                $('#interest').val(interest);
            } else if (date_range_days <= period2) {
                let interest = ((amount / 100) * rate2).toFixed(2);
                $('#interest').val(interest);
            } else if (date_range_days > period2) {
                let interest = ((amount / 100) * rate3).toFixed(2);
                $('#interest').val(interest);
            }
        }


        // Calling the total payment function
        redeemTotalCalculation()
        // Calculate the total payment
        function redeemTotalCalculation(){
            // Calculate the total payment
            let total_pay = 0;
            let amount = parseFloat( {{  $receiptData[0]['Amount'] }} ) || 0;
            let discount = parseFloat($('#redeem_discount').val());
            let interest_to_pay = parseFloat($('#interest').val());

            if (discount > 0) {
                total_pay = (amount + interest_to_pay) - discount;
                $('#redeem_total').val(total_pay);
            } else {
                total_pay = amount + interest_to_pay;
                $('#redeem_total').val(total_pay);
            }
        }

        // run total pay calculation when change the discount field
        $('#redeem_discount').on('keyup, change',function(e){
                e.preventDefault();
                redeemTotalCalculation()
        })


    });

</script>



