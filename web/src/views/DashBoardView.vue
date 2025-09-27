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
          @add-click="showAddModal(false)"
          @change:page="loadAIoTData"
          @change:perPage="handleIotPerPageChange"
          @change:search="handleIotSearch"
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

    <transition name="slide-down">
      <NewIoTDevicesModal
        v-if="showModal && !isAddingRouter"
        :show="showModal"
        :title="modalTitle"
        @close="showModal = false"
      />
    </transition>

    <transition name="slide-down">
      <NewRouterModal
        v-if="showModal && isAddingRouter"
        :show="showModal"
        :title="modalTitle"
        @close="showModal = false"
      />
    </transition>
  </div>
</template>

<script setup lang="ts">
import AppHeader from "../components/AppHeader.vue"
import DataBox from "../components/DataBox.vue"
import NewIoTDevicesModal from "../components/Modal/IoTDevicesModal.vue"
import NewRouterModal from "../components/Modal/RouterModal.vue"
import { ref, computed, onMounted } from "vue"
import axios from "axios"

interface IoTDevice {
  name: string
  serial_number: string
  device_name: string
  device_type: string
  coverage: number
  last_seen: string
  battery_level: number
  rssi: number
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
  "name",
  "serial_number",
  "device_type",
  "coverage",
  "last_seen",
  "battery_level",
  "rssi"
])

const routerData = ref<RouterDevice[]>([])
const routerColumns = ref([
  "mac_address",
  "name",
  "ip_address",
  "port",
  "status",
  "coverage",
  "location"
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

const modalTitle = computed(() =>
  isAddingRouter.value ? "Add Router Device" : "Add IoT Device"
)

const showAddModal = (isRouter: boolean) => {
  isAddingRouter.value = isRouter
  showModal.value = true
}

const searchTerm = ref("")

const loadAIoTData = async (
  page = 1,
  perPage = iotPagination.value.perPage,
  search = searchTerm.value,
) => {
  try {
    const params: Record<string, any> = {
      page,
      per_page: perPage,
    }

    if (search && search.trim() !== "") {
      params.search = search
    }
    iotLoading.value = true
    const { data: payload } = await axios.get("http://localhost:8000/api/iot-devices", {
      params
    })

    const devices = Array.isArray(payload.data) ? payload.data : []
    iotDeviceData.value = devices.map((item: IoTDevice) => ({
      name: item.device_name ?? item.name,
      serial_number: item.serial_number,
      device_type: item.device_type,
      coverage: item.coverage,
      last_seen: item.last_seen,
      battery_level: item.battery_level,
      rssi: item.rssi,
    }))

    iotPagination.value = {
      page: payload.current_page ?? page,
      perPage: payload.per_page ?? perPage,
      lastPage: payload.last_page ?? 1,
      total: payload.total ?? devices.length,
    }
  } catch (error) {
    console.error("Error loading data:", error)
  } finally {
    iotLoading.value = false
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
