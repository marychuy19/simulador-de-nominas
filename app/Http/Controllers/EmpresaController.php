<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmpresaController extends Controller
{
    private function ensureOwner(Empresa $empresa): void
    {
        if ((int)$empresa->user_id !== (int)auth()->id()) {
            abort(403, 'No puedes acceder a esta empresa.');
        }
    }

    public function index()
    {
        $empresas = Empresa::query()
            ->where('user_id', auth()->id())
            ->withCount('empleados')
            ->with('empleados')
            ->orderBy('nombre_razon_social')
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

        Empresa::create($validated + [
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('dashboard');
    }

    public function update(Request $request, Empresa $empresa)
    {
        $this->ensureOwner($empresa);

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
        $this->ensureOwner($empresa);

        $empresa->delete();

        return redirect()->route('dashboard')->with('success', 'Empresa eliminada correctamente');
    }
}