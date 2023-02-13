<div class="row">
    <div class="col-md-12" id="printTable">
        <p style="text-align:center" class="">
                <img src="{{asset_public("logo.jpg")}}" alt="Logo" alt="Logo" width="40"><br>
                <strong>{{env("APP_NAME")}}</strong>
        </p>
        <table class="table table-bordered" style="border-collapse:collapse;" border="1" width="100%">
            <tbody>
                <tr>
                    <th colspan="2" class="text-center bold" style="padding:8px;">Payment Acknowledgement</th>
                </tr>
                <tr>
                    <td style="padding:8px;">Registration No</td>
                    <th style="text-align:left; padding:8px;" class="bold">{{$application->student_id}}</th>
                </tr>
                <tr>
                    <td style="padding:8px;">Application No</td>
                    <th style="text-align:left; padding:8px;" class="bold">{{$application->id}}</th>
                </tr>
                <tr>
                    <td style="padding:8px;">Transaction ID</td>
                    <th style="text-align:left; padding:8px;" class="bold">{{$application->paymentReceipt->payment_id}}</th>
                </tr>
                <tr>
                    <td style="padding:8px;">Transaction Date</td>
                    <th style="text-align:left; padding:8px;" class="bold">{{dateFormat($application->paymentReceipt->created_at, "d-m-Y H:i:s")}}</th>
                </tr>
                <tr>
                    <td style="padding:8px;">Applicant Name</td>
                    <th style="text-align:left; padding:8px;" class="bold">{{$application->fullname}}</th>
                </tr>
                <tr>
                    <td style="padding:8px;">Type of Transaction</td>
                    <th style="text-align:left; padding:8px;" class="bold">Online </th>
                </tr>
                <tr>
                    <td style="padding:8px;">Amount</td>
                    <th style="text-align:left; padding:8px;" class="bold">Rs. {{number_format($application->paymentReceipt->amount, 2)}}</th>
                </tr>
                <tr>
                    <td style="padding:8px;">Amount in Word</td>
                    <th style="text-align:left; padding:8px;" class="bold">{{ucwords(getIndianCurrency($application->paymentReceipt->amount))}}</th>
                </tr>
            </tbody>
        </table>
    </div>
        <div class="col-xs-12 donot-print">
            <button type="button" class="btn btn-deafult dont-print" onclick="window.print()"><i class="fa fa-print"></i>
                Print</button>
        </div>
</div>