<?php

namespace App\Http\Controllers;

use App\Conference;
use App\Http\Requests\OtpVerify;
use Request;
use App\Models\Application;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd("ok");
        $application = Application::with("caste", "attachments", "student", "admit_card_published");
        if (auth("student")->check()) {
            $applications = $application->where("student_id", auth("student")->id());
        }
        $applications = $application->paginate(100);
        $conference = Conference::where('user_id',auth()->user()->id)->first();
        return view('home', compact("applications","conference"));
    }
    public function verifyOTP(OtpVerify $request)
    {
        $request->validate();
        $user = auth()->user();
        if ($user->otp_verified) {
            return back()->with("error", 'OTP is already verified.');
        }
        if (!$this->verify_otp($request->otp)) {
            return back()->with("error", 'Please enter the correct OTP.');
        }
        try {

            $user = auth()->user();
            $user->otp_verified = 1;
            $user->otp_verified_at = date("Y-m-d H:i:s");
            $user->save();
        } catch (\Exception $e) {
            \Log::error($e);
        }
        return redirect()->route("student.home")->with("success", "OTP verified successfully.");
    }
    private function verify_otp($otp)
    {
        if ($otp == auth()->guard("student")->user()->otp) {
            return true;
        }
        return false;
    }
    public function resendOTP(Request $request) {
        $user = auth("student")->user();
        if($user->otp_verified){
            return redirect()->back()->with("error", "OTP is already verified.");
        }else{
            $otp = mt_rand(123452,998877);
            $user->otp = $otp;
            if($user->otp_retry < config("vknrl.otp-limit")){
                $user->save();
                //$message = "Dear Applicant, {$user->otp} is the OTP for Registration to apply at VKNRL School of Nursing";
                $message = $user->otp . " is your OTP. VKNRL";
                $tempid='1207161519784471974';
                sendSMS($user->mobile_no, $message,$tempid);
                $user->increment('otp_retry');
                return redirect()->back()->with("success", "OTP successfully resent. Remaining ".(config("vknrl.otp-limit") - $user->otp_retry)." times.");
            }else{
                return redirect()->back()->with("error", "You have crossed the limit of OTP resend. Try another valid mobile no in registration. Or contact school authority.");
            }
        }
        return redirect()->back()->with("success", "OTP successfully resent.");

    }
}
