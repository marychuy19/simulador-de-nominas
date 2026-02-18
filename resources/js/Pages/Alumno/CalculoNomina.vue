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
    <div class="py-10 bg-gradient-to-br from-blue-100 via-blue-200 to-blue-100 min-h-screen">
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
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

  <!-- Trabajo diario -->
  <Link :href="route('alumno.nomina.diaria')" class="group block">
    <div class="relative bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-8 border-l-8 border-blue-800 overflow-hidden">

      <!-- Número decorativo -->
      <div class="absolute right-6 top-4 text-6xl font-bold text-blue-50 select-none">
        01
      </div>

      <h4 class="text-xl font-bold text-blue-900 mb-3">
        Trabajo diario
      </h4>

      <p class="text-gray-600 text-sm leading-relaxed mb-6">
        Cálculo de nómina por un día trabajado.
      </p>

      <div class="flex items-center justify-between">
        <div class="w-10 h-10">
          <img :src="imgTrabajo" class="w-full h-full object-contain" />
        </div>

        <span class="text-sm font-semibold text-blue-800 group-hover:underline">
          Acceder →
        </span>
      </div>

    </div>
  </Link>


  <!-- Periodo semanal -->
  <Link :href="route('alumno.nomina.semanal')" class="group block">
    <div class="relative bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-8 border-l-8 border-blue-800 overflow-hidden">

      <div class="absolute right-6 top-4 text-6xl font-bold text-blue-50 select-none">
        02
      </div>

      <h4 class="text-xl font-bold text-blue-900 mb-3">
        Periodo semanal
      </h4>

      <p class="text-gray-600 text-sm leading-relaxed mb-6">
         Cálculo de nómina por una semana trabajada.
      </p>

      <div class="flex items-center justify-between">
        <div class="w-10 h-10">
          <img :src="imgTrabajo" class="w-full h-full object-contain" />
        </div>

        <span class="text-sm font-semibold text-blue-800 group-hover:underline">
          Acceder →
        </span>
      </div>

    </div>
  </Link>


  <!-- Periodo 10 días -->
  <Link :href="route('alumno.nomina.decena')" class="group block">
    <div class="relative bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-8 border-l-8 border-blue-800 overflow-hidden">

      <div class="absolute right-6 top-4 text-6xl font-bold text-blue-50 select-none">
        03
      </div>

      <h4 class="text-xl font-bold text-blue-900 mb-3">
        Periodo 10 días
      </h4>

      <p class="text-gray-600 text-sm leading-relaxed mb-6">
        Cálculo de nómina para un periodo decenal.
      </p>

      <div class="flex items-center justify-between">
        <div class="w-10 h-10">
          <img :src="imgTrabajo" class="w-full h-full object-contain" />
        </div>

        <span class="text-sm font-semibold text-blue-800 group-hover:underline">
          Acceder →
        </span>
      </div>

    </div>
  </Link>


  <!-- Periodo quincenal -->
  <Link :href="route('alumno.nomina.quincenal')" class="group block">
    <div class="relative bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-8 border-l-8 border-blue-800 overflow-hidden">

      <div class="absolute right-6 top-4 text-6xl font-bold text-blue-50 select-none">
        04
      </div>

      <h4 class="text-xl font-bold text-blue-900 mb-3">
        Periodo quincenal
      </h4>

      <p class="text-gray-600 text-sm leading-relaxed mb-6">
        Calculo de nominas correspondiente a 15 días laborales.
      </p>

      <div class="flex items-center justify-between">
        <div class="w-10 h-10">
          <img :src="imgTrabajo" class="w-full h-full object-contain" />
        </div>

        <span class="text-sm font-semibold text-blue-800 group-hover:underline">
          Acceder →
        </span>
      </div>

    </div>
  </Link>


  <!-- Periodo mensual -->
  <Link :href="route('alumno.nomina.mensual')" class="group block">
    <div class="relative bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-8 border-l-8 border-blue-800 overflow-hidden">

      <div class="absolute right-6 top-4 text-6xl font-bold text-blue-50 select-none">
        05
      </div>

      <h4 class="text-xl font-bold text-blue-900 mb-3">
        Periodo mensual
      </h4>

      <p class="text-gray-600 text-sm leading-relaxed mb-6">
        Cálculo completo correspondiente a 30 días laborales.
      </p>

      <div class="flex items-center justify-between">
        <div class="w-10 h-10">
          <img :src="imgTrabajo" class="w-full h-full object-contain" />
        </div>

        <span class="text-sm font-semibold text-blue-800 group-hover:underline">
          Acceder →
        </span>
      </div>

    </div>
  </Link>

</div>



      </div>
    </div>
  </AuthenticatedLayout>
</template>
