<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Empresa;
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

        // ✅ Validar ownership de la empresa
        $empresaOk = Empresa::where('id', $validated['empresa_id'])
            ->where('user_id', auth()->id())
            ->exists();

        if (!$empresaOk) {
            abort(403, 'No puedes agregar empleados a empresas que no son tuyas.');
        }

        Empleado::create($validated);

        return back()->with('success', 'Empleado registrado correctamente');
    }

    public function update(Request $request, Empleado $empleado)
    {
        // ✅ Solo si su empresa pertenece al usuario
        if ((int)$empleado->empresa->user_id !== (int)auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'identificacion'  => 'required|string|max:255',
            'puesto'          => 'required|string|max:255',
            'tipo_contrato'   => 'required|string|max:50',
            'fecha_ingreso'   => 'required|date',
            'salario'         => 'required|numeric|min:0',
            'periodo_salario' => 'required|string|max:50',
            'tipo_salario'    => 'required|string|max:50',
            'jornada'         => 'required|string|max:50',
        ]);

        $empleado->update($data);

        return back()->with('success', 'Empleado actualizado');
    }

    public function destroy(Empleado $empleado)
    {
        if ((int)$empleado->empresa->user_id !== (int)auth()->id()) {
            abort(403);
        }

        $empleado->delete();

        return back()->with('success', 'Empleado eliminado');
    }
}