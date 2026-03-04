<!-- resources/js/Pages/Admin/IsrTarifas.vue -->
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { router, useForm } from '@inertiajs/vue3'

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
    <div class="max-w-6xl mx-auto py-10 space-y-6">
      <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-black text-slate-900">Tabla ISR</h1>
          <p class="text-slate-600">Edita renglones como Excel y guarda todo de una vez.</p>
        </div>

        <div class="flex items-center gap-3">
          <select class="border rounded px-3 py-2" :value="form.tipo" @change="cambiarTipo">
            <option v-for="t in tipos" :key="t" :value="t">{{ t }}</option>
          </select>

          <button class="bg-slate-800 text-white px-4 py-2 rounded" @click="addRow">
            + Agregar fila
          </button>

          <button
            class="bg-blue-600 text-white px-5 py-2 rounded disabled:opacity-60"
            :disabled="form.processing"
            @click="guardar"
          >
            Guardar tabla
          </button>
        </div>
      </div>

      <div class="overflow-auto bg-white rounded shadow border">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-100">
            <tr class="text-left">
              <th class="p-3">Orden</th>
              <th class="p-3">Límite inferior</th>
              <th class="p-3">Límite superior</th>
              <th class="p-3">Cuota fija</th>
              <th class="p-3">% (ej. 1.92)</th>
              <th class="p-3">Activo</th>
              <th class="p-3">Acciones</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="(r, i) in form.rows" :key="i" class="border-t">
              <td class="p-2 w-24">
                <input v-model.number="r.orden" type="number" class="w-full border rounded p-2" />
              </td>

              <td class="p-2">
                <input v-model.number="r.limite_inferior" type="number" step="0.01" class="w-full border rounded p-2" />
              </td>

              <td class="p-2">
                <input
                  v-model.number="r.limite_superior"
                  type="number"
                  step="0.01"
                  class="w-full border rounded p-2"
                  placeholder="(vacío = en adelante)"
                />
              </td>

              <td class="p-2">
                <input v-model.number="r.cuota_fija" type="number" step="0.01" class="w-full border rounded p-2" />
              </td>

              <td class="p-2">
                <input v-model.number="r.porcentaje" type="number" step="0.0001" class="w-full border rounded p-2" />
              </td>

              <td class="p-2 text-center">
                <input v-model="r.activo" type="checkbox" class="scale-110" />
              </td>

              <td class="p-2">
                <button class="bg-red-600 text-white px-3 py-2 rounded" @click="removeRow(i)">
                  Eliminar
                </button>
              </td>
            </tr>

            <tr v-if="!form.rows.length">
              <td class="p-4 text-center text-slate-500" colspan="7">
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