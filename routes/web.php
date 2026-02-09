<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NominaController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/empresas', [EmpresaController::class, 'store'])->name('empresas.store');
    Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');

    Route::middleware(['role:alumno'])
        ->prefix('alumno')
        ->name('alumno.')
        ->group(function () {

            Route::get('/empleado', [AlumnoController::class, 'empleado'])->name('empleado');
            Route::get('/calculo-nomina', [AlumnoController::class, 'calculoNomina'])->name('calculo-nomina');
            Route::get('/recibo', [AlumnoController::class, 'recibo'])->name('recibo');
            Route::get('/empleados/create', [EmpleadoController::class, 'create'])->name('empleados.create');


            Route::get('/nomina/diaria', [NominaController::class, 'diaria'])->name('nomina.diaria');
            Route::get('/nomina/semanal', [NominaController::class, 'semanal'])->name('nomina.semanal');
            Route::get('/nomina/decena', [NominaController::class, 'decena'])->name('nomina.decena');
            Route::get('/nomina/quincenal', [NominaController::class, 'quincenal'])->name('nomina.quincenal');
            Route::get('/nomina/quincenal2', function () {
            return Inertia::render('Alumno/Nomina/Quincenal2');
            })->name('alumno.nomina.quincenal2');

            Route::get('/nomina/mensual', [NominaController::class, 'mensual'])->name('nomina.mensual');
            Route::post('/nomina/guardar-isr', [NominaController::class, 'guardarIsr']) ->name('guardar.isr');


            // LISTA EMPRESAS (JSON)
            Route::get('/empresas-lista', function () {
                return \App\Models\Empresa::select('id', 'nombre_razon_social')->get();
            })->name('empresas.lista');

            // LISTA EMPLEADOS QUINCENALES POR EMPRESA (JSON)
            Route::get('/empleados-quincenales', function (\Illuminate\Http\Request $request) {

                $empresaId = $request->query('empresa_id');

                $q = \App\Models\Empleado::query()
                    ->where('periodo_salario', 'quincenal');

                if ($empresaId) {
                    $q->where('empresa_id', $empresaId);
                }

                return $q->select(
                    'id',
                    'empresa_id',
                    'nombre_completo',
                    'tipo_salario',
                    'periodo_salario',
                    'fecha_ingreso',
                    'salario'
                )->get();

            })->name('empleados.quincenales');

        });

    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios');
            Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
            Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
            Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');
        });
});

require __DIR__ . '/auth.php';