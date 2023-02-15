<?php

namespace App\Http\Controllers\Conference;

use App\Conference;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OnlinePaymentProcessing;
use App\Models\OnlinePaymentSuccess;
use App\Models\Session;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Log;

class ConferenceController extends Controller
{
    public function save(Request $request){
        $validated = $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required',
            'phone_no' => 'required|min:10|max:10',
            'institution_name' => 'required',
            'address' => 'required',
            "scientific_paper" => 'required',
            "poster_presentation" => 'required',
            "st_day"  => 'required',
            "nd_day"  => 'required',
        ]);
        DB::beginTransaction();
        try{
            $session=Session::where('is_active',1)->first();
            Conference::create([
                'user_id'            => auth()->user()->id,
                'session_id'         => $session->id,
                'first_name'         => $request->f_name,	
                'middle_name'        => $request->m_name??null,	
                'last_name'	         => $request->l_name,
                'email'	             => $request->email,
                'phone_no'	         => $request->phone_no,
                'institute_name'     => $request->institution_name,	
                'designation'        => $request->designation??null,	
                'address'	         => $request->address,
                'scientific_paper'   => $request->scientific_paper==1?1:0,	
                'poster_presentaion' => $request->poster_presentation==1?1:0,	
                'first_day'	         => $request->st_day==1?1:0,
                'second_day'	     => $request->nd_day==1?1:0,
                'form_step'          => 'submited',
            ]);
            DB::commit();
            return redirect()->back()->with('success','Successfully Submited');
        }catch(\Exception $e){
          DB::commit(); 
          return redirect()->back()->with('error','Something went wrong');
        }
    }

    public function payment(Request $request,$id)
    {
       try {
           $decrypted = Crypt::decrypt($id);
       } catch (\Exception $e) {
           abort(404);
       }

       try{
          $application=Conference::where('id',$decrypted)->first();
       }catch(\Exception $e){
          abort(404);
       }

       DB::beginTransaction();
       try {
           $accessId = env("PAYMENT_ACCESS_ID");
           $secretKey = env("PAYMENT_SECRET_KEY");
           $merchantOrderID = env("MERCHANT_ORDER_ID", uniqid());
           $amount = 300;
           // converting amount into paise
           $amount = $amount * 100;
           //The merchant_order_id is typically the identifier of the Customer Order, Booking etc in your system
          
           $client =  new Api($accessId, $secretKey);
           $tried_records = $application->online_payment_tried->last();
           if($tried_records){
               $previous_order = $client->order->fetch($tried_records->order_id);
               // $tried_records->succed_payments()->delete();
               $order_status = (isset($previous_order["status"]) ? $previous_order["status"] : $previous_order->status);
               if(strtolower($order_status) == "paid"){
                   $payments_data = $previous_order->payments();
                   foreach($payments_data->items as $index => $payment){
                       // dd($payment);
                       $online_payment = OnlinePaymentSuccess::create([
                           "application_id"    => $application->id,
                           "student_id"        => $application->user_id,
                           "order_id"          => $previous_order->id,
                           "amount"            => ($payment->amount/100),
                           "amount_in_paise"   => $payment->amount,
                           "response_amount"   => $payment->amount,
                           "currency"          => $payment->currency,
                           "merchant_order_id" => $merchantOrderID,
                           "payment_id"        => $payment->id,
                           "payment_signature" => null,
                           "is_error"          => $payment->error_code??0,
                           "error_message"     => $payment->error_description,
                           "biller_status"          => $payment->status,
                           "biller_response"          => json_encode($payment),
                           "status"            => 1,
                       ]);
                       $online_payment->tried_process()->update(['payment_done' => 1, "online_payment_successes_id" => $online_payment->id]);
                   }
                   //    $application->payment_status = 1;
                   $application->form_step = "payment_done";
                   $application->save();
                   DB::commit();
                   return redirect()->route('student.home')->with("success", "Your Payment is already done.");
               }
           }
           // check order if already payment
           // end of checking order payment

           $order = $client->order->create([
               'amount'    =>$amount,
               'currency'  =>'INR',
               'receipt' => $merchantOrderID,
               'payment_capture' => 1, // auto capture
               "notes"     => [
                   "merchant_order_id" => (string)$merchantOrderID,
                   "payment_processing"    => true,
                   "student_id"        => $application->student_id,
                   "application_id"        => $application->id
               ]
           ]);
           $data = [
               'key'     => $accessId,
               'name'          => "VKNRL",
               'order_id'      => $order->id,
               'amount'        => $amount,
               'image'         =>  asset_public("logo.jpg"),
               'description'   => env("APP_NAME").': Order #' . $merchantOrderID,
               'prefill'     => [
                   'name'      => $application->first_name.' '.$application->middle_name.' '.$application->last_name,
                   'email'     => $application->student->email,
                   'contact'   => $application->student->mobile_no
               ],
               'notes'       => [
                   'merchant_order_id' => (string)$merchantOrderID
               ],
               'theme' => [
                   'color' => '#2E86C1'
               ]
           ];
       }catch(\Exception $e){
           dd($e);
           \Log::error($e);
           DB::rollback();
           saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application Number:  {$application->id} Proceeding payment failed.");
           return redirect()->back()->with("error", "Whoops! something went wrong. try again later.");
       }
       OnlinePaymentProcessing::create([
           "application_id"    => $application->id,
           "student_id"        => $application->user_id,
           "order_id"          => $order->id,
           "amount"            => ($amount/100),
           "amount_in_paise"   => $amount,
           "currency"          => "INR",
           "merchant_order_id"    => $merchantOrderID,
           "payment_done"      => 0,
       ]);
       DB::commit();
       saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Proceeding for payment Application No: {$application->id}");
       $application = Conference::find($decrypted);
       return view("common.conference.process-payment", compact("application", "order", "client","data", "amount", "merchantOrderID"));
       
    }


    public function paymentPost(Request $request,$id){
        $accessId = env("PAYMENT_ACCESS_ID");
        $secretKey = env("PAYMENT_SECRET_KEY");
        $merchantOrderID = env("MERCHANT_ORDER_ID", uniqid());
        Log::notice(json_encode($request->all()));
        try {
            $decrypted_id = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(404);
        }


        try {
            $api = new Api($accessId, $secretKey);
            
            $api->utility->verifyPaymentSignature([
                'razorpay_payment_id'    => $request->get("payment_id"),
                'razorpay_order_id'      => $request->get("order_id"),
                'razorpay_signature' => $request->get("payment_signature"),
            ]);
            $payment = $api->payment->fetch($request->get("payment_id"));
        } catch (\Exception $e) {
            Log::emergency($e);
            return redirect()->route("student.application.index")->with("error", "Payment Details fetching error. Wait sometimes or contact to helpline no.");
        }
        // dd($payment);
        DB::beginTransaction();
        try {
            $application = Conference::findOrFail($decrypted_id);
            // Application id from application_id , student_id is just passed so not taken.
            $online_payment = OnlinePaymentSuccess::create([
                "application_id"    => $application->id,
                "student_id"        => $application->user_id,
                "order_id"          => $request->get("order_id"),
                "amount"            => $request->get("amount"),
                "amount_in_paise"   => ($request->get("amount") * 100),
                "response_amount"   => $payment->amount,
                "currency"          => $payment->currency,
                "merchant_order_id" => $request->get("merchant_order_id"),
                "payment_id"        => $request->get("payment_id"),
                "payment_signature" => $request->get("payment_signature"),
                "is_error"          => $request->get("is_error"),
                "error_message"     => $request->get("error_message"),
                "biller_status"          => $payment->status,
                "biller_response"        => $request->get("response"),
                "status"          => 1,
            ]);
            $online_payment->tried_process()->update(['payment_done' => 1, "online_payment_successes_id" => $online_payment->id]);
            $sl=Conference::where('form_step','payment_done')->count();
            $sl=$sl+1;
            // $application->registration_no = 'VKNRL-CONF-2023-00'.$sl;
            $application->registration_no = 'VKNRL-CONF-2023-' . str_pad($sl, 4, '0', STR_PAD_LEFT);
            $application->form_step = "payment_done";
            $application->save();
        } catch (\Exception $e) {
            DB::rollback();
            Log::emergency($e);
            return redirect()->back()->with("error", "Something went wrong. Please try again later.");
        }
        DB::commit();
        return redirect()->route('student.home')->with("success", "Payment Succssfull.");
    }
}
