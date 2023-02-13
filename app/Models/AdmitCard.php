<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmitCard extends Model
{
    use SoftDeletes;
    public static $rules = [
        "publish"       => "required|in:0,1",
        "exam_center"   => "required|exists:exam_centers,id",
        "date"          => "required|date",
        "time"          => "required",
        "applications"  => "required|array|min:1",
    ];
    public function application() {
        return $this->belongsTo("\App\Models\Application", "application_id", "id")->withTrashed();
    }
    public function exam_center() {
        return $this->belongsTo("\App\Models\ExamCenter", "exam_center_id", "id")->withTrashed();
    }
}
