<?php

namespace App\Http\Middleware;

use Closure;

class AvUserAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(\Auth::av_user()->check())
        {
            //do nothing
           // $person=date("YmdH:i:s")."aaa \n";
           //$file="/home/eznewlife/ad.eznewlife.com/laravel/public/av_user.log.php";
          //dd($person);
         // file_put_contents($file, $person);
        }
        else
        {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->route('avbodies.index');
            }
        }

        return $next($request);
    }
}
