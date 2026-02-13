<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'

/* IMÁGENES */
const iconUsuarios = new URL('../image/usuarios.jpeg', import.meta.url).href
const iconAlumnos = new URL('../image/estudiantes.jpeg', import.meta.url).href
const iconAdmin = new URL('../image/admin.jpeg', import.meta.url).href

const props = defineProps({
  users: {
    type: Array,
    default: () => []
  }
})

/* IMAGEN */
const avatarEmpleado = new URL('../image/user.jpeg', import.meta.url).href

const totalUsuarios = computed(() => props.users.length)

const totalAlumnos = computed(() =>
  props.users.filter(u => u.role === 'alumno').length
)

const totalAdmins = computed(() =>
  props.users.filter(u => u.role === 'admin').length
)

</script>

<template>
  <Head title="Panel Administrador" />

  <AuthenticatedLayout>
    <div class="py-10 bg-gradient-to-br from-blue-100 via-blue-200 to-blue-100 min-h-screen">
      <div class="max-w-7xl mx-auto px-6 space-y-8">

        <!-- HEADER -->
<div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4">
  
  <div class="w-14 h-14 rounded-xl overflow-hidden shadow">
    <img
      :src="avatarEmpleado"
      alt="Usuarios"
      class="w-full h-full object-cover"
    />
  </div>

  <div>
    <h1 class="text-2xl font-bold text-gray-800">
      Panel de Administración
    </h1>
    <p class="text-gray-600">
      Resumen general de usuarios registrados en el sistema
    </p>
  </div>

</div>


        <!-- ================= RESUMEN USUARIOS ================= -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

          <!-- TOTAL -->
<div class="bg-white rounded-2xl shadow-lg p-8 flex items-center justify-between hover:scale-105 transition">
  <div>
    <p class="text-sm text-gray-500 uppercase tracking-wide">
      Usuarios Totales
    </p>
    <p class="text-4xl font-bold text-blue-700 mt-2">
      {{ totalUsuarios }}
    </p>
  </div>

  <div class="w-16 h-16 rounded-2xl overflow-hidden shadow">
    <img
      :src="iconUsuarios"
      alt="Usuarios"
      class="w-full h-full object-cover"
    />
  </div>
</div>



          <!-- ALUMNOS -->
<div class="bg-white rounded-2xl shadow-lg p-8 flex items-center justify-between hover:scale-105 transition">
  <div>
    <p class="text-sm text-gray-500 uppercase tracking-wide">
      Alumnos
    </p>
    <p class="text-4xl font-bold text-green-600 mt-2">
      {{ totalAlumnos }}
    </p>
  </div>

  <div class="w-16 h-16 rounded-2xl overflow-hidden shadow">
    <img
      :src="iconAlumnos"
      alt="Alumnos"
      class="w-full h-full object-cover"
    />
  </div>
</div>


          <!-- ADMIN -->
<div class="bg-white rounded-2xl shadow-lg p-8 flex items-center justify-between hover:scale-105 transition">
  <div>
    <p class="text-sm text-gray-500 uppercase tracking-wide">
      Administradores
    </p>
    <p class="text-4xl font-bold text-purple-600 mt-2">
      {{ totalAdmins }}
    </p>
  </div>

  <div class="w-16 h-16 rounded-2xl overflow-hidden shadow">
    <img
      :src="iconAdmin"
      alt="Administradores"
      class="w-full h-full object-cover"
    />
  </div>
</div>


        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>
