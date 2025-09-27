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
                  <label class="block text-xs font-medium text-gray-700 mb-1">Device Name</label>
                  <input
                    v-model="formData.device_name"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter device name"
                    required
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Device Type</label>
                  <select
                    v-model="formData.device_type"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="sensor">Sensor</option>
                    <option value="actuator">Actuator</option>
                    <option value="gateway">Gateway</option>
                    <option value="camera">Camera</option>
                    <option value="light">Light</option>
                    <option value="thermostat">Thermostat</option>
                    <option value="other">Other</option>
                  </select>
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">IP Address</label>
                  <input
                    v-model="formData.ip_address"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter IP address"
                    required
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Port</label>
                  <input
                    v-model.number="formData.port"
                    type="number"
                    min="1"
                    max="65535"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter port"
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">MAC Address</label>
                  <input
                    v-model="formData.mac_address"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="XX:XX:XX:XX:XX:XX"
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Location</label>
                  <div class="flex gap-2">
                    <input
                      type="number"
                      class="w-1/2 px-2 py-1 border border-gray-300 rounded-lg text-xs"
                      placeholder="X"
                    />
                    <input
                      type="number"
                      class="w-1/2 px-2 py-1 border border-gray-300 rounded-lg text-xs"
                      placeholder="Y"
                    />
                  </div>
                </div>
              </div>

              <!-- Column 2 -->
              <div class="space-y-3">
                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Model</label>
                  <input
                    v-model="formData.model"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter model"
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1"
                    >Coverage Area (radius)</label
                  >
                  <input
                    v-model="formData.coverage"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter coverage area"
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Protocol</label>
                  <select
                    v-model="formData.protocol"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                  >
                    <option value="mqtt">MQTT</option>
                    <option value="coap">CoAP</option>
                    <option value="http">HTTP</option>
                    <option value="https">HTTPS</option>
                    <option value="modbus">Modbus</option>
                    <option value="zigbee">Zigbee</option>
                    <option value="other">Other</option>
                  </select>
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1"
                    >Firmware Version</label
                  >
                  <input
                    v-model="formData.firmware_version"
                    type="text"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter firmware version"
                  />
                </div>

                <div>
                  <label class="block text-xs font-medium text-gray-700 mb-1">Configuration</label>
                  <textarea
                    v-model="formData.config"
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter configuration"
                    rows="2"
                  ></textarea>
                </div>

                <div class="flex items-center">
                  <label class="inline-flex items-center">
                    <input
                      v-model="formData.is_active"
                      type="checkbox"
                      class="rounded text-blue-600 focus:ring-blue-500"
                    />
                    <span class="ml-2 text-xs text-gray-700">Is Active</span>
                  </label>
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

interface FormData {
  device_name: string
  device_type: string
  ip_address: string
  port?: number
  mac_address?: string
  model?: string
  config?: string
  is_active: boolean
  coverage?: string
  protocol: string
  firmware_version?: string
}

const props = withDefaults(defineProps<Props>(), {
  title: 'Add New IoT Device',
})

const emit = defineEmits<{
  close: []
  submit: [data: FormData]
}>()

const formData = reactive<FormData>({
  device_name: '',
  device_type: 'sensor',
  ip_address: '',
  port: 80,
  mac_address: '',
  model: '',
  config: '',
  is_active: true,
  coverage: '10',
  protocol: 'mqtt',
  firmware_version: '1.0.0',
})

const resetForm = () => {
  formData.device_name = ''
  formData.device_type = 'sensor'
  formData.ip_address = ''
  formData.port = 80
  formData.mac_address = ''
  formData.model = ''
  formData.config = ''
  formData.is_active = true
  formData.coverage = '10'
  formData.protocol = 'mqtt'
  formData.firmware_version = '1.0.0'
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
