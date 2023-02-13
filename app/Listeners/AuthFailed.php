<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use function GuzzleHttp\json_encode;

class AuthFailed
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
        if($event->user){
            $guard      = ($event->user->getTable() == "users" ? "student" : "admin");
            $activity   = "Login Failed ";
            $user_id    = $event->user->id;
            $username   = $event->user->name;
            saveLogs($user_id, $username, $guard, $activity, $save_to_log = false);
            Log::emergency("Login Failed. ".$event->user->getTable());
        }
        Log::emergency("Login Failed User.");
        Log::debug($event->credentials);
    }
}
