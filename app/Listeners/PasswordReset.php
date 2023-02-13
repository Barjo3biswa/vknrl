<?php

namespace App\Listeners;

use Illuminate\Auth\Events\PasswordReset as Reset;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class PasswordReset
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
     * @param  Reset  $event
     * @return void
     */
    public function handle(Reset $event)
    {
        $user = $event->user;
        Log::debug('Password Reset');
        Log::debug($user);
        $username   = ($event->user->getTable() == "users" ?  $event->user->mobile_no: $event->user->username);
        $guard      = ($event->user->getTable() == "users" ? "student" : "admin");
        $activity   = "Password Reset by ".json_encode($event->user->name);
        saveLogs($user->id, $username, $guard, $activity);
    }
}
