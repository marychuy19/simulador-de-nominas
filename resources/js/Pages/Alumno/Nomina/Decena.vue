<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
</script>

<template>
  <Head title="Nómina diaria" />

  <AuthenticatedLayout>
    <div class="p-10">
      <h1 class="text-2xl font-bold">Nómina diaria</h1>
    </div>
  </AuthenticatedLayout>
</template>