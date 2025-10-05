<template>
  <transition name="slide-down">
    <div v-if="show" class="fixed inset-0 flex justify-center z-50 px-4 pt-12">
      <div class="absolute inset-0" @click="closeModal"></div>

      <transition name="scale">
        <div
          v-if="show"
          class="relative bg-white rounded-lg shadow-xl w-full max-w-3xl p-6 transform transition-transform duration-300 scale-95 font-xs h-fit border border-gray-200 py-4 z-10"
          :class="{ 'scale-100': show }"
        >
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-semibold text-gray-800">
              {{ headerTitle }}
            </h3>
            <button
              @click="closeModal"
              class="text-gray-500 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="isBusy"
            >
              <svg
                class="w-5 h-5 cursor-pointer"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>

          <form @submit.prevent="handleSubmit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-3">
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Name</label>
                  <input
                    v-model="formData.name"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter router name"
                    :disabled="isBusy"
                    required
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">MAC Address</label>
                  <input
                    v-model="formData.mac_address"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="XX:XX:XX:XX:XX:XX"
                    maxlength="17"
                    :disabled="isBusy"
                    required
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">IP Address</label>
                  <input
                    v-model="formData.ip_address"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter IP address"
                    :disabled="isBusy"
                    required
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Port</label>
                  <input
                    v-model.number="formData.port"
                    type="number"
                    min="0"
                    max="65535"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter port"
                    :disabled="isBusy"
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Manufacturer</label>
                  <input
                    v-model="formData.manufacturer"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter manufacturer"
                    :disabled="isBusy"
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1"
                    >Firmware Version</label
                  >
                  <input
                    v-model="formData.firmware_version"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter firmware version"
                    :disabled="isBusy"
                  />
                </div>
              </div>

              <div class="space-y-3">
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Model</label>
                  <input
                    v-model="formData.model"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter model"
                    :disabled="isBusy"
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                  <select
                    v-model="formData.status"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    :disabled="isBusy"
                  >
                    <option value="online">Online</option>
                    <option value="offline">Offline</option>
                  </select>
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Location</label>
                  <input
                    v-model="formData.location"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter location"
                    :disabled="isBusy"
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1"
                    >Bandwidth (Mbps)</label
                  >
                  <input
                    v-model.number="formData.bandwidth"
                    type="number"
                    min="0"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter bandwidth"
                    :disabled="isBusy"
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Coverage</label>
                  <input
                    v-model.number="formData.coverage"
                    type="number"
                    min="0"
                    step="0.1"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter coverage"
                    :disabled="isBusy"
                  />
                </div>
              </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
              <div class="flex gap-3 ml-auto">
                <button
                  v-if="isUpdate"
                  type="button"
                  @click="handleDelete"
                  class="px-3 py-1 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:border-red-600 hover:text-white hover:bg-red-600"
                  :disabled="isBusy"
                >
                  Delete
                </button>
                <button
                  type="submit"
                  class="px-3 py-1 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700"
                  :disabled="isBusy"
                >
                  {{ submitLabel }}
                </button>
              </div>
            </div>
          </form>
        </div>
      </transition>
    </div>
  </transition>
</template>

<script setup lang="ts">
import { reactive, ref, watch, computed } from 'vue'
import axios from 'axios'
type RouterStatus = 'online' | 'offline'

interface RouterRecord {
  id?: number | null
  name: string
  mac_address: string
  ip_address: string
  port?: number | null
  location?: string | null
  model?: string | null
  manufacturer?: string | null
  firmware_version?: string | null
  status: RouterStatus
  bandwidth?: number | null
  coverage?: number | null
  created_at?: string | null
  updated_at?: string | null
}

type RouterWritePayload = Omit<RouterRecord, 'id' | 'created_at' | 'updated_at'>

interface RouterForm {
  id: number | null
  name: string
  mac_address: string
  ip_address: string
  port: number | null
  location: string
  model: string
  manufacturer: string
  firmware_version: string
  status: RouterStatus
  bandwidth: number | null
  coverage: number | null
}

const props = withDefaults(
  defineProps<{
    show: boolean
    title?: string
    mode?: 'add' | 'update'
    initialData?: RouterRecord | null
  }>(),
  {
    mode: 'add',
    initialData: null,
  },
)

const emit = defineEmits<{
  (e: 'close'): void
  (e: 'create', value: RouterRecord): void
  (e: 'update', value: RouterRecord): void
  (e: 'delete', value: string): void
  (e: 'error', message: string): void
}>()

const formData = reactive<RouterForm>({
  id: null,
  name: '',
  mac_address: '',
  ip_address: '',
  port: null,
  location: '',
  model: '',
  manufacturer: '',
  firmware_version: '',
  status: 'offline',
  bandwidth: null,
  coverage: null,
})

const originalMacAddress = ref<string | null>(null)
const isSubmitting = ref(false)
const isDeleting = ref(false)

const isUpdate = computed(() => props.mode === 'update')
const headerTitle = computed(() => {
  if (props.title) {
    return props.title
  }
  if (isUpdate.value) {
    return 'Router Device'
  }
  return 'Add Router Device'
})
const submitLabel = computed(() => (isUpdate.value ? 'Update' : 'Create Router'))
const isBusy = computed(() => isSubmitting.value || isDeleting.value)

const resetForm = () => {
  formData.id = null
  formData.name = ''
  formData.mac_address = ''
  formData.ip_address = ''
  formData.port = null
  formData.location = ''
  formData.model = ''
  formData.manufacturer = ''
  formData.firmware_version = ''
  formData.status = 'offline'
  formData.bandwidth = null
  formData.coverage = null
  originalMacAddress.value = null
}

const applyInitialData = (data: RouterRecord | null | undefined) => {
  resetForm()
  if (!data) return

  formData.id = data.id ?? null
  formData.name = data.name ?? ''
  formData.mac_address = data.mac_address ?? ''
  formData.ip_address = data.ip_address ?? ''
  formData.port = data.port ?? null
  formData.location = data.location ?? ''
  formData.model = data.model ?? ''
  formData.manufacturer = data.manufacturer ?? ''
  formData.firmware_version = data.firmware_version ?? ''
  formData.status = data.status === 'online' ? 'online' : 'offline'
  formData.bandwidth = data.bandwidth ?? null
  formData.coverage = data.coverage ?? null
  originalMacAddress.value = data.mac_address ?? null
}

const closeModal = () => {
  if (isBusy.value) return
  emit('close')
}

const buildPayload = (): RouterWritePayload => ({
  name: formData.name.trim(),
  mac_address: formData.mac_address.trim(),
  ip_address: formData.ip_address.trim(),
  port: formData.port ?? null,
  location: formData.location.trim() || null,
  model: formData.model.trim() || null,
  manufacturer: formData.manufacturer.trim() || null,
  firmware_version: formData.firmware_version.trim() || null,
  status: formData.status,
  bandwidth: formData.bandwidth ?? null,
  coverage: formData.coverage ?? null,
})

const handleSubmit = async () => {
  if (isBusy.value) return

  if (!formData.name.trim() || !formData.mac_address.trim() || !formData.ip_address.trim()) {
    emit('error', 'Name, MAC address, and IP address are required.')
    return
  }

  isSubmitting.value = true

  try {
    const payload = buildPayload()

    if (isUpdate.value) {
      const targetMac = (originalMacAddress.value ?? formData.mac_address).trim()
      if (!targetMac) {
        emit('error', 'Missing router MAC address.')
        return
      }

      const { data } = await axios.put(
        `http://localhost:8000/api/routers/${encodeURIComponent(targetMac)}`,
        payload,
      )
      originalMacAddress.value = data.mac_address ?? formData.mac_address
      formData.id = data.id ?? formData.id
      emit('update', data as RouterRecord)
    } else {
      const { data } = await axios.post('http://localhost:8000/api/add-router', payload)
      emit('create', data as RouterRecord)
    }

    resetForm()
    emit('close')
  } catch (error) {
    const message =
      axios.isAxiosError(error) && typeof error.response?.data?.message === 'string'
        ? error.response.data.message
        : 'Unable to save router.'
    emit('error', message)
  } finally {
    isSubmitting.value = false
  }
}

const handleDelete = async () => {
  if (!isUpdate.value || isBusy.value) return

  const targetMac = (originalMacAddress.value ?? formData.mac_address).trim()
  if (!targetMac) {
    emit('error', 'Missing router MAC address.')
    return
  }

  isDeleting.value = true
  try {
    await axios.delete(`http://localhost:8000/api/routers/${encodeURIComponent(targetMac)}`)
    emit('delete', targetMac)
    resetForm()
    emit('close')
  } catch (error) {
    const message =
      axios.isAxiosError(error) && typeof error.response?.data?.message === 'string'
        ? error.response.data.message
        : 'Unable to delete router.'
    emit('error', message)
  } finally {
    isDeleting.value = false
  }
}

watch(
  () => props.show,
  (visible) => {
    if (visible) {
      applyInitialData(props.initialData)
    } else {
      resetForm()
      isSubmitting.value = false
      isDeleting.value = false
    }
  },
  { immediate: true },
)

watch(
  () => props.initialData,
  (value) => {
    if (props.show) {
      applyInitialData(value)
    }
  },
  { immediate: true },
)
</script>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition:
    opacity 0.25s ease,
    transform 0.25s ease;
}

.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  transform: translateY(-12px);
}

.slide-down-enter-to,
.slide-down-leave-from {
  opacity: 1;
  transform: translateY(0);
}

.scale-enter-active,
.scale-leave-active {
  transition:
    opacity 0.2s ease,
    transform 0.2s ease;
}

.scale-enter-from,
.scale-leave-to {
  opacity: 0;
  transform: scale(0.96);
}

.scale-enter-to,
.scale-leave-from {
  opacity: 1;
  transform: scale(1);
}

button {
  cursor: pointer;
}
</style>


