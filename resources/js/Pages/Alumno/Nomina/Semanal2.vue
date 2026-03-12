<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'

const mostrarNotaAguinaldo = ref(false)
const mostrarNotaVacaciones = ref(false)
const mostrarNotaPrima = ref(false)

// ✅ RECIBE LOS DATOS QUE VIENEN DE SEMANAL1 (desde el controlador)
const props = defineProps({
  prefil2: {
    type: Object,
    default: () => ({
      empresa: '',
      empleado: '',
      salario_base: 0,
      dias_trabajados: 1,
    }),
  },
  configNomina: {
    type: Object,
    default: () => ({})
  },
  cuotasImss: {
    type: Object,
    default: () => ({})
  },
  empleados: {
    type: Object,
    default: () => ({})
  },
})


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
const diasTrabajados = ref(7)

const totalPercepciones = computed(() =>
  Number(salarioBase.value || 0) * Number(diasTrabajados.value || 0)
)

// ✅ banderita para evitar que el watch(empresa) te resetee cuando estamos precargando
const isPrefil2ing = ref(true)

/* =============================
   CARGAR EMPRESAS AL INICIAR + APLICAR PREFILL
============================= */
onMounted(async () => {
  try {
    const resEmpresas = await axios.get('/alumno/empresas-lista')
    empresas.value = resEmpresas.data
  } catch (e) {
    console.error('Error cargando empresas:', e)
    empresas.value = []
  }

  // ✅ Aplicamos valores iniciales que vienen de la vista 1
  if (props.prefil2?.empresa) empresa.value = String(props.prefil2.empresa)
  if (props.prefil2?.salario_base != null) salarioBase.value = Number(props.prefil2.salario_base || 0)
  if (props.prefil2?.dias_trabajados != null) diasTrabajados.value = Number(props.prefil2.dias_trabajados || 1)
})

/* =============================
   AL CAMBIAR EMPRESA -> CARGAR EMPLEADOS SEMANAL DE ESA EMPRESA
============================= */
watch(empresa, async (empresaId) => {
  // reset (pero NO cuando venimos precargando)
  empleado.value = ''
  empleados.value = []
  tipoSalario.value = ''
  tipoPago.value = ''
  fechaIngreso.value = ''

  if (!isPrefil2ing.value) {
    salarioBase.value = 300
  }

  if (!empresaId) return

  try {
    const resEmpleados = await axios.get('/alumno/empleados-semanal', {
      params: { empresa_id: empresaId }
    })
    empleados.value = resEmpleados.data

    if (isPrefil2ing.value && props.prefil2?.empleado) {
      empleado.value = String(props.prefil2.empleado)
    }

  } catch (e) {
    console.error('Error cargando empleados:', e)
    empleados.value = []
  } finally {
    isPrefil2ing.value = false
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

  
    if (props.prefil2?.salario_base != null && Number(props.prefil2.salario_base) > 0) {
      salarioBase.value = Number(props.prefil2.salario_base)
    } else {
      salarioBase.value = Number(emp.salario ?? 0)
    }
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
   UMA Y TOPES DESDE CONFIGURACIÓN
============================= */
const uma = computed(() => Number(props.configNomina?.uma ?? 0))
const limiteValesUMA = computed(() => Number(props.configNomina?.limite_vales_despensa ?? 0))

const limiteExentoVales = computed(() => uma.value * limiteValesUMA.value)

/* =============================
   SALARIO DIARIO
============================= */
const salarioDiario = computed(() => Number(salarioBase.value || 0))

/* =============================
   FACTOR DE INTEGRACIÓN
============================= */
const proporcionAguinaldo = computed(() => Number(diasAguinaldo.value || 0) / 365)
const proporcionVacaciones = computed(() =>
  (Number(diasVacaciones.value || 0) * Number(primaVacacional.value || 0)) / 365
)

const factorIntegracion = computed(() =>
  1 + proporcionAguinaldo.value + proporcionVacaciones.value
)


/* =============================
   SBC SIN VALES
============================= */
const sbcSinVales = computed(() => {
  return Number(salarioDiario.value * factorIntegracion.value)
})

/* =============================
   VALES DE DESPENSA
============================= */
const valesDiarios = computed(() =>
  Number(salarioDiario.value || 0) * Number(valesDespensaPorcentaje.value || 0)
)

const limiteExcentoVales = computed(() => Number(props.configNomina?.limite_excento_vales ?? 0))


const limiteExcentoPermtido = computed(() => Number(props.configNomina?.limite_vales_despensa ?? 0))

const valesDespensa = computed(() => {
  const emp = empleados.value.find(e => String(e.id) === String(empleado.value))
  return Number(emp?.vales_despensa ?? 0)
})

const valesGravados = computed(() => {
  const vales = Number(valesDespensa.value || 0)
  const limite = Number(limiteExcentoPermtido.value || 0)

  return Math.max(vales - limite, 0)
})

const valesDiariosGravados = computed(() => {
  const gravados = Number(valesGravados.value || 0)

  if (gravados === 0) {
    return 0
  }

  return gravados / 30
})

/* =============================
   SBC CON VALES
============================= */
const sbcConVales = computed(() => {
  return Number(sbcSinVales.value || 0) + Number(valesDiariosGravados.value || 0)
})

/* =============================
   BASE MENSUAL PARA CUOTAS
============================= */
const diasMes = ref(7)

const baseMensualIMSS = computed(() => sbcConVales.value * Number(diasMes.value || 0))

/* =============================
   CUOTAS IMSS – EXCEDENTE
============================= */
const tresUMA = computed(() => uma.value * 3)

const excedenteSBC = computed(() =>
  Math.max(sbcConVales.value - tresUMA.value, 0)
)

const diferenciaSBC = computed(() => {
  if (sbcConVales.value < tresUMA.value) {
    return 0
  }
  return sbcConVales.value - tresUMA.value
})

const calculoExcedente = computed(() => {
  return diferenciaSBC.value * Number(diasMes.value || 0)
})

const excedentePatronal = computed(() =>
  Number(props.cuotasImss?.excedente_patronal ?? 0)
)

const importeExcedente = computed(() => {
  return calculoExcedente.value * excedentePatronal.value
})

const excetePatronal = computed(() => {
  return sbcConVales.value * Number(diasMes.value || 0)
})

const prestacionesDinero = computed(() => {
  return excetePatronal.value * Number(props.cuotasImss?.prestaciones_dinero ?? 0)
})

const prestacionesEspecie = computed(() => {
  return excetePatronal.value * Number(props.cuotasImss?.prestaciones_especie ?? 0)
})

const invalidezVida = computed(() => {
  return excetePatronal.value * Number(props.cuotasImss?.invalidez_vida ?? 0)
})

const cesantiaVejez = computed(() => {
  return excetePatronal.value * Number(props.cuotasImss?.cesantia_vejez ?? 0)
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
const cuotaFijaPatron = computed(() => uma.value * 0.204)
const excedentePatron = computed(() => excedenteSBC.value * 0.011)
const prestacionesDineroPatron = computed(() => sbcConVales.value * 0.007)
const gastosMedicosPatron = computed(() => sbcConVales.value * 0.0105)
const invalidezVidaPatron = computed(() => sbcConVales.value * 0.0175)
const guarderiasPatron = computed(() => sbcConVales.value * 0.01)
const retiroPatron = computed(() => sbcConVales.value * 0.02)
const cesantiaPatron = computed(() => sbcConVales.value * 0.0315)


/* =============================
   CUOTAS IMSS – TRABAJADOR
============================= */
const porcentajeLimiteValesTexto = computed(() => {
  const valor = Number(props.configNomina?.limite_vales_despensa ?? 0)
  return (valor * 100).toFixed(0) + '%'
})

const porcentajeExcedentePatronalTexto = computed(() => {
  const valor = Number(props.cuotasImss?.excedente_patronal ?? 0)
  return (valor * 100).toFixed(2) + '%'
})

const porcentajePrestacionesDineroTexto = computed(() => {
  const valor = Number(props.cuotasImss?.prestaciones_dinero ?? 0)
  return (valor * 100).toFixed(2) + '%'
})

const porcentajePrestacionesEspecieTexto = computed(() => {
  const valor = Number(props.cuotasImss?.prestaciones_especie ?? 0)
  return (valor * 100).toFixed(2) + '%'
})

const porcentajeInvalidezVidaTexto = computed(() => {
  const valor = Number(props.cuotasImss?.invalidez_vida ?? 0)
  return (valor * 100).toFixed(2) + '%'
})

const porcentajeCesantiaVejezTexto = computed(() => {
  const valor = Number(props.cuotasImss?.cesantia_vejez ?? 0)
  return (valor * 100).toFixed(2) + '%'
})

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

    router.visit('/alumno/recibo')
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
              <tr><td class="td">SALARIO BASE</td><td class="td">$ {{ salarioBase }}</td></tr>
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
           <tr>
  <td>Aguinaldo (días)</td>
  <td>
    <input
      v-model.number="diasAguinaldo"
      type="number"
      class="input-sm"
      @focus="mostrarNotaAguinaldo = true"
      @blur="mostrarNotaAguinaldo = false"
    />
  </td>
</tr>

<tr v-if="mostrarNotaAguinaldo">
  <td colspan="2" class="text-sm text-gray-600 md:col-span-2">
   Los trabajadores tendran derecho a un aguinaldo anual que debera pagarse antes del dia 20 de diciembre, equivalente a 15 dias de salario por lo menos: Articulo 87 LFT.
  </td>
</tr>

 <tr>
  <td>Vacaviones (días)</td>
  <td>
    <input
      v-model.number="diasVacaciones"
      type="number"
      class="input-sm"
      @focus="mostrarNotaVacaciones = true"
      @blur="mostrarNotaVacaciones = false"
    />
  </td>
</tr>

<tr v-if="mostrarNotaVacaciones">
  <td colspan="2" class="text-sm text-gray-600 md:col-span-2">
Las personas trabajadoras que tengan mas de un año de servicios disfrutaran de un periodo anual de vacaciones pagadas, que en ningun caso podran ser inferior a 12 dias 
laborales, y aumentara de 2 dias laborales, hasta llegar a 20 por cada año subcecuente de servicios. Apartir del sexto año, el periodo de vacaciones aumentara en 2 dias por cada 5 años de servicios: Articulo 76 LFT.
  </td>
</tr>

<tr>
  <td>Prima vacacional</td>
  <td>
    <input
      v-model.number="primaVacacional"
      type="number"
      class="input-sm"
      @focus="mostrarNotaPrima = true"
      @blur="mostrarNotaPrima = false"
    />
  </td>
</tr>

<tr v-if="mostrarNotaPrima">
  <td colspan="2" class="text-sm text-gray-600 md:col-span-2">
Los trabajadores tendran derecho a una prima no menor del 25% sobre los salarios que le correspondan durante el periodo de vacaiones: Articulo 80 LFT.
  </td>
</tr>

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
            <tr><td>UMA</td><td>$ {{ uma.toFixed(2) }}</td></tr>
             <tr>
              <td>  Excento mensual</td><td>$ {{ limiteExcentoVales }}</td>
            </tr>
            <tr>
              <td>Límite excento permitido 40%</td><td>$ {{limiteExcentoPermtido }}</td>
            </tr>
            <tr>
              <td>Vales de despensa</td>
              <td>$ {{ valesDespensa}}</td>
            </tr>
            <tr><td>Vales gravados</td><td>$ {{  valesGravados.toFixed(2) }}</td></tr>
          
            <tr class="resaltado"><td>Vales diarios gravados</td><td>$ {{ valesDiariosGravados.toFixed(2)}}</td></tr>
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
            <tr><td>UMA</td><td>$ {{ uma.toFixed(2) }}</td></tr>
            <tr><td>3 UMA</td><td>$ {{ tresUMA.toFixed(2) }}</td></tr>
            <tr><td>SBC final</td><td>$ {{ sbcConVales.toFixed(2) }}</td></tr>
            <tr>
              <td>Diferencia</td>
              <td>$ {{ diferenciaSBC.toFixed(2) }}</td>
            </tr>
            <tr>
              <td>Días trabajados</td>
              <td><input v-model.number="diasMes" type="number" class="input-sm" /></td>
            </tr>
            <tr>
              <td>Cálculo</td>
              <td>$ {{ calculoExcedente.toFixed(2) }}</td>
            </tr>
            <tr>
              <td>Excedente patronal</td>
              <td class="font-semibold text-gray-700">{{ porcentajeExcedentePatronalTexto }}</td>
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
              <td>Base</td>
              <td>Importe</td>
            </tr>

            <tr>
              <td>Excedente patronal</td>
              <td>{{ porcentajeExcedentePatronalTexto }}</td>
              <td>$ {{ excetePatronal.toFixed(2) }}</td>
              <td>$ {{ importeExcedente.toFixed(2) }}</td>
            </tr>

            <tr>
              <td>Prestaciones de dinero</td>
              <td>{{ porcentajePrestacionesDineroTexto }}</td>
              <td>$ {{ excetePatronal.toFixed(2) }}</td>
              <td>$ {{ prestacionesDinero.toFixed(2) }}</td>
            </tr>

            <tr>
              <td>Prestaciones en especie</td>
              <td>{{ porcentajePrestacionesEspecieTexto }}</td>
              <td>$ {{ excetePatronal.toFixed(2) }}</td>
              <td>$ {{ prestacionesEspecie.toFixed(2) }}</td>
            </tr>

            <tr>
              <td>Invalidez y vida</td>
              <td>{{ porcentajeInvalidezVidaTexto }}</td>
              <td>$ {{ excetePatronal.toFixed(2) }}</td>
              <td>$ {{ invalidezVida.toFixed(2) }}</td>
            </tr>

            <tr>
              <td>Cesantía y vejez</td>
              <td>{{ porcentajeCesantiaVejezTexto }}</td>
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