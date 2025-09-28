<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Suas policies aqui, se houver
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Define o caminho das chaves OAuth (opcional se quiser personalizar)
        Passport::loadKeysFrom(storage_path('oauth'));
    }
}
