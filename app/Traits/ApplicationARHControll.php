<?php
namespace App\Traits;

use App\Models\Application;
use Log, Crypt, Exception, Validator;

use Illuminate\Http\Request;
/**
 * Trait for handling Accept Reject Hold (ARH) of Application from Admin Panel
 * date: 09-07-2019
 */
trait ApplicationARHControll
{
    public function acceptApplication(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            return $this->returnResponse(0, "Something went wrong try again later or contact administrator");
        }
        try {
            $application = Application::findOrFail($decrypted_id);
        } catch (Exception $e) {
            return $this->returnResponse(0, "Selected Application Not found.");
        }
        if($application->status != "payment_done" && $application->status != "on_hold"){
            return $this->returnResponse(0, "Sorry! Application status {$application->status} so, unable to Accept.");
        }
        try {
            $application->status = "accepted";
            $application->save();
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application {$application->id} Accepted Administrator side.");
            return $this->returnResponse(1, "Application is Accepted", $application);
        } catch (\Exception $th) {
            return $this->returnResponse(0, "Something went wrong try again later or contact administrator");
        }
    }
    public function rejectApplication(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            return $this->returnResponse(0, "Something went wrong try again later or contact administrator");
        }
        try {
            $application = Application::findOrFail($decrypted_id);
        } catch (Exception $e) {
            return $this->returnResponse(0, "Selected Application Not found.");
        }
        if($application->status != "payment_done" && $application->status != "on_hold"){
            return $this->returnResponse(0, "Sorry! Application status {$application->status} so, unable to Reject.");
        }
        // validate here
        $rules = [
            "rejection_reason" => "required|min:10"
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return $this->returnResponse(0, "Please fullfill the required critaria. ".implode(",\n",$validator->errors()));
        }
        try {
            $application->status = "rejected";
            $application->reject_reason = $request->get("rejection_reason");
            $application->save();
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application {$application->id} Rejected from Administrator side.");
            return $this->returnResponse(1, "Application is Rejected", $application);
        } catch (\Exception $th) {
            return $this->returnResponse(0, "Something went wrong try again later or contact administrator");
        }
    }
    public function holdApplication(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            return $this->returnResponse(0, "Something went wrong try again later or contact administrator");
        }
        try {
            $application = Application::findOrFail($decrypted_id);
        } catch (Exception $e) {
            return $this->returnResponse(0, "Selected Application Not found.");
        }
        if($application->status != "payment_done"){
            return $this->returnResponse(0, "Sorry! Application status {$application->status} so, unable to Hold.");
        }
        // validate here
        $rules = [
            "holding_reason" => "required|min:10"
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return $this->returnResponse(0, "Please fullfill the required critaria. ".implode(",\n",$validator->errors()));
        }
        try {
            $application->status = "on_hold";
            $application->hold_reason = $request->get("holding_reason");
            $application->save();
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Application {$application->id} Hold from Administrator side.");
            return $this->returnResponse(1, "Application is on hold", $application);
        } catch (\Exception $th) {
            return $this->returnResponse(0, "Something went wrong try again later or contact administrator");
        }
    }
    private function returnResponse($status, $message, $application = [], $status_code = 200){
        return response()->json([
            "message"   => $message,
            "application"=> (gettype($application) == "object" ? $application->only(["id", "status", "reject_reason", "hold_reason"]): ""),
            "status"    => $status
        ], $status_code);
    }

}