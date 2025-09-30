<template>
  <transition name="slide-down">
    <div v-if="show" class="fixed inset-0 flex justify-center z-50 px-4 pt-12">
      <!-- Overlay -->
      <div class="absolute inset-0" @click="closeModal"></div>

      <!-- Modal content -->
      <transition name="scale">
        <div
          v-if="show"
          class="relative bg-white rounded-lg shadow-xl w-full max-w-3xl p-6 transform transition-transform duration-300 scale-95 font-xs h-fit border border-gray-200 py-4 z-10"
          :class="{ 'scale-100': show }"
        >
          <!-- Header -->
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-sm font-semibold text-gray-800">
              {{ title }}
            </h3>
            <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
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

          <!-- Form -->
          <form @submit.prevent="handleSubmit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Column 1 -->
              <div class="space-y-3">
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Name</label>
                  <input
                    v-model="formData.name"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter router name"
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
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">IP Address</label>
                  <input
                    v-model="formData.ip_address"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter IP address"
                    required
                  />
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Manufacturer</label>
                  <input
                    v-model="formData.manufacturer"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter manufacturer"
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
                  />
                </div>

              </div>

              <!-- Column 2 -->
              <div class="space-y-3">
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Model</label>
                  <input
                    v-model="formData.model"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter model"
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                  <select
                    v-model="formData.status"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                  >
                    <option value="online">Online</option>
                    <option value="offline">Offline</option>
                  </select>
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1"
                    >Bandwidth (Mbps)</label
                  >
                  <input
                    v-model.number="formData.bandwidth"
                    type="number"
                    min="1"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter bandwidth"
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Coverage (radius)</label>
                  <input
                    v-model.number="formData.coverage"
                    type="number"
                    step="0.1"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs"
                    placeholder="Enter coverage"
                  />
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-3">
              <button
                type="button"
                @click="closeModal"
                class="px-3 py-1 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="px-3 py-1 bg-blue-600 text-white rounded-lg text-xs font-medium hover:bg-blue-700"
              >
                {{ title }}
              </button>
            </div>
          </form>
        </div>
      </transition>
    </div>
  </transition>
</template>

<script setup lang="ts">
import { reactive, watch } from 'vue'

interface Props {
  show: boolean
  title?: string
}

interface RouterForm {
  name: string
  mac_address: string
  ip_address: string
  location?: string
  model?: string
  manufacturer?: string
  firmware_version?: string
  status: 'online' | 'offline'
  bandwidth?: number
  coverage?: number
}

const props = withDefaults(defineProps<Props>(), {
  title: 'Add Router',
})

const emit = defineEmits<{
  close: []
  submit: [data: RouterForm]
}>()

const formData = reactive<RouterForm>({
  name: '',
  mac_address: '',
  ip_address: '',
  location: '',
  model: '',
  manufacturer: '',
  firmware_version: '',
  status: 'offline',
  bandwidth: undefined,
  coverage: undefined,
})

const resetForm = () => {
  formData.name = ''
  formData.mac_address = ''
  formData.ip_address = ''
  formData.location = ''
  formData.model = ''
  formData.manufacturer = ''
  formData.firmware_version = ''
  formData.status = 'offline'
  formData.bandwidth = undefined
  formData.coverage = undefined
}

const closeModal = () => {
  emit('close')
}

const handleSubmit = () => {
  emit('submit', { ...formData })
}

watch(
  () => props.show,
  (newShow) => {
    if (newShow) resetForm()
  },
)
</script>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: opacity 0.25s ease, transform 0.25s ease;
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
  transition: opacity 0.2s ease, transform 0.2s ease;
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
</style>
