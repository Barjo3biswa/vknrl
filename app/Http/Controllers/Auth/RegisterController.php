<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use Session;
use Illuminate\Validation\Rules\Unique;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'student/otp-verify';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest', "admissionController"]);
    }
    
    public function showRegistrationForm(Request $request)
    {
        $who=$request->who;
        return view('student.auth.register',compact('who'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:users',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                (new Unique('users', 'email'))->where(function ($query) {
                    return $query->where('category', 'student');
                }),
            ],
            // 'mobile_no' => 'required|digits:10|unique:users,mobile_no',
            'mobile_no' => [
                'required',
                'digits:10',
                (new Unique('users', 'mobile_no'))->where(function ($query) {
                    return $query->where('category', 'student');
                }),
            ],
            'password' => 'required|string|min:8|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%@]).*$/',
            'captcha' => 'required|captcha',
        ],[
            "regex" => "The :attribute must be at least 8 characters long, contain at least one number, one special character and have a mixture of uppercase and lowercase letters."
        ]);
    }

    protected function validatorConf(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:users',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                (new Unique('users', 'email'))->where(function ($query) {
                    return $query->where('category', 'conference');
                }),
            ],
            // 'mobile_no' => 'required|digits:10|unique:users,mobile_no',
            'mobile_no' => [
                'required',
                'digits:10',
                (new Unique('users', 'mobile_no'))->where(function ($query) {
                    return $query->where('category', 'conference');
                }),
            ],
            
            'password' => 'required|string|min:8|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%@]).*$/',
            'captcha' => 'required|captcha',
        ],[
            "regex" => "The :attribute must be at least 8 characters long, contain at least one number, one special character and have a mixture of uppercase and lowercase letters."
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $cate = Crypt::decrypt($data['who']);
        if($cate=='conf'){
            $category='conference';
        }else{
            $category='student';
        }
       
        $session=\DB::table('sessions')->where('is_active',1)->first();
        // dd($session);
        if (User::count() == 0) {
            \DB::statement('ALTER TABLE users AUTO_INCREMENT = 1000;');
        }
        $otp = mt_rand(123452,998877);
        // $message = "VKNRL Registration OTP is {$otp}.";
        // $message = "Dear Applicant, {$otp} is the OTP for Registration to apply at VKNRL School of Nursing";
        $tempid='1207161519784471974';
        $message = $otp . " is your OTP. VKNRL";
        sendSMS($data['mobile_no'], $message,$tempid);
        // dd([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'mobile_no' => $data['mobile_no'],
        //     'password' => bcrypt($data['password']),
        //     'otp'    => $otp,
        //     'session_id' => $session->id,
        //     'category'   => $category, 
        // ]);
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile_no' => $data['mobile_no'],
            'password' => bcrypt($data['password']),
            'otp'    => $otp,
            'session_id' => $session->id,
            'category'   => $category,
        ]);
    }
    

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            $decrypted = Crypt::decrypt($request->who);
        } catch (\Exception $e) {
           return redirect()->back()->with('error','Something went wrong'); 
        }
        $password                = base64_decode($request->password);
        $password_confirmation   = base64_decode($request->password_confirmation);
        $passphrase = (Session::has("admin_login_crypt") ? Session::get("admin_login_crypt") : null);
        $password = cryptoJsAesDecrypt($passphrase, $password);
        $password_confirmation = cryptoJsAesDecrypt($passphrase, $password_confirmation);
        $request->merge(["password" => $password]);
        $request->merge(["password_confirmation" => $password_confirmation]);
        if($decrypted=='conf'){
            $this->validatorConf($request->all())->validate();
        }else{
            $this->validator($request->all())->validate();
        }
        

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath())->with("status", "OTP is sent to your mobile no. Please verify to proceed.");
    }
}
