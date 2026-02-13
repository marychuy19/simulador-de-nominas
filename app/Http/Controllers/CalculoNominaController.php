<?php

namespace App\Http\Controllers;

use App\Models\CalculoNomina;
use Illuminate\Http\Request;

class CalculoNominaController extends Controller
{
    
public function store(Request $request)
{
    $request->validate([
        'empleado_id' => 'required|exists:empleados,id',
        'salarioDiario' => 'required|numeric',
    ]);

    CalculoNomina::create([
        'empleado_id' => $request->empleado_id,
        'dias_aguinaldo' => $request->diasAguinaldo,
        'dias_vacaciones' => $request->diasVacaciones,
        'prima_vacacional' => $request->primaVacacional,
        'vales_despensa_porcentaje' => $request->valesDespensaPorcentaje,
        'salario_diario' => $request->salarioDiario,
        'proporcion_aguinaldo' => $request->proporcionAguinaldo,
        'proporcion_vacaciones' => $request->proporcionVacaciones,
        'sbc_sin_vales' => $request->sbcSinVales,
        'vales_gravados' => $request->valesGravados,
        'sbc_con_vales' => $request->sbcConVales,
        'total_imss' => $request->totalIMSS,
    ]);

    return redirect()->back()->with('success', 'Cálculo guardado correctamente');
}
}
