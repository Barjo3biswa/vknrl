<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdmitCard;
use App\Models\Application;
use App\Models\Caste;
use App\Models\ExamCenter;
use App\Models\Session;
use Validator;
use Exception;
use Crypt, Log, PDF;
use App\Traits\ExportAdmitCard;
use function GuzzleHttp\json_encode;

class AdmitCardController extends Controller
{
    use ExportAdmitCard;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // dd("ok");
        $limit          = 200;
        $castes         = Caste::all();
        $admit_cards    = AdmitCard::with(["application.caste", "exam_center"]);
        if($request->get("application_id")){
            $admit_cards = $admit_cards->where("application_id", $request->get("application_id"));
        }
        if($request->get("registration_no")){
            $admit_cards = $admit_cards->whereHas("application", function($query) use ($request){
                return $query->where("student_id", $request->get("registration_no"));
            });
        }
        if($request->get("caste")){
            $admit_cards = $admit_cards->whereHas("application", function($query) use ($request){
                return $query->where("caste_id", $request->get("caste"));
            });
        }
        if($request->get("applicant_name")){
            $admit_cards = $admit_cards->whereHas("application", function($query) use ($request){
                return $query->where("fullname", "LIKE", "%".$request->get("applicant_name")."%");
            });
        }
        if($request->get("exam_center")){
            $admit_cards = $admit_cards->where("exam_center_id", $request->get("exam_center"));
        }
        if($request->get("session")){
            // $admit_cards = $admit_cards->where("session_id", $request->get("session"));
            $admit_cards = $admit_cards->whereHas("application", function($query) use ($request){
                return $query->where("session_id",$request->get("session"));
            });
        }
        if($request->get("limit")){
            $limit = $request->get("limit");
        }else{
            $request->merge([
                "limit" => $limit
            ]);
        }
        if($request->get("export-excel")){
            $admit_cards = $admit_cards->orderBy("application_id", "ASC")->get();
            return $this->exportAdmitCard($admit_cards);
        }
        $admit_cards = $admit_cards->paginate($limit);
        $exam_centers = ExamCenter::all();
        $sessioon=Session::get();
        return view("admin.admin_card.index", compact("admit_cards", "castes", "exam_centers","sessioon"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        /* 
        * Generate function 
        */
        (Int)$limit = 500;
        $applications =Application::with("caste");
        if($request->get("status") == "draft"){
            $applications = $applications->has("admit_card_draft",">=", 1);
        }elseif($request->get("status") == "not_generated"){
            $applications = $applications->has("admit_card","=", 0);
        }else{
            $applications = $applications->has("admit_card_published", "=", 0);
        }
        if($request->get("applicant_name_starting")){
            $applications = $applications->where("fullname","LIKE", $request->get("applicant_name_starting")."%");
        }
        if($request->get("application_id_from")){
            $applications = $applications->where("id",">=", $request->get("application_id_from"));
        }
        if($request->get("application_id_to")){
            $applications = $applications->where("id","<=", $request->get("application_id_to"));
        }
        if($request->get("limit")){
            $limit = $request->get("limit");
        }else{
            $request->merge([
                "limit" => $limit
            ]);
        }
        // dump($applications->toSql());
        $applications = $applications
        ->whereStatus("accepted")
        ->paginate($limit);
        
        // dump($applications->toSql());
        // $applications = $applications->get();
        $castes = Caste::all();
        $exam_centers = ExamCenter::all();
        return view("admin.admin_card.generate", compact("applications", "castes", "exam_centers"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = AdmitCard::$rules;
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            // dd($validator->errors());
            return redirect()
                ->back()
                ->withErrors($validator->errors())
                ->withInput($request->all());
        }
        if(!sizeof((array)$request->applications)){
            return redirect()
                ->back()
                ->with("error", "Please select at-least one admit card to generate.");
        }
        try {
            $application_ids = [];
            foreach($request->applications as $application_id){
                $application_ids[] = $application_id;
                $insert_data[] = [
                    "application_id"    => $application_id,
                    "exam_center_id"    => $request->exam_center,
                    "exam_date"         => dateFormat($request->date, "Y-m-d"),
                    "exam_time"         => $request->time,
                    "generated_by"      => auth(get_guard())->id(),
                    "created_at"        => date("Y-m-d H:i:s"),
                    "updated_at"        => date("Y-m-d H:i:s")
                ];
            }
            AdmitCard::whereIn("application_id", $application_ids)->delete();
            AdmitCard::insert($insert_data);
            \Log::debug('Admit Card Generated '.implode(",", $application_ids));
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admit Card Generated for ".implode(",", $application_ids));
        } catch (Exception $e) {
            Log::error($e);
            return redirect()
                    ->back()
                    ->with("error", "Whoops! something went wrong. Please try again later.");
        }
        return redirect()
            ->back()
            ->with("success", "Admit Card Successfully Generated for the selected applications.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $encrypted_id
     * @return \Illuminate\Http\Response
     */
    public function show($encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route("admin.admit-card.index")
                    ->with("error", "Whoops! Something went wrong please try again later.");
        }
        try {
            $admit_card = AdmitCard::with(["application.caste","application.attachments", "exam_center"])->findOrFail($decrypted_id);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()
                ->route("admin.admit-card.index")
                ->with("error", "Whoops! Something went wrong please try again later.");
        }
        return view("admin.admin_card.show", compact("admit_card"));
    }
    public function downloadPdfAdmin(Request $request, $encrypted_id) {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route("admin.admit-card.index")
                    ->with("error", "Whoops! Something went wrong please try again later.");
        }
        try {
            $admit_card = AdmitCard::with(["application.caste","application.attachments", "exam_center"])->findOrFail($decrypted_id);
            // if(!$admit_card->publish){
            //    return  redirect()->back()->with("error", "Admit card is not published yet.");
            // }
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()
                ->route("admin.admit-card.index")
                ->with("error", "Whoops! Something went wrong please try again later.");
        }
        // return view("common/application/admit_card/admit_card_download", compact("admit_card"));
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admit card Downloaded for application no {$admit_card->application_id}.");
        $pdf = PDF::loadView("common/application/admit_card/admit_card_download", compact("admit_card"));
        $pdf->setPaper('legal', 'portrait');
        return $pdf->download("Admit-card-".$admit_card->application->id.'.pdf');
    }
    public function downloadPdfApplicant(Request $request, $encrypted_id) {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route("admin.admit-card.index")
                    ->with("error", "Whoops! Something went wrong please try again later.");
        }
        try {
            $admit_card = AdmitCard::with(["application.caste","application.attachments", "exam_center"])->findOrFail($decrypted_id);
            if(!$admit_card->publish){
               return  redirect()->back()->with("error", "Admit card is not published yet.");
            }
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()
                ->route("admin.admit-card.index")
                ->with("error", "Whoops! Something went wrong please try again later.");
        }
        // return view("common/application/admit_card/admit_card_download", compact("admit_card"));
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admit card Downloaded {$admit_card->application_id}.");
        $pdf = PDF::loadView("common/application/admit_card/admit_card_download", compact("admit_card"));
        $pdf->setPaper('legal', 'portrait');
        return $pdf->download("Admit-card-".$admit_card->application->id.'.pdf');
    }
    public function publishAdmit(Request $request) {
        if(!sizeof((array)$request->admit_cards)){
            return redirect()->back()->with("error", "Please select at-least one admit card to publish.");
        }
        $card_ids = $request->admit_cards;
        try {
            AdmitCard::whereIn("id",$card_ids)->update([
                "publish"   => 1
            ]);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()->with("error", "Whoops! Something went wrong. try again later.");
        }
        \Log::debug('Admit Card published '.implode(",",$card_ids));
        saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Admit card published ".sizeof((array)$card_ids));
        return redirect()->back()->with("success", "Successfully Published.");
    }
}
