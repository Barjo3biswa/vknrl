<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlinePaymentSuccess extends Model
{
    use SoftDeletes;
    protected $guarded =["id"];
    public function tried_process() {
        return $this->hasMany("App\Models\OnlinePaymentProcessing", "order_id", "order_id");
    }
}
