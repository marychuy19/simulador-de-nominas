<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
</script>

<template>
  <Head title="Recibo de Nómina" />

  <AuthenticatedLayout>
    <!-- HEADER -->
    <template #header>
      <h2 class="text-2xl font-bold text-blue-900">
        Recibo de Nómina
      </h2>
    </template>

    <!-- CONTENIDO -->
    <div class="py-10 bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen">
      <div class="max-w-6xl mx-auto px-6">

        <div class="bg-white rounded-2xl shadow-lg p-8">
          <!-- TÍTULO -->
          <div class="flex items-center gap-4 mb-6">
            <div
              class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-600 to-blue-800
                     flex items-center justify-center text-white text-2xl font-bold shadow"
            >
              🧾
            </div>

            <div>
              <h3 class="text-xl font-semibold text-gray-800">
                Recibo de Nómina
              </h3>
              <p class="text-gray-600 text-sm">
                Visualiza y descarga tu recibo de nómina.
              </p>
            </div>
          </div>

          <!-- CONTENIDO BASE -->
          <div
            class="border border-dashed border-blue-300 rounded-xl p-10
                   text-center text-gray-500"
          >
            Aquí se mostrará el recibo de nómina generado.
          </div>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>
