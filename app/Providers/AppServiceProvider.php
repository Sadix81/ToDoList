<?php

namespace App\Providers;

use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $token_expire_token_time = (int)env('SESSION_LIFETIME', '60');
        $token_expire_token_time = $token_expire_token_time ? $token_expire_token_time : 60;
        Passport::personalAccessTokensExpireIn(now()->addDays($token_expire_token_time));
    }
}
