<?php

Route::get('/home', function () {
    // $users[] = Auth::user();
    // $users[] = Auth::guard()->user();
    // $users[] = Auth::guard('admin')->user();

    //dd($users);

    return view('admin.home');
})->name('home');
// Route::group(["prefix" => "application", "as" => "application."],function(){
    // Route::get("/", [
    //     "uses" => "Admin\ApplicationController@index", 
    //     "as" => "index"
    // ]);
    Route::post("applicants/change_pass", ["uses" => "Admin\AdminController@changePass", "as" =>"applicants.changepass"]);
    Route::resource("application", "Admin\ApplicationController", ['except' => ["update"]]);
    // });
    Route::resource("notification", "Admin\WebsiteNotificationController", ['except' => []]);
    Route::get("applicants/list", ["uses" => "Admin\AdminController@applicantList", "as" =>"applicants.list"]);
    Route::group(["prefix" => "application", "as" => "application."], function(){
        Route::put("/step_one_form/{application_id}", ["uses" =>"Admin\ApplicationController@stepOneUpdate", "as" => "step_one_form"]);
        Route::put("/step_two_form/{application_id}", ["uses" =>"Admin\ApplicationController@stepTwoUpdate", "as" => "step_two_form"]);
        Route::put("/step_three_form/{application_id}", ["uses" =>"Admin\ApplicationController@stepThreeUpdate", "as" => "step_three_form"]);
        Route::put("/step_final_form/{application_id}", ["uses" =>"Admin\ApplicationController@stepFinalUpdate", "as" => "step_final_form"]);
        Route::get("/payment-receipt/{application_id}", ["uses" => "Admin\ApplicationController@paymentReceipt", "as" => "payment-reciept"]);
        Route::put("/accept/{application_id}", ["uses" =>"Admin\ApplicationController@acceptApplication", "as" => "accept"]);
        Route::put("/reject/{application_id}", ["uses" =>"Admin\ApplicationController@rejectApplication", "as" => "recept"]);
        Route::put("/hold/{application_id}", ["uses" =>"Admin\ApplicationController@holdApplication", "as" => "hold"]);
        Route::group(['prefix' => 'upload'], function () {
            Route::get("qualified_student", ["uses" => "Admin\ApplicationController@qualifiedStudentImport", "as" => "upload.student.qualified"]);
            Route::post("qualified_student", ["uses" => "Admin\ApplicationController@qualifiedStudentImportPost", "as" => "upload.student.qualified.post"]);
        });
        Route::group(['prefix' => 'sms'], function () {
            Route::post("send", ["uses" => "Admin\ApplicationController@sendSMS", "as" => "sms.send"]);
        });
    });

    
    Route::get("conference/list", ["uses" => "Admin\AdminController@conferenceList", "as" =>"conference.list"]);



Route::resource("exam-center", "Admin\ExamCenterController", ["except" => ["show"]]);
Route::resource("admit-card", "Admin\AdmitCardController",["except" => ["destroy", "edit"]]);
Route::group(["prefix" => "admit-card"], function(){
    Route::get("/download/{admit_id}", ["uses" => "Admin\AdmitCardController@downloadPdfAdmin", "as" => "admit-card.download.pdf"]);
    Route::post("/publish", ["uses" => "Admin\AdmitCardController@publishAdmit", "as" => "admit-card.publish-all"]);
});
Route::group(['prefix' => 'logs'], function () {
    Route::get("/",["uses" => "Admin\LogController@index", "as" => "log.index"]);
});
