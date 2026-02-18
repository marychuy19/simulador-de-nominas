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

        <h1 class="text-lg font-semibold text-gray-700 hidden lg:block">
          Simulador Nómina
        </h1>

        <!-- Usuario -->
        <Dropdown align="right" width="48">
          <template #trigger>
            <button class="text-sm font-medium text-gray-600 hover:text-gray-800">
              {{ $page.props.auth.user.name }}
            </button>
          </template>

          <template #content>
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

      </header>

      <!-- MAIN -->
      <main class="flex-1 p-6">
        <slot />
      </main>

    </div>

  </div>
</template>
