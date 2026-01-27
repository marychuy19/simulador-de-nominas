<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| RUTAS AUTENTICADAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD (el controller decide vista según rol)
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | FORMULARIOS (POST) DESDE EL DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::post('/empresas', [EmpresaController::class, 'store'])->name('empresas.store');
    Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');

    /*
    |--------------------------------------------------------------------------
    | ROL ALUMNO (Pages/Alumno/*)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:alumno'])
        ->prefix('alumno')
        ->name('alumno.')
        ->group(function () {

            // ✅ Página: resources/js/Pages/Alumno/Empleado.vue
            Route::get('/empleado', [AlumnoController::class, 'empleado'])
                ->name('empleado');

            // ✅ Página: resources/js/Pages/Alumno/CalculoNomina.vue
            Route::get('/calculo-nomina', [AlumnoController::class, 'calculoNomina'])
                ->name('calculo-nomina');

            // ✅ Página: resources/js/Pages/Alumno/Recibo.vue
            Route::get('/recibo', [AlumnoController::class, 'recibo'])
                ->name('recibo');
        });

    /*
    |--------------------------------------------------------------------------
    | ROL ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['is_admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/usuarios', [UserController::class, 'index'])
                ->name('usuarios');

            Route::post('/usuarios', [UserController::class, 'store'])
                ->name('usuarios.store');

            Route::put('/usuarios/{user}', [UserController::class, 'update'])
                ->name('usuarios.update');

            Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])
                ->name('usuarios.destroy');
        });
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';