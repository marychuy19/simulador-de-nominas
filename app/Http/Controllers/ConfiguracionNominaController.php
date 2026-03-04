<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\ConfiguracionNomina;

class ConfiguracionNominaController extends Controller
{

    public function index()
    {
        $config = ConfiguracionNomina::first();

        if(!$config){
            $config = ConfiguracionNomina::create([
                'salario_minimo' => 0,
                'uma' => 0,
                'limite_vales_despensa' => 0,
                'subsidio_empleo' => 0
            ]);
        }

        return Inertia::render('Admin/ConfiguracionNomina',[
            'config' => $config
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

}