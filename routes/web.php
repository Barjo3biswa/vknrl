<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin', "as" => "admin."], function () {
    Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'AdminAuth\LoginController@login');
    Route::post('/logout', 'AdminAuth\LoginController@logout')->name('logout');

    Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'AdminAuth\RegisterController@register');

    Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request')->middleware("throttle:3,1");
    Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.email');
    Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});
Route::group(["prefix" => "student", "as" => "student.", "middleware" => "web"], function () {
    Auth::routes();
    $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email')->middleware("throttle:2,1");
});
Route::get("/application/attachment/{student_id}/{application_id}/{url}", [
    "uses" => "Common\ApplicationAttachmentController@show",
    "as" => "common.download.image"
]);
/* Route::get("/pass_cg", function(){
    return bcrypt("Rajabari@123");
}); */

/* 
Route::get("/send_sms_incomplete_application", function(){
    return "closed";
    $message = "Your status of application for admission into GNM course session 2019-20 in VKNRL School of Nursing is incomplete. Kindly complete it by 31/07/2019.";
    $mobile_nos = \App\Models\User::whereHas("application",function($query){
        return $query->where("payment_status", 0)->whereDate("updated_at", "<=", date("Y-m-d", strtotime(date("Y-m-d")." -1 days")));
    });
    $user_details = [];
    $records = $mobile_nos->get();
    if($records){
        foreach($records as $record){

            $user_details[] = [
                "name"       => $record->name,
                "mobile_no"  => $record->mobile_no,
                "registration no"  => $record->id,
                "application_id"  => $record->application->first()->id,
                "message"   => $message
            ];
        }
    }
    $mobile_nos     = $mobile_nos->pluck("mobile_no")->toArray();
    \Log::notice(json_encode($user_details));
    if($mobile_nos){
        foreach($mobile_nos as $mobile_no){
            sendSMS($mobile_no, $message);
        }
    }
    return "Done";
}); */