<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
        // Caso precise de algum prefixo ou middleware global para as rotas
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));
        
        // Qualquer outra configuração ou funcionalidade global
        // que você queira adicionar ao boot
    }
}
