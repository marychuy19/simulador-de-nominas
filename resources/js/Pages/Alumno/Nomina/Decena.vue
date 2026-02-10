<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'

/* =============================
   LISTAS DESDE BASE DE DATOS
============================= */
const empresas = ref([])
const empleados = ref([])

/* =============================
   CONTROL MODAL TARIFA
============================= */
const showTarifaModal = ref(false)

/* =============================
   DATOS GENERALES
============================= */
const empresa = ref('')
const empleado = ref('')
const tipoSalario = ref('')
const tipoPago = ref('')
const fechaIngreso = ref('')

/* =============================
   PERCEPCIONES
============================= */
const salarioBase = ref(0)
const diasTrabajados = ref(10)

const totalPercepciones = computed(() =>
  salarioBase.value * diasTrabajados.value
)

/* =============================
   CARGAR EMPRESAS AL INICIAR
============================= */
onMounted(async () => {
  try {
    const resEmpresas = await axios.get('/alumno/empresas-lista')
    empresas.value = resEmpresas.data
  } catch (e) {
    console.error('Error cargando empresas:', e)
    empresas.value = []
  }
})

/* =============================
   AL CAMBIAR EMPRESA -> CARGAR EMPLEADOS SEMANAL DE ESA EMPRESA
============================= */
watch(empresa, async (empresaId) => {
  // reset
  empleado.value = ''
  empleados.value = []
  tipoSalario.value = ''
  tipoPago.value = ''
  fechaIngreso.value = ''
  salarioBase.value = 300

  if (!empresaId) return

  try {
    const resEmpleados = await axios.get('/alumno/empleados-10_dias', {
      params: { empresa_id: empresaId }
    })
    empleados.value = resEmpleados.data
  } catch (e) {
    console.error('Error cargando empleados:', e)
    empleados.value = []
  }
})

/* =============================
   AUTOCOMPLETAR DATOS DEL EMPLEADO
============================= */
watch(empleado, (id) => {
  const emp = empleados.value.find(e => String(e.id) === String(id))
  if (emp) {
    tipoSalario.value = emp.tipo_salario ?? ''
    tipoPago.value = emp.periodo_salario ?? ''
    fechaIngreso.value = emp.fecha_ingreso ?? ''
    salarioBase.value = Number(emp.salario ?? 0)
  }
})

/* =============================
   TABLA ISR DECENAL 2026
============================= */
const tablaISR = [
  { li: 0.01,     ls: 277.80,    cuota: 0.00,     porcentaje: 1.92 },
  { li: 277.81,   ls: 2358.10,   cuota: 5.30,     porcentaje: 6.40 },
  { li: 2358.11,  ls: 4144.10,   cuota: 138.50,   porcentaje: 10.88 },
  { li: 4144.11,  ls: 4817.30,   cuota: 332.80,   porcentaje: 16.00 },
  { li: 4817.31,  ls: 5767.60,   cuota: 440.50,   porcentaje: 17.92 },
  { li: 5767.61,  ls: 11632.50,  cuota: 610.80,   porcentaje: 21.36 },
  { li: 11632.51, ls: 18334.40,  cuota: 1863.50,  porcentaje: 23.52 },
  { li: 18334.41, ls: 35003.50,  cuota: 3439.80,  porcentaje: 30.00 },
  { li: 35003.51, ls: 46671.30,  cuota: 8440.50,  porcentaje: 32.00 },
  { li: 46671.31, ls: 140013.80, cuota: 12174.20, porcentaje: 34.00 },
  { li: 140013.81, ls: Infinity, cuota: 43910.70, porcentaje: 35.00 },
]


/* =============================
   CÁLCULO ISR
============================= */
const filaISR = computed(() =>
  tablaISR.find(f =>
    totalPercepciones.value >= f.li &&
    totalPercepciones.value <= f.ls
  )
)

const excedente = computed(() =>
  filaISR.value ? totalPercepciones.value - filaISR.value.li : 0
)

const isrExcedente = computed(() =>
  filaISR.value ? excedente.value * (filaISR.value.porcentaje / 100) : 0
)

const isrDeterminado = computed(() =>
  filaISR.value ? filaISR.value.cuota + isrExcedente.value : 0
)

/* =============================
   SUBSIDIO PARA EL EMPLEO
============================= */
const uma = ref(117.31)
const porcentajeSubsidio = ref(15.02)
const topeSubsidio = ref(535.65)
const topeSubsidio2026 = ref(11492.66)

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
   CONDICIONALES
============================= */
const aplicaArticulo96 = computed(() =>
  totalPercepciones.value === 3150.40
)

const baseISRporDos = computed(() =>
  totalPercepciones.value * 2
)

const aplicaSubsidio = computed(() =>
  baseISRporDos.value <= topeSubsidio2026.value
)

/* =============================
   ISR A RETENER
============================= */
const isrRetener = computed(() => {
  if (aplicaArticulo96.value) return 0
  if (aplicaSubsidio.value) {
    return Math.max(isrDeterminado.value - subsidioPeriodo.value, 0)
  }
  return isrDeterminado.value
})

/* =============================
   GUARDAR
============================= */
const guardar = async () => {
  try {
    await axios.post('/alumno/nomina/guardar-isr', {
      empleado_id: empleado.value,
      salario_base: salarioBase.value,
      dias_trabajados: diasTrabajados.value,
      total_percepciones: totalPercepciones.value,
      isr_determinado: isrDeterminado.value,
      subsidio_periodo: subsidioPeriodo.value,
      isr_retener: isrRetener.value
    })

    router.visit('/alumno/nomina/decena2')
  } catch (e) {
    console.error('Error al guardar ISR:', e)
  }
}


</script>

<template>
  <Head title="Simulador de Nómina" />
  
  <AuthenticatedLayout>
    <div class="bg-blue-100 min-h-screen">
      <div class="max-w-6xl mx-auto p-6 space-y-6">

        <!-- DATOS GENERALES -->
        <div class="border rounded-xl overflow-hidden">
          <div class="bg-green-300 font-bold text-center py-2">DATOS</div>
          <table class="w-full border text-sm">
            <tr>
              <td class="td font-semibold">EMPRESA</td>
              <td class="td">
                <select v-model="empresa" class="input w-full">
                  <option value="">Seleccione empresa</option>
                  <option v-for="e in empresas" :key="e.id" :value="e.id">
                    {{ e.nombre_razon_social }}
                  </option>
                </select>
              </td>
            </tr>

            <tr>
              <td class="td font-semibold">NOMBRE DEL EMPLEADO</td>
              <td class="td">
                <select v-model="empleado" class="input w-full" :disabled="!empresa">
                  <option value="">Seleccione empleado</option>
                  <option v-for="e in empleados" :key="e.id" :value="e.id">
                    {{ e.nombre_completo }}
                  </option>
                </select>
              </td>
            </tr>

            <tr>
              <td class="td font-semibold">TIPO DE SALARIO</td>
              <td class="td"><input v-model="tipoSalario" class="input w-full" readonly /></td>
            </tr>
            <tr>
              <td class="td font-semibold">TIPO DE PAGO</td>
              <td class="td"><input v-model="tipoPago" class="input w-full" readonly /></td>
            </tr>
            <tr>
              <td class="td font-semibold">FECHA DE INGRESO</td>
              <td class="td"><input v-model="fechaIngreso" type="date" class="input w-full" readonly /></td>
            </tr>
          </table>
        </div>

        <!-- PERCEPCIONES -->
        <div class="border rounded-xl overflow-hidden">
          <div class="bg-green-300 font-bold text-center py-2">PERCEPCIONES</div>

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
    <div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="bg-blue-700 text-white font-bold text-center py-2">
      CÁLCULO DEL ISR
    </div>

    <table class="w-full text-sm">
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
      class="bg-blue-600 text-white px-4 py-2 w-full font-semibold"
    >
      Ver tarifa ISR
    </button>
  </div>

  <!-- SUBSIDIO -->
      <div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="bg-yellow-400 text-black font-bold text-center py-2">
      SUBSIDIO PARA EL EMPLEO
    </div>

    <table class="w-full text-sm">
      <tr>
        <td class="td">Tope de subsidio 2026</td>
        <td class="td">
          $ {{ topeSubsidio2026.toLocaleString('es-MX', { minimumFractionDigits: 2 }) }}
        </td>
      </tr>
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

        <!-- NOTA ARTÍCULO 96 -->
        <div
          v-if="aplicaArticulo96"
          class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-900 p-4 rounded-lg"
        >
          <p class="font-bold mb-1">Artículo 96 de la Ley del ISR</p>
          <p class="text-sm">
            No se efectuará retención a las personas que en el mes únicamente
            perciban un salario mínimo general correspondiente al área geográfica
            del contribuyente.
          </p>
        </div>

        <!-- RESULTADO FINAL -->
        <div class="bg-indigo-700 text-white text-center p-6 rounded-xl">
          <p class="text-lg font-bold">ISR A RETENER</p>
          <p class="text-4xl font-bold mt-2">$ {{ isrRetener.toFixed(2) }}</p>
        </div>

        <!-- BOTÓN GUARDAR -->
        <button
          @click="guardar"
          class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl w-full"
        >
          Guardar y siguiente
        </button>

      </div>
    </div>

    <!-- MODAL TARIFA ISR -->
    <div
      v-if="showTarifaModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded-xl w-full max-w-4xl p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold text-blue-700">Tarifa ISR 2026 (Decenal)</h2>
          <button @click="showTarifaModal = false" class="text-red-600 font-bold text-xl">✕</button>
        </div>

        <table class="w-full border text-sm">
          <thead class="bg-gray-200 font-bold">
            <tr>
              <td class="td">Límite inferior</td>
              <td class="td">Límite superior</td>
              <td class="td">Cuota fija</td>
              <td class="td">%</td>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(fila, i) in tablaISR"
              :key="i"
              :class="filaISR && fila.li === filaISR.li && fila.ls === filaISR.ls ? 'bg-yellow-200 font-bold' : ''"
            >
              <td class="td">$ {{ fila.li.toLocaleString('es-MX', { minimumFractionDigits: 2 }) }}</td>
              <td class="td">
                $ {{
                  fila.ls === Infinity
                    ? 'En adelante'
                    : fila.ls.toLocaleString('es-MX', { minimumFractionDigits: 2 })
                }}
              </td>
              <td class="td">$ {{ fila.cuota.toLocaleString('es-MX', { minimumFractionDigits: 2 }) }}</td>
              <td class="td">{{ fila.porcentaje }} %</td>
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