<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class ProductType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $type): Response
    {
        if (Auth::check()) {
            // get all payments of the auth user and check if a not expired exist
            $user = Auth::user();
            if($user->product_type($type) == false){
                if(auth()->user()->hasRole('admin')){
                    return redirect()->route('admin.dashboard.index');
                }
                if(auth()->user()->hasRole('user')){
                    return redirect()->route('users.dashboard.index');
                }
            }

        }
        return $next($request);
 
    }
 
}
