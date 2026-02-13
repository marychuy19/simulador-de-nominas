<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::withCount('empleados')
            ->with('empleados')
            ->get();

        return Inertia::render('Dashboard', [
            'empresas' => $empresas
        ]);
    }

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

        return redirect()->route('dashboard');

    }

    public function update(Request $request, Empresa $empresa)
    {
        $validated = $request->validate([
            'nombre_razon_social' => 'required|string',
            'rfc' => 'required|string',
            'direccion_fiscal' => 'required|string',
            'regimen_fiscal' => 'required|string',
            'periodo_pago' => 'required|string',
            'registro_patronal' => 'required|string',
        ]);

        $empresa->update($validated);

        return redirect()->back();
    }

    public function destroy(Empresa $empresa)
{
    $empresa->delete();

    return redirect()->route('dashboard')->with('success', 'Empresa eliminada correctamente');
}

}


