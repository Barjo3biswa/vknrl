<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class AuthLoggedOut
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
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        try {
            $user_id    = $event->user->id;
            $username   = ($event->user->getTable() == "users" ?  $event->user->mobile_no: $event->user->username);
            $guard      = ($event->user->getTable() == "users" ? "student" : "admin");
            $activity   = "Logged Out ".json_encode($event->user->name);
            saveLogs($user_id, $username, $guard, $activity, $save_to_log = false);
            Log::emergency("Logged Out. ".$event->user->getTable());
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
