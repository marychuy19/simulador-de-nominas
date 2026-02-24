<?php

namespace App\Http\Controllers;

use App\Models\CalculoNomina;
use Illuminate\Http\Request;

class CalculoNominaController extends Controller
{
    
public function store(Request $request)
{
    $validated = $request->validate([
        'empleado_id' => 'required|exists:empleados,id',
        'salarioDiario' => 'required|numeric',
    ]);

    CalculoNomina::create([
        'empleado_id' => $validated['empleado_id'],
        'dias_aguinaldo' => $request->diasAguinaldo ?? 0,
        'dias_vacaciones' => $request->diasVacaciones ?? 0,
        'prima_vacacional' => $request->primaVacacional ?? 0,
        'vales_despensa_porcentaje' => $request->valesDespensaPorcentaje ?? 0,
        'salario_diario' => $validated['salarioDiario'],
        'proporcion_aguinaldo' => $request->proporcionAguinaldo ?? 0,
        'proporcion_vacaciones' => $request->proporcionVacaciones ?? 0,
        'sbc_sin_vales' => $request->sbcSinVales ?? 0,
        'vales_gravados' => $request->valesGravados ?? 0,
        'sbc_con_vales' => $request->sbcConVales ?? 0,
        'total_imss' => $request->totalIMSS ?? 0,
    ]);

    return response()->json(['success' => true]);
}
}
