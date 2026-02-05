<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Empleado;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Asignar rol por defecto si no tiene uno
        if (empty($user->role)) {
            $user->role = 'alumno';
            $user->save();
        }

        // ✅ ALUMNO
        if ($user->role === 'alumno') {
            return Inertia::render('Dashboard', [
                'empresas' => Empresa::with('empleados')
                    ->withCount('empleados')
                    ->orderBy('nombre_razon_social')
                    ->get(),
            ]);
        }

        // ✅ ADMIN
        if ($user->role === 'admin') {
            return Inertia::render('Dashboard', [
                'empresas' => Empresa::with('empleados')
                    ->withCount('empleados')
                    ->orderBy('nombre_razon_social')
                    ->get(),

                'empleados' => Empleado::with('empresa')
                    ->latest()
                    ->get(),
            ]);
        }

        abort(403);
    }
}
