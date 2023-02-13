<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class AuthAuthenticated
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user_id    = $event->user->id;
        $username   = ($event->user->getTable() == "users" ?  $event->user->mobile_no: $event->user->username);
        $guard      = ($event->user->getTable() == "users" ? "student" : "admin");
        $activity   = "Login Success ".json_encode($event->user->name);
        saveLogs($user_id, $username, $guard, $activity, $save_to_log = false);
        Log::emergency("Login Success. ".$event->user->getTable());
    }
}
