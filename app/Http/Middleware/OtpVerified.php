<?php

namespace App\Http\Middleware;

use Closure, Auth;

class OtpVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $guard = Auth::guard("student");
        if($guard->check()){
            if(!$guard->user()->otp_verified){
                return redirect(route("student.otp-verify"))->with("status", "Please verify OTP to Proceed.");
            }
        }
        return $next($request);
    }
}
