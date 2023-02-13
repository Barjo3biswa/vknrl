<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationAttachment;
use App\Models\Caste;
use App\Models\Session;
use Crypt;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Validator;
use App\Traits\VknrlPayment;
use App\Traits\ApplicationExport;
use App\Traits\ApplicationDownloader;

class CommonApplicationController extends Controller
{
    use VknrlPayment, ApplicationExport, ApplicationDownloader;
    public $guard;
    public $user;
    public function __construct()
    {
        // $this->guard = get_guard();
        // $this->user = auth()->guard($this->guard)->user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $session=Session::where('is_active',1)->first();
        // dd($session->id);
        $application = Application::with("caste", "attachments", "student", "admit_card_published")->where('session_id',$session->id);
        if($request->has("status") && $request->get("status") != ""){
            if($request->get("status") != "all"){
                if(in_array($request->get("status"), ["application_submitted", "payment_pending"])){
                    $application = $application->whereIn("status", ["application_submitted", "payment_pending"]);
                }else
                    $application = $application->where("status", $request->get("status"));
            }
            if(auth("admin")->check() && !in_array($request->get("status"), ["application_submitted", "payment_pending"])){
                $application = $application->whereNotIn("status", ["application_submitted", "payment_pending"]);
            }
        }else{
            if(auth("admin")->check()){
                $application = $application->whereIn("status", Application::$statuses_for_admin);
            }
        }
        if($request->has("aplicant_name") && $request->get("aplicant_name") != ""){
            $application = $application->where("fullname", "LIKE","%".$request->get("aplicant_name")."%");
        }
        if($request->has("caste") && $request->get("caste") != ""){
            $application = $application->where("caste_id", $request->get("caste"));
        }
        if($request->has("application_id") && $request->get("application_id") != ""){
            $application = $application->where("id",$request->get("application_id"));
        }
        if($request->has("anm_or_lhv") && $request->get("anm_or_lhv")){
            $application = $application->where("anm_or_lhv", $request->get("anm_or_lhv"));
        }
        if($request->get("payment_date_from")){
            $application = $application->whereHas("online_payments_succeed", function($query) use ($request){
                return $query->whereDate("created_at",">=", dateFormat($request->payment_date_from, "Y-m-d"));
            });
        }
        if($request->get("payment_date_to")){
            $application = $application->whereHas("online_payments_succeed", function($query) use ($request){
                return $query->whereDate("created_at","<=", dateFormat($request->payment_date_to, "Y-m-d"));
            });
        }
        if (auth("student")->check()) {
            $applications = $application->where("student_id", auth("student")->id());
        }
        $castes = Caste::all();
        if($request->has("export-data")){
            $applications = $application->get();
            return $this->ExportApplicationData($applications, $request);
        }else
            $applications = $application->paginate(100);
        // dump($applications);
        
        return view($this->getIndexView(), compact("applications", "castes","session"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active_session = getActiveSession();
        $active_session_application = getActiveSessionApplication();
        if ($active_session->name == "NA") {
            return redirect()->route("student.home")->with("error", "Online Application is not started for the current session.");
        }
        if($active_session_application){
            return redirect()->route("student.home")->with("error", "Application is already submited. <a target='_blank' href='".route("student.application.show", Crypt::encrypt($active_session_application->id))."'>Click Here to View.</a>");
        }
        $castes = Caste::all();
        return view("student.application.create", compact("active_session", "castes"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Application::count() == 0) {
            \DB::statement('ALTER TABLE applications AUTO_INCREMENT = 1000;');
        }
        $active_session = getActiveSession();
        $active_session_application = getActiveSessionApplication();
        if ($active_session->name == "NA") {
            return redirect()->route("student.home")->with("error", "Online Application is not started for the current session.");
        }
        if ($active_session_application) {
            return redirect()->route("student.home")->with("error", "Application is already submited. <a target='_blank' href='" . route("student.application.show", Crypt::encrypt($active_session_application->id)) . "'>Click Here to View.</a>");
        }
        $rules = $this->getRules("personal_information");
        if((Int)$request->get("anm_or_lhv")){
            $rules = $this->ConvertNullableToRequired($rules, $string = "anm_or_lhv");
        }
        $validator = Validator::make($request->all(), $rules);
        
        $validator->sometimes('sub_cat', 'required|max:50', function ($input) {
            return in_array($input->caste , [1]);
        });
        DB::beginTransaction();
        try {
            if ($validator->fails()) {
                Log::error($validator->errors());
                // dd($validator->errors());
                return redirect()->route("student.application.create")->withInput($request->all())->withErrors($validator);
            }
            $lowerLimit = Application::$applicant_lower_age_limit;
            $upperLimit = Application::$applicant_upper_age_limit;

            $Lower_limit_extended_dob = strtotime($request->dob . "+ {$lowerLimit} years");
            $upper_limit_extended_dob = strtotime($request->dob . "+ {$upperLimit} years");

            $limit_date = strtotime(Application::$dob_compare_to);

            if ($Lower_limit_extended_dob > $limit_date) {
                $validator->errors()->add('dob', "Age minimum limit is {$lowerLimit} years.");
                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }

            if ($upper_limit_extended_dob < $limit_date) {
                $validator->errors()->add('dob', "Age maximum limit is {$upperLimit} years.");
                return redirect()->route("student.application.create")->withInput($request->all())->withErrors($validator);
            }

            // dump($validator->errors());
            

            $application_data = [
                "fullname"          => $request->get("fullname"),
                "gender"            => $request->get("gender"),
                "student_id"        => auth()->guard("student")->user()->id,
                "father_name"       => $request->get("father_name"),
                "father_occupation" => $request->get("father_occupation"),

                "mother_name"       => $request->get("mother_name"),
                "mother_occupation" => $request->get("mother_occupation"),
                "marital_status"    => $request->get("maritial_status"),
                "religion"          => $request->get("religion"),
                "nationality"       => $request->get("nationality"),
                "sub_cat"           => $request->get("sub_cat"),
                "dob"               => dateFormat($request->get("dob"), "Y-m-d"),
                "caste_id"          => $request->get("caste"),
                "session_id"        => $active_session->id,
                "form_step"         => 1,
                "person_with_disablity"         => $request->get("disablity"),
                "anm_or_lhv"         => $request->get("anm_or_lhv"),
                "anm_or_lhv_registration"         => $request->get("anm_or_lhv_registration"),
                // "bpl"               => $request->get("bpl"),
            ];
            $application = Application::create($application_data);
        } catch (\Execption $e) {
            DB::rollback();
            Log::error($e);
            return redirect()->back()->withInput($request->all())->with("error", "Whoops! something went wrong.");
        }
        DB::commit();
        return redirect()->route("student.application.edit", Crypt::encrypt($application->id))->with("success", "Step 1 form saved successfully.");
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOld(Request $request)
    {
        $active_session = getActiveSession();
        $active_session_application = getActiveSessionApplication();
        if ($active_session->name == "NA") {
            return redirect()->route("student.home")->with("error", "Online Application is not started for the current session.");
        }
        if ($active_session_application) {
            return redirect()->route("student.home")->with("error", "Application is already submited. <a target='_blank' href='" . route("student.application.show", Crypt::encrypt($active_session_application->id)) . "'>Click Here to View.</a>");
        }
        $rules = Application::$rules;
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            dd($validator->errors());
            return redirect()->route("student.application.create")->withInput($request->all())->withErrors($validator);
        }
        DB::beginTransaction();
        try {
            $application_data = [
                "fullname" => $request->get("fullname"),
                "gender" => $request->get("gender"),
                "student_id" => auth()->guard("student")->user()->id,
                "father_name" => $request->get("father_name"),
                "father_occupation" => $request->get("father_occupation"),

                "mother_name" => $request->get("mother_name"),
                "mother_occupation" => $request->get("mother_occupation"),
                "marital_status" => $request->get("maritial_status"),
                "religion" => $request->get("religion"),
                "nationality" => $request->get("nationality"),
                "dob" => dateFormat($request->get("dob"), "Y-m-d"),
                "ncl_valid_upto" => $request->get("ncl_valid_upto"),
                "caste_id" => $request->get("caste"),
                "session_id" => $active_session->id,
                // address
                "permanent_village_town" => $request->get("permanent_vill_town"),
                "permanent_po" => $request->get("permanent_po"),
                "permanent_ps" => $request->get("permanent_ps"),
                "permanent_state" => $request->get("permanent_state"),
                "permanent_district" => $request->get("permanent_district"),
                "permanent_pin" => $request->get("permanent_pin"),
                "permanent_contact_number" => $request->get("permanent_contact"),

                "correspondence_village_town" => $request->get("correspondence_vill_town"),
                "correspondence_po" => $request->get("correspondence_po"),
                "correspondence_ps" => $request->get("correspondence_ps"),
                "correspondence_state" => $request->get("correspondence_state"),
                "correspondence_district" => $request->get("correspondence_district"),
                "correspondence_pin" => $request->get("correspondence_pin"),
                "correspondence_contact_number" => $request->get("correspondence_contact"),
                // academic
                "other_qualification" => $request->get("other_qualification"),
                "english_mark_obtain" => $request->get("english_mark_obtained"),
                "academic_10_stream" => $request->get("academic_10_stream"),
                "academic_10_year" => $request->get("academic_10_year"),
                "academic_10_board" => $request->get("academic_10_board"),
                "academic_10_school" => $request->get("academic_10_stream"),
                "academic_10_subject" => $request->get("academic_10_subject"),
                "academic_10_mark" => $request->get("academic_10_mark"),
                "academic_10_percentage" => $request->get("academic_10_percentage"),

                "academic_12_stream" => $request->get("academic_12_stream"),
                "academic_12_year" => $request->get("academic_12_year"),
                "academic_12_board" => $request->get("academic_12_board"),
                "academic_12_school" => $request->get("academic_12_school"),
                "academic_12_subject" => $request->get("academic_12_subject"),
                "academic_12_mark" => $request->get("academic_12_mark"),
                "academic_12_percentage" => $request->get("academic_12_percentage"),

                "academic_voc_stream" => $request->get("academic_voc_stream"),
                "academic_voc_year" => $request->get("academic_voc_year"),
                "academic_voc_board" => $request->get("academic_voc_board"),
                "academic_voc_school" => $request->get("academic_voc_school"),
                "academic_voc_subject" => $request->get("academic_voc_subject"),
                "academic_voc_mark" => $request->get("academic_voc_mark"),
                "academic_voc_percentage" => $request->get("academic_voc_percentage"),

                "academic_anm_stream" => $request->get("academic_anm_stream"),
                "academic_anm_year" => $request->get("academic_anm_year"),
                "academic_anm_board" => $request->get("academic_anm_board"),
                "academic_anm_school" => $request->get("academic_anm_school"),
                "academic_anm_subject" => $request->get("academic_anm_subject"),
                "academic_anm_mark" => $request->get("academic_anm_mark"),
                "academic_anm_percentage" => $request->get("academic_anm_percentage"),
            ];
            $academic_details = [];
            $application = Application::create($application_data);
            $uploaded_docs = $this->storeDocs($request, $application);
            // $table->unsignedInteger("application_id");
            // $table->string("doc_name");
            // $table->string("file_name");
            // $table->string("original_name");
            // $table->string("mime_type");
            // $table->string("destination_path");
            $attachment_data = [];
            if ($uploaded_docs) {
                foreach ($uploaded_docs as $index => $doc) {
                    $attachment_data[] = [
                        "application_id" => $application->id,
                        "doc_name" => $doc["doc_name"],
                        "file_name" => $doc["file_name"],
                        "original_name" => $doc["original_name"],
                        "mime_type" => $doc["mime_type"],
                        "destination_path" => $doc["destination_path"],
                        "created_at" => current_date_time(),
                        "updated_at" => current_date_time(),
                    ];
                }
            }
            if ($attachment_data) {
                ApplicationAttachment::insert($attachment_data);
            }
        } catch (\Execption $e) {

            DB::rollback();
            Log::error($e);
            return redirect()->back()->withInput($request->all())->with("error", "Whoops! something went wrong.");
        }
        DB::commit();
        return redirect()->route("student.home")->with("success", "Application is Successfully Submitted. <a href='" . route("student.application.show", Crypt::encrypt($application->id)) . "'>Click Here</a> to view.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $encrypted_id)
    {
        try {
            $id = Crypt::decrypt($encrypted_id);
            $application = Application::with("attachments")->whereId($id);
            if (auth()->guard("student")->check()) {
                $application = $application->where("student_id", auth()->guard("student")->user()->id);
            }
            $application = $application->first();
            // dump($application);

        } catch (\Execption $e) {
            Log::error($e);
            return redirect()->route("student.home")->with("error", "Whoops! Something went wrong.");
        }
        if($request->has("download-pdf")){
            return $this->downloadApplicationAsPDF($application);
        }
        return view($this->getApplicationView(), compact("application"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Playing with URL Application Edit.");
        }

        // dd($decrypted_id);
        // dd("Halt Here");
        try {
            $application = Application::with("caste", "attachments", "session")->find($decrypted_id);
            if(get_guard() == "student"){
                $active_session_application = getActiveSessionApplication();
            }
            // dd($application);
            $castes = Caste::all();
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->route(get_guard().".home")->with("error", "Whoops! Something went wrong. Please try again later.");
        }
        if(!applicatinEditPermission($application)){
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step One permission denied. Application id {$application->id}");
            return redirect()->route(get_guard().".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
        }
        if(!isEditAvailable($application)){            
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Option no longer available Application no {$application->id}, Status: {$application->status}");
            return redirect()->route(get_guard().".home")->with("error", "Access Denied. Edit option not available.");
        }
        if($application->form_step >=4 ){
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application NO {$application->id}.");
        }else
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Inserting Application NO {$application->id} Step {$application->form_step}.");
        return view($this->getApplicationEditView(), compact("application", "active_session_application", "castes"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function storeDocs($request, $application)
    {
        $destinationPath = public_path('uploads/' . $application->student_id . "/" . $application->id);
        // $passport_name = '';
        // $sign_name = '';
        // $marksheet_name = '';
        // $pass_certificate_name = '';
        // $caste_certificate_name = '';
        $return_data = [];
        if (request()->hasFile('passport_photo')) {
            $passport_photo = request()->file('passport_photo');
            $passport_photo_name = date('YmdHis') . "_" . rand(4512, 6859) . "-passport_photo." . $passport_photo->getClientOriginalExtension();
            $passport_photo->move($destinationPath . "/", $passport_photo_name);
            $return_data[] = [
                "doc_name" => "passport_photo",
                "file_name" => $passport_photo_name,
                "original_name" => $passport_photo->getClientOriginalName(),
                "mime_type" => $passport_photo->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('signature')) {
            $signature = request()->file('signature');
            $signature_name = date('YmdHis') . "_" . rand(4512, 6859) . "-signature." . $signature->getClientOriginalExtension();
            $signature->move($destinationPath . "/", $signature_name);
            $return_data[] = [
                "doc_name" => "signature",
                "file_name" => $signature_name,
                "original_name" => $signature->getClientOriginalName(),
                "mime_type" => $signature->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('caste_certificate')) {
            $caste_certificate = request()->file('caste_certificate');
            $caste_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-caste_certificate." . $caste_certificate->getClientOriginalExtension();
            $caste_certificate->move($destinationPath . "/", $caste_certificate_name);
            $return_data[] = [
                "doc_name" => "caste_certificate",
                "file_name" => $caste_certificate_name,
                "original_name" => $caste_certificate->getClientOriginalName(),
                "mime_type" => $caste_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('prc_certificate')) {
            $prc_certificate = request()->file('prc_certificate');
            $prc_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-prc_certificate." . $prc_certificate->getClientOriginalExtension();
            $prc_certificate->move($destinationPath . "/", $prc_certificate_name);
            $return_data[] = [
                "doc_name" => "prc_certificate",
                "file_name" => $prc_certificate_name,
                "original_name" => $prc_certificate->getClientOriginalName(),
                "mime_type" => $prc_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('age_proof_certificate')) {
            $age_proof_certificate = request()->file('age_proof_certificate');
            $age_proof_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-age_proof_certificate." . $age_proof_certificate->getClientOriginalExtension();
            $age_proof_certificate->move($destinationPath . "/", $age_proof_certificate_name);
            $return_data[] = [
                "doc_name" => "age_proof_certificate",
                "file_name" => $age_proof_certificate_name,
                "original_name" => $age_proof_certificate->getClientOriginalName(),
                "mime_type" => $age_proof_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('12_admit_card')) {
            $admit_card_12 = request()->file('12_admit_card');
            $admit_card_12_name = date('YmdHis') . "_" . rand(4512, 6859) . "-admit_card_12." . $admit_card_12->getClientOriginalExtension();
            $admit_card_12->move($destinationPath . "/", $admit_card_12_name);
            $return_data[] = [
                "doc_name" => "admit_card_12",
                "file_name" => $admit_card_12_name,
                "original_name" => $admit_card_12->getClientOriginalName(),
                "mime_type" => $admit_card_12->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('12_marksheet')) {
            $marksheet_12 = request()->file('12_marksheet');
            $marksheet_12_name = date('YmdHis') . "_" . rand(4512, 6859) . "-marksheet_12." . $marksheet_12->getClientOriginalExtension();
            $marksheet_12->move($destinationPath . "/", $marksheet_12_name);
            $return_data[] = [
                "doc_name" => "marksheet_12",
                "file_name" => $marksheet_12_name,
                "original_name" => $marksheet_12->getClientOriginalName(),
                "mime_type" => $marksheet_12->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('document_mentioning_name_of_the_school_class_10')) {
            $school_mentioned_certificate = request()->file('document_mentioning_name_of_the_school_class_10');
            $school_mentioned_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-school_mentioned_certificate." . $school_mentioned_certificate->getClientOriginalExtension();
            $school_mentioned_certificate->move($destinationPath . "/", $school_mentioned_certificate_name);
            $return_data[] = [
                "doc_name" => "document_mentioning_name_of_the_school_class_10",
                "file_name" => $school_mentioned_certificate_name,
                "original_name" => $school_mentioned_certificate->getClientOriginalName(),
                "mime_type" => $school_mentioned_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('anm_registration')) {
            $anm_registration = request()->file('anm_registration');
            $anm_registration_name = date('YmdHis') . "_" . rand(4512, 6859) . "-anm_registration." . $anm_registration->getClientOriginalExtension();
            $anm_registration->move($destinationPath . "/", $anm_registration_name);
            $return_data[] = [
                "doc_name" => "anm_registration",
                "file_name" => $anm_registration_name,
                "original_name" => $anm_registration->getClientOriginalName(),
                "mime_type" => $anm_registration->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('anm_marksheet')) {
            $anm_marksheet = request()->file('anm_marksheet');
            $anm_marksheet_name = date('YmdHis') . "_" . rand(4512, 6859) . "-anm_marksheet." . $anm_marksheet->getClientOriginalExtension();
            $anm_marksheet->move($destinationPath . "/", $anm_marksheet_name);
            $return_data[] = [
                "doc_name" => "anm_marksheet",
                "file_name" => $anm_marksheet_name,
                "original_name" => $anm_marksheet->getClientOriginalName(),
                "mime_type" => $anm_marksheet->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('bpl_document')) {
            $bpl_document = request()->file('bpl_document');
            $bpl_document_name = date('YmdHis') . "_" . rand(4512, 6859) . "-bpl_document." . $bpl_document->getClientOriginalExtension();
            $bpl_document->move($destinationPath . "/", $bpl_document_name);
            $return_data[] = [
                "doc_name" => "bpl_document",
                "file_name" => $bpl_document_name,
                "original_name" => $bpl_document->getClientOriginalName(),
                "mime_type" => $bpl_document->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        if (request()->hasFile('disablity_certificate')) {
            $disablity_certificate = request()->file('disablity_certificate');
            $disablity_certificate_name = date('YmdHis') . "_" . rand(4512, 6859) . "-disablity_certificate." . $disablity_certificate->getClientOriginalExtension();
            $disablity_certificate->move($destinationPath . "/", $disablity_certificate_name);
            $return_data[] = [
                "doc_name" => "disablity_certificate",
                "file_name" => $disablity_certificate_name,
                "original_name" => $disablity_certificate->getClientOriginalName(),
                "mime_type" => $disablity_certificate->getClientMimeType(),
                "destination_path" => $destinationPath,
            ];
        }
        return $return_data;
    }

    public function stepOneUpdate(Request $request, $encrypted_id)
    {
        $application_form = "personal_information";
        $rules = $this->getRules($application_form);
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
            $application = Application::find($decrypted_id);
            if(!applicatinEditPermission($application)){
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step One permission denied. Application id {$application->id}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
            }
            if(!isEditAvailable($application)){            
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Option no longer available Application no {$application->id}, Status: {$application->status}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. Edit option not available.");
            }
            $message = "Editing Application No {$application->id} Step 1.";
            if($application->form_step == 2){
                $message = "Updating Application No {$application->id} Step 1.";
            }
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with("error", "Whoops! Lookes like you have mess something.");
        }
        if((Int)$request->get("anm_or_lhv")){
            $rules = $this->ConvertNullableToRequired($rules, $string = "anm_or_lhv");
        }
        $validator = Validator::make($request->all(), $rules);
        $validator->sometimes('sub_cat', 'required|max:50', function ($input) {
            return in_array($input->caste , [1]);
        });
        if($validator->fails()){
            Log::debug($validator->errors());
            return redirect()->to(url()->previous() . "#step-one-update")
            ->withErrors($validator)->withInput($request->all());
        }
        $lowerLimit = Application::$applicant_lower_age_limit;
        $upperLimit = Application::$applicant_upper_age_limit;

        $Lower_limit_extended_dob = strtotime($request->dob . "+ {$lowerLimit} years");
        $upper_limit_extended_dob = strtotime($request->dob . "+ {$upperLimit} years");

        $limit_date = strtotime(Application::$dob_compare_to);

        if ($Lower_limit_extended_dob > $limit_date) {
            $validator->errors()->add('dob', "Age minimum limit is {$lowerLimit} years.");
            return redirect()
            ->to(url()->previous() . "#step-one-update")
            ->withInput($request->all())->withErrors($validator);
        }

        if ($upper_limit_extended_dob < $limit_date) {
            $validator->errors()->add('dob', "Age maximum limit is {$upperLimit} years.");
            return redirect()
            ->to(url()->previous() . "#step-one-update")
            ->withInput($request->all())->withErrors($validator);
        }
        DB::beginTransaction();
        $old_application_form_step = $application->form_step;
        try {
            // Personal Information
            $application->fullname  = $request->get("fullname");
            $application->gender    = $request->get("gender");
            $application->sub_cat   = $request->get("sub_cat");

            $application->father_name       = $request->get("father_name");
            $application->father_occupation = $request->get("father_occupation");
            
            $application->mother_name       = $request->get("mother_name");
            $application->mother_occupation = $request->get("mother_occupation");

            $application->marital_status = $request->get("maritial_status");
            $application->religion       = $request->get("religion");
            $application->caste_id       = $request->get("caste");
            $application->dob            = dateFormat($request->get("dob"), "Y-m-d");

            $application->person_with_disablity = $request->get("disablity");
            $application->anm_or_lhv         = $request->get("anm_or_lhv");
            $application->anm_or_lhv_registration         = $request->get("anm_or_lhv_registration");
            if(!$request->get("anm_or_lhv")){
                $application->academic_anm_stream       = null;
                $application->academic_anm_year         = null;
                $application->academic_anm_board        = null;
                $application->academic_anm_school       = null;
                $application->academic_anm_subject      = null;
                $application->academic_anm_mark         = null;
                $application->academic_anm_percentage   = null;
                // attachments also delte.
                // $application->attachments()->where("doc_name", "anm")->delete();
            }
            // $application->bpl                   = $request->get("bpl");
            // below form_step not required due to first step already completed.
            if($application->form_step == 0){
                $application->form_step = 1;
            }
            // dump($application->getChanges());
            // dd($application);
            $application->save();
        } catch (Exception $e) {
            // dd($e);
            DB::rollback();
            Log::emergency($e);
            return redirect()
                    ->to(url()->previous() . "#step-two-update")
                    ->with("error", "Whoops! Something went wrong. Please try again later.")
                    ->withInput($request->all());
        }
        DB::commit();
        $success_message = "Step 1 Successfully Updated.";
        if($old_application_form_step == 1){
            $success_message = "Step 1 Successfully saved.";
        }
        return redirect()->back()
        ->with("success", $success_message);
    }
    public function stepTwoUpdate(Request $request, $encrypted_id)
    {
        $application_form = "address_information";
        $rules = $this->getRules($application_form);
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
            $application = Application::find($decrypted_id);
            if(!applicatinEditPermission($application)){
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step two permission denied. Application id {$application->id}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
            }
            if(!isEditAvailable($application)){            
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Option no longer available Application no {$application->id}, Status: {$application->status}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. Edit option not available.");
            }
            $message = "Editing Application No {$application->id} Step 2.";
            if($application->form_step == 1){
                $message = "Updating Application No {$application->id} Step 2.";
            }
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing or Inserting Application {$application->id} Step 2.");
        } catch (Exception $e) {
            Log::error($e);
            return redirect()
            ->to(url()->previous() . "#step-two-update")
            ->with("error", "Whoops! Lookes like you have missed something.");
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            Log::debug($validator->errors());
            return redirect()
            ->to(url()->previous() . "#step-two-update")
            ->withErrors($validator)->withInput($request->all());
        }
        DB::beginTransaction();
        try {
            $old_application = clone $application;
            // Correspondence Address
            $application->correspondence_village_town = $request->get("correspondence_vill_town");
            $application->correspondence_po = $request->get("correspondence_po");
            $application->correspondence_ps = $request->get("correspondence_ps");
            $application->correspondence_state = $request->get("correspondence_state");
            $application->correspondence_district = $request->get("correspondence_district");
            $application->correspondence_pin = $request->get("correspondence_pin");
            $application->correspondence_contact_number = $request->get("correspondence_contact");
            // permanent Address
            $application->permanent_village_town = $request->get("permanent_vill_town");
            $application->permanent_po = $request->get("permanent_po");
            $application->permanent_ps = $request->get("permanent_ps");
            $application->permanent_state = $request->get("permanent_state");
            $application->permanent_district = $request->get("permanent_district");
            $application->permanent_pin = $request->get("permanent_pin");
            $application->permanent_contact_number = $request->get("permanent_contact");
            $application->same_address = $request->get("same_address");
            if($application->form_step == 1){
                $application->form_step = 2;
            }
            $application->save();
        } catch (Exception $e) {
            DB::rollback();
            Log::emergency($e);
            return redirect()
                    ->to(url()->previous() . "#step-two-update")
                    ->with("error", "Whoops! Something went wrong. Please try again later.")
                    ->withInput($request->all());
        }
        DB::commit();
        $success_message = "Step 2 Successfully Updated.";
        $url = url()->previous()."#step-three-update";
        if($old_application->form_step == 1){
            $success_message = "Step 2 Successfully Saved.";
            $url = str_replace("#step-three-update", "", $url); 
        }
        return redirect()->to($url)
        ->with("success", $success_message);
    }
    public function stepThreeUpdate(Request $request, $encrypted_id)
    {
        $application_form = "academic_information";
        $rules = $this->getRules($application_form);
        // dd($rules);
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
            $application = Application::find($decrypted_id);
            if(!applicatinEditPermission($application)){
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step three permission denied. Application id {$application->id}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
            }
            if(!isEditAvailable($application)){            
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Option no longer available Application no {$application->id}, Status: {$application->status}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. Edit option not available.");
            }
            $message = "Editing Application No {$application->id} Step 3.";
            if($application->form_step == 2){
                $message = "Updating Application No {$application->id} Step 3.";
            }
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()
            ->to(url()->previous() . "#step-three-update")
            ->with("error", "Whoops! Lookes like you have mess something.");
        }
        if($this->checkAnmDataEntered($request, $rules) || $application->anm_or_lhv){
            $rules = $this->ConvertNullableToRequired($rules, $string = "anm");
        }
        if($this->checkVocationalDataEntered($request, $rules)){
            $rules = $this->ConvertNullableToRequired($rules, $string = "voc");
        }
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            Log::debug($validator->errors());
            return redirect()
            ->to(url()->previous() . "#step-three-update")
            ->withErrors($validator)->withInput($request->all());
        }
        DB::beginTransaction();
        $old_application_form_step = $application->form_step;
        try {
            // Academic Details
            // 10th class inforamtion
            $application->academic_10_stream = "NA";
            $application->academic_10_year = $request->get("academic_10_year");
            $application->academic_10_board = $request->get("academic_10_board");
            $application->academic_10_school = $request->get("academic_10_school");
            $application->academic_10_subject = $request->get("academic_10_subject");
            $application->academic_10_mark = $request->get("academic_10_mark");
            $application->academic_10_percentage = $request->get("academic_10_percentage");

            // 12th class inforamtion
            $application->academic_12_stream = $request->get("academic_12_stream");
            $application->academic_12_year = $request->get("academic_12_year");
            $application->academic_12_board = $request->get("academic_12_board");
            $application->academic_12_school = $request->get("academic_12_school");
            $application->academic_12_subject = $request->get("academic_12_subject");
            $application->academic_12_mark = $request->get("academic_12_mark");
            $application->academic_12_percentage = $request->get("academic_12_percentage");

            // Vocational class inforamtion
            $application->academic_voc_stream = $request->get("academic_voc_stream");
            $application->academic_voc_year = $request->get("academic_voc_year");
            $application->academic_voc_board = $request->get("academic_voc_board");
            $application->academic_voc_school = $request->get("academic_voc_school");
            $application->academic_voc_subject = $request->get("academic_voc_subject");
            $application->academic_voc_mark = $request->get("academic_voc_mark");
            $application->academic_voc_percentage = $request->get("academic_voc_percentage");

            // Vocational class inforamtion
            $application->academic_anm_stream = $request->get("academic_anm_stream");
            $application->academic_anm_year = $request->get("academic_anm_year");
            $application->academic_anm_board = $request->get("academic_anm_board");
            $application->academic_anm_school = $request->get("academic_anm_school");
            $application->academic_anm_subject = $request->get("academic_anm_subject");
            $application->academic_anm_mark = $request->get("academic_anm_mark");
            $application->academic_anm_percentage = $request->get("academic_anm_percentage");

            $application->other_qualification = $request->get("other_qualification");
            $application->english_mark_obtain = $request->get("english_mark_obtained");
            if($application->form_step == 2){
                $application->form_step = 3;
            }
            // dump($application->getChanges());
            // dd($application);
            $application->save();
        } catch (Exception $e) {
            // dd($e);
            DB::rollback();
            Log::emergency($e);
            return redirect()
                    ->to(url()->previous() . "#step-three-update")
                    ->with("error", "Whoops! Something went wrong. Please try again later.")
                    ->withInput($request->all());
        }
        DB::commit();
        $success_message = "Step 3 Successfully Updated.";
        $url = url()->previous() . "#step-four-update";
        if($old_application_form_step == 2){
            $success_message = "Step 3 process is completed.";
            $url = str_replace("#step-four-update", "", $url);
        }
        return redirect()->to($url)
        ->with("success", $success_message);
    }
    public function stepFinalUpdate(Request $request, $encrypted_id)
    {
        $application_form = "attachment_information";
        $rules = $this->getRules($application_form);
        // dump($request->all());
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
            $application = Application::with("attachments")->find($decrypted_id);
            if(!applicatinEditPermission($application)){
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step three permission denied. Application id {$application->id}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
            }
            if(!isEditAvailable($application)){            
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Edit Option no longer available Application no {$application->id}, Status: {$application->status}");
                return redirect()
                ->to(url()->previous() . "#step-four-update")
                ->with("error", "Access Denied. Edit option not available.");
            }
            $message = "Editing Application No {$application->id} Step 3.";
            if($application->form_step == 2){
                $message = "Updating Application No {$application->id} Step 3.";
            }
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()
            ->to(url()->previous() . "#step-four-update")
            ->with("error", "Whoops! Lookes like you have mess something.");
        }
        // check if anm academic details found update rules n add required field to attachment too
        /* if($this->checkAnmDataEntered($request, $rules)){
            $rules = $this->ConvertNullableToRequired($rules, $string = "anm");
        } */
        if($this->checkAnmDataEntered($request, $rules) || $application->anm_or_lhv){
            $rules = $this->ConvertNullableToRequired($rules, $string = "anm");
        }
        if($application->bpl){
            $rules["bpl_document"] = str_replace("nullable", "required", $rules["bpl_document"]);
        }
        if($application->person_with_disablity){
            $rules["disablity_certificate"] = str_replace("nullable", "required", $rules["disablity_certificate"]);
        }
        // check if files are already uploaded required is not compulsory
        $application->attachments->each(function($attachment, $key) use (&$rules){
            if($attachment->doc_name == "marksheet_12"){
                $rules["12_marksheet"] = str_replace("required", "nullable", $rules["12_marksheet"]);
            }
            if(isset($rules[$attachment->doc_name])){
                $rules[$attachment->doc_name] = str_replace("required", "nullable", $rules[$attachment->doc_name]);
            }
        });
        // dd($rules);
        // Validation
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            Log::debug($validator->errors());
            return redirect()
            ->to(url()->previous() . "#step-four-update")
            ->withErrors($validator)->withInput($request->all());
        }
        // dd($rules);
        DB::beginTransaction();
        $old_application_form_step = $application->form_step;
        try {
            $uploaded_docs = $this->storeDocs($request, $application);
            $attachment_data = [];
            $deleted_condition = [];
            if ($uploaded_docs) {
                foreach ($uploaded_docs as $index => $doc) {
                    $attachment_data[] = [
                        "application_id" => $application->id,
                        "doc_name" => $doc["doc_name"],
                        "file_name" => $doc["file_name"],
                        "original_name" => $doc["original_name"],
                        "mime_type" => $doc["mime_type"],
                        "destination_path" => $doc["destination_path"],
                        "created_at" => current_date_time(),
                        "updated_at" => current_date_time(),
                    ];
                    $deleted_condition[] = $doc["doc_name"];
                }
            }
            if($deleted_condition){
                $application->attachments()->whereIn("doc_name", $deleted_condition)->delete();
            }
            if ($attachment_data) {
                ApplicationAttachment::insert($attachment_data);
            }
            //Declaration Accepted
            $application->diclaration_accept = $request->get("accept");

            if($application->form_step == 3){
                $application->form_step = 4;
            }
            $application->save();
        } catch (Exception $e) {
            // dd($e);
            DB::rollback();
            Log::emergency($e);
            return redirect()
                    ->to(url()->previous() . "#step-four-update")
                    ->with("error", "Whoops! Something went wrong. Please try again later.")
                    ->withInput($request->all());
        }
        DB::commit();
        $success_message = "Step 4 Successfully Updated.";
        if($old_application_form_step == 3){
            $success_message = "Step 4 Successfully saved. Please review and accept for payment process.";
        }
        return redirect()->route(get_guard().".application.index")
        ->with("success", $success_message);
    }
    public function finalSubmit(Request $request, $encrypted_id) {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
            $application = Application::with("attachments")->find($decrypted_id);
            if(!applicatinEditPermission($application)){
                saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), "Editing Application Step three permission denied. Application id {$application->id}");
                return redirect()->route(get_guard().".home")->with("error", "Access Denied. You don't have the permission to edit other application.");
            }
            if($application->form_step == 4 && $application->status == "application_submitted"){
            }else{
                return redirect()->route(get_guard().".home")->with("error", "Final Submit option not available at this time.");
            }
            $message = "Final Submission Application No {$application->id}.";
            
        } catch (Exception $e) {
            // dd($e);
            Log::error($e);
            return redirect()->back()->with("error", "Whoops! Lookes like you have mess something.");
        }
        // Validation check
        // Caste uploaded (non General)
        $errors = [];
        if((strtolower($application->caste->name) != "general" || ($application->sub_cat != "NA" && $application->sub_cat != "")) ){
            if(!$application->attachments->where("doc_name", "caste_certificate")->count()){
                $errors[] = "Caste or EWS/NCL Certificate Required For {$application->caste->name}";
            }
        }
        // Disablity Certificate
        if($application->person_with_disablity){
            if(!$application->attachments->where("doc_name", "disablity_certificate")->count()){
                $errors[] = "Disability Certificate Required.";
            }
        }
        // Step one check anm is filled
        $hs_marks_percentn = Application::$hs_marks_percent; //percent
        $anm_marks_percent = Application::$anm_marks_percent;
        $marks_percent_relaxation = Application::$marks_percent_relaxation; //percent incase of ST/SC

        if(checkAnmDataEntered($application) || $application->anm_or_lhv){
            // if anm filled check anm marks
            if(!$application->attachments->where("doc_name", "anm_registration")->count()){
                $errors[] = "ANM Registration Certificate is required.";
            }
            if(!$application->attachments->where("doc_name", "anm_marksheet")->count()){
                $errors[] = "ANM Marksheet Certificate is required.";
            }
            if($application->academic_anm_percentage < $anm_marks_percent){
                $errors[] = "Required Minimum ANM Academic Percentage is {$anm_marks_percent}.";
            }
        }else{
            // incase of caste sc/st add relaxation n compare
            // marks checking
            if(strtolower(trim($application->caste->name)) == "sc" || strtolower(trim($application->caste->name)) == "st"){
                if($application->academic_12_percentage < ($anm_marks_percent - $marks_percent_relaxation)){
                    $errors[] = "Required Minimum HS Academic Percentage is ".($anm_marks_percent - $marks_percent_relaxation)." with {$application->caste->name} Relaxation .";
                }
            }else{
                if($application->academic_12_percentage < $anm_marks_percent){
                    $errors[] = "Required Minimum HS Academic Percentage is {$anm_marks_percent}.";
                }
            }
        }
        if($errors){
            $error_string = implode("<br>", $errors);
            return redirect()->back()->withError($errors)->with("error","<strong>Please fullfil the required critaria to final submit</strong>.<br>".$error_string);
            dd($errors);
        }
        // 
        DB::beginTransaction();
        try {
            $application->status = "payment_pending";
            $application->save();
            $message = "Final Review completed Application No : {$application->id}";
            saveLogs(auth(get_guard())->id(), auth(get_guard())->user()->name, get_guard(), $message);
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with("error", "Something went wrong.");
        }
        DB::commit();
        return redirect()->back()->with("success", "Final review completed. Payment option is now available.");
    }
    public function getIndexView()
    {
        $this->guard = get_guard();
        if ($this->guard == "admin") {
            return "admin.applications.index";
        } elseif ($this->guard == "student") {
            return "student.application.index";
        } else {
            return null;
        }

    }
    public function getApplicationView()
    {
        $this->guard = get_guard();
        if ($this->guard == "admin") {
            return "admin.applications.show";
        } elseif ($this->guard == "student") {
            return "student.application.show";
        } else {
            return null;
        }

    }
    public function getApplicationEditView()
    {
        $this->guard = get_guard();
        if ($this->guard == "admin") {
            return "admin.applications.edit";
        } elseif ($this->guard == "student") {
            return "student.application.edit";
        } else {
            return null;
        }
    }
    public function getRules($index)
    {
        if (isset(Application::$rules[$index])) {
            return Application::$rules[$index];
        }
        throw new \Exception("Validation Rules Not Found.", 1);
    }
    public function checkAnmDataEntered($request) {
        if($request->get("academic_anm_stream")){
            return true;
        }elseif($request->get("academic_anm_year")){
            return true;
        }elseif($request->get("academic_anm_board")){
            return true;
        }elseif($request->get("academic_anm_school")){
            return true;
        }elseif($request->get("academic_anm_subject")){
            return true;
        }elseif($request->get("academic_anm_mark")){
            return true;
        }elseif($request->get("academic_anm_percentage")){
            return true;
        }
        return false;
    }
    public function checkVocationalDataEntered($request) {
        if($request->get("academic_voc_stream")){
            return true;
        }elseif($request->get("academic_voc_year")){
            return true;
        }elseif($request->get("academic_voc_board")){
            return true;
        }elseif($request->get("academic_voc_school")){
            return true;
        }elseif($request->get("academic_voc_subject")){
            return true;
        }elseif($request->get("academic_voc_mark")){
            return true;
        }elseif($request->get("academic_voc_percentage")){
            return true;
        }
        return false;
    }
    public function ConvertNullableToRequired($rules, $search_string) {
        foreach($rules as $field_name => $validation_rule){
            if(stripos($field_name, $search_string) !== FALSE){
                // dump($field_name);
                // dump($validation_rule);
                $rules[$field_name] = str_replace("nullable", "required", $validation_rule);
            }
        }
        return $rules;
    }
    public function checkBirthDayLowerLimit($request)
    {
        return false;
        $lowerLimit = Application::$applicant_lower_age_limit;
        $extended_dob = strtotime($request->dob . "+ {$lowerLimit} years");
        dump(date("d-m-Y", $extended_dob));
        $limit_date = strtotime(Application::$dob_compare_to);
        if ($extended_dob < $limit_date) {
            return true;
        }
        return false;
    }
}
