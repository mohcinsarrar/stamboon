<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

use Illuminate\Support\Facades\Auth;

use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;


use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                $user = auth()->user();
                if($user->active == 0){
                    Auth::guard('web')->logout();

                    // Invalidate the session
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    // Redirect to the login page
                    return redirect()->route('login')->with('error','These credentials do not match our records.');
                }
                if($user->hasRole('admin')){
                    return redirect()->route('admin.dashboard.index');
                }
                if($user->hasRole('user')){
                    return redirect()->route('users.dashboard.index');
                }
            }
        });

        $this->app->instance(TwoFactorLoginResponse::class, new class implements TwoFactorLoginResponse {
            public function toResponse($request)
            {
                if(auth()->user()->hasRole('admin')){
                    return redirect()->route('admin.dashboard.index');
                }
                if(auth()->user()->hasRole('user')){
                    return redirect()->route('users.dashboard.index');
                }
            }
        });
        
        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse {
            public function toResponse($request)
            {
                if(auth()->user()->hasRole('admin')){
                    return redirect()->route('admin.dashboard.index');
                }
                if(auth()->user()->hasRole('user')){
                    return redirect()->route('users.dashboard.index');
                }
            }
        });

        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
            ->line('Your verification code is: ' . $notifiable->verification_code)
            ->line('Thank you for using our application!');
        });

        Fortify::VerifyEmailView(function () {
            $pageConfigs = ['myLayout' => 'blank'];
            return view('auth.verify-email', ['pageConfigs' => $pageConfigs]);
        });

        Fortify::registerView(function () {
            $pageConfigs = ['myLayout' => 'blank'];
            return view('auth.register', ['pageConfigs' => $pageConfigs]);
        });

        Fortify::loginView(function () {
            $pageConfigs = ['myLayout' => 'blank'];
            return view('auth.login', ['pageConfigs' => $pageConfigs]);
        });

        Fortify::requestPasswordResetLinkView(function () {
            $pageConfigs = ['myLayout' => 'blank'];
            return view('auth.forgot-password', ['pageConfigs' => $pageConfigs]);
        });

        Fortify::resetPasswordView(function () {
            $pageConfigs = ['myLayout' => 'blank'];
            return view('auth.reset-password', ['pageConfigs' => $pageConfigs]);
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password',['pageConfigs' => ['myLayout' => 'blank']]);
        });

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge',['pageConfigs' => ['myLayout' => 'blank']]);
         });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
