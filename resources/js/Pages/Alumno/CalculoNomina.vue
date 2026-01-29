<<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

// ===============================
// IMÁGENES LOCALES
// ===============================
const imgTrabajo = new URL('../image/trabajo.jpeg', import.meta.url).href
const imgLogo2 = new URL('../image/logo2.jpeg', import.meta.url).href
</script>

<template>
  <Head title="Inicio" />

  <AuthenticatedLayout>
    <!-- HEADER DEL LAYOUT -->
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-blue-900">
          Inicio
        </h2>
      </div>
    </template>

    <!-- CONTENIDO -->
    <div class="py-10 bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen">
      <div class="max-w-7xl mx-auto px-6 space-y-8">

        <!-- BIENVENIDA -->
        <div class="bg-white rounded-2xl shadow-lg p-8 flex items-center gap-6">

          <!-- LOGO -->
          <div
            class="w-16 h-16 rounded-xl bg-white flex items-center justify-center shadow"
          >
            <img
              :src="imgLogo2"
              alt="Logo"
              class="w-full h-full object-contain rounded-xl"
            />
          </div>

          <div>
            <h3 class="text-2xl font-semibold text-gray-800">
              Cálculo de nóminas
            </h3>
            <p class="text-gray-600 mt-1">
              Cálculo de nómina por días trabajados.
            </p>
          </div>
        </div>

        <!-- TARJETAS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

          <!-- Trabajo diario -->
          <Link :href="route('alumno.nomina.diaria')" class="block">
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition cursor-pointer">
              <div class="w-12 h-12 mb-4">
                <img :src="imgTrabajo" alt="Trabajo diario" class="w-full h-full object-contain" />
              </div>
              <h4 class="text-lg font-semibold text-gray-800">
                Trabajo realizado y número de días laborales
              </h4>
              <p class="text-gray-600 text-sm mt-2">
                Cálculo de nómina de tus empleados por un día de trabajo realizado.
              </p>
            </div>
          </Link>

          <!-- Periodo semanal -->
          <Link :href="route('alumno.nomina.semanal')" class="block">
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition cursor-pointer">
              <div class="w-12 h-12 mb-4">
                <img :src="imgTrabajo" alt="Periodo semanal" class="w-full h-full object-contain" />
              </div>
              <h4 class="text-lg font-semibold text-gray-800">
                Periodo semanal
              </h4>
              <p class="text-gray-600 text-sm mt-2">
                Calcula la nómina de tus empleados en un periodo de 7 días.
              </p>
            </div>
          </Link>

          <!-- Periodo 10 días -->
          <Link :href="route('alumno.nomina.decena')" class="block">
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition cursor-pointer">
              <div class="w-12 h-12 mb-4">
                <img :src="imgTrabajo" alt="Periodo de 10 días" class="w-full h-full object-contain" />
              </div>
              <h4 class="text-lg font-semibold text-gray-800">
                Periodo de 10 días
              </h4>
              <p class="text-gray-600 text-sm mt-2">
                Calcula la nómina de tus empleados en un periodo de 10 días.
              </p>
            </div>
          </Link>

          <!-- Periodo quincenal -->
          <Link :href="route('alumno.nomina.quincenal')" class="block">
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition cursor-pointer">
              <div class="w-12 h-12 mb-4">
                <img :src="imgTrabajo" alt="Periodo quincenal" class="w-full h-full object-contain" />
              </div>
              <h4 class="text-lg font-semibold text-gray-800">
                Periodo quincenal
              </h4>
              <p class="text-gray-600 text-sm mt-2">
                Calcula la nómina de tus empleados en un periodo de 15 días.
              </p>
            </div>
          </Link>

          <!-- Periodo mensual -->
          <Link :href="route('alumno.nomina.mensual')" class="block">
            <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition cursor-pointer">
              <div class="w-12 h-12 mb-4">
                <img :src="imgTrabajo" alt="Periodo mensual" class="w-full h-full object-contain" />
              </div>
              <h4 class="text-lg font-semibold text-gray-800">
                Periodo mensual
              </h4>
              <p class="text-gray-600 text-sm mt-2">
                Calcula la nómina de tus empleados en un periodo de 30 días.
              </p>
            </div>
          </Link>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
