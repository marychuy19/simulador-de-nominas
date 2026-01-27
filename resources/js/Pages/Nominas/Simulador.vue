<script setup>
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'


const salario = ref(0)
const resultado = ref(null)

const calcular = () => {
  resultado.value = salario.value * 0.9
}
</script>

<template>
  <Head title="Simulador de nómina" />
    <AuthenticatedLayout>


  <div class="max-w-xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Simulador de nómina</h1>

    <input
      v-model="salario"
      type="number"
      placeholder="Salario base"
      class="border p-2 rounded w-full mb-4"
    />

    <button
      @click="calcular"
      class="bg-green-600 text-white p-2 rounded w-full"
    >
      Simular
    </button>

    <div v-if="resultado" class="mt-4 text-lg font-semibold">
      Salario neto: ${{ resultado }}
    </div>
  </div>
    </AuthenticatedLayout>

</template>
