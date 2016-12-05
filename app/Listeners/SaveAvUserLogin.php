<?php

namespace App\Listeners;

use App\Events\AvUserLoginSaved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveAvUserLogin
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
     * @param  AvUserLoginSaved  $event
     * @return void
     */
    public function handle(AvUserLoginSaved $event)
    {
        //
    }
}
