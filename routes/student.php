<?php

Route::get("otp-verify", function(){
    return view("student.otp-verify");
})->name("otp-verify")->middleware("otp.guest");

Route::post("otp-verify", ["uses" => "HomeController@verifyOTP"])->name("otp-verify")->middleware("otp.guest");
Route::get("otp-resend", ["uses" => "HomeController@resendOTP"])->name("otp-resend");
Route::group(["middleware" => "otp"], function(){
    Route::get('/dashboard', 'HomeController@index')->name('home');
    // Application Route
    Route::resource("application", "Student\ApplicationController", ['except' => ["update"]]);
    // inserting OR Updating Application
    Route::group(["prefix" => "application"], function(){
        Route::put("/step_one_form/{application_id}", ["uses" =>"Student\ApplicationController@stepOneUpdate", "as" => "application.step_one_form"]);
        Route::put("/step_two_form/{application_id}", ["uses" =>"Student\ApplicationController@stepTwoUpdate", "as" => "application.step_two_form"]);
        Route::put("/step_three_form/{application_id}", ["uses" =>"Student\ApplicationController@stepThreeUpdate", "as" => "application.step_three_form"]);
        Route::put("/step_final_form/{application_id}", ["uses" =>"Student\ApplicationController@stepFinalUpdate", "as" => "application.step_final_form"]);
        Route::get("/process-payment/{application_id}", ["uses" => "Student\ApplicationController@processPayment", "as" => "application.process-payment"]);
        Route::post("/process-payment/{application_id}", ["uses" => "Student\ApplicationController@paymentRecieved", "as" => "application.process-payment-post"]);
        Route::get("/payment-receipt/{application_id}", ["uses" => "Student\ApplicationController@paymentReceipt", "as" => "application.payment-reciept"]);
        Route::get("/final-submit/{application_id}", ["uses" => "Student\ApplicationController@finalSubmit", "as" => "application.final-submit"]);
        Route::group(['prefix' => 'admit-card'], function () {
            Route::get('download/{application_id}', ["uses" => "Student\ApplicationController@downloadAdmitCard", "as" => "admit-card.download"]);
        });

        Route::post("/conferense-save", ["uses" => "Conference\ConferenceController@save", "as" => "application.conferense.save"]);
        
        Route::get("/conferense-payment/{id}", ["uses" => "Conference\ConferenceController@payment", "as" => "application.conferense.payment"]);
        Route::post("/conferense-payment-post/{id}", ["uses" => "Conference\ConferenceController@paymentPost", "as" => "application.conferense.payment-post"]);

        // Route::post("/payment-complete", ["uses" => "HomeController@index", "as" => "application.payment-complete"]);

    });
});



