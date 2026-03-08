<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'

const avatarFiscales = new URL('../image/fiscales.jpeg', import.meta.url).href

const props = defineProps({
    config: Object,
    cuotas: Object
})

const form = useForm({
    salario_minimo: props.config.salario_minimo ?? 0,
    uma: props.config.uma ?? 0,
    limite_vales_despensa: props.config.limite_vales_despensa ?? 0,
    subsidio_empleo: props.config.subsidio_empleo ?? 0,
    tope_subsidio: props.config.tope_subsidio ?? 0,
    tope_subsidio_mensual: props.config.tope_subsidio_mensual ?? 0,
    limite_ingreso_subsidio: props.config.limite_ingreso_subsidio ?? 0,
})

const formCuotas = useForm({
    excedente_patronal: props.cuotas.excedente_patronal ?? 0,
    prestaciones_dinero: props.cuotas.prestaciones_dinero ?? 0,
    prestaciones_especie: props.cuotas.prestaciones_especie ?? 0,
    invalidez_vida: props.cuotas.invalidez_vida ?? 0,
    cesantia_vejez: props.cuotas.cesantia_vejez ?? 0,
})

function guardarConfig() {
    form.post(route('admin.configuracion.nomina.update'))
}

function guardarCuotas() {
    formCuotas.post(route('admin.cuotas.imss.update'))
}
</script>

<template>
<AuthenticatedLayout>
<div class="py-10 bg-gradient-to-br from-blue-100 via-blue-200 to-blue-100 min-h-screen">
<div class="max-w-7xl mx-auto px-6 space-y-8">

    <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl overflow-hidden shadow">
            <img :src="avatarFiscales" alt="fiscales" class="w-full h-full object-cover" />
        </div>

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Actualizaciones de datos fiscales y cuotas IMSS
            </h1>
            <p class="text-gray-600">
                Modifica los datos generales, subsidio al empleo y cuotas del IMSS.
            </p>
        </div>
    </div>

    <div class="bg-white p-6 rounded shadow space-y-6">
        <h2 class="font-bold text-lg text-blue-600 border-b pb-2">
            Datos Generales
        </h2>

        <div>
            <label class="font-semibold">Salario Mínimo</label>
            <input v-model="form.salario_minimo" type="number" step="0.01" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="font-semibold">UMA</label>
            <input v-model="form.uma" type="number" step="0.01" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="font-semibold">Límite Exento de Vales de Despensa (veces UMA)</label>
            <input v-model="form.limite_vales_despensa" type="number" step="0.01" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="font-semibold">Subsidio al Empleo (%)</label>
            <input v-model="form.subsidio_empleo" type="number" step="0.01" class="w-full border p-2 rounded">
        </div>
        <div>
            <label class="font-semibold">Límite de Ingreso para Aplicar Subsidio</label>
            <input v-model="form.limite_ingreso_subsidio" type="number" step="0.01" class="w-full border p-2 rounded">
        </div>

        <button @click="guardarConfig"
            class="rounded-xl bg-blue-600 text-white px-4 py-2 font-semibold hover:bg-blue-700">
            Guardar Configuración
        </button>
    </div>

    <div class="bg-white p-6 rounded shadow space-y-6">
        <h2 class="font-bold text-lg text-emerald-600 border-b pb-2">
            Cuotas IMSS
        </h2>

        <div>
            <label class="font-semibold">Excedente Patronal (%)</label>
            <input v-model="formCuotas.excedente_patronal" type="number" step="0.0001" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="font-semibold">Prestaciones en Dinero (%)</label>
            <input v-model="formCuotas.prestaciones_dinero" type="number" step="0.0001" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="font-semibold">Prestaciones en Especie (%)</label>
            <input v-model="formCuotas.prestaciones_especie" type="number" step="0.0001" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="font-semibold">Invalidez y Vida (%)</label>
            <input v-model="formCuotas.invalidez_vida" type="number" step="0.0001" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="font-semibold">Cesantía y Vejez (%)</label>
            <input v-model="formCuotas.cesantia_vejez" type="number" step="0.0001" class="w-full border p-2 rounded">
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