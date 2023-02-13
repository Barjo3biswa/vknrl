<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExamCenter;
use Validator, Log, Exception, Crypt;

class ExamCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exam_centers = ExamCenter::paginate(100);
        return view("admin.exam_center.index", compact("exam_centers"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.exam_center.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = ExamCenter::$rules;
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            Log::debug($validator->errors());
            return redirect()
                    ->back()
                    ->withInput($request->all())
                    ->withErrors($validator)
                    ->with("error", "Whoops! Looks like you have missed something.");
        }
        try {
            ExamCenter::create([
                "center_name"   => $request->get("center_name"),
                "center_code"   => $request->get("center_code"),
                "address"       => $request->get("address"),
                "city"          => $request->get("city"),
                "state"         => $request->get("state"),
                "pin"           => $request->get("pin"),
                "created_by"    => auth("admin")->id()
            ]);
        } catch (Exception $e) {
            dd($e);
            Log::error($e);
            return redirect()
                ->back()
                ->with("error", "Whoops! Something went wrong. Exam Center Creation Failed.");

        }
        return redirect()
                ->back()
                ->with("success", "Exam Center Successfully Created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return "Show";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->route("admin.exam-center.index")
                    ->with("error", "Whoops! Something went wrong please try again later.");
        }
        try {
            $exam_center = ExamCenter::findOrFail($decrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()
                ->route("admin.exam-center.index")
                ->with("error", "Whoops! Something went wrong please try again later.");
        }
        return view("admin.exam_center.edit", compact("exam_center"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()
                ->back()
                ->with("error", "Whoops! Something went wrong please try again later.");
        }
        try {
            $exam_center = ExamCenter::findOrFail($decrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()
                ->back()
                ->with("error", "Whoops! Something went wrong please try again later.");
        }
        $rules = ExamCenter::$rules;
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors($validator->errors());
        }
        $exam_center->center_code = $request->get("center_code");
        $exam_center->center_name = $request->get("center_name");
        $exam_center->address     = $request->get("address");
        $exam_center->city        = $request->get("city");
        $exam_center->state       = $request->get("state");
        $exam_center->pin         = $request->get("pin");
        $exam_center->save();
        saveLogs(auth("admin")->id(), auth("admin")->user()->username, get_guard(),"Exam Center Updated.".$exam_center->id);
        return redirect()->route("admin.exam-center.index")->with("success", "Exam Center Successfully Updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $encrypted_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($encrypted_id)
    {
        try {
            $decrypted_id = Crypt::decrypt($encrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()
                ->back()
                ->with("error", "Whoops! Something went wrong please try again later.");
        }
        try {
            $exam_center = ExamCenter::findOrFail($decrypted_id);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()
                ->back()
                ->with("error", "Whoops! Something went wrong please try again later.");
        }
        $exam_center->deleted_by = auth(get_guard())->id();
        $exam_center->deleted_at = date("Y-m-d H:i:s");
        $exam_center->save();
        saveLogs(auth("admin")->id(),auth("admin")->user()->username, get_guard(),"Exam Center Deleted. ID ".$exam_center->id);
        return redirect()->back()->with("success", "Exam Center Successfully Deleted.");
        // return "destroy";
    }
}
