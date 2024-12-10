<?php

namespace App\Providers;

use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Repositories\ForgotPassword\ForgotPasswordRepository;
use App\Repositories\ForgotPassword\ForgotPasswordRepositoryInterface;
// use App\Repositories\ForgotPassword\ForgotpasswordRepositoryInterface;
use App\Repositories\Group\GroupRepository;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\Note\NoteRepository;
use App\Repositories\Note\NoteRepositoryInterface;
use App\Repositories\Subtask\SubtaskRepository;
use App\Repositories\Subtask\SubtaskRepositoryInterface;
use App\Repositories\Task\TaskRepository;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
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
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->bind(NoteRepositoryInterface::class, NoteRepository::class);
        $this->app->bind(SubtaskRepositoryInterface::class, SubtaskRepository::class);
        $this->app->bind(ForgotPasswordRepositoryInterface::class, ForgotPasswordRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $token_expire_token_time = (int) env('SESSION_LIFETIME', '60');
        $token_expire_token_time = $token_expire_token_time ? $token_expire_token_time : 60;
        Passport::personalAccessTokensExpireIn(now()->addDays($token_expire_token_time));

        Gate::before(function ($user, $ability) {
            return $user->hasRole('sysAdmin') ? true : null;
        });
    }
}
