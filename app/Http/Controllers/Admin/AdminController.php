<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\CommonApplicationController;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use App\Models\Session;

class AdminController extends CommonApplicationController
{
    public function applicantList(Request $request) {
        $applicants = new User;
        if($request->get("mobile_no")){
            $applicants = $applicants->where("mobile_no", "LIKE", "%".$request->get("mobile_no")."%");
        }
        if($request->get("email")){
            $applicants = $applicants->where("email", "LIKE", "%".$request->get("email")."%");
        }
        if($request->get("name")){
            $applicants = $applicants->where("name", "LIKE", "%".$request->get("name")."%");
        }
        if($request->get("registration_no")){
            $applicants = $applicants->where("id", "=", $request->get("registration_no"));
        }
        $session=Session::where('is_active',1)->first();
        $applicants = $applicants->where(['session_id'=>$session->id,'category'=>'student'])->paginate(100);
        return view("admin.applicants.index", compact("applicants"));
    }

    public function conferenceList(Request $request){
        $applicants = new User;
        if($request->get("mobile_no")){
            $applicants = $applicants->where("mobile_no", "LIKE", "%".$request->get("mobile_no")."%");
        }
        if($request->get("email")){
            $applicants = $applicants->where("email", "LIKE", "%".$request->get("email")."%");
        }
        if($request->get("name")){
            $applicants = $applicants->where("name", "LIKE", "%".$request->get("name")."%");
        }
        if($request->get("registration_no")){
            $applicants = $applicants->where("id", "=", $request->get("registration_no"));
        }
        $session=Session::where('is_active',1)->first();
        $applicants = $applicants->where(['session_id'=>$session->id,'category'=>'conference'])->paginate(100);
        return view("admin.applicants.index", compact("applicants")); 
    }
    public function changePass(Request $request)
    {
        try {
            $id =Crypt::decrypt($request->get("user_id"));
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                "message"   => "Failed",
                "status"    => false
            ]);
        }
        try {
            $user = User::findOrFail($id);
        } catch (\Throwable $th) {
            
        }
        try {
            $user->password = bcrypt($user->mobile_no);
            $user->save();    
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                "message"   => "Failed",
                "status"    => false
            ]);
        }
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Password reset for registration no {$user->id}");
        return response()->json([
            "message"   => "Password successfully changed to mobile no.",
            "status"    => true
        ]);
    }
}
