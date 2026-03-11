<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'


const mostrarNotaTopeSubsidio = ref(false)
const mostrarNotaTopeSubsidioPeriodo = ref(false)

const props = defineProps({
  configNomina: {
    type: Object,
    default: () => ({})
  },
  tablaIsr: {
    type: Array,
    default: () => []
  }
})

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
const diasTrabajados = ref(15)

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
   AL CAMBIAR EMPRESA -> CARGAR EMPLEADOS QUINCENALES DE ESA EMPRESA
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
    const resEmpleados = await axios.get('/alumno/empleados-quincenales', {
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
   TABLA ISR QUINCENAL 
============================= */
const tablaISR = computed(() =>
  (props.tablaIsr || []).map((fila) => ({
    li: Number(fila.limite_inferior ?? 0),
    ls: fila.limite_superior === null ? Infinity : Number(fila.limite_superior ?? 0),
    cuota: Number(fila.cuota_fija ?? 0),
    porcentaje: Number(fila.porcentaje ?? 0),
  }))
)

/* =============================
   CÁLCULO ISR
============================= */
const filaISR = computed(() =>
  tablaISR.value.find(f =>
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
const salarioMinimo = computed(() =>
  Number(props.configNomina?.salario_minimo ?? 0)
)
const uma = computed(() => Number(props.configNomina?.uma ?? 0))
const porcentajeSubsidio = computed(() => Number(props.configNomina?.subsidio_empleo ?? 0))
const topeSubsidio = computed(() => umaDiaria.value * 30.4)
const topeSubsidio2026 = computed(() => Number(props.configNomina?.limite_ingreso_subsidio ?? 0))

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
  salarioBase.value === salarioMinimo.value
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

    // ✅ AQUÍ está el cambio: mandamos lo seleccionado a quincenal2
    router.visit('/alumno/nomina/quincenal2', {
      data: {
        empresa: empresa.value,
        empleado: empleado.value,
        salario_base: salarioBase.value,
        dias_trabajados: diasTrabajados.value,
      },
      method: 'get',
      preserveState: false,
      replace: true,
    })
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
      <tr @click="mostrarNotaTopeSubsidio = !mostrarNotaTopeSubsidio" class="cursor-pointer">
  <td class="td">Tope de subsidio del año actual</td>
  <td class="td">
    $ {{ topeSubsidio2026.toLocaleString('es-MX', { minimumFractionDigits: 2 }) }}
  </td>
</tr>
<tr v-if="mostrarNotaTopeSubsidio">
  <td colspan="2" class="text-sm text-gray-600 px-2 pb-2">
  El subcidio para el empleo se establece en el decreto publicado en el Diario Oficial de la Federacion el 31 de diciembre,
    donde se actualiza el porcentaje de la UMA y el limite de ingresos mensuales.
  </td>
</tr>
              <tr><td class="td">UMA del año actual</td><td class="td">$ {{ uma }}</td></tr>
              <tr><td class="td">% de subsidio</td><td class="td">{{ porcentajeSubsidio }} %</td></tr>
              <tr><td class="td">UMA diaria</td><td class="td">$ {{ umaDiaria.toFixed(2) }}</td></tr>
             <tr @click="mostrarNotaTopeSubsidioPeriodo = !mostrarNotaTopeSubsidioPeriodo" class="cursor-pointer">
  <td class="td">Tope de subsidio</td>
  <td class="td">$ {{ topeSubsidio }}</td>
</tr>
<tr v-if="mostrarNotaTopeSubsidioPeriodo">
  <td colspan="2" class="text-sm text-gray-600 px-2 pb-2">
    El subsidio maximo mensual para el empledo es de:
  </td>
</tr>
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
          <h2 class="text-xl font-bold text-blue-700">Tarifa ISR (Quincenal)</h2>
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