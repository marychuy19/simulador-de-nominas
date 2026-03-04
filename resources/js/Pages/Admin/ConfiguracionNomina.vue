<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    config:Object,
    cuotas:Object
})

const form = useForm({
    salario_minimo: props.config.salario_minimo,
    uma: props.config.uma,
    limite_vales_despensa: props.config.limite_vales_despensa,
    subsidio_empleo: props.config.subsidio_empleo
})

const formCuotas = useForm({
    excedente_patronal: props.cuotas.excedente_patronal,
    prestaciones_dinero: props.cuotas.prestaciones_dinero,
    prestaciones_especie: props.cuotas.prestaciones_especie,
    invalidez_vida: props.cuotas.invalidez_vida,
    cesantia_vejez: props.cuotas.cesantia_vejez
})

function guardarConfig(){
    form.post(route('admin.configuracion.nomina.update'))
}

function guardarCuotas(){
    formCuotas.post(route('admin.cuotas.imss.update'))
}

</script>

<template>

<AuthenticatedLayout>

<div class="max-w-6xl mx-auto py-10 space-y-10">

<h1 class="text-2xl font-bold">
Configuración Fiscal de Nómina
</h1>

<!-- ================= DATOS GENERALES ================= -->
<div class="bg-white p-6 rounded shadow space-y-6">

<h2 class="font-bold text-lg border-b pb-2">
Datos Generales
</h2>

<div>
<label class="font-semibold">Salario Mínimo</label>
<p class="text-sm text-gray-500 mb-1">
Monto oficial diario establecido por ley.
</p>
<input v-model="form.salario_minimo" type="number" step="0.01"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">UMA (Unidad de Medida y Actualización)</label>
<p class="text-sm text-gray-500 mb-1">
Valor vigente utilizado para cálculos fiscales y de seguridad social.
</p>
<input v-model="form.uma" type="number" step="0.01"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Límite Exento de Vales de Despensa</label>
<p class="text-sm text-gray-500 mb-1">
Monto máximo libre de impuestos permitido para vales.
</p>
<input v-model="form.limite_vales_despensa" type="number" step="0.01"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Subsidio al Empleo</label>
<p class="text-sm text-gray-500 mb-1">
Cantidad aplicada como apoyo fiscal a trabajadores con bajos ingresos.
</p>
<input v-model="form.subsidio_empleo" type="number" step="0.01"
class="w-full border p-2 rounded">
</div>

<button @click="guardarConfig"
class="bg-blue-600 text-white px-6 py-2 rounded">
Guardar Configuración
</button>

</div>

<!-- ================= CUOTAS IMSS ================= -->
<div class="bg-white p-6 rounded shadow space-y-6">

<h2 class="font-bold text-lg text-blue-600 border-b pb-2">
Cuotas IMSS
</h2>

<div>
<label class="font-semibold">Excedente Patronal (%)</label>
<p class="text-sm text-gray-500 mb-1">
Cuota aplicada sobre el excedente del salario base mayor a 3 UMA.
</p>
<input v-model="formCuotas.excedente_patronal" type="number" step="0.0001"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Prestaciones en Dinero (%)</label>
<p class="text-sm text-gray-500 mb-1">
Cuota correspondiente a subsidios económicos por incapacidad.
</p>
<input v-model="formCuotas.prestaciones_dinero" type="number" step="0.0001"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Prestaciones en Especie (%)</label>
<p class="text-sm text-gray-500 mb-1">
Cuota destinada a servicios médicos y hospitalarios.
</p>
<input v-model="formCuotas.prestaciones_especie" type="number" step="0.0001"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Invalidez y Vida (%)</label>
<p class="text-sm text-gray-500 mb-1">
Aportación para pensiones por invalidez o fallecimiento.
</p>
<input v-model="formCuotas.invalidez_vida" type="number" step="0.0001"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Cesantía y Vejez (%)</label>
<p class="text-sm text-gray-500 mb-1">
Cuota destinada al fondo de retiro y pensión por edad avanzada.
</p>
<input v-model="formCuotas.cesantia_vejez" type="number" step="0.0001"
class="w-full border p-2 rounded">
</div>

<button @click="guardarCuotas"
class="bg-green-600 text-white px-6 py-2 rounded">
Guardar Cuotas IMSS
</button>

</div>

</div>

</AuthenticatedLayout>

</template>