<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot()
    {
        $this->registerPolicies();

        Gate::define('access-master', function ($user) {
            return in_array($user->role, ['admin']);
        });
        Gate::define('create-data', function ($user) {
            return in_array($user->role, ['admin', 'dosen']);
        });
        Gate::define('create-soal', function ($user) {
            return in_array($user->role, ['dosen']);
        });
        Gate::define('read-data', function ($user) {
            return in_array($user->role, ['admin', 'dosen']);
        });
        Gate::define('update-data', function ($user) {
            return in_array($user->role, ['admin', 'dosen']);
        });
        Gate::define('delete-data', function ($user) {
            return $user->role === 'admin' && !in_array($user->jabatan, ['dosen', 'plpp']);
        });
    }
}
