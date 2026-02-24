<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #000; padding:8px; vertical-align: top; }
    th { background:#f2f2f2; font-weight:bold; text-align:center; }
    .right { text-align:right; }
    .bold { font-weight:bold; }
    .mt { margin-top: 12px; }
  </style>
</head>
<body>

@php
  $emp = $calculo->empleado;
  $empresa = $emp?->empresa;

  // ✅ ISR más reciente del empleado (ya lo cargas con with('empleado.latestIsr'))
  $isr = $emp?->latestIsr;

  // ✅ Total percepciones DESDE ISR (BD) - NO calcular
  $totalPercepciones = (float) ($isr?->total_percepciones ?? 0);

  // ✅ Deducciones
  $imss = (float) ($calculo->total_imss ?? 0);
  $isrRetener = (float) ($isr?->isr_retener ?? 0);

  // ✅ Líquido
  $liquido = $totalPercepciones - $imss - $isrRetener;

      $path = base_path('resources/js/Pages/image/nomina.jpeg');

    if (!file_exists($path)) {
        die('No existe la imagen en: ' . $path);
    }

    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $imagen = 'data:image/' . $type . ';base64,' . base64_encode($data);

@endphp

<table>
  <tr>
    <th style="width:50%;">EMPRESA</th>
    <th style="width:50%;">TRABAJADOR/A</th>
  </tr>
  <tr>
    <td>
      <div><span class="bold">Nombre:</span> {{ $empresa?->nombre_razon_social ?? '—' }}</div>
      <div><span class="bold">Direccion fiscal:</span> {{ $empresa?->direccion_fiscal ?? '—' }}</div>
      <div><span class="bold">RFC:</span> {{ $empresa?->rfc ?? '—' }}</div>
      <div><span class="bold">Régimen fiscal:</span> {{ $empresa?->regimen_fiscal ?? '—' }}</div>
      <div><span class="bold">Registro patronal:</span> {{ $empresa?->registro_patronal ?? '—' }}</div>
      <div><span class="bold">Fecha elaboración:</span> {{ optional($calculo->created_at)->format('d/m/Y') }}</div>
    </td>
    <td>
      <div><span class="bold">Nombre:</span> {{ $emp?->nombre_completo ?? '—' }}</div>
      <div><span class="bold">Identificación:</span> {{ $emp?->identificacion ?? '—' }}</div>
      <div><span class="bold">Tipo contrato:</span> {{ $emp?->tipo_contrato ?? '—' }}</div>
      <div><span class="bold">Fecha ingreso:</span> {{ $emp?->fecha_ingreso ?? '—' }}</div>
      <div><span class="bold">Tipo nómina:</span> {{ $emp?->periodo_salario ?? '—' }}</div>
      <div><span class="bold">Tipo salario:</span> {{ $emp?->tipo_salario ?? '—' }}</div>
      <div><span class="bold">Jornada:</span> {{ $emp?->jornada ?? '—' }}</div>
    </td>
  </tr>
</table>

<!-- DEVENGOS / PERCEPCIONES -->
<table class="mt">
  <tr>
    <th style="width:60%;">DEVENGOS / PERCEPCIONES</th>
    <th style="width:40%;">TOTALES</th>
  </tr>
  <tr>
    <td>Salario diario</td>
    <td class="right">$ {{ number_format((float)($calculo->salario_diario ?? 0), 2) }}</td>
  </tr>

  <!-- ✅ total percepciones desde ISR -->
  <tr>
    <td class="bold">Total percepciones</td>
    <td class="right bold">$ {{ number_format($totalPercepciones, 2) }}</td>
  </tr>

  <tr>
    <td>Proporción aguinaldo</td>
    <td class="right">$ {{ number_format((float)($calculo->proporcion_aguinaldo ?? 0), 2) }}</td>
  </tr>
  <tr>
    <td>Proporción vacaciones</td>
    <td class="right">$ {{ number_format((float)($calculo->proporcion_vacaciones ?? 0), 2) }}</td>
  </tr>
</table>

<!-- DEDUCCIONES -->
<table class="mt">
  <tr>
    <th style="width:60%;">DEDUCCIONES</th>
    <th style="width:40%;">TOTALES</th>
  </tr>
  <tr>
    <td>IMSS (Total)</td>
    <td class="right">$ {{ number_format($imss, 2) }}</td>
  </tr>
  <tr>
    <td>ISR a retener</td>
    <td class="right">$ {{ number_format($isrRetener, 2) }}</td>
  </tr>
</table>

<!-- LÍQUIDO -->
<table class="mt">
  <tr>
    <th style="width:60%;">LÍQUIDO A PERCIBIR</th>
    <th class="right" style="width:40%;">$ {{ number_format($liquido, 2) }}</th>
  </tr>
</table>
<div style="margin-top: 80px; margin-left: 60px; margin-right: 60px; text-align: center;">
    <img src="{{ $imagen }}" style="width: 120%;">
</div>
</body>
</html>