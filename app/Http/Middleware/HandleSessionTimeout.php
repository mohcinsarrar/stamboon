<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HandleSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->getStatusCode() === 419) {
            Session::flash('error', 'You were logged out (Your data was saved), please log in again.'); 
            return redirect()->route('login');
        }

        return $response;
    }
}
