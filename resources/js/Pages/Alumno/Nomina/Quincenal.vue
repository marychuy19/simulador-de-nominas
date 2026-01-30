<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

/* =============================
   CONTROL MODAL TARIFA
============================= */
const showTarifaModal = ref(false)

/* =============================
   DATOS GENERALES
============================= */
const empresa = ref('')
const empleado = ref('')

/* =============================
   PERCEPCIONES (EXCEL)
============================= */
const salarioBase = ref(300)        // SALARIO BASE
const diasTrabajados = ref(15)      // DÍAS TRABAJADOS

const totalPercepciones = computed(() =>
  salarioBase.value * diasTrabajados.value
)

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
   CÁLCULO ISR (EXCEL)
============================= */
const filaISR = computed(() =>
  tablaISR.find(f =>
    totalPercepciones.value >= f.li &&
    totalPercepciones.value <= f.ls
  )
)

const excedente = computed(() =>
  filaISR.value
    ? totalPercepciones.value - filaISR.value.li
    : 0
)

const isrExcedente = computed(() =>
  filaISR.value
    ? excedente.value * (filaISR.value.porcentaje / 100)
    : 0
)

const isrDeterminado = computed(() =>
  filaISR.value
    ? filaISR.value.cuota + isrExcedente.value
    : 0
)

/* =============================
   SUBSIDIO PARA EL EMPLEO
============================= */
const uma = ref(117.31)
const porcentajeSubsidio = ref(15.02)
const topeSubsidio = ref(535.65)

const umaDiaria = computed(() =>
  uma.value * (porcentajeSubsidio.value / 100)
)

const subsidioAplicable = computed(() =>
  Math.min(umaDiaria.value, topeSubsidio.value)
)

const subsidioPeriodo = computed(() =>
  subsidioAplicable.value * diasTrabajados.value
)

/* =============================
   ISR A RETENER
============================= */
const isrRetener = computed(() =>
  Math.max(isrDeterminado.value - subsidioPeriodo.value, 0)
)
</script>

<template>
  <Head title="Simulador de Nómina" />

  <AuthenticatedLayout>
    <div class="max-w-6xl mx-auto p-6 space-y-6">

      <!-- DATOS GENERALES -->
      <div class="grid grid-cols-2 gap-4">
        <input v-model="empresa" class="input bg-gray-100" placeholder="Nombre de la empresa" />
        <input v-model="empleado" class="input bg-gray-100" placeholder="Nombre del empleado" />
      </div>

      <!-- PERCEPCIONES -->
      <div class="border rounded-xl overflow-hidden">
        <div class="bg-green-300 font-bold text-center py-2">
          PERCEPCIONES
        </div>

        <table class="w-full border text-sm">
          <tr>
            <td class="td">SALARIO BASE</td>
            <td class="td">$ <input v-model.number="salarioBase" type="number" class="input inline w-24" /></td>
          </tr>
          <tr>
            <td class="td">(x) DÍAS TRABAJADOS</td>
            <td class="td">
              <input v-model.number="diasTrabajados" type="number" class="input inline w-24" />
            </td>
          </tr>
          <tr class="font-bold bg-gray-100">
            <td class="td">TOTAL DE PERCEPCIONES</td>
            <td class="td">$ {{ totalPercepciones.toFixed(2) }}</td>
          </tr>
        </table>
      </div>

      <!-- ISR Y SUBSIDIO -->
      <div class="grid grid-cols-2 gap-6">

        <!-- ISR -->
        <div>
          <div class="bg-blue-700 text-white font-bold text-center py-1">
            CÁLCULO DEL ISR
          </div>

          <table class="w-full border text-sm">
            <tr><td class="td">Base del ISR</td><td class="td">$ {{ totalPercepciones.toFixed(2) }}</td></tr>
            <tr><td class="td">Límite inferior</td><td class="td">$ {{ filaISR?.li ?? 0 }}</td></tr>
            <tr><td class="td">Excedente</td><td class="td">$ {{ excedente.toFixed(2) }}</td></tr>
            <tr><td class="td">Tasa ISR</td><td class="td">{{ filaISR?.porcentaje ?? 0 }} %</td></tr>
            <tr><td class="td">ISR excedente</td><td class="td">$ {{ isrExcedente.toFixed(2) }}</td></tr>
            <tr><td class="td">Cuota fija</td><td class="td">$ {{ filaISR?.cuota ?? 0 }}</td></tr>
            <tr class="font-bold bg-gray-100">
              <td class="td">ISR determinado</td>
              <td class="td">$ {{ isrDeterminado.toFixed(2) }}</td>
            </tr>
          </table>

          <button
            @click="showTarifaModal = true"
            class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg w-full"
          >
            Ver tarifa ISR
          </button>
        </div>

        <!-- SUBSIDIO -->
        <div>
          <div class="bg-blue-500 text-white font-bold text-center py-1">
            SUBSIDIO PARA EL EMPLEO
          </div>

          <table class="w-full border text-sm">
            <tr><td class="td">UMA 2026</td><td class="td">$ {{ uma }}</td></tr>
            <tr><td class="td">% de subsidio</td><td class="td">{{ porcentajeSubsidio }} %</td></tr>
            <tr><td class="td">UMA diaria</td><td class="td">$ {{ umaDiaria.toFixed(2) }}</td></tr>
            <tr><td class="td">Tope de subsidio</td><td class="td">$ {{ topeSubsidio }}</td></tr>
            <tr class="font-bold bg-gray-100">
              <td class="td">Subsidio del periodo</td>
              <td class="td">$ {{ subsidioPeriodo.toFixed(2) }}</td>
            </tr>
          </table>
        </div>
      </div>

      <!-- RESULTADO FINAL -->
      <div class="bg-indigo-700 text-white text-center p-6 rounded-xl">
        <p class="text-lg font-bold">ISR A RETENER</p>
        <p class="text-4xl font-bold mt-2">$ {{ isrRetener.toFixed(2) }}</p>
      </div>
    </div>

    <!-- MODAL TARIFA ISR -->
    <div
      v-if="showTarifaModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white w-full max-w-5xl p-6 rounded-2xl">
        <div class="flex justify-between mb-4">
          <h2 class="font-bold text-xl">Tarifa ISR Diario 2026</h2>
          <button @click="showTarifaModal = false" class="text-red-600 font-bold">X</button>
        </div>

        <table class="w-full border text-sm text-center">
          <thead class="bg-gray-100">
            <tr>
              <th class="td">Límite inferior</th>
              <th class="td">Límite superior</th>
              <th class="td">Cuota fija</th>
              <th class="td">% ISR</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(fila, i) in tablaISR"
              :key="i"
              :class="fila === filaISR ? 'bg-yellow-200 font-bold' : ''"
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
  padding: 0.3rem;
  border: 1px solid #ccc;
  border-radius: 0.4rem;
}
.td {
  border: 1px solid #ccc;
  padding: 0.4rem;
}
</style>
