<?php

namespace App\Exports;

use App\Models\CalculoNomina;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NominaSingleExport implements FromArray, WithHeadings
{
    public function __construct(private int $id) {}

    public function headings(): array
    {
        return [
            'ID',
            'Empresa',
            'Empleado',
            'Tipo',
            'Salario Diario',
            'Total Percepciones (ISR)',
            'IMSS Total',
            'ISR Retener',
            'Líquido a Percibir',
            'Fecha',
        ];
    }

    public function array(): array
    {
        $c = CalculoNomina::with(['empleado.empresa','empleado.latestIsr'])->findOrFail($this->id);

        $e = $c->empleado;
        $emp = $e?->empresa;
        $isr = $e?->latestIsr;

        // ✅ BD desde ISR
        $tp = (float) ($isr?->total_percepciones ?? 0);
        $imss = (float) ($c->total_imss ?? 0);
        $isrRet = (float) ($isr?->isr_retener ?? 0);

        $liq = $tp - $imss - $isrRet;

        return [[
            $c->id,
            $emp?->nombre_razon_social,
            $e?->nombre_completo,
            $e?->periodo_salario,
            $c->salario_diario,
            $tp,
            $imss,
            $isrRet,
            $liq,
            optional($c->created_at)->format('Y-m-d H:i'),
        ]];
    }
}