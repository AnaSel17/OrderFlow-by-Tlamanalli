<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        /*
        |--------------------------------------------------------------------------
        | Gate para validar roles
        |--------------------------------------------------------------------------
        | - @can('is', 'admin')
        | - @can('is', 'mesero')
        | - @can('is', 'cocinero')
        | - @can('is', 'cajero')
        */
        Gate::define('is', function ($user, $rolNecesario) {
            return strtolower($user->rol->nombre ?? '') === strtolower($rolNecesario);
        });
    }
}
