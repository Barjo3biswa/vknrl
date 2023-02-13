@php
$string = "Application No: {$admit_card->application_id}\nRegistration No: {$admit_card->application->student_id}\nName:
{$admit_card->application->fullname}";
$qr_code = QrCode();
$qr_code
->setText($string)
->setSize(300)
->setPadding(10)
->setErrorCorrection('high')
->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
// ->setLabel('Scan Qr Code')
->setLabelFontSize(16)
->setImageType(QrCode()::IMAGE_TYPE_PNG);
// $barcode = BarCode();
// $barcode->setText("0123456789");
// $barcode->setType(BarCode()::Code128);
// $barcode->setScale(2);
// $barcode->setThickness(25);
// $barcode->setFontSize(10);
// $code = $barcode->generate();
@endphp
<style>
    ol {
        text-align: justify
    }
    #watermark { position: fixed; top: 0px; left: 15%; width: 60%; height: 400px; opacity: .1; z-index: -1; }
    @page { margin: 20px; }
    body { margin: 0px; }
</style>
<div class="admit-card-container">
    <div id="watermark"><img src="{{base_path("public/logo.jpg")}}" height="100%" width="100%"></div>
    <table width="100%" style="border:2px solid black;">
        <tbody>
            <tr>
                <td style="text-align: center; padding: 10px;
                    font-size: 20px;
                    background: gray;
                    color: white;
                    border: 1px black solid;" colspan="3">{{env("APP_NAME")}}<br />GNM Admission {{$admit_card->application->session->name}}<br /> Admit Card for entrance examination</td>
            </tr>
            <tr style="padding-top: 5px;">
                <td rowspan="{{(9 - (config("vknrl.admit-registration-required") ? 0 : 1))}}" style="text-align: center">
                    <img style="height:150px; width: 121px; border: 1px solid black; "
                        src="{{base_path("public/uploads/".$admit_card->application->student_id."/".$admit_card->application->id."/".$admit_card->application->passport_photo()->file_name)}}">
                </td>
                <td>Date</td>
                <td>: <strong>{{dateFormat($admit_card->exam_date, "d-F-Y (l)")}}</strong></td>
            </tr>
            <tr>
                <td>Examination Time</td>
                <td>: <strong>{{$admit_card->exam_time}}</strong></td>
            </tr>
            <tr>
                <td>Reporting Time</td>
                <td>: <strong>08:30 am</strong></td>
            </tr>
            <tr>
                <td>Last reporting time </td>
                <td>: <strong>09:00 am</strong></td>
            </tr>
            @if(config("vknrl.admit-registration-required"))
                <tr>
                    <td>Registration Number</td>
                    <td>: <strong>{{str_pad($admit_card->application->student_id, 4, "0000", STR_PAD_LEFT)}}</strong></td>
                </tr>
            @endif
            <tr>
                <td>Application No</td>
                <td>: <strong>{{str_pad($admit_card->application_id, 4, "0000", STR_PAD_LEFT)}}</strong></td>
            </tr>
            <tr>
                <td>Name</td>
                <td>: <strong>{{strtoupper($admit_card->application->fullname)}}</strong></td>
            </tr>

            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td>Examination Center:</td>
                <td><strong>{{$admit_card->exam_center->center_name}}
                        @if($admit_card->exam_center->center_code)({{$admit_card->exam_center->center_code}})
                        @endif</strong></td>
            </tr>
            <tr>
                <td rowspan="3" style="text-align: center">
                    <img style="width: 121px; height: 60px; max-width: 160px; max-height: 60px; border: 1px solid black;"
                        src="{{base_path("public/uploads/".$admit_card->application->student_id."/".$admit_card->application->id."/".$admit_card->application->signature()->file_name)}}">
                </td>
                <td></td>
                <td><strong>{{$admit_card->exam_center->address}}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td><strong>{{$admit_card->exam_center->city}}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td><strong>{{$admit_card->exam_center->state}}-{{$admit_card->exam_center->pin}}</strong></td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    @if(config("vknrl.admit_qr_code"))
                        {!!'<img src="data:'.$qr_code->getContentType().';base64,'.$qr_code->generate().'"
                        width="100px" />'!!}
                    @endif
                    {{-- {!!'<img src="data:image/png;base64,'.$code.'" />'!!} --}}
                </td>
                <td></td>
                <td style="text-align: center; padding-top:20px">
                    <img style="width: 121px; height: 60px; max-width: 160px; max-height: 60px;"
                        src="{{base_path("public/narendra_modi_sign.png")}}">
                    <p>Principal of {{env("APP_NAME")}}<br>
                        Ponka Grant, Numaligarh<br>
                        Golaghat, Assam- 785699</p>

                </td>
            </tr>
        </tbody>
    </table>
</div>
<p></p>
<div class="container-instruction">
    <h4 style="text-align: center;">Instructions to the candidates</h4>
    <h4>General Instructions:-</h4>
    <ol>
        <li>This <strong>Admit Card</strong> must be presented for verification along with at least one original (not
            photocopy or scanned copy valid photo Identification card such as PAN Card, Voter ID,
            Aadhaar-UUID, College ID, Driving License, Passport etc.</li>
        <li>This Admit Card is valid only if the candidate’s photograph and signature images are
            legible. To ensure this, print the admit card on an A4 sized paper using a laser printer,
            preferably a colour photo printer. If there is any discrepancies in the admit card, kindly
            contact the Principal, VKNRL School of Nursing, Numaligarh.</li>
        <li>Report to the examination venue by 08.30 am.</li>
        <li>Candidates will be permitted to appear for the examination ONLY after their credentials
            are verified by Centre officials.</li>
        <li>Candidates are advised to locate the examination center a day before the examination.</li>
        <li>Candidates are allowed to bring only black ball point pen to the examination hall.</li>
        <li>Candidates have to mark their response in the OMR sheet.</li>
        <li>The duration of examination will be of 2 (two) hours i.e from 10:00 am to 12:00pm
            Candidates will NOT be permitted to leave the examination hall before the end of the
            examination.</li>
        <li>Candidates will NOT be permitted to enter examination hall after the commencement of
            the exam.</li>
        <li>Device like calculator, mobile phones and any other electronic gadgets are strictly
            prohibited in the examination hall.</li>
        <li>Violation of any instruction and adoption of any unfair means in the examination hall will
            make the candidate ineligible for admission.</li>
        <li>Candidates are advised to wear their own mask and carry their own bottle of sanitizer.</li>
        <li>Only one guardian is allowed to accompany the candidate for the entrance examination to
            avoid overcrowding at the venue.</li>
    </ol>
    {{-- <ol>
        <li>This <strong>Admit Card</strong> must be presented for verification along with at least one original (not photocopy or scanned copy), valid photo Identification card (for example: College ID, Driving License, Passport, PAN Card, Voter ID, Aadhaar-UUID).</li>
        <li>This Admit Card is valid only if the candidate’s photograph and signature images are legible. To ensure this, print the admit card on an A4 sized paper using a laser printer, preferably a colour photo printer. If there is any discrepancies in the admit card, kindly contact the Principal VKNRL School of Nursing, Numaligarh.</li>
        <li>Please report to the examination venue by <strong>08.00 am</strong>.</li>
        <li>Candidates will be permitted to appear for the examination ONLY after their credentials are verified by centre officials.</li>
        <li>Candidates are advised to locate the examination centre a day before the examination.</li>
        <li>Candidates are allowed to bring only <strong>black ball point pen</strong> to the examination hall.</li>
        <li>Candidates have to mark their response in the OMR sheet.</li>
        <li>The duration of examination will be of 2 (two) hours. </li>
        <li>Candidates  will NOT be permitted to leave the examination hall before the end of the test.</li>
        <li>Candidates will NOT be permitted to enter examination hall after the <strong>last reporting time (09:00am).</strong></li>
        <li><strong>Device like calculator, mobile phones and any other electronic gadgets are strictly prohibited in the examination hall.</strong></li>
        <li>Violation of any instruction and adoption of any unfair means in the examination hall will make the candidate ineligible for admission. </li>
    </ol> --}}
    {{-- <h4>COVID Guidelines:-</h4>
    <ol>
        <li>Candidates must wear their own mask (N95/ Triple layer surgical mask); carry their own bottle of sanitizer (small).</li>
        <li>Only one guardian is allowed to accompany (or alone) for the entrance examination to avoid overcrowding at the venue. </li>
        <li>Guardian will not be allowed inside the campus.</li>
        <li>Candidates need to maintain distance of at least 6 feet from each other at all times.</li>
        <li>Candidates need to sanitize their hands with Hand Sanitizer before entry into the examination hall.</li>

    </ol> --}}
</div>