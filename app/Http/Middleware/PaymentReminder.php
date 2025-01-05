<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Mail\SubscriptionEmail;
use Illuminate\Support\Facades\Mail;

class PaymentReminder
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

            if($daysRemined <= 30 && $payment->month == false){
                // send email reminder
                $title = "30 days Reminder";
                $user_fullname = $user->firstname. " " . $user->lastname;
                $content = "<b>Your subscription will be expired in 30 days</b> ( active until ". $payment->active_until(). " ). <br> You can extend and keep your account active from subscriptions page";
                Mail::to($user->email)->send(new SubscriptionEmail($title, $user_fullname, $content));

                // update payment month
                $payment->month = true;
                $payment->save();
            }

            if($daysRemined <= 7 && $payment->week == false){
                // send email reminder
                $title = "7 days Reminder";
                $user_fullname = $user->firstname. " " . $user->lastname;
                $content = "<b>Your subscription will be expired in 7 days</b> ( active until ". $payment->active_until(). " ). <br> You can extend and keep your account active from subscriptions page";
                Mail::to($user->email)->send(new SubscriptionEmail($title, $user_fullname, $content));

                // update payment week
                $payment->week = true;
                $payment->save();
            }

            if($daysRemined < 0 && $payment->end == false){
                // send email reminder
                $title = "End of subscription";
                $user_fullname = $user->firstname. " " . $user->lastname;
                $content = "<b>Your subscription end</b>, you can still download all your data. <br> Or you can extend and keep your account active from subscriptions page";
                Mail::to($user->email)->send(new SubscriptionEmail($title, $user_fullname, $content));

                // update payment end
                $payment->end = true;
                $payment->save();
            }


            return $next($request);

        }
        return $next($request);
    }
}
