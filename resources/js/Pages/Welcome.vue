<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'

const route = window.route
const logoSrc = new URL('image/logo.jpeg', import.meta.url).href
const bgImage = new URL('image/contabilidad.jpeg', import.meta.url).href

const show = ref(false)

onMounted(() => {
  setTimeout(() => {
    show.value = true
  }, 100)
})
</script>

<template>
  <Head title="Bienvenido" />

  <div class="min-h-screen flex flex-col relative overflow-hidden">

    <!-- IMAGEN DE FONDO -->
    <img
      :src="bgImage"
      alt="Fondo"
      class="absolute inset-0 w-full h-full object-cover"
    />

    <!-- OVERLAY -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80"></div>

    <!-- DECORACIONES (más pequeñas en móvil) -->
    <div class="absolute w-40 h-40 md:w-72 md:h-72 bg-blue-400/20 rounded-full blur-3xl top-5 left-5 md:top-10 md:left-10"></div>
    <div class="absolute w-40 h-40 md:w-72 md:h-72 bg-blue-300/20 rounded-full blur-3xl bottom-5 right-5 md:bottom-10 md:right-10"></div>

    <!-- CONTENIDO -->
    <div class="relative z-10 flex flex-col min-h-screen text-white">

      <!-- HEADER -->
      <header class="flex items-center justify-between px-4 md:px-8 py-4 bg-white/10 backdrop-blur-md">
        <div class="flex items-center gap-2">
          <img :src="logoSrc" class="h-8 w-8 md:h-10 md:w-10 rounded" />
        </div>
      </header>

      <!-- HERO -->
      <div class="flex-1 flex items-center justify-center text-center px-4 sm:px-6">

        <!-- CARD -->
        <div
          :class="[
            'w-full max-w-lg md:max-w-2xl lg:max-w-3xl space-y-5 md:space-y-6',
            'bg-white/10 backdrop-blur-lg rounded-2xl px-5 py-8 md:px-8 md:py-10 shadow-xl',
            'transform transition-all duration-1000',
            show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
          ]"
        >

          <!-- TITULO RESPONSIVO -->
          <h1 class="text-3xl sm:text-4xl md:text-6xl lg:text-7xl font-extrabold tracking-wide
                     bg-gradient-to-r from-white to-blue-300
                     bg-clip-text text-transparent leading-tight">
            BIENVENIDO
          </h1>

          <!-- DESCRIPCION -->
          <p class="text-xs sm:text-sm md:text-base text-white/90 leading-relaxed px-2 md:px-0">
            Plataforma moderna para la simulación, cálculo y control de nóminas.
            Diseñada para estudiantes que desean aprender de forma práctica.
          </p>

          <!-- BOTON -->
          <div class="pt-2 md:pt-4">
            <Link
              :href="route('login')"
              class="inline-flex items-center justify-center gap-2
                     w-full sm:w-auto
                     px-6 md:px-8 py-2.5 md:py-3
                     bg-gradient-to-r from-blue-500 to-blue-700
                     text-white text-sm md:text-base font-semibold
                     rounded-full shadow-lg
                     hover:scale-105 hover:shadow-xl
                     transition-all duration-300"
            >
              Iniciar sesión
            </Link>
          </div>

        </div>

      </div>

    </div>
  </div>
</template>