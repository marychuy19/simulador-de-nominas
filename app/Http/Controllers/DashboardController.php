<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Empleado;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ✅ ALUMNO
        if ($user->role === 'alumno') {
            return Inertia::render('Alumno/Inicio', [
                'empresas' => Empresa::with('empleados') 
                    ->withCount('empleados')
                    ->orderBy('nombre_razon_social')
                    ->get(),
            ]);

            
        }
 // ✅ ADMIN
        if ($user->role === 'admin') {
            return Inertia::render('Admin/Inicio', [
                'users' => User::select('id', 'role')->get() // 👈 solo enviamos lo necesario
            ]);
        }
        abort(403);
    }
    
}
