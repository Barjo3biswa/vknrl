<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\StudentResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', "mobile_no",'otp_verified', "otp_verified_at", "otp","session_id"
    ];
    public static $otp_retry_limit = 3;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','otp'
    ];
    
    public function sendPasswordResetNotification($token) {
        $this->notify(new StudentResetPassword($token));
    }
    public function application() {
        return $this->hasMany("App\Models\Application", "student_id", "id");
    }
}
