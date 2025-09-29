<template>
  <transition name="slide-in" @after-leave="handleAfterLeave" appear>
    <div
      v-if="show"
      class="fixed top-6 right-6 max-w-sm w-full bg-white/95 backdrop-blur-sm shadow-2xl rounded-2xl pointer-events-auto ring-1 ring-gray-200/50 overflow-hidden border border-gray-100"
    >
      <div class="p-5 flex items-start gap-4">
        <div class="flex-shrink-0 mt-0.5">
          <slot name="icon">
            <div class="w-8 h-8 rounded-full flex items-center justify-center shadow-sm" :class="iconClasses">
              <svg v-if="type === 'success'" class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                />
              </svg>

              <svg v-else-if="type === 'error'" class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 001.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                />
              </svg>

              <svg v-else class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M18 10A8 8 0 112 10a8 8 0 0116 0zM9 9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-4a1.5 1.5 0 100 3 1.5 1.5 0 000-3z"
                />
              </svg>
            </div>
          </slot>
        </div>

        <div class="flex-1 min-w-0">
          <p class="text-xs font-semibold text-gray-900 tracking-wide leading-tight">
            {{ title }}
          </p>
          <p class="mt-1.5 text-xs text-gray-600 leading-relaxed">
            {{ message }}
          </p>
        </div>

        <div class="flex-shrink-0">
          <button
            @click="close"
            class="w-6 h-6 bg-gray-50 hover:bg-gray-100 rounded-full inline-flex items-center justify-center text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-150"
          >
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
              <path
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from "vue"

const props = defineProps({
  title: { type: String, default: "Notification" },
  message: { type: String, required: true },
  duration: { type: Number, default: 4000 },
  type: {
    type: String as () => "success" | "error" | "info",
    default: "success",
  },
})

const emit = defineEmits<{
  (e: "dismiss"): void
}>()

const show = ref(true)
const progress = ref(100)
let progressInterval: ReturnType<typeof setInterval> | null = null

const iconClasses = computed(() => {
  switch (props.type) {
    case "error":
      return "bg-gradient-to-br from-red-400 to-red-500"
    case "info":
      return "bg-gradient-to-br from-blue-400 to-blue-500"
    default:
      return "bg-gradient-to-br from-emerald-400 to-emerald-500"
  }
})

function close() {
  if (!show.value) return
  show.value = false
  if (progressInterval) {
    clearInterval(progressInterval)
    progressInterval = null
  }
}

function handleAfterLeave() {
  emit("dismiss")
}

onMounted(() => {
  if (props.duration > 0) {
    const updateInterval = 50
    const decrement = (100 / props.duration) * updateInterval

    progressInterval = setInterval(() => {
      progress.value -= decrement
      if (progress.value <= 0) {
        progress.value = 0
        close()
      }
    }, updateInterval)
  }
})

onUnmounted(() => {
  if (progressInterval) clearInterval(progressInterval)
})
</script>

<style scoped>
.slide-in-enter-active {
  transition: transform 0.35s ease, opacity 0.35s ease;
}

.slide-in-enter-from {
  transform: translateX(120%);
  opacity: 0;
}

.slide-in-enter-to {
  transform: translateX(0);
  opacity: 1;
}

.slide-in-leave-active {
  transition: none;
}

.slide-in-leave-from,
.slide-in-leave-to {
  opacity: 0;
}
</style>
