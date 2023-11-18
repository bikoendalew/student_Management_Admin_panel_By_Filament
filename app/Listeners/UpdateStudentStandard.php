<?php

namespace App\Listeners;

use App\Events\PromotStudent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateStudentStandard
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
        $event->student->standard_id+=1;
        $event->student->save();
        //
    }
}
