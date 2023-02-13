<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DailyLog;

class LogController extends Controller
{
    public function index(Request $request) {
        $limit = 500;
        $logs = DailyLog::orderBy("created_at", "DESC");
        if($request->get("user_type")){
            $logs = $logs->where("guard", $request->get("user_type"));
        }
        if($request->get("ip")){
            $logs = $logs->where("ip", $request->get("ip"));
        }
        if($request->get("application_id")){
            $logs = $logs->where("activity", "LIKE", "%".$request->get("application_id")."%");
        }
        if($request->get("registration_no")){
            $logs = $logs->whereHas("user", function($query) use ($request){
                return $query->where("id",$request->get('registration_no'));
            });
        }
        if($request->get("applicant_name")){
            $logs = $logs->where("username", "LIKE", "%".$request->get("applicant_name")."%");
        }
        if($request->get("activity")){
            $logs = $logs->where("activity", "LIKE", "%".$request->get("activity")."%");
        }
        if($request->get("limit")){
            $limit = $request->get("limit");
        }
        $request->merge(["limit" => $limit]);
        $logs = $logs->paginate($limit);
        return view("admin.logs.index", compact('logs'));
    }
}
