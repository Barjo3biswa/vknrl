<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\CommonApplicationController;
use App\Traits\ApplicationARHControll;
use App\Models\Application;
use Validator;
use Excel;
use Exception;

class ApplicationController extends CommonApplicationController
{
    use ApplicationARHControll;

    
    public function qualifiedStudentImport()
    {
        //  dd("ok");
        return view("admin.applications.upload_excel");
    }

    
    public function qualifiedStudentImportPost(Request $request)
    {
        $rules = [
            "file"  => "file|required|mimes:xls,xlsx"
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            dd($validator->errors());
        }
        $file = $request->file('file');
        $excel_records = [];
        try {
            $error = "";
            Excel::load($file, function ($reader) use (&$error, &$excel_records) {
                $date = date("Y-m-d H:i:s");
                if(sizeof($reader->toArray()) > 1){
                    if(isset($reader->toArray()[0][0])){
                        $error = "please upload excel with single sheet as shown in sample.";
                        return true;
                    }
                }
                foreach ($reader->toArray() as $row) {
                    if($row){
                        $excel_records[] = [
                            "application_id"    => (Int)trim($row["application_no"]),
                            "student_reg_no"    => (Int)trim($row["student_reg_no"]),
                            "name"              => (String)trim($row["name"]),
                            "marks"             => (float)trim($row["marks"]),
                            "out_of"            => (float)trim($row["out_of"]),
                            "paas"              => (String)trim($row["paas"]),
                        ];
                    }
                }
            });
        } catch (Exception $e) {
            \Log::error($e);
            return redirect()->back()->with('error', "Whoops! Something went wrong with your excel file. Please try again later.");
        }
        if($error){
            return redirect()->back()->with('error', $error);
        }
        dump($excel_records);
        // dump($file->getMimeType());
        // dd($request->all());
    }
    public function sendSMS(Request $request)
    {
        $rules = [
            "sms"               => "required|min:1",
            "application_ids"   => "required|array|min:1",
            "template_id"       => "required",
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            \Log::error($validator->errors());
            return redirect()
                ->back()
                ->withError($validator->errors())
                ->withInput($request->all())
                ->with("error", "Whoops! looks like you have missed something.");;
        }
        $application_ids = $request->get("application_ids");
        $applications = Application::with("student")->whereIn("id", $application_ids)->get();
        $sent_counter   = 0;
        $last_id        = "";
        $failed_counter = 0;
        try {
            if($application_ids){
                foreach ($applications as $key => $application) {
                    $sms = $request->get("sms");
                    $sms = str_replace("##name##", $application->fullname, $sms);
                    $sms = str_replace("##app_no##", $application->id, $sms);
                    $sms = str_replace("##reg_no##", $application->student_id, $sms);
                    $sms = str_replace("##hold_reason##", $application->hold_reason, $sms);
                    $sms = str_replace("##rejected_reason##", $application->reject_reason, $sms);
                    $sms = str_replace("##school_name##", env("APP_NAME", ""), $sms);
                    try {
                        sendSMS($application->student->mobile_no, $sms, $request->template_id, false);
                        $last_id = $application->id;
                        $sent_counter++;
                    } catch (\Exception $e) {
                        \Log::error($e);
                        $failed_counter ++;
                    }
                }
            }
    
        } catch (\Exception $e) {
            return redirect()->back()
                ->with("success", "Message sent total {$sent_counter} & Failed {$failed_counter} last message sent application no {$last_id}");
        }
        return redirect()
                ->back()
                ->with("success", "Message Successfully Sent to {$sent_counter} Applicants and failed {$failed_counter}.");
    }
}
