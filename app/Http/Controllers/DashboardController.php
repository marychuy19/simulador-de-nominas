<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Empresa;

class DashboardController extends Controller
{
    public function index()
    {
        $empresas = Empresa::query()
            ->where('user_id', auth()->id())
            ->withCount('empleados')
            ->with(['empleados' => function ($q) {
                $q->orderBy('nombre_completo'); 
            }])
            ->orderBy('nombre_razon_social')
            ->get();

        return Inertia::render('Alumno/Inicio', [
            'empresas' => $empresas,
        ]);
    }
}