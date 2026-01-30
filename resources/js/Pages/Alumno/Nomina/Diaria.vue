<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

/* =============================
   DATOS GENERALES
============================= */
const empresa = ref('')
const empleado = ref('')

/* =============================
   SALARIO
============================= */
const salarioMensualBruto = ref(15000)
const diasMes = ref(15)

const salarioDiario = computed(() => {
  if (diasMes.value <= 0) return 0
  return salarioMensualBruto.value / diasMes.value
})

/* =============================
   TABLA ISR DIARIA 2026
============================= */
const tablaISR = [
  { li: 0.01, ls: 27.78, cuota: 0.00, porcentaje: 1.92 },
  { li: 27.79, ls: 235.81, cuota: 0.53, porcentaje: 6.40 },
  { li: 235.82, ls: 414.41, cuota: 13.85, porcentaje: 10.88 },
  { li: 414.42, ls: 481.73, cuota: 33.28, porcentaje: 16.00 },
  { li: 481.74, ls: 576.76, cuota: 44.05, porcentaje: 17.92 },
  { li: 576.77, ls: 1163.25, cuota: 61.08, porcentaje: 21.36 },
  { li: 1163.26, ls: 1833.44, cuota: 186.35, porcentaje: 23.52 },
  { li: 1833.45, ls: 3500.35, cuota: 343.98, porcentaje: 30.00 },
  { li: 3500.36, ls: 4667.13, cuota: 844.05, porcentaje: 32.00 },
  { li: 4667.14, ls: 14001.38, cuota: 1217.42, porcentaje: 34.00 },
]

const filaISR = computed(() =>
  tablaISR.find(f => salarioDiario.value >= f.li && salarioDiario.value <= f.ls)
)

const excedente = computed(() =>
  filaISR.value ? salarioDiario.value - filaISR.value.li : 0
)

const isrDeterminado = computed(() =>
  filaISR.value
    ? filaISR.value.cuota +
      excedente.value * (filaISR.value.porcentaje / 100)
    : 0
)

/* =============================
   SUBSIDIO AL EMPLEO
============================= */
const uma = ref(117.31)
const porcentajeSubsidio = ref(15)
const topeSubsidio = ref(535.65)

const umaDiaria = computed(() =>
  uma.value * (porcentajeSubsidio.value / 100)
)

const subsidioAplicable = computed(() =>
  Math.min(umaDiaria.value, topeSubsidio.value)
)

/* =============================
   ISR FINAL
============================= */
const isrRetener = computed(() => {
  const diario = Math.max(isrDeterminado.value - subsidioAplicable.value, 0)
  return diario * diasMes.value
})
</script>

<template>
  <Head title="Cálculo de Nómina" />

  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto p-6 space-y-6">

      <!-- DATOS GENERALES -->
      <div class="grid grid-cols-2 gap-4">
        <input v-model="empresa" class="input bg-gray-200" placeholder="Nombre de la empresa" />
        <input v-model="empleado" class="input bg-gray-200" placeholder="Nombre del empleado" />
      </div>

      <!-- DETERMINACIÓN SALARIO -->
      <div class="border rounded-xl overflow-hidden">
        <div class="bg-green-200 text-center font-bold py-2">
          DETERMINACIÓN DEL SALARIO DIARIO
        </div>

        <table class="w-full border text-sm">
          <tr>
            <td class="td">SALARIO MENSUAL BRUTO</td>
            <td class="td">
              $ <input v-model.number="salarioMensualBruto" type="number" class="input inline w-32" />
            </td>
          </tr>
          <tr>
            <td class="td">(/) DÍAS DEL MES</td>
            <td class="td">
              <input v-model.number="diasMes" type="number" class="input inline w-24" />
            </td>
          </tr>
          <tr class="font-bold">
            <td class="td">SALARIO DIARIO</td>
            <td class="td">$ {{ salarioDiario.toFixed(2) }}</td>
          </tr>
        </table>
      </div>

      <!-- ISR Y SUBSIDIO -->
      <div class="grid grid-cols-2 gap-6">

        <!-- ISR -->
        <div>
          <div class="bg-blue-700 text-white text-center font-bold py-1">
            CÁLCULO DEL ISR
          </div>
          <table class="w-full border text-sm">
            <tr><td class="td">Base ISR</td><td class="td">$ {{ salarioDiario.toFixed(2) }}</td></tr>
            <tr><td class="td">Límite inferior</td><td class="td">$ {{ filaISR?.li ?? 0 }}</td></tr>
            <tr><td class="td">Excedente</td><td class="td">$ {{ excedente.toFixed(2) }}</td></tr>
            <tr><td class="td">% ISR</td><td class="td">{{ filaISR?.porcentaje ?? 0 }} %</td></tr>
            <tr><td class="td">Cuota fija</td><td class="td">$ {{ filaISR?.cuota ?? 0 }}</td></tr>
            <tr class="font-bold bg-gray-100">
              <td class="td">ISR determinado</td>
              <td class="td">$ {{ isrDeterminado.toFixed(2) }}</td>
            </tr>
          </table>
        </div>

        <!-- SUBSIDIO -->
        <div>
          <div class="bg-blue-500 text-white text-center font-bold py-1">
            SUBSIDIO PARA EL EMPLEO
          </div>
          <table class="w-full border text-sm">
            <tr><td class="td">UMA 2026</td><td class="td">$ {{ uma }}</td></tr>
            <tr><td class="td">% Subsidio</td><td class="td">{{ porcentajeSubsidio }} %</td></tr>
            <tr><td class="td">UMA diaria</td><td class="td">$ {{ umaDiaria.toFixed(2) }}</td></tr>
            <tr><td class="td">Tope subsidio</td><td class="td">$ {{ topeSubsidio }}</td></tr>
            <tr class="font-bold bg-gray-100">
              <td class="td">Subsidio aplicable</td>
              <td class="td">$ {{ subsidioAplicable.toFixed(2) }}</td>
            </tr>
          </table>
        </div>
      </div>

      <!-- RESULTADO -->
      <div class="text-center bg-indigo-700 text-white p-6 rounded-xl">
        <p class="text-lg font-semibold">ISR A RETENER</p>
        <p class="text-4xl font-bold mt-2">$ {{ isrRetener.toFixed(2) }}</p>
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
