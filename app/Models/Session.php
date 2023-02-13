<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Session extends Model
{
    use SoftDeletes;
    protected $guaraded = ["id"];
    
    public function admissionDateTime() {
        return $this->hasOne("\App\Models\AdmissionDateTime", "session_id", "id");
    }
}
