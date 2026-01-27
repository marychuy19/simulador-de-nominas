<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({
  status: {
    type: String,
  },
})

const bgImage = new URL('../image/contabilidad.jpeg', import.meta.url).href
const logoSrc = new URL('../image/logo.jpeg', import.meta.url).href
</script>

<template>
  <Head title="Recuperar contraseña" />

  <!-- CONTENEDOR GENERAL -->
  <div class="relative min-h-screen flex items-center justify-center overflow-hidden">

    <!-- FONDO -->
    <img
      :src="bgImage"
      alt="Contabilidad"
      class="absolute inset-0 w-full h-full object-cover"
    />

    <!-- OVERLAY -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-950/90 via-blue-900/85 to-black/80"></div>

    <!-- CARD -->
    <div
      class="relative z-10 w-full max-w-md
             bg-white/90 backdrop-blur-xl
             rounded-3xl shadow-2xl
             px-8 py-10 mx-4"
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
      <h1 class="text-3xl font-extrabold text-center text-gray-900">
        Recuperar contraseña
      </h1>

      <!-- MENSAJE -->
      <p class="mt-4 text-center text-gray-600 text-sm leading-relaxed">
        Comuníquese con el administrador del sistema para poder cambiar su contraseña.
      </p>

      <!-- STATUS -->
      <div
        v-if="status"
        class="mt-6 text-sm text-green-600 text-center font-medium"
      >
        {{ status }}
      </div>

      <!-- BOTÓN VOLVER -->
      <div class="mt-8">
        <Link
          :href="route('login')"
          class="block w-full text-center py-4 text-lg font-semibold
                 bg-blue-700 hover:bg-blue-800
                 text-white rounded-xl shadow-lg transition"
        >
          Volver al inicio de sesión
        </Link>
      </div>

      <!-- FOOTER -->
      <p class="mt-6 text-center text-xs text-gray-600">
        Acceso exclusivo para usuarios autorizados
      </p>
    </div>
  </div>
</template>

