<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OtpVerify extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->guard("student")->user()->is_veried){
            return false;
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "otp" => "required|numeric"
        ];
    }
}
