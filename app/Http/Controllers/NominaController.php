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

    // DIARIA2 con prefil1 desde querystring
    public function diaria2(Request $request)
    {
        return Inertia::render('Alumno/Nomina/Diaria2', [
            'prefil1' => [
                'empresa'        => $request->query('empresa', ''),
                'empleado'       => $request->query('empleado', ''),
                'salario_base'   => $request->query('salario_base', 0),
                'dias_trabajados'=> $request->query('dias_trabajados', 1),
            ],
        ]);
    }

    // SEMANAL2 con prefil2 desde querystring
    public function semanal2(Request $request)
    {
        return Inertia::render('Alumno/Nomina/Semanal2', [
            'prefil2' => [
                'empresa'        => $request->query('empresa', ''),
                'empleado'       => $request->query('empleado', ''),
                'salario_base'   => $request->query('salario_base', 0),
                'dias_trabajados'=> $request->query('dias_trabajados', 1),
            ],
        ]);
    }

    //DECENAL2 con prefil2 desde querystring
    public function decena2(Request $request)
    {
        return Inertia::render('Alumno/Nomina/Decena2', [
            'prefil3' => [
                'empresa'        => $request->query('empresa', ''),
                'empleado'       => $request->query('empleado', ''),
                'salario_base'   => $request->query('salario_base', 0),
                'dias_trabajados'=> $request->query('dias_trabajados', 1),
            ],
        ]);
    }

    // QUINCENAL2 con prefill desde querystring
    public function quincenal2(Request $request)
    {
        return Inertia::render('Alumno/Nomina/Quincenal2', [
            'prefill' => [
                'empresa'        => $request->query('empresa', ''),
                'empleado'       => $request->query('empleado', ''),
                'salario_base'   => $request->query('salario_base', 0),
                'dias_trabajados'=> $request->query('dias_trabajados', 15),
            ],
        ]);
    }

    //MENSUAL2 con prefil4 desde querystring
    public function mensual2(Request $request)
    {
        return Inertia::render('Alumno/Nomina/Mensual2', [
            'prefil4' => [
                'empresa'        => $request->query('empresa', ''),
                'empleado'       => $request->query('empleado', ''),
                'salario_base'   => $request->query('salario_base', 0),
                'dias_trabajados'=> $request->query('dias_trabajados', 1),
            ],
        ]);
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

        // (Esto lo dejas igual, tú ya haces router.visit a quincenal2 desde Vue)
        return redirect()->route('alumno.recibo');
    }
}