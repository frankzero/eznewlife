<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;
use Route;
class AdultMiddleware
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
/*
        if (($request->server->get('REMOTE_PORT') / 2) % 2 > 0)
        {
            throw new \Exception("WE DON'T LIKE ODD REMOTE PORTS");
        }
        $response = new Illuminate\Http\Response('Hello World');


        $response->withCookie(cookie('name', 'value',1));
*/
        $route = $request->route();
        if (Cookie::get('adult')==false) {
            //return new RedirectResponse(url('/'));
            //throw new \Exception($route);
           // return redirect(route("adult.login"));
            return redirect('adult/login');

        }
        return $next($request);
    }
}
