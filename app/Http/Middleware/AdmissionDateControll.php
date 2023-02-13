<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AdmissionDateControll
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
        $ips = ["103.138.154.181"];
        if(in_array($request->ip(), $ips)){
            return $next($request);
        }
        $active_session = getActiveSession();
        if($active_session){
            // dump($active_session->admissionDateTime);
            $time_details = $active_session->admissionDateTime;
            if(!$time_details){
                // handle date time not found here
                return $next($request);
            }
            $message = "Admission process is not started or closed.";
            if(strtotime($time_details->opening_date_time) < time() && strtotime($time_details->closing_date_time) > time()){
                return $next($request);
            }elseif(strtotime($time_details->opening_date_time) >= time()){
                // handle request before admission date started
                $message = "Application submission not yet started.";
                if($time_details->opening_message){
                    // search {{opening_date}} && {{closing_date}}
                    $opening_date_time = dateFormat($time_details->opening_date_time, "d-m-Y h:i:a");
                    $closing_date_time = dateFormat($time_details->closing_date_time, "d-m-Y h:i:a");
                    $message = str_replace("{{opening_date}}", $opening_date_time,$time_details->opening_message);
                    $message = str_replace("{{closing_date}}", $closing_date_time, $message );
                }
            }elseif(strtotime($time_details->closing_date_time) <= time()){
                // handle request after admission date expired.
                $message = "Application submission process is closed.";
                if($time_details->closing_message){
                    $opening_date_time = dateFormat($time_details->opening_date_time, "d-m-Y h:i:a");
                    $closing_date_time = dateFormat($time_details->closing_date_time, "d-m-Y h:i:a");
                    $message = str_replace("{{opening_date}}", $opening_date_time, $time_details->closing_message);
                    $message = str_replace("{{closing_date}}", $closing_date_time, $message);
                }
            }
            // return Response::make("Something error", 503);
            abort(503, $message);
            
        }else{
            // if session not active
            abort(503,"Admission not yet started.");
        }
        return $next($request);
    }
}
