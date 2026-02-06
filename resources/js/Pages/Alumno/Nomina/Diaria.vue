<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

/* =============================
   DATOS
============================= */
const trabajador = ref('Mary Carmen')
const tipoSalario = ref('Fijo')
const salarioMensual = ref(60000)
const tipoPago = ref('Semanal')
const diasTrabajados = ref(15)
const fechaIngreso = ref('2024-01-06')

/* =============================
   PRESTACIONES (SUPERIORES A LA LEY)
============================= */
const diasAguinaldo = ref(15)
const diasVacaciones = ref(12)
const primaVacacional = ref(0.25)
const valesDespensaPorcentaje = ref(0.10)

/* =============================
   UMA Y LÍMITES
============================= */
const UMA = ref(117.31)
const limiteExentoVales = computed(() => UMA.value * 0.40)
const tresUMA = computed(() => UMA.value * 3)

/* =============================
   SALARIO DIARIO (AHORA QUINCENAL)
============================= */
const salarioDiario = computed(() => salarioMensual.value / 15)

/* =============================
   FACTOR DE INTEGRACIÓN
============================= */
const proporcionAguinaldo = computed(() => diasAguinaldo.value / 365)

const integradoVacaciones = computed(() =>
  diasVacaciones.value * primaVacacional.value
)

const proporcionVacaciones = computed(() =>
  integradoVacaciones.value / 365
)

const factorIntegracion = computed(() =>
  1 + proporcionAguinaldo.value + proporcionVacaciones.value
)

/* =============================
   VALES DE DESPENSA (QUINCENAL)
============================= */
const valesMensuales = computed(() =>
  salarioMensual.value * valesDespensaPorcentaje.value
)

const valesDiarios = computed(() => valesMensuales.value / 15)

const valesExentos = computed(() =>
  Math.min(valesDiarios.value, limiteExentoVales.value)
)

const valesGravados = computed(() =>
  Math.max(valesDiarios.value - valesExentos.value, 0)
)

/* =============================
   SBC
============================= */
const sbcSinVales = computed(() =>
  salarioDiario.value * factorIntegracion.value
)

const sbcConVales = computed(() =>
  sbcSinVales.value + valesGravados.value
)

/* =============================
   EXCEDENTE PATRONAL
============================= */
const excedentePatronal = computed(() =>
  sbcConVales.value > tresUMA.value
    ? sbcConVales.value - tresUMA.value
    : 0
)

/* =============================
   BASE IMSS QUINCENAL
============================= */
const baseIMSSMensual = computed(() =>
  sbcConVales.value * 15
)

/* =============================
   CUOTAS IMSS
============================= */
const cuotaExcedentePatronal = computed(() =>
  excedentePatronal.value * 0.004 * 15
)

const prestacionesDinero = computed(() =>
  baseIMSSMensual.value * 0.0025
)

const prestacionesEspecie = computed(() =>
  baseIMSSMensual.value * 0.00375
)

const invalidezVida = computed(() =>
  baseIMSSMensual.value * 0.00625
)

const cesantiaVejez = computed(() =>
  baseIMSSMensual.value * 0.01125
)

const totalIMSS = computed(() =>
  cuotaExcedentePatronal.value +
  prestacionesDinero.value +
  prestacionesEspecie.value +
  invalidezVida.value +
  cesantiaVejez.value
)
</script>

<template>
<Head title="Cálculo SBC" />
<AuthenticatedLayout>
<div class="max-w-7xl mx-auto p-6 space-y-6">

  <!-- ================= DATOS ================= -->
  <div class="card">
    <h2 class="title">DATOS</h2>
    <table class="tabla">
      <tr><td>TRABAJADOR</td><td><input v-model="trabajador" class="input" /></td></tr>
      <tr><td>TIPO DE SALARIO</td><td><input v-model="tipoSalario" class="input" /></td></tr>
      <tr><td>SALARIO MENSUAL</td><td><input v-model.number="salarioMensual" type="number" class="input" /></td></tr>
      <tr><td>TIPO DE PAGO</td><td><input v-model="tipoPago" class="input" /></td></tr>
      <tr><td>FECHA DE INGRESO</td><td><input v-model="fechaIngreso" type="date" class="input" /></td></tr>
    </table>
  </div>

  <!-- ================= FACTOR INTEGRACIÓN ================= -->
  <div class="card">
    <h2 class="title">DETERMINACIÓN DEL FACTOR DE INTEGRACIÓN</h2>
    <table class="tabla">
      <tr><td>AGUINALDO (art. 87 LFT)</td><td><input v-model.number="diasAguinaldo" type="number" class="input" /></td></tr>
      <tr><td>PROPORCIÓN DIARIA DE AGUINALDO</td><td>{{ proporcionAguinaldo.toFixed(6) }}</td></tr>
      <tr><td>DÍAS DE VACACIONES (art. 76 LFT)</td><td><input v-model.number="diasVacaciones" type="number" class="input" /></td></tr>
      <tr><td>% PRIMA VACACIONAL (art. 80 LFT)</td><td><input v-model.number="primaVacacional" type="number" step="0.01" class="input" /></td></tr>
      <tr><td>INTEGRADO DIARIO DE VACACIONES</td><td>{{ integradoVacaciones.toFixed(4) }}</td></tr>
      <tr><td>PROPORCIÓN DIARIA DE VACACIONES</td><td>{{ proporcionVacaciones.toFixed(6) }}</td></tr>
      <tr class="resaltado"><td>FACTOR DE INTEGRACIÓN</td><td>{{ factorIntegracion.toFixed(6) }}</td></tr>
    </table>
  </div>

  <!-- ================= VALES ================= -->
  <div class="card">
    <h2 class="title">DETERMINAR EL LÍMITE EXENTO DE LOS VALES</h2>
    <table class="tabla">
      <tr><td>UMA</td><td><input v-model.number="UMA" type="number" class="input" /></td></tr>
      <tr><td>LÍMITE EXENTO (40%)</td><td>{{ limiteExentoVales.toFixed(2) }}</td></tr>
      <tr><td>VALES DE DESPENSA (%)</td><td><input v-model.number="valesDespensaPorcentaje" step="0.01" type="number" class="input" /></td></tr>
      <tr><td>VALES DIARIOS</td><td>${{ valesDiarios.toFixed(2) }}</td></tr>
      <tr><td>EXENTO PERMITIDO</td><td>${{ valesExentos.toFixed(2) }}</td></tr>
      <tr class="resaltado"><td>GRAVADO</td><td>${{ valesGravados.toFixed(2) }}</td></tr>
    </table>
  </div>

  <!-- ================= SBC ================= -->
  <div class="card">
    <h2 class="title">DETERMINACIÓN DEL SALARIO BASE DE COTIZACIÓN</h2>
    <table class="tabla">
      <tr><td>SALARIO DIARIO</td><td>${{ salarioDiario.toFixed(2) }}</td></tr>
      <tr><td>FACTOR DE INTEGRACIÓN</td><td>{{ factorIntegracion.toFixed(6) }}</td></tr>
      <tr><td>SBC SIN VALES</td><td>${{ sbcSinVales.toFixed(2) }}</td></tr>
      <tr><td>VALES GRAVADOS</td><td>${{ valesGravados.toFixed(2) }}</td></tr>
      <tr class="resaltado"><td>SBC CON VALES</td><td>${{ sbcConVales.toFixed(2) }}</td></tr>
    </table>
  </div>

  <!-- ================= EXCEDENTE ================= -->
  <div class="card">
    <h2 class="title">EXCEDENTE PATRONAL</h2>
    <table class="tabla">
      <tr><td>UMA</td><td>${{ UMA }}</td></tr>
      <tr><td>3 VECES LA UMA</td><td>${{ tresUMA.toFixed(2) }}</td></tr>
      <tr><td>SBC</td><td>${{ sbcConVales.toFixed(2) }}</td></tr>
      <tr class="resaltado"><td>DIFERENCIA</td><td>${{ excedentePatronal.toFixed(2) }}</td></tr>
    </table>
  </div>

  <!-- ================= CUOTAS IMSS ================= -->
  <div class="card">
    <h2 class="title">CÁLCULO CUOTAS IMSS</h2>
    <table class="tabla">
      <tr><td>DÍAS LABORADOS</td><td><input v-model.number="diasTrabajados" type="number" class="input" /></td></tr>
      <tr><td>BASE IMSS QUINCENAL</td><td>${{ baseIMSSMensual.toFixed(2) }}</td></tr>
      <tr><td>EXCEDENTE PATRONAL (0.4%)</td><td>${{ cuotaExcedentePatronal.toFixed(2) }}</td></tr>
      <tr><td>PRESTACIONES EN DINERO (0.25%)</td><td>${{ prestacionesDinero.toFixed(2) }}</td></tr>
      <tr><td>PRESTACIONES EN ESPECIE (0.375%)</td><td>${{ prestacionesEspecie.toFixed(2) }}</td></tr>
      <tr><td>INVALIDEZ Y VIDA (0.625%)</td><td>${{ invalidezVida.toFixed(2) }}</td></tr>
      <tr><td>CESANTÍA Y VEJEZ (1.125%)</td><td>${{ cesantiaVejez.toFixed(2) }}</td></tr>
      <tr class="total"><td>TOTAL IMSS</td><td>${{ totalIMSS.toFixed(2) }}</td></tr>
    </table>
  </div>

</div>
</AuthenticatedLayout>
</template>

<style scoped>
.card { @apply bg-white rounded-xl shadow p-5 }
.title { @apply font-bold text-lg mb-3 text-blue-800 }
.tabla { @apply w-full text-sm border }
.tabla td { @apply border p-2 }
.resaltado { @apply bg-blue-100 font-semibold }
.total { @apply bg-green-200 font-bold text-lg }
.input { @apply border rounded px-2 py-1 w-full }
</style>
