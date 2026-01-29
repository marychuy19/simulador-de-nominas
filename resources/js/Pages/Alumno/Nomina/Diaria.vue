<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

/* =============================
   CONTROL MODAL
============================= */
const showTarifaModal = ref(false)

/* =============================
   DATOS GENERALES
============================= */
const empresa = ref('')
const empleado = ref('')

/* =============================
   SALARIO
============================= */
const salarioMensual = ref(15000)
const diasMes = ref(15)

const salarioDiario = computed(() => {
  if (diasMes.value <= 0) return 0
  return salarioMensual.value / diasMes.value
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

/* =============================
   CÁLCULO ISR
============================= */
const filaISR = computed(() =>
  tablaISR.find(f =>
    salarioDiario.value >= f.li && salarioDiario.value <= f.ls
  )
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
   SUBSIDIO AL EMPLEO (DINÁMICO)
============================= */
const uma = ref(117.31)
const porcentajeSubsidio = ref(15)
const topeSubsidio = ref(535.65)

const subsidioDiario = computed(() =>
  uma.value * (porcentajeSubsidio.value / 100)
)

const subsidioAplicable = computed(() =>
  Math.min(subsidioDiario.value, topeSubsidio.value)
)

/* =============================
   ISR A RETENER
============================= */
const isrRetener = computed(() =>
  Math.max(isrDeterminado.value - subsidioAplicable.value, 0)
)
</script>

<template>
  <Head title="Nómina diaria" />

  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-b from-blue-50 to-blue-100 py-10">
      <div class="max-w-6xl mx-auto space-y-8">

        <!-- HEADER -->
        <div class="bg-white rounded-xl shadow p-6">
          <h1 class="text-2xl font-bold text-blue-900">
            Cálculo de Nómina Diaria – ISR y Subsidio 2026
          </h1>
          <p class="text-gray-600 mt-1">
            Cálculo de nómina con desglose paso a paso para fines educativos
          </p>
        </div>

        <!-- DATOS GENERALES -->
        <div class="bg-white p-6 rounded-xl shadow grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="font-semibold">Nombre de la empresa</label>
            <input v-model="empresa" class="input" />
          </div>
          <div>
            <label class="font-semibold">Nombre del empleado</label>
            <input v-model="empleado" class="input" />
          </div>
        </div>

        <!-- SALARIO -->
        <div class="bg-white p-6 rounded-xl shadow">
          <h2 class="font-bold text-lg text-blue-800">
            Determinación del salario diario
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
              <label>Salario mensual bruto</label>
              <input v-model.number="salarioMensual" type="number" class="input mt-1" />
            </div>
            <div>
              <label>Días del mes</label>
              <input v-model.number="diasMes" type="number" class="input mt-1" />
            </div>
          </div>

          <div class="mt-4 bg-blue-50 p-4 rounded-lg">
            <p class="font-semibold">
              Salario diario = Salario mensual ÷ Días del mes
            </p>
            <p class="text-2xl font-bold text-blue-900">
              ${{ salarioDiario.toFixed(2) }}
            </p>
          </div>
        </div>

        <!-- ISR -->
        <div class="bg-white p-6 rounded-xl shadow">
          <div class="flex justify-between items-center">
            <h2 class="font-bold text-lg text-red-700">
              Cálculo del ISR diario
            </h2>
            <button
              @click="showTarifaModal = true"
              class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
            >
              Tarifa de salario
            </button>
          </div>

          <table class="w-full mt-4 border text-sm">
            <tr><td class="td">Base ISR</td><td class="td">${{ salarioDiario.toFixed(2) }}</td></tr>
            <tr><td class="td">Límite inferior</td><td class="td">${{ filaISR?.li ?? 0 }}</td></tr>
            <tr><td class="td">Excedente</td><td class="td">${{ excedente.toFixed(2) }}</td></tr>
            <tr><td class="td">Porcentaje</td><td class="td">{{ filaISR?.porcentaje ?? 0 }}%</td></tr>
            <tr><td class="td">Cuota fija</td><td class="td">${{ filaISR?.cuota ?? 0 }}</td></tr>
            <tr class="font-bold bg-red-50">
              <td class="td">ISR determinado</td>
              <td class="td">${{ isrDeterminado.toFixed(2) }}</td>
            </tr>
          </table>
        </div>

        <!-- SUBSIDIO (FUNCIONAL) -->
        <div class="bg-white p-6 rounded-xl shadow">
          <h2 class="font-bold text-lg text-blue-700">
            Subsidio para el empleo
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div>
              <label class="font-semibold">UMA</label>
              <input v-model.number="uma" type="number" step="0.01" class="input mt-1" />
            </div>
            <div>
              <label class="font-semibold">% Subsidio</label>
              <input v-model.number="porcentajeSubsidio" type="number" step="0.01" class="input mt-1" />
            </div>
            <div>
              <label class="font-semibold">Tope subsidio</label>
              <input v-model.number="topeSubsidio" type="number" step="0.01" class="input mt-1" />
            </div>
          </div>

          <table class="w-full mt-4 border text-sm">
            <tr><td class="td">Subsidio diario</td><td class="td">${{ subsidioDiario.toFixed(2) }}</td></tr>
            <tr class="font-bold bg-blue-50">
              <td class="td">Subsidio aplicable</td>
              <td class="td">${{ subsidioAplicable.toFixed(2) }}</td>
            </tr>
          </table>
        </div>

        <!-- RESULTADO -->
        <div class="bg-indigo-600 text-white p-6 rounded-xl shadow text-center">
          <h2 class="font-bold text-xl">ISR a retener</h2>
          <p class="text-4xl font-bold mt-2">
            ${{ isrRetener.toFixed(2) }}
          </p>
        </div>

      </div>
    </div>

    <!-- MODAL TARIFA ISR -->
    <div
      v-if="showTarifaModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white w-full max-w-5xl rounded-xl p-6 max-h-[90vh] overflow-auto">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold text-blue-900">
            Tarifa ISR Diario 2026
          </h2>
          <button @click="showTarifaModal = false" class="text-red-600 font-bold">X</button>
        </div>

        <table class="w-full border text-sm text-center">
          <thead class="bg-gray-100 font-bold">
            <tr>
              <th class="td">Límite inferior</th>
              <th class="td">Límite superior</th>
              <th class="td">Cuota fija</th>
              <th class="td">% excedente</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(fila, i) in tablaISR"
              :key="i"
              :class="fila === filaISR ? 'bg-yellow-100 font-bold' : ''"
            >
              <td class="td">{{ fila.li }}</td>
              <td class="td">{{ fila.ls }}</td>
              <td class="td">{{ fila.cuota }}</td>
              <td class="td">{{ fila.porcentaje }}%</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </AuthenticatedLayout>
</template>

<style scoped>
.input {
  width: 100%;
  padding: 0.6rem;
  border-radius: 0.75rem;
  border: 1px solid #d1d5db;
}
.td {
  border: 1px solid #d1d5db;
  padding: 0.5rem;
}
</style>
