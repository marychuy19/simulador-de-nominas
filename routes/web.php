<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\NominaController;
use App\Http\Controllers\CalculoNominaController;
use App\Http\Controllers\ReciboController;
use App\Http\Controllers\AlumnoController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('login'), // si tienes register, cámbialo
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | ADMIN / SUPERADMIN
    |--------------------------------------------------------------------------
    | ✅ Un solo grupo admin, sin duplicados
    */
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(['role:admin|superadmin'])
        ->group(function () {

            Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
            Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
            Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
            Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');
        });

    /*
    |--------------------------------------------------------------------------
    | ALUMNO (y ADMIN también)
    |--------------------------------------------------------------------------
    | ✅ Aquí viven todas las rutas que tu frontend usa con route('alumno....')
    | ✅ Esto evita 403 y evita Ziggy routes faltantes
    */
    Route::prefix('alumno')
        ->name('alumno.')
        ->middleware(['role:admin|alumno'])
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Páginas (AlumnoController)
            |--------------------------------------------------------------------------
            */
            Route::get('/empleado', [AlumnoController::class, 'empleado'])->name('empleado');
            Route::get('/calculo-nomina', [AlumnoController::class, 'calculoNomina'])->name('calculoNomina');

            /*
            |--------------------------------------------------------------------------
            | EMPRESAS (CRUD)
            |--------------------------------------------------------------------------
            | ✅ Estas son las que usas desde Inicio.vue: alumno.empresas.store / destroy
            */
            Route::post('/empresas', [EmpresaController::class, 'store'])->name('empresas.store');
            Route::put('/empresas/{empresa}', [EmpresaController::class, 'update'])->name('empresas.update');
            Route::delete('/empresas/{empresa}', [EmpresaController::class, 'destroy'])->name('empresas.destroy');

            // LISTA EMPRESAS (JSON) - solo del usuario logueado
            Route::get('/empresas-lista', function () {
                return \App\Models\Empresa::query()
                    ->where('user_id', auth()->id())
                    ->select('id', 'nombre_razon_social')
                    ->orderBy('nombre_razon_social')
                    ->get();
            })->name('empresas.lista');

            /*
            |--------------------------------------------------------------------------
            | EMPLEADOS (CRUD)
            |--------------------------------------------------------------------------
            | ✅ Estas son las que usas desde Inicio.vue: alumno.empleados.store
            */
            Route::post('/empleados', [EmpleadoController::class, 'store'])->name('empleados.store');
            Route::put('/empleados/{empleado}', [EmpleadoController::class, 'update'])->name('empleados.update');
            Route::delete('/empleados/{empleado}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');

            // (Opcional) Si aún usas la vista create en /alumno/empleados/create
            Route::get('/empleados/create', [EmpleadoController::class, 'create'])->name('empleados.create');

            /*
            |--------------------------------------------------------------------------
            | NOMINA (pantallas)
            |--------------------------------------------------------------------------
            | ✅ Quité duplicados
            */
            Route::get('/nomina/diaria', [NominaController::class, 'diaria'])->name('nomina.diaria');
            Route::get('/nomina/diaria2', [NominaController::class, 'diaria2'])->name('nomina.diaria2');

            Route::get('/nomina/semanal', [NominaController::class, 'semanal'])->name('nomina.semanal');
            Route::get('/nomina/semanal2', [NominaController::class, 'semanal2'])->name('nomina.semanal2');

            Route::get('/nomina/decena', [NominaController::class, 'decena'])->name('nomina.decena');
            Route::get('/nomina/decena2', [NominaController::class, 'decena2'])->name('nomina.decena2');

            Route::get('/nomina/quincenal', [NominaController::class, 'quincenal'])->name('nomina.quincenal');
            Route::get('/nomina/quincenal2', [NominaController::class, 'quincenal2'])->name('nomina.quincenal2');

            Route::get('/nomina/mensual', [NominaController::class, 'mensual'])->name('nomina.mensual');
            Route::get('/nomina/mensual2', [NominaController::class, 'mensual2'])->name('nomina.mensual2');

            // Guardar ISR
            Route::post('/nomina/guardar-isr', [NominaController::class, 'guardarIsr'])->name('guardar.isr');

            // Guardar nómina (una sola vez)
            Route::post('/nomina/guardar-nomina', [CalculoNominaController::class, 'store'])->name('nomina.guardar');

            /*
            |--------------------------------------------------------------------------
            | RECIBOS
            |--------------------------------------------------------------------------
            */
            Route::get('/recibo', [ReciboController::class, 'index'])->name('recibo');
            Route::get('/recibo/{calculo}/pdf', [ReciboController::class, 'pdf'])->name('recibo.pdf');
            Route::get('/recibo/{calculo}/excel', [ReciboController::class, 'excel'])->name('recibo.excel');

            Route::get('/recibo/pdf-all', [ReciboController::class, 'pdfAll'])->name('recibo.pdfAll');
            Route::get('/recibo/excel-all', [ReciboController::class, 'excelAll'])->name('recibo.excelAll');

            Route::delete('/recibo/{calculo}', [ReciboController::class, 'destroy'])->name('recibo.destroy');

            /*
            |--------------------------------------------------------------------------
            | LISTAS JSON DE EMPLEADOS POR PERIODO (FILTRADO POR EMPRESA)
            |--------------------------------------------------------------------------
            */
            Route::get('/empleados-diario', function (\Illuminate\Http\Request $request) {
                $empresaId = $request->query('empresa_id');

                $q = \App\Models\Empleado::query()
                    ->where('periodo_salario', 'diario');

                if ($empresaId) $q->where('empresa_id', $empresaId);

                return $q->select(
                    'id', 'empresa_id', 'nombre_completo',
                    'tipo_salario', 'periodo_salario',
                    'fecha_ingreso', 'salario'
                )->get();
            })->name('empleados.diario');

            Route::get('/empleados-semanal', function (\Illuminate\Http\Request $request) {
                $empresaId = $request->query('empresa_id');

                $q = \App\Models\Empleado::query()
                    ->where('periodo_salario', 'semanal');

                if ($empresaId) $q->where('empresa_id', $empresaId);

                return $q->select(
                    'id', 'empresa_id', 'nombre_completo',
                    'tipo_salario', 'periodo_salario',
                    'fecha_ingreso', 'salario'
                )->get();
            })->name('empleados.semanal');

            Route::get('/empleados-10_dias', function (\Illuminate\Http\Request $request) {
                $empresaId = $request->query('empresa_id');

                $q = \App\Models\Empleado::query()
                    ->where('periodo_salario', '10_dias');

                if ($empresaId) $q->where('empresa_id', $empresaId);

                return $q->select(
                    'id', 'empresa_id', 'nombre_completo',
                    'tipo_salario', 'periodo_salario',
                    'fecha_ingreso', 'salario'
                )->get();
            })->name('empleados.10_dias');

            Route::get('/empleados-quincenales', function (\Illuminate\Http\Request $request) {
                $empresaId = $request->query('empresa_id');

                $q = \App\Models\Empleado::query()
                    ->where('periodo_salario', 'quincenal');

                if ($empresaId) $q->where('empresa_id', $empresaId);

                return $q->select(
                    'id', 'empresa_id', 'nombre_completo',
                    'tipo_salario', 'periodo_salario',
                    'fecha_ingreso', 'salario'
                )->get();
            })->name('empleados.quincenales');

            Route::get('/empleados-mensuales', function (\Illuminate\Http\Request $request) {
                $empresaId = $request->query('empresa_id');

                $q = \App\Models\Empleado::query()
                    ->where('periodo_salario', 'mensual');

                if ($empresaId) $q->where('empresa_id', $empresaId);

                return $q->select(
                    'id', 'empresa_id', 'nombre_completo',
                    'tipo_salario', 'periodo_salario',
                    'fecha_ingreso', 'salario'
                )->get();
            })->name('empleados.mensuales');
        });
});

require __DIR__.'/auth.php';