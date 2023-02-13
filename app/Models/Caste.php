<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caste extends Model
{
    use SoftDeletes;
    protected $guarded = ["id"];
    public function applications() {
        return $this->hasMany("App\Models\Application", "application_id", "id");
    }
}
