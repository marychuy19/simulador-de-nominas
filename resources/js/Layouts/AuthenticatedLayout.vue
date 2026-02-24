<script setup>
import { ref } from 'vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import { Link } from '@inertiajs/vue3'

const sidebarOpen = ref(false)

/* LOGO */
const logoSrc = new URL('../Pages/image/logo.jpeg', import.meta.url).href

/* ICONOS MENU */
const iconInicio = new URL('../Pages/image/inicio.jpeg', import.meta.url).href
const iconUsuarios = new URL('../Pages/image/usuario.jpeg', import.meta.url).href
const iconEmpleado = new URL('../Pages/image/empleado.jpeg', import.meta.url).href
const iconCalculo = new URL('../Pages/image/calculo.jpeg', import.meta.url).href
const iconRecibo = new URL('../Pages/image/recibo.jpeg', import.meta.url).href
</script>

<template>
  <div class="min-h-screen bg-gray-100 flex">

    <!-- SIDEBAR -->
    <aside
      :class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        'fixed z-50 inset-y-0 left-0 w-64 bg-blue-800 text-white transform transition-transform duration-300 ease-in-out',
        'lg:translate-x-0 lg:static lg:inset-0'
      ]"
    >

      <!-- Logo -->
      <div class="flex items-center justify-center h-16 border-b border-blue-800">
        <img :src="logoSrc" class="h-12 w-12 rounded-xl shadow-md object-cover" />
      </div>

      <!-- Menu -->
      <nav class="mt-6 px-4 space-y-2">

        <!-- INICIO -->
        <NavLink
          :href="route('dashboard')"
          :active="route().current('dashboard')"
          class="block"
        >
          <div
            :class="[
              'flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200',
              route().current('dashboard')
                ? 'bg-white text-black'
                : 'text-white hover:bg-blue-500/70'
            ]"
          >
            <img :src="iconInicio" class="w-5 h-5" />
            <span class="font-medium">Inicio</span>
          </div>
        </NavLink>

        <!-- ADMIN -->
        <NavLink
          v-if="$page.props.auth.user.role === 'admin'"
          :href="route('admin.usuarios')"
          :active="route().current('admin.usuarios')"
          class="block"
        >
          <div
            :class="[
              'flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200',
              route().current('admin.usuarios')
                ? 'bg-white text-black'
                : 'text-white hover:bg-blue-500/70'
            ]"
          >
            <img :src="iconUsuarios" class="w-5 h-5" />
            <span class="font-medium">Usuarios</span>
          </div>
        </NavLink>

        <!-- ALUMNO -->
        <template v-if="$page.props.auth.user.role === 'alumno'">

          <NavLink
            :href="route('alumno.empleado')"
            :active="route().current('alumno.empleado')"
            class="block"
          >
            <div
              :class="[
                'flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200',
                route().current('alumno.empleado')
                  ? 'bg-white text-black'
                  : 'text-white hover:bg-blue-500/70'
              ]"
            >
              <img :src="iconEmpleado" class="w-5 h-5" />
              <span class="font-medium">Empleado</span>
            </div>
          </NavLink>

          <NavLink
            :href="route('alumno.calculo-nomina')"
            :active="route().current('alumno.calculo-nomina')"
            class="block"
          >
            <div
              :class="[
                'flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200',
                route().current('alumno.calculo-nomina')
                  ? 'bg-white text-black'
                  : 'text-white hover:bg-blue-500/70'
              ]"
            >
              <img :src="iconCalculo" class="w-5 h-5" />
              <span class="font-medium">Cálculo de nómina</span>
            </div>
          </NavLink>

          <NavLink
            :href="route('alumno.recibo')"
            :active="route().current('alumno.recibo')"
            class="block"
          >
            <div
              :class="[
                'flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200',
                route().current('alumno.recibo')
                  ? 'bg-white text-black'
                  : 'text-white hover:bg-blue-500/70'
              ]"
            >
              <img :src="iconRecibo" class="w-5 h-5" />
              <span class="font-medium">Factura de nómina</span>
            </div>
          </NavLink>

        </template>

      </nav>
    </aside>

    <!-- OVERLAY (móvil y tablet) -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 bg-black/30 z-40 lg:hidden"
    ></div>

    <!-- CONTENIDO -->
    <div class="flex-1 flex flex-col">

      <!-- TOPBAR -->
      <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6">

        <!-- BOTÓN HAMBURGUESA (visible en móvil y tablet) -->
        <button
          @click="sidebarOpen = !sidebarOpen"
          class="text-2xl text-gray-600 lg:hidden"
        >
          ☰
        </button>

        <!-- TITULO -->
<div class="hidden lg:flex flex-col">
  <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100 leading-tight transition-colors duration-300">
    Simulador de Nómina
  </h1>
  <span class="text-xs text-gray-500 dark:text-gray-400">
    Sistema académico de cálculo
  </span>
</div>

<!-- LADO DERECHO -->
<div class="flex items-center gap-5">

  <!-- FECHA -->
  <span class="hidden md:block text-sm text-gray-500 dark:text-gray-400 transition-colors duration-300">
    {{ new Date().toLocaleDateString() }}
  </span>

  <!-- USUARIO -->
  <Dropdown align="right" width="64">
    <template #trigger>
      <button
        class="flex items-center gap-3 px-4 py-2 rounded-2xl
               bg-gray-100 dark:bg-gray-700
               hover:bg-gray-200 dark:hover:bg-gray-600
               shadow-sm hover:shadow-md
               transition-all duration-300 ease-in-out
               transform hover:scale-105"
      >
        <!-- Avatar -->
        <div class="relative">
          <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold shadow-md">
            {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
          </div>

          <!-- Indicador activo -->
          <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-white dark:border-gray-700 rounded-full animate-pulse"></span>
        </div>

        <!-- Nombre y Rol -->
        <div class="text-left hidden sm:block">
          <div class="text-sm font-semibold text-gray-700 dark:text-gray-200 transition-colors duration-300">
            {{ $page.props.auth.user.name }}
          </div>

          <!-- Rol dinámico -->
          <div
            class="text-xs font-medium px-2 py-0.5 rounded-full inline-block mt-1
                   transition-all duration-300"
            :class="{
              'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300': $page.props.auth.user.role === 'admin',
              'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300': $page.props.auth.user.role === 'alumno'
            }"
          >
            {{ $page.props.auth.user.role }}
          </div>
        </div>

        <!-- Flecha -->
        <svg class="w-4 h-4 text-gray-500 dark:text-gray-300 transition-transform duration-300 group-hover:rotate-180"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
    </template>

    <template #content>
      <div class="px-4 py-3 border-b dark:border-gray-600">
        <div class="text-sm font-semibold text-gray-700 dark:text-gray-200">
          {{ $page.props.auth.user.name }}
        </div>
        <div class="text-xs text-gray-400">
          Rol: {{ $page.props.auth.user.role }}
        </div>
      </div>

      <DropdownLink :href="route('profile.edit')">
        Perfil
      </DropdownLink>

      <DropdownLink
        :href="route('logout')"
        method="post"
        as="button"
      >
        Cerrar sesión
      </DropdownLink>
    </template>
  </Dropdown>

</div>

      </header>

      <!-- MAIN -->
      <main class="flex-1 p-6">
        <slot />
      </main>

    </div>

  </div>
</template>
