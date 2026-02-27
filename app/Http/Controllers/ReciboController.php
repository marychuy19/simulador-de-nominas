<?php

namespace App\Http\Controllers;

use App\Models\CalculoNomina;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NominaSingleExport;
use App\Exports\NominasAllExport;

class ReciboController extends Controller
{
    public function index(Request $request)
    {
        $q = CalculoNomina::where('user_id', auth()->id())
    ->with([
        'empleado.empresa',
        'empleado.latestIsr',
    ])
    ->latest();

        // Buscar por empleado o empresa
        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $q->whereHas('empleado', function ($qq) use ($search) {
                $qq->where('nombre_completo', 'like', "%{$search}%")
                   ->orWhereHas('empresa', function ($qe) use ($search) {
                       $qe->where('nombre_razon_social', 'like', "%{$search}%");
                   });
            });
        }

        // Filtro por empresa (texto)
        if ($request->filled('empresa')) {
            $empresa = $request->string('empresa')->toString();

            $q->whereHas('empleado.empresa', function ($qe) use ($empresa) {
                $qe->where('nombre_razon_social', 'like', "%{$empresa}%");
            });
        }

        // Filtro por tipo (usa empleado.periodo_salario)
        if ($request->filled('tipo')) {
            $tipo = $request->string('tipo')->toString();
            $q->whereHas('empleado', fn ($qq) => $qq->where('periodo_salario', $tipo));
        }

        $calculos = $q->paginate(10)->withQueryString();

        return Inertia::render('Alumno/Recibo', [
            'calculos' => $calculos,
            'filters' => $request->only(['search', 'empresa', 'tipo']),
        ]);
    }

   public function destroy($id)
{
    $calculo = CalculoNomina::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $calculo->delete();

    return back()->with('success', 'Nómina eliminada correctamente.');
}

    // PDF de UNA nómina
    public function pdf($id)
{
    $calculo = CalculoNomina::where('id', $id)
        ->where('user_id', auth()->id())
        ->with(['empleado.empresa', 'empleado.latestIsr'])
        ->firstOrFail();

    $pdf = Pdf::loadView('recibos.nomina', [
        'calculo' => $calculo,
    ])->setPaper('a4');

    return $pdf->download("nomina_{$calculo->id}.pdf");
}

    public function excel($id)
{
    $calculo = CalculoNomina::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    return Excel::download(
        new NominaSingleExport($calculo->id),
        "nomina_{$calculo->id}.xlsx"
    );
}

    // Excel de TODAS
    public function excelAll(Request $request)
    {
        return Excel::download(new NominasAllExport($request->only(['search','empresa','tipo'])), "nominas_todas.xlsx");
    }

    // PDF de TODAS (tipo reporte)
    public function pdfAll(Request $request)
    {
      return Excel::download(
    new NominasAllExport(
        array_merge(
            $request->only(['search','empresa','tipo']),
            ['user_id' => auth()->id()]
        )
    ),
    "nominas_todas.xlsx"
);

        // Reusa filtros
        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $q->whereHas('empleado', function ($qq) use ($search) {
                $qq->where('nombre_completo', 'like', "%{$search}%")
                  ->orWhereHas('empresa', fn($qe)=>$qe->where('nombre_razon_social','like',"%{$search}%"));
            });
        }
        if ($request->filled('empresa')) {
            $empresa = $request->string('empresa')->toString();
            $q->whereHas('empleado.empresa', fn($qe)=>$qe->where('nombre_razon_social','like',"%{$empresa}%"));
        }
        if ($request->filled('tipo')) {
            $tipo = $request->string('tipo')->toString();
            $q->whereHas('empleado', fn($qq)=>$qq->where('periodo_salario', $tipo));
        }

        $calculos = $q->get();

        $pdf = Pdf::loadView('recibos.nominas_all', [
            'calculos' => $calculos,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("nominas_todas.pdf");
    }
}