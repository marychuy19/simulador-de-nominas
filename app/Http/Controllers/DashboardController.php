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

        // ✅ ALUMNO: tu dashboard con modales (empresas/empleados)
        if ($user->role === 'alumno') {
            return Inertia::render('Dashboard', [
                'empresas' => Empresa::withCount('empleados')
                    ->orderBy('nombre_razon_social')
                    ->get(),
            ]);
        }

        // ✅ ADMIN: otro dashboard distinto
        if ($user->role === 'administrador' || $user->is_admin) {
            return Inertia::render('Dashboard', [
                'empresas' => Empresa::withCount('empleados')
                    ->orderBy('nombre_razon_social')
                    ->get(),

                // opcional: si admin quiere ver empleados también
                'empleados' => Empleado::with('empresa')
                    ->latest()
                    ->get(),
            ]);
        }

        abort(403);
    }
}