<script setup>
import { ref, computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'

/* IMAGEN LOCAL DEL EMPLEADO */
const avatarEmpleado = new URL('../image/user.jpeg', import.meta.url).href

/* IMAGEN LOCAL DEL BUSCADOR */
const iconBuscar = new URL('../image/lupa.jpeg', import.meta.url).href

/* PROPS */
const props = defineProps({
  empleados: {
    type: Array,
    default: () => []
  }
})

/* BUSCADOR */
const search = ref('')

/* FORMATO SALARIO */
const money = (v) => {
  const n = Number(v ?? 0)
  return n.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

/* FILTRADO */
const empleadosFiltrados = computed(() => {
  const list = props.empleados ?? []
  if (!search.value) return list

  const q = search.value.toLowerCase().trim()

  return list.filter((empleado) =>
    (empleado?.nombre_completo ?? '').toLowerCase().includes(q)
  )
})
</script>

<template>
  <Head title="Empleados" />

  <AuthenticatedLayout>
    <!-- HEADER -->
    <template #header>
      <h2 class="text-2xl font-bold text-blue-900">
        Empleados registrados
      </h2>
    </template>

    <!-- CONTENIDO -->
    <div class="py-10 bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen">
      <div class="max-w-6xl mx-auto px-6">

        <!-- BUSCADOR -->
        <div class="mb-6 relative">
          <!-- ICONO -->
          <img
            :src="iconBuscar"
            alt="Buscar"
            class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 opacity-70"
          />

          <!-- INPUT -->
          <input
            type="text"
            v-model="search"
            placeholder="Buscar empleado por nombre..."
            class="w-full rounded-2xl border-gray-300 pl-12 pr-5 py-4 shadow-md
                   focus:ring-2 focus:ring-blue-500 focus:outline-none"
          />
        </div>

        <!-- SIN EMPLEADOS -->
        <div
          v-if="empleadosFiltrados.length === 0"
          class="bg-white rounded-2xl shadow p-10 text-center text-gray-500"
        >
          No se encontraron empleados.
        </div>

        <!-- LISTADO -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div
            v-for="empleado in empleadosFiltrados"
            :key="empleado.id"
            class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-blue-600
                   flex justify-between items-start"
          >
            <!-- DATOS IZQUIERDA -->
            <div class="space-y-2 text-sm text-gray-700">
              <p><strong>Identificación:</strong> {{ empleado.identificacion }}</p>
              <p><strong>Tipo de contrato:</strong> {{ empleado.tipo_contrato }}</p>
              <p><strong>Fecha de ingreso:</strong> {{ empleado.fecha_ingreso }}</p>
              <p><strong>Salario:</strong> ${{ money(empleado.salario) }}</p>
              <p><strong>Periodo salario:</strong> {{ empleado.periodo_salario }}</p>
              <p><strong>Tipo de salario:</strong> {{ empleado.tipo_salario }}</p>
              <p><strong>Jornada:</strong> {{ empleado.jornada }}</p>

              <!-- ✅ Empresa (requiere que el backend mande with('empresa')) -->
              <p>
                <strong>Empresa:</strong>
                {{ empleado.empresa?.nombre_razon_social ?? 'Sin empresa' }}
              </p>
            </div>

            <!-- IMAGEN + NOMBRE DERECHA -->
            <div class="flex flex-col items-end gap-3 text-right">
              <div class="w-14 h-14 rounded-xl overflow-hidden shadow">
                <img
                  :src="avatarEmpleado"
                  alt="Empleado"
                  class="w-full h-full object-cover"
                />
              </div>

              <div>
                <h3 class="text-lg font-semibold text-gray-800">
                  {{ empleado.nombre_completo }}
                </h3>
                <p class="text-sm text-gray-500">
                  {{ empleado.puesto }}
                </p>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>