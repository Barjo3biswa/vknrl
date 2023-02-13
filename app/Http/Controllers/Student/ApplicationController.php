<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\CommonApplicationController;
use App\Models\Application;
use App\Models\Caste;
use App\Models\ApplicationAttachment;
use Crypt, Validator, DB, Log, PDF;

class ApplicationController extends CommonApplicationController
{
    public function __construct() {
        $this->middleware("admissionController", ["except" => ["show", "index","paymentReceipt", "downloadAdmitCard"]]);
    }

    public function downloadAdmitCard($encrypted_id) {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Playing with URL Application Edit.");
        }
        try {
            $application = Application::with("caste", "attachments", "session", "admit_card_published")->find($decrypted_id);
            if(get_guard() == "student"){
                $active_session_application = getActiveSessionApplication();
            }
            if(!$application->admit_card_published){
                abort(404);
            }
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route(get_guard().".home")->with("error", "Whoops! Something went wrong. Please try again later.");
        }
        $admit_card = $application->admit_card_published;
        // return view("common/application/admit_card/admit_card_download", compact("admit_card"));
        $pdf = PDF::loadView("common/application/admit_card/admit_card_download", compact("admit_card"));
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admit card Downloaded for application no {$admit_card->application_id}.");
        $pdf->setPaper('legal', 'portrait');
        return $pdf->download("Admit-card-".$admit_card->application->id.'.pdf');
    }
    
}
