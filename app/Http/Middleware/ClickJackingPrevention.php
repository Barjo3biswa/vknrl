<?php

namespace App\Http\Middleware;

use Closure;

class ClickJackingPrevention
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
        // return $next($request);
        $response = $next($request);
        $response->headers->set("X-Frame-Options", "DENY");
        $response->headers->set("Content-Security-Policy", "frame-ancestors 'none'", false);
        return $response;
    }
}
