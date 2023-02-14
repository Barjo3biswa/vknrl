<?php

namespace App\Http\Controllers\API;

use App\ConsumerTransaction;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApiPaymentControler extends Controller
{
    public function processPayment(Request $request)
    {
        $ym       = $request->ym;
        $rules    = [
            "ym" => "required|digits:4",
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Helper::api_response("Validation error.", $validator->messages(), false);
        }
        $consumer_info = consumer_info(Helper::getPrimaryConsumerNumber(auth('api')->user()->id)->consumer_number);
        // $consumer_info = consumer_info(Helper::getPrimaryConsumerNumber('028-007-06139'));
        //get the grid id
        $grid_arr = explode('-', $consumer_info->CONSUMER_NO);

        $grid_info = grid_info($grid_arr[0]);
        $bill_no   = $consumer_info->CONSUMER_NO . '/' . $ym;
        // $meter_reading_data = meter_reading($bill_no);
        $bill_details = bill_details($bill_no);

        $billFlag = '';

        if (isset($_GET['viewdump']) && $_GET['viewdump'] != '' && $_GET['viewdump'] == 'yes') {
            dump($bill_details);
        }

        if ($bill_details) {
            $billFlag = $bill_details->billFlag;
            $mmb_id   = $bill_details->MMB_ID;
            // $montly_billing     = montly_billing($mmb_id);
        } else {
            $montly_billing = [];
        }

        //view GST Bill from June 2017
        $check_if_bill_paid = checkIfPaid($bill_no);

        $yr = (int) (substr($ym, 0, 2));
        $mn = (int) (substr($ym, 2, 2));
        if (isset($_GET['webbill']) && $_GET['webbill'] == 'yes') {
            $billFlag = 1;
        }

        if ($consumer_info->BALANCE > 0) {
            $check_if_bill_paid = 1;
        }
        $message = "";
        if ($billFlag == 1) {
            if (!$check_if_bill_paid) {
                $available_for_payment = true;
            } else {
                $message = "Bill Already paid";
            }
        } elseif ($billFlag == 2) {
            if (!$check_if_bill_paid) {
                $message = "Current bill is under process. Please try again later.";
            } else {
                $message     = "PAID";
                $paid_status = true;
            }
        }
        // dd($bill_details);
        // $totalBill = $bill_details->Demand_TC + $bill_details->Demand_Service_Tax + $bill_details->Demand_GS 
        //             + $bill_details->Demand_Royalty + $bill_details->Demand_MM + $bill_details->Demand_HC 
        //             + $bill_details->Demand_Tax + $bill_details->LMC_DEMAND + $bill_details->SPECIAL_READING_DEMAND 
        //             + $bill_details->SPECIAL_READING_DEMAND + $bill_details->DAMAGE_METER_DEMAND + $bill_details->EMI_DEMAND 
        //             + $bill_details->EMI_DEMANDAC;

        $totalBill = $bill_details->Demand_Raised-$bill_details->Dem_Col;

        if ($consumer_info->CONSTYPEID == 2) {
            $totalBill += $bill_details->DEMAND_MMC;
        }

        $totalRoundedBill = number_format((float) round($totalBill), 2, '.', '');
        if (date('Y-m-d') <= $bill_details->payByDate) {
            $amount = round($totalRoundedBill + $bill_details->OUTSTANDING - ($consumer_info->BALANCE + $bill_details->ADJUSTMENTS));
        } else {
            $amount = round($totalRoundedBill + $bill_details->LATE_FINE + $bill_details->OUTSTANDING - ($consumer_info->BALANCE + $bill_details->ADJUSTMENTS));
        }

        $bill_no       = $bill_no;
        $amount        = $amount;
        // $amount        = "1.00";
        $consumer_no   = $consumer_info->CONSUMER_NO;
        $consumer_name = $consumer_info->FIRST_NAME . ' ' . $consumer_info->LAST_NAME;
        $grid          = $grid_info->GRID_NAME;

        $data                    = [];
        $data['consumer_number'] = $consumer_no;
        $data['bill_number']     = $bill_no;
        $data['bill_amount']     = $amount;

        if ($payment = Payment::create($data)) {
            $email         = Auth::user()->email;
            $mobile_number = Auth::user()->mobile_number;

            $CustomerID = str_replace('-', '.', $bill_no);
            $CustomerID = str_replace('/', '_', $CustomerID);

            $checksum = $this->generate_checksum($CustomerID, $amount, $mobile_number, $email, $payment->id);

            $data['email']         = $email;
            $data['mobile_number'] = $mobile_number;
            $data['checksum']      = $checksum;
            $data['consumer_name']      = $consumer_name;
            return Helper::api_response("Pay now", $data, true);

        }
        return Helper::api_response($message, $data = [], false);
    }


    public function paymentResponse(Request $request) {
        \Log::info($request->all());
        $msg = $request->msg;
        $checkSumKey = config('globals.checkSumKey');
        $checksum_value = substr(strrchr($msg, "|"), 1); //Last check sum value
        $str            = str_replace("|".$checksum_value,"",$msg);//string replace : with empy space
        $checksum       = hash_hmac('sha256',$str,$checkSumKey, false);
        $err_msg = '';
        try {
            if ($checksum_value == strtoupper($checksum)) {
                if ($msg != '') {
                    $checksum_value = substr(strrchr($msg, "|"), 1); //Last check sum value
                    $str            = str_replace("|" . $checksum_value, "", $msg); //string replace : with empy space

                    $payment_data = $this->savePaymentInfo($str);

                    $payment_data = explode('|', $payment_data);

                    $res_data = explode('|', $str);

                    $bank_response_code = $payment_data[0];
                    $payment         = Payment::findOrFail($res_data[21]);
                    $consumer_number = $payment->consumer_number;
                    //get consumer names
                    $cons_data 
                        = DB::table('tblconsumermaster')->where('CONSUMER_NO', $consumer_number)->first();
                
                    $url = route("api.user.payment_receipt", Crypt::encrypt($res_data[21]));
                   
                    return "
                        $msg
                        <script type='text/javascript'>
                                function getMsg(){
                                        var msg = '" . $msg . "';
                                        AndroidFunction.gotMsg(msg);
                                }
                                getMsg();
                        </script>

                    ";
                } else {
                    $err_msg = 'Payment Gateway return empty message. Transaction cancelled';
                }
            } else {
                $err_msg = 'Checksum not matched. Transaction cancelled';
            }

        } catch (\Exception $th) {
            $err_msg = $th->getMessage();
        }
        \Log::error($err_msg);
        // $err_msg = "AASAMGAS|028.002.01188_2005|SSBI9114135317|IGAJEKQNC2|00012829.00|SBI|NA|01|INR|DIRECT|NA|NA|8.26|14-08-2020 14:16:55|0399|NA|nabiurrahman2018@gmail.com|7002659658|NA|NA|NA|175772|NA|NA|Insufficient funds.";
        // return <<<EOL
        //     $msg
        //     <script type="text/javascript">
        //             function getMsg(){
        //                     var msg = "$msg";
        //                     AndroidFunction.gotMsg(msg);
        //             }
        //             getMsg();
        //     </script>
        // EOL;
        

       /*  return Helper::api_response($err_msg, ["receipt_link" => ""], false);
        return $err_msg; */
    }

    private function savePaymentInfo($str = NULL) {

        //$logo = public_path('assets/images/assam-gas-company-logo.jpg');

        //generate next receipt number
        $consumer_transaction = ConsumerTransaction::orderBy('Trans_SlNo', 'DESC')->select('Trans_SlNo', 'Receipt_No', 'Bill_No')->first();
        $receipt_number = $consumer_transaction->Receipt_No + 1;


        $logo = asset('assets/images/assam-gas-company-logo.jpg');

        $splitdata          = explode('|', $str);

        $bank_response_code = $splitdata[14];
        $payment_id         = $splitdata[21];
        $pay_amount         = $splitdata[4];
        
        $payment = Payment::findOrFail($payment_id);


        //get consumer number
        $consumer_number = $payment->consumer_number;
        //get consumer names
        $cons_data = DB::table('tblconsumermaster')->where('CONSUMER_NO', $consumer_number)->first();
        $consumer_name      = $cons_data->FIRST_NAME.' '.$cons_data->LAST_NAME;

        $payment->bank_response_code = $bank_response_code;
        $payment->response_message   = $str;
        $payment->bank_message       = $splitdata[23];
        $payment->bank_id            = $splitdata[5];
        $payment->amount_paid        = $pay_amount;
        $payment->receipt_number     = $receipt_number;
        
        $trans_date = date('Y-m-d');

        $bank_merchant_id   = $splitdata[6];

        //get consumer number and name
        //$consumer_number    = Helper::getPrimaryConsumerNumber(Auth::guard('user')->user()->id)->consumer_number;//Auth::guard('user')->user()->consumer_number;

        //$consumer_info      = Helper::getConsumerInfo(Helper::getPrimaryConsumerNumber(Auth::guard('user')->user()->id)->consumer_number);

        //$consumer_name      = $consumer_info->fname.' '.$consumer_info->lname;

        $mail_to          = $splitdata[16]; //$mail_to = 'nitish.dola@gmail.com';
        $mobile           = $splitdata[17]; //$mobile           = 9706926818;

        $mail_to          = 'sunil@webcomipl.net.in';
        $mobile           = 9678300810;


        if($bank_response_code == "0300") // success trans condition
        {
            $trans_date = date('Y-m-d H:i:s', strtotime($splitdata[13]));
            $payment_status = 1;

            $transaction_id             = $splitdata[2];
            $payment->payment_status    = $payment_status;

            $payment->transaction_id    = $transaction_id;

            $bill_number = $splitdata[1];
            $bill_number = str_replace('_','/', $bill_number);
            $bill_number = str_replace('.','-', $bill_number);

            //bank info
            $payment->pay_date = $trans_date;
            $payment->bank_marchant_id = $bank_merchant_id; 
            $payment->bank_response_code = $bank_response_code;

            if($payment->save()) {

                //send failed sms
               // $sms_messages = '';
                //$sms_messages .= 'Payment of AGCL BILL for Rs. '.$pay_amount.' was successfull. Keep transaction number '.$transaction_id.' for any issues further.';
                $filename = $transaction_id.'.pdf';
                // $pdf_path = public_path().'/pdf/'.$consumer_number.'/'.date('Ym');
                $pdf_path = asset() .env("UPLOAD_PDF_DIR", "/../../assamgas.co.in/public/pdf/"). $consumer_number . '/' . date('Ym');

                if (!file_exists($pdf_path)) {
                    \File::makeDirectory($pdf_path, $mode = 0777, true, true);
                }

                $html = '';
                $html .= '<div style="width:500px; margin:0 auto; font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;">';
                $html .= '<img src="'.$logo.'">';
                $html .= '<h3>Assam Gas Company, Duliajan</h3>';
                $html .= '<h4>Transaction Receipt</h4>';
                $html .= '<p>Transaction ID : '.$transaction_id.'</p>';
                $html .= '<p>Date : '.date('Y-m-d h:i A').'</p>';
                $html .= '<p>Consumer Number : '.$consumer_number.'</p>';
                $html .= '<p>Name : '.$consumer_name.'</p>';
                $html .= '<p>Payment Status : SUCCESS </p>';

                $html .= '<p>Amount Paid :'.$pay_amount.' </p>';

                $html .= '</div>';

                $pdf = \PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->save($pdf_path.'/'.$filename) ;                                   
            
                $mail = Helper::sendMail($filename, $pdf_path, $mail_to, $consumer_name, $transaction_id, $trans_date, $pay_amount, $splitdata[23], $payment_status);

                $sms_message = '';
                $sms_message = 'Your AGCL Gas Bill of Rs'.$pay_amount.' is successfull . Ref id : '.$transaction_id;
             
                $mobile = $mobile;
                //Helper::sendSMS($mobile,$sms_message);
				Helper::sendNewSMS($mobile,$sms_message);
            }else{
                throw new \Exception("Error Occurred ! Please contact administrator with your consumer number , date as soon as poss", 399);

                echo 'Error Occurred ! Please contact administrator with your consumer number , date as soon as poss';
            }
        }else{

            $payment_status = 0;

            $transaction_id             = 'FL'.strtoupper(uniqid()).Auth::guard('user')->user()->id;
            $payment->payment_status    = $payment_status;

            $payment->pay_date          = $trans_date;
            $payment->transaction_id    = $transaction_id;
            $payment->bank_id           = $splitdata[5];

            $payment->amount_paid        = 0.00;

            $bill_number = '';
            if($payment->save()) {

                //send failed sms
                $sms_message = '';
                $sms_message .= 'Payment of AGCL BILL for Rs. '.$pay_amount.' was un-successfull. Keep transaction number '.$transaction_id.' for any issues further.';

                //Helper::sendSMS($student_profile->mobile_number, $sms_message);

                
                $filename = $transaction_id.'pdf';
                // $pdf_path = public_path().'/pdf/'.$consumer_number.'/'.date('Ym');
                $pdf_path = asset() .env("UPLOAD_PDF_DIR", "/../../assamgas.co.in/public/pdf/"). $consumer_number . '/' . date('Ym');

                

                if (!file_exists($pdf_path)) {
                    \File::makeDirectory($pdf_path, $mode = 0777, true, true);
                }

                $html = '';
                $html .= '<div style="width:500px; margin:0 auto; font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;">';
                $html .= '<img src="'.$logo.'">';
                $html .= '<h3>Assam Gas Company, Duliajan</h3>';
                $html .= '<h4>Transaction Receipt</h4>';
                $html .= '<p>Transaction ID : '.$transaction_id.'</p>';
                $html .= '<p>Date : '.date('Y-m-d h:i A').'</p>';
                $html .= '<p>Consumer Number : '.$consumer_number.'</p>';
                $html .= '<p>Name : '.$consumer_name.'</p>';
                $html .= '<p>Payment Status : FAILED </p>';
                $html .= '</div>';

                \PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false)->save($pdf_path.'/'.$filename) ;                                   
            
                $mail = Helper::sendMail($filename, $pdf_path, $mail_to, $consumer_name, $transaction_id, $trans_date, $pay_amount, $splitdata[23], $payment_status);

                $sms_message = '';
                $sms_message = 'Your AGCL Gas Bill of Rs'.$pay_amount.' was not successfull . Ref id : '.$transaction_id;
             
                $mobile = $mobile;
                //Helper::sendSMS($mobile,$sms_message);
				Helper::sendNewSMS($mobile,$sms_message);
            }else{
                throw new \Exception("Error Occurred ! Please contact administrator with your consumer number , date as soon as poss", 399);
                
                // echo 'Error Occurred ! Please contact administrator with your consumer number , date as soon as poss';
            }  
        }
        return $bank_response_code.'|'.$transaction_id.'|'.$trans_date.'|'.$pay_amount.'|'.$bill_number;
    }

    public function getPaymentStatus(Request $request) {
        $transaction_id = $request->transaction_id;
        $result = Payment::where('transaction_id', $transaction_id)->first();
        $consumer_info = [];
        $consumer_name = '';
        $payment_info = [];

        if($result) {
            $payment_id         = $result->id;
            $consumer_info      = Helper::getConsumerInfo(Helper::getPrimaryConsumerNumber(Auth::guard('user')->user()->id)->consumer_number);

            $consumer_name      = $consumer_info->fname.' '.$consumer_info->lname;

            $payment_info = Payment::findOrFail($payment_id);
        }
        return view('bills.payments.check_payment_status', compact('result', 'consumer_info', 'consumer_name', 'payment_info'));
    }
   
    private function generate_checksum($CustomerID, $amount_to_be_paid = 0, $mobile_number = '', $email = '', $update_id = 'NA' ) {
		////BILL DESK////

		$checkSumKey    = config('globals.checkSumKey');
		$MerchantID     = config('globals.BillDeskMerchantID');


		$TxnAmount  		= $amount_to_be_paid;
		$CurrencyType 		= config('globals.CurrencyType');
		$Customer_email 	= $email;
		$Customer_phone 	= $mobile_number;
		$RU         		= config('globals.responseURLAPI');
		$SecurityID 		= config('globals.securityID');

        //$TxnAmount = 2;

		$bill_desk_str  = $MerchantID."|".$CustomerID."|"."NA"."|".$TxnAmount."|"."NA"."|"."NA"."|"."NA"."|".$CurrencyType."|"."NA"."|"."R"."|".$SecurityID."|"."NA"."|"."NA"."|"."F"."|".$Customer_email."|".$Customer_phone."|"."NA"."|"."NA"."|"."NA"."|".$update_id."|"."NA"."|".$RU;

		$checksum       = hash_hmac('sha256',$bill_desk_str, $checkSumKey, false);
		$checksum       = strtoupper($checksum);
		return $bill_desk_str."|".$checksum;
    }
}
