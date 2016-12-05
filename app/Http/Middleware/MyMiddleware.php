<?php

namespace App\Http\Middleware;

use Closure;

class MyMiddleware
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
        // Test for an even vs. odd remote port
        if (($request->server->get('REMOTE_PORT') / 2) % 2 > 0)
        {
            throw new \Exception("WE DON'T LIKE ODD REMOTE PORTS");
        }

        return $next($request);
        //return $next($request);
    }
}
