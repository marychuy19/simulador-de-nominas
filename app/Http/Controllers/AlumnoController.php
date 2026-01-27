<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Inertia\Inertia;

class AlumnoController extends Controller
{
    public function empleado()
    {
        return Inertia::render('Alumno/Empleado', [
            'empleados' => Empleado::with('empresa')->latest()->get()
        ]);
    }

    public function calculoNomina()
    {
        return Inertia::render('Alumno/CalculoNomina');
    }

    public function recibo()
    {
        return Inertia::render('Alumno/Recibo');
    }
}