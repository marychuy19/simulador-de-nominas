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

         <!-- HEADER -->
<div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 flex flex-col sm:flex-row items-center sm:items-start gap-4">

  <!-- ICONO -->
  <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-white flex items-center justify-center shadow shrink-0">
    <img
      :src="imgLogo2"
      alt="ISR"
      class="w-full h-full object-contain rounded-xl"
    />
  </div>

  <!-- TEXTO -->
  <div class="text-center sm:text-left">
    <h1 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-800 leading-tight">
       Cálculo de nóminas
    </h1>
    <p class="text-gray-600 mt-1 text-sm sm:text-base">
      Cálculo de nómina por días trabajados.
    </p>
  </div>

</div>
<!-- TARJETAS -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6 xl:gap-8">

  <!-- CARD -->
  <Link :href="route('alumno.nomina.diaria')" class="group block">
    <div class="relative h-full flex flex-col justify-between
                bg-white rounded-2xl shadow-md hover:shadow-xl
                transition-all duration-300
                p-5 sm:p-6 lg:p-8
                border-l-4 sm:border-l-8 border-blue-800
                overflow-hidden hover:-translate-y-1">

      <!-- Número -->
      <div class="absolute right-4 top-2 sm:right-6 sm:top-4
                  text-4xl sm:text-5xl lg:text-6xl
                  font-bold text-blue-50 select-none">
        01
      </div>

      <!-- CONTENIDO -->
      <div>
        <h4 class="text-lg sm:text-xl font-bold text-blue-900 mb-2 sm:mb-3">
          Trabajo diario
        </h4>

        <p class="text-gray-600 text-xs sm:text-sm leading-relaxed mb-4 sm:mb-6">
          Cálculo de nómina por un día trabajado.
        </p>
      </div>

      <!-- FOOTER -->
      <div class="flex items-center justify-between mt-auto">

        <div class="w-8 h-8 sm:w-10 sm:h-10">
          <img :src="imgTrabajo" class="w-full h-full object-contain" />
        </div>

        <span class="text-xs sm:text-sm font-semibold text-blue-800 group-hover:underline">
          Acceder →
        </span>

      </div>

    </div>
  </Link>


  <!-- Semanal -->
  <Link :href="route('alumno.nomina.semanal')" class="group block">
    <div class="relative h-full flex flex-col justify-between
                bg-white rounded-2xl shadow-md hover:shadow-xl
                transition-all duration-300
                p-5 sm:p-6 lg:p-8
                border-l-4 sm:border-l-8 border-blue-800
                overflow-hidden hover:-translate-y-1">

      <div class="absolute right-4 top-2 sm:right-6 sm:top-4 text-4xl sm:text-5xl lg:text-6xl font-bold text-blue-50">
        02
      </div>

      <div>
        <h4 class="text-lg sm:text-xl font-bold text-blue-900 mb-2 sm:mb-3">
          Periodo semanal
        </h4>

        <p class="text-gray-600 text-xs sm:text-sm mb-4 sm:mb-6">
          Cálculo de nómina por una semana trabajada.
        </p>
      </div>

      <div class="flex items-center justify-between mt-auto">
        <div class="w-8 h-8 sm:w-10 sm:h-10">
          <img :src="imgTrabajo" />
        </div>
        <span class="text-xs sm:text-sm font-semibold text-blue-800 group-hover:underline">
          Acceder →
        </span>
      </div>
    </div>
  </Link>
  <!-- Decenal -->
  <Link :href="route('alumno.nomina.decena')" class="group block">
    <div class="relative h-full flex flex-col justify-between
                bg-white rounded-2xl shadow-md hover:shadow-xl
                transition-all duration-300
                p-5 sm:p-6 lg:p-8
                border-l-4 sm:border-l-8 border-blue-800
                overflow-hidden hover:-translate-y-1">

      <div class="absolute right-4 top-2 sm:right-6 sm:top-4 text-4xl sm:text-5xl lg:text-6xl font-bold text-blue-50">
        03
      </div>

      <div>
        <h4 class="text-lg sm:text-xl font-bold text-blue-900 mb-2 sm:mb-3">
          Periodo qo dias
        </h4>

        <p class="text-gray-600 text-xs sm:text-sm mb-4 sm:mb-6">
          Cálculo de nómina para un periodo decenal.
        </p>
      </div>

      <div class="flex items-center justify-between mt-auto">
        <div class="w-8 h-8 sm:w-10 sm:h-10">
          <img :src="imgTrabajo" />
        </div>
        <span class="text-xs sm:text-sm font-semibold text-blue-800 group-hover:underline">
          Acceder →
        </span>
      </div>
    </div>
  </Link>
  <!-- Quincenal -->
  <Link :href="route('alumno.nomina.quincenal')" class="group block">
    <div class="relative h-full flex flex-col justify-between
                bg-white rounded-2xl shadow-md hover:shadow-xl
                transition-all duration-300
                p-5 sm:p-6 lg:p-8
                border-l-4 sm:border-l-8 border-blue-800
                overflow-hidden hover:-translate-y-1">

      <div class="absolute right-4 top-2 sm:right-6 sm:top-4 text-4xl sm:text-5xl lg:text-6xl font-bold text-blue-50">
        04
      </div>

      <div>
        <h4 class="text-lg sm:text-xl font-bold text-blue-900 mb-2 sm:mb-3">
          Periodo quincenal
        </h4>

        <p class="text-gray-600 text-xs sm:text-sm mb-4 sm:mb-6">
          Cálculo de nóminas correspondiante a 15 dias laborales.
        </p>
      </div>

      <div class="flex items-center justify-between mt-auto">
        <div class="w-8 h-8 sm:w-10 sm:h-10">
          <img :src="imgTrabajo" />
        </div>
        <span class="text-xs sm:text-sm font-semibold text-blue-800 group-hover:underline">
          Acceder →
        </span>
      </div>
    </div>
  </Link>
  <!-- Mensual -->
  <Link :href="route('alumno.nomina.mensual')" class="group block">
    <div class="relative h-full flex flex-col justify-between
                bg-white rounded-2xl shadow-md hover:shadow-xl
                transition-all duration-300
                p-5 sm:p-6 lg:p-8
                border-l-4 sm:border-l-8 border-blue-800
                overflow-hidden hover:-translate-y-1">

      <div class="absolute right-4 top-2 sm:right-6 sm:top-4 text-4xl sm:text-5xl lg:text-6xl font-bold text-blue-50">
        05
      </div>

      <div>
        <h4 class="text-lg sm:text-xl font-bold text-blue-900 mb-2 sm:mb-3">
          Periodo mensual
        </h4>

        <p class="text-gray-600 text-xs sm:text-sm mb-4 sm:mb-6">
          Cálculo de nómina por un mes o 30 dias de trabajo.
        </p>
      </div>

      <div class="flex items-center justify-between mt-auto">
        <div class="w-8 h-8 sm:w-10 sm:h-10">
          <img :src="imgTrabajo" />
        </div>
        <span class="text-xs sm:text-sm font-semibold text-blue-800 group-hover:underline">
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
