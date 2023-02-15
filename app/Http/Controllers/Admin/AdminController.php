<?php

namespace App\Http\Controllers\Admin;

use App\Conference;
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

    public function ConferenceCompleteList(Request $request)
    {
        // dd($request->all());
        $list=Conference::where('form_step','payment_done');
        if($request->registration_no){
            $list=$list->where('registration_no',$request->registration_no);
        }if($request->email){
            $list=$list->where('email',$request->email);
        }if($request->mobile_no){
            $list=$list->where('phone_no',$request->email);
        }
        if($request->export_data){
            return $this->ExportrToExcel($list);
        }
        $list=$list->paginate(100);
        
        return view('common.conference.conference-list',compact('list'));
    }

    public function ExportrToExcel($export)
    {

        $data=[
            'Registration_no' =>    'registration_no',
            'First Name'   => 'first_name',
            'Middle Name'   => 'middle_name',
            'Last Name'   => 'last_name',
            'Email'   => 'email',
            'Phone_no'   => 'phone_no',
            'Institute Name'   => 'institute_name',
            'Designation'   => 'designation',
            'Address'   => 'address',
            'Scientific Paper'   => 'scientific_paper==1?"Yes":"No"',
            'Poster Presentaion'   => 'poster_presentaion==1?"Yes":"No"',
            '9th March'   => 'first_day==1?"Yes":"No"',
            '10th March'   => 'second_day==1?"Yes":"No"',
        ];

        $columns=[];
        $col_relation = [];
        foreach( $data as $key=>$d){
            $columns[]=$key;
            $col_relation[] = $d;
        }
        $excel    = $export->get();
        $fileName = "Itm_list".'.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        );
        $callback = function () use ($excel, $columns,$col_relation) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        $count = 0;
        $array = '';
        $dynamic_array = [];
            foreach ($excel as $key=>$task) {
                foreach($col_relation as $k=>$c){
                    $val=eval('return $task->'.$c.';')??null;
                    $dynamic_array[] = $val;
                }
                fputcsv($file, $dynamic_array);
                $dynamic_array = [];
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    
    }
}
