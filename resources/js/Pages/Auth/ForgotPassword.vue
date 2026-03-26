<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'

defineProps({
  status: {
    type: String,
  },
})

const bgImage = new URL('../image/contabilidad.jpeg', import.meta.url).href
const logoSrc = new URL('../image/logo.jpeg', import.meta.url).href

const show = ref(false)

onMounted(() => {
  setTimeout(() => {
    show.value = true
  }, 100)
})
</script>

<template>
  <Head title="Recuperar contraseña" />

  <div class="min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- FONDO -->
    <img
      :src="bgImage"
      class="absolute inset-0 w-full h-full object-cover"
    />

    <!-- OVERLAY AZUL -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80"></div>

    <!-- DECORACIONES -->
    <div class="absolute w-40 h-40 md:w-72 md:h-72 bg-blue-400/20 rounded-full blur-3xl top-5 left-5 md:top-10 md:left-10"></div>
    <div class="absolute w-40 h-40 md:w-72 md:h-72 bg-blue-300/20 rounded-full blur-3xl bottom-5 right-5 md:bottom-10 md:right-10"></div>

    <!-- CARD -->
    <div
      :class="[
        'relative z-10 w-full max-w-md mx-4',
        'bg-white/10 backdrop-blur-lg rounded-2xl px-6 py-8 md:px-8 md:py-10 shadow-xl text-white text-center',
        'transform transition-all duration-1000',
        show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
      ]"
    >

      <!-- LOGO -->
      <div class="flex justify-center mb-6">
        <img
          :src="logoSrc"
          class="h-16 w-16 md:h-20 md:w-20 rounded-xl shadow-lg"
        />
      </div>

      <!-- TITULO -->
      <h1 class="text-2xl md:text-3xl font-bold
                 bg-gradient-to-r from-white to-blue-300
                 bg-clip-text text-transparent">
        Recuperar contraseña
      </h1>

      <!-- MENSAJE -->
      <p class="mt-4 text-sm md:text-base text-white/80 leading-relaxed">
        Comuníquese con el administrador del sistema para poder cambiar su contraseña.
      </p>

      <!-- STATUS -->
      <div
        v-if="status"
        class="mt-6 text-sm text-green-300 font-medium"
      >
        {{ status }}
      </div>

      <!-- BOTON -->
      <div class="mt-8">
        <Link
          :href="route('login')"
          class="inline-flex items-center justify-center
                 w-full px-6 py-2.5
                 bg-gradient-to-r from-blue-500 to-blue-700
                 text-white font-semibold text-sm md:text-base
                 rounded-full shadow-lg
                 hover:scale-105 hover:shadow-xl
                 transition-all duration-300"
        >
          Volver al inicio de sesión
        </Link>
      </div>

      <!-- FOOTER -->
      <p class="mt-6 text-xs text-white/60">
        Acceso exclusivo para usuarios autorizados
      </p>

    </div>

  </div>
</template>