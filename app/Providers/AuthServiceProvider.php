<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        //$this->registerPolicies();
        /* define a admin user role */
        Gate::define('isAdmin', function($user) {
           return $user->role == 'Admin';
        });
       
        /* define a manager user role */
        Gate::define('isMember', function($user) {
            return $user->role == 'Member';
        });
      
        /* define a user role */
        Gate::define('isStateLevelAdmin', function($user) {
            return $user->role == 'State Level Admin';
        });
      
        /* define a user role */
        Gate::define('isZonalLevelAdmin', function($user) {
            return $user->role == 'Zonal Level Admin';
        });
      
        /* define a user role */
        Gate::define('isCountryHead', function($user) {
            return $user->role == 'Country Head';
        });
    }
}
