<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\OpmController;
use App\Http\Controllers\ArmaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AlgemaController;
use App\Http\Controllers\ColeteController;
use App\Http\Controllers\EspadaController;
use App\Http\Controllers\CalibreController;
use App\Http\Controllers\CautelaController;
use App\Http\Controllers\MunicaoController;
use App\Http\Controllers\CarregadorController;
use App\Http\Controllers\ModeloArmaController;
use App\Http\Controllers\PostoGraduacaoController;

// ROTAS PÚBLICAS
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [UserController::class, 'register'])->name('api.register');
Route::get('/health', function () {
    return response()->json([
        'ok' => true,
        'app' => config('app.name'),
        'env' => config('app.env'),
        'time' => now()->toISOString(),
    ]);
})->name('api.health');

// ROTAS AUTENTICADAS
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('api.user');

    // Route::apiResource('users', UserController::class)->name('api.obsolete');
    // Route::patch('/users/{id}/obsolete', [UserController::class, 'markAsObsolete']);

    Route::apiResource('users', UserController::class)->names([
    'index'   => 'api.users.index',
    'store'   => 'api.users.store',
    'show'    => 'api.users.show',
    'update'  => 'api.users.update',
    'destroy' => 'api.users.destroy',
]);
Route::patch('/users/{id}/obsolete', [UserController::class, 'markAsObsolete'])->name('api.users.obsolete');


    // Rotas de Cautela
//    Route::get('/armas/disponiveis', [ArmaController::class, 'armasDisponiveis']);

Route::get('/armas/disponiveis', [ArmaController::class, 'armasDisponiveis'])->name('api.armas.disponiveis');
Route::post('/carregadores/emprestar/{armaId}', [CarregadorController::class, 'emprestarCarregador'])->name('api.carregadores.emprestar');
Route::post('/carregadores/devolver/{armaId}', [CarregadorController::class, 'devolverCarregador'])->name('api.carregadores.devolver');

// Route::post('/carregadores/emprestar/{armaId}', [CarregadorController::class, 'emprestarCarregador']);
// Route::post('/carregadores/devolver/{armaId}', [CarregadorController::class, 'devolverCarregador']);
//     Route::get('/cautelas', [CautelaController::class, 'index']);
//     Route::get('/cautelas/{id}', [CautelaController::class, 'show']);
//     Route::put('/cautelas/{id}', [CautelaController::class, 'update']);
//     Route::delete('/cautelas/{id}', [CautelaController::class, 'destroy']);
//     Route::get('/usuarios-com-cautelas-pendentes', [CautelaController::class, 'usuariosComCautelasPendentes']);
//     Route::get('/usuarios/{id}/cautelas-pendentes', [CautelaController::class, 'getCautelasPorUsuario']);
//     Route::get('/me/cautelas-pendentes', [CautelaController::class, 'cautelasPendentesDoUsuarioAutenticado']);

//     Route::post('/cautela/finalizar', [CautelaController::class, 'finalizar']);
Route::get('/cautelas', [CautelaController::class, 'index'])->name('api.cautelas.index');
Route::get('/cautelas/{id}', [CautelaController::class, 'show'])->name('api.cautelas.show');
Route::put('/cautelas/{id}', [CautelaController::class, 'update'])->name('api.cautelas.update');
Route::delete('/cautelas/{id}', [CautelaController::class, 'destroy'])->name('api.cautelas.destroy');
Route::get('/usuarios-com-cautelas-pendentes', [CautelaController::class, 'usuariosComCautelasPendentes'])->name('api.cautelas.usuarios_pendentes');
Route::get('/usuarios/{id}/cautelas-pendentes', [CautelaController::class, 'getCautelasPorUsuario'])->name('api.cautelas.usuario_pendentes');
Route::get('/me/cautelas-pendentes', [CautelaController::class, 'cautelasPendentesDoUsuarioAutenticado'])->name('api.cautelas.minhas_pendentes');
Route::post('/cautela/finalizar', [CautelaController::class, 'finalizar'])->name('api.cautelas.finalizar');
Route::post('/cautela/store', [CautelaController::class, 'store'])->name('api.cautelas.store');
// Route::post('/cautela/devolucao', [CautelaController::class, 'devolucao'])->name('api.cautelas.devolucao');
// Route::post('/cautela/auth-user', [CautelaController::class, 'authUser'])->name('api.cautelas.auth_user');


});

// Teste de autenticação
Route::middleware('auth:api')->get('/ckeck', function (Request $request) {
    return $request->user();
})->name('api.check');;



//Route::get('/carregadores', [ArmaController::class, 'listarCarregadores']);
Route::get('/carregadores', [ArmaController::class, 'listarCarregadores'])->name('api.carregadores.index');


//Route::post('/cautela/auth-user', [CautelaController::class, 'authUser']);
Route::post('/auth-policial', [AuthController::class, 'authPolicial'])->name('api.auth_policial');;
//Route::apiResource('algemas', AlgemaController::class)->name('api.algemas');
//Route::apiResource('armas', ArmaController::class);
Route::apiResource('armas', ArmaController::class)->names([
    'index'   => 'api.armas.index',
    'store'   => 'api.armas.store',
    'show'    => 'api.armas.show',
    'update'  => 'api.armas.update',
    'destroy' => 'api.armas.destroy',
]);

Route::apiResource('espadas', EspadaController::class)->names([
    'index' => 'api.espadas.index',
    'store' => 'api.espadas.store',
    'show' => 'api.espadas.show',
    'update' => 'api.espadas.update',
    'destroy' => 'api.espadas.destroy',
]);

Route::apiResource('modelo_armas', ModeloArmaController::class)->names([
    'index' => 'api.modelo_armas.index',
    'store' => 'api.modelo_armas.store',
    'show' => 'api.modelo_armas.show',
    'update' => 'api.modelo_armas.update',
    'destroy' => 'api.modelo_armas.destroy',
]);

Route::apiResource('municoes', MunicaoController::class)->names([
    'index' => 'api.municoes.index',
    'store' => 'api.municoes.store',
    'show' => 'api.municoes.show',
    'update' => 'api.municoes.update',
    'destroy' => 'api.municoes.destroy',
]);

Route::apiResource('posto_graduacoes', PostoGraduacaoController::class)->names([
    'index' => 'api.posto_graduacoes.index',
    'store' => 'api.posto_graduacoes.store',
    'show' => 'api.posto_graduacoes.show',
    'update' => 'api.posto_graduacoes.update',
    'destroy' => 'api.posto_graduacoes.destroy',
]);

Route::apiResource('algemas', AlgemaController::class)->names([
    'index' => 'api.algemas.index',
    'store' => 'api.algemas.store',
    'show' => 'api.algemas.show',
    'update' => 'api.algemas.update',
    'destroy' => 'api.algemas.destroy',
]);

Route::apiResource('opms', OpmController::class)->names([
    'index' => 'api.opms.index',
    'store' => 'api.opms.store',
    'show' => 'api.opms.show',
    'update' => 'api.opms.update',
    'destroy' => 'api.opms.destroy',
]);

Route::apiResource('calibres', CalibreController::class)->names([
    'index' => 'api.calibres.index',
    'store' => 'api.calibres.store',
    'show' => 'api.calibres.show',
    'update' => 'api.calibres.update',
    'destroy' => 'api.calibres.destroy',
]);

//Route::apiResource('carregadores', CarregadorController::class);
Route::apiResource('carregadores', CarregadorController::class)->names([
    'index'   => 'api.carregadores.api_index',
    'store'   => 'api.carregadores.api_store',
    'show'    => 'api.carregadores.api_show',
    'update'  => 'api.carregadores.api_update',
    'destroy' => 'api.carregadores.api_destroy',
]);
//Route::apiResource('coletes', ColeteController::class)->name('api.coletes');
Route::apiResource('coletes', ColeteController::class)->names([
    'index' => 'api.coletes.index',
    'store' => 'api.coletes.store',
    'show' => 'api.coletes.show',
    'update' => 'api.coletes.update',
    'destroy' => 'api.coletes.destroy',
]);
//Route::apiResource('opms', OpmController::class)->name('api.opms');
//Route::apiResource('calibres', CalibreController::class)->name('api.calibres');
//Route::apiResource('espadas', EspadaController::class)->name('api.espadas');
//Route::apiResource('modelo_armas', ModeloArmaController::class)->name('api.modelo_armas');
//Route::apiResource('municoes', MunicaoController::class)->name('api.municoes');
//Route::apiResource('posto_graduacoes', PostoGraduacaoController::class)->name('api.posto_graduacoes');;
Route::post('/cautela/store', [CautelaController::class, 'store'])->name('api.cautela.store');;

//Route::post('/cautela/devolucao', [CautelaController::class, 'devolucao']);
Route::post('/cautela/devolucao', [CautelaController::class, 'devolucao'])->name('api.cautelas.devolucao');
Route::post('/cautela/auth-user', [CautelaController::class, 'authUser'])->name('api.cautelas.auth_user');