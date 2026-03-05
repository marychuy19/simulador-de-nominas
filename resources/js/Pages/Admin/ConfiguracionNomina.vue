<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'

/* IMÁGENES */
const avatarFiscales = new URL('../image/fiscales.jpeg', import.meta.url).href

const props = defineProps({
    config:Object,
    cuotas:Object
})

const form = useForm({
    salario_minimo: props.config.salario_minimo,
    uma: props.config.uma,
    limite_vales_despensa: props.config.limite_vales_despensa,
    subsidio_empleo: props.config.subsidio_empleo,
    tope_subsidio: props.config.tope_subsidio
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
<div class="py-10 bg-gradient-to-br from-blue-100 via-blue-200 to-blue-100 min-h-screen">
<div class="max-w-7xl mx-auto px-6 space-y-8">


<!-- HEADER -->
        <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4">
          <div class="w-14 h-14 rounded-xl overflow-hidden shadow">
            <img
              :src="avatarFiscales"
              alt="fiscales"
              class="w-full h-full object-cover"
            />
          </div>

          <div>
            <h1 class="text-2xl font-bold text-gray-800">
              Actualizaciones de datos fiscales y cuotas IMSS
            </h1>
            <p class="text-gray-600">
              Modifica los datos generales y cuotas del IMSS al año.           
             </p>
          </div>
        </div>

<!-- ================= DATOS GENERALES ================= -->
<div class="bg-white p-6 rounded shadow space-y-6">

<h2 class="font-bold text-lg text-blue-600 border-b pb-2">
Datos Generales
</h2>

<div>
<label class="font-semibold">Salario Mínimo</label>
<input v-model="form.salario_minimo" type="number" step="0.01"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">UMA (Unidad de Medida y Actualización)</label>
<input v-model="form.uma" type="number" step="0.01"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Límite Exento de Vales de Despensa</label>
<input v-model="form.limite_vales_despensa" type="number" step="0.01"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Subsidio al Empleo (porcentaje)</label>
<input v-model="form.subsidio_empleo" type="number" step="0.01"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Tope Subsidio (mensual)</label>
<input v-model="form.tope_subsidio" type="number" step="0.01"
class="w-full border p-2 rounded">
</div>


<button @click="guardarConfig"
class="rounded-xl bg-blue-600 text-white px-4 py-2 font-semibold hover:bg-blue-700">
Guardar Configuración
</button>

</div>

<!-- ================= CUOTAS IMSS ================= -->
<div class="bg-white p-6 rounded shadow space-y-6">

<h2 class="font-bold text-lg text-emerald-600  border-b pb-2">
Cuotas IMSS
</h2>

<div>
<label class="font-semibold">Excedente Patronal (%)</label>
<input v-model="formCuotas.excedente_patronal" type="number" step="0.0001"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Prestaciones en Dinero (%)</label>
<input v-model="formCuotas.prestaciones_dinero" type="number" step="0.0001"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Prestaciones en Especie (%)</label>
<input v-model="formCuotas.prestaciones_especie" type="number" step="0.0001"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Invalidez y Vida (%)</label>
<input v-model="formCuotas.invalidez_vida" type="number" step="0.0001"
class="w-full border p-2 rounded">
</div>

<div>
<label class="font-semibold">Cesantía y Vejez (%)</label>
<input v-model="formCuotas.cesantia_vejez" type="number" step="0.0001"
class="w-full border p-2 rounded">
</div>

<button @click="guardarCuotas"
class="rounded-xl bg-emerald-600 text-white px-4 py-2 font-semibold hover:bg-emerald-700">
Guardar Cuotas IMSS
</button>

</div>

</div>
</div>


</AuthenticatedLayout>

</template>