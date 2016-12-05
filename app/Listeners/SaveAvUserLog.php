<?php

namespace App\Listeners;

use App\Events\AvUserChecked;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveAvUserLog
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
     * @param  AvUserChecked  $event
     * @return void
     */
    public function handle(AvUserChecked $event)
    {
        //
    }
}
