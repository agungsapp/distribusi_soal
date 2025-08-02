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

        // Gate untuk create data master
        Gate::define('create-data', function ($user) {
            return in_array($user->role, ['admin', 'dosen']);
        });

        // Gate untuk read data master
        Gate::define('read-data', function ($user) {
            return in_array($user->role, ['admin', 'dosen']);
        });

        // Gate untuk update data master
        Gate::define('update-data', function ($user) {
            return in_array($user->role, ['admin', 'dosen']);
        });

        // Gate untuk delete data master
        Gate::define('delete-data', function ($user) {
            return $user->role === 'admin' && !in_array($user->jabatan, ['dosen', 'plpp']);
        });
    }
}
