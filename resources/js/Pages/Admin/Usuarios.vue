<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Head } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps({
  users: Array
})

/* IMÁGENES */
const avatarEmpleado = new URL('../image/user.jpeg', import.meta.url).href

/* ================= FORM CREAR ================= */
const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  cuatrimestre: 1,
  role: 'alumno'
})

const submit = () => {
  form.post(route('admin.usuarios.store'), {
    onSuccess: () => form.reset()
  })
}

/* ================= FORM EDITAR ================= */
const editingUserId = ref(null)

const editForm = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  cuatrimestre: 1,
  role: 'alumno'
})

const startEdit = (user) => {
  editingUserId.value = Number(user.id)

  editForm.name = user.name ?? ''
  editForm.email = user.email ?? ''
  editForm.cuatrimestre = user.cuatrimestre ?? 1
  editForm.role = user.role ?? 'alumno'
  editForm.password = ''
  editForm.password_confirmation = ''
  editForm.clearErrors()
}

const cancelEdit = () => {
  editingUserId.value = null
  editForm.reset()
  editForm.clearErrors()
}

const updateUser = (user) => {
  editForm.put(route('admin.usuarios.update', user.id), {
    preserveScroll: true,
    onSuccess: () => cancelEdit(),
    onError: () => {
      // si hay error de validación, NO se sale del modo edición
      editingUserId.value = Number(user.id)
    }
  })
}

/* ================= ELIMINAR ================= */
const deleteUser = (user) => {
  if (confirm(`¿Seguro que deseas eliminar a ${user.name}?`)) {
    editForm.delete(route('admin.usuarios.destroy', user.id), {
      preserveScroll: true,
      onSuccess: () => {
        if (editingUserId.value === Number(user.id)) cancelEdit()
      }
    })
  }
}
</script>

<template>
  <Head title="Administración de usuarios" />

  <AuthenticatedLayout>
    <div class="py-10 bg-gradient-to-br from-blue-100 via-blue-200 to-blue-100 min-h-screen">
      <div class="max-w-7xl mx-auto px-6 space-y-8">

        <!-- HEADER -->
        <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4">
          <div class="w-14 h-14 rounded-xl overflow-hidden shadow">
            <img
              :src="avatarEmpleado"
              alt="Usuarios"
              class="w-full h-full object-cover"
            />
          </div>

          <div>
            <h1 class="text-2xl font-bold text-gray-800">
              Administración de usuarios
            </h1>
            <p class="text-gray-600">
              Crear, editar y eliminar usuarios del sistema
            </p>
          </div>
        </div>

        <!-- FORM CREAR -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
            Crear nuevo usuario
          </h2>

          <form
            @submit.prevent="submit"
            class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"
          >
            <input v-model="form.name" placeholder="Nombre" class="input" />
            <input v-model="form.email" type="email" placeholder="Correo" class="input" />
            <input v-model="form.password" type="password" placeholder="Contraseña" class="input" />
            <input v-model="form.password_confirmation" type="password" placeholder="Confirmar contraseña" class="input" />

            <select v-model="form.cuatrimestre" class="input">
              <option v-for="n in 11" :key="n" :value="n">
                Cuatrimestre {{ n }}
              </option>
            </select>

            <select v-model="form.role" class="input">
              <option value="alumno">Alumno</option>
              <option value="admin">Administrador</option>
            </select>

            <button
              class="col-span-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-xl transition shadow-sm"
            >
              Crear usuario
            </button>
          </form>
        </div>

        <!-- TABLA -->
     <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-x-auto">

          <table class="w-full text-sm">
            <thead class="bg-blue-600 text-white uppercase tracking-wide text-xs">
              <tr>
                <th class="p-3 text-left">Nombre</th>
                <th class="p-3 text-left">Correo</th>
                <th class="p-3 text-left">Cuatrimestre</th>
                <th class="p-3 text-left">Contraseña</th>
                <th class="p-3 text-left">Rol</th>
                <th class="p-3 text-left">Acciones</th>
              </tr>
            </thead>

            <tbody>
              <tr
  v-for="user in users"
  :key="user.id"
  class="border-b hover:bg-slate-50 transition"
>
  <!-- NOMBRE -->
  <td class="p-3">
    <input
      v-if="editingUserId === user.id"
      v-model="editForm.name"
      class="input"
    />
    <span v-else>
      {{ user.name }}
    </span>
  </td>

  <!-- EMAIL -->
  <td class="p-3">
    <input
      v-if="editingUserId === user.id"
      v-model="editForm.email"
      class="input"
    />
    <span v-else>
      {{ user.email }}
    </span>
  </td>

  <!-- CUATRIMESTRE -->
  <td class="p-3">
    <select
      v-if="editingUserId === user.id"
      v-model="editForm.cuatrimestre"
      class="input"
    >
      <option v-for="n in 11" :key="n" :value="n">
        {{ n }}
      </option>
    </select>
    <span v-else>
      {{ user.cuatrimestre }}
    </span>
  </td>

  <!-- PASSWORD -->
  <td class="p-3">
    <input
      v-if="editingUserId === user.id"
      v-model="editForm.password"
      type="password"
      placeholder="Nueva contraseña"
      class="input"
    />
    <span v-else class="text-gray-400">
      ••••••••
    </span>
  </td>

  <!-- ROL -->
  <td class="p-3">
    <select
      v-if="editingUserId === user.id"
      v-model="editForm.role"
      class="input"
    >
      <option value="alumno">Alumno</option>
      <option value="admin">Administrador</option>
    </select>
    <span v-else class="capitalize">
      {{ user.role }}
    </span>
  </td>

  <!-- ACCIONES -->
  <td class="p-3 flex gap-3">
    <template v-if="editingUserId === user.id">
      <button
        type="button"
        @click="updateUser(user)"
        class="px-3 py-1 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition"
      >
        Guardar
      </button>

      <button
        type="button"
        @click="cancelEdit"
        class="px-3 py-1 rounded-lg bg-gray-400 text-white hover:bg-gray-500 transition"
      >
        Cancelar
      </button>
    </template>

    <template v-else>
      <button
        type="button"
        @click="startEdit(user)"
        class="px-3 py-1 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition"
      >
        Editar
      </button>

      <button
        type="button"
        @click="deleteUser(user)"
        class="px-3 py-1 rounded-lg bg-red-500 text-white hover:bg-red-600 transition"
      >
        Eliminar
      </button>
    </template>
  </td>
</tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.input {
  @apply w-full bg-white border border-gray-300 rounded-xl px-3 py-2 
  focus:ring-2 focus:ring-blue-400 focus:border-blue-400 
  outline-none transition;
}
</style>
