<?php

namespace App\Http\Controllers;

use App\Models\CalculoNomina;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalculoNominaController extends Controller
{

    /* =====================================
       GUARDAR NUEVA NÓMINA
    ===================================== */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'salarioDiario' => 'required|numeric',
        ]);

        CalculoNomina::create([
            'user_id' => auth()->id(), 
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


    /* =====================================
       LISTAR NÓMINAS (SOLO DEL USUARIO)
    ===================================== */
    public function index()
    {
        $calculos = CalculoNomina::where('user_id', auth()->id()) // 🔐 FILTRO
            ->with(['empleado.empresa', 'empleado.latestIsr'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Alumno/Recibos', [
            'calculos' => $calculos
        ]);
    }


    /* =====================================
       ELIMINAR NÓMINA (PROTEGIDO)
    ===================================== */
    public function destroy($id)
    {
        $nomina = CalculoNomina::where('id', $id)
            ->where('user_id', auth()->id()) // 🔐 SEGURIDAD
            ->firstOrFail();

        $nomina->delete();

        return back();
    }


    /* =====================================
       VER NÓMINA INDIVIDUAL (PROTEGIDO)
    ===================================== */
    public function show($id)
    {
        $nomina = CalculoNomina::where('id', $id)
            ->where('user_id', auth()->id()) // 🔐 SEGURIDAD
            ->with(['empleado.empresa', 'empleado.latestIsr'])
            ->firstOrFail();

        return response()->json($nomina);
    }
}
