<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        // staff = admin or teacher (grades access)
        Gate::define('staff', function (User $user) {
            return in_array($user->role, ['admin', 'user']);
        });

        Gate::define('finance', function (User $user) {
            return $user->role === 'finance';
        });

        // cashier = admin or finance (invoice access)
        Gate::define('cashier', function (User $user) {
            return in_array($user->role, ['admin', 'finance']);
        });
    }
}
