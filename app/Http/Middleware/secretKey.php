<?php

namespace App\Http\Middleware;

use Closure;

class secretKey
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
        // [Skull] - Fixed (tested on Development only)
        // Change the SERVER_ADDR for SERVER_NAME
        //if($request->getClientIp() != $_SERVER['SERVER_NAME'])
        //    return response()->json('Invalid Request');

        return $next($request);
    }
}
