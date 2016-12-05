<?php

namespace App\Http\Middleware;

use Closure;

class EnlUserAuthenticate
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
        if(\Auth::enl_user()->check())
        {
            //do nothing
        }
        else
        {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->route('articles.index');
            }
        }

        return $next($request);
    }
}
