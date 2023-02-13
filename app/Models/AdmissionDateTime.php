<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionDateTime extends Model
{
    use SoftDeletes;
    protected $guarded = ["id"];
    public function session() {
        return $this->belongsTo("\App\Models\Session", "session_id", "id");
    }
}
