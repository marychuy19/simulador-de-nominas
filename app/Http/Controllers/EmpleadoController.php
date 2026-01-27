<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'empresa_id'      => 'required|exists:empresas,id',
            'nombre_completo' => 'required|string|max:255',
            'identificacion'  => 'required|string|max:255|unique:empleados,identificacion',
            'puesto'          => 'required|string|max:255',
            'tipo_contrato'   => 'required|string|max:50',
            'fecha_ingreso'   => 'required|date',
            'salario'         => 'required|numeric|min:0',
            'periodo_salario' => 'required|string|max:50',
            'tipo_salario'    => 'required|string|max:50',
            'jornada'         => 'required|string|max:50',
        ]);

        Empleado::create($validated);

        return back()->with('success', 'Empleado registrado correctamente');
    }
}