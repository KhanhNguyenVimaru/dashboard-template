<template>
  <footer
    class="bg-linear-to-b from-gray-50 to-white border-t border-gray-200 mt-10 px-8 py-8 text-gray-600 flex flex-col gap-8 shadow-inner"
  >
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-8">
      <!-- Brand Section -->
      <div class="space-y-2">
        <p class="text-xl font-semibold text-gray-900 tracking-tight">IoT Dashboard</p>
        <p class="text-sm text-gray-500 max-w-sm">
          A modern platform for real-time monitoring and management of IoT devices.
        </p>
      </div>

      <!-- Links Section -->
      <div class="text-sm space-y-3">
        <p class="font-semibold text-gray-800 uppercase tracking-wide">Connect With Us</p>
        <a
          href="https://github.com/KhanhNguyenVimaru/iot-dashboard-temp"
          target="_blank"
          rel="noopener"
          class="inline-flex items-center gap-2 text-gray-600 hover:text-blue-600 transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4 fill-current">
            <path
              d="M12 2C6.48 2 2 6.58 2 12.25c0 4.5 2.87 8.32 6.84 9.67.5.1.68-.23.68-.5l-.01-1.74c-2.78.62-3.37-1.37-3.37-1.37-.45-1.18-1.1-1.5-1.1-1.5-.9-.64.07-.63.07-.63 1 .07 1.53 1.06 1.53 1.06.89 1.58 2.34 1.13 2.91.86.09-.66.35-1.13.63-1.39-2.22-.26-4.56-1.15-4.56-5.12 0-1.13.39-2.06 1.03-2.79-.1-.26-.45-1.3.1-2.71 0 0 .84-.27 2.75 1.05a9.25 9.25 0 0 1 5 0c1.9-1.32 2.74-1.05 2.74-1.05.56 1.41.21 2.45.1 2.71.64.73 1.02 1.66 1.02 2.79 0 4-2.34 4.85-4.57 5.1.36.32.68.94.68 1.9l-.01 2.81c0 .27.18.6.69.5A10.27 10.27 0 0 0 22 12.25C22 6.58 17.52 2 12 2Z"
            />
          </svg>
          GitHub Repository
        </a>
        <p class="text-gray-500">Address: Hai Phong, Viet Nam</p>
      </div>
    </div>

    <div class="border-t border-gray-200 pt-4 text-center text-xs text-gray-400">
      © {{ currentYear }} IoT Dashboard. All rights reserved.
    </div>
  </footer>
</template>

<script setup lang="ts">
const currentYear = new Date().getFullYear()
</script>
