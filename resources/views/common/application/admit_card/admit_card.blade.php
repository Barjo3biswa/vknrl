@php
    $string = "Application No: {$admit_card->application_id}\nRegistration No: {$admit_card->application->student_id}\nName: {$admit_card->application->fullname}";
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
<div class="admit-card-container">
    <table width="100%" style="border:2px solid black;">
        <tbody>
            <tr><td style="text-align: center; padding: 10px;
                font-size: 20px;
                background: gray;
                color: white;
            border: 1px black solid;" colspan="3">{{env("APP_NAME")}}<br />GNM Admission {{$admit_card->application->session->name}}<br /> Admit Card for entrance examination</td></tr>
            <tr style="padding-top: 5px;">
               <td rowspan="{{(9 - (config("vknrl.admit-registration-required") ? 0 : 1))}}" style="text-align: center">                                    
                   <img style="height:150px; width: 121px; border: 1px solid black; " src="{{route("common.download.image", [$admit_card->application->student_id, $admit_card->application->id, $admit_card->application->passport_photo()->file_name])}}">
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
                <td><strong>{{$admit_card->exam_center->center_name}} @if($admit_card->exam_center->center_code)({{$admit_card->exam_center->center_code}}) @endif</strong></td>            </tr>
            <tr>
                <td rowspan="3" style="text-align: center">                                    
                    <img style="width: 121px; height: 60px; max-width: 160px; max-height: 60px; border: 1px solid black;"
                    src="{{route("common.download.image", [$admit_card->application->student_id, $admit_card->application->id, $admit_card->application->signature()->file_name])}}">
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
                        {!!'<img src="data:'.$qr_code->getContentType().';base64,'.$qr_code->generate().'" width="100px"/>'!!}
                    @endif
                    {{-- {!!'<img src="data:image/png;base64,'.$code.'" />'!!} --}}
                </td>
                <td></td>
                <td style="text-align: center;">
                        <img style="width: 121px; height: 60px; max-width: 160px; max-height: 60px;"
                        src="{{asset_public("narendra_modi_sign.png")}}">
                    <p>Principal of {{env("APP_NAME")}}<br>
                    Ponka Grant, Numaligarh<br>
                    Golaghat, Assam- 785699</p>

                </td>
            </tr>
        </tbody>
    </table>
</div>