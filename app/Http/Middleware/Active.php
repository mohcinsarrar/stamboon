<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class Active
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // get all payments of the auth user and check if a not expired exist
            $user = Auth::user();
            if($user->hasRole('admin')){
                return redirect()->route('admin.dashboard.index');
            }
            if($user->has_payment() == false){
                return redirect()->route('users.subscription.index');
            }

        }
        return $next($request);
    }
}
