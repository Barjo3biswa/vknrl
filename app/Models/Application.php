<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\ApplicationEdited;

class Application extends Model
{
    use SoftDeletes;
    protected $guarded = ["id"];
    public static $applicant_lower_age_limit = 17;
    public static $applicant_upper_age_limit = 35;
    public static $dob_compare_to = "2022-12-31";
    // marks related
    public static $hs_marks_percent = 40; //percent
    public static $anm_marks_percent = 40; //percent
    public static $marks_percent_relaxation = 5; //percent incase of ST/SC

    public static $statuses_for_admin = ["payment_done", "rejected", "on_hold", "accepted", "qualified"];
    public function caste()
    {
        return $this->belongsTo("App\Models\Caste", "caste_id", "id");
    }
    public function session()
    {
        return $this->belongsTo("App\Models\Session", "session_id", "id");
    }
    public function student()
    {
        return $this->belongsTo("App\Models\User", "student_id", "id");
    }
    public function attachments()
    {
        return $this->hasMany("App\Models\ApplicationAttachment", "application_id", "id");
    }
    public function attachment_others()
    {
        return $this->attachments->whereNotIn("doc_name", ["passport_photo", "signature"]);
        return $this->hasMany("App\Models\ApplicationAttachment", "application_id", "id");
    }
    public function passport_photo()
    {
        return $this->attachments->where("doc_name", "passport_photo")->first();
        return $this->hasOne("App\Models\ApplicationAttachment", "application_id", "id")->where("doc_name", "passport_photo");
    }
    public function signature()
    {
        return $this->attachments->where("doc_name", "signature")->first();
        return $this->hasOne("App\Models\ApplicationAttachment", "application_id", "id")->where("doc_name", "signature");
    }
    public function remarks()
    {
        return $this->hasMany("App\Models\ApplicationRemark", "application_id", "id");
    }
    public function online_payment_tried()
    {
        return $this->hasMany("App\Models\OnlinePaymentProcessing", "application_id", "id")->orderBy("id", "ASC");
    }
    public function online_payments_succeed()
    {
        return $this->hasMany("App\Models\OnlinePaymentSuccess", "application_id", "id")->orderBy("id", "ASC");
    }
    public function paymentReceipt()
    {
        return $this->hasOne("App\Models\OnlinePaymentSuccess", "application_id", "id")->where("status", 1)->orderBy("id", "ASC");
    }
    public function auditTrail()
    {
        return $this->hasMany("App\Models\ApplicationEdited", "application_id", "id");
    }
    public function admit_card()
    {
        return $this->hasOne("App\Models\AdmitCard", "application_id", "id");
    }
    public function admit_card_published()
    {
        return $this->hasOne("App\Models\AdmitCard", "application_id", "id")->where("publish", 1);
    }
    public function admit_card_draft()
    {
        return $this->hasOne("App\Models\AdmitCard", "application_id", "id")->where("publish", 0);
    }
    public static $rules = [
        "personal_information" => [
            "fullname" => "required|max:255",
            "gender" => "required|in:Female,Testing_gender",
            "father_name" => "required|max:255",
            "father_occupation" => "required|max:255",

            "mother_name" => "required|max:255",
            "mother_occupation" => "required|max:255",
            "maritial_status" => "required|in:Married,Unmarried",
            "religion" => "required|max:255",
            "nationality" => "required|max:255|in:Indian,India,INDIAN",
            "dob" => "required|date_format:d-m-Y",
            "disablity" => "required",
            "caste" => "required|exists:castes,id",
            "anm_or_lhv" => "required|numeric|in:1,0",
            "anm_or_lhv_registration" => "nullable|max:100",
            // "bpl"   => "required"
        ],
        "address_information" => [
            // address
            "correspondence_vill_town" => "required|max:255",
            "correspondence_po" => "required|max:255",
            "correspondence_ps" => "required|max:255",
            "correspondence_pin" => "required|digits:6",
            "correspondence_state" => "required|max:255",
            "correspondence_district" => "required|max:255",
            "correspondence_contact" => "required|digits:10",
            "same_address" => "numeric",
            "permanent_vill_town" => "required|max:255",
            "permanent_po" => "required|max:255",
            "permanent_ps" => "required|max:255",
            "permanent_pin" => "required|digits:6",
            "permanent_state" => "required|max:255",
            "permanent_district" => "required|max:255",
            "permanent_contact" => "required|digits:10",
        ],
        "academic_information" => [
            // academic
            "academic_10_stream" => "required|max:255",
            "academic_10_year" => "required|numeric",
            "academic_10_board" => "required|max:255",
            "academic_10_school" => "required|max:255",
            "academic_10_subject" => "required|max:255",
            "academic_10_mark" => "required|numeric|max:1000|min:0",
            "academic_10_percentage" => "required|numeric|max:100|min:30",

            "academic_12_stream" => "required|max:255",
            "academic_12_year" => "required|numeric",
            "academic_12_board" => "required|max:255",
            "academic_12_school" => "required|max:255",
            "academic_12_subject" => "required|max:255",
            "academic_12_mark" => "required|numeric|max:1000|min:0",
            "academic_12_percentage" => "required|numeric|max:100|min:30",

            "academic_voc_stream" => "nullable|max:255",
            "academic_voc_year" => "nullable|numeric",
            "academic_voc_board" => "nullable|max:255",
            "academic_voc_school" => "nullable|max:255",
            "academic_voc_subject" => "nullable|max:255",
            "academic_voc_mark" => "nullable|numeric|max:1000|min:0",
            "academic_voc_percentage" => "nullable|numeric|max:100|min:30",

            "academic_anm_stream" => "nullable|max:255",
            "academic_anm_year" => "nullable|numeric",
            "academic_anm_board" => "nullable|max:255",
            "academic_anm_school" => "nullable|max:255",
            "academic_anm_subject" => "nullable|max:255",
            "academic_anm_mark" => "nullable|numeric|max:1000|min:0",
            "academic_anm_percentage" => "nullable|numeric|max:100|min:30",

            "other_qualification" => "required|max:255",
            "english_mark_obtained" => "required|numeric|max:100|min:40",
        ],
        "attachment_information"    => [            
            // files
            'passport_photo'              => "image|required|mimes:jpeg,jpg,png|max:100|dimensions:max_width=200,max_height=250",
            'signature'                  => "image|required|mimes:jpeg,jpg,png|max:100|dimensions:max_width=200,max_height=150",
            "prc_certificate" => "verify_corrupted|file|required|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "caste_certificate" => "nullable|verify_corrupted|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "age_proof_certificate" => "verify_corrupted|required|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "12_marksheet" => "verify_corrupted|required|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "disablity_certificate" => "verify_corrupted|nullable|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "document_mentioning_name_of_the_school_class_10" => "verify_corrupted|required|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "anm_registration" => "verify_corrupted|nullable|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "anm_marksheet" => "verify_corrupted|nullable|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
            "bpl_document" => "verify_corrupted|nullable|mimetypes:application/pdf,image/jpeg,image/png|max:1024",
        ]

    ];
    protected $dispatchesEvents = [
        'saving' => ApplicationEdited::class
    ];
    // "application/pdf|image/jpeg|image/png"
}
