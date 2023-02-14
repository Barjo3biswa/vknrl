<div class="col-md-12" id="printableArea">
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
            {{-- <tr>
                <td style="padding:8px;">Transaction ID</td>
                <th style="text-align:left; padding:8px;" class="bold">{{ $conference->paymentReceipt->payment_id }}
                </th>
            </tr>
            <tr>
                <td style="padding:8px;">Transaction Date</td>
                <th style="text-align:left; padding:8px;" class="bold">
                    {{ dateFormat($conference->paymentReceipt->created_at, 'd-m-Y H:i:s') }}</th>
            </tr> --}}
            <tr>
                <td style="padding:8px;">Applicant Name</td>
                <th style="text-align:left; padding:8px;" class="bold">{{ $conference->fullname }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Type of Transaction</td>
                <th style="text-align:left; padding:8px;" class="bold">Online </th>
            </tr>
            {{-- <tr>
                <td style="padding:8px;">Amount</td>
                <th style="text-align:left; padding:8px;" class="bold">Rs.
                    {{ number_format($conference->paymentReceipt->amount, 2) }}</th>
            </tr>
            <tr>
                <td style="padding:8px;">Amount in Word</td>
                <th style="text-align:left; padding:8px;" class="bold">
                    {{ ucwords(getIndianCurrency($conference->paymentReceipt->amount)) }}</th>
            </tr> --}}
        </tbody>
    </table>
</div>
<div class="col-xs-12 donot-print">
    <input type="button" class="btn btn-primary" onclick="printDiv('printableArea')" value="print" />
</div>
<script>
    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
