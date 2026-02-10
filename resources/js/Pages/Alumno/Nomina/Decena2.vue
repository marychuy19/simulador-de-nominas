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
   PRESTACIONES
============================= */
const diasAguinaldo = ref(15)
const diasVacaciones = ref(12)
const primaVacacional = ref(0.25)
const valesDespensaPorcentaje = ref(0.10)

/* =============================
   UMA Y TOPES
============================= */
const uma = ref(117.31)
const limiteValesUMA = ref(0.40)

const limiteExentoVales = computed(() => uma.value * limiteValesUMA.value)

/* =============================
   SALARIO DIARIO
============================= */
const salarioDiario = computed(() => salarioBase.value)

/* =============================
   FACTOR DE INTEGRACIÓN
============================= */
const proporcionAguinaldo = computed(() => diasAguinaldo.value / 365)
const proporcionVacaciones = computed(() => (diasVacaciones.value * primaVacacional.value) / 365)

const factorIntegracion = computed(() =>
  1 + proporcionAguinaldo.value + proporcionVacaciones.value
)

/* =============================
   SBC SIN VALES
============================= */
const sbcSinVales = computed(() => salarioDiario.value * factorIntegracion.value)

/* =============================
   VALES DE DESPENSA
============================= */
const valesDiarios = computed(() => salarioDiario.value * valesDespensaPorcentaje.value)

const valesExentos = computed(() =>
  Math.min(valesDiarios.value, limiteExentoVales.value)
)

const valesGravados = computed(() =>
  Math.max(valesDiarios.value - valesExentos.value, 0)
)

/* =============================
   SBC CON VALES
============================= */
const sbcConVales = computed(() => sbcSinVales.value + valesGravados.value)

/* =============================
   BASE MENSUAL PARA CUOTAS
============================= */
const diasMes = ref(10)

const baseMensualIMSS = computed(() => sbcConVales.value * diasMes.value)

/* =============================
   CUOTAS IMSS – EXCEDENTE
============================= */

// Excedente sobre 3 UMA
const tresUMA = computed(() => uma.value * 3)

const excedenteSBC = computed(() =>
  Math.max(sbcConVales.value - tresUMA.value, 0)
)

const diferenciaSBC = computed(() => {
  if (sbcConVales.value < tresUMA.value) {
    return 0
  }
  return sbcConVales.value  - tresUMA.value
})

const calculoExcedente = computed(() => {
  return diferenciaSBC.value * sbcConVales.value
})

const excedentePatronal = 0.004 // 0.4000%

const importeExcedente = computed(() => {
  return calculoExcedente.value * excedentePatronal
})

const excetePatronal = computed(() => {
  return sbcConVales.value * diasMes.value
})

const prestacionesDinero = computed(() => {
  return excetePatronal.value * 0.0025
})

const prestacionesEspecie = computed(() => {
  return excetePatronal.value * 0.00375
})

const invalidezVida = computed(() => {
  return excetePatronal.value * 0.00625
})

const cesantiaVejez = computed(() => {
  return excetePatronal.value * 0.01125
})

const totalIMSS = computed(() => {
  return (
    importeExcedente.value +
    prestacionesDinero.value +
    prestacionesEspecie.value +
    invalidezVida.value +
    cesantiaVejez.value
  )
})


/* =============================
   CUOTAS IMSS – PATRÓN
============================= */

// Enfermedades y Maternidad
const cuotaFijaPatron = computed(() => uma.value * 0.204)
const excedentePatron = computed(() => excedenteSBC.value * 0.011)
const prestacionesDineroPatron = computed(() => sbcConVales.value * 0.007)
const gastosMedicosPatron = computed(() => sbcConVales.value * 0.0105)

// Invalidez y Vida
const invalidezVidaPatron = computed(() => sbcConVales.value * 0.0175)

// Guarderías
const guarderiasPatron = computed(() => sbcConVales.value * 0.01)

// Retiro
const retiroPatron = computed(() => sbcConVales.value * 0.02)

// Cesantía y Vejez
const cesantiaPatron = computed(() => sbcConVales.value * 0.0315)

/* =============================
   CUOTAS IMSS – TRABAJADOR
============================= */

const excedenteObrero = computed(() => excedenteSBC.value * 0.004)
const prestacionesDineroObrero = computed(() => sbcConVales.value * 0.0025)
const gastosMedicosObrero = computed(() => sbcConVales.value * 0.00375)
const invalidezVidaObrero = computed(() => sbcConVales.value * 0.00625)
const cesantiaObrero = computed(() => sbcConVales.value * 0.01125)



/* =============================
   TOTALES IMSS
============================= */

const totalPatronIMSS = computed(() =>
  cuotaFijaPatron.value +
  excedentePatron.value +
  prestacionesDineroPatron.value +
  gastosMedicosPatron.value +
  invalidezVidaPatron.value +
  guarderiasPatron.value +
  retiroPatron.value +
  cesantiaPatron.value
)

const totalObreroIMSS = computed(() =>
  excedenteObrero.value +
  prestacionesDineroObrero.value +
  gastosMedicosObrero.value +
  invalidezVidaObrero.value +
  cesantiaObrero.value
)

/* =============================
   GUARDAR
============================= */
const guardar = async () => {
  try {
    await axios.post('/alumno/nomina/guardar-nomina', {
      empleado_id: empleado.value,
      diasAguinaldo: diasAguinaldo.value,
      diasVacaciones: diasVacaciones.value,
      primaVacacional: primaVacacional.value,
      valesDespensaPorcentaje: valesDespensaPorcentaje.value,
      salarioDiario: salarioDiario.value,
      proporcionAguinaldo: proporcionAguinaldo.value,
      proporcionVacaciones: proporcionVacaciones.value,
      sbcSinVales: sbcSinVales.value,
      valesGravados: valesGravados.value,
      sbcConVales:sbcConVales.value,
      totalIMSS: totalIMSS.value
    })

    router.visit('/alumno/nomina/recibo')
  } catch (e) {
    console.error('Error al guarda:', e)
  }
}


</script>

<template>
  <Head title="Cálculo SBC" />

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
  <td class="td">
    <div class="flex items-center gap-2">
      <span class="font-semibold">$</span>
      <input
        v-model.number="salarioBase"
        type="number"
        class="input-sm"
      />
    </div>
  </td>
</tr>

<tr>
  <td class="td">(x) DÍAS TRABAJADOS</td>
  <td class="td">
    <input
      v-model.number="diasTrabajados"
      type="number"
      class="input-sm"
    />
  </td>
</tr>

            <tr class="font-bold bg-gray-100">
              <td class="td">TOTAL DE PERCEPCIONES</td>
              <td class="td">$ {{ totalPercepciones.toFixed(2) }}</td>
            </tr>
          </table>
        </div>

      <!-- PRESTACIONES -->
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="titulo-verde">PRESTACIONES</div>
        <table class="tabla">
          <tr><td>Aguinaldo (días)</td><td><input v-model.number="diasAguinaldo" type="number" class="input-sm" /></td></tr>
          <tr><td>Vacaciones (días)</td><td><input v-model.number="diasVacaciones" type="number" class="input-sm" /></td></tr>
          <tr><td>Prima vacacional (%)</td><td><input v-model.number="primaVacacional" step="0.01" type="number" class="input-sm" /></td></tr>
          <tr><td>Vales de despensa (%)</td><td><input v-model.number="valesDespensaPorcentaje" step="0.01" type="number" class="input-sm" /></td></tr>
        </table>
      </div>

      <!-- FACTOR DE INTEGRACIÓN -->
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="titulo-azul">FACTOR DE INTEGRACIÓN</div>
        <table class="tabla">
          <tr><td>Salario diario</td><td>$ {{ salarioDiario.toFixed(2) }}</td></tr>
          <tr><td>Proporción diaria aguinaldo</td><td>{{ proporcionAguinaldo.toFixed(4) }}</td></tr>
          <tr><td>Proporción diaria vacaciones</td><td>{{ proporcionVacaciones.toFixed(4) }}</td></tr>
          <tr class="resaltado"><td>Factor de integración</td><td>{{ factorIntegracion.toFixed(4) }}</td></tr>
        </table>
      </div>

      <!-- SBC SIN VALES -->
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="titulo-azul">SBC SIN VALES</div>
        <table class="tabla">
          <tr><td>SBC sin vales</td><td>$ {{ sbcSinVales.toFixed(2) }}</td></tr>
        </table>
      </div>

      <!-- VALES -->
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="titulo-morado">VALES DE DESPENSA</div>
        <table class="tabla">
          <tr><td>UMA</td><td>$ {{ uma }}</td></tr>
        <tr>
  <td>Límite exento vales</td>
  <td class="font-semibold text-gray-700">40%</td>
</tr>

<tr>
  <td>Exento permitido</td>
  <td>$ {{ limiteExentoVales.toFixed(2) }}</td>
</tr>

          <tr><td>Vales diarios</td><td>$ {{ valesDiarios.toFixed(2) }}</td></tr>
       <tr>
  <td>Exedente</td>
  <td class="font-semibold text-gray-700">46.924</td>
</tr>
          <tr class="resaltado"><td>Vales gravados</td><td>$ {{ valesGravados.toFixed(2) }}</td></tr>
        </table>
      </div>

      <!-- SBC CON VALES -->
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="titulo-rojo">SBC CON VALES</div>
        <table class="tabla">
          <tr class="resaltado"><td>SBC final</td><td>$ {{ sbcConVales.toFixed(2) }}</td></tr>
        </table>
      </div>

      <!-- EXCEDENTE PATRONAL -->
<div class="bg-white rounded-xl shadow overflow-hidden">
  <div class="titulo-azul">EXCEDENTE PATRONAL</div>
  <table class="tabla">
    <tr><td>Base mensual IMSS</td><td>$ {{ baseMensualIMSS.toFixed(2) }}</td></tr>
    <tr><td>UMA</td><td>$ {{ uma }}</td></tr>
    <tr><td>3 UMA</td><td>$ {{ tresUMA.toFixed(2) }}</td></tr>
    <tr><td>SBC final</td><td>$ {{ sbcConVales.toFixed(2) }}</td></tr>
    <tr>
  <td>Diferencia</td>
  <td>$ {{ diferenciaSBC.toFixed(2) }}</td>
</tr>
 <tr><td>Días trabajados</td><td><input v-model.number="diasMes" type="number" class="input-sm" /></td></tr>
  <tr>
  <td>Calculo</td>
  <td>$ {{ calculoExcedente.toFixed(2) }}</td>
</tr>
<tr>
  <td>Excedente patronal</td>
  <td class="font-semibold text-gray-700">0.4000%</td>
</tr>

    <tr class="resaltado">
  <td>Importe</td>
  <td>$ {{ importeExcedente.toFixed(2) }}</td>
</tr>

  </table>
</div>

<!-- CUOTAS IMSS -->
<div class="bg-white rounded-xl shadow overflow-hidden">
  <div class="titulo-azul">CUOTAS IMSS</div>

  <table class="tabla text-xs">
    <tr class="font-bold bg-gray-100">
      <td>Concepto</td>
      <td>Porcentaje</td>
      <td></td>
      <td></td>
    </tr>

       <tr>
      <td>Excedente patronal</td>
      <td>0.4000%</td>
      <td>$ {{ excetePatronal.toFixed(2) }}</td>
      <td>$ {{ importeExcedente.toFixed(2) }}</td>
    </tr>

    <tr>
      <td>Prestaciones de dinero</td>
      <td>0.25%</td>
       <td>$ {{ excetePatronal.toFixed(2) }}</td>
       <td>$ {{ prestacionesDinero.toFixed(2) }}</td>
    </tr>

    <tr>
      <td>Prestaciones en especie</td>
      <td>0.375%</td>
      <td>$ {{ excetePatronal.toFixed(2) }}</td>
      <td>$ {{ prestacionesEspecie.toFixed(2) }}</td>
    </tr>

    <tr>
      <td>Invalidez y vida</td>
      <td>0.625%</td>
      <td>$ {{ excetePatronal.toFixed(2) }}</td>
      <td>$ {{ invalidezVida.toFixed(2) }}</td>
    </tr>

    <tr>
      <td>Cesantia y vejez</td>
      <td>1.125%</td>
      <td>$ {{ excetePatronal.toFixed(2) }}</td>
      <td>$ {{ cesantiaVejez.toFixed(2) }}</td>
    </tr>

  <tr class="resaltado">
  <td>Suma de datos IMSS</td>
  <td>-</td>
  <td></td>
  <td>$ {{ totalIMSS.toFixed(2) }}</td>
</tr>
  </table>
</div>

<!-- BOTÓN GUARDAR -->
        <button
          @click="guardar"
          class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-xl w-full">
          Factura de nomina
        </button>


    </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.input { @apply border rounded-lg px-3 py-2 w-full }
.input-sm { @apply border rounded px-2 py-1 w-32 }
.tabla { @apply w-full text-sm border }
.tabla td { @apply border px-3 py-2 }
.titulo-verde { @apply bg-green-200 text-center font-bold py-2 }
.titulo-azul { @apply bg-blue-700 text-white text-center font-bold py-2 }
.titulo-morado { @apply bg-purple-600 text-white text-center font-bold py-2 }
.titulo-rojo { @apply bg-red-600 text-white text-center font-bold py-2 }
.resaltado { @apply font-bold bg-gray-100 }

.input {
  padding: 0.3rem;
  border: 1px solid #ccc;
  border-radius: 0.4rem;
}
.td {
  border: 1px solid #ccc;
  padding: 0.4rem;
}

.input-sm {
  width: 90px;
  padding: 0.25rem 0.4rem;
  border: 1px solid #ccc;
  border-radius: 0.4rem;
  text-align: right;
}

</style>