<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
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
          <div
            class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-600 to-blue-800
                   flex items-center justify-center text-white text-2xl font-bold shadow"
          >
            $
          </div>

          <div>
            <h3 class="text-2xl font-semibold text-gray-800">
              ¡Bienvenido al Simulador de Nóminas!
            </h3>
            <p class="text-gray-600 mt-1">
              Accede a las funciones disponibles según tu rol.
            </p>
          </div>
        </div>

        <!-- TARJETAS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

          <!-- PERFIL -->
          <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition">
            <div
              class="w-12 h-12 rounded-lg bg-blue-100 text-blue-700
                     flex items-center justify-center text-xl mb-4"
            >
              👤
            </div>
            <h4 class="text-lg font-semibold text-gray-800">
              Perfil
            </h4>
            <p class="text-gray-600 text-sm mt-2">
              Consulta y administra tu información personal.
            </p>
          </div>

          <!-- NÓMINA -->
          <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition">
            <div
              class="w-12 h-12 rounded-lg bg-blue-100 text-blue-700
                     flex items-center justify-center text-xl mb-4"
            >
              📊
            </div>
            <h4 class="text-lg font-semibold text-gray-800">
              Simulación de Nómina
            </h4>
            <p class="text-gray-600 text-sm mt-2">
              Realiza simulaciones de nómina de forma sencilla.
            </p>
          </div>

          <!-- ADMIN -->
          <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl transition">
            <div
              class="w-12 h-12 rounded-lg bg-blue-100 text-blue-700
                     flex items-center justify-center text-xl mb-4"
            >
              ⚙️
            </div>
            <h4 class="text-lg font-semibold text-gray-800">
              Administración
            </h4>
            <p class="text-gray-600 text-sm mt-2">
              Gestión de usuarios y configuraciones del sistema.
            </p>
          </div>

        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
