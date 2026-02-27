<script setup>
import { ref, computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm, usePage, router } from '@inertiajs/vue3'

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
  empresaForm.post(route('alumno.empresas.store'), {
    preserveScroll: true,
    onSuccess: () => {
      showEmpresaModal.value = false
      empresaForm.reset()
    }
  })
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
})

const guardarEmpleado = () => {
  empleadoForm.post(route('alumno.empleados.store'), {
    preserveScroll: true,
    onSuccess: () => {
      showEmpleadoModal.value = false
      empleadoForm.reset()
    }
  })
}

const eliminarEmpresa = (id) => {
  if (confirm('¿Seguro que deseas eliminar esta empresa?')) {
    router.delete(route('alumno.empresas.destroy', id), {
      preserveScroll: true,
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
      <div class="max-w-7xl mx-auto px-6">

        <!-- LAYOUT 2 COLUMNAS -->
        <div class="grid grid-cols-12 gap-8">

          <!-- =======================
               PANEL IZQUIERDO
          ======================== -->
          <div class="col-span-12 md:col-span-3">

            <div class="bg-white rounded-2xl shadow-lg p-6 space-y-6">

              <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">
                Acciones rápidas
              </h3>

              <button
                @click="showEmpresaModal = true"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl
                       bg-blue-700 text-white font-semibold
                       hover:bg-blue-900 transition"
              >
                Registrar empresa
              </button>

              <button
                @click="showEmpleadoModal = true"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl
                       bg-blue-700 text-white font-semibold
                       hover:bg-blue-900 transition"
              >
                Registrar empleado
              </button>

            </div>

          </div>

          <!-- =======================
               CONTENIDO PRINCIPAL
          ======================== -->
          <div class="col-span-12 md:col-span-9">

            <!-- CARD EMPRESAS -->
            <div class="bg-white rounded-2xl shadow-lg p-8">

              <div class="flex items-center justify-between mb-8 border-b pb-4">
                <h3 class="text-2xl font-bold text-blue-900">
                  Empresas registradas
                </h3>

                <span
                  v-if="empresas.length"
                  class="text-sm bg-blue-100 text-blue-900
                         px-4 py-1 rounded-full font-semibold"
                >
                  {{ empresas.length }} empresas
                </span>
              </div>

              <div v-if="empresas.length" class="space-y-6">

                <div
                  v-for="empresa in empresas"
                  :key="empresa.id"
                  @click="seleccionarEmpresa(empresa)"
                  class="cursor-pointer bg-white border border-gray-200
                         rounded-xl p-6
                         hover:shadow-md transition"
                >
                  <!-- HEADER -->
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-lg font-semibold text-gray-800">
                        {{ empresa.nombre_razon_social }}
                      </p>
                      <p class="text-xs text-gray-500 uppercase tracking-wide">
                        RFC · {{ empresa.rfc }}
                      </p>
                    </div>

                    <div class="flex items-center gap-4">
                      <span
                        class="px-3 py-1 rounded-full bg-blue-100 text-blue-800
                               font-semibold text-sm"
                      >
                        {{ empresa.empleados_count }} empleados
                      </span>

                      <button
  @click.stop="eliminarEmpresa(empresa.id)"
  class="
    w-full sm:w-auto
    px-4 sm:px-6
    py-2 sm:py-2.5
    text-sm sm:text-base
    rounded-full
    bg-red-500 text-white font-semibold
    hover:bg-red-600
    transition duration-200
    shadow-md hover:shadow-lg
    active:scale-95
  "
>
  Eliminar
</button>
                    </div>
                  </div>

                  <!-- EMPLEADOS -->
                  <div
                    v-if="empresaSeleccionada?.id === empresa.id"
                    class="mt-5 bg-gray-50 rounded-lg p-4 border border-gray-200"
                  >
                    <p class="font-semibold text-sm text-gray-700 mb-3">
                      Empleados registrados
                    </p>

                    <ul
                      v-if="empresa.empleados?.length"
                      class="space-y-2"
                    >
                      <li
                        v-for="empleado in empresa.empleados"
                        :key="empleado.id"
                        class="text-sm text-gray-700"
                      >
                        • {{ empleado.nombre_completo }}
                      </li>
                    </ul>

                    <p
                      v-else
                      class="text-sm text-gray-500 italic"
                    >
                      Esta empresa no tiene empleados registrados
                    </p>
                  </div>
                </div>

              </div>

              <div
                v-else
                class="text-center py-14 text-gray-500 italic"
              >
                No hay empresas registradas todavía
              </div>

            </div>

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
            Registrar empresa
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

          <input type="text" placeholder="Régimen fiscal"
            class="modal-input"
            v-model="empresaForm.regimen_fiscal" />

          <select class="modal-input"
            v-model="empresaForm.periodo_pago">
            <option value="">Periodo de pago</option>
            <option value="diario">Diario</option>
            <option value="semanal">Semanal</option>
            <option value="quincenal">Quincenal</option>
            <option value="10_dias">10 días</option>
            <option value="mensual">Mensual</option>
          </select>

          <input type="text" placeholder="Registro patronal"
            class="modal-input"
            v-model="empresaForm.registro_patronal" />

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

          <input type="date"
            class="modal-input"
            v-model="empleadoForm.fecha_ingreso" />

          <input type="number" step="0.01"
            placeholder="Salario base (diario)"
            class="modal-input"
            v-model="empleadoForm.salario" />

          <select class="modal-input"
            v-model="empleadoForm.periodo_salario">
            <option value="">Periodo de salario</option>
            <option value="diario">Diario</option>
            <option value="semanal">Semanal</option>
            <option value="10_dias">10 días</option>
            <option value="quincenal">Quincenal</option>
            <option value="mensual">Mensual</option>
          </select>

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