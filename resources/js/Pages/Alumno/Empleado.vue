<script setup>
import { ref, computed } from 'vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

/* IMÁGENES */
const avatarEmpleado = new URL('../image/user.jpeg', import.meta.url).href
const iconBuscar = new URL('../image/lupa.jpeg', import.meta.url).href
const iconEmples = new URL('../image/empleados.jpeg', import.meta.url).href

/* PROPS */
const props = defineProps({
  empleados: { type: Array, default: () => [] },
  empresas: { type: Array, default: () => [] }
})

/* BUSCADOR */
const search = ref('')

/* MODAL */
const showEmpleadoModal = ref(false)
const editando = ref(false)

/* FORMULARIO COMPLETO */
const empleadoForm = useForm({
  id: null,
  nombre_completo: '',
  identificacion: '',
  puesto: '',
  tipo_contrato: '',
  fecha_ingreso: '',
  salario: '',
  periodo_salario: '',
  tipo_salario: '',
  jornada: '',
  empresa_id: ''
})

/* FORMATO DINERO */
const money = (v) => {
  const n = Number(v ?? 0)
  return n.toLocaleString('es-MX', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })
}

const empleadosFiltrados = computed(() => {
  if (!search.value) return props.empleados

  const termino = search.value.toLowerCase()

  return props.empleados.filter(e => {
    const nombreEmpleado = e.nombre_completo?.toLowerCase() ?? ''
    const nombreEmpresa = e.empresa?.nombre_razon_social?.toLowerCase() ?? ''

    return (
      nombreEmpleado.includes(termino) ||
      nombreEmpresa.includes(termino)
    )
  })
})

const sinResultados = computed(() => {
  return search.value && empleadosFiltrados.value.length === 0
})


/* NUEVO */
const nuevoEmpleado = () => {
  editando.value = false
  empleadoForm.reset()
  showEmpleadoModal.value = true
}

/* EDITAR (CARGA TODOS LOS DATOS) */
const editarEmpleado = (empleado) => {
  editando.value = true

  Object.keys(empleadoForm.data()).forEach(key => {
    empleadoForm[key] = empleado[key] ?? ''
  })

  showEmpleadoModal.value = true
}

/* ELIMINAR */
const eliminarEmpleado = (id) => {
  if (confirm('¿Seguro que deseas eliminar este empleado?')) {
    router.delete(route('empleados.destroy', id))
  }
}

/* GUARDAR */
const guardarEmpleado = () => {
  if (editando.value) {
    empleadoForm.put(route('empleados.update', empleadoForm.id), {
      onSuccess: () => {
        showEmpleadoModal.value = false
        editando.value = false
        empleadoForm.reset()
      }
    })
  } else {
    empleadoForm.post(route('empleados.store'), {
      onSuccess: () => {
        showEmpleadoModal.value = false
        empleadoForm.reset()
      }
    })
  }
}
</script>

<template>
  <Head title="Empleados" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-blue-900">
          Empleados registrados
        </h2>

        <button
          @click="nuevoEmpleado"
          class="bg-green-700 text-white px-4 py-2 rounded-xl shadow hover:bg-green-800"
        >
          + Nuevo empleado
        </button>
      </div>
    </template>

    <div class="py-10 bg-gradient-to-br from-blue-100 via-blue-200 to-blue-100 min-h-screen">
      <div class="max-w-6xl mx-auto px-6">

         <!-- BIENVENIDA -->
       <div class="bg-white rounded-2xl shadow-lg p-8 flex items-center gap-6 mb-10">


          <!-- LOGO -->
          <div
            class="w-16 h-16 rounded-xl bg-white flex items-center justify-center shadow"
          >
            <img
              :src="iconEmples"
              alt="Logo"
              class="w-full h-full object-contain rounded-xl"
            />
          </div>

          <div>
            <h3 class="text-2xl font-semibold text-gray-800">
              Empleados registrados
            </h3>
            <p class="text-gray-600 mt-1">
              Busca empleados por nombre o empresa, edita su informacion o eliminalos.
            </p>
          </div>
        </div>



        <!-- BUSCADOR -->
        <div class="mb-6 relative">
          <img :src="iconBuscar"
               class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 opacity-70" />
          <input
            v-model="search"
            type="text"
            placeholder="Buscar empleado..."
            class="w-full rounded-2xl border-gray-300 pl-12 pr-5 py-4 shadow-md"
          />
        </div>

        <!-- LISTADO -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div v-for="empleado in empleadosFiltrados"
               :key="empleado.id"
               class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-blue-600 flex justify-between">

            <div class="space-y-2 text-sm text-gray-700">
              <p><strong>Identificación:</strong> {{ empleado.identificacion }}</p>
              <p><strong>Tipo contrato:</strong> {{ empleado.tipo_contrato }}</p>
              <p><strong>Fecha ingreso:</strong> {{ empleado.fecha_ingreso }}</p>
              <p><strong>Salario:</strong> ${{ money(empleado.salario) }}</p>
              <p><strong>Periodo:</strong> {{ empleado.periodo_salario }}</p>
              <p><strong>Tipo salario:</strong> {{ empleado.tipo_salario }}</p>
              <p><strong>Jornada:</strong> {{ empleado.jornada }}</p>
              <p><strong>Empresa:</strong>
                {{ empleado.empresa?.nombre_razon_social ?? 'Sin empresa' }}
              </p>

              <div class="flex gap-2 mt-3">
                <button
                  @click="editarEmpleado(empleado)"
                  class="px-3 py-1 text-sm bg-blue-600 text-white rounded-lg">
                  Editar
                </button>

                <button
                  @click="eliminarEmpleado(empleado.id)"
                  class="px-3 py-1 text-sm bg-red-600 text-white rounded-lg">
                  Eliminar
                </button>
              </div>
            </div>

            <div class="text-right">
              <div class="w-14 h-14 rounded-xl overflow-hidden shadow mb-2">
                <img :src="avatarEmpleado"
                     class="w-full h-full object-cover" />
              </div>
              <h3 class="font-semibold text-gray-800">
                {{ empleado.nombre_completo }}
              </h3>
              <p class="text-sm text-gray-500">
                {{ empleado.puesto }}
              </p>
            </div>

          </div>
        </div>

      <!-- MENSAJE -->
<div v-if="sinResultados"
     class="bg-white rounded-2xl shadow-md p-8 text-center text-gray-600 mt-6">
  <p class="text-lg font-semibold">
    No existe empleado o empresa con ese nombre.
  </p>
  <p class="text-sm mt-2 text-gray-500">
    Intenta con otro término de búsqueda.
  </p>
</div>

      </div>
    </div>

    <!-- MODAL COMPLETO -->
    <div v-if="showEmpleadoModal"
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 overflow-y-auto">

      <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 my-10">
        <h3 class="text-xl font-semibold mb-4">
          {{ editando ? 'Editar empleado' : 'Registrar empleado' }}
        </h3>

        <div class="space-y-4 max-h-[70vh] overflow-y-auto pr-2">

          <input v-model="empleadoForm.nombre_completo" type="text" placeholder="Nombre completo" class="w-full rounded-xl border-gray-300" />

          <input v-model="empleadoForm.identificacion" type="text" placeholder="CURP / NSS / Identificación" class="w-full rounded-xl border-gray-300" />

          <input v-model="empleadoForm.puesto" type="text" placeholder="Puesto" class="w-full rounded-xl border-gray-300" />

          <select v-model="empleadoForm.tipo_contrato" class="w-full rounded-xl border-gray-300">
            <option value="">Tipo de contrato</option>
            <option value="indefinido">Indefinido</option>
            <option value="temporal">Temporal</option>
            <option value="honorarios">Honorarios</option>
          </select>

          <input v-model="empleadoForm.fecha_ingreso" type="date" class="w-full rounded-xl border-gray-300" />

          <input v-model="empleadoForm.salario" type="number" step="0.01" placeholder="Salario base (diario)" class="w-full rounded-xl border-gray-300" />

          <select v-model="empleadoForm.periodo_salario" class="w-full rounded-xl border-gray-300">
            <option value="">Periodo de salario</option>
            <option value="diario">Diario</option>
            <option value="semanal">Semanal</option>
            <option value="10_dias">10 días</option>
            <option value="quincenal">Quincenal</option>
            <option value="mensual">Mensual</option>
          </select>

          <select v-model="empleadoForm.tipo_salario" class="w-full rounded-xl border-gray-300">
            <option value="">Tipo de salario</option>
            <option value="fijo">Fijo</option>
            <option value="variable">Variable</option>
            <option value="mixto">Mixto</option>
          </select>

          <select v-model="empleadoForm.jornada" class="w-full rounded-xl border-gray-300">
            <option value="">Jornada laboral</option>
            <option value="completa">Completa</option>
            <option value="media">Media</option>
            <option value="nocturna">Nocturna</option>
          </select>

        </div>

        <div class="flex justify-end gap-3 mt-6">
          <button @click="showEmpleadoModal = false"
                  class="px-4 py-2 rounded-lg bg-gray-300">
            Cancelar
          </button>

          <button @click="guardarEmpleado"
                  class="px-4 py-2 rounded-lg bg-green-700 text-white">
            {{ editando ? 'Actualizar' : 'Guardar' }}
          </button>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
