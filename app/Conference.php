<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conference extends Model
{
    use SoftDeletes;
    protected $guarded=['id'];

    public function online_payment_tried()
    {
        return $this->hasMany("App\Models\OnlinePaymentProcessing", "application_id", "id")->orderBy("id", "ASC");
    }

    public function student()
    {
        return $this->belongsTo("App\Models\User", "user_id", "id");
    }
    public function paymentReceipt()
    {
        return $this->hasOne("App\Models\OnlinePaymentSuccess", "application_id", "id")->where("status", 1)->orderBy("id", "ASC");
    }
}
