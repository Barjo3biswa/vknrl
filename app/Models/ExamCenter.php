<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamCenter extends Model
{
    use SoftDeletes;
    protected $guarded = ["id"];
    public static $rules = [
        "center_code"   => "max:255",
        "center_name"   => "required|max:255",
        "address"       => "required|max:255",
        "city"          => "required|max:100",
        "state"         => "required|max:100",
        "pin"           => "required|digits:6",
    ];
}
