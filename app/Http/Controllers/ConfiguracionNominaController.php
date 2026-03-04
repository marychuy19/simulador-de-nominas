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
    $cuotas = CuotaImss::first();

    if(!$config){
        $config = ConfiguracionNomina::create([
            'salario_minimo' => 0,
            'uma' => 0,
            'limite_vales_despensa' => 0,
            'subsidio_empleo' => 0,
        ]);
    }

    if(!$cuotas){
        $cuotas = CuotaImss::create([
            'excedente_patronal' => 0,
            'prestaciones_dinero' => 0,
            'prestaciones_especie' => 0,
            'invalidez_vida' => 0,
            'cesantia_vejez' => 0,
        ]);
    }

    return Inertia::render('Admin/ConfiguracionNomina',[
        'config' => $config,
        'cuotas' => $cuotas
    ]);
}

    public function update(Request $request)
    {
        $config = ConfiguracionNomina::first();

        $request->validate([
            'salario_minimo'=>'required|numeric',
            'uma'=>'required|numeric',
            'limite_vales_despensa'=>'required|numeric',
            'subsidio_empleo'=>'required|numeric',
        ]);

        $config->update($request->all());

        return back()->with('success','Configuración actualizada');
    }

   public function updateCuotas(Request $request)
{
    $cuotas = CuotaImss::first();

    if(!$cuotas){
        $cuotas = CuotaImss::create([]);
    }

    $cuotas->update($request->only([
        'excedente_patronal',
        'prestaciones_dinero',
        'prestaciones_especie',
        'invalidez_vida',
        'cesantia_vejez',
    ]));

    return back();
}
}