<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Payabbhi\Client as PayabbhiClient;
use Crypt, Log, Exception, DB;
use App\Models\Application;
use App\Models\OnlinePaymentSuccess;
use App\Models\OnlinePaymentProcessing;
use Razorpay\Api\Api;
/**
 * Trait for handling Payment (Payment)
 * date: 01-07-2019
 */
trait VknrlPayment
{
    // payment processing
    public function processPayment(Request $request, $encrypted_id) {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // Log::error($e);
            // saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Playing with URL Application Edit.");
            abort(404);
            // return redirect()->route(get_guard().".home")->with("error", "Whoops! Looks like you");
        }
        try {
            $application = Application::with("online_payment_tried", "online_payments_succeed")->find($decrypted_id);
            // dump($application);
        } catch (Exception $e) {
            dd($e);
            Log::emergency($e);
            return redirect()->back()->with("error", "Whoos! something went wrong. Please try again later.");
        }
        if($application->payment_status){
            return redirect()->route("student.application.index")->with("error", "Fee already paid.");
        }
        if($application->online_payments_succeed->count()){
            return redirect()->route("student.application.index")->with("error", "Fee already paid.");
        }
        DB::beginTransaction();
        try {
            $accessId = env("PAYMENT_ACCESS_ID");
            $secretKey = env("PAYMENT_SECRET_KEY");
            $merchantOrderID = env("MERCHANT_ORDER_ID", uniqid());
            $amount = 100;
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
                            "student_id"        => $application->student_id,
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
                    $application->payment_status = 1;
                    $application->status = "payment_done";
                    $application->save();
                    DB::commit();
                    $this->sendApplicationNoSMS($application);
                    return redirect()->route($this->getIndexView())->with("success", "Your Payment is already done.");
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
                    'name'      => $application->fullname,
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
            // $json = json_encode($data);
        }catch(Exception $e){
            // dd($e);
            \Log::error($e);
            DB::rollback();
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application Number:  {$application->id} Proceeding payment failed.");
            return redirect()->route("student.application.index")->with("error", "Whoops! something went wrong. try again later.");
        }
        OnlinePaymentProcessing::create([
            "application_id"    => $application->id,
            "student_id"        => $application->student_id,
            "order_id"          => $order->id,
            "amount"            => ($amount/100),
            "amount_in_paise"   => $amount,
            "currency"          => "INR",
            "merchant_order_id"    => $merchantOrderID,
            "payment_done"      => 0,
        ]);
        DB::commit();
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Proceeding for payment Application No: {$application->id}");
        $application = Application::find($decrypted_id);
        return view("student.application.process-payment", compact("application", "order", "client","data", "amount", "merchantOrderID"));
    }
    // after payment successfull
    public function paymentRecieved(Request $request, $encrypted_id) {
        $accessId = env("PAYMENT_ACCESS_ID");
        $secretKey = env("PAYMENT_SECRET_KEY");
        $merchantOrderID = env("MERCHANT_ORDER_ID", uniqid());
        Log::notice(json_encode($request->all()));
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::emergency($e);
            return redirect()->route('student.application.index')->with("error", "Whoops! Something went wrong.");
        }
        try {
            $api = new Api($accessId, $secretKey);
            
            $api->utility->verifyPaymentSignature([
                'razorpay_payment_id'    => $request->get("payment_id"),
                'razorpay_order_id'      => $request->get("order_id"),
                'razorpay_signature' => $request->get("payment_signature"),
            ]);
            $payment = $api->payment->fetch($request->get("payment_id"));
        } catch (Exception $e) {
            Log::emergency($e);
            return redirect()->route("student.application.index")->with("error", "Payment Details fetching error. Wait sometimes or contact to helpline no.");
        }
        // dd($payment);
        DB::beginTransaction();
        try {
            $application = Application::findOrFail($decrypted_id);
            // Application id from application_id , student_id is just passed so not taken.
            $online_payment = OnlinePaymentSuccess::create([
                "application_id"    => $application->id,
                "student_id"        => $application->student_id,
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
                "biller_response"          => $request->get("response"),
                "status"          => 1,
            ]);
            $online_payment->tried_process()->update(['payment_done' => 1, "online_payment_successes_id" => $online_payment->id]);
            if($payment->status == "captured"){
                $application->payment_status = 1;
            }
            $application->status = "payment_done";
            $application->save();
        } catch (Exception $e) {
            DB::rollback();
            Log::emergency($e);
            return redirect()->route("student.application.index")->with("error", "Something went wrong. Please try again later.");
        }
        DB::commit();
        $this->sendApplicationNoSMS($application);
        return redirect()->route("student.application.payment-reciept", Crypt::encrypt($application->id))->with("success", "Payment Succssfull.");
    }
    public function paymentReceipt(Request $request, $encrypted_id) {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            // dd($e);
            // Log::error();
            return redirect()->route("student.application.index")->with("error", "Whoops! something went wrong. Try again later.");
        }
        $application = Application::find($decrypted_id);
        // dd($application);
        return view($this->getReceiptView(), compact("application"));
    }
    public function getReceiptView() {
        $guard = get_guard();
        if($guard == "admin"){
            return "admin.applications.payment-receipt";
        }elseif($guard == "student"){
            return "student.application.payment-receipt";
        }

    }
    public function sendApplicationNoSMS($application)
    {
        $message = "Registration fee successfully received for you application no {$application->id}.";
        $mobile = $application->student->isd_code.$application->student->mobile_no;
        try {
            sendSMS($mobile, $message);
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }
}
