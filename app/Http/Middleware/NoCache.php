<?php

namespace App\Http\Middleware;

use Closure;

class NoCache
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
        $response = $next($request);

//        $response->header('Cache-Control', 'no-cache, must-revalidate');
        $response->header('Set-Cookie', 'HttpOnly;Secure;SameSite=None');

        return $response;
    }
}
