<script setup>
import { Head, Link } from '@inertiajs/vue3'

const route = window.route
const bgImage = new URL('image/contabilidad.jpeg', import.meta.url).href
const logoSrc = new URL('image/logo.jpeg', import.meta.url).href
</script>

<template>
  <Head title="Bienvenido" />

  <!-- CONTENEDOR GENERAL -->
  <div class="relative min-h-screen flex items-center justify-center overflow-hidden">

    <!-- IMAGEN DE FONDO -->
    <img
      :src="bgImage"
      alt="Contabilidad"
      class="absolute inset-0 w-full h-full object-cover"
    />

    <!-- OVERLAY MÁS OSCURO Y AZUL -->
    <div class="absolute inset-0 bg-gradient-to-br bg-blue-800/70"></div>

    <!-- CARD PRINCIPAL -->
    <div
      class="relative z-10 w-full max-w-md
             bg-white/90 backdrop-blur-xl
             rounded-2xl shadow-2xl
             px-8 py-10 mx-4 text-center"
    >

      <!-- LOGO -->
      <div class="flex justify-center mb-6">
        <img
          :src="logoSrc"
          alt="Logo"
          class="h-24 w-24 rounded-2xl shadow-md"
        />
      </div>

      <!-- TÍTULO -->
      <h1 class="text-4xl font-extrabold text-gray-900 leading-tight">
        Simulador de
        <span class="block text-gray-900">Nóminas</span>
      </h1>

      <!-- DESCRIPCIÓN -->
      <p class="mt-4 text-gray-700 text-base">
        Plataforma moderna para la simulación, cálculo y control de nóminas,
        diseñada para estudiantes.
      </p>

      <!-- BOTÓN -->
      <div class="mt-8">

  <Link
    :href="route('login')"
    class="group inline-flex items-center justify-center gap-2
           w-full px-4 py-2
           bg-black hover:bg-gray-900
           text-white font-medium text-sm
           rounded-lg shadow
           transition"
  >
    Iniciar sesión
    <svg
      class="h-4 w-4 transform group-hover:translate-x-1 transition"
      viewBox="0 0 24 24"
      fill="none"
    >
      <path
        d="M5 12h14M13 5l7 7-7 7"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
    </svg>
  </Link>

</div>

      <!-- TEXTO INFERIOR -->
      <p class="mt-6 text-sm text-gray-600">
        Acceso exclusivo para usuarios autorizados
      </p>
    </div>
  </div>
</template>
