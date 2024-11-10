<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PiezaController;
use App\Http\Controllers\API\ComponenteController;
use App\Http\Controllers\API\HistorialComponenteController;
use App\Http\Controllers\API\HistorialComputadoraController;
use App\Http\Controllers\API\HistorialPiezaController;
use App\Http\Controllers\API\HistorialLicenciaController;
use App\Http\Controllers\API\ComputadoraController;
use App\Http\Controllers\API\LicenciaController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\KeyManagerController;

// Rutas de Autenticación, no requieren autenticacion
// Estas rutas son accesibles sin autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas protegidas, requieren autenticación
Route::middleware(['auth:sanctum'])->group(function () {

    // Todas las rutas dentro de este bloque requerirán autenticación
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/isValidBuyer', [AuthController::class, 'isValidBuyer']);
    Route::post('/getAuthenticatedUserName', [AuthController::class, 'getAuthenticatedUserName']);
    Route::post('/isProvider', [AuthController::class, 'isProvider']);


    // Rutas de Piezas
    Route::get('/piezas', [PiezaController::class, 'index']);
    Route::get('/piezas/{id}', [PiezaController::class, 'show']);
    Route::post('/piezas', [PiezaController::class, 'store']);
    Route::put('/piezas/{id}', [PiezaController::class, 'update']);
    Route::delete('/piezas/{id}', [PiezaController::class, 'destroy']);
    // Filtros
    Route::get('/piezas-disponibles', [PiezaController::class, 'availablePieces']);
    Route::get('/piezas-cpu-disponibles', [PiezaController::class, 'filterByCpu']);
    Route::get('/piezas-monitor-disponibles', [PiezaController::class, 'filterByMonitor']);
    Route::get('/piezas-mouse-disponibles', [PiezaController::class, 'filterByMouse']);
    Route::get('/piezas-teclado-disponibles', [PiezaController::class, 'filterByTeclado']);
    Route::get('/piezas-ups-disponibles', [PiezaController::class, 'filterByUps']);
    Route::get('/piezas-bocinas-disponibles', [PiezaController::class, 'filterByBocinas']);
    Route::get('/historial-piezas', [HistorialPiezaController::class, 'index']);
    Route::get('/historial-piezas/{id}', [HistorialPiezaController::class, 'show']);

    // Rutas de Componentes
    Route::get('/componentes', [ComponenteController::class, 'index']);
    Route::get('/componentes/{id}', [ComponenteController::class, 'show']);
    Route::post('/componentes', [ComponenteController::class, 'store']);
    Route::put('/componentes/{id}', [ComponenteController::class, 'update']);
    Route::delete('/componentes/{id}', [ComponenteController::class, 'destroy']);
    // Filtros
    Route::get('/componentes-disponibles', [ComponenteController::class, 'availableComponents']);
    Route::get('/componentes-placa_base-disponibles', [ComponenteController::class, 'filterByPlacaBase']);
    Route::get('/componentes-lector_cd-disponibles', [ComponenteController::class, 'filterByLectorCd']);
    Route::get('/componentes-ram-disponibles', [ComponenteController::class, 'filterByRam']);
    Route::get('/componentes-disco_duro-disponibles', [ComponenteController::class, 'filterByDiscoDuro']);
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('/componentes-filterAllByPlacaBase', [ComponenteController::class, 'filterAllByPlacaBase']);
    Route::get('/componentes-filterAllByRam', [ComponenteController::class, 'filterAllByRam']);
    Route::get('/componentes-filterAllByDiscoDuro', [ComponenteController::class, 'filterAllByDiscoDuro']);
    Route::get('/componentes-filterAllByLectorCd', [ComponenteController::class, 'filterAllByLectorCd']);
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::get('/historial-componentes', [HistorialComponenteController::class, 'index']);
    Route::get('/historial-componentes/{id}', [HistorialComponenteController::class, 'show']);

    // Rutas de Computadoras
    Route::get('/computadoras', [ComputadoraController::class, 'index']);
    Route::get('/computadoras/{id}', [ComputadoraController::class, 'show']);
    Route::post('/computadoras', [ComputadoraController::class, 'store']);
    Route::delete('/computadoras/{id}', [ComputadoraController::class, 'destroy']);
    Route::put('/computadoras/{id}', [ComputadoraController::class, 'update']);
    Route::get('/computadoras/{id1}/{id2}', [ComputadoraController::class, 'getByDepartment']);
    Route::get('/historial-computadoras', [HistorialComputadoraController::class, 'index']);
    Route::get('/historial-computadoras/{id}', [HistorialComputadoraController::class, 'show']);

    // Rutas de Licencias
    Route::get('/licencias', [LicenciaController::class, 'index']);
    Route::get('/licencias/{id}', [LicenciaController::class, 'show']);
    Route::post('/licencias', [LicenciaController::class, 'store']);
    Route::delete('/licencias/{id}', [LicenciaController::class, 'destroy']);
    Route::put('/licencias/{id}', [LicenciaController::class, 'update']);
    Route::post('/licencias/{id}/update-estado',[LicenciaController::class, 'updateEstado']);
    Route::get('/historial-licencias', [HistorialLicenciaController::class, 'index']);
    Route::get('/historial-licencias/{id}', [HistorialLicenciaController::class, 'show']);

    // Rutas de Key Manager
    Route::get('/keyes', [KeyManagerController::class, 'index']);
    Route::post('/keyes', [KeyManagerController::class, 'store']);
    Route::put('/keyes/{id}', [KeyManagerController::class, 'update']);
});

