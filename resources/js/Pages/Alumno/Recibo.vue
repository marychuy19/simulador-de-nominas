<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

/* IMÁGENES */
const logoFactura = new URL('../image/factura.jpeg', import.meta.url).href

const props = defineProps({
  calculos: Object,
  filters: Object,
})

const money = (n) =>
  new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(Number(n || 0))

const search = ref(props.filters?.search || '')
const empresa = ref(props.filters?.empresa || '')
const tipo = ref(props.filters?.tipo || '')

const submitFilters = () => {
  router.get(route('alumno.recibo'), {
    search: search.value || null,
    empresa: empresa.value || null,
    tipo: tipo.value || null,
  }, { preserveState: true, replace: true })
}

const clearFilters = () => {
  search.value = ''
  empresa.value = ''
  tipo.value = ''
  submitFilters()
}

const downloadPdf = (id) => window.location.href = route('alumno.recibo.pdf', id)
const downloadExcel = (id) => window.location.href = route('alumno.recibo.excel', id)

const downloadAllPdf = () => {
  window.location.href = route('alumno.recibo.pdfAll', {
    search: search.value || null,
    empresa: empresa.value || null,
    tipo: tipo.value || null,
  })
}

const downloadAllExcel = () => {
  window.location.href = route('alumno.recibo.excelAll', {
    search: search.value || null,
    empresa: empresa.value || null,
    tipo: tipo.value || null,
  })
}

const borrar = (id) => {
  if (!confirm('¿Seguro que deseas borrar esta nómina?')) return
  router.delete(route('alumno.recibo.destroy', id), { preserveScroll: true })
}
</script>

<template>
  <Head title="Recibos de Nómina" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-2xl font-bold text-slate-900">Recibos de Nómina</h2>
    </template>

    <div class="py-10 bg-gradient-to-br from-blue-100 via-blue-200 to-blue-100 min-h-screen">
      <div class="max-w-7xl mx-auto px-6 space-y-6">
         <!-- BIENVENIDA -->
       <div class="bg-white rounded-2xl shadow-lg p-8 flex items-center gap-6 mb-10">


          <!-- LOGO -->
          <div
            class="w-16 h-16 rounded-xl bg-white flex items-center justify-center shadow"
          >
            <img
              :src="logoFactura"
              alt="Logo"
              class="w-full h-full object-contain rounded-xl"
            />
          </div>

          <div>
            <h3 class="text-2xl font-semibold text-gray-800">
                 Recibos de Nómina
            </h3>
            <p class="text-gray-600 mt-1">
              Gestión y descarga de recibos generados en el sistema.
            </p>
          </div>
        </div>

        <!-- Filtros + Acciones globales -->
        <div class="bg-white rounded-2xl shadow p-5 space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input v-model="search" @keyup.enter="submitFilters"
              class="rounded-xl border-slate-300 focus:border-slate-500 focus:ring-slate-500"
              placeholder="Buscar empleado o empresa..." />

            <input v-model="empresa" @keyup.enter="submitFilters"
              class="rounded-xl border-slate-300 focus:border-slate-500 focus:ring-slate-500"
              placeholder="Filtrar por empresa (texto)..." />

            <select v-model="tipo" @change="submitFilters"
              class="rounded-xl border-slate-300 focus:border-slate-500 focus:ring-slate-500">
              <option value="">Todos los tipos</option>
              <option value="diario">Diario</option>
              <option value="semanal">Semanal</option>
              <option value="10_dias">Decena (10 días)</option>
              <option value="quincenal">Quincenal</option>
              <option value="mensual">Mensual</option>
            </select>

            <div class="flex gap-2">
              <button @click="submitFilters"
                class="w-full rounded-xl bg-slate-900 text-white px-4 py-2 font-semibold hover:bg-slate-800">
                Buscar
              </button>
              <button @click="clearFilters"
                class="w-full rounded-xl border border-slate-300 px-4 py-2 font-semibold hover:bg-slate-50">
                Limpiar
              </button>
            </div>
          </div>

          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="text-sm text-slate-600">
              Mostrando {{ props.calculos?.data?.length || 0 }} de {{ props.calculos?.total || 0 }} registros
            </div>

            <div class="flex gap-2">
              <button @click="downloadAllPdf"
                class="rounded-xl bg-blue-600 text-white px-4 py-2 font-semibold hover:bg-blue-700">
                Descargar TODAS (PDF)
              </button>
              <button @click="downloadAllExcel"
                class="rounded-xl bg-emerald-600 text-white px-4 py-2 font-semibold hover:bg-emerald-700">
                Descargar TODAS (Excel)
              </button>
            </div>
          </div>
        </div>

        <!-- Tabla -->
        <div class="bg-white rounded-2xl shadow overflow-hidden">
          <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full text-sm">
              <thead class="bg-slate-50 text-slate-700">
                <tr>
                  <th class="text-left px-4 py-3">ID</th>
                  <th class="text-left px-4 py-3">Empresa</th>
                  <th class="text-left px-4 py-3">Empleado</th>
                  <th class="text-left px-4 py-3">Tipo</th>
                  <th class="text-right px-4 py-3">Sal. Diario</th>
                  <th class="text-right px-4 py-3">IMSS</th>
                  <th class="text-right px-4 py-3">ISR</th>
                  <th class="text-left px-4 py-3">Fecha</th>
                  <th class="text-right px-4 py-3">Acciones</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="c in props.calculos.data" :key="c.id" class="border-t">
                  <td class="px-4 py-3 font-semibold">{{ c.id }}</td>
                  <td class="px-4 py-3">
                    {{ c.empleado?.empresa?.nombre_razon_social || '—' }}
                  </td>
                  <td class="px-4 py-3">
                    {{ c.empleado?.nombre_completo || '—' }}
                  </td>
                  <td class="px-4 py-3 uppercase text-xs font-bold">
                    {{ c.empleado?.periodo_salario || '—' }}
                  </td>
                  <td class="px-4 py-3 text-right">{{ money(c.salario_diario) }}</td>
                  <td class="px-4 py-3 text-right">{{ money(c.total_imss) }}</td>
                  <td class="px-4 py-3 text-right">
                    {{ money(c.empleado?.latest_isr?.isr_retener || 0) }}
                  </td>
                  <td class="px-4 py-3">
                    {{ c.created_at ? new Date(c.created_at).toLocaleString() : '—' }}
                  </td>

                  <td class="px-4 py-3">
                    <div class="flex justify-end gap-2">
                      <button @click="downloadPdf(c.id)"
                        class="rounded-lg bg-blue-600 text-white px-3 py-1 font-semibold hover:bg-blue-700">
                        PDF
                      </button>
                      <button @click="downloadExcel(c.id)"
                        class="rounded-lg bg-emerald-600 text-white px-3 py-1 font-semibold hover:bg-emerald-700">
                        Excel
                      </button>
                      <button @click="borrar(c.id)"
                        class="rounded-lg bg-red-500  text-white px-3 py-1 font-semibold hover:bg-red-600">
                        Borrar
                      </button>
                    </div>
                  </td>
                </tr>

                <tr v-if="!props.calculos.data.length">
                  <td colspan="9" class="px-4 py-10 text-center text-slate-500">
                    No hay nóminas para mostrar.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

         
        </div>
        <div class="md:hidden space-y-4 p-4">
  <div
    v-for="c in props.calculos.data"
    :key="c.id"
    class="bg-white rounded-2xl shadow p-4 space-y-2"
  >
    <div class="flex justify-between">
      <span class="font-bold">#{{ c.id }}</span>
      <span class="text-xs uppercase font-semibold">
        {{ c.empleado?.periodo_salario }}
      </span>
    </div>

    <div class="text-sm text-gray-600">
      <div><strong>Empresa:</strong> {{ c.empleado?.empresa?.nombre_razon_social }}</div>
      <div><strong>Empleado:</strong> {{ c.empleado?.nombre_completo }}</div>
      <div><strong>Salario:</strong> {{ money(c.salario_diario) }}</div>
      <div><strong>IMSS:</strong> {{ money(c.total_imss) }}</div>
      <div><strong>ISR:</strong> {{ money(c.empleado?.latest_isr?.isr_retener || 0) }}</div>
    </div>

    <div class="flex flex-col gap-2 pt-2">
      <button
        @click="downloadPdf(c.id)"
        class="w-full bg-blue-600 text-white rounded-xl py-2 text-sm font-semibold"
      >
        Descargar PDF
      </button>

      <button
        @click="downloadExcel(c.id)"
        class="w-full bg-emerald-600 text-white rounded-xl py-2 text-sm font-semibold"
      >
        Descargar Excel
      </button>

      <button
        @click="borrar(c.id)"
        class="w-full bg-red-500 text-white rounded-xl py-2 text-sm font-semibold"
      >
        Borrar
      </button>
    </div>
  </div>
</div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>