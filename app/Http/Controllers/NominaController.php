<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Isr;


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

    public function guardarIsr(Request $request)
{
    $request->validate([
        'empleado_id' => 'required|exists:empleados,id',
        'salario_base' => 'required|numeric',
        'dias_trabajados' => 'required|numeric',
        'total_percepciones' => 'required|numeric',
        'isr_determinado' => 'required|numeric',
        'subsidio_periodo' => 'required|numeric',
        'isr_retener' => 'required|numeric',
    ]);

    Isr::create($request->all());

    return redirect()->route('alumno.recibo');
}
}
