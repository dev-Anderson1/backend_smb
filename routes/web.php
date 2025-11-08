<?php
use Illuminate\Support\Facades\Route;

// Se você ainda usa Blade tradicionais para outras páginas, mantenha-as ANTES do catch-all.

// Catch-all da SPA (deixa por último)
Route::get('/{any}', function () {
    return view('app'); // resources/views/app.blade.php
})->where('any', '.*');




