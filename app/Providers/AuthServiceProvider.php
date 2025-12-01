<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $roles = config('permisos.roles');

        foreach ($roles as $roleId => $permisos) {
            foreach ($permisos as $permiso) {
                Gate::define($permiso, function ($user) use ($roleId, $permiso) {
                    return $user->id_rol == $roleId
                        && in_array($permiso, config("permisos.roles.$roleId"));
                });
            }
        }
    }
}
