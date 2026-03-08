<?php

namespace App\Http\Controllers;

use App\Models\IsrTarifa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class IsrTarifaController extends Controller
{
    public function index(Request $request)
    {
        $tipo = $request->get('tipo', 'semanal');

        $rows = IsrTarifa::where('tipo', $tipo)
            ->orderBy('orden')
            ->orderBy('limite_inferior')
            ->get();

        return Inertia::render('Admin/IsrTarifas', [
            'tipo' => $tipo,
            'tipos' => ['diaria', 'semanal', 'decenal', 'quincenal', 'mensual'],
            'rows' => $rows,
        ]);
    }

    public function saveTable(Request $request)
    {
        $data = $request->validate([
            'tipo' => 'required|string|max:20',
            'rows' => 'required|array',
            'rows.*.id' => 'nullable|integer',
            'rows.*.limite_inferior' => 'required|numeric|min:0',
            'rows.*.limite_superior' => 'nullable|numeric|min:0',
            'rows.*.cuota_fija' => 'required|numeric|min:0',
            'rows.*.porcentaje' => 'required|numeric|min:0',
            'rows.*.orden' => 'nullable|integer|min:1',
            'rows.*.activo' => 'nullable|boolean',
        ]);

        $tipo = $data['tipo'];
        $rows = $data['rows'];

        foreach ($rows as $r) {
            if (isset($r['limite_superior']) && $r['limite_superior'] !== null) {
                if ((float) $r['limite_superior'] < (float) $r['limite_inferior']) {
                    return back()->withErrors([
                        'rows' => 'Hay renglones donde el límite superior es menor que el inferior.'
                    ]);
                }
            }
        }

        DB::transaction(function () use ($tipo, $rows) {
            $keepIds = [];

            foreach ($rows as $i => $r) {
                $payload = [
                    'tipo' => $tipo,
                    'limite_inferior' => $r['limite_inferior'],
                    'limite_superior' => $r['limite_superior'] ?? null,
                    'cuota_fija' => $r['cuota_fija'],
                    'porcentaje' => $r['porcentaje'],
                    'orden' => $r['orden'] ?? ($i + 1),
                    'activo' => isset($r['activo']) ? (bool) $r['activo'] : true,
                ];

                if (!empty($r['id'])) {
                    $row = IsrTarifa::where('tipo', $tipo)->where('id', $r['id'])->first();

                    if ($row) {
                        $row->update($payload);
                        $keepIds[] = $row->id;
                    } else {
                        $new = IsrTarifa::create($payload);
                        $keepIds[] = $new->id;
                    }
                } else {
                    $new = IsrTarifa::create($payload);
                    $keepIds[] = $new->id;
                }
            }

            if (count($keepIds) > 0) {
                IsrTarifa::where('tipo', $tipo)
                    ->whereNotIn('id', $keepIds)
                    ->delete();
            } else {
                IsrTarifa::where('tipo', $tipo)->delete();
            }
        });

        return back()->with('success', 'Tabla ISR guardada correctamente');
    }
}