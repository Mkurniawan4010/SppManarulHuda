<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Petugas;

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
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for user levels
        Gate::define('bendahara-only', function (Petugas $user) {
            return $user->level === 'bendahara';
        });

        Gate::define('bendahara-ketua-yayasan', function (Petugas $user) {
            return in_array($user->level, ['bendahara', 'ketua_yayasan']);
        });

        Gate::define('all-users', function (Petugas $user) {
            return in_array($user->level, ['bendahara', 'kepala_sekolah', 'ketua_yayasan']);
        });
        
        Gate::define('admin-only', function (Petugas $user) {
            return $user->level === 'bendahara';
        });
    }
}





