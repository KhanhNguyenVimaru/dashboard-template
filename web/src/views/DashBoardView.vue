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
    <NewIoTDevicesModal
      v-if="showModal && !isAddingRouter"
      :show="showModal"
      :mode="isUpdate ? 'update' : 'add'"
      :initialData="selectedDevice"
      @close="showModal = false"
    />

    <!-- Router Modal -->
    <NewRouterModal
      v-if="showModal && isAddingRouter"
      :show="showModal"
      :title="modalTitle"
      @close="showModal = false"
    />
  </div>
</template>

<script setup lang="ts">
import AppHeader from '../components/AppHeader.vue'
import DataBox from '../components/DataBox.vue'
import NewIoTDevicesModal from '../components/Modal/IoTDevicesModal.vue'
import NewRouterModal from '../components/Modal/RouterModal.vue'
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

interface IoTDevice {
  id: number
  device_name: string
  device_type: string
  serial_number: string
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
  } finally {
    iotLoading.value = false
  }
}

const selectedDevice = ref<IoTDevice | null>(null)
const isUpdate = ref(false)

const loadDetailIoTData = async (device: IoTDevice) => {
  try {
    const { data } = await axios.get(`http://localhost:8000/api/iot-devices`, {
      params: { serial_number: device.serial_number }
    })
    selectedDevice.value = data
    isUpdate.value = true
    showModal.value = true
  } catch (error) {
    console.error('Error loading IoT device details:', error)
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

// // CRUD
// const createIoTDevice = async (payload: IoTDevice) => {
//   try {
//     await axios.post('http://localhost:8000/api/iot-devices', payload)
//     showModal.value = false
//     loadAIoTData()
//   } catch (error) {
//     console.error('Error creating IoT device:', error)
//   }
// }

// const updateIoTDevice = async (payload: IoTDevice) => {
//   if (!payload.id) return
//   try {
//     await axios.put(`http://localhost:8000/api/iot-devices/${payload.id}`, payload)
//     showModal.value = false
//     loadAIoTData(iotPagination.value.page)
//   } catch (error) {
//     console.error('Error updating IoT device:', error)
//   }
// }

// const deleteIoTDevice = async (id: number) => {
//   try {
//     await axios.delete(`http://localhost:8000/api/iot-devices/${id}`)
//     showModal.value = false
//     loadAIoTData(iotPagination.value.page)
//   } catch (error) {
//     console.error('Error deleting IoT device:', error)
//   }
// }

onMounted(() => {
  loadAIoTData()
})
</script>
