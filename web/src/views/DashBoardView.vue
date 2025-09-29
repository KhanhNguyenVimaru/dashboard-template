<template>
  <AppHeader />
  <div class="flex flex-col gap-4 p-4">
    <div class="flex gap-5">
      <div class="flex-1">
        <DataBox
          :data="iotDeviceData"
          :columns="iotDeviceColumns"
          :pagination="iotPagination"
          :loading="iotLoading"
          title="IoT Devices"
          @add-click="openAddModal"
          @change:page="loadAIoTData"
          @change:perPage="handleIotPerPageChange"
          @change:search="handleIotSearch"
          @detail-click="(item) => loadDetailIoTData(item as any)"
        />
      </div>
      <div class="flex-1">
        <DataBox
          :data="routerData"
          :columns="routerColumns"
          title="Router Devices"
          @add-click="showAddModal(true)"
        />
      </div>
    </div>

    <div class="h-screen bg-gray-100 flex items-center justify-center text-sm text-gray-500">
      Network devices area
    </div>

    <!-- IoT Modal -->
    <transition name="slide-down">
      <NewIoTDevicesModal
        v-if="showModal && !isAddingRouter"
        :show="showModal"
        :mode="isUpdate ? 'update' : 'add'"
        :initialData="selectedDevice"
        @close="showModal = false"
        @submit="handleDeviceCreated"
        @update="handleDeviceUpdated"
        @error="handleModalError"
        @delete="handleDeviceDeleted"
        />
    </transition>

    <!-- Router Modal -->
    <transition name="slide-down">
      <NewRouterModal
        v-if="showModal && isAddingRouter"
        :show="showModal"
        :title="modalTitle"
        @close="showModal = false"
      />
    </transition>

    <!-- Notifications -->
    <div class="fixed top-6 right-6 space-y-3 z-[9999]">
      <Notification
        v-for="n in notifications"
        :key="n.id"
        :title="n.title"
        :message="n.message"
        :type="n.type"
        @dismiss="dismissNotification(n.id)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import AppHeader from '../components/AppHeader.vue'
import DataBox from '../components/DataBox.vue'
import NewIoTDevicesModal from '../components/Modal/IoTDevicesModal.vue'
import NewRouterModal from '../components/Modal/RouterModal.vue'
import Notification from '../components/AppNotification.vue'
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

interface IoTDevice {
  id?: number
  device_name: string
  device_type: string
  serial_number?: string
  ip_address?: string | null
  port?: number | null
  mac_address?: string | null
  model?: string | null
  config?: string | null
  is_active: boolean
  coverage?: string | null
  protocol?: string
  firmware_version?: string | null
  last_seen?: string | null
  battery_level?: number | null
  rssi?: number | null
}

interface RouterDevice {
  mac_address: string
  name: string
  ip_address: string
  status: string
  coverage: number
  deviceId: string
}

const iotDeviceData = ref<IoTDevice[]>([])
const iotDeviceColumns = ref([
  'device_name',
  'serial_number',
  'device_type',
  'coverage',
  'last_seen',
  'battery_level',
  'rssi',
])

const routerData = ref<RouterDevice[]>([])
const routerColumns = ref([
  'mac_address',
  'name',
  'ip_address',
  'port',
  'status',
  'coverage',
  'location',
])

const showModal = ref(false)
const isAddingRouter = ref(false)

const iotPagination = ref({
  page: 1,
  perPage: 5,
  lastPage: 1,
  total: 0,
})
const iotLoading = ref(false)

const modalTitle = computed(() => (isAddingRouter.value ? 'Add Router Device' : 'Add IoT Device'))

const showAddModal = (isRouter: boolean) => {
  isAddingRouter.value = isRouter
  showModal.value = true
}

const openAddModal = () => {
  selectedDevice.value = null
  isUpdate.value = false
  showAddModal(false)
}

const searchTerm = ref('')

// Notifications state
const notifications = ref<
  { id: number; title: string; message: string; type: 'success' | 'error' | 'info' }[]
>([])

function showNotification(
  title: string,
  message: string,
  type: 'success' | 'error' | 'info' = 'info',
) {
  const id = Date.now()
  notifications.value.push({ id, title, message, type })
}

function dismissNotification(id: number) {
  notifications.value = notifications.value.filter((n) => n.id !== id)
}

function handleDeviceCreated(device: IoTDevice) {
  showModal.value = false
  showNotification('Success', `IoT device '${device.device_name}' added successfully.`, 'success')
  loadAIoTData(iotPagination.value.page, iotPagination.value.perPage, searchTerm.value)
}

function handleDeviceUpdated(device: IoTDevice) {
  showModal.value = false
  showNotification('Success', `IoT device '${device.device_name}' updated successfully.`, 'success')
  selectedDevice.value = device
  loadAIoTData(iotPagination.value.page, iotPagination.value.perPage, searchTerm.value)
}

function handleDeviceDeleted() {
  showModal.value = false
  showNotification('Success', 'IoT device deleted successfully.', 'success')
  selectedDevice.value = null
  loadAIoTData(iotPagination.value.page, iotPagination.value.perPage, searchTerm.value)
}

function handleModalError(message: string) {
  showNotification('Error', message, 'error')
}

const loadAIoTData = async (
  page = 1,
  perPage = iotPagination.value.perPage,
  search = searchTerm.value,
) => {
  try {
    const params: Record<string, string | number> = { page, per_page: perPage }
    if (search && search.trim() !== '') params.search = search

    iotLoading.value = true
    const { data: payload } = await axios.get('http://localhost:8000/api/iot-devices', { params })

    const devices = Array.isArray(payload.data) ? payload.data : []
    iotDeviceData.value = devices

    iotPagination.value = {
      page: payload.current_page ?? page,
      perPage: payload.per_page ?? perPage,
      lastPage: payload.last_page ?? 1,
      total: payload.total ?? devices.length,
    }
  } catch (error) {
    console.error('Error loading data:', error)
    showNotification('Error', 'Error loading data', 'error')
  } finally {
    iotLoading.value = false
  }
}

const selectedDevice = ref<IoTDevice | null>(null)
const isUpdate = ref(false)

const loadDetailIoTData = async (device: IoTDevice) => {
  try {
    const { data } = await axios.get(`http://localhost:8000/api/iot-devices`, {
      params: { serial_number: device.serial_number },
    })
    selectedDevice.value = data
    isUpdate.value = true
    showModal.value = true
  } catch (error) {
    console.error('Error loading IoT device details:', error)
    showNotification('Error', 'Error loading IoT device details:', 'error')
  }
}

const handleIotSearch = (value: string) => {
  searchTerm.value = value
  loadAIoTData(1, iotPagination.value.perPage, searchTerm.value)
}

const handleIotPerPageChange = (value: number) => {
  iotPagination.value.perPage = value
  loadAIoTData(1, value, searchTerm.value)
}

onMounted(() => {
  loadAIoTData()
})
</script>
