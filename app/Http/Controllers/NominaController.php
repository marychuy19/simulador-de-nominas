<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class NominaController extends Controller
{
    public function diaria()
    {
        return Inertia::render('Alumno/Nomina/Diaria');
    }

    public function semanal()
    {
        return Inertia::render('Alumno/Nomina/Semanal');
    }

    public function decena()
    {
        return Inertia::render('Alumno/Nomina/Decena');
    }

    public function quincenal()
    {
        return Inertia::render('Alumno/Nomina/Quincenal');
    }

    public function mensual()
    {
        return Inertia::render('Alumno/Nomina/Mensual');
    }
}
