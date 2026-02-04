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

        // ✅ ALUMNO
        if ($user->role === 'alumno') {
            return Inertia::render('Dashboard', [
                'empresas' => Empresa::with('empleados') // 🔥 AQUÍ ESTABA EL ERROR
                    ->withCount('empleados')
                    ->orderBy('nombre_razon_social')
                    ->get(),
            ]);
        }

        // ✅ ADMIN
        if ($user->role === 'administrador' || $user->is_admin) {
            return Inertia::render('Dashboard', [
                'empresas' => Empresa::with('empleados') // 🔥 AQUÍ TAMBIÉN
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
