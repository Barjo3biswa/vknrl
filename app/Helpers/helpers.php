<?php

use App\Models\Session;

function asset_public($path, $secure = null)
{
    return asset("public/" . $path, $secure);
}

function dateFormat($dateTime, $format = "d-m-Y")
{
    if ($dateTime == "0000-00-00" || $dateTime == "0000-00-00 00:00:00") {
        return " ";
    }
    $date = strtotime($dateTime);
    if (date('d-m-Y', $date) != '01-01-1970') {
        return date($format, $date);
    } else {
        return " ";
    }
}

function sendSMS_old($mobile_no, $message)
{
    // dd("ok1");
    if (env("OTP_DRIVER") == "log") {
        \Log::info(["message" => $message, "mobile_no" => $mobile_no]);
        return true;
    }
    // ?data=<message-submit-request><username>numaligarhrefinery</username><password>123456</password><sender-id>NRLSMS</sender-id><MType>TXT</MType><message-text><text>" + message + "</text><to>" + ph + "</to></message-text></message-submit-request>
    $user = env('SMS_USER');
    $password = env('SMS_PASSWORD');
    $senderid = env('SMS_SENDERID');
    $url = env('SMS_URL');
    $app_name = env('APP_NAME');
    $message = urlencode($message);
    // if(is_array($mobile_no)){
    //     $mobile_no = "91".implode(",91", $mobile_no);
    // }else
        $mobile_no = '91' . $mobile_no;
    // $smsInit = curl_init($url . "?user=$user&password=$password&mobiles=$mobile_no&sms=" . $message . "&senderid=" . $senderid);
    $smsInit = curl_init($url . "?data=<message-submit-request><username>" . $user . "</username><password>" . $password . "</password><sender-id>" . $senderid . "</sender-id><MType>TXT</MType><message-text><text>" . $message . "</text><to>" . $mobile_no . "</to></message-text></message-submit-request>");
    curl_setopt($smsInit, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($smsInit);
    \Log::info("SMS Sending to: ".$mobile_no);
    \Log::info("Message: ".$message);
    \Log::info($res);
    return $res;
}
function sendSMS($mobile_no, $message,$tempid, $auto_header = true)
{
    // dd("ok");
    $user = env('SMS_USER');
    $password = env('SMS_PASSWORD');
    $senderid = env('SMS_SENDERID');
    $url = env('SMS_URL');
    $app_name  = env('APP_NAME');
    if($auto_header){
        $message   = urlencode($message . "\n" . $app_name);
    }else{
        $message   = urlencode($message);
    }
    $mobile_no = '91' . $mobile_no;
    //SmsLog::create($data);
   
    // $smsInit = curl_init($url . "?user=$user&password=$password&mobiles=$mobile_no&sms=" . $message . "&senderid=" . $senderid. "&tempid=".$tempid);
    $smsInit = curl_init($url.$senderid."/".$mobile_no."/".$message."/TXT?apikey=fa9855-3ed47c-e2d763-cca7df-754461&dltentityid=1201159265358162656&dlttempid=".$tempid);
    curl_setopt($smsInit, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($smsInit);

     \Log::info($res);
     \Log::info("SMS Sending to: ".$mobile_no);
    \Log::info("Message: ".$message);
    return $res;
}

function saveLogs($user_id, $username, $guard, $activity, $save_to_log = false)
{
    $log = [];
    $log['user_id'] = $user_id;
    $log['username'] = $username;
    $log['guard'] = $guard;
    $log['activity'] = $activity;
    $log['url'] = substr(\Request::fullUrl(), 0, 250);
    $log['method'] = \Request::method();
    $log['ip'] = \Request::ip();
    $log['agent'] = \Request::header('user-agent');
    if ($save_to_log) {
        return \Log::info($log);
    }
    \App\Models\DailyLog::create($log);
}
function getActiveSession()
{
    $session = \App\Models\Session::where("is_active", 1)->first();
    if ($session) {
        return $session;
    }
    return (object) ["name" => "NA"];
}
function getActiveSessionApplication()
{
    $session = getActiveSession();
    if ($session->name !== "NA") {
        $application = \App\Models\Application::where("session_id", $session->id)
            ->where("student_id", auth()->guard("student")->user()->id)
            ->first();
        return $application;
    }
    return [];
}
function current_date_time()
{
    return date("Y-m-d h:i:s");
}
function get_guard()
{
    if (Auth::guard('admin')->check()) {return "admin";} elseif (Auth::guard('student')->check()) {return "student";} else {
        return "";
    }

}
function getApplicationPermanentAddress($application)
{
    return "Vill/Town:" . $application->permanent_village_town . '</br> PS: ' . $application->permanent_ps . '</br> PO: ' . $application->permanent_po . '</br> Dist: ' . $application->permanent_district . '</br> State: ' . $application->permanent_state . '- ' . $application->permanent_pin;
}
function getCorrespondencePermanentAddress($application)
{
    return "Vill/Town:" . $application->correspondence_village_town . '</br> PS: ' . $application->correspondence_ps . '</br> PO: ' . $application->correspondence_po . '</br> Dist: ' . $application->correspondence_district . '</br> State: ' . $application->correspondence_state . '- ' . $application->correspondence_pin;
}
function totalRegisterdUser()
{
    $session=Session::where('is_active',1)->first();
    return \App\Models\User::where('session_id',$session->id)->count();
}
function getTotalApplicationCount()
{
    $session=Session::where('is_active',1)->first();
    $statuses = \App\Models\Application::$statuses_for_admin;
    return \App\Models\Application::whereIn("status", $statuses)->where('session_id',$session->id)->count();
}
function applicatinEditPermission($application)
{
    $guard = get_guard();
    if ($guard == "student") {
        if ($application->student_id != auth($guard)->user()->id) {
            return false;
        }
    } elseif ($guard == "web") {
        return false;
    }
    return true;
}
function isEditAvailable($application)
{
    if (auth("admin")->check()) {
        return true;
    }
    if ($application->status == "application_submitted") {
        return true;
    } else {
        return false;
    }
}
function checkAnmDataEntered($application)
{
    $application = collect($application->toArray());
    $common_class = new \App\Http\Controllers\CommonApplicationController();
    return $common_class->checkAnmDataEntered($application);
}

function getIndianCurrency(float $number)
{
    if ($number == 0) {
        return 'Zero only';
    }
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
        } else {
            $str[] = null;
        }

    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise . " only";
}
function findDateDiff($date_from, $date_two)
{
    $date_from = dateFormat($date_from, "Y-m-d H:i:s");
    $date_two = dateFormat($date_two, "Y-m-d H:i:s");
    $date1 = new DateTime($date_from);
    $date2 = $date1->diff(new DateTime($date_two));
    return [
        "years" => $date2->y,
        "days" => $date2->m,
        "months" => $date2->d,
    ];
    // echo $date2->days.'Total days'."\n";
    // echo $date2->y.' years'."\n";
    // echo $date2->m.' months'."\n";
    // echo $date2->d.' days'."\n";
    // echo $date2->h.' hours'."\n";
    // echo $date2->i.' minutes'."\n";
    // echo $date2->s.' seconds'."\n";
}

/**
 * Helper library for CryptoJS AES encryption/decryption
 * Allow you to use AES encryption on client side and server side vice versa
 *
 * @author BrainFooLong (bfldev.com)
 * @link https://github.com/brainfoolong/cryptojs-aes-php
 */
/**
 * Decrypt data from a CryptoJS json encoding string
 *
 * @param mixed $passphrase
 * @param mixed $jsonString
 * @return mixed
 */
function cryptoJsAesDecrypt($passphrase, $jsonString)
{
    $jsondata = json_decode($jsonString, true);
    try {
        $salt = hex2bin($jsondata["s"]);
        $iv = hex2bin($jsondata["iv"]);
    } catch (Exception $e) {
        return null;
    }
    $ct = base64_decode($jsondata["ct"]);
    $concatedPassphrase = $passphrase . $salt;
    $md5 = array();
    $md5[0] = md5($concatedPassphrase, true);
    $result = $md5[0];
    for ($i = 1; $i < 3; $i++) {
        $md5[$i] = md5($md5[$i - 1] . $concatedPassphrase, true);
        $result .= $md5[$i];
    }
    $key = substr($result, 0, 32);
    $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
    return $data;
    return json_decode($data, true);
}
/**
 * Encrypt value to a cryptojs compatiable json encoding string
 *
 * @param mixed $passphrase
 * @param mixed $value
 * @return string
 */
function cryptoJsAesEncrypt($passphrase, $value)
{
    $salt = openssl_random_pseudo_bytes(8);
    $salted = '';
    $dx = '';
    while (strlen($salted) < 48) {
        $dx = md5($dx . $passphrase . $salt, true);
        $salted .= $dx;
    }
    $key = substr($salted, 0, 32);
    $iv = substr($salted, 32, 16);
    $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
    $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
    return json_encode($data);
}
function dumpp($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}
function QrCode()
{
    return new \CodeItNow\BarcodeBundle\Utils\QrCode();
}
function BarCode()
{
    return new \CodeItNow\BarcodeBundle\Utils\BarcodeGenerator();
}
function detectBrowser($agent)
{
    if (strpos($agent, 'MSIE') !== false) {
        echo 'Internet explorer';
    } elseif (strpos($agent, 'Trident') !== false) //For Supporting IE 11
    {
        echo 'Internet explorer';
    } elseif (strpos($agent, 'Firefox') !== false) {
        echo 'Mozilla Firefox';
    } elseif (strpos($agent, 'Chrome') !== false) {
        echo 'Google Chrome';
    } elseif (strpos($agent, 'Opera Mini') !== false) {
        echo "Opera Mini";
    } elseif (strpos($agent, 'Opera') !== false) {
        echo "Opera";
    } elseif (strpos($agent, 'Safari') !== false) {
        echo "Safari";
    } else {
        echo 'Something else';
    }

}