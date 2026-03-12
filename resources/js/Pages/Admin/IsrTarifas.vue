<!-- resources/js/Pages/Admin/IsrTarifas.vue -->
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { router, useForm } from '@inertiajs/vue3'

/* IMÁGENES */
const avatarISR = new URL('../image/isr.jpeg', import.meta.url).href

const props = defineProps({
  tipo: String,
  tipos: Array,
  rows: Array,
})

const form = useForm({
  tipo: props.tipo,
  rows: (props.rows || []).map((r, idx) => ({
    id: r.id,
    limite_inferior: r.limite_inferior ?? 0,
    limite_superior: r.limite_superior ?? null,
    cuota_fija: r.cuota_fija ?? 0,
    porcentaje: r.porcentaje ?? 0,
    orden: r.orden ?? (idx + 1),
    activo: r.activo ?? true,
  })),
})

function cambiarTipo(e) {
  router.get(route('admin.isr.index'), { tipo: e.target.value }, { preserveScroll: true })
}

function addRow() {
  form.rows.push({
    id: null,
    limite_inferior: 0,
    limite_superior: null,
    cuota_fija: 0,
    porcentaje: 0,
    orden: form.rows.length + 1,
    activo: true,
  })
}

function removeRow(i) {
  form.rows.splice(i, 1)
  // reajusta orden
  form.rows.forEach((r, idx) => (r.orden = idx + 1))
}

function guardar() {
  form.post(route('admin.isr.save'), { preserveScroll: true })
}
</script>

<template>
  <AuthenticatedLayout>
<div class="py-10 bg-gradient-to-br from-blue-100 via-blue-200 to-blue-100 min-h-screen">
<div class="max-w-7xl mx-auto px-6 space-y-8">


        <!-- HEADER -->
        <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4">
          <div class="w-16 h-16 rounded-xl bg-white flex items-center justify-center shadow">
            <img
              :src="avatarISR"
              alt="ISR"
              class="w-full h-full object-contain rounded-xl"
            />
          </div>

          <div>
            <h1 class="text-2xl font-semibold text-gray-800">
              Actualizaciones de las tarifas del ISR
            </h1>
            <p class="text-gray-600 mt-1">
              Modifica las tablas de tarifa del ISR al año.         
             </p>
          </div>
        </div>

        <div class="flex items-center gap-3 p-5 flex items-center gap-5">
          <select class="rounded-xl border rounded px-5 py-2" :value="form.tipo" @change="cambiarTipo">
            <option v-for="t in tipos" :key="t" :value="t">{{ t }}</option>
          </select>

          <button class="rounded-xl bg-slate-900 text-white px-4 py-2 font-semibold hover:bg-slate-800" @click="addRow">
            + Agregar fila
          </button>

          <button
            class="rounded-xl bg-blue-700 text-white px-4 py-2 font-semibold hover:bg-blue-900"
            :disabled="form.processing"
            @click="guardar"
          >
            Guardar tabla
          </button>
        </div>
      </div>
<div class="overflow-auto bg-white rounded-2xl shadow-lg border border-gray-200 max-w-6xl mx-auto p-6">

  <table class="min-w-full text-sm">

    <thead class="bg-slate-100 text-gray-700">
      <tr class="text-left">
        <th class="p-3 font-semibold">Orden</th>
        <th class="p-3 font-semibold">Límite inferior</th>
        <th class="p-3 font-semibold">Límite superior</th>
        <th class="p-3 font-semibold">Cuota fija</th>
        <th class="p-3 font-semibold">% </th>
        <th class="p-3 font-semibold text-center">Activo</th>
        <th class="p-3 font-semibold">Acciones</th>
      </tr>
    </thead>

    <tbody>

      <tr
        v-for="(r, i) in form.rows"
        :key="i"
        class="border-t hover:bg-gray-50 transition"
      >

        <td class="p-2 w-24">
          <input
            v-model.number="r.orden"
            type="number"
            class="w-full border border-gray-300 rounded-lg p-2.5
            focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          />
        </td>

        <td class="p-2">
          <input
            v-model.number="r.limite_inferior"
            type="number"
            step="0.01"
            class="w-full border border-gray-300 rounded-lg p-2.5
            focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          />
        </td>

        <td class="p-2">
          <input
            v-model.number="r.limite_superior"
            type="number"
            step="0.01"
            class="w-full border border-gray-300 rounded-lg p-2.5
            focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
            placeholder="(vacío = en adelante)"
          />
        </td>

        <td class="p-2">
          <input
            v-model.number="r.cuota_fija"
            type="number"
            step="0.01"
            class="w-full border border-gray-300 rounded-lg p-2.5
            focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          />
        </td>

        <td class="p-2">
          <input
            v-model.number="r.porcentaje"
            type="number"
            step="0.0001"
            class="w-full border border-gray-300 rounded-lg p-2.5
            focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
          />
        </td>

        <td class="p-2 text-center">
          <input
            v-model="r.activo"
            type="checkbox"
            class="scale-110 accent-blue-600"
          />
        </td>

        <td class="p-2">
          <button
            class="bg-red-500 text-white px-4 py-2 rounded-lg
            hover:bg-red-600 transition shadow-sm hover:shadow-md"
            @click="removeRow(i)"
          >
            Eliminar
          </button>
        </td>

      </tr>

      <tr v-if="!form.rows.length">
        <td class="p-6 text-center text-gray-500" colspan="7">
          No hay filas. Agrega una.
        </td>
      </tr>

    </tbody>

  </table>

</div>

      <div v-if="form.errors.rows" class="bg-red-50 border border-red-200 text-red-700 p-3 rounded">
        {{ form.errors.rows }}
      </div>
    </div>
  </AuthenticatedLayout>
</template>