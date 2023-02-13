<?php

namespace App\Listeners;

use App\Events\ApplicationEdited;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendApplicationEditedNotification
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
     * @param  ApplicationEdited  $event
     * @return void
     */
    public function handle(ApplicationEdited $event)
    {
        $application = $event->application;
        \Log::info("Application {$event->application->id} is updated.");
        if ($application->isDirty()){
            // doSomething();
            // dump($application->getOriginal());
            $application->auditTrail()->create(array_merge(array_except($application->getOriginal(), ["created_at", "updated_at"]), [
                "edited_by_id"  => auth(get_guard())->id(),
                "edited_by"     =>get_guard()
            ]));
        }
        // dd($event->application->toArray());
    }
}
