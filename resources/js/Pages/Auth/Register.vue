<script setup>
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('register'), {
    onFinish: () =>
      form.reset('password', 'password_confirmation'),
  })
}

const bgImage = new URL('../../../image/contabilidad.jpeg', import.meta.url).href
const logoSrc = new URL('../../../image/logo.jpeg', import.meta.url).href
</script>

<template>
  <Head title="Registrarse" />

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

    <!-- CARD REGISTRO -->
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
        Crear cuenta
      </h1>

      <p class="mt-2 text-center text-gray-600 text-sm">
        Regístrate en el simulador de nóminas
      </p>

      <!-- FORMULARIO -->
      <form @submit.prevent="submit" class="mt-6 space-y-5">

        <!-- NOMBRE -->
        <div>
          <InputLabel for="name" value="Nombre completo" />
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            autofocus
            autocomplete="name"
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <!-- EMAIL -->
        <div>
          <InputLabel for="email" value="Correo electrónico" />
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
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
            autocomplete="new-password"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <!-- CONFIRM PASSWORD -->
        <div>
          <InputLabel
            for="password_confirmation"
            value="Confirmar contraseña"
          />
          <TextInput
            id="password_confirmation"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password_confirmation"
            required
            autocomplete="new-password"
          />
          <InputError
            class="mt-2"
            :message="form.errors.password_confirmation"
          />
        </div>

        <!-- BOTÓN -->
        <PrimaryButton
          class="w-full justify-center py-4 text-lg
                 bg-blue-700 hover:bg-blue-800
                 rounded-xl shadow-lg transition"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Registrarse
        </PrimaryButton>
      </form>

      <!-- FOOTER -->
      <p class="mt-6 text-center text-sm text-gray-600">
        ¿Ya tienes cuenta?
        <Link
          :href="route('login')"
          class="text-blue-700 hover:text-blue-900 font-semibold"
        >
          Inicia sesión
        </Link>
      </p>
    </div>
  </div>
</template>
