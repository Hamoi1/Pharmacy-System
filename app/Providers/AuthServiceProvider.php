<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Role;
use App\Models\User;
use App\Models\UserPermissions;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
      
        foreach ($this->getPermissions() as $permission) {
            Gate::define($permission->name, function (User $user) use ($permission) {
                foreach ($permission->permissions as $perm) {
                    if ($perm->user_id == $user->id) {
                        return true;
                    }
                }
            });
        }
    }

    public function getPermissions()
    {
        return Role::with('permissions')->get();
    }

}
