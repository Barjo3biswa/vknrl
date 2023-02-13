<?php

return [
    /*
    *  OTP Resend Limitation
    */
    "otp-limit"     => 3,
    "admit_qr_code" => false,
    "admit-registration-required" => false,
    
    "sms_templates" => [
        [
            "name"  => "Application accepted and admit card download",
            "template_id"  => "1207163661384388092",
            "template"  => "Your application for GNM entrance examination ".date("Y")." of VKNRL School of Nursing, Numaligarh has been accepted. You can download the admit card within {#var#} to {#var#} from www.vknrlnursingschool.edu.in. Thank you. VKNRL School of Nursing",
        ],
        [
            "name"  => "Application rejection",
            "template_id"  => "1207163661406245110",
            "template"  => "Your application for entrance examination for GNM course ".date("Y")." of VKNRL School of Nursing, Numaligarh has been rejected as per the rejection criteria mentioned in the website www.vknrlnursingschool.edu.in. Thank you, VKNRL School of Nursing",
        ],
    ],
];