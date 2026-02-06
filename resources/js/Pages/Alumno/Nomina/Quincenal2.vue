<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

/* =============================
   DATOS GENERALES (IGUAL QUE QUINCENAL 1)
============================= */
const empresa = ref('')
const empleado = ref('')
const tipoSalario = ref('')
const tipoPago = ref('')
const fechaIngreso = ref('')

/* =============================
   PERCEPCIONES (BASE DEL CÁLCULO)
============================= */
const salarioBase = ref(600) // salario diario
const diasTrabajados = ref(15)

const totalPercepciones = computed(() =>
  salarioBase.value * diasTrabajados.value
)

/* =============================
   PRESTACIONES
============================= */
const diasAguinaldo = ref(15)
const diasVacaciones = ref(12)
const primaVacacional = ref(0.25)
const valesDespensaPorcentaje = ref(0.10)

/* =============================
   UMA
============================= */
const UMA = ref(117.31)
const limiteExentoVales = computed(() => UMA.value * 0.40)
const tresUMA = computed(() => UMA.value * 3)

/* =============================
   SALARIO DIARIO (YA QUINCENAL)
============================= */
const salarioDiario = computed(() => salarioBase.value)

/* =============================
   FACTOR DE INTEGRACIÓN
============================= */
const proporcionAguinaldo = computed(() => diasAguinaldo.value / 365)
const integradoVacaciones = computed(() => diasVacaciones.value * primaVacacional.value)
const proporcionVacaciones = computed(() => integradoVacaciones.value / 365)
const factorIntegracion = computed(() =>
  1 + proporcionAguinaldo.value + proporcionVacaciones.value
)

/* =============================
   VALES DE DESPENSA (QUINCENAL)
============================= */
const valesDiarios = computed(() =>
  salarioDiario.value * valesDespensaPorcentaje.value
)

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
const baseIMSSQuincenal = computed(() =>
  sbcConVales.value * diasTrabajados.value
)

/* =============================
   CUOTAS IMSS
============================= */
const cuotaExcedentePatronal = computed(() =>
  excedentePatronal.value * 0.004 * diasTrabajados.value
)

const prestacionesDinero = computed(() =>
  baseIMSSQuincenal.value * 0.0025
)

const prestacionesEspecie = computed(() =>
  baseIMSSQuincenal.value * 0.00375
)

const invalidezVida = computed(() =>
  baseIMSSQuincenal.value * 0.00625
)

const cesantiaVejez = computed(() =>
  baseIMSSQuincenal.value * 0.01125
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
<Head title="Cálculo SBC Quincenal" />
<AuthenticatedLayout>
<div class="bg-blue-100 min-h-screen">
<div class="max-w-6xl mx-auto p-6 space-y-6">

  <!-- ================= DATOS ================= -->
  <div class="border rounded-xl overflow-hidden">
    <div class="bg-green-300 font-bold text-center py-2">DATOS</div>
    <table class="w-full border text-sm">
      <tr><td class="td font-semibold">EMPRESA</td><td class="td"><input v-model="empresa" class="input w-full" /></td></tr>
      <tr><td class="td font-semibold">NOMBRE DEL EMPLEADO</td><td class="td"><input v-model="empleado" class="input w-full" /></td></tr>
      <tr><td class="td font-semibold">TIPO DE SALARIO</td><td class="td"><input v-model="tipoSalario" class="input w-full" /></td></tr>
      <tr><td class="td font-semibold">TIPO DE PAGO</td><td class="td"><input v-model="tipoPago" class="input w-full" /></td></tr>
      <tr><td class="td font-semibold">FECHA DE INGRESO</td><td class="td"><input v-model="fechaIngreso" type="date" class="input w-full" /></td></tr>
    </table>
  </div>

  <!-- ================= PERCEPCIONES ================= -->
  <div class="border rounded-xl overflow-hidden">
    <div class="bg-green-300 font-bold text-center py-2">PERCEPCIONES</div>
    <table class="w-full border text-sm">
      <tr>
        <td class="td">SALARIO DIARIO</td>
        <td class="td">$ <input v-model.number="salarioBase" type="number" class="input inline w-24" /></td>
      </tr>
      <tr>
        <td class="td">(x) DÍAS TRABAJADOS</td>
        <td class="td"><input v-model.number="diasTrabajados" type="number" class="input inline w-24" /></td>
      </tr>
      <tr class="font-bold bg-gray-100">
        <td class="td">TOTAL PERCEPCIONES</td>
        <td class="td">$ {{ totalPercepciones.toFixed(2) }}</td>
      </tr>
    </table>
  </div>

  <!-- ================= FACTOR INTEGRACIÓN ================= -->
  <div class="border rounded-xl overflow-hidden">
    <div class="bg-blue-700 text-white font-bold text-center py-2">FACTOR DE INTEGRACIÓN</div>
    <table class="w-full border text-sm">
      <tr><td class="td">PROPORCIÓN AGUINALDO</td><td class="td">{{ proporcionAguinaldo.toFixed(6) }}</td></tr>
      <tr><td class="td">PROPORCIÓN VACACIONES</td><td class="td">{{ proporcionVacaciones.toFixed(6) }}</td></tr>
      <tr class="font-bold bg-gray-100"><td class="td">FACTOR</td><td class="td">{{ factorIntegracion.toFixed(6) }}</td></tr>
    </table>
  </div>

  <!-- ================= VALES ================= -->
  <div class="border rounded-xl overflow-hidden">
    <div class="bg-blue-500 text-white font-bold text-center py-2">VALES DE DESPENSA</div>
    <table class="w-full border text-sm">
      <tr><td class="td">VALES DIARIOS</td><td class="td">${{ valesDiarios.toFixed(2) }}</td></tr>
      <tr><td class="td">EXENTO</td><td class="td">${{ valesExentos.toFixed(2) }}</td></tr>
      <tr class="font-bold bg-gray-100"><td class="td">GRAVADO</td><td class="td">${{ valesGravados.toFixed(2) }}</td></tr>
    </table>
  </div>

  <!-- ================= SBC ================= -->
  <div class="border rounded-xl overflow-hidden">
    <div class="bg-indigo-700 text-white font-bold text-center py-2">SALARIO BASE DE COTIZACIÓN</div>
    <table class="w-full border text-sm">
      <tr><td class="td">SBC SIN VALES</td><td class="td">${{ sbcSinVales.toFixed(2) }}</td></tr>
      <tr><td class="td">VALES GRAVADOS</td><td class="td">${{ valesGravados.toFixed(2) }}</td></tr>
      <tr class="font-bold bg-gray-100"><td class="td">SBC CON VALES</td><td class="td">${{ sbcConVales.toFixed(2) }}</td></tr>
    </table>
  </div>

  <!-- ================= CUOTAS IMSS ================= -->
  <div class="border rounded-xl overflow-hidden">
    <div class="bg-green-600 text-white font-bold text-center py-2">CUOTAS IMSS</div>
    <table class="w-full border text-sm">
      <tr><td class="td">BASE IMSS QUINCENAL</td><td class="td">${{ baseIMSSQuincenal.toFixed(2) }}</td></tr>
      <tr><td class="td">EXCEDENTE PATRONAL</td><td class="td">${{ cuotaExcedentePatronal.toFixed(2) }}</td></tr>
      <tr><td class="td">PRESTACIONES DINERO</td><td class="td">${{ prestacionesDinero.toFixed(2) }}</td></tr>
      <tr><td class="td">PRESTACIONES ESPECIE</td><td class="td">${{ prestacionesEspecie.toFixed(2) }}</td></tr>
      <tr><td class="td">INVALIDEZ Y VIDA</td><td class="td">${{ invalidezVida.toFixed(2) }}</td></tr>
      <tr><td class="td">CESANTÍA Y VEJEZ</td><td class="td">${{ cesantiaVejez.toFixed(2) }}</td></tr>
      <tr class="font-bold bg-green-200 text-lg"><td class="td">TOTAL IMSS</td><td class="td">${{ totalIMSS.toFixed(2) }}</td></tr>
    </table>
  </div>

</div>
</div>
</AuthenticatedLayout>
</template>

<style scoped>
.input {
  padding: 0.3rem;
  border: 1px solid #ccc;
  border-radius: 0.4rem;
}
.td {
  border: 1px solid #ccc;
  padding: 0.4rem;
}
</style>
