<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\ConfiguracionNomina;
use App\Models\CuotaImss;

class ConfiguracionNominaController extends Controller
{
    public function index()
    {
        $config = ConfiguracionNomina::first();

        if (!$config) {
            $config = ConfiguracionNomina::create([
                'salario_minimo' => 278.80,
                'uma' => 117.31,
                'limite_vales_despensa' => 0.40,
                'subsidio_empleo' => 15.02,
                'tope_subsidio' => 535.65,
                'tope_subsidio_mensual' => 535.65,
                'limite_ingreso_subsidio' => 11492.66,
            ]);
        }

        $cuotas = CuotaImss::first();

        if (!$cuotas) {
            $cuotas = CuotaImss::create([
                'excedente_patronal' => 0.0040,
                'prestaciones_dinero' => 0.0025,
                'prestaciones_especie' => 0.00375,
                'invalidez_vida' => 0.00625,
                'cesantia_vejez' => 0.01125,
            ]);
        }

        return Inertia::render('Admin/ConfiguracionNomina', [
            'config' => $config,
            'cuotas' => $cuotas,
        ]);
    }

    public function update(Request $request)
    {
        $config = ConfiguracionNomina::first();

        if (!$config) {
            $config = ConfiguracionNomina::create([]);
        }

        $data = $request->validate([
            'salario_minimo' => 'required|numeric|min:0',
            'uma' => 'required|numeric|min:0',
            'limite_vales_despensa' => 'required|numeric|min:0',
            'subsidio_empleo' => 'required|numeric|min:0',
            'tope_subsidio' => 'required|numeric|min:0',
            'tope_subsidio_mensual' => 'required|numeric|min:0',
            'limite_ingreso_subsidio' => 'required|numeric|min:0',
        ]);

        $config->update($data);

        return back()->with('success', 'Configuración actualizada correctamente');
    }

    public function updateCuotas(Request $request)
    {
        $cuotas = CuotaImss::first();

        if (!$cuotas) {
            $cuotas = CuotaImss::create([]);
        }

        $data = $request->validate([
            'excedente_patronal' => 'required|numeric|min:0',
            'prestaciones_dinero' => 'required|numeric|min:0',
            'prestaciones_especie' => 'required|numeric|min:0',
            'invalidez_vida' => 'required|numeric|min:0',
            'cesantia_vejez' => 'required|numeric|min:0',
        ]);

        $cuotas->update($data);

        return back()->with('success', 'Cuotas IMSS actualizadas correctamente');
    }
}