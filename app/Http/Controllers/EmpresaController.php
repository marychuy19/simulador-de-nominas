<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmpresaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_razon_social' => 'required|string',
            'rfc' => 'required|string',
            'direccion_fiscal' => 'required|string',
            'regimen_fiscal' => 'required|string',
            'periodo_pago' => 'required|string',
            'registro_patronal' => 'required|string',
        ]);

        Empresa::create($validated);

        return redirect()->back()->with('success', 'Empresa registrada correctamente');
    }

    public function create()
{
    $empresas = Empresa::all();

    return Inertia::render('Empleado/Create', [
        'empresas' => $empresas
    ]);
}

    
}


