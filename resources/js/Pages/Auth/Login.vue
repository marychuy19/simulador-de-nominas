<script setup>
import Checkbox from '@/Components/Checkbox.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'

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

const show = ref(false)

onMounted(() => {
  setTimeout(() => {
    show.value = true
  }, 100)
})
</script>

<template>
  <Head title="Iniciar sesión" />

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

    <!-- CARD LOGIN -->
    <div
      :class="[
        'relative z-10 w-full max-w-md mx-4',
        'bg-white/10 backdrop-blur-lg rounded-2xl px-6 py-8 md:px-8 md:py-10 shadow-xl text-white',
        'transform transition-all duration-1000',
        show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'
      ]"
    >

      <!-- LOGO -->
      <div class="flex justify-center mb-6">
        <img :src="logoSrc" class="h-14 w-14 rounded-xl shadow-lg" />
      </div>

      <!-- TITULO -->
      <h1 class="text-2xl md:text-3xl font-bold text-center
                 bg-gradient-to-r from-white to-blue-300
                 bg-clip-text text-transparent">
        Iniciar sesión
      </h1>

      <!-- DESCRIPCION -->
      <p class="text-center text-sm text-white/80 mt-2 mb-6">
        Accede al simulador de nóminas
      </p>

      <!-- STATUS -->
      <div v-if="status" class="mb-4 text-sm text-green-300 text-center">
        {{ status }}
      </div>

      <!-- FORM -->
      <form @submit.prevent="submit" class="space-y-4">

        <!-- EMAIL -->
        <div>
          <TextInput
            id="email"
            type="email"
            class="w-full rounded-full bg-white/20 border-none px-4 py-2 text-white placeholder-white/70 focus:ring-2 focus:ring-blue-400"
            placeholder="Correo electrónico"
            v-model="form.email"
            required
            autofocus
          />
          <InputError class="mt-1 text-red-300" :message="form.errors.email" />
        </div>

        <!-- PASSWORD -->
        <div>
          <TextInput
            id="password"
            type="password"
            class="w-full rounded-full bg-white/20 border-none px-4 py-2 text-white placeholder-white/70 focus:ring-2 focus:ring-blue-400"
            placeholder="••••••••"
            v-model="form.password"
            required
          />
          <InputError class="mt-1 text-red-300" :message="form.errors.password" />
        </div>

        <!-- OPCIONES -->
        <div class="flex justify-between items-center text-sm">

          <label class="flex items-center">
            <Checkbox v-model:checked="form.remember" />
            <span class="ml-2 text-white/80">Recordar</span>
          </label>

          <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="text-white/70 hover:text-white"
          >
            ¿Olvidaste tu contraseña?
          </Link>

        </div>

        <!-- BOTON -->
        <PrimaryButton
          class="w-full justify-center py-2.5 rounded-full
                 bg-gradient-to-r from-blue-500 to-blue-700
                 hover:from-blue-600 hover:to-blue-800
                 transition-all duration-300"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Entrar
        </PrimaryButton>

      </form>

    </div>

  </div>
</template>