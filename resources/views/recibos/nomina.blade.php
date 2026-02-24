<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    .box { border:1px solid #000; }
    .row { width: 100%; border-collapse: collapse; }
    .row td, .row th { border:1px solid #000; padding:6px; vertical-align: top; }
    .title { background:#f2f2f2; font-weight:bold; text-align:center; }
    .right { text-align:right; }
    .bold { font-weight:bold; }
    .mt { margin-top: 10px; }
  </style>
</head>
<body>

@php
  $emp = $calculo->empleado;
  $empresa = $emp?->empresa;
  $isr = $emp?->latestIsr;

   $path = base_path('resources/js/Pages/image/nomina.jpeg');

    if (!file_exists($path)) {
        die('No existe la imagen en: ' . $path);
    }

    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $imagen = 'data:image/' . $type . ';base64,' . base64_encode($data)
@endphp


<table class="row box">
  <tr>
    <th class="title" style="width:50%;">EMPRESA</th>
    <th class="title" style="width:50%;">TRABAJADOR/A</th>
  </tr>
  <tr>
    <td>
      <div><span class="bold">Nombre:</span> {{ $empresa?->nombre_razon_social ?? '—' }}</div>
      <div><span class="bold">RFC/CIF:</span> {{ $empresa?->rfc ?? '—' }}</div>
      <div><span class="bold">Direccion fiscal:</span> {{ $empresa?->direccion_fiscal ?? '—' }}</div>
      <div><span class="bold">Rigemen fiscal:</span> {{ $empresa?->regimen_fiscal ?? '—' }}</div>
      <div><span class="bold">Registro patronal:</span> {{ $empresa?->registro_patronal ?? '—' }}</div>
      <div><span class="bold">Fecha de elaboración:</span> 
    {{ now()->format('d/m/Y') }}
</div>
    </td>
    <td>
      <div><span class="bold">Nombre:</span> {{ $emp?->nombre_completo ?? '—' }}</div>
      <div><span class="bold">Identificación:</span> {{ $emp?->identificacion ?? '—' }}</div>
      <div><span class="bold">Puesto:</span> {{ $emp?->puesto ?? '—' }}</div>
      <div><span class="bold">Tipo contrato:</span> {{ $emp?->tipo_contrato ?? '—' }}</div>
      <div><span class="bold">Fecha ingreso:</span> {{ $emp?->fecha_ingreso ?? '—' }}</div>
      <div><span class="bold">Tipo nómina:</span> {{ $emp?->periodo_salario ?? '—' }}</div>
      <div><span class="bold">Tipo salario:</span> {{ $emp?->tipo_salario ?? '—' }}</div>
      <div><span class="bold">Jornada:</span> {{ $emp?->jornada ?? '—' }}</div>
    </td>
  </tr>
</table>

<table class="row box mt">
  <tr>
    <th class="title" style="width:60%;">DEVENGOS / PERCEPCIONES</th>
    <th class="title" style="width:40%;">TOTALES</th>
  </tr>
  <tr>
    <td>Salario diario</td>
    <td class="right">$ {{ number_format((float)$calculo->salario_diario, 2) }}</td>
  </tr>
  <tr>
    <td>Proporción aguinaldo</td>
    <td class="right">$ {{ number_format((float)$calculo->proporcion_aguinaldo, 2) }}</td>
  </tr>
  <tr>
    <td>Proporción vacaciones</td>
    <td class="right">$ {{ number_format((float)$calculo->proporcion_vacaciones, 2) }}</td>
  </tr>
</table>

<table class="row box mt">
  <tr>
    <th class="title" style="width:60%;">DEDUCCIONES</th>
    <th class="title" style="width:40%;">TOTALES</th>
  </tr>
  <tr>
    <td>IMSS (Total)</td>
    <td class="right">$ {{ number_format((float)$calculo->total_imss, 2) }}</td>
  </tr>
  <tr>
    <td>ISR a retener</td>
    <td class="right">$ {{ number_format((float)($isr?->isr_retener ?? 0), 2) }}</td>
  </tr>
</table>

@php
  $totalDev = (float)$calculo->salario_diario + (float)$calculo->proporcion_aguinaldo + (float)$calculo->proporcion_vacaciones;
  $totalDed = (float)$calculo->total_imss + (float)($isr?->isr_retener ?? 0);
  $liquido = $totalDev - $totalDed;
@endphp

<table class="row box mt">
  <tr>
    <th class="title" style="width:60%;">LÍQUIDO A PERCIBIR</th>
    <th class="title right" style="width:40%;">$ {{ number_format($liquido, 2) }}</th>
  </tr>
</table>
<div style="margin-top: 80px; margin-left: 60px; margin-right: 60px; text-align: center;">
    <img src="{{ $imagen }}" style="width: 120%;">
</div>
</body>
</html>