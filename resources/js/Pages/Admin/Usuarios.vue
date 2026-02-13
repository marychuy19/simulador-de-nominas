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
    <div
      class="py-10 bg-gradient-to-br from-blue-100 via-blue-200 to-blue-100 min-h-screen"
    >
      <div class="max-w-7xl mx-auto px-6 space-y-8">

        <!-- HEADER -->
        <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center gap-4">
          <div
  class="w-14 h-14 rounded-xl overflow-hidden shadow"
>
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

        <!-- ================= FORM CREAR ================= -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">
            Crear nuevo usuario
          </h2>

          <form
            @submit.prevent="submit"
            class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4"
          >
            <div class="space-y-1">
              <input v-model="form.name" placeholder="Nombre" class="input" />
              <p v-if="form.errors.name" class="text-xs text-red-600">
                {{ form.errors.name }}
              </p>
            </div>

            <div class="space-y-1">
              <input v-model="form.email" type="email" placeholder="Correo" class="input" />
              <p v-if="form.errors.email" class="text-xs text-red-600">
                {{ form.errors.email }}
              </p>
            </div>

            <div class="space-y-1">
              <input v-model="form.password" type="password" placeholder="Contraseña" class="input" />
              <p v-if="form.errors.password" class="text-xs text-red-600">
                {{ form.errors.password }}
              </p>
            </div>

            <div class="space-y-1">
              <input v-model="form.password_confirmation" type="password" placeholder="Confirmar contraseña" class="input" />
            </div>

            <div class="space-y-1">
              <select v-model="form.cuatrimestre" class="input">
                <option v-for="n in 11" :key="n" :value="n">
                  Cuatrimestre {{ n }}
                </option>
              </select>
              <p v-if="form.errors.cuatrimestre" class="text-xs text-red-600">
                {{ form.errors.cuatrimestre }}
              </p>
            </div>

            <div class="space-y-1">
              <select v-model="form.role" class="input">
                <option value="alumno">Alumno</option>
                <option value="admin">Administrador</option>
              </select>
              <p v-if="form.errors.role" class="text-xs text-red-600">
                {{ form.errors.role }}
              </p>
            </div>

            <button
              class="col-span-full bg-blue-800 hover:bg-blue-900 text-white font-semibold py-2 rounded-xl transition shadow"
            >
              Crear usuario
            </button>
          </form>
        </div>

        <!-- ================= TABLA ================= -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
          <table class="w-full text-sm">
            <thead class="bg-blue-800 text-white">
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
                class="border-b hover:bg-blue-50 transition"
              >
                <!-- NOMBRE -->
                <td class="p-3">
                  <input
                    v-if="editingUserId === Number(user.id)"
                    v-model="editForm.name"
                    class="input-sm"
                  />
                  <span v-else>{{ user.name }}</span>
                  <p v-if="editingUserId === Number(user.id) && editForm.errors.name" class="text-xs text-red-600 mt-1">
                    {{ editForm.errors.name }}
                  </p>
                </td>

                <!-- EMAIL -->
                <td class="p-3">
                  <input
                    v-if="editingUserId === Number(user.id)"
                    v-model="editForm.email"
                    type="email"
                    class="input-sm"
                  />
                  <span v-else>{{ user.email }}</span>
                  <p v-if="editingUserId === Number(user.id) && editForm.errors.email" class="text-xs text-red-600 mt-1">
                    {{ editForm.errors.email }}
                  </p>
                </td>

                <!-- CUATRIMESTRE -->
                <td class="p-3">
                  <select
                    v-if="editingUserId === Number(user.id)"
                    v-model="editForm.cuatrimestre"
                    class="input-sm"
                  >
                    <option v-for="n in 11" :key="n" :value="n">{{ n }}</option>
                  </select>
                  <span v-else>{{ user.cuatrimestre }}</span>
                  <p v-if="editingUserId === Number(user.id) && editForm.errors.cuatrimestre" class="text-xs text-red-600 mt-1">
                    {{ editForm.errors.cuatrimestre }}
                  </p>
                </td>

                <!-- PASSWORD -->
                <td class="p-3">
                  <div v-if="editingUserId === Number(user.id)" class="space-y-1">
                    <input
                      v-model="editForm.password"
                      type="password"
                      placeholder="Nueva contraseña"
                      class="input-sm"
                    />
                    <input
                      v-model="editForm.password_confirmation"
                      type="password"
                      placeholder="Confirmar"
                      class="input-sm"
                    />
                    <p v-if="editForm.errors.password" class="text-xs text-red-600">
                      {{ editForm.errors.password }}
                    </p>
                  </div>
                  <span v-else class="text-gray-400">••••••••</span>
                </td>

                <!-- ROL -->
                <td class="p-3">
                  <select
                    v-if="editingUserId === Number(user.id)"
                    v-model="editForm.role"
                    class="input-sm"
                  >
                    <option value="alumno">Alumno</option>
                    <option value="admin">Admin</option>
                  </select>
                  <span v-else class="capitalize">{{ user.role }}</span>
                  <p v-if="editingUserId === Number(user.id) && editForm.errors.role" class="text-xs text-red-600 mt-1">
                    {{ editForm.errors.role }}
                  </p>
                </td>

                <!-- ACCIONES -->
                <td class="p-3 flex gap-3">
                  <button
                    type="button"
                    v-if="editingUserId !== Number(user.id)"
                    @click="startEdit(user)"
                    class="text-blue-700 hover:underline"
                  >
                    Editar
                  </button>

                  <button
                    type="button"
                    v-if="editingUserId !== Number(user.id)"
                    @click="deleteUser(user)"
                    class="text-red-600 hover:underline"
                  >
                    Eliminar
                  </button>

                  <template v-else>
                    <button
                      type="button"
                      @click="updateUser(user)"
                      class="text-green-600 hover:underline"
                    >
                      Guardar
                    </button>

                    <button
                      type="button"
                      @click="cancelEdit"
                      class="text-gray-600 hover:underline"
                    >
                      Cancelar
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
  @apply border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none;
}
.input-sm {
  @apply border rounded-lg px-2 py-1 w-full focus:ring-1 focus:ring-blue-500 outline-none;
}
</style>