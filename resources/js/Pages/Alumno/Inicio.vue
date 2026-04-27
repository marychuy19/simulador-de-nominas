<script setup>
import { ref, computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, usePage, router } from '@inertiajs/vue3'

/* IMÁGENES */
const imageEmpresa = new URL('../image/empresa.jpeg', import.meta.url).href
const imageEmpleado = new URL('../image/empleado2.jpeg', import.meta.url).href
const imageCalculadora = new URL('../image/calculadora.jpeg', import.meta.url).href
const imagePerfil = new URL('../image/perfilempleado.jpeg', import.meta.url).href

const mostrarNotaSalario = ref(false)
const mostrarNotaPeriodo = ref(false)
const mostrarNotaPatronal = ref(false)
const mostrarNotaVales = ref(false)

const editingEmpresaId = ref(null)

/* PROPS */
const { props } = usePage()
const empresas = computed(() => props.empresas ?? [])

/* MODALES */
const showEmpresaModal = ref(false)
const showEmpleadoModal = ref(false)

/* =========================
   EMPRESA SELECCIONADA
========================= */
const empresaSeleccionada = ref(null)

const seleccionarEmpresa = (empresa) => {
  empresaSeleccionada.value =
    empresaSeleccionada.value?.id === empresa.id
      ? null
      : empresa
}

/* =========================
   FORM EMPRESA
========================= */
const empresaForm = useForm({
  nombre_razon_social: '',
  rfc: '',
  direccion_fiscal: '',
  regimen_fiscal: '',
  periodo_pago: '',
  registro_patronal: '',
})

const guardarEmpresa = () => {
  if (editingEmpresaId.value) {
    // ✏️ ACTUALIZAR
    empresaForm.put(route('alumno.empresas.update', editingEmpresaId.value), {
      preserveScroll: true,
      onSuccess: () => {
        showEmpresaModal.value = false
        empresaForm.reset()
        editingEmpresaId.value = null

        router.visit(route('dashboard'), { preserveScroll: true })
      }
    })
  } else {
    // ➕ CREAR
    empresaForm.post(route('alumno.empresas.store'), {
      preserveScroll: true,
      onSuccess: () => {
        showEmpresaModal.value = false
        empresaForm.reset()

        router.visit(route('dashboard'), { preserveScroll: true })
      }
    })
  }
}
/* =========================
   FORM EMPLEADO
========================= */
const empleadoForm = useForm({
  empresa_id: '',
  nombre_completo: '',
  identificacion: '',
  puesto: '',
  tipo_contrato: '',
  fecha_ingreso: '',
  salario: '',
  periodo_salario: '',
  tipo_salario: '',
  jornada: '',
  vales_despensa: '',
})

const guardarEmpleado = () => {
  empleadoForm.post(route('alumno.empleados.store'), {
    preserveScroll: true,
    onSuccess: () => {
      showEmpleadoModal.value = false
      empleadoForm.reset()

      router.visit(route('dashboard'), {
        preserveScroll: true
      })
    }
  })
}

const editarEmpresa = (empresa) => {
  editingEmpresaId.value = empresa.id

  empresaForm.nombre_razon_social = empresa.nombre_razon_social ?? ''
  empresaForm.rfc = empresa.rfc ?? ''
  empresaForm.direccion_fiscal = empresa.direccion_fiscal ?? ''
  empresaForm.regimen_fiscal = empresa.regimen_fiscal ?? ''
  empresaForm.periodo_pago = empresa.periodo_pago ?? ''
  empresaForm.registro_patronal = empresa.registro_patronal ?? ''

  showEmpresaModal.value = true
}

const eliminarEmpresa = (id) => {
  if (confirm('¿Seguro que deseas eliminar esta empresa?')) {
    router.delete(route('alumno.empresas.destroy', id), {
      preserveScroll: true,
      onSuccess: () => {
        router.visit(route('dashboard'), {
          preserveScroll: true
        })
      }
    })
  }
}
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <!-- HEADER -->
    <template #header>
      <h2 class="text-2xl font-bold text-blue-900">
        Dashboard
      </h2>
    </template>

    <!-- CONTENIDO -->
    <div class="py-10 bg-gradient-to-br from-blue-100 via-blue-200 to-blue-100 min-h-screen">
  <div class="max-w-7xl mx-auto px-6 space-y-8">

   
    <!-- =========================
     ACCIONES RAPIDAS
========================= -->
<div>
  <h2 class="text-xl font-bold text-gray-800 mb-4">Acciones Rápidas</h2>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

    <!-- EMPRESA -->
    <button
      @click="showEmpresaModal = true"
      class="bg-white rounded-2xl p-6 shadow hover:shadow-xl transition text-center group"
    >
      <img :src="imageEmpresa" class="w-12 h-12 mx-auto mb-3 object-contain group-hover:scale-110 transition" />
      <p class="font-semibold text-gray-700">Registrar empresa</p>
    </button>

    <!-- EMPLEADO -->
    <button
      @click="showEmpleadoModal = true"
      class="bg-white rounded-2xl p-6 shadow hover:shadow-xl transition text-center group"
    >
      <img :src="imageEmpleado" class="w-12 h-12 mx-auto mb-3 object-contain group-hover:scale-110 transition" />
      <p class="font-semibold text-gray-700">Registrar empleado</p>
    </button>

    <!-- CALCULO -->
    <a
      :href="route('alumno.calculoNomina')"
      class="bg-white rounded-2xl p-6 shadow hover:shadow-xl transition text-center group"
    >
      <img :src="imageCalculadora" class="w-12 h-12 mx-auto mb-3 object-contain group-hover:scale-110 transition" />
      <p class="font-semibold text-gray-700">Iniciar cálculo</p>
    </a>

    <!-- PERFIL -->
    <a
      :href="route('alumno.empleado')"
      class="bg-white rounded-2xl p-6 shadow hover:shadow-xl transition text-center group"
    >
      <img :src="imagePerfil" class="w-12 h-12 mx-auto mb-3 object-contain group-hover:scale-110 transition" />
      <p class="font-semibold text-gray-700">Perfil empleado</p>
    </a>

  </div>
</div>

    <!-- =========================
         METRICAS
    ========================= -->
    <div>
      <h2 class="text-xl font-bold text-gray-800 mb-4">Resumen</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- TOTAL EMPRESAS -->
<div class="bg-white rounded-2xl shadow p-6 flex justify-between items-center">
  <div>
    <p class="text-sm text-gray-500">Total de empresas:</p>
    <p class="text-xl font-bold text-gray-800">
      {{ empresas.length }}
    </p>
  </div>

  <div class="bg-blue-100 p-3 rounded-xl">
    <img 
      :src="imageEmpresa" 
      class="w-8 h-8 object-contain group-hover:scale-110 transition"
    />
  </div>
</div>

<!-- TOTAL EMPLEADOS -->
<div class="bg-white rounded-2xl shadow p-6 flex justify-between items-center">
  <div>
    <p class="text-sm text-gray-500">Total de empleados:</p>
    <p class="text-xl font-bold text-gray-800">
      {{ empresas.reduce((acc, e) => acc + (e.empleados_count || 0), 0) }}
    </p>
  </div>

  <div class="bg-green-100 p-3 rounded-xl">
    <img 
      :src="imageEmpleado" 
      class="w-8 h-8 object-contain group-hover:scale-110 transition"
    />
  </div>
</div>

      </div>
    </div>

<!-- =========================
     EMPRESAS
========================= -->
<div class="bg-white rounded-2xl shadow p-4 sm:p-6">

  <!-- HEADER -->
  <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
    
    <h2 class="text-lg sm:text-xl font-bold text-gray-800 text-center sm:text-left">
      Empresas registradas
    </h2>

    <span class="bg-blue-100 text-blue-700 px-4 py-1 rounded-full text-xs sm:text-sm font-semibold self-center sm:self-auto">
      {{ empresas.length }} empresas
    </span>

  </div>

  <!-- LISTA -->
  <div v-if="empresas.length" class="space-y-4">

    <div
      v-for="empresa in empresas"
      :key="empresa.id"
      @click="seleccionarEmpresa(empresa)"
      class="border rounded-xl p-4 sm:p-5 hover:shadow-md transition cursor-pointer"
    >

      <!-- CONTENIDO PRINCIPAL -->
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">

        <!-- INFO -->
        <div class="text-center sm:text-left">
          <p class="font-semibold text-gray-800 text-sm sm:text-base">
            {{ empresa.nombre_razon_social }}
          </p>
          <p class="text-xs sm:text-sm text-gray-500 break-all">
            RFC: {{ empresa.rfc }}
          </p>
        </div>

        <!-- ACCIONES -->
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">

          <span class="text-xs sm:text-sm text-gray-600">
            {{ empresa.empleados_count }} empleados
          </span>
          <button
  @click.stop="editarEmpresa(empresa)"
  class="px-4 py-2 text-sm font-medium rounded-xl bg-blue-700 text-white hover:bg-blue-900 transition shadow-sm">
  Editar
</button>

          <button
            @click.stop="eliminarEmpresa(empresa.id)"
            class="px-4 py-2 text-sm font-medium rounded-xl bg-red-500 text-white hover:bg-red-600 transition shadow-sm">
            Eliminar
          </button>

        </div>

      </div>

      <!-- EMPLEADOS -->
      <div
        v-if="empresaSeleccionada?.id === empresa.id"
        class="mt-4 bg-gray-50 rounded-lg p-3 sm:p-4"
      >
        <p class="font-semibold text-xs sm:text-sm mb-2">
          Empleados:
        </p>

        <ul v-if="empresa.empleados?.length" class="space-y-1 text-xs sm:text-sm">
          <li v-for="empleado in empresa.empleados" :key="empleado.id" class="break-words">
            • {{ empleado.nombre_completo }}
          </li>
        </ul>

        <p v-else class="text-xs sm:text-sm text-gray-500 italic">
          Sin empleados registrados
        </p>
      </div>

    </div>

  </div>

  <!-- EMPTY -->
  <div v-else class="text-center text-gray-500 py-10 text-sm sm:text-base">
    No hay empresas registradas
  </div>

</div>

  </div>
</div>

    <!-- =========================
         MODAL EMPRESA
    ========================= -->
    <div
      v-if="showEmpresaModal"
      class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    >
      <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl border border-gray-200 overflow-hidden animate-fade">

        <!-- HEADER -->
        <div class="bg-blue-600 text-white px-6 py-4">
         <h3 class="text-lg font-semibold tracking-wide">
  {{ editingEmpresaId ? 'Editar empresa' : 'Registrar empresa' }}
</h3>
          <p class="text-xs opacity-80">
            Complete la información fiscal correspondiente
          </p>
        </div>

        <!-- BODY -->
        <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">

          <input type="text" placeholder="Nombre o razón social"
            class="modal-input"
            v-model="empresaForm.nombre_razon_social" />

          <input type="text" placeholder="RFC" maxlength="13"
            class="modal-input"
            v-model="empresaForm.rfc" />

          <textarea placeholder="Dirección fiscal" rows="2"
            class="modal-input"
            v-model="empresaForm.direccion_fiscal"></textarea>
            

<select
  class="modal-input"
  v-model="empresaForm.regimen_fiscal"
>
  <option value="">Régimen fiscal</option>

  <!-- PERSONAS MORALES -->
  <optgroup label="Personas Morales">
    <option value="601">601 General de Ley Personas Morales</option>
    <option value="603">603 Personas Morales con Fines No Lucrativos</option>
    <option value="620">620 Sociedades Cooperativas</option>
    <option value="623">623 Opcional para Grupos de Sociedades</option>
    <option value="624">624 Coordinados</option>
  </optgroup>

  <!-- PERSONAS FÍSICAS -->
  <optgroup label="Personas Físicas">
    <option value="605">605 Sueldos y Salarios</option>
    <option value="606">606 Arrendamiento</option>
    <option value="608">608 Demás ingresos</option>
    <option value="612">612 Actividades Empresariales y Profesionales</option>
    <option value="614">614 Ingresos por intereses</option>
    <option value="615">615 Obtención de premios</option>
    <option value="621">621 Incorporación Fiscal</option>
    <option value="622">622 Actividades agrícolas, ganaderas, etc.</option>
    <option value="626">626 Régimen Simplificado de Confianza</option>
  </optgroup>

  <!-- OTROS -->
  <optgroup label="Otros">
    <option value="607">607 Enajenación o adquisición de bienes</option>
    <option value="610">610 Residentes en el extranjero</option>
    <option value="611">611 Dividendos</option>
    <option value="616">616 Sin obligaciones fiscales</option>
    <option value="625">625 Plataformas tecnológicas</option>
  </optgroup>
</select>

          <select
  class="modal-input"
  v-model="empresaForm.periodo_pago"
  @focus="mostrarNotaPeriodo = true"
  @blur="mostrarNotaPeriodo = false"
>
  <option value="">Periodo de pago</option>
  <option value="diario">Diario</option>
  <option value="semanal">Semanal</option>
  <option value="quincenal">Quincenal</option>
  <option value="10_dias">10 días</option>
  <option value="mensual">Mensual</option>
</select>

<p v-if="mostrarNotaPeriodo" class="text-sm text-gray-600 md:col-span-2">
El salario puede fijarse por unidad de tiempo, por unidad de obra, por comision, a precio alzado o de cualquier otra manera: Articulo 83 LFT.
</p>



      <input
  type="text"
  placeholder="Registro patronal"
  class="modal-input"
  v-model="empresaForm.registro_patronal"
  @focus="mostrarNotaPatronal = true"
  @blur="mostrarNotaPatronal = false"
/>
<p v-if="mostrarNotaPatronal" class="text-sm text-gray-600 md:col-span-2">
Los patrones estan obligados a registrarse e inscribir a sus trabajadores en el Instituto Mexicano del Seguro Social, comunicas
sus altas y bajas, llevar registro de nominas y determina las coutas Obreros-Patronales a su cargo y enterarlas al Instituto: Articulo 15 LSS.
</p>
        
  

        </div>

        <!-- FOOTER -->
        <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 border-t">
          <button
            @click="showEmpresaModal = false"
            class="px-4 py-2 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 transition"
          >
            Cancelar
          </button>

          <button
            @click="guardarEmpresa"
            class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium shadow-sm transition"
          >
            Guardar
          </button>
        </div>

      </div>
    </div>

    <!-- =========================
         MODAL EMPLEADO
    ========================= -->
    <div
      v-if="showEmpleadoModal"
      class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 p-4"
    >
      <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl border border-gray-200 overflow-hidden animate-fade">

        <!-- HEADER -->
        <div class="bg-blue-600 text-white px-6 py-4">
          <h3 class="text-lg font-semibold tracking-wide">
            Registrar empleado
          </h3>
          <p class="text-xs opacity-80">
            Información laboral y contractual
          </p>
        </div>

        <!-- BODY -->
        <div class="p-6 grid md:grid-cols-2 gap-4 max-h-[70vh] overflow-y-auto">

          <input type="text" placeholder="Nombre completo"
            class="modal-input"
            v-model="empleadoForm.nombre_completo" />

          <input type="text" placeholder="CURP / NSS / Identificación"
            class="modal-input"
            v-model="empleadoForm.identificacion" />

          <input type="text" placeholder="Puesto"
            class="modal-input"
            v-model="empleadoForm.puesto" />

          <select class="modal-input"
            v-model="empleadoForm.tipo_contrato">
            <option value="">Tipo de contrato</option>
            <option value="indefinido">Indefinido</option>
            <option value="temporal">Temporal</option>
            <option value="honorarios">Honorarios</option>
          </select>

                   <div class="modal-group">
  <label class="modal-label">Fecha de ingreso</label>
  <input 
    type="date" 
    class="modal-input"
    v-model="empleadoForm.fecha_ingreso" 
  />
</div>

         <input
  type="number"
  step="0.01"
  placeholder="Salario base (diario)"
  class="modal-input"
  v-model="empleadoForm.salario"
  @focus="mostrarNotaSalario = true"
  @blur="mostrarNotaSalario = false"
/>
<p v-if="mostrarNotaSalario" class="text-sm text-gray-600 md:col-span-2">
El salario es la retribucion que debe pagar el patron al trabajador por su trabajo: Articulo 82 LFT.
</p>

            <select
  class="modal-input"
  v-model="empleadoForm.periodo_salario"
  @focus="mostrarNotaPeriodo = true"
  @blur="mostrarNotaPeriodo = false"
>
  <option value="">Periodo de pago</option>
  <option value="diario">Diario</option>
  <option value="semanal">Semanal</option>
  <option value="quincenal">Quincenal</option>
  <option value="10_dias">10 días</option>
  <option value="mensual">Mensual</option>
</select>

<p v-if="mostrarNotaPeriodo" class="text-sm text-gray-600 md:col-span-2">
El salario puede fijarse por unidad de tiempo, por unidad de obra, por comision, a precio alzado o de cualquier otra manera: Articulo 83 LFT.
</p>

<input
  type="number"
  step="0.01"
  placeholder="Vales de despensa"
  class="modal-input"
  v-model="empleadoForm.vales_despensa"
  @focus="mostrarNotaVales = true"
  @blur="mostrarNotaVales = false"
/>
<p v-if="mostrarNotaVales" class="text-m text-gray-600 md:col-span-2">
El monto total de los vales es por el periodo.
</p>

          <select class="modal-input"
            v-model="empleadoForm.tipo_salario">
            <option value="">Tipo de salario</option>
            <option value="fijo">Fijo</option>
            <option value="variable">Variable</option>
            <option value="mixto">Mixto</option>
          </select>

          <select class="modal-input"
            v-model="empleadoForm.jornada">
            <option value="">Jornada laboral</option>
            <option value="completa">Completa</option>
            <option value="media">Media</option>
            <option value="nocturna">Nocturna</option>
          </select>

          <select class="modal-input md:col-span-2"
            v-model="empleadoForm.empresa_id">
            <option value="">Empresa donde trabaja</option>
            <option
              v-for="empresa in empresas"
              :key="empresa.id"
              :value="empresa.id">
              {{ empresa.nombre_razon_social }}
            </option>
          </select>

        </div>

        <!-- FOOTER -->
        <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 border-t">
          <button
            @click="showEmpleadoModal = false"
            class="px-4 py-2 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 transition"
          >
            Cancelar
          </button>

          <button
            @click="guardarEmpleado"
            class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium shadow-sm transition"
          >
            Guardar
          </button>
        </div>

      </div>
    </div>

  </AuthenticatedLayout>
</template>

<style scoped>
.modal-input {
  @apply w-full bg-white border border-gray-300 rounded-xl px-3 py-2
  focus:ring-2 focus:ring-blue-400 focus:border-blue-400
  outline-none transition;
}

/* Animación suave */
.animate-fade {
  animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.96);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>