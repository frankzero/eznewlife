<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Carbon\Carbon;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     * * 應用程式的事件監聽器對映。
     * @var array
     */
    protected $listen = [
        /*
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        'auth.login' => [
            'App\Handlers\Events\AuthLoginEventHandler',
        ],*/
        'App\Events\AvUserChecked'=>[
            'App\Listeners\SaveAvUserListener'
        ],


    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    /*
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
        $events->listen('App\Events\AvUserChecked', function ($av_user, $remember) {
          //  $av_user->update(['last_login' => \Carbon\Carbon::now(), 'ip' => \Request::ip()]);
            $person=date("YmdH:i:s")."\n";
            $file="/home/eznewlife/ad.eznewlife.com/laravel/public/av_user.log.php";
            //dd($person);
            file_put_contents($file, $person);
        });
        //
    }*/

}
