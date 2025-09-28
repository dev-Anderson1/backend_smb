<!-- <?php

use Illuminate\Support\Facades\Route;
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

Route::Post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/register', [UserController::class, 'register']);

Route::middleware(['auth:api', 'is_admin'])->group(function () {
   
    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Bem-vindo ao painel de admin']);
    });
});

use App\Http\Controllers\SomeController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'is_admin'])->get('/admin', [SomeController::class, 'adminDashboard']);

Route::apiResource('users', UserController::class);
Route::apiResource('algemas',AlgemaController::class);
Route::apiResource('armas', ArmaController::class);
Route::apiResource('opms', OpmController::class);
Route::apiResource('calibres', CalibreController::class);
Route::apiResource('carregadores', CarregadorController::class);
Route::apiResource('coletes', ColeteController::class);
Route::apiResource('espadas', EspadaController::class);
Route::apiResource('modelo_armas', ModeloArmaController::class);
Route::apiResource('municoes', MunicaoController::class);
Route::apiResource('posto_graduacoes', PostoGraduacaoController::class);

Route::post('/cautela/auth-user', [CautelaController::class, 'authUser']);
Route::post('/cautela/store', [CautelaController::class, 'store']);
Route::post('/cautela/finalizar', [CautelaController::class, 'finalizar']);
Route::post('/cautela/devolucao', [CautelaController::class, 'devolucao']);
Route::get('/cautelas', [CautelaController::class, 'index']);
    Route::get('/cautelas/{id}', [CautelaController::class, 'show']);
    Route::put('/cautelas/{id}', [CautelaController::class, 'update']);
    Route::delete('/cautelas/{id}', [CautelaController::class, 'destroy']);
    Route::get('/usuarios-com-cautelas-pendentes', [CautelaController::class, 'usuariosComCautelasPendentes']);




 -->
