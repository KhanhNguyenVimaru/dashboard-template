<template>
  <AppHeader />
  <div class="flex flex-col gap-4 p-4">
    <!-- Temperature chart spanning full width above the two DataBox columns -->
    <TemperatureLineChart />

    <div class="flex gap-5">
      <div class="flex-1">
        <DataBox
          :data="iotDeviceData"
          :columns="iotDeviceColumns"
          :pagination="iotPagination"
          :loading="iotLoading"
          title="IoT Devices"
          @add-click="openAddModal"
          @change:page="loadIoTData"
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
          @change:page="loadRouterData"
          @change:perPage="handleRouterPerPageChange"
          @change:search="handleRouterSearch"
          @detail-click="(item) => loadDetailRouterData(item as any)"
          :pagination="routerPagination"
          :loading="routerLoading"
        />
      </div>
    </div>

    <div class="h-screen bg-gray-100 flex items-center justify-center text-sm text-gray-500">
      Network devices area
    </div>

    <!-- IoT Modal -->
    <transition name="slide-down">
      <IoTDevicesModal
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
      <RouterModal
        v-if="showModal && isAddingRouter"
        :show="showModal"
        :mode="isRouterUpdate ? 'update' : 'add'"
        :title="modalTitle"
        :initial-data="selectedRouter"
        @close="closeRouterModal"
        @create="handleRouterCreate"
        @update="handleRouterUpdated"
        @error="handleRouterError"
        @delete="handleRouterDeleted"
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
import IoTDevicesModal from '../components/Modal/IoTDevicesModal.vue'
import RouterModal from '../components/Modal/RouterModal.vue'
import Notification from '../components/AppNotification.vue'
import TemperatureLineChart from '../components/TemperatureLineChart.vue'
import { ref, computed, onMounted } from 'vue'
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

type RouterDevice = RouterRecord

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
const selectedRouter = ref<RouterDevice | null>(null)
const isRouterUpdate = ref(false)
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
const selectedDevice = ref<IoTDevice | null>(null)
const isUpdate = ref(false)

const routerPagination = ref({
  page: 1,
  perPage: 5,
  lastPage: 1,
  total: 0,
})
const routerLoading = ref(false)

const iotPagination = ref({
  page: 1,
  perPage: 5,
  lastPage: 1,
  total: 0,
})
const iotLoading = ref(false)

const showAddModal = (isRouter: boolean) => {
  isAddingRouter.value = isRouter
  if (isRouter) {
    isRouterUpdate.value = false
    selectedRouter.value = null
  }
  showModal.value = true
}

const closeRouterModal = () => {
  showModal.value = false
  isAddingRouter.value = false
  isRouterUpdate.value = false
  selectedRouter.value = null
}

const modalTitle = computed(() => {
  if (isAddingRouter.value) {
    return isRouterUpdate.value ? 'Router Device' : 'Add Router Device'
  }
  return isUpdate.value ? 'IoT Device Data' : 'Add IoT Device'
})

const openAddModal = () => {
  selectedDevice.value = null
  isUpdate.value = false
  showAddModal(false)
}

const searchTerm = ref('')

// Notifications state
type NotificationType = 'success' | 'error' | 'info'
interface NotificationEntry {
  id: number
  title: string
  message: string
  type: NotificationType
}

const notifications = ref<NotificationEntry[]>([])

const pushNotification = (type: NotificationType, message: string, title?: string) => {
  const id = Date.now()
  notifications.value.push({
    id,
    title: title ?? (type === 'error' ? 'Error' : type === 'success' ? 'Success' : 'Info'),
    message,
    type,
  })
}

const notifySuccess = (message: string, title?: string) => pushNotification('success', message, title)
const notifyError = (message: string, title?: string) => pushNotification('error', message, title)

const dismissNotification = (id: number) => {
  notifications.value = notifications.value.filter((n) => n.id !== id)
}
function handleDeviceCreated(device: IoTDevice) {
  showModal.value = false
  notifySuccess(`IoT device '${device.device_name}' added successfully.`)
  loadIoTData(iotPagination.value.page, iotPagination.value.perPage, searchTerm.value)
}

function handleDeviceUpdated(device: IoTDevice) {
  showModal.value = false
  notifySuccess(`IoT device '${device.device_name}' updated successfully.`)
  selectedDevice.value = device
  loadIoTData(iotPagination.value.page, iotPagination.value.perPage, searchTerm.value)
}

function handleDeviceDeleted() {
  showModal.value = false
  notifySuccess('IoT device deleted successfully.')
  selectedDevice.value = null
  loadIoTData(iotPagination.value.page, iotPagination.value.perPage, searchTerm.value)
}

function handleModalError(message: string) {
  notifyError(message)
}

// load toàn bộ dữ liệu cuả IoT devices
const loadIoTData = async ( page = 1, perPage = iotPagination.value.perPage, search = searchTerm.value,
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
    notifyError('Error loading data')
  } finally {
    iotLoading.value = false
  }
}

// hàm load dữ liệu của từng thiết bị
const loadDetailIoTData = async (device: IoTDevice) => {
  try {
    const { data } = await axios.get(`http://localhost:8000/api/iot-devices`, {
      params: { serial_number: device.serial_number },
    })
    selectedDevice.value = data
    isUpdate.value = true
    showModal.value = true
  } catch (error) {
    console.error('Error loading IoT device details.', error)
    notifyError('Error loading IoT device details.')
  }
}

// hàm tìm kiếm và phân trang thiết bị được tìm
const handleIotSearch = (value: string) => {
  searchTerm.value = value
  loadIoTData(1, iotPagination.value.perPage, searchTerm.value)
}

const handleIotPerPageChange = (value: number) => {
  iotPagination.value.perPage = value
  loadIoTData(1, value, searchTerm.value)
}

onMounted(() => {
  loadIoTData()
})
// load dữ liệu của router devices
function handleRouterCreate(router: RouterDevice) {
  closeRouterModal()
  notifySuccess(`Router device '${router.name}' added successfully.`)
  loadRouterData(routerPagination.value.page, routerPagination.value.perPage, searchTerm.value)
}

function handleRouterUpdated(router: RouterDevice) {
  closeRouterModal()
  notifySuccess(`Router device '${router.name}' updated successfully.`)
  loadRouterData(routerPagination.value.page, routerPagination.value.perPage, searchTerm.value)
}

function handleRouterDeleted(mac?: string) {
  closeRouterModal()
  notifySuccess('Router device deleted successfully.')
  selectedRouter.value = null
  if (mac) {
    routerData.value = routerData.value.filter((router) => router.mac_address !== mac)
  }
  loadRouterData(routerPagination.value.page, routerPagination.value.perPage, searchTerm.value)
}
function handleRouterError(message: string) {
  notifyError(message)
}
// hàm load dữ liệu của router devices
const loadRouterData = async( page = 1, perPage = routerPagination.value.perPage, search = searchTerm.value
) => {
  try {
    routerLoading.value = true
    const params: Record<string, string | number> = { page, per_page: perPage }
    if (search && search.trim() !== '') params.search = search

    const { data: payload } = await axios.get('http://localhost:8000/api/routers', { params })

    const devices = Array.isArray(payload.data) ? payload.data : []
    routerData.value = devices

    routerPagination.value = {
      page: payload.current_page ?? page,
      perPage: payload.per_page ?? perPage,
      lastPage: payload.last_page ?? 1,
      total: payload.total ?? devices.length,
    }
  } catch (error) {
    console.error('Error loading router data:', error)
    notifyError('Error loading router data')
  } finally{
    routerLoading.value = false;
  }
}
// hàm load dữ liệu của từng thiết bị
const loadDetailRouterData = async (device: RouterDevice) => {
  try {
    const { data } = await axios.get(`http://localhost:8000/api/routers`, {
      params: { mac_address: device.mac_address },
    })
    selectedRouter.value = data
    isRouterUpdate.value = true
    isAddingRouter.value = true
    showModal.value = true
  } catch (error) {
    console.error('Error loading router device details.', error)
    notifyError('Error loading router device details.')
  }
}
const handleRouterSearch = (value: string) => {
  searchTerm.value = value
  loadRouterData(1, routerPagination.value.perPage, searchTerm.value)
}
const handleRouterPerPageChange = (value: number) => {
  routerPagination.value.perPage = value
  loadRouterData(1, value, searchTerm.value)
}
onMounted(() => {
  loadRouterData()
})
</script>

