<template>
  <AppHeader />
  <div class="flex flex-col gap-4 p-4">
    <!-- Tầng 1: danh sách IoT Devices và Router -->
    <div class="flex gap-5 h-[40vh]">
      <div class="flex-1 bg-gray-100">
        <DataBox
          :data="iotDeviceData"
          :columns="iotDeviceColumns"
          title="IoT Devices"
          @add-click="showAddModal(false)"
        />
      </div>
      <div class="flex-1 bg-gray-100">
        <DataBox
          :data="routerData"
          :columns="routerColumns"
          title="Router Devices"
          @add-click="showAddModal(true)"
        />
      </div>
    </div>

    <!-- Tầng 2: kéo thả -->
    <div class="h-screen bg-gray-100 flex items-center justify-center">
      Kéo thả thiết bị mô phỏng
    </div>

    <!-- Modal IoT Device -->
    <transition name="slide-down">
      <NewIoTDevicesModal
        v-if="showModal && !isAddingRouter"
        :show="showModal"
        :title="modalTitle"
        @close="showModal = false"
        @submit="handleAddDevice"
      />
    </transition>

    <!-- Modal Router -->
    <transition name="slide-down">
      <NewRouterModal
        v-if="showModal && isAddingRouter"
        :show="showModal"
        :title="modalTitle"
        @close="showModal = false"
        @submit="handleAddDevice"
      />
    </transition>
  </div>
</template>

<script setup lang="ts">
import AppHeader from "../components/AppHeader.vue"
import DataBox from "../components/DataBox.vue"
import NewIoTDevicesModal from "../components/NewIoTDevicesModal.vue"
import NewRouterModal from "../components/NewRouterModal.vue"
import { ref, computed } from "vue"

// IoT device data (khởi đầu rỗng)
const iotDeviceData = ref<any[]>([])
const iotDeviceColumns = ref([
  "name",
  "status",
  "ip_address",
  "location",
  "deviceId",
])

// Router data (khởi đầu rỗng)
const routerData = ref<any[]>([])
const routerColumns = ref([
  "name",
  "status",
  "ip_address",
  "location",
  "deviceId",
])

// Modal state
const showModal = ref(false)
const isAddingRouter = ref(false)

// Modal title
const modalTitle = computed(() =>
  isAddingRouter.value ? "Add Router Device" : "Add IoT Device"
)

// Show modal
const showAddModal = (isRouter: boolean) => {
  isAddingRouter.value = isRouter
  showModal.value = true
}

// Add new device
const handleAddDevice = (deviceData: any) => {
  if (isAddingRouter.value) {
    routerData.value.push({
      ...deviceData,
      deviceId: `R${routerData.value.length + 1}`,
    })
  } else {
    iotDeviceData.value.push({
      ...deviceData,
      deviceId: `I${iotDeviceData.value.length + 1}`,
    })
  }
  showModal.value = false
}
</script>
