<?php

namespace App\Modules\Core\Providers;

use App\Modules\Core\Actions\Fortify\CreateNewUser;
use App\Modules\Core\Actions\Fortify\ResetUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn () => view('core::auth.login'));
        Fortify::verifyEmailView(fn () => view('core::auth.verify-email'));
        Fortify::twoFactorChallengeView(fn () => view('core::auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn () => view('core::auth.confirm-password'));
        Fortify::registerView(fn () => view('core::auth.register'));
        Fortify::resetPasswordView(fn () => view('core::auth.reset-password'));
        Fortify::requestPasswordResetLinkView(fn () => view('core::auth.forgot-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            /** @var string $username */
            $username = $request->input(Fortify::username());

            $throttleKey = Str::transliterate(Str::lower($username).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}
