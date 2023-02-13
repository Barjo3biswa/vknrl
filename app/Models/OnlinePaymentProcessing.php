<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlinePaymentProcessing extends Model
{
    use SoftDeletes;
    protected $guarded =["id"];
    public function succed_payments() {
        return $this->hasMany("App\Models\OnlinePaymentSuccess", "order_id", "order_id");
    }
}
