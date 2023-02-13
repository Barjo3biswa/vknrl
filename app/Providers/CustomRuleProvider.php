<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class CustomRuleProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('verify_corrupted', function ($attribute, $value, $parameters, $validator) {
            $mime_type = $value->getMimeType();
            if(stripos($mime_type, "pdf") !== false){
                $file = file($value);
                $endfile= trim($file[count($file) - 1]);
                $n="%%EOF";
                if ($endfile === $n) {
                    return true;
                } else {
                    return false;
                }
            }elseif(stripos($mime_type, "jpg")){

            }
            return true;
        });
        Validator::replacer('verify_corrupted', function($message, $attribute, $rule, $parameters) {
            return str_replace($message, "Uploaded ".str_replace("_"," ",$attribute)." is not a valid file.", $message);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
