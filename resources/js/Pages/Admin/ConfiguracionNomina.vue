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
    limite_excento_vales: props.config.limite_excento_vales ?? 0,
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

          <!-- HEADER -->
<div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 flex flex-col sm:flex-row items-center sm:items-start gap-4">

  <!-- ICONO -->
  <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-white flex items-center justify-center shadow shrink-0">
    <img
      :src="avatarFiscales"
      alt="ISR"
      class="w-full h-full object-contain rounded-xl"
    />
  </div>

  <!-- TEXTO -->
  <div class="text-center sm:text-left">
    <h1 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-800 leading-tight">
       Actualizaciones de datos fiscales y cuotas IMSS
    </h1>
    <p class="text-gray-600 mt-1 text-sm sm:text-base">
      Modifica los datos generales, subsidio al empleo y cuotas del IMSS.
    </p>
  </div>

</div>

    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 space-y-6">

    <h2 class="font-bold text-xl text-blue-600 border-b pb-3 flex items-center gap-2">
        Datos Generales
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="space-y-1">
            <label class="font-semibold text-gray-700">Salario Mínimo</label>
            <input v-model="form.salario_minimo" type="number" step="0.01"
                class="w-full border border-gray-300 p-2.5 rounded-lg
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                transition">
        </div>

        <div class="space-y-1">
            <label class="font-semibold text-gray-700">UMA</label>
            <input v-model="form.uma" type="number" step="0.01"
                class="w-full border border-gray-300 p-2.5 rounded-lg
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                transition">
        </div>

        <div class="space-y-1">
            <label class="font-semibold text-gray-700">
                Límite Exento permitido para el IMSS (40%)
            </label>
            <input v-model="form.limite_vales_despensa" type="number" step="0.01"
                class="w-full border border-gray-300 p-2.5 rounded-lg
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                transition">
        </div>

        <div class="space-y-1">
            <label class="font-semibold text-gray-700">
                Límite Exento de Vales de Despensa para el ISR mensual
            </label>
            <input v-model="form.limite_excento_vales" type="number" step="0.01"
                class="w-full border border-gray-300 p-2.5 rounded-lg
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                transition">
        </div>

        <div class="space-y-1">
            <label class="font-semibold text-gray-700">Subsidio al Empleo (%)</label>
            <input v-model="form.subsidio_empleo" type="number" step="0.01"
                class="w-full border border-gray-300 p-2.5 rounded-lg
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                transition">
        </div>

        <div class="space-y-1">
            <label class="font-semibold text-gray-700">
                Límite de Ingreso para Aplicar Subsidio (mensual)
            </label>
            <input v-model="form.limite_ingreso_subsidio" type="number" step="0.01"
                class="w-full border border-gray-300 p-2.5 rounded-lg
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                transition">
        </div>

    </div>

    <div class="pt-4 border-t flex justify-end">
        <button
            @click="guardarConfig"
            class="rounded-xl bg-blue-700 text-white px-6 py-2.5 font-semibold
            hover:bg-blue-900 transition shadow-md hover:shadow-lg">
            Guardar Configuración
        </button>
    </div>

</div>
    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 space-y-6">

    <h2 class="font-bold text-xl text-emerald-600 border-b pb-3 flex items-center gap-2">
    Cuotas IMSS
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="space-y-1">
            <label class="font-semibold text-gray-700">Excedente Patronal (%)</label>
            <input v-model="formCuotas.excedente_patronal" type="number" step="0.01"
                class="w-full border border-gray-300 p-2.5 rounded-lg
                focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                transition">
        </div>

        <div class="space-y-1">
            <label class="font-semibold text-gray-700">Prestaciones en Dinero (%)</label>
            <input v-model="formCuotas.prestaciones_dinero" type="text"
                class="w-full border border-gray-300 p-2.5 rounded-lg
                focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                transition">
        </div>

        <div class="space-y-1">
            <label class="font-semibold text-gray-700">Prestaciones en Especie (%)</label>
            <input v-model="formCuotas.prestaciones_especie" type="text"
                class="w-full border border-gray-300 p-2.5 rounded-lg
                focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                transition">
        </div>

        <div class="space-y-1">
            <label class="font-semibold text-gray-700">Invalidez y Vida (%)</label>
            <input v-model="formCuotas.invalidez_vida" type="text"
                class="w-full border border-gray-300 p-2.5 rounded-lg
                focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                transition">
        </div>

        <div class="space-y-1 md:col-span-2">
            <label class="font-semibold text-gray-700">Cesantía y Vejez (%)</label>
            <input v-model="formCuotas.cesantia_vejez" type="text"
                class="w-full border border-gray-300 p-2.5 rounded-lg
                focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                transition">
        </div>

    </div>

    <div class="pt-4 border-t flex justify-end">
        <button
            @click="guardarCuotas"
            class="rounded-xl bg-emerald-700 text-white px-6 py-2.5 font-semibold
            hover:bg-emerald-900 transition shadow-md hover:shadow-lg">
            Guardar Cuotas IMSS
        </button>
    </div>

</div>
</div>
</div>
</AuthenticatedLayout>
</template>