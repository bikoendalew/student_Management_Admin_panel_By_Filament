<?php

namespace App\Listeners;

use App\Events\PromotStudent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPromotedEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PromotStudent $event): void
    {
        logger('sending email to student'.$event->student->name);
        //
    }
}
