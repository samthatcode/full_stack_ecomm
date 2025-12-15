<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
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
        // TODO: Update this URL once the frontend reset-password page is ready
        // This placeholder ensures the password reset email works during testing

        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            return url('/reset-password-placeholder/reset-pasword?token=' . $token . '&email=' . urlencode($notifiable->email));
        });
    }
}
