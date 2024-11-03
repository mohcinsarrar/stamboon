<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;

class CheckRegisterAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Define your condition here
        $condition = false; // Replace with your actual condition

        // Check if the request path is '/register' and if it's a GET or POST request
        if ($request->is('register') && in_array($request->method(), ['GET', 'POST'])) {
            
            $path = resource_path('views/admin/settings/settings.json');
            $json = File::get($path);
            $data = json_decode($json, true);

            if ( $data['can_register'] == false ) {
                // Deny access if the condition is not met
                return redirect()->route('login')->with('error', 'Access denied to registration');
            }
        }

        return $next($request);
    }
}
