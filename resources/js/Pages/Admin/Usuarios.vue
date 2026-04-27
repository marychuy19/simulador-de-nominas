<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm, Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import { computed } from 'vue'

/* IMÁGENES */
const iconBuscar = new URL('../image/lupa.jpeg', import.meta.url).href

const avatarEmpleado = new URL('../image/user.jpeg', import.meta.url).href

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

const search = ref('')
const props = defineProps({
  users: Array
})

const filteredUsers = computed(() => {
  if (!search.value) return props.users

  return props.users.filter(user =>
    user.name.toLowerCase().includes(search.value.toLowerCase()) ||
    user.email.toLowerCase().includes(search.value.toLowerCase())
  )
})

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
      editingUserId.value = Number(user.id)
    }
  })
}

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
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

              <!-- HEADER -->
<div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 flex flex-col sm:flex-row items-center sm:items-start gap-4">

  <!-- ICONO -->
  <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-white flex items-center justify-center shadow shrink-0">
    <img
      :src="avatarEmpleado"
      alt="ISR"
      class="w-full h-full object-contain rounded-xl"
    />
  </div>

  <!-- TEXTO -->
  <div class="text-center sm:text-left">
    <h1 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-800 leading-tight">
      Administración de usuarios
    </h1>
    <p class="text-gray-600 mt-1 text-sm sm:text-base">
       Crear, editar y eliminar usuarios
    </p>
  </div>

</div>

        <!-- FORM -->
        <div class="bg-white rounded-2xl shadow-sm border p-4 sm:p-6">
          <form @submit.prevent="submit"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">

            <input v-model="form.name" placeholder="Nombre" class="input"/>
            <input v-model="form.email" placeholder="Correo" class="input"/>
            <input v-model="form.password" type="password" placeholder="Contraseña" class="input"/>
            <input v-model="form.password_confirmation" type="password" placeholder="Confirmar" class="input"/>

            <select v-model="form.cuatrimestre" class="input">
              <option v-for="n in 11" :key="n" :value="n">Cuatrimestre {{ n }}</option>
            </select>

            <select v-model="form.role" class="input">
              <option value="alumno">Alumno</option>
              <option value="admin">Admin</option>
            </select>

            <button class="col-span-full w-full bg-blue-700 text-white py-2 rounded-xl hover:bg-blue-900">
              Crear usuario
            </button>
          </form>
        </div>

      <!-- BUSCADOR -->
        <div class="mb-6 relative">
          <img :src="iconBuscar"
               class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 opacity-70" />
          <input
            v-model="search"
            type="text"
            placeholder="Buscar usuario por nombre o correo"
            class="w-full rounded-2xl border-gray-300 pl-12 pr-5 py-4 shadow-md"
          />
        </div>

        <!-- 📱 MOBILE CARDS -->
        <div class="md:hidden space-y-4">
          <div v-for="user in users" :key="'m-'+user.id">
            class="bg-white p-4 rounded-2xl shadow space-y-3">

            <div>
              <p class="text-xs text-gray-500">Nombre</p>
              <input v-if="editingUserId===user.id" v-model="editForm.name" class="input-mobile"/>
              <p v-else>{{ user.name }}</p>
            </div>

            <div>
              <p class="text-xs text-gray-500">Correo</p>
              <input v-if="editingUserId===user.id" v-model="editForm.email" class="input-mobile"/>
              <p v-else>{{ user.email }}</p>
            </div>

            <div>
              <p class="text-xs text-gray-500">Cuatrimestre</p>
              <select v-if="editingUserId===user.id" v-model="editForm.cuatrimestre" class="input-mobile">
                <option v-for="n in 11" :key="n" :value="n">{{ n }}</option>
              </select>
              <p v-else>{{ user.cuatrimestre }}</p>
            </div>

            <div>
              <p class="text-xs text-gray-500">Rol</p>
              <select v-if="editingUserId===user.id" v-model="editForm.role" class="input-mobile">
                <option value="alumno">Alumno</option>
                <option value="admin">Admin</option>
              </select>
              <p v-else>{{ user.role }}</p>
            </div>

            <div class="flex flex-col gap-2">
              <template v-if="editingUserId===user.id">
                <button @click="updateUser(user)" class="btn-blue">Guardar</button>
                <button @click="cancelEdit" class="btn-gray">Cancelar</button>
              </template>

              <template v-else>
                <button @click="startEdit(user)" class="btn-blue">Editar</button>
                <button @click="deleteUser(user)" class="btn-red">Eliminar</button>
              </template>
            </div>

          </div>
        </div>

        <!-- 💻 TABLA DESKTOP -->
        <div class="hidden md:block bg-white rounded-2xl shadow overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-blue-600 text-white text-xs">
              <tr>
                <th class="p-3">Nombre</th>
                <th class="p-3">Correo</th>
                <th class="p-3">Cuatrimestre</th>
                <th class="p-3">Contraseña</th>
                <th class="p-3">Rol</th>
                <th class="p-3">Acciones</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="user in filteredUsers" :key="user.id" class="border-b">
                
                <td class="p-3">
                  <input v-if="editingUserId===user.id" v-model="editForm.name" class="input"/>
                  <span v-else>{{ user.name }}</span>
                </td>

                <td class="p-3">
                  <input v-if="editingUserId===user.id" v-model="editForm.email" class="input"/>
                  <span v-else>{{ user.email }}</span>
                </td>

                <td class="p-3">
                  <select v-if="editingUserId===user.id" v-model="editForm.cuatrimestre" class="input">
                    <option v-for="n in 11" :key="n" :value="n">{{ n }}</option>
                  </select>
                  <span v-else>{{ user.cuatrimestre }}</span>
                </td>

                <td class="p-3">
                  <input v-if="editingUserId===user.id" v-model="editForm.password" class="input"/>
                  <span v-else>••••••</span>
                </td>

                <td class="p-3">
                  <select v-if="editingUserId===user.id" v-model="editForm.role" class="input">
                    <option value="alumno">Alumno</option>
                    <option value="admin">Admin</option>
                  </select>
                  <span v-else>{{ user.role }}</span>
                </td>

                <td class="p-3">
                  <div class="flex gap-2">
                    <template v-if="editingUserId===user.id">
                      <button @click="updateUser(user)" class="btn-blue">Guardar</button>
                      <button @click="cancelEdit" class="btn-gray">Cancelar</button>
                    </template>
                    <template v-else>
                      <button @click="startEdit(user)" class="btn-blue">Editar</button>
                      <button @click="deleteUser(user)" class="btn-red">Eliminar</button>
                    </template>
                  </div>
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
  @apply w-full border rounded-xl px-3 py-2 text-sm;
}

.input-mobile {
  @apply w-full border rounded-xl px-3 py-2 text-sm;
}

.btn-blue {
  @apply w-full text-white py-2 rounded-xl bg-blue-700 text-white hover:bg-blue-900 transition shadow-sm;
}

.btn-gray {
  @apply w-full text-white py-2 rounded-xl bg-gray-400 text-white hover:bg-gray-600 transition shadow-sm;
}

.btn-red {
  @apply w-full text-white py-2 rounded-xl bg-red-500 text-white hover:bg-red-600 transition shadow-sm;
}
</style>