<script setup>
import { ref } from 'vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import { Link } from '@inertiajs/vue3'

const showingNavigationDropdown = ref(false)

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
  <div>
    <div class="min-h-screen bg-gray-100">
      <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- LOGO -->
              <div class="shrink-0 flex items-center">
                <Link :href="route('dashboard')" class="flex items-center gap-2">
                  <img
                    :src="logoSrc"
                    alt="Logo"
                    class="h-10 w-10 rounded-xl shadow-md object-cover"
                  />
                  <span class="font-semibold text-gray-800 hidden sm:block">
                    Simulador Nómina
                  </span>
                </Link>
              </div>

              <!-- LINKS -->
              <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                <!-- INICIO -->
                <NavLink
                  :href="route('dashboard')"
                  :active="route().current('dashboard')"
                >
                  <div class="flex items-center gap-2">
                    <img :src="iconInicio" class="w-6 h-6" />
                    <span>Inicio</span>
                  </div>
                </NavLink>

                <!-- ADMIN -->
                <NavLink
                  v-if="$page.props.auth.user.role === 'admin'"
                  :href="route('admin.usuarios')"
                  :active="route().current('admin.usuarios')"
                >
                  <div class="flex items-center gap-2">
                    <img :src="iconUsuarios" class="w-6 h-6" />
                    <span>Usuarios</span>
                  </div>
                </NavLink>

                <!-- ALUMNO -->
                <template v-if="$page.props.auth.user.role === 'alumno'">

                  <NavLink
                    :href="route('alumno.empleado')"
                    :active="route().current('alumno.empleado')"
                  >
                    <div class="flex items-center gap-2">
                      <img :src="iconEmpleado" class="w-6 h-6" />
                      <span>Empleado</span>
                    </div>
                  </NavLink>

                  <NavLink
                    :href="route('alumno.calculo-nomina')"
                    :active="route().current('alumno.calculo-nomina')"
                  >
                    <div class="flex items-center gap-2">
                      <img :src="iconCalculo" class="w-6 h-6" />
                      <span>Cálculo de nómina</span>
                    </div>
                  </NavLink>

                  <NavLink
                    :href="route('alumno.recibo')"
                    :active="route().current('alumno.recibo')"
                  >
                    <div class="flex items-center gap-2">
                      <img :src="iconRecibo" class="w-6 h-6" />
                      <span>Factura de nómina</span>
                    </div>
                  </NavLink>

                </template>
              </div>
            </div>

            <!-- USER DROPDOWN -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
              <Dropdown align="right" width="48">
                <template #trigger>
                  <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white hover:text-gray-700">
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
            </div>

            <!-- HAMBURGER -->
            <div class="-me-2 flex items-center sm:hidden">
              <button
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="text-xl"
              >
                ☰
              </button>
            </div>
          </div>
        </div>

        <!-- RESPONSIVE -->
        <div
          :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
          class="sm:hidden"
        >
          <ResponsiveNavLink :href="route('dashboard')">
            <div class="flex items-center gap-2">
              <img :src="iconInicio" class="w-4 h-4" />
              <span>Inicio</span>
            </div>
          </ResponsiveNavLink>

          <ResponsiveNavLink
            v-if="$page.props.auth.user.role === 'admin'"
            :href="route('admin.usuarios')"
          >
            <div class="flex items-center gap-2">
              <img :src="iconUsuarios" class="w-4 h-4" />
              <span>Usuarios</span>
            </div>
          </ResponsiveNavLink>

          <template v-if="$page.props.auth.user.role === 'alumno'">

            <ResponsiveNavLink :href="route('alumno.empleado')">
              <div class="flex items-center gap-2">
                <img :src="iconEmpleado" class="w-4 h-4" />
                <span>Empleado</span>
              </div>
            </ResponsiveNavLink>

            <ResponsiveNavLink :href="route('alumno.calculo-nomina')">
              <div class="flex items-center gap-2">
                <img :src="iconCalculo" class="w-4 h-4" />
                <span>Cálculo de nómina</span>
              </div>
            </ResponsiveNavLink>

            <ResponsiveNavLink :href="route('alumno.recibo')">
              <div class="flex items-center gap-2">
                <img :src="iconRecibo" class="w-4 h-4" />
                <span>Recibo</span>
              </div>
            </ResponsiveNavLink>

          </template>
        </div>
      </nav>

      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
