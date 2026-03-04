<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    config:Object
})

const form = useForm({

    salario_minimo: props.config.salario_minimo,
    uma: props.config.uma,
    limite_vales_despensa: props.config.limite_vales_despensa,
    subsidio_empleo: props.config.subsidio_empleo

})

function guardar(){

    form.post(route('configuracion.nomina.update'))

}

</script>

<template>

<AuthenticatedLayout>

<div class="max-w-4xl mx-auto py-10">

<h1 class="text-2xl font-bold mb-6">
Configuración Fiscal de Nómina
</h1>


<div class="grid gap-6 bg-white p-6 rounded shadow">

<div>
<label class="font-semibold">Salario Mínimo</label>
<input v-model="form.salario_minimo" type="number" class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">UMA</label>
<input v-model="form.uma" type="number" class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Límite Exento Vales Despensa</label>
<input v-model="form.limite_vales_despensa" type="number" class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Subsidio al Empleo</label>
<input v-model="form.subsidio_empleo" type="number" class="w-full border p-2 rounded">
</div>

<button
@click="guardar"
class="bg-blue-600 text-white px-6 py-2 rounded mt-4"
>
Guardar cambios
</button>

</div>

</div>

</AuthenticatedLayout>

</template>