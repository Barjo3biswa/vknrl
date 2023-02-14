<div class="col-md-12" {{-- id="printableArea" --}}>
    <p style="text-align:center" class="">
        <img src="{{ asset_public('logo.jpg') }}" alt="Logo" alt="Logo" width="40"><br>
        <strong>{{ env('APP_NAME') }}</strong>
    </p>
    <table class="table table-bordered" style="border-collapse:collapse;" border="1" width="100%">
        <tbody>
            <tr>
                <th colspan="2" class="text-center bold" style="padding:8px;">Payment Acknowledgement</th>
            </tr>
            <tr>
                <td style="padding:8px;">Registration No</td>
                <th style="text-align:left; padding:8px;" class="bold">{{ $conference->registration_no }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Application No</td>
                <th style="text-align:left; padding:8px;" class="bold">{{ $conference->id }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Applicant Name</td>
                <th style="text-align:left; padding:8px;" class="bold">{{ $conference->first_name.' '.$conference->middle_name.' '.$conference->last_name }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Email Id</td>
                <th style="text-align:left; padding:8px;" class="bold">{{ $conference->email }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Phone No</td>
                <th style="text-align:left; padding:8px;" class="bold">{{ $conference->phone_no }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Name of the Institution:</td>
                <th style="text-align:left; padding:8px;" class="bold">{{ $conference->institute_name }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Applicant Designation</td>
                <th style="text-align:left; padding:8px;" class="bold">{{ $conference->designation }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Address</td>
                <th style="text-align:left; padding:8px;" class="bold">{{ $conference->address }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Participating in Scientific Paper Presentation </td>
                <th style="text-align:left; padding:8px;" class="bold">{{ $conference->scientific_paper==1?'Yes':'No' }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Participating in Poster Presentation:</td>
                <th style="text-align:left; padding:8px;" class="bold">{{ $conference->poster_presentaion==1?'Yes':'No' }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Accommodation Date </td>
                <th style="text-align:left; padding:8px;" class="bold">{{ $conference->first_day==1?'*9 th March 2023':'' }}
                    <br/>{{ $conference->second_day==1?'*10 th March 2023':'' }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Transaction ID</td>
                <th style="text-align:left; padding:8px;" class="bold">{{ $conference->paymentReceipt->payment_id }}
                </th>
            </tr>
            <tr>
                <td style="padding:8px;">Transaction Date</td>
                <th style="text-align:left; padding:8px;" class="bold">
                    {{ dateFormat($conference->paymentReceipt->created_at, 'd-m-Y H:i:s') }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Amount</td>
                <th style="text-align:left; padding:8px;" class="bold">Rs.
                    {{ number_format($conference->paymentReceipt->amount, 2) }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Amount in Word</td>
                <th style="text-align:left; padding:8px;" class="bold">
                    {{ ucwords(getIndianCurrency($conference->paymentReceipt->amount)) }}</th>
            </tr>

        </tbody>
    </table>
</div>
<div class="col-xs-12 donot-print">
    <input type="button" class="btn btn-primary" onclick="printDiv('printableArea')" value="print" />
</div>

