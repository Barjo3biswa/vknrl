<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    protected $guarded = ["id"];
    public function user() {
        if($this->guard == "admin"){
            return $this->belongsTo("App\Admin", "user_id", "id");
        }
        return $this->belongsTo("App\Models\User", "user_id", "id");
    }
}
