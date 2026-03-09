```vue
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
const bgImage2 = new URL('../image/contaa.jpeg', import.meta.url).href
const logoSrc = new URL('../image/logo.jpeg', import.meta.url).href
</script>

<template>
<Head title="Iniciar sesión" />

<div class="relative min-h-screen flex items-center justify-center overflow-hidden">

  <!-- FONDO -->
  <img
    :src="bgImage"
    class="absolute inset-0 w-full h-full object-cover"
  />

  <!-- OVERLAY VERDE -->
  <div class="absolute inset-0 bg-blue-800/70"></div>

  <!-- CARD PRINCIPAL -->
  <div class="relative z-10 w-[900px] max-w-full bg-white rounded-lg shadow-2xl flex overflow-hidden">

    <!-- LADO IZQUIERDO IMAGEN -->
    <div class="relative w-1/2 hidden md:block">

      <img
        :src="bgImage2"
        class="absolute inset-0 w-full h-full object-cover"
      />

      <div class="absolute inset-0"></div>


    </div>

    <!-- LADO DERECHO LOGIN -->
    <div class="w-full md:w-1/2 p-10 flex flex-col justify-center">

      <h1 class="text-3xl font-semibold text-gray-700 mb-2">
        Simulador de Nominas
      </h1>

      <p class="text-gray-500 mb-8 text-sm">
        Inicia sesion en tu cuenta para acceder al simulador de nominas.
      </p>

      <!-- STATUS -->
      <div
        v-if="status"
        class="mb-4 text-sm text-green-600"
      >
        {{ status }}
      </div>

      <!-- FORM -->
      <form @submit.prevent="submit" class="space-y-4">

        <!-- EMAIL -->
        <div>
          <TextInput
            id="email"
            type="email"
            class="w-full rounded-full border-gray-300 px-4 py-2"
            placeholder="Correo electronico"
            v-model="form.email"
            required
            autofocus
            autocomplete="username"
          />

          <InputError class="mt-1" :message="form.errors.email" />
        </div>

        <!-- PASSWORD -->
        <div>
          <TextInput
            id="password"
            type="password"
            class="w-full rounded-full border-gray-300 px-4 py-2"
            placeholder="••••••••••"
            v-model="form.password"
            required
            autocomplete="current-password"
          />

          <InputError class="mt-1" :message="form.errors.password" />
        </div>

        <!-- FORGOT -->
        <div class="flex justify-between text-sm">

          <label class="flex items-center">
            <Checkbox v-model:checked="form.remember" />
            <span class="ml-2 text-gray-500">Recordar</span>
          </label>

          <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="text-gray-400 hover:text-gray-600"
          >
            Haz olvidado tu contraseña?
          </Link>

        </div>

        <!-- BOTON -->
        <PrimaryButton
          class="w-full justify-center py-2 rounded-full bg-blue-600 hover:bg-blue-700"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Entrar
        </PrimaryButton>

      </form>



    </div>

  </div>

</div>
</template>

