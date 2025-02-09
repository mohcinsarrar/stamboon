<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Mail\SubscriptionEmail;
use Illuminate\Support\Facades\Mail;

class PaymentEnd
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if($user->last_payment() == false){
                return $next($request);
            }
            $payment = $user->last_payment();
            $paymentCountdown = $payment->countdown();
            $passedDays = $paymentCountdown['passedDays'];
            $totalDays = $paymentCountdown['totalDays'];
            $daysRemined = $totalDays-$passedDays;

            if($daysRemined < 0){
                return redirect()->back()->with('error', 'Your subscription end, you can still download all your data. Or you can extend and keep your account active from subscriptions page');
            }


            return $next($request);

        }
        return $next($request);
    }
}
