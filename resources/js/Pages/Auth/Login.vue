<script setup>
import Checkbox from '@/Components/Checkbox.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
  canResetPassword: Boolean,
  status: String,
})

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}

const bgImage = new URL('../image/contabilidad.jpeg', import.meta.url).href
const logoSrc = new URL('../image/logo.jpeg', import.meta.url).href
</script>

<template>
  <Head title="Iniciar sesión" />

  <!-- CONTENEDOR GENERAL -->
  <div class="relative min-h-screen flex items-center justify-center overflow-hidden">

    <!-- FONDO -->
    <img
      :src="bgImage"
      alt="Contabilidad"
      class="absolute inset-0 w-full h-full object-cover"
    />

    <!-- OVERLAY AZUL OSCURO -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-950/90 via-blue-900/85 to-black/80"></div>

    <!-- CARD LOGIN -->
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
        Iniciar sesión
      </h1>

      <p class="mt-2 text-center text-gray-600 text-sm">
        Accede al simulador de nóminas
      </p>

      <!-- STATUS -->
      <div
        v-if="status"
        class="mt-4 text-sm text-green-600 text-center font-medium"
      >
        {{ status }}
      </div>

      <!-- FORMULARIO -->
      <form @submit.prevent="submit" class="mt-6 space-y-5">

        <!-- EMAIL -->
        <div>
          <InputLabel for="email" value="Correo electrónico" />
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
            autofocus
            autocomplete="username"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <!-- PASSWORD -->
        <div>
          <InputLabel for="password" value="Contraseña" />
          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password"
            required
            autocomplete="current-password"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <!-- RECORDAR / RESET -->
        <div class="flex items-center justify-between text-sm">
          <label class="flex items-center">
            <Checkbox v-model:checked="form.remember" />
            <span class="ms-2 text-gray-600">Recordarme</span>
          </label>

          <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="text-blue-700 hover:text-blue-900 font-medium"
          >
            ¿Olvidaste tu contraseña?
          </Link>
        </div>

        <!-- BOTÓN -->
        <PrimaryButton
          class="w-full justify-center gap-2 py-4 text-lg
                 bg-blue-700 hover:bg-blue-800
                 rounded-xl shadow-lg transition"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Iniciar sesión
        </PrimaryButton>
      </form>

      <!-- FOOTER -->
      <p class="mt-6 text-center text-xs text-gray-600">
        Acceso exclusivo para usuarios autorizados
      </p>
    </div>
  </div>
</template>
